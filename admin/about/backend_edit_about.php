<?php
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

            if ($selected_data['degree'] && $selected_data['birthday'] && $selected_data['experience'] && $selected_data['address'] && $selected_data['company'] && $selected_data['phone']) {
                $sql = "UPDATE about SET birthday='$birthday', degree='$degree', experience='$experience', address='$address', company='$company', phone='$phone' WHERE user_id=$user_id";
            } else {
                $sql = "INSERT INTO about (name, email, user_id, degree, birthday, experience, address, company, phone) VALUES ('$name', '$email', '$user_id', '$degree', '$birthday','$experience','$address','$company','$phone')";
            }
             //Note: important for update // 
            if ($conn->query($sql) === TRUE) {
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