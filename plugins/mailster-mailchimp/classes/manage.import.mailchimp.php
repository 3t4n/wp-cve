<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MailsterImportMailchimp extends MailsterImport {



	protected $type = 'mailchimp';
	protected $name = 'Mailchimp';

	private $api;

	function init() {

	}

	public function api() {
		if ( ! $this->api ) {
			$data      = $this->get_credentials();
			$this->api = $this->get_api_class( $data['apikey'] );
		}

		return $this->api;
	}


	function get_lists( $statuses = null ) {

		$lists = array();

		$api_result = $this->api()->lists(
			array(
				'count'                  => 1000,
				'include_total_contacts' => true,
			)
		);

		foreach ( $api_result as $list ) {

			$total = 0;

			if ( is_null( $statuses ) ) {
				$total = $list->stats->member_count;
			} else {
				foreach ( $statuses as $status ) {
					switch ( $status ) {
						case 'unsubscribed':
							$total += $list->stats->{'unsubscribe_count'};
							break;
						case 'cleaned':
							$total += $list->stats->{'cleaned_count'};
							break;
						case 'subscribed':
							$total += $list->stats->{'member_count'};
							break;
					}
				}
			}

			$lists[ $list->id ] = array(
				'id'           => $list->id,
				'name'         => $list->name,
				'total'        => $total,
				'merge_fields' => $list->stats->merge_field_count,
			);
		}

		return $lists;

	}

	function get_statuses() {

		$statuses = array(
			'pending'       => 'pending',
			'subscribed'    => 'subscribed',
			'unsubscribed'  => 'unsubscribed',
			'transactional' => 'subscribed',
			'cleaned'       => 'hardbounced',
		// 'archived'      => 'deleted',
		);

		$return = array();

		foreach ( $statuses as $name => $status ) {
			$return[ $name ] = array(
				'id'     => $name,
				'name'   => $name,
				'mapped' => $status,
			);
		}
		return $return;

	}

	public function valid_credentials() {

		if ( $this->get_credentials() ) {
			return true;
		}

		parse_str( $_POST['data'], $data );

		$response = $this->get_api_class( $data['apikey'] )->ping();

		if ( is_wp_error( $response ) ) {

			return $response;
		}

		$this->update_credentials( $data, DAY_IN_SECONDS );

		$return['html'] = $this->get_import_options();
		wp_send_json_success( $return );
		exit;
	}


	public function get_import_part( &$import_data ) {

		$list_id      = $import_data['extra']['selected_lists'][ $import_data['extra']['current_list'] ];
		$merge_fields = $import_data['extra']['merge_fields'];
		$statuses     = $import_data['extra']['selected_statuses'];
		$offset       = $import_data['extra']['offset'];
		$limit        = $import_data['performance'] ? 20 : 250;

		$api_result = $this->api()->members(
			$list_id,
			array(
				'offset' => $offset,
				'count'  => $limit,
				'status' => implode( ',', $statuses ),
			)
		);

		if ( is_wp_error( $api_result ) ) {
			return $api_result;
		}

		$count = count( $api_result->members );

		$data     = array();
		$lists    = $this->get_lists( $statuses );
		$listname = $lists[ $list_id ]['name'];

		foreach ( $api_result->members as $entry ) {
			$e      = $this->map_entry( $entry, $merge_fields, $listname );
			$data[] = $e;
		}

		$import_data['extra']['offset'] += $limit;
		if ( $count < $limit && isset( $import_data['extra']['selected_lists'][ $import_data['extra']['current_list'] + 1 ] ) ) {
			$import_data['extra']['offset'] = 0;
			$import_data['extra']['current_list']++;
		}

		return $data;

	}


	public function get_import_data() {

		parse_str( $_POST['data'], $import_options );

		$sample_size  = 10;
		$total        = 0;
		$merge_fields = 0;
		$current_list = null;

		$data = array();

		if ( ! empty( $import_options['lists'] ) ) {

			$lists = $this->get_lists( $import_options['statuses'] );

			// for each selected list
			foreach ( $import_options['lists'] as $list_id ) {

				$listname     = $lists[ $list_id ]['name'];
				$total       += $lists[ $list_id ]['total'];
				$merge_fields = max( $merge_fields, $lists[ $list_id ]['merge_fields'] );

				// get two members as sample
				$api_result = $this->api()->members(
					$list_id,
					array(
						'count'  => ceil( $sample_size / count( $import_options['lists'] ) ),
						'status' => implode( ',', $import_options['statuses'] ),
					)
				);

				if ( is_wp_error( $api_result ) ) {
						  return $api_result;
				}

				foreach ( $api_result->members as $entry ) {
					$e      = $this->map_entry( $entry, $merge_fields, $listname );
					$data[] = $e;
				}
			}
		}

		$header               = array();
		$header['email']      = 'email';
		$header['first_last'] = 'first_last';
		$header['_lists']     = 'lists';
		for ( $i = 0; $i < $merge_fields; $i++ ) {
			$header[ '_merge_field_' . $i ] = 'merge_field_' . $i;
		}
		$header['_status']     = 'status';
		$header['_ip_signup']  = 'ip_signup';
		$header['_signup']     = 'signup';
		$header['_ip_confirm'] = 'ip_confirm';
		$header['_confirm']    = 'confirm';
		$header['_lang']       = 'lang';
		$header['_tags']       = 'tags';
		$header['_lat']        = 'lat';
		$header['_long']       = 'long';
		$header['_country']    = 'country';
		$header['_timeoffset'] = 'timeoffset';
		$header['_timezone']   = 'timezone';

		return array(
			'total'    => $total,
			'removed'  => null,
			'header'   => $header,
			'sample'   => $data,
			'extra'    => array(
				'current_list'      => 0,
				'offset'            => 0,
				'selected_lists'    => array_values( $import_options['lists'] ),
				'selected_statuses' => array_values( $import_options['statuses'] ),
				'merge_fields'      => $merge_fields,
			),
			'defaults' => array(
				'existing' => 'merge',
			),
		);
	}

	private function map_entry( $entry, $max_merge_fields, $listnames ) {

		$merge_fields = array_values( (array) $entry->merge_fields );

		$maped[] = $entry->email_address;
		$maped[] = $entry->full_name;
		$maped[] = $listnames;
		for ( $i = 0; $i < $max_merge_fields; $i++ ) {
			$maped[] = isset( $merge_fields[ $i ] ) ? $merge_fields[ $i ] : null;
		}
		$maped[] = $this->map_status( $entry->status );
		$maped[] = $entry->ip_signup;
		$maped[] = $entry->timestamp_signup;
		$maped[] = $entry->ip_opt;
		$maped[] = $entry->timestamp_opt;
		$maped[] = $entry->language;

		$maped[] = implode( ',', wp_list_pluck( $entry->tags, 'name' ) );
		$maped[] = $entry->location->latitude ? $entry->location->latitude : null;
		$maped[] = $entry->location->longitude ? $entry->location->longitude : null;
		$maped[] = $entry->location->country_code;
		$maped[] = $entry->location->gmtoff;
		$maped[] = $entry->location->timezone;

		return $maped;

	}

	private function map_status( $org_status ) {

		$statuses = $this->get_statuses();
		return isset( $statuses[ $org_status ] ) ? $statuses[ $org_status ]['mapped'] : null;

	}

	protected function credentials_form() {

		?>
			<p><label><?php esc_html_e( 'Enter your Mailchimp API Key.' ); ?></label></p>
			<input type="text" name="apikey" value="" class="widefat regular-text" autocomplete="off">
			<p class="howto"><?php printf( esc_html__( 'You can find your API Key %s.', 'mailster-mailchimp' ), '<a href="https://us2.admin.mailchimp.com/account/api-key-popup/" class="external">' . esc_html__( 'here', 'mailster-mailchimp' ) . '</a>' ); ?> <?php esc_html_e( 'Mailster will store this key for 24 hours.' ); ?></p>

		<?php
	}

	private function get_api_class( $apikey = null ) {
		include_once 'api.class.php';
		return new MailsterMailchimpAPI( $apikey );
	}

	public function filter( $insert, $data, $import_data ) {

		$insert['referer'] = 'mailchimp';
		return $insert;
	}

}
