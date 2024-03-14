<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Meta_Boxes_Settings' ) ) {

    class WModes_Admin_Meta_Boxes_Settings {

        public static function init() {

            add_filter( 'wmodes-admin/get-settings-section-panels', array( new self(), 'get_panel' ), 10 );
        }

        public static function get_panel( $in_fields ) {

            $in_fields[] = array(
                'id' => 'any_id',
                'type' => 'panel',
                'last' => true,
                'white_panel' => false,
                'panel_size' => 'smaller',
                'width' => '100%',
                'merge_fields' => false,
                'fields' => self::get_fields( array() ),
            );


            return $in_fields;
        }

        private static function get_fields( $in_fields ) {

            $in_fields[] = array(
                'id' => 'any_id',
                'type' => 'paneltitle',
                'full_width' => true,
                'center_head' => true,
                'title' => esc_html__( 'Product Data Metabox', 'wmodes-tdm' ),
                'desc' => esc_html__( 'Use these settings to enable catalog mode, product pricing, product settings &amp; promotions on "Product Data" metabox', 'wmodes-tdm' ),
            );

            $in_fields[] = array(
                'id' => 'meta_boxes',
                'type' => 'columns-field',
                'columns' => 3,
                'merge_fields' => true,
                'fields' => array(
                    array(
                        'id' => 'catalog_mode',
                        'type' => 'select2',
                        'column_size' => 1,
                        'tooltip' => esc_html__( 'Enables catalog mode settings on "Product Data" metabox', 'wmodes-tdm' ),
                        'column_title' => esc_html__( 'Enable Catalog Mode', 'wmodes-tdm' ),
                        'default' => array( 'yes' ),
                        'options' => array(
                            'yes' => esc_html__( 'Yes', 'wmodes-tdm' ),
                            'no' => esc_html__( 'No', 'wmodes-tdm' ),
                        ),
                        'width' => '100%',
                    ),
                    array(
                        'id' => 'product_pricing',
                        'type' => 'select2',
                        'column_size' => 1,
                        'tooltip' => esc_html__( 'Enables product pricing settings on "Product Data" metabox', 'wmodes-tdm' ),
                        'column_title' => esc_html__( 'Enable Product Pricing', 'wmodes-tdm' ),
                        'default' => array( 'yes' ),
                        'options' => array(
                            'yes' => esc_html__( 'Yes', 'wmodes-tdm' ),
                            'no' => esc_html__( 'No', 'wmodes-tdm' ),
                        ),
                        'width' => '100%',
                    ),
                    array(
                        'id' => 'product_options',
                        'type' => 'select2',
                        'column_size' => 1,
                        'tooltip' => esc_html__( 'Enables product settings &amp; promotions settings on "Product Data" metabox', 'wmodes-tdm' ),
                        'column_title' => esc_html__( 'Enable Product Settings &amp; Promotions', 'wmodes-tdm' ),
                        'default' => array( 'yes' ),
                        'options' => array(
                            'yes' => esc_html__( 'Yes', 'wmodes-tdm' ),
                            'no' => esc_html__( 'No', 'wmodes-tdm' ),
                        ),
                        'width' => '100%',
                    ),
                ),
            );

            return $in_fields;
        }

    }

    WModes_Admin_Meta_Boxes_Settings::init();
}