<?php
require 'config.php';

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action']; // action can be 'login' or 'forgot'

    if ($action == 'login') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // SQL query to select the user's hashed password
        $sql = "SELECT password FROM users WHERE username = '$username'";
        $result = mysqli_query($con, $sql);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $hashed_password = $row['password'];

                // Verify the password
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
    } elseif ($action == 'forgot') {
        $username = $_POST['username'];

        // Generate a random password
        $new_password = generateRandomPassword();

        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the user's password in the database
        $sql = "UPDATE users SET password = '$hashed_password' WHERE username = '$username'";
        if (mysqli_query($con, $sql)) {
            // Send the new password to the user (you may implement this part)
            $response['message'] = "New password has been sent to your email";
        } else {
            $response['message'] = "Error resetting password: " . mysqli_error($con);
        }
    } else {
        $response['message'] = "Invalid action";
    }

    // Set the content type of the response to JSON
    header("Content-Type: application/json");

    // Encode the response array into JSON format and print it
    echo json_encode($response, JSON_PRETTY_PRINT);
}

// Function to generate a random password
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
