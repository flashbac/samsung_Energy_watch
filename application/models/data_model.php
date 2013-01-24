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
    
    public function getIDfromMeternumber($meterID)
    {
        if (!is_numeric($meterID)) 
        {
            return 0;
        }
        
        $query = "SELECT `ID`
                  FROM `meter` 
                  WHERE `MeterNumber` = $meterID";
        
        $DBAnswer = $this -> db -> query($query);
        $DBAnswer = $DBAnswer -> result_array();

        if (count($DBAnswer)>0) 
        {
            return $DBAnswer[0]['ID'];
        } else {
            return 0;
        }
    }

	public function putValue($meterID, $value, $timeStamp)
	{
		if (!is_numeric($meterID) || !is_numeric($value)) 
		{
        	return FALSE;
        }
        
        /*
         * so meterID ist nicht die DB id sonder die nummer des Meters, sowas wie die Serialnumber 
         * deswegen müssen wir diese zu der DB id zupordnen
         * wenn es die Sierlanumber noch nicht gibt, erstellen wir einträge für ihn
         */
        $meterNumber = $this->getIDfromMeternumber($meterID);
        if($meterNumber != 0){
            
            $meterID = $meterNumber;
        } else{
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
 				 ORDER BY  `TimeStamp`";
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
	/**
	 * Funktion die ein 3 dim Array aufnimmt. 
	 * Inhalt wäre: meterID,Timestamp und value - wird mit InsertInto eingefügt
	 * */
	 public function putAreaValues($str)
	 {
	
// Explode Variante

		$array1 = explode("~",$str);

		$insertString = "INSERT INTO `value` (`ID`, `MeterID`, `Value`, `TimeStamp`) VALUES";
		
		foreach ($array1 as $row) 
		{
			$element = explode("_",$row);	
			$insertString = $insertString."(NULL , '$element[0]', '$element[1]','$element[2]'),";					
		}
		
		$insertString = $insertString.";";
		
		$insertString = str_replace(",;", ";", $insertString);
		
		If (defined('DEBUG')) {
            echo '<div id="debug">';
            echo "<p>SQL Query PutAreaValues</p>";
            echo "<p>SQL Query: " . $insertString . '</p>';
            echo '</div>';
        }
        
		$DBAnswer = $this -> db -> query($insertString);
								
		if (count($DBAnswer)>0) {
				return $DBAnswer;
		} else {
				return FALSE;
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
