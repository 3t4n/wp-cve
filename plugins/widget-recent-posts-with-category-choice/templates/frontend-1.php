<?php
/*
Default: Template for Advanced Recent Posts Widget
Widget Frontend Code
Plugin: Recent Posts Widget Advanced
Since: 1.2
Author: KGM Servizi
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

echo $args['before_widget'];

if ( $title ) {
    echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
}
?>
<ul>
    <?php foreach ( $query->posts as $recent_post ) : ?>
        <?php
        $post_title = get_the_title( $recent_post->ID );
        $title      = ( ! empty( $post_title ) ) ? $post_title : __( '(no title)' );    
        ?>
        <li>
            <?php if ($show_thumb) { ?>
                <a href="<?php the_permalink( $recent_post->ID ); ?>"><?php echo get_the_post_thumbnail( $recent_post->ID, array(50, 50), array('style' => 'margin: 5px 5px 0 0;') ); ?></a>
            <?php } ?>
            <a href="<?php the_permalink( $recent_post->ID ); ?>"><?php echo esc_html( $title ) ; ?></a>
            <?php if ( $show_date ) : ?>
                <span class="post-date"><?php echo get_the_date( '', $recent_post->ID ); ?></span>
            <?php endif; ?>
            <?php if ( $show_author ) : ?>
                <?php if ( $show_date ) : ?>
                    <span class="post-date">- <?php echo get_the_author( '', $recent_post->ID ); ?></span>
                <?php else: ?>
                    <span class="post-date"><?php echo get_the_author( '', $recent_post->ID ); ?></span>
                <?php endif; ?>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
<?php
echo $args['after_widget'];
