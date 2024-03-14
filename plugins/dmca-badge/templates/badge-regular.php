<?php
/**
 * Custom Badge Template
 */

$settings        = dmca_get_option( 'dmca_badge_settings' );
$settings        = isset( $settings->values ) ? $settings->values : array();
$badge_template  = sprintf( '<a href="%1$s" title="%2$s" class="dmca-badge"><img src="{{badge_url}}?ID=%1$s" alt="%2$s"></a>', dmca_badge_get_status_url(), esc_html__( 'Content Protection by DMCA.com', 'dmca-badge' ) );
$badge_template  = sprintf( '%s <script src="https://images.dmca.com/Badges/DMCABadgeHelper.min.js"> </script>', $badge_template );
$badge_settings  = isset( $settings['badge'] ) ? $settings['badge'] : array();
$badge_selection = isset( $badge_settings['badge_selection'] ) ? $badge_settings['badge_selection'] : 'regular';
$badge_selection = $badge_selection === 'regular' ? 'selected' : '';
?>
<div class="dmca-badge-wrap dmca-regular-badge <?php echo esc_attr( $badge_selection ); ?>">
<p>Select a DMCA Website Protection Badge by clicking on it.</p>
<pre style="display:none" id="badge-template"><?php echo htmlentities( $badge_template ); ?></pre>
<?php echo DMCA_Badge_Plugin::this()->get_badges_html( DMCA_Badge_Plugin::this()->get_form_settings_value( 'badge', 'url' ) ); ?>

