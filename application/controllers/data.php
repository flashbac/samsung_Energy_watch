<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//define ( 'DEBUG', TRUE);
/*
 * Dieser Controller enthällt den funktionen welche zur Datenausgabe benötigt werden.
 * Die Datenausgabe erfolgt im Json format
*/

class data extends CI_Controller {

	public function __construct()
    {
    	parent::__construct();
        // Your own constructor code
        
        //Models laden
        $this -> load -> model('Data_model');
		$this -> load -> model('Meter_model');
    }
	// hallo world
	public function index()
	{
		echo 'Hello World!';
	}
	
	public function getLastValue($meterID)
	{
		$data = array('data' => $this -> Data_model -> getLastValue($meterID));	
		echo json_encode($data);
	}
	
	public function putValue($meterID, $value, $timestamp = NULL)
	{
		//$meterID = $_GET['meter'];
		//$value = $_GET['value'];
		if( $timestamp != NULL)
		{
			If (defined('DEBUG')) {echo "Timestamp vorher:".$timestamp."\n";}
			$timestamp = urldecode($timestamp);
			$timestamp = str_replace("_", " ",$timestamp);
			If (defined('DEBUG')) {echo "Timestamp nachher:".$timestamp;}
		}

		if($anser = $this -> Data_model ->putValue($meterID, $value,$timestamp) )
		{
			echo "0 ".$anser ;	
		}	
		else {
			echo "1";
		}
	}
	
	public function getAreaValues($meterID, $startTS, $endTS)
	{
		$startTS = urldecode($startTS);
		$endTS = urldecode($endTS);
		$data = array('data' => $this -> Data_model -> getAreaValues($meterID, $startTS, $endTS));
		echo json_encode($data);
	}
	
	public function putAreaValues($values)
	{
		echo "Vorher: ".$values."<br/>";
		$values = urldecode($values);
		echo "Nachher: ".$values."<br/>";
		if($anser = $this -> Data_model ->putAreaValues($values) )
		{
			echo "0";
		}
		else {
			echo "1";
		}
	}
	
	/*
	 * $UserID 		-(int) Gibt an für welchen User der Zähler angelegt wird
	 * $Name		-(String) Name für den Meter
	 * $Number		-(String) Gibt eine Zählernummer an 
	 * $Discription -(String) Beschreibung für den Zähler 
	 * $Unit		-(String) Einheißt 
	 */
	public function putMeter($UserID, $Name, $Number, $Discription = "", $Unit)
	{
		echo "Vorher: ".$UserID."/".$Name."/".$Number."/".$Discription."/".$Unit."<br/>";
		$UserID = urldecode($UserID);
		$Name = urldecode($Name);
		$Number = urldecode($Number);
		$Discription = urldecode($Discription);
		$Unit = urldecode($Unit);
		echo "Nachher: ".$UserID."/".$Name."/".$Number."/".$Discription."/".$Unit;
		 
		if($anser = $this -> Data_model ->putMeter($UserID, $Name, $Number, $Discription, $Unit))
		{
			echo "MeterID: ".$anser;
		}
		else
			echo "1";
		
	}
	
	public function getMeter($UserID)
	{
		$data = array('data' => $this -> Meter_model -> getMeters($UserID));	
		echo json_encode($data);
	}
	
	public function getDataFromMeter($MeterID)
	{
		$data = array('data' => $this -> Meter_model -> getMeter($MeterID));	
		echo json_encode($data);
	}
}
?>