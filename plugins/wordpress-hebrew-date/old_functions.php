<?php

// old functions before class in version 2.0

function hebdate($date){
    global $hebdate;
    return $hebdate->hebdate($date);
}

function the_hebdate($date){
    global $hebdate;
    return $hebdate->the_hebdate($date);
}

function format($str, $heb, $greg){
    global $hebdate;
    return $hebdate->format($str, $heb, $greg);
}

function hebdate_format(){
    global $hebdate;
    return $hebdate->hebdate_format();
}

function comment_hebdate($date){
    global $hebdate;
    return $hebdate->comment_hebdate($date); 
}

function today_hebdate(){
    global $hebdate;
    $hebdate->today_hebdate(); 
}

function return_today_hebdate(){
    global $hebdate;
    return $hebdate->return_today_hebdate(); 
}

function hasLeapYear($juldate){
    global $hebdate;
    return $hebdate->hasLeapYear($juldate); 
}

function sunset($date){
    global $hebdate;
    return $hebdate->sunset($date); 
}