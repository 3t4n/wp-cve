<?php

namespace Photonic_Plugin\Components;

use Photonic_Plugin\Core\Photonic;
use Photonic_Plugin\Layouts\Core_Layout;
use Photonic_Plugin\Platforms\Base;

class Stack_Trace implements Printable {
	public $events = [];

	public function add_to_first_open_event($new_event) {
		$found = false;
		foreach ($this->events as $id => $event) {
			if (isset($event['start']) && !isset($event['end'])) {
				// Ongoing event. Need to add to this.
				$found = true;
				if (!isset($event['children'])) {
					$children = new Stack_Trace();
				}
				else {
					$children = $event['children'];
				}
				$children->add_to_first_open_event($new_event);
				$event['children'] = $children;
				$this->events[$id] = $event;
			}
			if ($found) {
				break;
			}
		}
		if (!$found) {
			$this->events[] = [
				'event' => $new_event,
				'start' => microtime(true),
			];
		}
	}

	public function pop_from_first_open_event(): bool {
		$found = false;
		foreach ($this->events as $id => $event) {
			if (isset($event['start']) && !isset($event['end'])) {
				// Ongoing event. Need to pop this or its open child
				$found = true;
				$found_child = false;
				if (isset($event['children'])) {
					/** @var Stack_Trace $children */
					$children = $event['children'];
					$found_child = $children->pop_from_first_open_event();
					$event['children'] = $children;
				}
				if (!$found_child) {
					$event['end'] = microtime(true);
					$event['time'] = $event['end'] - $event['start'];
				}
				$this->events[$id] = $event;
			}
			if ($found) {
				break;
			}
		}
		return $found;
	}

	private function get_nested_element($indent = "\t"): string {
		$ret = '';
		foreach ($this->events as $trace) {
			$trace_items = [];
			foreach ($trace as $key => $trace_item) {
				if ('children' !== $key) {
					$trace_items[] = strtoupper(substr($key, 0, 1)) . substr($key, 1) . ': ' . $trace_item;
				}
			}
			$ret .= $indent . implode(', ', $trace_items) . "\n";
			if (!empty($trace['children'])) {
				/** @var Stack_Trace $children */
				$children = $trace['children'];
				$ret .= $children->get_nested_element($indent . "\t");
			}
		}
		return $ret;
	}


	public function html(Base $module, Core_Layout $layout = null, $print = false): string {
		$ret = "<!--\n";
		$ret .= "Stats for Platform: {$module->provider}, Gallery: {$module->gallery_index}, Library: {$layout->get_library()}\n";
		$ret .= $this->get_nested_element();
		$ret .= "-->\n";

		if ($print) {
			echo wp_kses($ret, Photonic::$safe_tags);
		}

		return wp_kses($ret, Photonic::$safe_tags);
	}
}
