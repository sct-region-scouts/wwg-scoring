<?

// You may copy this PHP section to the top of file which needs to access after login.

session_start(); // Use session variable on this page. This function must put on the top of page.

if(!isset($_SESSION['username'])) { // if session variable "username" does not exist.

	echo '<head>';
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
	echo '<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />';
	echo '<title>WWG </title>';
	echo '<link href="css/style.css"rel="stylesheet" type="text/css" />';
	echo '</head>';
	echo '<body><div class="Menu"><p align="center">Access Denied</p>';
	echo '<div class="logo"><h1><img src="images/AccessDenied.gif" alt="WWG" /></h1></div>';
	echo "<a href=index.php>Back</a><br>";
	echo "<a href=login.php>Log In</a>";
	echo "</div></body>";
	exit;

//header("location:../login.php"); // Re-direct to login.php

}

if ($_SESSION['authlevel']>1)
{
	echo '<head>';
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
	echo '<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />';
	echo '<title>WWG </title>';
	echo '<link href="css/style.css"rel="stylesheet" type="text/css" />';
	echo '</head>';
	echo '<body><div class="Menu"><p align="center">Access Denied</p>';
	echo '<div class="logo"><h1><img src="images/AccessDenied.gif" alt="WWG" /></h1></div>';
	echo "<a href=index.php>Back</a><br>";
	echo "<a href=login.php>Log In</a>";
	echo "</div></body>";
	exit;
}



//Add tools

include 'tools/config.php';
include 'tools/DBConnect.php';
	require_once( 'classes/SyncData.php');
	



?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<title>WWG</title>
<link href="css/style.css"rel="stylesheet" type="text/css" />
</head>


<body>

<div class="logo"><h1><img src="images/logo.png" alt="WWG" /></h1></div>
<h2 class="head">Admin</h2>

<? 
	$SyncData = new SyncData();
	
		
	
	if($_GET["syncBases"]){
		echo "Syncing DB...<br>";
		//Output Current Buffer to screen
		ob_flush();
		flush();	
		$SyncData->SyncBases();
		$message = "Bases Synced";
	}
	
	if($_GET["syncAct"]){
		echo "Syncing DB...<br>";
		//Output Current Buffer to screen
		ob_flush();
		flush();
		$SyncData->SyncActivities();
		$message = "Activities Synced";
	}
	
	if($_GET["syncPatrols"]){
		echo "Syncing DB...<br>";
		//Output Current Buffer to screen
		ob_flush();
		flush();
		$SyncData->SyncPatrols();
		$message = "Patrols Synced";
	}
	
	if($_GET["syncAll"]){
		echo "Syncing DB...<br>";
		//Output Current Buffer to screen
		ob_flush();
		flush();
		
		$SyncData->SyncPatrols();
		echo "Patrols Synced<br>";

		ob_flush();
		flush();
		
		$SyncData->SyncActivities();
		echo "Activities Synced<br>";

		ob_flush();
		flush();
		
		$SyncData->SyncBases();
		echo "Bases Synced<br>";
	}
	
	if($_GET["ClearScan"] == 1){
		echo "Warning!!<br>";
		echo "This will clear the Scan table and ScanValueResult Table.<br>";
		echo 'Clear Tables? <a href="adminDB.php?ClearScan=2">Yes</a><br>';
	}
	
	if($_GET["ClearScan"] == 2){
		echo "Clearing Tables...<br>";
		//Output Current Buffer to screen
		ob_flush();
		flush();
		$SyncData->Tuncatetblscan();
		echo "Cleared Scan Tables.<br>";
	}
	
	if($_GET["ClearBaseSignIn"] == 1){
		echo "Warning!!<br>";
		echo "This will clear the BaseSignIn Table.<br>";
		echo 'Clear Tables? <a href="adminDB.php?ClearBaseSignIn=2">Yes</a><br>';
	}
	
	if($_GET["ClearBaseSignIn"] == 2){
		echo "Clearing Tables...<br>";
		//Output Current Buffer to screen
		ob_flush();
		flush();
		$SyncData->TuncateBaseSignIn();
		echo "Cleared Sign In Tables.<br>";
	}
	
	
		

echo $message; 

?>
<div class="menu">
<p><a href="adminDB.php?syncPatrols=1">Sync Patrols</a></p>
<p><a href="adminDB.php?syncBases=1">Sync Bases</a></p>
<p><a href="adminDB.php?syncAct=1">Sync Activities</a></p>
<p><a href="adminDB.php?syncAll=1">Sync All</a></p>
<p><a href="adminDB.php?ClearScan=1">Clear Scan Tables</a></p>
<p><a href="adminDB.php?ClearBaseSignIn=1">Clear Signin Table</a></p>
<p><a href="adminDBEMS.php">EMS Sync</a></p>
<p><a href="admin.php">Back</a></p>
</div>

<div class="footer">
 <p><? Echo $_SERVER['REMOTE_ADDR']; ?> - WWG NFC Scoring System - Created by Aiden Mayberry</p>
 </div>
</body>
</html>

