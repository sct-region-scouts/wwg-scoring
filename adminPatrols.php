<?

	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);

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
	if($_GET["sync"]){
		$SyncData = new SyncData();
		$SyncData->SyncPatrols();
	}

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
<h2 class="head">Admin - Patrols</h2>

<div class="content">


	<div align="center">
		<table><tr><td><b>GameTag</b></td><td><b>Patrol Name</b></td></tr>
		<?
		
		
		$sql = "SELECT * FROM tblpatrol";
		$result = $db->query($sql);
		while($row = $result->fetch_assoc()){
		    echo '<tr><td>' .$row['GameTag'] . "</td><td> " . $row['PatrolName'] . '</td></tr>';
		}
		 $result->close();
		
		?>
		</table>
	</div>
<br>

</p>
</div>
<div class="menu">
<p><a href="adminPatrols.php?sync=1">Sync Patrols</a></p>
<p><a href="admin.php">Back</a></p>
</div>
<div class="footer">
 <p><? Echo $_SERVER['REMOTE_ADDR']; ?> - WWG NFC Scoring System - Created by Aiden Mayberry</p>
 </div>
</body>
</html>

