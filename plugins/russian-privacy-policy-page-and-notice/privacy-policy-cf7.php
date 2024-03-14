<?php
/*
Plugin Name: Политика конфиденциальности для сайта. Согласие под формами Contact-Form 7
Plugin URI: https://aleksinsky.ru
Description: Добавляет на сайт страницу с политикой конфиденциальности. Также, добавляет под Контакт Форм 7 согласие на обработку персональных данных. Плагин предназначен только для русскоязычных сайтов. При входе пользователям показывает оповещение о использовании сайтом cookies. Подходит для 152ФЗ.
Version: 1.29
Author: aleksinsky.ru
Author URI: https://aleksinsky.ru
License: GPLv2
*/


add_action('admin_menu', 'ppcf7_create_menu');

function ppcf7_create_menu() {
    //create new top-level menu
    add_menu_page(__('Политика конфиденциальности'), __('Политика конфиденциальности'), 'administrator', __FILE__, 'ppcf7_settings_page','dashicons-shield');

    //call register settings function
    add_action( 'admin_init', 'register_ppcf7settings' );
}

function register_ppcf7settings() {
    register_setting('setting-group', 'ppcf7_name'); // имя организации
    register_setting('setting-group', 'ppcf7_contact'); // контакты организации
    register_setting('setting-group', 'ppcf7_date'); // дата публицкации
    register_setting('setting-group', 'ppcf7_footer_link'); // отображать ссылку в футере
	register_setting('setting-group', 'ppcf7_showTitle'); // показывать заголовок
	register_setting('setting-group', 'ppcf7_how'); // способо показа
	register_setting('setting-group', 'ppcf7_remember'); // время cookie
}

function short_ppcf7_name() {
    return get_option('ppcf7_name');
}

function short_ppcf7_date() {
    return get_option('ppcf7_date');
}

function short_ppcf7_contact() {
    return get_option('ppcf7_contact');
}

function short_ppcf7_showTitle() {
	return (!get_option('ppcf7_showTitle')) ? '<h1>Политика конфиденциальности</h1><br>' : '';
}

add_shortcode( 'ppcf7_name', 'short_ppcf7_name');
add_shortcode( 'ppcf7_contact', 'short_ppcf7_contact' );
add_shortcode( 'ppcf7_date', 'short_ppcf7_date' );
add_shortcode( 'ppcf7_title', 'short_ppcf7_showTitle' );

function ppcf7_activate() {
    
$post_name = get_page_by_path('privacy-policy');
$name = get_option('ppcf7_name');
$date = get_option('ppcf7_date');
$contact = get_option('ppcf7_contact');

    
$text = '<div class="container-ppcf7">[ppcf7_title]<p>Данная Политика конфиденциальности определяет порядок сбора, хранения, применения, раскрытия и передачи информации, которую [ppcf7_name] (далее КОМПАНИЯ) получает от пользователей и клиентов ресурса (далее ПОЛЬЗОВАТЕЛЬ) '.site_url().' (далее — САЙТ). Представленная Политика конфиденциальности распространяется на все сопутствующие ресурсы, поддомены, продукты, сервисы и услуги КОМПАНИИ.</p><p class="h3" style="font-size: 24px;font-weight: bold;margin: 20px 0;">Общие положения</p><p>Использование ПОЛЬЗОВАТЕЛЕМ САЙТА означает согласие с настоящей Политикой конфиденциальности и условиями обработки персональных данных ПОЛЬЗОВАТЕЛЯ. В случае несогласия с условиями Политики конфиденциальности ПОЛЬЗОВАТЕЛЬ должен прекратить использование САЙТА.</p><br><p>Представленная Политика конфиденциальности касается только настоящего САЙТА и информационные данные, которые добровольно предоставляют ПОЛЬЗОВАТЕЛИ. Ее действие не распространяется на сторонние ресурсы, в том числе, те, на которых упоминается САЙТ или на которых содержатся прямые ссылки на САЙТ. КОМПАНИЯ не проверяет достоверность персональных данных, предоставляемых ПОЛЬЗОВАТЕЛЕМ.</p><p class="h3" style="font-size: 24px;font-weight: bold;margin: 20px 0;">Персональная информация ПОЛЬЗОВАТЕЛЕЙ, которую обрабатывает КОМПАНИЯ</p><p>При посещении САЙТА автоматически определяется Ваш IP адрес, имя домена, страну регистрации IP. Также нами фиксируется факты перехода по страницам САЙТА, прочую информацию, которую Ваш браузер предоставляет открыто и добровольно. Данные сведения помогают существенно упростить пользование САЙТОМ, сделать поиск нужных или интересных для Вас материалов намного быстрее и комфортнее.</p><br><p>На САЙТЕ реализована стандартная технология персональной настройки стилей отображения страниц и размещенного на них контента под параметры конкретно Вашего монитора «cookies». «Куки» — это сохраняющиеся на жестком диске данные о посещаемых веб-сайтах, пользовательских настройках, персональных настройках режима просмотра контента. Реализованная на САЙТЕ технология «cookies» предоставляет информацию о том, с какого стороннего ресурса был выполнен переход на Сайт, имени домена Вашего провайдера, страны посетителя, данные о загруженных материалах с САЙТА. Данная технология используется и браузерными счетчиками компаний Yandex, Rambler, Google.</p><br><p>«Сookies» не собирает личной или конфиденциальной информации о пользователе, данную технологию можно заблокировать при персональной работе с САЙТОМ, используя настройки Вашего браузера или поставить обязательное уведомление о посылке «куки».</p><br><p>На САЙТЕ реализована стандартная технология подсчета количества посетителей и просмотров страниц, оценки технических возможностей хост-сервиров, рейтингов, посещаемости сторонних организаций. Данная информация позволяет нам вести учет активности посетителей, актуальности представленного контента, его востребованности, составлять характеристику посещаемой аудитории. Также подобный сбор данных помогает нам располагать страницы и материал наиболее удобным для пользователей образом, обеспечивать эффективное взаимодействие и безупречную работу с браузерами посетителей.</p><br><p>Мы фиксируем информацию о перемещениях по САЙТУ, просматриваемых страницах в общем, а не персональном порядке. Никакие личные или индивидуальные данные без разрешения пользователей КОМПАНИЯ не будет использована или передана третьим лицам.</p><br><p>Любая персональная информация, в том числе идентификационная, предоставляется пользователями САЙТА исключительно добровольно. Все данные, которые Вы оставляете на САЙТЕ собственноручно при регистрации, в ходе оформления заказа, заполнения форм (ФИО, адрес электронной почты, контактный телефон, данные кредитной карты, банковских счетов) сохраняются в тайне и не разглашаются. Каждый посетитель САЙТА в праве отказать от предоставления любой персональной информации и посещать ресурс на условиях абсолютной анонимности, кроме случаев, когда данные действия могут помешать корректному пользованию отдельными функциями или возможностями САЙТА.</p><br><p class="h3" style="font-size: 24px;font-weight: bold;margin: 20px 0;">Цели обработки персональной информации ПОЛЬЗОВАТЕЛЕЙ</p><p>КОМПАНИЯ собранная информация о личных данных посетителей САЙТА может быть применена для следующих целей:</p><br><p>Связь с ПОЛЬЗОВАТЕЛЕМ, в том числе направление уведомлений, запросов и информации, касающихся использования САЙТА, работы КОМПАНИИ, исполнения соглашений и договоров КОМПАНИИ, а также обработка запросов и заявок от ПОЛЬЗОВАТЕЛЯ;</p><br><p>Улучшение качества обслуживания клиентов и пользователей. Предоставляемые Вами данные помогают намного эффективнее реагировать на запросы или обращения посетителей, клиентов;</p><p>Персонализация пользовательского опыта. Информация применяется для составления «портрета» пользователя, определения интересующего Вас контента, актуальных для Вас услуг и сервисов, предоставляемых на САЙТЕ;</p><br><p>Обработка заказов и платежей. Предоставленная информация применяется для оформления заказа, контроля поступления оплаты за него. Любая финансовая или персональная информация о наших ПОЛЬЗОВАТЕЛЯХ не передается третьим лицам и сохраняется в тайне.</p><br><p class="h3" style="font-size: 24px;font-weight: bold;margin: 20px 0;">Меры, применяемые для защиты персональной информации ПОЛЬЗОВАТЕЛЯ</p><p>КОМПАНИЯ принимает необходимые и достаточные организационные и технические меры для защиты персональной информации ПОЛЬЗОВАТЕЛЯ от неправомерного или случайного доступа, уничтожения, изменения, блокирования, копирования, распространения, а также от иных неправомерных действий с ней третьих лиц.</p><br><p class="h3" style="font-size: 24px;font-weight: bold;margin: 20px 0;">Изменение Политики конфиденциальности. Применимое законодательство</p><br><p>КОМПАНИЯ оставляет за собой право менять или корректировать правила и условия Политики конфиденциальности. В случае внесения каких-либо поправок или нововведений в настоящую Политику, указывается дата последнего обновления. Используя данный САЙТ, Вы соглашаетесь с вышеописанными правилами и условиями, а также берете на себя ответственность за периодическое ознакомление с нововведениями и изменениями в Политике конфиденциальности.</p><br><p class="h3" style="font-size: 24px;font-weight: bold;margin: 20px 0;">Отказ от ответственности</p><p>САЙТ не берет на себя ответственность за действия других сайтов и ресурсов, третьих лиц и сторонних посетителей.</p><br><p class="h3" style="font-size: 24px;font-weight: bold;margin: 20px 0;">Обратная связь.</p><p>Все предложения или вопросы относительно Политики конфиденциальности САЙТА КОМПАНИИ ПОЛЬЗОВАТЕЛЬ вправе сообщать по нижеуказанным контактам :</p><br><p> [ppcf7_contact] <br><br> Дата публикации: [ppcf7_date] <br></p></div>';
// Create post object
$my_post = array(
  'post_title'    => __('Политика конфиденциальности'),
  'post_name'      => 'privacy-policy',
  'post_content'  => $text,
  'post_status'   => 'publish',
  'post_type'      => 'page'
);
 
// Insert the post into the database
if (!$post_name && $name && $date && $contact) {
wp_insert_post( $my_post );
}
}
add_action( 'admin_init', 'ppcf7_activate' );

function ppcf7_settings_page() {
    ?>
<style>
</style>
    <div class="wrap">
        <h2><?php _e('Политика конфиденциальности'); ?></h2>
        <form method="post" action="options.php" id="ppcf7_settings">
        <?php settings_fields('setting-group'); ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="ppcf7_name"><?php _e('Название организации'); ?></label></th>
                    <td>
                    <input name="ppcf7_name" type="text" id="ppcf7_name" value="<? echo get_option('ppcf7_name'); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="ppcf7_contact"><?php _e('Контакты организации'); ?></label></th>
                    <td>
                    <textarea rows="10" cols="45" id="ppcf7_contact" name="ppcf7_contact" class="regular-text" value="<? echo get_option('ppcf7_contact'); ?>"><? echo get_option('ppcf7_contact'); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="ppcf7_date"><?php _e('Дата публикации'); ?></label></th>
                    <td>
                    <input name="ppcf7_date" type="text" id="ppcf7_date" value="<? echo get_option('ppcf7_date'); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="ppcf7_date"><?php _e('Скрыть ссылку на политику в подвале'); ?></label></th>
                    <td>
                    <input name="ppcf7_footer_link[ppcf7_footer_link]" type="checkbox" value="1" <?php $option = get_option('ppcf7_footer_link'); if($option)  checked( $option['ppcf7_footer_link'] ); ?> id="ppcf7_footer_link" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="ppcf7_showTitle"><?php _e('Скрыть заголовок из текста политики'); ?></label></th>
                    <td>
                    <input name="ppcf7_showTitle" type="checkbox" value="1" <?php checked( get_option('ppcf7_showTitle') ); ?> id="ppcf7_showTitle" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="ppcf7_showTitle"><?php _e('Способ работы плагина'); ?></label></th>
                    <td>
                    <select name="ppcf7_how">
					<?php $select = get_option('ppcf7_how', 'all');
						$options = array(
						'all' =>  __('Всплывающее окно и под Contact Form 7'),
						'popup' =>  __('Только всплывающее  окно'),
						'form' =>  __('Только под Contact Form 7'),
						);
						foreach ($options as $key => $option) : ?>
						<option <?php selected( $select, $key ); ?> value="<?php echo $key; ?>"><?php echo $option; ?></option>
						<?php endforeach; ?>
					</select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="ppcf7_date"><?php _e('Сколько дней не показывать всплывающее окно после согласия'); ?></label></th>
                    <td>
                    <input name="ppcf7_remember" type="text" id="ppcf7_remember" value="<? echo get_option('ppcf7_remember', 30); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row" colspan="2"><label for="ppcf7_showTitle"><?php _e('Нравится плагин? Поддержите разработчика рублем:'); ?> <a target="_blank" href="https://yoomoney.ru/to/41001830869620"><?php _e('Поддержать'); ?></a></label></th>
                </tr>
            </tbody>
        </table>
            <input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" />
        </form>
    </div>
    <?php
}

function ppcf7_styles() {
$post_name = get_page_by_path('privacy-policy');
    if ($post_name) {
    ?>
    <style type="text/css" id="ppcf7-header-css">
.pp-cookies-notice{position:fixed;bottom:0;left:0;right:0;padding:10px 20px;border-top:1px solid #ccc;background:#fff;z-index:999;display:none;}.pp-left,.pp-right{float:left;}.pp-left{width:80%;}.pp-right{width:20%;text-align:right;}.pp-btn{cursor:pointer;border-bottom:1px dashed;display:inline-block;padding:2px;opacity:0.8;}.pp-btn:hover{border-color:transparent;}@media(max-width:991px){.pp-left,.pp-right{float:none;width:100%;}.pp-right{text-align:left;}}
    </style>    
    <?php
}
}

function ppcf7_scripts() {
    $post_name = get_page_by_path('privacy-policy');
    if ($post_name) {
    ?>
    <?php if (!get_option('ppcf7_footer_link')) : ?>
    <p class="privacy-policy"><a title="<?php _e('Политика конфиденциальности'); ?>" rel="nofollow" href="/privacy-policy"><?php _e('Политика конфиденциальности'); ?></a></p>
    <?php endif; ?>
<div class="pp-cookies-notice">
    <div class="pp-left">
        <p><?php _e('Наш сайт использует файлы cookies, чтобы улучшить работу и повысить эффективность сайта. Продолжая работу с сайтом, вы соглашаетесь с использованием нами cookies и ') ?><a title="<?php _e('Политика конфиденциальности'); ?>" target="_blank" rel="nofollow" href="/privacy-policy"><?php _e('политикой конфиденциальности'); ?></a>.</p>
    </div>
    <div class="pp-right">
        <div class="pp-btn btn btn-primary"><?php _e('Принять'); ?></div>
    </div>
</div>
    <script>
	if (typeof getCookie == 'undefined') {
		function getCookie(name) {
			var matches = document.cookie.match(new RegExp(
			"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
			));
			return matches ? decodeURIComponent(matches[1]) : undefined;
		}
	}
    jQuery(document).ready(function() {
		function submitButton(btnName) {
		if (jQuery(btnName)) {
			jQuery(btnName).each(function() {
			var but = jQuery(this);
			jQuery('<p class="ppcf7_alert"><?php _e('Нажимая кнопку'); ?> "'+but.val()+'", <?php _e('Вы автоматически соглашаетесь с'); ?>  <a title="<?php _e('Политика конфиденциальности'); ?>" rel="nofollow" target="_blank" href="/privacy-policy"><?php _e('политикой конфиденциальности'); ?></a> <?php _e('и даете свое согласие на обработку персональных данных. Ваши данные не будут переданы третьим лицам'); ?>. </p>').insertAfter(but);
			});
		}
		}
		<?php if (apply_filters('ppcf7_show_under_cf7', get_option('ppcf7_how', 'all') != 'popup')) : ?>
		submitButton('.wpcf7-submit');
		submitButton('#commentform #submit');
		<?php endif; ?>

	<?php if (apply_filters('ppcf7_show_popup', get_option('ppcf7_how', 'all') != 'form')) : ?>	
    date = new Date(new Date().getTime() + 86400 * 1000 * <?php echo get_option('ppcf7_remember', 30); ?>);
    jQuery('.pp-btn').click(function() {
        jQuery('.pp-cookies-notice').slideUp(700);
        document.cookie = 'pp-submit=ok; path=/; expires='+date.toUTCString();
    });
	if (typeof getCookie !== 'undefined' && getCookie('pp-submit') == 'ok') {
		jQuery('.pp-cookies-notice').remove();
	}
	else {
		setTimeout(function() {jQuery('.pp-cookies-notice').slideDown(700)},1000);
	}
	<?php endif; ?>
    });
    </script>
    <?php
}
}

function ppcf7_notice__error() {
    $post_name = get_page_by_path('privacy-policy');
    $class = 'notice notice-error';
    $message = __( 'Заполните обязательные поля для создания политики конфиденциальности на сайте на <a href="'.site_url().'/wp-admin/admin.php?page=russian-privacy-policy-page-and-notice%2Fprivacy-policy-cf7.php">странице настроек плагина</a>' );
    if (!$post_name) {

    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ),  $message ); 
    }
}
add_action( 'admin_notices', 'ppcf7_notice__error' );
add_action( 'wp_footer', 'ppcf7_scripts' );
add_action('wp_head', 'ppcf7_styles');

?>