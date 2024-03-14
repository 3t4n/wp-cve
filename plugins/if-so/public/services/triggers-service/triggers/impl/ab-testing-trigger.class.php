<?php
namespace IfSo\PublicFace\Services\TriggersService\Triggers;
require_once( plugin_dir_path ( __DIR__ ) . 'trigger-base.class.php');
class ABTestingTrigger extends TriggerBase {
	public function __construct() {
		parent::__construct('AB-Testing');
	}
	protected function is_valid($trigger_data) {
		$rule = $trigger_data->get_rule();
		if (empty($rule['ab-testing-sessions']))
			return false;
		$sessions_bound = $rule['ab-testing-sessions'];
		if ($sessions_bound == 'Custom' && 
			empty($rule['ab-testing-custom-no-sessions']))
			return false;
		if (empty($rule['AB-Testing']))
			return false;
		if (!$this->is_views_count_valid($rule))
			return false;
		return true;
	}
	private function is_views_count_valid($rule) {
		$sessions_bound = $rule['ab-testing-sessions'];
		if ($sessions_bound == 'Custom') {
			$sessions_bound = $rule['ab-testing-custom-no-sessions'];
		}
		$views_count = $rule['number_of_views'];
		if ($sessions_bound != 'Unlimited' && 
			$views_count >= (int)$sessions_bound) 
			return false;
		else
			return true;
	}
	public function handle($trigger_data) {
		$trigger_id = $trigger_data->get_trigger_id();
		$rule = $trigger_data->get_rule();
		$version_index = $trigger_data->get_version_index();
		$data_rules = &$trigger_data->get_data_rules();
		$content = $trigger_data->get_content();
		$views_count = $rule['number_of_views'];
		$factors = array("20%" => 5,
						 "25%" => 4,
						 "33%" => 3,
						 "50%" => 2,
						 "75%" => 4,
						 "100%" => 1);
		$perc = $rule['AB-Testing'];
		$factor = $factors[$perc];
		$fact_remainder = $views_count % $factor;
		$views_count += 1;
		$data_rules[$version_index]['number_of_views'] = $views_count;
		$data_rules_cleaned = 
			str_replace("\\", "\\\\\\", json_encode($data_rules));
		update_post_meta( $trigger_id, 'ifso_trigger_rules', $data_rules_cleaned);
        if ($perc == "20%" && $fact_remainder == 0) {
            return $content;
        }if ($perc == "25%" && $fact_remainder == 0) {
			return $content;
		} else if ($perc == "33%" && $fact_remainder == 0) {
			return $content;
		} else if ($perc == "50%" && $fact_remainder == 0) {
			return $content;
		} else if ($perc == "75%" &&
				   in_array($fact_remainder, array(0, 1, 2))) {
			return $content;
		} else if ($perc == "100%") {
			return $content;
		}
	
		return false;
	}
}