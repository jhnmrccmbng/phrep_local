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
      }</style>
  </head>
  <body>
      <form action = "sec_dashboard_action.php?" method = "POST" enctype = "multipart/form-data" class="form-horizontal">
      <div class="container-fluid">
          <?php $id = (int) $_GET['id'];?>
          <input type="hidden" value="<?php echo $mi['username']; ?>" name="username">
          <div class="row">
              <div class="row">
                  <div class="col-lg-1"></div>
                  <div class="col-lg-1"><br><a class="btn btn-success" href="sec_add_evaluation.php" role="button">Add Evaluation Form</a></div>
                  <div class="col-lg-8"><h2><center>List of Evaluation Forms</center></h2></div>
                  <div class="col-lg-2"></div>
              </div>
              <div class="col-lg-1"></div>
              <div class="col-lg-10">
                  
                  <div class="panel panel-default">

                    <table class="table table-bordered">
                        <tr>
                            <th><center><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span></center></th>
                            <th>Form Number</th>
                            <th>Form Name</th>
                            <th>Date Created</th>
                            <th>Version Number</th>
                        </tr>
                        <tr>
                            
                            <?php 
                            $getForm = $obj->gettingForms($mi['username']);
                            if($getForm){
                                foreach($getForm as $form){
                                    echo '<td><center><a class="btn btn-default" href="sec_evaluation_question.php?id='.$form[fid].'" role="button" data-toggle="tooltip" title="EDIT"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>&nbsp;
                                        <a class="btn btn-danger" href="sec_evaluation_list.php?id='.$form[fid].'" role="button" data-toggle="tooltip" title="ARCHIVE"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></center></td>
                                        <td>'.$form[fid].'</td>
                                        <td>'.$form[form_name].'</td>
                                        <td>'.$form[rec_form_version_date].'</td>
                                        <td>'.$form[rec_version_number].'</td>';
                                }
                            }
                            ?>
                            
                            
                        </tr>
                    </table>
                  </div>
                  
              </div>
              <div class="col-lg-1"></div>
              
             
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
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>