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
      } .tr_link{cursor:pointer}</style>
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
                          $recid = $rec['id'];
                      }
                      ?>
                  </h2>
              </div>
          </div>
          <hr>
          <div class="row">
              <div class="col-lg-2">
                  <h2>Dashboard</h2>
              </div>
              <div class="col-lg-10">
                  <h2>List of Suggestions</h2>
              </div>
          </div>
          
          <div class="row">
              <div class="col-lg-2">
                  <?php include("$currDir/main/sec_dashboard_pane.php"); ?>                  
              </div>
              
              
              <div class="col-lg-10">
                  <table class="table table-bordered table-condensed">
                      <tr>
                          <th>Code</th>
                          <th>Proposal</th>
                          <th>Suggested</th>
                          <th>Action</th>
                      </tr>
                      <?php 
                      $getsuggest = $obj->getsuggestions($recid);
                      if($getsuggest){
                          foreach($getsuggest as $sug){
                              if($sug['seen']=='0'){                                 
                              
                              echo '<tr>';
                              echo '<td>'.$sug['code'].'</td>';
                              
                              $strlen = strlen($sug['prop_ptitle']);
                                if ($strlen>50){echo'<td>'.substr($sug['prop_ptitle'], 0, 50).'...</td>';}
                                else {echo'<td>'.substr($sug['prop_ptitle'], 0, 50).'</td>';} 
                              
                              echo '<td>'.$sug['rt_name'].'</td>';
                              echo '<td>';?>
                                <a class="btn btn-primary btn-xs" href="#" data-href="sec_dashboard_action.php?deletert=1&id=<?php echo $sug['subid'];?>&u=<?php echo $userid;?>&r=<?php echo $sug['mxr'];?>" data-toggle="modal" data-target="#confirm-delete">Change</a> |
                                <a class="btn btn-danger btn-xs" href="#" data-href="?deletert=1&id=<?php echo $sug['subid'];?>&u=<?php echo $userid;?>" data-toggle="modal" data-target="#confirm-delete">Ignore</a>
                              <?php echo '</td></tr>';
                              }
                              else{
                                  echo '<tr>';
                                  echo '<tr><td colspan="4"><i><center>No suggestions has been raised by reviewers</center></i></td></tr>';
                                  echo '</tr>';
                              }
                          }
                      }
                      ?>
                  </table>
                  
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
              <div class="modal-dialog">
               <div class="modal-content">
                <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title">Proposal</h4>
                </div>
                <div class="modal-body" id="proposal_detail">

                </div>
                <div class="modal-footer">
                 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
               </div>
              </div>
            </div>

    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1>WARNING!</h1>
                </div>
                <div class="modal-body">
                    This is to delete the assigned reviewer and will revert back to assigning review type and reviewers. 
                    This will notify reviewers that you have changed its review type. Please proceed by 
                    clicking "Delete", otherwise, "Cancel". Would you confirm to delete
                    what has been assigned? 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger btn-ok">Delete</a>
                </div>
            </div>
        </div>
    </div>

<?php 
include_once("$currDir/footer.php");
?>


<script>
$('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>

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
   url:"sec_selectproposal.php",
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
var u = document.getElementById("userid").value;
$("#idp").attr("href", "sec_personal_info.php?id="+u);
</script>
