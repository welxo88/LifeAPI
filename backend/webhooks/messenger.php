<?php
require("../config.php");
require("../helper_functions.php");

######################### FOR HOOK VERIFICATION REASONS ONLY #########################
$challenge = $_REQUEST['hub_challenge'];
$own_token = $_REQUEST['hub_verify_token'];

// Set this Verify Token Value on your Facebook App 
if ($own_token === $verify_token) {
  echo $challenge;
}
######################################################################################

//GET INPUT DATA
$input = json_decode(file_get_contents('php://input'), true);

$empty_message = empty($input['entry'][0]['messaging'][0]['message']);
$sender = $input['entry'][0]['messaging'][0]['sender']['id'];
$message = $input['entry'][0]['messaging'][0]['message']['text'];

$attachment = $input['entry'][0]['messaging'][0]['message']['attachments'];
if($attachment[0]["type"]==="image"){
    $image_url = $attachment[0]["payload"]["url"];
}

if(isAllowedUser($sender)){
    sendMessengerMessage($sender,"Hi! ",$empty_message);
    if(isset($image_url)) {
        $image = getImage($image_url);
        $timestamp = date_format(date_create(), 'Ymd-His');
        $extension = strtok(pathinfo($image_url, PATHINFO_EXTENSION), '?');
        
        $image_filename = $sender.'-'.$timestamp.'.'.$extension;
        //$fp = fopen('../images/'.$image_filename,'x');
        //fwrite($fp, $image);
        //fclose($fp);
        
        //sendMessengerMessage($sender,"image found and saved... ",false);

        sendMessengerMessage($sender,"on break now",false);
        
        //$vision_response = getTagsFromGoogleVision($image);

        //$file = 'response.txt';
        //file_put_contents($file, $vision_response);

        //sendMessengerMessage($sender,"google response: ".substr(addslashes(json_encode($vision_response)),20),false);
    }
} else {
    $message_to_send = "This bot is for private use. If you know developer personally, contact him to gain access. Your ID for this page is ".$sender;
    sendMessengerMessage($sender,$message_to_send,false);
}

?>