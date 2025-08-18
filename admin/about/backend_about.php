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
        "file_name"=>"",
    ];
            
    if(mysqli_num_rows($result)>0) {
        $selected_data = mysqli_fetch_assoc($result);  
    }

    // if(isset($_POST["submit"])) {
        $target_dir = $_SERVER['DOCUMENT_ROOT']. "/my_portfolio/uploads/" . $user_id . "/"; 
?>