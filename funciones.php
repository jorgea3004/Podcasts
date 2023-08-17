<?php
function limpiaAcentos($cadena){
	$cadena = str_replace('_', '_', $cadena);
	$cadena = str_replace(' ', '_', $cadena);
	$cadena = str_replace('á', 'a', $cadena);
	$cadena = str_replace('é', 'e', $cadena);
	$cadena = str_replace('í', 'i', $cadena);
	$cadena = str_replace('ó', 'o', $cadena);
	$cadena = str_replace('ú', 'u', $cadena);
	$cadena = str_replace('ñ', 'n', $cadena);
	$cadena = str_replace('Á', 'A', $cadena);
	$cadena = str_replace('É', 'E', $cadena);
	$cadena = str_replace('Í', 'I', $cadena);
	$cadena = str_replace('Ó', 'O', $cadena);
	$cadena = str_replace('Ú', 'U', $cadena);
	$cadena = str_replace('Ñ', 'N', $cadena);
	$cadena = str_replace(':', '', $cadena);
	$cadena = str_replace(',', '', $cadena);
	$cadena = str_replace("'", '-', $cadena);
	$cadena = str_replace('"', '', $cadena);
	$cadena = str_replace('*', '', $cadena);
	$cadena = str_replace('+', '', $cadena);
	$cadena = str_replace('~', '-', $cadena);
	$cadena = str_replace('#', '', $cadena);
	$cadena = str_replace('!', '', $cadena);
	$cadena = str_replace('¡', '', $cadena);
	$cadena = str_replace('¿', '', $cadena);
	$cadena = str_replace('?', '', $cadena);
	$cadena = str_replace('·', '-', $cadena);
	$cadena = str_replace('_|_', '-', $cadena);
	return $cadena;
}
?>