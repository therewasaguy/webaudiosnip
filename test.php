<?php
header('Content-type: application/json');
//can respond with plain text, can respond with a json file, can respond with a URL, 
$cursor = $_POST['cursor'];
$arr = array('cursor' => $cursor);

echo json_encode($arr);
?>