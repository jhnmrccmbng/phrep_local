<?php  
include "../config.php";
//select.php  
if(isset($_POST["sid"]))
{
    $output = '';
    $connect = mysqli_connect($dbServer, $dbUsername, $dbPassword, $dbDatabase);

    $query = "SELECT * FROM `rev_comment` WHERE sub_id = '".$_POST['pid']."' and phrepuser_id = '".$_POST['uid']."' and id = '".$_POST['cid']."'";
    $result = mysqli_query($connect, $query);

    $numrows = mysqli_num_rows($result);

    if ($numrows > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $output .= $row['comment'];
        }
    }



    
    echo $output;
}
?>