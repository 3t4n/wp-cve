<?php
namespace Enteraddons\Admin;

/**
 * Enteraddons admin class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

class Admin_Notices{
	
    /**
     * Class constructor.
     *
     */
	function __construct() {
		if( !\Enteraddons\Classes\Helper::checkPhpV81() && ! \Enteraddons\Classes\Helper::is_pro_active()  ) {
			add_action( 'admin_notices', [$this, 'promotional_notice'] );
		}
	}

	/**
	 * Admin Notices
	 *
	 * Show promotional notice for free version
	 *
	 *
	 */
	public function promotional_notice() {

		$obj = new \Enteraddons\Admin\Admin_API();
		$content = $obj->get_data( 'promotionnotices-data' );

		if( !empty( $content ) ):
		?>
		<style>
			.banner-promo-notice {
				overflow: hidden;
				display: flex;
			    flex-wrap: wrap;
			    align-items: center;
			    justify-content: space-between;
			    padding: 10px;
			    background-repeat: no-repeat;
			    background-position: center center;
			    background-size: cover;
			}
			
			.banner-promo-notice .content-right img {
				max-width: 100%;
			}
			.content-left {
			    display: flex;
			    align-items: center;
			}

			.content-left > img {
			    margin-right: 20px;
			}

			.banner-promo-notice p {margin: 0;}
			.ea-promo-btn {
				display: inline-block;
			    background-color: transparent;
			    -webkit-transition: all .3s ease;
			    -o-transition: all .3s ease;
			    transition: all .3s ease;
			    border: 1px solid transparent;
			    text-transform: capitalize;
			    padding: 14px 28px;
			    font-weight: 500;
			    line-height: 1;
			    font-size: 14px;
			    position: relative;
			    cursor: pointer;
			    color: #fff;
			    outline: 0;
			    -webkit-box-shadow: none;
			    box-shadow: none;
			    z-index: 1;
			    text-decoration: none;
			}
			.ea-promo-btn:after {
			    position: absolute;
			    background: #815aff;
			    background: -webkit-gradient(221.88deg,#e82a5c 10.41%,#464163 91.45%);
			    background: -o-linear-gradient(221.88deg,#e82a5c 10.41%,#464163 91.45%);
			    background: linear-gradient(221.88deg,#e82a5c 10.41%,#464163 91.45%);
			    content: "";
			    width: 100%;
			    height: 100%;
			    left: 0;
			    top: 0;
			    -webkit-transition: all .3s;
			    -o-transition: all .3s;
			    transition: all .3s;
			    z-index: -1;
			    opacity: 1;
			}
			.ea-promo-btn:hover {
			    color: #913660;
			    border-color: #913660;
			}
			.ea-promo-btn:hover:after {
			    height: 80%;
			    opacity: 0;
			}

			@media screen and (max-width: 767px) {
				.banner-promo-notice .content-left,
				.banner-promo-notice .content-right {
					flex: 0 0 100%;
	    			max-width: 100%;
	    			width: 100%;
				}
				.banner-promo-notice .content-right { 
					margin-top: 20px;
				}
			}
		</style>
			<?php
			foreach( $content as $data ) {

			if( !empty( $data['promotion_notices_meta']['is_active'] ) && $data['promotion_notices_meta']['is_active'] == 'active' ) {

			$bg = !empty( $data['promotion_notices_meta']['bg_img'] ) ? 'style="background-image:url('.esc_url($data['promotion_notices_meta']['bg_img']).')"' : '';
			?>
			<div class="banner-promo-notice notice notice-success is-dismissible" <?php echo $bg; ?>>
				<div class="content-left">
					<img src="<?php echo esc_url( ENTERADDONS_DIR_URL.'assets/icon_s.png' ); ?>" />
					<div class="promo-notice-content" style="margin-bottom: 12px;">
						<?php 
						if( !empty( $data['promotion_notices_meta']['title_Top'] ) ) {
							echo '<h2>'.\Enteraddons\Classes\Helper::allowFormattingTagHtml( $data['promotion_notices_meta']['title_Top'] ).'</h2>';
						}
						//
						if( !empty( $data['promotion_notices_meta']['title_bottom'] ) ) {
							echo '<h4>'.\Enteraddons\Classes\Helper::allowFormattingTagHtml( $data['promotion_notices_meta']['title_bottom'] ).'</h4>';
						}
						?>
					</div>
				</div>
				<?php 
				// Button 
				if( !empty( $data['promotion_notices_meta']['btn_text'] ) && !empty( $data['promotion_notices_meta']['btn_url'] ) ) {
					echo '<div class="content-center"><a class="ea-promo-btn" target="_blank" href="'.esc_url( $data['promotion_notices_meta']['btn_url'] ).'">'.esc_html( $data['promotion_notices_meta']['btn_text'] ).'</a></div>';
				}
				//
				if( !empty( $data['promotion_notices_meta']['promo_img'] ) ) {
					echo '<div class="content-right"><img src="'.esc_url( $data['promotion_notices_meta']['promo_img'] ).'" /></div>';
				}
				?>
				
			</div>
		<?php
		}
		}
		endif;
	}

}