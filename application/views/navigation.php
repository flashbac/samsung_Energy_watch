
<ul>
	<li><a class="u3" href="<?php echo site_url("main/index"); ?>">Hauptseite</a></li>
	
	<li><a class="u1">Z&auml;hler</a></li>
	
	<li><a class="u2" href="<?php echo site_url("main/changeWebsite/addMeter"); ?>">Z&auml;hler anlegen</a></li>
	<li><a class="u2" href="<?php echo site_url("main/changeWebsite/listmeters"); ?>">Z&auml;hler anzeigen</a></li>
	<li><a class="u1">Verbrauch</a></li>
	
	<li><a class="u2" href="<?php echo site_url("main/changeWebsite/chart2"); ?>">aktuellen Verbrauch anzeigen</a></li>
	<li><a class="u2" href="<?php echo site_url("main/changeWebsite/chart"); ?>">Verbrauchs History</a></li>
	<li><a class="u2" href="<?php echo site_url("main/changeWebsite/lineChart"); ?>">Line Chart</a></li>
	<li><a class="u2" href="<?php echo site_url("main/changeWebsite/meterChart"); ?>">Meter Chart</a></li>
	<li><a class="u2" href="<?php echo site_url("main/changeWebsite/visualisierung"); ?>">Visualisierung</a></li>

	
	<li><a class="u1" ?>Hilfe</a></li>
	
	<li><a class="u2" href="<?php echo site_url("main/hilfe"); ?>">Hilfe</a></li>
	
	<?php if(! $this->config->item('meter_mode') == 'single'){?>
	
	<li><a class="u1" ?>Administration</a></li>
	
	<li><a class="u2" href="<?php echo site_url("main/changeWebsite/administration"); ?>">Zugang anlegen</a></li>
	<li><a class="u2" href="<?php echo site_url("main/changeWebsite/adminList"); ?>">Zug&auml;nge anzeigen und l&ouml;schen</a></li>
	<li><a class="u2" href="<?php echo site_url("/main/changeWebsite/changePW"); ?>">Zugangspasswort &auml;ndern</a></li>
	
	
	<?php } ?>
</ul>
