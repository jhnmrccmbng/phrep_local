<?php  
include "../config.php";
//select.php  
if(isset($_POST["sid"]))
{
    $output = '';
    $connect = mysqli_connect($dbServer, $dbUsername, $dbPassword, $dbDatabase);
    

    $query = "SELECT * FROM proposal_status a 
               INNER JOIN (SELECT MAX(id) as mid, sub_id FROM proposal_status WHERE sub_id = '" . $_POST["sid"] . "') b ON a.id = b.mid
               INNER JOIN proposal c ON a.sub_id = c.sub_id
               INNER JOIN phrepuser d ON c.username = d.id
               INNER JOIN proposal_status_action e ON a.status_action = e.id
               INNER JOIN review_type f ON c.sub_id = f.sub_id
               INNER JOIN (SELECT *, a.rt_duedate as rt FROM review_type_duedate a 
               INNER JOIN (SELECT MAX(id) as iddd FROM review_type_duedate GROUP BY subid) b ON a.id = b.iddd) g ON g.subid = c.sub_id";
    $result = mysqli_query($connect, $query);

    $numrows = mysqli_num_rows($result);

    if ($numrows > 0) {
        $output .= '<div class="table-responsive"><table class="table table-bordered table-condensed">';
        while ($row = mysqli_fetch_array($result)) {
            $output .= '
                           <div class="row">
                                <div class="col-lg-1"></div>
                               <div class="col-lg-3">
                                   <dl><center>
                                       <dt>Status</dt>
                                       <dd>' . $row["action_name"] . '</center></dd>
                                   </dl>
                               </div>
                               <div class="col-lg-4">
                                   <dl><center>
                                       <dt>Code</dt>
                                       <dd>' . $row["code"] . '</center></dd>
                                   </dl>
                               </div>
                               <div class="col-lg-3">
                                   <dl><center>
                                       <dt>Date Approved</dt>
                                       <dd>' . date("F d, Y", strtotime($row["status_date"])) . '</center></dd>
                                   </dl>
                               </div>
                                <div class="col-lg-1"></div>
                           </div>
                           <div class="row">
                               <div class="col-lg-12">
                                   <h3><center>"' . $row["prop_ptitle"] . '"<br>
                                   <small><strong>Author: </strong>' . $row["title"] . ' ' . $row["fname"] . ' ' . $row["mname"] . ' ' . $row["lname"] . ' | 
                                   ' . $row["institution"] . '</small></h3></center>

                               </div>
                           </div><hr>
                           <div class="row">
                               <div class="col-lg-1"></div>
                               <div class="col-lg-10">
                                   <dl>
                                       <dt><h4><strong>Background</strong></h4></dt>
                                       <dd>' . $row["prop_background"] . '</dd>
                                       <dt><h4><strong>Objectives</strong></h4></dt>
                                       <dd>' . $row["prop_obj"] . '</dd>
                                       <dt><h4><strong>Outcomes</strong></h4></dt>
                                       <dd>' . $row["prop_outcomes"] . '</dd>
                                   </dl>
                               </div>
                               <div class="col-lg-1"></div>
                           </div><hr><div class="list-group"><h3>List of Ethical Clearance(s)</h3>';
                    
                    $queryec = 'SELECT * FROM document WHERE sub_id = "'.$row["sub_id"].'" and kind = "EC"';
                    $result = mysqli_query($connect, $queryec);
                    if($result){
                        while ($rowec = mysqli_fetch_array($result)) {
                            $output .='<a href="'.$rowec["path"].'" class="list-group-item">'.$rowec["file_name"].'</a>';
                        }
                    }
            
            $output .= '</div><div class="row"><div class=col-lg-12></div><hr></row>';
        }
    }
    $output .= '</table></div>';
    echo $output;
}
?>