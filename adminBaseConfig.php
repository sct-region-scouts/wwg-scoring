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
include 'tools/DBConnect.php';
require_once( 'classes/SyncData.php');
	if($_GET["sync"]){
		$SyncData = new SyncData();
		$SyncData->SyncBases();
	}

if($_GET["Edit"])
	{
		$EditBase=$_GET["Edit"];
			$sql = "select * from tblbases where BaseID='$EditBase'";
			$result = $db->query($sql);

			/* Retrieve and display the results of the query. */
			$row = $result->fetch_assoc();
		    if ($row)
			{
					$BaseID=$row["BaseID"];
					$BaseName=$row["BaseName"];
					$BaseCode=$row["BaseCode"];
					$RandomEvents=$row["RandomEvents"];
					$RandomChance=$row["RandomChance"];
					$RandomListID=$row["RandomListID"];
					
					
			}
	}
	
if($_GET["Del"])
	{
		$DelBase=$_GET["Del"];
		$message = "Deleting Base " . $DelBase;
		//Delete all Base activities
		$sql = "DELETE FROM tblactivities where BaseID='$DelBase'";
		$result = $db->query($sql);
	
		//Delete Base
		$sql = "DELETE FROM tblbases where BaseID='$DelBase'";
		$result = $db->query($sql);
		
	}
	
$UpdateBase=$_POST['EditBase'];
if($UpdateBase)
{
	$EditBaseID=$_POST["BaseID"];
	$EditBaseName=$_POST["BaseName"];
	$EditBaseCode=$_POST["BaseCode"];
	$EditRandomChance=$_POST["RandomChance"];
	$EditRandomListID=$_POST["RandomListID"];
	
	if ($_POST['RandomEvents']=="on")
		{$EditRandomEvents="1";}
	else
		{$EditRandomEvents="0";}
	
	if ($EditBaseID != "" and $EditBaseName!= "" and  $EditBaseCode!= "")
	{
		$sql = "UPDATE tblbases SET BaseID='$EditBaseID',BaseName='$EditBaseName', BaseCode='$EditBaseCode', RandomEvents='$EditRandomEvents', RandomChance='$EditRandomChance', RandomListID='$EditRandomListID'
						WHERE BaseID='$EditBaseID'";
			if ($db->query($sql) === TRUE)
			{
				$message = "Updated Base";
			}
			else
			{
				//echo $ScanTime;
				$message = "Error: Writing to tblbases Table. Contact Admin. <br>" . $db->error . "<br>" . $sql;	
			}
	}
	else
	{
		$message = "Update Failed, All Fields are Required.";
	}
}

$AddBase=$_POST['AddBase'];
if($AddBase)
{

	$AddBaseID=$_POST["BaseID"];
	$AddBaseName=$_POST["BaseName"];
	$AddBaseCode=$_POST["BaseCode"];
	$AddRandomChance=$_POST["RandomChance"];
	$AddRandomListID=$_POST["RandomListID"];
	if ($_POST['RandomEvents']=="on")
		{$AddRandomEvents="1";}
	else
		{$AddRandomEvents="0";}
	
	if ($AddBaseID!= "" and $AddBaseName!= "" and  $AddBaseCode!= "")
	{
		$sql = "INSERT INTO tblbases
                         (BaseID, BaseName, BaseCode, RandomEvents, RandomChance, RandomListID)
							VALUES        ('$AddBaseID','$AddBaseName','$AddBaseCode','$AddRandomEvents', '$AddRandomChance', '$AddRandomListID')";
			if ($db->query($sql) === TRUE)
			{
				$message = "Added Base";
			}
			else
			{
				echo $ScanTime;
				$message = "Error: Writing to tblBases Table. Contact WWG Admin. <br>" . $db->error . "<br>" . $sql;	
			}
	}
	else
	{
		$message = "Add Failed, All Fields are Required.";
	}
	
	
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
if ($EditBase)
	{
		echo "<p><b>Edit Base </b></p>";
	}
	else
	{
		echo "<p><b>Add Base </b></p>";
	}
?>

 <div align="center">
    <table width="400" border="0.5">
      <tr>

		<td width="250">
		<form id="form1" name="frmBase" method="post"action="adminBaseConfig.php">
	    	BaseID: </td><td width="150">
	     <? echo '<input name="BaseID" type="text" Value="' . $BaseID . '" id="BaseID" size="2" maxlength="2" />'; ?>
	     </td></tr>
	     <tr><td> Base Name: </td><td>
	     <? echo '<input name="BaseName" type="text" Value="' . $BaseName . '" id="BaseName" size="25" maxlength="50" />'; ?>
	     </td></tr>
	     <tr><td> Base Code: </td><td>
	     <? echo '<input name="BaseCode" type="text" Value="' . $BaseCode . '" id="BaseCode" size="13" maxlength="12" />'; ?>
	     </td></tr>
	     <tr><td> Trigger Random Events: </td><td>
	     <? if($RandomEvents=="1")
					{echo '<input name="RandomEvents" type="checkbox" id="RandomEvents" checked />';} 
				else 
					{echo '<input name="RandomEvents" type="checkbox" id="RandomEvents"/>';} 
		?>
	     </td></tr>
	     <tr><td> Chance of Random Events: </td><td>
					<select name="RandomChance">
					<?
					$i = 0;
					while ($i < 10)
						{
						    if ($RandomChance == $i)
						    {
						        $selected = 'selected="selected"';
						    }
						    else
						    {
						    $selected = '';
						    }
						    $b = $i + 1;
						    echo  $selected;
						    echo '<option value="' . $i . '" ' . $selected . '>' .$b .'</option>';
						    $i++;
						}
					?>	
					</select>
							
					
	     </td></tr>
	     <tr id="randomgenrow"><td>Random Events List: </td><td>
	     
				<select name="RandomListID">
	     <?
				$sql = "SELECT * FROM tblrandomlists";
				$result = $db->query($sql);
				while($row = $result->fetch_assoc()){
					if ($RandomListID== $row["ListID"])
						    {
						        $selected = 'selected="selected"';
						    }
						    else
						    {
						    $selected = '';
						    }
    				echo '<option value="'.$row["ListID"].'" ' . $selected . '>'.$row["ListName"].'</option>';
				}
 				$result->close();
		?>
	     </select> 
							
					
	     </td></tr>
	     </table></div>
	     <? if ($EditBase)
		{
	    	echo '<p align="center"><input type="submit" value="Edit Base" name="EditBase" id="EditBase"></p>';
	    }
	    else
	    {
	    	echo '<p align="center"><input type="submit" value="Add Base" name="AddBase" id="AddBase"></p>';
	    } ?>
	</form>
<? Echo "<p>" . $message . "</p>"; ?>
<div align="center">
<p><b>Bases </b></p>
<?




echo '<table class="thintable" border="1"><tr><td><b>Base ID</b></td><td><b>Base Name</b></td><td><b>Base Code</b></td><td Colspan=3><b>Options</b></td></tr>';
/* Query SQL Server for Bases listed 
in the database. */
$sql = "SELECT * FROM tblbases ORDER BY BaseID ASC";
$result = $db->query($sql);
while($row = $result->fetch_assoc()){

    echo '<tr><td>'.$row['BaseID'] . "</td><td>" . $row['BaseName'] . "</td><td>" . $row['BaseCode'] . '</td><td class="menuInline"><a href="adminBaseConfig.php?Edit=' . $row['BaseID'] . '">Edit Base</a>' . '</td><td class="menuInline"><a href="adminActivityConfig.php?Base=' . $row['BaseID'] . '">Edit Activities</a></td><td class="menuInline"><a href="adminBaseConfig.php?Del=' . $row['BaseID'] . '">Delete Base</a></td></tr>';

}
echo '</table>';
 $result->free();


?>
</div>
<br>

</p>
</div>

<div class="menu">
<p><a href="adminBaseConfig.php?sync=1">Sync Bases from Server</a></p>
<p><a href="admin.php">Back</a></p>
</div>

<div class="footer">
  <p>WWG NFC Scoring System - Created by Aiden Mayberry</p>
</div>
</body>
</html>

