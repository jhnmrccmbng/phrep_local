<?php

$key = $obj->getmagicword(); 
$id = $obj->decrypt($_GET['id'],$key);

$where = array("sub_id" => $id);
$getid = $obj->fetch_record_with_where("submission", $where);
if(!$getid){ ?>
<script>
document.body.style.backgroundColor = "#DCDCDC";
</script>    
<div class="row">
    <div class="col-lg-2"></div>
    <div class="col-lg-8"><br><br><br><hr><center><h1><span class="glyphicon glyphicon-leaf" aria-hidden="true"></span><br>ERROR 404<br>Ooopps! Something went wrong. <br>Why dont you try to go back?</h1></center><hr></div>
    <div class="col-lg-2"></div>
</div>
    
<?php exit; }

?>
