<?
session_start(); // Use session variable on this page.
//trigger upload of offline data
include 'tools/RemoteDBConnect.php';
require_once( 'tools/Functions.php');

if ($_SESSION['Sync'] == 3)
{
	UploadOfflineResults();
	SyncScanTable();
	SyncScanValueResultTable();
	SyncItems();
	$_SESSION['Sync'] = 0;
}

?>