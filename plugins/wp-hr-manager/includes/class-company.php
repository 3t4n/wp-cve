<?php
namespace WPHR\HR_MANAGER;
use WPHR\HR_MANAGER\Admin\Models\Company_Locations;

/**
 * Company class
 */
class Company {

    /**
     * Holds the company data array
     *
     * @var array
     */
    private $data;

    /**
     * Option name in wp_options table
     */
    const key = '_wphr_company';

    /**
     * Constructor
     */
    public function __construct() {
        $this->data = get_option( self::key, $this->defaults() );
    }

    /**
     * Get some defaults if no data found
     *
     * @return array
     */
    public function defaults() {
        $defaults = [
            'logo'    => 0,
            'name'    => __( 'Untitled Company', 'wphr' ),
            'address' => [
                'address_1' => __( 'Street Address 1', 'wphr' ),
                'address_2' => __( 'Address Line 2', 'wphr' ),
                'city'      => __( 'City', 'wphr' ),
                'state'     => __( 'State', 'wphr' ),
                'postcode'  => '',
                'country'   => 'US'
            ],
            'phone'    => '',
            'fax'      => '',
            'mobile'   => '',
            'website'  => '',
            'currency' => 'USD'
        ];

        return apply_filters( 'wphr_company_defaults', $defaults );
    }

    /**
     * Magic method to get item data values
     *
     * @param  string
     *
     * @return string
     */
    public function __get( $key ) {
        if ( isset( $this->data[ $key ] ) ) {
            if ( is_array( $this->data[ $key ] ) ) {
                return $this->data[ $key ];
            }

            return stripslashes( $this->data[ $key ] );
        }
    }

    /**
     * Update company data
     *
     * @param  array   $args
     *
     * @return void
     */
    public function update( $args = [] ) {
        $value = wp_parse_args( $args, $this->defaults() );

        update_option( self::key, $value );
    }

    /**
     * Check if a company has logo
     *
     * @return boolean
     */
    public function has_logo() {
        return (int) $this->logo;
    }

    /**
     * Get the company logo
     *
     * @return string the HTML image attribute
     */
    public function get_logo() {
        $logo_id = (int) $this->logo;

        if ( ! $logo_id ) {
            $url   = $this->placeholder_logo();
        } else {
            $image = wp_get_attachment_image_src( $logo_id, 'medium' );
            $url   = $image[0];
        }

        $image = sprintf( '<img src="%s" alt="%s" />', esc_url( $url ), esc_attr( $this->name ) );

        return $image;
    }

    /**
     * [placeholder_logo description]
     *
     * @return string placeholder image url
     */
    public function placeholder_logo() {
        $url = WPHR_ASSETS . '/images/placeholder.png';

        return apply_filters( 'wphr_placeholder_image', $url );
    }

    /**
     * Get formatted address of the company
     *
     * @return string address
     */
    public function get_formatted_address() {
        $country = Countries::instance();

        return $country->get_formatted_address( array(
            'address_1' => isset( $this->address['address_1'] ) ? $this->address['address_1'] : '',
            'address_2' => isset( $this->address['address_2'] ) ? $this->address['address_2'] : '',
            'city'      => isset( $this->address['city'] ) ? $this->address['city'] : '',
            'state'     => isset( $this->address['state'] ) ? $this->address['state'] : '',
            'postcode'  => isset( $this->address['zip'] ) ? $this->address['zip'] : '',
            'country'   => isset( $this->address['country'] ) ? $this->address['country'] : ''
        ) );
    }

    /**
     * [get_edit_url description]
     *
     * @return string the url
     */
    public function get_edit_url() {
        $url = add_query_arg(
            array( 'action' => 'edit' ),
            admin_url( 'admin.php?page=wphr-company' )
        );

        return $url;
    }

    /**
     * Check if the employee belongs to the company
     *
     * @param  int   employee id
     *
     * @return boolean
     */
    public function has_employee( $employee_id ) {
        return true;
    }

    public function get_locations() {
        $locations = new Company_Locations();

        return $locations::all()->toArray();
    }

    /**
     * @param array $args
     *
     * @return \WP_Error
     */
    public function create_location( $args = [] ) {
		$company_financial_year = wphr_get_financial_year_dates();
        $defaults = array(
            'id'         => 0,
            'name'       => '',
            'address_1'  => '',
            'address_2'  => '',
            'city'       => '',
            'state'      => '',
            'zip'        => '',
            'country'    => '',
            'office_start_time'        => '',
            'office_end_time'    => '',
            'office_working_hours'    => 9,
			'office_financial_year_start' => $company_financial_year['start'],
            'office_financial_day_start'  => 1  
        );
        $fields = wp_parse_args( $args, $defaults );
        $location_id = intval( $fields['id'] );
        unset( $fields['id'] );
		//return new \WP_Error( 'no-name',$fields['office_working_hours'] );
        // validation
        if ( empty( $fields['name'] ) ) {
            return new \WP_Error( 'no-name', __( 'No location name provided.', 'wphr' ) );
        }

        if ( empty( $fields['address_1'] ) ) {
            return new \WP_Error( 'no-address_1', __( 'No address provided.', 'wphr' ) );
        }

        if ( empty( $fields['country'] ) ) {
            return new \WP_Error( 'no-country', __( 'No country provided.', 'wphr' ) );
        }
		
        /*if ( $fields['office_end_time'] != '00:00:00' && $fields['office_start_time'] != '00:00:00' &&  strtotime( $fields['office_end_time'] ) <= strtotime( $fields['office_start_time'] ) ) {
            return new \WP_Error( 'no-timing', __( 'Please check office time.', 'wphr' ) );
        }*/

        $location = new Company_Locations();

        if ( ! $location_id ) {
            $new_location = $location->create( $fields );

            do_action( 'wphr_company_location_new', $new_location->id, $fields );

            return $new_location->id;

        } else {
            $location->find( $location_id )->update( $fields );

            do_action( 'wphr_company_location_updated', $location_id, $fields );

            return $location_id;
        }
    }
}
