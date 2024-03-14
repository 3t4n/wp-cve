<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

/**
 * @author Neil.zhou
 * @author jssor
 */
class WjsslDocumentNodeCollection extends ArrayObject implements iWjsslRuntimeNode
{
    public function toJson()
    {
        $json = array();

        $iterator = $this->getIterator();
        while ($iterator->valid()) {
            $obj = $iterator->current();
            if ($obj instanceof iWjsslRuntimeNode) {
                $json[] = $obj->toJson();
            }
            $iterator->next();
        }
        return "[" . implode(",", $json) . "]";
    }

    public function toArray()
    {
        return $this;
    }
}
