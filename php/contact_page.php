<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Contact Page </title>
    <link rel="stylesheet" href="../css/contact_page.css">
</head>
<body>
    <?php
    // session_start();
    require_once "config.php";

    $name = $email = $subject = $message = "";
    $nameErr = $emailErr = $subjectErr = $messageErr ="";
    // $user_id = $_SESSION['id'];
    // echo $user_id; exit;
    if ($_SERVER["REQUEST_METHOD"] == "POST" ) {

        $name = test_input($_POST["name"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
            $nameErr = "Only letters and white space allowed";
            echo "Name Error: $nameErr <br>";
            echo "Inputted Name: $name <br> <br>";
            exit;
        } 
        $email = test_input($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format"; 
        echo "Email Error: $emailErr <br>";
        echo "Inputted Email: $email <br>";
        exit;
        }
        $subject = test_input($_POST['subject']);
        if (preg_match("/^[0-9]/",$subject)) {
            $subjectErr = "The subject cannot start of number";
            echo "Subject Error: $subjectErr ";
            exit;
        }
        $message = test_input($_POST['message']);

        $sql = "INSERT INTO contacts (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";

        if ($conn->query($sql) === TRUE) {
            // $_SESSION['success_message'] = "Successfully Created a Message!";
            // // $action = "Created a Mess";
            // // $sql = "INSERT INTO logs (user_id, action) VALUES ('$user_id', '$action')";
            // header("Location: ../html/index.html#contact");  
            // exit;      
        }
    } 

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }   
    ?>

    <div class="header">
        <div class="header_container"> 
            <div class="nav_bar">
                <h1 class="header_title"> <a href="/my_portfolio/php/dashboard.php"> My Portfolio </a> </h1>
                <nav class="header_text">
                    <ul>
                        <li> <a href="#about"> About </a> </li>
                        <li id="skills"> <a href="#skills"> Skills </a> </li>
                        <li id="projects"> <a href="#projects"> Projects </a> </li>
                        <li> <a href="/my_portfolio/html/register.html"> Register </a> </li>
                        <li> <a href="/my_portfolio/html/login.html"> Log In </a> </li>
                    </ul>
                
                </nav> 
                <!-- <div class="user"> User:  // echo $_SESSION['name'];  </div>  -->
            </div>
        </div>
    </div>

    <?php
        echo "Welcome '{$name}' <br>";
        echo "Here are your message details: <br>";
        echo "Name: {$name} <br>";
        echo "Email: {$email} <br>";
        echo "Subject: {$subject} <br>";
        echo "Message: {$message} <br>";
    ?> 
</body>
</html>


