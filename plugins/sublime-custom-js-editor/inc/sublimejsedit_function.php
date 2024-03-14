<?php

defined('ABSPATH') or die("Restricted access!");


function sublimejsedit_render_submenu_page() {

	// Variables
    $options = get_option( 'sublimejsedit_settings' );
    $content = isset( $options['sublimejsedit-content'] ) && ! empty( $options['sublimejsedit-content'] ) ? $options['sublimejsedit-content'] : '// Write your javascript';

	// Settings update message
	if ( isset( $_GET['settings-updated'] ) ) :
		?>
			<div id="message" class="updated notice is-dismissible">
				<p>
					<?php _e( 'Your Custom JavaScript was sucessfully updated.', 'sublimejsedit' ); ?>
				</p>
			</div>
		<?php
	endif;

	// Page
	?>
    <div class="wrap">
        <h2 class="title_and_hint">
            <?php _e( 'Sublime Custom JavaScript Editor', 'sublimejsedit' ); ?>
            <br/>
            <span>
                <?php _e( 'Press <code>Ctrl+Space</code> to hint/autocompletion.', 'sublimejsedit' ); ?>
            <span/>
        </h2>
		<form name="sublimejsedit-form" action="options.php" method="post" enctype="multipart/form-data">
			<?php settings_fields( 'sublimejsedit_settings_group' ); ?>

			<!-- Editor form -->
			<div id="container" class="edditor_container">
				<textarea name="sublimejsedit_settings[sublimejsedit-content]" id="code" ><?php echo $content; ?></textarea>
				<?php submit_button( __( 'Save Custom JS', 'sublimejsedit' ), 'primary', 'submit', true ); ?>
            </div>
			<!-- End Editor form -->
			<!-- script -->
			<script>
			  var value = "// The bindings defined specifically in the Sublime Text mode\nvar bindings = {\n";
			  var map = CodeMirror.keyMap.sublime;
			  for (var key in map) {
			    var val = map[key];
			    if (key != "fallthrough" && val != "..." && (!/find/.test(val) || /findUnder/.test(val)))
			      value += "  \"" + key + "\": \"" + val + "\",\n";
			  }
			  value += "}\n\n// The implementation of joinLines\n";
			  value += CodeMirror.commands.joinLines.toString().replace(/^function\s*\(/, "function joinLines(").replace(/\n  /g, "\n") + "\n";
			  
			  var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
			    lineNumbers: true,
			    viewportMargin: Infinity,
			    mode: "javascript",
			    keyMap: "sublime",
			    autoCloseBrackets: true,
			    matchBrackets: true,
			    styleActiveLine: true,
			    showCursorWhenSelecting: true,
			    theme: "monokai",
			    tabSize: 2,
			    extraKeys: {"Ctrl-Space": "autocomplete"},
			    closeCharacters: /[\s()\[\]{};:>,]/,
			    gutters: ["CodeMirror-lint-markers"],
			    lint: true,

			  });
			</script>

		</form>
	   </div>
	<?php
}