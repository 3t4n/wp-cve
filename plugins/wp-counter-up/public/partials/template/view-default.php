<div id="lgx_counter_up_app_<?php echo $lgx_app_id;?>" class="lgx_counter_up_app">

    <?php  echo(('yes'==$lgx_generator_meta['lgx_preloader_en']) ? '<div id="lgx_lsw_preloader_'.$lgx_app_id.'"" class="lgx_lsw_preloader"> <img src="'.((!empty($lgx_generator_meta['lgx_preloader_icon'])) ? $lgx_generator_meta['lgx_preloader_icon']: $lgx_lsw_loading_icon).'" /></div>' : ''); ?>

    <div class="lgx_counter_up lgx_counter_up_free">
        <div class="lgx_app_inner lgx_app_layout_<?php echo $lgx_showcase_type;?> ">
            <div class="lgx_app_<?php echo $lgx_generator_meta['lgx_section_container'];?>">

                <?php (($lgx_generator_meta['lgx_header_en']=='yes') ? include '_header.php' : ''); ?>
             
                <div id="lgx_app_content_wrap_<?php echo $lgx_app_id. rand ( 100, 999 );?>" class="lgx_app_content_wrapper lgx_counter_content lgx_layout_order_<?php echo $lgx_layout_order ;?> lgx_item_floating_<?php echo $lgx_item_floating;?> lgx_item_info_align_<?php echo $lgx_generator_meta['lgx_item_info_align'];?>"  >

                    <div class="lgx_app_item_row">
                        <?php

                    // wp_die(LGX_LS_WP_PLUGIN);

                        //$lgx_logo_order      =  ( isset($lgx_item_sort_order) ? $lgx_item_sort_order : 'ASC');
                        //$lgx_logo_order_by    = 'title';
                        $lgx_item_limit      = ((($lgx_generator_meta['lgx_item_limit']  <= 0) ) ? -1 : $lgx_generator_meta['lgx_item_limit']);
                        if(LGX_WCU_WP_PLUGIN != 'wp-counter-up-pro'){
                            if(($lgx_item_limit == -1) || ($lgx_item_limit >= 10 )  ){
                                $lgx_item_limit = 10;
                            }
                        }
                        $lgx_from_category   = $lgx_generator_meta['lgx_from_category'];

                        $lgx_counter_up_args = array(
                            'post_type'         => array( 'lgx_counter' ),
                            'post_status'       => array( 'publish' ),
                            'order'             => $lgx_generator_meta['lgx_item_sort_order'],
                            'orderby'           => $lgx_generator_meta['lgx_item_sort_order_by'],
                            'posts_per_page'    => $lgx_item_limit
                        );


                        // Category to Array Convert
                        if( !empty($lgx_from_category) && $lgx_from_category != '' && $lgx_from_category != 'all'  ){
                            $lgx_from_category = trim($lgx_from_category);
                            $lgx_from_category_arr   = explode(',', $lgx_from_category);

                            if(is_array($lgx_from_category_arr) && sizeof($lgx_from_category_arr) > 0){
                                $lgx_counter_up_args['tax_query'] = array(
                                    array(
                                        'taxonomy' => 'lgxcountercat',
                                        //  'field'    => 'slug',
                                        'field'    => 'id',
                                        'terms'    => $lgx_from_category_arr
                                    )
                                );
                            }
                        }

                        // The  Query
                        $lgx_counter_up_loop = new WP_Query( $lgx_counter_up_args );

                        if ( $lgx_counter_up_loop->have_posts() ){
                            while ( $lgx_counter_up_loop->have_posts() ) : $lgx_counter_up_loop->the_post();


                                // Add Item
                                include('_item.php');


                            endwhile;
                            wp_reset_postdata();// Restore original Post Data
                        } // Check post exist
                        else{
                            _e('There are no counter item. Please add some counter Item', 'wp-counter-up');
                        }
                        ?>
                    </div> <!--//.APP CONTENT INNER END-->                
                </div> <!-- //.CONTENT WRAP END-->

            </div><!--//.APP CONTAINER END-->
        </div> <!--//.INNER END-->
    </div> <!-- APP CONTAINER END -->

</div> <!--//.APP END-->