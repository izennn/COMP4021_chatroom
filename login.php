<?php

// if name is not in the post data, exit
if (!isset($_POST["name"])) {
    header("Location: error.html");
    exit;
}

// furthermore, we must also check if there is a picture attached
if (!isset($_POST['picture'])) {
    var_dump($_FILES);
    $err_msg = "No picture found under FILES";
    var_dump($err_msg);
    header("Location: error.html");
    exit;
}

require_once('xmlHandler.php');

// create the chatroom xml file handler
$xmlh = new xmlHandler("chatroom.xml");
if (!$xmlh->fileExist()) {
    // if file (chatroom.xml) doesn't exist, redirect to error.html
    header("Location: error.html");
    exit;
}

// open the existing XML file
$xmlh->openFile();

// get the 'users' element
$users_element = $xmlh->getElement("users");

// create a 'user' element
// ***NOTE*** I want to add the functionality of checking whether username exists already
// before adding it into the users element
$users_array = $xmlh->getChildNodes("users");
// var_dump($users_element);

// open a window to print the results of users
$user_element = $xmlh->addElement($users_element, "user");

// add the user name & picture location
$xmlh->setAttribute($user_element, "name", $_POST["name"]);
$img_url = 'profile_pictures/' . $_FILES['picutre'];
if (!move_uploaded_file($_FILES['picuitre']['tmp_name'], $image_url)) {
    header("Location: error.html");
}
$xmlh->setAttribute($user_element, "picture", $img_url);

// save the XML file
$xmlh->saveFile();

// set the name to the cookie
setcookie("name", $_POST["name"]);

// Cookie done, redirect to client.php (to avoid reloading of page from the client)
header("Location: client.php");

?>
