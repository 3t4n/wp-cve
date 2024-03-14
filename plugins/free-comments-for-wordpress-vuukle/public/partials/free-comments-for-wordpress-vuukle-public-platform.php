<?php

/**
 * This file is used to mark up the public-facing aspects of the plugin.
 *
 * @category   PHP
 * @package    Free_Comments_For_Wordpress_Vuukle
 * @subpackage Free_Comments_For_Wordpress_Vuukle/public/partials
 * @author     Vuukle <info@vuukle.com>
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 * @link       https://vuukle.com
 * @since      5.0
 */
if ( ! empty( $this ) && $this instanceof Free_Comments_For_Wordpress_Vuukle_Public ) {
    function get_the_post_id() {
        if (in_the_loop()) {
            $post_id = get_the_ID();
        } else {
            global $wp_query;
            $post_id = $wp_query->get_queried_object_id();
        }
        return $post_id;
    }
    $post_id   = get_the_post_id();
    $post_info = get_post( $post_id );
	$author    = get_the_author_meta( 'display_name', $post_info->post_author );
	$tags      = wp_get_post_tags( $post_id, [ 'number' => 3 ] );
	$tags      = ! empty( $tags ) ? array_map( function ( $obj ) {
		return $obj->name;
	}, $tags ) : null;
	$tags      = ! empty( $tags ) ? implode( ', ', $tags ) : '';
	?>
    <script data-cfasync="false">
        var VUUKLE_CONFIG = {
            apiKey: "<?php echo esc_html( $this->app_id ); ?>",
            articleId: "<?php echo esc_html( $post_id ); ?>",
            title: <?php echo "'" . esc_html( get_the_title( $post_id ) ) . "'"; ?>,
            tags: "<?php echo esc_html( str_replace( [ '"', "'" ], '', quotemeta( stripslashes( $tags ) ) ) ); ?>",
            author: "<?php echo esc_html( str_replace( [
				'"',
				"'"
			], '', quotemeta( stripslashes( $author ) ) ) ); ?>",
			<?php if ('1' === $this->settings['save_comments']) : ?>
            wordpressSync: true,
            eventHandler: function (e) {
                if (e.eventType === 'wpSync') {
                    function loadXMLDoc() {
                        var xmlHttp = new XMLHttpRequest(),
                            url = '<?=admin_url( 'admin-ajax.php' )?>',
                            cache = false,
                            formData = new FormData();
                        formData.append("action", "saveCommentToDb");
                        formData.append("comment_ID", e.comment_ID);
                        formData.append("comment_post_ID", e.comment_post_ID);
                        formData.append("comment_author", e.comment_author);
                        formData.append("comment_author_email", e.email);
                        formData.append("comment_author_url", e.comment_author_url);
                        formData.append("comment_content", e.comment_content);
                        formData.append("comment_type", e.comment_type);
                        formData.append("comment_parent_ID", e.comment_parent);
                        formData.append("user_id", e.user);
                        formData.append("comment_author_IP", e.comment_author_IP);
                        formData.append("comment_agent", e.comment_agent);
                        formData.append("comment_approved", e.comment_approved);
                        formData.append("comment_date", e.comment_date);
                        formData.append("comment_date_gmt", e.comment_date_gmt);
                        formData.append("comment_karma", e.comment_karma);
                        formData.append("_wpnonce", "<?php echo esc_attr( wp_create_nonce() ); ?>");
                        xmlHttp.open("POST", url, cache);
                        xmlHttp.send(formData);
                    }

                    loadXMLDoc();
                }
            },
			<?php endif; ?>
        };
    </script>
    <script src="https://cdn.vuukle.com/platform.js" async data-cfasync="false"></script>
<?php } ?>