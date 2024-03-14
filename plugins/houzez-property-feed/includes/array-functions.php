<?php

// https://www.php.net/manual/en/function.array-multisort.php#91638
function houzez_property_feed_array_msort($array, $cols)
{
    $colarr = array();
    foreach ($cols as $col => $order) {
        $colarr[$col] = array();
        foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
    }
    $eval = 'array_multisort(';
    foreach ($cols as $col => $order) {
        $eval .= '$colarr[\''.$col.'\'],'.$order.',';
    }
    $eval = substr($eval,0,-1).');';
    eval($eval);
    $ret = array();
    foreach ($colarr as $col => $arr) {
        foreach ($arr as $k => $v) {
            $k = substr($k,1);
            if (!isset($ret[$k])) $ret[$k] = $array[$k];
            $ret[$k][$col] = $array[$k][$col];
        }
    }
    return $ret;
}

function check_array_for_matching_key( $array, $looking_for ) 
{
    if ( is_array($array) && !empty($array) )
    {
        foreach ( $array as $key => $value ) 
        {
            if ( !is_numeric($key) && $key == $looking_for )
            {
                return $value;
            }

            if ( is_array($value) && !empty($value) ) 
            {
                $value_to_check = check_array_for_matching_key( $value, $looking_for );
                if ( $value_to_check !== false )
                {
                    return $value_to_check;
                }
            }
        }
    }

    return false;
}

function hpf_clean( $var ) 
{
    if ( is_array( $var ) ) 
    {
        return array_map( 'hpf_clean', $var );
    }
    else
    {
        return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
    }
}