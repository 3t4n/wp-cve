<?php
/**
 * PHP-Scoper Configuration
 *
 * @package   Prokerala\WP\Astrology
 * @copyright 2022 Ennexa Technologies Private Limited
 * @license   https://www.gnu.org/licenses/gpl-2.0.en.html GPLV2
 * @link      https://api.prokerala.com
 */

use Rector\Set\ValueObject\DowngradeLevelSetList;
use Rector\Config\RectorConfig;

return function ( RectorConfig $rector_config ): void {
	$rector_config->import( DowngradeLevelSetList::DOWN_TO_PHP_72 );
};
