<?
//uncomment the below to 2 lines for debugging.
	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);

session_start(); // Use session variable on this page. This function must put on the top of page.


//TZ Fix
date_default_timezone_set('Australia/Sydney');



//Add tools
include 'tools/DBConnect.php';
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="refresh" content="60" > 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />

<title>WWG </title>
<link href="css/style.css"rel="stylesheet" type="text/css" />
<script type="text/javascript" src="tools/popup.js"></script>
</head>


<body>

<h2 class="head">Issues Log / Action List</h2>
<h2 class="head" style="color: rgb(254, 254, 254); text-decoration: underline;"><a href="#" onclick="ShowAddIssue()">Add Issue/Action</a></h2>
<div style="padding: 0px 5px 0px 5px">
<p>
        <form id="frmStatus" name="frmStatus" method="post" action="Issues.php">
            View: 
            <select id="Status" name="Status" onchange="this.form.submit()">
                <?
					if($_POST["Status"] == 0) {$selected='selected="selected"';} else {$selected='';}
					echo '<option value="0" ' . $selected . ' > Hide Complete </option>';
					if($_POST["Status"] == 1) {$selected='selected="selected"';} else {$selected='';}
					echo '<option value="1" ' . $selected . '> Show Complete </option>';
				?>
           </select>
      </form>

<?


/* Query SQL Server for the login of the user accessing the

database. */

	if($_POST["Status"] == 1)
	{
		$sql = "SELECT * FROM tblissues";
	}
	else
	{
		$sql = "SELECT * FROM tblissues WHERE State!='2'";
	}

	$result = $db->query($sql);
	echo '<table width="100%" border=1 cellpadding="1px" style="border-collapse: collapse">';
	echo "<tr>";
    echo '<td><b>Date Added</b></td><td><b>Contact/Base</b></td><td><b>Action/Issue</b></td><td><b>Received by</b></td><td><b>Assigned To</b></td><td><b>State</b></td>';
	echo "</tr>";
	$rowcount = $result->num_rows;
	if($rowcount > 0)
	{
		while($row = $result->fetch_assoc()){
			if ($row['State'] == 0)
			{ $showState = "Unassigned"; }
			if ($row['State'] == 1)
			{ $showState = "In Progress"; }
			if ($row['State'] == 2)
			{ $showState = "Complete"; }
			$IDIssue = $row['IDIssue'];
			echo "<tr>";
			echo "<td>";
			echo date( "Y-m-d H:i:s", strtotime( $row['TimeUpdate']));
			//echo $row['Date'];
			echo "</td><td>" . $row['Contact'] . "</td><td>" . $row['Issue'] . "</td><td>" . $row['Receivedby'] . "</td><td>" . $row['AssignedTo'] . '</td><td><a href="#" onclick="ShowUpdateIssue(' . $IDIssue . ')">' . 
						$showState . "</a></td>";
			echo '';
			echo "</tr>";
		}
	}
echo "</table>";
 //$result->close();

?>

<br>

</p>
</div>
<div class="footer">
   <p><? Echo $_SERVER['REMOTE_ADDR']; ?> - WWG NFC Scoring System - Created by Aiden Mayberry</p>
</div>
</body>
<script type="text/javascript" src="tools/Admin.js"></script>
</html>