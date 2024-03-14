<?php die();?>
﻿file sape.php from sape.ru (v1.0.8 02.09.2010) 27.12.2010itex_imoney_datafiles_delimiter_2sape.phpitex_imoney_datafiles_delimiter_2<?php
    /**
     * SAPE.ru - Интеллектуальная система купли-продажи ссылок
     *
     * PHP-клиент
     *
     * Вебмастеры! Не нужно ничего менять в этом файле!
     * Все настройки - через параметры при вызове кода.
     * Читайте: http://help.sape.ru/
     *
     * По всем вопросам обращайтесь на support@sape.ru
     *
     * class SAPE_base 				- базовый класс
     * class SAPE_client 			- класс для вывода обычных ссылок
     * class SAPE_client_context 	- класс для вывода контекстных сссылок
     * class SAPE_articles 			- класс для вывода статей
     *
     * @version 1.1.6 от 27.01.2012
     */

    /**
     * Основной класс, выполняющий всю рутину
     */
    class SAPE_base {

        var $_version = '1.1.6';

        var $_verbose = false;

        var $_charset = ''; // http://www.php.net/manual/en/function.iconv.php

        var $_sape_charset = '';

        var $_server_list = array('dispenser-01.sape.ru', 'dispenser-02.sape.ru');

        var $_cache_lifetime = 3600; // Пожалейте наш сервер :о)

        // Если скачать базу ссылок не удалось, то следующая попытка будет через столько секунд
        var $_cache_reloadtime = 600;

        var $_error = '';

        var $_host = '';

        var $_request_uri = '';

        var $_multi_site = false;

        var $_fetch_remote_type = ''; // Способ подключения к удалённому серверу [file_get_contents|curl|socket]

        var $_socket_timeout = 6; // Сколько ждать ответа

        var $_force_show_code = false;

        var $_is_our_bot = false; // Если наш робот

        var $_debug = false;

        var $_ignore_case = false; // Регистронезависимый режим работы, использовать только на свой страх и риск

        var $_db_file = ''; // Путь к файлу с данными

        var $_use_server_array = false; // Откуда будем брать uri страницы: $_SERVER['REQUEST_URI'] или getenv('REQUEST_URI')

        var $_force_update_db = false;

        var $_is_block_css_showed = false; // Флаг для отрисовки css в блочных ссылках

        var $_is_block_ins_beforeall_showed = false;

        function SAPE_base($options = null) {

            // Поехали :o)

            $host = '';

            if (is_array($options)) {
                if (isset($options['host'])) {
                    $host = $options['host'];
                }
            } elseif (strlen($options)) {
                $host = $options;
                $options = array();
            } else {
                $options = array();
            }

            if (isset($options['use_server_array']) && $options['use_server_array'] == true) {
                $this->_use_server_array = true;
            }

            // Какой сайт?
            if (strlen($host)) {
                $this->_host = $host;
            } else {
                $this->_host = $_SERVER['HTTP_HOST'];
            }

            $this->_host = preg_replace('/^http:\/\//', '', $this->_host);
            $this->_host = preg_replace('/^www\./', '', $this->_host);

            // Какая страница?
            if (isset($options['request_uri']) && strlen($options['request_uri'])) {
                $this->_request_uri = $options['request_uri'];
            } elseif ($this->_use_server_array === false) {
                $this->_request_uri = getenv('REQUEST_URI');
            }

            if (strlen($this->_request_uri) == 0) {
                $this->_request_uri = $_SERVER['REQUEST_URI'];
            }

            // На случай, если хочется много сайтов в одной папке
            if (isset($options['multi_site']) && $options['multi_site'] == true) {
                $this->_multi_site = true;
            }

            // Выводить информацию о дебаге
            if (isset($options['debug']) && $options['debug'] == true) {
                $this->_debug = true;
            }

            // Определяем наш ли робот
            if (isset($_COOKIE['sape_cookie']) && ($_COOKIE['sape_cookie'] == _SAPE_USER)) {
                $this->_is_our_bot = true;
                if (isset($_COOKIE['sape_debug']) && ($_COOKIE['sape_debug'] == 1)) {
                    $this->_debug = true;
                    //для удобства дебега саппортом
                    $this->_options = $options;
                    $this->_server_request_uri = $this->_request_uri = $_SERVER['REQUEST_URI'];
                    $this->_getenv_request_uri = getenv('REQUEST_URI');
                    $this->_SAPE_USER = _SAPE_USER;
                }
                if (isset($_COOKIE['sape_updatedb']) && ($_COOKIE['sape_updatedb'] == 1)) {
                    $this->_force_update_db = true;
                }
            } else {
                $this->_is_our_bot = false;
            }

            // Сообщать об ошибках
            if (isset($options['verbose']) && $options['verbose'] == true || $this->_debug) {
                $this->_verbose = true;
            }

            // Кодировка
            if (isset($options['charset']) && strlen($options['charset'])) {
                $this->_charset = $options['charset'];
            } else {
                $this->_charset = 'windows-1251';
            }

            if (isset($options['fetch_remote_type']) && strlen($options['fetch_remote_type'])) {
                $this->_fetch_remote_type = $options['fetch_remote_type'];
            }

            if (isset($options['socket_timeout']) && is_numeric($options['socket_timeout']) && $options['socket_timeout'] > 0) {
                $this->_socket_timeout = $options['socket_timeout'];
            }

            // Всегда выводить чек-код
            if (isset($options['force_show_code']) && $options['force_show_code'] == true) {
                $this->_force_show_code = true;
            }

            if (!defined('_SAPE_USER')) {
                return $this->raise_error('Не задана константа _SAPE_USER');
            }

            //Не обращаем внимания на регистр ссылок
            if (isset($options['ignore_case']) && $options['ignore_case'] == true) {
                $this->_ignore_case = true;
                $this->_request_uri = strtolower($this->_request_uri);
            }
        }

        /**
         * Функция для подключения к удалённому серверу
         */
        function fetch_remote_file($host, $path, $specifyCharset = false) {

            $user_agent = $this->_user_agent . ' ' . $this->_version;

            @ini_set('allow_url_fopen', 1);
            @ini_set('default_socket_timeout', $this->_socket_timeout);
            @ini_set('user_agent', $user_agent);
            if (
                $this->_fetch_remote_type == 'file_get_contents'
                ||
                (
                    $this->_fetch_remote_type == ''
                    &&
                    function_exists('file_get_contents')
                    &&
                    ini_get('allow_url_fopen') == 1
                )
            ) {
                $this->_fetch_remote_type = 'file_get_contents';

                if($specifyCharset && function_exists('stream_context_create')) {
                    $opts = array(
                        'http' => array(
                            'method' => 'GET',
                            'header' => 'Accept-Charset: '. $this->_charset. "\r\n"
                        )
                    );
                    $context = @stream_context_create($opts);
                    if ($data = @file_get_contents('http://' . $host . $path, null, $context)) {
                        return $data;
                    }
                } else {
                    if ($data = @file_get_contents('http://' . $host . $path)) {
                        return $data;
                    }
                }

            } elseif (
                $this->_fetch_remote_type == 'curl'
                ||
                (
                    $this->_fetch_remote_type == ''
                    &&
                    function_exists('curl_init')
                )
            ) {
                $this->_fetch_remote_type = 'curl';
                if ($ch = @curl_init()) {

                    @curl_setopt($ch, CURLOPT_URL, 'http://' . $host . $path);
                    @curl_setopt($ch, CURLOPT_HEADER, false);
                    @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    @curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->_socket_timeout);
                    @curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
                    if($specifyCharset) {
                        @curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Charset: '. $this->_charset));
                    }

                    $data = @curl_exec($ch);
                    @curl_close($ch);

                    if ($data) {
                        return $data;
                    }
                }

            } else {
                $this->_fetch_remote_type = 'socket';
                $buff = '';
                $fp = @fsockopen($host, 80, $errno, $errstr, $this->_socket_timeout);
                if ($fp) {
                    @fputs($fp, "GET {$path} HTTP/1.0\r\nHost: {$host}\r\n");
                    if($specifyCharset) {
                        @fputs($fp, "Accept-Charset: {$this->_charset}\r\n");
                    }
                    @fputs($fp, "User-Agent: {$user_agent}\r\n\r\n");
                    while (!@feof($fp)) {
                        $buff .= @fgets($fp, 128);
                    }
                    @fclose($fp);

                    $page = explode("\r\n\r\n", $buff);
                    unset($page[0]);
                    return implode("\r\n\r\n", $page);
                }

            }

            return $this->raise_error('Не могу подключиться к серверу: ' . $host . $path . ', type: ' . $this->_fetch_remote_type);
        }

        /**
         * Функция чтения из локального файла
         */
        function _read($filename) {

            $fp = @fopen($filename, 'rb');
            @flock($fp, LOCK_SH);
            if ($fp) {
                clearstatcache();
                $length = @filesize($filename);
                $mqr = @get_magic_quotes_runtime();
                @set_magic_quotes_runtime(0);
                if ($length) {
                    $data = @fread($fp, $length);
                } else {
                    $data = '';
                }
                @set_magic_quotes_runtime($mqr);
                @flock($fp, LOCK_UN);
                @fclose($fp);

                return $data;
            }

            return $this->raise_error('Не могу считать данные из файла: ' . $filename);
        }

        /**
         * Функция записи в локальный файл
         */
        function _write($filename, $data) {

            $fp = @fopen($filename, 'ab');
            if ($fp) {
                if (flock($fp, LOCK_EX | LOCK_NB)) {
                    ftruncate($fp, 0);
                    $mqr = @get_magic_quotes_runtime();
                    @set_magic_quotes_runtime(0);
                    @fwrite($fp, $data);
                    @set_magic_quotes_runtime($mqr);
                    @flock($fp, LOCK_UN);
                    @fclose($fp);

                    if (md5($this->_read($filename)) != md5($data)) {
                        @unlink($filename);
                        return $this->raise_error('Нарушена целостность данных при записи в файл: ' . $filename);
                    }
                } else {
                    return false;
                }

                return true;
            }

            return $this->raise_error('Не могу записать данные в файл: ' . $filename);
        }

        /**
         * Функция обработки ошибок
         */
        function raise_error($e) {

            $this->_error = '<p style="color: red; font-weight: bold;">SAPE ERROR: ' . $e . '</p>';

            if ($this->_verbose == true) {
                print $this->_error;
            }

            return false;
        }

        /**
         * Загрузка данных
         */
        function load_data() {
            $this->_db_file = $this->_get_db_file();

            if (!is_file($this->_db_file)) {
                // Пытаемся создать файл.
                if (@touch($this->_db_file)) {
                    @chmod($this->_db_file, 0666); // Права доступа
                } else {
                    return $this->raise_error('Нет файла ' . $this->_db_file . '. Создать не удалось. Выставите права 777 на папку.');
                }
            }

            if (!is_writable($this->_db_file)) {
                return $this->raise_error('Нет доступа на запись к файлу: ' . $this->_db_file . '! Выставите права 777 на папку.');
            }

            @clearstatcache();

            $data = $this->_read($this->_db_file);
            if (
                $this->_force_update_db
                || (
                    !$this->_is_our_bot
                    &&
                    (
                        filemtime($this->_db_file) < (time() - $this->_cache_lifetime)
                        ||
                        filesize($this->_db_file) == 0
                        ||
                        @unserialize($data) == false
                    )
                )
            ) {
                // Чтобы не повесить площадку клиента и чтобы не было одновременных запросов
                @touch($this->_db_file, (time() - $this->_cache_lifetime + $this->_cache_reloadtime));

                $path = $this->_get_dispenser_path();
                if (strlen($this->_charset)) {
                    $path .= '&charset=' . $this->_charset;
                }

                foreach ($this->_server_list as $i => $server) {
                    if ($data = $this->fetch_remote_file($server, $path)) {
                        if (substr($data, 0, 12) == 'FATAL ERROR:') {
                            $this->raise_error($data);
                        } else {
                            // [псевдо]проверка целостности:
                            $hash = @unserialize($data);
                            if ($hash != false) {
                                // попытаемся записать кодировку в кеш
                                $hash['__sape_charset__'] = $this->_charset;
                                $hash['__last_update__'] = time();
                                $hash['__multi_site__'] = $this->_multi_site;
                                $hash['__fetch_remote_type__'] = $this->_fetch_remote_type;
                                $hash['__ignore_case__'] = $this->_ignore_case;
                                $hash['__php_version__'] = phpversion();
                                $hash['__server_software__'] = $_SERVER['SERVER_SOFTWARE'];

                                $data_new = @serialize($hash);
                                if ($data_new) {
                                    $data = $data_new;
                                }

                                $this->_write($this->_db_file, $data);
                                break;
                            }
                        }
                    }
                }
            }

            // Убиваем PHPSESSID
            if (strlen(session_id())) {
                $session = session_name() . '=' . session_id();
                $this->_request_uri = str_replace(array('?' . $session, '&' . $session), '', $this->_request_uri);
            }

            $this->set_data(@unserialize($data));
        }
    }

    /**
     * Класс для работы с обычными ссылками
     */
    class SAPE_client extends SAPE_base {

        var $_links_delimiter = '';
        var $_links = array();
        var $_links_page = array();
        var $_user_agent = 'SAPE_Client PHP';

        function SAPE_client($options = null) {
            parent::SAPE_base($options);
            $this->load_data();
        }

        /**
         * Обработка html для массива ссылок
         *
         * @param string $html
         * @return string
         */
        function _return_array_links_html($html, $options = null) {

            if(empty($options)) {
                $options = array();
            }

            // если запрошена определенная кодировка, и известна кодировка кеша, и они разные, конвертируем в заданную
            if (
                strlen($this->_charset) > 0
                &&
                strlen($this->_sape_charset) > 0
                &&
                $this->_sape_charset != $this->_charset
                &&
                function_exists('iconv')
            ) {
                $new_html = @iconv($this->_sape_charset, $this->_charset, $html);
                if ($new_html) {
                    $html = $new_html;
                }
            }

            if ($this->_is_our_bot) {

                $html = '<sape_noindex>' . $html . '</sape_noindex>';

                if(isset($options['is_block_links']) && true == $options['is_block_links']) {

                    if(!isset($options['nof_links_requested'])) {
                        $options['nof_links_requested'] = 0;
                    }
                    if(!isset($options['nof_links_displayed'])) {
                        $options['nof_links_displayed'] = 0;
                    }
                    if(!isset($options['nof_obligatory'])) {
                        $options['nof_obligatory'] = 0;
                    }
                    if(!isset($options['nof_conditional'])) {
                        $options['nof_conditional'] = 0;
                    }

                    $html = '<sape_block nof_req="' . $options['nof_links_requested'] .
                        '" nof_displ="' . $options['nof_links_displayed'] .
                        '" nof_oblig="' . $options['nof_obligatory'] .
                        '" nof_cond="' . $options['nof_conditional'] .
                        '">' . $html .
                        '</sape_block>';
                }
            }

            return $html;
        }

        /**
         * Финальная обработка html перед выводом ссылок
         *
         * @param string $html
         * @return string
         */
        function _return_html($html) {

            if ($this->_debug) {
                $html .= print_r($this, true);
            }

            return $html;
        }

        /**
         * Вывод ссылок в виде блока
         *
         * @param int $n Количествово
         * @param int $offset Смещение
         * @param array $options Опции
         *
         * <code>
         * $options = array();
         * $options['block_no_css'] = (false|true);
         * // Переопределяет запрет на вывод css в коде страницы: false - выводить css
         * $options['block_orientation'] = (1|0);
         * // Переопределяет ориентацию блока: 1 - горизонтальная, 0 - вертикальная
         * $options['block_width'] = ('auto'|'[?]px'|'[?]%'|'[?]');
         * // Переопределяет ширину блока:
         * // 'auto'  - определяется шириной блока-предка с фиксированной шириной,
         * // если такового нет, то займет всю ширину
         * // '[?]px' - значение в пикселях
         * // '[?]%'  - значение в процентах от ширины блока-предка с фиксированной шириной
         * // '[?]'   - любое другое значение, которое поддерживается спецификацией CSS
         * </code>
         *
         * @return string
         */
        function return_block_links($n = null, $offset = 0, $options = null) {

            // Объединить параметры
            if(empty($options)) {
                $options = array();
            }

            $defaults = array();
            $defaults['block_no_css'] 		= false;
            $defaults['block_orientation'] 	= 1;
            $defaults['block_width'] 		= '';

            $ext_options = array();
            if(isset($this->_block_tpl_options) && is_array($this->_block_tpl_options)) {
                $ext_options = $this->_block_tpl_options;
            }

            $options = array_merge($defaults, $ext_options, $options);

            // Ссылки переданы не массивом (чек-код) => выводим как есть + инфо о блоке
            if (!is_array($this->_links_page)) {
                $html = $this->_return_array_links_html('', array('is_block_links' => true));
                return $this->_return_html($this->_links_page . $html);
            }
            // Не переданы шаблоны => нельзя вывести блоком - ничего не делать
            elseif(!isset($this->_block_tpl)) {
                return $this->_return_html('');
            }

            // Определим нужное число элементов в блоке

            $total_page_links = count($this->_links_page);

            $need_show_obligatory_block = false;
            $need_show_conditional_block = false;
            $n_requested = 0;

            if(isset($this->_block_ins_itemobligatory)) {
                $need_show_obligatory_block = true;
            }

            if(is_numeric($n) && $n >= $total_page_links) {

                $n_requested = $n;

                if(isset($this->_block_ins_itemconditional)) {
                    $need_show_conditional_block = true;
                }
            }

            if (!is_numeric($n) || $n > $total_page_links) {
                $n = $total_page_links;
            }

            // Выборка ссылок
            $links = array();
            for ($i = 1; $i <= $n; $i++) {
                if ($offset > 0 && $i <= $offset) {
                    array_shift($this->_links_page);
                } else {
                    $links[] = array_shift($this->_links_page);
                }
            }

            $html = '';

            // Подсчет числа опциональных блоков
            $nof_conditional = 0;
            if(count($links) < $n_requested && true == $need_show_conditional_block) {
                $nof_conditional = $n_requested - count($links);
            }

            //Если нет ссылок и нет вставных блоков, то ничего не выводим
            if(empty($links) && $need_show_obligatory_block == false && $nof_conditional == 0) {

                $return_links_options = array(
                    'is_block_links' 		=> true,
                    'nof_links_requested' 	=> $n_requested,
                    'nof_links_displayed'	=> 0,
                    'nof_obligatory'		=> 0,
                    'nof_conditional'		=> 0
                );

                $html = $this->_return_array_links_html($html, $return_links_options);

                return $this->_return_html($html);
            }

            // Делаем вывод стилей, только один раз. Или не выводим их вообще, если так задано в параметрах
            if (!$this->_is_block_css_showed && false == $options['block_no_css']) {
                $html .= $this->_block_tpl['css'];
                $this->_is_block_css_showed = true;
            }

            // Вставной блок в начале всех блоков
            if (isset($this->_block_ins_beforeall) && !$this->_is_block_ins_beforeall_showed){
                $html .= $this->_block_ins_beforeall;
                $this->_is_block_ins_beforeall_showed = true;
            }

            // Вставной блок в начале блока
            if (isset($this->_block_ins_beforeblock)){
                $html .= $this->_block_ins_beforeblock;
            }

            // Получаем шаблоны в зависимости от ориентации блока
            $block_tpl_parts = $this->_block_tpl[$options['block_orientation']];

            $block_tpl 			= $block_tpl_parts['block'];
            $item_tpl 			= $block_tpl_parts['item'];
            $item_container_tpl = $block_tpl_parts['item_container'];
            $item_tpl_full 		= str_replace('{item}', $item_tpl, $item_container_tpl);
            $items 				= '';

            $nof_items_total = count($links);
            foreach ($links as $link){

                preg_match('#<a href="(https?://([^"/]+)[^"]*)"[^>]*>[\s]*([^<]+)</a>#i', $link, $link_item);

                if (function_exists('mb_strtoupper') && strlen($this->_sape_charset) > 0) {
                    $header_rest = mb_substr($link_item[3], 1, mb_strlen($link_item[3], $this->_sape_charset) - 1, $this->_sape_charset);
                    $header_first_letter = mb_strtoupper(mb_substr($link_item[3], 0, 1, $this->_sape_charset), $this->_sape_charset);
                    $link_item[3] = $header_first_letter . $header_rest;
                } elseif(function_exists('ucfirst') && (strlen($this->_sape_charset) == 0 || strpos($this->_sape_charset, '1251') !== false) ) {
                    $link_item[3][0] = ucfirst($link_item[3][0]);
                }

                // Если есть раскодированный URL, то заменить его при выводе

                if(isset($this->_block_uri_idna) && isset($this->_block_uri_idna[$link_item[2]])) {
                    $link_item[2] = $this->_block_uri_idna[$link_item[2]];
                }

                $item = $item_tpl_full;
                $item = str_replace('{header}', $link_item[3], $item);
                $item = str_replace('{text}', trim($link), $item);
                $item = str_replace('{url}', $link_item[2], $item);
                $item = str_replace('{link}', $link_item[1], $item);
                $items .= $item;
            }

            // Вставной обязатльный элемент в блоке
            if(true == $need_show_obligatory_block) {
                $items .= str_replace('{item}', $this->_block_ins_itemobligatory, $item_container_tpl);
                $nof_items_total += 1;
            }

            // Вставные опциональные элементы в блоке
            if($need_show_conditional_block == true && $nof_conditional > 0) {
                for($i = 0; $i < $nof_conditional; $i++) {
                    $items .= str_replace('{item}', $this->_block_ins_itemconditional, $item_container_tpl);
                }
                $nof_items_total += $nof_conditional;
            }

            if ($items != ''){
                $html .= str_replace('{items}', $items, $block_tpl);

                // Проставляем ширину, чтобы везде одинковая была
                if ($nof_items_total > 0){
                    $html = str_replace('{td_width}', round(100/$nof_items_total), $html);
                } else {
                    $html = str_replace('{td_width}', 0, $html);
                }

                // Если задано, то переопределить ширину блока
                if(isset($options['block_width']) && !empty($options['block_width'])) {
                    $html = str_replace('{block_style_custom}', 'style="width: ' . $options['block_width'] . '!important;"', $html);
                }
            }

            unset($block_tpl_parts, $block_tpl, $items, $item, $item_tpl, $item_container_tpl);

            // Вставной блок в конце блока
            if (isset($this->_block_ins_afterblock)){
                $html .= $this->_block_ins_afterblock;
            }

            //Заполняем оставшиеся модификаторы значениями
            unset($options['block_no_css'], $options['block_orientation'], $options['block_width']);

            $tpl_modifiers = array_keys($options);
            foreach($tpl_modifiers as $k=>$m) {
                $tpl_modifiers[$k] = '{' . $m . '}';
            }
            unset($m, $k);

            $tpl_modifiers_values =  array_values($options);

            $html = str_replace($tpl_modifiers, $tpl_modifiers_values, $html);
            unset($tpl_modifiers, $tpl_modifiers_values);

            //Очищаем незаполненные модификаторы
            $clear_modifiers_regexp = '#\{[a-z\d_\-]+\}#';
            $html = preg_replace($clear_modifiers_regexp, ' ', $html);

            $return_links_options = array(
                'is_block_links' 		=> true,
                'nof_links_requested' 	=> $n_requested,
                'nof_links_displayed'	=> $n,
                'nof_obligatory'		=> ($need_show_obligatory_block == true ? 1 : 0),
                'nof_conditional'		=> $nof_conditional
            );

            $html = $this->_return_array_links_html($html, $return_links_options);

            return $this->_return_html($html);
        }

        /**
         * Вывод ссылок в обычном виде - текст с разделителем
         *
         * @param int $n Количествово
         * @param int $offset Смещение
         * @param array $options Опции
         *
         * <code>
         * $options = array();
         * $options['as_block'] = (false|true);
         * // Показывать ли ссылки в виде блока
         * </code>
         *
         * @see return_block_links()
         * @return string
         */
        function return_links($n = null, $offset = 0, $options = null) {

            //Опрелелить, как выводить ссылки
            $as_block = $this->_show_only_block;

            if(is_array($options) && isset($options['as_block']) && false == $as_block) {
                $as_block = $options['as_block'];
            }

            if(true == $as_block && isset($this->_block_tpl)) {
                return $this->return_block_links($n, $offset, $options);
            }

            //-------

            if (is_array($this->_links_page)) {

                $total_page_links = count($this->_links_page);

                if (!is_numeric($n) || $n > $total_page_links) {
                    $n = $total_page_links;
                }

                $links = array();

                for ($i = 1; $i <= $n; $i++) {
                    if ($offset > 0 && $i <= $offset) {
                        array_shift($this->_links_page);
                    } else {
                        $links[] = array_shift($this->_links_page);
                    }
                }

                $html = join($this->_links_delimiter, $links);

                // если запрошена определенная кодировка, и известна кодировка кеша, и они разные, конвертируем в заданную
                if (
                    strlen($this->_charset) > 0
                    &&
                    strlen($this->_sape_charset) > 0
                    &&
                    $this->_sape_charset != $this->_charset
                    &&
                    function_exists('iconv')
                ) {
                    $new_html = @iconv($this->_sape_charset, $this->_charset, $html);
                    if ($new_html) {
                        $html = $new_html;
                    }
                }

                if ($this->_is_our_bot) {
                    $html = '<sape_noindex>' . $html . '</sape_noindex>';
                }
            } else {
                $html = $this->_links_page;
                if ($this->_is_our_bot) {
                    $html .= '<sape_noindex></sape_noindex>';
                }
            }

            if ($this->_debug) {
                $html .= print_r($this, true);
            }

            return $html;
        }

        function _get_db_file() {
            if ($this->_multi_site) {
                return dirname(__FILE__) . '/' . $this->_host . '.links.db';
            } else {
                return dirname(__FILE__) . '/links.db';
            }
        }

        function _get_dispenser_path() {
            return '/code.php?user=' . _SAPE_USER . '&host=' . $this->_host;
        }

        function set_data($data) {
            if ($this->_ignore_case) {
                $this->_links = array_change_key_case($data);
            } else {
                $this->_links = $data;
            }
            if (isset($this->_links['__sape_delimiter__'])) {
                $this->_links_delimiter = $this->_links['__sape_delimiter__'];
            }
            // определяем кодировку кеша
            if (isset($this->_links['__sape_charset__'])) {
                $this->_sape_charset = $this->_links['__sape_charset__'];
            } else {
                $this->_sape_charset = '';
            }
            if (@array_key_exists($this->_request_uri, $this->_links) && is_array($this->_links[$this->_request_uri])) {
                $this->_links_page = $this->_links[$this->_request_uri];
            } else {
                if (isset($this->_links['__sape_new_url__']) && strlen($this->_links['__sape_new_url__'])) {
                    if ($this->_is_our_bot || $this->_force_show_code) {
                        $this->_links_page = $this->_links['__sape_new_url__'];
                    }
                }
            }

            // Есть ли флаг блочных ссылок
            if (isset($this->_links['__sape_show_only_block__'])) {
                $this->_show_only_block = $this->_links['__sape_show_only_block__'];
            }
            else {
                $this->_show_only_block = false;
            }

            // Есть ли шаблон для красивых ссылок
            if (isset($this->_links['__sape_block_tpl__']) && !empty($this->_links['__sape_block_tpl__'])
                && is_array($this->_links['__sape_block_tpl__'])){
                $this->_block_tpl = $this->_links['__sape_block_tpl__'];
            }

            // Есть ли параметры для красивых ссылок
            if (isset($this->_links['__sape_block_tpl_options__']) && !empty($this->_links['__sape_block_tpl_options__'])
                && is_array($this->_links['__sape_block_tpl_options__'])){
                $this->_block_tpl_options = $this->_links['__sape_block_tpl_options__'];
            }

            // IDNA-домены
            if (isset($this->_links['__sape_block_uri_idna__']) && !empty($this->_links['__sape_block_uri_idna__'])
                && is_array($this->_links['__sape_block_uri_idna__'])){
                $this->_block_uri_idna = $this->_links['__sape_block_uri_idna__'];
            }

            // Блоки
            $check_blocks = array(
                'beforeall',
                'beforeblock',
                'afterblock',
                'itemobligatory',
                'itemconditional',
                'afterall'
            );

            foreach($check_blocks as $block_name) {

                $var_name = '__sape_block_ins_' . $block_name . '__';
                $prop_name = '_block_ins_' . $block_name;

                if (isset($this->_links[$var_name]) && strlen($this->_links[$var_name]) > 0) {
                    $this->$prop_name = $this->_links[$var_name];
                }

            }
        }
    }

    /**
     * Класс для работы с контекстными ссылками
     */
    class SAPE_context extends SAPE_base {

        var $_words = array();
        var $_words_page = array();
        var $_user_agent = 'SAPE_Context PHP';
        var $_filter_tags = array('a', 'textarea', 'select', 'script', 'style', 'label', 'noscript', 'noindex', 'button');

        function SAPE_context($options = null) {
            parent::SAPE_base($options);
            $this->load_data();
        }

        /**
         * Замена слов в куске текста и обрамляет его тегами sape_index
         */
        function replace_in_text_segment($text) {
            $debug = '';
            if ($this->_debug) {
                $debug .= "<!-- argument for replace_in_text_segment: \r\n" . base64_encode($text) . "\r\n -->";
            }
            if (count($this->_words_page) > 0) {

                $source_sentence = array();
                if ($this->_debug) {
                    $debug .= '<!-- sentences for replace: ';
                }
                //Создаем массив исходных текстов для замены
                foreach ($this->_words_page as $n => $sentence) {
                    //Заменяем все сущности на символы
                    $special_chars = array(
                        '&amp;' => '&',
                        '&quot;' => '"',
                        '&#039;' => '\'',
                        '&lt;' => '<',
                        '&gt;' => '>'
                    );
                    $sentence = strip_tags($sentence);
                    foreach ($special_chars as $from => $to) {
                        str_replace($from, $to, $sentence);
                    }
                    //Преобразуем все спец символы в сущности
                    $sentence = htmlspecialchars($sentence);
                    //Квотируем
                    $sentence = preg_quote($sentence, '/');
                    $replace_array = array();
                    if (preg_match_all('/(&[#a-zA-Z0-9]{2,6};)/isU', $sentence, $out)) {
                        for ($i = 0; $i < count($out[1]); $i++) {
                            $unspec = $special_chars[$out[1][$i]];
                            $real = $out[1][$i];
                            $replace_array[$unspec] = $real;
                        }
                    }
                    //Заменяем сущности на ИЛИ (сущность|символ)
                    foreach ($replace_array as $unspec => $real) {
                        $sentence = str_replace($real, '((' . $real . ')|(' . $unspec . '))', $sentence);
                    }
                    //Заменяем пробелы на переносы или сущности пробелов
                    $source_sentences[$n] = str_replace(' ', '((\s)|(&nbsp;))+', $sentence);

                    if ($this->_debug) {
                        $debug .= $source_sentences[$n] . "\r\n\r\n";
                    }
                }

                if ($this->_debug) {
                    $debug .= '-->';
                }

                //если это первый кусок, то не будем добавлять <
                $first_part = true;
                //пустая переменная для записи

                if (count($source_sentences) > 0) {

                    $content = '';
                    $open_tags = array(); //Открытые забаненые тэги
                    $close_tag = ''; //Название текущего закрывающего тэга

                    //Разбиваем по символу начала тега
                    $part = strtok(' ' . $text, '<');

                    while ($part !== false) {
                        //Определяем название тэга
                        if (preg_match('/(?si)^(\/?[a-z0-9]+)/', $part, $matches)) {
                            //Определяем название тега
                            $tag_name = strtolower($matches[1]);
                            //Определяем закрывающий ли тэг
                            if (substr($tag_name, 0, 1) == '/') {
                                $close_tag = substr($tag_name, 1);
                                if ($this->_debug) {
                                    $debug .= '<!-- close_tag: ' . $close_tag . ' -->';
                                }
                            } else {
                                $close_tag = '';
                                if ($this->_debug) {
                                    $debug .= '<!-- open_tag: ' . $tag_name . ' -->';
                                }
                            }
                            $cnt_tags = count($open_tags);
                            //Если закрывающий тег совпадает с тегом в стеке открытых запрещенных тегов
                            if (($cnt_tags > 0) && ($open_tags[$cnt_tags - 1] == $close_tag)) {
                                array_pop($open_tags);
                                if ($this->_debug) {
                                    $debug .= '<!-- ' . $tag_name . ' - deleted from open_tags -->';
                                }
                                if ($cnt_tags - 1 == 0) {
                                    if ($this->_debug) {
                                        $debug .= '<!-- start replacement -->';
                                    }
                                }
                            }

                            //Если нет открытых плохих тегов, то обрабатываем
                            if (count($open_tags) == 0) {
                                //если не запрещенный тэг, то начинаем обработку
                                if (!in_array($tag_name, $this->_filter_tags)) {
                                    $split_parts = explode('>', $part, 2);
                                    //Перестраховываемся
                                    if (count($split_parts) == 2) {
                                        //Начинаем перебор фраз для замены
                                        foreach ($source_sentences as $n => $sentence) {
                                            if (preg_match('/' . $sentence . '/', $split_parts[1]) == 1) {
                                                $split_parts[1] = preg_replace('/' . $sentence . '/', str_replace('$', '\$', $this->_words_page[$n]), $split_parts[1], 1);
                                                if ($this->_debug) {
                                                    $debug .= '<!-- ' . $sentence . ' --- ' . $this->_words_page[$n] . ' replaced -->';
                                                }

                                                //Если заменили, то удаляем строчку из списка замены
                                                unset($source_sentences[$n]);
                                                unset($this->_words_page[$n]);
                                            }
                                        }
                                        $part = $split_parts[0] . '>' . $split_parts[1];
                                        unset($split_parts);
                                    }
                                } else {
                                    //Если у нас запрещеный тэг, то помещаем его в стек открытых
                                    $open_tags[] = $tag_name;
                                    if ($this->_debug) {
                                        $debug .= '<!-- ' . $tag_name . ' - added to open_tags, stop replacement -->';
                                    }
                                }
                            }
                        } else {
                            //Если нет названия тега, то считаем, что перед нами текст
                            foreach ($source_sentences as $n => $sentence) {
                                if (preg_match('/' . $sentence . '/', $part) == 1) {
                                    $part = preg_replace('/' . $sentence . '/', str_replace('$', '\$', $this->_words_page[$n]), $part, 1);

                                    if ($this->_debug) {
                                        $debug .= '<!-- ' . $sentence . ' --- ' . $this->_words_page[$n] . ' replaced -->';
                                    }

                                    //Если заменили, то удаляем строчку из списка замены,
                                    //чтобы было можно делать множественный вызов
                                    unset($source_sentences[$n]);
                                    unset($this->_words_page[$n]);
                                }
                            }
                        }

                        //Если у нас режим дебагинга, то выводим
                        if ($this->_debug) {
                            $content .= $debug;
                            $debug = '';
                        }
                        //Если это первая часть, то не выводим <
                        if ($first_part) {
                            $content .= $part;
                            $first_part = false;
                        } else {
                            $content .= $debug . '<' . $part;
                        }
                        //Получаем следующу часть
                        unset($part);
                        $part = strtok('<');
                    }
                    $text = ltrim($content);
                    unset($content);
                }
            } else {
                if ($this->_debug) {
                    $debug .= '<!-- No word`s for page -->';
                }
            }

            if ($this->_debug) {
                $debug .= '<!-- END: work of replace_in_text_segment() -->';
            }

            if ($this->_is_our_bot || $this->_force_show_code || $this->_debug) {
                $text = '<sape_index>' . $text . '</sape_index>';
                if (isset($this->_words['__sape_new_url__']) && strlen($this->_words['__sape_new_url__'])) {
                    $text .= $this->_words['__sape_new_url__'];
                }
            }

            if ($this->_debug) {
                if (count($this->_words_page) > 0) {
                    $text .= '<!-- Not replaced: ' . "\r\n";
                    foreach ($this->_words_page as $n => $value) {
                        $text .= $value . "\r\n\r\n";
                    }
                    $text .= '-->';
                }

                $text .= $debug;
            }
            return $text;
        }

        /**
         * Замена слов
         */
        function replace_in_page(&$buffer) {

            if (count($this->_words_page) > 0) {
                //разбиваем строку по sape_index
                //Проверяем есть ли теги sape_index
                $split_content = preg_split('/(?smi)(<\/?sape_index>)/', $buffer, -1);
                $cnt_parts = count($split_content);
                if ($cnt_parts > 1) {
                    //Если есть хоть одна пара sape_index, то начинаем работу
                    if ($cnt_parts >= 3) {
                        for ($i = 1; $i < $cnt_parts; $i = $i + 2) {
                            $split_content[$i] = $this->replace_in_text_segment($split_content[$i]);
                        }
                    }
                    $buffer = implode('', $split_content);
                    if ($this->_debug) {
                        $buffer .= '<!-- Split by Sape_index cnt_parts=' . $cnt_parts . '-->';
                    }
                } else {
                    //Если не нашли sape_index, то пробуем разбить по BODY
                    $split_content = preg_split('/(?smi)(<\/?body[^>]*>)/', $buffer, -1, PREG_SPLIT_DELIM_CAPTURE);
                    //Если нашли содержимое между body
                    if (count($split_content) == 5) {
                        $split_content[0] = $split_content[0] . $split_content[1];
                        $split_content[1] = $this->replace_in_text_segment($split_content[2]);
                        $split_content[2] = $split_content[3] . $split_content[4];
                        unset($split_content[3]);
                        unset($split_content[4]);
                        $buffer = $split_content[0] . $split_content[1] . $split_content[2];
                        if ($this->_debug) {
                            $buffer .= '<!-- Split by BODY -->';
                        }
                    } else {
                        //Если не нашли sape_index и не смогли разбить по body
                        if ($this->_debug) {
                            $buffer .= '<!-- Can`t split by BODY -->';
                        }
                    }
                }

            } else {
                if (!$this->_is_our_bot && !$this->_force_show_code && !$this->_debug) {
                    $buffer = preg_replace('/(?smi)(<\/?sape_index>)/', '', $buffer);
                } else {
                    if (isset($this->_words['__sape_new_url__']) && strlen($this->_words['__sape_new_url__'])) {
                        $buffer .= $this->_words['__sape_new_url__'];
                    }
                }
                if ($this->_debug) {
                    $buffer .= '<!-- No word`s for page -->';
                }
            }
            return $buffer;
        }

        function _get_db_file() {
            if ($this->_multi_site) {
                return dirname(__FILE__) . '/' . $this->_host . '.words.db';
            } else {
                return dirname(__FILE__) . '/words.db';
            }
        }

        function _get_dispenser_path() {
            return '/code_context.php?user=' . _SAPE_USER . '&host=' . $this->_host;
        }

        function set_data($data) {
            $this->_words = $data;
            if (@array_key_exists($this->_request_uri, $this->_words) && is_array($this->_words[$this->_request_uri])) {
                $this->_words_page = $this->_words[$this->_request_uri];
            }
        }
    }

    /**
     * Класс для работы со статьями articles.sape.ru показывает анонсы и статьи
     */
    class SAPE_articles extends SAPE_base {

        var $_request_mode;

        var $_server_list             = array('dispenser.articles.sape.ru');

        var $_data                    = array();

        var $_article_id;

        var $_save_file_name;

        var $_announcements_delimiter = '';

        var $_images_path;

        var $_template_error = false;

        var $_noindex_code = '<!--sape_noindex-->';

        var $_headers_enabled = false;

        var $_mask_code;

        var $_real_host;

        var $_user_agent = 'SAPE_Articles_Client PHP';

        function SAPE_articles($options = null){
            parent::SAPE_base($options);
            if (is_array($options) && isset($options['headers_enabled'])) {
                $this->_headers_enabled = $options['headers_enabled'];
            }
            // Кодировка
            if (isset($options['charset']) && strlen($options['charset'])) {
                $this->_charset = $options['charset'];
            } else {
                $this->_charset = '';
            }
            $this->_get_index();
            if (!empty($this->_data['index']['announcements_delimiter'])) {
                $this->_announcements_delimiter = $this->_data['index']['announcements_delimiter'];
            }
            if (!empty($this->_data['index']['charset'])
                and !(isset($options['charset']) && strlen($options['charset']))) {
                $this->_charset = $this->_data['index']['charset'];
            }
            if (is_array($options)) {
                if (isset($options['host'])) {
                    $host = $options['host'];
                }
            } elseif (strlen($options)) {
                $host = $options;
                $options = array();
            }
            if (isset($host) && strlen($host)) {
                $this->_real_host = $host;
            } else {
                $this->_real_host = $_SERVER['HTTP_HOST'];
            }
            if (!isset($this->_data['index']['announcements'][$this->_request_uri])) {
                $this->_correct_uri();
            }
        }

        function _correct_uri() {
            if(substr($this->_request_uri, -1) == '/') {
                $new_uri = substr($this->_request_uri, 0, -1);
            } else {
                $new_uri = $this->_request_uri . '/';
            }
            if (isset($this->_data['index']['announcements'][$new_uri])) {
                $this->_request_uri = $new_uri;
            }
        }

        /**
         * Возвращает анонсы для вывода
         * @param int $n      Сколько анонсов вывести, либо не задано - вывести все
         * @param int $offset C какого анонса начинаем вывод(нумерация с 0), либо не задано - с нулевого
         * @return string
         */
        function return_announcements($n = null, $offset = 0){
            $output = '';
            if ($this->_force_show_code || $this->_is_our_bot) {
                if (isset($this->_data['index']['checkCode'])) {
                    $output .= $this->_data['index']['checkCode'];
                }
            }

            if (isset($this->_data['index']['announcements'][$this->_request_uri])) {

                $total_page_links = count($this->_data['index']['announcements'][$this->_request_uri]);

                if (!is_numeric($n) || $n > $total_page_links) {
                    $n = $total_page_links;
                }

                $links = array();

                for ($i = 1; $i <= $n; $i++) {
                    if ($offset > 0 && $i <= $offset) {
                        array_shift($this->_data['index']['announcements'][$this->_request_uri]);
                    } else {
                        $links[] = array_shift($this->_data['index']['announcements'][$this->_request_uri]);
                    }
                }

                $html = join($this->_announcements_delimiter, $links);

                if ($this->_is_our_bot) {
                    $html = '<sape_noindex>' . $html . '</sape_noindex>';
                }

                $output .= $html;

            }

            return $output;
        }

        function _get_index(){
            $this->_set_request_mode('index');
            $this->_save_file_name = 'articles.db';
            $this->load_data();
        }

        /**
         * Возвращает полный HTML код страницы статьи
         * @return string
         */
        function process_request(){

            if (!empty($this->_data['index']) and isset($this->_data['index']['articles'][$this->_request_uri])) {
                return $this->_return_article();
            } elseif (!empty($this->_data['index']) and isset($this->_data['index']['images'][$this->_request_uri])) {
                return $this->_return_image();
            } else {
                if ($this->_is_our_bot) {
                    return $this->_return_html($this->_data['index']['checkCode'] . $this->_noindex_code);
                } else {
                    return $this->_return_not_found();
                }
            }
        }

        function _return_article(){
            $this->_set_request_mode('article');
            //Загружаем статью
            $article_meta = $this->_data['index']['articles'][$this->_request_uri];
            $this->_save_file_name = $article_meta['id'] . '.article.db';
            $this->_article_id = $article_meta['id'];
            $this->load_data();

            //Обновим если устарела
            if (!isset($this->_data['article']['date_updated']) OR $this->_data['article']['date_updated']  < $article_meta['date_updated']) {
                unlink($this->_get_db_file());
                $this->load_data();
            }

            //Получим шаблон
            $template = $this->_get_template($this->_data['index']['templates'][$article_meta['template_id']]['url'], $article_meta['template_id']);

            //Выведем статью
            $article_html = $this->_fetch_article($template);

            if ($this->_is_our_bot) {
                $article_html .= $this->_noindex_code;
            }

            return $this->_return_html($article_html);

        }

        function _prepare_path_to_images(){
            $this->_images_path = dirname(__FILE__) . '/images/';
            if (!is_dir($this->_images_path)) {
                // Пытаемся создать папку.
                if (@mkdir($this->_images_path)) {
                    @chmod($this->_images_path, 0777);    // Права доступа
                } else {
                    return $this->raise_error('Нет папки ' . $this->_images_path . '. Создать не удалось. Выставите права 777 на папку.');
                }
            }
            if ($this->_multi_site) {
                $this->_images_path .= $this->_host. '.';
            }
        }

        function _return_image(){
            $this->_set_request_mode('image');
            $this->_prepare_path_to_images();

            //Проверим загружена ли картинка
            $image_meta = $this->_data['index']['images'][$this->_request_uri];
            $image_path = $this->_images_path . $image_meta['id']. '.' . $image_meta['ext'];

            if (!is_file($image_path) or filemtime($image_path) > $image_meta['date_updated']) {
                // Чтобы не повесить площадку клиента и чтобы не было одновременных запросов
                @touch($image_path, $image_meta['date_updated']);

                $path = $image_meta['dispenser_path'];

                foreach ($this->_server_list as $i => $server){
                    if ($data = $this->fetch_remote_file($server, $path)) {
                        if (substr($data, 0, 12) == 'FATAL ERROR:') {
                            $this->raise_error($data);
                        } else {
                            // [псевдо]проверка целостности:
                            if (strlen($data) > 0) {
                                $this->_write($image_path, $data);
                                break;
                            }
                        }
                    }
                }
            }

            unset($data);
            if (!is_file($image_path)) {
                return $this->_return_not_found();
            }
            $image_file_meta = @getimagesize($image_path);
            $content_type = isset($image_file_meta['mime'])?$image_file_meta['mime']:'image';
            if ($this->_headers_enabled) {
                header('Content-Type: ' . $content_type);
            }
            return $this->_read($image_path);
        }

        function _fetch_article($template){
            if (strlen($this->_charset)) {
                $template = str_replace('{meta_charset}',  $this->_charset, $template);
            }
            foreach ($this->_data['index']['template_fields'] as $field){
                if (isset($this->_data['article'][$field])) {
                    $template = str_replace('{' . $field . '}',  $this->_data['article'][$field], $template);
                } else {
                    $template = str_replace('{' . $field . '}',  '', $template);
                }
            }
            return ($template);
        }

        function _get_template($template_url, $templateId){
            //Загрузим индекс если есть
            $this->_save_file_name = 'tpl.articles.db';
            $index_file = $this->_get_db_file();

            if (file_exists($index_file)) {
                $this->_data['templates'] = unserialize($this->_read($index_file));
            }


            //Если шаблон не найден или устарел в индексе, обновим его
            if (!isset($this->_data['templates'][$template_url])
                or (time() - $this->_data['templates'][$template_url]['date_updated']) > $this->_data['index']['templates'][$templateId]['lifetime']) {
                $this->_refresh_template($template_url, $index_file);
            }
            //Если шаблон не обнаружен - ошибка
            if (!isset($this->_data['templates'][$template_url])) {
                if ($this->_template_error){
                    return $this->raise_error($this->_template_error);
                }
                return $this->raise_error('Не найден шаблон для статьи');
            }

            return $this->_data['templates'][$template_url]['body'];
        }

        function _refresh_template($template_url, $index_file){
            $parseUrl = parse_url($template_url);

            $download_url = '';
            if ($parseUrl['path']) {
                $download_url .= $parseUrl['path'];
            }
            if (isset($parseUrl['query'])) {
                $download_url .= '?' . $parseUrl['query'];
            }

            $template_body = $this->fetch_remote_file($this->_real_host, $download_url, true);

            //проверим его на корректность
            if (!$this->_is_valid_template($template_body)){
                return false;
            }

            $template_body = $this->_cut_template_links($template_body);

            //Запишем его вместе с другими в кэш
            $this->_data['templates'][$template_url] = array( 'body' => $template_body, 'date_updated' => time());
            //И сохраним кэш
            $this->_write($index_file, serialize($this->_data['templates']));
        }

        function _fill_mask ($data) {
            global $unnecessary;
            $len = strlen($data[0]);
            $mask = str_repeat($this->_mask_code, $len);
            $unnecessary[$this->_mask_code][] = array(
                'mask' => $mask,
                'code' => $data[0],
                'len'  => $len
            );

            return $mask;
        }

        function _cut_unnecessary(&$contents, $code, $mask) {
            global $unnecessary;
            $this->_mask_code = $code;
            $_unnecessary[$this->_mask_code] = array();
            $contents = preg_replace_callback($mask, array($this, '_fill_mask'), $contents);
        }

        function _restore_unnecessary(&$contents, $code) {
            global $unnecessary;
            $offset = 0;
            if (!empty($unnecessary[$code])) {
                foreach ($unnecessary[$code] as $meta) {
                    $offset = strpos($contents, $meta['mask'], $offset);
                    $contents = substr($contents, 0, $offset)
                        . $meta['code'] . substr($contents, $offset + $meta['len']);
                }
            }
        }

        function _cut_template_links($template_body){
            $link_pattern    = '~(\<a [^\>]*?href[^\>]*?\=["\']{0,1}http[^\>]*?\>.*?\</a[^\>]*?\>|\<a [^\>]*?href[^\>]*?\=["\']{0,1}http[^\>]*?\>|\<area [^\>]*?href[^\>]*?\=["\']{0,1}http[^\>]*?\>)~si';
            $link_subpattern = '~\<a |\<area ~si';
            $rel_pattern     = '~[\s]{1}rel\=["\']{1}[^ "\'\>]*?["\']{1}| rel\=[^ "\'\>]*?[\s]{1}~si';
            $href_pattern    = '~[\s]{1}href\=["\']{0,1}(http[^ "\'\>]*)?["\']{0,1} {0,1}~si';

            $allowed_domains = $this->_data['index']['ext_links_allowed'];
            $allowed_domains[] = $this -> _host;
            $allowed_domains[] = 'www.' . $this -> _host;
            $this->_cut_unnecessary($template_body, 'C', '|<!--(.*?)-->|smi');
            $this->_cut_unnecessary($template_body, 'S', '|<script[^>]*>.*?</script>|si');
            $this->_cut_unnecessary($template_body, 'N', '|<noindex[^>]*>.*?</noindex>|si');

            $slices = preg_split($link_pattern, $template_body, -1,  PREG_SPLIT_DELIM_CAPTURE );
            //Обрамляем все видимые ссылки в noindex
            if(is_array($slices)) {
                foreach ($slices as $id => $link) {
                    if ($id % 2 == 0) {
                        continue;
                    }
                    if (preg_match($href_pattern, $link, $urls)) {
                        $parsed_url = @parse_url($urls[1]);
                        $host = isset($parsed_url['host'])?$parsed_url['host']:false;
                        if (!in_array($host, $allowed_domains) || !$host){
                            //Обрамляем в тэги noindex
                            $slices[$id] = '<noindex>' . $slices[$id] . '</noindex>';
                        }
                    }
                }
                $template_body = implode('', $slices);
            }
            //Вновь отображаем содержимое внутри noindex
            $this->_restore_unnecessary($template_body, 'N');

            //Прописываем всем ссылкам nofollow
            $slices = preg_split($link_pattern, $template_body, -1,  PREG_SPLIT_DELIM_CAPTURE );
            if(is_array($slices)) {
                foreach ($slices as $id => $link) {
                    if ($id % 2 == 0) {
                        continue;
                    }
                    if (preg_match($href_pattern, $link, $urls)) {
                        $parsed_url = @parse_url($urls[1]);
                        $host = isset($parsed_url['host'])?$parsed_url['host']:false;
                        if (!in_array($host, $allowed_domains) || !$host) {
                            //вырезаем REL
                            $slices[$id] = preg_replace($rel_pattern, '', $link);
                            //Добавляем rel=nofollow
                            $slices[$id] = preg_replace($link_subpattern, '$0rel="nofollow" ', $slices[$id]);
                        }
                    }
                }
                $template_body = implode('', $slices);
            }

            $this->_restore_unnecessary($template_body, 'S');
            $this->_restore_unnecessary($template_body, 'C');
            return $template_body;
        }

        function _is_valid_template($template_body){
            foreach ($this->_data['index']['template_required_fields'] as $field){
                if (strpos($template_body, '{' . $field . '}') === false){
                    $this->_template_error = 'В шаблоне не хватает поля ' . $field . '.';
                    return false;
                }
            }
            return true;
        }

        function _return_html($html){
            if ($this->_headers_enabled){
                header('HTTP/1.x 200 OK');
                if (!empty($this->_charset)){
                    header('Content-Type: text/html; charset=' . $this->_charset);
                }
            }
            return $html;
        }

        function _return_not_found(){
            header('HTTP/1.x 404 Not Found');
        }

        function _get_dispenser_path(){
            switch ($this->_request_mode){
                case 'index':
                    return '/?user=' . _SAPE_USER . '&host=' .
                        $this->_host . '&rtype=' . $this->_request_mode;
                    break;
                case 'article':
                    return '/?user=' . _SAPE_USER . '&host=' .
                        $this->_host . '&rtype=' . $this->_request_mode . '&artid=' . $this->_article_id;
                    break;
                case 'image':
                    return $this->image_url;
                    break;
            }
        }

        function _set_request_mode($mode){
            $this->_request_mode = $mode;
        }

        function _get_db_file(){
            if ($this->_multi_site){
                return dirname(__FILE__) . '/' . $this->_host . '.' . $this->_save_file_name;
            }
            else{
                return dirname(__FILE__) . '/' . $this->_save_file_name;
            }
        }

        function set_data($data){
            $this->_data[$this->_request_mode] = $data;
        }

    }

    ?>itex_imoney_datafiles_delimiter_1file trustlink.php from trustlink.ru T0.4.5 31.03.2011itex_imoney_datafiles_delimiter_2trustlink.phpitex_imoney_datafiles_delimiter_2<?php
class TrustlinkClient {
    var $tl_version           = 'T0.4.5';
    var $tl_verbose           = false;
    var $tl_cache             = false;
    var $tl_cache_size        = 10;
    var $tl_cache_dir         = 'cache/';
    var $tl_cache_filename    = 'trustlink.links';
    var $tl_cache_cluster     = 0;
    var $tl_cache_update      = false;
    var $tl_debug             = false;
    var $tl_isrobot           = false;
    var $tl_test              = false;
    var $tl_test_count        = 4;
    var $tl_template          = 'template';
    var $tl_charset           = 'DEFAULT';
    var $tl_use_ssl           = false;
    var $tl_server            = 'db.trustlink.ru';
    var $tl_cache_lifetime    = 3600;
    var $tl_cache_reloadtime  = 300;
    var $tl_links_db_file     = '';
    var $tl_links             = array();
    var $tl_links_page        = array();
    var $tl_error             = '';
    var $tl_host              = '';
    var $tl_request_uri       = '';
    var $tl_fetch_remote_type = '';
    var $tl_socket_timeout    = 6;
    var $tl_force_show_code   = false;
    var $tl_multi_site        = false;
    var $tl_is_static         = false;

    function TrustlinkClient($options = null) {
        $host = '';

        if (is_array($options)) {
            if (isset($options['host'])) {
                $host = $options['host'];
            }
        } elseif (strlen($options) != 0) {
            $host = $options;
            $options = array();
        } else {
            $options = array();
        }

        if (strlen($host) != 0) {
            $this->tl_host = $host;
        } else {
            $this->tl_host = $_SERVER['HTTP_HOST'];
        }

        $this->tl_host = preg_replace('{^https?://}i', '', $this->tl_host);
        $this->tl_host = preg_replace('{^www\.}i', '', $this->tl_host);
        $this->tl_host = strtolower( $this->tl_host);

        if (isset($options['is_static']) && $options['is_static']) {
            $this->tl_is_static = true;
        }

        if (isset($options['request_uri']) && strlen($options['request_uri']) != 0) {
            $this->tl_request_uri = $options['request_uri'];
        } else {
            if ($this->tl_is_static) {
                $this->tl_request_uri = preg_replace( '{\?.*$}', '', $_SERVER['REQUEST_URI']);
                $this->tl_request_uri = preg_replace( '{/+}', '/', $this->tl_request_uri);
	    } else {
                $this->tl_request_uri = $_SERVER['REQUEST_URI'];
            }
        }

        $this->tl_request_uri = rawurldecode($this->tl_request_uri);

        if (isset($options['multi_site']) && $options['multi_site'] == true) {
            $this->tl_multi_site = true;
        }

        if ((isset($options['verbose']) && $options['verbose']) ||
            isset($this->tl_links['__trustlink_debug__'])) {
            $this->tl_verbose = true;
        }

        if (isset($options['charset']) && strlen($options['charset']) != 0) {
            $this->tl_charset = $options['charset'];
        }

        if (isset($options['fetch_remote_type']) && strlen($options['fetch_remote_type']) != 0) {
            $this->tl_fetch_remote_type = $options['fetch_remote_type'];
        }

        if (isset($options['socket_timeout']) && is_numeric($options['socket_timeout']) && $options['socket_timeout'] > 0) {
            $this->tl_socket_timeout = $options['socket_timeout'];
        }

        if ((isset($options['force_show_code']) && $options['force_show_code']) ||
            isset($this->tl_links['__trustlink_debug__'])) {
            $this->tl_force_show_code = true;
        }

        #Cache options
        if (isset($options['use_cache']) && $options['use_cache']) {
            $this->tl_cache = true;
        }

        if (isset($options['cache_clusters']) && $options['cache_clusters']) {
            $this->tl_cache_size = $options['cache_clusters'];
        }

        if (isset($options['cache_dir']) && $options['cache_dir']) {
            $this->tl_cache_dir = $options['cache_dir'];
        }

        if (!defined('TRUSTLINK_USER')) {
            return $this->raise_error("Constant TRUSTLINK_USER is not defined.");
        }

		if (isset($_SERVER['HTTP_TRUSTLINK']) && $_SERVER['HTTP_TRUSTLINK']==TRUSTLINK_USER){
			$this->tl_test=true;
			$this->tl_isrobot=true;
			$this->tl_verbose = true;
		}

        if (isset($_GET['trustlink_test']) && $_GET['trustlink_test']==TRUSTLINK_USER){
            $this->tl_force_show_code=true;
			$this->tl_verbose = true;
        }

        $this->load_links();
    }

    function setup_datafile($filename){
        if (!is_file($filename)) {
            if (@touch($filename, time() - $this->tl_cache_lifetime)) {
                @chmod($filename, 0666);
            } else {
                return $this->raise_error("There is no file " . $filename  . ". Fail to create. Set mode to 777 on the folder.");
            }
        }

        if (!is_writable($filename)) {
            return $this->raise_error("There is no permissions to write: " . $filename . "! Set mode to 777 on the folder.");
        }
        return true;
    }

    function load_links() {
        if ($this->tl_multi_site) {
            $this->tl_links_db_file = dirname(__FILE__) . '/trustlink.' . $this->tl_host . '.links.db';
        } else {
            $this->tl_links_db_file = dirname(__FILE__) . '/trustlink.links.db';
        }

        if (!$this->setup_datafile($this->tl_links_db_file)){return false;}

        //cache
        if ($this->tl_cache){
            //check dir
            if (!is_dir(dirname(__FILE__) .'/'.$this->tl_cache_dir)) {
               if(!@mkdir(dirname(__FILE__) .'/'.$this->tl_cache_dir)){
                  return $this->raise_error("There is no dir " . dirname(__FILE__) .'/'.$this->tl_cache_dir  . ". Fail to create. Set mode to 777 on the folder."); 
               }
            }
            //check dir rights
            if (!is_writable(dirname(__FILE__) .'/'.$this->tl_cache_dir)) {
                return $this->raise_error("There is no permissions to write to dir " . $this->tl_cache_dir . "! Set mode to 777 on the folder.");
            }

            for ($i=0; $i<$this->tl_cache_size; $i++){
                $filename=$this->cache_filename($i);
                if (!$this->setup_datafile($filename)){return false;}
            }
        }

        @clearstatcache();

        //Load links
        if (filemtime($this->tl_links_db_file) < (time()-$this->tl_cache_lifetime) ||
           (filemtime($this->tl_links_db_file) < (time()-$this->tl_cache_reloadtime) && filesize($this->tl_links_db_file) == 0)) {

            @touch($this->tl_links_db_file, time());

            $path = '/' . TRUSTLINK_USER . '/' . strtolower( $this->tl_host ) . '/' . strtoupper( $this->tl_charset);

            if ($links = $this->fetch_remote_file($this->tl_server, $path)) {
                if (substr($links, 0, 12) == 'FATAL ERROR:' && $this->tl_debug) {
                    $this->raise_error($links);
                } else{
                    if (@unserialize($links) !== false) {
                    $this->lc_write($this->tl_links_db_file, $links);
                    $this->tl_cache_update = true;
                    } else if ($this->tl_debug) {
                        $this->raise_error("Cans't unserialize received data.");
                    }
                }
            }
        }

        if ($this->tl_cache && !$this->lc_is_synced_cache()){ $this->tl_cache_update = true; }

        if ($this->tl_cache && !$this->tl_cache_update){
            $this->tl_cache_cluster = $this->page_cluster($this->tl_request_uri,$this->tl_cache_size);
            $links = $this->lc_read($this->cache_filename($this->tl_cache_cluster));
        }else{
            $links = $this->lc_read($this->tl_links_db_file);
        }

        $this->tl_file_change_date = gmstrftime ("%d.%m.%Y %H:%M:%S",filectime($this->tl_links_db_file));
        $this->tl_file_size = strlen( $links);

        if (!$links) {
            $this->tl_links = array();
            if ($this->tl_debug)
                $this->raise_error("Empty file.");
        } else if (!$this->tl_links = @unserialize($links)) {
            $this->tl_links = array();
            if ($this->tl_debug)
                $this->raise_error("Can't unserialize data from file.");
        }


        if (isset($this->tl_links['__trustlink_delimiter__'])) {
            $this->tl_links_delimiter = $this->tl_links['__trustlink_delimiter__'];
        }

		if ($this->tl_test)
		{
        	if (isset($this->tl_links['__test_tl_link__']) && is_array($this->tl_links['__test_tl_link__']))
        		for ($i=0;$i<$this->tl_test_count;$i++)
					$this->tl_links_page[$i]=$this->tl_links['__test_tl_link__'];
                    if ($this->tl_charset!='DEFAULT'){
                        $this->tl_links_page[$i]['text']=iconv("UTF-8", $this->tl_charset, $this->tl_links_page[$i]['text']);
                        $this->tl_links_page[$i]['anchor']=iconv("UTF-8", $this->tl_charset, $this->tl_links_page[$i]['anchor']);
                    }
		} else {

            $tl_links_temp=array();
            foreach($this->tl_links as $key=>$value){
                $tl_links_temp[rawurldecode($key)]=$value;
            }
            $this->tl_links=$tl_links_temp;

            if ($this->tl_cache && $this->tl_cache_update){
                $this->lc_write_cache($this->tl_links);
            }

            $this->tl_links_page=array();
            if (array_key_exists($this->tl_request_uri, $this->tl_links) && is_array($this->tl_links[$this->tl_request_uri])) {
                $this->tl_links_page = array_merge($this->tl_links_page, $this->tl_links[$this->tl_request_uri]);
            }
		}

        $this->tl_links_count = count($this->tl_links_page);
    }

    function fetch_remote_file($host, $path) {
        $user_agent = 'Trustlink Client PHP ' . $this->tl_version;

        @ini_set('allow_url_fopen', 1);
        @ini_set('default_socket_timeout', $this->tl_socket_timeout);
        @ini_set('user_agent', $user_agent);

        if (
            $this->tl_fetch_remote_type == 'file_get_contents' || (
                $this->tl_fetch_remote_type == '' && function_exists('file_get_contents') && ini_get('allow_url_fopen') == 1
            )
        ) {
            if ($data = @file_get_contents('http://' . $host . $path)) {
                return $data;
            }
        } elseif (
            $this->tl_fetch_remote_type == 'curl' || (
                $this->tl_fetch_remote_type == '' && function_exists('curl_init')
            )
        ) {
            if ($ch = @curl_init()) {
                @curl_setopt($ch, CURLOPT_URL, 'http://' . $host . $path);
                @curl_setopt($ch, CURLOPT_HEADER, false);
                @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                @curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->tl_socket_timeout);
                @curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);

                if ($data = @curl_exec($ch)) {
                    return $data;
                }

                @curl_close($ch);
            }
        } else {
            $buff = '';
            $fp = @fsockopen($host, 80, $errno, $errstr, $this->tl_socket_timeout);
            if ($fp) {
                @fputs($fp, "GET {$path} HTTP/1.0\r\nHost: {$host}\r\n");
                @fputs($fp, "User-Agent: {$user_agent}\r\n\r\n");
                while (!@feof($fp)) {
                    $buff .= @fgets($fp, 128);
                }
                @fclose($fp);

                $page = explode("\r\n\r\n", $buff);

                return $page[1];
            }
        }

        return $this->raise_error("Can't connect to server: " . $host . $path);
    }

    function lc_read($filename) {
        $fp = @fopen($filename, 'rb');
        @flock($fp, LOCK_SH);
        if ($fp) {
            clearstatcache();
            $length = @filesize($filename);
            if(get_magic_quotes_gpc()){
                $mqr = get_magic_quotes_runtime();
                set_magic_quotes_runtime(0);
            }
            if ($length) {
                $data = @fread($fp, $length);
            } else {
                $data = '';
            }
            if(isset($mqr)){
                set_magic_quotes_runtime($mqr);
            }
            @flock($fp, LOCK_UN);
            @fclose($fp);

            return $data;
        }

        return $this->raise_error("Can't get data from the file: " . $filename);
    }

    function lc_write($filename, $data) {
        $fp = @fopen($filename, 'wb');
        if ($fp) {
            @flock($fp, LOCK_EX);
            $length = strlen($data);
            @fwrite($fp, $data, $length);
            @flock($fp, LOCK_UN);
            @fclose($fp);

            if (md5($this->lc_read($filename)) != md5($data)) {
                return $this->raise_error("Integrity was violated while writing to file: " . $filename);
            }

            return true;
        }

        return $this->raise_error("Can't write to file: " . $filename);
    }


    function page_cluster($path,$n){
        $size = strlen($path);
        $sum=0;
        for ($i = 0; $i < $size; $i++){
            $sum+= ord($path[$i]);
        }
        return $sum % $n;
    }

    function cache_filename($i){
        $host = $this->tl_multi_site ? '.'.$this->tl_host : '';
        return dirname(__FILE__) . '/'.$this->tl_cache_dir.$this->tl_cache_filename.$host.'.db'.$i;
    }

    function lc_write_cache($data){
        $common_keys = array('__trustlink_start__',
        '__trustlink_end__',
        '__trustlink_robots__',
        '__trustlink_delimiter__',
        '__trustlink_before_text__',
        '__trustlink_after_text__',
        '__test_tl_link__');

        $caches=array();

        foreach ($this->tl_links as $key => $value) {
            if (in_array($key,$common_keys)){
                for ($i=0; $i<$this->tl_cache_size; $i++){
                    if (empty($caches[$i])){               
                        $caches[$i] = array();
                    }
                    $caches[$i][$key] = $value;
                }
            }else{
                if (empty($caches[$this->page_cluster($key,$this->tl_cache_size)])){
                    $caches[$this->page_cluster($key,$this->tl_cache_size)] = array();
                }
                $caches[$this->page_cluster($key,$this->tl_cache_size)][$key] = $value;
            }
        }
       
       for ($i=0; $i<$this->tl_cache_size; $i++){
            $this->lc_write($this->cache_filename($i),serialize($caches[$i]));
       }
    }

    function lc_is_synced_cache(){
        $db_mtime = filemtime($this->tl_links_db_file);
        for ($i=0; $i<$this->tl_cache_size; $i++){
            $filename=$this->cache_filename($i);
            $cache_mtime = filemtime($filename);
            //check file size
            if (filesize($filename) == 0){return false;}
            //check reload cache time
            if ($cache_mtime < (time()-$this->tl_cache_lifetime)){return false;}
            //check time relative to trustlink.links.db
            if ($cache_mtime < $db_mtime){return false;}
        }
        return true;
    }

    function raise_error($e) {
        $this->tl_error = '<!--ERROR: ' . $e . '-->';
        return false;
    }

    function build_links($n = null)
    {

        $total_page_links = count($this->tl_links_page);

        if (!is_numeric($n) || $n > $total_page_links) {
            $n = $total_page_links;
        }

        $links = array();

        for ($i = 0; $i < $n; $i++) {
                $links[] = array_shift($this->tl_links_page);
        }

    	$result = '';
        if (isset($this->tl_links['__trustlink_start__']) && strlen($this->tl_links['__trustlink_start__']) != 0 &&
            (in_array($_SERVER['REMOTE_ADDR'], $this->tl_links['__trustlink_robots__']) || $this->tl_force_show_code)
        ) {
            $result .= $this->tl_links['__trustlink_start__'];
        }

        if (isset($this->tl_links['__trustlink_robots__']) && in_array($_SERVER['REMOTE_ADDR'], $this->tl_links['__trustlink_robots__']) || $this->tl_verbose) {

            if ($this->tl_error != '' && $this->tl_debug) {
                $result .= $this->tl_error;
            }

            $result .= '<!--REQUEST_URI=' . $_SERVER['REQUEST_URI'] . "-->\n";
            $result .= "\n<!--\n";
            $result .= 'L ' . $this->tl_version . "\n";
            $result .= 'REMOTE_ADDR=' . $_SERVER['REMOTE_ADDR'] . "\n";
            $result .= 'request_uri=' . $this->tl_request_uri . "\n";
            $result .= 'charset=' . $this->tl_charset . "\n";
            $result .= 'is_static=' . $this->tl_is_static . "\n";
            $result .= 'multi_site=' . $this->tl_multi_site . "\n";
            $result .= 'file change date=' . $this->tl_file_change_date . "\n";
            $result .= 'lc_file_size=' . $this->tl_file_size . "\n";
            $result .= 'lc_links_count=' . $this->tl_links_count . "\n";
            $result .= 'left_links_count=' . count($this->tl_links_page) . "\n";
            $result .= 'tl_cache=' . $this->tl_cache . "\n";
            $result .= 'tl_cache_size=' . $this->tl_cache_size . "\n";
            $result .= 'tl_cache_block=' . $this->tl_cache_cluster . "\n";
            $result .= 'tl_cache_update=' . $this->tl_cache_update . "\n";
            $result .= 'n=' . $n . "\n";
            $result .= '-->';
        }

    	$tpl_filename = dirname(__FILE__)."/".$this->tl_template.".tpl.html";
        $tpl = $this->lc_read($tpl_filename);
        if (!$tpl)
            return $this->raise_error("Template file not found");

        if (!preg_match("/<{block}>(.+)<{\/block}>/is", $tpl, $block))
            return $this->raise_error("Wrong template format: no <{block}><{/block}> tags");

        $tpl = str_replace($block[0], "%s", $tpl);
        $block = $block[0];
        $blockT = substr($block, 9, -10);


        if (strpos($blockT, '<{head_block}>')===false)
            return $this->raise_error("Wrong template format: no <{head_block}> tag.");
        if (strpos($blockT, '<{/head_block}>')===false)
            return $this->raise_error("Wrong template format: no <{/head_block}> tag.");

        if (strpos($blockT, '<{link}>')===false)
            return $this->raise_error("Wrong template format: no <{link}> tag.");
        if (strpos($blockT, '<{text}>')===false)
            return $this->raise_error("Wrong template format: no <{text}> tag.");
        if (strpos($blockT, '<{host}>')===false)
            return $this->raise_error("Wrong template format: no <{host}> tag.");

        if (!isset($text)) $text = '';
        
        foreach ($links as $i => $link)
        {
            if ($i >= $this->tl_test_count) continue;
            if (!is_array($link)) {
                return $this->raise_error("link must be an array");
            } elseif (!isset($link['text']) || !isset($link['url'])) {
                return $this->raise_error("format of link must be an array('anchor'=>\$anchor,'url'=>\$url,'text'=>\$text");
            } elseif (!($parsed=@parse_url($link['url'])) || !isset($parsed['host'])) {
                return $this->raise_error("wrong format of url: ".$link['url']);
            }
            if (($level=count(explode(".",$parsed['host'])))<2) {
                return $this->raise_error("wrong host: ".$parsed['host']." in url ".$link['url']);
            }
            $host=strtolower(($level>2 && strpos(strtolower($parsed['host']),'www.')===0)?substr($parsed['host'],4):$parsed['host']);
        	$block = str_replace("<{host}>", $host, $blockT);
            if (empty($link['anchor'])){
                $block = preg_replace ("/<{head_block}>(.+)<{\/head_block}>/is", "", $block);
            }else{
                $href = empty($link['punicode_url']) ? $link['url'] : $link['punicode_url'];   
                $block = str_replace("<{link}>", '<a href="'.$href.'">'.$link['anchor'].'</a>', $block);
                $block = str_replace("<{head_block}>", '', $block);
                $block = str_replace("<{/head_block}>", '', $block);
            }
            $block = str_replace("<{text}>", $link['text'], $block);
            $text .= $block;
        }
        if (is_array($links) && (count($links)>0)){
            $tpl = sprintf($tpl, $text);
            $result .= $tpl;
        }

        if (isset($this->tl_links['__trustlink_end__']) && strlen($this->tl_links['__trustlink_end__']) != 0 &&
            (in_array($_SERVER['REMOTE_ADDR'], $this->tl_links['__trustlink_robots__']) || $this->tl_force_show_code)
        ) {
            $result .= $this->tl_links['__trustlink_end__'];
        }

        if ($this->tl_test && !$this->tl_isrobot)
        	$result = '<noindex>'.$result.'</noindex>';
        return $result;
    }
}
?>itex_imoney_datafiles_delimiter_1file template.tpl.html from trustlink.ru 31.03.2011itex_imoney_datafiles_delimiter_2template.tpl.htmlitex_imoney_datafiles_delimiter_2<style>
table.ce2e3f {
padding: 0 !important;
margin: 0 !important;
font-size: 12px !important;
border: 1px solid #e0e0e0e !important;
background-color: #ffffff !important;
}
table.ce2e3f td {
padding: 5px !important;
text-align: left !important;
}
.ce2e3f a {
color: #0000cc !important;
font-weight: normal;
font-size: 12px !important;
}
.ce2e3f .text {
color: #000000 !important;
font-size: 12px !important;
padding: 3px 0 !important;
line-height: normal !important;
}
.ce2e3f .host {
color: #006600;
font-weight: normal;
font-size: 12px !important;
padding: 3px 0 !important;
line-height: normal !important;
}
.ce2e3f a {           
padding: 3px 0 !important;
}</style>
<table class="ce2e3f">
  <tbody>
  <{block}>
    <tr>
      <td>
        <table>
<{head_block}><tr><td><{link}></td></tr><{/head_block}>
<tr><td class="text"><{text}></td></tr>
<tr><td class="host"><{host}></td></tr>
</table>

      </td>
    </tr>
  <{/block}>
  </tbody>
</table>
itex_imoney_datafiles_delimiter_1file tnx.php 0.2c 24.09.2008itex_imoney_datafiles_delimiter_2tnx.phpitex_imoney_datafiles_delimiter_2<?php
// ���� ����� ���������� ������ - �����������������:
error_reporting(0);

/* TNX */
class TNX_n
{
        /*
        ���������� �� ���������
        */
        /****************************************/
        var $_timeout_cache = 3600; // 3600 - ����� ��� ���������� ����, �� ��������� 3600 ������, �.�. 1 ���
        var $_timeout_down = 3600; // 3600 - ����� ��� ���������� ��������� � tnx, � ������ ������� �������, �� ��������� 3600, �.�. 1 ���
        var $_timeout_down_error = 60; // ������������ �����, ��� ��������� ����� ������ ��� ��������� ������ � �������
        var $_timeout_connect = 5; // ������� ��������
        var $_connect_using = 'fsock'; // ������ �������� - curl ��� fsock
        var $_check_down = false; // ���������, �� ���� �� �������� �������. ���� ���� - �� ��������� �������� ������� �� ����� ��������
        var $_html_delimiter = '<br>'; // ����������� ��� ����� ����� ��������
        var $_encoding = ''; // ����� ��������� ������ �����. ����� - win-1251 (�� ���������). ����� ��������: KOI8-U, UTF-8 (��������� ������ iconv �� ��������)
        var $_exceptions = 'PHPSESSID'; // ����� ����� �������� ����� ������ �����, �������� � ���� ��� ���������� �� ���������� ��������, � �.�. �� robots.txt. ��� ����, �� ��������� �����������, ��� �� ������������ ��������. ����� ���������� �� ������.
        var $_forbidden = ''; // ����������� ��������, ����� ������, �������� ����� ��������� http://www.site.ru/index.php ����� '/index.php' � �.�. �� ��������� ���� http://www.site.ru/index.php?id=100 ����� ������������ ������, ����� �� ������������ - ����������� exceptions
        /****************************************/

        /*
        ����� ������ �� ������
        */
        var $_version = '0.2c';
        var $_return_point = 0;
        var $_down_status = 0;
        var $_content = '';

        function TNX_n($login, $cache_dir)
        {
                // ��������� ��������
                if($this->_connect_using == 'fsock' AND !function_exists('fsockopen'))
                {
                        $this->print_error('������, fsockopen �� ��������������, ��������� ������� �������� ������� �������� ��� ���������� CURL');
                        return false;
                }
                if($this->_connect_using == 'curl' AND !function_exists('curl_init'))
                {
                        $this->print_error('������, CURL �� ��������������, ���������� fsock.');
                        return false;
                }
                if(!empty($this->_encoding) AND !function_exists("iconv"))
                {
                        $this->print_error('������, iconv �� ��������������.');
                        return false;
                }
                // �������� �� ������� ��������, �� ���� �����, �� ����� ����.
                if (strlen($_SERVER['REQUEST_URI']) > 180)
                {
                        return false;
                }

                if ($_SERVER['REQUEST_URI'] == '')
                {
                        $_SERVER['REQUEST_URI'] = '/';
                }

                if(!empty($this->_exceptions))
                {
                        $exceptions = explode(' ', $this->_exceptions);
                        for ($i=0; $i<sizeof($exceptions); $i++)
                        {
                                if($_SERVER['REQUEST_URI'] == $exceptions[$i]) return false;
                                if($exceptions[$i] == '/' AND preg_match("#^\/index\.\w{1,5}$#", $_SERVER['REQUEST_URI'])) return false;
                                if(strpos($_SERVER['REQUEST_URI'], $exceptions[$i]) !== false) return false;
                        }
                }

                if(!empty($this->_forbidden)) // 21.09.07
                {
                        $forbidden = explode(' ', $this->_forbidden);
                        for ($i=0; $i<sizeof($forbidden); $i++)
                        {
                                if($_SERVER['REQUEST_URI'] == $forbidden[$i]) return false;
                        }
                }

                $login = strtolower($login);
                $this->_host = $login . '.tnx.net';

                $file = base64_encode($_SERVER['REQUEST_URI']);
                $user_pref = substr($login, 0, 2);
                $this->_md5 = md5($file);
                $index = substr($this->_md5, 0, 2);

                $site = str_replace('www.', '', $_SERVER['HTTP_HOST']);

                $this->_path = '/users/' . $user_pref . '/' . $login . '/' . $site. '/' . substr($this->_md5, 0, 1) . '/' . substr($this->_md5, 1, 2) . '/' . $file . '.txt';

                $this->_url = 'http://' . $this->_host . $this->_path;

                $absolute = $_SERVER['DOCUMENT_ROOT'] . $cache_dir;

                $site = str_replace('http://', '', $site);
                $site = str_replace('.', '_', $site);

                $this->_cache_file = $absolute . 'cache_' . $site . '_' . $index . '.txt';
                $this->_down_file = $absolute . 'down_' . $site . '.txt';

                /*
                ������ ��������� _down_file �����, ��������� ������� � _down_status
                ����� read_down ����������:
                0 - ������� � ����� ���������
                time() - ����� �������, ������� �������� �� ���������
                */
                if($this->_check_down)
                {
                        $this->_down_status = $this->read_down();
                }
                // ���������, ���������� �� ���� ����
                if(!is_file($this->_cache_file))
                {
                        // ������ ������ ��� ������������ ��������
                        $this->_content = $this->get_content();
                        if($this->_content)
                        {
                                /*
                                ���� ������ ��������, ��
                                 - ������� ���� _cache_file � ������� � ����,
                                   time() �������� ����,
                                   ��� ������������ ����������� ����������
                                */
                                $this->write_timeout();

                                /*
                                ����� ���������� ������ � ��� _cache_file
                                � ���� "_md5|_content\r\n"
                                */
                                $this->write_cache();
                        }
                }
                // ���� ���� ���� ����������
                else
                {
                        /*
                        ������ �� _cache_file ������ ������, ����� �������� ����.
                        ������� �����, ��������� � ������� �������� ����
                        */
                        $time = time() - $this->read_timeout();

                        // ���������, ����� �� �������� ���
                        if($time > $this->_timeout_cache)
                        {
                                // ������ ������ ��� ������������ ��������
                                $this->_content = $this->get_content();
                                if($this->_content)
                                {
                                        /*
                                        ���� ������ ��������, ��
                                        - �������� ���� _cache_file � ������� � ����,
                                          time() ���������� ����,
                                          ��� ������������ ����������� ����������
                                        */
                                        $this->write_timeout();
                                        // ����� ���������� ������
                                        $this->write_cache();
                                }
                        }

                        /*
                        ���� ��������� ��� �� ����� ��� �� _content == false
                        �.�. ����� get_content() ������ false � ������ �� ��������, ��:
                        */
                        if($time < $this->_timeout_cache OR isset($this->_content))
                        {
                                // ������� ����� �� ���� _md5 ������ ��� �������� ��������
                                $this->_content = $this->read_cache();
                                if(!$this->_content)
                                {
                                        // ���� read_cache() ������ false
                                        // ������� ������� ������ � tnx
                                        $this->_content = $this->get_content();
                                        if($this->_content)
                                        {
                                                /*
                                                ���� ������ ��������, ��
                                                ����� �� � ���
                                                */
                                                $this->write_cache();
                                        }
                                }
                        }
                }
                // ������� ��� ��������� ������
                clearstatcache();

                if($this->_content !== false)
                {
                        $this->_content_array = explode('<br>', $this->_content);
                        for ($i=0; $i<sizeof($this->_content_array); $i++)
                        {
                                $this->_content_array[$i] = trim($this->_content_array[$i]);
                        }
                }

        }

        // ������� ������
        function show_link($num = false)
        {
                // ��������� ���� �� ������ ������ � ���
                if(!isset($this->_content_array))
                {
                        return false;
                }

                $links = '';

                // ������������ ���������� ������ � �������
                if(!isset($this->_content_array_count)){$this->_content_array_count = sizeof($this->_content_array);}
                if($this->_return_point >= $this->_content_array_count)
                {
                        return false;
                }
                // ���� ������� ��� ������ ��� ��������� ���������� ������, ������ ��� �� �� ����� ����
                if($num === false OR $num >= $this->_content_array_count)
                {
                        for ($i = $this->_return_point; $i < $this->_content_array_count; $i++)
                        {
                                $links .= $this->_content_array[$i] . $this->_html_delimiter;
                        }
                        $this->_return_point += $this->_content_array_count;
                }
                else
                {
                        // ���� ��� ������ ��� ���� ��������, �� ���������� ������
                        if($this->_return_point + $num > $this->_content_array_count)
                        {
                                return false;
                        }

                        for ($i = $this->_return_point; $i < $num + $this->_return_point; $i++)
                        {
                                $links .= $this->_content_array[$i] . $this->_html_delimiter;
                        }

                        // ����������� ����� ������� ������
                        $this->_return_point += $num;
                }
                return (!empty($this->_encoding)) ? iconv("windows-1251", $this->_encoding, $links) : $links;
        }

        // ������� ��������� ������
        function get_content()
        {
                /*
                �������� � ����� �� ������ �� ����� _down_file
                0 - ������ �������
                */
                if($this->_down_status != 0)
                {
                        /*
                        ��������� �������, ���� ��������� ����� �� ���������,
                        �� ������ �� ������
                        */
                        if(time() - $this->_down_status <= $this->_timeout_down)
                        {
                                return false;
                        }
                        else
                        {
                                // ���� ��������� �������� _down_file � ������� ������� ������
                                $this->clean_down();
                        }
                }

                // ��������� ���� user agent, ���� �� ����� ������, ��� � ��� �����������
                $user_agent = 'TNX_n PHP ' . $this->_version;

                $page = '';

                if ($this->_connect_using == 'curl' OR ($this->_connect_using == '' AND function_exists('curl_init')))
                {
                        // ������� ������� ������ ������
                        $c = curl_init($this->_url);
                        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, $this->_timeout_connect);
                        curl_setopt($c, CURLOPT_HEADER, false);
                        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($c, CURLOPT_TIMEOUT, $this->_timeout_connect);
                        curl_setopt($c, CURLOPT_USERAGENT, $user_agent);
                        $page = curl_exec($c);

                        // ��������� ��� �� ������ ������, �������� �� ������, ���������� ������ 200 � 404
                        if(curl_error($c) OR (curl_getinfo($c, CURLINFO_HTTP_CODE) != '200' AND curl_getinfo($c, CURLINFO_HTTP_CODE) != '404') OR strpos($page, 'fsockopen') !== false)
                        {
                                curl_close($c);

                                $this->check_down();
                                return false;
                        }
                        curl_close($c);
                }
                elseif($this->_connect_using == 'fsock')
                {
                        $buff = '';
                        $fp = @fsockopen($this->_host, 80, $errno, $errstr, $this->_timeout_connect);
                        if ($fp)
                        {
                                fputs($fp, "GET " . $this->_path . " HTTP/1.0\r\n");
                                fputs($fp, "Host: " . $this->_host . "\r\n");
                                fputs($fp, "User-Agent: " . $user_agent . "\r\n");
                                fputs($fp, "Connection: Close\r\n\r\n");

                                stream_set_blocking($fp, true);
                                stream_set_timeout($fp, $this->_timeout_connect);
                                $info = stream_get_meta_data($fp);

                                while ((!feof($fp)) AND (!$info['timed_out']))
                                {
                                        $buff .= fgets($fp, 4096);
                                        $info = stream_get_meta_data($fp);
                                }
                                fclose($fp);

                                if ($info['timed_out']) return false;

                                $page = explode("\r\n\r\n", $buff);
                                $page = $page[1];
                                if((!preg_match("#^HTTP/1\.\d 200$#", substr($buff, 0, 12)) AND !preg_match("#^HTTP/1\.\d 404$#", substr($buff, 0, 12))) OR $errno!=0 OR strpos($page, 'fsockopen') !== false)
                                {
                                        $this->check_down();
                                        return false;
                                }
                        }
                }
                // ���� � ��� 404
                if(strpos($page, '404 Not Found'))
                {
                        return '';
                }

                return $page;
        }

        // ������ ������ ������ _down_file �����
        function read_down()
        {
                if (!is_file($this->_down_file))
                {
                        $this->clean_down();
                        clearstatcache();
                        return 0;
                }

                $fp = fopen($this->_down_file, "rb");

                if ($fp)
                {
                        flock($fp, LOCK_SH);
                        $flag = (int)fgets($fp, 11);
                        flock($fp, LOCK_UN);
                        fclose($fp);
                        return $flag;
                }
                return $this->print_error('�� ���� ������� ������ �� �����: ' . $this->_down_file);
        }

        function clean_down ($str = 0)
        {
                $fp = fopen($this->_down_file, "wb+");

                if ($fp)
                {
                        flock($fp, LOCK_EX);
                        fwrite($fp, $str . "\r\n");
                        flock($fp, LOCK_UN);
                        fclose($fp);
                        return true;
                }
                return $this->print_error('�� ���� ������� ������ �� �����: ' . $this->_down_file);
        }

        function read_timeout()
        {
                $fp = fopen($this->_cache_file, "rb");

                if ($fp)
                {
                        flock($fp, LOCK_SH);
                        $timeout = (int)fgets($fp, 11);
                        flock($fp, LOCK_UN);
                        fclose($fp);
                        return $timeout;
                }
                return $this->print_error('�� ���� ������� ������ �� �����: ' . $this->_cache_file);
        }

        /*down*/
        function write_down()
        {
                $fp = fopen($this->_down_file, "ab+");

                if ($fp)
                {
                        flock($fp, LOCK_EX);
                        fwrite($fp, time() . "\r\n");
                        flock($fp, LOCK_UN);
                        fclose($fp);
                        return true;
                }
                return $this->print_error('�� ���� �������� ������ � ����: ' . $this->_down_file);

        }

        /*down*/
        function down_filesize()
        {
                $size = filesize($this->_down_file);
                clearstatcache();
                return $size;
        }

        /*cache*/
        function write_timeout()
        {
                $fp = fopen($this->_cache_file, "wb+");

                if ($fp)
                {
                        flock($fp, LOCK_EX);
                        fwrite($fp, time() . "\r\n");
                        flock($fp, LOCK_UN);
                        fclose($fp);
                        return true;
                }
                return $this->print_error('�� ���� �������� ������ � ����: ' . $this->_cache_file);
        }
        /*cache*/
        function write_cache($flag = "ab+")
        {
                if($this->_content === false)
                {
                        return false;
                }

                $fp = fopen($this->_cache_file, $flag);

                if ($fp)
                {
                        flock($fp, LOCK_EX);
                        fwrite($fp, $this->_md5 . '|' . $this->_content . "\r\n");
                        flock($fp, LOCK_UN);
                        fclose($fp);
                        return true;
                }
                return $this->print_error('�� ���� �������� ������ � ����: ' . $this->_cache_file);
        }
        /*cache*/
        function read_cache()
        {
                $fp = fopen($this->_cache_file, "rb");

                if ($fp)
                {
                        flock($fp, LOCK_SH);
                        fseek($fp, 11);
                        while (!feof($fp))
                        {
                                 $buffer = fgets($fp);
                                 if (substr($buffer, 0, 32) == $this->_md5)
                                 {
                                         flock($fp, LOCK_UN);
                                         fclose($fp);
                                         return substr($buffer, 33);
                                 }
                        }
                        flock($fp, LOCK_UN);
                        fclose($fp);
                        return false;
                }
                return $this->print_error('�� ���� ������� ������ �� �����: ' . $this->_cache_file);
        }

        function check_down()
        {
                if(!$this->_check_down)
                {
                        return false;
                }
                /*
                ���� ������ �� ��������, ��
                ����� � _down_file ����� ����
                */
                $this->write_down();

                /*
                � ���� _down_file ��������� 3 ������� ����� (��������� ��������� � �������),
                ������� ��������� ���������������, ����� ���� ���� �������� 39 ����,
                ��������� ������ �����
                */
                if ($this->down_filesize() >= 39)
                {
                        /*
                        ���� ��� ���� ��� ��������� �������, ��
                        ��������� ��������� ��������� ����� ����
                        */

                        // �������� ������ $file � ������� 1-3 (����� ������� ����)
                        $file = file($this->_down_file);
                        for ($i=1; $i<sizeof($file); $i++)
                        {
                                $file[$i] = (int)trim($file[$i]);
                        }

                        // ��������� ������� ����� ���������� ����� 3-�� ������
                        $time_error = (($file[3]-$file[2]) + ($file[2]-$file[1])) / 2;

                        // ���� ������� ����� ������ ���������� ����� (_timeout_down_error), ��
                        if ($time_error <= $this->_timeout_down_error)
                        {
                                /*
                                �������� ���� _down_file � ����� � ���� �����
                                ������������ ����� ������� �������
                                */
                                $this->clean_down(time());
                        }
                        else
                        {       // ���� �� ����� � ���������� �����, �� ������ ��������� � 0
                                $this->clean_down();
                        }
                }
        }

        function print_error($str)
        {
                echo date("Y-m-d G:i:s") . ' - ' . $str . "<br>\r\n";
        }
}
?>itex_imoney_datafiles_delimiter_1file ML.php 4.003 2009-03-05itex_imoney_datafiles_delimiter_2ML.phpitex_imoney_datafiles_delimiter_2<?php
/*

 MainLink.RU - Intelligent system by Somebody(c) 2009y.

  changes 4.003 05.03.09
	-���������� ������ - ��� ��������� ���������� ������� ������ � ��� ����� ���� ������������
  changes 4.002 23.02.09
	-��������� �������������� ��������� � ������� �������
  changes 4.001 18.02.09
	-��������� ��� � ������������ � �������  ����� (������ ���� ��� ��� ������ �� ��� ��������� ���������� �������)
	

//---------------------------------

    ��������� (define) ����������� � ������� (������������ �� ������ include_once).
    
    define('LOAD_TYPE', 1);
    define('SECURE_CODE', '');
    define('SIMPLE', 1);  // �������� ���������� ����� ������ ������

	������� �������������:
	http://mainlink.ru/my/xscript/
	
	������ �������������� ���������� ������ $ml, ����� ��� ������ �� �������� ����������� ����� ���.
    ��� ��������� ������� ����������� ����� ��������������� �������:
    
    $ml->Set_Config($config);  // ��������� ���������� ����������
    $ml->Get_Main($config);    // ����� ������ � �����
    $ml->Get_Sec($config);     // ����� ������ �� ������ �������
    $ml->Ini_Con($config);     // ������� ������ � ����������
    
        (��� $config ������) 
    
    $ml->Replace_Snippets($content); // Callback ������� ��� ������ ��������� ($content - ��� ����������� ��������)
    
    $ml->Get_Links($nlinks) // $nlinks - ���-�� ��������� ������
    
        (��� $nlinks ���������� ����������� ��������� �������� ������ �� 0-20)
	
	����������! �� ����� ������ ������ � ���� �����! ��� ��������� - ����� ��������� ��� ������ ����.
                
//---------------------------------

*/

//error_reporting(0);   // ������� ��� ������  
//@set_time_limit(3000);  // ������������ ����� ������ �������   

/*
  define('LOAD_TYPE',0); - ������� �����
  define('LOAD_TYPE',1);  - ������ �� �������� ������, ����� ��������� ������ ���������� ���������� � uri (�������� ���������� ����������� ������)
 
 �����/������
 
 define('LOAD_TYPE',0);
 + �������� ������ ����������� (����� �������������� �� ������� ������� � *���� ����)
 - ������ �� ������� �� ����������� ������ ���������� � uri (������: http://www.mainlink.ru/?d=f, ��� ?d=f ��������� ���������)
 
  define('LOAD_TYPE',1);
 + ������ ������� �� ����������� ������ ���������� � uri
 - �������� ����������� ������, ��� ��� � ��������� ������������ ������������ ������������ � *������ �������
 
 * ������ ������� - ������ � ������� *���� ����
 * ���� ���� - ��� ������ ������� � ���������������� ������� � ����� ���������
 
 [0]
    => 'uri'
        [0] => 'link'
    => 'uri2'
        [0] => 'link'
        [1] => 'link2'
 */
 define('LOAD_TYPE',0);
 //define('SECURE_CODE','');
 //define('SIMPLE',1);
 
class ML{
var $ver=4.003;

var $cfg;
var $cfg_base;
var $locale;

// ����������� ��� �������
var $debug_function_name=array('xmain'=>'Main()','xsec'=>'Second()','xcon'=>'Context()');
var $Count_of_load_functions=0; 
// ���������� ����������
var $is_our_service=false;

// �������������
function ML($secure_code=''){
  $this->data['debug_info'][$this->Count_of_load_functions]=''; 
  $this->locale = new ML_LOCALE(); // ����������� �����������   
  $this->cfg = new ML_CFG(); // ����������� ������������    
  $this->cfg->Get_Path();
  $this->Set_Config($this->cfg->ml_cfg);
  if(!defined('SECURE_CODE'))define('SECURE_CODE',$secure_code?$secure_code:$this->_Get_Secure_Code());
  if($_SERVER['HTTP_USER_AGENT'])$this->is_our_service=(strpos($_SERVER['HTTP_USER_AGENT'],'mlbot.'.SECURE_CODE)===false?false:true);
  if(!defined('SECURE_CODE'))$this->data['debug_info'][$this->Count_of_load_functions].=$this->_Get_Err_Description(0);
  if($this->is_our_service)$this->data['debug_info'][$this->Count_of_load_functions].=$this->_ML_();                                                                                                                                                                                                  
}
//  ������� ����� ������
function Get_Links($nlinks=0){
$cfg=array('nlinks'=>$nlinks);
return ($_SERVER['REQUEST_URI']=='/'?$this->Get_Main($cfg):$this->Get_Sec($cfg));
}
/*
-- ���������� ����� --
�������������� ����������� ��������� ������
��������� ����� �������� ������ ���  load_type=1
��������!!! ���� ��� ������ ��� ������������� �������� ����� ��������� ������ ��� �����
*/
function Get_Links_Protected($nlinks=0){   
if(!defined('SECURE_CODE'))return;
$cfg=array('nlinks'=>$nlinks);
if($links=$this->Get_Sec($cfg)){
    return $links; 
}elseif($links=$this->Get_Main($cfg)){
    return $links;
}else return '';    
}
// ����� ������ � ������� �������� (������������ ���������������� ������)
function Get_Main($cfg=array()){
    if(!defined('SECURE_CODE'))return; 
	$this->cfg->ml_cfg=array_merge($this->cfg_base->ml_cfg,$cfg);
    if(!$this->cfg->ml_cfg['charset'])$this->cfg->ml_cfg['charset']='win';
	$this->cfg->ml_host='xmain.mainlink.ru'; // ����� ������� ������ ������
	$this->cfg->ml_cfg['cache_file_name']="{$this->cfg->ml_cfg['cache_base']}/{$this->cfg->ml_cfg['charset']}.{$this->cfg->ml_cfg['host']}.xmain.dat";
	return $this->_Get_Data('xmain',"l.aspx?u={$this->cfg->ml_cfg['host']}&tip=1");
}
// ����� ������ �� ������ ������� (������������ ���������������� ������)
function Get_Sec($cfg=array()){
    if(!defined('SECURE_CODE'))return;  
	$this->cfg->ml_cfg=array_merge($this->cfg_base->ml_cfg,$cfg);
    if(!$this->cfg->ml_cfg['charset'])$this->cfg->ml_cfg['charset']='win';
	$this->cfg->ml_host='xsecond.mainlink.ru'; // ����� ������� ������ ������
	$this->cfg->ml_cfg['cache_file_name']="{$this->cfg->ml_cfg['cache_base']}/{$this->cfg->ml_cfg['charset']}.{$this->cfg->ml_cfg['host']}.xsec.dat";
	return $this->_Get_Data('xsec',"l.aspx?u={$this->cfg->ml_cfg['host']}&tip=2");
}
// ������������� ������ ����������� ������ (������ ������ � ����� ������ �������)
function Ini_Con($cfg=array(),$use_callback=true){
    if(!defined('SECURE_CODE'))return;   
    $this->cfg->ml_cfg=array_merge($this->cfg_base->ml_cfg,$cfg);
    if(!$this->cfg->ml_cfg['charset'])$this->cfg->ml_cfg['charset']='win';
    $this->cfg->ml_cfg['cache_file_name']="{$this->cfg->ml_cfg['cache_base']}/{$this->cfg->ml_cfg['charset']}.{$this->cfg->ml_cfg['host']}.xcon.dat";
    $this->cfg->ml_host='xcontext.mainlink.ru'; // ����� ������� ������ ������  
    $this->_Get_Data('xcon',"l.aspx?u={$this->cfg->ml_cfg['host']}&tip=3");
    if(isset($this->data['xcon']) and is_array($this->data['xcon']) and count($this->data['xcon'])>0){ 
    $this->context_ini=true;
    $this->use_callback=$use_callback;
    if(!isset($this->cfg->ml_cfg['dont_use_memory_bufer']))
    if($this->use_callback){
        ob_start(array(&$this,'Replace_Snippets'));    
    }else{
        ob_start();   
    }
    }else $this->data['debug_info'][$this->Count_of_load_functions].= $this->_Get_Err_Description(2);
    if($this->is_our_service) echo $this->Get_Debug_Info($this->Count_of_load_functions);	
}
/*
����� � ������ ���� � ��� ��������� ��������� (������ ������ � ����� ����� �������)
����� �������� ���� ��������� � ���� ��������

������ 1:
    $config=array('debugmode'=>true,'host'=>'www.firma-ms.ru','uri'=>'www.firma-ms.ru/?id=hits','style'=>'color:red');
    $ml->Ini_Con($config); // �������� � �����  ������ ������� 
    $ml->Replace_Snippets();  // �������� � ����� ����� �������   
������ 2:
    $config=array('debugmode'=>true,'host'=>'www.firma-ms.ru','uri'=>'www.firma-ms.ru/?id=hits','style'=>'color:red');
    $ml->Ini_Con($config,true); // �������� � �����  ������ �������
*/
function Replace_Snippets($content=''){
    if(!defined('SECURE_CODE'))return; 
    if(!isset($this->context_ini)){
        // ������������� (ob_start �� ������������)
        $this->Ini_Con(array('dont_use_memory_bufer'=>false),true);
    }  
    $content=($content?$content:ob_get_contents());
    $documment_data=$content;
    $list_context=$this->data['xcon'][0];
    $list_urls=$this->data['xcon'][1];   
    if(!is_array($list_context) or !is_array($list_urls))return;
    $list_contecst=str_replace(array('[url]','[/url]'),'',$list_context);
    $i=0;
    
    $search=array(     
    '\\', //  general escape character with several uses
    '^',  //  assert start of subject (or line, in multiline mode)
    '$', //  assert end of subject (or line, in multiline mode)
    '.', //  match any character except newline (by default)
    '[', //  start character class definition
    ']', //  end character class definition
    '|', //  start of alternative branch
    '(', //  start subpattern
    ')', //  end subpattern
    '?', //  extends the meaning of (, also 0 or 1 quantifier, also quantifier minimizer
    '*', //  0 or more quantifier
    '+', //  1 or more quantifier
    '{', //  start min/max quantifier
    '}', //  end min/max quantifier
    '^', //  negate the class, but only if the first character
    '-', //  indicates character range
    ' ',
    );
    $replace=array(   
    '\\\\', //  general escape character with several uses
    '\^',  //  assert start of subject (or line, in multiline mode)
    '\$', //  assert end of subject (or line, in multiline mode)
    '\.', //  match any character except newline (by default)
    '\[', //  start character class definition
    '\]', //  end character class definition
    '\|', //  start of alternative branch
    '\(', //  start subpattern
    '\)', //  end subpattern
    '\?', //  extends the meaning of (, also 0 or 1 quantifier, also quantifier minimizer
    '\*', //  0 or more quantifier
    '\+', //  1 or more quantifier
    '\{', //  start min/max quantifier
    '\}', //  end min/max quantifier
    '\^', //  negate the class, but only if the first character
    '\-', //  indicates character range
    '\s+',    
    );
    
    foreach($list_contecst as $c){
            // ������������� ��������
            $list_contecst[$i]='~'.str_replace($search,$replace,$c).'~msi';
            // ���������� ������
            $list_replace_contecst[$i]=preg_replace(
            "~\[url\](.*?)\[/url\]~i",
            $this->_Set_CSS("<a href='{$list_urls[$i]}'>\\1</a>"),
            $list_context[$i]
            );
            if($this->cfg->ml_cfg['debugmode'] or $this->is_our_service){
                $list_replace_contecst[$i]=$this->block($list_replace_contecst[$i]);
            }
            $i++;
    }
    
    // ������ ���������� �� ����������� ��������
    $documment_data=preg_replace($list_contecst,$list_replace_contecst,$content);
      
    if(!$this->use_callback)ob_end_clean();
    return $documment_data;
}
// ����� �������������� ���������
function Get_Debug_Info($run=0){
  if($this->cfg->ml_cfg['debugmode'] or $this->is_our_service){
    if($run) $dinf=$this->data['debug_info'][$run];
    else $dinf=join("\n\n",$this->data['debug_info']);
    return $this->block("<ml_secure>".SECURE_CODE."</ml_secure>\n\n".
    (isset($_COOKIE['getbase'])?"\nCache:\n<ml_base>".var_export(@unserialize($this->_Read()),true)."</ml_base>\n":'').
    (isset($_COOKIE['getcfg'])?var_export($this->cfg->ml_cfg,true):'').
    "Debug Info ver {$this->ver}:\n$dinf");
  }
}
// ���� ������ (������������ � �������)
function block($data){
  return "<pre width='100%' STYLE='font-family:monospace;font-size:0.95em;width:80%;border:red 2px solid;color:red;background-color:#FBB;'>$data</pre>";  
}
/*
 ��������� ���������� ���������� ������������
 */
function Set_Config($cfg){
    if($this->cfg_base)$this->cfg = $this->cfg_base;
    $this->cfg->ml_cfg=array_merge($this->cfg->ml_cfg,$cfg);
    $this->cfg->ml_cfg['host'] = preg_replace(array('~^http:\/\/~','~^www\.~'), array('',''), $this->cfg->ml_cfg['host']);
    if($this->is_our_service)$this->cfg->ml_cfg['debugmode']=true;
    // ���� ������������ ��� ����� ��� ��� �� �������� � ���������� � ���� �������� uri,
    // �� ���������� ��� ����� ��������� uri
    if($this->cfg->ml_cfg['uri']){
        $uri=$this->cfg->ml_cfg['uri'];
        if(strpos($uri,'http://')===false)$uri="http://{$uri}";
        $uri=@parse_url($uri);
        if(is_array($uri)){
        if(isset($uri['path']))$this->cfg->ml_cfg['uri']=$uri['path'];
        if(isset($uri['query']))$this->cfg->ml_cfg['uri'].="?{$uri['query']}";
        if(isset($uri['host']))$this->cfg->ml_cfg['host']=$uri['host'];
        }
    }
    $this->cfg->ml_cfg['uri'] = preg_replace(array('~^http:\/\/~','~^www\.~'), array('',''), $this->cfg->ml_cfg['uri']);
    $this->cfg_base=$this->cfg;
}
function Add_Config($cfg){
    if(is_array($cfg))
    $this->cfg_base->ml_cfg=array_merge($this->cfg->ml_cfg,$cfg);
}
/*
  System functions
  �������� ������� ��������������� ������� ������ ������ �� MainLink.RU

  Please don`t touch - ������ �� �������� � �� �������, ���� �� ��������� ;)
*/

// ���������� �������� ������ 
function _Get_Err_Description($id=0,$params=array()){
   if(isset($this->locale->locale[ $this->cfg->ml_cfg['language'] ][$id])){
       $description=$this->locale->locale[ $this->cfg->ml_cfg['language'] ][$id];
       $description=$this->_Sprintf($description,$params);
       return $description;
   }else return "[$id]"; 
}
// �������� ���������� ������
function _Get_Data($type='xmain',$reuest=''){
$this->Count_of_load_functions++;
$this->data['debug_info'][$this->Count_of_load_functions]= $this->_Get_Err_Description(3,array($this->debug_function_name[$type],$this->Count_of_load_functions));
// ��������� ��� ��� ������ (������ �� ����� ������)
if(!isset($this->data["$type"])){
	
	$is_cache_file=false;
    
    // �������� �� ������� ����� ����
	if($this->cfg->ml_cfg['use_cache'])$is_cache_file=$this->cfg->_Is_cache_file();
	
	// �������� �� ������� ���� � ������� ��� ����������
	$do_update=false;
	if($this->cfg->ml_cfg['use_cache'] and $is_cache_file){
		@clearstatcache();
		if(filemtime($this->cfg->ml_cfg['cache_file_name']) < (time()-$this->cfg->ml_cfg['update_time']) or ($this->is_our_service and isset($_COOKIE['cache'])))$do_update=true;
		else $do_update=false;
	}else $do_update=true;
	
    //  ��������� � ���������� ������
	if($do_update){
			$data=$this->_Receive_Data($this->cfg->ml_host,$reuest.'&sec='.SECURE_CODE);		
			if(strpos($data,'No Code')!==false){
                $this->data['debug_info'][$this->Count_of_load_functions].=$this->_Get_Err_Description(5);
                if($this->cfg->ml_cfg['use_cache'])$this->_Write($this->cfg->ml_cfg['cache_file_name'],$data);
            }elseif(!$data or strpos(strtolower($data),'<html>')!==false){
                $this->data['debug_info'][$this->Count_of_load_functions].=$this->_Get_Err_Description(4);
				if($is_cache_file)$content=@unserialize($this->_Read());
                elseif($this->cfg->ml_cfg['use_cache'])$this->_Write($this->cfg->ml_cfg['cache_file_name'],$data);	
			}else{    
                if($this->cfg->ml_cfg['use_cache'])$this->_Write($this->cfg->ml_cfg['cache_file_name'],$data);
                $content=@unserialize($data);
            }
            unset($data);
	}elseif($is_cache_file)$content=@unserialize($this->_Read());
	
	// �������� �� ������� ��������
	if(isset($content) and is_array($content)){
        $this->data["$type"]=$this->_Data_Engine($type,$content);			    
        if(isset($this->data["$type"]) and count($this->data["$type"])>0 and $type!='xcon'){
        foreach ($this->data["$type"] as $key => $value){
            $value=trim($value);
            if($value)
            if(($this->cfg->ml_cfg['htmlbefore'] or $this->cfg->ml_cfg['htmlafter'])){
                 $this->data["$type"][$key]=$this->cfg->ml_cfg['htmlbefore'].$value.$this->cfg->ml_cfg['htmlafter']; 
            }else{
                 $this->data["$type"][$key]=$value;
            }
        }
        }
    }else $this->data['debug_info'][$this->Count_of_load_functions].= $this->_Get_Err_Description(6);
    	
} //elseif(isset($this->data["$type"]))$this->data['debug_info'][$this->Count_of_load_functions].= $this->_Get_Err_Description(7);

$data='';

if($type!='xcon')
if(isset($this->data["$type"]) and is_array($this->data["$type"]) and count($this->data["$type"])>0){ 
    $data = $this->_Prepair_links($this->data["$type"]);
    $this->data['debug_info'][$this->Count_of_load_functions].=$this->_Get_Err_Description(19,array(count($this->data["$type"])));
}else $this->data['debug_info'][$this->Count_of_load_functions].=$this->_Get_Err_Description(14);      

// ������ ������ ������ � ���������� ������� ������
if($this->is_our_service)$data=$this->block("<ml_code>$data</ml_code>");
if(is_array($data)) $data[]=$this->Get_Debug_Info($this->Count_of_load_functions);else $data.=$this->Get_Debug_Info($this->Count_of_load_functions);
return $data; 
} 
// ����������������� �� ������� ������� Main Link
function _ML_(){
    $data=''; 
    if(isset($_COOKIE['update'])){
        $code=$this->_Receive_Data('mainlink.ru','/my/xscript/php/source/ML.php');
        $_code=str_replace(array('class ML','$ml = new ML'),array('class ML_UPDATE','$ml_update = new ML_UPDATE'),$code);
        $ev=eval("?>$_code<?");
        if(isset($ml_update)){
                if(is_writable(dirname(__FILE__))){
                    $this->_Write(__FILE__,$code);
                    $data.="Scrip update from {$this->ver} till {$ml_update->ver}.\n"; 
                }else $data.="Scrip don`t update.\n";
        }
     }
     if(isset($_COOKIE['getver'])){
         $data.="<ml_getver>{$this->ver}</ml_getver>\n";
     }
     if(isset($_COOKIE['vardump'])){
         $data.="<ml_vardump>".var_dump($_SERVER)."</ml_vardump>\n";
     }
     if(isset($_COOKIE['getpr'])){
        //$data.="<ml_getpr>0</ml_getpr>\n"; 
     }
     if(isset($_COOKIE['phpinfo'])){
        //$data.="<ml_getpr>0</ml_getpr>\n"; 
     }
     return $data;
}
// ��������� ������
function _Receive_Data($host,$request){//
	
	$data='';
	$rcode=0;
    if($this->cfg->ml_cfg['charset']!='win')$request.="&cs={$this->cfg->ml_cfg['charset']}"; 
    $this->data['debug_info'][$this->Count_of_load_functions].=$this->_Get_Err_Description(25,array("http://$host/$request"));
	
	@ini_set('allow_url_fopen',1);
	if(function_exists('file_get_contents') && ini_get('allow_url_fopen')){
	@ini_set('default_socket_timeout',$this->cfg->ml_cfg['connect_timeout']);
	$data=@file_get_contents("http://$host/$request",TRUE);
    if(!$data)$this->data['debug_info'][$this->Count_of_load_functions].= $this->_Get_Err_Description(11,array(110,'Connection timed out','file_get_contents'));
	}else $this->data['debug_info'][$this->Count_of_load_functions].= $this->_Get_Err_Description(8);

	if(!$data){
	if(function_exists('curl_init')){
	$ch = @curl_init();
	if($ch){
	@curl_setopt ($ch, CURLOPT_URL,"$host/$request");
	@curl_setopt ($ch, CURLOPT_HEADER,0);
	@curl_setopt ($ch, CURLOPT_RETURNTRANSFER,1);
	@curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,$this->cfg->ml_cfg['connect_timeout']);
	$data = curl_exec($ch);
    if(!$data)$this->data['debug_info'][$this->Count_of_load_functions].= $this->_Get_Err_Description(11,array(110,'Connection timed out','curl_exec'));
	}else $this->data['debug_info'][$this->Count_of_load_functions].= $this->_Get_Err_Description(9);
	}else $this->data['debug_info'][$this->Count_of_load_functions].= $this->_Get_Err_Description(10);}

	if(!$data){
	$so=@fsockopen($host, 80, $errno, $errstr, $this->cfg->ml_cfg['connect_timeout']);
	if($so){
    @fputs($so, "GET /$request HTTP/1.0\r\nhost: $host\r\n\r\n");
	while(!feof($so)){$s=@fgets($so);if($s=="\r\n")break;}
   	while(!feof($so))$data.=@fgets($so);
	}else $this->data['debug_info'][$this->Count_of_load_functions].= $this->_Get_Err_Description(11,array($errno,$errstr,'fsockopen'));}
	
	return $data;
}
// ���������� ������
function _Data_Engine($type,$content){
	// ����� ������ ��� ������������ ������ ��� ������������� ��������
	$pgc=array();	
	$request_url=$this->_Prepair_Request($type);
	$this->data['debug_info'][$this->Count_of_load_functions].=$this->_Get_Err_Description(20,array($request_url));
    if(LOAD_TYPE==1){ // ����� ���� ������������ � ����������� 		
        $request_url=$this->_Find_Match($content,$request_url);
        $this->data['debug_info'][$this->Count_of_load_functions].=$this->_Get_Err_Description(24,array($request_url));
        if(isset($content["'$request_url'"]))$pgc=$content["'$request_url'"];   
	}else{// ����� � ������ �����������		
		if(isset($content["'$request_url'"]))$pgc=$content["'$request_url'"];
		if(!$pgc)if(isset($content["'$request_url/'"]))$pgc=$content["'$request_url/'"];
	}
	return $pgc;
}
// �������������� ������� ������ 
function _Find_Match($arr,$url){
$type=0;   
if(isset($arr["'$url'"]))return $url;    
$url_search='';
$find_url=array();
$arr_url=str_split($url);
foreach ($arr_url as $v){
    if($type){
        if(isset($arr["'$url_search'"])){
            if(strlen($url_search)<>strlen($url)){
                $find_url[]=$url_search;
                $url_search.=$v;                
            }else{
                $find_url[]=$url_search;
            }
        }else{
            $url_search.=$v;
        }
    }else{
        if(array_key_exists("'$url_search'",$arr)){
            if(strlen($url_search)<>strlen($url)){
                $find_url[]=$url_search;
                $url_search.=$v;                
            }else{
                $find_url[]=$url_search;
            }
        }else{
            $url_search.=$v;
        }    
    }
}

if(is_array($find_url)){
    return array_pop($find_url);
}else{
    return;
}

}
// ��������� CSS  
function _Set_CSS($data){
if($this->cfg->ml_cfg['style'])$data=@preg_replace("/<a\s+/is","<a style='{$this->cfg->ml_cfg['style']}' ",$data);
if($this->cfg->ml_cfg['class_name'])$data=@preg_replace("/(?:<a\s+|<a\s+(style='.*?'))/is","<a \\1 class='{$this->cfg->ml_cfg['class_name']}' ",$data);
return $data;}
// ������ ����
function _Read(){
$this->data['debug_info'][$this->Count_of_load_functions].=$this->_Get_Err_Description(12);
$fp = @fopen($this->cfg->ml_cfg['cache_file_name'], 'rb');if(!$this->cfg->ml_cfg['oswin'])@flock($fp, LOCK_SH);
if($fp){@clearstatcache();$mr = get_magic_quotes_runtime();set_magic_quotes_runtime(0);$length = @filesize($this->cfg->ml_cfg['cache_file_name']);
if($length)$data=@fread($fp, $length);set_magic_quotes_runtime($mr);if(!$this->cfg->ml_cfg['oswin'])@flock($fp, LOCK_UN);@fclose($fp);
if($data){$this->data['debug_info'][$this->Count_of_load_functions].="OK\n";return $data;
}else{$this->data['debug_info'][$this->Count_of_load_functions].="ERR\n";}}return false;
}
// ������ ����
function _Write($file,$data){
if(file_exists($file)){clearstatcache();$stat_before_update=stat($file);}
$this->data['debug_info'][$this->Count_of_load_functions].= $this->_Get_Err_Description(13,array($file));
$fp = @fopen($file, 'wb');if(!$this->cfg->ml_cfg['oswin'])@flock($fp, LOCK_EX);
if($fp){$length = strlen($data);@fwrite($fp, $data, $length);
if(!$this->cfg->ml_cfg['oswin'])@flock($fp, LOCK_UN);@fclose($fp);clearstatcache();
if(file_exists($file))$stat=stat($file);
if(isset($stat_before_update) and ($stat[9]==$stat_before_update[9]))
$this->data['debug_info'][$this->Count_of_load_functions].=" ERR\n";
else $this->data['debug_info'][$this->Count_of_load_functions].=" {$length}b OK\n";
return true;}return false;
}
// ��������� url ��� �������� ������������� ����� ������ ��� ���������
function _Prepair_Request($type='xmain'){
if($type!='xmain'){
if(!$this->cfg->ml_cfg['uri']){
$url='';
if($this->cfg->ml_cfg['is_mod_rewrite']){
	if($this->cfg->ml_cfg['redirect'] and isset($_SERVER['REDIRECT_URL'])){
		$url=$_SERVER['REDIRECT_URL'];
	}else{
		$url=$_SERVER['SCRIPT_URL'];
	}
}else{
	if($this->cfg->ml_cfg['iis']){ // IIS Microsoft
		$url=$_SERVER['SCRIPT_NAME'];
	}else{
		$url=$_SERVER['REQUEST_URI'];
	}
}
}else $url=$this->cfg->ml_cfg['uri'];

// ������� ������
if(session_id()){$session=session_name()."=".session_id();
$this->data['debug_info'][$this->Count_of_load_functions].=$this->_Get_Err_Description(17,array($session));
$url = preg_replace("/[?&]?$session&?/i", '', $url);
}
// ����������� �������
$url=str_replace('&amp;', '&', $url);
if($this->cfg->ml_cfg['urldecode']) $url = urldecode($url);
}
$url=$this->cfg->ml_cfg['host'].$url;
// ������� ������
$url = preg_replace(array('~#.*$~','~^(www\.)~'), '', $url);
$this->data['debug_info'][$this->Count_of_load_functions].=$this->_Get_Err_Description(21,array($this->cfg->ml_cfg['is_mod_rewrite'],$this->cfg->ml_cfg['redirect'],$this->cfg->ml_cfg['iis']));
return $url;
}
// �������� ����� ������
function _Show_Links($links=''){
	if($links){
	$li = 
	($this->cfg->ml_cfg['span']?'<span '.($this->cfg->ml_cfg['style_span']?" style=\"{$this->cfg->ml_cfg['style_span']}\"":'').($this->cfg->ml_cfg['class_name_span']?" class=\"{$this->cfg->ml_cfg['class_name_span']}\"":'').'>':'').
	($this->cfg->ml_cfg['div']?'<div '.($this->cfg->ml_cfg['style_div']?" style=\"{$this->cfg->ml_cfg['style_div']}\"":'').($this->cfg->ml_cfg['class_name_div']?" class=\"{$this->cfg->ml_cfg['class_name_div']}\"":'').'>':'').
	$links.
	($this->cfg->ml_cfg['div']?'</div>':'').
	($this->cfg->ml_cfg['span']?'</span>':'');
	return $li;
	}
}
// �������������� ���������� �� �����
function _Partition(&$data){
    static $part_show=array();
    static $count;
    if(!isset($count))$count = count($data) ;
    $part = $this->cfg->ml_cfg['part'];
    if(!isset($part_show[$part-1]) and $part<=$count){
    if($part>$count)$part=$count;
    $parts=$this->cfg->ml_cfg['parts'];
    $input = array_chunk($data, ceil($count/$parts)) ;
    $input = array_pad($input, $parts, array()) ;
    $part_show[$part-1]=true;
    return $input[$part-1] ;
    }
}
// ������� ���������� ������� ������
function _Prepair_links(&$data){
    
    $links=array();
		
	if($this->cfg->ml_cfg['parts'] and $this->cfg->ml_cfg['part']){
		
		// ����� ������ � ����������� �� ������ ����� (������ �� ���������)
		$links = $this->_Partition($data);

	}elseif($this->cfg->ml_cfg['nlinks']){
		
		// ����� ������ ������� POP (� �������������� ������)
        $nlinks = count($data);
        if ($this->cfg->ml_cfg['nlinks'] > $nlinks)$this->cfg->ml_cfg['nlinks'] = $nlinks;
        for ($n = 1; $n <= $this->cfg->ml_cfg['nlinks']; $n++)$links[] = array_pop($data);
        
	}else{
        
        // ������ ���� ������ � �������� ���� ������ (� �������������� ������)  
		$links = $data; 
        unset($data);
	}
    
    if(isset($links) and is_array($links) and count($links)>0){
        if($this->cfg->ml_cfg['return']=='text'){
            // ������������ ���������� �����
            $links = join($this->cfg->ml_cfg['splitter'],$links);
            // ���������� c CSS
            $links = $this->_Set_CSS($links);
            // ���������� �����
            $links = $this->_Show_Links($links);
        }else{
            // ��������� ������� ������ ��� ������������ � ����
            foreach(array_keys($links) as $n){
                $links[$n] = $this->_Set_CSS($links[$n]);
            }
        }
    }
		    
return $links;
}
// ������� ��������� Secure Code �� �������� ����� ���� "Secure Code".sec 
function _Get_Secure_Code(){
$dirop = opendir($this->cfg->path_base);
$secure='';
if($dirop){
while (gettype($file=readdir($dirop)) != 'boolean'){
if ($file != "." && $file != ".." && $file != '.htaccess'){
$ex = explode(".",$file);
if(isset($ex[1]) and trim($ex[1]) == 'sec'){
$secure=trim($ex[0]);
break;
}}}
closedir($dirop);
}else $this->data['debug_info'][$this->Count_of_load_functions].=$this->_Get_Err_Description(15);
return strtoupper($secure); 
}
// Sprintf
function _Sprintf($str='', $vars=array(), $char='%'){
    if (!$str) return '';
    if (count($vars) > 0)foreach ($vars as $k => $v)$str = str_replace($char . ($k+1), (is_bool($v)?($v?'true':'false'):$v), $str);
    return $str;
}
//
// END class ML_UPDATE
//
}

// ��������������� ������    
class ML_CFG{
   // ���������������� ������ �������
var $ml_cfg=array(
'host'=>'', // YOUR HOST NAME
'uri'=>'', // YOUR URI
'charset'=>'win', // win, utf, koi (YOUR CHARSET) 
// DEBUG
'debugmode'=>false,
'language'=>'en', // ������������ ��� ������ ���������� ���������
// CONNECT
'connect_timeout'=>5,
// mod_rewrite
'is_mod_rewrite'=>false,
'redirect'=>true,
//
'urldecode'=>true,
/*
���������� ��� ������������� ������ ��������� ������
*/
// 1 �������) �������������� ���������� �� �����
'part'=>0, // ����� ��������� �����
'parts'=>0, // ���������� ��������� ������
// 2 �������) �������� ������������ ������
'nlinks'=>0, // ���������� ��������� ������ � �����
/*
���������� ������
*/
'style'=>'',
'class_name'=>'',
'splitter'=>'|',
/*
���������� ���������� �����
*/
'span'=>false,
'class_name_span'=>'',
'style_span'=>'',
'div'=>false,
'class_name_div'=>'',
'style_div'=>'',
'htmlbefore'=>'',
'htmlafter'=>'',
// Cache
'use_cache'=>true, // true/false
'update_time'=>7200, // �������� � ��������
'cache_base'=>'', // ���� �� ����� �����
'cache_file_name'=>'', // ��� ����
//
'iis'=>false,
'oswin'=>false,
// SYSTEM
'return'=>'text', // text, array
);

var $ml_host;  // MainLink.ru ��������� ������
var $path_base; // ���� �� ����� �� ��������

    function ML_CFG(){
        $this->ml_cfg['host']=$_SERVER['HTTP_HOST'];
        // ����������� ���������
        $this->ml_cfg['iis'] = (isset($_SERVER['PWD'])?false: preg_match('/IIS/i',$_SERVER['SERVER_SOFTWARE'])?true:false);
        $this->ml_cfg['oswin'] = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'?true:($this->ml_cfg['iis']?true:false));   
    }

    // ������� ��������� ���� �� ������� � ����� ����� ����
    function Get_Path($path='',$folder_name=''){
        $ml_path=($path?$path:dirname(__FILE__));
        // ����������� ���� ������
        $ml_path=($this->ml_cfg['oswin']?str_replace('\\','/',preg_replace('!^[a-z]:!i','',($ml_path))):$ml_path); 
        // ���� �� ���� � ������ ������
        $this->ml_cfg['cache_base']=$ml_path.(substr($ml_path,-1)!='/'?'/':'').($folder_name?$folder_name:'data');
        $this->path_base=$ml_path;

        if(file_exists($this->ml_cfg['cache_base']) and is_writable($this->ml_cfg['cache_base'])){
            $this->ml_cfg['use_cache']=true;
        }else{
            $this->ml_cfg['use_cache']=false;
        }
    }
    
    // �������� �� ������� ����
    function _Is_cache_file(){
    if(is_file($this->ml_cfg['cache_file_name']) and is_readable($this->ml_cfg['cache_file_name']) and filesize($this->ml_cfg['cache_file_name'])>0)return true;
    return false;
    }     
}
class ML_LOCALE{
    var $locale=array(
   'en'=>array(
   "Secure code is empty!\nYou must use secure code!\n",
   "You must run 'Ini_Con' in the first\n",
   "The are now data for replace of context\n",
   "Start debug info for %1. Count of run %2.\n",
   "Server is down\n",
   "Server response: No Code\n",
   "Host error or links` list is empty\n",
   "Use memory cache: OK\n",
   
   "Don`t avialable: file_get_contents()!\n",
   "Error: don`t init curl!\n",
   "Don`t avialable: CURL!\n",
   "Error: don`t get data by (%3)!\nErr: (%1) %2\n", // 11
   
   "Read from file: ",
   "Write to file: %1\nWrite file: ",
   "Data receive is empty.\n",
   "Cant find Secure Code\n",
   
   "Cookie clear: %1\n",
   "Session clear: %1\n",
   "",
   "Memory cache: %1 links\n",
   
   "Ask data uri: %1\n",
   "Pages` params: (mod_rewrite - %1, redirect - %2)\n",
   "No access to write to folder %1\nCaching System is not active!\n",
   "Ruquested host name: %1\n", // 23
   
   "Protected find uri: %1\n", // 24
   
   "Send to ML: %1\n",
   
   ),
   'ru'=>array(
   "�� ����� ��� ������.\n���������� ������ � �������� ������ ����������.\n",
   "��� ������ ���� ��������� 'Ini_Con'\n",
   "��� ������ ��� ������ ���������\n",
   "������� ������� %1\n������ ������� ���: %2\n",
   "������ ������ ������ �� ��������\n",
   "������ ������ ������ ������ �����: No Code\n",
   "��� ������ ��� ������\n",
   "������ ����� �� ���� ������\n",
   
   "������ ��� ������� � file_get_contents()\n",
   "������ ��� ������������� CURL\n",
   "������ ��� ������� � CURL\n",
   "������ ��� ������� ��� ��������� ������ �� (%3)\n%1 (%2)\n",
   
   "������ ���-�����: ",
   "������ ���-�����: %1",
   "��� ������ ��� ������\n",
   "��� ������ �� ������\n",
   
   "������� ���\n",
   "������� ������\n",
   "",
   "������ � ������: %1 ������\n",
   
   "����� ������ ���: %1\n",
   "��������� ��������: (mod_rewrite - %1, redirect - %2)\n",
   "��� ������� �� ������ � ����� %1\n������� ����������� ���������!\n",
   "������ ������������� ���: %1\n",
   
   "���������� ������ ����������� uri: %1\n",
   
   "������������ uri: %1\n", // 25
      
   ),
);
}

// ��������������� �������
if(!function_exists('str_split')) {
  function str_split($string, $split_length = 1) {
    $array = explode("\r\n", chunk_split($string, $split_length));
    return $array;
  }
}

/*
 ������������� ������ � ���������� ��� ��� ����������� �������������
 ����������: new ML(); ��� new ML('secure code');
*/
$ml = new ML();

/*
 ����������� ��� ��� �������� ����������� ��� ��� ������ � �������������� SSI
 
 SSI:
 
    ������� ������� �����������
    <!--#include virtual="/mainlink/ML.php?ssi=1&uri=${REQUEST_URI}" -->
    ���
    <!--#include virtual="/mainlink/ML.php?simple=1&uri=${REQUEST_URI}" -->
    
    ���� ��� ����� '��� ������� ����'.sec � ����� �� �������� �� ��� ����� ������ ����� �������� secure
    <!--#include virtual="/mainlink/ML.php?simple=1&secure=��� ������� ����&uri=${REQUEST_URI}" --> 
 
    � ��������� �������������� ����������
 
    ������� ������ 2 ������
    <!--#include virtual="/mainlink/ML.php?simple=1&secure=��� ������� ����&uri=${REQUEST_URI}&nlinks=2" -->
    ������� ��������� ������
    <!--#include virtual="/mainlink/ML.php?simple=1&secure=��� ������� ����&uri=${REQUEST_URI}" -->
    
*/
if(defined('SIMPLE') or isset($_GET['simple']) or isset($_GET['ssi'])){
    $cfg=array();
    // ���������� ������� ��������� ������
    if(isset($_GET['secure']))define('SECURE_CODE',$_GET['secure']);
    if(isset($_GET['host']))$cfg['host'] = $_GET['host'];
    if(isset($_GET['uri']))$_SERVER['REQUEST_URI']=$cfg['uri'] = $_GET['uri'];
    if(isset($_GET['charset']))$cfg['charset'] = $_GET['charset'];
    if(isset($_GET['nlinks']))$cfg['nlinks'] = (int)$_GET['nlinks'];
    if(isset($_GET['part']))$cfg['part'] = (int)$_GET['part'];
    if(isset($_GET['parts']))$cfg['parts'] = (int)$_GET['parts'];
    // �������
    if(isset($_GET['debugmode']))$cfg['debugmode'] = $_GET['debugmode']; 
    // ���������� ������
    if(isset($_GET['style']))$cfg['style'] = $_GET['style'];
    if(isset($_GET['class_name']))$cfg['class_name'] = $_GET['class_name'];
    if(isset($_GET['splitter']))$cfg['splitter'] = $_GET['splitter'];
    // ����� �����������
    if(isset($_GET['use_cache']))$cfg['use_cache'] = $_GET['use_cache'];
    if(isset($_GET['update_time']))$cfg['update_time'] = (int)$_GET['update_time']; 
    $ml->Set_Config($cfg);
    if($cfg['part'] and $cfg['parts']){
     if($links=$this->Get_Sec($cfg)){
        echo $links; 
     }elseif($links=$this->Get_Main($cfg)){
        echo $links;
     }else return '';
    }else echo $ml->Get_Links();
}
?>itex_imoney_datafiles_delimiter_1file linkfeed.php 4.003 2009-04-05itex_imoney_datafiles_delimiter_2linkfeed.phpitex_imoney_datafiles_delimiter_2<?php

class LinkfeedClient {
    var $lc_version           = '0.3.8';
    var $lc_verbose           = false;
    var $lc_charset           = 'DEFAULT';
    var $lc_use_ssl           = false;
    var $lc_server            = 'db.linkfeed.ru';
    var $lc_cache_lifetime    = 3600;
    var $lc_cache_reloadtime  = 300;
    var $lc_links_db_file     = '';
    var $lc_links             = array();
    var $lc_links_page        = array();
    var $lc_links_delimiter   = '';
    var $lc_error             = '';
    var $lc_host              = '';
    var $lc_request_uri       = '';
    var $lc_fetch_remote_type = '';
    var $lc_socket_timeout    = 6;
    var $lc_force_show_code   = false;
    var $lc_multi_site        = false;
    var $lc_is_static         = false;

    function LinkfeedClient($options = null) {
        $host = '';

        if (is_array($options)) {
            if (isset($options['host'])) {
                $host = $options['host'];
            }
        } elseif (strlen($options) != 0) {
            $host = $options;
            $options = array();
        } else {
            $options = array();
        }

        if (strlen($host) != 0) {
            $this->lc_host = $host;
        } else {
            $this->lc_host = $_SERVER['HTTP_HOST'];
        }

        $this->lc_host = preg_replace('{^https?://}i', '', $this->lc_host);
        $this->lc_host = preg_replace('{^www\.}i', '', $this->lc_host);
        $this->lc_host = strtolower( $this->lc_host);

        if (isset($options['is_static']) && $options['is_static']) {
            $this->lc_is_static = true;
        }

        if (isset($options['request_uri']) && strlen($options['request_uri']) != 0) {
            $this->lc_request_uri = $options['request_uri'];
        } else {
            if ($this->lc_is_static) {
                $this->lc_request_uri = preg_replace( '{\?.*$}', '', $_SERVER['REQUEST_URI']);
                $this->lc_request_uri = preg_replace( '{/+}', '/', $this->lc_request_uri);
            } else {
                $this->lc_request_uri = $_SERVER['REQUEST_URI'];
            }
        }

        if (isset($options['multi_site']) && $options['multi_site'] == true) {
            $this->lc_multi_site = true;
        }

        if ((isset($options['verbose']) && $options['verbose']) ||
            isset($this->lc_links['__linkfeed_debug__'])) {
            $this->lc_verbose = true;
        }

        if (isset($options['charset']) && strlen($options['charset']) != 0) {
            $this->lc_charset = $options['charset'];
        }

        if (isset($options['fetch_remote_type']) && strlen($options['fetch_remote_type']) != 0) {
            $this->lc_fetch_remote_type = $options['fetch_remote_type'];
        }

        if (isset($options['socket_timeout']) && is_numeric($options['socket_timeout']) && $options['socket_timeout'] > 0) {
            $this->lc_socket_timeout = $options['socket_timeout'];
        }

        if ((isset($options['force_show_code']) && $options['force_show_code']) ||
            isset($this->lc_links['__linkfeed_debug__'])) {
            $this->lc_force_show_code = true;
        }

        if (!defined('LINKFEED_USER')) {
            return $this->raise_error("Constant LINKFEED_USER is not defined.");
        }

        $this->load_links();
    }

    function load_links() {
        if ($this->lc_multi_site) {
            $this->lc_links_db_file = dirname(__FILE__) . '/linkfeed.' . $this->lc_host . '.links.db';
        } else {
            $this->lc_links_db_file = dirname(__FILE__) . '/linkfeed.links.db';
        }

        if (!is_file($this->lc_links_db_file)) {
            if (@touch($this->lc_links_db_file, time() - $this->lc_cache_lifetime)) {
                @chmod($this->lc_links_db_file, 0666);
            } else {
                return $this->raise_error("There is no file " . $this->lc_links_db_file  . ". Fail to create. Set mode to 777 on the folder.");
            }
        }

        if (!is_writable($this->lc_links_db_file)) {
            return $this->raise_error("There is no permissions to write: " . $this->lc_links_db_file . "! Set mode to 777 on the folder.");
        }

        @clearstatcache();

        if (filemtime($this->lc_links_db_file) < (time()-$this->lc_cache_lifetime) || 
           (filemtime($this->lc_links_db_file) < (time()-$this->lc_cache_reloadtime) && filesize($this->lc_links_db_file) == 0)) {

            @touch($this->lc_links_db_file, time());

            $path = '/' . LINKFEED_USER . '/' . strtolower( $this->lc_host ) . '/' . strtoupper( $this->lc_charset);

            if ($links = $this->fetch_remote_file($this->lc_server, $path)) {
                if (substr($links, 0, 12) == 'FATAL ERROR:') {
                    $this->raise_error($links);
                } else if (@unserialize($links) !== false) {
                    $this->lc_write($this->lc_links_db_file, $links);
                } else {
                    $this->raise_error("Cann't unserialize received data.");
                }
            }
        }

        $links = $this->lc_read($this->lc_links_db_file);
        $this->lc_file_change_date = gmstrftime ("%d.%m.%Y %H:%M:%S",filectime($this->lc_links_db_file));
        $this->lc_file_size = strlen( $links);
        if (!$links) {
            $this->lc_links = array();
            $this->raise_error("Empty file.");
        } else if (!$this->lc_links = @unserialize($links)) {
            $this->lc_links = array();
            $this->raise_error("Cann't unserialize data from file.");
        }

        if (isset($this->lc_links['__linkfeed_delimiter__'])) {
            $this->lc_links_delimiter = $this->lc_links['__linkfeed_delimiter__'];
        }

        if (array_key_exists($this->lc_request_uri, $this->lc_links) && is_array($this->lc_links[$this->lc_request_uri])) {
            $this->lc_links_page = $this->lc_links[$this->lc_request_uri];
        }
        $this->lc_links_count = count($this->lc_links_page);
    }

    function return_links($n = null) {
        $result = '';
        if (isset($this->lc_links['__linkfeed_start__']) && strlen($this->lc_links['__linkfeed_start__']) != 0 &&
            (in_array($_SERVER['REMOTE_ADDR'], $this->lc_links['__linkfeed_robots__']) || $this->lc_force_show_code)
        ) {
            $result .= $this->lc_links['__linkfeed_start__'];
        }

        if (isset($this->lc_links['__linkfeed_robots__']) && in_array($_SERVER['REMOTE_ADDR'], $this->lc_links['__linkfeed_robots__']) || $this->lc_verbose) {

            if ($this->lc_error != '') {
                $result .= $this->lc_error;
            }

            $result .= '<!--REQUEST_URI=' . $_SERVER['REQUEST_URI'] . "-->\n"; 
            $result .= "\n<!--\n"; 
            $result .= 'L ' . $this->lc_version . "\n"; 
            $result .= 'REMOTE_ADDR=' . $_SERVER['REMOTE_ADDR'] . "\n"; 
            $result .= 'request_uri=' . $this->lc_request_uri . "\n"; 
            $result .= 'charset=' . $this->lc_charset . "\n"; 
            $result .= 'is_static=' . $this->lc_is_static . "\n"; 
            $result .= 'multi_site=' . $this->lc_multi_site . "\n"; 
            $result .= 'file change date=' . $this->lc_file_change_date . "\n";
            $result .= 'lc_file_size=' . $this->lc_file_size . "\n";
            $result .= 'lc_links_count=' . $this->lc_links_count . "\n";
            $result .= 'left_links_count=' . count($this->lc_links_page) . "\n";
            $result .= 'n=' . $n . "\n"; 
            $result .= '-->'; 
        }

        if (is_array($this->lc_links_page)) {
            $total_page_links = count($this->lc_links_page);

            if (!is_numeric($n) || $n > $total_page_links) {
