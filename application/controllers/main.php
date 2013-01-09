<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @Author: Lars Willrich
 */
class main extends CI_Controller {

    private $layout_data;

    function __construct() {
        parent::__construct();

        $this -> load -> library('session');
        $this -> load -> helper('date');
        $this -> load -> library('encrypt');
        $this -> load -> helper('form');
        $this -> load -> helper('url');
        $this -> load -> helper('MY_helper');
        $this -> load -> library('form_validation');

        //tamplate laden
        $this -> layout_data['case'] = "OK";
        $this -> layout_data['pageTitle'] = "JRK - Mitgliederverwaldung";
        $this -> layout_data['header'] = $this -> load -> view('header', $this -> layout_data, true);
        $this -> layout_data['navigation'] = $this -> load -> view('navigation', NULL, true);

        //Models laden
        $this -> load -> model('user_model');
        //Pr�fung des Logins und Session
        $this -> is_logged_in();
        $this -> updateSession();
    }

    function is_logged_in() {
        $is_logged_in = $this -> session -> userdata('is_logged_in');

        if (!isset($is_logged_in) || $is_logged_in != true) {
            redirect('login/index/NOT_LOGIN');
        } else {
            if (!($this -> isSessionValid()))
                redirect('login/index/SESSION_UNAVAILABLE');
        }
        return true;
    }

    function isAdmin($userID = FALSE) {
        if (!is_numeric($userID)) {
            return FALSE;
        }
        $id = $this -> user_model -> isAdmin($this -> session -> userdata('user_id'));
        if (is_numeric($id) && $id > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function isSessionValid() {
        //@Test 60 sekunden, sp�ter 60*60 Sekunden
        if ((now() - $this -> session -> userdata('last_activity')) < 60 * 10)
            return true;
        return false;
    }

    private function updateSession() {
        $this -> session -> set_userdata('last_activity', now());
    }

    function logout() {
        redirect('login/index/LOGOUT');
    }

    /*Funktion, welche zur n�chsten Seite wechselt
     *
     * Parameter:
     * nextWebsite: N�chste Website
     * params: Parameter, die zur�ckkommen
     */
    function changeWebsite($nextWebsite = "", $para1 = FALSE, $para2 = FALSE, $para3 = FALSE) {

        $lastWebsite = $this -> session -> userdata('last_Website');
        $isAdmin = $this -> session -> userdata('isAdmin');

        if ($lastWebsite == "")
            $lastWebsite = "overwiev";
        if ($nextWebsite == "this" || $nextWebsite == "") {
            $nextWebsite = $lastWebsite;
        } else {
            $this -> session -> set_userdata('last_Website', $nextWebsite);
        }

        //Helper und Libraries laden

        $this -> layout_data['cssfile'] = $nextWebsite . ".css";
        $this -> layout_data['case'] = "OK";

        $this -> load -> library('table');

        $site = "";
        switch ($nextWebsite) {
            case 'help' :
                $site = 'form/help';
                break;
            case 'testdb' :
                $this -> load -> model('User_model');              
                $site = 'test_db';
                break;

            case 'addZaehler' :
                //**************Veranstaltungen**********************
                $site = "form/list_meter";
                $this -> load -> model('Meter_model');
                $contentData['pos'] = FALSE;
                $contentData['delID'] = FALSE;
                if ($para1) {
                    if (is_numeric($para1)) {
                        $contentData['pos'] = $para1;
                    } else {
                        if ($para2) {
                            switch ($para1) {
                                case 'delete' :
                                    if(is_numeric($para2)){
                                        $contentData['delID'] = $para2;
                                    }
                                    break;
                                default :
                                    break;
                            }
                        }
                    }
                }

                break;
            case 'changePW' :
                $site = "form/admin_change_pw";
                $this -> load -> model('user_model');
                $this -> form_validation -> set_message('required', 'Das Feld %s ist erforderlich.');
                $this -> form_validation -> set_rules($this -> user_model -> getValidationRulesCHANGEPW());
                breaK;
            /*case 'webinosConfig.json' :
               $attachment_location = $_SERVER["CONTEXT_DOCUMENT_ROOT"] . "js/webinos_config.json";
                if (file_exists($attachment_location)) {

                header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
                header("Cache-Control: public"); // needed for i.e.
                header("Content-Type: application/zip");
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length:".filesize($attachment_location));
                header("Content-Disposition: attachment; filename=webinosConfig.json");
                readfile($attachment_location);
                die();        
            } else {
                die("Error: File not found.");
            } 
                breaK;*/
            case 'deleteuser' :
                if ($isAdmin) {
                    if (!empty($para1)) {
                        $contentData['pos'] = $para1;
                    }
                    if (!empty($para2)) {
                        $contentData['delNAME'] = $para2;
                    }
                    $site = "form/admin_list";
                    $this -> layout_data['cssfile'] = "adminList.css";
                } else {
                    $site = 'overview';
                    $this -> load -> model('User_model');
                    $this -> load -> model('Vera_model');
                    $this -> load -> model('Kreis_model');
                    $this -> load -> model('Quali_model');
                    $this -> load -> model('Pos_model');
                }

                break;
            case 'adminList' :
                if ($isAdmin) {
                    $site = "form/admin_list";
                    $contentData['pos'] = $para1;

                } else {
                    $site = 'overview';
                    $this -> load -> model('User_model');
                    $this -> load -> model('Vera_model');
                    $this -> load -> model('Kreis_model');
                    $this -> load -> model('Quali_model');
                    $this -> load -> model('Pos_model');
                }
                break;
            case 'listmeters' :
                $this -> load -> model('Meter_model');
                $site = "form/list_meter";
                $contentData['pos'] = $para1;
                $contentData['delID'] = FALSE;
                if ($para2 && $isAdmin) {
                    $contentData['delID'] = $para2;
                }
                break;
            case 'administration' :
                if ($isAdmin) {
                    $this -> load -> library('form_validation');
                    $this -> load -> model('user_model');
                    $site = 'administration';
                    $this -> form_validation -> set_rules($this -> user_model -> getValidationRules());
                    if ($this -> form_validation -> run()) {
                        $this -> layout_data['isOK'] = TRUE;
                    }
                }
                break;
            case 'addMeter' :                
                    $this -> load -> library('form_validation');
                    $this -> load -> model('Meter_model');
                    $site = 'form/add_meter';
                    $contentData['meterID'] = $para1;
                    $this -> form_validation -> set_message('required', 'Das Feld %s ist erforderlich.');
                    $this -> form_validation -> set_rules($this -> Meter_model -> getValidationRules());               
                break;
				
            case 'chart' :
                $site = "form/chart";
                break;
			case 'chart2' :                
                $site = "form/chart2";
                break;
			case 'meterChart' :
                $site = "form/meterChart";
				$this -> layout_data['jsfile'] = "charthelper.js";
                break;
			case 'lineChart' :
                $site = "form/lineChart";
				$this -> layout_data['jsfile'] = "charthelper.js";		
                break;					
			case 'visualisierung' :
                $site = "form/visualisierung";
				$this -> layout_data['cssfile'] = "jquery-ui.css";
				$this -> layout_data['jsfile'] = "charthelper.js";
                break;	
				

				
			default :
                //************Begruessung Site**********************
                $site = "overview";
                
                break;
        }

        $contentData['nextWebseite'] = "$nextWebsite";

        if ($this -> form_validation -> run() == FALSE) {

            $this -> layout_data['content'] = $this -> load -> view($site, $contentData, true);
        } else {
            $contentData['isOK'] = true;
            $this -> layout_data['content'] = $this -> load -> view($site, $contentData, true);

        }

        //Aufruf der Seite
        $this -> load -> view('main', $this -> layout_data);
    }
	
	
	function hilfe()
	{
        $isAdmin = $this -> session -> userdata('isAdmin');

        //Helper und Libraries laden
        $this -> layout_data['cssfile'] = "help.css";
		$contentData['isOK'] = true;
		$this -> layout_data['content'] = $this -> load -> view("hilfe",$contentData, true);

        //Aufruf der Seite
        $this -> load -> view('main', $this -> layout_data);
	
	}
	
    private function validate() {
        //Validierungsregeln setzen
        $this -> form_validation -> set_rules($this -> model -> getValidationRules());

        return $this -> form_validation -> run();
    }

    function index() {
        $this -> changeWebsite("overview");
    }
}

function base_url($uri = '') {
    $CI = &get_instance();
    return $CI -> config -> base_url($uri);
    //vlt. ne alternative die schneller ist!
    //return $this->ci->config->base_url($uri);
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
