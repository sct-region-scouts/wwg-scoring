<?
//Weekend Wide Game NFC Scoring System
//Created by Aiden Mayberry

//uncomment the below to 2 lines for debugging.
	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);
	
	//ob_implicit_flush(true);
	

session_start(); // Use session variable on this page.
if(!isset($_SESSION['username'])) { // if session variable "username" does not exist.
header("location:./login.php"); // Re-direct to login.php
}

if($_GET["Base"]){
	$_SESSION['Base']=$_GET['Base'];
	}

if($_GET["Act"]){
	$_SESSION['Act']=$_GET['Act'];
	}
	
include 'tools/config.php';
include 'tools/BaseConfig.php';
include 'tools/DBConnect.php';
include 'tools/Functions.php';
require_once( 'classes/ItemCrafting.php');
require_once( 'classes/Rankings.php');
require_once( 'classes/NFCdata.php');
require_once( 'classes/BaseControl.php');
require_once( 'classes/SyncData.php');

$SkipSubmit = false;

if($_GET["ID"]){
	$PatrolSignIn=$_GET['ID'];
	$Status= $_GET['S'];
	
	if ($Status== "A" ){
			$message = $PatrolSignIn." already signed in.";
		}
		//Error
		else if ($Status== "E" ){
			$message = '<font color="#FF0000">' . $PatrolSignIn. ' does not exist in the Database!!!.</font><br> Contact WWG Admin!!!';
		}
		else {
			$message = $PatrolSignIn." signed in.";
		}
	}
	
$OpenNFCWriter = new nfcdata();
$IDPatrol = $_POST['DBPatrolID'];
//$OpenNFCWriter->OpenTagWriter($IDPatrol, $Value1, $Value2, $Value3);
	
	
/*
RANKING System
*/	
if($_POST["RankingPromote"])
	{
		
		$IDPatrol = $_POST['DBPatrolID'];
		$Rankings = new Rankings($IDPatrol);
		if ($Rankings->PromotePatrol() == false)
		{
			$SkipSubmit = "Yes";
			$message = "<error>Patrol does not have enough money in their credit account.</error>";
		}
	}
	
	
/*
ITEM CRAFTING
*/

	if($_POST["CraftItem"])
	{
		$IDPatrol = $_POST['DBPatrolID'];
		$IDItem = $_POST["CraftItem"];
		$ItemCrafting = new ItemCrafting();
		
		$ItemQty = 1;
		if($ItemCrafting->CraftItem($IDPatrol, $IDItem) == true)
		{
			AddItemToDB($DeviceName, $IDItem, $IDPatrol, $ItemQty);
		}
		else
		{
			$SkipSubmit = "Yes";
			$message = $ItemCrafting->messages[0];
		}
	}

//****TRADE******
//Check Price
for($i=0; $i<count($_POST['BuyItem']); ++$i){
	$IDPatrol = $_POST['DBPatrolID'];
	$DBIDItem = $_POST['IDItem'][$i];
	$DBBuy =  $_POST['BuyItem'][$i];
	$DBSell = $_POST['SellItem'][$i];
	if($DBBuy > 0)
	{
		$TotalItemBUYPrice = ($TotalItemBUYPrice + $DBBuy * GetItemPrice($DeviceName, $DBIDItem));
		$TotalBuyStock = $TotalBuyStock + $DBBuy;
	}
	if($DBSell > 0)
	{
		$TotalItemSELLPrice = ($TotalItemSELLPrice + $DBSell * GetItemPrice($DeviceName, $DBIDItem));
		$TotalSellStock  = $TotalSellStock + $DBSell;
	}
	//echo $TotalItemBUYPrice . " ";
	//echo $TotalItemSELLPrice . " ";
	
	$TotalTradePrice = ($TotalItemBUYPrice - $TotalItemSELLPrice);
	$TotalTradeStock = $TotalBuyStock - $TotalSellStock;
	
	//check if patrol has enough money
	$PatrolBalance = CheckBalance($IDPatrol, "Credit");
	if ($PatrolBalance > $TotalTradePrice )
	{
		$TradeCheck = "true";
	}
	else
	{
		$TradeCheck = "false";
	}
	
	
}

//check current stock 

	if(count($_POST['BuyItem'])>0)
	{
		$PatrolsStockCount = CountAllPatrolItems($IDPatrol);
		$PatrolsFraction = strtoupper(GetFraction($IDPatrol));
		if($PatrolsFraction == "SCOA")
		{
			//Echo "FRACTION A";
			$MaxStock = 50;
		}
		else if ($PatrolsFraction == "SCOB")
		{
			//Echo "FRACTION B";
			$MaxStock = 20;
		}
		else if ($PatrolsFraction == "SCOC")
		{
			//Echo "FRACTION C";
			$MaxStock = 40;
		}
		else if ($PatrolsFraction == "SCOD")
		{
			//Echo "FRACTION D";
			$MaxStock = 40;
		}
		else
		{
			
			$TradeCheck = "false";
		}
		
		//echo $MaxStock;
		//echo $PatrolsStockCount;
		
		if (($PatrolsStockCount + TotalTradeStock) >= $MaxStock)
		{
			$TradeCheck = "false";
		}
		
		

		$CheckTime = date("Y-m-d H:i:s", strtotime("-20 minutes"));
		//check Time since last Trade
		if (GetLastTradeVisitTime($BaseCode, $IDPatrol) > $CheckTime)
		{
			$TradeCheck = "time";
		} 

	}

//echo "Visit time: " . GetLastTradeVisitTime($BaseCode, 'SCOA01');
//echo "<br> Time: " . date("Y-m-d H:i:s", strtotime("-20 minutes"));



//TRADE
//Check Buy and sell Item Fields and update db
if ($TradeCheck === "true")
{
	for($i=0; $i<count($_POST['BuyItem']); ++$i){
			
			$IDPatrol = $_POST['DBPatrolID'];
			$DBIDItem = $_POST['IDItem'][$i];
			$DBBuy =  $_POST['BuyItem'][$i];
			
			
			//Buy Items
			if($DBBuy > 0)
			{				
				//Add Stock to Patrol
				AddItemToDB($DeviceName, $DBIDItem, $IDPatrol, $DBBuy);
				
				//Remove Stock from shop
				$DeviceBuy = 0 - $DBBuy;
				AddItemToDB($DeviceName, $DBIDItem, $DeviceName, $DeviceBuy);
	
			}
			
			//Sell Items
			$DBSell = $_POST['SellItem'][$i];
			if($DBSell > 0)
			{
				//Remove Stock from Patrol
				$PatrolSell = 0 - $DBSell;
				AddItemToDB($DeviceName, $DBIDItem, $IDPatrol, $PatrolSell);
				
				//Add Stock to shop
				AddItemToDB($DeviceName, $DBIDItem, $DeviceName, $DBSell);
				
			}
			
			
	}
}

if ($TradeCheck == "false")
{
	$message = "<error>Patrol does not have enough money in there credit account 
					<br> or
					<br> Cargo is already Full </error>
					<br> Cost: $" . $TotalTradePrice . " 
					<br> Patrol Funds: $" . $PatrolBalance . "
					<br> Patrol Cargo: " . $PatrolsStockCount . 
					"<br>Patrol MAX Cargo: " . $MaxStock . "  ";
	$SkipSubmit = "Yes";
	//echo $message;
}

if ($TradeCheck == "time")
{
	$message = "<error>Patrol Must wait 20 mins before completing another Trade transaction.</error>";
	$SkipSubmit = "Yes";
}



//****UPGRADES*****
//Get Data and set checkupgrade to true.
	if(count($_POST['IDUpgrade'])>0)
	{
		$CheckUpgrade = "true";
		$IDPatrol = $_POST['DBPatrolID'];
		$UpgradeCost = 0;
	}


//Check Price of Upgrade.
	for($i=0; $i<count($_POST['IDUpgrade']); ++$i)
	{	
		$IDUpgrade = $_POST['IDUpgrade'][$i];
		$CurrentCV =  $_POST['CurrentCV'][$i];
		$NEWCV = $_POST['NEWCV'][$i];
		
		if ($CurrentCV <= $NEWCV)
		{
			$UpgradeAmount = $NEWCV - $CurrentCV;
			$UpgradeCost =  $UpgradeCost + $UpgradeAmount * 40; 
			
		}
		else
		{
			$CheckCVValue[] = "CVToLow";
			$SkipSubmit = "Yes";
			$message = "<error>New CV Value must be higher then the current CV value</error>";

		}
		
	}
	
	
	//Validate Upgrade
	if(count($_POST['IDUpgrade'])>0)
	{
	
		//check if patrol has enough money
		$PatrolBalance = CheckBalance($IDPatrol, "Credit");
		if ($PatrolBalance < $UpgradeCost)
		{
			$CheckUpgrade = "Money";
			$SkipSubmit = "Yes";
			$message = "<error>Patrol does not have enough money in there credit account</error> 
					<br> Cost: $" . $UpgradeCost . "
					<br> Patrol Funds: $" . $PatrolBalance;
		}
		
		if ( $CheckUpgrade === "true")
		{
			AddBankTransaction($IDPatrol, $BaseCode, $UpgradeCost, "Credit", " Withdrawal");
		}

	}
	
//BANKING
	
	if($Bank == 1) 
	{
		if ($_POST['SubmitResult'])
		{
			if ($_POST['Amount'] == "")
			{
				$message = "<error>Please enter an Amount </error>";
				$SkipSubmit = "Yes";
			}
			if ($_POST['Account'] == "0" or $_POST['Type'] == "0")
			{
				$message = "<error>Please select an Account and Transaction type. </error>";
				$SkipSubmit = "Yes";
			}
			
		}
		
	}
	

	
	


if($SkipSubmit != "Yes")
{
	$SubmitResult=$_POST['SubmitResult'];
		if($SubmitResult)
		{
		
			$GameTag= $_POST['DBPatrolID'];
			$GameTag2= $_POST['DBPatrolID2'];
			$ScanTime= date("Y-m-d H:i:s");
			$ResultValue = $_POST['ResultValue'];
			$ResultValue2 = $_POST['ResultValue2'];
			$Result= $_POST['Result'];
			$Result2= $_POST['Result2'];
			$Comment = $_POST['CommentField'];
			$RandomEventText=  $_POST['RandomEventText'];
			
			if($Bank == 1) 
			{
				$ResultValue = $_POST['Amount'];
				$ActivityCode = $_POST['Account'];
				$Comment = trim($_POST['Type']);
			}
			
			if ($DropDownField == 1)
			{
				//Overwrite comments field with Dropdown value
				$ResultValue = $_POST['DropDownField'];
			}
			
			if ($TradeCheck == "true")
			{
				$ResultValue = 0 - $TotalTradePrice;
				$ActivityCode = "Credit";
				$Comment = "Trade";
			
			}
			
			//echo "Pass on Value: " . $PassBasedonValueResult;
			if ($PassBasedonValueResult == 1)
			{
				if ($ResultValue > $PassValue)
				{
					$Result = "Success";
				}
				else
				{
					$Result = "Fail";
				}
			}
			
			$Comment = $RandomEventText . " " . $Comment;
			if ($ResultValue2) {
				$Comment = $ResultValue2;
			}
			if(CheckIfSignedIN($GameTag, $BaseCode) == true)
				{
					if(InsertScanValueResult($GameTag, $ScanTime, $BaseCode, $ActivityCode, trim($Comment), $ResultValue, $Result, $GameTag2) === true)
					{
							$message = "Record Saved<br>";
							$Closewindow= "true"; // - Disabled for debuging
							$CheckAlert = "True";
							if($Result == "Success" and $Reward == "1")
							{
								GiveAward($GameTag, $BaseCode, $RewardValue);
							}
							if(SignOut($GameTag, $BaseCode, $Status, $ScanTime)){
								
							}
							else
							{
								$message = "Failed";
							}
							
					}
					else 
					{ 
						$message = "<br>Error in executing query.</br>";
						die( print_r( $db->error, true));
					}
					
					if ($GameTag2!="")
					{
						if(InsertScanValueResult($GameTag2, $ScanTime, $BaseCode, $ActivityCode,  trim($Comment), $ResultValue, $Result2, $GameTag) === true)
						{
							$message = "Record Saved<br>";
							$CheckAlert = "True";
							if($Result2 == "Success" and $Reward == "1")
							{
								GiveAward($GameTag, $BaseCode, $RewardValue);
							}
							if(SignOut($GameTag2, $BaseCode, $Status, $ScanTime)){
								
							}
							else
							{
								$message = "Failed";
							}
							
						}
						else 
						{ 
							$message = "<br>Error in executing query.</br>";
							die( print_r( $DB->error, true));
						}
					}
				
				 }
				 else
				 {
				 	$message = "Patrol is not signed in to base.";
				 }
		 }
} //used for skiping submit if statement
	 

//Trigger Alert after result is submited
if($Alert)
	{
		
		if ($AlertRule == 1){//send alert if last result is higher then $x
			//Get Vist count and compare to alert value from DB
			$VisitCount = GetVisitCount($BaseCode, $ActivityCode);
			If ($AlertCount == $VisitCount and $CheckAlert == "True")
			{
				
				echo '<script>';
				echo 'alert("' . $AlertMessage . '");';
				echo '</script>';
			
			}
		}
		
		if ($AlertRule == 2){//send alert if last result is higher then $x
		
			if ($Result == "Success" and $CheckAlert == "True")
			{
				
				echo '<script>';
				echo 'alert("' . $AlertMessage . '");';
				echo '</script>';
			
			}
		}
			
		
	}
 	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<title>WWG - Base</title>
<link href="css/style.css"rel="stylesheet" type="text/css" />
<script type="text/javascript" src="tools/jquery.min.js"></script>
<script type="text/javascript" src="tools/Base.js"></script>
</head>

<body onload="CheckValidResult();">
<div class="logo"><h1><img src="images/logo.png" alt="WWG" /></h1></div>
<h2 class="head"><? echo $BaseName; ?></h2>
<h2 class="head"><? echo "Activity: " . $ActivityName; ?></h2>
<div class="menuInline">
<p align="center">



<? 
//echo '<a href="activity_NFCWriteTag:#scoa01#12">Open Tag Writer</a> ';

if ($Closewindow == "true")
		{
			
			?>
            Record Submited<br />
			<script> 
			alert("Record Saved"); 
		    window.open('','_self');
		    window.close();
			</script>
			<?
		}
		
	//Load Base Menu
	include 'BaseMenu.php';
?>
</p>
</div>

<div class="content">
<p align="center">
	<?
		//display any messages.
			If($message or $message1)
			{
				echo "<b>".$message."</b>";
			}
			//echo '<p color="blue"> <b>1 BEAD = 1 JEM</b></p>';
	?>
</p>


<?
//If Validate Button pressed check Patrol ID is valid
$validPatrol=false;
$ValidatePatrolID=$_POST['ValidateID'];
	if($ValidatePatrolID)
	{	
		if ($ActivityType == 1)	
		{
			$PatrolID=$_POST['PatrolID'];
			Echo '<p align="center">';
			$validPatrol = ValidatePatrolID($PatrolID); 
			
			//Display Fractions
			echo "<b>";
			//$PatrolsFraction = strtoupper(GetFraction($PatrolID));  -- DISABLED
			if($PatrolsFraction == "SCOA")
			{
				Echo "Half Blood";
			}
			else if ($PatrolsFraction == "SCOB")
			{
				Echo "Pure Blood";
			}
			else if ($PatrolsFraction == "SCOC")
			{
				Echo "Muggle Born";
			}
			else if ($PatrolsFraction == "SCOD")
			{
				Echo "Muggle Born";
			}
			

			Echo '</b></p>';
		}
		
		if ($ActivityType == 2 or $ActivityType == 3)	
		{
			$PatrolID=$_POST['PatrolID'];
			$PatrolID2=$_POST['PatrolID2'];
			If($PatrolID!=$PatrolID2)
			{
				Echo '<p align="center">';
				$validPatrol = ValidatePatrolID($PatrolID);
				echo '</p>';
				If($PatrolID2 != "None")
					{
					Echo '<p align="center">Vs <br></p><p align="center">';
					$validPatrol2 = ValidatePatrolID($PatrolID2);
					echo '</p>';
					}
			}
			else
			{
				Echo '<p align="center"><b>Patrol IDs must not be the same.</b></p>';
			}
		}
	}

// if not a Valid Patrol ID or none yet entered display Form.
If($validPatrol==false){
?>
	<p align="center">Select Patrol ID and click Validate Patrol ID </p>
	<form id="form1" name="frmpatrolid" method="post" action="Base.php">
	      
	     
	     <?
				$sql = "SELECT * FROM tblbasesignin  WHERE (Status = 'SignIn') AND (IDBaseCode = '$BaseCode')";
				$result = $db->query($sql);
				$RowCount = $result->num_rows;
				if ($RowCount > 0)
				{
					echo '<p align="center"> Patrol: <select name="PatrolID">';
					while($row = $result->fetch_assoc()){
	    				echo '<option value="'.$row["IDPatrol"].'">'.$row["IDPatrol"].'</option>';
					}
					echo '</select></p><br>';
				}
				else
				{
					echo '<p align="center"><b>To sign in a Patrol, press their Patrol tag to the back of the device.</b></p><br>';
				}
 				$result->close();
		?>
	      
	     <?
			if ($ActivityType == 2 or $ActivityType == 3)
			{
				?>
				     <p align="center"> Patrol 2: 
				     <select name="PatrolID2">
				     <?
				     		if ($ActivityType == 3){
								echo '<option value="None">None</option>';
							}

							$sql = "SELECT * FROM tblbasesignin  WHERE (Status = 'SignIn') AND (IDBaseCode = '$BaseCode')";
							$result = $db->query($sql);
							while($row = $result->fetch_assoc()){
			    				echo '<option value="'.$row["IDPatrol"].'">'.$row["IDPatrol"].'</option>';
							}
							
			 				$result->close();
					?>
				     </select> </p><br>
				 <?
			}
			
			if ($RowCount > 0)
			{
				echo '<p align="center"><input type="submit" value="Validate Patrol ID" name="ValidateID" id="ValidateID" onclick="Show_LoadingMsg()"></p>';
			}
			echo '</form>';
	} 
	
	//If a Valid Patrol Display Change Patrol Button 
 If($validPatrol==true){
 ?>
 <form id="form1" name="frmchangepatrol" method="post" action="<? echo $PHP_SELF; ?>">
	   <p align="center"> <input type="submit" value="Change Patrols" name="ChangePatrol" id="ChangePatrol"> </p>
	</form><br>

<? if($HideSubmitButton == 0)
		{
			echo '<p id="Message" align="center"></p>';
		}
?>
	 
	<? //Results Form ?>
	<form id="frmresult" name="frmresult" onsubmit="CheckValidResult();" action="<? echo $PHP_SELF; ?>" method="post" >
		 
		<? 	
		echo "<input type='hidden' name='DBPatrolID' value='$PatrolID'>";
		echo "<input type='hidden' name='DBPatrolID2' value='$PatrolID2'>";
		
//Base Random Event Trigger
		if ($RandomEvents == 1)
		{
			
			$RandomEventText = GetRandomText($RandomListID,$RandomChance);
			if ($RandomEventText){
				echo '<p align="center">';
				echo $RandomEventText;
				echo '<br><br> </p>';
				}
		echo "<input type='hidden' name='RandomEventText' value='$RandomEventText'>";
		}
		
//Show Base Control
		if ($BaseControl == 1)
		{
			$BaseControl = new BaseControl($BaseCode, $ActivityCode);
			$BaseControledBy = $BaseControl->GetCurrentFractionInControl();
			
			echo '<p align="center">';
			echo "Faction in control: " . $BaseControledBy;
			echo '<br><br> </p>';
		}
		
//Activity Random Event Trigger
		if ($RandomGen == 1)
		{
			
			$RandomEventText = GetRandomText($RandomGenListID ,0);
			if ($RandomEventText){
				echo '<p align="center">';
				echo '<b><font color="#FF0000">Activity Random Event</font></b><br>';
				echo $RandomEventText;
				echo '<br><br> </p>';
				}
		echo "<input type='hidden' name='RandomEventText' value='$RandomEventText'>";
		}
		
//Show if Ranking Enabled
	//$Ranking = 1; //used for testing only
	if ($Ranking == 1)
		{
			$Rankings = new Rankings($PatrolID);
			echo '<p align="center">';
			echo "Current Rank: " . $Rankings->showCurrentRank() . " <br>"; 
			echo "Next Rank: " . $Rankings->showNextRank() . " <br>"; 
			echo "Cost of Next Rank: " . $Rankings->showNextRankCost() . " <br>"; 
			echo "<input type='hidden' name='RankingPromote' value='1'>";
			echo '</p>';
			
		}
		
		//echo $Bank;
//Show Bank if enabled
		if ($Bank == 1)
		{

			echo '<p align="center">';
			
			echo "<b>Bank Balances</b></br>";
			//*************************Used to Check Cridit Account Balance
			//$AccountBal = "Credit";
			//echo "Credit Balance: " . CheckBalance($PatrolID, $AccountBal) . '<br>';
			
			//*************************Used for south 2015 only
			$AccountBal = "General";
			echo "General Balance: " . CheckBalance($PatrolID, $AccountBal) . '<br>';
			$AccountBal = "Base_Defence";
			echo "Base Defence Contribution: " . CheckBalance($PatrolID, $AccountBal) . '<br>';
			//echo "Council Rock Total: " . CheckBalanceBase($BaseCode, $AccountBal) . '<br>';
			//$RaidAmount = (CheckBalanceBase($BaseCode, $AccountBal)*0.1);
			//if($RaidAmount <= 10){
			//	$RaidAmount = 10;
			//}
			
			//echo "Raid Amount: " . round($RaidAmount) . '<br>';
			//***************end South 2015 only section
			
			echo "<br><b>New Transaction</b></br>";
			echo 'Account: ';
			
			echo '<select name="Account" id="Account" onchange="Update_ComboTransactionType(' . $DisableWithdrawal . ');">';
			echo '<option value="0">--Select Account--</option>';
			$sql = "SELECT * FROM tblbankconfig";
			$result = $db->query($sql);
			while($row = $result->fetch_assoc()){
				echo '<option value="'.$row["AccountName"].'">'.$row["AccountName"].'</option>';
			}
			$result->close();
			echo '</select><br>';
			
			echo 'Transaction Type: ';
			echo '<select name="Type" id="BankType">';
			echo '<option value="0">--Select Transaction Type--</option>';
			echo '<option value="Deposit">Deposit</option>';
			echo '<option value="Withdrawal">Withdrawal</option>';
			echo '<option value="Transfer">Transfer</option>';
			echo '</select><br>';
			
			echo 'Amount: '; //record in value result
			//echo '<select id="Amount" name="Amount" onchange="CheckValidResult();"> </p>';
		   // $count=0;
		    //while($count <= 100)
		    //	{
			//    	echo '<option value="' .$count. '">' .$count. '</option>';
			//		$count = $count +1;
			//	}
	    	//echo '</select><br>';	
	    	
			echo '<input type="number" id="Amount" max="1000" name="Amount" onchange="CheckValidResult();"> </p>';
		}
		
//Show Upgrades
	if ($Upgrades == 1)
	{
		echo '<table class="thintable" align=center border="1"><tr><th></th>';	
			$sql = "SELECT * FROM tblupgrades";
			$results = $db->query($sql);
			$RowCount = $results->num_rows;
			while($row = $results->fetch_assoc())
				{
			    	$IDUpgrade[] = $row['IDUpgrade'];
			    	echo '<th>' . $row['Upgrade'] . '</th>';
				}
			echo '</tr>';
			
			Echo "<tr><td>Current CV Value:</td>";
			for ($x=0;$x<count($IDUpgrade);$x++)
			{
				echo "<input type='hidden' id='IDUpgrade[]' name='IDUpgrade[]' value='" . $IDUpgrade[$x] . "'>";
				echo '<td><p align="center"><input type=number min=1 max=10 id="CurrentCV[]" name="CurrentCV[]" value="1" onchange="CheckValidResult();"> </p>';
			    echo '</p></td>';

			
			}
			Echo "<tr><td>NEW CV Value:</td>";
			for ($x=0;$x<count($IDUpgrade);$x++)
			{
				echo '<td><p align="center"><input type=number min=1 max=10 id="NEWCV[]" name="NEWCV[]" value="1"  onchange="CheckValidResult();"> </p>';
			    echo '</p></td>';

			
			}
			echo "</tr></table>";
	}
	
////Show Item Crafting
	if ($ItemCrafting == 1)
	{
		if ($_SESSION['RemoteDBOffline'] == 1)
			{
			echo '<error>Item Crafting CURRENTLY OFFLINE, PLEASE TRY AGAIN LATER.</error>';
			}
			else
			{
				$sql = "SELECT * FROM tblitems where ItemType='2'";	
				echo '<p align="center"> Craft Item: ';
				echo '<select id="CraftItem" name="CraftItem">';
				$results = $db->query($sql);
				$RowCount = $results->num_rows;
				while($row = $results->fetch_assoc())
				{
			    	echo '<option value="' . $row['IDItem'] . '">' . $row['ItemName'] . '</option>';
				}
				echo '</select>';
				
				echo "<br><b>Required Items to Craft</b></p>";
				
				
					
			}
	}
	
		

//Trade 
	//$Trade = 1; //used for testing
	if ($Trade == 1)
		{
			if ($_SESSION['RemoteDBOffline'] == 1)
			{
			echo '<error>TRADE CURRENTLY OFFLINE, PLEASE TRY AGAIN LATER. (No Network)</error>';
			}
			$sql = "SELECT * FROM tblitems where ItemType='1'";
			$results = $db->query($sql);
			$RowCount = $results->num_rows;
			
			echo '<table class="thintable" align=center border="1"><tr><th></th>';
			while($row = $results->fetch_assoc())
				{
			    	$ItemID[] = $row['IDItem'];
			    	echo '<th>' . $row['ItemName'] . '</th>';
				}
			echo '</tr>';
			
			//Check Price for Each Item
			echo '<tr><td>Price</td>';
			for ($x=0;$x<count($ItemID);$x++){
				$sqlitemid = $ItemID[$x];
				$itmPrice = GetItemPrice($DeviceName, $sqlitemid);
				echo '<td><p align="center">$' . $itmPrice  . 'ea</p></td>';
			}
			
			echo '</tr>';	
			
			echo '<tr><td>World Qty</td>';
			for ($x=0;$x<count($ItemID);$x++){
				$sqlitemid = $ItemID[$x];
				$sql = "SELECT * FROM tblitemtransactions 
							WHERE IDItem='$sqlitemid' AND IDPatrol='$DeviceName' ";
				$results = $db->query($sql);
				$RowCount = $results->num_rows;
				if($RowCount > 0){
					$ItemQtyBase[$x] = 0;
					while($row = $results->fetch_assoc())
					{
				    	$ItemQtyBase[$x] = $row['ItemQty'] + $ItemQtyBase[$x];
					}
				}
				else
				{
					$ItemQtyBase[$x] = 0;
				}
				echo '<td><p align="center">' . $ItemQtyBase[$x] . '</p></td>';
			}
				
			echo '</tr>';	

			echo '<tr><td>Patrol Qty</td>';
			for ($x=0;$x<count($ItemID);$x++){
				$sqlitemid = $ItemID[$x];
				$sql = "SELECT * FROM tblitemtransactions 
							WHERE IDItem='$sqlitemid' AND IDPatrol='$PatrolID' ";
				$results = $db->query($sql);
				$RowCount = $results->num_rows;
				if($RowCount > 0){
					while($row = $results->fetch_assoc())
					{
				    	$ItemQtyPatrol[$x] = $row['ItemQty'] + $ItemQtyPatrol[$x];
					}
				}
				else
				{
					$ItemQtyPatrol[$x] = 0;
				}
				echo '<td><p align="center">' . $ItemQtyPatrol[$x] . '</p></td>';
			}
			
			
			echo '<tr><td>Buy</td>';
			for ($x=0;$x<count($ItemID);$x++){
				$sqlitemid = $ItemID[$x];
				$sql = "SELECT * FROM tblitemtransactions 
							WHERE IDItem='$sqlitemid' AND IDPatrol='$DeviceName' ";
				$results = $db->query($sql);
				$RowCount = $results->num_rows;
				if($RowCount > 0){
					while($row = $results->fetch_assoc())
					{
				    	$ItemQtyBuy[$x] = $row['ItemQty'] + $ItemQtyBuy[$x];
					}
				}
				else
				{
					$ItemQtyBuy[$x] = 0;
				}
					echo "<input type='hidden' id='IDItems[]' name='IDItem[]' value='" . $ItemID[$x] . "'>";
					echo '<td><p align="center"><input type=number min=0 max="' . $ItemQtyBuy[$x] . '" id="BuyItem[]" name="BuyItem[]" onchange="CheckValidResult();"> </p>';
			    	echo '</p></td>';
			}
			
			
			echo '<tr><td>Sell</td>';
			for ($x=0;$x<count($ItemID);$x++){
				$sqlitemid = $ItemID[$x];
				$sql = "SELECT * FROM tblitemtransactions 
							WHERE IDItem='$sqlitemid' AND IDPatrol='$PatrolID' ";
				$results = $db->query($sql);
				$RowCount = $results->num_rows;
				if($RowCount > 0){
					while($row = $results->fetch_assoc())
					{
				    	$ItemQtySell[$x] = $row['ItemQty'] + $ItemQtySell[$x];
					}
				}
				else
				{
					$ItemQtySell[$x] = 0;
				}
				echo '<td><p align="center"><input type=number min=0 max="' . $ItemQtySell[$x] . '" id="' . $ItemID[$x] . '" name="SellItem[]" onchange="CheckValidResult();"> </p>';
		    	echo '</p></td>';
			}
			
			
			
			
			echo '</table>';	
		}
	
		
		
//Value Result Field
		if ($ValueResultFeild == 1)
		{
			echo '<p align="center">';
			//echo $ValueResultMax;
			echo $ValueResultName . ' (0 - ' . $ValueResultMax. '): <input type="number" min=0 max=' . $ValueResultMax . ' id="ResultValue" name="ResultValue" onchange="CheckValidResult()"></p>';
			//<select ;">';
		   	//$count=0;
		    //while($count <= $ValueResultMax)
		    //	{
			//    	echo '<option value="' .$count. '">' .$count. '</option>';
			//		$count = $count + 1;
			//	}
			//
	    	//echo '</select> </p>';
	    }
	    else
	    {
	    	Echo "<input id='ResultValue' type='hidden' name='ResultValue' value='0'>";
	    }
	    
	    
//Value Result Field 2
		if ($ValueResultFeild2 == 1)
		{
			echo '<p align="center">';
			echo $ValueResultName2 . ': <select id="ResultValue2" name="ResultValue2" onchange="CheckValidResult();"> </p>';
		    $count=0;
		    while($count <= 100)
		    	{
			    	echo '<option value="' .$count. '">' .$count. '</option>';
					$count = $count +1;
				}
		
	    	echo '</select> </p>';
	    }
	    
	    
//Success or Fail field
		if ($SuccessFailResultField == 1)
			{
				echo '<p align="center"> Patrol 1 Result';
	    		echo '<select id="Result" name="Result" onchange="CheckValidResult();">';
	    		echo '<option value="0">NA</option>';
	    		echo '<option value="Success">Success</option>';
	    		echo '<option value="Fail">Fail</option>';
	   			echo '</select> </p>';
	   			
	   			if ($ActivityType == 2 or $ActivityType == 3 and $PatrolID2 != "None")
	   				{
	   					echo '<br><p align="center"> Patrol 2 Result';
			    		echo '<select name="Result2" onchange="CheckValidResult();">';
			    		echo '<option value="0">NA</option>';
			    		echo '<option value="Success">Success</option>';
			    		echo '<option value="Fail">Fail</option>';
			   			echo '</select> </p>';
			   		}
	   			 
			}
		else
			{
				echo "<input type='hidden' id='Result' name='Result' value='Success'>";
			}
			
//DropDown field from list
		if ($DropDownField == 1)
			{
				//echo $DropDownFieldListID;
				echo '<p align="center"> Select Option';
	    		echo '<select id="DropDownField" name="DropDownField" onchange="CheckValidResult();">';

				$sql = "SELECT * FROM tblrandomvalues 
							WHERE ListID='$DropDownFieldListID' ";
							//echo $sql;
				$results = $db->query($sql);
				$RowCount = $results->num_rows;
				if($RowCount > 0){
					while($row = $results->fetch_assoc())
					{
						echo '<option value="' . $row['ListDBValue'] . '">' . $row['ListValue'] . '</option>';
				    	
					}
				}
				else
				{
					echo "no list found";
				}
	   			echo '</select> </p>';
	   			
	   			if ($ActivityType == 2 or $ActivityType == 3 and $PatrolID2 != "None")
	   				{
	   					echo '<br><p align="center"> Patrol 2 Result';
			    		echo '<select name="Result2" onchange="CheckValidResult();">';
			    		echo '<option value="0">NA</option>';
			    		echo '<option value="Success">Success</option>';
			    		echo '<option value="Fail">Fail</option>';
			   			echo '</select> </p>';
			   		}
	   			 
			}
		else
			{
				Echo "<input type='hidden' id='DropDownField' name='DropDownField' value='Success'>";
			}
			

		
		//Comment Field
		if ($CommentField== 1){	
			echo '<p align="center"> Comment:<br>';		
			echo '<input name="CommentField" type="text" Value="" id="CommentField" size="60" maxlength="180" />';
			echo '</p>';	
			}
		
		
		
		if($HideSubmitButton == 0)
		{
	    	echo '<br><p align="center"> <input type="submit" value="Submit Result"  name="SubmitResult" id="SubmitResult" onclick="Show_SubmitLoadingMsg()"> </p>';
	    }
	    ?>
	</form>
 <? } 
    ?>

</div>
<div class="footer">
<div class="menu">


	
</div>
    <div class="clear"></div>
    <p>Remote DB: <? if ($_SESSION['RemoteDBOffline'] == 0){echo 'Online';}else{echo 'Offline';}?> <? Echo $_SERVER['REMOTE_ADDR']; ?> - WWG NFC Scoring System - Created by Aiden Mayberry</p>
</div>
</body>
</html>

<?
$_SESSION['Sync'] = $_SESSION['Sync'] + 1;
echo $_SESSION['Sync'];
ob_flush();
flush();
//trigger upload of offline data
if($_SESSION['Remote'] == 1)
{
	$SyncData = new SyncData();
	include 'tools/RemoteDBConnect.php';

	if ($_SESSION['Sync'] > 3)
	{
		if($_SESSION['RemoteDBOffline'] == 0)
		{
			UploadOfflineResults();
			SyncScanTable();
			SyncScanValueResultTable();
			SyncItems();
			echo "_";
			$_SESSION['Sync'] = 0;
		}
		
	}
}

?>