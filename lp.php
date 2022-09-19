<?php
//Funkcja filtrująca zmienne przekazywane przez GET, POST, REQUEST - zabezpiecza przed atakiem SQL Injection, usuwa znaczniki HTML
function filterData($data,$filter = array('strip_tags','stripslashes'))
{
	if (count($filter) > 0)
	{
		if (!is_array($data)) foreach($filter AS $filt) 
		{
			if(function_exists($filt)) $data = call_user_func($filt,$data);
			else $data = $data;
		}
		else foreach($filter AS $filt) 
		{
			if(function_exists($filt)) $data = array_map($filt,$data);
			else $data = $data;
		}
		return $data;
	}
	else return $data;
}

$lpno = filterData($_GET['lpno']);
$lpno = str_replace(" ","",$lpno);

$wzorzec_dhl = '~([0-9]){11}~imsU';
//25291129326
$wzorzec_xpress = '~([0-9]){12}~imsU';
//003205436285
$wzorzec_suus = '~([a-zA-Z]){2}+([a-zA-Z0-9]){11}~imsU';
//LKRJ220088701
$wzorzec_geis = '~([0-9]){13}~imsU';
//6215000886642
$wzorzec_dpd = '~([0-9]){13}+([a-zA-Z]){1}~imsU';
//1000500403095U
$wzorzec_pp = '~([0-9]){20}~imsU';
//00159007738167018474
$wzorzec_inpost = '~([0-9]){24}~imsU';
//661872008278400123376851
$wzorzec_terg = '~([a-zA-Z0-9]){32}~imsU';
//b18da0ed503e38f90c81fcc3cc2abc3d

if(preg_match($wzorzec_terg, $lpno)) $link = "https://www.mediaexpert.pl/lp,status_zamowienia?hash=".$lpno;
elseif(preg_match($wzorzec_inpost, $lpno)) $link = "https://inpost.pl/sledzenie-przesylek?number=".$lpno;
elseif(preg_match($wzorzec_pp, $lpno)) $link = "https://emonitoring.poczta-polska.pl/?numer=".$lpno;
elseif(preg_match($wzorzec_dpd, $lpno)) $link = "https://tracktrace.dpd.com.pl/parcelDetails?typ=1&p1=".$lpno;
elseif(preg_match($wzorzec_geis, $lpno)) $link = "https://www.geis.pl/pl/detail-of-cargo?packNumber=".$lpno;
elseif(preg_match($wzorzec_suus, $lpno)) $link = "https://wb.suus.com/druid.php?m=project&s=Tracking&key=list_przewozowy_nr%2C".$lpno;
elseif(preg_match($wzorzec_xpress, $lpno)) $link = "https://onekurier.pl/sledzenie-paczki?numer=".$lpno;
elseif(preg_match($wzorzec_dhl, $lpno)) $link = "https://sprawdz.dhl.com.pl/szukaj.aspx?m=0&sn=".$lpno;

echo $link;
//header('Location: '.$link);
?>