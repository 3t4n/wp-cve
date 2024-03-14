<?php

namespace DF_SCC\GutenbergBlock;

use formController;

class SCC_Gutenberg_Block {

    /**
     * Indicate if current integration is allowed to load.
     *
     * @since 1.4.8
     *
     * @return bool
     */
    public function allow_load() {
        return function_exists( 'register_block_type' );
    }

    /**
     * Load an integration.
     */
    public function load() {
        $this->hooks();
    }

    /**
     * Integration hooks.
     *
     * @since 1.4.8
     */
    protected function hooks() {
        add_action( 'init', [ $this, 'register_block' ] );
        add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_assets' ] );
    }

    /**
     * Register the block.
     *
     * @since 1.4.8
     */
    public function register_block() {
        // Register the block.
        register_block_type(
            'stylish-cost-calculator/calc-picker',
            [
                'attributes'      => [
                    'formId' => [
                        'type' => 'number',
                    ],
                ],
                'render_callback' => [ $this, 'render_block' ],
            ]
        );
    }

    public function render_block( $attr ) {
        $calc_id = $attr['calcId'];

        return do_shortcode( "[scc_calculator type='text' idvalue=$calc_id]" );
    }

    public function enqueue_block_editor_assets() {
        wp_enqueue_script(
            'stylish-cost-calculator-premium-gutenberg-block',
            SCC_LIB_URL . '/gutenberg-block/js/gutenberg-block.es5.js',
            [ 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor' ],
            filemtime( SCC_LIB_DIR . '/gutenberg-block/js/gutenberg-block.es5.js' ),
            true
        );
        wp_enqueue_style(
            'stylish-cost-calculator-premium-gutenberg-block',
            SCC_LIB_URL . '/gutenberg-block/css/gutenberg-block.css',
            [ 'wp-edit-blocks' ],
            filemtime( SCC_LIB_DIR . '/gutenberg-block/css/gutenberg-block.css' )
        );
        wp_localize_script(
            'stylish-cost-calculator-premium-gutenberg-block',
            'stylish_cost_calculator_calculator_data',
            $this->get_calc_data()
        );
    }
    private function get_calc_data() {
        require SCC_DIR . '/admin/controllers/formController.php';
        $form_c = new formController();
        $calcs  = $form_c->read_all_gutenberg();

        return $calcs;
    }
}
