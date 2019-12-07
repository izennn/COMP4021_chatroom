<?php

if (!isset($_COOKIE["name"])) {
    header("Location: error.html");
    return;
}

// get the name from cookie
$name = $_COOKIE["name"];

// get the message content
$message = $_POST["message"];
if (trim($message) == "") $message = "__EMPTY__";

// also get color from POST request
$color = $_POST["color"];

require_once('xmlHandler.php');

// create the chatroom xml file handler
$xmlh = new xmlHandler("chatroom.xml");
if (!$xmlh->fileExist()) {
    header("Location: error.html");
    exit;
}

// create the following DOM tree structure for a message
// and add it to the chatroom XML file
//
// <message name="...">...</message>
//


// 1. open XML file
$xmlh->openFile();

// 2. append new message to the 'messages' group in chatroom.xml
// 2.1. get messages element
$messages_element = $xmlh->getElement("messages");
// 2.2. create message (single) element for the recieved message
$message_element = $xmlh->addElement($messages_element, "message");

// 3. Add the name and actual text to the newly created message element
// 3.1. Add "name" attribute to the newly created message element
$xmlh->setAttribute($message_element, "name", $name);
$xmlh->setAttribute($message_element, "color", $color);
// 3.2. Add the text to the newly created message element
$xmlh->addText($message_element, $message);

// 4. Save the file
$xmlh->saveFile();

header("Location: client.php");

?>
