<?
require("../config.php");

function isAllowedUser($fb_id){
    //might be implemented with db also
    if (in_array($fb_id,$allowed_users)) {
        return true;
    } else {
        return true;
    }
}

function getMessengerToken($fb_id){
    global $messenger_token;
    //might be implemented in db also
    return $messenger_token;
}

function getMessengerURL(){
    global $messenger_token;
    //possibility for access token update might be put here
    return 'https://graph.facebook.com/v2.6/me/messages?access_token='.$messenger_token;
}

function generateJsonForMessenger($fb_id,$message){
    return '{
        "messaging_type": "RESPONSE",
        "recipient":{
            "id":"' . $fb_id . '"
        }, 
        "message":{
            "text":"' . $message . '"
        }
    }';
}

function sendMessengerMessage($fb_id,$message_to_send,$empty_message){
    if(!$empty_message){
        $api_url = getMessengerURL();

        $ch = curl_init($api_url);

        $request_body = generateJsonForMessenger($fb_id,$message_to_send);

        #curl_setopt($ch, CURLOPT_VERBOSE, true);
        #$fp = fopen(dirname(__FILE__).'/errorlog.txt', 'w');
        #curl_setopt($ch, CURLOPT_STDERR, $fp);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $result = curl_exec($ch);
        
        return true;
    } else {
        return false;
    }
}

function getImage($url){
    $ch = curl_init ($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $raw=curl_exec($ch);
    curl_close($ch);
    return $raw;
}

function getTagsFromGoogleVision($raw_image){
    global $google_vision_key;

    $api_url = "https://vision.googleapis.com/v1/images:annotate?key=".$google_vision_key;
    $request_body = '
        {
            "requests": [{
                "features": [{"type": "LABEL_DETECTION"}],
                "image":  {"content": "'.base64_encode($raw_image).'"}
            }]
        }';

    $ch = curl_init($api_url);
        
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request_body);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);

    $response = curl_exec($ch);

    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $response_body = substr($response, $header_size);

    $parsed_body = json_decode($response_body);
    $restructured_response = [];

    foreach ($parsed_body['responses'][0]['labelAnnotations'] as $key => $tag){
        $restructured_response[$key]['tag'] = $tag["description"];
        $restructured_response[$key]['score'] = round($tag["score"], 4);
    }

    $file = 'response.txt';
    file_put_contents($file, $response_body);
    
    return $restructured_response;
}
?>