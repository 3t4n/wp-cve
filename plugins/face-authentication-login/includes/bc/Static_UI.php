<?php

/**
 * This class print UI elements that aren't dependent on any particular form (without creating a form instance)
 */
namespace DataPeen\FaceAuth;

class Static_UI
{
	/**
	 * Echos an label element
	 *
	 * @param $field_id
	 * @param string $text
	 *
	 * @return string|void
	 */
	public static function label($field_id, $text, $echo = true, $hidden = false)
	{

		$hidden_string = $hidden ? 'style="display: none;"' : '';
		$output = sprintf('<label for="%1$s" %3$s class="bc-doc-label">%2$s</label>', $field_id, $text, $hidden_string);
		if ($echo)
			echo $output;
		else
			return $output;
    }


	/**
	 * Output a line
	 *
	 * @param bool $echo
	 *
	 * @return void|string
	 */
	public static function line($echo = true)
	{
		if ($echo)
			echo '<hr />';
		else
			return '<hr />';
	}


	/**
	 * @param $content
	 * @param bool $echo
	 */
	public static function explain($content, $echo = true)
    {
        $html = sprintf('<p></p>');
    }

	/**
	 * @param bool $condition the condition to check
	 * @param array $content_if_true
	 * @param array $content_if_false
	 * @param bool $echo
	 *
	 * @return mixed|void
	 */
	public static function conditional($condition, $content_if_true, $content_if_false, $echo = true)
    {
        $true_content = implode("", $content_if_true);
        $false_content = implode("", $content_if_false);


        if ($condition) {
            if ($echo)
                echo $true_content;
            else
	            return $true_content;
        } else
        {
            if ($echo)
                echo $false_content;
            else
                return $false_content;
        }
    }




	/**
	 * Output a button with CSS ID. This button is used in forms to perform individual ajax request without saving the form
	 *
	 * @param $css_id
	 * @param $text
	 * @param bool $echo
	 *
	 * @return void|string
	 */
	public static function button($css_id, $text, $echo = true)
	{
		$html = sprintf('<button class="bc-uk-button bc-uk-button-primary no-submit-button" id="%1$s">%2$s</button>', $css_id, $text);

		if ($echo)
			echo $html;
		else
			return $html;
	}


	/**
	 * Open a form tag
	 *
	 *
	 */
	public static function open_form($echo = true)
	{
		$html = '<form class="bc-single-form">';
		if ($echo)
			echo $html;
		else
			return $html;
	}




	/**
	 * Close an open form
	 */
	public static function close_form($echo = true)
	{
		if ($echo)
			echo '</form>';
		else
			return '</form>';
	}
	/**
	 * Print opening root div (bc-root)
	 *
	 */
	public static function open_root($echo = true)
	{

		$html = '<div class="bc-root bc-doc"> <!-- opening bc-root -->';
		if ($echo)
			echo $html;
		else
			return $html;
	}

	/**
	 * Print closing root div (bc-root)
	 *
	 */
	public static function close_root($echo = true)
	{
		$html = '</div> <!-- closing bc-root -->';
		if ($echo)
			echo $html;
		else
			return $html;
	}


	/**
	 * Create a row
	 *
	 * @param $content array of content, each element of this array represents a column
	 * array(
	 *       'content' => 'array of html content of the col',
	 *       'width' => 'width of the col'
	 *      )
	 *
	 * @param $echo boolean, whether to echo or not
	 *
	 * @return void|string
	 */
	public static function row($content, $echo = true)
	{
		$html = '';
		foreach ($content as $col)
		{
			//if a width is specified, it should be in this format 1-2, 1-3, 1-4... or 1-2@m...
			$width = isset($col['width']) ? $col['width'] : 'auto@m';

			$width = 'bc-uk-width-'. $width;
			$html .= sprintf('<div class="%1$s">%2$s</div>', $width, implode("", $col['content']));
		}

		$html = sprintf('<div bc-uk-grid>%1$s</div>', $html);

		if ($echo)
			echo $html;
		else
			return $html;
	}
	/**
	 * Create tabs
	 *
	 * @param array $content array(array('title' => 'title', 'content' => array of string, 'is_active" => false, 'is_disabled' => false))
	 * @param bool $echo echo or not
	 *
	 * @return string|void
	 */
	public static function tabs($content, $echo = false)
	{
		$tab_head = '';
		$tab_body = '';

		foreach ($content as $item)
		{
			$active_class = isset($item['is_active']) && $item['is_active']? 'bc-uk-active' : '';
			$disabled_class = isset($item['is_disabled']) && $item['is_disabled']? 'bc-uk-disabled' : '';

			/**
			 * generate a random tab ID for each tab. Along with bc-single-tab (below), this will be used to select
			 * the correct tab after form saved and redirected
			 *
			 * @var $random_id
			 */
			$random_id = 'bc-tab-' . rand(1,20000);
			//add the class bc-single-tab here. It will be used to redirect and select the tab later when form is saved (function save_form in Options_Form.php)
			$tab_head .= sprintf('<li class="%1$s %2$s bc-single-tab" id="%4$s" ><a href="#">%3$s</a></li>', $disabled_class, $active_class, $item['title'], $random_id);

			$tab_body .= sprintf('<li>%1$s</li>', implode("", $item['content']));
		}

		$tab_head = sprintf('<ul bc-uk-tab>%1$s</ul>', $tab_head);

		$tab_body = sprintf('<ul class="bc-uk-switcher">%1$s</ul>', $tab_body);

		$html = $tab_head.$tab_body;

		if ($echo)
			echo $html;
		else
			return $html;

	}


	/**
	 * Create heading
	 *
	 * @param string $content HTML content of the heading, usually just text
	 * @param int $level heading level, similar to h1 to h6 but with smaller text. There are only three levels
	 * with text size 38px, 24px and 18px
	 *
	 * @return string
	 *
	 */
	public static function heading($content, $level = 1, $echo = true)
	{

		$output = sprintf('<div class="bc-doc-heading-%1$s">%2$s</div>', $level, $content);

		if ($echo)
			echo $output;
		else
			return $output;

	}



	/**
	 * Create a notice
	 *
	 * @param string $content html content
	 * @param string $type [error|info|warning|success]
	 * @param bool $closable
	 * @param bool $echo
	 *
	 * @return string
	 */
	public static function notice($content, $type, $closable = false, $echo = true)
	{

		switch ($type)
		{
			case 'info':
				$type_class = 'bc-uk-alert-primary';
				break;

			case 'success':
				$type_class = 'bc-uk-alert-success';
				break;

			case 'warning':
				$type_class = 'bc-uk-alert-warning';
				break;

			case 'error':
				$type_class = 'bc-uk-alert-danger';
				break;

			default:
				$type_class = 'bc-uk-alert-primary';
				break;

		}

		$closable = $closable ? '<a class="bc-uk-alert-close" bc-uk-close></a>' : '';

		$output = sprintf('<div class="%1$s" bc-uk-alert> %2$s <p>%3$s</p> </div>', $type_class, $closable, $content);

		if ($echo)
			echo $output;
		else
			return $output;

	}
	/**
	 * Create a flex section with content. Content is an array of HTML
	 *
	 * @param array $content: array of HTML
	 * @param string $flex_class: css class, from UI kit
	 *
	 * @return string HTML
	 */
	public static function flex_section($content, $flex_class = 'bc-uk-flex-left', $hidden = false)
	{

		$hidden_string = $hidden ? 'style="display: none;"' : '';
		$html = sprintf('<div class="bc-uk-flex %1$s" %2$s>', $flex_class, $hidden_string);

		foreach ($content as $c)
			$html .= sprintf('<div>%1$s</div>', $c);

		return $html . '</div>';
	}


	/**
	 * @param $content_li array list of item (li)
	 * @param $is_wrapped boolean is the items are wrapped in <li> or not
	 * @param $echo boolean echo or return
	 *
	 * @return void|string
	 */
	public static function ul($content_li, $is_wrapped, $echo = true)
	{
		$html = '';
		foreach ($content_li as $li)
		{
			if ($is_wrapped)
				$html .= $li;
			else
				$html .= sprintf('<li>%1$s</li>', $li);
		}

		$html = sprintf('<ul>%1$s</ul>', $html);

		if ($echo)
			echo $html;
		else
			return $html;
	}

	/**
	 * print necessary js code to handle form submit via ajax.
	 */
	public static function js_post_form()
	{ ?>

        <script>

            (function ($) {

                $(function () {
                    //save the settings on key press
                    $(window).bind('keydown', function(event) {
                        if (event.ctrlKey || event.metaKey) {
                            switch (String.fromCharCode(event.which).toLowerCase()) {
                                case 's'://bind Ctrl+S to save/submit form
                                    event.preventDefault();
                                    //save all forms
                                    _.each($('.bc-form-submit-button'), function(the_button){
                                        save_form($(the_button));
                                    });

                                    break;

                            }
                        }
                    });


                    $('.bc-form-submit-button').on('click', function (e) {
                        e.preventDefault();
                        save_form($(this));
                    });
                    /**
                     * when clicking on this button, add one more row. This is for settings that
                     * have value varied. For example the thank you page associate with categories
                     * in the thank you page plugin
                     */
                    $(document).on('click', '.add-data-row', function(){
                        add_data_row($(this));
                    });
                    /**
                     * Same reason as above
                     */
                    $(document).on('click', '.minus-data-row', function(){
                        remove_data_row($(this));
                    });

                });
                /**
                 * this function save form data via ajax
                 * form action value (ajax action) is defined by the
                 * action field in form (printed by form_settings())
                 * @param the_button button that clicked
                 */
                function save_form(the_button)
                {
                    var data = {};

                    //Collect data from fields that have keys (name), if the fields don't have keys, they are part of a
                    //bigger field (such as key_select_select)

                    //this array keeps multiple checkboxes that were processed
                    //multiple checkboxes fields need to processed only once to avoid value override
                    let multiple_checkbox_processed = [];

                    _.each(the_button.closest('form').find('input, select, textarea').not('.bc-no-key-field'), function (i) {

                        let input = $(i);
                        let input_name = (input).attr('name');
                        let input_value = undefined;


                        //for checkbox, get value of the checked one
                        //this is for simple checkbox, where the value is yes or no/true or false
                        //in case of multiple checkbox, we need to grab multiple values
                        if (input.attr('type') === 'checkbox' && input.val() === "")
                            input_value = input.is(":checked");
                        //save the multiple checkbox
                        else if (input.attr('type') === 'checkbox' && input.val() !== "")
                        {
                            //if the first checkbox with this name was processed, we skip because the values
                            //of all available options are saved in the first place
                            if (typeof multiple_checkbox_processed[input_name] === 'undefined')
                            {
                                input_value = [];

                                _.each(the_button.closest('form').find('input[name="'+input_name+'"]'), function(this_input_name){
                                    if ($(this_input_name).is(":checked"))
                                        input_value.push($(this_input_name).val())
                                });

                                //add the checkbox name to this of processed checkbox so the remaining checkboxes
                                //with the same name will not be processed again
                                multiple_checkbox_processed.push(input_name);
                            }


                        }
                        else if (input.attr('type') === 'radio') {
                            //for radio input, since there are many radios share the same name, only get the value of checked radio
                            if (input.is(':checked'))
                                input_value = input.val();
                        }
                        else
                        {

                            if (input.is('textarea') && input_name.indexOf('_save_html'))
                            {
                                console.log('saving HTML field');
                                input_value = encodeURIComponent(input.val());
                            }

                            else
                                input_value = input.val();
                        }

                        if (typeof (input_value) !== 'undefined')
                            data[input_name] = input_value;


                    });
                    //save data from fields that aren't simple input, select but have multiple inputs, selects
                    _.each(the_button.closest('form').find('.bc-key-array-assoc-data-field'), function(field){
                        var data_rows = {};

                        _.each($(field).find('.bc-single-data-row'), function(single_data_row){

                            var data_key = $(single_data_row).find('.bc-single-data-value').eq(0).val();
                            var data_value = $(single_data_row).find('.bc-single-data-value').eq(1).val();
                            if (data_key !== '' )
                                data_rows[data_key] = data_value;

                        });

                        //update the data of this field to the total data
                        data[$(field).attr('data-name')] = data_rows;

                    });


                    $.post(ajaxurl, data, function (response) {

                        swal('', response.message, 'info');
                        if (typeof (response.redirect_url) !== 'undefined')
                        {
                            var current_tab = the_button.closest('.bc-single-tab').attr('id');
                            window.location.href = response.redirect_url + '&active_tab='+ current_tab;
                        }

                    });
                }

                //add one more data ro
                function add_data_row(add_button)
                {
                    //clone current row
                    var clone = add_button.closest('.bc-single-data-row').clone();
                    add_button.closest('.bc-data-field').append(clone);

                }

                function remove_data_row(remove_button)
                {
                    var current_row = remove_button.closest('.bc-single-data-row');
                    //don't remove if it's the last row
                    var data_field = remove_button.closest('.bc-data-field');

                    if (data_field.find('.bc-single-data-row').length <=1)
                        return;

                    current_row.remove();


                }


            })(jQuery);

        </script>


		<?php

	}


	/**
	 * Echos an input text element
	 *
	 * @param $setting_field_name
	 * @param string $type
	 * @param bool $disabled
	 * @return string|void
	 */
	public static function temp_input_field($css_id, $type = 'text', $label = '', $disabled = false, $width = 200, $echo = true)
	{

		$disabled = $disabled ? 'disabled' : '';
		$html = '';
		$html .= '<div class="bc-temp-input">';
		if ($label != '')
			$html .= sprintf('<label class="bc-doc-label" for="%1$s">%2$s</label>', $css_id, $label);
		$html .= sprintf('<input class="bc-uk-input bc-temp-input" type="%1$s" id="%2$s"  %3$s style="width: %4$s;"/>', $type, $css_id, $disabled, $width . 'px');
		$html .= '</div>';

		if ($echo)
			echo $html;
		else
			return $html;

	}


	/**
	 * Returns an input text element
	 *
	 * @param $setting_field_name
	 *
	 * @return string|void
	 */
	public static function temp_hidden($css_id, $value, $echo = true)
	{

		$html = sprintf('<input class="bc-temp-input" type="hidden" id="%1$s" value="%2$s" />', $css_id, $value);

		if ($echo)
			echo $html;
		else
			return $html;

	}


	/**
	 * @param $title string title of the card
	 * @param $content array html array
	 */
	public static function card_section($title, $content, $width_class = '', $echo = true)
	{
		$html = sprintf('<div class="bc-uk-card-body bc-uk-card bc-uk-card-default %1$s">', $width_class);
		$html .= sprintf('<div class="bc-doc-heading-2">%1$s</div>', $title);

		$html .= implode("", $content) . '</div>';

		if ($echo)
			echo $html;
		return $html;
	}



}

