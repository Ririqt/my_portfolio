<?php
    if(empty($_SESSION['name']) && (empty($_SESSION['email']))) {
            header('location: /my_portfolio/html/login.html');
            exit;
        }
    
    $user_id = $_SESSION['id'];
    $name = $_SESSION['name'];
    $email = $_SESSION['email'];
    $query = "SELECT * FROM about WHERE user_id=$user_id";
    $result = mysqli_query($conn,$query);
    $selected_data = [
        "degree"=>"",
        "birthday"=>"",
        "experience"=>"",
        "phone"=>"",
        "address"=>"",
        "company"=>"",
        "role"=>"",
        "description"=>"",
    ];

    if(mysqli_num_rows($result)>0) {
        $selected_data = mysqli_fetch_assoc($result);  
    }
        
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['id'];
            $birthday = test_input($_POST["birthday"]);
            $degree = test_input($_POST["degree"]);
            $experience = test_input($_POST["experience"]);
            $address = test_input($_POST["address"]);
            $company = test_input($_POST["company"]);
            $phone = test_input($_POST["phone"]);
            $role = test_input($_POST["role"]);
            $description = test_input($_POST["description"]);
            $file_name = basename( $_FILES["fileToUpload"]["name"]);

            if(isset($_POST["submit"]) && !empty(basename($_FILES["fileToUpload"]["name"]))) {
                $target_dir = $_SERVER['DOCUMENT_ROOT']. "/my_portfolio/uploads/" . $user_id . "/";
                $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                
                if($check !== false) {
                    echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1; 
                } else {
                    $uploadOk = 0;
                    $_SESSION['error'] = 'Image Error, Please Upload Again';
                    header("Location: ../about/edit_about.php");
                    exit;
                }
                if (!file_exists($target_dir)) {
                    mkdir( $target_dir, 0777, true);
                }
                if ($uploadOk == 0) {
                } else {
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                        echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
                        $action = "Uploaded a Picture";
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                        header("Location: ../about/edit_about.php");
                    }
                }
            } 
            
            if ($selected_data['degree'] && $selected_data['birthday'] && $selected_data['experience'] && $selected_data['address'] && $selected_data['company'] && $selected_data['phone'] ) {
                if ($file_name) {
                    $sql = "UPDATE about SET birthday='$birthday', degree='$degree', experience='$experience', address='$address', company='$company', phone='$phone', role='$role', description='$description', file_name='$file_name' WHERE user_id=$user_id";
                    unlink($_SERVER['DOCUMENT_ROOT'] . "/my_portfolio/uploads/" . $user_id . "/".DIRECTORY_SEPARATOR. $selected_data['file_name']);
                    $_SESSION['success_message'] = "Edit was Successful";
                    
                } elseif (($degree !== $selected_data['degree']) || ($birthday !== $selected_data['birthday']) || ($experience !== $selected_data['experience']) ||
                         ($address !== $selected_data['address']) || ($company !== $selected_data['company']) || ($phone !== $selected_data['phone']) || 
                         ($role !== $selected_data['role']) || ($description !== $selected_data['description'])) {
                    $sql = "UPDATE about SET birthday='$birthday', degree='$degree', experience='$experience', address='$address', company='$company', phone='$phone', role='$role', description='$description' WHERE user_id=$user_id";
                    $_SESSION['success_message'] = "Edit was Successful";
                    $action = "Edited About";
                } else {
                    $_SESSION['edit_none_message'] = "Nothing was Edited";
                    header("Location: ../about/about.php");
                    exit;
                }
            } else {
                if ($file_name) {
                    $sql = "INSERT INTO about (name, email, user_id, degree, birthday, experience, address, company, phone, role, description, file_name) VALUES ('$name', '$email', '$user_id', '$degree', '$birthday','$experience','$address','$company','$phone', '$role', '$description', '$file_name')";
                    $_SESSION['success_message'] = "Edit was Successful";
                } else {
                    $sql = "INSERT INTO about (name, email, user_id, degree, birthday, experience, address, company, phone, role, description) VALUES ('$name', '$email', '$user_id', '$degree', '$birthday','$experience','$address','$company','$phone', '$role', '$description')";
                    $_SESSION['success_message'] = "Edit was Successful";
                }
                $action = "Inserted About";
            }

            if ($conn->query($sql) === TRUE) { // no matter what happens in above this will return true
                $sql = "INSERT INTO logs (user_id, action) VALUES ('$user_id', '$action')";

                    if ($conn->query($sql) === TRUE) {
                        
                    }
                header("Location: ../about/about.php");
                exit;
            } else {
                echo "Update failed: " . $conn->error;
            }
               
        }

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
?>