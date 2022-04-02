<?php
include "researcher_action.php";
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
if (!in_array($mi['group'], array('Admins', 'Researcher'))) {
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                    <form class="form-horizontal" role="form" action="researcher_action.php" method="POST">
                        <fieldset>
                            <?php 
                            $where = array("memberID" => $mi['username']);
                            $getuser = $obj->getUser("membership_users", $where);
                            if ($getuser){
                                foreach($getuser as $user){?>
                                
                            
                        <!-- Form Name -->
                        <legend>Update Profile</legend>
                        
                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="username">Username</label>  
                          <div class="col-md-9">
                          <input id="username" name="username2" value="<?php echo $user[memberID];?>" type="hidden" placeholder="" class="form-control input-md">
                          <input id="username" name="username" value="<?php echo $user[memberID];?>" type="text" placeholder="" class="form-control input-md" disabled>

                          </div>
                        </div>
                        
                        <!-- Select Basic -->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="title">Title</label>
                          <div class="col-md-9">
                            <select id="title" name="title" class="form-control">
                                <option disabled selected>(Select title)</option>
                              <option value="Mr.">Mr.</option>
                              <option value="Ms.">Ms.</option>
                              <option value="Ms.">Mrs.</option>
                              <option value="Dr.">Dr.</option>
                              <option value="Atty.">Atty.</option>
                              <option value="Rev.">Rev.</option>
                              <option value="Hon.">Hon.</option>
                              <option value="Hon.">Sec.</option>
                            </select>
                          </div>
                        </div>
                        
                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="fname">First Name</label>  
                          <div class="col-md-9">
                          <input id="fname" name="fname" type="text" placeholder="" class="form-control input-md">

                          </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="mname">Middle Name</label>  
                          <div class="col-md-9">
                          <input id="mname" name="mname" type="text" placeholder="" class="form-control input-md">

                          </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="lname">Last Name</label>  
                          <div class="col-md-9">
                          <input id="lname" name="lname" type="text" placeholder="" class="form-control input-md">

                          </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="pnum">Contact Number</label>  
                          <div class="col-md-9">
                          <input id="pnum" name="pnum" type="text" placeholder="09XX-XXXX-XXX" class="form-control input-md">

                          </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="intitu">Institution</label>  
                          <div class="col-md-9">
                          <input id="intitu" name="intitu" type="text" placeholder="" class="form-control input-md">

                          </div>
                        </div>

                        </fieldset>
                        <div class="row">
                            <div class="col-lg-4"></div>
                            <div class="col-lg-4"><center>
                                <button type="submit" name="updateprofile" class="btn btn-info">Save</button>
                                <button type="button" class="btn btn-danger" onclick='goBack()'>Cancel</button></center></center></div>
                            <div class="col-lg-4"></div>
                        </div>
                                <?php }}?>
                    
                        </form>
                    
                </div>
                <div class="col-lg-2"></div>
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
function goBack() {
    window.history.back();
}
</script>