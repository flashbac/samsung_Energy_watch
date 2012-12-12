<?php
    $id = $this -> session -> userdata('user_id');
    $errors = array();
    $successes = array();
    
    if( !$id || !$this->Dbuser_model->isAdmin($id) ){
        redirect(site_url("main/index")); 
    }   
echo "";
    if(isset($isOK)){
        $userName   = $this->input->post('Benutzername');
        $userPW     = $this->input->post('Passwort');
        $pww        = $this->input->post('Passwortw');
        $isAdmin    = $this->input->post('Admin');
        if($this->Dbuser_model->getIDfromUsername($userName)){
            $errors[] =  '<p>'.htmlentities("Benutzername ($userName) schon vorhanden!").'</p>';
        }else{
            if($id = $this->Dbuser_model->addUser($userName, $userPW, $isAdmin)){
                //erfolgreich
                $successes[] = '<p>'.htmlentities("neuer Benutzer angelegt (ID: $id)").'</p>';
            }else{
                //fehler
                $errors[] = '<p>'.htmlentities('neuer Benutzer konnte nicht angelegt werden!').'</p>';
            }
        }
    }
if(validation_errors()){
    $errors[] = validation_errors();
}
    
?>

<link type="text/css" href="<?php echo base_url() ?>css/administration.css" rel="stylesheet" />
<h3>Hinzuf&uuml;gen von Adminstratoren oder Benutzern</h3>

<?php
foreach ($errors as $value) {
    ?>
    <div class="error">
        <?php echo $value ?>
    </div>
    <?php
}

foreach ($successes as $value) {
    ?>
    <div class="success">
        <?php echo $value ?>
    </div>
    <?php
}

?>

<div class="eingabe"><?php 
$adminstrationsform = array(
			'Benutzername'=>array(
				'htmltype' => 'text',
				'name' => 'Benutzername:',
				'html' => array(
					'name' => 'Benutzername',			//Name von oben
					'id' => 'Benutzername',				//Wie Name	
					'maxlength' => '50',		//Zeichenanzahl
					'value' => '',
				)
			),
			'Passwort'=>array(
				'htmltype' => 'password',
				'name' => 'Passwort:',
				'html' => array(
					'name' => 'Passwort',			//Name von oben
					'id' => 'Passwort',				//Wie Name	
					'maxlength' => '40',		//Zeichenanzahl
					'value' => '',
				)
			),
			'Passwortw'=>array(
				'htmltype' => 'password',
				'name' => 'Passwort wiederholen:',
				'html' => array(
					'name' => 'Passwortw',			//Name von oben
					'id' => 'Passwortw',				//Wie Name	
					'maxlength' => '40',		//Zeichenanzahl
					'value' => '',
				)
			),
			'Admin'=>array(
				'htmltype' => 'checkbox',
				'name' => 'Admin:',
				'html' => array(
					'name' => 'Admin',
					'id' => 'Admin',
					'checked' => false,
				)
			)
			);

echo form_fieldset('Administration');
echo form_open("main/changeWebsite/administration");
foreach ($adminstrationsform as $element) {

	echo '<div class="input">';
	echo "\n\t\t\t\t";
	echo form_label($element['name'],$element['html']['id']);
	echo "\n\t\t\t\t";

	switch ($element['htmltype']) {
		case 'text':
			echo form_input($element['html']);
			break;
		case 'password':
			echo form_password($element['html']);
			break;
		case 'textarea':
			echo form_textarea($element['html']);
			break;
		case 'function':
			echo $element['funcname']($element);
			break;
		case 'checkbox':
			echo form_checkbox($element['html'],$element['html']['id'],$element['html']['checked']);
			break;
		case 'dropdown':

			echo form_dropdown($element['html']['id'],$element['values'],$element['selected']);
			break;
	}

	echo "\n\t\t\t\t";
	echo "</div>\n\t\t\t\t";
}

echo form_fieldset_close();
echo form_submit('Speichern','Speichern');
echo form_close();
?>