<?php
defined('ABSPATH') or die("Cheating........Uh!!");
/** 
 * Shortcode for Social Commenting.
 */ 
function heateor_sc_commenting_shortcode($params){
	global $heateor_sc_options;
	extract(shortcode_atts(array(
		'style' => ''
	), $params));
	$commentsOrder = $heateor_sc_options['commenting_order'];
	$commentsOrder = explode( ',', $commentsOrder );
	
	$tabs = '';
	$divs = '';

	foreach( $commentsOrder as $key => $order ) {
		$commentsOrder[$key] = trim( $order );
		if ( ! isset( $heateor_sc_options['enable_' .$order. 'comments'] ) ) { unset($commentsOrder[$key]); }
	}

	$orderCount = 0;
	foreach( $commentsOrder as $order ) {
		$order = trim( $order );
		if ( ! isset( $heateor_sc_options['enable_' .$order. 'comments'] ) || $order == 'wordpress' ) { continue; }
		
		$comment_div = '';
		if ( $order == 'facebook' ) {
			$comment_div = heateor_sc_render_fb_comments();
		} elseif ( $order == 'disqus' ) {
			if ( isset( $heateor_sc_options['counts'] ) ) {
				$comments_count = heateor_sc_get_dq_comments_count();
			}
			$comment_div = heateor_sc_render_dq_comments();
		} elseif ( $order == 'vkontakte' ) {
			$comment_div = heateor_sc_render_vk_comments();
		}

		$divs .= '<div ' . ( $orderCount != 0 ? 'style="display:none"' : '' ) . ' id="heateor_sc_' . $order . '_comments">' . ( isset( $heateor_sc_options['commenting_layout'] ) && $heateor_sc_options['commenting_layout'] == 'stacked' && isset( $heateor_sc_options['label_' . $order . '_comments'] ) ? '<h3 class="comment-reply-title">' . $heateor_sc_options['label_' . $order . '_comments'] . ( isset( $comments_count ) ? ' (' . $comments_count . ')' : '' ) . '</h3>' : '' );
		$divs .= $comment_div;
		$divs .= '</div>';

		if ( ! isset( $heateor_sc_options['commenting_layout'] ) || $heateor_sc_options['commenting_layout'] == 'tabbed' ) {
			$tabs .= '<li><a ' . ( $orderCount == 0 ? 'class="heateor-sc-ui-tabs-active"' : '' ) . ' id="heateor_sc_' . $order . '_comments_a" href="javascript:void(0)" onclick="this.setAttribute(\'class\', \'heateor-sc-ui-tabs-active\');document.getElementById(\'heateor_sc_' . $order . '_comments\').style.display = \'block\';';
			foreach ($commentsOrder as $commenting) {
				if($commenting != $order && $commenting != 'wordpress'){
					$tabs .= 'document.getElementById(\'heateor_sc_' . $commenting . '_comments_a\').setAttribute(\'class\', \'\');document.getElementById(\'heateor_sc_' . $commenting . '_comments\').style.display = \'none\';';
				}
			}
			$tabs .= '">';
			// icon
			if ( isset( $heateor_sc_options['enable_' . $order . 'icon'] ) || ( ! isset( $heateor_sc_options['enable_' . $order . 'icon'] ) && ! isset( $heateor_sc_options['label_' . $order . '_comments'] ) ) ) {
				$alt = isset( $heateor_sc_options['label_' . $order . '_comments'] ) ? $heateor_sc_options['label_' . $order . '_comments'] : ucfirst( $order ) . ' Comments';
				$tabs .= '<div title="'. $alt .'" alt="'. $alt .'" class="heateor_sc_' . $order . '_background"><i class="heateor_sc_' . $order . '_svg"></i></div>';
			}
			// label
			if ( isset( $heateor_sc_options['label_' . $order . '_comments'] ) ) {
				$tabs .= '<span class="heateor_sc_comments_label">' . $heateor_sc_options['label_' . $order . '_comments'] . '</span>';
			}
			if ( $order != 'facebook' ) {
				$tabs .= ( isset( $comments_count ) ? ' (' . $comments_count . ')' : '' );
			}
			$tabs .= '</a></li>';
			
			$orderCount++;
		}
	}
	$commentingHtml = '<div class="heateor_sc_social_comments" ' . ( $style != '' ? 'style="' . esc_attr( $style ) . '"' : '' ) . '>';
	if ( $tabs ) {
		$commentingHtml .= ( isset( $heateor_sc_options['commenting_label'] ) ? '<div style="clear:both"></div><h3 class="comment-reply-title">' . $heateor_sc_options['commenting_label'] . '</h3><div style="clear:both"></div>' : '' ) . '<ul class="heateor_sc_comments_tabs">' . $tabs . '</ul>';
	}
	$commentingHtml .= $divs;
	$commentingHtml .= '</div>';
	return $commentingHtml;
}
add_shortcode('Heateor-SC', 'heateor_sc_commenting_shortcode');