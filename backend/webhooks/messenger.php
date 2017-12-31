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

if(isAllowedUser($sender)){
    $message_to_send = " Processing... ";
    sendMessengerMessage($sender,$message_to_send,$empty_message);
} else {
    $message_to_send = "This bot is for private use. If you know developer personally, contact him to gain access. Your ID for this page is ".$sender;
    sendMessengerMessage($sender,$message_to_send,$empty_message);
}

?>