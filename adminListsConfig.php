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

if($_GET["Edit"])
	{
		$EditList=$_GET["Edit"];
			$sql = "select * from tblrandomlists where ListID='$EditList'";
			$result = $db->query($sql);

			/* Retrieve and display the results of the query. */
			$row = $result->fetch_assoc();
		    if ($row)
			{
					$ListID=$row["ListID"];
					$ListName=$row["ListName"];
					
			}
	}
	
if($_GET["Del"])
	{
		$DelList=$_GET["Del"];
		$message = "Deleting List " . $DelBase;
		//Delete all List Values
		$sql = "DELETE FROM tblrandomvalues where ListID='$DelList'";
		$result = $db->query($sql);
	
		//Delete Base
		$sql = "DELETE FROM tblrandomlists where ListID='$DelList'";
		$result = $db->query($sql);
		
	}
	
$UpdateList=$_POST['EditList'];
if($UpdateList)
{
	$EditListID=$_POST["ListID"];
	$EditListName=$_POST["ListName"];

	if ($EditListID != "" and $EditListName!= "")
	{
		$sql = "UPDATE tblrandomlists SET ListID='$EditListID',ListName='$EditListName'
						WHERE ListID='$EditListID'";
			if ($db->query($sql) === TRUE)
			{
				$message = "Updated List";
			}
			else
			{
				//echo $ScanTime;
				$message = "Error: Writing to tbllist Table. Contact Admin" . $DB->error . "<br>" . $sql;	
			}
	}
	else
	{
		$message = "Update Failed, All Fields are Required.";
	}
}

$AddList=$_POST['AddList'];
if($AddList)
{

	$AddListID=$_POST["ListID"];
	$AddListName=$_POST["ListName"];
	
	if ($AddListID!= "" and $AddListName!= "")
	{
		$sql = "INSERT INTO tblrandomlists
                         (ListID, ListName)
							VALUES        ('$AddListID','$AddListName')";
			if ($db->query($sql) === TRUE)
			{
				$message = "Added List";
			}
			else
			{
				echo $ScanTime;
				$message = "Error: Writing to tbllists Table. Contact WWG Admin." . $DB->error . "<br>" . $sql;	
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
<h1 class="head">Admin</h1>

<div class="content">

<?
if ($EditList)
	{
		echo "<p><b>Edit List </b></p>";
	}
	else
	{
		echo "<p><b>Add List </b></p>";
	}
?>

 <div align="center">
    <table width="400" border="0.5">
      <tr>

		<td width="250">
		<form id="form1" name="frmList" method="post"action="adminListsConfig.php">
	    	ListID: </td><td width="150">
	     <? echo '<input name="ListID" type="text" Value="' . $ListID . '" id="ListID" size="2" maxlength="3" />'; ?>
	     </td></tr>
	     <tr><td> List Name: </td><td>
	     <? echo '<input name="ListName" type="text" Value="' . $ListName . '" id="ListName" size="25" maxlength="25" />'; ?>
	     </td></tr></table></div>
	     <? if ($EditList)
		{
	    	echo '<p align="center"><input type="submit" value="Edit List" name="EditList" id="EditList"></p>';
	    }
	    else
	    {
	    	echo '<p align="center"><input type="submit" value="Add List" name="AddList" id="AddList"></p>';
	    } ?>
	</form>
<? Echo "<p>" . $message . "</p>"; ?>
<div align="center">
<p><b>Current Lists </b></p>
<?




echo '<table border="1"><tr><td><b>List ID</b></td><td><b>List Name</b></td><td Colspan=3><b>Options</b></td></tr>';
/* Query SQL Server for Bases listed 
in the database. */
$sql = "SELECT * FROM tblrandomlists";
$result = $db->query($sql);
while($row = $result->fetch_assoc()){

    echo '<tr><td>'.$row['ListID'] . "</td><td>" . $row['ListName'] . '</td><td><a href="adminListsConfig.php?Edit=' . $row['ListID'] . '">Edit List</a>' . '</td><td><a href="adminListsValuesConfig.php?List=' . $row['ListID'] . '">Edit List Values</a></td><td><a href="adminListsConfig.php?Del=' . $row['ListID'] . '">Delete List</a></td></tr>';

}
echo '</table>';
 $result->free();


?>
</div>
<br>

</p>
</div>

<div class="menu">
<p><a href="admin.php">Back</a></p>
</div>

<div class="footer">
  <p>WWG NFC Scoring System</a></p>
</div>
</body>
</html>

