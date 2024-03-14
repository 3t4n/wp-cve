<?php 
// If this file was called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="acc-ind-col-1 ">
		<fieldset class="accodes_ind_field">
			<div>
				<p class="acc_info">
				<label for="_accodes_header_metabox" class="first-label accodes-label green-label">
					<?php
						// This runs the text through a translation and echoes it (for internationalization)
						_e( 'Header Codes', 'add-custom-codes' );
					?>
				</label>
					<?php _e( "Codes & scripts to add before <em>&lt;/head&gt;</em> section of this page. Use  <em>&lt;script&gt; &lt;/script&gt;</em>, <em>&lt;style&gt; &lt;/style&gt;</em> tags when necessary.", 'add-custom-codes' ); ?>
	</p>
				<textarea
					type="text"
					name="_accodes_header_metabox"
					id="_accodes_header_metabox" class="codemirror small-codemirror"
						  ><?php echo esc_attr( $header_script ); ?></textarea>
			</div>
			<p>
                <label for="accodes_hide_header" class="accodes-checkbox-label">
                            <input type="checkbox" <?php echo checked( $hide_header, 'on', false ) ?>
                                   name="accodes_hide_header" id="accodes_hide_header"/>
							<?php _e( "Hide Global Header Codes on this page", 'add-custom-codes' ); ?>
				</label>
				<?php _e( "Go to <em>Appearance -> Add Custom Codes</em> to see your Global Header Codes", 'add-custom-codes' ); ?>
			</p>
		</fieldset>
	
		<fieldset class="accodes_ind_field">
			<div>
				<p class="acc_info">
				<label for="_accodes_footer_metabox" class="accodes-label green-label">
					<?php
						_e( 'Footer Codes', 'add-custom-codes' );
					?>
				</label>
					<?php _e( "Codes & scripts to add before <em>&lt;/body&gt;</em> section of this page. Use <em>&lt;script&gt; &lt;/script&gt;</em>, <em>&lt;style&gt; &lt;/style&gt;</em> tags when necessary.", 'add-custom-codes' ); ?>
	</p>
				<textarea
					type="text"
					name="_accodes_footer_metabox"
					id="_accodes_footer_metabox" class="codemirror small-codemirror"
						  ><?php echo esc_attr( $footer_script ); ?></textarea>
				<p>
                <label for="accodes_hide_footer" class="accodes-checkbox-label">
                            <input type="checkbox" <?php echo checked( $hide_footer, 'on', false ) ?>
                                   name="accodes_hide_footer" id="accodes_hide_footer"/>
							<?php _e( "Hide Global Footer Codes on this page", 'add-custom-codes' ); ?>
                        </label>
				<?php _e( "Go to <em>Appearance -> Add Custom Codes</em> to see your Global Footer Codes", 'add-custom-codes' ); ?>
				</p>
			</div>
		</fieldset>
	
	</div>
	<div class="acc-ind-col-2">
		<p>
			<em>Add Custom Codes by Mak</em> plugin is <span>Free Forever</span> to use! <a class="acc-link1" href="https://donate.stripe.com/9AQdRz5xJ87c9i0bIS" target="_blank">Donate</a> or <a class="acc-link1" href="https://maktalseo.com/" target="_blank">Hire us for your next project</a> to support us!
		</p>
	</div>
	<div style="clear:both;"></div>