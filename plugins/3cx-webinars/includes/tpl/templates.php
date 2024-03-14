<?php

define("TCXWM_TEMPLATES", array(
  'dialog'=>'<div class="tcxdialog" id="tcxdialog" title="">
		<p class="tcxwebinardialogstartdate"></p>
		<p class="tcxwebinardialogdescr"></p>
		<form id="tcxform">
			<fieldset>
				<p id="tcxtips"></p>
				<input type="text" name="name" id="tcxname" value="" placeholder="'.esc_html(__('Name', '3cx-webinar')).'" class="text ui-widget-content ui-corner-all">
				<input type="text" name="email" id="tcxemail" value="" placeholder="'.esc_html(__('Email', '3cx-webinar')).'" class="text ui-widget-content ui-corner-all">
				<input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
			</fieldset>
		</form>
	</div>',

  'messagebox'=>'<div class="tcxdialog" id="tcxdialogmessage" title="'.esc_html(__('Webinar Subscription', '3cx-webinar')).'">
		<p>'.esc_html(__('You\'ve successfully been registered for this Webinar.', '3cx-webinar')).'</p>
	</div>'
));

?>