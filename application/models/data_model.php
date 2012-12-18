<?php
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
				  ORDER BY `TimeStamp` DESC 
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
						"VALUES (NULL , '$meterID', '$value', $timeStamp);";	
		}
					
        $DBAnswer = $this -> db -> query($query);

        if (count($DBAnswer)>0) 
        {
            return TRUE; //$this -> db -> insert_id();
        } else {
            return FALSE;
        }
	}
	
	public function getArea($meterID, $startTime, $endTime)
	{
			
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
	 public function putArray($str)
	 {
		
// Explode Variante

		$array1 = explode(";",$str);
		$temp = array();
		$oneValue = array(); 
		
		foreach ($array1 as $temp) 
		{
			
			$temp = explode(",",$str);
			
			foreach ($temp as $oneValue)
			{
				
				if($oneValue[2] != NULL)
				{
					
					$insert = 		"INSERT INTO `value` (`ID`, `MeterID`, `Value`, `TimeStamp`)".
									"VALUES (NULL , '$oneValue[0]', '$oneValue[1]', '$oneValue[2]');";
						
					$DBAnswer = $this -> db -> query($insert);
						
						if (count($DBAnswer)>0) 
						{
				            return $DBAnswer;
				        } else {
				            return FALSE;
				        }
				   	
					// $oneValue = array(); oder reset($oneValue)?
				}
			}
		}
		
/**	strtock Variante
 * 	
		$kette = "$str";								//Zeichenkette		
		$temp1 = strtok($kette,";");					//Ausplitten des ersten Teiles ";"
		$tempArray = array();							//Hier Array erzeugen
		
		while ($temp1) {								//Wenn ein Wert vorhanden dann gehe weiter 											1.TEILUNG
					
				$temp2 = strtok($temp1, ","); 			//zweiten und zugleich kleineren Teil der Zeichenkette nehmen und wieder splitten					
				// $tempArray = array();					
				$i = 0;									//Zählvariable
				
				while ($temp2) {						//																					2. Teilung
								
					$tempArray[$i] = $temp2;			//wenn Wert vorhanden dann in Array schreiben			
					
					if($i == 2){						//Wenn insgesamt 3 Werte vorhanden sind dann schreibe diese dann auch in die DB
						
						$i = 0;							//Null setztem
						
						$insert = 	"INSERT INTO `value` (`ID`, `MeterID`, `Value`, `TimeStamp`)".
									"VALUES (NULL , '$tempArray[0]', '$tempArray[1]', '$tempArray[2]');";
						
								
						unset($tempArray);				//Array leeren
						
		  				$DBAnswer = $this -> db -> query($insert);
						
						if (count($DBAnswer)>0) {
				            return $DBAnswer;
				        } else {
				            return FALSE;
				        }	
					}
					
					$temp1 = strtok(",");				//Damit "," nicht mit angegeben wird
					$i = $i + 1;
				}
				
			$temp1 = strtok(";");
*/				
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
		
		//Aktuell letzte meterID holen für die Rückgabe
		  $lastMeter = "SELECT  `meter`.`ID` 
		  			   FROM  `meter` 
		  			   ORDER BY  `meter`.`ID` DESC 
		  			   LIMIT 1";
		  
		  $DBAnswer = $this -> db -> query($lastMeter);
		  
		  if (count($DBAnswer)>0) 
		  {
	            return $DBAnswer;
	      } else {
	            return FALSE;
	      }	
	  } 
}

?>
