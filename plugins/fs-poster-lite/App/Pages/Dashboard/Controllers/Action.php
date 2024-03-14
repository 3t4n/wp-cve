<?php

namespace FSPoster\App\Pages\Dashboard\Controllers;

use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Date;
use FSPoster\App\Providers\Helper;

class Action
{
	public function get_stats ()
	{
		$report3Data = DB::DB()->get_results( "SELECT driver , SUM(visit_count) AS c FROM " . DB::table( 'feeds' ) . " tb1 WHERE ( (node_type='account' AND (SELECT COUNT(0) FROM " . DB::table( 'accounts' ) . " tb2 WHERE tb2.id=tb1.node_id )>0) OR (node_type<>'account' AND (SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " tb2 WHERE tb2.id=tb1.node_id )) ) GROUP BY driver ORDER BY c DESC LIMIT 0,10", ARRAY_A );
		$report3     = [
			'data'   => [],
			'labels' => []
		];
		foreach ( $report3Data as $r3Data )
		{
			$report3[ 'data' ][]   = $r3Data[ 'c' ];
			$report3[ 'labels' ][] = esc_html( ucfirst( $r3Data[ 'driver' ] ) );
		}

		$report4Data = DB::DB()->get_results( "SELECT CONCAT(node_id,'_',node_type) AS node , SUM(visit_count) AS c FROM " . DB::table( 'feeds' ) . " tb1 WHERE ( (node_type='account' AND (SELECT COUNT(0) FROM " . DB::table( 'accounts' ) . " tb2 WHERE tb2.id=tb1.node_id)>0) OR (node_type<>'account' AND (SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " tb2 WHERE tb2.id=tb1.node_id)) ) GROUP BY CONCAT(node_id,'_',node_type) ORDER BY c DESC LIMIT 0,10", ARRAY_A );
		$report4     = [
			'data'   => [],
			'labels' => []
		];
		foreach ( $report4Data as $r4Data )
		{
			$node     = explode( '_', $r4Data[ 'node' ] );
			$nodeType = $node[ 1 ];
			$nodeId   = $node[ 0 ];

			$node_infoTable = $nodeType === 'account' ? 'accounts' : 'account_nodes';

			$node_info = DB::fetch( $node_infoTable, $nodeId );

			if ( ! $node_info )
			{
				continue;
			}

			if ( $r4Data[ 'c' ] == 0 )
			{
				continue;
			}

			$report4[ 'data' ][]   = $r4Data[ 'c' ];
			$report4[ 'labels' ][] = esc_html( ucfirst( $node_info[ 'driver' ] ) . ' / ' . Helper::cutText( $node_info[ 'name' ], 20 ) );
		}

		$monthStart      = Date::format( 'Y-m-01' );
		$monthEnd        = Date::dateSQL();
		$sharesThisMonth = DB::DB()->get_row( "SELECT COUNT(0) AS c FROM " . DB::table( 'feeds' ) . " tb1 WHERE is_sended='1' AND status='ok' AND CAST(send_time AS DATE) BETWEEN '$monthStart' AND '$monthEnd' AND ( (node_type='account' AND (SELECT COUNT(0) FROM " . DB::table( 'accounts' ) . " tb2 WHERE tb2.id=tb1.node_id )>0) OR (node_type<>'account' AND (SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " tb2 WHERE tb2.id=tb1.node_id )) )", ARRAY_A );

		$hitsThisMonth = DB::DB()->get_row( "SELECT SUM(visit_count) AS c FROM " . DB::table( 'feeds' ) . " tb1 WHERE is_sended='1' AND status='ok' AND CAST(send_time AS DATE) BETWEEN '$monthStart' AND '$monthEnd' AND ( (node_type='account' AND (SELECT COUNT(0) FROM " . DB::table( 'accounts' ) . " tb2 WHERE tb2.id=tb1.node_id)>0) OR (node_type<>'account' AND (SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " tb2 WHERE tb2.id=tb1.node_id)) )", ARRAY_A );

		$accounts = DB::DB()->get_row( "SELECT COUNT(0) AS c FROM " . DB::table( 'accounts' ), ARRAY_A );

		$nodes = DB::DB()->get_row( "SELECT COUNT(0) AS c FROM " . DB::table( 'account_nodes' ), ARRAY_A );

		$hitsThisMonthSchedule = DB::DB()->get_row( "SELECT SUM(visit_count) AS c FROM " . DB::table( 'feeds' ) . " tb1 WHERE is_sended='1' AND status='ok' AND CAST(send_time AS DATE) BETWEEN '$monthStart' AND '$monthEnd' AND ( (node_type='account' AND (SELECT COUNT(0) FROM " . DB::table( 'accounts' ) . " tb2 WHERE tb2.id=tb1.node_id)>0) OR (node_type<>'account' AND (SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " tb2 WHERE tb2.id=tb1.node_id)) )", ARRAY_A );

		return [
			'sharesThisMonth'       => $sharesThisMonth,
			'hitsThisMonth'         => $hitsThisMonth,
			'accounts'              => $accounts,
			'nodes'                 => $nodes,
			'hitsThisMonthSchedule' => $hitsThisMonthSchedule,
			'report3'               => $report3,
			'report4'               => $report4
		];
	}
}
