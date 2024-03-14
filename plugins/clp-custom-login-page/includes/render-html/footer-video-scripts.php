<?php 

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

$video_yt = esc_url( get_option('clp_background-video_yt', '') );
$video_local = esc_attr( get_option('clp_background-video_local', '') );

$source = empty( $video_yt  ) ? empty( $video_local ) ? null : 'video/mp4' : 'YouTube';

if ( $source ) {

    $video_autoloop	= get_option('clp_background-video_loop', true);
    $video_poster  = wp_get_attachment_image_src( get_option('clp_background-video_thumb', ''), 'full' );

    if ( !empty( $video_poster ) ) {
        $video_poster = $video_poster[0];       
    }
    
    ob_start();  ?>
    <script type='text/javascript' src='<?php echo CLP_PLUGIN_PATH;?>assets/js/external/vidim.min.js?ver=<?php echo CLP_VERSION;?>'></script>

    <script>
        <?php 
        switch ( $source ) {
            case 'YouTube': ?>
                var src = '<?php echo $video_yt; ?>'.split('?t=');
                var myBackground = new vidim( '.login-background', {
                    src: src[0],
                    type: 'YouTube',
                    poster: '<?php echo esc_url( $video_poster ); ?>',
                    quality: 'hd1080',
                    muted: true,
                    loop: <?php echo $video_autoloop ? 'true' : 'false' ; ?>,
                    startAt: src.length > 1 ? src[1] : '0',
                    showPosterBeforePlay: <?php echo $video_poster ? 'true' : 'false' ; ?>
                });

            <?php 
                break;

            case 'video/mp4':
                $video_url = $video_local;
                $video_url = wp_get_attachment_url( $video_url ); ?>
                var myBackground = new vidim( '.login-background', {
                    src: [
                        {
                        type: 'video/mp4',
                        src: '<?php echo esc_url( $video_url ); ?>',
                        },
                    ],
                    poster: '<?php echo esc_url( $video_poster ); ?>',
                    showPosterBeforePlay: <?php echo $video_poster ? 'true' : 'false' ; ?>,
                    loop: <?php echo $video_autoloop ? 'true' : 'false' ; ?>
                });
                <?php 
                break;
            default:
                break;
        } ?>
    </script>
    <?php

    $html = ob_get_clean();
}