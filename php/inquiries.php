<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Inquiries </title>
    <link rel="stylesheet" href="../css/inquiries.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"> </script>
    <script>
        $(document).ready(function(){ // jQuery: Document Ready Event //$(the selector).action() 
        $('#data').after('<div id="nav"></div>');
            var rowsShown = 20;
            var rowsTotal = $('#data tbody tr').length;
            var numPages = rowsTotal/rowsShown;
            for(i = 0;i < numPages;i++) {
                var pageNum = i + 1;
                $('#nav').append('<a href="javascript:void()" rel="'+i+'">'+pageNum+'</a> ');
            }
        $('#data tbody tr').hide();
        $('#data tbody tr').slice(0, rowsShown).show();
        $('#nav a:first').addClass('active');
        $('#nav a').bind('click', function(){

            $('#nav a').removeClass('active');
            $(this).addClass('active');
            var currPage = $(this).attr('rel');
            var startItem = currPage * rowsShown;
            var endItem = startItem + rowsShown;
            $('#data tbody tr').css('opacity','0.0').hide().slice(startItem, endItem).
            css('display','table-row').animate({opacity:1}, 300);
        });
    });
    </script>
</head>
<body>
    <?php
    session_start();
    require_once "config.php";
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
                <div class="user"> User: <?php echo $_SESSION['name'];  ?> </div> 
            </div>
        </div>
    </div>

    <div class="table_container"> 
        <div id="table" class="activity_log_data_table">
                <table class="styled_table" id="data">
                    <thead>
                        <tr class="message_row">
                            <th> No. </th>
                            <th> Name </th>
                            <th> Subject </th>
                            <th> Messages </th>
                        </tr>
                    </thead>
        <?php
        $sql = "SELECT * FROM contacts";
        $result = mysqli_query($conn,$sql);

        // echo json_encode($result);
        // exit;
        if(mysqli_num_rows($result)>0) {
            $i = 1;
                    while($row = mysqli_fetch_assoc($result)) {
        ?>

        <tbody>
            <tr>
                <td> <?php echo $i; $i++; ?> </td>  
                <td> <?php echo $row['name']; ?> </td>  
                <td> <?php echo $row['subject']; ?> </td> 
                <td> <?php echo $row['message']; ?> </td> 
            <?php
                }
            } else {
                echo '<tr> <td colspan="3" id="add">' , "Nothing in the Inquiries" , "</td>" , "</tr>";
            }
        ?>
            </tr>
        </tbody>
                </table>
        </div>
    </div>

</body>
</html>