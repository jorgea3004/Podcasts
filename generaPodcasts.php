<?php
ini_set("max_execution_time",3600);
require_once('./getid3/getid3.php');
function formatoFechaDir($axo,$mm){
	$mes='';
	$mm=$mm+1-1;
	switch ($mm) {
	    case 1: $mes = 'Enero'; break;
	    case 2: $mes = 'Febrero';  break;
	    case 3: $mes = 'Marzo';  break;
	    case 4: $mes = 'Abril';  break;
	    case 5: $mes = 'Mayo';  break;
	    case 6: $mes = 'Junio';  break;
	    case 7: $mes = 'Julio';  break;
	    case 8: $mes = 'Agosto';  break;
	    case 9: $mes = 'Septiembre'; break;
	    case 10: $mes = 'Octubre'; break;
	    case 11: $mes = 'Noviembre'; break;
	    case 12: $mes = 'Diciembre'; break;
	}
	if (strlen($mm)==1) {
		$mm='0'.$mm;
	}
	return $axo . $mm . '_' . $mes; 
}
function resta_fechas($fecha1,$fecha2)
{
  if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha1))
		  list($dia1,$mes1,$año1)=explode("/",$fecha1);
  if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha1))
		  list($dia1,$mes1,$año1)=explode("-",$fecha1);
	if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha2))
		  list($dia2,$mes2,$año2)=explode("/",$fecha2);
  if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha2))
		  list($dia2,$mes2,$año2)=explode("-",$fecha2);
	$dif = mktime(0,0,0,$mes1,$dia1,$año1) - mktime(0,0,0,$mes2,$dia2,$año2);
  $ndias=floor($dif/(24*60*60));
  return($ndias);
}
function conectByCurl($urlLink){
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $urlLink);
	curl_setopt($curl, CURLOPT_TIMEOUT, 3);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);

	// FeedBurner requires a proper USER-AGENT...
	curl_setopt($curl, CURL_HTTP_VERSION_1_1, true);
	curl_setopt($curl, CURLOPT_ENCODING, "gzip, deflate");
	curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3");

	$feedContent = curl_exec($curl);
	curl_close($curl);
	return $feedContent;
}

function getDirContents($dir, $filter = '', &$results = array()) {
    $files = scandir($dir);

    foreach($files as $key => $value){
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value); 

        if(!is_dir($path)) {
            if(empty($filter) || preg_match($filter, $path)) $results[] = $path;
        } elseif($value != "." && $value != "..") {
            getDirContents($path, $filter, $results);
        }
    }

    return $results;
} 
function generaListaPodcasts($directorioPodcasts){
	$sep = '
	';
	$mp3m3u = '#EXTM3U'.$sep;

	$dest=$directorioPodcasts;
	$array1 = getDirContents($dest, '/\.mp3$/');
	$array2 = getDirContents($dest, '/\.m4a$/');
	$arreglo = array_merge($array1, $array2);
	for ($i=(count($arreglo)-1); $i >= 0; $i--) { 
		$item = $arreglo[$i];
		$item = str_replace('\\', '/', $item);
		$item = str_replace("C:","",$item);
		$item = str_replace("D:","",$item);
		$item = str_replace("E:","",$item);
		$item = str_replace("F:","",$item);
		$item = str_replace("G:","",$item);

		$getID3 = new getID3;
		$pathName = $item;
		$ThisFileInfo = $getID3->analyze($pathName);

		$time = (!empty(@$ThisFileInfo['playtime_string']) ? @$ThisFileInfo['playtime_string'] : '00:00:01');
		$arrT = explode(":", $time);
		if (count($arrT)<3) {
			$arrT[2] = $arrT[1];
			$arrT[1] = $arrT[0];
			$arrT[0] = '00';
		}
		if (!isset($arrT[2])) {
			$arrT[2]='00';
		}
		if (!isset($arrT[1])) {
			$arrT[1]='00';
		}
		if (!isset($arrT[0])) {
			$arrT[0]='00';
		}
		$segundos = ($arrT[0]*3600) + ($arrT[1]*60) + $arrT[2];

		$arrN = explode("/", $item);
		$mp3name = $arrN[count($arrN)-1];
		$mp3name = str_replace(".mp3", '', $mp3name);
		$mp3m3u .= '#EXTINF:'.$segundos . ',' 
				. htmlentities(!empty($ThisFileInfo['comments_html']['artist']) ? implode('', $ThisFileInfo['comments_html']['artist']) : 'Podcasts')
				. ' - ' 
				. htmlentities(!empty($ThisFileInfo['comments_html']['title']) ? implode('', $ThisFileInfo['comments_html']['title']) : $mp3name) . $sep;
		$mp3m3u .= str_replace('/', '\\', $item) . $sep;
	}

	/*$myfile = fopen("/jorge/musik/podcasts.m3u", "w");
	fwrite($myfile, $mp3m3u);
	fclose($myfile);*/
}
/*
$dir ='/jorge/Podcasts/';
generaListaPodcasts($dir);
*/
?>