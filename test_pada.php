<?php

ini_set("memory_limit","100M");
ini_set("max_execution_time",3600);
require_once('generaPodcasts.php');
$flagSave = true;
$base = '/jorge/';
$dir = $base.'Podcasts/';
$dirpods = $base.'musik/';
$dirCopy = $base.'downloads/';
$totalNuevos=0;
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
	<title>Test Feeds Pada</title>
</head>
<body>
   <h2>Actualizaci&oacute;n Podcasts:</h2>
<?php

$subDir = 'Capitan Pada y sus monitos/';
$feedUrl = "http://mx.ivoox.com/es/capitan-pada-sus-monitos_fg_f1560540_filtro_1.xml"; 
$feedContent = conectByCurl($feedUrl);
//var_dump($feedContent);
if($feedContent && !empty($feedContent)):
   $feedXml = @simplexml_load_string($feedContent);
   //if($feedXml): 
   	?>
   <h3>Del Blog pada ...</h3>
   <ul>
     <?php foreach($feedXml->channel->item as $item):
	     $pod = $item->enclosure['url'];
	     $apod = explode("?", $pod);
	     if( count($apod)>1 ){
	     	$arch = $apod[0];
	     } else {
	     	$arch = $pod;
	     }

	     $spod = explode("/", $arch);
	    $archloc = $spod[count($spod)-1];

     	$localFile = $dir . $subDir . $archloc;

     	if (!file_exists($localFile)) {
     		if (!file_exists($dir . $subDir)) {
	     		if(!mkdir($dir . $subDir,0777,true)){
	     			echo "No se puede crear directorio."; exit();
	     		}
	     	}
	     	$txt='';
	     	//if ($flagSave) {
				if ( copy($arch, $localFile) ) {
				    $txt = "Copy success";
				    if (!file_exists($localFile)) {
						copy($arch, $localFile);
				    }
				    $totalNuevos++;
				}else{
				    $txt = "Copy failed";
				}
			//}
     	 	echo "<li>" . $txt . ": ".$localFile ."</li>";
     	}

      endforeach;
      ?>
   </ul>
   <?php //endif; ?>
 <?php endif; ?>
 <?php 
?>
<h3>FIN DE PROCESO</h3>
</body>
</html>