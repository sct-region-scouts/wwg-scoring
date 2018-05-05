<? 
session_start();
	$ConfigBase = $_SESSION['Base'];
	include 'tools/DBConnect.php';
	
 /* Query SQL Server for the Base Activitys in the database. */
		$sql = "select * from tblactivities where BaseID='$ConfigBase'";
		$result = $db->query($sql);
		$RowCount = $result->num_rows;
		$i = 1;
			if ($RowCount > 0)
			{
				//Bank
				$x = 0;
				while($row = $result->fetch_assoc())
				{
		    		$ActivityName =$row['ActivityName'];
		    		$x++;
		    		if ($x == 7)
		    		{
		    			echo '<br><br>';
		    		}
		    		if ($_SESSION['Act'] == $i)
		    		{
		    			Echo ' <a id="selected" href="Base.php?Act=' . $i . '">' . $ActivityName . '</a> ';
		    		}
		    		else
		    		{
						Echo ' <a href="Base.php?Act=' . $i . '">' . $ActivityName . '</a> ';
					}
					$i++;
				}
			}
 $result->close();
 

Echo ' <a href="index.php">Main Menu</a>';

?>