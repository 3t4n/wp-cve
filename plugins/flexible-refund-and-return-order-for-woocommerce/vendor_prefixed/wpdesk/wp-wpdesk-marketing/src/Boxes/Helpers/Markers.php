<?php

/**
 * Placeholder helper for replace defined placeholders.
 *
 * @package WPDesk\Library\Marketing\Abstracts
 */
namespace FRFreeVendor\WPDesk\Library\Marketing\Boxes\Helpers;

/**
 * Placeholders parser.
 */
class Markers
{
    /**
     * @var array<string, string>
     */
    private $placeholders = [];
    public function __construct()
    {
        $this->add_placeholder('{siteurl}', \get_site_url() . '/');
    }
    public function add_placeholder(string $placeholder, string $value) : void
    {
        $this->placeholders[$placeholder] = $value;
    }
    /**
     * @deprecated 1.1.3 Never used outside this class.
     * @return array<string, string>
     */
    public function get_placeholders() : array
    {
        return $this->placeholders;
    }
    public function replace(string $string) : string
    {
        foreach ($this->placeholders as $placeholder => $value) {
            $string = \str_replace($placeholder, $value, $string);
        }
        return $string;
    }
}
