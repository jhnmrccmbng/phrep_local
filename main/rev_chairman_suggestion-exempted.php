<?php
include "rev_dashboard_action.php";
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
if (!in_array($mi['group'], array('Admins', 'Reviewer'))) {
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
      body {font-family: 'Libre Franklin', sans-serif;}
      .tr_link{cursor:pointer}
      .namerts{font-size: 12px;}
    </style>
  </head>
  <body>
      <form action = "rev_dashboard_action.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">
        <?php
        $where = array("username" => $mi['username']);
        $getUserID = $obj->getUser("phrepuser", $where);
        if ($getUserID) {
            foreach ($getUserID as $user) {
                echo '<input type="hidden" class="form-control" value="' . $user['id'] . '" name="userid" id="userid">';
                $userid = $user['id'];
            }
        }
        ?>
        <?php
        $getchairman1 = $obj->gettingChairman($userid);
        if ($getchairman1 != null) {
            foreach ($getchairman1 as $gcm) {
                $cmname = $gcm['cid'];
            }
        }
        ?>
          
        <div class="container-fluid">
        <?php $id = (int) $_GET['id'];?>
        <?php $str = (int) $_GET['str'];?>
            <input type="hidden" value="<?php echo $mi['username']; ?>" name="username">
            <input type="hidden" value="<?php echo $id; ?>" name="subid">
            <input type="hidden" value="<?php echo $str; ?>" name="str">
            
            
            <div class="row">
                <div class="col-lg-8">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <?php 

                            if($_GET['str'] == '2'){
                                $review = "Exempted";     
                                $inst = "Please input your reasons for this exemption.";                           
                            }
                            
                            $where = array("sub_id" => $id);
                            $titlep = $obj->fetch_record_with_where("proposal",$where);
                            foreach($titlep as $tp){
                                $title = $tp['prop_ptitle'];
                            }
                            ?>
                            <h2><center><small><?php echo $review;?><br></small><?php echo $title;?></center></h2>
                            <hr><p><?php echo $inst; ?></p>

                            <textarea class="form-control" id="exemption_reason" name="exemption_reason" rows="8"></textarea>

                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-warning btn-lg btn-block" name="submit_reasons">Exempt</button>
                    <button type="button" class="btn btn-default btn-lg btn-block" onclick='goBack()'>Cancel</button>
                    
                </div>
                <div class="col-lg-4">            
                <h1>Files</h1><hr>
                    <input type="hidden" class="form-control" value="<?php echo $id; ?>" name="sub_id">
                    <div class="col-lg-12">
                    
                        <div class="panel panel-default">
                            <div class="panel-heading">
                              <h4 class="panel-title">Attachments</h4>
                            </div>
                            <div class="panel-body">
                              <table class="table table-striped">
                                <tr>
                                    <th>FILE TYPE</th>
                                    <th></th>
                                </tr>
                                
                                <?php
                                $getmaxbatch = $obj->getmaxbatch($id);
                                for($i = 1; $i <= $getmaxbatch; $i++){
                                    echo '<tr><td class="warning" colspan="2"><strong>'.$obj->ordinalize($i).' SUBMISSION</strong></td></tr>';
                                    $getdocbybatch = $obj->getdocbybatch($id, $i);
                                    if($getdocbybatch){
                                        foreach($getdocbybatch as $b){
                                            if($b['doctype'] == '1'){
                                                if($b['newsubmit'] == '1'){$n = "<span class='badge'>New</span>";} else{$n = "";}
                                                if($b['finaldoc'] == '1'){$f = "<span class='badge' data-toggle='tooltip' title='FINALDOC'><span class='glyphicon glyphicon-pushpin' aria-hidden='true'></span></span>";} else{$f = "";}
                                                echo '<tr>
                                                        <td>
                                                            '.$b['doctype_desc'].' <span class="badge"><span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span></span> ('.$b['doctypetimes'].') '.$n.$f.'
                                                                <br><small class="filename">'.$b['file_name'].' | '.$obj->ordinalize($b['revision']).' version <br>'; ?>
                                                                    <?php $d = strtotime($b['date_uploaded']);   echo date("M j, Y",$d); ?><?php
                                                                    echo '</small>
                                                        </td>';?>
                                                    <?php
                                                    echo'<td><center>
                                                        <input name="dlfile" type="hidden" value="'.$b['file_name'].'">
                                                        <a class="btn btn-success" href="'.$b['path'].'" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
                                                        <a target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/'.$b['file_name'].'&embedded=true" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>    
                                                        </center>
                                                        </td>
                                                    </tr>';
                                                
                                                
                                            }
                                            else{
                                                if($b['newsubmit'] == '1'){$n = "<span class='badge'>New</span>";} else{$n = "";}
                                                if($b['finaldoc'] == '1'){$f = "<span class='badge' data-toggle='tooltip' title='FINALDOC'><span class='glyphicon glyphicon-pushpin' aria-hidden='true'></span></span>";} else{$f = "";}
                                                echo '<tr>
                                                        <td>
                                                            '.$b['doctype_desc'].' ('.$b['doctypetimes'].') '.$n.$f.'
                                                                <br><small class="filename">'.$b['file_name'].' | '.$obj->ordinalize($b['revision']).' version <br>'; ?>
                                                                    <?php $d = strtotime($b['date_uploaded']);   echo date("M j, Y",$d); ?><?php
                                                                    echo '</small>
                                                        </td>';?>
                                                    <?php
                                                    echo'<td><center>
                                                        <input name="dlfile" type="hidden" value="'.$b['file_name'].'">
                                                        <a class="btn btn-success" href="'.$b['path'].'" role="button"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>
                                                        <a target = "_blank" href="https://docs.google.com/gview?url=http://phrep.pchrd.dost.gov.ph/main/uploads/main/'.$b['file_name'].'&embedded=true" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>    
                                                        </center>
                                                        </td>
                                                    </tr>';
                                            }
                                        }
                                    }
                                    
                                }
                                
                                ?>
                            </table>
                            </div>
                          </div>
                    </div>      
                </div>
                
                <!-- <div class="col-lg-6">
                    <?php
                    // $where = array("phrepuser_id" => $userid);
                    // $getrec = $obj->fetch_record_with_where("rec_groups", $where);
                    // foreach($getrec as $recc){$rec = $recc['rec_list_id'];}
                    
                    ?>
                    
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h2><center>Reviewer's Assignment</center></h2>
                            
                        </div>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Name</th>
                                    <th class="text-center"># of Assigned Proposals</th>
                                </tr>
                                <?php
                                // $where = array("rec_list_id" => $rec);
                                // $getrevs = $obj->fetch_record_with_where("rec_groups", $where);
                                // foreach($getrevs as $revs){
                                //     echo "<tr>";
                                //     echo "<td>";
                                //     $where = array("id" => $revs['phrepuser_id']);
                                //     $getname = $obj->fetch_record_with_where("phrepuser", $where);
                                //     foreach($getname as $n){
                                //         echo $n['title'].' '.$n['fname'].' '.$n['mname'].' '.$n['lname'];
                                //     }
                                //     echo "</td>";
                                //     echo "<td class='text-center'>";
                                //     echo "<a href='#' class='view_data' uid='".$revs['phrepuser_id']."'>".$obj->getcountprop($revs['phrepuser_id'])."</a>";
                                //     echo "</td>";
                                //     echo "</tr>";
                                // }
                                
                                ?>
                                
                                
                            </table>
                    </div>
                    
                </div> -->
            </div> 
        </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
     <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
     
    </form>
  </body>
  
</html>

<?php 
include_once("$currDir/footer.php");
?>

<!-- <div class="modal fade" id="confirm-suggestions" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Warning!</h3>
            </div>
            <div class="modal-body">
                Are you sure you want ADD DOCUMENT TYPE? 
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div> -->

<!-- <script>
$('#confirm-suggestions').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});

var checkBoxes = $('tbody #rev');
checkBoxes.change(function () {
    $('#revButton').prop('disabled', checkBoxes.filter(':checked').length < 1);
});
$('tbody #rev').change();
</script> -->

<!-- <script>
 $(document).on('click', '.view_data', function(){
//  $('#dataModal').modal();
  var userid = $(this).attr("uid");
  $.ajax({
   url:"selectassignedprop.php",
   method:"POST",
   data:{uid:userid},
   success:function(data){
    $('#proposal_detail').html(data);
    $('#dataModal').modal('show');
   }
  });
 });
</script> -->


<!-- <div id="dataModal" class="modal fade">
    <div class="modal-dialog">
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
</div> -->

<script>
CKEDITOR.replace( 'exemption_reason' );
</script>