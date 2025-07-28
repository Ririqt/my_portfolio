<?php
$name = $email = $subject = $message = "";
$nameErr = $emailErr = $subjectErr = $messageErr ="";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = test_input($_POST["name"]);
    if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
        $nameErr = "Only letters and white space allowed";
        echo "Name Error: $nameErr <br>";
        echo "Inputted Name: $name <br> <br>";
    } 
    $email = test_input($_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format"; 
      echo "Email Error: $emailErr <br>";
      echo "Inputted Email: $email <br>";
    }
    $subject = test_input($_POST['subject']);
    $message = test_input($_POST['message']);
} 

{   
    echo "Welcome '{$name}' <br>";
    echo "Here are your details: <br>";
    echo "Name: {$name} <br>";
    echo "Email: {$email} <br>";
    echo "Subject: {$subject} <br>";
    echo "Message: {$message} <br>";
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}   
?>