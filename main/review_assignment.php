<?php
include "sec_dashboard_action.php";
?>
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
$id3 = "reclist_id";
$id4 = "id";
$id5 = "secretary";
$id6 = "username";

$myrow = $obj->gettingMaxValueStatus("submission", "rec_list", "proposal", "phrepuser", "proposal_status", $id1, $id2, $id3, $id4, $id5, $id6, $mi['username']);
$num = count($myrow);
if ($num > 0) {
    foreach ($myrow as $row) {

        if ($row['sa'] == '1') {
            $idc = $row[sub_id];
            $id1 = "sub_id";
            $id3 = "reclist_id";
            $id4 = "id";
            $id5 = "secretary";
            $id6 = "username";
            $myrow1 = $obj->gettingProposalByStatus("submission", "rec_list", "proposal", "phrepuser", $id1, $id3, $id4, $id5, $id6, $mi['username'], $idc);
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

?>