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
	if(!in_array($mi['group'], array('Admins', 'Reviewer'))){
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
          }
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
                    echo '<input type="hidden" class="form-control" value="' . $user['id'] . '" name="userid">';
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
                <?php $id = (int) $_GET['id']; ?>
                <input type="hidden" value="<?php echo $mi['username']; ?>" name="username">
                <div class="row">
                    <div class="col-lg-2"><h2>Information</h2><hr></div>
                    <div class="col-lg-10"><h2>Update</h2> <hr>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-2">

                        <?php include "rev_dashboard_pane.php"; ?>

                    </div>
                    <div class="col-lg-6">
                        <?php
                        $wherem = array("memberID" => $mi['username']);
                        $getmem =  $obj->fetch_record_with_where("membership_users", $wherem);
                        foreach($getmem as $em){
                            $email = $em['email'];
                        }
                        
                        
                        $whereuser = array("id" => $id);
                        $getinfo = $obj->fetch_record_with_where("phrepuser", $whereuser);
                        foreach($getinfo as $gi){
                        ?>
                        <form class="form-horizontal" id="profileForm" action="researcher_action.php" method="POST">
                                <input type="hidden" class="form-control" value="<?php echo $id; ?>" name="userid">
                                <input type="hidden" class="form-control" value="<?php echo $mi['username']; ?>" name="username">
                                <!-- Select Basic -->
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="title">Title</label>
                                        <div class="col-md-9">
                                            <select id="title" name="title" class="form-control">
                                                <option selected value="<?php echo $gi['title']; ?>"><?php echo $gi['title']; ?></option>
                                                <option value="Mr.">Mr.</option>
                                                <option value="Ms.">Ms.</option>
                                                <option value="Mrs.">Mrs.</option>
                                                <option value="Dr.">Dr.</option>
                                                <option value="Atty.">Atty.</option>
                                                <option value="Rev.">Rev.</option>
                                                <option value="Hon.">Hon.</option>
                                                <option value="Sec.">Sec.</option>
                                                <option value="Prof.">Prof.</option>
                                            </select>
                                        </div>
                                    </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">First Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="fname" value="<?php echo $gi['fname']; ?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Middle Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="mname" value="<?php echo $gi['mname']; ?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Last Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="lname" value="<?php echo $gi['lname']; ?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Phone Number</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="pnum" value="<?php echo $gi['pnum']; ?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Institution/Affiliation</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="insti" value="<?php echo $gi['institution']; ?>" required>
                                    </div>
                                </div>

                                <hr>                              
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Email</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" name="email" id="inputEmail3" value="<?php echo $email; ?>" placeholder="Email">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label text text-danger"><b>New Password</b></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="password" id="password" placeholder="Please enter your new Password here">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-10">
                                        <button type="submit" class="btn btn-primary" name="updateresprofile">Update</button>
                                    </div>
                                </div>
                            </form>   
                        
                            
                            
                        <?php    
                        }
                        ?>

                    </div>
                </div> <!--row-->
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