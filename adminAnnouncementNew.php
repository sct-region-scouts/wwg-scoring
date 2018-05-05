<?

//uncomment the below to 2 lines for debugging.
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	
	ob_implicit_flush(true);


session_start(); // Use session variable on this page. This function must put on the top of page.

if(!isset($_SESSION['username'])) { // if session variable "username" does not exist.

	echo "Access Denied<br>";
	echo "<a href=login.php>Log In</a>";
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
require_once( 'classes/Announcements.php');

$Announcements = new Announcements();

$AddAnnouncement=$_POST['AddAnnouncement'];
if($AddAnnouncement)
{

	$AlertType=$_POST["AlertType"];
	$TabletID=$_POST["TabletID"];
	$IDPatrol=$_POST["IDPatrol"];
	$AlertMsg=$_POST["AlertMsg"];
	$Announcements->InsertAnnouncement($AlertType, $TabletID, $IDPatrol, $AlertMsg);
	
}



?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<title>WWG </title>
<link href="css/style.css"rel="stylesheet" type="text/css" />
</head>


<body>
<div class="logo"><h1><img src="images/logo.png" alt="WWG" /></h1></div>
<h2 class="head">Admin</h2>

<div class="content">

<?

		echo "<p><b>New Announcement </b></p>";

?>

 <div align="center">
 	<? Echo "<p>" . $message . "</p>"; ?>
    <table width="400" border="0.5">
      <tr>

		<td width="250">
		<form id="FrmAnnouncement" name="FrmAnnouncement" method="post"action="adminAnnouncementNew.php">
	    	Alert Type: </td><td width="150">
	     <? echo '<input name="AlertType" type="text" Value="' . $AlertType . '" id="AlertType" size="2" maxlength="2" />'; ?>
	     </td></tr>
	     <tr><td> Tablet ID: </td><td>
	     <? echo '<input name="TabletID" type="text" Value="' . $TabletID . '" id="TabletID" size="2" maxlength="3" />'; ?>
	     </td></tr>
	     <tr><td> Patrol ID: </td><td>
	     <? echo '<input name="IDPatrol" type="text" Value="' . $IDPatrol . '" id="IDPatrol" size="8" maxlength="6" />'; ?>
	     </td></tr>
			<tr><td> Alert Message: </td><td>
	     <? echo '<input name="AlertMsg" type="text" Value="' . $AlertMsg . '" id="AlertMsg" size="100" maxlength="255" />'; ?>
	     </td></tr>				
				
	     </table></div>
	     <?

	    	echo '<p align="center"><input type="submit" value="Add Announcement" name="AddAnnouncement" id="AddAnnouncement"></p>';
	    ?>
	</form>
<br />
</div>
<div class="menu">
<p><a href="admin.php">Back</a></p>
</div>

<div class="footer">
  <p>WWG NFC Scoring System - Created by Aiden Mayberry</p>
</div>
</body>
</html>

