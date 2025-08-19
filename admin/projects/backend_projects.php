<?php
    if(empty($_SESSION['name']) && (empty($_SESSION['email']))) {
            header('location: /my_portfolio/html/login.html');
            exit;
        }

    $user_id = $_SESSION['id'];
    $_SESSION['error_projects'] = 'An error occurred, Please Try again';

        if (isset($_GET['delete'])) {
            $delete_id = intval($_GET['delete']);

            $sql = "SELECT * FROM projects WHERE id = $delete_id"; //Note: select all // 
            $result = mysqli_query($conn,$sql);
            
            if (mysqli_num_rows($result) == 1) {
                $get = mysqli_fetch_assoc($result); // Note: getting the result into an array //
                $file_name = $get['file_name']; 
                unlink($_SERVER['DOCUMENT_ROOT'] . "/my_portfolio/uploads/projects/" . $user_id . "/".DIRECTORY_SEPARATOR. $file_name);
            }

            $sql = "DELETE FROM projects WHERE id = $delete_id"; // important for delete // 
            if ($conn->query($sql) === TRUE) {
                $_SESSION['deleted_message'] = "Successfully Deleted";
                header("Location: projects.php");
                exit;
            } else {
                echo $_SESSION['error'];
            }
        }

        $project_name = $description = $status = "";
        $projectNameErr = $descriptionErr = "";

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $user_id = $_SESSION['id'];
            $project_name = test_input($_POST["project_name"]);
            $description = test_input($_POST['description']);
            $status = test_input($_POST["status"]);
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

                $sql = "INSERT INTO projects (name, description, status, user_id, file_name) VALUES ('$project_name', '$description', '$status', '$user_id', '$file_name')";
                $_SESSION['success_message'] = "Successfully Created a Project!";
            } else {
                $sql = "INSERT INTO projects (name, description, status, user_id) VALUES ('$project_name', '$description', '$status', '$user_id')";
                $_SESSION['success_message'] = "Successfully Created a Project!";
            }
                    
        if ($conn->query($sql) === TRUE) {
            //$_SESSION['success_message'] = "Successfully Created a Project!";
        } else {
            echo $_SESSION['error'];
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

    // mysqli_close($conn); 
?>