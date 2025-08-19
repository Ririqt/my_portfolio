<?php
    $user_id = $_SESSION['id'];

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
        }
    }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['edit'])) {
            $edit_id = intval($_GET['edit']);
            $name = trim($_POST['project_name']);
            $description = trim($_POST['description']);
            $status = trim($_POST['status']);
            $file_name = basename($_FILES["fileToUpload"]["name"]);

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
                
                $sql = "UPDATE projects SET name='$name', description='$description', status='$status', file_name='$file_name' WHERE id=$edit_id"; 
                $_SESSION['edited_message'] = "Successfully Edited";
            } elseif (($name !== $get['name']) || ($description !== $get['description']) || ($status !== $get['status'])) {
                $sql = "UPDATE projects SET name='$name', description='$description', status='$status' WHERE id=$edit_id"; //Note: important for update // 
                $_SESSION['edited_message'] = "Successfully Edited";
            
            } else {
                $_SESSION['edit_none_message'] = "Nothing was Edited";
                header("Location: ../projects/projects.php");
                exit;
            } 
            
            if ($conn->query($sql) === TRUE) {
                    //$_SESSION['edited_message'] = "Successfully Edited";
                    header("Location: ../projects/projects.php");
                    exit;
                } else {
                    echo "Update failed: " . $conn->error;
                }
        }

?>