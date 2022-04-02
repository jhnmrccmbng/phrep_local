<?php
include "sec_dashboard_action.php";
date_default_timezone_set('Asia/Manila');
$datetime = date("Y-m-d H:i:s",strtotime("now"));

?>
<?php
	$currDir = dirname(__FILE__);
        $currDir = substr($currDir, 0, -5);
//        echo $currDir;
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	
	/* grant access to the groups 'Admins' and 'Data entry' */
	$mi = getMemberInfo();
	if(!in_array($mi['group'], array('Admins', 'Secretary'))){
            header("location: ../index.php?signIn=1");
//		exit;
	}

       
include_once("$currDir/header.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Dashboard</title>

    <!-- Bootstrap -->
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Libre+Franklin" rel="stylesheet">
    <link rel="stylesheet" href="../resources/select2/select2.css">

    <style>
      body {
        font-family: 'Libre Franklin', sans-serif;
      } .tr_link{cursor:pointer}
      .font-rev {font-size: 8pt;}
      .prime-submitted{font-size: 8pt; color: green; font-weight: bolder;}
      .underline {text-decoration: underline;}
      
    @media screen and (max-width: 1020px) {
            .nav {
                padding-left:2px;
                padding-right:2px;
            }
            .nav li {
                display:block !important;
                width:100%;
                margin:0px;
            }
            .nav li.active {
                border-bottom:1px solid #ddd!important;
                margin: 0px;
            }
        }
    
    </style>
  </head>
  <body>
      <form action = "sec_dashboard_action.php?" method = "POST" enctype = "multipart/form-data" class="form-horizontal">
          <?php 
                    $where = array("username" => $mi['username']);
                    $getUserID = $obj->getUser("phrepuser", $where);
                    if($getUserID){
                        foreach($getUserID as $user){
                            echo '<input type="hidden" class="form-control" value="'.$user['id'].'" name="userid" id="userid">';
                            $userid = $user['id'];
                        }
                    }
                    ?>
          
      <div class="container-fluid">
          <?php $id = (int) $_GET['id'];?>
          <input type="hidden" value="<?php echo $mi['username']; ?>" name="username">
          
          <div class="row">
              <div class="col-lg-12">
                  <h2>
                      <?php
                      $where = array("secretary" => $userid);
                      $getRECname = $obj->fetch_record("rec_list", $where);
                      foreach($getRECname as $rec){
                          echo $rec['erc_name'];
                      }
                      ?>
                  </h2>
              </div>
          </div>
          <hr>          
          <div class="row">
              <div class="col-lg-2">
                  <h2>Dashboard</h2>
                  <?php include("$currDir/main/sec_dashboard_pane.php"); ?>
                  
              </div>
              
              <div class="col-lg-10 tab-content">





                          <!-- start of new approval -->
                                                    <!--start of approved-->
                          <div id="newapproved" class="tab-pane fade in active">
                              <div class="panel panel-success">
                              <!-- Default panel contents -->
                              <div class="panel-heading">

                                <h5>P R E - Total number of proposals  

                                <?php 

                                if($_GET['status'] == 'underwentfullreview'){

                                  $statusName = 'Underwent Full Review';

                                }else if($_GET['status'] == 'underwentexemptedreview'){

                                  $statusName = 'Underwent Exempted Review';

                                }else if($_GET['status'] == 'underwentexpeditedreview'){

                                  $statusName = 'Underwent Expedited Review';
                                
                                }else if($_GET['status'] == 'submitted'){

                                  $statusName = 'Submitted';
                                
                                }else if($_GET['status'] == 'accepted'){

                                  $statusName = 'Accepted';
                                
                                }else if($_GET['status'] == 'approved'){

                                  $statusName = 'Approved';
                                
                                }else if($_GET['status'] == 'disapproved'){

                                  $statusName = 'Disapproved';
                                
                                }else if($_GET['status'] == 'revision'){

                                  $statusName = 'Revision';
                                }
                                              
                                  echo '<b>'.$statusName.'</b> in year '.$_GET['year'];  

                                  $year = $_GET['year']; 
                                  $status = $_GET['status'];

                                  $getmaxdoc = $obj->getMaxDocumentStat_new($userid, $year, $status);

                                  $getmaxdoc != null ? $countresult = count($getmaxdoc) : $countresult = '0';

                                  echo ' | '.$countresult.' results found.';
                                ?>
                                
                                <a href="sec_dashboard_active.php?#reviewers" class="pull-right" data-toggle="tooltip" title="Back"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span></a>

                                </h5>

                               
                              </div>

                              <table class="table table-condensed table-bordered table-hover">
                                 
                                 <tr>
                                  <th><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></th>
                                  
                                  <th>ID</th>
                                  <th>REVIEW TYPE</th>
                                  <th>TITLE</th>
                                  <th><center><span class="glyphicon glyphicon-option-horizontal" aria-hidden="true"></span></center></th>
                                  </tr>

                                  <?php
                                  
                                  
                                    if($getmaxdoc == ''){

                                        echo '<tr><td colspan="4"><center>No proposal has been reviewed yet.</center></td></tr>';

                                    }
                                    else{

                                        $countID = 1;

                                        foreach($getmaxdoc as $id => $prop){

//                                            if(($subm['pa_status'] == "onreview")||$subm['pa_status'] == null){
                                                if(($prop['status_action'] == '6')){
                                                //CODE
                                                $whereps = array("sub_id" => $prop['sub_id']);

                                                $getcodd = $obj->fetch_record_with_where("submission", $whereps);

                                                foreach ($getcodd as $cc) {
                                                    if ($cc['coding'] == "") {
                                                        $wherepp = array("sub_id" => $prop['sub_id']);
                                                        $getcoddd = $obj->fetch_record_with_where("proposal", $wherepp);
                                                        foreach ($getcoddd as $vv) {
                                                            $code = $vv['code'];
                                                        }
                                                    } else {
                                                        $code = $cc['coding'];
                                                    }
                                                }
                                                //CODE
                                                    echo '<tr>';//1
                                                    echo '<td>';
                                                    echo '<a class="btn btn-default btn-xs" href="sec_sendemail.php?id='.$prop['sub_id'].'" role="button" data-toggle="tooltip" title="Request for Report">';
                                                    echo '<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> Email';
                                                    echo '</td></a>';   
                                                    echo '<td>'.$code.'</td>';                                                        

                                                    echo '<td>'.$prop['rt_name'].'</td>';

                                                    $uc = ucwords($prop['prop_ptitle']);
                                                    $strlen = strlen($prop['prop_ptitle']);
                                                    if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
                                                    else {echo'<td>'.substr($uc, 0, 40).'</td>'; }  
                                                    
                                                    echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></center></td>';
                                                    echo '</tr>';
                                                }
                                                
                                                else if(($prop['status_action'] == '23')){
                                                //CODE
                                                $whereps = array("sub_id" => $prop['sub_id']);
                                                $getcodd = $obj->fetch_record_with_where("submission", $whereps);
                                                foreach ($getcodd as $cc) {
                                                    if ($cc['coding'] == "") {
                                                        $wherepp = array("sub_id" => $prop['sub_id']);
                                                        $getcoddd = $obj->fetch_record_with_where("proposal", $wherepp);
                                                        foreach ($getcoddd as $vv) {
                                                            $code = $vv['code'];
                                                        }
                                                    } else {
                                                        $code = $cc['coding'];
                                                    }
                                                }
                                                //CODE
                                                    echo '<tr>';//1
                                                    echo '<td><button class="btn btn-default btn-xs" type="button">DONE</button></center></td></td>';   
                                                    echo '<td>'.$code.'</td>';                                                        

                                                    echo '<td>'.$prop['rt_name'].'</td>';

                                                    $uc = ucwords($prop['prop_ptitle']);
                                                    $strlen = strlen($prop['prop_ptitle']);
                                                    if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
                                                    else {echo'<td>'.substr($uc, 0, 40).'</td>'; }  
                                                    
                                                    echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a>';
                                                    echo '</tr>';
                                                }
                                                
                                                
                                                
                                                else if($prop['status_action'] == '12'){
                                                    //CODE
                                                $whereps = array("sub_id" => $prop['sub_id']);
                                                $getcodd = $obj->fetch_record_with_where("submission", $whereps);
                                                foreach ($getcodd as $cc) {
                                                    if ($cc['coding'] == "") {
                                                        $wherepp = array("sub_id" => $prop['sub_id']);
                                                        $getcoddd = $obj->fetch_record_with_where("proposal", $wherepp);
                                                        foreach ($getcoddd as $vv) {
                                                            $code = $vv['code'];
                                                        }
                                                    } else {
                                                        $code = $cc['coding'];
                                                    }
                                                }
                                                //CODE
                                                    echo '<tr>';//1
                                                    echo '<td><span class="glyphicon glyphicon-ok" aria-hidden="true" data-toggle="tooltip" title="EXEMPTED"></span></td>';   
                                                    echo '<td>'.$code.'</td>';                                                        

                                                    echo '<td>'.$prop['rt_name'].'</td>';

                                                    $uc = ucwords($prop['prop_ptitle']);
                                                    $strlen = strlen($prop['prop_ptitle']);
                                                    if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
                                                    else {echo'<td>'.substr($uc, 0, 40).'</td>'; }  
                                                    echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></center></td>';
                                                    echo '</tr>';
                                                

                                                // added by JM
                                                }else if($_GET['year'] != 0000){


                                                $whereps = array("sub_id" => $prop['sub_id']);
                                                $getcodd = $obj->fetch_record_with_where("proposal", $whereps);

                                                foreach ($getcodd as $cc) {

                                                    if ($cc['coding'] == "") {
                                                        $wherepp = array("sub_id" => $prop['sub_id']);
                                                        $getcoddd = $obj->fetch_record_with_where("proposal", $wherepp);
                                                        foreach ($getcoddd as $vv) {
                                                            $code = $vv['code'];
                                                        }
                                                    
                                                    } else {
                                                        $code = $cc['coding'];
                                                    }
                                                }


                                                 echo '<tr>';//1

                                                    echo "<td><span aria-hidden=\"true\" data-toggle=\"tooltip\" title=\"Numbering\"></span>".($id + 1)."</td>";   

                                                    echo '<td>'.$code.'</td>';                                                        

                                                    echo '<td>'.$prop['rt_name'].'</td>';

                                                    $uc = ucwords($prop['prop_ptitle']);
                                                    $strlen = strlen($prop['prop_ptitle']);
                                                    if ($strlen>50){echo'<td>'.substr($uc, 0, 40).'...</td>';}
                                                    else {echo'<td>'.substr($uc, 0, 40).'</td>'; }  
                                                    echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></center></td>';
                                                    echo '</tr>';


                                                }else{



                                                $whereps = array("sub_id" => $prop['sub_id']);

                                                $getcodd = $obj->fetch_record_with_where("proposal", $whereps);

                                                foreach ($getcodd as $id => $cc) {

                                                    if ($cc['coding'] == "") {
                                                        $wherepp = array("sub_id" => $prop['sub_id']);
                                                        $getcoddd = $obj->fetch_record_with_where("proposal", $wherepp);
                                                        foreach ($getcoddd as $vv) {
                                                            $code = $vv['code'];
                                                        }
                                                    
                                                    } else {
                                                        $code = $cc['coding'];
                                                    }
                                                }


                                                 echo "<td><span aria-hidden=\"true\" data-toggle=\"tooltip\" title=\"Numbering\"></span>".($id + 1)."</td>";  

                                                    $uc = ucwords($prop['prop_ptitle']);
                                                    $strlen = strlen($prop['prop_ptitle']);

                                                    echo'<td>'.$prop['prop_ptitle'].'</td>';

                                                    // echo '<td><center><a class="btn btn-primary btn-xs" href="proposal_info.php?id='.$prop['sub_id'].'" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></center></td>';

                                                    echo '</tr>';


                                                }
                                                
//                                            }
                                        }       
                                    }



                                  ?>
 
                              </table>


                            </div>
                          </div>






























                     


                              
                          </div>
                          
              </div>
          </div>
   
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
     <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
     
    </form>
  </body>
  
</html>

<div id="dataModal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><center>Information</center></h4>
            </div>
            <div class="modal-body" id="proposal_detail">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-exempted" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1>REMINDER!</h1>
            </div>
            <div class="modal-body">
                This submission will be immediately APPROVE without assigning to reviewers.
                Are you sure you want to tag this submission as "Exempted Review"? 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-warning btn-ok">Okay</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-expedited" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1>REMINDER!</h1>
            </div>
            <div class="modal-body">
                Are you sure you want to assign this to an Expedited Review? 
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-full" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>REMINDER!</h3>
            </div>
            <div class="modal-body">
                Are you sure you want to assign this to a Full Review?  
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>


<script>
$('tr[data-href]').on("click", function() {
    document.location = $(this).data('href');
});

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});

</script>

<script>
 $(document).on('click', '.view_data', function(){
//  $('#dataModal').modal();
  var subid = $(this).attr("id");
  $.ajax({
   url:"selectproposal.php",
   method:"POST",
   data:{sid:subid},
   success:function(data){
    $('#proposal_detail').html(data);
    $('#dataModal').modal('show');
   }
  });
 });
</script>

<script>
$('#myTab a').click(function(e) {
  e.preventDefault();
  $(this).tab('show');
  history.pushState( null, null, $(this).attr('href') );
});

// store the currently selected tab in the hash value
$("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
  var id = $(e.target).attr("href").substr(1);
  window.location.hash = id;
});

// on load of the page: switch to the currently selected tab
var hash = window.location.hash;
$('#myTab a[href="' + hash + '"]').tab('show');
</script>

<script>
$('#confirm-exempted').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});

$('#confirm-expedited').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});

$('#confirm-full').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});

var u = document.getElementById("userid").value;
$("#idp").attr("href", "sec_personal_info.php?id="+u);
</script>

<script>
 $(document).on('click', '.view_review', function(){
//  $('#dataModal').modal();
  var userid = $(this).attr("uid");
  $.ajax({
   url:"selectassignedprop.php",
   method:"POST",
   data:{uid:userid},
   success:function(data){
    $('#proposal_details').html(data);
    $('#dataModals').modal('show');
   }
  });
 });
</script>


<div id="dataModals" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><center>Information</center></h4>
            </div>
            <div class="modal-body" id="proposal_details">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $("tr .clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});
</script>

<?php 
include_once("$currDir/footer.php");
?>