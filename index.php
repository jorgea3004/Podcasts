<?php
ini_set("memory_limit","100M");
ini_set("max_execution_time",7200);
require_once('generaPodcasts.php');
require_once('readXmlFeedPodcasts.php');
require_once('readItunesXmlFeedPodcasts.php');
require_once('funciones.php');
$flagSave = true;
$base = '/xampp/downloads/';
$dir = $base.'Podcasts/';
$dirpods = $base.'musica/';
$dirCopy = $base.'downloads/';
$totalNuevos = 0;
//if(!file_exists($dir)){
if(!file_exists($dir)){
	if(!mkdir($dir,0777,true))//Se crea la carpeta
	{
		echo "Error: Al crear directorio: ".$dir."<br>"; exit();
	}
}
if(!file_exists($dirCopy)){
	if(!mkdir($dirCopy,0777,true))//Se crea la carpeta de
	{
		echo "Error: Al crear directorio ".$dirCopy; exit();
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
$subDir = 'farandula021/';
$feedUrl = "https://feeds.acast.com/public/shows/farandula021";
//echo readItunesXmlFeedPodcasts($dir,$subDir,$feedUrl,$dirCopy,'Farandula21',1);

$subDir = 'Nerdcore-Podcast/';
$feedUrl = "https://media.rss.com/nerdcore-podcast/feed.xml";
//echo readXmlFeedPodcasts($dir,$subDir,$feedUrl,$dirCopy,'Nerdcore-Podcast',0);

$subDir = 'programar-es-mierda/';
$feedUrl = "https://www.ivoox.com/podcast-programar-es-mierda_fg_f1432444_filtro_1.xml";
echo readXmlFeedPodcasts($dir,$subDir,$feedUrl,$dirCopy,'programar-es-mierda',0);

$subDir = 'hermanos-de-leche/';
$feedUrl = "https://www.spreaker.com/show/5337194/episodes/feed";
//echo readXmlFeedPodcasts($dir,$subDir,$feedUrl,$dirCopy,'hermanos-de-leche',0);

$subDir = 'azcarate-al-oido/';
$feedUrl = "https://www.spreaker.com/show/4813631/episodes/feed";
//echo readXmlFeedPodcasts($dir,$subDir,$feedUrl,$dirCopy,'azcarate-al-oido',0);

$subDir = 'All-Ears-English/';
$feedUrl = "https://feeds.megaphone.fm/allearsenglish";
//echo readXmlFeedPodcasts($dir,$subDir,$feedUrl,$dirCopy,'allearsenglish',1);

//generaListaPodcasts($dir);
?>
<h3>FIN DE PROCESO</h3>
</body>
</html>