<?php
namespace Wdr\App\Controllers\Admin\Tabs\Reports;

use Wdr\App\Helpers\Woocommerce;
use Wdr\App\Models\DBTable;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class RuleNameDiscount extends Base {

    protected $rule_obj;

    public function __construct($rule)
    {
        $this->rule_obj = $rule;
    }

    public function get_title() {
        return $this->rule_obj->getTitle();
    }

    public function get_subtitle() {
        return __( 'Discounted amount shown in default store currency', 'woo-discount-rules' );
    }

    public function get_type() {
        return 'line';
    }

    public function get_data( $params ) {
        $rule_id = $params['type'];

        $params = $this->prepare_params( $params );

        $data = $this->load_raw_data( $params,  $rule_id);
        $rule_amount_stats = $data['stats'];

        $rules   = array_unique( array_column( $rule_amount_stats, 'title' ) );
        $columns = array_merge( array( __( 'Date', 'woo-discount-rules' ) ), $rules );

        $rows  = array();
        $dates = $this->get_dates( $params['from'], $params['to'] );
        foreach ( $dates as $date ) {
            $rows[ $date ]    = array_fill( 0, count( $columns ), 0.0 );
            $rows[ $date ][0] = $date;

        }
        foreach ( $rule_amount_stats as $rule_amount_item ) {
            $date = date( 'Y-m-d', strtotime( $rule_amount_item->date_rep ) );
            if ( ! isset( $rows[ $date ] ) ) {
                continue;
            }

            $column_key = array_search( $rule_amount_item->title, $columns );
            if ( false === $column_key ) {
                continue;
            }

            $rows[ $date ][ $column_key ] = (float) $rule_amount_item->value;
        }

        $res = $this->prepare_data( $columns, $rows, $data['other'] );

        return $res;
    }

    protected function prepare_data( $columns, $rows, $other ) {
        $data['chart'] = array(
            'title'    => $this->get_title(),
            'subtitle' => $this->get_subtitle(),
            'type'     => $this->get_type(),
            'columns'  => $columns,
            'rows'     => $rows,
        );

        if (!empty($other)) {
            $data['other'] = [
                'total_orders' => (int) $other->total_orders,
                'revenue' => Woocommerce::formatPrice($other->revenue),
                'discounted_amount' => Woocommerce::formatPrice($other->discounted_amount),
                'total_free_shipping' => (int) $other->total_free_shipping,
            ];
        }
        return $data;
    }

    protected function load_raw_data( $params, $rule_id = 0 ) {
        $data = DBTable::get_rule_rows_summary( $params, $rule_id );
        if (empty($data)) {
            $data = []; // to avoid false to array conversion warnings
        }
        if ( empty( $data['stats'] ) ) {
            $data['stats'] = array();
        }
        if ( empty( $data['other'] ) ) {
            $data['other'] = array();
        }
        return $data;
    }

    protected function prepare_params( $params ) {
        return array(
            'from'                  => $params['from'],
            'to'                    => $params['to'],
            'limit'                 => 5,
            'include_amount'        => true,
            'include_cart_discount' => true,
        );
    }

    protected function get_dates( $from, $to ) {
        $ret = array();

        $to = strtotime( $to );
        for ( $current = strtotime( $from ); $current <= $to; $current += 60 * 60 * 24 ) {
            $ret[] = date( 'Y-m-d', $current );
        }

        return $ret;
    }
}