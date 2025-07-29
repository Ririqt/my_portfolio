<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <?php
    session_start();

    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        header("location: index.php");
        exit;
    }

    require_once "config.php";

    $email = $password = "";
    $emailErr = $passwordErr = $loginErr = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(empty(trim($_POST["email"]))) {
            $emailErr = "Please enter Email.";
        } else {
            $email = trim($_POST["email"]);
        }
        

        if(empty($emailErr) && empty($passwordErr)) {
            $sql = "SELECT id, email, password FROM users WHERE email = ?";

            if($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $param_email);
                $param_email = $email;
                
                if(mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    if(mysqli_stmt_num_rows($stmt) == 1) {
                        mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password);
                        if(mysqli_stmt_fetch($stmt)) {
                            if(password_verify($password, $hashed_password)) {
                                session_start();

                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["email"] = $email;
                                echo $password;
                                echo "7890";
                                header("location: index.html");
                            } else {
                                $loginErr = "Invalid username or password.";
                                echo $loginErr;
                            }
                        }
                    } else {
                        $loginErr = "Invalid username or password.";
                    }
                } else {
                    echo "Something went wrong.";
                }
                echo $password;
            }
            mysqli_stmt_close($stmt);
            // header("location: ../html/index.html");
            echo "Goods";
            echo $password;
        }
        mysqli_close($conn);
    }
    ?>
</body>
</html>