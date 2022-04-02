<?php

$id = $_POST["subid"];
$date = $_POST["date"];
$times = $_POST["times"];
$docname = $_POST["docname"];
$file_tmp = $_FILES['image']['tmp_name'];
$temp = explode(".", $file_name);


    if ( 0 < $_FILES['file']['error'] ) {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    }
    else {
        move_uploaded_file($_FILES['file']['tmp_name'], 'main/uploads/main/' .$id."-".$date."-".$times."-$docname.".end($temp));
    }

?>