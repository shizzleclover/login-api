<?php 
    $con = mysqli_connect("localhost", "root", "", "user_auth");
    
    if(!$con){
            die("Connection failed:".mysqli_connect_error());
    }
?>