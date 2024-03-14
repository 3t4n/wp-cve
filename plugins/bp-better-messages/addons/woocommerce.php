<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Better_Messages_WooCommerce' ) ) {

    class Better_Messages_WooCommerce
    {

        public static function instance()
        {

            static $instance = null;

            if (null === $instance) {
                $instance = new Better_Messages_WooCommerce();
            }

            return $instance;
        }

        public function __construct(){
            if( ! defined('BM_DEV') ) return;
            add_filter('better_messages_get_unique_conversation', array( $this, 'unique_conversation_id' ), 20, 3 );
            add_filter('better_messages_rest_thread_item', array( $this, 'thread_item' ), 10, 5 );
        }

        public function unique_conversation_id( $conversation_id, $key, $user_id ){
            if( $conversation_id ) return $conversation_id;
            if( ! empty( $key ) ) {
                if (str_starts_with($key, 'wc_product_')) {
                    $product_id = str_replace('wc_product_', '', $key);

                    $product = wc_get_product( $product_id );
                    if( $product ) {
                        $post = get_post($product->get_id());

                        if ($post->post_type === 'product') {
                            $subject = sprintf( 'Questions about %s', $product->get_title() );

                            $user_ids = [ $user_id, $post->post_author ];
                            $thread_id = Better_Messages()->functions->get_unique_conversation_id( $user_ids, $key, $subject );

                            if( $thread_id ){
                                Better_Messages()->functions->update_thread_meta( $thread_id, 'special_subject', $key );
                                return $thread_id;
                            }
                        }
                    }
                }
            }

            return $conversation_id;
        }

        public function thread_item( $thread_item, $thread_id, $thread_type, $include_personal, $user_id ){
            if( $thread_type !== 'thread'){
                return $thread_item;
            }

            $special_subject = Better_Messages()->functions->get_thread_meta( $thread_id, 'special_subject' );

            if( ! empty( $special_subject ) ){
                if( str_starts_with( $special_subject, 'wc_product_' ) ){
                    $product_id = str_replace( 'wc_product_', '', $special_subject );
                    $thread_info = '';
                    if( isset( $thread_item['threadInfo'] ) ) $thread_info = $thread_item['threadInfo'];

                    $thread_info .= $this->thread_info( $product_id );

                    $thread_item['threadInfo'] = $thread_info;
                }
            }

            return $thread_item;
        }

        public function thread_info( $product_id ){
            $product = wc_get_product( $product_id );
            $image_id = $product->get_image_id();
            $image_src = wp_get_attachment_image_src( $image_id, [100, 100] );

            $image         = false;
            $title         = $product->get_title();
            $url           = $product->get_permalink();
            $price         = $product->get_price(); //wc_price( $product->get_price() );
            $regular_price = $product->get_regular_price(); //wc_price( $product->get_price() );
            if( $image_src ){
                $image = $image_src[0];
            }

            $final_price = wc_price($price);
            if( $price !== $regular_price ){
                $final_price .= '<del>' . wc_price( $regular_price ) . '</del>';
            }
            //var_dump( wc_price( $price ), wc_price( $regular_price ) );

            $html = '<div class="bm-product-info">';

            if( $image ){
                $html .= '<div class="bm-product-image">';
                    $html .= '<a href="' . $url . '" target="_blank"><img src="' . $image . '" alt="' . $title . '" /></a>';
                $html .= '</div>';
            }

            $html .= '<div class="bm-product-details">';
                $html .= '<div class="bm-product-title"><a href="' . $url . '" target="_blank">' . $title . '</a></div>';
                $html .= '<div class="bm-product-price">' . $final_price . '</div>';
            $html .= '</div>';

            $html .= '</div>';

            //var_dump($image);
            //var_dump($title);
            //var_dump($price);
            //var_dump($regular_price);

            return $html;
        }

    }
}

