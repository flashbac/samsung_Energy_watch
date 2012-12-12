<p><img id="logojpg" src="<?php echo base_url() ?>/image/jrk_logo.jpg"
	alt="Beuth-Logo" width="100" height="96" /></p>
	
<style type="text/css">
<!-- @import "<?php echo base_url() ?>"/css/header.css; -->
</style>
<h1>Energy Watch</h1>

<!-- <p align="right" >  -->

<?php
if ($case == "OK"){
	$username = $this -> session -> userdata('username');
	$img = base_url().'img/edit.png';
?>
	<div id="acInfo">
	<p>Eingelogt als <?=$username?> 
	<?php 
	$adminid =$this -> session -> userdata('isAdmin');
	if (is_numeric($adminid) && $adminid > 0) {
		echo "<img src=\"$img\" alt=\"ist Admin\" height=\"20\" width=\"20\" ></a>";
	}
	?></p>
	</div>
<?php 
}
?>
<div>
<?php
if ($case == "OK"){
	$attributes = array('class' => 'main', 'id' => 'logoutButton');
	echo form_open("main/logout", $attributes);

	echo form_submit('Logout', 'Logout');
	echo form_close();
}
?>
</div>
<!-- </p> -->


