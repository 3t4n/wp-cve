<?php
$infusionsoft_fields = array(
    'company' => 'Company',
    'email' => 'Email Address',
    'first-name' => 'First Name',
    'last-name' => 'Last Name',
    'notes' => 'Person Notes',
    'phone' => 'Phone Number',
    'website' => 'Website',
);

function wpcf7_tag_generator_infusionsoft_old() { 
    global $infusionsoft_fields;
?>
    <div id="wpcf7-tg-pane-infusionsoft" class="hidden">
    	<form action="">
    		<table>
    			<tbody>
    				<tr>
    					<td style="display:none;">
    						<input type="checkbox" name="required" checked disabled>&nbsp;Required field (for InfusionSoft)
    					</td>
    				</tr>
    				<tr>
    					<td>
    						Type<br>
    						<select id="infusionsoft-tag" name="name">
                                <option value="0">--</option>
                                <?php 
                                    foreach ($infusionsoft_fields as $value => $name) {
                                        echo '<option value="infusionsoft-' . $value . '">' . $name . '</option>';
                                    }
                                ?>
    						</select>
    					</td>
    					<td></td>
    				</tr>
    			</tbody>
    		</table>
    		<table>
    		<tbody>
    			<tr>
    				<td>
    					<code>id</code> (optional)<br>
    					<input type="text" name="id" class="idvalue oneline option">
    				</td>
    				<td>
    					<code>class</code> (optional)<br>
    					<input type="text" name="class" class="classvalue oneline option">
    				</td>
    			</tr>
    			<tr>
    				<td>
    					<code>size</code> (optional)<br>
    					<input type="number" name="size" class="numeric oneline option" min="1">
    				</td>
    				<td>
    					<code>maxlength</code> (optional)<br>
    					<input type="number" name="maxlength" class="numeric oneline option" min="1">
    				</td>
    			</tr>
    			<tr>
    				<td>
    					Placeholder text (optional)<br><input type="text" name="values" class="oneline">
    				</td>
    				<td>
    					<br><input type="checkbox" name="placeholder" class="option">&nbsp;Use placeholder text?</td>
    			</tr>
    		</tbody></table>

    		<div class="tg-tag">
    			Copy this code and paste it into the form to the left.<br>
    			<input id="infusionsoft-tag-type" type="text" name="text" class="tag" readonly="readonly" onfocus="this.select()">
    		</div>
    		<div class="tg-mail-tag">
    			And, put this code into the Mail fields below.<br>
    			<input type="text" class="mail-tag" readonly="readonly" onfocus="this.select()">
    		</div>

    		<table>
    			<tr>
    				<td>
    					Note:<br>
    					<span style="font-size:1em; color: #666; font-style: italic; display: block;">
    						To ensure that the form can successfully send data to InfusionSoft, you must add fields for <code>Email Address</code> and at least one of the following: <code>First Name</code> or <code>Last Name</code>.
    						InfusionSoft requires an email address and first OR last name (at a minimum) in order to add a contact to the database.
    					</span>
    					<ul>
    						<li>
    							This plugin also supports email and phone HTML5 input types. If you wish to use HTML5, these can be used immediately, by copying and pasting any of the following into the form to the left:
    						</li>
    						<li>
    							Email: <code>[email* infusionsoft-email]</code>
    						</li>
    						<li>
    							Phone: <code>[tel infusionsoft-phone]</code>
    						</li>
    					</ul>
    				</td>
    			</tr>
    		</table>
    	</form>
    </div>
<?php }

function wpcf7_tag_generator_infusionsoft( $contact_form, $args = '' ) {
    global $infusionsoft_fields;
    $args = wp_parse_args( $args, array() );

    $description = "Generate a form-tag for various InfusionSoft fields. For more details, see %s.";
    $desc_link = wpcf7_link( 'https://wordpress.org/plugins/contact-form-7-infusionsoft-add-on/faq/', 'the add-on FAQs' );

?>
<div class="control-box">
<fieldset>
<legend><?php echo sprintf( esc_html( $description ), $desc_link ); ?></legend>

<table class="form-table">
<tbody>
    <tr>
        <th scope="row"><label for="infusionsoft-field-name">Field Type</label></th>
        <td>
            <select name="infusionsoft-field-name" class="tg-name oneline" id="infusionsoft-field-name">
                <option value="0">--</option>
                <?php 
                    foreach ($infusionsoft_fields as $value => $name) {
                        echo '<option value="' . $value . '">' . $name . '</option>';
                    }
                ?>
            </select>
        </td>
    </tr>

    <tr>
        <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-values' ); ?>"><?php echo esc_html( __( 'Default value', 'contact-form-7' ) ); ?></label></th>
        <td><input type="text" name="values" class="oneline" id="<?php echo esc_attr( $args['content'] . '-values' ); ?>" /><br />
            <label><input type="checkbox" name="placeholder" class="option" /> <?php echo esc_html( __( 'Use this text as the placeholder of the field', 'contact-form-7' ) ); ?></label></td>
        </tr>

        <tr>
            <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'Id attribute', 'contact-form-7' ) ); ?></label></th>
            <td><input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-id' ); ?>" /></td>
        </tr>

        <tr>
            <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', 'contact-form-7' ) ); ?></label></th>
            <td><input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" /></td>
        </tr>

        <tr>
            <th scope="row"><?php echo esc_html( __( 'Field type', 'contact-form-7' ) ); ?></th>
            <td>
                <fieldset>
                    <legend class="screen-reader-text"><?php echo esc_html( __( 'Field type', 'contact-form-7' ) ); ?></legend>
                    <label><input type="checkbox" name="required" /> <?php echo esc_html( __( 'Required field', 'contact-form-7' ) ); ?></label>
                </fieldset>
            </td>
        </tr>
</tbody>
</table>
</fieldset>
</div>

<div class="insert-box" style="height: 100px; bottom: 50px; overflow-x: hidden;">
    <input type="text" name="text" class="tag code" readonly="readonly" onfocus="this.select()" />

    <div class="submitbox">
    <input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
    </div>

    <br class="clear" />

    <p class="description mail-tag">
        <label for="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>">
            <?php echo sprintf( esc_html( __( "To use the value input through this field in a mail field, you need to insert the corresponding mail-tag (%s) into the field on the Mail tab.", 'contact-form-7' ) ), '<strong><span class="mail-tag"></span></strong>' ); ?>
            <input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>" />
        </label>
    </p>
</div>
<?php
}
