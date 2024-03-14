<?php //phpcs:disable Squiz.Commenting.FunctionComment.MissingParamTag
/**
 * Extended_Data class file.
 *
 * @package WooCommerce Utils
 */

namespace Oblak\WooCommerce\Data;

use BadMethodCallException;
use Oblak\WooCommerce\Data\Extended_Data_Store;

/**
 * * Extended Data Class.
 *
 * Does the entire heavy lifting to suit all of your WC_Data needs.
 *
 * Defines an extra base data array - `core_data` - which is used to get the keys and data which should go into the main data table
 * Data and extra data can be used interchangeably for the metadata table.
 *
 * Props defined in `core_data`, `data` and `extra_data` can use the `get_` and `set_` methods to get and set the data.
 *
 * Defines a `db` context for getters which will return the data in the format it should be stored in the database.
 *
 * Defines a `prop_types` array which is used to determine how to handle the data when getting and setting props.
 * By default supported types are:
 *  - `date`       - a DateTime object
 *  - `bool`      - a boolean value
 *  - `array`     - an array which will be either imploded for a core key, or serialized for a meta key
 *  - `array_raw` - an array which will always be saved as a comma separated string
 *  - `binary`    - A hex string which will be converted to binary when saved to the database
 */
abstract class Extended_Data extends \WC_Data {
    /**
     * {@inheritDoc}
     *
     * @var Extended_Data_Store
     */
    protected $data_store;

    /**
     * Array linking props to their types.
     *
     * @var array<string, string>
     */
    protected array $prop_types = array();

    /**
     * Array of core data keys.
     *
     * Core data keys are the keys that are stored in the main table.
     *
     * @var array<int, string>
     */
    protected array $core_data = array();

    /**
     * Get the Data Object ID if ID is passed, otherwise Data is new and empty.
     *
     * @param  int|Extended_Data|object $data Package to init.
     */
    public function __construct( int|Extended_Data|\stdClass $data = 0 ) {
        $this->data       = \array_merge( $this->core_data, $this->data );
        $this->data_store = $this->load_data_store();

        parent::__construct( $data );

        $this->load_data( $data );

        if ( $this->get_id() <= 0 ) {
            return;
        }

        $this->data_store->read( $this );
    }

    /**
     * Load the data for this object from the database.
     *
     * @param  int|Extended_Data|object $data Package to init.
     */
    protected function load_data( int|Extended_Data|\stdClass $data ) {
        $id_field = $this->data_store->get_object_id_field();

        if ( \is_numeric( $data ) && $data > 0 ) {
            $this->set_id( $data );
        } elseif ( $data instanceof Extended_Data ) {
            $this->set_id( $data->get_id() );
        } elseif ( ( (int) $data->$id_field ) > 0 ) {
            $this->set_id( \absint( $data->$id_field ) );
        } else {
            $this->set_object_read( true );
        }
    }

    /**
     * Load the data for this object from the database.
     *
     * @return Extended_Data_Store
     */
    public function load_data_store() {
        return \WC_Data_Store::load( $this->object_type );
    }

    /**
     * Universal prop getter / setter
     *
     * @param  string $name      Method name.
     * @param  array  $arguments Method arguments.
     *
     * @throws \BadMethodCallException If prop does not exist.
     */
    public function __call( $name, $arguments ) {
        if ( ! \preg_match( '/^(get|set)_/', $name ) ) {
            throw new \BadMethodCallException(
                \sprintf( 'Method %s::%s does not exist', static::class, \esc_html( $name ) ),
            );
        }

        $property = \preg_replace( '/^(get|set)_/', '', $name );

        if ( ! \in_array( $property, $this->get_data_keys(), true ) ) {
            throw new \BadMethodCallException(
                \sprintf( 'Property %s does not exist', \esc_html( $property ) ),
            );
        }

        return \preg_match( '/^get_/', $name )
            ? $this->get_prop( $property, $arguments[0] ?? 'view' )
            : $this->set_prop( $property, $arguments[0] );
    }

    /**
     * Get the data keys for this object. These are the columns for the main table.
     *
     * @return array<int, string>
     */
    final public function get_core_data_keys(): array {
        return \array_keys( $this->core_data );
    }

    /**
     * Get the core data for this object.
     *
     * @param  string $context The context for the data.
     * @return array<string, mixed>
     */
    final public function get_core_data( string $context = 'view' ): array {
        $data = array();

        foreach ( $this->get_core_data_keys() as $prop ) {
            $data[ $prop ] = $this->get_prop( $prop, $context );
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    protected function set_prop( $prop, $value ) {
        $prop_type = $this->prop_types[ $prop ] ?? '';

        match ( $prop_type ) {
            'date'      => $this->set_date_prop( $prop, $value ),
            'bool'      => $this->set_bool_prop( $prop, $value ),
            'array'     => $this->set_array_prop( $prop, $value ),
            'array_raw' => $this->set_array_prop( $prop, $value ),
            'binary'    => $this->set_binary_prop( $prop, $value ),
            'json_obj'  => $this->set_json_prop( $prop, $value, false ),
            'json'      => $this->set_json_prop( $prop, $value ),
            'int'       => $this->set_int_prop( $prop, $value ),
            'float'     => $this->set_float_prop( $prop, $value ),
            default     => parent::set_prop( $prop, $value ),
        };
    }

    /**
     * Set a boolean prop
     *
     * @param  string $prop  Property name.
     * @param  mixed  $value Property value.
     */
    protected function set_bool_prop( string $prop, $value ) {
        if ( '' === $value ) {
            return;
        }

        parent::set_prop( $prop, \wc_string_to_bool( $value ) );
    }

    /**
     * Set an array prop
     *
     * @param  string $prop  Property name.
     * @param  mixed  $value Property value.
     */
    protected function set_array_prop( string $prop, $value ) {
        parent::set_prop( $prop, \wc_string_to_array( $value ) );
    }

    /**
     * Set a binary prop
     *
     * @param  string $prop  Property name.
     * @param  mixed  $value Property value.
     */
    protected function set_binary_prop( string $prop, $value ) {
        if ( \preg_match( '/[^\x20-\x7E]/', $value ) > 0 ) {
            $value = \bin2hex( $value );
        }

        parent::set_prop( $prop, $value );
    }

    /**
     * Set a json prop
     *
     * @param  string $prop  Property name.
     * @param  string $value Property value.
     * @param  bool   $assoc Whether to return an associative array or not.
     */
    protected function set_json_prop( string $prop, string $value, bool $assoc = true ) {
        parent::set_prop( $prop, \json_decode( $value, $assoc ) );
    }

    /**
     * Set an int prop
     *
     * @param  string $prop  Property name.
     * @param  mixed  $value Property value.
     */
    protected function set_int_prop( string $prop, $value ) {
        parent::set_prop( $prop, \intval( $value ) );
    }

    /**
     * Set a float prop
     *
     * @param  string $prop  Property name.
     * @param  mixed  $value Property value.
     */
    protected function set_float_prop( string $prop, $value ) {
        parent::set_prop( $prop, \floatval( $value ) );
    }

    /**
     * {@inheritDoc}
     */
    protected function get_prop( $prop, $context = 'view' ) {
        $value = parent::get_prop( $prop, $context );
        $type  = $this->prop_types[ $prop ] ?? '';

        if ( \is_null( $value ) || 'db' !== $context ) {
            return $value;
        }

        $is_core_key = \in_array( $prop, $this->get_core_data_keys(), true );
        $date_cb     = \str_ends_with( $prop, '_gmt' ) ? 'getTimestamp' : 'getOffsetTimestamp';

        return match ( $type ) {
            'date'      => \gmdate( 'Y-m-d H:i:s', $value->{"$date_cb"}() ),
            'bool'      => \wc_bool_to_string( $value ),
            'array'     => $is_core_key ? \implode( ',', $value ) : $value,
            'array_raw' => \implode( ',', $value ),
            'binary'    => 0 === \preg_match( '/[^\x20-\x7E]/', $value ) ? \hex2bin( $value ) : $value,
            'json'      => \wp_json_encode( $value ),
            'json_obj'  => \wp_json_encode( $value, \JSON_FORCE_OBJECT ),
            default     => $value,
        };
    }

    /**
     * Get prop types
     *
     * @return array
     */
    final public function get_prop_types(): array {
        return $this->prop_types;
    }

    /**
     * Checks if the object has a date_created prop.
     *
     * @param  bool $gmt Whether to check for GMT or site time.
     * @return bool
     */
    final public function has_created_prop( $gmt = false ): bool {
        $prop_name = $gmt ? 'date_created_gmt' : 'date_created';
        return \in_array( $prop_name, $this->get_core_data_keys(), true );
    }

    /**
     * Checks if the object has a date_modified prop.
     *
     * @param  bool $gmt Whether to check for GMT or site time.
     * @return bool
     */
    final public function has_modified_prop( $gmt = false ): bool {
        $prop_name = $gmt ? 'date_modified_gmt' : 'date_modified';
        return \in_array( $prop_name, $this->get_core_data_keys(), true );
    }

    /**
     * {@inheritDoc}
     */
    protected function is_internal_meta_key( $key ) {
        $parent_check = parent::is_internal_meta_key( $key );

        if ( ! $parent_check && \in_array( $key, $this->get_data_keys(), true ) ) {
            \wc_doing_it_wrong(
                __FUNCTION__,
                \sprintf(
                    // Translators: %s: $key Key to check.
                    \__(
                        'Generic add/update/get meta methods should not be used for internal meta data, including "%s". Use getters and setters.',
                        'woocommerce',
                    ),
                    $key,
                ),
                'WooCommerce Utils - 3.2.0',
            );
            return true;
        }

        return $parent_check;
    }
}
