<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

 if ( ! defined( 'ABSPATH' ) ) { exit; } if ( class_exists( 'Debug_Bar_Panel' ) ) { class Freemius_Debug_Bar_Panel extends Debug_Bar_Panel { public function init() { $this->title( 'Freemius' ); } public static function requests_count() { if ( class_exists( 'Freemius_Api_WordPress' ) ) { $logger = Freemius_Api_WordPress::GetLogger(); } else { $logger = array(); } return number_format( count( $logger ) ); } public static function total_time() { if ( class_exists( 'Freemius_Api_WordPress' ) ) { $logger = Freemius_Api_WordPress::GetLogger(); } else { $logger = array(); } $total_time = .0; foreach ( $logger as $l ) { $total_time += $l['total']; } return number_format( 100 * $total_time, 2 ) . ' ' . fs_text_x_inline( 'ms', 'milliseconds' ); } public function render() { ?>
				<div id='debug-bar-php'>
					<?php fs_require_template( '/debug/api-calls.php' ) ?>
					<br>
					<?php fs_require_template( '/debug/scheduled-crons.php' ) ?>
					<br>
					<?php fs_require_template( '/debug/plugins-themes-sync.php' ) ?>
					<br>
					<?php fs_require_template( '/debug/logger.php' ) ?>
				</div>
			<?php
 } } }