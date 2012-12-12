<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class login extends CI_Controller {

    private $layout_data;

    function __construct() {
        parent::__construct();
        $this -> load -> library('session');
        $this -> load -> helper('date');
        $this -> load -> library('encrypt');
        $this -> load -> helper('form');
        $this -> load -> helper('url');

        //$this->layout_data = $this->viewHeadandNavi();
    }

    private function isSessionValid() {
        
        // Zeit wird von Codeigniter angegeben, siehe config => sess_expiration == 7200 Sec.
        
        if( $this -> session -> userdata('is_logged_in')){
            return TRUE;
        }
                
        return false;
    }

    private function updateSession() {
        $this -> session -> set_userdata('last_activity', now());
    }

    function validate_credentials() {
        echo "<p>validate_credentials</p>";
        $this -> load -> model('dbuser_model');
        echo "<p>dbuser_model loaded</p>";

        //$query = $this -> dbuser_model -> check_user($this -> dbuser_model -> getIDfromUsername($this -> input -> post('username')), $this -> input -> post('username_pw'));
        $query = $this -> dbuser_model -> check_user($this -> input -> post('username'), $this -> input -> post('username_pw'));
        
        
        if ($query)// if the user's credentials validated...
        {
            echo "<p>user valid</p>";
            $data = array('username' => $this -> input -> post('username'), 'user_id' => $this -> dbuser_model -> getIDfromUsername($this -> input -> post('username')), 'is_logged_in' => true);
            $this -> updateSession();
            $this -> session -> set_userdata($data);
            $this -> session -> set_userdata('per_page','20');
            $this -> session -> set_userdata('isAdmin',$this -> dbuser_model -> isAdmin($data['user_id']));
            redirect('main/index');
        } else// incorrect username or password
        {
            echo "<p>user NOT valid</p>";
            $this -> index("LOGIN_FAIL");
        }
    }

    function is_logged_in() {

        if ( $this -> session -> userdata('is_logged_in') != TRUE || !($this -> isSessionValid()))
            return false;
        
        return true;
    }

    function index($case = "") {
        if ($case == "LOGOUT") {
            $this -> session -> sess_destroy();
        } else {
            if ($this -> is_logged_in()) {
                redirect('main/index');
            }
        }
        $this -> layout_data['case'] = $case;

        //				$this -> layout_data['cssfile'] =  $nextWebsite.".css";
        $this -> layout_data['pageTitle'] = "Energy Watch";

        $this -> layout_data['header'] = $this -> load -> view('header', $this -> layout_data, true);

        $this -> layout_data['cssfile'] = "login.css";
        $this -> layout_data['navigation'] = $this -> load -> view('clean', NULL, true);
        $this -> layout_data['content'] = $this -> load -> view('signup_form', $this -> layout_data, true);
        //Welches Content File geladen werden soll
        //$this->load->view('login_tamplate', $this->layout_data);
        $this -> load -> view('main', $this -> layout_data);
    }

}
