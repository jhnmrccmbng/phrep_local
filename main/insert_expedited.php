<?php
include "../config.php";
//insert.php  
$connect = mysqli_connect($dbServer, $dbUsername , $dbPassword, $dbDatabase);
if (!empty($_POST)) {
    $subid = mysqli_real_escape_string($connect, $_POST["subid"]);
    
    $query = "INSERT INTO review_type (id, sub_id, rt_id)  
     VALUES('', '$subid', '1')";?>
    
<!--<script>alert('hello');</script>-->
 <?php   
 
 
     if (mysqli_query($connect, $query)) {?>
<?php

$output .= '<div class="panel-heading">Type of Review Assignment</div>


<!-- Table -->
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>Submitted</th>
        <th>Title</th>
        <th><center>Type of Review</center></th>
</tr>';?>
<?php
$id1 = "sub_id";
$id2 = "status_action";
$id3 = "inst_id";
$id4 = "id";
$id5 = "secretary";
$id6 = "username";

$myrow = $obj->gettingMaxValueStatus("submission", "combased", "rec_list", "proposal", "phrepuser", "proposal_status", $id1, $id2, $id3, $id4, $id5, $id6, $mi['username']);
$num = count($myrow);
if ($num > 0) {
    foreach ($myrow as $row) {

        if ($row['sa'] == '1') {
            $idc = $row[sub_id];
            $id1 = "sub_id";
            $id2 = "status_action";
            $id3 = "inst_id";
            $id4 = "id";
            $id5 = "secretary";
            $id6 = "username";
            $myrow1 = $obj->gettingProposalByStatus("submission", "combased", "rec_list", "proposal", "phrepuser", $id1, $id2, $id3, $id4, $id5, $id6, $mi['username'], $idc);
            $num1 = count($myrow1);
//                                                
            if ($num1 > 0) {
                foreach ($myrow1 as $row1) {
                    $output .= '<tr>
                                                                <input type="hidden" value="' . $row1[sub_id] . '" name="submid">
                                                                <td>' . $row1[code] . '</td>                                                        
                                                                <td>' . date("F j, Y", strtotime($row1[date_submitted])) . '</td>';
                    $strlen = strlen($row1["prop_ptitle"]);
                    if ($strlen > 50) {
                        $output .= '<td>' . substr($row1["prop_ptitle"], 0, 50) . '...</td>';
                    } else
                        $output .= '<td>' . substr($row1["prop_ptitle"], 0, 50) . '</td>';
                        $output .= '     <td><center>
                                                                <a data-id="' . $row1[sub_id] . '" data-toggle="modal" data-target="#add_data_Modal"  class="btn btn-info open-expidited">Expedited</a>
                                                                <button type="button" class="btn btn-warning" name="exempted">Exempted</button>
                                                                <button type="button" class="btn btn-danger" name="full">Full</button>
                                                                </center></td>    
                                                            </tr>';
                }
            }
        }
    }
}
else {
    $output .= '<tr><td colspan="4"><i><center>No new submissions yet.</center></i></td></tr>';
}
?><?php

$output .= '</table>';

?><?php
         
         }
//        $output .= '</table>';
 
//    if (mysqli_multi_query($connect, $query)) {
//            do {
//                if ($result = mysqli_store_result($connect)) {
//                    mysqli_free_result($result);
//                } else {
//                    
//                }
//            } while (mysqli_more_results($connect) && mysqli_next_result($connect));
//        }
//    
//    $query = mysqli_multi_query($connect, $query);
//    if($query){
//        $currDir = dirname(__FILE__);
//        include_once("$currDir/review_assignment.php");
//        
//    }

    echo $output;
//}

        
//    if (mysqli_query($connect, $query)) {
//        $output .= '<label class="text-success">Data Inserted</label>';
//        $select_query = "SELECT * FROM employee ORDER BY id DESC";
//        $result = mysqli_query($connect, $select_query);
//        $output .= '<table class="table table-bordered">  
//                    <tr>  
//                         <th width="70%">Employee Name</th>  
//                         <th width="30%">View</th>  
//                    </tr>';
//        while ($row = mysqli_fetch_array($result)) {
//            $output .= '<tr>  
//                         <td>' . $row["name"] . '</td>  
//                         <td><input type="button" name="view" value="view" id="' . $row["id"] . '" class="btn btn-info btn-xs view_data" /></td>  
//                    </tr>';
//        }
//        $output .= '</table>';
//    }
}
?>
