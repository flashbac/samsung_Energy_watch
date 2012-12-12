<?php
// define ( 'DEBUG', TRUE);
class Com_model extends CI_Model {

    public function __construct() {
        $this -> load -> database();
    }

    public function getAllUserPosition($userID = FALSE) {
        if (!is_numeric($userID)) {
            return FALSE;
        }

        // $query = "SELECT userposition.PositionID, Position, BedarfHaben FROM position
        // INNER JOIN userposition ON userposition.PositionID= position.PositionID
        // WHERE userposition.UserID=$userID;";
        $query = "SELECT * FROM userposition WHERE userposition.UserID=$userID;";

        $DBAnswer = $this -> db -> query($query);
        $DBAnswer = $DBAnswer -> result_array();

        if (count($DBAnswer)>0) {
            return $DBAnswer;
        } else {
            return FALSE;
        }
    }

    public function getUserPosition($userID = FALSE) {
        if (!is_numeric($userID)) {
            return FALSE;
        }

        $query = "SELECT userposition.PositionID, Position, BedarfHaben FROM position
                    // INNER JOIN userposition ON userposition.PositionID= position.PositionID
                    // WHERE userposition.UserID=$userID;";

        $DBAnswer = $this -> db -> query($query);
        $DBAnswer = $DBAnswer -> result_array();

        if (count($DBAnswer)>0) {
            return $DBAnswer;
        } else {
            return FALSE;
        }
    }

    public function addUserPosition($userID = FALSE, $posID = FALSE, $von = FALSE, $bis = FALSE) {

        if (!is_numeric($userID) || !is_numeric($posID)) {
            return FALSE;
        }

        $query = "INSERT INTO `userposition` (UserPositionID, UserID, PositionID, Von, Bis) VALUES (NULL, '$userID', '$posID', '$von', '$bis');";

        $DBAnswer = $this -> db -> query($query);

        if (count($DBAnswer)>0) {
            return $this -> db -> insert_id();
        } else {
            return FALSE;
        }
    }

    public function deleteUserPosition($UserPositionID = FALSE) {

        if (!is_numeric($UserPositionID)) {
            return FALSE;
        }

        $query = "DELETE FROM `userposition` WHERE UserPositionID = $UserPositionID;";

        $DBAnswer = $this -> db -> query($query);

        if ($tmp = $this -> db -> affected_rows() != 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function updateUserPosition($UserPositionID = FALSE, $userID = FALSE, $posID = FALSE, $von = FALSE, $bis = FALSE) {

        if (!is_numeric($UserPositionID) || !is_numeric($userID) || !is_numeric($posID)) {
            return FALSE;
        }

        $query = "UPDATE `userposition` set UserID=$userID, PositionID=$posID, Von='$von', Bis='$bis' WHERE UserPositionID = $UserPositionID;";

        $DBAnswer = $this -> db -> query($query);

        if ($this -> db -> affected_rows() != 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function getAllUserQualifikation($userID = FALSE) {
        if (!is_numeric($userID)) {
            return FALSE;
        }

        $query = "SELECT * FROM userqualifikation WHERE userqualifikation.UserID=$userID;";

        $DBAnswer = $this -> db -> query($query);
        $DBAnswer = $DBAnswer -> result_array();

        if (count($DBAnswer)>0) {
            return $DBAnswer;
        } else {
            return FALSE;
        }
    }

    public function getUserQualifikation($userID = FALSE) {
        if (!is_numeric($userID)) {
            return FALSE;
        }

        $query = "SELECT userqualifikation.QualifikationID, Beschreibung, Grundrichtung FROM qualifikation
                    INNER JOIN userqualifikation ON userqualifikation.QualifikationID= qualifikation.QualifikationID
                    WHERE userqualifikation.UserID=$userID;";

        $DBAnswer = $this -> db -> query($query);
        $DBAnswer = $DBAnswer -> result_array();

        if (count($DBAnswer)>0) {
            return $DBAnswer;
        } else {
            return FALSE;
        }
    }

    public function addUserQualifikation($userID = FALSE, $qualiID = FALSE, $hatBedarf = 0, $von = NULL, $bis = NULL) {

        if (!is_numeric($userID) || !is_numeric($qualiID)) {
            return FALSE;
        }

        if ($hatBedarf != 0) {
            $hatBedarf = 1;
        }

        $query = "INSERT INTO `userqualifikation` (UserID, QualifikationID, BedarfHaben, Von, Bis) VALUES ('$userID', '$qualiID', $hatBedarf, '$von', '$bis');";

        $DBAnswer = $this -> db -> query($query);
        
        If (defined('DEBUG')) {
			 echo '<div id="debug">';
	        echo "<p>[addUserQualifikation] input:</p>";
	        echo "<p>SQL Query [addUserQualifikation]: " . $query .'</p>';
	        echo "<p>SQL Antwort [addUserQualifikation]: " . $this -> db -> insert_id() . '</p>';
	        echo '</div>';
        }
        
        if (count($DBAnswer)>0) {
            return $this -> db -> insert_id();
        } else {
            return FALSE;
        }
    }

    public function deleteUserQualifikation($userID = FALSE, $qualiID = FALSE) {

        if (!is_numeric($userID) || !is_numeric($qualiID)) {
            return FALSE;
        }

        $query = "DELETE FROM `userqualifikation` WHERE UserID = $userID AND QualifikationID=$qualiID;";

        $DBAnswer = $this -> db -> query($query);

        if ($tmp = $this -> db -> affected_rows() != 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function updateUserQualifikation($userID = FALSE, $qualiID = FALSE, $hatBedarf = 0, $von = NULL, $bis = NULL) {

        if (!is_numeric($userID) || !is_numeric($qualiID)) {
            return FALSE;
        }

        if ($hatBedarf != 0) {
            $hatBedarf = 1;
        }

        $query = "UPDATE `userqualifikation` set Von='$von', Bis='$bis' WHERE UserID = $userID AND QualifikationID=$qualiID AND BedarfHaben=$hatBedarf; ";
        $DBAnswer = $this -> db -> query($query);

        If (defined('DEBUG')) {
			echo '<div id="debug">';
	        echo "<p>[updateUserQualifikation] input:</p>";
	        echo "<p>SQL Query [updateUserQualifikation]: " . $query .'</p>';
	        echo "<p>SQL Antwort [updateUserQualifikation]: " . $this -> db -> insert_id() . '</p>';
	        echo '</div>';
        }

        if ($this -> db -> affected_rows() != 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function getUserInteressen($userID = FALSE) {
        if (!is_numeric($userID)) {
            return FALSE;
        }

        $query = "SELECT userinteressen.InteressenID, Name, BedarfHaben FROM interessen
                    INNER JOIN userinteressen ON userinteressen.InteressenID= interessen.InteressenID
                    WHERE userinteressen.UserID=$userID;";

        $DBAnswer = $this -> db -> query($query);
        $DBAnswer = $DBAnswer -> result_array();

        if (count($DBAnswer)>0) {
            return $DBAnswer;
        } else {
            return FALSE;
        }
    }

    public function addUserInteressen($userID = FALSE, $intIDD = FALSE, $hatBedarf = 0) {

        if (!is_numeric($userID) || !is_numeric($intID)) {
            return FALSE;
        }

        if ($hatBedarf != 0) {
            $hatBedarf = 1;
        }

        $query = "INSERT INTO `userinteressen` (UserID, InteressenID, BedarfHaben) VALUES ('$userID', '$intID', $hatBedarf);";

        $DBAnswer = $this -> db -> query($query);

        if (count($DBAnswer)>0) {
            return $this -> db -> insert_id();
        } else {
            return FALSE;
        }
    }

    public function deleteUserInteressen($userID = FALSE, $intID = FALSE) {

        if (!is_numeric($userID) || !is_numeric($intID)) {
            return FALSE;
        }

        $query = "DELETE FROM `userinteressen` WHERE UserID = $userID AND InteressenID=$intID;";

        $DBAnswer = $this -> db -> query($query);

        if ($tmp = $this -> db -> affected_rows() != 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function updateUserInteressen($userID = FALSE, $intID = FALSE, $hatBedarf = 0) {

        if (!is_numeric($userID) || !is_numeric($intID)) {
            return FALSE;
        }

        if ($hatBedarf != 0) {
            $hatBedarf = 1;
        }

        $query = "UPDATE `userinteressen` set BedarfHaben=$hatBedarf WHERE UserID = $userID AND InteressenID=$intID;";

        $DBAnswer = $this -> db -> query($query);

        if ($this -> db -> affected_rows() != 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function getUserKreisverband($userID = FALSE) {
        if (!is_numeric($userID)) {
            return FALSE;
        }

        //$query = "SELECT userkreisverband.KreisverbandID, MitgliedSeit, Austritt, Abkuerzung, Kreisjugendleiter, Kreisjugendleiter2, Ortsteil, Ort, Strasse, Hausnr, Plz FROM kreisverband
        //            INNER JOIN userkreisverband ON userkreisverband.KreisverbandID= kreisverband.KreisverbandID
        //            WHERE userkreisverband.UserID=$userID;";

        $query = "SELECT *
				from userkreisverband
				 WHERE userkreisverband.UserID=$userID;";

        $DBAnswer = $this -> db -> query($query);
        $DBAnswer = $DBAnswer -> result_array();

        if (count($DBAnswer) > 0) {
            return $DBAnswer;
        } else {
            return FALSE;
        }
    }

    public function IsInKreisverband($userID = FALSE, $kreisID = FALSE) {
        if (!is_numeric($userID) || !is_numeric($kreisID)) {
            return FALSE;
        }

        //$query = "SELECT userkreisverband.KreisverbandID, MitgliedSeit, Austritt, Abkuerzung, Kreisjugendleiter, Kreisjugendleiter2, Ortsteil, Ort, Strasse, Hausnr, Plz FROM kreisverband
        //            INNER JOIN userkreisverband ON userkreisverband.KreisverbandID= kreisverband.KreisverbandID
        //            WHERE userkreisverband.UserID=$userID;";

        $query = "SELECT UserID
                from userkreisverband
                 WHERE userkreisverband.UserID=$userID AND KreisverbandID=$kreisID;";

        $DBAnswer = $this -> db -> query($query);
        $DBAnswer = $DBAnswer -> result_array();

        if (count($DBAnswer) > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function getKreisverbandUser($kreisID = FALSE, $limitStart = 0, $limitEnd = 50) {
        if (!is_numeric($kreisID)) {

            return FALSE;
        }

        $query = "SELECT userkreisverband.UserID, user.Name, user.Vorname, user.Plz, userkreisverband.MitgliedSeit FROM userkreisverband
                    INNER JOIN user ON userkreisverband.UserID=user.UserID
                    WHERE Austritt IS NULL AND userkreisverband.KreisverbandID=$kreisID LIMIT $limitStart,$limitEnd;";

        $DBAnswer = $this -> db -> query($query);
        $DBAnswer = $DBAnswer -> result_array();

        if (count($DBAnswer) > 0) {
            foreach ($DBAnswer as $key => $value) {
                $DBAnswer[$key]['MitgliedSeit'] = transform_date($DBAnswer[$key]['MitgliedSeit']);
            }
            return $DBAnswer;
        } else {
            return FALSE;
        }
    }

    public function getAktUserKreisverband($userID = FALSE) {
        if (!is_numeric($userID)) {
            return FALSE;
        }

        $query = "SELECT kreisverband.Name from userkreisverband INNER JOIN kreisverband ON userkreisverband.KreisverbandID=kreisverband.KreisverbandID WHERE userkreisverband.UserID=$userID AND Austritt IS NULL;";

        $DBAnswer = $this -> db -> query($query);
        $DBAnswer = $DBAnswer -> result_array();

        if (count($DBAnswer) > 0) {
            return $DBAnswer[0]['Name'];
        } else {
            return FALSE;
        }
    }

    public function addUserKreisverband($userID = FALSE, $kreisID = FALSE, $MitgliedSeit = FALSE, $Austritt = FALSE) {
        if (!is_numeric($userID) || !is_numeric($kreisID) || $MitgliedSeit === FALSE) {
            return FALSE;
        }

        if ($Austritt === FALSE)
            $Austritt = 'NULL';
        else
            $Austritt = "'" . $Austritt . "'";
        $MitgliedSeit = "'" . $MitgliedSeit . "'";
        if ($Austritt === FALSE) {
            $query = "INSERT INTO `userkreisverband` (UserID, KreisverbandID, MitgliedSeit) VALUES ('$userID', '$kreisID', $MitgliedSeit);";
        } else {
            $query = "INSERT INTO `userkreisverband` (UserID, KreisverbandID, MitgliedSeit, Austritt) VALUES ('$userID', '$kreisID', $MitgliedSeit, $Austritt);";
        }
        $DBAnswer = $this -> db -> query($query);

        If (defined('DEBUG')) {
			 echo '<div id="debug">';
	        echo "<p>[addUserKreisverband] input:</p>";
	        echo "<p>SQL Query [addUserKreisverband]: " . $query .'</p>';
	        echo "<p>SQL Antwort [addKreisverband]: " . $this -> db -> insert_id() . '</p>';
	        echo '</div>';
        }

        if ($this -> db -> _error_message()) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function updateUserKreisverband($userID = FALSE, $kreisID = FALSE, $MitgliedSeit = FALSE, $Austritt = FALSE) {

        if (!is_numeric($userID) || !is_numeric($kreisID) || $MitgliedSeit === FALSE) {
            return FALSE;
        }
		
		if ($Austritt === FALSE)
            $Austritt = 'NULL';
        else
            $Austritt = "'" . $Austritt . "'";
        $MitgliedSeit = "'" . $MitgliedSeit . "'";
        
        $query = "UPDATE `userkreisverband` set Austritt=$Austritt, MitgliedSeit=$MitgliedSeit WHERE UserID = $userID AND KreisverbandID=$kreisID;";

        $DBAnswer = $this -> db -> query($query);

        If (defined('DEBUG')) {
			 echo '<div id="debug">';
	        echo "<p>[addUser] input:</p>";
	        echo "<p>SQL Query [updateUserKreisverband]: " . $query .'</p>';
	        echo "<p>SQL Antwort [updateUserKreisverband]: " . $this -> db -> insert_id() . '</p>';
	        echo '</div>';
        }

        if ($this -> db -> affected_rows() != 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
	
    public function deleteUserKreisverband($userID = FALSE, $kreisID = FALSE) {

        if (!is_numeric($userID) || !is_numeric($kreisID)) {
            return FALSE;
        }

        $query = "DELETE FROM `userkreisverband` WHERE UserID = $userID AND KreisverbandID=$kreisID;";

        $DBAnswer = $this -> db -> query($query);

        if ($tmp = $this -> db -> affected_rows() != 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function updateMitgleidSeitInKreisverband($userID = FALSE, $kreisID = FALSE, $MitgliedSeit = FALSE) {

        if (!is_numeric($userID) || !is_numeric($kreisID) || $MitgliedSeit == FALSE) {
            return FALSE;
        }

        $query = "UPDATE `userkreisverband` set MitgliedSeit=$MitgliedSeit WHERE UserID = $userID AND KreisverbandID=$kreisID;";

        $DBAnswer = $this -> db -> query($query);

        if ($this -> db -> affected_rows() != 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function updateAustrittKreisverband($userID = FALSE, $kreisID = FALSE, $austritt = 'today') {

        if (!is_numeric($userID) || !is_numeric($kreisID)) {
            return FALSE;
        }
        
        if($data = $this->IsInKreisverband($userID, $kreisID)){
            if($data['Kreisjugendleiter'] == $userID){
                $this->Kreis_model->setKreisLeiter($kreisID, 'NULL', FALSE); 
            }
            if($data['Kreisjugendleiter2'] == $userID){
                $this->Kreis_model->setKreisLeiter($kreisID, FALSE, 'NULL'); 
            }
        }

        if ($austritt == 'today') {
            $query = "UPDATE `userkreisverband` set Austritt=CURDATE() WHERE UserID = $userID AND KreisverbandID=$kreisID;";
        } else {
            $query = "UPDATE `userkreisverband` set Austritt=$austritt WHERE UserID = $userID AND KreisverbandID=$kreisID;";
        }
        
        $DBAnswer = $this -> db -> query($query);

        if ($this -> db -> affected_rows() != 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }


    public function getCountOfUserInKreis($kreisID = FALSE) {
        if (!is_numeric($kreisID)) {

            return FALSE;
        }

        $query = "SELECT COUNT(UserID) AS ANZAHL FROM userkreisverband WHERE KreisverbandID=$kreisID;";

        $DBAnswer = $this -> db -> query($query);
        $DBAnswer = $DBAnswer -> result_array();

        if (count($DBAnswer) > 0) {
            return $DBAnswer[0]['ANZAHL'];
        } else {
            return FALSE;
        }
    }

    public function getUserVeranstaltung($userID = FALSE) {
        if (!is_numeric($userID)) {
            return FALSE;
        }

        $query = "SELECT userveranstaltung.VeranstaltungID, Art, Name FROM veranstaltung
                    INNER JOIN userveranstaltung ON userveranstaltung.VeranstaltungID= veranstaltung.VeranstaltungID
                    WHERE userveranstaltung.UserID=$userID;";

        $DBAnswer = $this -> db -> query($query);
        $DBAnswer = $DBAnswer -> result_array();

        if (count($DBAnswer)>0) {
            return $DBAnswer;
        } else {
            return FALSE;
        }
    }

    public function getVeranstaltungUser($veraID = FALSE, $limitStar = 0, $limitEnd = 50) {
        if (!is_numeric($veraID)) {

            return FALSE;
        }

        $query = "SELECT userveranstaltung.UserID, user.Name, user.Vorname, user.Plz, userveranstaltung.Funktion, userveranstaltung.Bezahlt FROM veranstaltung
                    INNER JOIN userveranstaltung ON userveranstaltung.VeranstaltungID= veranstaltung.VeranstaltungID
                    INNER JOIN user ON userveranstaltung.UserID= user.userID
                    WHERE userveranstaltung.VeranstaltungID=$veraID LIMIT $limitStar,$limitEnd;";

        $DBAnswer = $this -> db -> query($query);
        $DBAnswer = $DBAnswer -> result_array();

        If (defined('DEBUG')) {
			 echo '<div id="debug">';
	        echo "<p>[getVeranstaltungUser] input:</p>";
			 echo "<pre>";
			 print_r($DBAnswer);
	        echo "</pre>"; 
	        echo "<p>SQL Query [getVeranstaltungUser Query]: " . $query .'</p>';
	        echo '</div>';
        }
        
        if (count($DBAnswer)>0) {
            return $DBAnswer;
        } else {
            return FALSE;
        }
    }
    
    public function IsInVeranstaltung($userID = FALSE, $veraID = FALSE) {
        if (!is_numeric($userID) || !is_numeric($veraID)) {
            return FALSE;
        }

        $query = "SELECT UserID
                from userveranstaltung
                 WHERE userveranstaltung.UserID=$userID AND VeranstaltungID=$veraID;";

        $DBAnswer = $this -> db -> query($query);
        $DBAnswer = $DBAnswer -> result_array();

        if (count($DBAnswer) > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function updateUVFkt($userID = FALSE, $veraID = FALSE, $funktion = FALSE) {

        if (!is_numeric($userID) || !is_numeric($veraID) || $funktion === FALSE) {
            return FALSE;
        }
        
        $query = "UPDATE `userveranstaltung` SET Funktion='$funktion' WHERE UserID=$userID AND VeranstaltungID=$veraID;";

        $DBAnswer = $this -> db -> query($query);

        if ($tmp = $this -> db -> affected_rows() != 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function updateUVFPayed($userID = FALSE, $veraID = FALSE, $payed = FALSE) {

        if (!is_numeric($userID) || !is_numeric($veraID) || !is_numeric($payed)) {
            return FALSE;
        }
        
        $query = "UPDATE `userveranstaltung` SET Bezahlt=$payed WHERE UserID=$userID AND VeranstaltungID=$veraID;";

        $DBAnswer = $this -> db -> query($query);

        if ($tmp = $this -> db -> affected_rows() != 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    public function addUserVeranstaltung($userID = FALSE, $veraID = FALSE, $teilgenommen = 0, $bezahlt = 0, $funktion = 'Teilnehmer') {

        if (!is_numeric($userID) || !is_numeric($veraID)) {
            return FALSE;
        }

        if ($teilgenommen != 0) {
            $teilgenommen = 1;
        }

        if ($bezahlt != 0) {
            $bezahlt = 1;
        }

        $query = "INSERT INTO `userveranstaltung` (UserID, VeranstaltungID, Teilgenommen, Bezahlt, Funktion) VALUES ('$userID', '$veraID', '$teilgenommen', '$bezahlt', '$funktion');";

        $DBAnswer = $this -> db -> query($query);

        if (count($DBAnswer)>0) {
            return $this -> db -> insert_id();
        } else {
            return FALSE;
        }
    }

    public function deleteUserVeranstaltung($userID = FALSE, $veraID = FALSE) {

        if (!is_numeric($userID) || !is_numeric($veraID)) {
            return FALSE;
        }

        $query = "DELETE FROM `userveranstaltung` WHERE UserID=$userID AND VeranstaltungID=$veraID;";

        $DBAnswer = $this -> db -> query($query);

        if ($tmp = $this -> db -> affected_rows() != 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function getCountofUserInVera($veraID = FALSE) {
        if (!is_numeric($veraID)) {

            return FALSE;
        }

        $query = "SELECT COUNT(UserID) AS ANZAHL FROM userveranstaltung WHERE VeranstaltungID=$veraID;";

        $DBAnswer = $this -> db -> query($query);
        $DBAnswer = $DBAnswer -> result_array();

        if (count($DBAnswer) > 0) {
            return $DBAnswer[0]['ANZAHL'];
        } else {
            return FALSE;
        }
    }

    public function getUserIDformRegisteredUsersInYear($year = FALSE, $limitStart = 0, $limitStop = 100) {
        if ($year == FALSE) {
            return FALSE;
        }

        $query = "SELECT UserID, YEAR(InsertTimeStamp) AS insertJahr, YEAR(LetzteAenderung) as aenderungJahr FROM `user` WHERE YEAR(InsertTimeStamp)<=$year AND YEAR(LetzteAenderung)>=$year LIMIT $limitStart,$limitStop;";

        $DBAnswer = $this -> db -> query($query);
        $DBAnswer = $DBAnswer -> result_array();

        If (defined('DEBUG')) {
            echo '<div id="debug">';
            echo "<p>[getUserIDformRegisteredUsersInYear] input: \$year = $year</p>";
            echo "<p>SQL Query [getUserIDformRegisteredUsersInYear]: " . $query . '</p>';
            echo "<p>SQL Antwort  [getUserIDformRegisteredUsersInYear]: " . $DBAnswer . '</p>';
            echo '</div>';
        }

        if (count($DBAnswer)>0) {
            return $DBAnswer;
        } else {
            return FALSE;
        }
    }

    function getValidationRules() {
    }

}
?>
