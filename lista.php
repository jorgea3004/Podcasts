<?php
ini_set("max_execution_time",3600);
require_once('./getid3/getid3.php');
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
	$sep = '<br>';
	$mp3m3u = '#EXTM3U'.$sep;
	$directorioPodcasts = '/jorge/iTunes/Podcasts/';
	$dest=$directorioPodcasts;
	$arreglo = getDirContents($dest, '/\.mp3$/');
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
		echo "Tiempo: " . $time . " -> " .  $arrT[0] . " : " .  $arrT[1] . " : " .  $arrT[2] . " = " . $segundos . "<br>";

		$arrN = explode("/", $item);
		$mp3name = $arrN[count($arrN)-1];
		$mp3name = str_replace(".mp3", '', $mp3name);
		$mp3m3u .= '#EXTINF:'.$segundos . ',' 
				. htmlentities(!empty($ThisFileInfo['comments_html']['artist']) ? implode('', $ThisFileInfo['comments_html']['artist']) : 'Podcasts')
				. ' - ' 
				. htmlentities(!empty($ThisFileInfo['comments_html']['title']) ? implode('', $ThisFileInfo['comments_html']['title']) : $mp3name) . $sep;
		$mp3m3u .= str_replace('/', '\\', $item) . $sep;
	}
	echo "<br><br>".$mp3m3u;
	/*$myfile = fopen("/jorge/musik/podcasts.m3u", "w");
	fwrite($myfile, $mp3m3u);
	fclose($myfile);*/

?>