<?php

namespace Smashballoon\Customizer;

interface PreviewProvider
{
    public function render($attr, $settings);
}
