<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * Dieser Controller enthällt den funktionen welche zur Datenausgabe benötigt werden.
 * Die Datenausgabe erfolgt im Json format
*/

class Data extends CI_Controller {

	public function __construct()
    {
    	parent::__construct();
        // Your own constructor code
        
        //Models laden
        $this -> load -> model('data_model');
    }
	// hallo world
	public function index()
	{
		echo 'Hello World!';
	}
	
	public function getvalue($meterID)
	{
		echo $this -> Data_model -> getLastValue($meterID);
	
	}
	
	
}
?>