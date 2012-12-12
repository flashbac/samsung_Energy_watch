<?php
/*
 * Example aufruf DayValue(geburts,13,4,2011);
 */
function DayValue($parameters) {
    $txt = "";
    // TODO: umbedingt nochmal Ändern
    $parameter = $parameters['parameter'];
    //für Tag
    $txt .= "\n<select name=\"" . $parameter['name'] . "tag\">\n";
    for ($i = 1; $i <= 31; $i++) {
        $txt .= "\t<option value=\"" . $i . "\"";
        if ($parameter['tag'] == $i)
            $txt .= ' selected="selected" ';
        $txt .= ">" . $i . "</option>\n";
    }
    $txt .= "</select>\n.";

    //für Monat
    $txt .= "\n<select name=\"" . $parameter['name'] . "monat\">\n";
    for ($i = 1; $i <= 12; $i++) {
        $txt .= "\t<option value=\"" . $i . "\"";
        if ($parameter['monat'] == $i)
            $txt .= ' selected="selected" ';
        $txt .= ">" . $i . "</option>\n";
    }
    $txt .= "</select>\n.";

    //für Jahr
    //$txt .=  "\n<select name=\"".$parameter['name']."jahr\">\n";
    $txt .= '<input type="text" name="' . $parameter['name'] . 'jahr" value="' . $parameter['jahr'] . '" id="' . $parameter['name'] . '" maxlength="4"  size="4"/>';

    // for($i=1920;$i<=2020;$i++) {
    // $txt .=  "\t<option value=\"". $i ."\"";
    // if ($parameter['jahr'] == $i )
    // $txt .=  ' selected="selected" ';
    // $txt .=  ">". $i ."</option>\n";
    //}
    //$txt .=  "</select>\n";
    return $txt;
}

function GeschlechtValue($parameters) {
    $txt = "";	
	$txt .= '<div class="input">';
	$txt .= '<div id="geschlecht">';
		//	echo "\n\t\t\t\t";
	$txt .= form_label($parameters['name'],$parameters['html']['id']);
		//	echo "\n\t\t\t\t";
    $parameter = $parameters['parameter'];
    foreach ($parameter['optionen'] as $key => $element) {
    	$txt .= '<span class="radiotrenner">';
        $txt .= '<input type="radio" name="' . $parameter['id'] . '" value="' . $key . '"';
        if ($parameter['checked'] == $key)
            $txt .= ' checked="checked" ';
        $txt .= '>' . $element;
        $txt .= '</span>';
    }
	$txt .= "</div></div>\n\t\t\t\t";
    return $txt;
}

function TelefonEmailValue($parameter) {
    $txt = "";
    $txt .= form_dropdown($parameter['html']['id'], $parameter['type'], $parameter['selected']);
    $txt .= " ";
    $txt .= form_input($parameter['html']);
    return $txt;
}

function printPosition($element) {
    $txt = "";
    Foreach ($element['parameter']['positionArr'] as $pos) {
        $positionArr[$pos['PositionID']] = $pos['Position'];
    }
    $selected = $element['parameter']['selected'];
    $von['parameter'] = $element['parameter']['von'];
    $bis['parameter'] = $element['parameter']['bis'];

    $txt .= form_dropdown($element['html']['id'], $positionArr, $selected);
    $txt .= " von ";
    DayValue($von);
    $txt .= " bis ";
    DayValue($bis);
    return $txt;
}

function printQualli($parameter) {
    $txt = "";
    $selectedQualli = $parameter['selected'];
    $quallifikationen = $parameter['quallifikationsArr'];
    foreach ($selectedQualli as $key => $element) {
        $txt .= "\n<select name=\"" . $parameter['html']['id'] . $key . "\">\n";
        foreach ($quallifikationen as $keyq => $qualli) {
            $txt .= "\t<option value=\"" . $qualli . "\"";
            if ($element == $keyq)//wenn der index des qualliarrays gleich dem aktuellem selected element ist.
                $txt .= ' selected="selected" ';
            $txt .= ">" . $qualli . "</option>\n";
        }
        $txt .= "</select></br>\n";
    }
    return $txt;
}

function printFuehZeug($parameter) {
    $txt = "";
    $txt .= form_checkbox($parameter['html'],$parameter['html']['id'],$parameter['html']['checked']);
    $txt .= " vorgelegt am: ";
	$js = 'onblur="checkdateEmpty(value)"'.' onkeyup="colordate(this)" onmouseover="colordate(this)" ';
    $txt .= form_input($parameter['datehtml'], $parameter['date'],$js);
    return $txt;
}

function getuserformarray() {
    $userform = array('Foermlich' => array('htmltype' => 'checkbox', 'html' => array('name' => 'F&ouml;rmlich:', 'id' => 'foermlich', 'checked' => TRUE, )), 'Name' => array('htmltype' => 'text', 'html' => array('name' => 'Name:', 'id' => 'Name', 'value' => 'Walter', 'maxlength' => '100', )), 'Vorname' => array('htmltype' => 'text', 'html' => array('name' => 'Vorname:', 'id' => 'Vorname', 'maxlength' => '100', )), 'Strasse' => array('htmltype' => 'text', 'html' => array('name' => 'Stra&szlig;e:', 'id' => 'Strasse', 'maxlength' => '100', )), 'HausNr' => array('htmltype' => 'text', 'html' => array('name' => 'Hausnummer:', 'id' => 'HausNr', 'maxlength' => '100', )), 'Plz' => array('htmltype' => 'text', 'html' => array('name' => 'PLZ:', 'id' => 'Plz', 'maxlength' => '100', )), 'Ort' => array('htmltype' => 'text', 'html' => array('name' => 'Ort:', 'id' => 'Ort', 'maxlength' => '100', )), 'Bezirk' => array('htmltype' => 'text', 'html' => array('name' => 'Bezirk:', 'id' => 'Bezirk', 'maxlength' => '100', )), 'Geburtstag' => array('htmltype' => 'function', 'html' => array('name' => 'Geburtstag:', 'id' => 'geburtstag', ), 'funcname' => 'DayValue', 'parameter' => array('name' => 'Geburts', 'tag' => '1', 'monat' => '1', 'jahr' => '2000', ), ), 'Geschlecht' => array('htmltype' => 'function', 'html' => array('name' => 'Geschlecht:', 'id' => 'geschlecht', ), 'funcname' => 'GeschlechtValue', 'parameter' => array('id' => 'Geschlecht', 'checked' => 'm', 'optionen' => array('m' => 'm', 'w' => 'w', ), ), ), 'Beruf' => array('htmltype' => 'text', 'html' => array('name' => 'Beruf:', 'id' => 'Beruf', 'maxlength' => '100', )), 'Arbeitszeit' => array('htmltype' => 'text', 'html' => array('name' => 'Arbeitszeit:', 'id' => 'Arbeitszeit', 'maxlength' => '80', )), 'TelefonNr0' => array('htmltype' => 'function', 'funcname' => 'TelefonEmailValue', 'html' => array('name' => 'Telefonnummer:', 'id' => 'TelefonNr0', 'maxlength' => '100', ), 'type' => array('Arbeit' => 'Arbeit', 'Privat' => 'Privat', 'Mobil' => 'Mobil', ), 'selected' => 'Privat', ), 'Email0' => array('htmltype' => 'function', 'funcname' => 'TelefonEmailValue', 'html' => array('name' => 'Email:', 'id' => 'Email0', 'maxlength' => '100', ), 'type' => array('Arbeit' => 'Arbeit', 'Privat' => 'Privat', 'Mobil' => 'Mobil', ), 'selected' => 'Privat', ), 'Facebook' => array('htmltype' => 'text', 'html' => array('name' => 'Facebook:', 'id' => 'Facebook', 'maxlength' => '100', )), 'BevorzugteKommunikation' => array('htmltype' => 'dropdown', 'html' => array('name' => 'Bevorzugte Kommunikation:', 'id' => 'BevorzugteKommunikation', ), 'values' => array('Telefon' => 'Telefon', 'Email' => 'Email', 'Facebook' => 'Facebook', 'Post' => 'Post', ), 'selected' => 'Email', ), 'MitgliedSeid' => array('htmltype' => 'function', 'html' => array('name' => 'Mitglied seid:', 'id' => 'MitgliedSeid', ), 'funcname' => 'DayValue', 'parameter' => array('name' => 'MitgliedSeid', 'tag' => '1', 'monat' => '1', 'jahr' => '2000', ), ), 'Kreisverband' => array('htmltype' => 'dropdown', 'html' => array('name' => 'Kreisverband:', 'id' => 'Kreisverband', ), 'values' => array('1' => 'lala', '3' => 'tratra', '4' => 'hihi', ), 'selected' => '4', ), 'Position1' => array('html' => array('name' => 'Position:', 'id' => 'Position1', ), 'htmltype' => 'function', 'funcname' => 'printPosition', 'parameter' => array('positionArr' => array('1' => 'JRKP', '2' => 'Gruppenf&uuml;hrer', '3' => 'Erste Hilfe', ), 'selected' => '2', 'von' => array('name' => 'Position1von', 'tag' => '1', 'monat' => '1', 'jahr' => '2000', ), 'bis' => array('name' => 'Position2bis', 'tag' => '1', 'monat' => '1', 'jahr' => '2000', ), ), ), 'Quallifikation' => array('htmltype' => 'function', 'funcname' => 'printQualli', 'html' => array('name' => 'Quallifikation:', 'id' => 'Quallifikation', ), 'quallifikationsArr' => array('1' => 'lala', '2' => 'lata', '3' => 'tratra', '4' => 'hihi', ), 'selected' => array('1' => '2', '2' => '4', ), ), 'IQuallifikation' => array('htmltype' => 'function', 'funcname' => 'printQualli', 'html' => array('name' => 'Intressierte Quallifikationen:', 'id' => 'IQuallifikation', ), 'quallifikationsArr' => array('1' => 'lala', '2' => 'lata', '3' => 'tratra', '4' => 'hihi', ), 'selected' => array('1' => '2', '2' => '4', ), ), 'Faehigkeiten' => array('htmltype' => 'textarea', 'html' => array('name' => 'F&auml;higkeiten:', 'id' => 'Faehigkeiten', 'maxlength' => '1000', 'rows' => '8', 'cols' => '50', ), ), 'Anmerkungen' => array('htmltype' => 'textarea', 'html' => array('name' => 'Anmerkungen:', 'id' => 'Anmerkungen', 'maxlength' => '10000', 'rows' => '8', 'cols' => '50', ), ), 'Fuehrerschein' => array('htmltype' => 'checkbox', 'html' => array('name' => 'F&uuml;hrerschein:', 'id' => 'Fuehrerschein', 'checked' => TRUE, )), 'ErweitertesFuehrungszeugnis' => array('html' => array('name' => 'Erweitertes Fuehrungszeugnis:', 'id' => 'ErweitertesFuehrungszeugnis', 'checked' => true, ), 'htmltype' => 'function', 'funcname' => 'printFuehZeug', 'date' => array('name' => 'ErweitertesFuehrungszeugnis', 'tag' => '1', 'monat' => '1', 'jahr' => '2000', ), ), 'Besonderheiten' => array('htmltype' => 'textarea', 'html' => array('name' => 'Besonderheiten:', 'id' => 'Besonderheiten', 'maxlength' => '1000', 'rows' => '8', 'cols' => '50', ), ), );
    return $userform;

}

/*
 * Helperfunctionen für Zeitumwandlung
 *
 */
function transform_date($date, $to = 'eng_to_dt')//$to= dt_to_eng, oder eng_to_dt
{
    $cache = '';
    $datum = '';
    switch($to) {
        case 'dt_to_eng' :
            $cache = explode('.', $date);
            if (count($cache) > 1)
                $datum = $cache[2] . '-' . $cache[1] . '-' . $cache[0];
            else
                $datum = False;
            break;
        case 'eng_to_dt' :
        default :
            $cache = explode('-', $date);
            if (count($cache) > 1)
                $datum = $cache[2] . '.' . $cache[1] . '.' . $cache[0];
            else
                $datum = False;
            break;
        //obere break mit absicht weggelassen
    }
    return $datum;
}

function is_today($date) {
    $cache = FALSE;
    if ($date == date('d.m.Y'))
        $cache = TRUE;
    ;
    return $cache;
}

function transform_timestamp($timestamp)//nach dt.
{
    $cache = explode(' ', $timestamp);
    if (count($cache) > 1) {

        if (is_today(transform_date($cache[0], 'eng_to_dt')))
            $stamp = $cache[1];
        else {
            $stamp = $cache[1] . ' ' . transform_date($cache[0], 'eng_to_dt');
        }
    } else
        $stamp = False;

    return $stamp;
}

function transformToTimestamp($year = FALSE, $month = FALSE, $day = FALSE, $hour = FALSE, $minute = FALSE) {

    if (!$year || !$month || !$day || !$hour || !$minute) {
        return FALSE;
    }

    // Bsp.: '2005-05-13 07:15:31'
    return "$year-$month-$day $hour:$minute:00";
}

function transformFromTimestamp($timestamp = FALSE, $seperated = TRUE) {
    if (!$timestamp) {

        return FALSE;

    }
    // Bsp.: '2005-05-13 07:15:31'
    if ($seperated) {
        $tmp = explode(' ', $timestamp);
        if (count($tmp) < 2) {
            return FALSE;
        }
        $year = explode('-', $tmp[0]);
        if (count($year) < 3) {
            return FALSE;
        }
        $time = explode(':', $tmp[1]);
        if (count($time) < 3) {
            return FALSE;
        }
        $result = array('Jahr' => $year[0], 'Monat' => $year[1], 'Tag' => $year[2], 'Stunde' => $time[0], 'Minute' => $time[1], 'Sekunde' => $time[2]);
    } else {
        $tmp = explode(' ', $timestamp);
        $date = transform_date($tmp[0]);
        $tmp2 = explode(':', $tmp[1]);
        $time = $tmp2[0] . ':' . $tmp2[1];
        $result = $date . ' ' . $time;
    }
    return $result;
}

function isInArray($data, $key1, $key2, $value1, $value2) {
    foreach ($data as $index => $v) {
        if (($data[$index][$key1] == $value1) && ($data[$index][$key2] == $value2)) {
            return TRUE;
        }
    }
    return FALSE;
}

function diff_array($a1, $a2, $main_index1, $main_index2) {
    $result = array();
    foreach ($a1 as $key => $value) {
        if (!isInArray($a2, $main_index1, $main_index2, $a1[$key][$main_index1], $a1[$key][$main_index2])) {
            $result[] = $a1[$key];
        }
    }

    return $result;
}

function diffTel($array1, $array2, $main_index1, $main_index2) {
    $data = array();
    foreach ($array1 as $key => $value) {
        if (substr($key, 0, -1) == "TelefonID") {
            $data[] = array('Type' => $array1['TelefonType' . substr($key, -1)], 'Nummer' => $array1['TelefonNr' . substr($key, -1)]);
        }
    }
    $result['del'] = diff_array($array2, $data, 'Type', 'Nummer');
    $result['new'] = diff_array($data, $array2, 'Type', 'Nummer');

    return $result;
}

function birthday($birthday) {
    list($year, $month, $day) = explode("-", $birthday);
    $year_diff = date("Y") - $year;
    $month_diff = date("m") - $month;
    $day_diff = date("d") - $day;
    if ($day_diff < 0 || $month_diff < 0)
        $year_diff--;
    return $year_diff;
}

function calcUserAge($data) {
    foreach ($data as $key => $value) {
        if (isset($data[$key]['Geburtstag'])) {

            $data[$key]['Alter'] = birthday($data[$key]['Geburtstag']) + 1;
        }
    }
    return $data;
}

function intiliForStr($str) {
    if(strlen($str)<1){
        $str = '%';
    }else{
        if ($str[0] == '*' || strpos($str, '*')) {
            $str = str_replace("*", "%", $str);
        } else {
            $str = $str . '%';
        }
    }
    return "'" . $str . "'";
}

function intiliForNumber($str, $length = 5) {
    $str = str_replace("*", "", $str);
    if(empty($str)){
        $length=0;
    }
    if ($length < 5) {
        $digits = 5 - $length;
        $upper = '';
        $lower = '';
        for ($i = 0; $i < $digits; $i++) {
            $upper .= '9';
            $lower .= '0';
        }
        $str = " BETWEEN $str$lower AND $str$upper";
    }
    return $str;
}

function intiliForDate($str, $DBName) {
    $result = '';
    $str = explode('.', $str);
    // 0=Tag, 1=Monat, 2=Jahr
    $cnt = count($str);
    switch ($cnt) {
        default :
        case 1 :
            $result = "year($DBName) = $str[0]";
            break;
        case 2 :
            $result .= "year($DBName) =  $str[1] AND month($DBName) = $str[0]";
            break;
        case 3 :
            $result .= "year($DBName) = $str[2] AND month($DBName) = $str[1] AND day($DBName) = $str[0]";
    }
    return $result;
}

function intiliForGeb($str, $DBName) {
    $result = '';
    $str = explode('.', $str);
    // 0=Tag, 1=Monat, 2=Jahr
    $cnt = count($str);
    $first = TRUE;
    switch ($cnt) {
        default :
        case 1 :
            if(!empty($str[0]) && $str[0]!='*'){
                $result = "day($DBName) = $str[0]";
            }
            break;
        case 2 :
            if(!empty($str[1]) && $str[1]!='*'){
                $result .= "month($DBName) =  $str[1]";
                $first = FALSE;
            }
            if(!empty($str[0]) && $str[0]!='*'){
                if($first){
                    $result .= "day($DBName) =  $str[0]";
                    $first = FALSE;    
                }else{
                    $result .= " AND day($DBName) =  $str[0]";
                }   
            }
            break;
        case 3 :
            if(!empty($str[1]) && $str[1]!='*'){
                $result .= "month($DBName) =  $str[1]";
                $first = FALSE;
            }
            if(!empty($str[0]) && $str[0]!='*'){
                if($first){
                    $result .= "day($DBName) =  $str[0]";
                    $first = FALSE;    
                }else{
                    $result .= " AND day($DBName) =  $str[0]";
                }   
            }
            if(!empty($str[2]) && $str[2]!='*'){
                if($first){
                    $result .= "year($DBName) =  $str[2]";
                }else{
                    $result .= " AND year($DBName) =  $str[2]";
                }
            }
            break;
    }
    return $result;
}

function getIntili($str, $conf) {

    switch ($conf['format']) {
        default :
        case 'str' :
            $str = intiliForStr($str);
            $str = $conf['DBName'] . ' LIKE ' . $str;
            break;
        case 'number' :
            if (strlen($str) < 5) {
                $str = ' (' . $conf['DBName'] . '' . intiliForNumber($str, strlen($str)) . ')';
            } else {
                $str = ' ' . $conf['DBName'] . '=' . intiliForNumber($str);
            }
            break;
        case 'timestamp' :
            $str = explode(' ', $str);
            $str = $str[0];
            $str = ' ' . intiliForDate($str, $conf['DBName']);
            break;
        case 'date' :
            $str = intiliForDate($str, $conf['DBName']);
            break;
        case 'geb' :
            $str = intiliForGeb($str, $conf['DBName']);
            break;
        case 'special_quali':
            $str = intiliForStr($str);
            switch ($conf['typed']) {
                default:
                case 'q':
                        $str = ' Grundrichtung LIKE ' . $str . ' OR Beschreibung LIKE '.$str;
                    break;
                case 'bq':
                        $str = ' ((Grundrichtung LIKE ' . $str . ' OR Beschreibung LIKE ' . $str . ') AND BedarfHaben=1)';
                    break;
                case 'hq':
                        $str = ' ((Grundrichtung LIKE ' . $str . ' OR Beschreibung LIKE ' . $str . ') AND BedarfHaben=0)';
                    break;
            }
           break;
    }
    return $str;
}

function getConf($config,$typed){
    foreach ($config as $key => $value) {
        $tmp = explode(',',$config[$key]['typed']);
        foreach ($tmp as $key2 => $value2) {
            if($value2 == $typed){
                if($config[$key]['DBName'] === 'noDB_Quali'){
                    $config[$key]['typed'] = $value2;
                }
                return $config[$key];
            }
        }
    }
}

function intiliSearch($searchString = FALSE, $config = FALSE) {
    if ($searchString == FALSE || $config == FALSE) {
        return FALSE;
    }

    //type DBName='Name', typed='name:', format='str', pri=0
    //type DBName='Vorname', typed='vname:', format='str', pri=1
    //type DBName='Name', typed='name:', format='str', pri=0
    //$searchString = 'hallo, ich, 12, 23.02.2001';

    $result = '';
    $opterator = ' AND ';
    $cnt = 0;
    $anzahl = 0;
    $cnt_conf = count($config);
    $searchString = trim($searchString);
    $last = $searchString[strlen($searchString)-1];
    if($last == ',' || $last == ';'){
        $searchString = substr($searchString, 0, -1);
    }
    $kPos = strpos($searchString, ',');
    $sPos = strpos($searchString, ';');
    if($kPos !== FALSE AND $sPos !== FALSE){
        if($kPos < $sPos){
            $searchString = str_replace(';', ',', $searchString);
        }else{
            $searchString = str_replace(',', ';', $searchString);
        }
    }

    $explode_komma = explode(',', $searchString);
    $explode_simmi = explode(';', $searchString);
    $anzahl = count($explode_komma);

    if (count($explode_simmi) > $anzahl) {
        $opterator = ' OR ';
        $explode_komma = $explode_simmi;
        $anzahl = count($explode_simmi);
        unset($explode_simmi);
    }

    foreach ($explode_komma as $key => $value) {
        if ($cnt == $cnt_conf) {
            return $result;
        }
        $isset = FALSE;
        $value = trim($value);
        foreach ($config as $key2 => $value2) {
            if ($value2['prio'] == $cnt) {

                $conf = $value2;
                break;
            }
        }

        if (strlen($value) < 1) {

            switch ($conf['format']) {
                case 'str' :
                    $value = '*';
                    break;
                case 'number' :
                    break;
                default :
                    break;
            }
            $value = '*';
        }
        if (strpos($value, ':')) {
            if ($cnt != 0) {
                $result .= $opterator;
            }
            $ex = explode(':',$value);
            $conf = getConf($config,$ex[0]);
            $result = $result . getIntili($ex[1], $conf);
        } else {
            if ($cnt != 0) {
                $result .= $opterator;
            }
            $result = $result . getIntili($value, $conf);
            $isset = TRUE;
        }

        $cnt++;
    }
if(empty($result)){
    return FALSE;
}else{
    return $result;   
}    
}
?>