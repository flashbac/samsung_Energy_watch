<?php
//define ( 'DEBUG', TRUE);
   class Data_model extends CI_Model 
   {
   	
	public function __construct() 
	{
       $this -> load -> database();
    }

    public function getAllUserPosition($userID = FALSE) 
    {
        if (!is_numeric($userID)) 
        {
            return FALSE;
    	}
	}
	
	public function getLastValue($meterID){
		
		$query = "SELECT `Value`, `TimeStamp` 
				  FROM `value` 
				  WHERE `MeterID` = $meterID
				  ORDER BY  `TimeStamp` DESC 
				  LIMIT 1";
		
		$DBAnswer = $this -> db -> query($query);
        $DBAnswer = $DBAnswer -> result_array();
		
        if (count($DBAnswer)>0)
		{
            return $DBAnswer;
        } else {
            return FALSE;
        }
	}
		 
	public function putValue($meterID, $value, $timeStamp)
	{
		if (!is_numeric($meterID) || !is_numeric($value)) 
		{
        	return FALSE;
        }
		
		/**Erweiterung 
		 * TimeStamp Eingabe möglich, wenn kein Wert vorhanden
		 * aktuellen Wert mit Now() nehmen*/
		 
		if ($timeStamp == NULL)
		{	
			$query = 	"INSERT INTO `value` (`ID`, `MeterID`, `Value`, `TimeStamp`)".
						"VALUES (NULL , '$meterID', '$value', now());";
		}else{
			$query = 	"INSERT INTO `value` (`ID`, `MeterID`, `Value`, `TimeStamp`)".
						"VALUES (NULL , '$meterID', '$value', '$timeStamp');";	
		}
		
		If (defined('DEBUG')) {
            echo '<div id="debug">';
            echo "<p>SQL Query PutValue</p>";
            echo "<p>SQL Query: " . $query . '</p>';
            echo '</div>';
        }
				
        $DBAnswer = $this -> db -> query($query);

        if (count($DBAnswer)>0) 
        {
            return TRUE; //$this -> db -> insert_id();
        } else {
            return FALSE;
        }
	}
	
	public function getAreaValues($meterID, $startTime, $endTime)
	{
			
		$query = "SELECT  `Value` ,  `TimeStamp` 
				 FROM  `value` 
				 WHERE  `MeterID` = '$meterID'
				 AND  `TimeStamp` >=  '$startTime'
				 AND  `TimeStamp` <=  '$endTime'
 				 ORDER BY  `TimeStamp`
				 LIMIT 4000";
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
		
        if (count($DBAnswer)>0) 
        {
            return $DBAnswer;
        } else {
            return FALSE;
        }	
	}
	
	/**
	 * Funktion die ein 3 dim Array aufnimmt. 
	 * Inhalt wäre: meterID,Timestamp und value - wird mit InsertInto eingefügt
	 * */
	 public function putAreaValues($str)
	 {
		
// Explode Variante

		$array1 = explode(";",$str);
		
		$temp1 = array();
		$oneValue1 = array(); 
		$i = 0;
		
		foreach ($array1 as $temp) 
		{
			
			$temp2 = explode(",",$temp);	
			$temp1 = $temp2;
			

			foreach ($temp1 as $oneValue)
			{
				//Abfrage in welches Türchen die Daten reinfallen sollen 
				if($i == 0){
					$meterID = $oneValue;
					
					 /**$meterID = array()
					 * $meterID[] = $oneValue;
					 * */
					 
				};
				if($i == 1){
					$value = $oneValue;
				};	
				if($i == 2){
					$timeStamp = $oneValue;
				};
				
				//Wenn timeStamp belegt dann kann endlich gepushed werden
				if($i=2)
				{
					//reset des Zaehlers
					$i=-1;
					$insert = 		"INSERT INTO `value` (`ID`, `MeterID`, `Value`, `TimeStamp`)".
									"VALUES (NULL , '$meterID', '$value', '$timeStamp');";
						
					$DBAnswer = $this -> db -> query($insert);
					if($DBAnswer != FALSE)
						return FALSE;
				}
				//inkrementierung
				$i = $i + 1;
			}
		}
	 }
	 
	 /**
	  * Funktion die neuen Meter anlegt
	  * Alle Parameter der Tabelle Meter
	  * Rückgabewert meterID die vergeben wurde*/
	  
	  public function putMeter($UserID, $name, $meterNumber, $description, $unit)
	  {
		  	
	  	//neuen Meter anlegen
		  
		  $insert = "INSERT INTO `meter` (`ID`, `UserID`, `Name`, `MeterNumber`, `Description`, `Unit`)".
					"VALUES (NULL , '$UserID', '$name', '$meterNumber', '$description', '$unit');";
					
		  $DBAnswer = $this -> db -> query($insert);


		  if (count($DBAnswer)>0) 
		  {
	            return $this -> db -> insert_id();
	      } else {
	            return FALSE;
	      }
		  	
	  }
	  
	  public function getMeter($UserName,$passwort)
	  {
	  	
	  	
	  }
	  
	  
	public function deleteAllValuesByMeterID($meterID)
	{
		$query = "DELETE FROM `value` WHERE `MeterID` = $meterID;";
		 
		$DBAnswer = $this -> db -> query($query);

		if ($this -> db -> affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }	
	}
}

?>
