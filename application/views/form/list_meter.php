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

if ($delID) {
    $name = $this -> meter_model -> getMeter($delID);
    $name = $name['Grundrichtung'] . ', ' . $name['Beschreibung'];

    if ($name) {
        if ($this -> Quali_model -> delete($delID)) {
            $successes[] = '<p>' . htmlentities("Eintrag für $name wurde erfolgreich gelsöcht", 0, 'UTF-8') . '</p>';
        } else {
            $errors[] = '<p>' . htmlentities("Der eintrag für $name konnte nicht gelöscht werden", 0, 'UTF-8') . '</p>';
        }
    } else {
        $errors[] = '<p>' . htmlentities("Der Eintrag für $name konnte nicht gefunden werden", 0, 'UTF-8') . '</p>';
    }

}
?>

<h3>Liste der Z&auml;hler</h3>

<?php
if(count($errors)>0){
echo '<div class="error">';
foreach ($errors as $value) {
echo $value;
}
echo '</div>';
}

if(count($successes)>0){
echo '<div class="success">';
foreach ($successes as $value) {
echo $value;
}
echo '</div>';
}

$per_page = $this -> session -> userdata('per_page');
if(!$pos){
$pos = 1;
}
$data = $this -> Meter_model -> getMeters(($pos-1)*$per_page,$per_page);
if ($data === FALSE) {

echo "<p>Keine Positionen vorhanden!</p>";

} else {

?>
<table border="0" cellpadding="4" cellspacing="0" class="tabelle">
	<thead>
		<tr>
			<th id="MeterName">Name</th><th id="MeterNumber">Z&auml;hlernummer</th><th id="MeterDescription">Z&auml;hlerbeschreibung</th><th id="MeterUnit">Z&auml;hlereinheit</th>
		</tr>
	</thead>
	<tbody>
		<?php
        foreach ($data as $key => $value1) {
            echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\">";
            $url = '';
            $cnt = 0;
            $name = '';
            foreach ($data[$key] as $key2 => $value2) {
                if ($key2 == 'ID') {
                    $id = $value2;
                } else {
                    $cnt++;

                    $url = base_url() . "index.php/main/changeWebsite/listmeters/$pos/$id";
                    $name = $value2;

                    echo "<td class=\"a$cnt\">$value2</td>";
                }
            }
            $img = base_url().'img/edit.png';
            $url = base_url() . "index.php/main/changeWebsite/addMeter/$id";
            echo "<td class=\"a3\"><a href=\"$url\"><img src=\"$img\" alt=\"edit\"></a></td>\n";
            $img = base_url().'img/trash.png';
            $url = base_url() . "index.php/main/changeWebsite/listmeters/$pos/$id";
            echo "<td class=\"a2\"><a href=\"#\" onClick=\"deleteMeter('$url','$name');\"><img src=\"$img\" alt=\"löschen\"></a></td>";
            echo "</tr>\n";
        }
		?>
	</tbody>
</table>
<div class="paging">
	<?php
    $count = $this -> Meter_model -> getCountOfMeters();
    if ($count > $per_page) {
        if ($pos > 1) {
            echo "<a href=\"" . base_url() . 'index.php/main/changeWebsite/qualiList/' . ($pos - 1) . "\"><</a>";
        }
        $tmpcnt = $pos * $per_page;
        if($tmpcnt>$count){
            $tmpcnt = $count;
        }
        echo "<a>" . ($pos - 1) * $per_page . ' von ' . $tmpcnt . '</a>';
        if (($pos * $per_page) < $count) {
            echo "<a href=\"" . base_url() . 'index.php/main/changeWebsite/qualiList/' . ($pos + 1) . "\">></a>";
        }
    }
?>
</div>
<?php } ?>
<script type="text/javascript">
	function deleteMeter(url, name) {
		if (confirm('Wollen Sie den Zähler ' + name + ' und alle abhänigkeiten löschen?'))
			location.href = url;

	}
</script>