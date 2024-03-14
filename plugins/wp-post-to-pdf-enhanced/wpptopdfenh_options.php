<script type="text/javascript">
    jQuery(document).ready(function() {
        // hides as soon as the DOM is ready
        jQuery('div.wpptopdfenh-option-body').hide();
        // shows on clicking the noted link
        jQuery('h3').click(function() {
            jQuery(this).toggleClass("open");
            jQuery(this).next("div").slideToggle('1000');
            return false;
        });
        jQuery('.button-secondary').click(function() {
            return confirm('Are you sure you want to clear the cache? This is not required if you have not changed any PDF Formatting Options.');
        });
    });
</script>
<div id="wpptopdfenh-options" class="wpptopdfenh-option wrap">
<div id="icon-options-general" class="icon32"><br></div>
<h2>WP Post to PDF Enhanced Options</h2>
<p>For detailed documentation visit <a target="_blank" title="WP Post to PDF Enhanced" rel="bookmark"
                                       href="http://www.2rosenthals.net/wordpress/help/general-help/wp-post-to-pdf-enhanced/">WP Post to PDF Enhanced</a>
</p>
<p>Feel free to comment, report issues, or request enhancements.</p>
<form method="post" action="options.php">
<?php settings_fields( 'wpptopdfenh_options' );
$wpptopdfenhopts = get_option( 'wpptopdfenh' ); ?>
<h3>Include/Exclude Content Types, Posts, Pages; Caching</h3>
<div class="wpptopdfenh-option-body">
    <table class="form-table">
        <tr valign="top">
            <th scope="row">Allowed Custom Post Types</th>
            <td>
                <?php
$post_types = get_post_types( array( 'public'   => true ), 'names' );
foreach ( $post_types as $post_type ) { ?>
                    <input name="wpptopdfenh[<?php echo $post_type; ?>]"
                           value="1" <?php echo ( isset( $wpptopdfenhopts[$post_type] ) ) ? 'checked="checked"' : ''; ?>
                           type="checkbox"/> <?php echo $post_type; ?><br/>
                <?php } ?>
                <p>Select custom post types where you want users to be able to generate PDF content.</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Non Public Only</th>
            <td>
                <input name="wpptopdfenh[nonPublic]"
                       value="1" <?php echo ( isset( $wpptopdfenhopts['nonPublic'] ) ) ? 'checked="checked"' : ''; ?>
                       type="checkbox"/>
                <p>Select if you want to disable PDF content for public users. If selected, only logged in users
                    will be able to generate PDF content.</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Only on Single</th>
            <td>
                <input name="wpptopdfenh[onSingle]"
                       value="1" <?php echo ( isset( $wpptopdfenhopts['onSingle'] ) ) ? 'checked="checked"' : ''; ?>
                       type="checkbox"/>
                <p>Select if you want to display the PDF icon only on a single page. If selected, the front page will not
                    display the PDF icon.</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Include/Exclude</th>
            <td>
                <input name="wpptopdfenh[include]"
                       value="0" <?php echo ( $wpptopdfenhopts['include'] ) ? '' : 'checked="checked"'; ?>
                       type="radio"/> Exclude the following&nbsp;&nbsp;&nbsp;
                <input name="wpptopdfenh[include]"
                       value="1" <?php echo ( $wpptopdfenhopts['include'] ) ? 'checked="checked"' : ''; ?>
                       type="radio"/> Include the following
                <br/>
                <input type="text" name="wpptopdfenh[excludeThis]" id="wpptopdfenh[excludeThis]"
                       value="<?php echo ( $wpptopdfenhopts['excludeThis'] ) ? $wpptopdfenhopts['excludeThis'] : ''; ?>"/>
                <p>Enter a list of comma-separated post/page IDs which you want to include/exclude from generating PDF content (show/hide PDF icon).<br/><span
                            class="wpptopdfenh-notice">To allow PDF content generation on all posts/pages, select "Exclude the following" and leave the textbox empty.</span></p>
            </td>
        </tr>
	    <tr valign="top">
            <th scope="row">Include/Exclude from Cache</th>
            <td>
                <input name="wpptopdfenh[includeCache]"
                       value="0" <?php echo ( $wpptopdfenhopts['includeCache'] ) ? '' : 'checked="checked"'; ?>
                       type="radio"/> Exclude the following&nbsp;&nbsp;&nbsp;
                <input name="wpptopdfenh[includeCache]"
                       value="1" <?php echo ( $wpptopdfenhopts['includeCache'] ) ? 'checked="checked"' : ''; ?>
                       type="radio"/> Include the following
                <br/>
                <input type="text" name="wpptopdfenh[excludeThisCache]" id="wpptopdfenh[excludeThisCache]"
                       value="<?php echo ( $wpptopdfenhopts['excludeThisCache'] ) ? $wpptopdfenhopts['excludeThisCache'] : ''; ?>"/>
                <p>Enter a list of comma-separated post/page IDs for which you want to disable PDF caching.
	                The PDF will be generated on the fly when requested for these posts/pages. This is useful when
	                the content of your post(s)/page(s) is/are updated frequently by another plugin (e.g., "RSS in Page").<br/><span
                            class="wpptopdfenh-notice">To use caching on all posts/pages, select "Exclude the following" and leave the textbox empty.</span></p>
            </td>
        </tr>
    </table>
</div>
<h3>Icon/Link Presentation</h3>
<div class="wpptopdfenh-option-body">
    <table class="form-table">
        <tr valign="top">
            <th scope="row">Icon Position</th>
            <td>
                <?php
$iconPosition = array( 'Before' => 'before', 'After' => 'after', 'Before and After' => 'beforeandafter', 'Manual' => 'manual' );
echo '<select name="wpptopdfenh[iconPosition]">';
foreach ( $iconPosition as $key => $value ) {
	if ( $wpptopdfenhopts['iconPosition'] ) {
		$checked = ( $wpptopdfenhopts['iconPosition'] == $value ) ? 'selected="selected"' : '';
	}
	echo '<option value="' . $value . '" ' . $checked . ' >' . $key . '</option>';
}
echo '</select>';
?><br/>
                <?php
$iconLeftRight = array( 'Left' => 'left', 'Right' => 'right' );
echo '<select name="wpptopdfenh[iconLeftRight]">';
foreach ( $iconLeftRight as $key => $value ) {
	if ( $wpptopdfenhopts['iconPosition'] ) {
		$checked = ( $wpptopdfenhopts['iconLeftRight'] == $value ) ? 'selected="selected"' : '';
	}
	echo '<option value="' . $value . '" ' . $checked . ' >' . $key . '</option>';
}
echo '</select>';
?>
                <p>Select where to place the PDF icon (before or after content; left or right aligned). <br/><span class="wpptopdfenh-notice">If you select manual, the left/right alignment setting will be ignored. Use following code within your theme
          to place the icon in the desired location:</span><br/>
                    <code><?php echo htmlentities( '<?php if (function_exists("wpptopdfenh_display_icon")) echo wpptopdfenh_display_icon();?>' ); ?></code><br/>
                </p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">PDF Download Icon</th>
            <td>
                <textarea id="imageIconSrc"
                          name="wpptopdfenh[imageIcon]"><?php echo ( $wpptopdfenhopts['imageIcon'] ) ? $wpptopdfenhopts['imageIcon'] : '<img alt="Download PDF" src="' . WPPTOPDFENH_URL . '/asset/images/pdf.png">'; ?></textarea>
                <p>Enter the content you would like to display for the PDF download icon (you may use HTML). <br/><span
                        class="wpptopdfenh-notice">Use the following code in the textbox above for the included PDF icon.</span><br/><code><?php echo htmlentities( '<img alt="Download PDF" src="' . WPPTOPDFENH_URL . '/asset/images/pdf.png">' );  ?></code>
                </p>
            </td>
        </tr>
    </table>
</div>
<h3>General</h3>
<div class="wpptopdfenh-option-body">
    <table class="form-table">
        <tr valign="top">
            <th scope="row">Other Plugins</th>
            <td>
                <input name="wpptopdfenh[otherPlugin]"
                       value="1" <?php echo ( isset( $wpptopdfenhopts['otherPlugin'] ) ) ? 'checked="checked"' : ''; ?>
                       type="checkbox"/>
                <p>Select if you would like to include formatting applied by other plugins in the PDF.</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Process Shortcodes</th>
            <td>
                <input name="wpptopdfenh[processShortcodes]"
                       value="1" <?php echo ( isset( $wpptopdfenhopts['processShortcodes'] ) ) ? 'checked="checked"' : ''; ?>
                       type="checkbox"/>
                <p>Select if you would like to process shortcodes and display their results in the PDF.</p>
            </td>
        </tr>
       <?php $author = array( 'None' => '', 'Username' => 'user_nicename', 'Display Name' => 'display_name', 'Nickname' => 'nickname' ); ?>
        <tr valign="top">
            <th scope="row">Display Author Detail</th>
            <td>
                <?php
echo '<select name="wpptopdfenh[authorDetail]">';
foreach ( $author as $key => $value ) {
	if ( $wpptopdfenhopts['authorDetail'] == '' ) {
		'selected="None"';
		$checked = ( $wpptopdfenhopts['authorDetail'] == $value ) ? 'selected="selected"' : '';
		echo '<option value="' . $value . '" ' . $checked . ' >' . $key . '</option>';
	} else {
		if ( $wpptopdfenhopts['authorDetail'] ) {
			$checked = ( $wpptopdfenhopts['authorDetail'] == $value ) ? 'selected="selected"' : '';
		}
		echo '<option value="' . $value . '" ' . $checked . ' >' . $key . '</option>';
	}
}
echo '</select>';
?>
                    <p>Select if you would like to include the author's name in the PDF, and how it should be formatted.</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Display Post Category List</th>
            <td>
                <input name="wpptopdfenh[postCategories]"
                       value="1" <?php echo ( isset( $wpptopdfenhopts['postCategories'] ) ) ? 'checked="checked"' : ''; ?>
                       type="checkbox"/>
                <p>Select if you would like to include the post category list in the PDF.</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Display Post Tag List</th>
            <td>
                <input name="wpptopdfenh[postTags]"
                       value="1" <?php echo ( isset( $wpptopdfenhopts['postTags'] ) ) ? 'checked="checked"' : ''; ?>
                       type="checkbox"/>
                <p>Select if you would like to include the post tag list in the PDF.</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Display Post Date</th>
            <td>
                <input name="wpptopdfenh[postDate]"
                       value="1" <?php echo ( isset( $wpptopdfenhopts['postDate'] ) ) ? 'checked="checked"' : ''; ?>
                       type="checkbox"/>
                <p>Select if you would like to include the post date in the PDF.</p>
            </td>
        </tr>
        <tr valign="top">
	</table>
</div>
<h3>Header</h3>
<div class="wpptopdfenh-option-body">
    <table class="form-table">
        <tr valign="top">
            <th scope="row">Header Display</th>
            <td>
                <?php
$header = array( 'All Pages' => 'all', 'First Page Only' => 'first', 'Suppressed' => 'none' );
echo '<select name="wpptopdfenh[headerAllPages]">';
foreach ( $header as $key => $value ) {
	if ( $wpptopdfenhopts['headerAllPages'] ) {
		$checked = ( $wpptopdfenhopts['headerAllPages'] == $value ) ? 'selected="selected"' : '';
	}
	echo '<option value="' . $value . '" ' . $checked . ' >' . $key . '</option>';
}
echo '</select>';
?><br/>
                    <p>Select if you would like to include the header in the PDF, and whether it should be on all pages or just the first page (default is All Pages).</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Header Logo Image</th>
            <td>
                <input name="wpptopdfenh[headerlogoImage]"
                       value="1" <?php echo ( isset( $wpptopdfenhopts['headerlogoImage'] ) ) ? 'checked="checked"' : ''; ?>
                       type="checkbox"/>
                <p>Select if you would like to display the logo image in the PDF header. It will be displayed in the upper left of the header.</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"></th>
            <td>
                <?php if ( file_exists( WP_CONTENT_DIR . '/uploads/wp-post-to-pdf-enhanced-logo.png' ) ) { ?>
                <img src="<?php echo WP_CONTENT_URL . '/uploads/wp-post-to-pdf-enhanced-logo.png'; ?>"
                     alt="<?php bloginfo( 'name' );?>"/>
                <p>To change this image, replace it here
                    '<?php echo WP_CONTENT_DIR . '/uploads/wp-post-to-pdf-enhanced-logo.png'; ?>'</p>
                <?php
}
else {
?>
                <p><span
                        class="wpptopdfenh-notice">Logo image not found. Please upload it to  '<?php echo WP_CONTENT_DIR . '/uploads/wp-post-to-pdf-enhanced-logo.png'; ?>
                    '.</span></p>
                <?php } ?>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Header Logo Image Factor</th>
            <td>
                <input type="text" name="wpptopdfenh[headerlogoImageFactor]" id="wpptopdfenh[headerlogoImageFactor]"
                       value="<?php echo ( $wpptopdfenhopts['headerlogoImageFactor'] ) ? $wpptopdfenhopts['headerlogoImageFactor'] : '14'; ?>"/>
                <p>Enter your desired factor to be applied to the logo (default is 14). This is applied to logo width/logo height, to provide space around the logo image. It <em>will</em> adjust the overall size of the logo as well as the surrounding space.</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Header Margin</th>
            <td>
                <input type="text" name="wpptopdfenh[marginHeader]" id="wpptopdfenh[marginHeader]"
                       value="<?php echo ( $wpptopdfenhopts['marginHeader'] ) ? $wpptopdfenhopts['marginHeader'] : '5'; ?>"/>
                <p>Enter your desired top margin for the header (default is 5<?php echo $wpptopdfenhopts['unitMeasure']?>).</p>
            </td>
        </tr>
	</table>
</div>
<h3>Body</h3>
<div class="wpptopdfenh-option-body">
    <table class="form-table">
        <tr valign="top">
            <th scope="row">Display Featured Image</th>
            <td>
                <input name="wpptopdfenh[featuredImage]"
                       value="1" <?php echo ( isset( $wpptopdfenhopts['featuredImage'] ) ) ? 'checked="checked"' : ''; ?>
                       type="checkbox"/>
                <p>Select if you would like to display the featured image in the PDF header. If a featured image has been set for the particular post/page, it will be displayed just below the title.</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Image Scaling Ratio</th>
            <td>
                <input type="text" name="wpptopdfenh[imageScale]" id="wpptopdfenh[imageScale]"
                       value="<?php echo ( $wpptopdfenhopts['imageScale'] ) ? $wpptopdfenhopts['imageScale'] : '1.25'; ?>"/>
                <p>Enter your desired image scaling ratio as a decimal (default is 1.25). This represents the relative size of the image in the browser vs the size of the image in the PDF. Thus, 1.25 yields a 1.25:1 scale of web:PDF.</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Custom CSS</th>
            <td>
                <input name="wpptopdfenh[applyCSS]"
                       value="1" <?php echo ( isset( $wpptopdfenhopts['applyCSS'] ) ) ? 'checked="checked"' : ''; ?>
                       type="checkbox"/>
                <p>Select if you would like to apply a custom css to all PDFs generated.</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"></th>
            <td>
                <textarea id="customCssSrc"
                          name="wpptopdfenh[customCss]"><?php echo ( $wpptopdfenhopts['customCss'] ) ? $wpptopdfenhopts['customCss'] : '' ?></textarea>
                <p>Use the editor above to create or edit custom css to be applied to all PDFs.</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Custom Bullet Image</th>
            <td>
                <input name="wpptopdfenh[liSymbol]"
                       value="1" <?php echo ( isset( $wpptopdfenhopts['liSymbol'] ) ) ? 'checked="checked"' : ''; ?>
                       type="checkbox"/>
                <p>Select if you would like to render list bullets in the PDF as an image. If so, please specify details, below.</p>
            </td>
        </tr>
       <?php $symbol = array( 
		'JPG' => 'jpg', 
		'PNG' => 'png',
		); ?>
        <tr valign="top">
            <th scope="row">Custom Bullet Image Type</th>
            <td>
                <?php
echo '<select name="wpptopdfenh[liSymbolType]">';
foreach ( $symbol as $key => $value ) {
	if ( $wpptopdfenhopts['liSymbolType'] == '' ) {
		'selected="JPG"';
		$checked = ( $wpptopdfenhopts['liSymbolType'] == $value ) ? 'selected="selected"' : '';
		echo '<option value="' . $value . '" ' . $checked . ' >' . $key . '</option>';
	} else {
		if ( $wpptopdfenhopts['liSymbolType'] ) {
			$checked = ( $wpptopdfenhopts['liSymbolType'] == $value ) ? 'selected="selected"' : '';
		}
		echo '<option value="' . $value . '" ' . $checked . ' >' . $key . '</option>';
	}
}
echo '</select>';
?>
                    <p>Select the custom bullet image file type (default is JPG).</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Custom Bullet Image Width</th>
            <td>
                <input type="text" name="wpptopdfenh[liSymbolWidth]" id="wpptopdfenh[liSymbolWidth]"
                       value="<?php echo ( $wpptopdfenhopts['liSymbolWidth'] ) ? $wpptopdfenhopts['liSymbolWidth'] : '3'; ?>"/>
                <p>Enter your desired width for the bullet image (default is 3<?php echo $wpptopdfenhopts['unitMeasure']?>).</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Custom Bullet Image Height</th>
            <td>
                <input type="text" name="wpptopdfenh[liSymbolHeight]" id="wpptopdfenh[liSymbolHeight]"
                       value="<?php echo ( $wpptopdfenhopts['liSymbolHeight'] ) ? $wpptopdfenhopts['liSymbolHeight'] : '2'; ?>"/>
                <p>Enter your desired height for the bullet image (default is 2<?php echo $wpptopdfenhopts['unitMeasure']?>).</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Custom Bullet Image File</th>
            <td>
                <input type="text" name="wpptopdfenh[liSymbolFile]" id="wpptopdfenh[liSymbolFile]"
                       value="<?php echo ( $wpptopdfenhopts['liSymbolFile'] ) ? $wpptopdfenhopts['liSymbolFile'] : ''; ?>"/>
                <p>Enter your desired custom image file for list bullets (upload to '<?php echo WP_CONTENT_DIR . '/uploads/'; ?>').</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Top Margin</th>
            <td>
                <input type="text" name="wpptopdfenh[marginTop]" id="wpptopdfenh[marginTop]"
                       value="<?php echo ( $wpptopdfenhopts['marginTop'] ) ? $wpptopdfenhopts['marginTop'] : '27'; ?>"/>
                <p>Enter your desired top margin (default is 27<?php echo $wpptopdfenhopts['unitMeasure']?>).</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Left Margin</th>
            <td>
                <input type="text" name="wpptopdfenh[marginLeft]" id="wpptopdfenh[marginLeft]"
                       value="<?php echo ( $wpptopdfenhopts['marginLeft'] ) ? $wpptopdfenhopts['marginLeft'] : '15'; ?>"/>
                <p>Enter your desired left margin (default is 15<?php echo $wpptopdfenhopts['unitMeasure']?>).</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Right Margin</th>
            <td>
                <input type="text" name="wpptopdfenh[marginRight]" id="wpptopdfenh[marginRight]"
                       value="<?php echo ( $wpptopdfenhopts['marginRight'] ) ? $wpptopdfenhopts['marginRight'] : '15'; ?>"/>
                <p>Enter your desired right margin (default is 15<?php echo $wpptopdfenhopts['unitMeasure']?>).</p>
            </td>
        </tr>
	</table>
</div>
<h3>Footer</h3>
<div class="wpptopdfenh-option-body">
    <table class="form-table">
        <tr valign="top">
            <th scope="row">Custom Footer</th>
            <td>
                <input name="wpptopdfenh[customFooter]"
                       value="1" <?php echo ( isset( $wpptopdfenhopts['customFooter'] ) ) ? 'checked="checked"' : ''; ?>
                       type="checkbox"/>
                <p>Select if you would like to use custom footer content on all PDFs.</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"></th>
            <td>
                <textarea id="customFooterText"
                          name="wpptopdfenh[customFooterText]"><?php echo ( $wpptopdfenhopts['customFooterText'] ) ? $wpptopdfenhopts['customFooterText'] : '' ?></textarea>
                <p>Use the editor above to create or edit custom footer content to be added to all PDFs.</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Footer Cell Width</th>
            <td>
                <input type="text" name="wpptopdfenh[footerWidth]" id="wpptopdfenh[footerWidth]"
                       value="<?php echo ( $wpptopdfenhopts['footerWidth'] ) ? $wpptopdfenhopts['footerWidth'] : '0'; ?>"/>
                <p>Enter your desired width (in <?php echo $wpptopdfenhopts['unitMeasure']?>) for the footer cell (default is 0<?php echo $wpptopdfenhopts['unitMeasure']?>). If 0, the cell extends up to the right margin.</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Footer Cell Minimum Height</th>
            <td>
                <input type="text" name="wpptopdfenh[footerMinHeight]" id="wpptopdfenh[footerMinHeight]"
                       value="<?php echo ( $wpptopdfenhopts['footerMinHeight'] ) ? $wpptopdfenhopts['footerMinHeight'] : '0'; ?>"/>
                <p>Enter your desired minimum height (in <?php echo $wpptopdfenhopts['unitMeasure']?>) for the footer cell (default is 0<?php echo $wpptopdfenhopts['unitMeasure']?>). The cell extends automatically if needed.</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Footer Cell Upper Left Corner (X)</th>
            <td>
                <input type="text" name="wpptopdfenh[footerX]" id="wpptopdfenh[footerX]"
                       value="<?php echo ( $wpptopdfenhopts['footerX'] ) ? $wpptopdfenhopts['footerX'] : '10'; ?>"/>
                <p>Enter your desired positioning (offset in <?php echo $wpptopdfenhopts['unitMeasure']?> from left margin) for the upper left corner of the footer cell (X coordinate; default is 10<?php echo $wpptopdfenhopts['unitMeasure']?>).</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Footer Cell Upper Left Corner (Y)</th>
            <td>
                <input type="text" name="wpptopdfenh[footerY]" id="wpptopdfenh[footerY]"
                       value="<?php echo ( $wpptopdfenhopts['footerY'] ) ? $wpptopdfenhopts['footerY'] : '270'; ?>"/>
                <p>Enter your desired positioning (offset in <?php echo $wpptopdfenhopts['unitMeasure']?> from top margin) for the upper left corner of the footer cell (Y coordinate; default is 260<?php echo $wpptopdfenhopts['unitMeasure']?>).</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Footer Cell Border</th>
            <td>
                <input type="text" name="wpptopdfenh[footerCellBorder]" id="wpptopdfenh[footerCellBorder]"
                       value="<?php echo ( $wpptopdfenhopts['footerCellBorder'] ) ? $wpptopdfenhopts['footerCellBorder'] : ''; ?>"/>
                <p>Enter your desired border for the footer cell (default is none). Valid entries are:<br/>
                	<li>1: renders a frame</li>
                	or a string containing some or all of the following characters (in any order):<br/>
                	<li>L: left border</li>
                	<li>T: top border</li>
                	<li>R: right border</li>
                	<li>B: bottom border</li>
                	<span class="wpptopdfenh-notice">or an array of line styles for each border group:</span><br/>
                	<code>array('LTRB' => array('width' => 2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)))</code></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Footer Cell Fill</th>
            <td>
		<input name="wpptopdfenh[footerFill]"
                       value="1" <?php echo ( isset( $wpptopdfenhopts['footerFill'] ) ) ? 'checked="checked"' : ''; ?>
                       type="checkbox"/>
                <p>Select if you would like the footer cell to be painted (default is no).</p>
            </td>
        </tr>
       <?php $footeralign = array( 'Auto' => '', 'Left' => 'L', 'Right' => 'R', 'Center' => 'C' ); ?>
        <tr valign="top">
            <th scope="row">Footer Cell Text Alignment</th>
            <td>
		<?php
echo '<select name="wpptopdfenh[footerAlign]">';
foreach ( $footeralign as $key => $value ) {
	if ( $wpptopdfenhopts['footerAlign'] ) {
		$checked = ( $wpptopdfenhopts['footerAlign'] == $value ) ? 'selected="selected"' : '';
	}
	echo '<option value="' . $value . '" ' . $checked . ' >' . $key . '</option>';
}
echo '</select>';
?>
                <p>Select your desired text alignment for the footer cell (default is auto left-to-right or right-to-left).</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Footer Cell Auto-padding</th>
            <td>
		<input name="wpptopdfenh[footerPad]"
                       value="1" <?php echo ( isset( $wpptopdfenhopts['footerPad'] ) ) ? '' : 'checked="checked"'; ?>
                       type="checkbox"/>
                <p>Select if you would like to automatically adjust internal padding to account for line width (default is yes).</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Footer Margin</th>
            <td>
                <input type="text" name="wpptopdfenh[marginFooter]" id="wpptopdfenh[marginFooter]"
                       value="<?php echo ( $wpptopdfenhopts['marginFooter'] ) ? $wpptopdfenhopts['marginFooter'] : '10'; ?>"/>
                <p>Enter your desired bottom margin for the footer (default is 10<?php echo $wpptopdfenhopts['unitMeasure']?>). This is the minimum distance between the footer and the bottom page margin.</p>
            </td>
        </tr>
	</table>
</div>
<h3>Typography</h3>
<div class="wpptopdfenh-option-body">
    <table class="form-table">
        <?php $fonts = array(
	'Al Arabiya'                              => 'aealarabiya',
	'Furat'                                   => 'aefurat',
	'Arial'                                   => 'helvetica',
	'Arial Bold'                              => 'helveticab',
	'Arial Bold Italic'                       => 'helveticabi',
	'Arial Italic'                            => 'helveticai',
	'Courier'                                 => 'courier',
	'Courier Bold'                            => 'courierb',
	'Courier Bold Italic'                     => 'courierbi',
	'Courier Italic'                          => 'courieri',
	'DejaVu Sans'                             => 'dejavusans',
	'DejaVu Sans Bold'                        => 'dejavusansb',
	'DejaVu Sans Bold Italic'                 => 'dejavusansbi',
	'DejaVu Sans Condensed'                   => 'dejavusanscondensed',
	'DejaVu Sans Condensed Bold'              => 'dejavusanscondensedb',
	'DejaVu Sans Condensed Bold Italic'       => 'dejavusanscondensedbi',
	'DejaVu Sans Condensed Italic'            => 'dejavusanscondensedi',
	'DejaVu Sans Extra Light'                 => 'dejavusansextralight',
	'DejaVu Sans Italic'                      => 'dejavusansi',
	'DejaVu Sans Mono'                        => 'dejavusansmono',
	'DejaVu Sans Mono Bold'                   => 'dejavusansmonob',
	'DejaVu Sans Mono Bold Italic'            => 'dejavusansmonobi',
	'DejaVu Sans Mono Italic'                 => 'dejavusansmonoi',
	'DejaVu Serif'                            => 'dejavuserif',
	'DejaVu Serif Bold'                       => 'dejavuserifb',
	'DejaVu Serif Bold Italic'                => 'dejavuserifbi',
	'DejaVu Serif Condensed'                  => 'dejavuserifcondensed',
	'DejaVu Serif Condensed Bold'             => 'dejavuserifcondensedb',
	'DejaVu Serif Condensed Bold Italic'      => 'dejavuserifcondensedbi',
	'DejaVu Serif Condensed Italic'           => 'dejavuserifcondensedi',
	'DejaVu Serif Italic'                     => 'dejavuserifi',
	'Free Mono'                               => 'freemono',
	'Free Mono Bold'                          => 'freemonob',
	'Free Mono Bold Italic'                   => 'freemonobi',
	'Free Mono Italic'                        => 'freemonoi',
	'Free Sans'                               => 'freesans',
	'Free Sans Bold'                          => 'freesansb',
	'Free Sans Bold Italic'                   => 'freesansbi',
	'Free Sans Italic'                        => 'freesansi',
	'Free Serif'                              => 'freeserif',
	'Free Serif Bold'                         => 'freeserifb',
	'Free Serif Bold Italic'                  => 'freeserifbi',
	'Free Serif Italic'                       => 'freeserifi',
	'Helvetica'                               => 'helvetica',
	'Helvetica Bold'                          => 'helveticab',
	'Helvetica Bold Italic'                   => 'helveticabi',
	'Helvetica Italic'                        => 'helveticai',
	'Kozuka Gothic Pro (Japanese Sans-Serif)' => 'kozgopromedium',
	'Kozuka Mincho Pro (Japanese Serif)'      => 'kozminproregular',
	'MSung Light (Trad. Chinese)'             => 'msungstdlight',
	'MyungJo Medium (Korean)'                 => 'hysmyeongjostdmedium',
	'PDF/A Courier'                           => 'pdfacourier',
	'PDF/A Courier Bold'                      => 'pdfacourierb',
	'PDF/A Courier Bold Italic'               => 'pdfacourierbi',
	'PDF/A Courier Italic'                    => 'pdfacourieri',
	'PDF/A Helvetica'                         => 'pdfahelvetica',
	'PDF/A Helvetica Bold'                    => 'pdfahelveticab',
	'PDF/A Helvetica Bold Italic'             => 'pdfahelveticabi',
	'PDF/A Helvetica Italic'                  => 'pdfahelveticai',
	'PDF/A Symbol'                            => 'pdfasymbol',
	'PDF/A Times Roman'                       => 'pdfatimes',
	'PDF/A Times Bold'                        => 'pdfatimesb',
	'PDF/A Times Bold Italic'                 => 'pdfatimesbi',
	'PDF/A Times Italic'                      => 'pdfatimesi',
	'PDF/A ZapfDingbats'                      => 'pdfazapfdingbats',
	'STSong Light (Simp. Chinese)'            => 'stsongstdlight',
	'Symbol'                                  => 'symbol',
	'Times Roman'                             => 'times',
	'Times Bold'                              => 'timesb',
	'Times Bold Italic'                       => 'timesbi',
	'Times Italic'                            => 'timesi',
	'ZapfDingbats'                            => 'zapfdingbats',
); ?>
        <tr valign="top">
            <th scope="row">Header Font</th>
            <td>
                <?php
echo '<select name="wpptopdfenh[headerFont]">';
foreach ( $fonts as $key => $value ) {
	if ( $wpptopdfenhopts['headerFont'] ) {
		$checked = ( $wpptopdfenhopts['headerFont'] == $value ) ? 'selected="selected"' : '';
	}
	echo '<option value="' . $value . '" ' . $checked . ' >' . $key . '</option>';
}
echo '</select>';
?>
                    <p>Select a font for the header.</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Header Font Size</th>
            <td>
                <input type="text" name="wpptopdfenh[headerFontSize]" id="wpptopdfenh[headerFontSize]"
                       value="<?php echo ( $wpptopdfenhopts['headerFontSize'] ) ? $wpptopdfenhopts['headerFontSize'] : '10'; ?>"/>
                <p>Enter the font size for header.</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Footer Font</th>
            <td>
                <?php
echo '<select name="wpptopdfenh[footerFont]">';
foreach ( $fonts as $key => $value ) {
	if ( $wpptopdfenhopts['footerFont'] ) {
		$checked = ( $wpptopdfenhopts['footerFont'] == $value ) ? 'selected="selected"' : '';
	}
	echo '<option value="' . $value . '" ' . $checked . ' >' . $key . '</option>';
}
echo '</select>';
?>
                    <p>Select a font for the footer.</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Footer Font Size</th>
            <td>
                <input type="text" name="wpptopdfenh[footerFontSize]" id="wpptopdfenh[footerFontSize]"
                       value="<?php echo ( $wpptopdfenhopts['footerFontSize'] ) ? $wpptopdfenhopts['footerFontSize'] : '10'; ?>"/>
                <p>Enter the font size for the footer.</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Content Font</th>
            <td>
                <?php
echo '<select name="wpptopdfenh[contentFont]">';
foreach ( $fonts as $key => $value ) {
	if ( $wpptopdfenhopts['contentFont'] ) {
		$checked = ( $wpptopdfenhopts['contentFont'] == $value ) ? 'selected="selected"' : '';
	}
	echo '<option value="' . $value . '" ' . $checked . ' >' . $key . '</option>';
}
echo '</select>';
?>
                    <p>Select the default monospaced font.</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Content Font Size</th>
            <td>
                <input type="text" name="wpptopdfenh[contentFontSize]" id="wpptopdfenh[contentFontSize]"
                       value="<?php echo ( $wpptopdfenhopts['contentFontSize'] ) ? $wpptopdfenhopts['contentFontSize'] : '12'; ?>"/>
                <p>Enter the font size for the main content.</p>
            </td>
        </tr>
    </table>
</div>
<h3>Page Size & Units</h3>
<div class="wpptopdfenh-option-body">
    <table class="form-table">
        <?php $page_size = array(
	'ISO 216 A Series + 2 SIS 014711 extensions (default: A4)'                    => 'A4',
	'A0 (841x1189 mm ; 33.11x46.81 in)'                                           => 'A0',
	'A1 (594x841 mm ; 23.39x33.11 in)'                                            => 'A1',
	'A2 (420x594 mm ; 16.54x23.39 in)'                                            => 'A2',
	'A3 (297x420 mm ; 11.69x16.54 in)'                                            => 'A3',
	'A4 (210x297 mm ; 8.27x11.69 in)'                                             => 'A4',
	'A5 (148x210 mm ; 5.83x8.27 in)'                                              => 'A5',
	'A6 (105x148 mm ; 4.13x5.83 in)'                                              => 'A6',
	'A7 (74x105 mm ; 2.91x4.13 in)'                                               => 'A7',
	'A8 (52x74 mm ; 2.05x2.91 in)'                                                => 'A8',
	'A9 (37x52 mm ; 1.46x2.05 in)'                                                => 'A9',
	'A10 (26x37 mm ; 1.02x1.46 in)'                                               => 'A10',
	'A11 (18x26 mm ; 0.71x1.02 in)'                                               => 'A11',
	'A12 (13x18 mm ; 0.51x0.71 in)'                                               => 'A12',
	'ISO 216 B Series + 2 SIS 014711 extensions (default: B4)'                    => 'B4',
	'B0 (1000x1414 mm ; 39.37x55.67 in)'                                          => 'B0',
	'B1 (707x1000 mm ; 27.83x39.37 in)'                                           => 'B1',
	'B2 (500x707 mm ; 19.69x27.83 in)'                                            => 'B2',
	'B3 (353x500 mm ; 13.90x19.69 in)'                                            => 'B3',
	'B4 (250x353 mm ; 9.84x13.90 in)'                                             => 'B4',
	'B5 (176x250 mm ; 6.93x9.84 in)'                                              => 'B5',
	'B6 (125x176 mm ; 4.92x6.93 in)'                                              => 'B6',
	'B7 (88x125 mm ; 3.46x4.92 in)'                                               => 'B7',
	'B8 (62x88 mm ; 2.44x3.46 in)'                                                => 'B8',
	'B9 (44x62 mm ; 1.73x2.44 in)'                                                => 'B9',
	'B10 (31x44 mm ; 1.22x1.73 in)'                                               => 'B10',
	'B11 (22x31 mm ; 0.87x1.22 in)'                                               => 'B11',
	'B12 (15x22 mm ; 0.59x0.87 in)'                                               => 'B12',                               
	'ISO 216 C Series + 2 SIS 014711 extensions + 2 EXTENSION (default: C4)'      => 'C4',
	'C0 (917x1297 mm ; 36.10x51.06 in)'                                           => 'C0',
	'C1 (648x917 mm ; 25.51x36.10 in)'                                            => 'C1',
	'C2 (458x648 mm ; 18.03x25.51 in)'                                            => 'C2',
	'C3 (324x458 mm ; 12.76x18.03 in)'                                            => 'C3',
	'C4 (229x324 mm ; 9.02x12.76 in)'                                             => 'C4',
	'C5 (162x229 mm ; 6.38x9.02 in)'                                              => 'C5',
	'C6 (114x162 mm ; 4.49x6.38 in)'                                              => 'C6',
	'C7 (81x114 mm ; 3.19x4.49 in)'                                               => 'C7',
	'C8 (57x81 mm ; 2.24x3.19 in)'                                                => 'C8',
	'C9 (40x57 mm ; 1.57x2.24 in)'                                                => 'C9',
	'C10 (28x40 mm ; 1.10x1.57 in)'                                               => 'C10',
	'C11 (20x28 mm ; 0.79x1.10 in)'                                               => 'C11',
	'C12 (14x20 mm ; 0.55x0.79 in)'                                               => 'C12',
	'C76 (81x162 mm ; 3.19x6.38 in)'                                              => 'C76',
	'DL (110x220 mm ; 4.33x8.66 in)'                                              => 'DL',
	'SIS 014711 E Series (default: E4)'                                           => 'E4',
	'E0 (879x1241 mm ; 34.61x48.86 in)'                                           => 'E0',
	'E1 (620x879 mm ; 24.41x34.61 in)'                                            => 'E1',
	'E2 (440x620 mm ; 17.32x24.41 in)'                                            => 'E2',
	'E3 (310x440 mm ; 12.20x17.32 in)'                                            => 'E3',
	'E4 (220x310 mm ; 8.66x12.20 in)'                                             => 'E4',
	'E5 (155x220 mm ; 6.10x8.66 in)'                                              => 'E5',
	'E6 (110x155 mm ; 4.33x6.10 in)'                                              => 'E6',
	'E7 (78x110 mm ; 3.07x4.33 in)'                                               => 'E7',
	'E8 (55x78 mm ; 2.17x3.07 in)'                                                => 'E8',
	'E9 (39x55 mm ; 1.54x2.17 in)'                                                => 'E9',
	'E10 (27x39 mm ; 1.06x1.54 in)'                                               => 'E10',
	'E11 (19x27 mm ; 0.75x1.06 in)'                                               => 'E11',
	'E12 (13x19 mm ; 0.51x0.75 in)'                                               => 'E12',
	'SIS 014711 G Series (default: G4)'                                           => 'G4',
	'G0 (958x1354 mm ; 37.72x53.31 in)'                                           => 'G0',
	'G1 (677x958 mm ; 26.65x37.72 in)'                                            => 'G1',
	'G2 (479x677 mm ; 18.86x26.65 in)'                                            => 'G2',
	'G3 (338x479 mm ; 13.31x18.86 in)'                                            => 'G3',
	'G4 (239x338 mm ; 9.41x13.31 in)'                                             => 'G4',
	'G5 (169x239 mm ; 6.65x9.41 in)'                                              => 'G5',
	'G6 (119x169 mm ; 4.69x6.65 in)'                                              => 'G6',
	'G7 (84x119 mm ; 3.31x4.69 in)'                                               => 'G7',
	'G8 (59x84 mm ; 2.32x3.31 in)'                                                => 'G8',
	'G9 (42x59 mm ; 1.65x2.32 in)'                                                => 'G9',
	'G10 (29x42 mm ; 1.14x1.65 in)'                                               => 'G10',
	'G11 (21x29 mm ; 0.83x1.14 in)'                                               => 'G11',
	'G12 (14x21 mm ; 0.55x0.83 in)'                                               => 'G12',
	'ISO Press (default: RA4)'                                                    => 'RA4',
	'RA0 (860x1220 mm ; 33.86x48.03 in)'                                          => 'RA0',
	'RA1 (610x860 mm ; 24.02x33.86 in)'                                           => 'RA1',
	'RA2 (430x610 mm ; 16.93x24.02 in)'                                           => 'RA2',
	'RA3 (305x430 mm ; 12.01x16.93 in)'                                           => 'RA3',
	'RA4 (215x305 mm ; 8.46x12.01 in)'                                            => 'RA4',
	'SRA0 (900x1280 mm ; 35.43x50.39 in)'                                         => 'SRA0',
	'SRA1 (640x900 mm ; 25.20x35.43 in)'                                          => 'SRA1',
	'SRA2 (450x640 mm ; 17.72x25.20 in)'                                          => 'SRA2',
	'SRA3 (320x450 mm ; 12.60x17.72 in)'                                          => 'SRA3',
	'SRA4 (225x320 mm ; 8.86x12.60 in)'                                           => 'SRA4',
	'German DIN 476 (default: 4A0)'                                               => '4A0',
	'4A0 (1682x2378 mm ; 66.22x93.62 in)'                                         => '4A0',
	'2A0 (1189x1682 mm ; 46.81x66.22 in)'                                         => '2A0',
	'Variations on the ISO Standard (default: A4_EXTRA)'                          => 'A4_EXTRA',
	'A2_EXTRA (445x619 mm ; 17.52x24.37 in)'                                      => 'A2_EXTRA',
	'A3+ (329x483 mm ; 12.95x19.02 in)'                                           => 'A3+',
	'A3_EXTRA (322x445 mm ; 12.68x17.52 in)'                                      => 'A3_EXTRA',
	'A3_SUPER (305x508 mm ; 12.01x20.00 in)'                                      => 'A3_SUPER',
	'SUPER_A3 (305x487 mm ; 12.01x19.17 in)'                                      => 'SUPER_A3',
	'A4_EXTRA (235x322 mm ; 9.25x12.68 in)'                                       => 'A4_EXTRA',
	'A4_SUPER (229x322 mm ; 9.02x12.68 in)'                                       => 'A4_SUPER',
	'SUPER_A4 (227x356 mm ; 8.94x14.02 in)'                                       => 'SUPER_A4',
	'A4_LONG (210x348 mm ; 8.27x13.70 in)'                                        => 'A4_LONG',
	'F4 (210x330 mm ; 8.27x12.99 in)'                                             => 'F4',
	'SO_B5_EXTRA (202x276 mm ; 7.95x10.87 in)'                                    => 'SO_B5_EXTRA',
	'A5_EXTRA (173x235 mm ; 6.81x9.25 in)'                                        => 'A5_EXTRA',
	'ANSI Series (default: ANSI_A)'                                               => 'ANSI_A',
	'ANSI_E (864x1118 mm ; 34.00x44.00 in)'                                       => 'ANSI_E',
	'ANSI_D (559x864 mm ; 22.00x34.00 in)'                                        => 'ANSI_D',
	'ANSI_C (432x559 mm ; 17.00x22.00 in)'                                        => 'ANSI_C',
	'ANSI_B (279x432 mm ; 11.00x17.00 in)'                                        => 'ANSI_B',
	'ANSI_A (216x279 mm ; 8.50x11.00 in)'                                         => 'ANSI_A',
	'Traditional \'Loose\' North American Paper Sizes (default: LETTER)'          => 'LETTER',
	'LEDGER, USLEDGER (432x279 mm ; 17.00x11.00 in)'                              => 'LEDGER',
	'TABLOID, USTABLOID, BIBLE, ORGANIZERK (279x432 mm ; 11.00x17.00 in)'         => 'TABLOID',
	'LETTER, USLETTER, ORGANIZERM (216x279 mm ; 8.50x11.00 in)'                   => 'LETTER',
	'LEGAL, USLEGAL (216x356 mm ; 8.50x14.00 in)'                                 => 'LEGAL',
	'GLETTER, GOVERNMENTLETTER (203x267 mm ; 8.00x10.50 in)'                      => 'GLETTER',
	'JLEGAL, JUNIORLEGAL (203x127 mm ; 8.00x5.00 in)'                             => 'JLEGAL',
	'Other North American Paper Sizes (default: FOLIO)'                           => 'FOLIO',
	'QUADDEMY (889x1143 mm ; 35.00x45.00 in)'                                     => 'QUADDEMY',
	'SUPER_B (330x483 mm ; 13.00x19.00 in)'                                       => 'SUPER_B',
	'QUARTO (229x279 mm ; 9.00x11.00 in)'                                         => 'QUARTO',
	'FOLIO, GOVERNMENTLEGAL (216x330 mm ; 8.50x13.00 in)'                         => 'FOLIO',
	'EXECUTIVE, MONARCH (184x267 mm ; 7.25x10.50 in)'                             => 'EXECUTIVE',
	'MEMO, STATEMENT, ORGANIZERL (140x216 mm ; 5.50x8.50 in)'                     => 'MEMO',
	'FOOLSCAP (210x330 mm ; 8.27x13.00 in)'                                       => 'FOOLSCAP',
	'COMPACT (108x171 mm ; 4.25x6.75 in)'                                         => 'COMPACT',
	'ORGANIZERJ (70x127 mm ; 2.75x5.00 in)'                                       => 'ORGANIZERJ',
	'Canadian standard CAN 2-9.60M (default: P4)'                                 => 'P4',
	'P1 (560x860 mm ; 22.05x33.86 in)'                                            => 'P1',
	'P2 (430x560 mm ; 16.93x22.05 in)'                                            => 'P2',
	'P3 (280x430 mm ; 11.02x16.93 in)'                                            => 'P3',
	'P4 (215x280 mm ; 8.46x11.02 in)'                                             => 'P4',
	'P5 (140x215 mm ; 5.51x8.46 in)'                                              => 'P5',
	'P6 (107x140 mm ; 4.21x5.51 in)'                                              => 'P6',
	'North American Architectural Sizes (default: ARCH_A)'                        => 'ARCH_A',
	'ARCH_E (914x1219 mm ; 36.00x48.00 in)'                                       => 'ARCH_E',
	'ARCH_E1 (762x1067 mm ; 30.00x42.00 in)'                                      => 'ARCH_E1',
	'ARCH_D (610x914 mm ; 24.00x36.00 in)'                                        => 'ARCH_D',
	'ARCH_C, BROADSHEET (457x610 mm ; 18.00x24.00 in)'                            => 'ARCH_C',
	'ARCH_B (305x457 mm ; 12.00x18.00 in)'                                        => 'ARCH_B',
	'ARCH_A (229x305 mm ; 9.00x12.00 in)'                                         => 'ARCH_A',
	'Announcement Envelopes (default: ANNENV_A2)'                                 => 'ANNENV_A2',
	'ANNENV_A2 (111x146 mm ; 4.37x5.75 in)'                                       => 'ANNENV_A2',
	'ANNENV_A6 (121x165 mm ; 4.75x6.50 in)'                                       => 'ANNENV_A6',
	'ANNENV_A7 (133x184 mm ; 5.25x7.25 in)'                                       => 'ANNENV_A7',
	'ANNENV_A8 (140x206 mm ; 5.50x8.12 in)'                                       => 'ANNENV_A8',
	'ANNENV_A10 (159x244 mm ; 6.25x9.62 in)'                                      => 'ANNENV_A10',
	'ANNENV_SLIM (98x225 mm ; 3.87x8.87 in)'                                      => 'ANNENV_SLIM',
	'Commercial Envelopes (default: COMMENV_N10)'                                 => 'COMMENV_N10',
	'COMMENV_N6_1/4 (89x152 mm ; 3.50x6.00 in)'                                   => 'COMMENV_N6_1/4',
	'COMMENV_N6_3/4 (92x165 mm ; 3.62x6.50 in)'                                   => 'COMMENV_N6_3/4',
	'COMMENV_N8 (98x191 mm ; 3.87x7.50 in)'                                       => 'COMMENV_N8',
	'COMMENV_N9 (98x225 mm ; 3.87x8.87 in)'                                       => 'COMMENV_N9',
	'COMMENV_N10 (105x241 mm ; 4.12x9.50 in)'                                     => 'COMMENV_N10',
	'COMMENV_N11 (114x263 mm ; 4.50x10.37 in)'                                    => 'COMMENV_N11',
	'COMMENV_N12 (121x279 mm ; 4.75x11.00 in)'                                    => 'COMMENV_N12',
	'COMMENV_N14 (127x292 mm ; 5.00x11.50 in)'                                    => 'COMMENV_N14',
	'Catalogue Envelopes (default: CATENV_N10_1/2)'                               => 'CATENV_N10_1/2',
	'CATENV_N1 (152x229 mm ; 6.00x9.00 in)'                                       => 'CATENV_N1',
	'CATENV_N1_3/4 (165x241 mm ; 6.50x9.50 in)'                                   => 'CATENV_N1_3/4',
	'CATENV_N2 (165x254 mm ; 6.50x10.00 in)'                                      => 'CATENV_N2',
	'CATENV_N3 (178x254 mm ; 7.00x10.00 in)'                                      => 'CATENV_N3',
	'CATENV_N6 (191x267 mm ; 7.50x10.50 in)'                                      => 'CATENV_N6',
	'CATENV_N7 (203x279 mm ; 8.00x11.00 in)'                                      => 'CATENV_N7',
	'CATENV_N8 (210x286 mm ; 8.25x11.25 in)'                                      => 'CATENV_N8',
	'CATENV_N9_1/2 (216x267 mm ; 8.50x10.50 in)'                                  => 'CATENV_N9_1/2',
	'CATENV_N9_3/4 (222x286 mm ; 8.75x11.25 in)'                                  => 'CATENV_N9_3/4',
	'CATENV_N10_1/2 (229x305 mm ; 9.00x12.00 in)'                                 => 'CATENV_N10_1/2',
	'CATENV_N12_1/2 (241x318 mm ; 9.50x12.50 in)'                                 => 'CATENV_N12_1/2',
	'CATENV_N13_1/2 (254x330 mm ; 10.00x13.00 in)'                                => 'CATENV_N13_1/2',
	'CATENV_N14_1/4 (286x311 mm ; 11.25x12.25 in)'                                => 'CATENV_N14_1/4',
	'CATENV_N14_1/2 (292x368 mm ; 11.50x14.50 in)'                                => 'CATENV_N14_1/2',
	'Japanese (JIS P 0138-61) Standard B-Series (default: JIS_B5)'                => 'JIS_B5',
	'JIS_B0 (1030x1456 mm ; 40.55x57.32 in)'                                      => 'JIS_B0',
	'JIS_B1 (728x1030 mm ; 28.66x40.55 in)'                                       => 'JIS_B1',
	'JIS_B2 (515x728 mm ; 20.28x28.66 in)'                                        => 'JIS_B2',
	'JIS_B3 (364x515 mm ; 14.33x20.28 in)'                                        => 'JIS_B3',
	'JIS_B4 (257x364 mm ; 10.12x14.33 in)'                                        => 'JIS_B4',
	'JIS_B5 (182x257 mm ; 7.17x10.12 in)'                                         => 'JIS_B5',
	'JIS_B6 (128x182 mm ; 5.04x7.17 in)'                                          => 'JIS_B6',
	'JIS_B7 (91x128 mm ; 3.58x5.04 in)'                                           => 'JIS_B7',
	'JIS_B8 (64x91 mm ; 2.52x3.58 in)'                                            => 'JIS_B8',
	'JIS_B9 (45x64 mm ; 1.77x2.52 in)'                                            => 'JIS_B9',
	'JIS_B10 (32x45 mm ; 1.26x1.77 in)'                                           => 'JIS_B10',
	'JIS_B11 (22x32 mm ; 0.87x1.26 in)'                                           => 'JIS_B11',
	'JIS_B12 (16x22 mm ; 0.63x0.87 in)'                                           => 'JIS_B12',
	'PA Series (default: PA4)'                                                    => 'PA4',
	'PA0 (840x1120 mm ; 33.07x44.09 in)'                                          => 'PA0',
	'PA1 (560x840 mm ; 22.05x33.07 in)'                                           => 'PA1',
	'PA2 (420x560 mm ; 16.54x22.05 in)'                                           => 'PA2',
	'PA3 (280x420 mm ; 11.02x16.54 in)'                                           => 'PA3',
	'PA4 (210x280 mm ; 8.27x11.02 in)'                                            => 'PA4',
	'PA5 (140x210 mm ; 5.51x8.27 in)'                                             => 'PA5',
	'PA6 (105x140 mm ; 4.13x5.51 in)'                                             => 'PA6',
	'PA7 (70x105 mm ; 2.76x4.13 in)'                                              => 'PA7',
	'PA8 (52x70 mm ; 2.05x2.76 in)'                                               => 'PA8',
	'PA9 (35x52 mm ; 1.38x2.05 in)'                                               => 'PA9',
	'PA10 (26x35 mm ; 1.02x1.38 in)'                                              => 'PA10',
	'Standard Photographic Print Sizes (default: 8R, 6P)'                         => '8R',
	'PASSPORT_PHOTO (35x45 mm ; 1.38x1.77 in)'                                    => 'PASSPORT_PHOTO',
	'E (82x120 mm ; 3.25x4.72 in)'                                                => 'E',
	'3R, L (89x127 mm ; 3.50x5.00 in)'                                            => '3R',
	'4R, KG (102x152 mm ; 4.02x5.98 in)'                                          => '4R',
	'4D (120x152 mm ; 4.72x5.98 in)'                                              => '4D',
	'5R, 2L (127x178 mm ; 5.00x7.01 in)'                                          => '5R',
	'6R, 8P (152x203 mm ; 5.98x7.99 in)'                                          => '6R',
	'8R, 6P (203x254 mm ; 7.99x10.00 in)'                                         => '8R',
	'S8R, 6PW (203x305 mm ; 7.99x12.01 in)'                                       => 'S8R',
	'10R, 4P (254x305 mm ; 10.00x12.01 in)'                                       => '10R',
	'S10R, 4PW (254x381 mm ; 10.00x15.00 in)'                                     => 'S10R',
	'11R (279x356 mm ; 10.98x14.02 in)'                                           => '11R',
	'S11R (279x432 mm ; 10.98x17.01 in)'                                          => 'S11R',
	'12R (305x381 mm ; 12.01x15.00 in)'                                           => '12R',
	'S12R (305x456 mm ; 12.01x17.95 in)'                                          => 'S12R',
	'Common Newspaper Sizes (default: NEWSPAPER_TABLOID)'                         => 'NEWSPAPER_TABLOID',
	'NEWSPAPER_BROADSHEET (750x600 mm ; 29.53x23.62 in)'                          => 'NEWSPAPER_BROADSHEET',
	'NEWSPAPER_BERLINER (470x315 mm ; 18.50x12.40 in)'                            => 'NEWSPAPER_BERLINER',
	'NEWSPAPER_COMPACT, NEWSPAPER_TABLOID (430x280 mm ; 16.93x11.02 in)'          => 'NEWSPAPER_TABLOID',
	'Business Cards (default: BUSINESS_CARD)'                                     => 'BUSINESS_CARD',
	'CREDIT_CARD, BUSINESS_CARD, BUSINESS_CARD_ISO7810 (54x86 mm ; 2.13x3.37 in)' => 'BUSINESS_CARD',
	'BUSINESS_CARD_ISO216 (52x74 mm ; 2.05x2.91 in)'                              => 'BUSINESS_CARD_ISO216',
	'BUSINESS_CARD_IT, UK, FR, DE, ES (55x85 mm ; 2.17x3.35 in)'                  => 'BUSINESS_CARD_IT',
	'BUSINESS_CARD_US, CA (51x89 mm ; 2.01x3.50 in)'                              => 'BUSINESS_CARD_US',
	'BUSINESS_CARD_JP (55x91 mm ; 2.17x3.58 in)'                                  => 'BUSINESS_CARD_JP',
	'BUSINESS_CARD_HK (54x90 mm ; 2.13x3.54 in)'                                  => 'BUSINESS_CARD_HK',
	'BUSINESS_CARD_AU, DK, SE (55x90 mm ; 2.17x3.54 in)'                          => 'BUSINESS_CARD_AU',
	'BUSINESS_CARD_RU, CZ, FI, HU, IL (50x90 mm ; 1.97x3.54 in)'                  => 'BUSINESS_CARD_RU',
	'Billboards (default: 4SHEET)'                                                => '4SHEET',
	'4SHEET (1016x1524 mm ; 40.00x60.00 in)'                                      => '4SHEET',
	'6SHEET (1200x1800 mm ; 47.24x70.87 in)'                                      => '6SHEET',
	'12SHEET (3048x1524 mm ; 120.00x60.00 in)'                                    => '12SHEET',
	'16SHEET (2032x3048 mm ; 80.00x120.00 in)'                                    => '16SHEET',
	'32SHEET (4064x3048 mm ; 160.00x120.00 in)'                                   => '32SHEET',
	'48SHEET (6096x3048 mm ; 240.00x120.00 in)'                                   => '48SHEET',
	'64SHEET (8128x3048 mm ; 320.00x120.00 in)'                                   => '64SHEET',
	'96SHEET (12192x3048 mm ; 480.00x120.00 in)'                                  => '96SHEET',
	'Old Imperial English (default: EN_ATLAS)'                                    => 'EN_ATLAS',
	'EN_EMPEROR (1219x1829 mm ; 48.00x72.00 in)'                                  => 'EN_EMPEROR',
	'EN_ANTIQUARIAN (787x1346 mm ; 31.00x53.00 in)'                               => 'EN_ANTIQUARIAN',
	'EN_GRAND_EAGLE (730x1067 mm ; 28.75x42.00 in)'                               => 'EN_GRAND_EAGLE',
	'EN_DOUBLE_ELEPHANT (679x1016 mm ; 26.75x40.00 in)'                           => 'EN_DOUBLE_ELEPHANT',
	'EN_ATLAS (660x864 mm ; 26.00x34.00 in)'                                      => 'EN_ATLAS',
	'EN_COLOMBIER (597x876 mm ; 23.50x34.50 in)'                                  => 'EN_COLOMBIER',
	'EN_ELEPHANT (584x711 mm ; 23.00x28.00 in)'                                   => 'EN_ELEPHANT',
	'EN_DOUBLE_DEMY (572x902 mm ; 22.50x35.50 in)'                                => 'EN_DOUBLE_DEMY',
	'EN_IMPERIAL (559x762 mm ; 22.00x30.00 in)'                                   => 'EN_IMPERIAL',
	'EN_PRINCESS (546x711 mm ; 21.50x28.00 in)'                                   => 'EN_PRINCESS',
	'EN_CARTRIDGE (533x660 mm ; 21.00x26.00 in)'                                  => 'EN_CARTRIDGE',
	'EN_DOUBLE_LARGE_POST (533x838 mm ; 21.00x33.00 in)'                          => 'EN_DOUBLE_LARGE_POST',
	'EN_ROYAL (508x635 mm ; 20.00x25.00 in)'                                      => 'EN_ROYAL',
	'EN_SHEET, EN_HALF_POST (495x597 mm ; 19.50x23.50 in)'                        => 'EN_SHEET, EN_HALF_POST',
	'EN_SUPER_ROYAL (483x686 mm ; 19.00x27.00 in)'                                => 'EN_SUPER_ROYAL',
	'EN_DOUBLE_POST (483x775 mm ; 19.00x30.50 in)'                                => 'EN_DOUBLE_POST',
	'EN_MEDIUM (445x584 mm ; 17.50x23.00 in)'                                     => 'EN_MEDIUM',
	'EN_DEMY (445x572 mm ; 17.50x22.50 in)'                                       => 'EN_DEMY',
	'EN_LARGE_POST (419x533 mm ; 16.50x21.00 in)'                                 => 'EN_LARGE_POST',
	'EN_COPY_DRAUGHT (406x508 mm ; 16.00x20.00 in)'                               => 'EN_COPY_DRAUGHT',
	'EN_POST (394x489 mm ; 15.50x19.25 in)'                                       => 'EN_POST',
	'EN_CROWN (381x508 mm ; 15.00x20.00 in)'                                      => 'EN_CROWN',
	'EN_PINCHED_POST (375x470 mm ; 14.75x18.50 in)'                               => 'EN_PINCHED_POST',
	'EN_BRIEF (343x406 mm ; 13.50x16.00 in)'                                      => 'EN_BRIEF',
	'EN_FOOLSCAP (343x432 mm ; 13.50x17.00 in)'                                   => 'EN_FOOLSCAP',
	'EN_SMALL_FOOLSCAP (337x419 mm ; 13.25x16.50 in)'                             => 'EN_SMALL_FOOLSCAP',
	'EN_POTT (318x381 mm ; 12.50x15.00 in)'                                       => 'EN_POTT',
	'Old Imperial Belgian (default: BE_ELEPHANT)'                                 => 'BE_ELEPHANT',
	'BE_GRAND_AIGLE (700x1040 mm ; 27.56x40.94 in)'                               => 'BE_GRAND_AIGLE',
	'BE_COLOMBIER (620x850 mm ; 24.41x33.46 in)'                                  => 'BE_COLOMBIER',
	'BE_DOUBLE_CARRE (620x920 mm ; 24.41x36.22 in)'                               => 'BE_DOUBLE_CARRE',
	'BE_ELEPHANT (616x770 mm ; 24.25x30.31 in)'                                   => 'BE_ELEPHANT',
	'BE_PETIT_AIGLE (600x840 mm ; 23.62x33.07 in)'                                => 'BE_PETIT_AIGLE',
	'BE_GRAND_JESUS (550x730 mm ; 21.65x28.74 in)'                                => 'BE_GRAND_JESUS',
	'BE_JESUS (540x730 mm ; 21.26x28.74 in)'                                      => 'BE_JESUS',
	'BE_RAISIN (500x650 mm ; 19.69x25.59 in)'                                     => 'BE_RAISIN',
	'BE_GRAND_MEDIAN (460x605 mm ; 18.11x23.82 in)'                               => 'BE_GRAND_MEDIAN',
	'BE_DOUBLE_POSTE (435x565 mm ; 17.13x22.24 in)'                               => 'BE_DOUBLE_POSTE',
	'BE_COQUILLE (430x560 mm ; 16.93x22.05 in)'                                   => 'BE_COQUILLE',
	'BE_PETIT_MEDIAN (415x530 mm ; 16.34x20.87 in)'                               => 'BE_PETIT_MEDIAN',
	'BE_RUCHE (360x460 mm ; 14.17x18.11 in)'                                      => 'BE_RUCHE',
	'BE_PROPATRIA (345x430 mm ; 13.58x16.93 in)'                                  => 'BE_PROPATRIA',
	'BE_LYS (317x397 mm ; 12.48x15.63 in)'                                        => 'BE_LYS',
	'BE_POT (307x384 mm ; 12.09x15.12 in)'                                        => 'BE_POT',
	'BE_ROSETTE (270x347 mm ; 10.63x13.66 in)'                                    => 'BE_ROSETTE',
	'Old Imperial French (default: FR_PETIT_AIGLE)'                               => 'FR_PETIT_AIGLE',
	'FR_UNIVERS (1000x1300 mm ; 39.37x51.18 in)'                                  => 'FR_UNIVERS',
	'FR_DOUBLE_COLOMBIER (900x1260 mm ; 35.43x49.61 in)'                          => 'FR_DOUBLE_COLOMBIER',
	'FR_GRANDE_MONDE (900x1260 mm ; 35.43x49.61 in)'                              => 'FR_GRANDE_MONDE',
	'FR_DOUBLE_SOLEIL (800x1200 mm ; 31.50x47.24 in)'                             => 'FR_DOUBLE_SOLEIL',
	'FR_DOUBLE_JESUS (760x1120 mm ; 29.92x44.09 in)'                              => 'FR_DOUBLE_JESUS',
	'FR_GRAND_AIGLE (750x1060 mm ; 29.53x41.73 in)'                               => 'FR_GRAND_AIGLE',
	'FR_PETIT_AIGLE (700x940 mm ; 27.56x37.01 in)'                                => 'FR_PETIT_AIGLE',
	'FR_DOUBLE_RAISIN (650x1000 mm ; 25.59x39.37 in)'                             => 'FR_DOUBLE_RAISIN',
	'FR_JOURNAL (650x940 mm ; 25.59x37.01 in)'                                    => 'FR_JOURNAL',
	'FR_COLOMBIER_AFFICHE (630x900 mm ; 24.80x35.43 in)'                          => 'FR_COLOMBIER_AFFICHE',
	'FR_DOUBLE_CAVALIER (620x920 mm ; 24.41x36.22 in)'                            => 'FR_DOUBLE_CAVALIER',
	'FR_CLOCHE (600x800 mm ; 23.62x31.50 in)'                                     => 'FR_CLOCHE',
	'FR_SOLEIL (600x800 mm ; 23.62x31.50 in)'                                     => 'FR_SOLEIL',
	'FR_DOUBLE_CARRE (560x900 mm ; 22.05x35.43 in)'                               => 'FR_DOUBLE_CARRE',
	'FR_DOUBLE_COQUILLE (560x880 mm ; 22.05x34.65 in)'                            => 'FR_DOUBLE_COQUILLE',
	'FR_JESUS (560x760 mm ; 22.05x29.92 in)'                                      => 'FR_JESUS',
	'FR_RAISIN (500x650 mm ; 19.69x25.59 in)'                                     => 'FR_RAISIN',
	'FR_CAVALIER (460x620 mm ; 18.11x24.41 in)'                                   => 'FR_CAVALIER',
	'FR_DOUBLE_COURONNE (460x720 mm ; 18.11x28.35 in)'                            => 'FR_DOUBLE_COURONNE',
	'FR_CARRE (450x560 mm ; 17.72x22.05 in)'                                      => 'FR_CARRE',
	'FR_COQUILLE (440x560 mm ; 17.32x22.05 in)'                                   => 'FR_COQUILLE',
	'FR_DOUBLE_TELLIERE (440x680 mm ; 17.32x26.77 in)'                            => 'FR_DOUBLE_TELLIERE',
	'FR_DOUBLE_CLOCHE (400x600 mm ; 15.75x23.62 in)'                              => 'FR_DOUBLE_CLOCHE',
	'FR_DOUBLE_POT (400x620 mm ; 15.75x24.41 in)'                                 => 'FR_DOUBLE_POT',
	'FR_ECU (400x520 mm ; 15.75x20.47 in)'                                        => 'FR_ECU',
	'FR_COURONNE (360x460 mm ; 14.17x18.11 in)'                                   => 'FR_COURONNE',
	'FR_TELLIERE (340x440 mm ; 13.39x17.32 in)'                                   => 'FR_TELLIERE',
	'FR_POT (310x400 mm ; 12.20x15.75 in)'                                        => 'FR_POT',
); ?>
        <tr valign="top">
            <th scope="row">Page Size</th>
            <td>
                <?php
echo '<select name="wpptopdfenh[pageSize]">';
foreach ( $page_size as $key => $value ) {
	if ( $wpptopdfenhopts['pageSize'] ) {
		$checked = ( $wpptopdfenhopts['pageSize'] == $value ) ? 'selected="selected"' : '';
	}
	echo '<option value="' . $value . '" ' . $checked . ' >' . $key . '</option>';
}
echo '</select>';
?>
                    <p>Select the desired page size (default is LETTER).</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Orientation</th>
            <td>
                <input name="wpptopdfenh[orientation]"
                       value="P" <?php if ( 'P' == ( $wpptopdfenhopts['orientation'] )  || ! isset( $wpptopdfenhopts['orientation'] ) ) echo 'checked="checked"'; ?>
                       type="radio"/> Portrait&nbsp;&nbsp;&nbsp;
                <input name="wpptopdfenh[orientation]"
                       value="L" <?php if ( 'L' == ( $wpptopdfenhopts['orientation'] ) ) echo 'checked="checked"'; ?>
                       type="radio"/> Landscape
                <br/>
                <p>Select the desired page orientation (default is Portrait).</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Unit of Measurement</th>
            <td>
                <?php
$unit = array( 'Point' => 'pt', 'Millimeter' => 'mm', 'Centimeter' => 'cm', 'Inch' => 'in' );
echo '<select name="wpptopdfenh[unitMeasure]">';
foreach ( $unit as $key => $value ) {
	if ( $wpptopdfenhopts['unitMeasure'] == '' ) {
		'selected="Millimeter"'; 
		$checked = ( $wpptopdfenhopts['unitMeasure'] == $value ) ? 'selected="selected"' : '';
		echo '<option value="' . $value . '" ' . $checked . ' >' . $key . '</option>';
	} else {
		if ( $wpptopdfenhopts['unitMeasure'] ) {
			$checked = ( $wpptopdfenhopts['unitMeasure'] == $value ) ? 'selected="selected"' : '';
		}
		echo '<option value="' . $value . '" ' . $checked . ' >' . $key . '</option>';
	}
}
echo '</select>';
?>
                <p>Select the desired unit of measurement (default is mm).</p>
            </td>
        </tr>
    </table>
</div>
<p class="submit">
    <input type="submit" class="button-primary" name="wpptopdfenh[submit]" value="<?php _e( 'Save Changes' ) ?>"/>
    <input type="submit" class="button-secondary" name="wpptopdfenh[submit]"
           value="<?php _e( 'Save and Reset PDF Cache' ) ?>"/>
</p>
</form>
<h4>If you find this plugin useful, <a target="_blank" title="Will open in new window" href="http://wordpress.org/extend/plugins/wp-post-to-pdf-enhanced/">please review and rate it</a>.</h4>
</div>
