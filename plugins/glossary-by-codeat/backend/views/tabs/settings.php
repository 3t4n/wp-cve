<?php

/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2016 GPL 3.0+
 * @license   GPL-2.0+
 * @link      http://codeat.co
 */
?>

<div id="tabs-settings" class="metabox-holder">
<?php 
$cmb = new_cmb2_box( array(
    'id'         => GT_SETTINGS . '_options',
    'hookup'     => false,
    'show_on'    => array(
    'key'   => 'options-page',
    'value' => array( 'glossary-by-codeat' ),
),
    'show_names' => true,
) );
$cmb->add_field( array(
    'name' => __( 'Settings for Post Types', GT_TEXTDOMAIN ),
    'id'   => 'title_post_types',
    'type' => 'title',
) );
$cmb->add_field( array(
    'name' => __( 'Enable in:', GT_TEXTDOMAIN ),
    'id'   => 'posttypes',
    'type' => 'multicheck_posttype',
) );
$where_enable = array(
    'home'         => __( 'Home', GT_TEXTDOMAIN ),
    'category'     => __( 'Category archive', GT_TEXTDOMAIN ),
    'tag'          => __( 'Tag archive', GT_TEXTDOMAIN ),
    'arc_glossary' => __( 'Glossary Archive', GT_TEXTDOMAIN ),
    'tax_glossary' => __( 'Glossary Taxonomy', GT_TEXTDOMAIN ),
);
$cmb->add_field( array(
    'name'    => __( 'Enable also in following archives:', GT_TEXTDOMAIN ),
    'id'      => 'is',
    'type'    => 'multicheck',
    'options' => $where_enable,
) );
$cmb->add_field( array(
    'name' => __( 'Alphabetical order in Glossary Archives', GT_TEXTDOMAIN ),
    'id'   => 'order_terms',
    'type' => 'checkbox',
) );
$temp = array(
    'name' => __( 'Add Alphabetical list on top of Glossary Archives', GT_TEXTDOMAIN ),
    'desc' => __( 'After the title of the archive will add a list like ABCDE... with links.<br>', GT_TEXTDOMAIN ) . sprintf( $doc, 'http://docs.codeat.co/glossary/' ) . $pro,
    'id'   => 'archive_alphabetical_bar',
    'type' => 'checkbox',
);
if ( !empty($pro) ) {
    $temp['attributes'] = array(
        'readonly' => 'readonly',
        'disabled' => 'disabled',
    );
}
$cmb->add_field( $temp );
$cmb->add_field( array(
    'name'    => __( 'Glossary Terms Slug', GT_TEXTDOMAIN ),
    'desc'    => __( 'Terms and Categories cannot have the same custom slug.', GT_TEXTDOMAIN ),
    'id'      => 'slug',
    'type'    => 'text',
    'default' => 'glossary',
) );
$cmb->add_field( array(
    'name'    => __( 'Glossary Category Slug', GT_TEXTDOMAIN ),
    'desc'    => __( 'Terms and Categories cannot have the same custom base slug.', GT_TEXTDOMAIN ),
    'id'      => 'slug_cat',
    'type'    => 'text',
    'default' => 'glossary-cat',
) );
$cmb->add_field( array(
    'name'    => __( 'Singular Post Type Label', GT_TEXTDOMAIN ),
    'desc'    => __( 'Change the name of the post type shown in both the backend and the frontend', GT_TEXTDOMAIN ),
    'id'      => 'label_single',
    'type'    => 'text',
    'default' => '',
) );
$cmb->add_field( array(
    'name'    => __( 'Plural Post Type Label', GT_TEXTDOMAIN ),
    'desc'    => __( 'Change the name of the post type shown in both the backend and the frontend', GT_TEXTDOMAIN ),
    'id'      => 'label_multi',
    'type'    => 'text',
    'default' => '',
) );
$cmb->add_field( array(
    'name' => __( 'Disable Glossary post type in the frontend', GT_TEXTDOMAIN ),
    'desc' => __( 'Don\'t forget to flush the permalinks in the General Settings.<br>', GT_TEXTDOMAIN ) . sprintf( $doc, 'http://docs.codeat.co/glossary/advanced-settings/#disable-archives-in-the-frontend' ),
    'id'   => 'post_type_hide',
    'type' => 'checkbox',
) );
$cmb->add_field( array(
    'name' => __( 'Disable Archive in the frontend for Glossary Terms', GT_TEXTDOMAIN ),
    'desc' => __( 'Don\'t forget to flush the permalinks in the General Settings.<br>', GT_TEXTDOMAIN ) . sprintf( $doc, 'http://docs.codeat.co/glossary/advanced-settings/#disable-archives-in-the-frontend' ),
    'id'   => 'archive',
    'type' => 'checkbox',
) );
$cmb->add_field( array(
    'name' => __( 'Disable Archive in the frontend for Glossary Categories', GT_TEXTDOMAIN ),
    'id'   => 'tax_archive',
    'type' => 'checkbox',
) );
$cmb->add_field( array(
    'name' => __( 'Remove "Archive/Category" prefix from meta titles in Archive/Category pages', GT_TEXTDOMAIN ),
    'desc' => sprintf( $doc, 'http://docs.codeat.co/glossary/advanced-settings/#remove-the-archivecategory-prefix-from-meta-titles' ),
    'id'   => 'remove_archive_label',
    'type' => 'checkbox',
) );
$temp = array(
    'name' => __( 'Add total number of terms in the meta title of the page', GT_TEXTDOMAIN ),
    'desc' => sprintf( $doc, 'http://docs.codeat.co/glossary/premium-features/#how-to-add-the-total-number-of-terms-in-the-meta-title-of-the-page' ),
    'id'   => 'number_archive_title',
    'type' => 'checkbox',
);
if ( !empty($pro) ) {
    $temp['attributes'] = array(
        'readonly' => 'readonly',
        'disabled' => 'disabled',
    );
}
$cmb->add_field( $temp );
$cmb->add_field( array(
    'name' => __( 'Behaviour', GT_TEXTDOMAIN ),
    'id'   => 'title_behaviour',
    'type' => 'title',
) );
$cmb->add_field( array(
    'name' => __( 'Ignore &lt;span&gt; tags during the term search in the content', GT_TEXTDOMAIN ),
    'id'   => 'ignore_span',
    'type' => 'checkbox',
    'desc' => __( 'Use this option only when you see issues or conflicts with other plugins or component of the page. Or if you want to avoid to have more then 1 term in a single sentence.', GT_TEXTDOMAIN ),
) );
$temp = array(
    'name' => __( 'Link only the first occurrence of all key terms', GT_TEXTDOMAIN ),
    'desc' => __( 'Prevents duplicating links and tooltips for all key terms that point to the same definition.<br>', GT_TEXTDOMAIN ) . sprintf( $doc, 'http://docs.codeat.co/glossary/premium-features/#link-only-the-first-occurrence-of-all-terms-keys' ) . $pro,
    'id'   => 'first_occurrence',
    'type' => 'checkbox',
);
if ( !empty($pro) ) {
    $temp['attributes'] = array(
        'readonly' => 'readonly',
        'disabled' => 'disabled',
    );
}
$cmb->add_field( $temp );
$temp = array(
    'name' => __( 'Link only the first occurrence of all the term keys', GT_TEXTDOMAIN ),
    'desc' => __( 'Prevent duplicate links and tooltips for the same term, even if has more than one key, in a single post.<br>', GT_TEXTDOMAIN ) . sprintf( $doc, 'http://docs.codeat.co/glossary/advanced-settings/#link-only-the-first-occurrence-of-the-same-key-term' ) . $pro,
    'id'   => 'first_all_occurrence',
    'type' => 'checkbox',
);
if ( !empty($pro) ) {
    $temp['attributes'] = array(
        'readonly' => 'readonly',
        'disabled' => 'disabled',
    );
}
$cmb->add_field( $temp );
$cmb->add_field( array(
    'name' => __( 'Add icon to external link', GT_TEXTDOMAIN ),
    'desc' => __( 'Add a css class with an icon to external link', GT_TEXTDOMAIN ),
    'id'   => 'external_icon',
    'type' => 'checkbox',
) );
$cmb->add_field( array(
    'name' => __( 'Force Glossary terms to be included within WordPress search results', GT_TEXTDOMAIN ),
    'desc' => __( 'Choose this option if you don\'t see your terms while searching for them in WordPress.<br>', GT_TEXTDOMAIN ) . sprintf( $doc, 'http://docs.codeat.co/glossary/advanced-settings/#force-glossary-terms-to-be-included-within-wordpress-search-results' ),
    'id'   => 'search',
    'type' => 'checkbox',
) );
$temp = array(
    'name' => __( 'Match case-sensitive terms', GT_TEXTDOMAIN ),
    'id'   => 'case_sensitive',
    'type' => 'checkbox',
    'desc' => $pro,
);
if ( !empty($pro) ) {
    $temp['attributes'] = array(
        'readonly' => 'readonly',
        'disabled' => 'disabled',
    );
}
$cmb->add_field( $temp );
$cmb->add_field( array(
    'name' => __( 'Prevent term links from appearing on their own description page', GT_TEXTDOMAIN ),
    'desc' => __( 'Choose this option to avoid redundancy.<br>', GT_TEXTDOMAIN ) . sprintf( $doc, 'http://docs.codeat.co/glossary/premium-features/#prevent-term-links-from-appearing-in-their-own-description-page' ),
    'id'   => 'match_same_page',
    'type' => 'checkbox',
) );
$cmb->add_field( array(
    'name' => __( 'Open external link in a new window', GT_TEXTDOMAIN ),
    'desc' => __( 'Choose this option to enable globally the opening of external link in a new tab.<br>', GT_TEXTDOMAIN ),
    'id'   => 'open_new_window',
    'type' => 'checkbox',
) );
$temp = array(
    'name' => __( 'Append string to injected URL', GT_TEXTDOMAIN ),
    'desc' => __( 'Append a string to internal and external URLs for tracking purposes (E.g. utm_content=service).', GT_TEXTDOMAIN ) . $pro,
    'id'   => 'url_suffix',
    'type' => 'text_small',
);
if ( !empty($pro) ) {
    $temp['attributes'] = array(
        'readonly' => 'readonly',
        'disabled' => 'disabled',
    );
}
$cmb->add_field( $temp );
$cmb->add_field( array(
    'name' => __( 'Settings for Tooltip', GT_TEXTDOMAIN ),
    'id'   => 'title_tooltip',
    'type' => 'title',
) );
$glossary_tooltip_type = array(
    'link'         => __( 'Only Link', GT_TEXTDOMAIN ),
    'link-tooltip' => __( 'Link and Tooltip', GT_TEXTDOMAIN ),
);
$cmb->add_field( array(
    'name'    => __( 'Enable tooltips on terms', GT_TEXTDOMAIN ),
    'desc'    => __( 'Tooltip will popup on hover', GT_TEXTDOMAIN ),
    'id'      => 'tooltip',
    'type'    => 'select',
    'options' => $glossary_tooltip_type,
) );
$themes = apply_filters( 'glossary_themes_dropdown', array(
    'classic' => 'Classic',
    'box'     => 'Box',
    'line'    => 'Line',
    'simple'  => 'Simple',
) );
$cmb->add_field( array(
    'name'    => __( 'Tooltip style', GT_TEXTDOMAIN ),
    'desc'    => __( 'The featured image will only show with the Classic and all the PRO themes.<br>', GT_TEXTDOMAIN ) . sprintf( $doc, 'http://docs.codeat.co/glossary/tooltips/' ),
    'id'      => 'tooltip_style',
    'type'    => 'select',
    'options' => $themes,
) );
$cmb->add_field( array(
    'name' => __( 'Enable image in tooltips', GT_TEXTDOMAIN ),
    'id'   => 't_image',
    'type' => 'checkbox',
) );
$temp = array(
    'name' => __( 'Remove "more" link in tooltips', GT_TEXTDOMAIN ),
    'id'   => 'more_link',
    'type' => 'checkbox',
    'desc' => $pro,
);
if ( !empty($pro) ) {
    $temp['attributes'] = array(
        'readonly' => 'readonly',
        'disabled' => 'disabled',
    );
}
$cmb->add_field( $temp );
$temp = array(
    'name' => __( 'Change "more" link text in tooltips', GT_TEXTDOMAIN ),
    'id'   => 'more_link_text',
    'type' => 'text',
    'desc' => $pro,
);
if ( !empty($pro) ) {
    $temp['attributes'] = array(
        'readonly' => 'readonly',
        'disabled' => 'disabled',
    );
}
$cmb->add_field( $temp );
$cmb->add_field( array(
    'name' => __( 'Excerpt', GT_TEXTDOMAIN ),
    'id'   => 'title_excerpt_limit',
    'type' => 'title',
) );
$cmb->add_field( array(
    'name' => __( 'Limit the excerpt by words', GT_TEXTDOMAIN ),
    'desc' => __( 'As opposed to characters', GT_TEXTDOMAIN ),
    'id'   => 'excerpt_words',
    'type' => 'checkbox',
) );
$cmb->add_field( array(
    'name'    => __( 'Excerpt length in characters or words', GT_TEXTDOMAIN ),
    'desc'    => __( 'Refers to selection above. If value is 0 the complete content will be used.<br>', GT_TEXTDOMAIN ) . sprintf( $doc, 'https://docs.codeat.co/glossary/advanced-settings/#how-works-the-text-generation-inside-the-tooltips' ),
    'id'      => 'excerpt_limit',
    'type'    => 'text_number',
    'default' => '60',
) );
$cmb->add_field( array(
    'name' => __( 'Disable ... on excerpt', GT_TEXTDOMAIN ),
    'id'   => 'excerpt_dots',
    'type' => 'checkbox',
) );
cmb2_metabox_form( GT_SETTINGS . '_options', GT_SETTINGS . '-settings' );
?>

</div>
