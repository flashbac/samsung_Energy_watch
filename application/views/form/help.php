<h1>Hilfe</h1>

<?php

echo '<h2 class="suche_h">Mitglieder anlegen</h2>';
echo '<p class="suche_p1">Wenn man einem Mitglied den Aktuellen Kreisverband zuzuordnen möchte, so lässt man das Austrittsdatum leer.</p>';
echo '<p class="suche_p1">Falls keine Kreisverbände, Positionen oder Qualifikationen eingetragen wurden. So kann man einem Mitglied auch keine Kreisverbände, Positionen oder Qualifikationen zuordnen.</p>';

echo '<h2 class="suche_h">Suche</h2>';
$img = base_url().'img/typing_waiting.gif';
echo "<p class=\"gif_suche\"><img src=\"$img\" alt=\"waiting\"></p>";
echo '<p class="suche_p1">Diese Animation erscheint, wenn auf weitere Eingaben gewartet wird.</p>';

$img = base_url().'img/typing_search.gif';
echo "<p class=\"gif_warte\"><img src=\"$img\" alt=\"search\"></p>";
echo '<p>Diese Animation erscheint, wenn auf die Antwort der Datenbank gewartet wird.</p>';
Echo '<blockquote id="suche_b2">Die einzelnen Suchparameter werden bei einer UND Verknüpfung mit einem Komma getrennt';
Echo ' bei einer ODER Verknüpfung mit einem Semikolon. Diese beiden Sucharten können nicht gemischt werden!</blockquote>';
echo '<blockquote id="suche_b3">Bei Zeichenketten können Sie zusätzlich mit dem * arbeiten. z.B. "*h*" sucht in der gewählten Tabelle einen Eintrag wo das h vorkommt</blockquote>';
echo '<blockquote id="suche_b4">Bei Postleitzahlen können Sie die hinteren Zahlen weg lassen. z.B. findet 123 alle postleitzahlen die mit 123 anfangen.</blockquote>';
echo '<blockquote id="suche_b5">Bei Daten können Sie z.B. nur das Jahr z.B. "2009 ==" alle Daten aus 2009 ODER nur Monat und Jahr: "04.2009 ==" alle Daten im April 2009 ODER ein volles Datum "01.04.2009 ==" Alle Daten vom 01.04.2009</blockquote>';
echo '<blockquote id="suche_b17">Bei einem Geburstag verhält es sich genau anders herum: 01.04 zeigt alle Personen die am 01.04 Geburstag haben oder *.04 alle die im April Geburstag haben.</blockquote>'; 
echo '<h3 class="suche_h31">Suche unter Personen</h3>';
echo '<p class="suche_erk1">Die Suche f&uuml;r Personen kann wie folgt genutzt werden:</p>';
echo '<blockquote id="suche_b1">Die Eingabereihenfolge lautet:<ol><li>Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>[n]</b></li><li>Vorname&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>[vn]</b></li><li>Kreisverband<b>[k]</b></li><li>Qualifikation&nbsp;<b>[q,bq,hq,bg,hg,bb,hb]</b></li><li>Position&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>[p]</b></li><li>Plz&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>[plz]</b></li><li>Ort&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>[o]</b></li><li>Bezirk&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>[bz]</b></li><li>Geburtstag&nbsp;&nbsp;&nbsp;<b>[g]</b></li><li>Beruf&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>[br]</b></li></blockquote>';
echo '<blockquote id="suche_b6">Bsp. 1: Wenn man alle Personen eines Bezirkes finden möchte: "*,*,Wedding ==" dies würde alle Personen wo der Bezirk mit Wedding eingetragen ist anzeigen</blockquote>';
echo '<blockquote id="suche_b7">Bsp. 2: Wenn man alle Personen eines Bezirkes ODER alle Personen die mit ihrem Namen mit "Ge" anfangen finden möchte: "Ge,*,Wedding ==" dies würde alle Personen, wo der Bezirk mit Wedding eingetragen ist und die wo nur der Name mit "Ge" anfängt anzeigen</blockquote>';
echo '<h3 class="suche_h32">Suche unter Veranstaltungen</h3>';
echo '<p class="suche_erk2">Die Suche f&uuml;r Veranstaltungen kann wie folgt genutzt werden:</p>';
echo '<blockquote id="suche_b8">Die Eingabereihenfolge lautet: 1. Name der Veranstaltung  2. Grundrichtung  3. Plz  4. DatumBegin  5. DatumEnde</blockquote>';
echo '<blockquote id="suche_b9">Bsp. 1.1: wenn man alle Veranstaltungen in einem Plz Bereich anzeigen lassen will: "*,*,121 ==" dies würde die Veranstaltungen anzeigen, die mit einer Plz von 121 anfangen</blockquote>';
echo '<blockquote id="suche_b10">Bsp. 1.2: wenn man alle Veranstaltungen in einem Monat anzeigen lassen will: "*,*,*,04.2012 ==" dies würde die Veranstaltungen anzeigen, die im April 2012 anfangen</blockquote>';
echo '<blockquote id="suche_b11">Bsp. 1.3: wenn man alle Veranstaltungen in einem Monat anzeigen lassen will: "*,*,*,*,04.2012 ==" dies würde die Veranstaltungen anzeigen, die im April 2012 enden</blockquote>';
echo '<blockquote id="suche_b12">Alternativ können Sie auch eine Syntax wählen, in der die Reihenfolge keine Rolle spielt, dazu benutzen Sie bitte die in Eckigen Klammern angegeben Buchstaben gefolgt von einem Doppelpunkt und ihrem Suchbegriff</blockquote>';
echo '<blockquote id="suche_b13">Unten aus der Tabelle entnehmen Sie bitte die Suchoptionen</blockquote>';
echo '<blockquote id="suche_b14">Bsp. 2.1: wer hat oder hatte Bedarf an einer Qualifikation "q:Erste Hilfe"</blockquote>';
echo '<blockquote id="suche_b15">Bsp. 2.2: Suche nach einen Namen und hat Bedarf an einer bestimmten Qualifikation "n:Klaus,hb:Erste"</blockquote>';
echo '<blockquote id="suche_b16">Im Detail haben alle Suchparameter nur eine kurz Form, abgesehen von der Qualifikation hier gilt folgendes</blockquote>';
?>
<blockquote>
<table>
    <th>Kurzform</th><th>Bedeutung</th>
  <tr>
    <td><b>q</b></td>
    <td>Sucht nach allen Qualifikationen (Grundrichtung ist ODER Verknüft mit Beschreibung)</td>
  </tr>
  <tr>
    <td><b>bq</b></td>
    <td>Sucht nach allen Qualifikationen, bei denen Bedarf besteht (Grundrichtung ist ODER Verknüpft mit Beschreibung)</td>
  </tr>
  <tr>
    <td><b>hq</b></td>
    <td>Sucht nach allen Qualifikationen, bei denen kein Bedarf besteht (Grundrichtung ist ODER Verknüpft mit Beschreibung)</td>
  </tr>
  <tr>
    <td><b>bg</b></td>
    <td>Sucht nach alle Qualifikationen, bei denen Bedarf besteht (nur Grundrichtung)</td>
  </tr>
  <tr>
    <td><bhg</b></td>
    <td>Sucht nach allen Qualifikationen, bei denen kein Bedarf besteht (nur Grundrichtung)</td>
  </tr>
  <tr>
    <td><b>bb</b></td>
    <td>Sucht nach allen Qualifikationen, bei den der Bedarf besteht (nur Beschreibung)</td>
  </tr>
  <tr>
    <td><b>hb</b></td>
    <td>Sucht nach allen Qualifikationen, bei denen kein Bedarf besteht (nur Beschreibung)</td>
  </tr>
</table>
</blockquote>
<?php
echo '<h2>Kreisverb&auml;nde</h2>';
$img = base_url().'img/add_star_gold.png';
echo "<p class=\"star_gold_add\"><img src=\"$img\" alt=\"star_gold_add\"></p>";
echo '<blockquote id="kreis_b1">Ein Klick auf dieses Symbol befördertet ein Mitglied in einem Kreisverband zu dem Kreisverbandsleiter, wenn er noch kein Leiter ist.</blockquote>';

$img = base_url().'img/add_star_silver.png';
echo "<p class=\"star_silver_add\"><img src=\"$img\" alt=\"star_silver_add\"></p>";
echo '<blockquote id="kreis_b2">Ein Klick auf dieses Symbol befördertet ein Mitglied in einem Kreisverband zu dem 2. Kreisverbandsleiter, wenn er noch kein Leiter ist.</blockquote>';

$img = base_url().'img/delete_star_gold.png';
echo "<p class=\"star_gold_delete\"><img src=\"$img\" alt=\"star_gold_delete\"></p>";
echo '<blockquote id="kreis_b3">Ein Klick auf dieses Symbol nimmt einem Mitglied in einem Kreisverband den Status Kreisverbandsleiter.</blockquote>';

$img = base_url().'img/delete_star_silver.png';
echo "<p class=\"star_silver_delete\"><img src=\"$img\" alt=\"star_silver_delete\"></p>";
echo '<blockquote id="kreis_b4">Ein Klick auf dieses Symbol nimmt einem Mitglied in einem Kreisverband den Status 2. Kreisverbandsleiter.</blockquote>';

echo '<h2 id="eg1">Das Generieren einer Excel - Liste</h3>';
echo '<blockquote id="eg_b1">Wenn man eine Excel - Liste einer Veranstaltung generieren möchte geht man wie folgt vor:
Man klickt zunächst auf den Navigationseintrag „Veranstaltungen anzeigen“. Daraufhin werden die bestehenden Veranstaltungen unter ein ander aufgelistet. In jeder Zeile wird rechts ein grünes Excel- Symbol bereitgestellt, mit diesem kann man die Excel - Datei für jede einzelne Veranstaltung erstellen lassen kann. Wenn auf eine Veranstaltung geklickt wird, kann oben ebenfalls eine Excel - Liste erstellt werden mit einem Klick auf „Exceltabelle erstellen“.
Wenn man einen der Excel - Button betätigt, wird man automatisch auf eine Seite weitergeleitet, auf der diese Datei ausgewählt und runtergeladen werden kann. Zusätzlich ist einem in dieser Ansicht auch gestattet, Excel - Listen durch den Löschen - Button einzelnd zu entfernen. Das Löschen aller Excel - Dateien kann durch den Button „Alle Loeschen!“ ausgelöst werden.</blockquote>';
echo '<blockquote id="eg_b1">Eine Excel - Liste beinhaltet alle Mitglieder, die zu dieser Veranstaltung hinzugeführt wurden. Hiervon ausgeschlossen sind Mitglieder, die auf der Warteliste der zugehörigen Veranstaltung stehen. Wenn alle Mitglieder auf der Excel - Liste aufgelistet werden sollen, erhöht man das Maximal - Limit für eine Veranstaltung.</blockquote>';
?>