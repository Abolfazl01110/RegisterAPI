<?php

@include 'config.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json'); // Set the response content type to JSON

$result = array(); // Initialize the result array

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['name'], $data['lastname'], $data['email'], $data['phoneNumber'], $data['password'])) {
        $name = mysqli_real_escape_string($conn, $data['name']);
        $lastname = mysqli_real_escape_string($conn, $data['lastname']);
        $email = mysqli_real_escape_string($conn, $data['email']);
        $phoneNumber = mysqli_real_escape_string($conn, $data['phoneNumber']);
        $password = md5($data['password']);

        $select = "SELECT * FROM user_form WHERE email = '$email' ";

        $query_result = mysqli_query($conn, $select);

        if (mysqli_num_rows($query_result) > 0) {
            $result['success'] = false;
            $result['message'] = 'User already exists';
        } else {
            $insert = "INSERT INTO user_form(name, lastname, email, phoneNumber, password, user_type) VALUES('$name','$lastname','$email','$phoneNumber','$password','user')";
            mysqli_query($conn, $insert);

            $result['success'] = true;
            $result['message'] = 'Registration successful';
        }
    } else {
        $result['success'] = false;
        $result['message'] = 'Please provide name, lastname, email, phoneNumber, and password';
    }
} else {
    $result['success'] = false;
    $result['message'] = 'Invalid request method';
}

echo json_encode($result);
?>
