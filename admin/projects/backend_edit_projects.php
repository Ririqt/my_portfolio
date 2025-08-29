<?php
    $user_id = $_SESSION['id'];
    // $file_name = $_GET['file_name'];
    // echo $file_name; exit;

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
            $description = $get['description'];
            $status = $get['status'];
            $file_name = $get['file_name'];

            // echo json_encode($get); exit;
        }
    }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['edit'])) {
            $edit_id = intval($_GET['edit']);
            $name = trim($_POST['project_name']);
            $description = trim($_POST['description']);
            $status = trim($_POST['status']);
            $file_name = basename($_FILES["fileToUpload"]["name"]);

            $sql = "SELECT * FROM projects WHERE user_id = '$user_id' AND name = '$name'";
            $result = $conn->query($sql);
            // echo json_encode($get);
            // echo json_encode($get['file_name']) . "<br>"; 
            // echo $file_name;
            // // echo $get['file_name'];
            // // exit;
            // exit;
            if(!empty(basename($_FILES["fileToUpload"]["name"]))) {
                $target_dir = $_SERVER['DOCUMENT_ROOT']. "/my_portfolio/uploads/projects/" . $user_id . "/";
                $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                
                if($check !== false) {
                    $uploadOk = 1; 
                } else {
                    $uploadOk = 0;
                    $_SESSION['error'] = 'Image must be PNG, JPEG, JPG, or any JFIF files, Please Upload Again';
                    header("Location: ../projects/projects.php");
                    exit;
                }

                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                if (file_exists($target_file)) {
                    $uploadOk = 0;
                    $_SESSION['error'] = "Sorry, file already exists.";
                    header("Location: ../projects/projects.php");
                    exit;
                    
                }

                if ($uploadOk == 0) {
                } else {
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    } else {
                        echo 'no';
                        exit;
                    }
                }
            } else {
                $file_name = $get["file_name"];
            }

            // if ($result && $result->num_rows > 0) {
            //     $_SESSION['error'] = "Existing Project: $name";
            //     header("Location: ../skills/skills.php");
            //     exit;

            if ($name !== $get['name']) {
                if ($result && $result->num_rows > 0) {
                $_SESSION['error'] = "Existing project: $name";
                header("Location: ../projects/projects.php");
                exit;
                } 
            } elseif (($name === $get['name'] && $file_name !== $get['file_name']) && ($description === $get['description'] && $file_name !== $get['file_name']) && ($status === $get['status'] && $file_name !== $get['file_name'])) {
                $sql = "UPDATE projects SET file_name='$file_name' WHERE id=$edit_id"; 
                $_SESSION['edited_message'] = "Successfully Uploaded";
                unlink($_SERVER['DOCUMENT_ROOT'] . "/my_portfolio/uploads/projects/" . $user_id . "/".DIRECTORY_SEPARATOR. $get['file_name']);
                $action = "Uploaded a Picture in Projects";

            } elseif (($name !== $get['name'] && $file_name !== $get['file_name']) || ($description !== $get['description'] && $file_name !== $get['file_name']) 
                        || ($status !== $get['status'] && $file_name !== $get['file_name'])) {
                if ($name !== $get['name']) {
                    if ($result && $result->num_rows > 0) {
                        $_SESSION['error'] = "Existing project: $name";
                        header("Location: ../projects/projects.php");
                        exit;
                    } 
                } else {
                $sql = "UPDATE projects SET name='$name', description='$description', status='$status', file_name='$file_name' WHERE id=$edit_id"; 
                $_SESSION['edited_message'] = "Successfully Edited and Uploaded";
                unlink($_SERVER['DOCUMENT_ROOT'] . "/my_portfolio/uploads/projects/" . $user_id . "/".DIRECTORY_SEPARATOR. $get['file_name']);
                $action = "Edited and Uploaded a Picture in Project";
                }
            } elseif (($name !== $get['name'] && $file_name === $get["file_name"]) || ($description !== $get['description'] && $file_name === $get["file_name"]) || ($status !== $get['status'] && $file_name === $get["file_name"])) {
                $sql = "UPDATE projects SET name='$name', description='$description', status='$status', file_name='$file_name' WHERE id=$edit_id"; //Note: important for update // 
                $_SESSION['edited_message'] = "Successfully Edited";
                $action = "Edited a Project";
                
            } else {
                $_SESSION['edit_none_message'] = "Nothing was Edited";
                header("Location: ../projects/projects.php");
                exit;
            } 
            
            if ($conn->query($sql) === TRUE) {
                    //$_SESSION['edited_message'] = "Successfully Edited";
                    
                    $sql = "INSERT INTO logs (user_id, action) VALUES ('$user_id', '$action')";

                    if ($conn->query($sql) === TRUE) {
                        
                    }
                    header("Location: ../projects/projects.php");
                    exit;
            } else {
                echo "Update failed: " . $conn->error;
            }
        }
        
            

?>