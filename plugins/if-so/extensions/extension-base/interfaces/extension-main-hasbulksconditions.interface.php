<?php
/*Located here and not in the bulks extension for now, to avoid errors on systems that don't have that extension*/
namespace IfSo\Addons\Bulks\Extension;

interface WithBulksConditions{
    public function bulks_data_rules_model_extension();
    public function filter_bulks_data_rules_ui_model();
    public function extend_bulktriggers_list();
}