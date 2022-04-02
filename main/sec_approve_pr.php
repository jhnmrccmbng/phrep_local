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
                                  $getproposal = $obj->getproposalinfo($id);
                                  if($getproposal){foreach($getproposal as $prop){echo '<tr><td><center><span class="glyphicon glyphicon-qrcode" aria-hidden="true"></span></center></td><td>'.$prop['code'].'</td></tr>';}}
                                  ?>
                                  <?php
                                  $getproposal = $obj->getproposalinfo($id);
                                  if($getproposal){foreach($getproposal as $prop){echo '<tr><td><center><span class="glyphicon glyphicon-file" aria-hidden="true"></span></center></td><td>'.$prop['prop_ptitle'].'</td></tr>';}}
                                  ?>
                                  <?php
                                  $getproposal = $obj->getproposalinfo($id);
                                  if($getproposal){foreach($getproposal as $prop){echo '<tr><td><center><span class="glyphicon glyphicon-user" aria-hidden="true"></span></center></td><td>'.$prop['fname'].' '.$prop['mname'].' '.$prop['lname'].'</td></tr>';}}
                                  ?>
                                  <?php
                                  $getproposal = $obj->getproposalinfo($id);
                                  if($getproposal){foreach($getproposal as $prop){echo '<tr ><td><center><span class="glyphicon glyphicon-calendar" aria-hidden="true" data-toggle="tooltip" title="DUE DATE"></span></center></td><td>'.$prop['rt_duedate'].'</td></tr>';}}
                                  ?>
                                    
                                </table>
                          </div>
                      </div>                        
                  </div>
                    <div class="row">
                    <div class="col-lg-12">
                        
                        <?php 
                        $whereicc = array("sub_id" => $id, "loa_id" => "5", "loa_ans" => "1");
                        $geticc = $obj->fetch_record_with_where("assessment_ans", $whereicc);
                        if($geticc){ ?>
                            <?php
                            $wherein = array("sub_id" => $id, "submit" => "1");
                            $getifsenticc = $obj->fetch_record_with_where("indigenous", $wherein);
                            if($getifsenticc){ ?>
                                <?php 
                                    $getdoc = array("sub_id" => $id, "kind" => "FPI");
                                    $pathfpi = $obj->fetch_record_with_where("document", $getdoc);
                                    foreach($pathfpi as $pf){
                                        $fp = $pf['path'];
                                    }
                                ?>
                            
                                <div class="panel panel-danger">
                                    <div class="panel-body">
                                        <h4>FPIC APPROVAL<small><span class="pull-right"><a href="<?php echo $fp; ?>">DOWNLOAD</a></span></small></h4>
                                    </div>
                                </div>
                        
                        
                        
                        
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                      <form action = "sec_dashboard_action.php" method = "POST" enctype = "multipart/form-data" class="form">
                                          <h4>Ethical Clearance Validity:</h4>
                                          <div class="row">
                                              <?php
                                                $getclearancedate = $obj->getclearanceDate($id);

                                                if($getclearancedate){
                                                    foreach($getclearancedate as $cdate){
                                                        $where = array("ec_id" => $cdate['ecid']);
                                                        $getid = $obj->fetch_record_with_where("ethical_clearance", $where);
                                                        if($getid){
                                                            foreach($getid as $gcid){
                                                                $ecend = strtotime($gcid['ec_end']);
                                                                $today = strtotime('now');
                                                                if(($ecend > $today)){
                                                                echo '<div class="form-group col-lg-12">
                                                                        <table class="table">
                                                                        <tr>
                                                                        <th><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></th>
                                                                        <th>Start Date</th>
                                                                        <th>End Date</th>
                                                                        </tr>
                                                                        <tr>
                                                                        <td><a href="'.PREPEND_PATH.'sec_dashboard_action.php?delc=1&id='.$gcid['ec_id'].'&subid='.$id.'"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>
                                                                        <td>'.$gcid['ec_start'].'</td>
                                                                        <td>'.$gcid['ec_end'].'</td>
                                                                        </tr>
                                                                        </table>
                                                                    </div>';                                                            
                                                                }
                                                                else{
                                                                    if($ecend != '0'){
                                                                        echo '<div class="row"><div class="col-lg-12"><div class="alert alert-warning" role="alert">Previous clearance was already expired. You can now create another one.</div></div></div>';
                                                                        echo '<div class="form-group col-sm-6">
                                                                                <label for="name" class="h4">Start Date</label>
                                                                                <div id="sandbox-container">
                                                                                <input type="text" class="form-control" id="startdate" name="stclearance"  placeholder="Start" required readonly>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group col-sm-6">
                                                                                <label for="name" class="h4">End Date</label>
                                                                                <div id="sandbox-container">
                                                                                <input type="text" class="form-control" id="enddate" name="endclearance" placeholder="Expire" required readonly>
                                                                                </div>
                                                                            </div>'; 
                                                                    } 
                                                                    else{
                                                                        echo'<div class="form-group col-sm-6">
                                                                                <label for="name" class="h4">Start Date</label>
                                                                                <div id="sandbox-container">
                                                                                <input type="text" class="form-control" id="startdate" name="stclearance"  placeholder="Start" required readonly>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group col-sm-6">
                                                                                <label for="name" class="h4">End Date</label>
                                                                                <div id="sandbox-container">
                                                                                <input type="text" class="form-control" id="enddate" name="endclearance" placeholder="Expire" required readonly>
                                                                                </div>
                                                                            </div>'; 

                                                                    }
                                                                }
                                                            }
                                                        }
                                                        else{//kung walay value sa ethical clearance
                                                                        echo'<div class="form-group col-sm-6">
                                                                                <label for="name" class="h4">Start Date</label>
                                                                                <div id="sandbox-container">
                                                                                <input type="text" class="form-control" id="startdate" name="stclearance"  placeholder="Start" required readonly>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group col-sm-6">
                                                                                <label for="name" class="h4">End Date</label>
                                                                                <div id="sandbox-container">
                                                                                <input type="text" class="form-control" id="enddate" name="endclearance" placeholder="Expire" required readonly>
                                                                                </div>
                                                                            </div>'; 
                                                        }
                                                    }
                                                }
                                              ?>


                                          </div>

                                          <h4>Attached Ethical Clearance:</h4>       
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
                                                    <input id="docname" name="docname" type="hidden" value="EthicalClearance" placeholder="" class="form-control input-md">
                                                    <input id="date" name="date" type="hidden" value="<?php $date;?>" placeholder="" class="form-control input-md">
                                                    <input id="doctype" name="doctype" type="hidden" value="15" placeholder="" class="form-control input-md">
                                                    <input id="submid" name="submid" type="hidden" value="<?php echo $_GET['id'];?>" placeholder="" class="form-control input-md">
                                                    <input id="kind" name="kind" type="hidden" value="EC" placeholder="" class="form-control input-md">
                                                    <?php $actual_link = "http://".$_SERVER['HTTP_HOST']."".$_SERVER['REQUEST_URI'].""; ?>
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
                                                        </div>
                                                    </div>
                                                </form>

                                        <?php 
                                        $where = array("sub_id" => $id, "doctype" => "15", "kind" => "EC", "finaldoc" => "1");
                                        $getclerance = $obj->fetch_record_with_where("document", $where);
                                        if($getclerance){
                                            foreach($getclerance as $clear){
                                               echo '<br><span class="glyphicon glyphicon-file" aria-hidden="true"></span> '.$clear['file_name'];
                                               echo ' <input id="ecpath" name="ecpath" type="hidden" value="'.$clear['path'].'">';
                                               $ecpath = $clear['path'];
                                            }
                                        }

                                        ?>

                                        <hr>
                                        <form role="form" method="POST" action = "sec_dashboard_action.php">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <?php 
                                                    $getrec = $obj->getReciever($id);
                                                        if($getrec){
                                                            foreach($getrec as $rec){
                                                                echo '<input name="srtclearance" type="hidden" class="form-control" id="name" value="'.$rec['fname'].' '.$rec['mname'].' '.$rec['lname'].'">';
                                                            }
                                                        }
                                                ?> 
                                                <?php 
                                                    $getrec = $obj->getReciever($id);
                                                        if($getrec){
                                                            foreach($getrec as $rec){
                                                                echo '<input name="endclearance" type="hidden" class="form-control" id="email" value="'.$rec['email'].'">';
                                                            }
                                                        }
                                                ?> 
                                            </div>
                                        </div>        


                                                <div class="form-group">
                                                    <textarea style="display:none;"  class="form-control" rows="5" placeholder="Enter your message">


                                                    </textarea>
                                                </div>

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

                                                        <?php 
                                                        $getMaxRevisionEC = $obj->getMaxRevisionEC($id);
                                                        $whereec = array("sub_id" => $id, "kind" => "EC", "revision" => $getMaxRevisionEC);
                                                        $ec = $obj->fetch_record_with_where("document", $whereec);
                                                        if($ec){$ecc = "";} else{$ecc = "disabled";}
                                                        ?>    
                                                            <button name="approvepropec" type="submit" class="btn btn-primary" <?php echo $ecc;?>>APPROVE</button> </center>
                                                    </div>
                                                    <div class="col-lg-3">
                                                    </div>
                                                </div>
                                                </form>



                                                <div id="msgSubmit" class="h3 text-center hidden">Message Submitted!</div>
                                                <div id="msgnotSubmit" class="h3 text-center hidden">Message NOT Submitted!</div>

                                    </div>
                              </div>
                                
                            <?php    
                            }
                            else{
                            ?>    
                        <div class="panel panel-default">
                                <div class="panel-body">
                                    <form action = "sec_dashboard_action.php" method = "POST" enctype = "multipart/form-data" class="form">
                                        <h3>Indigenous People/Indigenous Cultural Communities<br><small>Attach Recommendation Letter</small></h3>
                                        
                                            <input id="times" name="times" type="hidden" value="1" placeholder="" class="form-control input-md">
                                            <?php $whered = array("docid" => "22"); $getdoctype = $obj->fetch_record_with_where("document_type", $whered); foreach($getdoctype as $gdt){$docname = $gdt['forfilename']; $did = $gdt['docid'];}?>
                                            <input id="docname" name="docname" type="hidden" value="<?php echo $docname; ?>" placeholder="" class="form-control input-md">
                                            <input id="date" name="date" type="hidden" value="<?php $date;?>" placeholder="" class="form-control input-md">
                                            <input id="doctype" name="doctype" type="hidden" value="<?php echo $did; ?>" placeholder="" class="form-control input-md">
                                            <input id="submid" name="submid" type="hidden" value="<?php echo $_GET['id'];?>" placeholder="" class="form-control input-md">
                                            <input id="kind" name="kind" type="hidden" value="ICC" placeholder="" class="form-control input-md">
                                            <?php $actual_link = "http://".$_SERVER['HTTP_HOST']."".$_SERVER['REQUEST_URI'].""; ?>
                                            <input id="urllink" name="urllink" type="hidden" value="<?php echo $actual_link;?>" placeholder="" class="form-control input-md">
                                            <input id="username" name="username" type="hidden" value="<?php echo $userid;?>" placeholder="" class="form-control input-md">
                                            
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <span class="btn btn-default btn-file">
                                                        <input id="attach" type = "file" name = "imageicc" />  
                                                </span>                      
                                                <input id="attached" type = "submit" name="icc" class="btn btn-primary"/>
                                            
                                                    <?php 
                                            $where = array("sub_id" => $id, "doctype" => "22", "kind" => "ICC", "finaldoc" => "1");
                                            $getclerance = $obj->fetch_record_with_where("document", $where);
                                            if($getclerance){
                                                foreach($getclerance as $clear){
                                                   echo '<br><br><span class="glyphicon glyphicon-file" aria-hidden="true"></span> '.$clear['file_name'];
                                                   echo ' <input id="ecpath" name="ecpath" type="hidden" value="'.$clear['path'].'">';
                                                   $ecpath = $clear['path'];
                                                }
                                                echo '<br><br><a class="btn btn-primary" href="sec_dashboard_active.php#review" role="button">Done</a>';
                                            }

                                            ?>  
                                            </div>                                          
                                        </div>
                                        
                                    </form>
                                </div>
                            </div>      
                        
                        <?php        
                            }
                            ?>
                        <?php }
                        else{ ?>
                             
                           
                            <div class="panel panel-default">
                            <div class="panel-body">
                              <form action = "sec_dashboard_action.php" method = "POST" enctype = "multipart/form-data" class="form">
                                                                    
                                  <h4>Upload Progress Report Approval Letter:</h4>       
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
                                            <input id="docname" name="docname" type="hidden" value="ProgressReportApprovalLetter" placeholder="" class="form-control input-md">
                                            <input id="date" name="date" type="hidden" value="<?php $date;?>" placeholder="" class="form-control input-md">
                                            <input id="doctype" name="doctype" type="hidden" value="30" placeholder="" class="form-control input-md">
                                            <input id="submid" name="submid" type="hidden" value="<?php echo $_GET['id'];?>" placeholder="" class="form-control input-md">
                                            <input id="kind" name="kind" type="hidden" value="PRAL" placeholder="" class="form-control input-md">
                                            <?php $actual_link = "http://".$_SERVER['HTTP_HOST']."".$_SERVER['REQUEST_URI'].""; ?>
                                            <input id="urllink" name="urllink" type="hidden" value="<?php echo $actual_link;?>" placeholder="" class="form-control input-md">
                                            <input id="username" name="username" type="hidden" value="<?php echo $mi['username'];?>" placeholder="" class="form-control input-md">
                                            
                                            <?php
                                            $getmaxec = $obj->getMaxRevisionAAL($id);
                                            echo '<input name="revision" type="hidden" value="'.$getmaxec.'">';
                                            ?>
                                            
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <span class="btn btn-default btn-file">
                                                    <input id="attach" type = "file" name = "progressreportletter" />  
                                                    </span>                      
                                                    <input id="attached" type = "submit" class="btn btn-primary"/>
                                                </div>
                                            </div>
                                        </form>
                                
                                <?php 
                                $ppaid = $obj->getmaxpropapp($id);
                                $where = array("sub_id" => $id, "doctype" => "30", "kind" => "PRAL", "finaldoc" => "1", "post_approval_type" => $ppaid);
                                $getclerance = $obj->fetch_record_with_where("document_postapproval", $where);
                                if($getclerance){
                                    foreach($getclerance as $clear){
                                       echo '<br><a class="btn btn-default btn-xs" href="sec_dashboard_action.php?deleteaal=1&id='.$clear['file_id'].'&subid='.$clear['sub_id'].'" role="button"><span data-toggle="tooltip" title="DELETE" class="glyphicon glyphicon-remove" aria-hidden="true"></span></a> '.$clear['orig_filename'];
                                       echo ' <input id="ecpath" name="ecpath" type="hidden" value="'.$clear['path'].'">';
                                       $ecpath = $clear['path'];
                                    }
                                }
                                
                                ?>
                                
                                <hr>
                                <form role="form" method="POST" action = "sec_dashboard_action.php">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <?php 
                                            $getrec = $obj->getReciever($id);
                                                if($getrec){
                                                    foreach($getrec as $rec){
                                                        echo '<input name="srtclearance" type="hidden" class="form-control" id="name" value="'.$rec['fname'].' '.$rec['mname'].' '.$rec['lname'].'">';
                                                    }
                                                }
                                        ?> 
                                        <?php 
                                            $getrec = $obj->getReciever($id);
                                                if($getrec){
                                                    foreach($getrec as $rec){
                                                        echo '<input name="endclearance" type="hidden" class="form-control" id="email" value="'.$rec['email'].'">';
                                                    }
                                                }
                                        ?> 
                                    </div>
                                </div>        
                                
                                
                                        <div class="form-group">
                                            <textarea style="display:none;"  class="form-control" rows="5" placeholder="Enter your message">
                                                

                                            </textarea>
                                        </div>
                                
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
                                                    
                                                <?php 
                                                $getMaxRevisionAL = $obj->getMaxRevisionAL($id, "PRAL");
                                                $whereec = array("sub_id" => $id, "kind" => "PRAL", "revision" => $getMaxRevisionAL);
                                                $ec = $obj->fetch_record_with_where("document_postapproval", $whereec);
                                                if($ec){$ecc = "";} else{$ecc = "disabled";}
                                                
                                                
                                                $ppaid = $obj->getmaxpropapp($id);
                                                echo '<input name="ppaid" type="hidden" value="'.$ppaid.'">';  
                                                
                                                ?>    
                                                    <button name="approvepropprpa" type="submit" class="btn btn-primary" <?php echo $ecc;?>>APPROVE</button> </center>
                                            </div>
                                            <div class="col-lg-3">
                                            </div>
                                        </div>
                                        </form>
                                        
                                        
                                        
                                        <div id="msgSubmit" class="h3 text-center hidden">Message Submitted!</div>
                                        <div id="msgnotSubmit" class="h3 text-center hidden">Message NOT Submitted!</div>
                                
                            </div>
                      </div>
                      <?php 
                      }
                      ?>
                        
                        
                    </div>
                    </div>
                  
              
              </div>    
              <div class="col-lg-3">
                  <div class="row">
                      
                      <center>
                          <div class="panel panel-primary">
                            <div class="panel-heading">Reviewer's Decision</div>
                            <table class="table table-condensed table-bordered table-hover table-striped table-responsive">
                                <tr>
                                    <th>Reviewer</th>
                                    <th>Decision</th>
                                </tr>
                                
                                <?php 
                                $ppaid = $obj->getmaxpropapp($id);
                                $maxrev = $obj->getmaxreviewerpaa($id,$ppaid);
                                $getevaluators = $obj->getreviewersevalpa($userid, $id, $maxrev, $ppaid);
                                if($getevaluators){
                                    foreach($getevaluators as $eval){
                                        if($eval['desid'] == '1'){
                                        echo '<tr class="success">';
                                        echo '<td>'.substr($eval['fname'],0, 1).'. '.substr($eval['mname'],0,1).'. '.$eval['lname'].'</td>';
                                        echo '<td>'.$eval['dec_desc'].'</td>';
                                        echo '</tr>';                                            
                                        }
                                        else if(($eval['desid'] == '2')||(($eval['desid'] == '3'))){
                                        echo '<tr class="warning">';
                                        echo '<td>'.substr($eval['fname'],0, 1).'. '.substr($eval['mname'],0,1).'. '.$eval['lname'].'</td>';
                                        echo '<td>'.$eval['dec_desc'].'</td>';
                                        echo '</tr>';                                            
                                        }
                                        else if($eval['desid'] == '4'){
                                        echo '<tr>';
                                        echo '<td>'.substr($eval['fname'],0, 1).'. '.substr($eval['mname'],0,1).'. '.$eval['lname'].'</td>';
                                        echo '<td>'.$eval['dec_desc'].'</td>';
                                        echo '</tr>';                                            
                                        }
                                        else{
                                        echo '<tr>';
                                        echo '<td>'.substr($eval['fname'],0, 1).'. '.substr($eval['mname'],0,1).'. '.$eval['lname'].'</td>';
                                        echo '<td>'.$eval['dec_desc'].'</td>';
                                        echo '</tr>';                                            
                                        }
                                        
                                    }
                                }
                                
                                ?>
                                
                                
                            </table>
                        </div>                         
                          
                        </center>
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
    CKEDITOR.replace( 'message' );
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