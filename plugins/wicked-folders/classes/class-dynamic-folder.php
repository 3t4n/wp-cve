<?php

namespace Wicked_Folders;

/**
 * Represents a dynamic folder.
 */
abstract class Dynamic_Folder extends Folder {

    public $movable = false;
    
    public $editable = false;

    public $assignable = false;

    public $deletable = false;
    
    public $is_dynamic = true;
    
    public function __construct( $args = array() ) {
        parent::__construct( $args );
    }

    public abstract function pre_get_posts( $query );

}
