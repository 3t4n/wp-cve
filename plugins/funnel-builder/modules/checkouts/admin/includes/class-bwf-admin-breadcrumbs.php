<?php
/**
 * Class to control breadcrumb and its behaviour accross the buildwoofunnels
 * @author buildwoofunnels
 */
if ( ! class_exists( 'BWF_Admin_Breadcrumbs' ) ) {

	class BWF_Admin_Breadcrumbs {

		private static $ins = null;

		/**
		 * @var array nodes use to contain all the nodes
		 */
		public static $nodes = [];

		/**
		 * @var array ref used to contain refs to pass to the urls
		 */
		public static $ref = [];

		/**
		 * Insert a single node into the property
		 *
		 * @param $config [] of the node getting registered
		 */
		public static function register_node( $config ) {
			self::$nodes[] = wp_parse_args( $config, [ 'class' => '', 'link' => '', 'text' => '' ] );
		}


		/**
		 * Insert a referral property so that we can populate the referral accross all urls.
		 *
		 * @param $key
		 * @param $val
		 */
		public static function register_ref( $key, $val ) {
			self::$ref[ $key ] = $val;
		}

		/**
		 * Render HTML for all the registerd nodes
		 */
		public static function render() {
			if ( ! empty( self::$nodes ) ) {
				?>
				<ul><?php foreach ( self::$nodes as $node ) { ?>
						<li class="<?php echo $node['class'] ?>">
							<?php if ( ! empty( $node['link'] ) ) { ?>
								<a href="<?php echo esc_url( $node['link'] ) ?>"><?php echo wp_kses_post( $node['text'] ); ?></a>
							<?php } else {
								echo wp_kses_post( $node['text'] );
							} ?>

						</li>
					<?php } ?>
				</ul>
				<?php
			}
		}


		/**
		 * Add the registered referral to the url passed
		 * ref should contain the query param as key and value as value
		 *
		 * @param $url URL to add refs to
		 *
		 * @return string modified url
		 */
		public static function maybe_add_refs( $url ) {
			if ( empty( self::$ref ) ) {
				return $url;
			}

			return add_query_arg( self::$ref, $url );
		}


	}
}
