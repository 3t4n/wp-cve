<?php

defined( 'ABSPATH' ) or die();

class RapidLoad_Buffer
{

    public function __construct()
    {
        add_action('init', [$this, 'maybe_init_process'], defined('RAPIDLOAD_BUFFER_PRIORITY') ? RAPIDLOAD_BUFFER_PRIORITY : -10);
    }

    public function maybe_init_process() {
        ob_start( [ $this, 'maybe_process_buffer' ] );
    }

    public function maybe_process_buffer( $buffer ) {

        do_action( 'rapidload_before_maybe_process_buffer', $buffer );

        if ( ! $this->is_html( $buffer ) && apply_filters('rapidload_buffer_is_html', true) ) {
            return $buffer;
        }

        return (string) apply_filters( 'rapidload_buffer', $buffer );
    }

    protected function is_html( $buffer ) {
        return preg_match( '/<\/html>/i', $buffer );
    }
}