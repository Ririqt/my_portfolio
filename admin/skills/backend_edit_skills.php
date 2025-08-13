<?php
    if(empty($_SESSION['name']) && (empty($_SESSION['email']))) {
            header('location: /my_portfolio/html/login.html');
            exit;
        }

    if (isset($_GET['edit'])) {
        $edit_id = $_GET['edit'];
        $sql = "SELECT * FROM skills WHERE id = $edit_id"; //Note: select all // 
        $result = mysqli_query($conn,$sql);
        
        if (mysqli_num_rows($result) == 1) {
            $get = mysqli_fetch_assoc($result); // Note: getting the result into an array //
            $name = $get['name'];
            $type = $get['type'];
            $rate = $get['rate'];
        }
    }

    if(mysqli_num_rows($result)>0) {
        $selected_data = mysqli_fetch_assoc($result);  
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['edit'])) {
        $edit_id = intval($_GET['edit']);
        $name = trim($_POST['skill_name']);
        $type = trim($_POST['type']);
        $rate = trim($_POST['rate']);

        $sql = "UPDATE skills SET name='$name', type='$type', rate='$rate' WHERE id=$edit_id"; //Note: important for update // 
         if ($conn->query($sql) === TRUE) {
            header("Location: ../skills/skills.php");
            exit;
        } else {
            echo "Update failed: " . $conn->error;
        }
    }
?>