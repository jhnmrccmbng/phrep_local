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
            if ($row["status_action"] == "6") {//KUNG APPROVED NA
                $a = strtotime($row["rt_duedate"]);
                $b = strtotime("now");
                $numDays = abs($b - $a) / 60 / 60 / 24;
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
                           </div>';
            } else if ($row["status_action"] == "3") {//KUNG ASSIGNED PA ANG PROPOSAL
                $a = strtotime($row["rt_duedate"]);
                $b = strtotime("now");
                $numDays = abs($b - $a) / 60 / 60 / 24;
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
                                       <dt>Due Date</dt>
                                       <dd>' . date("F d, Y", strtotime($row["rt_duedate"])) . ' <br> <i>' . ceil($numDays) . ' day(s) left</i></center></dd>
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
                           </div>';
            } else if ($row["status_action"] == "11") {//KUNG ASSIGNED PA ANG PROPOSAL
                $a = strtotime($row["rt_duedate"]);
                $b = strtotime("now");
                $numDays = abs($b - $a) / 60 / 60 / 24;
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
                                       <dt>Due Date</dt>
                                       <dd>' . date("F d, Y", strtotime($row["rt_duedate"])) . ' <br> <i>' . ceil($numDays) . ' day(s) left</i></center></dd>
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
                           </div>';
            }
        }
    } else {//KUNG ASSIGNED PA ANG PROPOSAL
        $query = "SELECT * FROM proposal_status a 
               INNER JOIN (SELECT MAX(id) as mid, sub_id FROM proposal_status WHERE sub_id = '" . $_POST["sid"] . "') b ON a.id = b.mid
               INNER JOIN proposal c ON a.sub_id = c.sub_id
               INNER JOIN phrepuser d ON c.username = d.id
               INNER JOIN proposal_status_action e ON a.status_action = e.id";
        $result = mysqli_query($connect, $query);
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
                                            <dt>Date Submitted</dt>
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
                                </div>';
        }
    }



    $output .= '</table></div>';
    echo $output;
}
?>