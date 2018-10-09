<?php
ini_set("memory_limit","100M");
ini_set("max_execution_time",36000);
require_once('generaPodcasts.php');
require_once('readXmlFeedPodcasts.php');
$flagSave = true;
$base = '/jorge/';
$dir = $base.'Podcasts/';
$dirpods = $base.'musik/';
$dirCopy = $base.'downloads/';
$totalNuevos = 0;
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
	<title>Feeds Podcasts</title>
</head>
<body>
   <h2>Actualizaci&oacute;n Podcasts:</h2>
<?php

/****************************************/
/*	Proceso para feed disparamargot     */
/****************************************/

$contenido_xml = "";
$idblog = 'disparamargot';
$subDir = 'Dispara, Margot, dispara/';
$feedUrl = "http://feeds.feedburner.com/disparamargot?format=xml"; 
$feedContent = conectByCurl($feedUrl);
// Did we get feed content?
if($feedContent && !empty($feedContent)):
   $feedXml = @simplexml_load_string($feedContent);
   if($feedXml): ?>
   <h3>Del Blog Dispara-Margot...</h3>
   <ul>
     <?php foreach($feedXml->channel->item as $item):
     	$fmp3 = $item->enclosure['url'][0];
     	$datea = explode("/", $fmp3);
     	$a = $datea[count($datea)-1];
     	$dateab = explode("-", $a);
     	$ymdir = formatoFechaDir($dateab[0],$dateab[1]);
   		$img_path = $dir . $subDir . $ymdir . "/";
     	$localFile = $img_path . $a;
     	if (!file_exists($localFile)) {
     		if (!file_exists($img_path)) {
	     		if(!mkdir($img_path,0777,true)){
	     			echo "No se puede crear directorio."; exit();
	     		}
	     	}
	     	$txt='Ok';
	     	if ($flagSave) {
				if ( copy($fmp3, $localFile) ) {
					copy($localFile, $dirCopy.$a);
				    $txt = "Copy success";
				    $totalNuevos++;
				}else{
				    $txt = "Copy failed";
				}
	     	}
     	 	echo "<li>" . $txt . ": ".$localFile."</li>";
     	} 
      ?>
     <?php endforeach;
      ?>
   </ul>
   <?php endif; ?>
 <?php endif; ?>
 <?php 

/****************************************/
/*	    Proceso para feed desafora2     */
/****************************************/

$subDir = 'desafora2/';
$feedUrl = "http://desafora2.libsyn.com/rss/"; 
echo readXmlFeedPodcasts($dir,$subDir,$feedUrl,$dirCopy,'Desafora2');

/****************************************/
/*        Proceso para feed dixo        */
/****************************************/

$contenido_xml = "";
$blogs=array();
$blogs[] = array('fernanda','Fernanda Tapia');
$blogs[] = array('pada','Capitan Pada y sus monitos');
$blogs[] = array('fragshampoo','Fragancia Shampoo');
$blogs[] = array('trujo','Trujo');
$blogs[] = array('warpig','Warpig');
//$blogs[] = array('byte','Byte Podcast');

$subDir = '/';
$feedUrl = "http://www.dixo.com/feed/"; 
$feedContent = conectByCurl($feedUrl);

if($feedContent && !empty($feedContent)):
   $feedXml = @simplexml_load_string($feedContent);
   if($feedXml): ?>
   <h3>Del Blog DIXO ...</h3>
   <ul>
     <?php foreach($feedXml->channel->item as $item):
     	$txt='OK';
     	 for ($i=0; $i < count($blogs); $i++) { 
     	 	$idblog = $blogs[$i][0];
	     	if (strstr($item->link, $idblog)) {
				$dateComplete = strtotime($item->pubDate);
				$fecha1 = date('d-m-Y H:i:s',$dateComplete);
				$fecha2 = date('d-m-Y H:i:s');
				$fecha1a = explode(" ", $fecha1);
				$fecha2a = explode(" ", $fecha2);
				$dias = resta_fechas($fecha1a[0],$fecha2a[0]);
				if($dias > -16){
			   		$img_path = $dir . $blogs[$i][1] . $subDir;
			     	$fmp3 = $item->enclosure['url'];
			     	$datea = explode("/", $fmp3);
			     	$a = $datea[count($datea)-1];
			     	$localFile = $img_path . $a;
			     	if (!file_exists($localFile)) {
			     		if (!file_exists($img_path)) {
				     		if(!mkdir($img_path,0777,true)){
				     			echo "No se puede crear directorio."; exit();
				     		}
				     	}
				     	if ($flagSave) {
							if ( copy($item->enclosure['url'], $localFile) ) {
							    $txt = "Copy success";
								copy($localFile, $dirCopy.$a);
							    $totalNuevos++;
							}else{
							    $txt = "Copy failed";
							}
						}
			     	 	echo "<li>" . $txt . ": ".$blogs[$i][1] . " / " . $a ."</li>";
			     	}
		     	}
 			}
     	}
      endforeach;
      ?>
   </ul>
   <?php endif; ?>
 <?php endif;

/****************************************/
/*        Proceso para feed Revolver    */
/****************************************/
$subDir = 'revolver/';
$feedUrl = "http://revolver.dixo.libsynpro.com/rss/"; 
echo readXmlFeedPodcasts($dir,$subDir,$feedUrl,$dirCopy,'Revolver');

/****************************************/
/*	Proceso para feed Capitan Pada     */
/****************************************/

$subDir = 'Capitan Pada y sus monitos/';
$feedUrl = "http://mx.ivoox.com/es/capitan-pada-sus-monitos_fg_f1560540_filtro_1.xml";
echo readXmlFeedPodcasts($dir,$subDir,$feedUrl,$dirCopy,'Capitan Pada');

generaListaPodcasts($dir);
?>
<h3>FIN DE PROCESO</h3>
</body>
</html>