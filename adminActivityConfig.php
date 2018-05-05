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
	echo '<title>WWG</title>';
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

if($_GET["Base"])
	{
		$CurrentBase=$_GET["Base"];
			$sql = "select * from tblbases where BaseID='$CurrentBase'";
			$result = $db->query($sql);

			/* Retrieve and display the results of the query. */
			$row = $result->fetch_assoc();
		    if ($row)
			{
					$BaseID=$row["BaseID"];
					$BaseName=$row["BaseName"];
					$BaseCode=$row["BaseCode"];
			}
	}

if($_GET["Edit"])
	{
			$EditActivity=$_GET["Edit"];
			$sql = "select * from tblactivities where ActivityID='$EditActivity'";
			$result = $db->query($sql);

			/* Retrieve and display the results of the query. */
			$row = $result->fetch_assoc();
		    if ($row)
			{
					$ActivityID=$row["ActivityID"];
					$ActivityName=$row["ActivityName"];
					$ActivityCode=$row["ActivityCode"];
					$ValueResultName=$row["ValueResultName"];
					$ValueResultField=$row["ValueResultField"];
					$ValueResultName2=$row["ValueResultName2"];
					$ValueResultField2=$row["ValueResultField2"];
					$SuccessFailResultField=$row["SuccessFailResultField"];
					$Trade=$row["Trade"];
					$CommentField=$row["CommentField"];
					$ActivityType=$row["ActivityType"];
					$RandomGen=$row["RandomGen"];
					$RandomGenListID=$row["RandomGenListID"];
					$Bank=$row["Bank"];
					$DisableWithdrawal=$row["DisableWithdrawal"];
					$Alert=$row["Alert"];
					$AlertCount=$row["AlertCount"];
					$AlertMessage=$row["AlertMessage"];
					$Ranking=$row["Ranking"];
					$DropDownField=$row['DropDownField'];
					$DropDownFieldListID=$row['DropDownFieldListID'];
					$PassBasedonValueResult = $row['PassBasedonValueResult'];
					$PassValue = $row['PassValue'];
			}
	}
	
$UpdateActivity=$_POST['EditActivity'];
if($UpdateActivity)
{
	$EditActivityID=$_POST["ActivityID"];
	$EditActivityName=$_POST["ActivityName"];
	$EditActivityCode=$_POST["ActivityCode"];
	
	if ($_POST['Bank']=="on")
		{$EditBank="1";}
	else
		{$EditBank="0";}
		
	if ($_POST['DisableWithdrawal']=="on")
		{$EditDisableWithdrawal="1";}
	else
		{$EditDisableWithdrawal="0";}
		
	if ($_POST['PassBasedonValueResult']=="on")
		{$EditPassBasedonValueResult="1";}
	else
		{$EditPassBasedonValueResult="0";}
		
	$EditPassValue=$_POST["PassValue"];
	
	$EditValueResultName=$_POST["ValueResultName"];
	if ($_POST['ValueResultField']=="on")
		{$EditValueResultField="1";}
	else
		{$EditValueResultField="0";}
		
	$EditValueResultName2=$_POST["ValueResultName2"];
	
	
	if ($_POST['ValueResultField2']=="on")
		{$EditValueResultField2="1";}
	else
		{$EditValueResultField2="0";}	
	
	
	if ($_POST['SuccessFailResultField']=="on")
		{$EditSuccessFailResultField="1";}
	else
		{$EditSuccessFailResultField="0";}
		
		
	if ($_POST['Trade']=="on")
		{$EditTrade="1";}
	else
		{$EditTrade="0";}	
	
		if ($_POST['Ranking']=="on")
		{$EditRanking="1";}
	else
		{$EditRanking="0";}	
		
	if ($_POST['Alert']=="on")
		{$EditAlert="1";}
	else
		{$EditAlert="0";}	
	$EditAlertMessage =$_POST["AlertMessage"];
	if($_POST["AlertCount"])
		{
			$EditAlertCount=$_POST["AlertCount"];
		}
	else
		{
			$EditAlertCount=0;
		}
	
	if ($_POST['CommentField']=="on")
		{$EditCommentField="1";}
	else
		{$EditCommentField="0";}
		
	if ($_POST['RandomGen']=="on")
		{$EditRandomGen="1";}
	else
		{$EditRandomGen="0";}
		

	
	$EditRandomGenListID=$_POST["RandomGenListID"];
	
	$EditActivityType=$_POST["ActivityType"];
	
	
	if ($EditActivityID!= "" and $EditActivityName!= "" and  $EditActivityCode!= "")
	{
		$sql = "UPDATE tblactivities 
					SET ActivityName='$EditActivityName', ActivityCode='$EditActivityCode', ValueResultName='$EditValueResultName', ValueResultField='$EditValueResultField', 
					ValueResultName2='$EditValueResultName2', ValueResultField2='$EditValueResultField2', Alert='$EditAlert', 
					AlertCount='$EditAlertCount', AlertMessage='$EditAlertMessage', Bank='$EditBank', DisableWithdrawal='$EditDisableWithdrawal',
					SuccessFailResultField='$EditSuccessFailResultField', Trade='$EditTrade', CommentField='$EditCommentField', RandomGen='$EditRandomGen', RandomGenListID='$EditRandomGenListID', ActivityType='$EditActivityType', Ranking='$EditRanking', PassBasedonValueResult='$EditPassBasedonValueResult', PassValue='$EditPassValue'
					WHERE ActivityID='$EditActivityID'";
			if ($db->query($sql) === TRUE)
			{
				$message = "Updated Activity";
			}
			else
			{
				echo $ScanTime;
				$message = "Error: Writing to tblBases Table." . $db->error . "<br>" . $sql;	
			}
	}
	else
	{
		$message = "Update Failed, All Fields are Required.";
	}
}

if($_GET["Del"])
	{
		$DelAct=$_GET["Del"];
		$message = "Deleting Activity " . $DelAct;
		//Delete Base activities
		$sql = "DELETE FROM tblactivities where ActivityID='$DelAct'";
		$result = $db->query($sql);
		
	}

$AddActivity=$_POST['AddActivity'];
if($AddActivity)
{


	$AddActivityName=$_POST["ActivityName"];
	$AddActivityCode=$_POST["ActivityCode"];
	$AddValueResultName=$_POST["ValueResultName"];
	
	if ($_POST['Bank']=="on")
		{$AddBank="1";}
	else
		{$AddBank="0";}
		
	if ($_POST['DisableWithdrawal']=="on")
		{$AddDisableWithdrawal="1";}
	else
		{$AddDisableWithdrawal="0";}
		
	if ($_POST['PassBasedonValueResult']=="on")
		{$AddPassBasedonValueResult="1";}
	else
		{$AddPassBasedonValueResult="0";}
		
	$AddPassValue=0;
	$AddPassValue=$_POST["PassValue"];
	
	if ($_POST['ValueResultField']=="on")
		{$AddValueResultField="1";}
	else
		{$AddValueResultField="0";}
		
	$AddValueResultName2=$_POST["ValueResultName2"];
	if ($_POST['ValueResultField2']=="on")
		{$AddValueResultField2="1";}
	else
		{$AddValueResultField2="0";}
	
	if ($_POST['SuccessFailResultField']=="on")
		{$AddSuccessFailResultField="1";}
	else
		{$AddSuccessFailResultField="0";}
		
	if ($_POST['Trade']=="on")
		{$AddTrade="1";}
	else
		{$AddTrade="0";}	
		
	if ($_POST['Ranking']=="on")
		{$AddRanking="1";}
	else
		{$AddRanking="0";}	
		
	if ($_POST['Alert']=="on")
		{$AddAlert="1";}
	else
		{$AddAlert="0";}
	$AddAlertMessage =$_POST["AlertMessage"];
	if($_POST["AlertCount"])
		{
			$AddAlertCount=$_POST["AlertCount"];
		}
	else
		{
			$AddAlertCount=0;
		}

	if ($_POST['CommentField']=="on")
		{$AddCommentField="1";}
	else
		{$AddCommentField="0";}
		
	if ($_POST['RandomGen']=="on")
		{$AddRandomGen="1";}
	else
		{$AddRandomGen="0";}
		
	$AddRandomGenListID=$_POST["RandomGenListID"];
	
	$AddActivityType=$_POST["ActivityType"];
	
	if ($AddActivityType!= "" and $AddActivityName!= "" and  $AddActivityCode!= "")
	{
		$sql = "INSERT INTO tblactivities
                         (BaseID, ActivityName, ActivityCode,ValueResultName, ValueResultField, ValueResultName2, ValueResultField2, SuccessFailResultField, Trade, CommentField, RandomGen,RandomGenListID, ActivityType, Alert, AlertCount, AlertMessage, Ranking, Bank, DisableWithdrawal, PassBasedonValueResult, PassValue)
							VALUES        ('$BaseID','$AddActivityName','$AddActivityCode','$AddValueResultName','$AddValueResultField','$AddValueResultName2','$AddValueResultField2','$AddSuccessFailResultField',
											'$AddTrade','$AddCommentField','$AddRandomGen','$AddRandomGenListID','$AddActivityType','$AddAlert','$AddAlertCount','$AddAlertMessage','$AddRanking','$AddBank','$AddDisableWithdrawal','$AddPassBasedonValueResult','$AddPassValue')";
			if ($db->query($sql) === TRUE)
			{
				$message = "Added Activity";
			}
			else
			{
				echo $ScanTime;
				$message = "<b>Error: Writing to tblactivities Table. Contact WWG Admin.<b><br>" . $db->error . "<br>" . $sql;	
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
<title>WWG  Activity Configuration</title>
<link href="css/style.css"rel="stylesheet" type="text/css" />
<script type="text/javascript" src="tools/jquery.min.js"></script>
<script type="text/javascript" src="tools/Admin.js"></script>
</head>


<body>
<div class="logo"><h1><img src="images/logo.png" alt="WWG" /></h1></div>
<h2 class="head">Admin</h2>

<div class="content">


<?
if ($EditActivity)
	{
		echo "<p><b>Edit Activity </b></p>";
	}
	else
	{
		echo "<p><b>Add Activity </b></p>";
	}
?>

 <div align="center">
    <table border="0.5">
      <tr>

		<td width="200">
		<? echo '<form id="form1" name="frmActvity" method="post"action="adminActivityConfig.php?Base=' . $CurrentBase . '"/>';
		 echo '<input type="hidden" name="ActivityID" Value="' . $ActivityID . '" />'; ?>
	    	Activity Name: </td><td width="100">
	     <? echo '<input name="ActivityName" type="text" Value="' . $ActivityName . '" id="ActivityName" size="25" maxlength="24"/>'; ?>
	     </td></tr>
	     <tr><td> Activity Code: </td><td colspan=2>
	     <? echo '<input name="ActivityCode" type="text" Value="' . $ActivityCode. '" id="ActivityCode" size="13" maxlength="12"/>'; ?>
	     </td></tr>

	     
	     <tr><td>Type of field</td><td>Name of Field</td><td>Active</td></tr>
	     <? 
	     	//display bank field
	     	echo '<tr><td>Bank:</td><td></td><td>';
    		if($Bank=="1")
					{echo '<input name="Bank" type="checkbox" id="Bank" checked />';} 
				else 
					{echo '<input name="Bank" type="checkbox" id="Bank"/>';}

    		echo '</td></tr>';
    		echo '<tr><td>Disable Withdrawal:</td><td></td><td>';
    		if($DisableWithdrawal=="1")
					{echo '<input name="DisableWithdrawal" type="checkbox" id="DisableWithdrawal" checked />';} 
				else 
					{echo '<input name="DisableWithdrawal" type="checkbox" id="DisableWithdrawal"/>';}

    		echo '</td></tr>';
     		echo '<tr><td> Value Result:</td><td><input name="ValueResultName" type="text" Value="' . $ValueResultName. '" id="ValueResultName" size="33" maxlength="40"/></td><td>';
    		if($ValueResultField=="1")
					{echo '<input name="ValueResultField" type="checkbox" id="ValueResultField" checked />';} 
				else 
					{echo '<input name="ValueResultField" type="checkbox" id="ValueResultField"/>';}

    		echo '</td></tr>';
    		
    		//Secound Value Result field, result will be record in the comment field in DB.
    		
    		echo '<tr><td> Value Result 2:</td><td><input name="ValueResultName2" type="text" Value="' . $ValueResultName2. '" id="ValueResultName2" size="33" maxlength="40"/></td><td>';
    		if($ValueResultField2=="1")
					{echo '<input name="ValueResultField2" type="checkbox" id="ValueResultField2" checked />';} 
				else 
					{echo '<input name="ValueResultField2" type="checkbox" id="ValueResultField2"/>';}

    		echo '</td><td>Note: this field will overwrite the <br>comment field when recording results in DB</td></tr>';
    		
			
			
    		echo '<tr><td> Pass\Fail on Value Result?:</td><td><input name="PassValue" type="number"  min=0 max=999 Value="' . $PassValue. '" id="PassValue" size="10" maxlength="3"/></td><td>';
    		if($PassBasedonValueResult=="1")
					{echo '<input name="PassBasedonValueResult" type="checkbox" id="PassBasedonValueResult" checked />';} 
				else 
					{echo '<input name="PassBasedonValueResult" type="checkbox" id="PassBasedonValueResult"/>';}
			
    		
    		
    		
    		echo '<tr><td> Success or Fail Result?:</td><td></td><td>';
    		if($SuccessFailResultField=="1")
					{echo '<input name="SuccessFailResultField" type="checkbox" id="SuccessFailResultField" checked />';} 
				else 
					{echo '<input name="SuccessFailResultField" type="checkbox" id="SuccessFailResultField"/>';}

    		echo '</td></tr>';
    		echo '<tr><td> Display Trade?:</td><td></td><td>';
    		if($Trade=="1")
					{echo '<input name="Trade" type="checkbox" id="Trade" checked />';} 
				else 
					{echo '<input name="Trade" type="checkbox" id="Trade"/>';}
					
			echo '</td></tr>';
    		echo '<tr><td> Display Ranking?:</td><td></td><td>';
    		if($Ranking=="1")
					{echo '<input name="Ranking" type="checkbox" id="Ranking" checked />';} 
				else 
					{echo '<input name="Ranking" type="checkbox" id="Ranking"/>';}

    		echo '</td></tr>';
    		echo '<tr><td> Comments?:</td><td></td><td>';
    		if($CommentField=="1")
					{echo '<input name="CommentField" type="checkbox" id="CommentField" checked />';} 
				else 
					{echo '<input name="CommentField" type="checkbox" id="CommentField"/>';}

    		echo '</td></tr>';
    		
    		echo '<tr><td>Alert?:</td><td>
    		<input id="AlertMessage" name="AlertMessage" type="text" Value="' . $AlertMessage. '"  size="33" maxlength="255"/></td><td>';
    		if($Alert=="1")
					{echo '<input name="Alert" type="checkbox" id="Alert" onclick="HideRandomGenFields();" checked />';} 
				else 
					{echo '<input name="Alert" type="checkbox" id="Alert" onclick="HideRandomGenFields();"/>';}
    		echo '</td></tr>';
    		echo '<tr><td>Alert Triggered:</td><td><input type="number"  min=0 max=99 id="AlertCount" name="AlertCount"  Value="' . $AlertCount. '" /></td><td>';

    		
    		
    		echo '<tr><td> Random Generator?:</td><td></td><td>';
    		if($RandomGen=="1")
					{echo '<input name="RandomGen" type="checkbox" id="RandomGen" onclick="HideRandomGenFields();" checked />';} 
				else 
					{echo '<input name="RandomGen" type="checkbox" id="RandomGen" onclick="HideRandomGenFields();"/>';}
    		echo '</td></tr>';

    	
    		echo '<tr id="RandomGenRow"><td>Random List: </td><td>
				<select name="RandomGenListID" id="RandomGenListID">';
	     
				$sql = "SELECT * FROM tblrandomlists";
				$result = $db->query($sql);
				while($row = $result->fetch_assoc()){
					if ($RandomGenListID== $row["ListID"])
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
    	<?
    		echo '<tr><td> Activity Type:</td><td>';
    		
	    		echo '<select name="ActivityType">';
	    		if($ActivityType=="1")
	    			{echo '<option value="1" Selected>Single Patrol</option>';}
	    		else
	    			{echo '<option value="1">Single Patrol</option>';}
	    		if($ActivityType=="2")
	    			{echo '<option value="2" Selected>Patrol VS Patrol</option>';}
	    		else
	    			{echo '<option value="2">Patrol VS Patrol</option>';}
	    		if($ActivityType=="3")
	    			{echo '<option value="3" Selected>Patrol VS Patrol or Single Patrol</option>';}
	    		else
	    			{echo '<option value="3">Patrol VS Patrol or Single Patrol</option>';}
	    		
	    		
	   			echo '</select> </p>';
	   		

    		echo '</td></tr>';
    		
    		

	   	?>
	     </table></div>
	     <? if ($EditActivity)
		{
	    	echo '<p align="center"><input type="submit" value="Edit Activity" name="EditActivity" id="EditActivity">';
	    	echo '<input type="submit" value="Cancel" name="Cancel" id="Cancel"></p>';
	    }
	    else
	    {
	    	echo '<p align="center"><input type="submit" value="Add Activity" name="AddActivity" id="AddActivity"></p>';
	    } ?>
	</form>
<? Echo "<p>" . $message . "</p>"; ?>
<div align="center">
<p><b>Activities for <? Echo $BaseName;?></b></p>
<?




/* Query SQL Server for the login of the user accessing the

database. */

$sql = "select * from tblactivities where BaseID='$BaseID'";

$result = $db->query($sql);

echo '<table class="thintable" border="1"><tr><td><b>Activity Name</b></td><td><b>Activity<br>Code</b></td><td><b>Value<br>Result</b></td><td><b>Success<br>or Fail</b></td><td><b>Comments</b></td><td><b>RandomGen</b></td><td><b>Activity Type</b></td><td Colspan=2><b>Options</b></td></tr>';
while($row = $result->fetch_assoc()){

    echo '<tr class="menuInline"><td>'.$row['ActivityName'] . "</td>
    	<td>" . $row['ActivityCode'] . "</td>";
    Echo "<td>";
    if($row["ValueResultField"]=="1")
    		{Echo "Yes";} 
		else 
			{Echo "No";}
	
	Echo "</td><td>";
    if($row["SuccessFailResultField"]=="1")
    		{Echo "Yes";} 
		else 
			{Echo "No";}
	Echo "</td><td>";
    if($row["CommentField"]=="1")
    		{Echo "Yes";} 
		else 
			{Echo "No";}
		Echo "</td><td>";
    if($row["RandomGen"]=="1")
    		{Echo "Yes";} 
		else 
			{Echo "No";}
		Echo "</td><td>";
    if($row["ActivityType"]=="1")
    		{Echo "Single Patrol";} 
	else if($row["ActivityType"]=="2")
			{Echo "Patrol VS Patrol";}
	else if($row["ActivityType"]=="3")
			{Echo "Patrol VS Patrol or Single Patrol";}

    echo '</td><td><a href="adminActivityConfig.php?Base=' . $CurrentBase . '&Edit=' . $row['ActivityID'] . '">Edit Activity</a>' . '</td><td><a href="adminActivityConfig.php?Base='. $CurrentBase . '&Del=' . $row['ActivityID'] . '">Delete</a></td></tr>';

}
echo '</table>';
 $result->close();


?>
</div>
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
<script>
	HideRandomGenFields();
</script>
</html>

