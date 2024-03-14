<?php

	$aokolaoa = '';
	$numxers = 10;
	$kenuriokolona = array();
	$kenuriokolona = array('2', '3', '4', '5', '6', '7', '8', '9', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'V', 'W', 'X', 'Y', 'Z', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j', 'k', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z');
	for ( $ibide = 0; $ibide < $numxers; $ibide++ )
	{
		shuffle($kenuriokolona);
		$aokolaoa .= $kenuriokolona[mt_rand(0, count($kenuriokolona) - 1)];
	}

?>
