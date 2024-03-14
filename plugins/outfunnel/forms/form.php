<?php
namespace Outfunnel\Forms;

interface Form
{
    public function is_form_plugin_active();
    public function get_all_forms();
    public function get_plugin_version();
}
