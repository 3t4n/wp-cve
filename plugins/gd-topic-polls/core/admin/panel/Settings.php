<?php

namespace Dev4Press\Plugin\GDPOL\Admin\Panel;

use Dev4Press\v43\Core\Quick\BP;
use Dev4Press\v43\Core\UI\Admin\PanelSettings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Settings extends PanelSettings {
	public $settings_class = '\\Dev4Press\\Plugin\\GDPOL\\Admin\\Settings';

	public function __construct( $admin ) {
		parent::__construct( $admin );

		$this->subpanels = $this->subpanels + array(
				'basic'       => array(
					'title'      => __( 'Basics', 'gd-topic-polls' ),
					'icon'       => 'ui-tasks',
					'break'      => __( 'Standard', 'gd-topic-polls' ),
					'break-icon' => 'ui-chart-bar',
					'info'       => __( 'Control who can create polls and which forums are available for polls.', 'gd-topic-polls' ),
				),
				'fields'      => array(
					'title' => __( 'Poll Fields', 'gd-topic-polls' ),
					'icon'  => 'ui-sliders-base-hor',
					'info'  => __( 'Control over the fields available when creating a poll and poll defaults.', 'gd-topic-polls' ),
				),
				'display'     => array(
					'title' => __( 'Poll Display', 'gd-topic-polls' ),
					'icon'  => 'ui-paint-brush',
					'info'  => __( 'Control over the display of poll results, poll users and calculations.', 'gd-topic-polls' ),
				),
				'integration' => array(
					'title'      => __( 'Integration', 'gd-topic-polls' ),
					'icon'       => 'ui-code',
					'break'      => __( 'bbPress', 'gd-topic-polls' ),
					'break-icon' => 'logo-bbpress',
					'info'       => __( 'Control how the plugin is integrated in bbPress topics and forums.', 'gd-topic-polls' ),
				),
				'labels'      => array(
					'title'      => __( 'Objects Labels', 'gd-topic-polls' ),
					'icon'       => 'ui-book-spells',
					'break'      => __( 'Advanced', 'gd-topic-polls' ),
					'break-icon' => 'ui-magic',
					'info'       => __( 'These settings control labels used by objects added by the plugin.', 'gd-topic-polls' ),
				),
			);
	}
}
