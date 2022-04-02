			<!-- Add footer template above here -->
			<div class="clearfix"></div>
			<?php if(!$_REQUEST['Embedded']){ ?>
				<div style="height: 70px;" class="hidden-print"></div>
			<?php } ?>

			<?php if(!$_REQUEST['Embedded']){ ?>
				<!-- AppGini powered by notice -->
				<div style="height: 60px;" class="hidden-print"></div>
				<nav class="navbar navbar-default navbar-fixed-bottom" role="navigation">
                                    <p class="navbar-text col-lg-4 col-md-4 col-sm-4 col-xs-7">
                                    <?php 
                                    $currPath = $_SERVER['REQUEST_URI'];
                                    $pathId = explode('/', $currPath);
                                    // print_r($pathId);
                                    $pathSearch = array_search("main",$pathId);
                                    // echo $pathSearch;
                                    if($pathSearch){define('PREPEND_PATH', '../');}  
                                    if(!$pathSearch){define('PREPEND_PATH', '');}  
                                                           
                                    
                                    ?>
                                        <small><img id="dost" src="<?php echo PREPEND_PATH; ?>images/dost.png" alt="dost" style="width:20px;height:20px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <img id="pchrd" src="<?php echo PREPEND_PATH; ?>images/pchrd.png" alt="pchrd" style="width:20px;height:20px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="allrights">&copy; All rights reserved.</span> </small></p>
                                    <p class="navbar-text col-lg-3 col-md-4 col-sm-4 col-xs-4 text-center">
                                        <i class="fa fa-facebook fa-lg"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <i class="fa fa-twitter fa-lg"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <i class="fa fa-instagram fa-lg"></i>&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                                       <i class="fa fa-youtube fa-lg"></i>
                                    <p class="navbar-text col-lg-4 col-md-4 col-sm-4 col-xs-5">
                                        <small class="pull-right">
						<a data-toggle="modal" data-target="#contact" class="pointer" id="cadmin">Contact administrator?</a>
					</small>
                                    </p>
				</nav>
			<?php } ?>

		</div> <!-- /div class="container" -->
        <!-- <script src="<?php #echo ORIG_PATH; ?>bootstrap/js/bootstrap.min.js"></script> -->
		<?php if(is_file(dirname(__FILE__) . '/hooks/footer-extras.php')){ include(dirname(__FILE__).'/hooks/footer-extras.php'); } ?>
		<script src="<?php echo PREPEND_PATH; ?>resources/lightbox/js/lightbox.min.js"></script>
        <script src="<?php echo PREPEND_PATH; ?>resources/select2/select2.min.js"></script>
                <script>
                    $(document).ready(function() {
                        $('#select2').select2();
                        $('#select3').select2();                       
                        $('#sponsor1').select2();                      
                        $('#studytype').select2();                      
                        $('.sponsor2').select2({});                      
                        $('.getanswers').select2({});                  
                        $('.resfield').select2({});                  
                        $('#monsource').select2();                    
                        $('.country').select2({});                    
                        $('.regionmulti').select2({});                  
                        $('.proptype').select2({});
                      });
                </script>
                <script>
                    $(document).ready(function(){
                        $(".keyword").select2({
                            tags: true
                        });                        
                      });
                </script>
                <!--DATETIMEPICKER-->
                <script type="text/javascript" src="<?php echo PREPEND_PATH; ?>resources/bootstrap-datetimepicker/bootstrap-datepicker.min.js"></script>
                <!--DATETIMEPICKER-->
                
                <!--WYSIWYG-->          
                <!--WYSIWYG-->
                
                
                <!--SLIPTREE-->
                <script type="text/javascript" src="../resources/sliptree/js/bootstrap-tokenfield.js"></script>
                <script type="text/javascript" src="../resources/sliptree/js/bootstrap-tokenfield.min.js"></script>
                <!--SLIPTREE-->
                <script>
                $(document).ready(function(){
                    $('[data-toggle="tooltip"]').tooltip();   
                });
                </script>
                <script>
                function goBack() {
                    window.history.back();
                }
                
                document.getElementById("dost").src="images/dost.png";
                document.getElementById("pchrd").src="images/pchrd.png";
                </script>
                <style>

                    .pointer {
                        cursor: pointer;
                        text-decoration: none;
                    }
                </style>
                
                <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.5.3/js/bootstrapValidator.js"></script>
                

                <script>

                var nb = document.getElementsByClassName('navbar-text');
                    for (var i = 0; i < nb.length; i++) {
                        if(nb[i].tagName === 'P'){
                        nb[i].id = "nt_" + [i + 1];        
                        }
                    } 

                </script>

                
                
	</body>
</html>