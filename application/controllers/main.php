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
            case 'addMeter' :
                //**************Veranstaltungen**********************
                $site = "form/list_meter";
                $this -> load -> model('Metermodel');
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
                if ($isAdmin) {
                    $this -> load -> library('form_validation');
                    $this -> load -> model('Meter_model');
                    $site = 'form/add_meter';
                    $contentData['meterID'] = $para1;
                    $this -> form_validation -> set_message('required', 'Das Feld %s ist erforderlich.');
                    $this -> form_validation -> set_rules($this -> Meter_model -> getValidationRules());
                } else {
                    $site = 'overview';
                }
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

    public function generateExcel($verid) {

        $this -> load -> helper('download');
        error_reporting(E_ALL);
        // Include path

        ini_set('include_path', ini_get('include_path') . ';../Classes/');
        $this -> load -> library('PHPExcel');
        include_once (APPPATH . '/libraries/PHPExcel/Writer/Excel5.php');

        $teilnehmer = array( array("NAME" => "Lars", "NATION" => "Deutsch", "ALTER" => "22", "FUNKTION" => "Chef", "TAGE" => "45", "UEBERNACHTUNG" => "JA"), array("NAME" => "Sven", "NATION" => "Deutsch", "ALTER" => "52", "FUNKTION" => "Chef2", "TAGE" => "111", "UEBERNACHTUNG" => "NEIN"), );

        $this -> load -> model('vera_model', 'vera');
        $this -> load -> model('quali_model', 'quali');
        $this -> load -> model('com_model', 'com');
        $this -> load -> model('user_model', 'user');
        $ver = $this -> vera -> getVeranstaltung($verid);

        $quali = $this -> quali -> getQualifikation($ver["Thema"]);

        $tmp = $this -> vera -> getNameOfVera($ver["VeranstaltungID"]);
        $max_user = $tmp['MaxTeilnehmer'];
        $teilnehmer = $this -> com -> getVeranstaltungUser($ver["VeranstaltungID"], 0, $max_user);

        //		$teilnehmer = $this -> com -> getVeranstaltungUser($ver["VeranstaltungID"]);
        //      print_r($teilnehmer);

        $traeger = "Senat";
        $thema = $quali["Beschreibung"];
        $ort = $ver["Ort"];
        $verBegin = $ver["DatumBegin"];
        $verEnde = $ver["DatumEnde"];
        $artDerMassnahme = $ver["Art"];

        $datum = $ver["DatumBegin"];
        $timestamp = explode(" ", $datum);
        $filename = $timestamp[0] . "_" . $ver["Name"] . "_" . $ver["Thema"];
        $filename = str_replace(":", "", $filename);

        //echo sizeof($teilnehmer);

        //print_r($teilnehmer);
        //		echo count($teilnehmer);
        //echo "ADS".$teilnehmer[0]['UserID'];
        //		print_r($teilnehmer);
        //		return;
        $cc;
        if ($teilnehmer[0]['UserID'] == "")
            $cc = 0;
        else
            $cc = sizeof($teilnehmer);

        for ($i = 0; $i < $cc; $i++) {
            //print_r($this -> user -> get_user($teilnehmer[$i]['UserID']));
            $teilnehmerData[$i] = $this -> user -> get_user($teilnehmer[$i]['UserID']);
        }

        //        return;
        //print_r($teilnehmerData);
        //		foreach ($teilnehmer as $aaa){
        //			$a = $this -> user -> get_user($aaa['UserID']);
        //			echo $a['Name']."<br>";
        //		}

        //echo $filename;

        if ($cc == 0)
            generateExcelTeilnehmerListe($traeger, $thema, $ort, $verBegin, $verEnde, $artDerMassnahme, $teilnehmerData, $teilnehmer, $filename, 0);

        for ($i = 0; $i < $cc; $i += 11) {
            //			if (sizeof($teilnehmer) > ($indexTeilnehmer + 11)) {
            generateExcelTeilnehmerListe($traeger, $thema, $ort, $verBegin, $verEnde, $artDerMassnahme, $teilnehmerData, $teilnehmer, $filename, $i);
            //			}
        }
        redirect('main/changeWebsite/excelDownload');
        //      echo "Done writing file";
    }

}

function generateExcelTeilnehmerListe($traeger, $thema, $ort, $verBegin, $verEnde, $artDerMassnahme, $teilnehmer, $teilnehmerListe, $fileName, $indexTeilnehmer = 0) {

    $path = getcwd() . '/tmp';

    $traegerCell = "A2";
    $themaCell = "D2";
    $ortCell = "A4";
    $zeitraumCell = "C4";
    $anzahlTageCell = "F4";
    $artDerMassnahmeCell = "E5";

    $OffsetTeilnehmerListe = 8;

    $teilnehmerCol = array("NAME" => "B", "NATION" => "C", "ALTER" => "D", "FUNKTION" => "E", "LEER" => "F", "TAGE" => "G", "UEBERNACHTUNG" => "H");

    //Todo - Zeitraum berechenn
    if (isset($verEnde) && !empty($verEnde) && isset($verBegin) && !empty($verBegin)) {
        $anzahlTage = floor((strtotime(substr($verEnde, 0, 10)) - strtotime(substr($verBegin, 0, 10))) / 86400);
        $zeitraum = substr($verBegin, 0, 10) . " - " . chr(10) . substr($verEnde, 0, 10);
    } else {
        $anzahlTage = "";
        $zeitraum = "";
    }

    //2007 ??
    $objReader = new PHPExcel_Reader_Excel5();

    $objPHPExcel = $objReader -> load(getcwd() . '/application/helpers/Teilnahmeliste Kurse.xls');
    $sheet = $objPHPExcel -> getActiveSheet();

    //Infos zur Veranstaltung
    //ToDo: Hächcken setzen (Art der Ma�nahme)             $cell->getCalculatedValue();

    $sheet -> setCellValue($traegerCell, get_cell($traegerCell, $objPHPExcel) . " " . $traeger);
    $sheet -> setCellValue($themaCell, get_cell($themaCell, $objPHPExcel) . " " . $thema);
    $sheet -> setCellValue($ortCell, get_cell($ortCell, $objPHPExcel) . " " . $ort);
    $sheet -> setCellValue($zeitraumCell, get_cell($zeitraumCell, $objPHPExcel) . " " . $zeitraum);
    $sheet -> setCellValue($anzahlTageCell, get_cell($anzahlTageCell, $objPHPExcel) . " " . $anzahlTage);
    $sheet -> setCellValue($artDerMassnahmeCell, 'Art der Massnahme: ' . $artDerMassnahme);

    $eintragungen = sizeof($teilnehmer) - $indexTeilnehmer;
    if ($eintragungen > 11) {
        $eintragungen = 11;
    }
    //print_r($teilnehmerListe);
    //  echo "eintragungen: ".$eintragungen;
    //Eintragen von Mitgliedern
    for ($i = 0; $i < $eintragungen; $i++) {

        if ($teilnehmerListe[$i]['UserID'] == 1)
            $sheet -> setCellValue($teilnehmerCol["NAME"] . "" . ($i + $OffsetTeilnehmerListe), "Person wurde gelöscht");
        
else {
            $sheet -> setCellValue($teilnehmerCol["NAME"] . "" . ($i + $OffsetTeilnehmerListe), $teilnehmer[$indexTeilnehmer + $i]["Name"] . ", " . $teilnehmer[$indexTeilnehmer + $i]["Vorname"] . ", " . $teilnehmer[$indexTeilnehmer + $i]["Strasse"] . " " . $teilnehmer[$indexTeilnehmer + $i]["Hausnr"] . ", " . $teilnehmer[$indexTeilnehmer + $i]["Plz"] . " " . $teilnehmer[$indexTeilnehmer + $i]["Ort"]);
            $sheet -> setCellValue($teilnehmerCol["NATION"] . "" . ($i + $OffsetTeilnehmerListe), "");

            //			echo "geb:".$teilnehmer[$indexTeilnehmer + $i]["Geburtstag"]."<br>";
            if ($teilnehmer[$indexTeilnehmer + $i]["Geburtstag"] != "") {
                $zerlegen = explode(".", $teilnehmer[$indexTeilnehmer + $i]["Geburtstag"]);
                $gebjahr = $zerlegen[2];
                $now = date("Y");
                $alter = $now - $gebjahr;

                //				echo $teilnehmerListe[$i]['UserID']." -->zerlegen 2:".$alter."<br>";
                $sheet -> setCellValue($teilnehmerCol["ALTER"] . "" . ($i + $OffsetTeilnehmerListe), $alter);
            }
        }

        //$teilnehmer[$indexTeilnehmer + $i]["NATION"]);

        $sheet -> setCellValue($teilnehmerCol["FUNKTION"] . "" . ($i + $OffsetTeilnehmerListe), $teilnehmerListe[$indexTeilnehmer + $i]["Funktion"]);

        $sheet -> setCellValue($teilnehmerCol["LEER"] . "" . ($i + $OffsetTeilnehmerListe), "");

        $sheet -> setCellValue($teilnehmerCol["TAGE"] . "" . ($i + $OffsetTeilnehmerListe), "");
        //$teilnehmer[$indexTeilnehmer + $i]["TAGE"]);

        $sheet -> setCellValue($teilnehmerCol["UEBERNACHTUNG"] . "" . ($i + $OffsetTeilnehmerListe), "JA");
        //$teilnehmer[$indexTeilnehmer + $i]["UEBERNACHTUNG"]);
    }

    //Excelliste schreiben

    //  print_r($array_data);
    // PHPExcel_Writer_Excel2007
    $ersetzen = array('ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue', 'ß' => 'ss', ' ' => '_', '\\' => '-', '/' => '-', '*' => '-', '?' => '-', '|' => '-', '<' => '-', '>' => '-', ':' => '-', '+' => '-', '"' => '-');

    $fileName = strtr(strtolower($fileName), $ersetzen);

    $pathForNewExcelFile = $path . "/" . $fileName . "" . getNextIndexOfExcelFile($path . "/" . $fileName) . ".xls";
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

    $objWriter -> save($pathForNewExcelFile);

    $ddata = file_get_contents($pathForNewExcelFile);
    // Read the file's contents
    $dname = $fileName . "" . getNextIndexOfExcelFile($path . "/" . $fileName) . ".xls";

    //force_download($dname, $ddata);
    //echo "adasd".sizeof($teilnehmer);
    //	if (sizeof($teilnehmer) > ($indexTeilnehmer + 11)) {
    //
    //		$indexTeilnehmer += 12;
    //		generateExcelTeilnehmerListe($traeger, $thema, $ort, $zeitraum, $teilnehmer, $fileName, $indexTeilnehmer);
    //	}
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
