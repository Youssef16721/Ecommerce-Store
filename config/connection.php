<?php

$conn = mysqli_connect('localhost','root','12345','ecommerce_store');

if(!$conn)
    die( "Connection failed" . mysqli_connect_error());

?>