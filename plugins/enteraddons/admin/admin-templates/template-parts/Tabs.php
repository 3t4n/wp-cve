<?php
namespace Enteraddons\Admin;
/**
 * Enteraddons admin section
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

trait Tabs{

	private $getTabs = '';

	public function admin_tabs() {

		if( !empty( $this->getTabs ) ):
		?>
		<div class="tab-btn">
		    <div class="container">
		        <ul class="list-unstyled">
		        	<?php
		        	foreach( $this->getTabs as $key => $tab ) {

		        		$active = !empty( $tab['is_active'] )  ? 'active' : '';

		        		if( $tab['show_in'] == 'ALL' || ( $tab['show_in'] == ENTERADDONS_VERSION_TYPE && !\Enteraddons\Classes\Helper::is_pro_active() )  ) {

		        			echo '<li data-tab-select="'.esc_attr( $key ).'" class="'.esc_attr( $active ).'"><i class="'.esc_html( $tab['icon'] ).'"></i> '.esc_html( $tab['name'] ).'</li>';
		        		}

		        	}
		            ?>
		        </ul>
		    </div>
		</div>
		<?php
		endif;
	}

	public function tabs_items( array $tabs ) {

		$default = array(
				'general' 	 => array(
					'name' => esc_html__( 'General', 'enteraddons' ),
					'icon' => 'fa fa-home',
					'is_active' => true,
					'show_in'	=> 'ALL'
				)
			);

		$tabs = wp_parse_args( $tabs, $default );

		$this->getTabs =  $tabs;
	}
}
?>
