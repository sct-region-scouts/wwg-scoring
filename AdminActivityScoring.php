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
//require_once( 'classes/SyncData.php');

$Update=$_POST['Update'];
if($Update)
{
	//echo "Update data";
	
	for($i=0; $i<count($_POST['ActivityID']); ++$i){

		//Update Scoring Data
		$ActivityID= $_POST['ActivityID'][$i];
		$Scoring_Success= $_POST['Scoring_Success'][$i];
		$Scoring_Fail= $_POST['Scoring_Fail'][$i];
		$Scoring_Value= $_POST['Scoring_Value'][$i];
		//echo $ActivityID. ' <br>';
		
		$sql = "UPDATE tblactivities SET Scoring_Success='$Scoring_Success',Scoring_Fail='$Scoring_Fail',Scoring_Value='$Scoring_Value' WHERE ActivityID='$ActivityID'";

		//echo $sql .'<br>';

			if ($db->query($sql) === TRUE)

			{

				$message = "<b>Updated</b>";

			}
			else
			{
				$message = "<b>Update Failed</b>";
			}
			
	}

	
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
<h2 class="head">Admin - Scoring</h2>
<div align="center">
<? 

echo $message; 

$sql = "select * from tblactivities ORDER BY BaseID ASC";

$result = $db->query($sql);
echo '<br>';
echo '<form id="frmScore" name="frmScore" method="post"action="AdminActivityScoring.php?update=1"/>';
echo '<table class="thintable" border="1"><tr><td><b>Base ID</b></td><td><b>Activity Name</b></td><td><b>Activity<br>Code</b></td><td><b>Success</b></td><td><b>Fail</b></td><td><b>Value<br>Result</b></td></tr>';
while($row = $result->fetch_assoc()){
	
	echo '<tr class="menuInline"><td>'.$row['BaseID'] . "</td>
    <td>" . $row['ActivityName'] . "</td>";
	echo "<td>" . $row['ActivityCode'] . "</td>";
	echo '<td><input type="hidden" id="ActivityID" name="ActivityID[]" Value="' . $row['ActivityID'] . '"/>';
    echo '<input id="Scoring_Success" name="Scoring_Success[]" type="number" Value="' . $row['Scoring_Success'] . '"  size="3" maxlength="3"/></td>';
	echo '<td><input id="Scoring_Fail" name="Scoring_Fail[]" type="number" Value="' . $row['Scoring_Fail'] . '"  maxlength="3"/></td>';
	echo '<td witdh=20px><input id="Scoring_Value" name="Scoring_Value[]" type="text" Value="' . $row['Scoring_Value'] . '"  size="4" maxlength="4"/></td>';
	
	}
echo '</table>';
echo '<p align="center"><input class="button" type="submit" value="Save" name="Update" id="Update"></p>';
echo '</form>';



?>
</div>
<div class="menu">
<p><a href="admin.php">Back</a></p>
</div>

<div class="footer">
 <p><? Echo $_SERVER['REMOTE_ADDR']; ?> - WWG NFC Scoring System - Created by Aiden Mayberry</p>
 </div>
</body>
</html>

