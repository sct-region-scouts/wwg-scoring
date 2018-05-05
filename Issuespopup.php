<?


//include 'tools/config.php';
include 'tools/Functions.php';
include 'tools/DBConnect.php';

$isnewjob = 0;

if($_GET['IDIssue'])
{
	$IDIssue= $_GET['IDIssue'];
	$sql = "SELECT * FROM tblissues WHERE IDIssue='$IDIssue'";
	//echo $sql;
	$Result= $db->query($sql);
	$RowCount = $Result->num_rows;
	//display results
	if($RowCount > "0")
	{
		$row = $Result->fetch_assoc();
		$IDIssue= $row['IDIssue'];
		$Contact= $row['Contact'];
		$Issue= $row['Issue'];
		$Receivedby= $row['Receivedby'];
		$AssignedTo= $row['AssignedTo'];
		$Solution= $row['Solution'];
		$State= $row['State'];
	}
}
else
{
	$IDIssue='Add';
	$isnewjob= 1 ;
}

if($_POST['Update'])
{
	$IDIssue= $_POST['IDIssue'];
	$Contact= $_POST['Contact'];
	$Issue= $_POST['Issue'];
	$Receivedby= $_POST['Receivedby'];
	$AssignedTo= $_POST['AssignedTo'];
	$Solution= $_POST['Solution'];
	$State= $_POST['State'];
	
	if ($IDIssue == 'Add')
	{
	$sql = "INSERT tblissues (Contact, Issue, Receivedby, AssignedTo)
			Values ('$Contact', '$Issue', '$Receivedby', '$AssignedTo')
			";
	}
	else
	{
	$sql = "UPDATE tblissues
						SET Contact='$Contact', Issue='$Issue', Receivedby='$Receivedby', AssignedTo='$AssignedTo', AssignedTo='$AssignedTo', Solution='$Solution', State='$State'
						WHERE (IDIssue='$IDIssue')";
	}
	//echo $sql;
				if ($db->query($sql) === TRUE)
				{
					Echo "Updated";
				}
				else
				{
						echo "Failed to update record";
						die( print_r( $db->error, true));	
				}
	?>	
	<script type="text/javascript">window.opener.location.reload(true)</script>
	<? //
}

if($_POST['Add'])
{
	$IDIssue= $_POST['IDIssue'];
	$Contact= $_POST['Contact'];
	$Issue= $_POST['Issue'];
	$Receivedby= $_POST['Receivedby'];
	$AssignedTo= $_POST['AssignedTo'];
	$Solution= $_POST['Solution'];
	$State= $_POST['State'];
	

	$sql= "INSERT INTO " . $Prefix . "__tblmedical (IDPerson, MedicalDetails)VALUES ('$IDPerson','$MedDetails')";
				if ($db->query($sql) === TRUE)
				{
					Echo "Added";
				}
				else
				{
					echo "Failed to add record";
					die( print_r( $db->error, true));	
				}
	?>	
    
    
	<script type="text/javascript">window.close();</script>
	<?
}


?>


<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>WWG Rego - Issues</title>
 <!-- **** layout stylesheet **** -->
  <link rel="stylesheet" type="text/css" href="css/style.css" />
  <script type="text/javascript" src="tools/popup.js"></script>
</head>


<body>
	<form id="FrmIssues" name="FrmIssues" method="post" action="<? echo $PHP_SELF; ?>">
		<table>
			<input type="hidden" name="IDIssue" id="IDIssue" value="<? echo $IDIssue; ?>"/>
			<tr><td>Contact/Base:</td><td><input type=text name="Contact" id="Contact" value="<? echo $Contact; ?>" /></td></tr>
			<tr><td>Action/Issue: </td><td><input type=text name="Issue" id="Issue" value="<? echo $Issue; ?>" size="55" /> </td></tr>
			<tr><td>Received by: </td><td><input type=text name="Receivedby" id="Receivedby" value="<? echo $Receivedby; ?>" /> </td></tr>
			<tr><td>Assigned To: </td><td><input type=text name="AssignedTo" id="AssignedTo" value="<? echo $AssignedTo; ?>" /> </td></tr>
			<tr><td>Solution: </td><td><input type=text name="Solution" id="Solution" value="<? echo $Solution; ?>"  size="55" /> </td></tr>
			<?php
			if($isnewjob == 0)
			{
				echo '<tr><td>State: </td><td>';
				echo '<select name="State" id="State">';
				if ($State == 0)
				{echo '<option value="0" selected=selected>Unassigned</option>';
				echo '<option value="1">In Progress</option>';
				echo '<option value="2">Complete</option>';}
				if ($State == 1)
				{echo '<option value="0">Unassigned</option>';
				echo '<option value="1" selected=selected>In Progress</option>';
				echo '<option value="2">Complete</option>';}
				if ($State == 2)
				{echo '<option value="0">Unassigned</option>';
				echo '<option value="1">In Progress</option>';
				echo '<option value="2" selected=selected>Complete</option>';}
				
				echo '</select> </td></tr>';
			}
			else
			{
				echo '<tr><td>State: </td><td>';
				echo '<select name="State" id="State">';
				echo '<option value="0">Unassigned</option>';
				echo '<option value="1">In Progress</option>';
				echo '<option value="2">Complete</option>';
				echo '</select> </td></tr>';
			}

			?>
			<tr><td><input class="button" type="submit" value="Update" name="Update" id="Update"></td>
			<td><input class="button" type="button" value="Close" name="Close" id="Close" onClick="RefreshParent();CloseWindow();"></td></tr>
		</table>
	</form>
</body>

</html>