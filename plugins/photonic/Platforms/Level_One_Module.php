<?php
namespace Photonic_Plugin\Platforms;

require_once PHOTONIC_PATH . '/Components/Photo.php';
require_once PHOTONIC_PATH . '/Components/Photo_List.php';
require_once PHOTONIC_PATH . '/Components/Single_Photo.php';

interface Level_One_Module {
	public function build_level_1_objects($response, array $short_code, $module_parameters = [], $options = []): array;
}
