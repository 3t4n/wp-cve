<?php
/**/
// and below files
    $atss_page = 'policy-ats.php'; // it's part of the URL of the page
/*
* Function that adds a plugin settings page in the menu
*/
    add_action('admin_menu', 'atss_options');
    function atss_options() {
	global $atss_page;
	add_options_page( 'Параметры', 'ATs Privacy Policy', 'manage_options', $atss_page, 'atss_option_page');
}

/*
* This return function (Callback)
*/ 
    function atss_option_page(){
	global $atss_page;
?>

<div class="wrap">
		<h2>Параметры плагина ATs Privacy Policy</h2>
		<form method="post" enctype="multipart/form-data" action="options.php">
			<?php settings_fields('atss_options');
			do_settings_sections($atss_page); ?>
			<p class="submit">  
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
</div>
<?php
}

/*
* Register my
*/
/*************************************/
    add_action( 'admin_init', 'atss_option_settings_price' );
    function atss_option_settings_price() {
	global $atss_page;
	register_setting( 'atss_options', 'atss_options', 'atsse_validate_settings' );

// Added section and
	add_settings_section( 'atss_section_price', 'Привет! я Михаил ATs и мой плагин ATs Privacy Policy - запросто с Вордпресс ATs media', '', $atss_page );

// Create a text box of the first section
	$atss_field_params = array(
            'type'      => 'text',
            'desc'      => '
            <div class="atsdescr-price"><div class="atsdescr-prices"><b>Вы можете заказать у меня услуги по WordPress, от мелких правок до создания полноценного сайта.<br />Быстро, качественно, дешево!<br />Прайс-лист смотрите по <a title="Прайс-лист запросто с Вордпресс" href="https://mihalica.ru/product-category/uslugi-ats-media/" target="_blank" rel="noopener"><b>ЭТОМУ</b></a> адресу, и там далее по ссылкам</b>.
            </div>Если вам понравился мой плагин, то, пожалуйста, поставьте ему <a title="Плагин Ats privacy policy - запросто с Вордпресс" href="https://wordpress.org/support/plugin/ats-privacy-policy/reviews/" target="_blank" rel="noopener"><b>5 звезд</b></a> в репозитории.
            <br />либо, если есть желание, возможно просто <a title="помощь студии ATs media fashion " href="https://mihalica.ru/pomoshh-proektu-zaprosto-s-wordpress/#pomosys" target="_blank" rel="noopener"><b>помочь проекту</b></a>
            <hr />Пожалуйста, присылайте свои пожелания относительно настроек плагина: что добавить, что исключить.?.<br />реквизиты для связи ниже:<br />по всем вопросам развития плагина писать сюда: <b><a href="mailto:mihalica.ru@ya.ru">mihalica.ru@ya.ru</a></b>&nbsp;&nbsp;<a title="Плагин ATs Privacy Policy добавляет чекбокс политики конфиденциальности к форме комментирования" href="https://mihalica.ru/product/plagin-mats-privacy-policy-dobavit-privacy-policy-k-forme-kommentariev/" target="_blank" rel="noopener">оф. страница плагина</a>&nbsp;(пожалуйста, делитесь в соцсетях - поможем друг другу!)<br /><a title="Плагин ATs Privacy Policy страничка в депозитарии WordPress.org" href="https://ru.wordpress.org/plugins/ats-privacy-policy/" target="_blank" rel="noopener">страничка WordPress.org</a><br />посмотреть видео по примерным настройкам плагина <b>ATs Privacy Policy</b> можно по этой&nbsp;<a title="видео по настройкам плагина ATs Privacy Policy" href="https://www.youtube.com/watch?v=s67S7I3I-sw" target="_blank" rel="noopener"><b>ссылке ATs Privacy Policy</b></a> - YouTube<br />
            </div>'
	);
	add_settings_field( 'atsy_text_price_field', '<br />Пожалуйста, минуту внимания:', 'atss_option_display_price_settings', $atss_page, 'atss_section_price', $atss_field_params );
}
/*
* This example sets the HTML and PHP that outputs the fields
*/
    function atss_option_display_price_settings($args) {
	extract( $args );
	$option_ats = 'atss_options';
	$o = get_option( $option_ats );
	switch ( $type ) {
		case 'text':
			$o[$id] = esc_attr( stripslashes($o[$id]) );
			echo '';
			echo "<br />";
            echo ($desc != '') ? "$desc" : "";
} }
/*************************************/

/*
* Register your settings. My settings will be stored in a database called atss_options (this is also evident in the previous function)
*/
// disabling the policy form
    add_action( 'admin_init', 'atss_option_settings_checkes_forms' );
    function atss_option_settings_checkes_forms() {
 	global $atss_page;

// Assign a validation function ( atss_validate_settings() ). use it below
	register_setting( 'atss_options', 'atss_options', 'atsse_validate_settings' ); // added:

	// Added section and
	add_settings_section( 'atss_section_forms', '<hr /><br />Включить ATs Privacy Policy в форме комментирования:', '', $atss_page );

// checkbox activity data
	$atss_field_params = array(
            'type'      => 'checkbox',
            'desc'      => 'Включить/Отключить privacy-policy в форме комментирования! Если чекбокс не отмечен, то в форме комментирования останутся только ваши произвольные ссылки, если таковые заданы в настройках <a href="#atsyy">ниже</a>!<br />После окончания настроек плагина, чтобы проверить его чёткую отработку - <span style="color:red;">!обязательно!</span> почистите кэш (если установлены плагины) сайта и браузера<hr />',
            'id'        => 'checkesform',
            'label_for' => 'checkesform'
);
	add_settings_field( 'checkesform_field', 'Отметь, чтобы Включить ATs Privacy Policy в форме комментирования.<hr />', 'ats_option_display_settings_checkes_forms', $atss_page, 'atss_section_forms', $atss_field_params );	    
}
// checkbox activity data
    function ats_option_display_settings_checkes_forms($args) {
	extract( $args );
	$option_ats = 'atss_options';                                                 // added:
	$o = get_option( $option_ats );
	switch ( $type ) {
	    case 'checkbox':
			$checked = ($o[$id] == 'on') ? " checked='checked'" :  '';
			echo "<label><input type='checkbox' id='$id' name='" . $option_ats . "[$id]' $checked /><br />";
			echo "</label>";
			echo ($desc != '') ? $desc : "";
} }
// disabling the policy form

//
    add_action( 'admin_init', 'atss_option_settings_forms' );
    function atss_option_settings_forms() {
	global $atss_page;
	register_setting( 'atss_options', 'atss_options', 'atsse_validate_settings' );

// Added section and
	add_settings_section( 'atss_section_commentforms', 'Если у вас кастомная форма комментирования (настройки для продвинутых)', '', $atss_page );

// Create a text box of the first section
	$atss_field_params = array(
            'type'      => 'text',
            'id'        => 'atsy_text_forms',
            'desc'      => '<div class="atsdescr-forms">Если в вашем шаблоне используется нестандартная функция формы комментирования, укажите её в окне выше.</div><hr />'//
	);
	add_settings_field( 'atsy_text_forms_field', '<br />укажите в этом поле имя функции - БЕЗ СКОБОК', 'atss_option_display_forms_settings', $atss_page, 'atss_section_commentforms', $atss_field_params );
}
/*
* This example sets the HTML and PHP that outputs the fields
*/
    function atss_option_display_forms_settings($args) {
	extract( $args );
	$option_ats = 'atss_options';
	$o = get_option( $option_ats );
	switch ( $type ) {
		case 'text':
			$o[$id] = esc_attr( stripslashes($o[$id]) );
			echo "Укажите функцию вашей формы комментирования по типу:  
			<b>comment_form()</b><br />например: <b>my_comment_form</b> <i> - без скобок&nbsp;<s>&nbsp;()&nbsp;</s></i>&nbsp;внимательнее, чтобы не было ЛИШНИХ пробелов!<br />";
			echo "<input class='regular-text' type='text' id='$id' name='" . $option_ats . "[$id]' value='$o[$id]' />";
			echo "<br />ваша функция формы комментирования: <b>";
			$all_options = get_option('atss_options'); echo $all_options['atsy_text_forms']; echo "</b><br />";
            echo ($desc != '') ? "$desc" : "";
} }
//

/*
* A function of displaying input fields = texts before the link
*/
    add_action( 'admin_init', 'atss_option_settings_texs' );
    function atss_option_settings_texs() {
	global $atss_page;
	register_setting( 'atss_options', 'atss_options', 'atsse_validate_settings' );

// Added section and
	add_settings_section( 'atss_section_on', 'Текст перед ссылкой Privacy Policy на <b>Правила конфиденциальности</b>:', '', $atss_page );

// Create a text box of the first section
	$atss_field_params = array(
            'type'      => 'text',
            'id'        => 'atsy_text_tex',
            'desc'      => '<div class="atsdescr">далее введите текст ссылки</div>'
            //'label_for' => 'atsy_text_tex'
	);
	add_settings_field( 'atsy_text_tex_field', 'Текст перед ссылкой на<br />правила конфиденциальности', 'atss_option_display_settings_texs', $atss_page, 'atss_section_on', $atss_field_params );
}
// texts before the link
    function atss_option_display_settings_texs($args) {
	extract( $args );
	$option_ats = 'atss_options';
	$o = get_option( $option_ats );
	switch ( $type ) {
		case 'text':
			$o[$id] = esc_attr( stripslashes($o[$id]) );
			echo "Укажите текст (например: \"Необходимо принять правила конфиденциальности\"";
            echo "<br /><br />";
			echo "<input class='regular-text' type='text' id='$id' name='" . $option_ats . "[$id]' value='$o[$id]' />";
			echo "<br />";
            echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";
} }

/*
* link text
*/
    add_action( 'admin_init', 'atss_option_settings_texs_links' );
    function atss_option_settings_texs_links() {
	global $atss_page;
	register_setting( 'atss_options', 'atss_options', 'atsse_validate_settings' );

// Added section and
	add_settings_section( 'atss_section_on', 'Текст перед ссылкой Privacy Policy на <b>Правила конфиденциальности</b>:', '', $atss_page );

// Create a text box of the first section
	$atss_field_params = array(
            'type'      => 'text',
            'id'        => 'atsy_text_links',
            'desc'      => '<div class="atsdescr">далее введите ссылку</div>'
            //'label_for' => 'atsy_text_tex'
	);
	add_settings_field( 'atsy_text_links_field', 'Текст ссылки на<br />правила конфиденциальности', 'atss_option_display_settings_texs_links', $atss_page, 'atss_section_on', $atss_field_params );
}
// link text
    function atss_option_display_settings_texs_links($args) {
	extract( $args );
	$option_ats = 'atss_options';
	$o = get_option( $option_ats );
	switch ( $type ) {
		case 'text':
			$o[$id] = esc_attr( stripslashes($o[$id]) );
			echo "Укажите текст ссылки (например: \"правила конфиденциальности\"";
            echo "<br /><br />";
			echo "<input class='regular-text' type='text' id='$id' name='" . $option_ats . "[$id]' value='$o[$id]' />";
			echo "<br />";
            echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";
} }
// Create a text box of the first section = link text

/*
* link ATs Privacy Policy
*/
    add_action( 'admin_init', 'atss_option_settings' );
    function atss_option_settings() {
	global $atss_page;
	register_setting( 'atss_options', 'atss_options', 'atsse_validate_settings' );

// Added section and
	add_settings_section( 'atss_section_on', 'Поля настроек ссылок ATs Privacy Policy - <b>Правила конфиденциальности</b>:', '', $atss_page );

// Create a text box of the first section
	$atss_field_params = array(
            'type'      => 'text',
            'id'        => 'atsy_text',
            'desc'      => '<div class="atsdescr">Разработка/создание и оптимизация сайтов... администрирование ваших площадок...<br />Приемлемые цены!</div>'
            //'label_for' => 'atsy_text'
	);
	add_settings_field( 'atsy_text_field', 'Ссылка<br />на правила конфиденциальности', 'atss_option_display_settings', $atss_page, 'atss_section_on', $atss_field_params );
}

/*
* This example sets the HTML and PHP that outputs the fields
*/
    function atss_option_display_settings($args) {
	extract( $args );
	$option_ats = 'atss_options';
	$o = get_option( $option_ats );
	switch ( $type ) {
		case 'text':
			$o[$id] = esc_attr( stripslashes($o[$id]) );
			echo "Укажите нужную ссылку в поле ниже - введите адрес... 
			дописав <b>sample_post</b> - хвостик ссылки:<br />например: https://имя_домена.ru/<i>privacy-policy-site</i><br /><br />";
			echo site_url(); echo "/"; /*echo "<?php _e('Save Changes') ?>";*/
			echo "<input class='regular-text' type='text' id='$id' name='" . $option_ats . "[$id]' value='$o[$id]' />";
			echo "<br /><br />HTML адрес на Вашу текущую страницу <b>правил кофиденциальности:</b><br />";
			echo site_url(); echo "/<b>";
			$all_options = get_option('atss_options');
			echo $all_options['atsy_text']; echo "</b><br /><br />";
			echo 'Так выглядит ваша ссылка ATs Privacy Policy в форме комментирования:&nbsp;<br /><span class="atsy-text-links"><span style="font-size:20px">&#9745;</span>&nbsp;' . $all_options['atsy_text_tex'] . '<a title="' . $all_options['atsy_text_links'] . '" href="/' . $all_options['atsy_text'] . '" target="_blank" rel="nofollow">' . $all_options['atsy_text_links'] . '</a></span>';
			echo "<br /><br /><div class='atstitle'>Создание сайтов: визитки, блоги, порталы... расторопно и качественно!</div><div class='tabs-img'><a href='https://mihalica.ru/product/plagin-privacy-policy-wordpress' title='Запросто с WordPress - ATs media' target='_blank'><img class='ats-privacy-img' src='/wp-content/plugins/ats-privacy-policy/images/ats-privacy-policy.png' alt='запросто с WordPress' title='Запросто с WordPress - ATs media' /></a></div><div class='atstitle2'>запросто с Вордпресс - ATs media fashion - Reception WordPress golden</div></div>";
            echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";
} }
// Create a text box of the first section


// the default check box
    add_action( 'admin_init', 'atss_option_settings_checkes' );
    function atss_option_settings_checkes() {
 	global $atss_page;
	register_setting( 'atss_options', 'atss_options', 'atsse_validate_settings' ); // added:
	
	// Added section and
	add_settings_section( 'atss_section_checkes', '<hr /><br />Параметры чекбокса конфиденциальности:', '', $atss_page );

// checkbox activity data
	$atss_field_params = array(
            'type'      => 'checkbox',
            'desc'      => 'флажок в форме комментирования - помечать "согласие" автоматически для каждого пользователя.?. <span style="color:red;">в этом случае может быть больше спама!!</span>',
            'id'        => 'atscheckes'
);
	add_settings_field( 'atscheckes_field', 'Отметь, чтобы флажок был установлен по умолчанию:<hr />или оставь пустым, если автоотметка не требуется', 'ats_option_display_settings_checkes', $atss_page, 'atss_section_checkes', $atss_field_params );	    
}
// the default check box
    function ats_option_display_settings_checkes($args) {
	extract( $args );
	$option_ats = 'atss_options';                                                 // added:
	$o = get_option( $option_ats );
	switch ( $type ) {
	    case 'checkbox':
			$checked = ($o[$id] == 'on') ? " checked='checked'" :  '';
			echo "<label><input type='checkbox' id='$id' name='" . $option_ats . "[$id]' $checked /><br />";
			echo "</label>";
			echo ($desc != '') ? $desc : "";
} }
// the default check box


// disabling the URL
    add_action( 'admin_init', 'ats_option_settings_checkes_url' );
    function ats_option_settings_checkes_url() {
 	global $atss_page;
	register_setting( 'atss_options', 'atss_options', 'atsse_validate_settings' ); // added:
	
	// Added section and
	add_settings_section( 'atss_section_url', '<hr /><br />Отключить поле URL сайта автора комментария в форме комментирования:', '', $atss_page );

// checkbox activity data
	$atss_field_params = array(
            'type'      => 'checkbox',
            'desc'      => 'Отключить/Включить поле URL сайта в форме комментирования.<br /> Тестируйте <span style="color:red;">осторожнее!</span> исключая поля - может быть нарушена блочная стилистика отображения в форме комментирования! Всё в полной зависимости от вашей темы: если, отключив поля, структура полей ввода сломается, попробуйте подключить отладку CSS (чекбокс ниже) ЛИБО ЖЕ поправьте стили CSS в файле стилей вашего активного шаблона сайта.',
            'id'        => 'urlcheckes'
);
	add_settings_field( 'urlcheckes_field', 'Отметь, чтобы Отключить поле URL в форме комментирования.<hr />', 'ats_option_display_settings_checkes_url', $atss_page, 'atss_section_url', $atss_field_params );	    
}
// checkbox activity data
    function ats_option_display_settings_checkes_url($args) {
	extract( $args );
	$option_ats = 'atss_options';                                                 // added:
	$o = get_option( $option_ats );
	switch ( $type ) {
	    case 'checkbox':
			$checked = ($o[$id] == 'on') ? " checked='checked'" :  '';
			echo "<label><input type='checkbox' id='$id' name='" . $option_ats . "[$id]' $checked /><br />";
			echo "</label>";
			echo ($desc != '') ? $desc : "";
} }
// disabling the URL


// disabling the CSS
    add_action( 'admin_init', 'ats_option_settings_checkes_css' );
    function ats_option_settings_checkes_css() {
 	global $atss_page;
	register_setting( 'atss_options', 'atss_options', 'atsse_validate_settings' ); // added:
	
	// Added section and
	add_settings_section( 'atss_section_css', 'Откл/Вкл стили css', '', $atss_page );

// checkbox activity data
	$atss_field_params = array(
            'type'      => 'checkbox',
            'desc'      => 'Отключить/Включить стили css плагина для отладки формы комментирования.<br />',
            'id'        => 'checkescss'
);
	add_settings_field( 'checkescss_field', 'Отметь, чтобы включить стили css подстройки.<hr />', 'ats_option_display_settings_checkes_css', $atss_page, 'atss_section_css', $atss_field_params );	    
}
// checkbox activity data
    function ats_option_display_settings_checkes_css($args) {
	extract( $args );
	$option_ats = 'atss_options';                                                 // added:
	$o = get_option( $option_ats );
	switch ( $type ) {
	    case 'checkbox':
			$checked = ($o[$id] == 'on') ? " checked='checked'" :  '';
			echo "<label><input type='checkbox' id='$id' name='" . $option_ats . "[$id]' $checked /><br />";
			echo "</label>";
			echo ($desc != '') ? $desc : "";
} }
// disabling the CSS


// disabling the Email
    add_action( 'admin_init', 'ats_option_settings_checkes_email' );
    function ats_option_settings_checkes_email() {
 	global $atss_page;
	register_setting( 'atss_options', 'atss_options', 'atsse_validate_settings' ); // added:
	
	// Added section and
	add_settings_section( 'atss_section_email', '<br />Отключить поле EMAIL в форме комментирования:', '', $atss_page );

// checkbox activity data
	$atss_field_params = array(
            'type'      => 'checkbox',
            'desc'      => 'Отключить/Включить поле EMAIL (эл/п) в форме комментирования.<br /><span style="color:red;">Внимание!</span> Если убираете <span style="color:red;">поле Email</span>, то непременно пройдите в настройки своего сайта раздел "<a title="Обсуждения" href="/wp-admin/options-discussion.php" target="_blank" rel="noopener">Обсуждения</a>" и снимите галочку с чекбокса "Автор комментария должен указать имя и e-mail" потому что это поле является обязательным!',
            'id'        => 'emailcheckes'
);
	add_settings_field( 'emailcheckes_field', 'Отметь, чтобы Отключить поле EMAIL (эл/п) в форме комментирования.<hr />', 'ats_option_display_settings_checkes_email', $atss_page, 'atss_section_email', $atss_field_params );	    
}
// checkbox activity data
    function ats_option_display_settings_checkes_email($args) {
	extract( $args );
	$option_ats = 'atss_options';                                                 // added:
	$o = get_option( $option_ats );
	switch ( $type ) {
	    case 'checkbox':
			$checked = ($o[$id] == 'on') ? " checked='checked'" :  '';
			echo "<label><input type='checkbox' id='$id' name='" . $option_ats . "[$id]' $checked /><br />";
			echo "</label>";
			echo ($desc != '') ? $desc : "";
} }
// disabling the Email


// Create a privacys textarea 
    add_action( 'admin_init', 'atss_option_settings_textr_none' );
    function atss_option_settings_textr_none() {
 	global $atss_page;
	register_setting( 'atss_options', 'atss_options', 'atsse_validate_settings' ); // added:
	
	// Added section and
	add_settings_section( 'atss_section_textr_none', '<hr /><br />HTML код дополнительной ссылки<a id="atsyy"></a> (или ссылок) к форме принятия "пользовательского соглашения":', '', $atss_page );

	$atss_field_params = array(
            'type'      => 'textarea',
            'id'        => 'atsy_textsssy_non',
            'desc'      => '<br />...если дополнительные ссылки не требуется <span style="font-weight: 700;color: #038900;font-style: italic;">оставьте поле пустым</span>'
	);
	add_settings_field( 'atsy_textsssy_non_field', '<br /><br />Варианты HTML ссылок', 'atss_option_display_settings_none', $atss_page, 'atss_section_textr_none', $atss_field_params );
}
// textarea privacys function
    function atss_option_display_settings_none($args) {
	extract( $args );
	$option_ats = 'atss_options';                                                 // added:
	$o = get_option( $option_ats );
	switch ( $type ) {
		case 'textarea':
			$o[$id] = esc_attr( stripslashes($o[$id]) );
			$dsarea = '<<span style="display: none;">!</span>a title="Заголовок" href="полный-адрес страницы" rel="nofollow noopener noreferrer" onclick="return !window.open(this.href)">пользовательского соглашения<<span style="display: none;">!</span>/a>';
			echo "<div class='atsdescr'>Настройки, расположенные ниже, позволяют добавить прочие произвольные ссылки, - например, на страницу \"Пользовательского соглашения\". ...либо же на какую-то иную полезную иформацию вашего сайта</div>";
			echo 'добавьте в поле ниже любой вариант вашей html ссылки, например: <br /><br /><code style="font-weight: 700;font-size: 15px;">' . $dsarea . '</code><br /><br />Можно скопировать и отредактировать на своё усмотрение! добавляйте стили CSS!';
			$all_options = get_option('atss_options');                            // added: double styles
			echo "<br /><br />";/*echo "<?php _e('Save Changes') ?>";*/
			echo "<textarea placeholder='  любые варианты ваших html ссылок...' class='code large-text' cols='50' rows='2' type='text' id='$id' name='" . $option_ats . "[$id]' >$o[$id]</textarea>";
			echo "<br /><br />Прямой переход по ссылке/ссылкам:&nbsp;";	echo $all_options['atsy_textsssy_non'];
			echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";
} }
// fin news textarea privacys function


// Create a textarea
    add_action( 'admin_init', 'atss_option_settings_textr' );
    function atss_option_settings_textr() {
 	global $atss_page;

// Assign a validation function ( atss_validate_settings() ). use it below
	register_setting( 'atss_options', 'atss_options', 'atsse_validate_settings' );  // added:
    
    //  Added section and
	add_settings_section( 'atss_options_texts', '<hr /><br />Введите cтили CSS для элементов плагина ATs Privacy Policy - виртуальный селектор: .ats-privacy-policy - положение элементов текста, чекбокса...', '', $atss_page );   

	$atss_field_params = array(
            'type'      => 'textarea',
            'id'        => 'atsy_textsssy',
            'desc'      => '<b>}</b><br /><br />например, такие приблизительные варианты CSS:
            <br />добавьте в поле выше и немного подправьте в соответствии со стилистикой Вашей активной темы...<br /><br /><span class="atsdescr">изменяйте свойства так, как вам требуется!!. или вовсе исключите</span>
            <br /><br /><code>font-weight: 700;  /* насыщенность - в значениях свойств возможно использовать ВАЖНОСТЬ <b>!important</b>, там, где требуется */
            <br />color: #747474 !important;     /* цвет */
            <br />margin: 0 0 0 2px;             /* расположение текста/ccылок - право/лево: <span style="font-weight: 700;color: green">обычно достаточно этого параметра</span> */
            <br />padding: 0 10px 7px; /* расположение - внутренний отступ */	    
            <br />font-size: 11px;               /* размер текста */
            <br />font-family: Arial,Helvetica;  /* шрифт текста */
            <br />font-style: italic;            /* наклон текста */
            <br />line-height: 25px;             /* межстрочный отступ - интервал */
            </code><br /><br /><span class="atsdescr">прочий дизайн текста ссылок</span> возможно более тонко регулировать классами:<br /><b><code>.refe</code></b> - стили чекбока и ссылки "Правила конфиденциальности"<br /><b><code>.ats-privacy&nbsp;.ats-privacy a</code></b> - стили ссылки "Пользовательского соглашения" и пр.<br />добавьте эти классы (селекторы) к себе в активный шаблон в файл стилей (style.css)  и задавайте оптимальную стилистику в соответствии со своей темой...<br />Либо обозначьте текст стилями CSS прямо в форме вариантов HTML <b><a href="#atsyy">выше</a></b>
            <hr />'
	);
    add_settings_field( 'atsy_textsssy_field', '<br /><br />Введите в поле свойства и значения стилей CSS', 'atss_option_display_settings_sssy', $atss_page, 'atss_options_texts', $atss_field_params );
}
// textarea function
    function atss_option_display_settings_sssy($args) {
	extract( $args );
	$option_ats = 'atss_options';                                                 // added:
	$o = get_option( $option_ats );
	switch ( $type ) {
		case 'textarea':
			$o[$id] = esc_attr( stripslashes($o[$id]) );
			echo "здесь показаны Ваши стили CSS: положение/цвет текста/ссылок...<br />селектор: <b><code>.ats-privacy-policy</code></b> "; echo "( ";
			$all_options = get_option('atss_options');                            // added: double styles
			echo $all_options['atsy_textsssy'];                                   // added:
			echo " )<br /><b>{</b>";/*echo "<?php _e('Save Changes') ?>";*/
			echo "<br />";
			echo "<textarea class='code large-text' cols='50' rows='10' type='text' id='$id' name='" . $option_ats . "[$id]' >$o[$id]</textarea>";
			echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";
} }
// fin news

// disabling the policy form link
    add_action( 'admin_init', 'atss_option_settings_checkes_forms_lincs' );
    function atss_option_settings_checkes_forms_lincs() {
 	global $atss_page;

// Assign a validation function ( atss_validate_settings() ). use it below
	register_setting( 'atss_options', 'atss_options', 'atsse_validate_settings' ); // added:
	
	// Added section and
	add_settings_section( 'atss_section_forms_lincs', '<br />Включить ссылку на страницу плагина ATs Privacy Policy в форме комментирования:', '', $atss_page );

// checkbox activity data
	$atss_field_params = array(
            'type'      => 'checkbox',
            'desc'      => 'Отметьте чек, если хотите убрать ссылку.<br />Я буду очень признателен Вам за то, если найдёте возможным оставить ссылку в форме комментирования! Ссылка закрыта атрибутом <code>nofollow</code> Линк Вам ничем не повредит. А напротив обусловит наше с Вами сотрудничество...<br /><span style="color:red;">! ссылка важна для распространения этого плагина ! Давайте помогать друг другу !</span>',
            'id'        => 'checkesformlincs'
);
	add_settings_field( 'checkesform_field', 'Убрать ссылку на оф. страницу плагина ATs Privacy Policy.<hr />', 'ats_checkes_forms_lincs', $atss_page, 'atss_section_forms_lincs', $atss_field_params );	    
}
// checkbox activity data
    function ats_checkes_forms_lincs($args) {
	extract( $args );
	$option_ats = 'atss_options';                                                 // added:
	$o = get_option( $option_ats );
	switch ( $type ) {
	    case 'checkbox':
			$checked = ($o[$id] == 'on') ? " checked='checked'" :  '';
			echo "<label><input type='checkbox' id='$id' name='" . $option_ats . "[$id]' $checked />";
			echo "</label>";
			echo ($desc != '') ? $desc : "";
} }
// disabling the policy form link

/*The function of checking the correctness of the input fields
function atss_validate_settings($input) {
}*/