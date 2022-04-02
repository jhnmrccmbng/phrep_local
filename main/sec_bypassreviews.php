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
          <?php $id = (int) $_GET['i'];?>
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
              <div class="col-lg-10">
                    <?php 
                    $gttitle = array("sub_id" => $id);
                    $gett = $obj->fetch_record_with_where("proposal", $gttitle);
                    foreach($gett as $t){
                        echo "<h2>".$t['prop_ptitle']."</h2>";
                    }
                    ?>
                    <ul class="nav nav-tabs" id="myTab">
                        <li class="active"><a href="#eval">Evaluation</a></li>
                        <li><a href="#suggest">Recommendation</a></li>
                    </ul>
                
                
              <div class="tab-content">

              <!--  start of NEW  -->
              <div id="eval" class="tab-pane fade in active">

                        <h3>Evaluations</h3>
                        <ol>
                          <?php 
                          $getbypassrev = $obj->getpassrev($id);
                          if ($getbypassrev) {
                              foreach($getbypassrev as $p){
                                
                                  echo '<li>';
                                  echo $p['title'].' '.$p['fname'].' '.$p['mname'].' '.$p['lname'].' - <a href="form1.php?id='.$p['sub_id'].'&idu='.$p['id'].'&et='.$p['rft_id'].'">'.$p['rft_desc'].'</a>';
                                  echo '</li>';                            
                              }


                          } else {
                              echo '';
                          }
                          ?>                  
                        </ol>
                        <h3>Comments</h3>
                        <ol>
                          <?php 
                          $getmaxr = $obj->getmaxreviewer($id);
                          ?>

                          <?php 
                          $getbypassrev = $obj->getpasscomment($id);
                          if ($getbypassrev) {
                              foreach($getbypassrev as $p){
                                  echo '<li>';
                                  echo $p['title'].' '.$p['fname'].' '.$p['mname'].' '.$p['lname'].' - <a href="form2.php?id='.$p['sub_id'].'&idu='.$p['id'].'&r='.$p['version'].'">'.$obj->ordinalize($p['version']).' Evaluation</a>';
                                  echo '</li>';                            
                              }


                          } else {
                              echo '';
                          }
                          ?>                  
                        </ol>

              </div>
              
              <!--  start of NEW  -->
              <div id="suggest" class="tab-pane fade">
                  
                        <h3>Recommendations</h3>
                        <!-- List group -->
                        <ul class="list-group">
                            <?php
                            $getmaxcol = $obj->getmaxcol($id);
                            
                            for($i=1; $i<=$getmaxcol; $i++){
                                $getsuggestions = $obj->getsuggestioncol($id,$i);
                                foreach($getsuggestions as $s){
                                    echo '<li class="list-group-item">';
                                    echo '<h4>'.$obj->ordinalize($s['rev'])." Recommendation</h4><br>";
                                    echo $s['collated_desc'];
                                    echo '</li>';
                                }
                            }
                            
                            ?>
                        </ul>
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

<script>
var u = document.getElementById("userid").value;
$("#idp").attr("href", "sec_personal_info.php?id="+u);

$("#subm").attr("href", "sec_dashboard_active.php#review");
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

<?php 
include_once("$currDir/footer.php");
?>
