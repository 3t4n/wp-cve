<?php

declare(strict_types=1);

class Mailup_FormField
{
    /**
     * @var Name
     */
    public $id;

    public $name;

    public $required;

    public $type;

    public function __construct($args)
    {
        if (is_array($args)) {
            $this->id = $args['id'] ?? null;
            $this->name = trim(stripslashes($args['name']));
            $this->required = filter_var($args['required'], FILTER_VALIDATE_BOOLEAN);
            $this->type = $args['type'] ?? 'text';
        } else {
            $this->id = $args->id;
            $this->name = $args->name;
            $this->required = (bool) $args->required;
            $this->type = $args->type;
        }
    }
}
