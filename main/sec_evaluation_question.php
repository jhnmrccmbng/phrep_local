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
      <form action = "sec_dashboard_action.php" method = "POST" enctype = "multipart/form-data" class="form-horizontal">
      <div class="container-fluid">
          <?php $id = (int) $_GET['id'];?>
          <input type="hidden" value="<?php echo $mi['username']; ?>" name="username">
          <input type="hidden" value="<?php echo $id; ?>" name="formid">
          <div class="row">
              <div class="row">
                  <div class="col-lg-1"></div>
                  <div class="col-lg-1"><br></div>
                  <div class="col-lg-8"><h2><center>Evaluation Form<br><small>Add Main Questions</small></center></h2></div>
                  <div class="col-lg-2"></div>
              </div>
              
              <div class="row">
                  <div class="col-lg-1"></div>
                  <div class="col-lg-10"><h4>Questions</h4></div>
                  <div class="col-lg-1"></div>
                  
              </div>
              
              <div class="rowfields">
              <div class="row">
                  <div class="col-lg-1"></div>
                  <div class="col-lg-10">
                      <input id="qval" name="qval[]" type="text" placeholder="Add Question Here..." class="form-control input-md" required="">
                  </div>
                  <div class="col-lg-1"></div>  
                  </div><br>
              </div>
              
              
              
              <div class="row">
                  <div class="col-lg-1"></div>
                  <div class="col-lg-5"><br>
                    <button class="add_q btn btn-info">Add Another Question</button></div>
                  <div class="col-lg-5"></div>
                  <div class="col-lg-1"></div>
              </div><br>
             
          </div>
          <div class="row">
              <div class="col-lg-3"></div>
              <div class="col-lg-6"><center>
                    <button class="btn btn-primary" type="submit" name="saveForm">Save</button>
                    <button class="btn btn-default" type="submit">Cancel</button>
              </center></div>
              <div class="col-lg-3"></div>
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
$(document).ready(function() {
    var max_fields      = 100; //maximum input boxes allowed
    var wrapper         = $(".rowfields"); //Fields wrapper
    var add_button      = $(".add_q"); //Add button 
    
    var x = 1; //initlal text box count
    
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            
            $("body").on("focus",".getanswers",function(){
                $(this).select2();
            });
            $(wrapper).append('<div class="row"><a href="#" class="remove_field btn btn-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a><div class="col-lg-1"></div><div class="col-lg-10"><input id="qval" name="qval[]" type="text" placeholder="Add Question Here..." class="form-control input-md" required=""></div><div class="col-lg-1"><br></div></div>'); //add input box
        }   
     
    });
    
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })
});
</script>