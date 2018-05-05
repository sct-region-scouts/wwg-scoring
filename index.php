<?
//Created by Aiden Mayberry

session_start(); // Use session variable on this page.
if(!isset($_SESSION['username'])) { // if session variable "username" does not exist.
header("location:./login.php"); // Re-direct to login.php
}

//include Common Config
include 'tools/config.php';
include 'tools/DBConnect.php';

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
<h2 class="head"><? echo $GameName; ?></h2>
<div class="menu">

<?
/* Query SQL Server for the Base in the database. */
		$sql = "select * from tblbases ORDER BY BaseID ASC";
		$result = $db->query($sql);
		$RowCount = $result->num_rows;
		//echo "Rows: " . $RowCount;
		$i = 1;
			if ($RowCount > 0)
			{
				while($row = $result->fetch_assoc())
				{
					$BaseID=$row['BaseID'];
		    		$BaseCode=$row['BaseCode'];
					$BaseName =$row['BaseName'];
					$NumActvitys =$row['NumberActivities'];
					echo '<a href="Base.php?Base=' . $BaseID . '&Act=1">' . $BaseName . '</a>';
					$i++;
				}
			}

 $db->close();

?>
    
    <a href="admin.php">Admin</a>
    <a href="login.php">Logout</a>
</div>
<div class="footer">
 <p><? Echo $_SERVER['REMOTE_ADDR']; ?> - WWG NFC Scoring System - Created by Aiden Mayberry</p>
</div>
</body>
</html>
