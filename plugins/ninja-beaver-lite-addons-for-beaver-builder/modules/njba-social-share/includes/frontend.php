<div class="njba-social-share njba-social-share-<?php echo $settings->share_icon_pos; ?>">
    <div class="njba-social-share-main">
		<?php
		$icon_count = 1;
		if ( count( $settings->social_icons ) > 0 ) {
			foreach ( $settings->social_icons as $icon ) {
				if ( ! is_object( $icon ) ) {
					continue;
				}
				$url          = 'javascript:void(0);';
				$current_page = urlencode( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
				switch ( $icon->social_share_type ) {
					case 'facebook':
						$url = 'http://www.facebook.com/sharer.php?u=' . $current_page;
						break;
					case 'twitter':
						$url = 'https://twitter.com/share?url=' . $current_page;
						break;
					case 'google':
						$url = 'https://plus.google.com/share?url=' . $current_page;
						break;
					case 'pinterest':
						$url = 'https://pinterest.com/pin/create/bookmarklet/?url=' . $current_page;
						break;

					case 'linkedin':
						$url = 'http://www.linkedin.com/shareArticle?url=' . $current_page;
						break;
					case 'digg':
						$url = 'http://digg.com/submit?url=' . $current_page;
						break;
					case 'blogger':
						$url = 'https://www.blogger.com/blog_this.pyra?t&amp;u=' . $current_page;
						break;
					case 'reddit':
						$url = 'http://reddit.com/submit?url=' . $current_page;
						break;
					case 'stumbleupon':
						$url = 'http://www.stumbleupon.com/submit?url=' . $current_page;
						break;
					case 'tumblr':
						$url = 'https://www.tumblr.com/widgets/share/tool?canonicalUrl=' . $current_page;
						break;
					case 'myspace':
						$url = 'https://myspace.com/post?u=' . $current_page;
						break;
				}
				echo '<div class="njba-social-share-inner"><a class="njba-social-share-link njba-social-share-list_' . $icon_count . '" href="' . $url . '" target="_blank" onclick="window.open(this.href,\'social-share\',\'left=20,top=20,width=500,height=500,toolbar=1,resizable=0\');return false;">';
				$icon_array = array(
					'image_type'        => 'icon',
					'overall_alignment' => $settings->overall_alignment,
					'icon'              => $icon->icon
				);
				FLBuilder::render_module_html( 'njba-icon-img', $icon_array );
				echo '</a></div>';
				$icon_count = $icon_count + 1;
			}
		}
		?>
    </div>
</div>
