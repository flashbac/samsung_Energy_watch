<div >
<h1>Hallo</h1>
<h4>Hier soll kurz eine Übersicht aller Datencontrollerbefehle aufgeführt werden.</h4>
<p>Daten hinzufügen: <a href="<?php echo site_url("data/putValue/1/20.21"); ?>">PutValue MeterID 1 Value 20.21</a></p>
<p>Letzen Wert lesen: <a href="<?php echo site_url("data/getLastValue/1"); ?>">getLastValue von MeterID 1</a></p>
<p>Zähler hinzufügen: <a href="<?php echo site_url("data/putMeter/1/Temeraturzaehler/01233/Dieser Zaehler ist in Renskys Zimmer/GradC"); ?>">PutMeter Example</a></p>
<p>Mehrere Daten hinzufügen: <a href="<?php echo site_url("data/putAreaValues/22_20.32_2012-01-05 9:00:01~22_20.4_2012-01-05 9:01:01"); ?>">putAriaValues/"22_20.32_2012-01-05 9:00:01~22_20.4_2012-01-05 9:01:01"</a></p>
<p>getAreaValues: <a href="<?php echo site_url("/data/getAreaValues/1/2012-12-01%2000:00:00/2013-01-01%2000:00:00"); ?>">GetAreaValues from MeterID 1</a></p>
<p>getMeter: <a href="<?php echo site_url("/data/getMeter/1"); ?>">GetMeter from UserID 1</a></p> 
</div>
