<?php
include "upload_file.php";
?>
<?php
	$currDir = dirname(__FILE__);
        $currDir = substr($currDir, 0, -5);
        //echo $currDir;
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	
	/* grant access to the groups 'Admins' and 'Data entry' */
	$mi = getMemberInfo();
	if(!in_array($mi['group'], array('Admins', 'Researcher'))){
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
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Libre+Franklin" rel="stylesheet">
    <link rel="stylesheet" href="../resources/select2/select2.css">

    <style>
      body {
        font-family: 'Libre Franklin', sans-serif;
      }</style>
  </head>
  <body>
      <?php 
        $id = $_GET['id'];
        $key = $obj->getmagicword(); 
        $id = $obj->decrypt($_GET['id'],$key);
        include "checker.php";//checks whether the id has been used by the researcher already.
        $where = array("sub_id" => $id);
        $none = $obj->fetch_record_with_where("proposal", $where);
        foreach($none as $avail){
            if (!$avail['date_submitted'] == null){
                echo '<div class="container-fluid">
                        <div class="row">
                                <div class="col-md-12">
                                        <div class="jumbotron well">
                                                <h2>
                                                        Uh-oh! Sorry the page is not available. Please try again or click the button to return home.
                                                </h2>

                                        </div> 
                                        <a class="btn btn-danger btn-block btn-default" href="dashboard.php" role="button">HOME</a>
                                </div>
                        </div>
                </div>'; 
                exit;
            }
        }
      ?>  
      <div class="container-fluid">

          <div class="row"><!--THIS IS THE BREADCRUMBS OF THE PROCESS-->
                <?php 
                    $currDirr = dirname(__FILE__);
                    include("$currDirr/breadcrumbs.php");

                ?>
          </div><!--THIS IS THE BREADCRUMBS OF THE PROCESS-->
          
        <!--<form class="form-horizontal" role="form" action="submission_action.php" method="POST">-->          
          <div class="row"><!--THIS IS THE FORM AREA-->
            <h1>Step 3. Uploading the Main Proposal File</h1>
            <hr>
            <div class="row">
                <div class="col-lg-12">
                    <p>To upload a proposal for review, complete the following steps.</p>
                    <ol>
                        <li>On the page, click Browse (or Choose File) which opens a Choose File window for locating the file on the hard drive of your computer.</li>
                        <li>Locate the file you wish to submit and highlight it.</li>
                        <li>Click Open on the Choose File window, which places the name of the file on this page</li>
                        <li>Click Upload on this page, which uploads the file from the computer to the website and renames it following the Demo National Health Research Portal naming conventions.</li>
                        <li>Once on the proposal is updated, click Save and Continue at the bottom of this page.</li>
                    </ol>
                    <p>Encountering difficulties? Contact REC System Admin for assistance.</p>
                </div>
            </div>
            <hr>
            <div class="row">
                <h4>Main Submission File</h4>
            </div>
            
            <div class="row well well-lg">
                <div class="row">
                    <div class="col-lg-6">
                        <h4>File Submitted</h4>
                        <?php 
                            $myrow = $obj->get_upload_info("document", $id, "MP");
                            $count = count($myrow);
                            if ($count > 0){
                                foreach ($myrow as $row) {
                            ?>
                            <table class="table table-hover">
                                
                                <tr>
                                    <td>Sent File:</td>
                                    <td><?php echo $row['file_name']; ?></td>
                                </tr>
                                <tr>
                                    <td>Original File Name:</td>
                                    <td><?php echo $row['orig_filename']; ?></td>
                                </tr>
                                <tr>
                                    <td>File Size:</td>
                                    <td><?php $data = $row['file_size']/1024;echo round($data,2)." KB"; ?></td>
                                </tr>
                                <tr>
                                    <td>Date Uploaded:</td>
                                    <td><?php echo $row['date_uploaded']; ?></td>
                                </tr>
                                
                                
                                
                                <?php 
                                }   } 
                                else { ?>
                                    <tr><td colspan="2"><i><center><div class="alert alert-danger">No Main Proposal has been added to this submission.</div></center></i></td></tr> <?php }?>
                     </table>
                    </div>
                    <div class="col-lg-6">
                        <form action = "upload_file.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">
                            
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
                            <fieldset> 
                                <input id="subid" name="submid" type="hidden" value="<?php echo $_GET['id'];?>" placeholder="" class="form-control input-md">
                                <input id="kind" name="kind" type="hidden" value="MP" placeholder="" class="form-control input-md">
                                <input id="doctype" name="doctype" type="hidden" value="1,MainProposal" placeholder="" class="form-control input-md">
                                <?php $actual_link = "https://".$_SERVER['HTTP_HOST']."".$_SERVER['REQUEST_URI'].""; ?>
                                <input id="urllink" name="urllink" type="hidden" value="<?php echo $actual_link;?>" placeholder="" class="form-control input-md">
                                <?php $mi = getMemberInfo(); ?>
                                <input id="username" name="username" type="hidden" value="<?php echo $userid;?>" placeholder="" class="form-control input-md">
                            </fieldset>

                            <div class="row"><!--THIS IS THE BUTTON-->
                                    <div class="col-lg-4">
                                        <input type = "file" name = "image" accept="application/pdf"/><br>
                                        <input type = "submit" class="btn btn-success"/>
                                    </div>
                                    <div class="col-lg-4">
                                    </div>
                                    <div class="col-lg-4"></div>              
                            </div><!--THIS IS THE BUTTON-->
                         </form>  
                    </div>                    
                </div>
                
            </div>
            <hr>
          </div><!--THIS IS THE FORM AREA-->
          <?php 
          $getMPdoc = $obj->getFileifUpload($id, "1", "1");
          
        //   $count = count($getMPdoc);
          if($getMPdoc){
                  ?>
          <form action = "upload_file.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">
          <div class="row"><!--THIS IS THE BUTTON-->
              <input id="fname" name="submid" type="hidden" value="<?php echo $_GET['id'];?>" placeholder="" class="form-control input-md">
                <div class="col-lg-4"></div>
                <div class="col-lg-4"><center>           
                    <button type="submit" name="submitmainprop" class="btn btn-info" >Save and Continue</button>
                    <button type="button" class="btn btn-default" onclick='goBack()'>Back</button></center>
                </div>
                <div class="col-lg-4"></div>              
          </div><!--THIS IS THE BUTTON-->
          </form>
          
          <?php
          }
          else{ ?>
              
          <form action = "upload_file.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">
          <div class="row"><!--THIS IS THE BUTTON-->
              <input id="fname" name="submid" type="hidden" value="<?php echo $_GET['id'];?>" placeholder="" class="form-control input-md">
                <div class="col-lg-4"></div>
                <div class="col-lg-4"><center>           
                    <button type="submit" name="submitmainprop" class="btn btn-info" disabled="disabled" >Save and Continue</button>
                    <button type="button" class="btn btn-danger" onclick='goBack()'>Cancel</button></center>
                </div>
                <div class="col-lg-4"></div>              
          </div><!--THIS IS THE BUTTON-->
          </form>
          <?php }
          ?>
          
          
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
function goBack() {
    window.history.back();
}
</script>