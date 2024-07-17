<?php
require 'config.php';

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'forgot') {
        if (isset($_POST['username']) && isset($_POST['new_password'])) {
            $username = $_POST['username'];
            $new_password = $_POST['new_password'];
 
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            
            $sql = "UPDATE users SET password = '$hashed_password' WHERE username = '$username'";
            if (mysqli_query($con, $sql)) {
          
                $response['message'] = "Password reset successful. Your new password is: $new_password";
            } else {
                $response['message'] = "Error resetting password: " . mysqli_error($con);
            }
        } else {
            $response['message'] = "Username and new password required";
        }
    } else {
        $response['message'] = "Invalid action";
    }

   
    header("Content-Type: application/json");

 
    echo json_encode($response, JSON_PRETTY_PRINT);
} else {
    $response['message'] = "Invalid request method";
    header("Content-Type: application/json");
    echo json_encode($response, JSON_PRETTY_PRINT);
}
?>
