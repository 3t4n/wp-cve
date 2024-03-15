<?php
/**
 * add small style and scripts in footer
 *
 */
if ( ! function_exists( 'ndc_widget_wp_footer' ) ) :

    function ndc_widget_wp_footer(){
        ?>
        <style type="text/css">
            .nepali-date-converter,.nepali-date-converter-result,.nepali-date-converter-trigger{
                margin-top: 10px;
            }
        </style>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                $(document).on("click",".nepali-date-converter-trigger",function(){
                    if($(this).hasClass('from-nep') || $(this).hasClass('from-eng') ){
                        var from = 'from-eng';
                        if($(this).hasClass('from-nep')){
                            from = 'from-nep';
                        }
                        var lang = 'nep_char';
                        if($(this).data('lang')){
                            lang = $(this).data('lang');
                        }
                        var year = $(this).siblings('.year').val();
                        var month = $(this).siblings('.month').val();
                        var day = $(this).siblings('.day').val();
                        var result_format = $(this).data('result');
                        var result_html = $(this).closest('.nepali-date-converter').siblings('.nepali-date-converter-result');
                        jQuery.ajax({
                            type: "POST",
                            url: "<?php echo admin_url('admin-ajax.php'); ?>",
                            data: {
                                action: 'nepali_date_converter_ajax',
                                from : from,
                                year : year,
                                month : month,
                                day : day,
                                result_format : result_format,
                                lang : lang
                            },
                            beforeSend: function(){
                                var wait		= 'Please wait...';
                                if('from-eng' == from ){
                                    wait		= 'कृपया पर्खनुहोस्...';
                                }
                                result_html.html(wait);
                            },
                            success:function(response){
                                if(response){
                                    result_html.html(response);
                                }
                            }
                        })
                    }
                });
            });
        </script>
        <?php
    }
endif;