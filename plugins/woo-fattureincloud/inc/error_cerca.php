<?php


/*
*
* cerca il termine cod_iva nella risposta di errore
*
*/
/*  
$term = 'cod_iva';
    $ser = function ( $val ) use ( $term ) {
        return ( stripos($val, $term) !== false ? true : false );
    };

    $valore_iva = array_keys(array_filter($fattureincloud_result, $ser));
*/
    //print_r($keys);
/*
*
*cerca paese_iso nell'errore
*
*
*/
/*
    $term = 'paese_iso';
    $ser = function ( $val ) use ( $term ) {

        return ( stripos($val, $term) !== false ? true : false );

    };

    $valore_paese_iso = array_keys(array_filter($fattureincloud_result, $ser));
*/
/*
 *
 * cerca api_key e api_uid nell'errore
 *
 *
 
    $term = 'api_key';
    $ser = function ( $val ) use ( $term ) {
        return ( stripos($val, $term) !== false ? true : false );
    };
    $valore_api_uid = array_keys(array_filter($response_value, $ser));

    */