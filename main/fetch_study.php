<?php
include "../config.php";

$connect = mysqli_connect($dbServer, $dbUsername, $dbPassword, $dbDatabase);

$output = '';

if($_POST["search"] == ''){
    
}
else{
    
    $sql = "

        SELECT * FROM proposal a INNER JOIN phrepuser b ON a.username = b.id 
        WHERE a.code != '' AND (a.prop_ptitle LIKE '%".$_POST["search"]."%' OR b.fname LIKE '%".$_POST["search"]."%' OR b.lname LIKE '%".$_POST["search"]."%')

        ";
 // -- WHERE a.prop_ptitle LIKE '%".$_POST["search"]."%' AND a.code != ''

    $result = mysqli_query($connect, $sql);

    if(mysqli_num_rows($result) > 0){       
        
        $output .= '<h4 align="center">Search Result</h4>';
        $output .= '<div class="table-responsive">'
                . '<table class="table table-bordered">'
                . '<tr>'
                . '<th>Research Information</th>'
                . '</tr>';
        while($row = mysqli_fetch_array($result)){
            
            
            $sqlpost = "SELECT * FROM proposal_post_approval WHERE subid = '$row[sub_id]' LIMIT 1";
            $resultpost = mysqli_query($connect, $sqlpost);
            if(mysqli_num_rows($resultpost) > 0){
                while($rowpost = mysqli_fetch_array($resultpost)){
                    
                    $uc = ucwords($row["prop_ptitle"]);
                    $strlen = strlen($row["prop_ptitle"]);
                        if ($strlen>100){$title = substr($uc, 0, 100).'...';}
                        else {$title = substr($uc, 0, 100);}             


                    $output .= '<tr>'
                            . '<td><strong><a href="summary.php?id='.$row["sub_id"].'">'.strtoupper($title).''
                            . '</a></strong><span class="glyphicon glyphicon-ok pull-right" data-toggle="tooltip" title="Approved" aria-hidden="true"></span><br>'
                            . '<small>'.$row["title"].' '.$row["fname"].' '.$row["mname"].' '.$row["lname"].''
                            . '</td>'
                            . '</tr>';
                }
            }
            else{
                    $uc = ucwords($row["prop_ptitle"]);
                    $strlen = strlen($row["prop_ptitle"]);
                        if ($strlen>100){$title = substr($uc, 0, 100).'...';}
                        else {$title = substr($uc, 0, 100);}             


                    $output .= '<tr>'
                            . '<td><strong><a href="summary.php?id='.$row["sub_id"].'">'.strtoupper($title).'</a></strong><br>'
                            . '<small>'.$row["title"].' '.$row["fname"].' '.$row["mname"].' '.$row["lname"].'</td>'
                            . '</tr>';
                
            }
            
        }
        echo $output;
    }
    else{
        echo '<div class="alert alert-warning" role="alert"><strong>Ooops!</strong> Study not found.</div>';
    }
    
}
