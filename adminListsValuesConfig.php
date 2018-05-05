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

if($_GET["List"])
	{
		$CurrentList=$_GET["List"];
			$sql = "select * from tblrandomlists where ListID='$CurrentList'";
			$result = $db->query($sql);

			/* Retrieve and display the results of the query. */
			$row = $result->fetch_assoc();
		    if ($row)
			{
					$ListID=$row["ListID"];
					$ListName=$row["ListName"];
					
			}
	}

if($_GET["Edit"])
	{
			$EditValues=$_GET["Edit"];
			$sql = "select * from tblrandomvalues where ValueID='$EditValues'";
			$result = $db->query($sql);

			/* Retrieve and display the results of the query. */
			$row = $result->fetch_assoc();
		    if ($row)
			{
					$ValueID=$row["ValueID"];
					$ListID=$row["ListID"];
					$ListValue=$row["ListValue"];

			}
	}
	
$EditValue=$_POST['EditValue'];
if($EditValue)
{
	$EditValueID=$_POST["ValueID"];
	$EditListValue=$_POST["ListValue"];

	
	if ($EditValueID!= "" and $EditListValue!= "")
	{
		$sql = "UPDATE tblrandomvalues SET ListValue='$EditListValue'
						WHERE ValueID='$EditValueID'";
			if ($db->query($sql) === TRUE)
			{
				$message = "Updated List Value";
			}
			else
			{
				echo $ScanTime;
				$message = "Error: Writing to tblrandomvalues Table." . $DB->error . "<br>" . $sql;	
			}
	}
	else
	{
		$message = "Update Failed, All Fields are Required.";
	}
}

if($_GET["Del"])
	{
		$DelValue=$_GET["Del"];
		$message = "Deleting Value " . $DelValue;
		//Delete List Values
		$sql = "DELETE FROM tblrandomvalues where ValueID='$DelValue'";
		$result = $db->query($sql);
		
	}

$AddValue=$_POST['AddValue'];
if($AddValue)
{


	$AddListValue=$_POST["ListValue"];
		
	if ($AddListValue!= "")
	{
		$sql = "INSERT INTO tblrandomvalues
                         (ListID, ListValue)
							VALUES        ('$ListID','$AddListValue')";
			if ($db->query($sql) === TRUE)
			{
				$message = "Added List Value";
			}
			else
			{
				echo $ScanTime;
				$message = "Error: Writing to tblrandomvalues Table. Contact WWG Admin." . $DB->error . "<br>" . $sql;	
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
		echo "<p><b>Edit Value</b></p>";
	}
	else
	{
		echo "<p><b>Add Value </b></p>";
	}
?>

 <div align="center">
    <table width="300" border="0.5">
      <tr>

		<td width="200">
		<? echo '<form id="form1" name="frmListValues" method="post"action="adminListsValuesConfig.php?List=' . $CurrentList . '"/>';
		 echo '<input type="hidden" name="ValueID" Value="' . $ValueID. '" />'; ?>
	    	List Value: </td><td width="100">
	     <? echo '<input name="ListValue" type="text" Value="' . $ListValue. '" id="ListValue" size="30" />';
    		echo '</td></tr>';	

	   	?>
	     </table></div>
	     <? if ($EditValues)
		{
	    	echo '<p align="center"><input type="submit" value="Edit Value" name="EditValue" id="EditValue">';
	    	echo '<input type="submit" value="Cancel" name="Cancel" id="Cancel"></p>';
	    }
	    else
	    {
	    	echo '<p align="center"><input type="submit" value="Add Value" name="AddValue" id="AddValue"></p>';
	    } ?>
	</form>
<? Echo "<p>" . $message . "</p>"; ?>
<div align="center">
<p><b>Values for <? Echo $ListName;?></b></p>
<?




/* Query SQL Server for the login of the user accessing the

database. */

$sql = "select * from tblrandomvalues where ListID='$ListID'";

$result = $db->query($sql);

echo '<table border="1"><tr><td><b>List Value</b></td><td Colspan=2><b>Options</b></td></tr>';
while($row = $result->fetch_assoc()){

    echo '<tr><td>'.$row['ListValue'] . "</td>";
    echo '<td><a href="adminListsValuesConfig.php?List=' . $CurrentList . '&Edit=' . $row['ValueID'] . '">Edit Value</a>' . '</td><td><a href="adminListsValuesConfig.php?List='. $ListID. '&Del=' . $row['ValueID'] . '">Delete</a></td></tr>';

}
echo '</table>';
 $result->close();


?>
</div>
<br>

</p>
</div>

<div class="menu">
<p><a href="adminListsConfig.php">Back</a></p>
</div>

<div class="footer">
  <p>WWG NFC Scoring System</a></p>
</div>
</body>
</html>

