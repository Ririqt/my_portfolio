<?php
    if(empty($_SESSION['name']) && (empty($_SESSION['email']))) {
            header('location: /my_portfolio/html/login.html');
            exit;
        }

    $id = $degree = $birthday = $experience = $address = $company = $phone = $role = $description = "";
    $degreeErr = $birthdayErr = $experienceErr = $addressErr = $companyErr = $phoneErr = $roleErr = $description = "";

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

    // if(isset($_POST["submit"])) {
        $target_dir = $_SERVER['DOCUMENT_ROOT']. "/my_portfolio/uploads/" . $user_id . "/";
        // $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    //     $uploadOk = 1;
    //     $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    //     $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            
    //     if($check !== false) {
    //         // echo "File is an image - " . $check["mime"] . ".";
    //         $uploadOk = 1;
    //     } else {
    //         $upload_error = "File is not an image. Please Upload Again";
    //         $uploadOk = 0;
    //     }
    // }

    // if (file_exists($target_file)) {
    //     $upload_error = "Sorry, file already exists.";
    //     $uploadOk = 0;
    // }
    // // Check file size
    // if ($_FILES["fileToUpload"]["size"] > 5000000) {
    //     echo "Sorry, your file is too large.";
    //     $uploadOk = 0;
    // }
    // mysqli_close($conn); 
?>