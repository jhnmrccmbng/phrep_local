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
          <?php 
                    $where = array("username" => $mi['username']);
                    $getUserID = $obj->getUser("phrepuser", $where);
                    if($getUserID){
                        foreach($getUserID as $user){
                            echo '<input type="hidden" class="form-control" value="'.$user['id'].'" name="userid">';
                            $userid = $user['id'];
                        }
                    }
                    ?>
          
      <div class="container-fluid">
          <?php $id = (int) $_GET['id'];?>
          <input type="hidden" value="<?php echo $mi['username']; ?>" name="username">
          <div class="row">
              <div class="col-lg-2"><h2>Dashboard</h2></div>
              <div class="col-lg-10"><h2>Decision</h2></div>
              
          </div>
          <div class="row">
              <div class="col-lg-2">
                  
                  <div class="row">
                      <div class="list-group">
                        <a href="#" class="list-group-item active">
                          Submissions
                        </a>
                        <a href="#" class="list-group-item">My Profile</a>
                        <a href="#" class="list-group-item">Messages</a>
                        <a href="#" class="list-group-item">Documents</a>
                        <a href="#" class="list-group-item">Certificates</a>
                      </div>
                  </div>
                  
              </div>
              <div class="col-lg-7">
                  <div class="row">
                      <div class="col-lg-12">
                                                    
                          <div class="panel panel-default">
                              
                                <table class="table table-bordered">
                                    <?php
                                        $where = array("sub_id" => $id);
                                        $proposal = $obj->fetch_record_with_where("proposal", $where);
                                        foreach($proposal as $p){
                                            $code = $p['code'];
                                            $title = $p['prop_ptitle'];

                                            $where = array("id" => $p['username'] );
                                            $user = $obj->fetch_record_with_where("phrepuser", $where);
                                            foreach($user as $u){
                                                $fname = $u['fname'];
                                                $mname = $u['mname'];
                                                $lname = $u['lname'];
                                            }
                                        }

                                            $code = $p['code'];
                                        echo '<tr><td><center><span class="glyphicon glyphicon-qrcode" aria-hidden="true"></span></center></td><td>'.$code.'</td></tr>';
                                        echo '<tr><td><center><span class="glyphicon glyphicon-file" aria-hidden="true"></span></center></td><td>'.$title.'</td></tr>';
                                        echo '<tr><td><center><span class="glyphicon glyphicon-user" aria-hidden="true"></span></center></td><td>'.$fname.' '.$mname.' '.$lname.'</td></tr>';

                                    ?>
                                    
                                </table>
                          </div>
                      </div>                        
                  </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Chairman's Reason for Exemption</strong> 
                                    <span class="pull-right"><a class="btn btn-primary btn-xs" href="form3.php?id=<?php echo $id;?>&et=1" target="_blank" role="button">
                                    <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> View</a></span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                              <form action = "sec_dashboard_action.php" method = "POST" enctype = "multipart/form-data" class="form">
                                  <h4>Attached Decision Letter:</h4>       
                                            <?php 
                                                $where = array("username" => $mi['username']);
                                                $getUserID = $obj->getUser("phrepuser", $where);
                                                if($getUserID){
                                                    foreach($getUserID as $user){
                                                        echo '<input type="hidden" class="form-control" value="'.$user['id'].'" name="userid">';
                                                        $userid = $user['id'];
                                                    }
                                                }
                                            ?>
                                            <?php
                                            date_default_timezone_set('Asia/Manila');
                                            $date = date("mdyHis", strtotime("now"));
                                            ?>
                                            <input id="times" name="times" type="hidden" value="1" placeholder="" class="form-control input-md">
                                            <input id="docname" name="docname" type="hidden" value="LetterforExemption" placeholder="" class="form-control input-md">
                                            <input id="date" name="date" type="hidden" value="<?php $date;?>" placeholder="" class="form-control input-md">
                                            <input id="doctype" name="doctype" type="hidden" value="59" placeholder="" class="form-control input-md">
                                            <input id="submid" name="submid" type="hidden" value="<?php echo $_GET['id'];?>" placeholder="" class="form-control input-md">
                                            <input id="kind" name="kind" type="hidden" value="LoE" placeholder="" class="form-control input-md">
                                            <?php $actual_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].""; ?>
                                            <input id="urllink" name="urllink" type="hidden" value="<?php echo $actual_link;?>" placeholder="" class="form-control input-md">
                                            <input id="username" name="username" type="hidden" value="<?php echo $mi['username'];?>" placeholder="" class="form-control input-md">
                                            
                                            <?php
                                            $getmaxec = $obj->getMaxRevisionEC($id);
                                            echo '<input name="revision" type="hidden" value="'.$getmaxec.'">';
                                            ?>
                                            
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <span class="btn btn-default btn-file">
                                                    <input id="attach" type = "file" name = "image" />  
                                                    </span>                      
                                                    <input id="attached" type = "submit" name="clearance" class="btn btn-primary"/>
                                                    <p class="help-block">Just re-upload when you want to change current upload file.</p>
                                                </div>
                                            </div>
                                        </form>
                                
                                <?php 
                                $where = array("sub_id" => $id, "doctype" => "59", "kind" => "LoE", "finaldoc" => "1");
                                $getclerance = $obj->fetch_record_with_where("document", $where);
                                if($getclerance){
                                    foreach($getclerance as $clear){
                                        echo '<div class="alert alert-success" role="alert">'; 
                                       echo '<span class="glyphicon glyphicon-file" aria-hidden="true"></span> '.$clear['file_name'].'</div>';
                                       echo ' <input id="ecpath" name="ecpath" type="hidden" value="'.$clear['path'].'">';
                                       $ecpath = $clear['path'];
                                    }
                                }
                                
                                ?>
                                
                                <hr>
                                <form role="form" method="POST" action = "sec_dashboard_action.php">       
                                                                
                                        <input id="ecpath" type="hidden" name="ecpath" value="<?php echo $ecpath; ?>">
                                        <input id="submid" type="hidden" name="submid" value="<?php echo $id; ?>">
                                        <input id="userid" type="hidden" name="userid" value="<?php echo $userid; ?>">
                                        
                                        <?php
                                            $getDocOwner = $obj->getInfo($id);
                                            if($getDocOwner){
                                                foreach($getDocOwner as $owner){
                                                    $email = $owner['email'];
                                                    $name = $owner['fname'].' '.$owner['mname'].' '.$owner['lname'];
                                                }
                                            }
                                        ?>
                                        
                                        <input id="email" type="hidden" name="email" value="<?php echo $email; ?>">
                                        <input id="subject" type="hidden" name="subject" value="YOUR PROPOSAL HAS BEEN APPROVED!">
                                        <input id="oname" type="hidden" name="oname" value="<?php echo $name; ?>">
                                        
                                        <?php
                                            $getSecInfo = $obj->getSecInfo($userid);
                                            if($getSecInfo){
                                                foreach($getSecInfo as $sec){
                                                    $secemail = $sec['email'];
                                                    $secname = $sec['fname'].' '.$sec['mname'].' '.$sec['lname'];
                                                }
                                            }
                                        ?>
                                        <?php 
                                            $getfileid = $obj->getFileID($id,"1", "MP");
                                            if($getfileid>0){
                                                foreach($getfileid as $fieldid){
                                                    echo '<input id="docid" type="hidden" name="docid[]" value="'.$fieldid['file_id'].'">';
                                                }
                                            }
                                            
                                        
                                        ?>
                                        
                                        <?php 
                                            $getfileid = $obj->getARF($id, "ARF");
                                            if($getfileid>0){
                                                foreach($getfileid as $fieldid){
                                                    echo '<input id="docid" type="hidden" name="arf[]" value="'.$fieldid['file_id'].'">';
                                                }
                                            }
                                            
                                        
                                        ?>
                                        
                                        <input id="secemail" type="hidden" name="secemail" value="<?php echo $secemail; ?>">
                                        <input id="secname" type="hidden" name="secname" value="<?php echo $secname; ?>">
                                        

                                        <div class="row">
                                            <div class="col-lg-3">
                                            </div>
                                            <div class="col-lg-6"><center><div class="alert alert-danger" role="alert">Please double check before approving.</div>
                                                <button name="exempting" type="submit" class="btn btn-success">EXEMPT</button> </center>
                                            </div>
                                            <div class="col-lg-3">
                                            </div>
                                        </div>
                                        </form>
                                
                            </div>
                      </div>
                        
                        
                        
                    </div>
                    </div>
                  
              
              </div>   
              
          </div>
      </div>    

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
     <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
     
  </body>
  
</html>
<?php 
include_once("$currDir/footer.php");
?>
<script>
$('tr[data-href]').on("click", function() {
    document.location = $(this).data('href');
});

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>

<script>
    CKEDITOR.replace( 'exempt_msg' );
</script>

<script>
$("#contactForm").submit(function(event){
    // cancels the form submission
    event.preventDefault();
    submitForm();
});

function submitForm(){
    // Initiate Variables With Form Content
    var name = $("#name").val();
    var email = $("#email").val();
    var message = $("#message").val();
    var ecpath = $("#ecpath").val();
 
    $.ajax({
        type: "POST",
        url: "mailer/phrepmail.php",
        data: "name=" + name + "&email=" + email + "&message=" + message + "&ecpath=" + ecpath,
        success : function(text){
            if (text == "success"){
                formSuccess();
            }else{
                formFailure();
            }
        }
    });
}
function formSuccess(){
    $( "#msgSubmit" ).removeClass( "hidden" );
}
function formFailure(){
    $( "#msgnotSubmit" ).removeClass( "hidden" );
}

</script>

<script>
    $(document).ready(function(){ 
        $('#sandbox-container input').datepicker({
            orientation: "top left",
            autoclose: true,
            todayHighlight: true
        });
    });
    
</script>