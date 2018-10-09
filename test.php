<?php
ini_set("memory_limit","100M");
ini_set("max_execution_time",36000);
require_once('generaPodcasts.php');
$flagSave = true;
$base = '/jorge/';
$dir = $base.'Podcasts/';
$dirpods = $base.'musik/';
$dirCopy = $base.'downloads/';
if(!file_exists($dir) || !file_exists($dirpods)){
	if(!mkdir($dir,0777,true))//Se crea la carpeta de imagenes
	{
		echo "Error: Al crear directorio"; exit();
	}

	if(!mkdir($dirpods,0777,true))//Se crea la carpeta de imagenes
	{
		echo "Error: Al crear directorio Musik"; exit();
	}
}
?>
<html>
<head>
	<title>Test Feeds Podcasts</title>
</head>
<body>
   <h2>Actualizaci&oacute;n Podcasts:</h2>
<?php

$subDir = 'desafora2/';
$feedUrl = "http://desafora2.libsyn.com/rss/"; 
$feedContent = conectByCurl($feedUrl);
if($feedContent && !empty($feedContent)):
   $feedXml = @simplexml_load_string($feedContent);
   if($feedXml): ?>
   <h3>Del Blog desafora2 ...</h3>
   <ul>
     <?php foreach($feedXml->channel->item as $item):
     	$txt='OK';
		$dateComplete = strtotime($item->pubDate);
		$fecha1 = date('d-m-Y H:i:s',$dateComplete);
		$fecha2 = date('d-m-Y H:i:s');
		$fecha1a = explode(" ", $fecha1);
		$fecha2a = explode(" ", $fecha2);
		$dias = resta_fechas($fecha1a[0],$fecha2a[0]);
		if($dias > -16){
	   		$img_path = $dir . $subDir;
	   		$arrFormats = array('mp3','m4a');
   			$fr = $item->title;
   			$fmp3 = str_replace('_', '', $fr);
   			$fmp3 = str_replace(' ', '_', $fr);
   			$fmp3 = str_replace('á', 'a', $fmp3);
   			$fmp3 = str_replace('é', 'e', $fmp3);
   			$fmp3 = str_replace('í', 'i', $fmp3);
   			$fmp3 = str_replace('ó', 'o', $fmp3);
   			$fmp3 = str_replace('ú', 'u', $fmp3);
   			$fmp3 = str_replace('ñ', 'n', $fmp3);
   			$fmp3 = str_replace(':', '', $fmp3);
   			$fmp3 = str_replace(',', '', $fmp3);
   			$fmp3 = str_replace("'", '-', $fmp3);
   			$fmp3 = str_replace('"', '', $fmp3);
   			$fmp3 = str_replace('*', '', $fmp3);
   			$fmp3 = str_replace('+', '', $fmp3);
   			$fmp3 = str_replace('~', '-', $fmp3);
	     	$localFile = $img_path . $fmp3.".mp3";
	     	$nn='';
		    $rn='';
		    $nn = $img_path.$rn;
	     	if (!file_exists($localFile)) {
	     		if (!file_exists($img_path)) {
		     		if(!mkdir($img_path,0777,true)){
		     			echo "No se puede crear directorio."; exit();
		     		}
		     	}
		     	$txt='';
		     	if ($flagSave) {
					if ( copy($item->enclosure['url'], $localFile) ) {
					    $txt = "Copy success";
					    if (file_exists($localFile)) {
							copy($localFile, $dirCopy.$fmp3.".mp3");
					    }
					    $totalNuevos++;
					}else{
					    $txt = "Copy failed";
					}
				}
	     	 	echo "<li>" . $txt . ": ".$localFile . " -> " . $nn ."</li>";
	     	}
     	}
      endforeach;
      ?>
   </ul>
   <?php endif; ?>
 <?php endif; ?>
 <?php 
?>
<h3>FIN DE PROCESO</h3>
</body>
</html>