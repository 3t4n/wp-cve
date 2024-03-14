<style type="text/css">
	.sprite { position: relative; float: left; margin-right: 5px; }
	.sprite-YandexVerification { width: 16px; height: 16px; background:url(<?php echo plugins_url('dl-verification/sprites.png') ?>) no-repeat -0px -0px; }
	.sprite-GooglVerification { width: 16px; height: 16px; background:url(<?php echo plugins_url('dl-verification/sprites.png') ?>) no-repeat -0px -16px; }
	.sprite-BingVerification { width: 16px; height: 16px; background:url(<?php echo plugins_url('dl-verification/sprites.png') ?>) no-repeat -0px -32px; }
	.sprite-YandexMetrika { width: 16px; height: 16px; background:url(<?php echo plugins_url('dl-verification/sprites.png') ?>) no-repeat -0px -48px; }
	.sprite-GoogleAnalytics { width: 16px; height: 16px; background:url(<?php echo plugins_url('dl-verification/sprites.png') ?>) no-repeat -0px -64px; }
	.sprite-MailVerification { width: 16px; height: 16px; background:url(<?php echo plugins_url('dl-verification/sprites.png') ?>) no-repeat -0px -80px; }
	.sprite-MailTop { width: 16px; height: 16px; background:url(<?php echo plugins_url('dl-verification/sprites.png') ?>) no-repeat -0px -96px; }
</style>
<div class="wrap">

<h2>DL Verification</h2>

<ul class="subsubsub">
	<li><a href="#yaweb"> Яндекс.Вебмастер</a> | </li>
	<li><a href="#yametrika" > Яндекс.Метрика</a> | </li>
	<li><a href="#googleverification"> Google нструменты</a> | </li>
	<li><a href="#googleanalytics"> Google Analytics</a> | </li>
	<li><a href="#mailweb"> Mail веб-мастер</a> | </li>
	<li><a href="#mail"> Рейтинг Mail</a> | </li>
	<li><a href="#bing"> Bing веб-мастер</a> | </li>
	<li><a href="#liveinternet"> LiveInternet</a></li>
</ul>

<form method="post" action="options.php">

	<div id="yaweb"></div>

    <?php settings_fields( 'dl-settings-group' ); ?>
	
	<?php settings_errors(); ?>	
	
    <table class="form-table">
    
	<tr valign="top">
        <th scope="row" id="yaweb"><div class="sprite sprite-YandexVerification"></div> Яндекс.Вебмастер </th>
        <td><input 
			type="text" 
			name="YandexVerification"
			placeholder="мета-тэг"
			value="<?php echo get_option('YandexVerification'); ?>" />
		<code>&lt;meta name="yandex-verification" content="<?php echo get_option('YandexVerification'); ?>" /&gt;</code> - этот код добавлен в head сайта
		<p class="description"><b>Яндекс.Вебмастер</b> — это сервис, предоставляющий информацию о том, как индексируются ваши сайты. Он позволяет сообщить Яндексу о новых и удаленных страницах, настроить индексирование сайта и улучшить представление сайта в результатах поиска.</p>
		<p>
			<a href="http://webmaster.yandex.ru/sites/" target="_blank">Мои сайты</a> | 
			<a href="http://webmaster.yandex.ru/site/add.xml" target="_blank">Добавить сайт</a>
		</p>
	<div id="yametrika"></div>	
	</td></tr>

	
	<tr valign="top">
		<th scope="row" id="metrika"><div class="sprite sprite-YandexMetrika"></div> Яндекс.Метрика</th>
		<td><textarea 
			name="YandexMetrika" 
			class="large-text code" 
			type="textarea" 
			rows="5"
			placeholder="HTML-код счетчика"><?php echo get_option('YandexMetrika'); ?></textarea>
		<p class="description"><b>Яндекс.Метрика</b> — бесплатный инструмент для повышения конверсии сайта. Наблюдайте за ключевыми показателями эффективности сайта, анализируйте поведение посетителей, оценивайте отдачу от рекламных кампаний.</p>
		<p>
			<a href="https://metrika.yandex.ru/list/" target="_blank">Мои cчётчики</a> | 
			<a href="https://metrika.yandex.ru/add/" target="_blank">Добавить счётчик</a> | 
			<a href="https://metrika.yandex.ru/stat/?counter_id=5265289" target="_blank">Демо-доступ без регистрации</a>
		</p>
	<div id="googleverification"></div>	
	</td></tr>
	
	
    <tr valign="top" id="googleverification">
        <th scope="row"><div class="sprite sprite-GooglVerification"></div> Google нструменты</th>
        <td><input 
			type="text" 
			name="GooglVerification" 
			placeholder="мета-тэг"
			value="<?php echo get_option('GooglVerification'); ?>" />
		<code>&lt;meta name="google-site-verification" content="<?php echo get_option('GooglVerification'); ?>" /&gt;</code> - этот код добавлен в head сайта
		<p class="description"><b>Google Инструменты</b> для веб-мастеров предоставляют подробные отчеты о частоте показа ваших страниц в Google.</p>
		<p><a href="https://www.google.com/webmasters/tools/" target="_blank">Мои сайты</a></p>
	<div id="googleanalytics"></div>	
	</td></tr>

	
	<tr valign="top">
		<th scope="row" id="googleanalytics"><div class="sprite sprite-GoogleAnalytics"></div> Google Analytics</th>
		<td><textarea 
			name="GoogleAnalytics" 
			class="large-text code" 
			type="textarea" 
			rows="5"
			placeholder="HTML-код счетчика"><?php echo get_option('GoogleAnalytics'); ?></textarea>
		<p class="description"><b>Google Analytics</b> позволяет оценивать рентабельность инвестиций, отслеживать Flash- и видеорекламу, а также распространение контента в приложениях и социальных сетях.</p>
		<p>
			<a href="https://www.google.com/analytics/web/" target="_blank">Все аккаунты</a>
		</p>
	<div id="mailweb"></div>	
	</td></tr>

	
    <tr valign="top">
        <th scope="row"><div class="sprite sprite-MailVerification"></div> Mail веб-мастер</th>
        <td><input 
			type="text" 
			name="MailVerification" 
			placeholder="мета-тэг"
			value="<?php echo get_option('MailVerification'); ?>" />
		<code>&lt;meta name="wmail-verification" content="<?php echo get_option('MailVerification'); ?>" /&gt;</code> - этот код добавлен в head сайта
		<p class="description"><b>Кабинет вебмастера Mail</b> — сервис, позволяющий взглянуть на ваш сайт глазами Поиска@Mail.Ru и сделать его более привлекательными для пользователей</p>
		<p>
			<a href="http://webmaster.mail.ru/" target="_blank">Мои сайты</a>
		</p>
	<div id="mail"></div>	
	</td></tr>	


	<tr valign="top">
		<th scope="row"><div class="sprite sprite-MailTop"></div> Рейтинг Mail</th>
		<td><textarea 
			name="MailTop" 
			class="large-text code" 
			type="textarea" 
			rows="5"
			placeholder="HTML-код счетчика"><?php echo get_option('MailTop'); ?></textarea>
		<p class="description"><b>Рейтинг Mail.ru</b> — это система статистики для владельцев сайтов. Для сбора данных необходимо получить и установить на страницы вашего сайта код счетчика.</p>
		<p>
			<a href="https://top.mail.ru/mycounters" target="_blank">Мои счетчики </a> | 
			<a href="http://top.mail.ru/add" target="_blank">Зарегистрировать сайт</a>
		</p>
		<div id="bing"></div>
	</td></tr>

	
    <tr valign="top">
        <th scope="row"><div class="sprite sprite-BingVerification"></div> Bing веб-мастер</th>
        <td><input 
			type="text" 
			name="BingVerification" 
			placeholder="мета-тэг"
			value="<?php echo get_option('BingVerification'); ?>" />
		<code>&lt;meta name="msvalidate.01" content="<?php echo get_option('BingVerification'); ?>" /&gt;</code> - этот код добавлен в head сайта
		<p class="description"><b>Bing Webmaster Tools</b> — средства веб-мастера, позволяющие собирать статистику, анализировать трафик, следить за индексацией, отслеживать ссылки, удалять или добавлять страницы.</p>
		<p><a href="https://ssl.bing.com/webmaster/home/" target="_blank">Мои сайты</a> | <a href="https://ssl.bing.com/webmaster/home/addsite" target="_blank">Добавить сайт</a></p>
	<div id="liveinternet"></div>
	</td>
	</tr>

	
	<tr valign="top">
        <th scope="row"><img src="http://www.liveinternet.ru/favicon.ico" /> LiveInternet</th>
        <td>
		<textarea 
			name="LiveInternetVerification" 
			class="large-text code" 
			type="textarea" 
			rows="5" 
			placeholder="HTML-код счетчика"><?php echo get_option('LiveInternetVerification'); ?></textarea>
		<textarea 
			name="LiveInternetIMGVerification" 
			class="large-text code" 
			type="textarea" 
			rows="5" 
			placeholder="HTML-код логотипа"><?php echo get_option('LiveInternetIMGVerification'); ?></textarea>
		<p class="description"><b>LiveInternet</b> — ведущий сервис статистики Интернет ресурсов, предоставляющий наиболее качественные инструменты сбора, обработки и последующего анализа данных посещаемости Интернет-ресурсов. Широкий спектр возможностей обработки данных, графическое отображение результатов и удобный интерфейс, заслужили признание множества профессионалов, которые каждый день имеют дело с Интернет статистикой.</p>
		
		<p class="description"><b>Выберите тип счетчика</b> в виде двух картинок, одна из которых является счётчиком (прозрачный GIF размером 1x1), а другая - логотипом LiveInternet. Данный способ размещения позволит вам вставить код невидимого счетчика в начале страницы, а логотип - там, где позволяет дизайн и содержимое страницы.</p>
		
		<p class="description">Для размещения логотипа liveinternet.ru используйте шорткод<strong><code style="font-style: normal">[dlliveinternet]</code></strong> или в теме сайта <strong><code style="font-style: normal">&lt;? echo do_shortcode('[dlliveinternet]'); ?&gt;</code></strong></p>
		
		<p><a href="http://www.liveinternet.ru/stat/<? echo $_SERVER['SERVER_NAME']; ?>" target="_blank">Статистика сайта</a> | <a href="http://www.liveinternet.ru/add" target="_blank">Добавить счетчик</a> | <a href="http://www.liveinternet.ru/rating" target="_blank">Рейтинг сайтов</a></p>
		
	</td></tr>
	
	</table>
    
    <p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
</div>