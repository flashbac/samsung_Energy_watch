<?php
class user_model extends CI_Model {

    public function __construct() {
        $this -> load -> database();
    }

    /*
     * function check_user takes the Username and transforme it to lowercase + take the hased and salted PW
     * returns if exists the userID
     */
    public function check_user($userID = FALSE, $userPW = FALSE) {

        if ($userID == FALSE || $userPW == FALSE) {
            return FALSE;
        }

        if (!is_numeric($userID)) {
            $userID = $this -> getIDfromUsername($userID);
        }

        if ($userID == 0) {
            return FALSE;
        }

        //first step, get the Salt from DB
        $query = 'SELECT Salt from `user` WHERE ID=' . $userID . ';';

        $DBAnswer = $this -> db -> query($query);

        if ($tmp = $this -> db -> affected_rows() != 1) {
            return FALSE;
        }// wenn es weniger oder mehr als ein result kommt, abbrechen

        $DBAnswer = $DBAnswer -> result_array();

        $salt = $DBAnswer[0]['Salt'];

        $hasedPW = sha1($userPW . $salt);

        $query = "SELECT ID FROM `user` WHERE ID=$userID AND Password='$hasedPW'";

        $DBAnswer = $this -> db -> query($query);

        if ($tmp = $this -> db -> affected_rows() != 1) {
            return FALSE;
        }// wenn es weniger oder mehr als ein result kommt, abbrechen

        $DBAnswer = $DBAnswer -> result_array();

        return $DBAnswer[0]['ID'];
    }

    public function getIDfromUsername($userName = FALSE) {
        if ($userName == FALSE) {
            return FALSE;
        }

        $userName = strtolower($userName);

        $query = "SELECT ID FROM `user` WHERE UserName='$userName'";

        $DBAnswer = $this -> db -> query($query);

        if ($tmp = $this -> db -> affected_rows() != 1) {
            return FALSE;
        }// wenn es weniger oder mehr als ein result kommt, abbrechen

        $DBAnswer = $DBAnswer -> result_array();

        return $DBAnswer[0]['ID'];
    }

    public function isAdmin($userID = FALSE) {
        if ($userID == FALSE) {
            return FALSE;
        }

        if (!is_numeric($userID)) {
            $userID = $this -> getIDfromUsername($userID);
        }

        if ($userID == 0) {
            return FALSE;
        }

        $query = "SELECT ID FROM `user` WHERE ID=$userID AND Admin=1";

        $DBAnswer = $this -> db -> query($query);

        if ($tmp = $this -> db -> affected_rows() != 1) {
            return FALSE;
        }// wenn es weniger oder mehr als ein result kommt, abbrechen

        $DBAnswer = $DBAnswer -> result_array();

        if ($DBAnswer[0]['ID'] == $userID) {
            //return TRUE;
            return $DBAnswer[0]['ID'];
        } else {
            return FALSE;
        }
    }

    function generateSalt($max = 40) {
        $characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*?";
        $i = 0;
        $salt = "";
        while ($i < $max) {
            $salt .= $characterList{mt_rand(0, (strlen($characterList) - 1))};
            $i++;
        }
        return $salt;
    }

    public function addUser($userName = FALSE, $userPW = FALSE, $isAdmin = FALSE) {
        if (!$userName || !$userPW) {
            return FALSE;
        }

        if ($isAdmin) {
            $isAdmin = 1;
        } else {
            $isAdmin = 0;
        }
        $salt = $this -> generateSalt();
        $hasedPW = sha1($userPW . $salt);
        $query = "INSERT INTO `user` (Name, Password, Salt, Admin) VALUES ('$userName', '$hasedPW', '$salt', $isAdmin);";

        $DBAnswer = $this -> db -> query($query);

        if (count($DBAnswer)>0) {
            return $this -> db -> insert_id();
        } else {
            return FALSE;
        }
    }

    public function deleteUser($userID = FALSE) {
        if ($userID == FALSE) {
            return FALSE;
        }

        if (!is_numeric($userID)) {
            $userID = $this -> getIDfromUsername($userID);
        }

        if ($userID == 0) {
            return FALSE;
        }

        $query = "DELETE FROM `user` WHERE ID = $userID";

        $DBAnswer = $this -> db -> query($query);

        if ($this -> db -> affected_rows() != 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function changePW($userID = FALSE, $oldPW, $newPW) {
        if ($userID == FALSE) {
            return FALSE;
        }

        if (!is_numeric($userID)) {
            $userID = $this -> getIDfromUsername($userID);
        }

        if (!isset($oldPW) || !isset($newPW)) {
            return FALSE;
        }

        if (!$this -> check_user($userID, $oldPW)) {
            return FALSE;
        }

        $salt = $this -> generateSalt();
        $hasedPW = sha1($newPW . $salt);

        $query = "UPDATE `user`SET Password='$hasedPW', Salt='$salt' WHERE ID=$userID";

        $DBAnswer = $this -> db -> query($query);

        if ($this -> db -> affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function getCountOfusers() {

        $query = "SELECT COUNT(ID) AS ANZAHL FROM `user`;";

        $DBAnswer = $this -> db -> query($query);

        $DBAnswer = $DBAnswer -> result_array();

        if (count($DBAnswer)>0) {
            return $DBAnswer[0]['ANZAHL'];
        } else {
            return FALSE;
        }
        return $data;
    }

    public function getAlluser($limitStart = 0, $limitStop = 100) {
        if (!is_numeric($limitStart) || !is_numeric($limitStop)) {
            return FALSE;
        }

        $query = "SELECT ID, UserName, Admin FROM `user` LIMIT $limitStart,$limitStop;";

        $DBAnswer = $this -> db -> query($query);

        $DBAnswer = $DBAnswer -> result_array();

        if (count($DBAnswer)>0) {
            return $DBAnswer;
        } else {
            return FALSE;
        }
        return $data;
    }

    function getValidationRules() {
        $config = array( array('field' => 'Benutzername', 'label' => 'Benutzername', 'rules' => 'required|trim|min_length[5]|max_length[12]|xss_clean'), array('field' => 'Password', 'label' => 'Password', 'rules' => 'required|min_length[5]|max_length[20]|matches[Passwordw]'), array('field' => 'Passwordw', 'label' => 'Passwordw', 'rules' => 'required|min_length[5]|max_length[20]'));
        return $config;
    }

    function getValidationRulesCHANGEPW() {
        $config = array( array('field' => 'altPW', 'label' => 'altes Password', 'rules' => 'required|trim|min_length[5]|max_length[12]|xss_clean'), array('field' => 'newPW', 'label' => 'Password', 'rules' => 'required|min_length[5]|max_length[20]|matches[newPWw]'), array('field' => 'newPWw', 'label' => 'Passwordw', 'rules' => 'required|min_length[5]|max_length[20]'));
        return $config;
    }

}
?>
