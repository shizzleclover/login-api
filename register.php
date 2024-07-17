<?php
        require 'config.php';
        $response = array();

        if($con){
            echo 'success';
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'];
            $password = $_POST['password'];

            //hasinh
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        //query to insert user data

        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
        if (mysqli_query($con, $sql)) {
            $response ['message'] = "User Registered successfully";

        }
        else{
            $response ['message'] = "User Registration Failed";
        }
        echo json_encode($response, JSON_PRETTY_PRINT);
        }
        mysqli_close($con);
?>