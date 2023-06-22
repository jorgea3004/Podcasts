<?php
function readXmlFeedPodcasts($dir,$subDir,$feedUrl,$dirCopy,$titulo,$flagFileName){
	$html = '';
	$feedContent = conectByCurl($feedUrl);
	if($feedContent && !empty($feedContent)):
	    $feedXml = @simplexml_load_string($feedContent);

	    $html .= '<h3>Del Blog '.$titulo.'</h3><ol>';
	    foreach($feedXml->channel->item as $item):
			$dateComplete = strtotime($item->pubDate);
			$fecha1 = date('d-m-Y H:i:s',$dateComplete);
			$fecha2 = date('d-m-Y H:i:s');
			$fecha1a = explode(" ", $fecha1);
			$fecha2a = explode(" ", $fecha2);
			$dias = resta_fechas($fecha1a[0],$fecha2a[0]);
			if($dias > -91){
			    $pod = $item->enclosure['url'];
	   			$fmp3 = limpiaAcentos(strtolower($item->title));
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
			    $podext = $item->enclosure['type'];
			    if ($podext=='audio/x-m4a') {
			    	$ext = 'm4a';
			    } else {
			    	$ext = 'mp3';
			    }

			    $spod = explode("/", $arch);
			    $archloc = $spod[count($spod)-1];

		     	$localFile = $dir . $subDir . $fmp3.".".$ext;

		     	//echo $arch . " -> " . $localFile . "<br>";

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
			}
		endforeach;
		$html .='</ol>';
	endif; 
	return $html;
}
?>