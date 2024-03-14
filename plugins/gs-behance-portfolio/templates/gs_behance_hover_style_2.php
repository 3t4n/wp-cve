<?php
namespace GSBEH;
?>

<div class="gs-containeer">
	<div class="gs-roow">
<?php
	foreach( $gs_behance_shots as $gs_beh_single_shot ) {
        
        $bfields = unserialize($gs_beh_single_shot['bfields']);

        if ( ! empty( $atts['field'] ) ) {
            if (in_array($atts['field'],  array_column($bfields,'name'))){ ?>

                <div class="<?php echo esc_attr($columnClasses); ?> beh-projects">

                    <div class="gs_beh-content-wrap">
                        <a href="<?php echo esc_url($gs_beh_single_shot[ 'url' ]); ?>" target="<?php echo esc_attr($shortcode_settings['link_target']); ?>">
                            <?php echo plugin()->helpers->get_shot_thumbnail( $gs_beh_single_shot['thum_image'], '' ); ?>
                        </a>

                        <a class="gh_beh_redirect_link" href=""><i class="fa fa-eye"></i></a>

                        <div class="gs_beh-content">
                            <div class="gs_beh-content-left">                              
                                <h3 class="gs_beh-title"><?php echo esc_html( $gs_beh_single_shot['name'] ); ?></h3>
                                <h4 class="gs_beh-user-name"><?php echo esc_html( $gs_beh_single_shot['beusername'] ); ?></h4>
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
            }
        } 
        else { ?>

           <div class="<?php echo esc_attr($columnClasses); ?> beh-projects">
                <div class="gs_beh-content-wrap">                   
                    <a href="<?php echo esc_url($gs_beh_single_shot[ 'url' ]); ?>" target="<?php echo esc_attr($shortcode_settings['link_target']); ?>">
                        <?php echo plugin()->helpers->get_shot_thumbnail( $gs_beh_single_shot['thum_image'], '' ); ?>
                    </a>

                    <a class="gh_beh_redirect_link" href=""><i class="fa fa-eye"></i></a>

                    <div class="gs_beh-content">
                        <div class="gs_beh-content-left">                         
                            <h3 class="gs_beh-title"><?php echo esc_html( $gs_beh_single_shot['name'] ); ?></h3>
                            <h4 class="gs_beh-user-name"><?php echo esc_html( $gs_beh_single_shot['beusername'] ); ?></h4>
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
        }
   
    } // foreach
    ?>
   </div><?php
    do_action('gs_behance_custom_css'); ?>
</div>
