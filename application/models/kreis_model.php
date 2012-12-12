<?php

class Kreis_model extends CI_Model {

    function __construct() {

        $this -> load -> database();
    }

    function getKreisverband($kreisID = FALSE) {
        if ($kreisID == FALSE) {
            return FALSE;
        }

        /* Bsp. fuer SQL WHERE query
         * SELECT * FROM Persons WHERE UserID=1;
         */

        $query = "SELECT * FROM `kreisverband` WHERE KreisverbandID = $kreisID;";

        $DBAnswer = $this -> db -> query($query);

        $DBAnswer = $DBAnswer -> result_array();

        if (count($DBAnswer) > 0) {
            return $DBAnswer[0];
        } else {
            return FALSE;
        }
    }

    public function getIDfromKreis($kreisName = FALSE) {
        if ($kreisName == FALSE) {
            return FALSE;
        }

        $query = "SELECT KreisverbandID FROM `kreisverband` WHERE Name='$kreisName'";

        $DBAnswer = $this -> db -> query($query);

        if ($tmp = $this -> db -> affected_rows() != 1) {
            return FALSE;
        }// wenn es weniger oder mehr als ein result kommt, abbrechen

        $DBAnswer = $DBAnswer -> result_array();

        return $DBAnswer[0]['KreisverbandID'];
    }

    public function getKreise($limitStart = 0, $limitStop = 100) {
        if (!is_numeric($limitStart) || !is_numeric($limitStop)) {
            return FALSE;
        }

        /* Bsp. fuer SQL WHERE query
         * SELECT * FROM Persons WHERE UserID=1;
         */

        $query = "SELECT * FROM `kreisverband` LIMIT $limitStart,$limitStop;";

        $DBAnswer = $this -> db -> query($query);

        $DBAnswer = $DBAnswer -> result_array();

        if (count($DBAnswer) > 0) {
            return $DBAnswer;
        } else {
            return FALSE;
        }
        return $data;
    }

    public function getKreisWith($limitStart = 0, $limitStop = 100) {
        $data = $this -> getKreise($limitStart, $limitStop);
        if ($data !== FALSE) {
            foreach ($data as $key => $value) {
                if (is_numeric($data[$key]['Kreisjugendleiter'])) {
                    $tmp = $this -> User_model -> get_user($data[$key]['Kreisjugendleiter']);
                    $data[$key]['Kreisjugendleiter'] = $tmp['Name'] . ', ' . $tmp['Vorname'];
                } else {
                    $data[$key]['Kreisjugendleiter'] = 'nicht gesetzt';
                }

                $data[$key]['Strasse'] = $data[$key]['Strasse'] . ' ' . $data[$key]['Hausnr'];

                unset($data[$key]['Hausnr']);
                unset($data[$key]['Kreisjugendleiter2']);
            }

            if (count($data) > 0) {
                return $data;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }

    }

    public function getCountOfKreis() {

        $query = "SELECT COUNT(KreisverbandID) AS ANZAHL FROM `kreisverband`;";

        $DBAnswer = $this -> db -> query($query);

        $DBAnswer = $DBAnswer -> result_array();

        if (count($DBAnswer) > 0) {
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

    public function updateKreisverband($kreisID = FALSE, $data) {
        if ($kreisID == FALSE) {
            return FALSE;
        }
        //KreisverbandID    Abkuerzung  Kreisjugendleiter   Kreisjugendleiter2  Ortsteil    Ort     Strasse     Hausnr  Plz
        $data = $this -> prepareArray($data, array('Name', 'Kreisjugendleiter', 'Kreisjugendleiter2', 'Ortsteil', 'Ort', 'Strasse', 'Hausnr', 'Plz'));

        if (is_numeric($kreisID)) {
            return $this -> updateKreis($kreisID, $data);
        } else {
            return $this -> createKreis($data);
        }
    }

    private function createKreis($data) {

        $query_front = 'INSERT INTO `kreisverband` (';
        $query_back = 'VALUES (';

        // fuer den ersten DB-Wert, damit er kein Komma schreibt
        $isNotFirstEntry = FALSE;

        if ($data['Name'] == FALSE || $data['Ortsteil'] == FALSE) {
            echo "<pre>";
            print_r($data);
            echo "</pre>";
            echo "<p>exit</p>";
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
            echo "<p>[createKreis] input:</p>";
            echo '<pre>';
            print_r($data);
            echo '</pre>';
            echo "<p>SQL Query [createKreis]: " . $query_front . $query_back . '</p>';
            echo "<p>SQL Antwort  [createKreis]: " . $this -> db -> insert_id() . '</p>';
            echo '</div>';
        }

        if (count($DBAnswer) > 0) {
            return $this -> db -> insert_id();
        } else {
            return FALSE;
        }
    }

    private function updateKreis($kreisID = FALSE, $data) {
        /*
         * SQL UPDATE Bsp.: UPDATE Persons SET Address='Nissestien 67', City='Sandnes'
         * WHERE LastName='Tjessem' AND FirstName='Jakob'
         */
        $query_front = 'UPDATE `kreisverband` SET ';
        $query_back = "WHERE KreisverbandID=$kreisID";

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
            echo "<p>[updateKreis] input:</p>";
            echo '<pre>';
            print_r($data);
            echo '</pre>';
            echo "<p>SQL Query [updateKreis]: " . $query_front . $query_back . '</p>';
            echo "<p>SQL Antwort  [updateKreis]: " . $this -> db -> affected_rows() . '</p>';
            echo '</div>';
        }

        if (count($DBAnswer) > 0) {
            if ($this -> db -> affected_rows() > 0) {
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }

    public function deleteKreis($kreisID = FALSE) {

        if ($kreisID == FALSE) {
            return FALSE;
        }

        if ($kreisID == 0) {
            return FALSE;
        }

        $query = "DELETE FROM `userkreisverband` WHERE KreisverbandID=$kreisID";

        $DBAnswer = $this -> db -> query($query);

        $query = "DELETE FROM `kreisverband` WHERE KreisverbandID=$kreisID;";

        $DBAnswer = $this -> db -> query($query);

        if ($this -> db -> affected_rows() != 1) {
            return FALSE;
        } else {
            return TRUE;
        }

    }

    public function getKreisverbandLeiter($kreisID = FALSE) {
        if (!is_numeric($kreisID)) {
            return FALSE;
        }

        //$query = "SELECT userkreisverband.KreisverbandID, MitgleidSeit, Austritt, Abkuerzung, Kreisjugendleiter, Kreisjugendleiter2, Ortsteil, Ort, Strasse, Hausnr, Plz FROM kreisverband
        //            INNER JOIN userkreisverband ON userkreisverband.KreisverbandID= kreisverband.KreisverbandID
        //            WHERE userkreisverband.UserID=$userID;";

        $query = "SELECT *
                from kreisverband
                 WHERE KreisverbandID=$kreisID;";

        $DBAnswer = $this -> db -> query($query);
        $DBAnswer = $DBAnswer -> result_array();

        if (count($DBAnswer) > 0) {
            return $DBAnswer[0];
        } else {
            return FALSE;
        }
    }

    public function getKreisverbandByUser($UserID = FALSE) {
        if (!is_numeric($UserID)) {
            return FALSE;
        }

        //$query = "SELECT userkreisverband.KreisverbandID, MitgleidSeit, Austritt, Abkuerzung, Kreisjugendleiter, Kreisjugendleiter2, Ortsteil, Ort, Strasse, Hausnr, Plz FROM kreisverband
        //            INNER JOIN userkreisverband ON userkreisverband.KreisverbandID= kreisverband.KreisverbandID
        //            WHERE userkreisverband.UserID=$userID;";

        $query = "SELECT kreisverband.Name from userkreisverband left join
				kreisverband on kreisverband.KreisverbandID = userkreisverband.KreisverbandID
                WHERE userkreisverband.UserID=$UserID;";

        $DBAnswer = $this -> db -> query($query);

        $DBAnswer = $DBAnswer -> result_array();

        if (count($DBAnswer) > 0) {
            return $DBAnswer;
        } else {
            return FALSE;
        }
        return $data;
    }

    public function setKreisLeiter($kreisID = FALSE, $leiter1 = FALSE, $leiter2 = FALSE) {
        if ($leiter1 === FALSE && $leiter2 === FALSE) {
            return FALSE;
        }

        $query_front = 'UPDATE `kreisverband` SET ';
        $query_back = " WHERE KreisverbandID=$kreisID";

        if ($leiter1) {
            $query_front .= 'Kreisjugendleiter=' . $leiter1;
        }
        if ($leiter1 && $leiter2) {
            $query_front .= ', ';
        }
        if ($leiter2) {
            $query_front .= 'Kreisjugendleiter2=' . $leiter2;
        }

        $DBAnswer = $this -> db -> query($query_front . $query_back);

        if (count($DBAnswer) > 0) {
            if ($this -> db -> affected_rows() > 0) {
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }

    function getValidationRules() {
        $config = array( array('field' => 'Name', 'label' => 'Name:', 'rules' => 'required|trim|xss_clean'), array('field' => 'Strasse', 'label' => 'Stra&szlig;e:', 'rules' => 'required|trim|xss_clean'), array('field' => 'HausNr', 'label' => 'Hausnummer:', 'rules' => 'required|trim|xss_clean'), array('field' => 'Plz', 'label' => 'PLZ:', 'rules' => 'required|trim|xss_clean|exact_length[5]'), array('field' => 'Ort', 'label' => 'Ort:', 'rules' => 'required|trim|xss_clean'), );
        return $config;
    }

}
?>