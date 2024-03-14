<?php

namespace FRFreeVendor\WPDesk\Library\Marketing\Boxes\Abstracts;

interface BoxInterface
{
    public function get_title() : string;
    public function get_slug() : string;
    public function get_type() : string;
    public function get_description() : string;
    /**
     * @return array
     */
    public function get_links() : array;
    /**
     * @return mixed
     */
    public function get_field(string $slug);
    public function get_row_open() : bool;
    public function get_row_close() : bool;
    /**
     * @return array
     */
    public function get_button() : array;
    public function render() : string;
}
