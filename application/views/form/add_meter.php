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
//$grundrichtungen = $this->Quali_model->getGrundrichtungen();

// 
// echo "<pre>";
// print_r($grundrichtungen);
// echo "</pre>";

$errors = array();
$successes = array();

// echo "QualiID".$qualiID;

if ($this -> input -> post('Speichern')) {
		
	$qdata =array(
		'Name' => $this -> input -> post('Name'),
		'MeterNumber' => $this -> input -> post('MeterNumber'),
		'Description' => $this -> input -> post('Description'),
		'Unit' => $this -> input -> post('Unit'),
	);
	
    $name = $this -> input -> post('name');

	if (!is_numeric($meterID))
	{
	    $qdata['UserID'] = $id;
	    if ($this -> Meter_model -> getIDfromMeter($name) != 0) 
	    {
	        $errors[] = '<p>Z&auml;hler $name schon vorhanden!</p>';
	    } 
	    else
		{
	        if ($id = $this -> Meter_model -> update('new', $qdata)) {
	            //erfolgreich
	            $successes[] = '<p>Neuer Z&auml;hler angelegt (ID: '.$id.')</p>';
				$meterID = $id;
	        } else {
	            //fehler
	            $errors[] = '<p>Neuer Z&auml;hler konnte nicht angelegt werden!</p>';
	        }
	    }
	}
	else 
	{
		if ($this -> Meter_model -> update($meterID, $qdata)) {
            //erfolgreich
            $successes[] = '<p>Z&auml;hler Wurde geupdated.</p>';
        } else {
            //fehler
            $successes[] = '<p>Es wurden keine Daten f&uuml; Z&auml;hler verändert!</p>';
        }
    }	
}


if (!isset($_POST['Speichern'])) {
	if ($meterID != 'new' && is_numeric($meterID)) {
	// Daten aus der DB holen			
		//Userliste Holen
		$qdb = $this -> Meter_model -> getMeter($meterID);
		
		$qdata =array(
			'Name' => $qdb['Name'],
			'MeterNumber' => $qdb['MeterNumber'],
			'Description' => $qdb['Description'],
			'Unit' => $qdb['Unit'],
			'ID' => $qdb['ID'],
			);
	}
	else 
	{
	// Keine Kreisverband angegeben
		$qdata =array(
            'Name' => '',
            'MeterNumber' => '',
            'Description' => '',
            'Unit' => '',
            );
	}
}


if (validation_errors()) {
    $errors[] = validation_errors();
}
?>

<h3>Hinzuf&uuml;gen von einem Z&auml;hler</h3>

<?php
if (count($errors) > 0) {
    echo "<div class=\"error\">";

    foreach ($errors as $value) {

        echo $value;

    }
    echo "</div>";
}

if (count($successes) > 0) {

    echo "<div class=\"success\">";
    foreach ($successes as $value) {

        echo $value;

    }
    echo "</div>";
}
?>
<div class="eingabe"><?php

$adminstrationsform = array( 
            	'Name' => array(
            		'htmltype' => 'text', 
            		'name' => 'Name:', 
            		'html' => array(
            			'name' => 'Name', //Name von oben
						'id' => 'Name', //Wie Name
						'maxlength' => '40', //Zeichenanzahl
						'size' => '60',
						),
					'value' => $qdata['Name'],
				),
				'MeterNumber' => array(
                    'htmltype' => 'text', 
                    'name' => 'Z&auml;hlernummer:', 
                    'html' => array(
                        'name' => 'MeterNumber', //Name von oben
                        'id' => 'MeterNumber', //Wie Name
                        'maxlength' => '40', //Zeichenanzahl
                        'size' => '60',
                        ),
                    'value' => $qdata['MeterNumber'],
				),
                'Description' => array(
                    'htmltype' => 'text', 
                    'name' => 'Beschreibung:', 
                    'html' => array(
                        'name' => 'Description', //Name von oben
                        'id' => 'Description', //Wie Name
                        'maxlength' => '500', //Zeichenanzahl
                        'size' => '60',
                        ),
                    'value' => $qdata['Description'],
                ),
                'Unit' => array(
                    'htmltype' => 'text', 
                    'name' => 'Einheit:', 
                    'html' => array(
                        'name' => 'Unit', //Name von oben
                        'id' => 'Unit', //Wie Name
                        'maxlength' => '10', //Zeichenanzahl
                        'size' => '60',
                        ),
                    'value' => $qdata['Unit'],
                )
			);
echo form_open("main/changeWebsite/addMeter/".$meterID);
if (empty($qdb['ID']))
{
	echo form_fieldset('Z&auml;hler');
}
else 
{
	echo form_fieldset('Z&auml;hler ID: '.$qdb['ID']);
}
foreach ($adminstrationsform as $element) {

    echo '<div class="input">';
    echo "\n\t\t\t\t";
    echo form_label($element['name'], $element['html']['id']);
    echo "\n\t\t\t\t";

    switch ($element['htmltype']) {
        case 'text' :
            echo form_input($element['html'], $element['value']);
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
$attributes = array('class' => 'main', 'id' => 'administration');

echo form_submit('Speichern', 'Speichern');
echo form_close();
?>