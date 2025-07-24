<?php
$name = $email = $subject = $message = "";
$nameErr = $emailErr = $subjectErr = $messageErr ="";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $name = test_input($_POST["name"]);
    $email = test_input($_POST['email']);
    if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
        $nameErr = "Only letters and white space allowed";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format";
    }
    $subject = test_input($_POST['subject']);
    $message = test_input($_POST['message']);
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}   

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($nameErr)) {
        echo "Name Error: $nameErr <br>";
    } 
    if (!empty($_POST["email"])) {
        echo "Email Error: $emailErr <br>";
        echo "Email Input: {$email} <br>";
    } else {
        echo "Welcome {$name} <br>";
        echo "Here are your details: <br>";
        echo "Name: {$name} <br>";
        echo "Email: {$email} <br>";
        echo "Subject: {$subject} <br>";
        echo "Message: {$message} <br>";
    }
}
?>