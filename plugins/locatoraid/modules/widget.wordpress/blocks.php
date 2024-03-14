<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
if( ! class_exists('Locatoraid_Blocks') )
{
class Locatoraid_Blocks
{
	public $plugin;

	public function __construct( $plugin )
	{
		$this->plugin = $plugin;
		add_action(	'init', array($this, 'init') );
	}

	public function init()
	{
		if( ! function_exists('register_block_type') ){
			return;
		}

		$uri = plugins_url( 'modules/widget.wordpress/locatoraid-blocks.js?hcver=' . LC3_VERSION, $this->plugin->full_path );
		wp_register_script( 'locatoraid_block_map', $uri, [
			'wp-blocks',
			'wp-element',
			'wp-components',
			'wp-editor'
		]);

	// map
		$view = $this->plugin->hcapp->make('/front/view/shortcode');

		$frontView = $this->plugin->hcapp->make('/front/view');
		$shortcodeParams = $frontView->params();

		$attr = [];
		foreach( $shortcodeParams as $k => $v ){
			$attr[ $k ] = [ 'type' => 'string', 'default' => $v ];
		}

		$blockRegistry = WP_Block_Type_Registry::get_instance();

		if( ! $blockRegistry->get_registered( 'locatoraid/locatoraid-map' ) ){
			register_block_type( 'locatoraid/locatoraid-map', [
				'title' => 'Locatoraid Store Locator Map',
				'editor_script' => 'locatoraid_block_map',
				'render_callback' => [$view, 'render'],
				'attributes' => $attr,
				]
			);
		}

		$pages = array();
		global $wpdb;
		$shortcode = 'locatoraid';
		$block = 'locatoraid/locatoraid-map';

		$pages = $wpdb->get_results( 
			"
			SELECT 
				ID 
			FROM $wpdb->posts 
			WHERE 
				( post_type = 'post' OR post_type = 'page' ) 
				AND 
				(
					( post_content LIKE '%[" . $shortcode . "%]%' ) OR 
					( post_content LIKE '% wp:" . $block . " %' )
				)
				AND 
				( post_status = 'publish' )
			"
			);

		$pageOptions = array();
		foreach( $pages as $page ){
			$permalink = get_permalink( $page->ID );
			$pageOptions[ $permalink ] = $permalink;
		}
		$defaultPage = null;
		if( $pages ){
			$defaultPage = $permalink;
		}

	// widget
		if( ! $blockRegistry->get_registered( 'locatoraid/locatoraid-searchform' ) ){
			register_block_type( 'locatoraid/locatoraid-searchform', [
				'title' => 'Locatoraid Store Locator Search Form',
				'editor_script' => 'locatoraid_block_map',
				'render_callback' => [$this, 'renderSearchForm'],
				'attributes' => [
					'target' => [
						'type' => 'string',
						'default' => $defaultPage,
					],
					'label' => [
						'type' => 'string',
						'default' => __( 'Address or Zip Code', 'locatoraid' ),
					],
					'btn' => [
						'type' => 'string',
						'default' => __( 'Search', 'locatoraid' ),
					],
				]
			]);
		}

		wp_localize_script( 'locatoraid_block_map', 'locatoraidBlockSearchFormOptions', array_keys($pageOptions) );

		wp_localize_script( 'locatoraid_block_map', 'locatoraidBlockShortcodeParams', array_keys($shortcodeParams) );
	}

	public function renderSearchForm( $attr )
	{
		$target = isset( $attr['target'] ) ? $attr['target'] : '';
		$label = isset( $attr['label'] ) ? $attr['label'] : __( 'Address or Zip Code', 'locatoraid' );
		$btn = isset( $attr['btn'] ) ? $attr['btn'] : __( 'Search', 'locatoraid' );

		$searchValue = isset($_GET['lpr-search']) ? $_GET['lpr-search'] : '';

		ob_start();
?>
<section class="widget locatoraid-search-widget">
<form action="<?= esc_attr($target); ?>" method="get" role="search" class="search-form">
<label for="lpr-search"><?= $label; ?></label>
<input type="text" placeholder="<?= esc_html($label); ?>" name="lpr-search" value="<?= esc_attr($searchValue); ?>" id="lpr-search" class="search-field" />
<button type="submit" class="search-submit"><?= $btn; ?></button>
</form>
</section>
<?php
		$ret = ob_get_clean();
		return $ret;
	}
}
}