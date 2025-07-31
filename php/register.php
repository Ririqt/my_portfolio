<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/valid_register.css">
    <title> Register Succeed</title>
</head>
<body>

   <div class="header">
        <div class="header_container"> 
            <h1 class="header_title"> <a href="#index"> My Portfolio </a> </h1>
                <nav class="header_text">
                    <ul>
                    <li> <a href="#about"> About </a> </li>
                    <li> <a href="#skills"> Skills </a> </li>
                    <li> <a href="#projects"> Projects </a> </li>
                    <li> <a href="../html/register.html"> Register </a> <li>
                    </ul>
                </nav>
        </div>
    </div>
    <?php
    require_once "config.php";

    $full_name = $email = $password = $confirm_password = "";
    $full_nameErr = $emailErr = $passwordErr = $confirm_passwordErr = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $full_name = test_input($_POST["full_name"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/",$full_name)) {
            $full_nameErr = "Only letters and white space allowed";
            echo "Name Error: $full_nameErr <br>";
            echo "Inputted Name: $full_name <br>";
            exit;
        } 
        $email = test_input($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format"; 
            echo "Email Error: $emailErr <br>";
            echo "Inputted Email: $email <br>";
        } else {
            $query ="SELECT email FROM users WHERE email='$email' ";
            // echo json_encode($result);
            // echo $query;
            //     exit;
            if ($result=mysqli_query($conn,$query)) {
                
                if(mysqli_num_rows($result)>0){
                    echo "this email exist, try again! <br>";
                    echo "Please click the Register.";
                    exit;
                } else {
                }
            }
        }

        if(!empty($_POST["password"]) && ($_POST["password"] == $_POST["confirm_password"])) {
            $password = test_input($_POST["password"]);
            $confirm_password = test_input($_POST["confirm_password"]);
            if (strlen($_POST["password"]) <= 7 ) {
                $passwordErr = "Your Password Must Contain At Least 8 Characters! <br> ";
                echo '<div class="password_error">', $passwordErr ;
                echo "Inputted Characters: " , strlen($password),'</div>'; 
                exit;
            }
            elseif(!preg_match("#[0-9]+#",$password)) {
                $passwordErr = "Your Password Must Contain At Least 1 Number! <br> ";
                echo $passwordErr;
                exit;
            }
            elseif(!preg_match("#[A-Z]+#",$password)) {
                $passwordErr = "Your Password Must Contain At Least 1 Capital Letter! <br> "; 
                echo $passwordErr;
                exit;
            }
            elseif(!preg_match("#[a-z]+#",$password)) {
                $passwordErr = "Your Password Must Contain At Least 1 Lowercase Letter! <br> ";
                echo $passwordErr;
                exit;
            }
        }
        elseif(!empty($_POST["password"])) {
            $confirm_passwordErr = "Please Check You've Entered Or Confirmed Your Password! <br> ";
            echo $confirm_passwordErr ;
            exit;
        } else {
            $passwordErr = "Please enter password   ";
            echo $confirm_passwordErr ;
            exit;
        } 

        $hashed_password = password_hash($password, PASSWORD_DEFAULT) ;
        
        $sql = "INSERT INTO users (name, email, password) VALUES ('$full_name', '$email', '$hashed_password')";
        
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // $connectDb = new PDO('Mysql:host=127.0.0.1;dbname=my_portfolio','root','');
        // $enteredEmail = 'dada123@gmail.com';
        // $checkExist = $conn->prepare('SELECT email FROM users WHERE email= ?');
        // $checkExist->execute(array($enteredEmail));
        // $existCount = $checkExist->rowCount();

        // if($existCount >= 0) {
        //     echo "this email already exist";
        // }
        // $conn->close();

        // $query ="SELECT * FROM users WHERE email='.$email.' ";
        // if ($result=mysqli_query($conn,$query)) {
        //     if(mysqli_num_rows($result)>0){
        //         echo "this email exist";
        //     } else {
        //         echo "Doesn't exist";
        //     }
        // }

    }

    {   
        echo '<div class="full_name">',"Welcome ", ucfirst($full_name), "!<br> <br>",'</div>';
        echo '<div class="details">',"Here are your details: <br>";
        echo "Name: {$full_name} <br>";
        echo "Email: {$email} <br>";
        // echo "Password: ", password_hash($password, PASSWORD_DEFAULT), "<br>";
        // echo "Confirm Password: ", password_hash($confirm_password, PASSWORD_DEFAULT), "<br>", '</div>';
        // echo "Confirm Password: {$confirm_password} <br>", '</div>';
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    } 
    ?>

    <form action="../html/login.html" method="POST" class="login_form">
        <div class="button_continue"> 
            <button type="submit"> Log In </button>
        </div>
    </form>
</body>
</html>

