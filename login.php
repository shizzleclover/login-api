<?php
require 'config.php';

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];  

        if ($action == 'login') {
            if (isset($_POST['username']) && isset($_POST['password'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];

                 
                $sql = "SELECT password FROM users WHERE username = '$username'";
                $result = mysqli_query($con, $sql);

                if ($result) {
                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        $hashed_password = $row['password'];

                         
                        if (password_verify($password, $hashed_password)) {
                            $response['message'] = "Login Successful";
                        } else {
                            $response['message'] = "Invalid Username or Password";
                        }
                    } else {
                        $response['message'] = "Invalid Username or Password";
                    }
                } else {
                    $response['message'] = "Error: " . mysqli_error($con);
                }
            } else {
                $response['message'] = "Username and Password required";
            }
        } elseif ($action == 'forgot') {
            if (isset($_POST['username'])) {
                $username = $_POST['username'];

                // Generate a random password
                $new_password = generateRandomPassword();

                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                 
                $sql = "UPDATE users SET password = '$hashed_password' WHERE username = '$username'";
                if (mysqli_query($con, $sql)) {
                    
                    $response['message'] = "New password has been sent to your email";
                } else {
                    $response['message'] = "Error resetting password: " . mysqli_error($con);
                }
            } else {
                $response['message'] = "Username required";
            }
        } else {
            $response['message'] = "Invalid action";
        }
    } else {
        $response['message'] = "Log in succesful";
    }
 
    header("Content-Type: application/json");

    
    echo json_encode($response, JSON_PRETTY_PRINT);
} else {
    $response['message'] = "Invalid request method";
    header("Content-Type: application/json");
    echo json_encode($response, JSON_PRETTY_PRINT);
}
 
function generateRandomPassword($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $characters_length = strlen($characters);
    $random_password = '';
    for ($i = 0; $i < $length; $i++) {
        $random_password .= $characters[rand(0, $characters_length - 1)];
    }
    return $random_password;
}
?>
