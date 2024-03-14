<?php

namespace Dev4Press\Plugin\GDPOL\Admin\Panel;

use Dev4Press\v43\Core\UI\Admin\PanelDashboard;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Dashboard extends PanelDashboard {
	public function __construct( $admin ) {
		parent::__construct( $admin );

		$this->sidebar_links['plugin'] = array(
			'polls' => array(
				'icon'  => $this->a()->menu_items['polls']['icon'],
				'class' => 'button-primary',
				'url'   => $this->a()->panel_url( 'polls' ),
				'label' => __( 'Polls', 'gd-topic-polls' ),
			),
			'votes' => array(
				'icon'  => $this->a()->menu_items['votes']['icon'],
				'class' => 'button-primary',
				'url'   => $this->a()->panel_url( 'votes' ),
				'label' => __( 'Votes', 'gd-topic-polls' ),
			),
		);
	}
}
