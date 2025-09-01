<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="logs of the user">
    <link rel="stylesheet" href="../css/logs.css">
    <title> Logs </title>
</head>
<body>
<?php
session_start();
require_once "config.php";

if (empty($_SESSION['name']) && (empty($_SESSION['email'])) && (empty($_SESSION['id']))) {
    header('location: ../html/login.html');
    exit;
}

$user_id = $_SESSION["id"];
$rows_per_page = 18;

// Get current page from query string (default = 1)
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1; //Getting the number of page if the page is set and is a number then It will assigned the n of a page which the default is page 1

// Calculate offset (this is for database offset)
$offset = ($page - 1) * $rows_per_page; //this formula calculates the offset (the data cutoff) for example: 3 is the $page then (3-1) * 18 is 36 which means the data will be up to 36  

$total_query = "SELECT COUNT(*) as total FROM logs WHERE user_id=$user_id";
$total_result = mysqli_query($conn, $total_query);
$total = mysqli_fetch_assoc($total_result)['total'];

// Calculate total pages
$total_pages = ceil($total / $rows_per_page); //ceil rounds the number to the nearest integer above its current value

// Fetch logs only for the current page
$query = "SELECT * FROM logs WHERE user_id=$user_id ORDER BY timestamp DESC LIMIT $rows_per_page OFFSET $offset";
$result = mysqli_query($conn, $query);
?>

<div class="header">
    <div class="header_container"> 
        <div class="nav_bar">
            <h1 class="header_title"> <a href="/my_portfolio/php/dashboard.php"> My Portfolio </a> </h1>
            <nav class="header_text">
                <ul>
                    <li> <a href="../admin/about/about.php"> About </a> </li>
                    <li id="skills"> <a href="../admin/skills/skills.php"> Skills </a> </li>
                    <li id="projects"> <a href="../admin/projects/projects.php"> Projects </a> </li>
                    <li id="logs"> <a href="../php/logs.php"> Logs </a> </li>
                    <li id="inquiries"> <a href="../php/inquiries.php"> Inquiries </a> </li>
                    <li> <a href="/my_portfolio/php/logout.php" onclick="return confirm('Are you sure you want to Log Out?')"> Log Out </a> </li>
                </ul>
            </nav> 
            <div class="user"> <a href="../php/users.php"> User:</a> <?php echo $_SESSION['name']; ?> </div> 
        </div>
    </div>
</div>

<div class="table_container"> 
    <div id="table" class="activity_log_data_table">
        <table class="styled_table" id="data">
            <thead>
                <tr class="header_row">
                    <th> Action </th>
                    <th> Date </th>
                    <th> Time </th> 
                </tr>
            </thead>
            <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $time = new DateTime($row['timestamp']);
                    $date = $time->format('F j, Y');
                    $time = $time->format('h:i a');
                    echo "<tr>
                            <td>{$row['action']}</td>
                            <td>{$date}</td>
                            <td>{$time}</td>
                          </tr>";
                }
            } else {
                echo '<tr><td colspan="3" id="add">Nothing in the Logs</td></tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</div> 

<div id="nav" style="margin-top:20px; text-align:center;">
    <?php if ($total_pages > 1): ?>
        <?php
            $max_visible = 5; // how many page numbers to show
            $start = max(1, $page - floor($max_visible / 2)); // floor rounds down the number, max gets the value of highest value in that range
            echo $total_pages;
            echo $start; 
            echo $page; 
            // echo $max_visible; exit;
            $end = min($total_pages, $start + $max_visible - 1); // signifies the end in which it gets the smallest from total pages to t
            echo $end; 
            // shift window if at the end
            if ($end - $start + 1 < $max_visible) { // this calculates the start value if the end value is greater than the max visible pages
                $start = max(1, $end - $max_visible + 1); // this returns the value of start whether the user is in the end value
            }
        ?>

        <!-- First + Prev -->
        <?php if ($page > 1): ?>
            <a href="?page=1"> First </a> <!-- Page 1 this is useful when page has been double-triple digit-->
            <a href="?page=<?php echo $page - 1; ?>"> Prev </a> <!-- Allocating the previous page -->
        <?php endif; ?> <!-- signifies the end of the condition statement-->

        <!-- Page numbers -->
        <?php for ($i = $start; $i <= $end; $i++): ?>
            <?php if ($i == $page): ?> <!-- If i reaches the certain page it will echo that i--> 
                <b> <?php echo $i; ?> </b> <!-- Current Page -->
            <?php else: ?> <!-- Everything Else -->
                <a href="?page=<?php echo $i; ?>"> <!-- allocating the other pages --> <?php echo $i; ?> </a>
            <?php endif; ?>
        <?php endfor; ?>

        <!-- Next + Last -->
        <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo $page + 1; ?>">Next</a>
            <a href="?page=<?php echo $total_pages; ?>">Last</a>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>
