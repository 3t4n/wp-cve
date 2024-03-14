<?php

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Templates {
    /**
     * @var null
     */
    public static $instance = null;

    /**
     * @return null|Templates
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return array|mixed|object|\WP_Error
     */
    public function get() {

        if ( defined( 'STAX_DEV' ) && STAX_DEV ) {
            delete_transient( 'stax-default-templates' );
        }

        $templates = [];

        if ( get_transient( 'stax-default-templates' ) ) {
            $templates = (array) json_decode( get_transient( 'stax-default-templates' ), true );
        } else {
            $templates_data = wp_remote_get( 'https://staxbuilder.com/api/get_templates.php' );

            if ( ! is_wp_error( $templates_data ) ) {
                $templates = wp_remote_retrieve_body( $templates_data );
                // Check for error
                if ( ! is_wp_error( $templates ) ) {
                    set_transient( 'stax-default-templates', $templates, 60 * 60 * 24 );
                    $templates = (array) json_decode( $templates );
                }
            }
        }

        return $templates;
    }

    public function get_by_id( $id ) {

        $template = false;

        if ( ! is_numeric( $id ) ) {
            return $template;
        }

        $transient_name = 'stax-default-template-' . $id;
        if ( defined( 'STAX_DEV' ) && STAX_DEV ) {
            delete_transient( $transient_name );
        }

        if ( get_transient( $transient_name ) ) {
            $template = json_decode( get_transient( $transient_name ), true );
        } else {
            $template_data = wp_remote_get( 'https://staxbuilder.com/api/get_templates.php?id=' . $id );

            if ( ! is_wp_error( $template_data ) ) {
                $template = wp_remote_retrieve_body( $template_data );

                // Check for error
                if ( ! is_wp_error( $template ) ) {
                    set_transient( $transient_name, $template, 60 * 60 * 24 );
                    $template = json_decode( $template );
                }
            }
        }

        if ( $template ) {
            foreach ( $template->pack->containers as $container ) {
                if ( ! $container instanceof \stdClass ) {
                    continue;
                }
                $template->pack->containers->{$container->uuid} = Editor::instance()->matchAndMergeFields( $container );
            }

            foreach ( $template->pack->columns as $column ) {
                if ( ! $column instanceof \stdClass ) {
                    continue;
                }
                $template->pack->columns->{$column->uuid} = Editor::instance()->matchAndMergeFields( $column );
            }

            if ( isset( $template->pack->elements ) ) {
                foreach ( $template->pack->elements as $element ) {
                    if ( ! $element instanceof \stdClass ) {
                        continue;
                    }
                    $template->pack->elements->{$element->uuid} = Editor::instance()->matchAndMergeFields( $element );
                }
            }
        }

        return $template;
    }

    public function rest_get_by_id( \WP_REST_Request $request ) {
        $data = $request->get_params();

        if ( ! isset( $data['id'] ) || ! is_numeric( $data['id'] ) ) {
            return $this->response( self::STATUS_FAILED );
        }

        return $this->get_by_id( $data['id'] );
    }
}
