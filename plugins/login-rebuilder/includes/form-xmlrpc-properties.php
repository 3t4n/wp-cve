<?php
/*
 * XML-RPC properties page content
 *
 * @since 2.0.0
 *
 * @package WordPress
 *
 * require login_rebuilder::xmlrpc_properties()
 */

if ( !( isset( $this ) && is_a( $this, 'login_rebuilder' ) ) ) die( 0 );
?>
<div id="<?php echo self::XMLRPC_PROPERTIES_NAME; ?>" class="wrap">
<div id="icon-options-general" class="icon32"><br /></div>
<h2><?php _e( 'XML-RPC', LOGIN_REBUILDER_DOMAIN ); ?> <?php _e( 'Settings' ) ;?></h2>
<?php $this->_properties_message( $message ); ?>

<div id="xmlrpc-widget" class="metabox-holder">
<form method="post" action="<?php echo esc_url( str_replace( '%07E', '~', $this->request_uri ) ); ?>">
<table summary="xmlrpc properties" class="form-table">
<tr valign="top">
<th><?php _e( 'Usual', LOGIN_REBUILDER_DOMAIN ); ?></th>
<td>
<input type="checkbox" name="properties[xmlrpc_disabled]" id="xmlrpc_disabled" value="1" <?php checked( $this->properties['xmlrpc_disabled'] ); ?> /><label for="xmlrpc_disabled">&nbsp;<span><?php _e( 'Disables the XML-RPC method to authenticate.', LOGIN_REBUILDER_DOMAIN ); ?></span></label><br />
<input type="checkbox" name="properties[limits_user]" id="limits_user" value="1" <?php checked( $this->properties['limits_user'] ); ?> /><label for="limits_user">&nbsp;<span><?php _e( 'User with a check mark is valid.', LOGIN_REBUILDER_DOMAIN ); ?></span></label><br />
<ul style="margin-left: 1.75em; margin-top: .25em;">
<?php foreach ( $users as $_user ) { ?>
<li style="display: inline-block; margin-right: .5em;"><input type="checkbox" name="properties[login_possible][]" id="login_possible_<?php echo $_user->ID; ?>" value="<?php echo esc_attr( $_user->user_login ); ?>" <?php checked( in_array( $_user->user_login, $this->properties['login_possible'] ) ); ?> /><label for="login_possible_<?php echo $_user->ID; ?>">&nbsp;<span><?php echo esc_html( $_user->user_nicename ); ?>(<?php echo implode( ',', array_map( 'translate_user_role', array_map( 'ucfirst', $_user->roles ) ) ); ?>)</span></label></li>
<?php } ?>
</ul>
<input type="checkbox" name="properties[limits_method]" id="limits_method" value="1" <?php checked( $this->properties['limits_method'] ); ?> /><label for="limits_method">&nbsp;<span><?php _e( 'Method with a check mark is valid.', LOGIN_REBUILDER_DOMAIN ); ?></span></label><br />
<ul style="margin-left: 1.75em; margin-top: .25em;">
<?php
	foreach ( $methods as $prefix=>$_methods ) {
		ob_start();
		$nactive = 0;
		foreach ( $_methods as $_no=>$_method ) {
		$iactive = in_array( $_method, $this->properties['active_method'] );
		if ( $iactive ) $nactive++;
?>
<li style="display: inline-block; margin-right: .5em;"><input type="checkbox" data-group="<?php echo esc_attr( $prefix ); ?>" name="properties[active_method][]" id="active_method_<?php echo $prefix.'_'.$_no; ?>" value="<?php echo esc_attr( $_method ); ?>" <?php checked( $iactive ); ?> /><label for="active_method_<?php echo $prefix.'_'.$_no; ?>">&nbsp;<span><?php echo esc_html( $_method ); ?></span></label></li>
<?php
		}
		$out = ob_get_clean();
?>
<li><input type="checkbox" data-group="<?php echo esc_attr( $prefix ); ?>" id="method_group_<?php echo esc_attr( $prefix ); ?>" value="<?php echo esc_attr( $prefix ); ?>" <?php ?> class="method_group" <?php checked( $nactive > 0 ); ?>/><label for="method_group_<?php echo esc_attr( $prefix ); ?>">&nbsp;<span><?php echo esc_html( ( strlen( $prefix ) == 2 )? strtoupper( $prefix ): ucfirst( $prefix ) ); ?> API</span></label>
<ul style="margin-left: 1.75em;<?php if ( $nactive === 0 ) echo ' display: none;'; ?>">
<?php echo $out; ?>
</ul>
</li>
<?php } ?>
</ul>
</td>
</tr>
<tr valign="top">
<th><?php _e( 'Pingback', LOGIN_REBUILDER_DOMAIN ); ?></th>
<td>
<input type="checkbox" name="properties[self_pingback]" id="self_pingback" value="1" <?php checked( $this->properties['self_pingback'] ); ?> /><label for="self_pingback">&nbsp;<span><?php _e( "Does not send the pingback from the own site to the own site.", LOGIN_REBUILDER_DOMAIN ); ?></span></label><br />
<input type="checkbox" name="properties[pingback_disabled]" id="pingback_disabled" value="1" <?php checked( $this->properties['pingback_disabled'] ); ?> /><label for="pingback_disabled">&nbsp;<span><?php _e( "Does not receive all pingbacks. [Important] If this is checked, the setting of the above-mentioned 'pingback.ping' is ignored.", LOGIN_REBUILDER_DOMAIN ); ?></span></label><br />
<?php if ( $this->_is_wp_version( '3.6', '>=' ) ) { ?>
<input type="checkbox" name="properties[pingback_receive]" id="pingback_receive" value="1" <?php checked( $this->properties['pingback_receive'] ); ?> /><label for="pingback_receive">&nbsp;<span><?php _e( "To limit the pingback to be received in a certain period of time.", LOGIN_REBUILDER_DOMAIN ); ?></span></label><br />
<ul style="margin-left: 1.75em; margin-top: .25em;">
<li<?php if ( in_array( 'receive_per_sec', $caution_number ) || in_array( 'receive_nsec', $caution_number ) ) echo ' class="red"'; ?>>
<?php
			echo sprintf( __( "Pingbacks can receive up to a maximum of %s per %s second(s).", LOGIN_REBUILDER_DOMAIN ),
				'<input type="number" name="properties[receive_per_sec]" id="receive_per_sec" value="'.$this->properties['receive_per_sec'].'" min="1" max="50" size="3" />',
				'<input type="number" name="properties[receive_nsec]" id="receive_nsec" value="'.$this->properties['receive_nsec'].'" min="1" max="60" size="3" />' );
?></li>
<li<?php if ( in_array( 'refuses_to_accept', $caution_number ) ) echo ' class="red"'; ?>><?php echo sprintf( __( "If the number of received pingback has exceeded the limit it refuses to accept %s minutes.", LOGIN_REBUILDER_DOMAIN ), '<input type="number" name="properties[refuses_to_accept]" id="refuses_to_accept" value="'.$this->properties['refuses_to_accept'].'" min="1" max="120" size="3" />' ); ?></li>
<li><?php _e( 'Status' ); ?> : <?php
if ( isset( $this->properties['refuses_datetime'] ) && !empty( $this->properties['refuses_datetime'] ) ) {
	$gmt_offset = get_option( 'gmt_offset' );
?><span style="color: #CC0000;"><?php echo sprintf( __( "It rejects the reception from %s", LOGIN_REBUILDER_DOMAIN ), date_i18n( 'H:i', $this->properties['refuses_datetime']+$gmt_offset*3600 ) ); ?></span>&nbsp;<input type="submit" name="submit" value="<?php esc_attr_e( 'Acceptance resumes', LOGIN_REBUILDER_DOMAIN ); ?>" class="button" />
<?php } else { _e( 'Accepting', LOGIN_REBUILDER_DOMAIN ); } ?></li>
</ul>
<?php } ?>
</td>
</tr>

<tr valign="top">
<th><?php _e( 'Status' ); ?></th>
<td>
<input type="radio" name="properties[xmlrpc_enhanced]" id="properties_xmlrpc_enhanced_0" value="<?php echo esc_attr( self::XMLRPC_ENHANCED_IN_PREPARATION ); ?>" <?php checked( $this->properties['xmlrpc_enhanced'] == self::XMLRPC_ENHANCED_IN_PREPARATION ); ?> /><label for="properties_xmlrpc_enhanced_0">&nbsp;<span><?php _e( 'in preparation', LOGIN_REBUILDER_DOMAIN ); ?></span></label><br />
<input type="radio" name="properties[xmlrpc_enhanced]" id="properties_xmlrpc_enhanced_1" value="<?php echo esc_attr( self::XMLRPC_ENHANCED_WORKING ); ?>" <?php checked( $this->properties['xmlrpc_enhanced'] == self::XMLRPC_ENHANCED_WORKING ); ?> /><label for="properties_xmlrpc_enhanced_1">&nbsp;<span><?php _e( 'working', LOGIN_REBUILDER_DOMAIN ); ?></span></label><br />
</td>
</tr>

<tr valign="top">
<td colspan="2">
<input type="submit" name="submit" value="<?php esc_attr_e( 'Save Changes' ); ?>" class="button-primary" />
<?php wp_nonce_field( self::XMLRPC_PROPERTIES_NAME.$this->_nonce_suffix() ); ?>
</td>
</tr>
</table>
</form>
</div><!-- .metabox-holder -->
</div><!-- .wrap -->

<script type="text/javascript">
( function($) {
	$( '.method_group' ).click( function () {
		$( 'input[data-group="'+$(this).data( 'group' )+'"]' ).prop( 'checked', $(this).prop( 'checked' ) );
		if ( $(this).prop( 'checked' ) )
			$(this).siblings( 'ul' ).show();
		else
			$(this).siblings( 'ul' ).hide();
	} );
} )( jQuery );
</script>
