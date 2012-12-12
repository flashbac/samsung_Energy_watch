<h1>Login Energy Watch</h1>
<center>
<?php
if ($case== "SESSION_UNAVAILABLE"){
	echo "Das System hat Sie nach einer Stunde automatisch ausgeloggt. Bitte loggen Sie sich wieder ein.";
}
if ($case== "LOGOUT"){
	echo "Sie wurden erfolgreich ausgeloggt.";
}
if ($case== "LOGIN_FAIL"){
	echo "Benutzername oder Passwort falsch.";
}
 if ($case== "NOT_LOGIN"){
	echo "Bitte loggen Sie sich ein.";
} 
?>

</center></br></br>
<fieldset>
<legend>Login</legend>
<?php

echo form_open("login/validate_credentials");

echo form_input('username', '');
echo form_password('username_pw', '');
echo form_submit('login', 'Login');
?>
</fieldset>

<?php echo validation_errors('<p class="error">'); ?>
</fieldset>