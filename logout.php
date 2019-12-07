<?php 
require_once('xmlHandler.php');

if (!isset($_COOKIE["name"])) {
    header("Location: error.html");
    exit;
}
// create the chatroom xml file handler
$xmlh = new xmlHandler("chatroom.xml");
if (!$xmlh->fileExist()) {
    header("Location: error.html");
    exit;
}

$name = $_COOKIE["name"];

$xmlh->openFile();
// get the users element
$users_node = $xmlh->getElement("users");
// get all user nodes
$users_array = $xmlh->getChildNodes("user");
if($users_array != null) {
    // delete the current user from the users element
    foreach ($users_array as $user) {
        $username = $xmlh->getAttribute($user, "name");
        if ($username == $name){
            $xmlh->removeElement($users_node, $user);
        }
    }
}
        
$xmlh->saveFile();

unset($_COOKIE["name"]);
// setcookie('name', null, time() - 3600);
header('Location: ./login.html');
?>