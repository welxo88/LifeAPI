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
    //might be implemented in db also
    return $messenger_tokens[$fb_id];
}

function getMessengerURL(){
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
        $url = 'https://graph.facebook.com/v2.6/me/messages?access_token='.$messenger_token;

        $ch = curl_init($url);

        $jsonData = '{
            "messaging_type": "RESPONSE",
            "recipient":{
                "id":"' . $fb_id . '"
            }, 
            "message":{
                "text":"' . $message_to_send . '"
            }
        }';

        curl_setopt($ch, CURLOPT_VERBOSE, true);
        $fp = fopen(dirname(__FILE__).'/errorlog.txt', 'w');
        curl_setopt($ch, CURLOPT_STDERR, $fp);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $result = curl_exec($ch);

        return true;
    } else {
        return false;
    }
}

?>