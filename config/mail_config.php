<?php

// Define Gmail's smtp server
define('MAIL_HOST', "smtp.gmail.com");


// Define as a username the email i use
define('USERNAME', "info.zoomia242@gmail.com");

//Define you 16 digit Gmail .app-password
define('PASSWORD', "bzjv kdqh qpjs shwj");

//Define the email address from which the email is sent.
define('SEND_FROM', "info.zoomia242@gmail.com");

// Define the name of the sender
define('SEND_FROM_NAME', "zoomia");

// Define the REPLY_TO address
define('REPLY_TO', "info.zoomia242@gmail.com");


// Define the REPLY_TO name
if(isset($_SESSION['admin_name']))
    define('REPLY_TO_NAME', $_SESSION['admin_name']);
else
    define('REPLY_TO_NAME', "YoussefGB");


?>