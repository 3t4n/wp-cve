<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.david-manson.com
 * @since      1.0.0
 *
 * @package    Say_It
 * @subpackage Say_It/admin/partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) die;
$options = $this->options;
if($options['google_tts_key']){
    $options['google_tts_key'] = '*****';
}
if($options['amazon_polly_secret']){
    $options['amazon_polly_secret'] = '*****';
}
?>
<div class="card">
    <h2>Debug Informations</h1>
    <pre>
        <?php print_r($options); ?>
    </pre>
</div>