<?php

namespace Hurrytimer\Placeholders;

abstract class Placeholder
{
    public abstract function get_value( $options = [] );
}