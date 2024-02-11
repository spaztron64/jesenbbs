<?php
	error_reporting(0);
	if($_GET['lang'] == "en")
		$_SESSION['lang'] = "en";
	else if ($_GET['lang'] == "jp")
		$_SESSION['lang'] = "jp";
	else if ($_GET['lang'] == "sr")
		$_SESSION['lang'] = "sr";
	if(!isset($_GET['lang']) && !isset($_SESSION['lang']))
		$_SESSION['lang'] = "en";
	error_reporting(-1);
	
	include($_SESSION['lang'] . '.php');
?>