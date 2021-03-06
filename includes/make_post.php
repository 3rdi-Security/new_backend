<?php
session_start();

include 'dbConfig.php';

function SQLInjFilter(&$unfilteredString){
	$unfilteredString = mb_convert_encoding($unfilteredString, 'UTF-8', 'UTF-8');
	$unfilteredString = htmlentities($unfilteredString, ENT_QUOTES, 'UTF-8');
	// return $unfilteredString;
}
$error = "";
$return = "";
$status = 0;
$ret = array();

$text = $_POST['text'];

if (!isset($text) || $text=='' ) {
    $error .= "Post text blank.";
    $status = 400;
}

if($status!=400){
	SQLInjFilter($text);
	$feeds = new Feed($dbCon, $_SESSION['user']);
	if($feeds->MakePost($text)){	
		$ret['status'] = 200;
		$ret['feeds'] = $feeds->getFeed_json();
		$_COOKIES['feeds'] = $ret['feeds'];
	}else{
		$ret['status'] = 500;
		$ret['err'] = "Could not fetch data...";
	}	
}else{
	$ret['status'] = 400;
	$ret['err'] = "Invalid text";
}

echo json_encode($ret);

?>
