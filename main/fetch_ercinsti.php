<?php
include "../config.php";

$connect = mysqli_connect($dbServer, $dbUsername , $dbPassword, $dbDatabase);
$output = '';
$sql = "SELECT * FROM rec_list WHERE insti_id = '" . $_POST["instiId"] . "' AND secretary != '0' ORDER BY erc_name";
$result = mysqli_query($connect, $sql);
$output = '<option value="">(Select Institution)</option>';

while ($row = mysqli_fetch_array($result)) {
    $output .= '<option value = "' . $row["id"] . '">' . $row["erc_name"] . '</option>';
}

echo $output;
?>