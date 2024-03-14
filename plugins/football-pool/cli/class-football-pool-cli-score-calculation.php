<?php
/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2023 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/COPYING
 *
 * This file is part of Football pool.
 *
 * Football pool is free software: you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * Football pool is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 * PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with Football pool.
 * If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * Executes functions in the Football Pool plugin.
 */

WP_CLI::add_command( 'football-pool calc', 'Football_Pool_CLI_Command' );

class Football_Pool_CLI_Command {
	/**
	 * Calculates the user ranking.
	 *
	 * ## OPTIONS
	 *
	 * [--force-calculation]
	 * : To force a calculation (see help page in plugin for extra info).
	 *
	 * [--normal-calculation]
	 * : To force the normal method of a calculation (regardless of plugin setting).
	 *
	 * [--simple-calculation]
	 * : To force the simple method of a calculation (regardless of plugin setting, see help page in plugin for extra info).
	 *
	 * [--output-time-only]
	 * : Only output the time the calculation took to complete (H:m:i).
	 *   Will only output time when the calculation ends without warning or error.
	 *
	 * ## EXAMPLES
	 *
	 *     # Perform a standard calculation.
	 *     $ wp football-pool calc
	 *     Calculating scores  100% [===============================================================] 0:58 / 0:51
	 *     Success: Calculation completed. Thanks for your patience.
	 *
	 *     # Force a calculation.
	 *     $ wp football-pool calc --force-calculation
	 *     Calculating scores  100% [===============================================================] 1:05 / 0:56
	 *     Success: Calculation completed. Thanks for your patience.
	 *
	 *     # Perform a standard calculation and only output the time it took for the command to complete.
	 *     $ wp football-pool calculate --output-time-only
	 *     00:02:39
	 *
	 * @alias calculate
	 */
	public function __invoke( $args, $assoc_args ) {
		$time_start = microtime( true );

		$completed = 0;
		$error = false;
		$message = '';
		
		$output_time_only = ( isset( $assoc_args['output-time-only'] ) 
								&& $assoc_args['output-time-only'] === true );
		$force_calculation = ( isset( $assoc_args['force-calculation'] ) 
								&& $assoc_args['force-calculation'] === true ) ? 1 : 0;
		$normal_calculation = ( isset( $assoc_args['normal-calculation'] ) 
								&& $assoc_args['normal-calculation'] === true ) ? 1 : 0;
		$simple_calculation = ( isset( $assoc_args['simple-calculation'] ) 
								&& $assoc_args['simple-calculation'] === true ) ? 1 : 0;
								
		if ( $normal_calculation ) Football_Pool_Utils::set_fp_option( 'simple_calculation_method', 0 );
		if ( $simple_calculation ) Football_Pool_Utils::set_fp_option( 'simple_calculation_method', 1 );
		
		$calc_args = array(
			'force_calculation' => $force_calculation,
			'iteration' => 0,
		);
		
		// first run of the calculation method to kick off the calculation
		$calc_args = Football_Pool_Admin_Score_Calculation::process( true, $calc_args );
		extract( $calc_args, EXTR_OVERWRITE );
		
		if ( ! $output_time_only ) {
			/** @var integer $total_iterations */
			$progress = \WP_CLI\Utils\make_progress_bar( 'Calculating scores', $total_iterations );
			// already tick one because we did the prepare step
			$progress->tick();
		}

		// loop through rest of the calculation steps
		while ( $completed !== 1 && $error === false ) {
			$calc_args = Football_Pool_Admin_Score_Calculation::process( true, $calc_args );
			extract( $calc_args, EXTR_OVERWRITE );
			if ( ! $output_time_only ) $progress->tick();
		}

		// on finish
		if ( $completed === 1 && ! $output_time_only ) $progress->finish();

		if ( $error !== false ) {
			WP_CLI::error( $message );
		} else {
			if ( isset( $message_type ) && $message_type === 'warning' ) {
				WP_CLI::warning( $message );
			} else {
				if ( $output_time_only ) {
					$time_end = microtime( true );
					$time = $time_end - $time_start;
					$hours = floor( $time / HOUR_IN_SECONDS );
					$time -= $hours * HOUR_IN_SECONDS;
					$minutes = floor( $time / MINUTE_IN_SECONDS );
					$time -= $minutes * MINUTE_IN_SECONDS;
					$seconds = floor( $time );
					WP_CLI::log( sprintf( "%02d:%02d:%02d", $hours, $minutes, $seconds ) );
				} else {
					WP_CLI::success( $message );
				}
			}
		}
	}
}
