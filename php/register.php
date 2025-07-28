<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/register_valid.css">
    <title> Register Succeed</title>
</head>
<body>

    <div class="header">
    <h1 class="header_title"> <a href="/html/index.html" target="_blank"> My Portfolio </a> </h1>
        <nav class="header_text">
            <ul>
              <li> <a href="/html/index.html#about" target="_blank"> About </a> </li>
              <li> <a href="/html/index.html#skills" target="_blank"> Skills </a> </li>
              <li> <a href="/html/index.html#projects" target="_blank"> Projects </a> </li>
              <li> <a href="/html/login.html" target="_blank"> Log In </a> </li>
            </ul>
        </nav>
    </div>

    <?php
    $full_name = $email = $password = $confirm_password = "";
    $full_nameErr = $emailErr = $passwordErr = $confirm_passwordErr = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $full_name = test_input($_POST["full_name"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/",$full_name)) {
            $nameErr = "Only letters and white space allowed";
            echo "Name Error: $nameErr <br>";
            echo "Inputted Name: $name <br>";
        } 
        $email = test_input($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format"; 
        echo "Email Error: $emailErr <br>";
        echo "Inputted Email: $email <br>";
        }

        if(!empty($_POST["password"]) && ($_POST["password"] == $_POST["confirm_password"])) {
            $password = test_input($_POST["password"]);
            $confirm_password = test_input($_POST["confirm_password"]);
            if (strlen($_POST["password"]) < 8 ) {
                $passwordErr = "Your Password Must Contain At Least 8 Characters! <br> ";
                echo '<div class="password_error">', $passwordErr ;
                echo "Inputted Characters: " , strlen($password),'</div>'; 
            }
            elseif(!preg_match("#[0-9]+#",$password)) {
                $passwordErr = "Your Password Must Contain At Least 1 Number! <br> ";
                echo $passwordErr;
            }
            elseif(!preg_match("#[A-Z]+#",$password)) {
                $passwordErr = "Your Password Must Contain At Least 1 Capital Letter! <br> "; 
                echo $passwordErr;
            }
            elseif(!preg_match("#[a-z]+#",$password)) {
                $passwordErr = "Your Password Must Contain At Least 1 Lowercase Letter! <br> ";
                echo $passwordErr;
            }
        }
        elseif(!empty($_POST["password"])) {
            $confirm_passwordErr = "Please Check You've Entered Or Confirmed Your Password! <br> ";
            echo $confirm_passwordErr ;
        } else {
            $passwordErr = "Please enter password   ";
            echo $confirm_passwordErr ;
        }
    } 

    {   
        echo '<div class="full_name">',"Welcome ", ucfirst($full_name), "!<br> <br>",'</div>';
        echo '<div class="details">',"Here are your details: <br>";
        echo "Name: {$full_name} <br>";
        echo "Email: {$email} <br>";
        echo "Password: {$password} <br>";
        echo "Confirm Password: {$confirm_password} <br>", '</div>';
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    } 
    ?>
</body>
</html>

