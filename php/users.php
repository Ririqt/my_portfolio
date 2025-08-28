<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Users </title>
</head>
<body>
    <?php
   
    session_start();
    require_once "config.php";

    $num_items = 2;
    $total_query = "SELECT COUNT(*) FROM users";            
    $total_result = mysqli_query($conn, $total_query);
    $total = mysqli_fetch_assoc($total_result)['total'];
    $pages = ceil($total[0] / $num_items);
    ?>

    <div class="center">
        <h2>Simple Pagination Demo</h2>
        <div id="content"></div>
        <ul id="pagination">
            <?php for ($i = 1; $i <= $pages; $i++) {
                echo '<li id="' . $i . '">' . $i . '</li>';
            } ?>
        </ul>
        <div id="loading"></div>
    </div>

    <?php
    $per_page = 2;
    $page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;

    $start = ($page - 1) * $per_page;
    $sql = "SELECT * FROM users ORDER BY id LIMIT $start, $per_page";
    $result = $conn->query($sql);
    ?>
    <table class="user-table">
        <tr><th>ID</th><th>First Name</th><th> Email</th></tr>
        <?php while ($row = $result->fetch_array()) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <!-- <td><?php //echo $row['LastName']; ?></td> -->
            </tr>
        <?php } ?>
    </table>
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"> </script>
<script> 
    $(document).ready(function() {
    function showLoading() {
        $("#loading").fadeIn(100).html('');
    }
    function hideLoading() {
        $("#loading").fadeOut('slow');
    }

    $("#pagination li:first").css({ 'color': '#FF0084', 'border': 'none' });
    $("#content").load("data.php?page=1", hideLoading);

    $("#pagination li").click(function() {
        showLoading();
        $("#pagination li").css({ 'border': 'solid #ddd 1px', 'color': '#0063DC' });
        $(this).css({ 'color': '#FF0084', 'border': 'none' });
        var pageNum = this.id;
        $("#content").load("data.php?page=" + pageNum, hideLoading);
    });
});
</script>