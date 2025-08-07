<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Edit About </title>
</head>
<body>

    <?php
    session_start();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "my_portfolio";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
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
    ];

    if(mysqli_num_rows($result)>0) {
        $selected_data = mysqli_fetch_assoc($result);  
        echo json_encode($selected_data);
        exit;
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
     <form action="edit_about.php" method="POST" class="project_form"> 
        <div class="about_section">
            <div class="about_form"> Your Details 
                <div class="about_container">
                    <div class="about_name">
                        Name: <?php echo $name ?>
                    </div>

                    <div class="about_email">
                        Email: <?php echo $email ?>
                    </div>

                    <div class="about_degree">
                        <label for="degree"> Degree: </label>
                        <input type="text" id="degree" name="degree" value="<?php echo $selected_data['degree']?>" required>
                    </div>

                    <div class="about_birthday">
                        <label for="bday"> Birthday: </label>
                        <input type="date" id="birthday" name="birthday" value="<?php echo $selected_data['birthday']?>" required>
                    </div>

                    <div class="about_experience">
                        <label for="exp"> Experience: </label>
                        <input type="text" id="experience" name="experience" value="<?php echo $selected_data['experience']?>" required>
                    </div>

                    <div class="about_phone">
                        <label for="phone"> Phone: </label>
                        <input type="number" id="phone" name="phone" value="<?php echo $selected_data['phone']?>" required>
                    </div>

                    <div class="about_address">
                        <label for="address"> Address: </label>
                        <input type="text" id="address" name="address" value="<?php echo $selected_data['address']?>" required>
                    </div>

                    <div class="about_company">
                        <label for="company"> Company: </label>
                        <input type="text" id="company" name="company" value="<?php echo $selected_data['company']?>" required>
                    </div>

                    <label for="submit">
                        <input type="Submit" name="Submit" value="Submit">
                    </label>
                </div>
            </div>
        </div>
    </form>
</body>
</html>