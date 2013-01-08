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
                $this -> load -> model('user_model');
                $this -> load -> model('Vera_model');
                $this -> load -> model('Kreis_model');
                $this -> load -> model('Quali_model');
                $this -> load -> model('Pos_model');
                $this -> load -> model('Com_model');
                $this -> load -> model('Email_model');
                $this -> load -> model('Tel_model');
                $site = 'test_db';
                break;
            case 'site_neuesMitglied' :
                //*********Neues Mitglied - Eingabeformular***********
                $site = "form/user";
                $this -> load -> model('User_model');
                $this -> load -> model('Quali_model');
                $this -> load -> model('Tel_model');
                $this -> load -> model('Email_model');
                $this -> load -> model('Pos_model');
                $this -> load -> model('Kreis_model');
                $this -> load -> model('Com_model');
                $contentData['MitgliedID'] = $para1;
                $this -> layout_data['jsfile'] = "myjavafunc.js";

                $this -> form_validation -> set_message('required', 'Das Feld %s ist erforderlich.');
                $this -> form_validation -> set_rules($this -> User_model -> getValidationRules());
                break;
            case 'site_neueVeranstaltungen' :
                //******Neues Veranstaltung - Eingabeformular*********
                $site = "form/veranstaltung";
                $contentData['VeranstaltungID'] = $para1;
                $this -> load -> model('Vera_model');
                $this -> load -> model('Quali_model');
                $this -> layout_data['jsfile'] = "myjavafunc.js";
                $this -> form_validation -> set_message('required', 'Das Feld %s ist erforderlich.');
                $this -> form_validation -> set_rules($this -> Vera_model -> getValidationRules());
                break;
            case 'listMitglieder' :
                //**************Mitglieder****************************
                $site = "form/utabelle";
                $contentData['kreisID'] = FALSE;
                $contentData['delID'] = FALSE;
                $contentData['kreisID'] = FALSE;
                $contentData['veraID'] = FALSE;
                if ($para1) {
                    if (is_numeric($para1)) {
                        $contentData['pos'] = $para1;
                        $this -> load -> model('User_model');
                        if ($this -> isAdmin($this -> session -> userdata('user_id'))) {
                            $contentData['delID'] = $para2;
                        } else {
                            $site = 'overview';
                            $this -> load -> model('User_model');
                            $this -> load -> model('Vera_model');
                            $this -> load -> model('Kreis_model');
                            $this -> load -> model('Quali_model');
                            $this -> load -> model('Pos_model');
                        }
                    } else {
                        switch ($para1) {
                            case 'kreisverbandAdd' :
                                $contentData['pos'] = 1;
                                $contentData['kreisID'] = $para2;
                                break;
                            case 'veraAdd' :
                                $contentData['pos'] = 1;
                                $contentData['veraID'] = $para2;
                                break;
                            default :
                                $site = 'overview';
                                $this -> load -> model('User_model');
                                $this -> load -> model('Vera_model');
                                $this -> load -> model('Kreis_model');
                                $this -> load -> model('Quali_model');
                                $this -> load -> model('Pos_model');
                                break;
                        }
                    }
                }
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
            case 'qualiList' :
                $this -> load -> model('Quali_model');
                $site = "form/quali_list";
                $contentData['pos'] = $para1;
                $contentData['delID'] = FALSE;
                if ($para2 && $isAdmin) {
                    $contentData['delID'] = $para2;
                }
                break;
            case 'chart' :
                
                //$this -> load -> model('User_model');
                $site = "form/chart";
                $contentData['meterID'] = FALSE;
                $contentData['date'] = FALSE;
                if ($para1) {
                    if (is_numeric($para1)) {
                        $contentData['meterID'] = $para1;
                        if ($para2) {
                            $contentData['date'] = $para2;
                        }
                    } else {

                    }
                } else {
                    $contentData['meterID'] = FALSE;
                }
                break;
			case 'chart2' :
                
                //$this -> load -> model('User_model');
                $site = "form/chart2";
                $contentData['meterID'] = FALSE;
                $contentData['date'] = FALSE;
                if ($para1) {
                    if (is_numeric($para1)) {
                        $contentData['meterID'] = $para1;
                        if ($para2) {
                            $contentData['date'] = $para2;
                        }
                    } else {

                    }
                } else {
                    $contentData['meterID'] = FALSE;
                }
                break;
			case 'meterChart' :
                
                //$this -> load -> model('User_model');
                $site = "form/meterChart";
                $contentData['meterID'] = FALSE;
                $contentData['date'] = FALSE;
                if ($para1) {
                    if (is_numeric($para1)) {
                        $contentData['meterID'] = $para1;
                        if ($para2) {
                            $contentData['date'] = $para2;
                        }
                    } else {

                    }
                } else {
                    $contentData['meterID'] = FALSE;
                }
                break;
			case 'lineChart' :
                
                //$this -> load -> model('User_model');
                $site = "form/lineChart";
                $contentData['meterID'] = FALSE;
                $contentData['date'] = FALSE;
                if ($para1) {
                    if (is_numeric($para1)) {
                        $contentData['meterID'] = $para1;
                        if ($para2) {
                            $contentData['date'] = $para2;
                        }
                    } else {

                    }
                } else {
                    $contentData['meterID'] = FALSE;
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
            case 'posAdd' :
                if ($isAdmin) {
                    $this -> load -> library('form_validation');
                    $this -> load -> model('Pos_model');
                    $site = 'form/pos_add';
                    $contentData['posID'] = $para1;
                    $this -> form_validation -> set_message('required', 'Das Feld %s ist erforderlich.');
                    $this -> form_validation -> set_rules($this -> Pos_model -> getValidationRules());
                } else {
                    $site = 'overview';
                    $this -> load -> model('User_model');
                    $this -> load -> model('Vera_model');
                    $this -> load -> model('Kreis_model');
                    $this -> load -> model('Quali_model');
                    $this -> load -> model('Pos_model');
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

            case 'alteUserLoeschen' :
                if ($isAdmin) {
                    $this -> load -> model('User_model');
                    $site = "form/alteMitglieder_list";
                    $contentData['pos'] = $para1;
                    $contentData['delID'] = FALSE;
                    if ($para2 && $isAdmin) {
                        $contentData['delID'] = $para2;
                    }
                } else {
                    $site = "overview";
                    $this -> load -> model('User_model');
                    $this -> load -> model('Vera_model');
                    $this -> load -> model('Kreis_model');
                    $this -> load -> model('Quali_model');
                    $this -> load -> model('Pos_model');
                }
                break;
            case 'kreisAdd' :
                if ($this -> session -> userdata('isAdmin')) {//$isAdmin) {
                    $this -> load -> library('form_validation');
                    $this -> load -> model('Kreis_model');
                    $this -> load -> model('User_model');
                    $contentData['KreisID'] = $para1;
                    $site = 'form/kreis_add';
                    $this -> form_validation -> set_message('required', 'Das Feld %s ist erforderlich.');
                    $this -> form_validation -> set_rules($this -> Kreis_model -> getValidationRules());
                } else {
                    $site = 'overview';
                    $this -> load -> model('User_model');
                    $this -> load -> model('Vera_model');
                    $this -> load -> model('Kreis_model');
                    $this -> load -> model('Quali_model');
                    $this -> load -> model('Pos_model');
                }
                break;
            default :
                //************Begruessung Site**********************
                $site = "overview";
                

                break;
            case 'listUserOfVera' :
                $site = 'form/userOfVera';
                $this -> load -> model('Vera_model');
                $this -> load -> model('Com_model');
                $this -> load -> model('User_model');
                if (is_numeric($para1)) {
                    $contentData['veraID'] = $para1;
                    $contentData['delID'] = FALSE;
                    $contentData['addID'] = FALSE;
                    $contentData['editID'] = FALSE;
                    $contentData['payID'] = FALSE;
                    $contentData['revertID'] = FALSE;
                    if (is_numeric($para2)) {
                        if ($para2 && $isAdmin) {
                            $contentData['delID'] = $para2;
                            $this -> load -> model('User_model');
                        }
                    } else {
                        switch ($para2) {
                            default :
                            case 'add' :
                                if (is_numeric($para3) && $isAdmin) {
                                    $contentData['addID'] = $para3;
                                }
                            case 'editFkt' :
                                if (is_numeric($para3) && $isAdmin) {
                                    $contentData['editID'] = $para3;
                                }
                                break;
                            case 'payed' :
                                if (is_numeric($para3) && $isAdmin) {
                                    $contentData['payID'] = $para3;
                                }
                                break;
                            case 'revertMoney' :
                                if (is_numeric($para3) && $isAdmin) {
                                    $contentData['revertID'] = $para3;
                                }
                                break;
                        }
                    }
                } else {
                    $site = 'overview';
                    $this -> load -> model('User_model');
                    $this -> load -> model('Vera_model');
                    $this -> load -> model('Kreis_model');
                    $this -> load -> model('Quali_model');
                    $this -> load -> model('Pos_model');
                }
                break;

            case 'excelDownload' :
                $contentData['file'] = $para1;
                $contentData['download'] = $para2;
                $contentData['delID'] = $para3;
                $site = 'excelDownload';
                break;

            case 'listUserOfKreis' :
                $site = 'form/userOfKreis';
                $this -> load -> model('Kreis_model');
                $this -> load -> model('Com_model');
                $this -> load -> model('User_model');
                $contentData['delID'] = FALSE;
                $contentData['addID'] = FALSE;
                $contentData['grantFirstAddID'] = FALSE;
                $contentData['grantSecondtAddID'] = FALSE;
                $contentData['revertFirstAddID'] = FALSE;
                $contentData['revertSecondtAddID'] = FALSE;

                if ($para1 && is_numeric($para1)) {
                    $contentData['kreisID'] = $para1;
                    if (is_numeric($para2)) {
                        if ($isAdmin) {
                            $contentData['delID'] = $para2;
                            $this -> load -> model('User_model');
                        }
                    } else {
                        switch ($para2) {
                            default :
                            case 'add' :
                                if (is_numeric($para3) && $isAdmin) {
                                    $contentData['addID'] = $para3;
                                }
                                break;
                            case 'grantFirst' :
                                if (is_numeric($para3) && $isAdmin) {
                                    $contentData['grantFirstAddID'] = $para3;
                                }
                                break;
                            case 'grantSecond' :
                                if (is_numeric($para3) && $isAdmin) {
                                    $contentData['grantSecondtAddID'] = $para3;
                                }
                                break;
                            case 'revertFirst' :
                                if (is_numeric($para3) && $isAdmin) {
                                    $contentData['revertFirstAddID'] = $para3;
                                }
                                break;
                            case 'revertSecond' :
                                if (is_numeric($para3) && $isAdmin) {
                                    $contentData['revertSecondtAddID'] = $para3;
                                }
                                break;
                        }
                    }
                } else {
                    $site = 'overview';
                    $this -> load -> model('User_model');
                    $this -> load -> model('Vera_model');
                    $this -> load -> model('Kreis_model');
                    $this -> load -> model('Quali_model');
                    $this -> load -> model('Pos_model');
                }
                break;
        }

        $contentData['nextWebseite'] = "$nextWebsite";

        if ($this -> form_validation -> run() == FALSE) {

            $this -> layout_data['content'] = $this -> load -> view($site, $contentData, true);
        } else {
            //if ($para1 == "newData" && $this->newData($lastWebsite)){
            $contentData['isOK'] = true;
            $this -> layout_data['content'] = $this -> load -> view($site, $contentData, true);

        }

        //Model setzen
        $model = $this -> getModel($nextWebsite);
        $this -> load -> model($model, 'model');

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
	
    private function getModel($site) {
        switch ($site) {
            case 'site_neuesMitglied' :
                return "User_model";

            case 'site_neueVeranstaltungen' :
                return "Vera_model";

            case 'site_Kreisverband' :
                return "Kreis_model";

            case 'site_Mitglieder' :
                return "User_model";

            case 'site_Veranstaltungen' :
                return "Vera_model";

            case 'site_position' :
                return "Pos_model";
        }

        return "";
    }

    private function newData($lastWebsite) {
        $this -> load -> model($this -> getModel($lastWebsite), 'model');
        ;
        $this -> validate();
    }

    private function validate() {
        //Validierungsregeln setzen
        $this -> form_validation -> set_rules($this -> model -> getValidationRules());

        return $this -> form_validation -> run();
    }

    function index() {
        $this -> changeWebsite("overview");
    }

    private function formularKreisverband_ValidationRules() {

    }

    function phpskript($skript) {
        $this -> updateSession();
        $this -> load -> model('Vera_model');
        $this -> load -> model('User_model');
        $this -> load -> model('Com_model');
        $this -> load -> model('Quali_model');
        $this -> load -> model('Pos_model');
        $this -> load -> model('Kreis_model');
        $this -> load -> view('phpskript/' . $skript);
    }

}


function get_cell($cell, $objPHPExcel) {
    $objCell = ($objPHPExcel -> getActiveSheet() -> getCell($cell));
    return $objCell -> getvalue();
}

function getNextIndexOfExcelFile($CompliteFileName) {
    $index = 1;
    while (file_exists($CompliteFileName . "" . $index . ".xls"))
        $index++;
    return $index;
}

function base_url($uri = '') {
    $CI = &get_instance();
    return $CI -> config -> base_url($uri);
    //vlt. ne alternative die schneller ist!
    //return $this->ci->config->base_url($uri);
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
