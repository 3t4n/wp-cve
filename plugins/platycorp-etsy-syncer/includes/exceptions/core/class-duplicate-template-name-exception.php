<?php
namespace platy\etsy;

class DuplicateTemplateNameException extends EtsySyncerException
{
    function __construct($name){
        parent::__construct("Duplicate tempalte name - $name");
    }
}