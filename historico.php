<?php
ini_set("memory_limit","100M");
ini_set("max_execution_time",36000);
function formatoFechaLang($mm){
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
	return $mes;
}

$axo=2015;
$base = '/jorge/';
$dir = $base.'Podcasts/Dispara, Margot, dispara/';
for ($m=1; $m <= 12 ; $m++) {
	$mest = formatoFechaLang($m);
	if(strlen($m)==1){$n='0'.$m;} else {$n=''.$m;}
	$img_path = $dir . $axo.$n."_". $mest ."/";
	for ($i=1; $i <= 31 ; $i++) { 
		if(strlen($i)==1){$j='0'.$i;} else {$j=''.$i;}
		$date = $axo.'-'.$n.'-'.$j;
		$dayofweek = date('w', strtotime($date));
		if ($dayofweek>0 && $dayofweek<6) {
			//echo $date . " => Entre Semana<br>";
			$a = $date.'-dispara.mp3';
			$url = 'http://www.disparamargotdispara.com/audio/'.$a;
	     	$localFile = $img_path . $a;
	     	echo $date . " => ".$localFile."<br>";
	     	if (!file_exists($localFile)) {
	     		if (!file_exists($img_path)) {
		     		if(!mkdir($img_path,0777,true)){
		     			echo "No se puede crear directorio."; exit();
		     		}
		     	}
		     	$txt='Ok';
					if ( copy($url, $localFile) ) {
					    $txt = "Copy success";
					}else{
					    $txt = "Copy failed";
					}
	     	 	echo "" . $txt . ": ".$localFile."<br>";
	     	} 
		}
	}
}
?>