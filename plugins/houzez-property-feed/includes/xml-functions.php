<?php

// https://stackoverflow.com/a/54661392/762994
function get_all_node_names( $node, $names, $parents = array() )
{
    $children = false;
    $nodes_names = array();

    foreach ( $names as $name )
    {
        $nodes_names[] = ( !empty($parents) ? '/' . implode("/", $parents) : '' ) . '/' . $node->getName();

        foreach ( $node->attributes() as $a => $b ) 
        {
            $nodes_names[] = ( !empty($parents) ? '/' . implode("/", $parents) : '' ) . '/' . $node->getName() . '[@' . $a . ']';
        }

        if ( count($node->children($name)) ) 
        {
            $children = true;

            $new_parents = $parents;
            $new_parents[] = trim($node->getName());

            foreach ( $node->children($name) as $i => $child ) 
            {
                $new_node_names = get_all_node_names($child, $names, $new_parents);
                if ( !empty($new_node_names) )
                {
                    $nodes_names = array_merge($nodes_names, $new_node_names);
                }
            }
        }
    }

    if ( !$children ) 
    {
        //$nodes_names[] = implode("/" . $parents) . '/' . $node->getName();
    }

    return $nodes_names;
}

function SimpleXML2ArrayWithCDATASupport( $xml )
{   
    $array = (array)$xml;

    if ( count($array) === 0 ) 
    {
        return (string)$xml;
    }

    foreach ( $array as $key => $value ) 
    {
        if ( !is_object($value) || strpos(get_class($value), 'SimpleXML') === false ) 
        {
            continue;
        }
        $array[$key] = SimpleXML2ArrayWithCDATASupport($value);
    }

    return $array;
}