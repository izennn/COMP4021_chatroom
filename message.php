<?php

// get the name from cookie
$name = "";
if (isset($_COOKIE["name"])) {
    $name = $_COOKIE["name"];
}

print "<?xml version=\"1.0\" encoding=\"utf-8\"?>";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Message Page</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
        <script language="javascript" type="text/javascript">
        //<![CDATA[
        var loadTimer = null;
        var request;
        var datasize;
        var prevMessageLen;

        function load() {
            console.log("load");
            var username = document.getElementById("username");
            if (username.value == "") {
                loadTimer = setTimeout("load()", 100);
                return;
            }

            loadTimer = null;
            datasize = 0;
            prevMessageLen = 0;
            
            var node = document.getElementById("chatroom");
            node.style.setProperty("visibility", "visible", null);

            getUpdate();
            // setTimeout('getUpdate()', 200);
        }

        function unload() {
            var username = document.getElementById("username");
            if (username.value != "") {
                //request = new ActiveXObject("Microsoft.XMLHTTP");
                request = new XMLHttpRequest();
                request.open("POST", "logout.php", true);
                request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                request.send(null);
                username.value = "";
            }
            if (loadTimer != null) {
                loadTimer = null;
                clearTimeout("load()", 100);
            }
        }

        function getUpdate() {
            console.log("get update");
            //request = new ActiveXObject("Microsoft.XMLHTTP");
            request = new XMLHttpRequest();
            request.onreadystatechange = stateChange;
            request.open("POST", "server.php", true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send("datasize=" + datasize);
        }

        function stateChange() {
            console.log("state change");
            if (request.readyState == 4 && request.status == 200 && request.responseText) {
                var xmlDoc;
                var parser = new DOMParser();
                xmlDoc = parser.parseFromString(request.responseText, "text/xml");
                datasize = request.responseText.length;
                updateChat(xmlDoc);
                getUpdate();
            }
        }

        function updateChat(xmlDoc) {
            console.log("update chat");
            //point to the message nodes
            var messages = xmlDoc.getElementsByTagName("message");

            // create a string for the messages
            for (var i = prevMessageLen; i < messages.length; ++i) {
                var username = messages[i].getAttribute('name');
                var color = messages[i].getAttribute('color');
                showMessage(username, messages[i].textContent, color);
            }
            prevMessageLen = messages.len;
        }

        function showMessage(nameStr, contentStr, color) {
            console.log("show message");
            var node = document.getElementById("chattext");
            // Create the name text span
            var nameNode = document.createElementNS("http://www.w3.org/2000/svg", "tspan");

            // Set the attributes and create the text
            nameNode.setAttribute("x", 20);
            nameNode.setAttribute("dy", 20);
            nameNode.setAttribute("style", "fill:" + color);
            nameNode.appendChild(document.createTextNode(nameStr));

            // Add the name to the text node
            node.appendChild(nameNode);

            // Create the score text span
            var contentNode = document.createElementNS("http://www.w3.org/2000/svg", "tspan");

            // Set the attributes and create the text
            contentNode.setAttribute("x", 100);
            contentNode.setAttribute("style", "fill:" + color);

            // here, we need to add automatic hyperlink to the message if needed
            
            var startPos = contentStr.indexOf("http://");
            if (startPos >= 0) {
                var linkLength= 0;
                var url = "";
                while (contentStr[startPos + linkLength] !== ' ')
                    linkLength++;
                
                // create link element
                url = contentStr.substr(startPos, linkLength);
                var link = document.createElementNS("http://www.w3.org/2000/svg", "a");
                link.setAttributeNS('http://www.w3.org/1999/xlink', 'xlink:href', url);
                link.setAttributeNS('http://www.w3.org/1999/xlink', 'xlink:show', "new");
                link.setAttribute("text-decoration", "underline");
                link.setAttribute("style", "fill:"+color);
                link.appendChild(document.createTextNode(linkStr));

                // piece together the message string
                var firstPart = contentStr.substr(0, startPos);
                var lastPart = contentStr.substr(startPos + linkLength); 

                contentNode.appendChild(document.createTextNode(firstPart));
                contentNode.appendChild(link);
                contentNode.appendChild(document.createTextNode(firstPart));
            } else {
                contentNode.appendChild(document.createTextNode(contentStr));                    
            }

            // Add the name to the text node
            node.appendChild(contentNode);
        }

        //]]>
        </script>
    </head>

    <body style="text-align: left" onload="load()" onunload="unload()">
        <svg width="800px" height="2000px"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xhtml="http://www.w3.org/1999/xhtml"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        xmlns:a="http://www.adobe.com/svg10-extensions" a:timeline="independent"
        >

            <g id="chatroom" style="visibility:hidden; border: 1px solid red">                
                <rect width="520" height="2000" style="fill:white;stroke:red;stroke-width:2"/>
                <text x="260" y="40" style="fill:red;font-size:30px;font-weight:bold;text-anchor:middle">Chat Window</text> 
                <text id="chattext" y="45" style="font-size: 20px;font-weight:bold"/>
            </g>
        </svg>
    
        <form action="">
            <input type="hidden" value="<?php print $name; ?>" id="username" />
        </form>
    </body>
</html>
