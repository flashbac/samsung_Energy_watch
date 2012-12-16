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
		
		$query = "SELECT `Value` `TimeStamp` FROM `value` WHERE `MeterID` = $meterID ORDER BY `TimeStamp` DESC LIMIT 1";
		
		$DBAnswer = $this -> db -> query($query);
        $DBAnswer = $DBAnswer -> result_array();
		
		if (count($DBAnswer)>0){
          	return $DBAnswer;
		} 
 		else{
          	return FALSE;
		}
	}
	
	
}
?>