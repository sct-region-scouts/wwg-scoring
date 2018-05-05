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




	
$UpdateConfig=$_POST['UpdateConfig'];
if($UpdateConfig)
	{
	$EditGameName=$_POST["GameName"];
	$EditIDDevice=$_POST["IDDevice"];
	if ($_POST['Remote']=="on")
		{$EditRemote="1";}
	else
		{$EditRemote="0";}

		$sql = "UPDATE tblgameconfig SET GameName='$EditGameName', Remote='$EditRemote', DeviceName='$EditIDDevice'
			WHERE GameID='1'";
			if ($db->query($sql) === TRUE)
			{
				$message = "Updated Game Config";
			}
			else
			{
				//echo $ScanTime;
				$message = "Update Failed, All Fields are Required.";
				$message = "Error: Writing to tblgameconfig Table. Contact Admin. <br>" . $db->error . "<br>" . $sql;	
			}
	}

	//Get current Values for Game config.
	$sql = "SELECT * FROM tblgameconfig";
	$result = $db->query($sql);
	$row = $result->fetch_assoc();
	$GameName=$row['GameName'];
	$Remote=$row['Remote'];
	$IDDevice=$row['DeviceName'];
	$result->close();



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
<h2 class="head">Game Config</h2>

<div class="content">


 <div align="center">
    <table width="400" border="0.5">
      <tr>

		<td width="180">
		<form id="form1" name="frmGameConfig" method="post"action="adminGameConfig.php">
	    	Game Name: </td><td width="220">
	     <? echo '<input name="GameName" type="text" Value="' . $GameName. '" id="GameName" size="40" />'; ?>
	     </td></tr>
	     
	    <tr><td>Remote: </td><td>
	     <? if($Remote=="1")
					{echo '<input name="Remote" type="checkbox" id="Remote" checked />';} 
				else 
					{echo '<input name="Remote" type="checkbox" id="Remote"/>';} 
		
		echo	'</td></tr>';
		echo '<tr><td>Device ID: </td><td> <input name="IDDevice" type="text" Value="' . $IDDevice. '" id="IDDevice" size="40" />'; 
		echo	'</td></tr>';
		
		?>

						
	     </table></div>

	   	<p align="center"><input type="submit" value="Update" name="UpdateConfig" id="UpdateConfig"></p>';

	</form>
<? Echo "<p>" . $message . "</p>"; ?>

<br>

</p>
</div>

<div class="menu">
<p><a href="admin.php">Back</a></p>
</div>

<div class="footer">
   <p><? Echo $_SERVER['REMOTE_ADDR']; ?> - WWG NFC Scoring System - Created by Aiden Mayberry</p>
</div>
</body>
</html>

