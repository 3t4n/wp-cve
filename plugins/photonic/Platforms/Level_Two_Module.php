<?php
namespace Photonic_Plugin\Platforms;

use Photonic_Plugin\Components\Pagination;

require_once PHOTONIC_PATH . '/Components/Album.php';
require_once PHOTONIC_PATH . '/Components/Album_List.php';
require_once PHOTONIC_PATH . '/Components/Pagination.php';

interface Level_Two_Module {
	public function build_level_2_objects($objects_or_response, array $short_code, array $filter_list = [], array &$options = [], Pagination &$pagination = null): array;
}
