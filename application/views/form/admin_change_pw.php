<script type="text/javascript">
    var myTimeout = 0;

    $(document).ready(function() {
        if (myTimeout > 0)
            window.clearTimeout(myTimeout);
        myTimeout = window.setTimeout(hide_infos, 5000);

        function hide_infos() {
            $('.error').hide("slow");
            $('.success').hide("slow");
        }

    }); 
</script>
<?php
$id = $this -> session -> userdata('user_id');
$errors = array();
$successes = array();

$oldPW = $this -> input -> post('altPW');
$newPW = $this -> input -> post('newPW');
$newPWw = $this -> input -> post('newPWw');

if (validation_errors()) {
    $errors[] = validation_errors();
}

if ($this -> input -> post('Speichern') && count($errors)<1) {
    if ($newPW != $newPWw) {
        $errors[] = '<p>' . htmlentities('Die wiederholte Eingabe stimmt nicht mit dem neune Passwort überein',0,'UTF-8') . '</p>';
    } else {

        if (!$this -> Dbuser_model -> changePW($id, $oldPW, $newPW)) {
            $errors[] = '<p>' . htmlentities("bitte überpürfen Sie Ihr aktuelles Passswort!", 0, 'UTF-8') . '</p>';
        } else {
            $successes[] = '<p>' . htmlentities("Ihr Passwort wurde erfolgreich geändert!", 0, 'UTF-8') . '</p>';
        }
    }
}
?>

<link type="text/css" href="<?php echo base_url() ?>css/administration.css" rel="stylesheet" />
<h3>Hinzuf&uuml;gen von Adminstratoren oder Benutzern</h3>

<?php
foreach ($errors as $value) {
?>
<div class="error">
	<?php echo $value
	?>
</div>
<?php
}

foreach ($successes as $value) {
?>
<div class="success">
	<?php echo $value
	?>
</div>
<?php
}
?>

<div class="eingabe"><?php
$adminstrationsform = array('Benutzername' => array('htmltype' => 'password', 'name' => 'altes Passwort:', 'html' => array('name' => 'altPW', //Name von oben
'id' => 'altPW', //Wie Name
'maxlength' => '50', //Zeichenanzahl
'value' => '', )), 'Passwort' => array('htmltype' => 'password', 'name' => 'Passwort:', 'html' => array('name' => 'newPW', //Name von oben
'id' => 'newPW', //Wie Name
'maxlength' => '40', //Zeichenanzahl
'value' => '', )), 'Passwortw' => array('htmltype' => 'password', 'name' => 'Passwort wiederholen:', 'html' => array('name' => 'newPWw', //Name von oben
'id' => 'newPWw', //Wie Name
'maxlength' => '40', //Zeichenanzahl
'value' => '', )), );

echo form_fieldset('&auml;nderung deines Passwortes');
echo form_open("main/changeWebsite/changePW");
foreach ($adminstrationsform as $element) {

    echo '<div class="input">';
    echo "\n\t\t\t\t";
    echo form_label($element['name'], $element['html']['id']);
    echo "\n\t\t\t\t";

    switch ($element['htmltype']) {
        case 'text' :
            echo form_input($element['html']);
            break;
        case 'password' :
            echo form_password($element['html']);
            break;
        case 'textarea' :
            echo form_textarea($element['html']);
            break;
        case 'function' :
            echo $element['funcname']($element);
            break;
        case 'checkbox' :
            echo form_checkbox($element['html'], $element['html']['id'], $element['html']['checked']);
            break;
        case 'dropdown' :
            echo form_dropdown($element['html']['id'], $element['values'], $element['selected']);
            break;
    }

    echo "\n\t\t\t\t";
    echo "</div>\n\t\t\t\t";
}

echo form_fieldset_close();
$attributes = array('class' => 'main', 'id' => 'admin_change_pw');

echo form_submit('Speichern', 'Speichern');
echo form_close();
?>