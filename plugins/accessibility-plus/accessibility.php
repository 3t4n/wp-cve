<?php
/*
Plugin Name: AccessibilityPlus
Plugin URI: 
Description: Improve pagespeed insight or lighthouse accessibility scores by fixing recommended issues.
Version: 1.2.4
Author: easywpstuff
Author URI: https://easywpstuff.com
License: GNU General Public License v2 or later
*/

// Load the SimpleHTMLDOM library
require_once('inc/simple_html_dom.php');

// include option

include_once('inc/options.php');

function process_html_with_dom($html, $callback) {
  $dom = lhb_str_get_html($html, false, true, 'UTF-8', false, PHP_EOL, ' ');
  
  if (empty($dom)) {
    return $html;
  }
  
  $callback($dom);

  $html = $dom->save(); 
  return $html;
}

// Name: Form elements do not have associated labels
function easywpstuff_assesplus_check_input_labels($html) {
  return process_html_with_dom($html, function($dom) {
  $inputs = $dom->find('input');
	
  foreach ($inputs as $input) {
    $parent = $input->parent();
    $previousSibling = $input->previousSibling();
    $nextSibling = $input->nextSibling();

    // Check if label is a sibling and has the 'for' attribute matching input's 'id'
    if (
        ($previousSibling && $previousSibling->tag === 'label' && $previousSibling->getAttribute('for') === $input->getAttribute('id')) ||
        ($nextSibling && $nextSibling->tag === 'label' && $nextSibling->getAttribute('for') === $input->getAttribute('id')) ||
        ($parent && $parent->tag === 'label' && $parent->getAttribute('for') === $input->getAttribute('id'))
    ) {
        continue; // Skip if label with 'for' attribute is found
    }

    // Check if no descriptive attributes and not in a labeled context
    if (!$input->getAttribute('placeholder') || !$input->getAttribute('aria-label') || !$input->getAttribute('aria-labelledby')) {
        $inputType = $input->getAttribute('type');

        // Check if input type is not hidden, button, or submit
        if ($inputType && !in_array($inputType, ['hidden', 'button', 'submit'])) {
            $input->setAttribute('aria-label', $inputType);
        }
    }
}
  });
}
function hasNonEmptyTextContent($element) {
    $textContent = $element->plaintext;
    return $textContent !== '' && trim($textContent) !== ''; // Check if it's not empty or consists only of whitespace
}
// Links do not have a discernible name
function easywpstuff_assesplus_add_aria_labels_to_links($html) {
  $html = str_replace('</img>', '', $html);
  $html = str_replace('</source>', '', $html);
  
  return process_html_with_dom($html, function($dom) {
  $links = $dom->find('a');

foreach ($links as $link) {
    $hasTextContent = false;
    $hasImageWithNonBlankAlt = false;

    // Check if link or its child elements have text content
    if (hasNonEmptyTextContent($link)) {
        $hasTextContent = true;
    } else {
        foreach ($link->find('*') as $element) {
            if (hasNonEmptyTextContent($element)) {
                $hasTextContent = true;
                break;
            }
        }
    }

    // Check if link lacks text content or child with text content, and aria-label attributes
    if (!$hasTextContent && !$link->getAttribute('aria-label') && !$link->getAttribute('aria-labelledby')) {
        foreach ($link->find('img') as $imgElement) {
            if (trim($imgElement->getAttribute('alt'))) {
                $hasImageWithNonBlankAlt = true;
                break;
            }
        }

        // Exclude links with image children having non-blank alt attribute
        if (!$hasImageWithNonBlankAlt) {
            $link->setAttribute('aria-label', 'link');
        }
    }
}

  });
}


//Buttons do not have an accessible name

function easywpstuff_assesplus_add_aria_labels_to_buttons($html) {

  return process_html_with_dom($html, function($dom) {
  $buttons = $dom->find('button');
  foreach ($buttons as $button) {
    if (!trim($button->plaintext) && !$button->getattribute('aria-label')) {
      $child_elements = $button->find('*');
      if (empty($child_elements)) {
        $button->setattribute('aria-label', 'button');
      } else {
        $button->setattribute('aria-label', 'button');
      }
    }
	  if (!$button->getattribute('aria-label')) {
    $svg = $button->find('svg',0);
    $path = $button->find('path',0);
    $span = $button->find('span',0);
    if($svg && $path){
      $button->setattribute('aria-label', 'button');
    }elseif($span){
      $span_text = trim($span->plaintext);
      if($span_text){
        $button->setattribute('aria-label', $span_text);
      }
    }
  }
  }
  });
}

//[user-scalable="no"] is used in the <meta name="viewport"> element or the [maximum-scale] attribute is less than 5.
function easywpstuff_assesplus_modify_viewport_meta_tag($html) {
	
  return process_html_with_dom($html, function($dom) {
  $viewport_meta_tag = $dom->find('meta[name=viewport]', 0);
  if ($viewport_meta_tag) {
    $content = $viewport_meta_tag->getAttribute('content');
	  
    $content = preg_replace('/,\s*user-scalable=(?:no|0)/', '', $content);

    if (preg_match('/maximum-scale=([^,\s]*)/', $content, $matches)) {

      $maximum_scale = (float) $matches[1];
      if ($maximum_scale < 2) {
        $content = preg_replace('/maximum-scale=[^,\s]*/', 'maximum-scale=2', $content);
      }
    } else {
      $content .= ', maximum-scale=2';
    }

    $viewport_meta_tag->setAttribute('content', $content);
  }
  });
}


function easywpstuff_assesplus_add_aria_labels_to_headers($html) {
    return process_html_with_dom($html, function($dom) {

    foreach ($dom->find('div') as $div) {
    // Check if the div or its children have no plain text
    if (empty(trim($div->plaintext))) {
        if ($div->role == 'link' && !$div->hasAttribute('aria-label')) {
            $div->setAttribute('aria-label', 'link');
        }
        if ($div->role == 'button' && !$div->hasAttribute('aria-label')) {
            $div->setAttribute('aria-label', 'button');
        }
        if ($div->role == 'menuitem' && !$div->hasAttribute('aria-label')) {
            $div->setAttribute('aria-label', 'menuitem');
        }
      }
   }

   });
}

// <frame> or <iframe> elements do not have a title
function easywpstuff_assesplus_booster_add_to_iframes($html) {
    return process_html_with_dom($html, function($dom) {

    foreach($dom->find('iframe') as $iframe) {
        if (!$iframe->hasAttribute('title')) {
            $iframe->setAttribute('title', 'iframe');
        }
    }

    });
}

function easywpstuff_assesplus_modify_tabindex_to_zero($html) {
    return process_html_with_dom($html, function($dom) {

        foreach ($dom->find('*[tabindex]') as $element) {
            $tabindex = $element->getAttribute('tabindex');

            if ($tabindex > 0) {
                $element->setAttribute('tabindex', '0');
            }
        }

    });
}

//Links are not crawlable
function easywpstuff_assesplus_addHashToBlankLinks($html) {
	return process_html_with_dom($html, function($dom) {
    $blankLinks = $dom->find('a');
    foreach ($blankLinks as $link) {
        if (!$link->href || $link->href === '') {
            $link->setattribute('href', '#');
        }
		if (!$link->href || $link->href === 'javascript: void(0);') {
            $link->setattribute('href', '#');
        }
		if (!$link->href || $link->href === 'javascript:void(0);') {
            $link->setattribute('href', '#');
        }
		if (!$link->href || $link->href === 'javascript:void(0)') {
            $link->setattribute('href', '#');
        }
    }
	});
}

//ARIA progressbar elements do not have accessible names.
function easywpstuff_assesplus_add_aria_label_to_progressbar($html) {
    // Use Simple HTML DOM to parse the HTML
    return process_html_with_dom($html, function($dom) {
    foreach($dom->find('[role=progressbar]') as $element) {
        // Check if the element has aria-label, aria-labelledby or title attribute
        $has_label = $element->hasAttribute('aria-label') || 
                     $element->hasAttribute('aria-labelledby') || 
                     $element->hasAttribute('title');
        // If the element doesn't have a label, add aria-label="progressbar"
        if (!$has_label || (empty($element->getAttribute('aria-label')) && empty($element->getAttribute('aria-labelledby')) && empty($element->getAttribute('title')))) {
            $element->setAttribute('aria-label', 'progressbar');
        }
    }
    });
}
//Image elements do not have [alt] attributes
function easywpstuff_assesplus_addAltToImg($html) {
    return process_html_with_dom($html, function($dom) {
    $imgs = $dom->find('img');
    foreach ($imgs as $img) {
        if (!$img->alt) {
            $alt = '';
            if ($img->title) {
                $alt = $img->title;
            } else {
                $src = $img->src;
                $alt = basename($src);
                $alt = preg_replace('/\d+/', '', $alt);
                $alt = preg_replace('/\.[^.]+$/', '', $alt);
                $alt = str_replace('-', ' ', $alt);
				$alt = str_replace('_', ' ', $alt);
				if (empty($alt) || is_numeric($alt)) {
                $alt = 'image';
            }
            }
            $img->setattribute('alt', $alt);
        }
    }
    });
}

// buffer if option is enabled

function easywpstuff_assesplus_bstr_template_redirect($buffer) {
    $options = get_option('easywpstuff_assesplus_booster_options');

    if ($options['easywpstuff_assesplus_booster_field_no_labels'] == 1) {
        $buffer = easywpstuff_assesplus_check_input_labels($buffer);
    }

    if ($options['easywpstuff_assesplus_booster_field_no_accessible_name'] == 1) {
        $buffer = easywpstuff_assesplus_add_aria_labels_to_buttons($buffer);
    }

    if ($options['easywpstuff_assesplus_booster_field_viewport_meta_tag'] == 1) {
        $buffer = easywpstuff_assesplus_modify_viewport_meta_tag($buffer);
    }

    if ($options['easywpstuff_assesplus_booster_field_menuitem_accessible'] == 1) {
        $buffer = easywpstuff_assesplus_add_aria_labels_to_headers($buffer);
    }

    if ($options['easywpstuff_assesplus_booster_field_iframe_title_tag'] == 1) {
        $buffer = easywpstuff_assesplus_booster_add_to_iframes($buffer);
    }

    if ($options['easywpstuff_assesplus_add_aria_label_to_progressbar'] == 1) {
        $buffer = easywpstuff_assesplus_add_aria_label_to_progressbar($buffer);
    }

    if ($options['easywpstuff_assesplus_links_not_crawlable'] == 1) {
        $buffer = easywpstuff_assesplus_addHashToBlankLinks($buffer);
    }
	if ($options['easywpstuff_assesplus_modify_tabindex_to_zero'] == 1) {
        $buffer = easywpstuff_assesplus_modify_tabindex_to_zero($buffer);
    }

    if ($options['easywpstuff_assesplus_img_donot_alt'] == 1) {
        $buffer = easywpstuff_assesplus_addAltToImg($buffer);
    }
	
	if ($options['easywpstuff_assesplus_booster_field_no_discernible_name'] == 1) {
        $buffer = easywpstuff_assesplus_add_aria_labels_to_links($buffer);
    }
	

    return $buffer;
}

add_action( 'template_redirect', 'easywpstuff_assesplus_loader', 99 );
function easywpstuff_assesplus_loader() {

     ob_start( 'easywpstuff_assesplus_bstr_template_redirect');
	
}

// added settings link
function easywpstuff_assesplus_bs_settings_link($links) {
  $settings_link = '<a href="' . admin_url( 'options-general.php?page=accessibilityplus' ) . '">' . __( 'Settings' ) . '</a>';
  array_unshift( $links, $settings_link );
  return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'easywpstuff_assesplus_bs_settings_link' );

// enqueue admin styles on option page
function easywpstuff_assesplus_admin_enqueue_plugin_styles( $hook ) {
  if ( 'settings_page_accessibilityplus' === $hook ) {
    wp_enqueue_style( 'lhbooster-styles', plugin_dir_url( __FILE__ ) . 'assets/style.css' );
  }
}
add_action( 'admin_enqueue_scripts', 'easywpstuff_assesplus_admin_enqueue_plugin_styles' );