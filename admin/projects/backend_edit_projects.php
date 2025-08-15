<?php    
    if(empty($_SESSION['name']) && (empty($_SESSION['email']))) {
            header('location: /my_portfolio/html/login.html');
            exit;
        }

    if (isset($_GET['edit'])) {
        $edit_id = $_GET['edit'];
        $sql = "SELECT * FROM projects WHERE id = $edit_id"; //Note: select all // 
        $result = mysqli_query($conn,$sql);
        
        if (mysqli_num_rows($result) == 1) {
            $get = mysqli_fetch_assoc($result); // Note: getting the result into an array //
            $name = $get['name'];

            // if ($name === $name) {
            //     echo true;
            //     exit;
            // }
            $description = $get['description'];
            $status = $get['status'];
        }
    }
// if (($name !== $name) || ($description !== $description) || ($status !== $status) )
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['edit'])) {
            $edit_id = intval($_GET['edit']);
            $name = trim($_POST['project_name']);
            // echo $name;
            // echo $get['name'];
            // exit;
            $description = trim($_POST['description']);
            $status = trim($_POST['status']);

            if (($name !== $get['name']) || ($description !== $get['description']) || ($status !== $get['status'])) {
                $sql = "UPDATE projects SET name='$name', description='$description', status='$status' WHERE id=$edit_id"; //Note: important for update // 
                if ($conn->query($sql) === TRUE) {
                    $_SESSION['edited_message'] = "Successfully Edited";
                    header("Location: ../projects/projects.php");
                    exit;
                } else {
                    echo "Update failed: " . $conn->error;
                }
            } else {
                header("Location: ../projects/projects.php");
                exit;
            }
        }

?>