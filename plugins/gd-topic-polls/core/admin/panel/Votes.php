<?php

namespace Dev4Press\Plugin\GDPOL\Admin\Panel;

use Dev4Press\Plugin\GDPOL\Admin\Panel;
use Dev4Press\Plugin\GDPOL\Table\Votes as VotesTable;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Votes extends Panel {
	protected $table = true;
	protected $sidebar = false;
	protected $form = true;
	protected $form_multiform = false;
	protected $form_method = 'get';

	public function screen_options_show() {
		add_screen_option( 'per_page', array(
			'label'   => __( 'Rows', 'gd-topic-polls' ),
			'default' => 50,
			'option'  => 'gdpol_votes_rows_per_page',
		) );

		$this->get_table_object();
	}

	public function get_table_object() {
		if ( is_null( $this->table_object ) ) {
			$this->table_object = new VotesTable();
		}

		return $this->table_object;
	}
}
