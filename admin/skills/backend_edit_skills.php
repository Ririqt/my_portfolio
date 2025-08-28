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
        $user_id = $_SESSION["id"];
        // echo $rate;
        // echo $get['rate'];
        // exit;
        $check_sql = "SELECT * FROM skills WHERE user_id = '$user_id' AND name = '$name'";
        $check_result = $conn->query($check_sql);

        // echo json_encode($check_result); exit;
        
        if ($name === $get['name'] && $type === $get['type'] && $rate === $get['rate']) {
            $_SESSION['edit_none_message'] = "Nothing was Edited";
            header("Location: ../skills/skills.php");
            exit;
        } elseif (($name === $get['name'] && $type !== $get['type']) || ($name === $get['name'] && $rate !== $get['rate'])) {
            // if ($check_result && $check_result->num_rows > 0) {
            //     $_SESSION['error'] = "Existing skill: $name";
            //     header("Location: ../skills/skills.php");
            //     exit;
            // }
            $sql = "UPDATE skills SET name='$name', type='$type', rate='$rate' WHERE id=$edit_id"; //Note: important for update // 
            if ($conn->query($sql) === TRUE) {
                $_SESSION['edited_message'] = "Successfully Edited";
                $user_id = $_SESSION['id'];
                $action = "Edited a Skill";
                $sql = "INSERT INTO logs (user_id, action) VALUES ('$user_id', '$action')";

                if ($conn->query($sql) === TRUE) {
                    
                }
                header("Location: ../skills/skills.php");
                exit;
            } else {
                $_SESSION['error'] = 'An error occurred, Please Try again';
                echo $_SESSION['error'];
                // echo "Update failed: " . $conn->error;
            }
        } elseif ($name !== $get['name'])  {
            if ($check_result && $check_result->num_rows > 0) {
                $_SESSION['error'] = "Existing skill: $name";
                header("Location: ../skills/skills.php");
                exit;
            } else {
                $sql = "UPDATE skills SET name='$name', type='$type', rate='$rate' WHERE id=$edit_id";
                if ($conn->query($sql) === TRUE) {
                $_SESSION['edited_message'] = "Successfully Edited";
                $user_id = $_SESSION['id'];
                $action = "Edited a Skill";
                $sql = "INSERT INTO logs (user_id, action) VALUES ('$user_id', '$action')";

                    if ($conn->query($sql) === TRUE) {
                        
                    }
                    header("Location: ../skills/skills.php");
                    exit;
                }
            }
        } else {
            if ($check_result && $check_result->num_rows > 0) {
                $_SESSION['error'] = "Existing skill: $name";
                header("Location: ../skills/skills.php");
                exit;
            }
        } 

        // if ($conn->query($sql) === TRUE) {
        //     $_SESSION['edited_message'] = "Successfully Edited";
        //     $user_id = $_SESSION['id'];
        //     $action = "Edited a Skill";
        //     $sql = "INSERT INTO logs (user_id, action) VALUES ('$user_id', '$action')";

        //     if ($conn->query($sql) === TRUE) {
                
        //     }
        //     header("Location: ../skills/skills.php");
        //     exit;
        // }
    }
?>