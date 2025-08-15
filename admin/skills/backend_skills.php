<?php
    $id = $skill_name = $type = $rate = "";
        $skill_nameErr = $typeErr = $rateErr = "";

        $user_id = $_SESSION['id'];
        $name = $_SESSION['name'];
        $email = $_SESSION['email'];
        $_SESSION['error_skills'] = 'An error occurred, Please Try again';

        if(empty($_SESSION['name']) && (empty($_SESSION['email']))) {
            header('location: /my_portfolio/html/login.html');
            exit;
        }

        if (isset($_GET['delete'])) {
            $delete_id = intval($_GET['delete']);
            $sql = "DELETE FROM skills WHERE id = $delete_id"; // important for delete // 
            if ($conn->query($sql) === TRUE) {
                $_SESSION['deleted_message'] = "Successfully Deleted";
                header("location: skills.php");
                exit;
            } else {
                echo $_SESSION['error'];
            }
        }

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $user_id = $_SESSION['id'];
            $skill_name = test_input($_POST["skill_name"]);
            $type = test_input($_POST["type"]);
            $rate = test_input($_POST["rate"]);

            $sql = "INSERT INTO skills (name, type, rate, user_id) VALUES ('$skill_name', '$type', '$rate', '$user_id')"; 
            if ($conn->query($sql) === TRUE) {
                $_SESSION['success_message'] = "Successfully Created a Skill!";
            } else {
                echo $_SESSION['error'];
                // echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        $query = "SELECT * FROM skills WHERE user_id=$user_id";
        $result = mysqli_query($conn,$query);
        

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    } 

    // mysqli_close($conn);
    // $conn->close();
?>