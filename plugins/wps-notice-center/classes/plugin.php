<?php

namespace WPS\WPS_Notice_Center;

class Plugin {

	use Singleton;

	protected function init() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_scripts' ) );
		add_action( 'admin_notices', array( $this, 'sample_admin_notice__success' ), 1 );
		add_action( 'admin_footer', array( $this, 'admin_footer' ), 9999 );
	}

	/**
	 * Enqueue css
	 */
	public static function admin_enqueue_scripts() {
		wp_enqueue_style( 'wps-notice-center', WPS_NOTICE_CENTER_URL . 'assets/css/style.css' );
		wp_enqueue_script( 'postbox' );
	}

	public function sample_admin_notice__success() { ?>
        <div class="wrap wps-notice-center clearfix" style="display: none">
            <div id="all-notices" class="postbox-container">
                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox closed">
                        <button type="button" class="handlediv" aria-expanded="false"><span class="screen-reader-text"><?php _e( 'Open/close the WPS Notice Center section', 'wps-notice-center' ); ?></span>
                            <span class="toggle-indicator" aria-hidden="true"></span></button>
                        <div class="notice-block"><span class="text-notice"><?php _e( 'See the notices', 'wps-notice-center' ); ?> <span class="counter-bg"><span class="pending-count"></span></span></span></div>
                        <div class="inside">
                            <div class="notices"></div>
                            <div class="easter-eggs"><?php _e( 'Nicely hidden notices with', 'wps-notice-center' ); ?> <span class="dashicons dashicons-heart"></span> par <a href="https://www.wpserveur.net/?refwps=14&campaign=wpsnoticecenter" target="_blank" title="<?php _e( 'WordPress specialized hosting', 'wps-notice-center' ); ?>">WPServeur</a> | WPS Notice Center</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}

	public function admin_footer() { ?>
        <script type="text/javascript">
            (function ($) {
                $(function () {
                    $(window).on('load', function () {
                        var html = [];
                        $(".wrap").children('.error').each(function () {
                            html.push($(this).prop('outerHTML'));
                        });

                        $(".wrap").children('.updated').each(function () {
                            html.push($(this).prop('outerHTML'));
                        });

                        $(".wrap").children('.notice').each(function () {
                            html.push($(this).prop('outerHTML'));
                        });

                        var goodhtml = [];
                        $.each(html, function (index, value) {
                            if (goodhtml.indexOf(value) === -1) {
                                goodhtml.push(value);
                            }
                        });

                        $.each(goodhtml, function (index, value) {
                            $(value).appendTo($(".notices"));
                        });

                        $('#all-notices').find('.pending-count').text(goodhtml.length);

                        if( goodhtml.length > 1 ) {
                            $('.wps-notice-center').show();
                        }

                        $( document ).ajaxComplete(function () {
                            $('#all-notices').find('.pending-count').text($('.wps-notice-center').find('.notices').children('div').length);
                        });

                        $('.postbox').on( 'click', '.handlediv', function () {
                            var $this = $(this).closest('div');

                            var pathname = window.location.pathname;
                            if( '/wp-admin/index.php' === pathname ) {
                                if ( $this.hasClass('closed') ) {
                                    $this.addClass('closed');
                                    $this.removeClass('open');
                                    $this.attr( 'aria-expanded', 'false' );
                                    $('#all-notices').find('.handlediv').show();
                                } else {
                                    $this.removeClass('closed');
                                    $this.addClass('open');
                                    $this.attr( 'aria-expanded', 'true' );
                                    $('#all-notices').find('.handlediv').show();
                                }
                            } else {
                                if ( ! $this.hasClass('closed') ) {
                                    $this.addClass('closed');
                                    $this.removeClass('open');
                                    $this.attr( 'aria-expanded', 'false' );
                                    $('#all-notices').find('.handlediv').show();
                                } else {
                                    $this.removeClass('closed');
                                    $this.addClass('open');
                                    $this.attr( 'aria-expanded', 'true' );
                                    $('#all-notices').find('.handlediv').show();
                                }
                            }


                        });

                        $( '.notice.is-dismissible' ).on('click', '.notice-dismiss', function ( event ) {
                            var container = $(this).closest('div');
                            if ( ! container.hasClass('grr') ) {
                                container.addClass('grr');
                                container.remove();
                                $( '.notice.is-dismissible' ).not(".grr").find('.notice-dismiss').trigger('click');
                            }
                        });

                        $( '.connection-banner-dismiss' ).on( 'click', function() {
                            var container = $(this).parent().parent();
                            if ( ! container.hasClass('grr') ) {
                                container.addClass('grr');
                                container.remove();
                                $( '.jp-wpcom-connect__container' ).not(".grr").find('.connection-banner-dismiss').trigger('click');
                            }
                        });
                    });
                });
            })(jQuery);
        </script>
        <style>
            .notice, .error, .updated {
                display: none;
            }
            .plugin-update .notice,#all-notices, #all-notices .notice, #all-notices .error, #all-notices .updated {
                display: block;
            }
            .plugins .updated {
                display: table-row;
            }
            #all-notices .hidden, #setting-error-tgmpa {
                display: none;
            }
            .clearfix:after {
                visibility: hidden;
                display: block;
                font-size: 0;
                content: " ";
                clear: both;
                height: 0;
            }
            .clearfix { display: inline-block; }
            /* start commented backslash hack */
            * html .clearfix { height: 1%; }
            .clearfix { display: block; }
            /* close commented backslash hack */
        </style>
		<?php
	}
}