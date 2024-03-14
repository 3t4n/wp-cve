<?php
/*
 * @author WP doin
 * @package acf-recent-posts-widget/templates
 * @version 4.3
 * 
 * Don't remove these 3 lines, they contain the variables passed over to the query
 */
global $acf_rpw_instance, $acf_rpw_args;
extract($acf_rpw_instance );
$args = $acf_rpw_args;
?>
</ul>
<?php
/*
 * Display the after posts block
 *
 * @param string $after_posts user specified content
 */
if ( !empty( $after_posts ) ): ?>
	<div class="acf-rpw-after-loop">
		<?php echo htmlspecialchars_decode( $after_posts ); ?>
	</div>
<?php endif; ?>
</div>
<?php 
/**
 * @param string after_widget WP generated content as specified in the register_sidebar function where the widget was used
 */
echo $args['after_widget']; 