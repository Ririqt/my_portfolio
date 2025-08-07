<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/valid_register.css">
    <title> Register Succeed</title>

    <script>
    if (window.history.replaceState ) {
        window.history.replaceState(null, null, window.location.href );
    }
    </script>
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
    require_once "config.php"; //

    $full_name = $email = $password = $confirm_password = ""; //variables of important details in register
    $full_nameErr = $emailErr = $passwordErr = $confirm_passwordErr = ""; //variables of errors is set to empty string ""

    if ($_SERVER["REQUEST_METHOD"] == "POST") { // If the server has requested a post returns the statement if true

        $full_name = test_input($_POST["full_name"]); // Shows the array of details that is pass by the post / returns the name and will stored to the variable name
        if (!preg_match("/^[a-zA-Z-' ]*$/", $full_name)) { //RegEx -> Regular Expression /preg_match will tell if the string matches the given RegEx
        // '/' is the delimiter wherein delimiter is a special characters to block a certain string.
        // '^' is a MetaCharacter detects the beginning of the string that is enclosed to the condition.
        // '[]' finds the character that is between this brackets
        // 'a-z' is finding small a to small z, 'A-Z' is for finding big A to big Z, and the '-' , (') , is for finding the whitespace
        //  * matches if there is zero or more numbers
        // $ end of the string.
        // so that statement says that if the condition doesn't match the string it will raise the following code. 
            $full_nameErr = "Only letters and white space allowed";
            echo "Name Error: $full_nameErr <br>";
            echo "Inputted Name: $full_name <br>";
            exit;
        } 

        $email = test_input($_POST['email']); //storing the email that's fetched to the POST
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { //filtering the given email it it's valid or not, if invalid execute the statement
            // filter_var - filters a variable with the specified filter // Parameter - (var, filtername, options)
            $emailErr = "Invalid email format"; 
            echo "Email Error: $emailErr <br>";
            echo "Inputted Email: $email <br>";
        } else { //execute this statement if valid
            $query = "SELECT email FROM users WHERE email='$email' "; // always store a sql command either $sql, $query
            if ($result=mysqli_query($conn,$query)) {  //performs a query (request for information) against a database 
                // $conn and $query will establish connection within themselves and will be the result set if seen in the database
                // after a successful connection it will store to the result variable and it will execute the code below
                if(mysqli_num_rows($result)>0){ //If the result is in (as integer) the database it means that the record is existed in the database
                    echo "this email exist, try again! <br>";
                    echo "Please click the Register.";
                    exit;
                } else {
                }
            }
        }

        if(($_POST["password"] == $_POST["confirm_password"])) {
            $password = test_input($_POST["password"]);
            $confirm_password = test_input($_POST["confirm_password"]);
            if (strlen($_POST["password"]) <= 7 ) {
                $passwordErr = "Your Password Must Contain At Least 8 Characters! <br> ";
                echo '<div class="password_error">', $passwordErr ;
                echo "Inputted Characters: " , strlen($password),'</div>'; 
                exit;
            }
            elseif(!preg_match("#[0-9]+#",$password)) { // # is the delimiter, + matches any string that has at least one in the brackets
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

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (name, email, password) VALUES ('$full_name', '$email', '$hashed_password')";
        
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    {   
        echo '<div class="full_name">',"Welcome ", ucfirst($full_name), "!<br> <br>",'</div>';
        echo '<div class="details">',"Here are your details: <br>";
        echo "Name: {$full_name} <br>";
        echo "Email: {$email} <br>";
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

