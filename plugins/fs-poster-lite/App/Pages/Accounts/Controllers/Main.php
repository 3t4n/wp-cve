<?php

namespace FSPoster\App\Pages\Accounts\Controllers;

use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;
use FSPoster\App\Providers\Request;

class Main
{
	private function load_assets ()
	{
		wp_register_script( 'fsp-accounts', Pages::asset( 'Accounts', 'js/fsp-accounts.js' ), [
			'jquery',
			'fsp'
		], NULL );
		wp_enqueue_script( 'fsp-accounts' );

		wp_enqueue_style( 'fsp-accounts', Pages::asset( 'Accounts', 'css/fsp-accounts.css' ), [ 'fsp-ui' ], NULL );
	}

	public function index ()
	{
		$this->load_assets();

		$filter             = Request::get( 'filter_by', '', [
			'all',
			'active',
			'inactive',
			'visible',
			'hidden'
		] );
		$fsp_accounts_count = Pages::action( 'Accounts', 'get_counts' );

		if ( ! empty( $filter ) )
		{
			Helper::setOption( 'accounts_default_filter_' . get_current_user_id(), $filter );
		}
		else
		{
			$filter = Helper::getOption( 'accounts_default_filter_' . get_current_user_id(), 'visible' );
		}

		$data = [
			'accounts_count' => $fsp_accounts_count,
			'filter'         => $filter
		];

		$activeTab = Request::get( 'tab', 'fb', 'string' );

		$button_text = esc_html__( 'ADD AN ACCOUNT', 'fs-poster' );

		$data[ 'button_text' ]   = $button_text;
		$data[ 'active_tab' ]    = $activeTab;
		$data[ 'show_accounts' ] = TRUE;

		Pages::view( 'Accounts', 'index', $data );

	}
}