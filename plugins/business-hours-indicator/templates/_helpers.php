<?php
if(! function_exists(__NAMESPACE__ . '\render_normal_hours'))
{
	function render_normal_hours( array $entries, $main_html, $show_current, $inline_separator = '' ) {
		$first_item = true;
		foreach ( $entries as $entry ) {
			if ( ! $first_item && $inline_separator != '' ) {
				echo $inline_separator;
			}
			echo sprintf(
				$main_html,
				$entry->current && $show_current ? 'class="mbhi-is-current"' : '',
				$entry->range,
				$entry->hours
			);
			$first_item = false;
		}
	}
}

if(! function_exists(__NAMESPACE__ . '\render_special_hours'))
{
	function render_special_hours( $specials, $main_html, $show_current, $inline_separator = '' ) {
		foreach ( $specials as $entry ) {
			if ( $inline_separator != '' ) {
				echo $inline_separator;
			}
			echo sprintf(
				$main_html,
				$entry->current && $show_current ? ' mbhi-is-current' : '',
				$entry->range,
				$entry->hours
			);

		}
	}
}

if(! function_exists(__NAMESPACE__ . '\render_vacations'))
{
	function render_vacations( $vacations, $main_html, $show_current, $slug, $inline_separator = '' ) {
		foreach ( $vacations as $entry ) {
			if ( $inline_separator != '' ) {
				echo $inline_separator;
			}
			echo sprintf(
				$main_html,
				$entry->current && $show_current ? ' mbhi-is-current' : '',
				$entry->range,
				__( 'Closed', 'business-hours-indicator' )
			);
		}
	}
}
