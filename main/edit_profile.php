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
      <div class="container-fluid">
          
          <?php $id = (int) $_GET['id'];?>
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
$where = array("username" => $mi['username']);
$getUserUpdate = $obj->getUser("phrepuser", $where);
    if($getUserUpdate){?>
          
          <div class="row">
              <div class="col-lg-2"></div>
              <div class="col-lg-10"></div>
          </div>
          
          <div class="row">
                  <div class="col-lg-2">
                      <hr>                    
                      <div class="list-group">
                          <a href="dashboard.php" class="list-group-item">Home</a>
                          <!--<a href="#" class="list-group-item active">Proposals</a>-->
                          <!--<a href="#" class="list-group-item">Letters</a>-->
                      </div>
                  </div>
              
              <div class="col-lg-10">
                  <div class="row">
                      <div class="col-lg-8">
                          <h1>Edit Your Information</h1>
                  
                          <?php
                            $key = $obj->getmagicword(); 
                            $id = $obj->decrypt($_GET['u'],$key);
                            
                          
                            $table = array("phrepuser", "membership_users");
                            $join_on = array("username", "memberID");
                            $where = array("phrepuser.id" => $id);
                            $getprof = $obj->fetch_record_innerjoin($table, $join_on, $where);
                            if($getprof){
                                foreach($getprof as $gp){
                                    ?>
                          <form class="form-horizontal" id="profileForm" action="researcher_action.php" method="POST">
                              <input type="hidden" class="form-control" value="<?php echo $id; ?>" name="userid">
                              <input type="hidden" class="form-control" value="<?php echo $mi['username']; ?>" name="username">
                              
                              <!-- Select Basic -->
                            <div class="form-group">
                              <label class="col-md-3 control-label" for="title">Title</label>
                              <div class="col-md-9">
                                <select id="title" name="title" class="form-control">
                                  <option selected value="<?php echo $gp['title'];?>"><?php echo $gp['title'];?></option>
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
                                    <input type="text" class="form-control" name="fname" value="<?php echo $gp['fname']; ?>" required>
                                </div>
                              </div>
                                
                              <div class="form-group">
                                <label class="col-sm-3 control-label">Middle Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="mname" value="<?php echo $gp['mname']; ?>" required>
                                </div>
                              </div>
                                
                              <div class="form-group">
                                <label class="col-sm-3 control-label">Last Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="lname" value="<?php echo $gp['lname']; ?>" required>
                                </div>
                              </div>
                                
                              <div class="form-group">
                                <label class="col-sm-3 control-label">Phone Number</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="pnum" value="<?php echo $gp['pnum']; ?>" required>
                                </div>
                              </div>
                                
                              <div class="form-group">
                                <label class="col-sm-3 control-label">Institution/Affiliation</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="insti" value="<?php echo $gp['institution']; ?>" required>
                                </div>
                              </div>
                                
                              <hr>                              
                              <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Email</label>
                                <div class="col-sm-9">
                                  <input type="email" class="form-control" name="email" id="inputEmail3" value="<?php echo $gp['email']; ?>" placeholder="Email">
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
                            }
                            else{
                                //error page
                            }
                            
                          ?>
                          
                          
                            
                      </div>
                  </div>
              </div>
          </div>
<?php } else{ echo '<br><br><br><br><br><br><br><div class="row">
              <div class="col-lg-4"></div>
              <div class="col-lg-4"><div class="alert alert-danger" role="alert"><center>Please update your profile to get started.<br><br><a class="btn btn-success" href="update_profile.php" role="button">Update Profile!</a></center></div></div>
              <div class="col-lg-4"></div>
          </div> '; }?>
        
          
          
          
          
          
          
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
    <div class="modal-dialog modal-lg">
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

<script>
 $(document).on('click', '.view_data', function(){
//  $('#dataModal').modal();
  var subid = $(this).attr("id");
  $.ajax({
   url:"selectapproved.php",
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
$(document).ready(function() {
    $('#profileForm').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            fname: {
                validators: {
                        stringLength: {
                        min: 2,
                    },
                        notEmpty: {
                        message: 'Please supply your first name'
                    }
                }
            },
             mname: {
                validators: {
                     stringLength: {
                        min: 2,
                    },
                    notEmpty: {
                        message: 'Please supply your middle name'
                    }
                }
            },
             lname: {
                validators: {
                     stringLength: {
                        min: 2,
                    },
                    notEmpty: {
                        message: 'Please supply your last name'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'Please supply your email address'
                    },
                    emailAddress: {
                        message: 'Please supply a valid email address'
                    }
                }
            },
             insti: {
                validators: {
                     stringLength: {
                        min: 2,
                    },
                    notEmpty: {
                        message: 'Please supply your Institution'
                    }
                }
            },
            pnum: {
                validators: {
                    notEmpty: {
                        message: 'Please supply your phone number'
                    },
                    pnum: {
                        country: 'PH',
                        message: 'Please supply a vaild phone number with area code'
                    }
                }
            },
            passwordconfirm: {
                validators: {
                    identical: {
                        field: 'newpassword',
                        message: 'The password and its confirm are not the same'
                    }
                }
            }
//            address: {
//                validators: {
//                     stringLength: {
//                        min: 8,
//                    },
//                    notEmpty: {
//                        message: 'Please supply your street address'
//                    }
//                }
//            },
//            city: {
//                validators: {
//                     stringLength: {
//                        min: 4,
//                    },
//                    notEmpty: {
//                        message: 'Please supply your city'
//                    }
//                }
//            },
            }
        });
//        .on('success.form.bv', function(e) {
//            $('#success_message').slideDown({ opacity: "show" }, "slow") // Do something ...
//                $('#profileForm').data('bootstrapValidator').resetForm();
//
//            // Prevent form submission
//            e.preventDefault();
//
//            // Get the form instance
//            var $form = $(e.target);
//
//            // Get the BootstrapValidator instance
//            var bv = $form.data('bootstrapValidator');
//
//            // Use Ajax to submit form data
////            $.post($form.attr('action'), $form.serialize(), function(result) {
////                console.log(result);
////            }, 'json');
//        });
});


</script>