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
 *
 * @phpcs:disable WordPress.Security.EscapeOutput
 */
?>
<div id="tabs-extra" class="metabox-holder">
<?php 
$cmb = new_cmb2_box( array(
    'id'         => GT_SETTINGS . '_options3',
    'hookup'     => false,
    'show_on'    => array(
    'key'   => 'options-page',
    'value' => array( 'glossary-by-codeat' ),
),
    'show_names' => true,
) );
$cmb->add_field( array(
    'name' => __( 'OpenAI ChatGPT', GT_TEXTDOMAIN ),
    'id'   => 'text_opeai_chatgpt',
    'desc' => __( 'A valid OpenAI key is needed. Please head over to <a href="https://docs.codeat.co/glossary/chatgpt/" target="_blank">the dedicated documentation page</a>.', GT_TEXTDOMAIN ),
    'type' => 'title',
) );
$cmb->add_field( array(
    'name'    => __( 'Secret Key', GT_TEXTDOMAIN ),
    'id'      => 'openai_key',
    'type'    => 'text',
    'default' => '',
) );
$cmb->add_field( array(
    'name'    => __( 'Temperature', GT_TEXTDOMAIN ),
    'id'      => 'openai_temperature',
    'type'    => 'text_small',
    'default' => '1',
) );
$cmb->add_field( array(
    'name'    => __( 'OpenAI Model', GT_TEXTDOMAIN ),
    'id'      => 'openai_model',
    'type'    => 'select',
    'default' => 'gpt-3.5-turbo',
    'options' => array(
    'gpt-3.5-turbo' => 'gpt-3.5-turbo',
    'gpt-4'         => 'gpt-4',
),
) );
cmb2_metabox_form( GT_SETTINGS . '_options3', GT_SETTINGS . '-extra' );
?>
</div>
