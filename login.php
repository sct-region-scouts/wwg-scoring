<?
// You may copy this PHP section to the top of file which needs to access after login.
session_start(); // Use session variable on this page. This function must put on the top of page.

////// Logout Section. Delete all session variable.
session_destroy();

// You may copy this PHP section to the top of file which needs to access after login.
session_start(); // Use session variable on this page. This function must put on the top of page.

////// Login Section.
$Login=$_POST['Login'];
if($Login){ // If clicked on Login button.
	$username=$_POST['username'];
	$md5_password=md5($_POST['password']); // Encrypt password with md5() function.
	$DBConnection = $_POST['DBConnection'];
	$_SESSION['DBConnection'] = $DBConnection;
	include 'tools/DBConnect.php';
	
	/* Query SQL Server for the login of the user accessing the
	database. */
	$sql = "select * from webusers where username='$username' and password='$md5_password'";
	$result = $db->query($sql);
	
	
	
	
	/* Retrieve and display the results of the query. */
	$row = $result->fetch_assoc();
    if ($row)
	{
			$userid=$row["username"];
			$username=$row["name"];
			$authlevel=$row["access"];
			If ($authlevel > 10)
			{
				$style = 2;
			}
			$result->close();
			$_SESSION['userid'] = $userid;
			$_SESSION['username'] = $username;
			$_SESSION['authlevel'] = $authlevel;
			$_SESSION['Style'] = $style;
			$_SESSION['Base'] = 1;
			$_SESSION['Act'] = 1;
			header("location:index.php"); // Re-direct to main.php
			
	}
	else{ 
		$message="--- Incorrect Username or Password ---"; //.$md5_password;
		$result->close();
		}
	 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<title>WWG - Login</title>
<link href="css/style.css"rel="stylesheet" type="text/css" />
</head>

<body>
<div class="logo"><h1><img src="images/logo.png" alt="WWG" /></h1></div>
<h2 class="head">Login</h2>
<div class="content">
<? echo $PHP_SELF; ?>
<form id="form1" name="form1" method="post" action="<? echo $PHP_SELF; ?>">
  <div align="center">
    <table width="146" border="0.5">
      <tr>
        <td width="62">Username:</td>
        <td width="68"><input name="username" type="text" id="username" size="12" /></td>
        </tr>
      <tr>
        <td>Password:</td>
        <td><input name="password" type="password" id="password" size="12" /></td>
        </tr>
      <tr>
      	<td>Database:</td>
      	<td><select id="DBConnection" name="DBConnection">
            <option value="4">South 2015</option>
            <option value="5">Cubs 2016</option>
            <option value="6">South 2016</option>
            <option value="7">North 2016</option>
            <option value="8">South 2017</option>
            <option value="9">North 2017</option>
             <option selected value="10">South 2018</option>
      		</select>
      	</td>
      	</tr>
    </table>
    <p>&nbsp;</p>
    <p>
	    <div class=buttons>
	      <input class="buttons" name="Login" type="submit" value="Login" />
	    </div>
    </p>
  </div>
</form>
</div>
<div class="footer">
  <div class="clear"></div>
 <p><? Echo $_SERVER['REMOTE_ADDR']; ?> - WWG NFC Scoring System - Created by Aiden Mayberry</p>
</div>
</body>
</html>
