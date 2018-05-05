<?
//uncomment the below to 2 lines for debugging.
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	
	//ob_implicit_flush(true);


// You may copy this PHP section to the top of file which needs to access after login.
session_start(); // Use session variable on this page. This function must put on the top of page.
if(!isset($_SESSION['username'])) { // if session variable "username" does not exist.
header("location:login.php"); // Re-direct to login.php
exit;
}

//include Common Config
include 'tools/config.php';
include 'tools/BaseConfig.php';
include 'tools/DBConnect.php';
include 'tools/Functions.php';

	if($_GET["ID"]){
			//Get information
			$PatrolID=$_GET["ID"];
			$ScanTime= date("Y-m-d H:i:s");
			$Status="SignIn";
	
		
		$link="Base.php?ID=".$PatrolID;
			
		
			//Echo to Screen For testing
			//Echo "Patrol: " . $PatrolID;
			//Echo "<br>BaseCode: " . $BaseCode;
			//Echo "<br>ScanTime: " . $ScanTime;
			
			
			?>


			
			<?
			//Check if Patrol is in DB
			$sql = "SELECT * FROM tblpatrol WHERE (GameTag = '$PatrolID')";
			$result = $db->query($sql);
			$RowCount = $result->num_rows;
			if ($RowCount > 0)
				{
					//echo "True";
					//echo $PatrolID;
				}
				else
				{
						$link = $link . '&S=E';
						header("location:$link");
						$sql = "INSERT INTO tblbasesignin
	                         (IDPatrol, IDBaseCode, ScanTime, Status)
								VALUES        ('$PatrolID','$BaseCode','$ScanTime','$Status')";
						if ($db->query($sql) === TRUE)
						{
							exit;
						}
						else
						{
							die( print_r( $db->error, true));
							echo "Failed";
							exit;
						}
					
					
				}
			
			//Check if Patrol is already signed in 
			
			$sql = "SELECT IDBaseCode, IDPatrol, Status, ScanTime FROM tblbasesignin WHERE (IDPatrol = '$PatrolID') AND (IDBaseCode = '$BaseCode') ORDER BY IDPatrol";
			$result = $db->query($sql);
				
				
				$row = $result ->fetch_assoc();
				//echo "<br>" . $row['IDPatrol'] . " " . $row['IDBaseCode'] . " " . $row['Status'] . '<br />';
				$CurrentStatus = $row['Status'];
				$result->close();
				//echo "<br>Current Status: " . $CurrentStatus . "<br>";
			//Check Status Of Patrol
			if($CurrentStatus == $Status){
				//Echo "<b>Patrol Already Signed In.</b>";
				//echo "<br><br><a href=" . $link	. "> Base Menu</a>";
				$link = $link . '&S=A';
				header("location:$link");
				exit;
				}
			else if($CurrentStatus == "SignOut"){
				header("location:$link");
				//Updating SignIn Status in DB;
				$sql = "UPDATE tblbasesignin
						SET Status = '$Status', ScanTime = '$ScanTime'
						WHERE (IDPatrol = '$PatrolID') AND (IDBaseCode = '$BaseCode')";
				if ($db->query($sql) === TRUE)
				{
					return true;
				}
				else
				{
						die( print_r( $db->error, true));
						echo "Failed";
						return false;
				}
					
			}
			else
			{
				header("location:$link");
				echo "Inserting SignIn.";
				$sql = "INSERT INTO tblbasesignin
	                         (IDPatrol, IDBaseCode, ScanTime, Status)
								VALUES        ('$PatrolID','$BaseCode','$ScanTime','$Status')";
					if ($db->query($sql) === TRUE)
					{
						//echo "<b><br><br>Patrol Signed In</b>";
						//echo "<br><br><a href=" . $link	. "> Base Menu</a>";
						exit;
					}
					else
					{
						die( print_r( $db->error, true));
						echo "Failed";
						exit;
					}
				
				}
			
		
			
		}

?>

</html>

