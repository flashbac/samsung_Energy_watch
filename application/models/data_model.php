<?php
   class Data_model extends CI_Model {
   	
	public function __construct() {
       $this -> load -> database();
    }

    public function getAllUserPosition($userID = FALSE) {
        if (!is_numeric($userID)) {
            return FALSE;
    	}
	}
	
	public function getLastValue($meterID){
		
		$query = "SELECT `Value`, `TimeStamp` FROM `value` WHERE `MeterID` = $meterID ORDER BY `TimeStamp` DESC LIMIT 1";
		
		$DBAnswer = $this -> db -> query($query);
        $DBAnswer = $DBAnswer -> result_array();
		
        if (count($DBAnswer)>0) {
            return $DBAnswer;
        } else {
            return FALSE;
        }
	}
		 
	public function putValue($meterID, $value, $timeStamp)
	{
		if (!is_numeric($meterID) || !is_numeric($value)) {
        	return FALSE;
        }
		
		/**Erweiterung 
		 * TimeStamp Eingabe möglich, wenn kein Wert vorhanden
		 * aktuellen Wert mit Now() nehmen*/
		 
		if ($timeStamp == NULL){	
			$query = 	"INSERT INTO `value` (`ID`, `MeterID`, `Value`, `TimeStamp`)".
						"VALUES (NULL , '$meterID', '$value', now());";
		}else{
			$query = 	"INSERT INTO `value` (`ID`, `MeterID`, `Value`, `TimeStamp`)".
						"VALUES (NULL , '$meterID', '$value', $timeStamp);";	
		}
					
        $DBAnswer = $this -> db -> query($query);

        if (count($DBAnswer)>0) {
            return TRUE; //$this -> db -> insert_id();
        } else {
            return FALSE;
        }
	}
	
	public function getArea($meterID, $startTime, $endTime){
			
		$query = "SELECT  `Value` ,  `TimeStamp` 
				 FROM  `value` 
				 WHERE  `MeterID` = $meterID
				 AND  `TimeStamp` >=  $startTime
				 AND  `TimeStamp` <=  $endTime
 				 ORDER BY  `TimeStamp` DESC" ;
		/*
		 * Alternative mit Datums Formatierung?
		 * 
		$query = "SELECT  `Value` ,  `TimeStamp` 
				 FROM  `value` 
				 WHERE  `MeterID` = $meterID
				 AND  `TimeStamp` >=  (SELECT date_format($startTime, '%Y-%m-%d %k:%i:%s'))
				 AND  `TimeStamp` <=  (SELECT date_format($endTime, '%Y-%m-%d %k:%i:%s'))
 				 ORDER BY  `TimeStamp` DESC" ;
			*/	 
		$DBAnswer = $this -> db -> query($query);
        $DBAnswer = $DBAnswer -> result_array();
		
        if (count($DBAnswer)>0) {
            return $DBAnswer;
        } else {
            return FALSE;
        }	
	}
	
	/**
	 * Funktion die ein 3 dim Array aufnimmt. 
	 * Inhalt wäre: meterID,Timestamp und value - wird mit InsertInto eingefügt
	 * */
	 public function putArray(){
	 	
		
	 }
	 
	 /**
	  * Funktion die neuen Meter anlegt
	  * Alle Parameter der Tabelle Meter
	  * Rückgabewert meterID?? die vergeben wurde*/
	  
	  public function putMeter($name, $meterNumber, $description, $unit){
		  	
	  	//Aktuell letzte UserID holen
		  $lastUser = "SELECT  `meter`.`UserID` 
		  			   FROM  `meter` 
		  			   ORDER BY  `meter`.`UserID` DESC 
		  			   LIMIT 1";
		  
		  $DBAnswer = $this -> db -> query($lastUser);
	        
		//Useraddition für den folgenden Insert Befehl
		   
		  $newUser = $DBAnswer + 1;
		  
		//neuen Meter anlegen
		  
		  $insert = "INSERT INTO `meter` (`ID`, `UserID`, `Name`, `MeterNumber`, `Description`, `Unit`)".
					"VALUES (NULL , '$newUser', '$name', '$meterNumber', '$description', '$unit');";
					
		  $DBAnswer = $this -> db -> query($insert);
		
		//Aktuell letzte meterID holen für die Rückgabe
		  $lastMeter = "SELECT  `meter`.`ID` 
		  			   FROM  `meter` 
		  			   ORDER BY  `meter`.`ID` DESC 
		  			   LIMIT 1";
		  
		  $DBAnswer = $this -> db -> query($lastMeter);
		  
		  if (count($DBAnswer)>0) {
	            return $DBAnswer;
	        } else {
	            return FALSE;
	        }	
	  } 
}

?>
