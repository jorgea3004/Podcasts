<?php
function xml2array($contents, $get_attributes = 1, $priority = 'tag'){
    if (!$contents) return array();
    if (!function_exists('xml_parser_create')) {
        // print "'xml_parser_create()' function not found!";
        return array();
    }
    // Get the XML parser of PHP - PHP must have this module for the parser to work
    $parser = xml_parser_create('');
    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); // http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, trim($contents) , $xml_values);
    xml_parser_free($parser);
    if (!$xml_values) return; //Hmm...
    // Initializations
    $xml_array = array();
    $parents = array();
    $opened_tags = array();
    $arr = array();
    $current = & $xml_array; //Refference
    // Go through the tags.
    $repeated_tag_index = array(); //Multiple tags with same name will be turned into an array
    foreach($xml_values as $data) {
        unset($attributes, $value); //Remove existing values, or there will be trouble
        // This command will extract these variables into the foreach scope
        // tag(string), type(string), level(int), attributes(array).
        extract($data); //We could use the array by itself, but this cooler.
        $result = array();
        $attributes_data = array();
        if (isset($value)) {
            if ($priority == 'tag') $result = $value;
            else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
        }
        // Set the attributes too.
        if (isset($attributes) and $get_attributes) {
            foreach($attributes as $attr => $val) {                                   
                                if ( $attr == 'ResStatus' ) {
                                    $current[$attr][] = $val;
                                }
                if ($priority == 'tag') $attributes_data[$attr] = $val;
                else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
            }
        }
        // See tag status and do the needed.
                    //echo"<br/> Type:".$type;
        if ($type == "open") { //The starting of the tag '<tag>'
            $parent[$level - 1] = & $current;
            if (!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                $current[$tag] = $result;
                if ($attributes_data) $current[$tag . '_attr'] = $attributes_data;
                                    //print_r($current[$tag . '_attr']);
                $repeated_tag_index[$tag . '_' . $level] = 1;
                $current = & $current[$tag];
            }
            else { //There was another element with the same tag name
                if (isset($current[$tag][0])) { //If there is a 0th element it is already an array
                    $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                    $repeated_tag_index[$tag . '_' . $level]++;
                }
                else { //This section will make the value an array if multiple tags with the same name appear together
                    $current[$tag] = array(
                        $current[$tag],
                        $result
                    ); //This will combine the existing item and the new item together to make an array
                    $repeated_tag_index[$tag . '_' . $level] = 2;
                    if (isset($current[$tag . '_attr'])) { //The attribute of the last(0th) tag must be moved as well
                        $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                        unset($current[$tag . '_attr']);
                    }
                }
                $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                $current = & $current[$tag][$last_item_index];
            }
        }
        elseif ($type == "complete") { //Tags that ends in 1 line '<tag />'
            // See if the key is already taken.
            if (!isset($current[$tag])) { //New Key
                $current[$tag] = $result;
                $repeated_tag_index[$tag . '_' . $level] = 1;
                if ($priority == 'tag' and $attributes_data) $current[$tag . '_attr'] = $attributes_data;
            }
            else { //If taken, put all things inside a list(array)
                if (isset($current[$tag][0]) and is_array($current[$tag])) { //If it is already an array...
                    // ...push the new element into that array.
                    $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                    if ($priority == 'tag' and $get_attributes and $attributes_data) {
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                    }
                    $repeated_tag_index[$tag . '_' . $level]++;
                }
                else { //If it is not an array...
                    $current[$tag] = array(
                        $current[$tag],
                        $result
                    ); //...Make it an array using using the existing value and the new value
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    if ($priority == 'tag' and $get_attributes) {
                        if (isset($current[$tag . '_attr'])) { //The attribute of the last(0th) tag must be moved as well
                            $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                            unset($current[$tag . '_attr']);
                        }
                        if ($attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                        }
                    }
                    $repeated_tag_index[$tag . '_' . $level]++; //0 and 1 index is already taken
                }
            }
        }
        elseif ($type == 'close') { //End of tag '</tag>'
            $current = & $parent[$level - 1];
        }
    }
    return ($xml_array);
}
function readItunesXmlFeedPodcasts($dir,$subDir,$feedUrl,$dirCopy,$titulo,$flagFileName,$daysToReveview){
    $html = '<h3>Del Blog '.$titulo.'</h3><ol>';
    $feedContent = conectByCurl($feedUrl);
    $xmlArray = xml2array($feedContent, $get_attributes = 3, $priority = 'tag'); // it will work 100% if not ping me @skype: sapan.mohannty
    $xmlArrayItems = $xmlArray['rss']['channel']['item'];
    for ($i = 0; $i < count($xmlArrayItems); ++$i) {
        $item = $xmlArrayItems[$i];
        //var_dump($item);exit();
        $ep = 0;
        if (isset($item['itunes:episode'])) {
            $ep = $item['itunes:episode'];
        }
        if(strlen($ep)<3){
            $ep = (strlen($ep)==2) ? '0'.$ep : '00'.$ep;
        }
        $dateComplete = strtotime($item['pubDate']);
        $fecha1 = date('d-m-Y H:i:s',$dateComplete);
        $fecha2 = date('d-m-Y H:i:s');
        $fecha1a = explode(" ", $fecha1);
        $fecha2a = explode(" ", $fecha2);
        $dias = resta_fechas($fecha1a[0],$fecha2a[0]);
        if($dias > $daysToReveview){
            $pod = $item['enclosure_attr']['url'];
            $fmp3 = limpiaAcentos(strtolower($item['title']));
            if ($flagFileName==0) {
                $pod1 = explode("/", $pod);
                $nmFile = $pod1[count($pod1)-1];
                $nmFile = str_replace('.mp3', '', $nmFile);
                $nmFile = str_replace('.m4a', '', $nmFile);
                $fmp3 = limpiaAcentos(strtolower($nmFile));
            }

            $apod = explode("?", $pod);
            if( count($apod)>1 ){
                $arch = $apod[0];
            } else {
                $arch = $pod;
            }
            $podext = $item['enclosure_attr']['type'];
            if ($podext=='audio/x-m4a') {
                $ext = 'm4a';
            } else {
                $ext = 'mp3';
            }

            $spod = explode("/", $arch);
            $archloc = $spod[count($spod)-1];

            $localFile = $dir . $subDir . $ep . "_" .$fmp3.".".$ext;

            if (!file_exists($localFile)) {
                if (!file_exists($dir . $subDir)) {
                    if(!mkdir($dir . $subDir,0777,true)){
                        $html .= "No se puede crear directorio.";
                    }
                }
                $txt='';
                if ( copy($arch, $localFile) ) {
                    $txt = "Copy success";
                    if (file_exists($localFile)) {
                        copy($arch, $localFile);
                        if (file_exists($localFile)) {
                            copy($localFile, $dirCopy.$fmp3.".".$ext);
                            unlink($dirCopy.$fmp3.".".$ext);
                        }
                    }
                    //$totalNuevos++;
                }else{
                    $txt = "Copy failed";
                }
                $html .= "<li>" . $txt . ": ".$localFile ."</li>";
            }
            //$html .= "<li>: ".$localFile ."</li>";
        }
        //echo $ep . " -> " . $xmlArrayItems[$i]['title'] . " => " . $pod . "<br>";
    }
    $html .='</ol>';
    return $html;
    //var_dump($xmlArrayItems);
}

?>