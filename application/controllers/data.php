<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

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
    }
	// hallo world
	public function index()
	{
		echo 'Hello World!';
	}
	
	public function getLastValue($meterID)
	{
		echo json_encode($this -> Data_model -> getLastValue($meterID));
	}
	
	public function putValue($meterID, $value)
	{
		//$meterID = $_GET['meter'];
		//$value = $_GET['value'];
		
		if($anser = $this -> Data_model ->putValue($meterID, $value) )
		{
			echo "0 ".$anser ;	
		}	
		else {
			echo "1";
		}
	}
	
	public function getAreaValues($meterID, $startTS, $endTS)
	{
		echo json_encode($this -> Data_model -> getAreaValues($meterID, $startTime, $endTime));
	}
	
	public function putAriaValues($valus)
	{
		if($anser = $this -> Data_model ->putAreaValues($mvalues) )
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
	public function putMeter($UserID, $Name, $Number, $Discription, $Unit)
	{
		
		
	}
}
?>