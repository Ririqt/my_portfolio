<?php
    $user_id = $_SESSION['id'];
            
        if (isset($_GET['delete'])) {
            $delete_id = intval($_GET['delete']);
            $sql = "DELETE FROM projects WHERE id = $delete_id"; // important for delete // 
            $conn->query($sql);
            header("Location: projects.php");
            exit;
        }

        $project_name = $description = $status = "";
        $projectNameErr = $descriptionErr = "";

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $user_id = $_SESSION['id'];
            $project_name = test_input($_POST["project_name"]);
            $description = test_input($_POST['description']);
            $status = test_input($_POST["status"]);

            $sql = "INSERT INTO projects (name, description, status, user_id) VALUES ('$project_name', '$description', '$status', '$user_id')"; // important for create // 
            if ($conn->query($sql) === TRUE) {
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        $query = "SELECT * FROM projects WHERE user_id=$user_id";
        $result = mysqli_query($conn,$query);

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    } 

    $conn->close();
?>