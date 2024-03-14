<?php 
namespace Adminz\Helper;
use Adminz\Admin\Adminz;

/*
	Tự tạo html template theo cấu trúc của photoswipe 
	new một biến ADMINZ_Helper_Woocommerce_Photoswipe
	setup thuộc tính doom_links
	chạy init();

*/

class ADMINZ_Helper_Woocommerce_Photoswipe{
	public $dom_links = '';

	function __construct() {
		
	}

	function init(){

		if(!$this->dom_links){
			echo 'no set dom_links';
			return;
		}

		$this->enqueue();
	}

	function enqueue(){
		add_action('wp_enqueue_scripts',function(){

			// if(function_exists('WC')){
			// 	wp_enqueue_script( 'photoswipe' );
			// 	wp_enqueue_script( 'photoswipe-ui-default' );

			// 	wp_enqueue_style( 'photoswipe' );
			// 	wp_enqueue_style( 'photoswipe-default-skin' );

			// }else{

				wp_enqueue_script( 'photoswipe', ADMINZ_DIR_URL."assets/photoswipe/photoswipe.min.js", [], null, true );
				wp_enqueue_script( 'photoswipe-ui-default', ADMINZ_DIR_URL."assets/photoswipe/photoswipe-ui-default.min.js", [], null, true );
				wp_enqueue_style( 'photoswipe', ADMINZ_DIR_URL."assets/photoswipe/photoswipe.min.css", [], null, 'all' );
				wp_enqueue_style( 'photoswipe-default-skin', ADMINZ_DIR_URL."assets/photoswipe/default-skin/default-skin.min.css", [], null, 'all' );



				
			// }
		});

		add_action('wp_footer',function(){
		    // require_once get_template_directory() . '/woocommerce/single-product/photoswipe.php';
		    ?>
			<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true"> <div class="pswp__bg"></div> <div class="pswp__scroll-wrap"> <div class="pswp__container"> <div class="pswp__item"></div> <div class="pswp__item"></div> <div class="pswp__item"></div> </div> <div class="pswp__ui pswp__ui--hidden"> <div class="pswp__top-bar"> <div class="pswp__counter"></div> <button class="pswp__button pswp__button--close" aria-label="<?php esc_attr_e( 'Close (Esc)', 'woocommerce' ); ?>"></button> <button class="pswp__button pswp__button--zoom" aria-label="<?php esc_attr_e( 'Zoom in/out', 'woocommerce' ); ?>"></button> <div class="pswp__preloader"> <div class="loading-spin"></div> </div> </div> <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap"> <div class="pswp__share-tooltip"></div> </div> <button class="pswp__button--arrow--left" aria-label="<?php esc_attr_e( 'Previous (arrow left)', 'woocommerce' ); ?>"></button> <button class="pswp__button--arrow--right" aria-label="<?php esc_attr_e( 'Next (arrow right)', 'woocommerce' ); ?>"></button> <div class="pswp__caption"> <div class="pswp__caption__center"></div> </div> </div> </div> </div>

		    <script type="text/javascript">
            jQuery(document).ready(function($){
                $('<?php echo esc_attr($this->dom_links); ?>').on('click',function(e){
                    e.preventDefault();

                    const items = [];
                    const index = $(this).closest(".col").index();

                    $('<?php echo esc_attr($this->dom_links); ?>').each(function(){
                        const src = $(this).attr('href');
                        let size = $(this).data('size'); // 800x600

						if (size === undefined) {
							const imgElement = $(this).find('img');
							if (imgElement.length > 0) {
								const width = imgElement.attr('width');
								const height = imgElement.attr('height');
								size = width + "x" + height;
							}
						}

						if(size !== undefined){
							size = size.split('x');
						}

                        const item = {
                            src: src,
                            w: parseInt(size[0], 10),
                            h: parseInt(size[1], 10)
                        };
                        if (!items.some(existingItem => existingItem.src === item.src)) {
							items.push(item);
						}
                    });

                    const options = {
                        index: index,
                        // Cấu hình PhotoSwipe theo ý muốn
                    };
                    const pswpElement = document.querySelectorAll('.pswp')[0];
                    const gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
                    gallery.init();

                });
            })
        </script>
		    <?php
		});
	}
	
}


/* 
	====================== Ví dụ
	disable  Enable Flatsome Lightbox
	
	1. HTML
		<div class="thuvienanh">
			<a 
				href="https://happytoursvietnam.com/wp-content/uploads/2023/09/082126100_1575571114-Felix_Rostig.webp" 
				data-size="800x450"
				>
                IMAGE TAG
            </a>
            <a 
				href="https://happytoursvietnam.com/wp-content/uploads/2023/09/082126100_1575571114-Felix_Rostig.webp" 
				data-size="800x450"
				>
                IMAGE TAG
            </a>
		</div>

	2. PHP
		$gallery = new Adminz\Helper\ADMINZ_Helper_Woocommerce_Photoswipe;
    	$gallery->dom_links = ".thuvienanh a";
    	$gallery->init();




*/