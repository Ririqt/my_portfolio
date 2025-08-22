<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Log In </title>
</head>
<body>
    <?php if (isset($_SESSION['register_message'])) {
                echo '<div class="register_message">'
                    . $_SESSION['register_message'] . '</div>';
            // Clear the session variable
                unset($_SESSION['register_message']);
    }?>
    <?php
    session_start(); // indicates for a session to start

    require_once "config.php"; //access the connection to the database

    $email = $password = "";
    $emailErr = $passwordErr = $loginErr = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);
        $sql = "SELECT id, name, email, password FROM users WHERE email='$email'";

        if ($result=mysqli_query($conn,$sql)) {
            if(mysqli_num_rows($result)>0) {
                $selected_data = mysqli_fetch_assoc($result); //fetches the result row into an assoc array, assoc array -> kung ano ang array mismo doon sa tinawag mo
                if(password_verify($password, $selected_data["password"])) { // if the variable password and the database password are the same if true run the below code
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $selected_data["id"];
                    $_SESSION["email"] = $selected_data["email"];
                    $_SESSION["name"] = $selected_data["name"];
                    $_SESSION['login_message'] = 'Log in was successful!';
                    $user_id = $selected_data["id"];
                    $action = "Logged In";
                    $sql = "INSERT INTO logs (user_id, action) VALUES ('$user_id', '$action')";

                    if ($conn->query($sql) === TRUE) {
                        
                    }
                    header("location: ../php/dashboard.php");
                    exit;
                } else {
                    echo "Invalid username or password.";
                    exit;
                }
            } else {
                echo "You are not registered";
            }
        }
    }
    ?>

    <form action="../html/register.html" method="POST" class="login_form">
        <div class="button_continue"> 
            <button type="submit"> Register </button>
        </div>
    </form>

    <?php 
    mysqli_close($conn);
    ?>
</body>
</html>