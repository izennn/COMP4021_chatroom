<?php

if (!isset($_COOKIE["name"])) {
    header("Location: error.html");
    return;
}

// get the name from cookie
$name = $_COOKIE["name"];

print "<?xml version=\"1.0\" encoding=\"utf-8\"?>";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Add Message Page</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
        <style>
            .div-color {
                position: absolute;
                width: 50px;
                height: 50px;
            }
        </style>
        <script type="text/javascript">
        //<![CDATA[
        function load() {
            var name = "<?php print $name; ?>";

            //delete this line 
            //window.parent.frames["message"].document.getElementById("username").setAttribute("value", name)

            setTimeout("document.getElementById('msg').focus()",100);
        }

        function select(color) {
            if (confirm('Are you sure to change your message color to ' + color + ' ?')) {
                document.getElementById('color').value = color;
            }
        }

        function showUsers() {
            console.log("Open a window to display users!");
            setTimeout(() => {
                window.open("file://Users/izenhuang/Desktop/fall_19_20/COMP4021/labs/L07/chatroom/show_users.php", "Online Users");
            }, 2000);
        }
        //]]>
        </script>
    </head>

    <body style="text-align: left" onload="load()">
        <form action="add_message.php" method="post">
            <table border="0" cellspacing="5" cellpadding="0">
                <tr>
                    <td>What is your message?</td>
                </tr>
                <tr>
                    <td><input class="text" type="text" name="message" id="msg" style= "width: 780px" /></td>
                </tr>
                <tr >
                    <td>
                        <input class="button" type="submit" value="Send Your Message" style="width: 200px" />

                        <h4>Choose your color: </h4>
                        <div style="position:relative; min-height: 50px">
                            <div class="div-color" style="background-color:red;left:0px" onclick="select('red')"></div>
                            <div class="div-color" style="background-color:yellow;left:50px" onclick="select('yellow')"></div>
                            <div class="div-color" style="background-color:green;left:100px" onclick="select('green')"></div>
                            <div class="div-color" style="background-color:cyan;left:150px" onclick="select('cyan')"></div>
                            <div class="div-color" style="background-color:blue;left:200px" onclick="select('blue')"></div>
                            <div class="div-color" style="background-color:magenta;left:250px" onclick="select('magenta')"></div>
                        </div>
                        <input type="hidden" name="color" id="color" value="black" />
                    </td>
                </tr>
            </table>
        </form>
        
        <!--logout button-->
        <br />
        <form action="logout.php" method="POST">
            <input class="button" type="submit" id="showOnlineUsers" value="Show Online User List" onclick="showUsers()" style="text-align: center" />
            <input class="button" type="submit" id="logoutButton" value="Logout" style="text-align: center" />
        </form>

    </body>
</html>
