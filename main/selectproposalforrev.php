<?php  
include "../config.php";
date_default_timezone_set('Asia/Manila');
//select.php  
if(isset($_POST["sid"]))
{
 $output = '';
 $connect = mysqli_connect($dbServer, $dbUsername , $dbPassword, $dbDatabase);
 
                $query = "SELECT *, a.status_action as st FROM proposal_status a 
                       INNER JOIN (SELECT MAX(id) as mid, sub_id, status_username, status_action FROM proposal_status WHERE sub_id = '".$_POST["sid"]."') b ON a.id = b.mid
                       INNER JOIN proposal c ON a.sub_id = c.sub_id
                       INNER JOIN phrepuser d ON c.username = d.id
                       INNER JOIN proposal_status_action e ON a.status_action = e.id
                       INNER JOIN review_type f ON c.sub_id = f.sub_id
                       INNER JOIN (SELECT * FROM review_type_duedate a 
                                    INNER JOIN (SELECT MAX(id) as iddd FROM review_type_duedate GROUP BY subid) b ON a.id = b.iddd) g ON g.subid = c.sub_id
                       INNER JOIN rev_groups h ON f.sub_id = h.sub_id 
                       INNER JOIN (SELECT MAX(review) as mr FROM rev_groups WHERE phrepuser_id = '".$_POST["idu"]."' and sub_id = '".$_POST["sid"]."') i ON h.review = i.mr
                        WHERE h.phrepuser_id = '".$_POST["idu"]."'";
                $result = mysqli_query($connect, $query);
                
                if($result){
                    foreach ($result as $res){
                        if($res["st"] == '12'){
                            $output .= '  
                                 <div class="table-responsive">  
                                      <table class="table table-bordered table-condensed">';
                                $output .= '
                                       <div class="row">
                                            <div class="col-lg-1"></div>
                                           <div class="col-lg-3">
                                               <dl><center>
                                                   <dt>Status</dt>
                                                   <dd>'.$res["action_name"].'</center></dd>
                                               </dl>
                                           </div>
                                           <div class="col-lg-4">
                                               <dl><center>
                                                   <dt>Code</dt>
                                                   <dd>'.$res["code"].'</center></dd>
                                               </dl>
                                           </div>
                                           <div class="col-lg-3">
                                               <dl><center>
                                                   <dt>Date Exempted</dt>
                                                   <dd>'.date("F d, Y",strtotime($res["status_date"])).'</center></dd>
                                               </dl>
                                           </div>
                                            <div class="col-lg-1"></div>
                                       </div>
                                       <div class="row">
                                           <div class="col-lg-12">
                                               <h3><center>"'.$res["prop_ptitle"].'"<br>
                                               <small><strong>Author: </strong>'.$res["title"].' '.$res["fname"].' '.$res["mname"].' '.$res["lname"].' | 
                                               '.$res["institution"].'</small></h3></center>

                                           </div>
                                       </div><hr>
                                       <div class="row">
                                           <div class="col-lg-1"></div>
                                           <div class="col-lg-10">
                                               <dl>
                                                   <dt><h4><strong>Background</strong></h4></dt>
                                                   <dd>'.$res["prop_background"].'</dd>
                                                   <dt><h4><strong>Objectives</strong></h4></dt>
                                                   <dd>'.$res["prop_obj"].'</dd>
                                                   <dt><h4><strong>Outcomes</strong></h4></dt>
                                                   <dd>'.$res["prop_outcomes"].'</dd>
                                               </dl>
                                           </div>
                                           <div class="col-lg-1"></div>
                                       </div>';
                            
                        }
                        else if($res["st"] == '6'){
                            $output .= '  
                                 <div class="table-responsive">  
                                      <table class="table table-bordered table-condensed">';
                                $output .= '
                                       <div class="row">
                                            <div class="col-lg-1"></div>
                                           <div class="col-lg-3">
                                               <dl><center>
                                                   <dt>Status</dt>
                                                   <dd>'.$res["action_name"].'</center></dd>
                                               </dl>
                                           </div>
                                           <div class="col-lg-4">
                                               <dl><center>
                                                   <dt>Code</dt>
                                                   <dd>'.$res["code"].'</center></dd>
                                               </dl>
                                           </div>
                                           <div class="col-lg-3">
                                               <dl><center>
                                                   <dt>Date Approved</dt>
                                                   <dd>'.date("F d, Y",strtotime($res["status_date"])).'</center></dd>
                                               </dl>
                                           </div>
                                            <div class="col-lg-1"></div>
                                       </div>
                                       <div class="row">
                                           <div class="col-lg-12">
                                               <h3><center>"'.$res["prop_ptitle"].'"<br>
                                               <small><strong>Author: </strong>'.$res["title"].' '.$res["fname"].' '.$res["mname"].' '.$res["lname"].' | 
                                               '.$res["institution"].'</small></h3></center>

                                           </div>
                                       </div><hr>
                                       <div class="row">
                                           <div class="col-lg-1"></div>
                                           <div class="col-lg-10">
                                               <dl>
                                                   <dt><h4><strong>Background</strong></h4></dt>
                                                   <dd>'.$res["prop_background"].'</dd>
                                                   <dt><h4><strong>Objectives</strong></h4></dt>
                                                   <dd>'.$res["prop_obj"].'</dd>
                                                   <dt><h4><strong>Outcomes</strong></h4></dt>
                                                   <dd>'.$res["prop_outcomes"].'</dd>
                                               </dl>
                                           </div>
                                           <div class="col-lg-1"></div>
                                       </div>';
                            
                        }
                        else if($res["st"] == '3'){
                            $output .= '  
                                 <div class="table-responsive">  
                                      <table class="table table-bordered table-condensed">';
                                $output .= '
                                       <div class="row">
                                            <div class="col-lg-1"></div>
                                           <div class="col-lg-3">
                                               <dl><center>
                                                   <dt>Status</dt>
                                                   <dd>'.$res["action_name"].'</center></dd>
                                               </dl>
                                           </div>
                                           <div class="col-lg-4">
                                               <dl><center>
                                                   <dt>Code</dt>
                                                   <dd>'.$res["code"].'</center></dd>
                                               </dl>
                                           </div>
                                           <div class="col-lg-3">
                                               <dl><center>
                                                   <dt>Due Date</dt>
                                                   <dd>'.date("F d, Y",strtotime($res["rt_duedate"])).'</center></dd>
                                               </dl>
                                           </div>
                                            <div class="col-lg-1"></div>
                                       </div>
                                       <div class="row">
                                           <div class="col-lg-12">
                                               <h3><center>"'.$res["prop_ptitle"].'"<br>
                                               <small><strong>Author: </strong>'.$res["title"].' '.$res["fname"].' '.$res["mname"].' '.$res["lname"].' | 
                                               '.$res["institution"].'</small></h3></center>

                                           </div>
                                       </div><hr>
                                       <div class="row">
                                           <div class="col-lg-1"></div>
                                           <div class="col-lg-10">
                                               <dl>
                                                   <dt><h4><strong>Background</strong></h4></dt>
                                                   <dd>'.$res["prop_background"].'</dd>
                                                   <dt><h4><strong>Objectives</strong></h4></dt>
                                                   <dd>'.$res["prop_obj"].'</dd>
                                                   <dt><h4><strong>Outcomes</strong></h4></dt>
                                                   <dd>'.$res["prop_outcomes"].'</dd>
                                               </dl>
                                           </div>
                                           <div class="col-lg-1"></div>
                                       </div>';
                            
                        }
                    }
                }
                
                $output .= '  
                     <div class="table-responsive">  
                          <table class="table table-bordered table-condensed">';
                   while($row = mysqli_fetch_array($result))
                   {
                    $output .= '
                           <div class="row">
                                <div class="col-lg-1"></div>
                               <div class="col-lg-3">
                                   <dl><center>
                                       <dt>Status</dt>
                                       <dd>'.$row["action_name"].'</center></dd>
                                   </dl>
                               </div>
                               <div class="col-lg-4">
                                   <dl><center>
                                       <dt>Code</dt>
                                       <dd>'.$row["code"].'</center></dd>
                                   </dl>
                               </div>
                               <div class="col-lg-3">
                                   <dl><center>
                                       <dt>Evaluation Date</dt>
                                       <dd>'.date("F d, Y",$gt["eval_date"]).'</center></dd>
                                   </dl>
                               </div>
                                <div class="col-lg-1"></div>
                           </div>
                           <div class="row">
                               <div class="col-lg-12">
                                   <h3><center>"'.$row["prop_ptitle"].'"<br>
                                   <small><strong>Author: </strong>'.$row["title"].' '.$row["fname"].' '.$row["mname"].' '.$row["lname"].' | 
                                   '.$row["institution"].'</small></h3></center>

                               </div>
                           </div><hr>
                           <div class="row">
                               <div class="col-lg-1"></div>
                               <div class="col-lg-10">
                                   <dl>
                                       <dt><h4><strong>Background</strong></h4></dt>
                                       <dd>'.$row["prop_background"].'</dd>
                                       <dt><h4><strong>Objectives</strong></h4></dt>
                                       <dd>'.$row["prop_obj"].'</dd>
                                       <dt><h4><strong>Outcomes</strong></h4></dt>
                                       <dd>'.$row["prop_outcomes"].'</dd>
                                   </dl>
                               </div>
                               <div class="col-lg-1"></div>
                           </div>';
                   }
         
     
    $output .= '</table></div>';
    echo $output;
}
?>