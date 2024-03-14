<?php
/*  
 * Security Antivirus Firewall (wpTools S.A.F.)
 * http://wptools.co/wordpress-security-antivirus-firewall
 * Version:           	2.3.5
 * Build:             	77229
 * Author:            	WpTools
 * Author URI:        	http://wptools.co
 * License:           	License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * Date:              	Sat, 01 Dec 2018 19:09:28 GMT
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ) exit;


class wptsafExtensionSystemLogWidget extends wptsafAbstractExtensionWidget{

	public function content(){
		$view = new wptsafView();
		$rows = $this->extension->getLog()->getRows(10);

		// show only first line of message
		foreach ($rows as $i => $row) {
			$lines = explode("\n", $row['message']);
			$rows[$i]['message'] = reset($lines);

			$rows[$i]['type'] = "<span class='label label-{$row['type']}'>" . __( wpToolsSAFHelperClass::getTypeLabel( $row['type'] ),  'wptsaf_security') . "</span>";
		}

		return $view->content(
			$this->extension->getExtensionDir() . 'template/widget.php',
			array(
				'title' => $this->extension->getTitle(),
				'description' => $this->extension->getDescription(),
				'rowHeader' => array(
					'date_gmt' 		=> __('Date', 'wptsaf_security'),
					'extension' 	=> __('Module', 'wptsaf_security'),
					'message' 		=> __('Comment', 'wptsaf_security'),
					'type' 			=> __('Details', 'wptsaf_security')
				),
				'rows' => $rows
			)
		);
	}
}
