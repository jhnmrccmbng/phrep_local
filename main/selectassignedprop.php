<?php  
include "../config.php";
//select.php  
if(isset($_POST["uid"]))
{
    $output = '';
    $connect = mysqli_connect($dbServer, $dbUsername, $dbPassword, $dbDatabase);

    // JM added confirmation to 1
    $query = "SELECT * FROM rev_groups a 
            INNER JOIN proposal b ON a.sub_id = b.sub_id
            INNER JOIN phrepuser c ON b.username = c.id
            WHERE a.review = '1' AND a.confirmation = '1' AND a.primary_reviewer = '1' AND a.phrepuser_id = '".$_POST['uid']."'";
    $result = mysqli_query($connect, $query);

    $numrows = mysqli_num_rows($result);
    
    $i = 1;
    if ($numrows > 0) {
        $output .= '<div class="table-responsive"><table class="table table-bordered table-condensed">';
        while ($row = mysqli_fetch_array($result)) {
            $output .= '<tr>';
            $output .= '<td class=text-center text-nowrap>';
            $output .= $i;
            $output .= '</td>';
            $output .= '<td><strong>';
            $output .= '<a data-toggle="tooltip" title="PROPOSAL" target = "_blank" href="proposal_info.php?id='.$row["sub_id"].'" onclick="newwin(this.href)" role="button">';
            $output .= $row["prop_ptitle"];
            $output .= '</a>'; 
            $output .= '</strong><br><small>'.$row["title"].' '.$row["fname"].' '.$row["mname"].' '.$row["lname"].'</small>';
            $output .= '</td>';
            $output .= '</tr>';
    $i++;
        }
    }
    $output .= '</table></div>';
    echo $output;
}
?>
<script>
function newwin(a) {
    window.open(a, 'newwindow', 'width=1024, height=768'); 
    return true;
}
</script>