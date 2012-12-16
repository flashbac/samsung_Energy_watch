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
	
	public function putValue($meterID, $value)
	{
		if (!is_numeric($meterID) || !is_numeric($value)) {
        	return FALSE;
        }
		$query = 	"INSERT INTO `value` (`ID`, `MeterID`, `Value`, `TimeStamp`)".
					"VALUES (NULL , '$meterID', '$value', NOW( ));";
					
        $DBAnswer = $this -> db -> query($query);

        if (count($DBAnswer)>0) {
            return TRUE; //$this -> db -> insert_id();
        } else {
            return FALSE;
        }
	
	}
	
}
?>