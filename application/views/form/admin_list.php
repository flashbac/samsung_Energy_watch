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

if (isset($delNAME)) {
    if ($this -> user_model -> deleteUser($delNAME)) {
        $successes[] = '<p>' . htmlentities("Eintrag für $delNAME wurde erfolgreich gelsöcht", 0, 'UTF-8') . '</p>';
    } else {
        $errors[] = '<p>' . htmlentities("Der eintrag für $delNAME konnte nicht gelöscht werden", 0, 'UTF-8') . '</p>';
    }
}
?>

<link type="text/css" href="<?php echo base_url() ?>css/administration.css" rel="stylesheet" />
<h3>Listen von Logindaten</h3>

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

$per_page = 20;

if (!$this -> user_model -> getAlluser(0, 1)) {

echo "<p>Keine Mitglieder vorhanden!</p>";

} else {
if(!$pos){
$pos = 1;
}


$data = $this -> user_model -> getAlluser(($pos-1)*$per_page,$per_page);
}
?>
<table border="0" cellpadding="4" cellspacing="0" class="tabelle">
	<thead>
		<tr>
			<th id="name">Name</th><th id="admin">hat Adminrechte</th><th id="del">l&ouml;schen</th>
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
                    if ($key2 == 'Admin') {
                        if ($value2) {
                            $value2 = 'JA';
                        } else {
                            $value2 = 'NEIN';
                        }
                    }else{
                         $url = base_url() . "index.php/main/changeWebsite/deleteuser/$pos/$value2";
                         $name = $value2;
                    }
                    echo "<td class=\"a$cnt\">$value2</td>";
                }
            }
           
            //echo "<td class=\"a$cnt\"><a href=\"$url\">X</a></td>";
            $img = base_url().'img/trash.png';
            echo "<td class=\"a$cnt\"><a href=\"#\" onClick=\"deleteUser('$url','$name');\"><img src=\"$img\" alt=\"löschen\"></a></td>";
            echo "</tr>\n";
        }
		?>
	</tbody>
</table>
<div class="paging">
	<?php
    $count = $this -> user_model -> getCountOfusers();
    if ($count > $per_page) {
        if ($pos > 1) {
            echo "<a href=\"" . base_url() . 'index.php/main/changeWebsite/adminList/' . ($pos - 1) . "\"><</a>";
        }
        echo "<a>" . ($pos - 1) * $per_page . ' von ' . $pos * $per_page . '</a>';
        if (($pos * $per_page) < $count) {
            echo "<a href=\"" . base_url() . 'index.php/main/changeWebsite/adminList/' . ($pos + 1) . "\">></a>";
        }
    }
	?>
</div>
<script type="text/javascript">
function deleteUser (url, name) {
if (confirm('Wollen Sie den DB-User '+name+' l&ouml;schen?')) 
location.href=url;
  
}
</script>