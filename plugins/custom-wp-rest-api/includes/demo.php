<?php
$array = array('base1','base2');
  foreach ($array as $key => $value) {
      echo 'function '.$value.'1(){ return "ff"; }';

      echo 'function '.$value.'1_permission1(){ return true;  }';
      
    } ?>