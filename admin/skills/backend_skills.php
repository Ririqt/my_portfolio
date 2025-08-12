<?php
    $id = $skill_name = $type = $rate = "";
        $skill_nameErr = $typeErr = $rateErr = "";

        $user_id = $_SESSION['id'];
        $name = $_SESSION['name'];
        $email = $_SESSION['email'];

        if(empty($_SESSION['name']) && (empty($_SESSION['email']))) {
            header('location: /my_portfolio/html/login.html');
            exit;
        }

        if (isset($_GET['delete'])) {
            $delete_id = intval($_GET['delete']);
            $sql = "DELETE FROM skills WHERE id = $delete_id"; // important for delete // 
            $conn->query($sql);
            header("location: skills.php");
            exit;
        }

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $user_id = $_SESSION['id'];
            $skill_name = test_input($_POST["skill_name"]);
            $type = test_input($_POST["type"]);
            $rate = test_input($_POST["rate"]);

            $sql = "INSERT INTO skills (name, type, rate, user_id) VALUES ('$skill_name', '$type', '$rate', '$user_id')"; 
            if ($conn->query($sql) === TRUE) {
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
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