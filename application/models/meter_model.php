<?php

class Meter_model extends CI_Model {

    function __construct() {

        $this -> load -> database();
    }

    function getMeter($meterID = FALSE) {
        if ($meterID == FALSE) {
            return FALSE;
        }

        /* Bsp. fuer SQL WHERE query
         * SELECT * FROM Persons WHERE UserID=1;
         */

        $query = "SELECT * FROM `meter` WHERE ID=$meterID;";

        $DBAnswer = $this -> db -> query($query);

        $DBAnswer = $DBAnswer -> result_array();

        If (defined('DEBUG')) {
            echo '<div id="debug">';
            echo "<p>[getmeter] input: \$meterID = $meterID;</p>";
            echo "<p>SQL Query [getmeter]: " . $query . '</p>';
            echo "<p>SQL Antwort  [getmeter]: </p>";
            echo '</div>';
        }

        if (count($DBAnswer) > 0) { 
            return $DBAnswer[0];
        } else {
            return FALSE;
        }
    }

    function getIDfromMeter($name = FALSE) {
        if ($name == FALSE) {
            return FALSE;
        }

        $query = "SELECT ID FROM `meter` WHERE Name='$name';";

        $DBAnswer = $this -> db -> query($query);

        $DBAnswer = $DBAnswer -> result_array();

        if (isset($DBAnswer[0])) {
            return $DBAnswer[0]['ID'];
        } else {
            return 0;
        }
    }

    public function getMeters($userID = FALSE, $limitStart = 0, $limitStop = 100, $keineWdh = FALSE) {
        if ($userID == FALSE || !is_numeric($limitStart) || !is_numeric($limitStop)) {
            return FALSE;
        }

        /* Bsp. fuer SQL WHERE query
         * SELECT * FROM Persons WHERE UserID=1;
         */
         $query = "SELECT kreisverband.Name from userkreisverband left join
				kreisverband on kreisverband.KreisverbandID = userkreisverband.KreisverbandID
                WHERE userkreisverband.UserID=$userID;";
				
         if($keineWdh){
             $query = "SELECT DISTINCT ID, Name, MeterNumber, Description, Unit FROM `meter` ORDER BY Name LIMIT $limitStart,$limitStop;";
         }else{
             $query = "SELECT ID, Name, MeterNumber, Description, Unit  FROM `meter` WHERE userID=$userID ORDER BY Name LIMIT $limitStart,$limitStop;";
         }

        $DBAnswer = $this -> db -> query($query);

        $DBAnswer = $DBAnswer -> result_array();

        if (count($DBAnswer)>0) {
            return $DBAnswer;
        } else {
            return FALSE;
        }
        return $data;
    }

    public function getCountOfMeters() {

        $query = "SELECT COUNT(ID) AS ANZAHL FROM `meter`;";

        $DBAnswer = $this -> db -> query($query);

        $DBAnswer = $DBAnswer -> result_array();

        if (count($DBAnswer)>0) {
            return $DBAnswer[0]['ANZAHL'];
        } else {
            return FALSE;
        }
        return $data;
    }

    private function prepareArray($data, $keys) {
        foreach ($keys as $key => $value) {
            if ((!isset($data[$value])) || empty($data[$value])) {
                $data[$value] = false;
            }
        }
        return $data;
    }

    public function update($ID = FALSE, $data) {
        if (!$ID) {
            return FALSE;
        }
        //KreisverbandID    Abkuerzung  Kreisjugendleiter   Kreisjugendleiter2  Ortsteil    Ort     Strasse     Hausnr  Plz
        $data = $this -> prepareArray($data, array('UserID','Name', 'MeterNumber', 'Description', 'Unit'));

        if (is_numeric($ID)) {
            return $this -> updatemeter($ID, $data);
        } else {
            return $this -> createmeter($data);
        }
    }

    private function createmeter($data) {

        $query_front = 'INSERT INTO `meter` (';
        $query_back = 'VALUES (';

        // fuer den ersten DB-Wert, damit er kein Komma schreibt
        $isNotFirstEntry = FALSE;

        if ($data['UserID'] == FALSE || $data['Name'] == FALSE || $data['MeterNumber'] == FALSE || $data['Description'] == FALSE || $data['Unit'] == FALSE) {
            return false;
        }

        foreach ($data as $key => $value) {
            if ($value) {
                if ($isNotFirstEntry) {
                    $query_front = $query_front . ',' . $key;
                    $query_back = $query_back . ",'" . $value . "'";
                } else {
                    $query_front = $query_front . $key;
                    $query_back = $query_back . "'" . $value . "'";
                    $isNotFirstEntry = TRUE;
                }
            }
        }

        $query_front = $query_front . ') ';
        $query_back = $query_back . ')';
        $DBAnswer = $this -> db -> query($query_front . $query_back);

        If (defined('DEBUG')) {
            echo '<div id="debug">';
            echo "<p>[createmeter] input:</p>";
            echo '<pre>';
            print_r($data);
            echo '</pre>';
            echo "<p>SQL Query [createmeter]: " . $query_front . $query_back . '</p>';
            echo "<p>SQL Antwort  [createmeter]: " . $this -> db -> insert_id() . '</p>';
            echo '</div>';
        }

        if (count($DBAnswer)>0) {
            return $this -> db -> insert_id();
        } else {
            return FALSE;
        }
    }

    private function updatemeter($meterID = FALSE, $data) {
        /*
         * SQL UPDATE Bsp.: UPDATE Persons SET Address='Nissestien 67', City='Sandnes'
         * WHERE LastName='Tjessem' AND FirstName='Jakob'
         */
        $query_front = 'UPDATE `meter` SET ';
        $query_back = "WHERE ID=$meterID";

        // fuer den ersten DB-Wert, damit er kein Komma schreibt
        $isNotFirstEntry = FALSE;

        foreach ($data as $key => $value) {
            if ($value) {
                if ($isNotFirstEntry) {
                    $query_front = $query_front . ", $key='$value'";
                } else {
                    $query_front = $query_front . " $key='$value'";
                    $isNotFirstEntry = TRUE;
                }
            }
        }
        $DBAnswer = $this -> db -> query($query_front . $query_back);

        If (defined('DEBUG')) {
            echo '<div id="debug">';
            echo "<p>[updatemeter] input:</p>";
            echo '<pre>';
            print_r($data);
            echo '</pre>';
            echo "<p>SQL Query [updatemeter]: " . $query_front . $query_back . '</p>';
            echo "<p>SQL Antwort  [updatemeter]: " . $this -> db -> affected_rows() . '</p>';
            echo '</div>';
        }

        if (count($DBAnswer)>0) {
            if ($this -> db -> affected_rows() > 0) {
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }

    public function deletemeter($meterID = FALSE) {
        if ($meterID == FALSE) {
            return FALSE;
        }

        $query = "DELETE FROM meter WHERE ID = $meterID";

        $DBAnswer = $this -> db -> query($query);

        if ($this -> db -> affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }

    }


    function getValidationRules() {
       	$config = array(
			array('field' => 'Name', 'label' => 'Name:', 'rules' => 'required',
			'field' => 'MeterNumber', 'label' => 'MeterNumber:', 'rules' => 'required',
			'field' => 'Description', 'label' => 'Description:', 'rules' => 'required',
			'field' => 'Unit', 'label' => 'Unit:', 'rules' => 'required',
            )
       	);
        return $config;
	}
}
?>