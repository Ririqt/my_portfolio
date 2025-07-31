<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Log In </title>
</head>
<body>
    
    <?php
    session_start(); 
    // session_destroy();
    // if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    //     echo "Goods";
    //     exit;
    // }

    require_once "config.php";

    $email = $password = "";
    $emailErr = $passwordErr = $loginErr = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if(empty(trim($_POST["email"]))) {
            $emailErr = "Please enter Email.";
        } else {
            $email = trim($_POST["email"]);
        }
        
        if(empty(trim($_POST["password"]))) {
            $passwordErr = "Please enter your password.";
        } else {
            $password = trim($_POST["password"]);
        }

        if(empty($emailErr) && empty($passwordErr)) {
            $sql = "SELECT id, name, email, password FROM users WHERE email='$email'";

            if ($result=mysqli_query($conn,$sql)) {
                if(mysqli_num_rows($result)>0) {
                    $selected_data = mysqli_fetch_assoc($result);
                    // echo $selected_data["email"];
                    // exit;
                    if(password_verify($password, $selected_data["password"])) {
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $selected_data["id"];
                        $_SESSION["email"] = $selected_data["email"];
                        $_SESSION["name"] = $selected_data["name"];
                        echo "Login Success";
                        header("location: ../php/dashboard.php");
                        exit;
                    } else {
                        echo "Invalid username or password.";
                        exit;
                    }
                  
                } else {
                    echo "You are not registered";
                }
        mysqli_close($conn);
        }
    }
    }
    
    ?>

    <form action="../html/register.html" method="POST" class="login_form">
        <div class="button_continue"> 
            <button type="submit"> Register </button>
        </div>
    </form>
</body>
</html>