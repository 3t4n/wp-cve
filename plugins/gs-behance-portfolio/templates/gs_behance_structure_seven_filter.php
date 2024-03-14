<?php
    namespace GSBEH;

    plugin()->builder->ajax = false;
    $filterAllLabel         = plugin()->builder->get( 'filter_all_label', 'All' );
    $wpdb                   = plugin()->builder->get_wp_db();
    $tableName              = plugin()->db->get_data_table();
?>
<div class="gs-containeer">
	<div class="gs-roow">
    <?php 
        if ( ! empty( $shortcode_settings['userid'] ) ) {
            $gs_behance_fields = $wpdb->get_results( "SELECT * FROM {$tableName} WHERE beusername='{$shortcode_settings['userid']}' ORDER BY time ASC LIMIT {$shortcode_settings['count']} ",ARRAY_A);
        }
        else {
            $gs_behance_fields = $wpdb->get_results( "SELECT * FROM {$tableName} ORDER BY id DESC LIMIT {$atts['count']} ",ARRAY_A);
        } 
    ?>
       <div class="filter">
            <div class="button-group filters-button-group">
                <button class="button is-checked" data-filter="*"><?php echo esc_attr( $filterAllLabel ); ?></button>
<?php


            if( !empty( $gs_behance_fields ) ) {
                foreach( $gs_behance_fields as $gs_beh_single_shot ) {
                    $bfields = unserialize($gs_beh_single_shot['bfields']);
                    foreach ( $bfields  as  $bcat) {
                        $bcat_termname[] = $bcat['name'] ?? '';
                    }  
                }

                $tm_fields_list = array_unique($bcat_termname);

                foreach($tm_fields_list as $field):
                    if ( ! empty( $field ) ) :
                        $be_field_string = str_replace(' ', '-', $field);
                        $be_field_string = str_replace('/', '-', $be_field_string);
                        $be_field_string = strtolower($be_field_string); ?>
                         <button class="button" data-filter="<?php echo '.' . esc_attr($be_field_string); ?>">
                            <?php echo esc_html($field); ?>
                         </button>
                         <?php
                    endif;
                endforeach;
            }                                
            ?>
            </div>
        </div>
        <div class="grid">
<?php
        	foreach( $gs_behance_shots as $gs_beh_single_shot ) {

                $bfields    = unserialize($gs_beh_single_shot['bfields']);                
                $categories = plugin()->helpers->fetch_project_categories( $gs_beh_single_shot['beid'] );

                $classes = [
                    $columnClasses,
                    $categories,
                    'beh-projects'
                ];
               
                    ?>
                <div class="<?php echo esc_attr( join(' ', $classes) ); ?>" data-category="<?php echo esc_attr($categories); ?>">

                <div class="gs_beh-content-wrap">

                <a href="<?php echo esc_attr($gs_beh_single_shot[ 'url' ]); ?>" target="<?php echo esc_attr($shortcode_settings['link_target']); ?>">
                    <?php echo plugin()->helpers->get_shot_thumbnail( $gs_beh_single_shot['thum_image'], '' ); ?>
                </a>

                <div class="gs_beh-content">
                    <div class="gs_beh-content-left">                          
                        <h3 class="gs_beh-title"><?php echo esc_html( $gs_beh_single_shot['name'] ); ?></h3>
                    </div>

                    <ul class="gs_beh-credentials">
                        <li class="beh-app"><i class="fa fa-thumbs-o-up"></i><span class="number"><?php echo number_format_i18n( $gs_beh_single_shot['blike'] ); ?></span></li>
                        <li class="beh-views"><i class="fa fa-eye"></i><span class="number "><?php echo number_format_i18n( $gs_beh_single_shot['bview'] ); ?></span></li>
                        <li class="beh-comments"><i class="fa fa-comment-o"></i><span class="number"><?php echo number_format_i18n( $gs_beh_single_shot['bcomment'] ); ?></span></li>
                    </ul>
                </div>
           </div>
                </div>
                <?php
            } ?>
    
        </div>
   </div><?php 
    do_action('gs_behance_custom_css'); ?>
</div>
