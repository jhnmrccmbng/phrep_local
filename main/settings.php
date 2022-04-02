<?php
include "superadmin_action.php";

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
	if(!in_array($mi['group'], array('Admins'))){
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
      }.tr_link {cursor:pointer}</style>
  </head>
    <body>
    <form class="form-horizontal" role="form" action="superadmin_action.php" method="POST">
        <div class="container-fluid">
            <?php $id = (int) $_GET['id'];?>
            <?php 
                      $where = array("username" => $mi['username']);
                      $getUserID = $obj->getUser("phrepuser", $where);
                      if($getUserID){
                          foreach($getUserID as $user){
                              echo '<input type="hidden" class="form-control" value="'.$user[id].'" name="userid">';
                              $userid = $user[id];
                          }
                      }
            ?>
            <div class="row">
                <h3>phrepuser</h3>
                <div class="col-lg-4">
                        <table class="table table-bordered">
                        <?php 
                            $getphrepuser = $obj->fetch_record("phrepuser");
                            if($getphrepuser){
                                foreach ($getphrepuser as $u){
                                    echo '<tr>';
                                    echo '<td>';
                                    echo $u['id'];
                                    echo '</td>';
                                    echo '<td>';
                                    echo $u['username'];
                                    echo '</td>';
                                    echo '<td>';
                                    echo $u['lname'];
                                    echo '</td>';
                                    echo '<td>';
                                    echo '<a href="#" data-href="superadmin_action.php?deletepuser=1&u='.$u["id"].'" class="btn btn-danger" data-toggle="modal" data-target="#confirm-deletemember">Delete</a>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                            }
                        ?>
                        
                        </table>
                        <!--<button class="btn btn-danger" type="submit" name="truncate">TRUNCATE</button>-->      
                </div>
                
            </div>
            
            <div class="row">
                <h3>membership_users</h3>
                <div class="col-lg-4">
                        <table class="table table-bordered">
                        <?php 
                            $getphrepuser = $obj->fetch_record("membership_users");
                            if($getphrepuser){
                                foreach ($getphrepuser as $u){
                                    echo '<tr>';
                                    echo '<td>';
                                    echo $u['memberID'];
                                    echo '</td>';
                                    echo '<td>';
                                    echo $u['email'];
                                    echo '</td>';
                                    echo '<td>';
                                    echo '<a href="#" data-href="superadmin_action.php?deletemember=1&p='.$u["memberID"].'" class="btn btn-danger" data-toggle="modal" data-target="#confirm-deletemember">Delete</a>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                            }
                        ?>
                        
                        </table>
                        <!--<button class="btn btn-danger" type="submit" name="truncate">TRUNCATE</button>-->      
                </div>
                
            </div>
            
            <div class="row">
                <h3>Academic Degree List</h3>
                <div class="col-lg-8">
                    <table class="table table-bordered table-condensed">
                        <tr>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                        <?php 
                        $getacad = $obj->academic();
                        if($getacad){
                            foreach($getacad as $ga){
                                if($ga[acad_id] != null){
                                    echo '<tr>';
                                    echo '<td><p class="pull-left">'.$ga['desc_acad'].'</p><p class="pull-right">
                                            <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span></p></td>';
                                    echo '<td></td>';
                                    echo '</tr>';                                    
                                }
                                else{
                                    echo '<tr>';
                                    echo '<td><p class="pull-left">'.$ga['desc_acad'].'</p></td>';
                                    echo '<td></td>';
                                    echo '</tr>';   
                                    
                                }
                            }
                        }
                        ?>
                    </table>
                </div>
                <div class="col-lg-4"></div>
            </div>
            
            <div class="row">
                <div class="col-lg-12">
                    <h1>REVIEWERS GROUPS</h1>
                    <table class="table table-bordered table-condensed">
                        <tr>
                            <th>ID</th>
                            <th>SUBID</th>
                            <th>PHREPUSERID</th>
                            <th>REVIEW</th>
                            <th>CONFIRM</th>
                            <th>PRIMEREV</th>
                            <th>EVALTYPE</th>
                            <th>EVALSUBMIT</th>
                            <th>DECISION</th>
                            <th>EVALDATE</th>
                            <th>ACTION</th>
                        </tr>
                    <?php 
                    $getrevgroups = $obj->fetch_record("rev_groups");
                    foreach($getrevgroups as $rg){
                        echo '<tr>';
                        echo '<td>'.$rg["id"].'</td>';
                        echo '<td>'.$rg["sub_id"].'</td>';
                        echo '<td>'.$rg["phrepuser_id"].'</td>';
                        echo '<td>'.$rg["review"].'</td>';
                        echo '<td>'.$rg["confirmation"].'</td>';
                        echo '<td>'.$rg["primary_reviewer"].'</td>';
                        echo '<td>'.$rg["evaluation_type"].'</td>';
                        echo '<td>'.$rg["evaluation_submitted"].'</td>';
                        echo '<td>'.$rg["decision"].'</td>';
                        echo '<td>'.$rg["eval_date"].'</td>';  
                        echo '<td><a href="#" data-href="superadmin_action.php?delrevgroup=1&p='.$rg["id"].'" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#confirm-revgroup">DELETE</a> | '; 
                        echo '<a href="#" data-href="settings.php?eval=1&e='.$rg["evaluation_submitted"].'&i='.$rg["id"].'" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#confirm-editeval">EDIT</a></td>'; 
                        echo '</tr>';
                    }
                    
                    ?>
                    </table>
                </div>
            </div>
            
            <?php 
            if(isset($_GET['eval']) == '1'){
                    $evalsub = $_GET['e'];
                    $id = $_GET['i']; ?>
            
            <div class="row">
                    <div class="col-lg-12">
                        <h1>Evaluation Submitted</h1>
                        <?php 
                        $whereid = array("id" => $id);
                        $getid = $obj->fetch_record_with_where("rev_groups", $whereid);
                        foreach($getid as $rr){
                            $previewer = $rr["primary_reviewer"];
                        }
                        ?>

                        <form class="form-horizontal" id="profileForm" action="superadmin_action.php" method="POST">
                            <input type="hidden" class="form-control" value="<?php echo $id; ?>" name="evalid">

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Evaluation Submitted</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="evalsub" value="<?php echo $evalsub; ?>" required>
                                </div>
                                <label class="col-sm-3 control-label">Primary Reviewer</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="primaryrev" value="<?php echo $previewer; ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-10">
                                    <button type="submit" class="btn btn-primary" name="evalsubmit">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
            
            
            <div class="row">
                <div class="col-lg-12">
                    <h1>PROPOSAL STATUS</h1>
                    <table class="table table-bordered table-condensed">
                        <tr>
                            <th>ID</th>
                            <th>SUBID</th>
                            <th>STATUSACTION</th>
                            <th>STATUSDATE</th>
                            <th>STATUS_USERNAME</th>
                            <th>ACTION</th>
                        </tr>
                    <?php 
                    $getrevgroups = $obj->fetch_record("proposal_status");
                    foreach($getrevgroups as $rg){
                        echo '<tr>';
                        echo '<td>'.$rg["id"].'</td>';
                        echo '<td>'.$rg["sub_id"].'</td>';
                        echo '<td>'.$rg["status_action"].'</td>';
                        echo '<td>'.$rg["status_date"].'</td>';
                        echo '<td>'.$rg["status_username"].'</td>'; 
                        echo '<td><a href="#" data-href="superadmin_action.php?delpropstatus=1&p='.$rg["id"].'" class="btn btn-primary btn-xs pull-right" data-toggle="modal" data-target="#confirm-propstatus">DELETE</a></td>';                        
                        echo '</tr>';
                    }
                    
                    ?>
                    </table>
                </div>
            </div>
            
            
            <div class="row">
                <div class="col-lg-12">
                    <h1>REVIEW FEEDBACK LIST</h1>
                    <table class="table table-bordered table-condensed">
                        <tr>
                            <th>SUBID</th>
                            <th>REVID</th>
                            <th>REVFORM</th>
                            <th>ACTION</th>
                        </tr>
                    <?php 
                    $getrevgroups = $obj->getallreviewanswer();
                    foreach($getrevgroups as $rg){
                        echo '<tr>';    
                        echo '<td>'.$rg["sub_id"].'</td>';
                        echo '<td>'.$rg["revid"].'</td>';
                        echo '<td>'.$rg["revform_id"].'</td>';
                        echo '<td><a href="#" data-href="superadmin_action.php?delrevans=1&i='.$rg["sub_id"].'&r='.$rg["revid"].'" class="btn btn-primary btn-xs pull-right" data-toggle="modal" data-target="#confirm-reviewanse">DELETE</a></td>';                        
                        echo '</tr>';
                    }
                    
                    ?>
                    </table>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-12">
                    <h1>REVIEW TYPE DUEDATE</h1>
                    <table class="table table-bordered table-condensed">
                        <tr>
                            <th>ID</th>
                            <th>SUBID</th>
                            <th>RTDUEDATE</th>
                            <th colspan="2"><center>ACTION</center></th>
                        </tr>
                    <?php 
                    $getrevgroups = $obj->fetch_record("review_type_duedate");
                    foreach($getrevgroups as $rg){
                        echo '<tr>';
                        echo '<td>'.$rg["id"].'</td>';
                        echo '<td>'.$rg["subid"].'</td>';
                        echo '<td>'.$rg["rt_duedate"].'</td>';
                        echo '<td><a href="#" data-href="settings.php?updatereviewtype=1&p='.$rg["id"].'&d='.$rg["rt_duedate"].'" class="btn btn-primary btn-xs pull-right" data-toggle="modal" data-target="#confirm-rtddedit">EDIT</a></td>';
                        echo '<td><a href="#" data-href="superadmin_action.php?delrtduedate=1&p='.$rg["id"].'" class="btn btn-primary btn-xs pull-right" data-toggle="modal" data-target="#confirm-rtduedate">DELETE</a></td>';                        
                        echo '</tr>';
                    }
                    
                    ?>
                    </table>
                    
                    <?php
            
            if($_GET['updatereviewtype'] == '1'){
                $id = $_GET['p'];
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1>Edit Review Type Due Date</h1>


                        <form class="form-horizontal" id="profileForm" action="superadmin_action.php" method="POST">
                            <input type="hidden" class="form-control" value="<?php echo $id; ?>" name="ddateid">

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Due Date</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="ddate" value="<?php echo $_GET['d'];?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-10">
                                    <button type="submit" class="btn btn-primary" name="updateddate">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php    
            }            
            ?>
                    
                    
                </div>
            </div>
            
            
            <div class="row">
                <div class="col-lg-12">
                    <h1>NOTE</h1>
                    <table class="table table-bordered table-condensed">
                        <tr>
                            <th>ID</th>
                            <th>SUBID</th>
                            <th>SENDER</th>
                            <th>MESSAGE</th>
                            <th>DATESENT</th>
                            <th>ACTION</th>
                        </tr>
                    <?php 
                    $getrevgroups = $obj->fetch_record("note");
                    foreach($getrevgroups as $rg){
                        echo '<tr>';
                        echo '<td>'.$rg["id"].'</td>';
                        echo '<td>'.$rg["sub_id"].'</td>';
                        echo '<td>'.$rg["sender"].'</td>';
                        echo '<td>'.$rg["message"].'</td>';
                        echo '<td>'.$rg["datesent"].'</td>';
                        echo '<td><a href="#" data-href="superadmin_action.php?delnote=1&p='.$rg["id"].'" class="btn btn-primary btn-xs pull-right" data-toggle="modal" data-target="#confirm-note">DELETE</a></td>';                        
                        echo '</tr>';
                    }
                    
                    ?>
                    </table>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-12">
                    <h1>REC LIST OF REVIEWERS</h1>
                    <table class="table table-bordered table-condensed">
                        <tr>
                            <th>ID</th>
                            <th>REC ID</th>
                            <th>PHREPUSER</th>
                            <th>TYPE ID</th>
                            <th>ACTION</th>
                        </tr>
                    <?php 
                    $getrevgroups = $obj->fetch_record("rec_groups");
                    foreach($getrevgroups as $rg){
                        echo '<tr>';
                        echo '<td>'.$rg["id"].'</td>';
                        echo '<td>'.$rg["rec_list_id"].'</td>';
                        echo '<td>'.$rg["phrepuser_id"].'</td>';
                        echo '<td>'.$rg["type_id"].'</td>';
                        echo '<td><a href="#" data-href="superadmin_action.php?delrecgroup=1&p='.$rg["id"].'" class="btn btn-primary btn-xs pull-right" data-toggle="modal" data-target="#confirm-recgroup">DELETE</a></td>';                        
                        echo '</tr>';
                    }
                    
                    ?>
                    </table>
                </div>
            </div>
            
            
            <div class="row">
                <div class="col-lg-12">
                    <h1>PROPOSAL</h1>
                    <table class="table table-bordered table-condensed">
                        <tr>
                            <th>USERNAME</th>
                            <th>SUB_ID</th>
                            <th>CODE</th>
                            <!--<th>ACTION</th>-->
                        </tr>
                    <?php 
                    $getrevgroups = $obj->fetch_record("proposal");
                    foreach($getrevgroups as $rg){
                        echo '<tr>';
                        echo '<td>'.$rg["username"].'</td>';
                        echo '<td>'.$rg["sub_id"].'</td>';
                        echo '<td>'.$rg["code"].'</td>';
                        echo '<td><a href="#" data-href="settings.php?editpropcode=1&p='.$rg["sub_id"].'&c='.$rg["code"].'" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#confirm-editcode">EDIT</a></td>';                        
                        echo '</tr>';
                    }
                    
                    ?>
                    </table>
                </div>
            </div>
            
            <?php
            
            if($_GET['editpropcode'] == '1'){
                $subid = $_GET['p'];
                $code = $_GET['c'];
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1>Edit Code</h1>


                        <form class="form-horizontal" id="profileForm" action="superadmin_action.php" method="POST">
                            <input type="hidden" class="form-control" value="<?php echo $subid; ?>" name="subid">

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Evaluation Submitted</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="code" value="<?php echo $code; ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-10">
                                    <button type="submit" class="btn btn-primary" name="codeedit">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php    
            }            
            ?>
                        
            <div class="row">
                <div class="col-lg-12">
                    <h1>SUBMISSION</h1>
                    <table class="table table-bordered table-condensed">
                        <tr>
                            <th>SUB_ID</th>
                            <th>RECLISTID</th>
                            <th>RCID</th>
                            <th>ORDER</th>
                            <th>CODE</th>
                            <th>YEAR</th>
                            <th>ACTION</th>
                        </tr>
                    <?php 
                    $getrevgroups = $obj->fetch_record("submission");
                    foreach($getrevgroups as $rg){
                        echo '<tr>';
                        echo '<td>'.$rg["sub_id"].'</td>';
                        echo '<td>'.$rg["reclist_id"].'</td>';
                        echo '<td>'.$rg["rc_id"].'</td>';
                        echo '<td>'.$rg["ordering"].'</td>';
                        echo '<td>'.$rg["coding"].'</td>';
                        echo '<td>'.$rg["year"].'</td>';
                        echo '<td><a href="#" data-href="settings.php?editsubmission=1&s='.$rg["sub_id"].'&ri='.$rg["reclist_id"].'" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#confirm-editsubmit">EDIT</a> | ';                        
                        echo '<a href="#" data-href="settings.php?editordering=1&s='.$rg["sub_id"].'&ri='.$rg["reclist_id"].'" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#confirm-editordering">EDIT</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    
                    ?>
                    </table>
                </div>
            </div>
            
            <?php
            
            if($_GET['editordering'] == '1'){
                $subid = $_GET['s'];
                $reclistid = $_GET['ri'];
                
                $wherei = array("sub_id" => $subid);
                $getsubmission = $obj->fetch_record_with_where("submission", $wherei);
                foreach($getsubmission as $sss){
                    $order = $sss["ordering"];
                    $code = $sss["coding"];
                    $year = $sss["year"];
                }
                
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1>Edit Submitted</h1>


                        <form class="form-horizontal" id="profileForm" action="superadmin_action.php" method="POST">
                            <input type="hidden" class="form-control" value="<?php echo $subid; ?>" name="subid">

                            <div class="form-group">
                                <label class="col-sm-3 control-label">ORDER</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="ordering" value="<?php echo $order; ?>" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">CODE</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="coding" value="<?php echo $code; ?>" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">YEAR</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="year" value="<?php echo $year; ?>" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-10">
                                    <button type="submit" class="btn btn-primary" name="orderedit">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php    
            }            
            ?>
            
            
            <?php
            
            if($_GET['editsubmission'] == '1'){
                $subid = $_GET['s'];
                $reclistid = $_GET['ri'];
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1>Edit Submitted</h1>


                        <form class="form-horizontal" id="profileForm" action="superadmin_action.php" method="POST">
                            <input type="hidden" class="form-control" value="<?php echo $subid; ?>" name="subid">

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Evaluation Submitted</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="reclistid" value="<?php echo $reclistid; ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-10">
                                    <button type="submit" class="btn btn-primary" name="submissionedit">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php    
            }            
            ?>
                
            <div class="row">
                <div class="col-lg-12">
                    <h1>DOCUMENT TYPES</h1><a href="#" data-href="settings.php?adddoctype=1" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#confirm-adddoctype">ADD</a>
                    <table class="table table-bordered table-condensed">
                        <tr>
                            <th>DOCID</th>
                            <th>DOCDESC</th>
                            <th>FORFILENAME</th>
                        </tr>
                    <?php 
                    $getrevgroups = $obj->fetch_record("document_type");
                    foreach($getrevgroups as $rg){
                        echo '<tr>';
                        echo '<td>'.$rg["docid"].'</td>';
                        echo '<td>'.$rg["doctype_desc"].'</td>';
                        echo '<td>'.$rg["forfilename"].'</td>';
                        echo '</tr>';
                    }
                    
                    ?>
                    </table>
                </div>
            </div>
            <?php
            
            if($_GET['adddoctype'] == '1'){
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1>Add Document Type</h1>


                        <form class="form-horizontal" id="profileForm" action="superadmin_action.php" method="POST">

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Document Type Description</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="doctypedesc" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Forfilename</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="forfilename" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-10">
                                    <button type="submit" class="btn btn-primary" name="adddoctype">Add DocType</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php    
            }            
            ?>
            
<!--            <div class="row">
                <div class="col-lg-12">
                    <h1>EMAIL TEMPLATE</h1>
                    <table class="table table-bordered table-condensed">
                        <tr>
                            <th>ID</th>
                            <th>EMAIL KEY</th>
                            <th>SUBJECT</th>
                            <th>ACTION</th>
                        </tr>
                    <?php 
//                    $getrevgroups = $obj->fetch_record("email_templates");
//                    foreach($getrevgroups as $rg){
//                        echo '<tr>';
//                        echo '<td>'.$rg["id"].'</td>';
//                        echo '<td>'.$rg["email_key"].'</td>';
//                        echo '<td>'.$rg["subject"].'</td>';
//                        echo '<td><a href="settings.php?emailtemp=1&p='.$rg["id"].'" class="btn btn-warning btn-xs">EDIT</a></td>';                        
//                        echo '</tr>';
//                    }
                    
                    ?>
                    </table>
                    
                    <form action = "superadmin_action.php" method = "POST">
                    <?php 
//                    if(isset($_GET['emailtemp']) == '1'){
//                        $getid = $_GET["p"];
//                        
//                        $whereem = array("id" => $getid);
//                        $getemailtemplate = $obj->fetch_record_with_where("email_templates", $whereem);
//                        
//                        foreach($getemailtemplate as $gt){
//                            echo '<div class="form-group">
//                                    <label for="comment">Template:</label>
//                                    <textarea class="form-control" rows="5" id="template" name="template">'.$gt['body'].'</textarea>
//                                  </div>';
//                        }
//                        echo '<input type="hidden" name="tempid" value="'.$gt["id"].'">';
//                        echo '<input type="submit" name="updatetemplate" class="btn btn-info" value="Update">';
//                        
//                    }
                    ?>
                    </form>
                    
                    
                </div>
            </div>-->
            

        </div>                   
    </form>   

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
jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});


$('tr[data-href]').on("click", function() {
    document.location = $(this).data('href');
});
</script>

<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
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
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

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

    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1>Attention!</h1>
                </div>
                <div class="modal-body">
                    Are you sure to delete unfinished submission?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger btn-ok">Delete</a>
                </div>
            </div>
        </div>
    </div>

<script>
$('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>

<div class="modal fade" id="confirm-deletemember" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Warning!</h3>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this user? 
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-deletemember').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>

<div class="modal fade" id="confirm-revgroup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Warning!</h3>
            </div>
            <div class="modal-body">
                Are you sure you want to withdraw this proposal? 
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-revgroup').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>

<div class="modal fade" id="confirm-propstatus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Warning!</h3>
            </div>
            <div class="modal-body">
                Are you sure you want to withdraw this proposal? 
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-propstatus').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>

<div class="modal fade" id="confirm-rtduedate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Warning!</h3>
            </div>
            <div class="modal-body">
                Are you sure you want to withdraw this proposal? 
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-rtduedate').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>

<div class="modal fade" id="confirm-note" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Warning!</h3>
            </div>
            <div class="modal-body">
                Are you sure you want to withdraw this proposal? 
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-note').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>

<div class="modal fade" id="confirm-recgroup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Warning!</h3>
            </div>
            <div class="modal-body">
                Are you sure you want to withdraw this proposal? 
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-recgroup').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>

<div class="modal fade" id="confirm-editeval" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Warning!</h3>
            </div>
            <div class="modal-body">
                Are you sure you want to EDIT this Review? 
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-editeval').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script> 

<div class="modal fade" id="confirm-reviewanse" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Warning!</h3>
            </div>
            <div class="modal-body">
                Are you sure you want to DELETE this Review FEEDBACK? 
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-reviewanse').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>

<div class="modal fade" id="confirm-editsubmit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Warning!</h3>
            </div>
            <div class="modal-body">
                Are you sure you want to EDIT this SUBMISSION? 
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-editsubmit').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>

<div class="modal fade" id="confirm-editcode" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Warning!</h3>
            </div>
            <div class="modal-body">
                Are you sure you want to EDIT this PROPOSAL CODE? 
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-editcode').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>

<div class="modal fade" id="confirm-editordering" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Warning!</h3>
            </div>
            <div class="modal-body">
                Are you sure you want to EDIT this ORDERING? 
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-editordering').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>

<div class="modal fade" id="confirm-adddoctype" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
</div>

<script>
$('#confirm-adddoctype').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>

<div class="modal fade" id="confirm-rtddedit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Warning!</h3>
            </div>
            <div class="modal-body">
                Are you sure you want EDIT REVIEW TYPE DUE DATE? 
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary btn-ok">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#confirm-rtddedit').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>