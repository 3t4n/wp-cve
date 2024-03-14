<?php

namespace MABEL_BHI_LITE\Controllers
{
	use MABEL_BHI_LITE\Core\Config_Manager;
	use MABEL_BHI_LITE\Core\Linq\Enumerable;
	use MABEL_BHI_LITE\Core\Settings_Manager;
	use MABEL_BHI_LITE\Core\Shortcode;
	use MABEL_BHI_LITE\Models\IfOpenClosed_VM;
	use MABEL_BHI_LITE\Models\Indicator_VM;
	use MABEL_BHI_LITE\Models\List_VM;
	use MABEL_BHI_LITE\Models\List_VM_Entry;
	use MABEL_BHI_LITE\Models\Location;
	use MABEL_BHI_LITE\Models\Opening_Hours_Set;
	use MABEL_BHI_LITE\Services\Conversion_Service;
	use MABEL_BHI_LITE\Services\DateTime_Service;
	use MABEL_BHI_LITE\Services\Opening_Hours_Service;

	if(!defined('ABSPATH')){die;}

	class Shortcode_Controller
	{
		private $slug;

		private static $format_12 =  'g:i A';
		private static $format_24 = 'G:i';

		public function __construct()
		{
			$this->slug = Config_Manager::$slug;
			$this->init_hours_list_shortcode();
			$this->init_isopenclosed_shortcodes();
			$this->init_indicator_shortcode();
		}

		#region Indicator Shortcode
		private function init_indicator_shortcode()
		{
			new Shortcode(
				'mbhi',
				'indicator.php',
				[ $this,'create_indicator_model' ],
				[
					'location' => null,
					'includetime' => Settings_Manager::get_setting('includetime'),
					'includeday' => Settings_Manager::get_setting('includeday'),
					'format' => Settings_Manager::get_setting('format'),
					'approximation' => Settings_Manager::get_setting('approximation'),
					'openingsoonmessagetime' => (int)Settings_Manager::get_setting('warning') * 60,
					'closingsoonmessagetime' => (int)Settings_Manager::get_setting('warningclosing') * 60,
					'openmessage' => Settings_Manager::get_translated_setting('openline'),
					'closedmessage' =>  Settings_Manager::get_translated_setting('closedline'),
					'openingsoonmessage' => Settings_Manager::get_translated_setting('opensoonline'),
					'closingsoonmessage' => Settings_Manager::get_translated_setting('closedsoonline'),
				]
			);
		}

		public function create_indicator_model($attributes)
		{
			$model = new Indicator_VM();
			$model->slug = $this->slug;

			$location = Conversion_Service::convert_to_location(Settings_Manager::get_setting('locations'),$attributes['location']);

			if($location == null){
				$model->show_location_error = true;
				return $model;
			}

			$model->include_day = $attributes['includeday'];
			$model->include_time = $attributes['includetime'];

			if($model->include_time)
				$model->time = DateTime_Service::getInstance()->getNow()->format( $attributes['format'] == 12 ? self::$format_12 : self::$format_24 );

			if($model->include_day)
				$model->today = DateTime_Service::getInstance()->getNow()->format('l');

			$model->open = Opening_Hours_Service::instance()->is_open($location);

			if($attributes['approximation'])
				$model->indicator_text = $this->get_appromixation_text($location, $attributes, $model->open);
			else
				$model->indicator_text = $model->open ? $attributes['openmessage'] : $attributes['closedmessage'];

			return $model;
		}

		#endregion

		#region IsClosed/Open Shortcode

		private function init_isopenclosed_shortcodes()
		{
			new Shortcode(
				'mbhi_ifopen',
				'ifopenclosed.php',
				[ $this,'create_ifopenclosed_model' ],
				[ 'location' => null ]
			);
			new Shortcode(
				'mbhi_ifclosed',
				'ifopenclosed.php',
				[ $this,'create_ifopenclosed_model' ],
				[ 'location' => null ]
			);
		}

		public function create_ifopenclosed_model($attributes, $content, $code)
		{
			$model = new IfOpenClosed_VM();
			$model->slug = $this->slug;

			$location = Conversion_Service::convert_to_location(Settings_Manager::get_setting('locations'),$attributes['location']);

			if($location == null){
				$model->show_location_error = true;
				return $model;
			}

			$isopen = Opening_Hours_Service::instance()->is_open($location);

			if(strpos($code,'ifopen') !== false) 
				$model->show_content = $isopen ? true : false;
			else 
				$model->show_content = $isopen ? false : true;

			if($model->show_content)
				$model->content = do_shortcode($content);

			return $model;
		}
		#endregion

		#region List Shortcode
		private function init_hours_list_shortcode()
		{
			new Shortcode(
				'mbhi_hours',
				'list.php',
				[ $this,'create_hours_list_model' ],
				[
					'location' => null,
					'format'=> Settings_Manager::get_setting('format'),
					'display' => Settings_Manager::get_setting('tabledisplaymode'),
					'output' => Settings_Manager::get_setting('output'),
					'includeholidays' => Settings_Manager::get_setting('includespecialdates'),
					'includevacations' => Settings_Manager::get_setting('includevacations'),
				]
			);
		}

		public function create_hours_list_model($attributes)
		{
			$format = $attributes['format'] == 24 ? self::$format_24 : self::$format_12;

			$model = new List_VM();
			$model->slug = $this->slug;

			$location =  Conversion_Service::convert_to_location(Settings_Manager::get_setting('locations'),$attributes['location']);

			if($location == null){
				$model->show_location_error = true;
				return $model;
			}

			$consolidated = $attributes['display'] == 1 || strtolower($attributes['display']) === 'consolidated';
			$model->consolidate = $consolidated;
			$model->normal_entries = $consolidated ?
				$this->sets_as_consolidated_output($location->opening_hours, $format) :
				$this->sets_as_output($location->opening_hours, $format);

			$model->show_vacations = $attributes['includevacations'] == true;
			$model->show_specials = $attributes['includeholidays'] == true;

			if($model->show_specials) {
				$model->special_entries = $this->specials_as_output( $location, $consolidated, $format );
			if (Enumerable::from($model->special_entries)->any(function($x){return $x->current == true;}))
				$model->has_current = 2;
			}

			if($model->show_vacations){
				$model->vacation_entries = $this->vacations_as_output($location, $consolidated );
				if(Enumerable::from($model->vacation_entries)->any(function($x){return $x->current == true;}))
					$model->has_current = 3;
			}

			$model->show_as_table =  $attributes['output'] == 1 || strtolower($attributes['output']) === 'table';

			return $model;
		}
		#endregion

		#region Private Helpers

		private function get_appromixation_text(Location $location, array $attributes, $open)
		{
			$next_hours = $open ?
				Opening_Hours_Service::instance()->get_next_closing_time($location) :
				Opening_Hours_Service::instance()->get_next_opening_time($location);

			if($next_hours === null)
				return __('Please enter your opening times in the settings', 'business-hours-indicator');

			$difference = DateTime_Service::getInstance()->get_difference($next_hours,DateTime_Service::getInstance()->getNow()) + 60;

			if($open){ 
				if($difference <= $attributes['closingsoonmessagetime'])
					return str_replace('{x}',floor($difference/60),$attributes['closingsoonmessage']);
			}else{
				if($difference < $attributes['openingsoonmessagetime'])
					return str_replace('{x}',floor($difference/60),$attributes['openingsoonmessage']);
			}

			return $open ? $attributes['openmessage'] : $attributes['closedmessage'];
		}

		private function sets_as_consolidated_output(array $sets, $time_format)
		{
			$consolidated = [];
			$consolidated_row = [];
			$closed = __('Closed', 'business-hours-indicator');

			foreach($sets as $set)
			{
				if(sizeof($consolidated_row) === 0) {
					$consolidated_row[] = $set;
					continue;
				}
				$is_same = true;
				foreach($consolidated_row as $entry)
				{
					if(!$set->is_equal($entry)){
						$is_same = false;
						break;
					}
				}
				if($is_same){
					$consolidated_row[] = $set;
				}else{
					$consolidated[]   = $consolidated_row;
					$consolidated_row = [ $set ];
				}
			}
			$consolidated[] = $consolidated_row;

			$entries = [];

			foreach($consolidated as $consolidated_row)
			{
				$base_set = $consolidated_row[0];
				$size = sizeof($consolidated_row);
				$day_range = ($size > 1) ?
					__($base_set->day_name, 'business-hours-indicator') . ' - ' . __($consolidated_row[$size - 1]->day_name, 'business-hours-indicator') :
					__($base_set->day_name, 'business-hours-indicator');

				$entry = new List_VM_Entry();
				$entry->range =  $day_range;
				$entry->hours = $base_set->is_closed() ? $closed : $base_set->opening_hours[0]->to_string($time_format);

				$has_current = Enumerable::from($consolidated_row)->firstOrDefault(function($x){return $x->is_today == true;});
				$entry->current = $has_current != null;

				$entries[] = $entry;

			}
			return $entries;
		}

		private function sets_as_output(array $sets, $time_format)
		{
			$entries = [];
			foreach($sets as $set)
			{
				$entry = new List_VM_Entry();
				$entry->range =  __($set->day_name, 'business-hours-indicator');
				$entry->hours = $set->is_closed() ? __('Closed', 'business-hours-indicator') : $set->opening_hours[0]->to_string($time_format);
				$entry->current = $set->is_today;
				$entries[] = $entry;
			}
			return $entries;
		}

		private function specials_as_output(Location $location, $consolidated, $time_format)
		{
			$entries = [];

			if(empty($location->specials)) return $entries;

			$sorted_specials = Enumerable::from($location->specials)->orderBy(function($x){return $x->date;})->toArray();

			$closed = __('Closed', 'business-hours-indicator');
			foreach($sorted_specials as $special) {
				$entry = new List_VM_Entry();
				$entry->range = sprintf(
					'%s %s',
					$special->date->format('j'),
					__($special->date->format('M'), 'business-hours-indicator')
				);
				$entry->hours = $special->is_closed() ? $closed : $special->opening_hours[0]->to_string($time_format);
				$entry->current = $special->is_today;
				$entries[] = $entry;
			}

			if( !$consolidated || sizeof($entries) === 0 )
				return $entries;

			$consolidated_entries = [];

			foreach($entries as $entry)
			{
				if(Enumerable::from($consolidated_entries)->any(function($x) use($entry){
					return $x->is_equal($entry) || (!$x->is_equal($entry) && $entry->hours === $x->hours);
				})) continue;

				$consolidated_row = Enumerable::from($entries)->where(function($x) use ($entry){
					return $x->is_equal($entry) || (!$x->is_equal($entry) && $entry->hours === $x->hours);
				});

				$consolidated_entry = new List_VM_Entry();
				$consolidated_entry->range = $consolidated_row->join(function($x){return $x->range;},', ');
				$consolidated_entry->hours = $entry->hours;
				$consolidated_entry->current = $consolidated_row->firstOrDefault(function($x){return $x->current === true;}) != null;

				$consolidated_entries[] = $consolidated_entry;
			}

			return $consolidated_entries;
		}

		private function vacations_as_output(Location $location,$consolidated)
		{
			$entries = [];

			if(empty($location->vacations)) return $entries;

			$sorted_vacations = Enumerable::from($location->vacations)->orderBy(function($x){return $x->from;})->toArray();

			foreach($sorted_vacations as $vacation)
			{
				$entry = new List_VM_Entry();
				$entry->range = sprintf(
					'%s %s - %s %s',
					$vacation->from->format('j'),
					__($vacation->from->format('M'), 'business-hours-indicator'),
					$vacation->to->format('j'),
					__($vacation->to->format('M'), 'business-hours-indicator')
				);
				$entry->current = $vacation->spans_today;
				$entries[] = $entry;
			}

			if(!$consolidated || sizeof($entries) === 0 )
				return $entries;

			$collection = Enumerable::from($entries);

			$entry = new List_VM_Entry();
			$entry->range = $collection->join(function($x){return $x->range;},', ');
			$entry->current = $collection->firstOrDefault(function($x){return $x->current === true;}) != null;

			return [ $entry ];
		}
		#endregion
	}
}