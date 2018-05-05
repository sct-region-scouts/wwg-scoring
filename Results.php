<?

// You may copy this PHP section to the top of file which needs to access after login.

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
include 'tools/Functions.php';
include 'tools/DBConnect.php';

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="refresh" content="40" > 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<title>WWG </title>
<link href="css/style.css"rel="stylesheet" type="text/css" />
</head>


<body>
<div class="logo"><h1><img src="images/logo.png" alt="WWG" /></h1></div>
<h2 class="head">Results</h2>

<p>

<?


/* Query SQL Server for the login of the user accessing the

database. */

$sql = "SELECT * FROM vwwwgfinalscore";
//$sql1 = "SELECT  scan.IDPatrol, scan.ScanTime, scan.IDBaseCode, scan.Comment, scanvalueresult.ResultValue, scanvalueresult.Result
//			FROM scan INNER JOIN scanvalueresult ON scan.ScanTime=scanvalueresult.ScanTime";

	$result = $db->query($sql);
	echo "<table>";
	echo "<tr>";
    echo '<td><b>Patrol ID</b></td><td><b>Group Name</b></td><td><b>Patrol Name</b></td><td><b>Handicap</b></td><td><b>Score</b></td><td><b>Mod Score</b></td>';
	echo "</tr>";
	while($row = $result->fetch_assoc()){
		echo "<tr>";
	    echo "<td>" . $row['GameTag'] . "</td><td>" . $row['GroupName'] . "</td><td>" . $row['PatrolName'] . "</td><td>" . $row['Handicap	'] . "</td><td>" . $row['Score'] . "</td><td>" . $row['ModScore'] . '</td>';
		echo "</tr>";
	}
echo "</table>";
 $result->close();

?>

<br>

</p>
<div class="menu">
<p><a href="admin.php">Back</a></p>
</div>
<div class="footer">
   <p><? Echo $_SERVER['REMOTE_ADDR']; ?> - WWG NFC Scoring System - Created by Aiden Mayberry</p>
</div>
</body>
<script type="text/javascript" src="tools/Admin.js"></script>
<script>

//AutoRefreshPage();

</script>
</html>

<?
ob_flush();
flush();
//trigger upload of offline data
include 'tools/RemoteDBConnect.php';
UploadOfflineResults();
SyncScanTable();
SyncScanValueResultTable();
SyncItems();
?>