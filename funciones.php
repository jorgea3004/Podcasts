<?php
function limpiaAcentos($cadena){
	$fmp3 = str_replace('_', '', $cadena);
	$fmp3 = str_replace(' ', '_', $fmp3);
	$fmp3 = str_replace('á', 'a', $fmp3);
	$fmp3 = str_replace('é', 'e', $fmp3);
	$fmp3 = str_replace('í', 'i', $fmp3);
	$fmp3 = str_replace('ó', 'o', $fmp3);
	$fmp3 = str_replace('ú', 'u', $fmp3);
	$fmp3 = str_replace('ñ', 'n', $fmp3);
	$fmp3 = str_replace('Á', 'A', $fmp3);
	$fmp3 = str_replace('É', 'E', $fmp3);
	$fmp3 = str_replace('Í', 'I', $fmp3);
	$fmp3 = str_replace('Ó', 'O', $fmp3);
	$fmp3 = str_replace('Ú', 'U', $fmp3);
	$fmp3 = str_replace('Ñ', 'N', $fmp3);
	$fmp3 = str_replace(':', '', $fmp3);
	$fmp3 = str_replace(',', '', $fmp3);
	$fmp3 = str_replace("'", '-', $fmp3);
	$fmp3 = str_replace('"', '', $fmp3);
	$fmp3 = str_replace('*', '', $fmp3);
	$fmp3 = str_replace('+', '', $fmp3);
	$fmp3 = str_replace('~', '-', $fmp3);
	$fmp3 = str_replace('#', '', $fmp3);
	$fmp3 = str_replace('!', '', $fmp3);
	$fmp3 = str_replace('¡', '', $fmp3);
	$fmp3 = str_replace('¿', '', $fmp3);
	$fmp3 = str_replace('?', '', $fmp3);
	$fmp3 = str_replace('·', '-', $fmp3);
	$fmp3 = str_replace('_|_', '-', $fmp3);
	return $fmp3;
}
?>