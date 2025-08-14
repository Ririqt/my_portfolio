<?php
// session_start();

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
            // echo json_encode($description);
            // exit;

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
                    // $error_message = "File is not an image. Please Upload Again";
                    $uploadOk = 0;
                    $_SESSION['error'] = 'Image Error, Please Upload Again';
                    echo $_SESSION['error'];
                    header("Location: ../about/edit_about.php");
                    exit;
                }
            

                if (!file_exists($target_dir)) {
                    mkdir( $target_dir, 0777, true);
                }

                // Check if file already exists

                // Allow certain file formats
                // if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                //     echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                //     $uploadOk = 0;
                //     echo $uploadOk;
                //     // header("Location: ../about/edit_about.php");
                //     // exit;
                //     if ($_SESSION['error'] === $file_not_image_message) {
                //         $_SESSION['error'] = 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
                //         echo 'hi';
                //         //header("Location: ../about/edit_about.php");
                //         exit;
                //     }
                //     //$_SESSION['error'] = ;
                //     // echo $_SESSION['error'];
                // }

                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    //echo "Sorry, your file was not uploaded.";
                    
                    // echo "error";
                    // exit;
                    // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                        echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
                        
                        if ($selected_data['degree'] && $selected_data['birthday'] && $selected_data['experience'] && $selected_data['address'] && $selected_data['company'] && $selected_data['phone'] ) {
                            if ($file_name) {
                                $sql = "UPDATE about SET birthday='$birthday', degree='$degree', experience='$experience', address='$address', company='$company', phone='$phone', role='$role', description='$description', file_name='$file_name' WHERE user_id=$user_id";
                            } else {
                                $sql = "UPDATE about SET birthday='$birthday', degree='$degree', experience='$experience', address='$address', company='$company', phone='$phone', role='$role', description='$description' WHERE user_id=$user_id";
                            }
                        } else {
                            if ($file_name) {
                                $sql = "INSERT INTO about (name, email, user_id, degree, birthday, experience, address, company, phone, role, description, file_name) VALUES ('$name', '$email', '$user_id', '$degree', '$birthday','$experience','$address','$company','$phone', '$role', '$description', '$file_name')";
                            } else {
                                $sql = "INSERT INTO about (name, email, user_id, degree, birthday, experience, address, company, phone, role, description) VALUES ('$name', '$email', '$user_id', '$degree', '$birthday','$experience','$address','$company','$phone', '$role', '$description')";
                            }
                        }

                        if ($conn->query($sql) === TRUE) { // no matter what happens in above this will return true
                            header("Location: ../about/about.php");
                            exit;
                        } else {
                            echo "Update failed: " . $conn->error;
                        }
                                
                        // header("Location: ../about/about.php");
                        // exit;
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                        header("Location: ../about/edit_about.php");
                    }
                }
            }

            // if ($selected_data['degree'] && $selected_data['birthday'] && $selected_data['experience'] && $selected_data['address'] && $selected_data['company'] && $selected_data['phone'] ) {
                // if ($file_name) {
                //     $sql = "UPDATE about SET birthday='$birthday', degree='$degree', experience='$experience', address='$address', company='$company', phone='$phone', role='$role', description='$description', file_name='$file_name' WHERE user_id=$user_id";
                // } else {
                //     $sql = "UPDATE about SET birthday='$birthday', degree='$degree', experience='$experience', address='$address', company='$company', phone='$phone', role='$role', description='$description' WHERE user_id=$user_id";
                // }
                
                
           
                // Check if image file is a actual image or fake image
                
             //Note: important for update // 
            
            if ($selected_data['degree'] && $selected_data['birthday'] && $selected_data['experience'] && $selected_data['address'] && $selected_data['company'] && $selected_data['phone'] ) {
                if ($file_name) {
                    $sql = "UPDATE about SET birthday='$birthday', degree='$degree', experience='$experience', address='$address', company='$company', phone='$phone', role='$role', description='$description', file_name='$file_name' WHERE user_id=$user_id";
                } else {
                    $sql = "UPDATE about SET birthday='$birthday', degree='$degree', experience='$experience', address='$address', company='$company', phone='$phone', role='$role', description='$description' WHERE user_id=$user_id";
                }
            } else {
                if ($file_name) {
                    $sql = "INSERT INTO about (name, email, user_id, degree, birthday, experience, address, company, phone, role, description, file_name) VALUES ('$name', '$email', '$user_id', '$degree', '$birthday','$experience','$address','$company','$phone', '$role', '$description', '$file_name')";
                } else {
                    $sql = "INSERT INTO about (name, email, user_id, degree, birthday, experience, address, company, phone, role, description) VALUES ('$name', '$email', '$user_id', '$degree', '$birthday','$experience','$address','$company','$phone', '$role', '$description')";
                }
            }

            if ($conn->query($sql) === TRUE) { // no matter what happens in above this will return true
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