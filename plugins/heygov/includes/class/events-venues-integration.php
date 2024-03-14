<?php

class HeyGovVenuesIntegration {

	public function __construct() {
		add_action('init', array($this, 'registerTribeEventsMeta'));

		// show and save Venues field in WP Admin -> Event page
		add_action('tribe_events_details_table_bottom', array($this, 'tribeEventsMeta'));
		add_action('save_post', [$this, 'saveEventVenue'], 100, 2);

		// show and save Venues field in Rest API
		add_action('rest_api_init', array($this, 'registerTribeEventsMetaRest'));
		add_action('tribe_rest_event_data', [$this, 'tribeRestEventData'], 100, 2);
		add_action('twd_after_save_event', array($this, 'hg_block_event_venue') , 100, 2);

		//add_filter( 'tribe_events_update_meta', array($this, 'hg_block_event_venue') , 10, 3);
		//add_filter( 'tribe_events_rest_event_prepare_postarr', array($this, 'hg_block_event_venue') , 10, 2);
	}

	public function registerTribeEventsMeta() {
		add_post_type_support('tribe_events', 'custom-fields');

		register_post_meta( 'tribe_events', 'heygov_venues', [
			'name'				=>	__('HeyGov venue', 'heygov'),
			'type'				=>	'string',
			'default'			=>	'',
			'description'		=>	'Block slots in HeyGov Venues',
			'single'			=>	true,
			'sanitize_callback'	=>	'sanitize_text_field',
			'show_in_rest'		=>	true,
		]);
	}

	public function registerTribeEventsMetaRest() {
		register_rest_field( 'tribe_events',
			'heygov_venues',
			array(
				'get_callback'    => null,
				'update_callback' => array( $this, 'tribe_events_update_venue' ),
				'schema'          => null,
			)
		);
	}

	public function tribe_events_update_venue($value, $object, $field_name) {
		/* $fp = fopen(__DIR__ . '/xxxxPostSchedule.txt', 'a');
		fwrite($fp, print_r($value,true).'
		================================
		');
		fclose($fp); */

		return update_post_meta($object->ID, $field_name, $value);
	}

	public function tribeEventsMeta($eventId) {
		$eventVenues = get_post_meta($eventId, 'heygov_venues', true) ?: '';
		$eventVenues = explode(',', $eventVenues);

		$venues = heyGovVenues();
		?>

		<table id="event_url" class="eventtable">
			<tbody>
				<tr>
					<td colspan="2" class="tribe_sectionheader">
						<h4>HeyGov Venues</h4>
						<p>Block venue reservations in these venues, when this event takes place.</p>
					</td>
				</tr>
				<tr>
					<td style="width:172px;">Venues:</td>
					<td>
						<select multiple id="heygov_venues" name="heygov_venues[]" size="4">
							<?php foreach($venues as $venue) : ?>
								<option value="<?php echo $venue->id; ?>" <?php selected(in_array($venue->id, $eventVenues)) ?>><?php echo $venue->name ?></option>
							<?php endforeach ?>
						</select>
						<p>Keep <code>ctrl</code> pressed to select multiple venues.</p>
					</td>
				</tr>
			</tbody>
		</table>

		<?php
	}

	public function saveEventVenue($postId, $post) {
		if ($post->post_type !== 'tribe_events') return;

		if (isset($_POST['heygov_venues'])) {
			$value = is_array($_POST['heygov_venues']) ? implode(',', $_POST['heygov_venues']) : $_POST['heygov_venues'];
			$value = sanitize_text_field($value);

			update_post_meta($post->ID, 'heygov_venues', $value);

			if (tribe_is_recurring_event($postId)) {
				$children = get_children([
					'posts_per_page' => -1,
					'post_parent'    => $postId,
					'post_type'      => 'tribe_events',
				]);

				foreach ($children as $child) {
					update_post_meta($child->ID, 'heygov_venues', $value);
				}
			}

		} else {
			delete_post_meta($post->ID, 'heygov_venues');
		}
	}

	public function tribeRestEventData($data, $event) {
		// expose heygov_venues field in Rest API

		$venues = get_post_meta($event->ID, 'heygov_venues', true);
		$data['heygov_venues'] = $venues;

		return $data;
	}

	public function hg_block_event_venue($event_id, $heygov_venues) {
		update_post_meta($event_id, 'heygov_venues', $heygov_venues);
	}

}
