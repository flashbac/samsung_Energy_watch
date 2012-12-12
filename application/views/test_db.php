<?php

//$data = $this->User_model->deleteUser(2);
echo "<h1>testDB</h1>";
//$this->User_model->addUser(array('Vorname' => 'dennis','Name' => 'asd'));
//$data = $this->User_model->isUserAvailable(array('Vorname' => 'dennis','Name' => 'a'));
//$data = $this->User_model->get_user(2);

//$data  = $this->Quali_model->getQualifikation('new',array('Abkuerzung' => 'Gstars','Ortsteil' => 'teil', 'Kreisjugendleiter' => '1', 'Kreisjugendleiter2' => '9', 'Ort' => 'Berlin', 'Strasse' => 'Strr.', 'Hausnr' => '22', 'Plz' => '12345'));
//$this->Com_model->updateAustrittKreisverband(2,2);
//$data = $this->Com_model->getUserIDformRegisteredUsersInYear(2012);

//$data = $this->Dbuser_model->addUser('test', MD5('test' . $this -> config -> item('encryption_key')), true);
//$data = $this->Com_model->getUserPosition(2);
//$data = $this->Vera_model->getVeranstaltungen();


   $this->User_model->deleteUser(6);

if (isset($data)) {

    echo '<pre>';
    print_r($data);
    echo '</pre>';
}
?>