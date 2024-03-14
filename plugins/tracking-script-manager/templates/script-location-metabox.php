<select id="r8_tsm_script_location" name="r8_tsm_script_location">
    <option value="header"<?php if ( $location === 'header' ) { echo ' selected="selected"'; } ?>>Header</option>
    <option value="page"<?php if ( $location === 'page' ) { echo ' selected="selected"'; } ?>>After &lt;body&gt;</option>
    <option value="footer"<?php if ( $location === 'footer' ) { echo ' selected="selected"'; } ?>>Footer</option>
</select>

<p>Note: to use the "After &lt;body&gt;" location, you must include: <code>do_action( 'wp_body_open' );</code> directly after the opening <code>&lt;body&gt;</code> tag in your theme. Typically this can be found in header.php but can vary theme to theme.<br />
For more information on setting this up see <a href="https://red8interactive.com/products/tracking-scripts-manager/">Tracking Script Manager's landing page</a>.</p>