<?php
//convert ical to XML
function icalparse($string) {
$folder = "\r\n".' ';
$prop = str_replace($folder,'',$string);
$prop = str_replace('\;', ';',$prop);
$prop = str_replace('\:', ':',$prop);
$prop = str_replace('\,', ',',$prop);
$prop = str_replace('\"', '"',$prop);
$prop = str_replace('  ', ' ',$prop);
$prop = str_replace("\n".' ',' ',$prop);
$prop = str_replace("\r",'',$prop);
$prop = explode("\n",$prop);
$xml = '<?xml version="1.0" standalone="yes"?>'."\n";
$xml = '<?xml-stylesheet type="text/xsl" href="datesort.xsl"?>'."\n";
     foreach($prop as $line) {
 
        $matches = array();
        // This matches PROPERTYNAME;ATTRIBUTES:VALUE
        if (preg_match('/^([^:^;]*)(?:;([^:]*))?:(.*)$/',$line,$matches)) {
            $propertyName = strtolower($matches[1]);
            $attributes = strtolower($matches[2]);
            $value = $matches[3];
            if ($propertyName == 'begin') {

                $xml .= '<'.strtolower($value).'>'."\n";
                continue;
            } elseif ($propertyName == 'end') {
                $xml .= '</'.strtolower($value).'>'."\n";
                continue;
            }
 
             $xml .= str_repeat(" ",$spaces);
            $xml .= '<'.$propertyName;
            if ($attributes) {
                // There can be multiple attributes
                $attributes = explode(';',strtolower($attributes));
                foreach($attributes as $att) {
 
                    list($attName,$attValue) = explode('=',$att,2);
                    $xml .= ' '.$attName.'="'.$attValue.'"';
 
                }
            }
 
            $xml .= '>'.htmlspecialchars($value).'</'.$propertyName.'>'."\n";
 
        }

    }
return $xml;
}

//Able to be used as XHTTP GET resource

$uri = $_GET['uri'] ?? '';

if ($uri){
$uri = str_replace('webcal://','http://',$uri);
$options  = array('http' => array('user_agent' => 'PHP/7.0.27'));
$context  = stream_context_create($options);
$body = file_get_contents($uri,false,$context);
$event = icalparse($body);
$ev = simplexml_load_string($event);
if ($ev->vevent) {
foreach ($ev->vevent as $e) {
//$now = strtotime("now");
//if (strtotime($e->dtstart) >= $now) {
$date = date('j M Y\, g:ia', strtotime($e->dtstart));
$band = $e->summary;
$band = str_replace("’","'", $band);
$band = str_replace(', '.date('j F Y', strtotime($e->dtstart)),'', $band);
$location = $e->location;
$loc = explode(',',$location);
$desc = htmlentities($e->description);
echo '<li>'.$date.': '.$band.' - '.$location.'</li>'."\n";
//}
}
}
}
?>
