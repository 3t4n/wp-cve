<?php
/*
Plugin Name: iMoney August Edition
Version: 0.36 (01-08-2012)
Plugin URI: http://itex.name/imoney
Description: Adsense, <a href="http://www.sape.ru/r.a5a429f57e.php">Sape.ru</a>, <a href="http://www.tnx.net/?p=119596309">tnx.net/xap.ru</a>, <a href="http://referal.begun.ru/partner.php?oid=114115214">Begun.ru</a>, <a href="http://admitad.com/ru/promo/?ref=f0fc9a3889">admitad.com</a>, <a href="http://www.mainlink.ru/?partnerid=42851">mainlink.ru</a>, <a href="http://www.linkfeed.ru/reg/38317">linkfeed.ru</a>, <a href="http://adskape.ru/unireg.php?ref=17729&d=1">adskape.ru</a>, <a href="http://teasernet.com/?owner_id=18516">Teasernet.com</a>, <a href="http://trustlink.ru/registration/106535">Trustlink.ru</a>, php exec and html inserts helper.
Author: Itex
Author URI: http://itex.name/
*/

/*
Copyright 2007-2012  Itex (web : http://itex.name/)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
/*
EN
Plugin iMoney is meant for monetize your blog using Adsense, sape.ru, tnx.net and other systems.
Features:
Placing Ads or links up to the text of page, after page of text in the widget and footer.
Widget course customizable.
Automatic installation of a plug and the rights to the sape and tnx folder on request.
Adjustment of amount of displayed links depending on the location.

Requirements:
Wordpress 2.3-3.4.1
PHP5, maybe PHP4
Widget compatible theme, to use the links in widgets.

Installation:
Copy the file iMoney.php in wp-content/plugins .
In Plugins activate iMoney.
In settings-> iMoney.

Adsense - enter your Adsense id.
Customize the size, position and channel ads blocks as you want.

Sape - enter your Sape Uid.
If you want to create a Sape folder automatically, coinciding with your Sape Uid.
Allow work Sape links, to specify how many references to the use of text after text, widget and footer.
Allow Sape context.
If you are adding content frequently , then content links of main page, tags and categories can return error.
If you frequently add content, the content of the main links, tags, categories can fly out in error.
For preventation of it switch on the option "Show context only on Pages and Posts".
As required switch on Check - a verification code.

Tnx/xap - enter your Tnx Uid.
If you want to create a Tnx folder automatically, coinciding with your Tnx Uid.
Allow work Tnx links, to specify how many references to the use of text after text, widget and footer.
If you are adding content frequently , then content links of main page, tags and categories can return error.
As required switch on Check - a verification code.

Html - Enter your html code in the right place

For activating widget you shall go to a design-> widgets, activate the widget and point its title.
If define ('WPLANG', 'ru_RU'); in wp-config.php then russian language;

RU
Плагин iMoney предназначен для монетизации Вашего блога при помощи Adsense, sape.ru, tnx.net и других систем.
Возможности:
Размещение ссылок или рекламы до текста страницы, после текста страницы, в виджетах и футере.
Автоматическая установка плагина и прав на папки sape.ru и tnx.net по желанию.
Регулировка количества показываемых ссылок в зависимости от места расположения.

Требования:
Wordpress 2.3-3.4.1
ПХП 4-5
Виджет совместимая тема, если использовать ссылки в виджетах.

Установка:
Скопировать файл iMoney.php в wp-content/plugins/ вордпресса.
В плагинах активировать iMoney.
В настройках->iMoney.

Adsense - ввести ваш Adsense id.
Настройтие размер, позицию и канал блоков рекламы как вы хотите.

Sape - ввести ваш Sape Uid.
По желанию создать автоматом папку Сапы, совпадающей с вашим Sape Uid.
Разрешить работу Sape links, указать сколько ссылок использовать до текста, после текста, в виджете и футере.
Разрешить Sape context.
Если часто добавляете контент, то контентные ссылки в главной,тегах,категориях могут вылетать в эррор.
Для предотвращения включите опцию "Show context only on Pages and Posts".
По мере надобности включить Check - проверочный код.

Tnx/xap - ввести ваш Tnx Uid.
По желанию создать автоматом папку, совпадающей с вашим Tnx Uid.
Разрешить работу Tnx links, указать сколько ссылок использовать до текста, после текста, в виджете и футере.
По мере надобности включить Check - проверочный код.

Html - Введите ваш html код в нужные места.

Для активации виджетов нужно зайти в дизайн->виджеты, активировать виджет и указать его заголовок.
Если define ('WPLANG', 'ru_RU'); в wp-config.php, то будет русский язык.

*/

class itex_money
{
    var $version = '0.36';
    var $full = 0;
    var $error = '';
    //var $force_show_code = true;
    var $sape;
    var $sapecontext;
    var $sapearticles;
    var $zilla;
    var $tnx;
    var $setlinks;
    var $setlinkscontext;
    //var $enable = false;
    var $links = array();
    var $sidebar = array();
    var $sidebar_links = '';
    var $footer = '';
    var $beforecontent = '';
    var $aftercontent = '';
    var $safeurl = '';
    var $document_root = '';
    //var $debug = 1;
    var $debuglog = '';
    var $memory_get_usage = 0; //start memory_get_usage
    var $get_num_queries = 0; //start get_num_queries
    //var $replacecontent = 0;
    var $encoding = 'UTF-8';

    var $wordpress = 1;
    var $drupal = 0;
    var $joomla = 0;
    var $bitrix = 0;
    var $isape_converted = 1;
    var $server; //zamena server, v kachestve zashity ot staticheskih analizatorov uyazvimostey
    var $REQUEST_URI; //zamena REQUEST_URI
    var $force_show =1; //vsegda pokazyvat cod i ssylki
    /**
     * constructor, function __construct()  in php4 not working
     *
     */
    function itex_money()
    {
        $this->server = &$_SERVER; //zamena server ssylkoy
        $this->REQUEST_URI = &$this->server['REQUEST_URI'];
        if (substr(phpversion(),0,1) == 4) $this->php4(); //fix php4 bugs

        $this->document_root = ($this->server['DOCUMENT_ROOT'] != str_replace($this->server["SCRIPT_NAME"],'',$this->server["SCRIPT_FILENAME"]))?(str_replace($this->server["SCRIPT_NAME"],'',$this->server["SCRIPT_FILENAME"])):($this->server['DOCUMENT_ROOT']);

        if (!$this->get_option('itex_m_install_date'))
        {
            $this->update_option('itex_m_install_date',time());
        }



        if ($this->wordpress)
        {
            if (!function_exists(add_action)) return 0;

            $this->force_show = get_option('itex_m_global_force_show');

            //if (!get_option('itex_m_isape_converted')) $this->isape_converted = 0; //для совместимомти с isape, через несколько месяцев надо удалить
            //else $this->isape_converted = 0;

            add_action('widgets_init', array(&$this, 'itex_m_init'));
            //add_action("widgets_init", array(&$this, 'itex_m_widget_init'));
            add_action('admin_menu', array(&$this, 'itex_m_menu'));
            add_action('wp_footer', array(&$this, 'itex_m_footer'));


            //			if (!$this->get_option('itex_m_install_date'))
            //			{
            //				$this->update_option('itex_m_install_date',time());
            //			}

            $this->encoding = $this->get_option('blog_charset')?$this->get_option('blog_charset'):'UTF-8';

        }
        if ($this->joomla)
        {
            if (!defined('_VALID_MOS') && !defined('_JEXEC')) return 0;
            if (!isset($GLOBALS['_MAMBOTS'])) return 0;

            $GLOBALS['_MAMBOTS']->registerFunction('onAfterStart', array(&$this, 'itex_m_init'));

            $GLOBALS['mainframe']->registerEvent('onPrepareContent', array(&$this, 'itex_m_replace'));
            //что за mainframe ваще и можно ли передавать array вторым параметром

            $GLOBALS['mainframe']->registerEvent('onContentPrepare', array(&$this, 'itex_m_replace')); //для 1.6 джумлы

        }
        if ($this->bitrix)
        {

        }

    }

    /**
     * php4 support
     *
     */
    function php4()
    {
        if (!function_exists('file_put_contents'))
        {
            function file_put_contents($filename, $data)
            {
                $f = @fopen($filename, 'w');
                if (!$f) return false;
                else
                {
                    $bytes = fwrite($f, $data);
                    fclose($f);
                    return $bytes;
                }
            }
        }
        $this->itex_debug('Used php4');
    }

    /**
     *  Russian lang support
     * deprecated
     *
     */
    function lang_ru()
    {
        return ; // тк перешел на .mo файлы

    }

    /**
     * Debug collector
     *
     */
    function itex_debug($text='')
    {
        $this->debuglog .= "\r\n".$text."\r\n";
    }

    /**
     * Get option
     *
     */
    function get_option($option)
    {
        if ($this->wordpress)
        {
            //if (!$this->isape_converted) //для совместимомти с isape, через несколько месяцев надо удалить
            //{
            //	$option  = str_ireplace('itex_m_','itex_s_',$option);
            //}
            return get_option($option);
        }
        if ($this->joomla)
        {
            return $GLOBALS['params']->get($option);
        }

        if ($this->bitrix)
        {
            return COption::GetOptionString("imoney", $option, false);
        }

        return false;
    }

    /**
     * Update option
     *
     */
    function update_option($option,$value)
    {
        if ($this->wordpress)
        {
            return update_option($option,$value);
        }

        if ($this->bitrix)
        {
            return COption::SetOptionString("imoney", $option, $value);
        }

        return false;
    }

    /**
     * Translate api
     *
     */
    function __($text, $context = 'iMoney')
    {
        if ($this->wordpress)
        {
            return __($text, $context);
        }
        if ($this->joomla)
        {
            return JText::_($text);
        }
        if ($this->bitrix)
        {
            $text2 = GetMessage($text);
            if (!empty($text2))
                return $text2;
        }
        return $text;

    }

    /**
     * Url masking
     *
     * @return  bool
     */
    function itex_m_safe_url()
    {
        $vars=array('p','p2','pg','page_id', 'm', 'cat', 'tag', 'paged');

        //для шаблона сейп артиклес
        if ($this->get_option('itex_m_sape_sapeuser')) $vars[] = 'itex_sape_articles_template.'.$this->get_option('itex_m_sape_sapeuser').'.html';
        if ($this->get_option('itex_m_sape_sapeuser')) $vars[] = 'itex_sape_articles_template.'.$this->get_option('itex_m_sape_sapeuser');

        $url=explode("?",strtolower($this->REQUEST_URI));
        if(isset($url[1]))
        {
            //$count = preg_match_all("/(.*)=(.*)\&/Uis",$url[1]."&",$get);
            $count = preg_match_all("/(.*)=(.*)&/Uis",$url[1]."&",$get);
            for($i=0; $i < $count; $i++)
                if (in_array($get[1][$i],$vars) && !empty($get[2][$i]))
                    $ret[] = $get[1][$i]."=".$get[2][$i];
            if (count($ret))
            {
                $ret = '?'.implode("&",$ret);
                //print_r($ret);die();
            }
            else $ret = '';
        }
        else $ret = '';
        $this->safeurl = $url[0].$ret;

        $this->itex_debug('safe_url '.$this->safeurl);

        return 1;
    }



    /**
     * get links
     *
     * @param   int   $c		count
     * @param   int   $c		a only if 1
     * @return  string $ret
     */
    function itex_m_get_links($c = 30, $q=1) //$q = a only
    {
        $ret = '';
        for ($i=1;$i<=$c;$i++)
        {
            if (count($this->links))
                foreach ($this->links as $k=>$v)
                {
                    $ret .= $v;
                    //$ret .= $this;

                    unset($this->links[$k]);
                    break;
                }


            //			if ($q)
            //			{
            //				if (count($this->links))
            //				foreach ($this->links as $k=>$v)
            //				{
            //					$ret .= $v;
            //					//$ret .= $this;
            //
            //					unset($this->links[$k]);
            //					break;
            //				}
            //			}
            //			else
            //			{
            //				if (count($this->links))
            //				foreach ($this->links as $k=>$v)
            //				{
            //					$ret .= $v;
            //					unset($this->links[$k]);
            //					break;
            //				}
            //			}
        }
        return $ret;
    }


    /**
     * plugin init function
     *
     * @return  bool
     */
    function itex_m_init()
    {
        if ( function_exists('memory_get_usage') ) $this->memory_get_usage = memory_get_usage();
        if ( function_exists('get_num_queries') ) $this->get_num_queries = get_num_queries();

        if ($this->get_option('itex_m_global_masking'))
        {
            $this->itex_m_safe_url();
            $last_REQUEST_URI = $this->REQUEST_URI;
            $this->REQUEST_URI = $this->safeurl;
        }

        $this->itex_debug('REQUEST_URI = '.$this->REQUEST_URI);

        //moget tnx ne produmali multihosting bag, mb ya mudag pravda)) skorey vsego ta mudag i chtonit kuril, ppc narko kod), pri multi $this->server['DOCUMENT_ROOT'] == "/var/www/default/"
        if ($this->server['DOCUMENT_ROOT'] != $this->document_root) //nachinaniem izvrasheniya ((
        {
            $last_DOCUMENT_ROOT = $this->server['DOCUMENT_ROOT'];
            $this->server['DOCUMENT_ROOT'] = $this->document_root;
        }
        $this->itex_debug('DOCUMENT_ROOT = '.$this->document_root);

        $this->itex_init_adsense();
        $this->itex_init_html();
        $this->itex_init_sape();
        $this->itex_init_tnx();
        $this->itex_init_begun();
        $this->itex_init_admitad();
        $this->itex_init_ilinks();
        $this->itex_init_mainlink();
        $this->itex_init_linkfeed();
        $this->itex_init_adskape();
        $this->itex_init_php();
        $this->itex_init_setlinks();
        $this->itex_init_teasernet();
        $this->itex_init_trustlink();
        $this->itex_init_zilla();

        $this->itex_m_widget_init();
        if (strlen($this->footer))
        {
            if ($this->wordpress) add_action('wp_footer', array(&$this, 'itex_m_footer'));
        }

        if ((strlen($this->beforecontent)) || (strlen($this->aftercontent)) )
        {
            $this->itex_debug('strlenbeforecontent = '.strlen($this->beforecontent));
            $this->itex_debug('strlenaftercontent = '.strlen($this->aftercontent));

            if ($this->wordpress)
            {
                add_filter('the_content', array(&$this, 'itex_m_replace'));
                add_filter('the_excerpt', array(&$this, 'itex_m_replace'));
            }
            if ($this->joomla)
            {
                $GLOBALS['_MAMBOTS']->registerFunction( 'onPrepareContent', array(&$this, 'itex_m_replace') );
            }

        }

        if (isset($last_REQUEST_URI)) //privodim REQUEST_URI v poryadok
        {
            $this->REQUEST_URI = $last_REQUEST_URI;
            unset($last_REQUEST_URI);
        }

        if (isset($last_DOCUMENT_ROOT)) //zakanchivaem izvrashatsa i privodim vse v poryadok
        {
            $this->server['DOCUMENT_ROOT'] = $last_DOCUMENT_ROOT;
            unset($last_DOCUMENT_ROOT);
        }


        if ( function_exists('memory_get_usage') ) $this->itex_debug("memory start/end/dif ".$this->memory_get_usage.'/'.memory_get_usage().'/'.(memory_get_usage()-$this->memory_get_usage));
        if ( function_exists('get_num_queries') ) $this->itex_debug("get_num_queries start/end/dif ".intval($this->get_num_queries).'/'.intval(get_num_queries()).'/'.(intval(get_num_queries())-intval($this->get_num_queries)));

        return 1;
    }

    /**
     * sape init
     *
     * @return  bool
     */
    function itex_init_sape()
    {
        if (!$this->get_option('itex_m_sape_enable') && !$this->get_option('itex_m_sape_sapecontext_enable') && !$this->get_option('itex_m_sape_sapearticles_enable')) return 0;

        if (!defined('_SAPE_USER')) define('_SAPE_USER', $this->get_option('itex_m_sape_sapeuser'));
        else $this->error .= '_SAPE_USER '.$this->__('already defined<br/>', 'iMoney');
        $this->itex_debug('SAPE_USER = '.$this->get_option('itex_m_sape_sapeuser'));

        //FOR MASS INSTALL ONLY, REPLACE if (0) ON if (1)
        //		if (0)
        //		{
        //			$this->update_option('itex_sape_sapeuser', 'abcdarfkwpkgfkhagklhskdgfhqakshgakhdgflhadh'); //sape uid
        //			$this->update_option('itex_sapecontext_enable', 1);
        //			$this->update_option('itex_sape_enable', 1);
        //			$this->update_option('itex_sape_links_footer', 'max');
        //		}

        $file = $this->document_root . '/' . _SAPE_USER . '/sape.php'; //<< Not working in multihosting.
        if (file_exists($file)) require_once($file);
        else return 0;

        $o['charset'] = $this->encoding;
        //$o['force_show_code'] = $this->force_show_code;
        //$o['force_show_code'] = 1; // сделал так, тк новые страницы не добавляются
        $o['force_show_code'] = $this->force_show;

        $o['multi_site'] = true;
        if ($this->get_option('itex_m_sape_enable'))
        {
            $this->sape = new SAPE_client($o);



            //добавляем ссылки в линкс
            $i = 1;
            while ($i++)
            {
                $q = trim($this->sape->return_links(1));
                if (empty($q) || !strlen($q))
                {
                    break;
                }



                //убрал, тк сайт не индексируются возможно из-за этого
                //if(!preg_match("/^\<\!\-\-/", $q)) $q .= $this->sape->_links_delimiter; // убираем коммент, не повредит дебагу?


                    if (strlen($q)) $this->links[] = $q.$this->sape->_links_delimiter;

                $q1 = @trim(strip_tags($q)); //если нет текста, то и нечего показывать, значит ссылок больше нет
                if (empty($q1) || !strlen($q1))
                {
                    break;
                }

                //!!!!!!!!!!check it, tk ne vozvrashaet pustuu stroku
                if ($i > 30) break;
            }
            ///if (!count($this->links)) // если нет размещенных ссылок, и включен debugenable добавляем чеккод
            if ($i<2)
            {
                if ($this->force_show)
                    $this->links[] = trim($this->sape->return_links());
            }
            $this->itex_debug('sape links:'.var_export($this->links, true));


            //$this->itex_init_sape_links();





            ///check it
            $url = 1;
            if ($this->wordpress) if (is_object($GLOBALS['wp_rewrite'])) $url = url_to_postid($this->REQUEST_URI);

            if (($url) || !$this->get_option('itex_sape_pages_enable'))
            {
                if ($this->get_option('itex_m_sape_links_beforecontent') == '0')
                {
                    //$this->beforecontent = '';
                }
                else
                {
                    $this->beforecontent .= '<div>'.$this->itex_m_get_links(intval($this->get_option('itex_sape_links_beforecontent'))).'</div>';
                }

                if ($this->get_option('itex_m_sape_links_aftercontent') == '0')
                {
                    //$this->aftercontent = '';
                }
                else
                {

                        $this->aftercontent .= '<div>'.$css.$this->itex_m_get_links(intval($this->get_option('itex_m_sape_links_aftercontent'))).'</div>';
                }
            }

            $countsidebar = $this->get_option('itex_m_sape_links_sidebar');
            $check = $this->get_option('itex_m_global_debugenable')?'<!---check sidebar '.$countsidebar.'-->':'';



            if ($countsidebar == 'max')
            {
                //$this->sidebar = '<div>'.$this->sape->return_links().'</div>';
                $this->sidebar_links .= '<div>'.$this->itex_m_get_links().'</div>';
            }
            elseif ($countsidebar == '0')
            {
                //$this->sidebar = '';
            }
            else
            {
                $this->sidebar_links .= '<div>'.$this->itex_m_get_links(intval($countsidebar)).'</div>';
            }
            $this->sidebar_links = $check.$this->sidebar_links;

            $countfooter = $this->get_option('itex_m_sape_links_footer');
            $check = $this->get_option('itex_m_global_debugenable')?'<!---check footer '.$countfooter.'-->':'';
            $this->footer .= $check;
            if ($countfooter == 'max')
            {
                //$this->footer = '<div>'.$this->sape->return_links().'</div>';
            }
            elseif ($countfooter == '0')
            {
                //$this->footer = '';
            }
            else
            {
                $this->footer .= '<div>'.$this->itex_m_get_links($countfooter).'</div>';
            }
            $this->footer = $check.$this->footer;

            if (($countsidebar == 'max') && ($countfooter == 'max')) $this->footer .= $this->itex_m_get_links();
            else
            {
                if  ($countsidebar == 'max') $this->sidebar_links .= $this->itex_m_get_links();
                else $this->footer .= $this->itex_m_get_links();
            }


            if (strlen($this->sape->_error))$this->itex_debug('sape error:'.var_export($this->sape->_error, true));
        }

        if ($this->get_option('itex_m_sape_sapecontext_enable'))
        {
            $this->sapecontext = new SAPE_context($o);
            if ($this->wordpress)
            {
                add_filter('the_content', array(&$this, 'itex_m_replace'));
                add_filter('the_excerpt', array(&$this, 'itex_m_replace'));
            }

        }

        if ($this->get_option('itex_m_sape_sapearticles_enable'))
        {
            //подстраховываемся, если старый файл сапы
            //if (function_exists('SAPE_articles::SAPE_articles()')) $this->sapearticles = new SAPE_articles($o);
            if(is_callable(array('SAPE_articles', 'SAPE_articles')))
            {
                $this->itex_debug('Class SAPE_articles exist');
                $this->sapearticles = new SAPE_articles($o);

                $this->itex_debug('sape articles url template /itex_sape_articles_template.'._SAPE_USER.'.html');

                //для прохождения модераторов
                $isvalidurl = 0;
                $preg = $this->get_option('itex_m_sape_sapearticles_template_url');
                if (strlen($preg) > 4)
                {
                    $preg = str_ireplace('\\','\\\\',$preg);
                    $preg = str_ireplace('.','\\.',$preg);
                    $preg = str_ireplace('?','\\?',$preg);
                    $preg = str_ireplace('{date_d}','[0-9]{1,2}',$preg);
                    $preg = str_ireplace('{date_m}','[0-9]{1,2}',$preg);
                    $preg = str_ireplace('{date_y}','[0-9]{2,4}',$preg);
                    $preg = str_ireplace('{name}','([a-z0-9\_\-]+)',$preg);
                    $preg = str_ireplace('{id}','([0-9]+)',$preg);
                    $preg = '@'.$preg.'@i';
                    $this->itex_debug('sapearticles_template_url preg = '.$preg);

                    if (preg_match($preg,$this->REQUEST_URI))
                    {
                        $isvalidurl = 1;
                    }
                }

                //генерация шаблона
                if (preg_match('@itex_sape_articles_template\.'._SAPE_USER.'@i',$this->REQUEST_URI))
                {
                    if (!headers_sent())
                    {
                        header(200);

                        //на всякий пожарный, если сервер отдаст не ту кодировку
                        header('Content-Type: text/html; charset='.$o['charset']);
                        $this->itex_debug('header 200 sent');
                        echo '';flush(); //чтоб не переопределили хеадер,куки для шаблона не нужны
                    }
                    //phpinfo();die();
                    if ($this->wordpress)
                    {
                        remove_all_actions('wp');
                        remove_all_actions('wp_head');
                        add_action('wp', array(&$this, 'itex_init_sape_articles_template'),-999);
                        global $wp_query;
                    }

                }

                //если есть статьи или адрес соотвествует адресу шаблона, то передаем управление коду сапы
                elseif ((!empty($this->sapearticles->_data['index']) and isset($this->sapearticles->_data['index']['articles'][$this->sapearticles->_request_uri])) ||
                    ($isvalidurl)||
                    (!empty($this->sapearticles->_data['index']) and isset($this->sapearticles->_data['index']['images'][$this->sapearticles->_request_uri])))
                {
                    if (!headers_sent())
                    {
                        header(200);

                        //на всякий пожарный, если сервер отдаст не ту кодировку
                        //надо разобраться, тк могут побиться картинки
                        if (!isset($this->sapearticles->_data['index']['images'][$this->sapearticles->_request_uri]))
                            header('Content-Type: text/html; charset='.$o['charset']);
                        $this->itex_debug('header 200 sent');
                    }
                    echo $this->sapearticles->process_request();
                    die();
                }

                //анонсы
                ///check it
                $url = 1;
                if ($this->wordpress) if (is_object($GLOBALS['wp_rewrite'])) $url = url_to_postid($this->REQUEST_URI);

                if (($url) || !$this->get_option('itex_m_sape_sapearticles_pages_enable'))
                {
                    $this->itex_debug('sapearticles announcements worked');
                    if ($this->get_option('itex_m_sape_sapearticles_beforecontent') == '0')
                    {
                        //$this->beforecontent = '';
                    }
                    else
                    {
                        $this->beforecontent .= '<div>'.$this->sapearticles->return_announcements(intval($this->get_option('itex_m_sape_sapearticles_beforecontent'))).'</div>';
                    }

                    if ($this->get_option('itex_m_sape_sapearticles_aftercontent') == '0')
                    {
                        //$this->aftercontent = '';
                    }
                    else
                    {

                        $this->aftercontent .= '<div>'.$this->sapearticles->return_announcements(intval($this->get_option('itex_m_sape_sapearticles_aftercontent'))).'</div>';
                    }
                }
                $countsidebar = $this->get_option('itex_m_sape_sapearticles_sidebar');
                $check = $this->get_option('itex_m_global_debugenable')?'<!---check sidebar '.$countsidebar.'-->':'';
                if ($countsidebar == 'max')
                {
                    //$this->sidebar = '<div>'.$this->sape->return_links().'</div>';
                }
                elseif ($countsidebar == '0')
                {
                    //$this->sidebar = '';
                }
                else
                {
                    $this->sidebar_links .= '<div>'.$this->sapearticles->return_announcements(intval($countsidebar)).'</div>';
                }
                $this->sidebar_links = $check.$this->sidebar_links;

                $countfooter = $this->get_option('itex_m_sape_sapearticles_footer');
                $check = $this->get_option('itex_m_global_debugenable')?'<!---check footer '.$countfooter.'-->':'';
                $this->footer .= $check;
                if ($countfooter == 'max')
                {
                    //$this->footer = '<div>'.$this->sape->return_links().'</div>';
                }
                elseif ($countfooter == '0')
                {
                    //$this->footer = '';
                }
                else
                {
                    $this->footer .= '<div>'.$this->sapearticles->return_announcements($countfooter).'</div>';
                }
                $this->footer = $check.$this->footer;

                if (($countsidebar == 'max') && ($countfooter == 'max')) $this->footer .= $this->sapearticles->return_announcements();
                else
                {
                    if  ($countsidebar == 'max') $this->sidebar_links .= $this->sapearticles->return_announcements();
                    else $this->footer .= $this->sapearticles->return_announcements();
                }

            }
            else $this->itex_debug('Class SAPE_articles not exist');

        }
        return 1;
    }

    /**
     * get sape links
     *
     * @return  bool
     */
    function itex_init_sape_links_del()
    {
        $i = 1;

        while ($i++)
        {
            $q = trim($this->sape->return_links(1));
            if (empty($q) || !strlen($q))
            {
                break;
            }



            //убрал, тк сайт не индексируются возможно из-за этого
            //if(!preg_match("/^\<\!\-\-/", $q)) $q .= $this->sape->_links_delimiter; // убираем коммент, не повредит дебагу?


                if (strlen($q)) $this->links[] = $q.$this->sape->_links_delimiter;

            $q1 = trim(strip_tags($q)); //если нет текста, то и нечего показывать, значит ссылок больше нет
            if (empty($q1) || !strlen($q1))
            {
                break;
            }

            //!!!!!!!!!!check it, tk ne vozvrashaet pustuu stroku
            if ($i > 30) break;
        }
        if (!count($this->links)) // если нет размещенных ссылок, и включен debugenable добавляем чеккод
        {
            if ($this->get_option('itex_m_global_debugenable'))
                $this->links[] = trim($this->sape->return_links());
        }
        $this->itex_debug('sape links:'.var_export($this->links, true));
        return 1;
    }



    /**
     * get sape articles
     *
     * @return  bool
     */
    function itex_init_sape_articles_template()
    {
        $this->itex_debug('itex_init_sape_articles_template worked');

        if ($this->wordpress)
        {
            global $wp_query;
            global $post;
            $wp_query = new WP_Query('');
            $this->itex_debug('sapearticles template worked');
            $post = new stdClass();
            $post->ID= -404;
            $post->post_category= array(); //Add some categories. an array()???
            $post->post_status='publish';
            $post->post_type='post'; //page.
            $post->post_content="<h1>{header}</h1>\r\n{body} ".$this->sapearticles->_data['index']['checkCode'];
            $post->post_excerpt= "{description}";
            $post->post_title= "{title}";
            $post->post_author = 1;
            $wp_query->queried_object=$post;
            $wp_query->post=$post;
            $wp_query->posts = array($post);
            $wp_query->found_posts = 1;
            $wp_query->post_count = 1;
            $wp_query->max_num_pages = 1;
            $wp_query->is_single = 1;
            $wp_query->is_404 = false;
            $wp_query->is_posts_page = 1;

            $wp_query->page=false;
            $wp_query->is_post=true;

            remove_all_actions('wp_head');
            remove_all_actions('get_header'); remove_all_actions('template_redirect'); //для плагинов с редиректом

            add_action('wp_head', array(&$this, 'itex_init_sape_articles_wp_head'),-999);
        }
        if (!headers_sent())
        {
            header(200);
            $this->itex_debug('header 200 sent');
            echo '';flush(); //чтоб не переопределили хеадер,куки для шаблона не нужны
        }

    }

    /**
     * get sape articles in wp_head()
     *
     * @return  bool
     */
    function itex_init_sape_articles_wp_head()
    {

        echo '<!-- iMoney start-->
<meta http-equiv="content-type" content="text/html; charset={meta_charset}" >
<meta name="description" content="{description}">
<meta name="keywords" content="{keywords}">
<!-- iMoney end-->';
        //phpinfo();
        //die('itex_init_sape_articles_wp_head');

        return ;
    }


    /**
     * zilla init
     *
     * @return  bool
     */
    function itex_init_zilla()
    {
        if (!$this->get_option('itex_m_zilla_enable') ) return 0;

        if (!defined('_zilla_USER')) define('_ZILLA_USER', $this->get_option('itex_m_zilla_user'));
        else $this->error .= '_ZILLA_USER '.$this->__('already defined<br/>', 'iMoney');
        $this->itex_debug('_ZILLA_USER = '.$this->get_option('itex_m_zilla_user'));

        //FOR MASS INSTALL ONLY, REPLACE if (0) ON if (1)
        //		if (0)
        //		{
        //			$this->update_option('itex_zilla_user', 'abcdarfkwpkgfkhagklhskdgfhqakshgakhdgflhadh'); //zilla uid
        //			$this->update_option('itex_zillacontext_enable', 1);
        //			$this->update_option('itex_zilla_enable', 1);
        //			$this->update_option('itex_zilla_links_footer', 'max');
        //		}

        $file = $this->document_root . '/' . $this->get_option('itex_m_zilla_user') . '/zilla.php'; //<< Not working in multihosting.
        if (file_exists($file)) require_once($file);
        else return 0;
        $this->itex_debug('zilla file:'.$file);
        $o['charset'] = $this->encoding;
        //$o['force_show_code'] = $this->force_show_code;
        $o['force_show_code'] = 1; // сделал так, тк новые страницы не добавляются
        $o['force_show_code'] = $this->force_show;
        $o['multi_site'] = true;
        if ($this->get_option('itex_m_zilla_enable'))
        {
            $this->zilla = new ZILLA_client($o);


            $i = 1;

            while ($i++)
            {
                $q = trim($this->zilla->return_links(1));
                if (empty($q) || !strlen($q))
                {
                    break;
                }



                //убрал, тк сайт не индексируются возможно из-за этого
                //if(!preg_match("/^\<\!\-\-/", $q)) $q .= $this->zilla->_links_delimiter; // убираем коммент, не повредит дебагу?

                if (strlen($q)) $this->links[] = $q.$this->zilla->_links_delimiter;

                $q1 = trim(strip_tags($q)); //если нет текста, то и нечего показывать, значит ссылок больше нет, но если что показали чеккод
                if (empty($q1) || !strlen($q1))
                {
                    break;
                }

                //!!!!!!!!!!check it, tk ne vozvrashaet pustuu stroku
                if ($i > 30) break;
            }
            if (!count($this->links)) // если нет размещенных ссылок, и включен debugenable добавляем чеккод
            {
                if ($this->get_option('itex_m_global_debugenable'))
                    $this->links[] = trim($this->zilla->return_links());
            }
            $this->itex_debug('zilla links:'.var_export($this->links, true));

            //$this->itex_init_zilla_links();

            ///check it
            $url = 1;
            if ($this->wordpress) if (is_object($GLOBALS['wp_rewrite'])) $url = url_to_postid($this->REQUEST_URI);

            if (($url) || !$this->get_option('itex_zilla_pages_enable'))
            {
                if ($this->get_option('itex_m_zilla_links_beforecontent') == '0')
                {
                    //$this->beforecontent = '';
                }
                else
                {
                    $this->beforecontent .= '<div>'.$this->itex_m_get_links(intval($this->get_option('itex_zilla_links_beforecontent'))).'</div>';
                }

                if ($this->get_option('itex_m_zilla_links_aftercontent') == '0')
                {
                    //$this->aftercontent = '';
                }
                else
                {

                        $this->aftercontent .= '<div>'.$css.$this->itex_m_get_links(intval($this->get_option('itex_m_zilla_links_aftercontent'))).'</div>';
                }
            }
            $countsidebar = $this->get_option('itex_m_zilla_links_sidebar');
            $check = $this->get_option('itex_m_global_debugenable')?'<!---check sidebar '.$countsidebar.'-->':'';
            if ($countsidebar == 'max')
            {
                //$this->sidebar = '<div>'.$this->zilla->return_links().'</div>';
            }
            elseif ($countsidebar == '0')
            {
                //$this->sidebar = '';
            }
            else
            {
                $this->sidebar_links .= '<div>'.$this->itex_m_get_links(intval($countsidebar)).'</div>';
            }
            $this->sidebar_links = $check.$this->sidebar_links;

            $countfooter = $this->get_option('itex_m_zilla_links_footer');
            $check = $this->get_option('itex_m_global_debugenable')?'<!---check footer '.$countfooter.'-->':'';
            $this->footer .= $check;
            if ($countfooter == 'max')
            {
                //$this->footer = '<div>'.$this->zilla->return_links().'</div>';
            }
            elseif ($countfooter == '0')
            {
                //$this->footer = '';
            }
            else
            {
                $this->footer .= '<div>'.$this->itex_m_get_links($countfooter).'</div>';
            }
            $this->footer = $check.$this->footer;

            if (($countsidebar == 'max') && ($countfooter == 'max')) $this->footer .= $this->itex_m_get_links();
            else
            {
                if  ($countsidebar == 'max') $this->sidebar_links .= $this->itex_m_get_links();
                else $this->footer .= $this->itex_m_get_links();
            }
            if (strlen($this->zilla->_error))$this->itex_debug('zilla error:'.var_export($this->zilla->_error, true));
        }




        return 1;
    }

    /**
     * tnx init
     *
     * @return  bool
     */
    function itex_init_tnx()
    {
        if (!$this->get_option('itex_m_tnx_enable')) return 0;
        $file = $this->document_root . '/' . 'tnxdir_'.md5($this->get_option('itex_m_tnx_tnxuser')) . '/tnx.php'; //<< Not working in multihosting.
        if (file_exists($file)) require_once($file);
        else return 0;
        $this->itex_debug('TNX_USER = '.$this->get_option('itex_m_tnx_tnxuser'));


        //		$dir .= '/..';
        //		for ($i = 0;$i<10;$i++) $dir .= '/..';
        //		$dir .= dirname($file).'/';
        //
        $dir = '/' . 'tnxdir_'.md5($this->get_option('itex_m_tnx_tnxuser')).'/';
        $this->tnx = new TNX_n($this->get_option('itex_m_tnx_tnxuser'), $dir);
        $this->tnx->_encoding = $this->encoding;



        if ($this->get_option('itex_m_tnx_enable'))
        {
            if ($this->get_option('itex_m_tnx_links_beforecontent') == '0')
            {
                //$this->beforecontent = '';
            }
            else
            {
                $this->beforecontent .= '<div>'.$this->tnx->show_link(intval($this->get_option('itex_tnx_links_beforecontent'))).'</div>';
            }

            if ($this->get_option('itex_m_tnx_links_aftercontent') == '0')
            {
                //$this->aftercontent = '';
            }
            else
            {
                $this->aftercontent .= '<div>'.$this->tnx->show_link(intval($this->get_option('itex_tnx_links_aftercontent'))).'</div>';
            }

            $countsidebar = $this->get_option('itex_m_tnx_links_sidebar');
            $check = $this->get_option('itex_m_global_debugenable')?'<!---check sidebar tnx'.$countsidebar.'-->':'';
            if ($countsidebar == 'max')
            {
                //$this->sidebar = '<div>'.$this->tnx->show_link().'</div>';
            }
            elseif ($countsidebar == '0')
            {
                //$this->sidebar = '';
            }
            else
            {
                $this->sidebar_links .= '<div>'.$this->tnx->show_link(intval($countsidebar)).'</div>';
            }
            $this->sidebar_links = $check.$this->sidebar_links;

            $countfooter = $this->get_option('itex_m_tnx_links_footer');
            $check = $this->get_option('itex_m_global_debugenable')?'<!---check footer tnx'.$countfooter.'-->':'';
            $this->footer .= $check;
            if ($countfooter == 'max')
            {
                //$this->footer = '<div>'.$this->tnx->show_link().'</div>';
            }
            elseif ($countfooter == '0')
            {
                //$this->footer = '';
            }
            else
            {
                $this->footer .= '<div>'.$this->tnx->show_link(intval($countfooter)).'</div>';
            }
            $this->footer = $check.$this->footer;

            if (($countsidebar == 'max') && ($countfooter == 'max')) $this->footer .=$this->tnx->show_link();
            else
            {
                if  ($countsidebar == 'max') $this->sidebar_links .=$this->tnx->show_link();
                else $this->footer .=$this->tnx->show_link();
            }
        }
        return 1;
    }


    /**
     * Trustlink init
     *
     * @return  bool
     */
    function itex_init_trustlink()
    {
        if (!$this->get_option('itex_m_trustlink_enable') ) return 0;

        if (!defined('TRUSTLINK_USER')) define('TRUSTLINK_USER', $this->get_option('itex_m_trustlink_user'));
        else $this->error .= 'TRUSTLINK_USER '.$this->__('already defined<br/>', 'iMoney');
        $this->itex_debug('TRUSTLINK_USER = '.$this->get_option('itex_m_trustlink_user'));

        $file = $this->document_root . DIRECTORY_SEPARATOR . TRUSTLINK_USER . DIRECTORY_SEPARATOR . 'trustlink.php'; //<< Not working in multihosting.
        if (file_exists($file)) require_once($file);
        else return 0;

        $o['charset'] = $this->encoding;
        $o['force_show_code'] = 1; // сделал так, тк новые страницы не добавляются
        $o['force_show_code'] = $this->force_show;
        $o['multi_site'] = true;
        $o['use_cache'] = true; //кеширование, только для нового кода
        if ($this->get_option('itex_m_trustlink_enable'))
        {
            $trustlink = new TrustlinkClient($o);

            if ($this->get_option('itex_m_trustlink_links_beforecontent') == '0')
            {
                //$this->beforecontent = '';
            }
            else
            {
                $this->beforecontent .= '<div>'.$trustlink->build_links().'</div>';
            }

            if ($this->get_option('itex_m_trustlink_links_aftercontent') == '0')
            {
                //$this->aftercontent = '';
            }
            else
            {
                $this->aftercontent .= '<div>'.$trustlink->build_links().'</div>';
            }

            $countsidebar = $this->get_option('itex_m_trustlink_links_sidebar');
            $check = $this->get_option('itex_m_global_debugenable')?'<!---check sidebar '.$countsidebar.'-->':'';
            if ($countsidebar == 'max')
            {
                $this->sidebar_links .= '<div>'.$trustlink->build_links().'</div>';
            }
            elseif ($countsidebar == '0')
            {
                //$this->sidebar = '';
            }

            $this->sidebar_links = $check.$this->sidebar_links;

            $countfooter = $this->get_option('itex_m_trustlink_links_footer');
            $check = $this->get_option('itex_m_global_debugenable')?'<!---check footer '.$countfooter.'-->':'';
            $this->footer .= $check;
            if ($countfooter == 'max')
            {
                //$this->footer = '<div>'.$this->mainlink->return_links().'</div>';
                $this->footer .= '<div>'.$trustlink->build_links().'</div>';
            }
            elseif ($countfooter == '0')
            {
                //$this->footer = '';
            }
            else
            {
                $this->footer .= '<div>'.$trustlink->build_links().'</div>';
            }
            $this->footer = $check.$this->footer;

        }
        return 1;
    }

    /**
     * Adsense init
     *
     * @return  bool
     */
    function itex_init_adsense()
    {
        if (!$this->get_option('itex_m_adsense_enable')) return 0;

        if ($this->get_option('itex_m_adsense_id'));
        else $this->error .= $this->__('Adsense Id not defined<br/>', 'iMoney');

        $maxblock = 4; //max  adsense blocks - 1
        for ($block=1;$block<$maxblock;$block++)
        {
            if ($this->get_option('itex_m_adsense_b'.$block.'_enable'))
            {
                $size = $this->get_option('itex_m_adsense_b'.$block.'_size');
                $size = explode('x',$size);

                //$pos = array('sidebar', 'footer', 'beforecontent','aftercontent');
                $script = '<script type="text/javascript"><!--
google_ad_client = "'.$this->get_option('itex_m_adsense_id').'"; google_ad_slot = "'.$this->get_option('itex_m_adsense_b'.$block.'_adslot').'"; google_ad_width = '.$size[0].'; google_ad_height = '.$size[1].';
//--></script><script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';
                $pos = $this->get_option('itex_m_adsense_b'.$block.'_pos');
                switch ($pos)
                {
                    case 'sidebar':
                        {
                        $this->sidebar['imoney_adsense_'.$block] = '<p>'.$script.'</p>';
                        //die('imoney_adsense_'.$block);
                        break;
                        }
                    case 'footer':
                        {
                        $this->footer .= '<p style="float:left;">'.$script.'</p>';
                        break;
                        }
                    case 'beforecontent':
                        {
                        $this->beforecontent .= '<p style="float:left;">'.$script.'</p>';
                        break;
                        }
                    case 'aftercontent':
                        {
                        $this->aftercontent .= '<p style="float:left;">'.$script.'</p>';
                        break;
                        }
                    default: {}
                }

            }
        }
        return 1;
    }

    /**
     * Teasernet init
     *
     * @return  bool
     */
    function itex_init_teasernet()
    {
        if (!$this->get_option('itex_m_teasernet_enable')) return 0;

        if ($this->get_option('itex_m_teasernet_padid'));
        else $this->error .= $this->__('Teasernet Id not defined<br/>', 'iMoney');

        $maxblock = 4; //max  teasernet blocks - 1
        for ($block=1;$block<$maxblock;$block++)
        {
            if ($this->get_option('itex_m_teasernet_b'.$block.'_enable'))
            {
                $size = $this->get_option('itex_m_teasernet_b'.$block.'_size');
                $size = explode('x',$size);

                //$pos = array('sidebar', 'footer', 'beforecontent','aftercontent');
                $script = '<script type="text/javascript"><!--
teasernet_blockid = '.$this->get_option('itex_m_teasernet_b'.$block.'_blockid').';
teasernet_padid = '.$this->get_option('itex_m_teasernet_padid').';
//--></script><script type="text/javascript" src="http://associeta.com/block.js"></script>';
                $pos = $this->get_option('itex_m_teasernet_b'.$block.'_pos');
                switch ($pos)
                {
                    case 'sidebar':
                        {
                        $this->sidebar['imoney_teasernet_'.$block] = '<p>'.$script.'</p>';
                        //die('imoney_teasernet_'.$block);
                        break;
                        }
                    case 'footer':
                        {
                        $this->footer .= '<p style="float:left;">'.$script.'</p>';
                        break;
                        }
                    case 'beforecontent':
                        {
                        $this->beforecontent .= '<p style="float:left;">'.$script.'</p>';
                        break;
                        }
                    case 'aftercontent':
                        {
                        $this->aftercontent .= '<p style="float:left;">'.$script.'</p>';
                        break;
                        }
                    default: {}
                }

            }
        }
        return 1;
    }


    /**
     * Begun init
     *
     * @return  bool
     */
    function itex_init_begun()
    {
        if (!$this->get_option('itex_m_begun_enable')) return 0;

        if ($this->get_option('itex_m_begun_id'));
        else $this->error .= $this->__('begun Id not defined<br/>', 'iMoney');

        $maxblock = 4; //max  begun blocks - 1
        for ($block=1;$block<$maxblock;$block++)
        {
            if ($this->get_option('itex_m_begun_b'.$block.'_enable'))
            {
                $script = '<p><script type="text/javascript"><!--
var begun_auto_pad = '.$this->get_option('itex_m_begun_id').';var begun_block_id = '.$this->get_option('itex_m_begun_b'.$block.'_block_id').';
//--></script><script type="text/javascript" src="http://autocontext.begun.ru/autocontext2.js"></script></p>';
                $pos = $this->get_option('itex_m_begun_b'.$block.'_pos');
                switch ($pos)
                {
                    case 'sidebar':
                        {
                        $this->sidebar['iMoney_begun_'.$block] = $script;
                        break;
                        }
                    case 'footer':
                        {
                        $this->footer .= $script;
                        break;
                        }
                    case 'beforecontent':
                        {
                        $this->beforecontent .= $script;
                        break;
                        }
                    case 'aftercontent':
                        {
                        $this->aftercontent .= $script;
                        break;
                        }
                    default: {}
                }

            }
        }
        return 1;
    }


    /**
     * admitad init
     *
     * @return  bool
     */
    function itex_init_admitad()
    {
        if (!$this->get_option('itex_m_admitad_enable')) return 0;

        //if ($this->get_option('itex_m_admitad_id'));
        //else $this->error .= $this->__('admitad Id not defined<br/>', 'iMoney');

        $maxblock = 4; //max  admitad blocks - 1
        for ($block=1;$block<$maxblock;$block++)
        {
            if ($this->get_option('itex_m_admitad_b'.$block.'_enable'))
            {
                $script = '<p><script type="text/javascript">(function() {
    /* Optional settings (these lines can be removed): */
    subID = "";  // - local banner key;
    injectTo = "";  // - #id of html element (ex., "top-banner").
    /* End settings block */

    if(injectTo=="")injectTo="admitad_shuffle"+subID+Math.round(Math.random()*100000000);
    if(subID=="")subid_block=""; else subid_block="subid/"+subID+"/";
    document.write(\'<div id="\'+injectTo+\'"></div>\');
    var s = document.createElement("script");
    s.type = "text/javascript"; s.async = true;
    s.src = "http://www.ad.admitad.com/shuffle/'.$this->get_option('itex_m_admitad_b'.$block.'_id').'/"+subid_block+"?inject_to="+injectTo;
    var x = document.getElementsByTagName("script")[0];
    x.parentNode.insertBefore(s, x);
})();</script></p>';
                $pos = $this->get_option('itex_m_admitad_b'.$block.'_pos');
                switch ($pos)
                {
                    case 'sidebar':
                        {
                        $this->sidebar['iMoney_admitad_'.$block] = $script;
                        break;
                        }
                    case 'footer':
                        {
                        $this->footer .= $script;
                        break;
                        }
                    case 'beforecontent':
                        {
                        $this->beforecontent .= $script;
                        break;
                        }
                    case 'aftercontent':
                        {
                        $this->aftercontent .= $script;
                        break;
                        }
                    default: {}
                }

            }
        }
        return 1;
    }


    /**
     * adskape init
     *
     * @return  bool
     */
    function itex_init_adskape()
    {
        if (!$this->get_option('itex_m_adskape_enable')) return 0;

        if ($this->get_option('itex_m_adskape_id'));
        else $this->error .= $this->__('Adskape Id not defined<br/>', 'iMoney');

        $maxblock = 4; //max  adskape blocks - 1
        for ($block=1;$block<$maxblock;$block++)
        {
            if ($this->get_option('itex_m_adskape_b'.$block.'_enable'))
            {
                $size = $this->get_option('itex_m_adskape_b'.$block.'_size');
                //$size = explode('x',$size);

                //$pos = array('sidebar', 'footer', 'beforecontent','aftercontent');
                $script = '<script type="text/javascript" src="http://p'.$this->get_option('itex_m_adskape_id').'.adskape.ru/adout.js?p='.$this->get_option('itex_m_adskape_id').'&t='.$size.'"></script>';
                $pos = $this->get_option('itex_m_adskape_b'.$block.'_pos');
                switch ($pos)
                {
                    case 'sidebar':
                        {
                        $this->sidebar['imoney_adskape_'.$block] = '<div style="clear:right;">'.$script.'</div>';
                        break;
                        }
                    case 'footer':
                        {
                        $this->footer .= '<p style="float:left;">'.$script.'</p>';
                        break;
                        }
                    case 'beforecontent':
                        {
                        $this->beforecontent .= '<p style="float:left;">'.$script.'</p>';
                        break;
                        }
                    case 'aftercontent':
                        {
                        $this->aftercontent .= '<p style="float:left;">'.$script.'</p>';
                        break;
                        }
                    default: {}
                }

            }
        }
        return 1;
    }


    /**
     * Html init
     *
     * @return  bool
     */
    function itex_init_html()
    {
        if (!$this->get_option('itex_m_html_enable')) return 0;

        if ($this->get_option('itex_m_html_sidebar_enable')) $this->sidebar['iMoney_html'] = stripslashes($this->get_option('itex_m_html_sidebar'));
        if ($this->get_option('itex_m_html_footer_enable')) $this->footer .= stripslashes($this->get_option('itex_m_html_footer'));
        if ($this->get_option('itex_m_html_beforecontent_enable')) $this->beforecontent .= stripslashes($this->get_option('itex_m_html_beforecontent'));
        if ($this->get_option('itex_m_html_aftercontent_enable')) $this->aftercontent .= stripslashes($this->get_option('itex_m_html_aftercontent'));
    }

    /**
     * php init
     *
     * @return  bool
     */
    function itex_init_php()
    {
        if (!$this->get_option('itex_m_php_enable')) return 0;
        if (preg_match('@wp-admin@i',$this->server['PHP_SELF'])) return 0; //можно вернуться в админку и исправить косяки
        if ($this->get_option('itex_m_php_sidebar_enable'))
        {
            ob_start();
            $code = stripslashes($this->get_option('itex_m_php_sidebar'));
            if (strlen($code)>1) if ($this->itex_m_admin_php_syntax($code)) eval($code);
            $code = ob_get_contents();
            ob_end_clean();
            $this->sidebar['iMoney_php'] = $code;
        }
        if ($this->get_option('itex_m_php_beforecontent_enable'))
        {
            ob_start();
            $code = stripslashes($this->get_option('itex_m_php_beforecontent'));
            if (strlen($code)>1) if ($this->itex_m_admin_php_syntax($code)) eval($code);
            $code = ob_get_contents();
            ob_end_clean();
            $this->beforecontent .= $code;
        }
        if ($this->get_option('itex_m_php_aftercontent_enable'))
        {
            ob_start();
            $code = stripslashes($this->get_option('itex_m_php_aftercontent'));
            if (strlen($code)>1) if ($this->itex_m_admin_php_syntax($code)) eval($code);
            $code = ob_get_contents();
            ob_end_clean();
            $this->aftercontent .= $code;
        }
        if ($this->get_option('itex_m_php_footer_enable'))
        {
            ob_start();
            $code = stripslashes($this->get_option('itex_m_php_footer'));
            //echo '_php'.$code;die();
            if (strlen($code)>1) if ($this->itex_m_admin_php_syntax($code)) if (eval($code)){};
            $code = ob_get_contents();
            ob_end_clean();
            $this->footer .= $code;
        }
        return true;
    }


    /**
     * iLinks init
     *
     * @return  bool
     */
    function itex_init_ilinks()
    {
        if (!$this->get_option('itex_m_ilinks_enable')) return 0;
        $separator = trim($this->get_option('itex_m_ilinks_separator'));
        if (empty($separator)) return 0;
        if ($this->get_option('itex_m_ilinks_sidebar_enable'))
        {
            $l = explode("\n",stripslashes($this->get_option('itex_m_ilinks_sidebar')));
            foreach ($l as $q)
            {
                $w = explode($separator,trim($q),2);
                if (strtolower($w[0]{0}) == 'r')
                {
                    $w[0] = substr($w[0],1,strlen($w[0]));
                    if (preg_match("@".$w[0]."@i",$this->REQUEST_URI)) $this->sidebar['iMoney_ilinks']  .= $w[1];
                }
                elseif  ($this->REQUEST_URI == $w[0]) $this->sidebar['iMoney_ilinks']  .= $w[1];

            }
        }
        if ($this->get_option('itex_m_ilinks_footer_enable'))
        {
            $l = explode("\n",stripslashes($this->get_option('itex_m_ilinks_footer')));
            foreach ($l as $q)
            {
                $w = explode($separator,trim($q),2);
                if (strtolower($w[0]{0}) == 'r')
                {
                    $w[0] = substr($w[0],1,strlen($w[0]));
                    if (preg_match("@".$w[0]."@i",$this->REQUEST_URI)) $this->footer .= $w[1];

                }
                elseif  ($this->REQUEST_URI == $w[0]) $this->footer .= $w[1];
            }
        }
        if ($this->get_option('itex_m_ilinks_beforecontent_enable'))
        {
            $l = explode("\n",stripslashes($this->get_option('itex_m_ilinks_beforecontent')));
            foreach ($l as $q)
            {
                $w = explode($separator,trim($q),2);
                if (strtolower($w[0]{0}) == 'r')
                {
                    $w[0] = substr($w[0],1,strlen($w[0]));
                    if (preg_match("@".$w[0]."@i",$this->REQUEST_URI)) $this->beforecontent .= $w[1];

                }
                elseif  ($this->REQUEST_URI == $w[0]) $this->beforecontent .= $w[1];
            }
        }
        if ($this->get_option('itex_m_ilinks_aftercontent_enable'))
        {
            $l = explode("\n",stripslashes($this->get_option('itex_m_ilinks_aftercontent')));
            foreach ($l as $q)
            {
                $w = explode($separator,trim($q),2);
                if (strtolower($w[0]{0}) == 'r')
                {
                    $w[0] = substr($w[0],1,strlen($w[0]));
                    //if (eregi($w[0],$this->REQUEST_URI)) $this->aftercontent .= $w[1];
                    if (preg_match("@".$w[0]."@i",$this->REQUEST_URI)) $this->aftercontent .= $w[1];
                }
                elseif  ($this->REQUEST_URI == $w[0]) $this->aftercontent .= $w[1];
            }
        }
        return true;
    }

    /**
     * mainlink init
     *
     * @return  bool
     */
    function itex_init_mainlink()
    {
        if (!$this->get_option('itex_m_mainlink_enable')) return 0;
        if (!$this->get_option('itex_m_mainlink_mainlinkuser')) return 0;
        if (!defined('SECURE_CODE')) define('SECURE_CODE', $this->get_option('itex_m_mainlink_mainlinkuser'));
        else $this->error .= 'SECURE_CODE '.$this->__('already defined<br/>', 'iMoney');
        $this->itex_debug('MAINLINK_USER = '.$this->get_option('itex_m_mainlink_mainlinkuser'));



        $file = $this->document_root . '/mainlink_'.SECURE_CODE.'/ML.php';
        if (file_exists($file))
        {

            require_once($file);
        }
        else return 0;

        $mlcfg=array();
        if (preg_match('@1251@i', $this->encoding)) $mlcfg['charset'] = 'win';
        else $mlcfg['charset'] = 'utf';

        if ($this->get_option('itex_m_global_debugenable'))
        {
            $mlcfg['debugmode'] = 1;
        }

        //$mlcfg['is_mod_rewrite'] = 1;  //проверить че за нах
        //$mlcfg['redirect'] = 0;

        $mlcfg['uri'] = $this->safeurl;
        $ml->Set_Config($mlcfg);
        if ($this->get_option('itex_m_mainlink_enable'))
        {
            if ($this->get_option('itex_m_mainlink_links_beforecontent') == '0')
            {
                //$this->beforecontent = '';
            }
            else
            {
                $this->beforecontent .= '<div>'.$ml->Get_Links(intval($this->get_option('itex_m_mainlink_links_beforecontent'))).'</div>';
            }

            if ($this->get_option('itex_m_mainlink_links_aftercontent') == '0')
            {
                //$this->aftercontent = '';
            }
            else
            {
                $this->aftercontent .= '<div>'.$ml->Get_Links(intval($this->get_option('itex_m_mainlink_links_aftercontent'))).'</div>';
            }
            //}
            $countsidebar = $this->get_option('itex_m_mainlink_links_sidebar');
            $check = $this->get_option('itex_m_global_debugenable')?'<!---check sidebar '.$countsidebar.'-->':'';
            if ($countsidebar == 'max')
            {
                //$this->sidebar = '<div>'.$this->mainlink->return_links().'</div>';
            }
            elseif ($countsidebar == '0')
            {
                //$this->sidebar = '';
            }
            else
            {
                $this->sidebar_links .= '<div>'.$ml->Get_Links(intval($countsidebar)).'</div>';
            }
            $this->sidebar_links = $check.$this->sidebar_links;

            $countfooter = $this->get_option('itex_m_mainlink_links_footer');
            $check = $this->get_option('itex_m_global_debugenable')?'<!---check footer '.$countfooter.'-->':'';
            $this->footer .= $check;
            if ($countfooter == 'max')
            {
                //$this->footer = '<div>'.$this->mainlink->return_links().'</div>';
            }
            elseif ($countfooter == '0')
            {
                //$this->footer = '';
            }
            else
            {
                $this->footer .= '<div>'.$ml->Get_Links($countfooter).'</div>';
            }
            $this->footer = $check.$this->footer;

            if (($countsidebar == 'max') && ($countfooter == 'max')) $this->footer .= $ml->Get_Links();
            else
            {
                if  ($countsidebar == 'max') $this->sidebar_links .= $ml->Get_Links();
                else $this->footer .= $ml->Get_Links();
            }

        }

        return 1;
    }

    /**
     * linkfeed init
     *
     * @return  bool
     */
    function itex_init_linkfeed()
    {
        if (!$this->get_option('itex_m_linkfeed_enable')) return 0;
        if (!defined('LINKFEED_USER')) define('LINKFEED_USER', $this->get_option('itex_m_linkfeed_linkfeeduser'));
        else $this->error .= 'LINKFEED_USER '.$this->__('already defined<br/>', 'iMoney');
        $this->itex_debug('LINKFEED_USER = '.$this->get_option('itex_m_linkfeed_linkfeeduser'));



        $file = $this->document_root . '/linkfeed_'.LINKFEED_USER.'/linkfeed.php';
        if (file_exists($file))
        {
            require_once($file);
        }
        else return 0;

        $o['charset'] = $this->encoding;
        $o['multi_site'] = true;

        if ($this->get_option('itex_m_global_debugenable'))
        {
            $o['force_show_code'] = 1;
            //$o['verbose'] = 1;  //в футере инфу выдаст
        }
        $o['force_show_code'] = $this->force_show;
        $linkfeed = new LinkfeedClient($o);

        if ($this->get_option('itex_m_linkfeed_enable'))
        {
            if ($this->get_option('itex_m_linkfeed_links_beforecontent') == '0')
            {
                //$this->beforecontent = '';
            }
            else
            {
                $this->beforecontent .= '<div>'.$linkfeed->return_links(intval($this->get_option('itex_m_linkfeed_links_beforecontent'))).'</div>';
            }

            if ($this->get_option('itex_m_linkfeed_links_aftercontent') == '0')
            {
                //$this->aftercontent = '';
            }
            else
            {
                $this->aftercontent .= '<div>'.$linkfeed->return_links(intval($this->get_option('itex_m_linkfeed_links_aftercontent'))).'</div>';
            }
            //}
            $countsidebar = $this->get_option('itex_m_linkfeed_links_sidebar');
            $check = $this->get_option('itex_m_global_debugenable')?'<!---check sidebar '.$countsidebar.'-->':'';
            if ($countsidebar == 'max')
            {
                //$this->sidebar = '<div>'.$this->linkfeed->return_links().'</div>';
            }
            elseif ($countsidebar == '0')
            {
                //$this->sidebar = '';
            }
            else
            {
                $this->sidebar_links .= '<div>'.$linkfeed->return_links(intval($countsidebar)).'</div>';
            }
            $this->sidebar_links = $check.$this->sidebar_links;

            $countfooter = $this->get_option('itex_m_linkfeed_links_footer');
            $check = $this->get_option('itex_m_global_debugenable')?'<!---check footer '.$countfooter.'-->':'';
            $this->footer .= $check;
            if ($countfooter == 'max')
            {
                //$this->footer = '<div>'.$this->linkfeed->return_links().'</div>';
            }
            elseif ($countfooter == '0')
            {
                //$this->footer = '';
            }
            else
            {
                $this->footer .= '<div>'.$linkfeed->return_links($countfooter).'</div>';
            }
            $this->footer = $check.$this->footer;

            if (($countsidebar == 'max') && ($countfooter == 'max')) $this->footer .= $linkfeed->return_links();
            else
            {
                if  ($countsidebar == 'max') $this->sidebar_links .= $linkfeed->return_links();
                else $this->footer .= $linkfeed->return_links();
            }

        }

        return 1;
    }

    /**
     * setlinks init
     * Author Zya
     *
     * @return  bool
     */
    function itex_init_setlinks()
    {
        if (!$this->get_option('itex_m_setlinks_enable')) return 0;
        if (!$this->get_option('itex_m_setlinks_setlinksuser')) return 0;

        $this->itex_debug('SETLINKS_USER = '.$this->get_option('itex_m_setlinks_setlinksuser'));
        $file = $this->document_root . '/setlinks_' . $this->get_option('itex_m_setlinks_setlinksuser') . '/slclient.php'; //<< Not working in multihosting.
        if (file_exists($file)) require_once($file);
        else return 0;

        if ($this->get_option('itex_m_setlinks_enable'))
        {
            $this->setlinks = new SLClient();

            $this->setlinks->Config->encoding = $this->encoding;
            //$this->setlinks->Config->show_comment = (bool)$this->get_option('itex_m_global_debugenable');
            $this->setlinks->Config->show_comment = true;
            $this->setlinks->Config->use_safe_method = (bool)$this->get_option('itex_m_setlinks_masking');

            $this->itex_init_setlinks_links();

            ///check it
            $url = 1;
            if ($this->wordpress) if (is_object($GLOBALS['wp_rewrite'])) $url = url_to_postid($this->REQUEST_URI);

            if (($url) || !$this->get_option('itex_setlinks_pages_enable'))
            {
                if ((bool)$this->get_option('itex_m_setlinks_links_beforecontent'))
                {
                    $this->beforecontent .= '<div>'.$this->itex_m_get_links(intval($this->get_option('itex_m_setlinks_links_beforecontent'))).'</div>';
                }

                if ((bool)$this->get_option('itex_m_setlinks_links_aftercontent'))
                {
                    $this->aftercontent .= '<div>'.$this->itex_m_get_links(intval($this->get_option('itex_m_setlinks_links_aftercontent'))).'</div>';
                }
            }

            $countsidebar = $this->get_option('itex_m_setlinks_links_sidebar');
            $check = $this->get_option('itex_m_global_debugenable')?'<!---check sidebar '.$countsidebar.'-->':'';
            if ($countsidebar == 'max')
            {
                //$this->sidebar = '<div>'.$this->setlinks->GetLinks().'</div>';
            }
            elseif ($countsidebar == '0')
            {
                //$this->sidebar = '';
            }
            else
            {
                $this->sidebar_links .= '<div>'.$this->itex_m_get_links(intval($countsidebar)).'</div>';
            }
            $this->sidebar_links = $check.$this->sidebar_links;

            //setlinks footer
            $countfooter = $this->get_option('itex_m_setlinks_setlinks_footer');
            $check = $this->get_option('itex_m_global_debugenable')?'<!---check footer '.$countfooter.'-->':'';
            $this->footer .= $check;
            if ($countfooter == 'max')
            {
                //$this->footer = '<div>'.$this->sape->GetLinks().'</div>';
            } elseif ($countfooter == '0') {
                //$this->footer = '';
            } else {
                $this->footer .= '<div>'.$this->itex_m_get_links($countfooter).'</div>';
            }
            $this->footer = $check.$this->footer;

            if (($countsidebar == 'max') && ($countfooter == 'max'))
            {
                $this->footer .= $this->itex_m_get_links();
            } else {
                if ($countsidebar == 'max')
                {
                    $this->sidebar_links .= $this->itex_m_get_links();
                } else $this->footer .= $this->itex_m_get_links();
            }
        }

        if ($this->get_option('itex_m_setlinks_setlinkscontext_enable'))
        {
            $this->setlinkscontext = new SLClient();
            add_filter('the_content', array(&$this, 'itex_m_replace'));
            //add_filter('the_excerpt', array(&$this, 'itex_m_replace'));
        }
        return 1;
    }

    /**
     * get setlinks links
     * Author Zya
     *
     * @return  bool
     */
    function itex_init_setlinks_links()
    {
        $i = 1;

        while ($i++)
        {
            $q = $this->setlinks->GetLinks(1);
            if (empty($q) || !strlen($q))
            {
                break;
            }

            if (strlen($q)) $this->links[] = $q;

            //!!!!!!!!!!check it, tk ne vozvrashaet pustuu stroku
            if ($i > 30) break;
        }
        $this->itex_debug('setlinks links:'.var_export($this->links, true));
        return 1;
    }

    /** Output Functions  **/

    /**
     * Footer output
     *
     */
    function itex_m_footer()
    {
        echo $this->footer;
        //		if ($this->get_option('itex_m_php_enable') && $this->get_option('itex_m_php_footer_enable'))
        //		{
        //			$code = $this->get_option('itex_m_php_footer');
        //			if (strlen($code)>1) eval($code);
        //		}

        if ($this->get_option('itex_m_global_debugenable'))
        {
            //echo 'is_user_logged_in'.intval(is_user_logged_in()).'_'.intval($this->get_option('itex_m_global_debugenable_forall'));//die();
            //echo 'reqweqweqweqweqwe';//die();
            if ((intval(is_user_logged_in())) || intval($this->get_option('itex_m_global_debugenable_forall')))
            {
                $this->debuglog = str_ireplace('<!--','<! --',$this->debuglog);
                $this->debuglog = str_ireplace('-->','-- >',$this->debuglog);
                echo '<!--- iMoneyDebugLogStart'.$this->debuglog.' iMoneyDebugLogEnd --->';
                echo '<!--- iMoneyDebugErrorsStart'.$this->error.' iMoneyDebugErrorsEnd --->';
            }
        }
    }

    /**
     * Content links and before-after content links
     *
     * @param   string   $content   input text
     * @return  string	$content   outpu text
     */
    function itex_m_replace($content)
    {
        // можно сделать function itex_m_replace(&$content)   для совместимости с джумлой и в конце возвращать true
        //if ($this->get_option('itex_m_sape_sapearticles_enable'))
        //{
        /*if (strpos($content, "<!-- SAPE_articles -->") !== FALSE)
		{
		//$content = preg_replace('/<p>\s*<!--(.*)-->\s*<\/p>/i', "<!--$1-->", $content);
		$content = str_replace('<!-- SAPE_articles -->', SAPE_articles(), $content);

		}*/
        //		$content = $content.'<p>'.$this->sapearticles->return_announcements().'</p>';
        //		$this->itex_debug('sapearticles worked');
        //	}

        //sape context
        if ($this->get_option('itex_m_sape_sapecontext_enable'))
        {
            if (url_to_postid($this->REQUEST_URI) || !$this->get_option('itex_sape_pages_enable'))
            {
                //if (defined('_SAPE_USER') || is_object($this->sapecontext))
                if (is_object($this->sapecontext))
                {
                    $content = $this->sapecontext->replace_in_text_segment($content);
                    if ($this->get_option('itex_m_global_debugenable'))
                    {
                        $content = '<!---checkcontext_start-->'.$content.'<!---checkcontext_stop-->';
                    }
                    $this->itex_debug('sapecontext worked');
                }
                else $this->itex_debug('$this->sapecontext not object');
            }
            else $this->itex_debug('url_to_postid='.url_to_postid($this->REQUEST_URI).' itex_sape_pages_enable='.$this->get_option('itex_sape_pages_enable'));
        }
        else $this->itex_debug('sapecontext disabled');


        if ((strlen($this->beforecontent)) || (strlen($this->aftercontent)))
        {
            if ($this->get_option('itex_m_global_debugenable'))
            {

                $content = '<!---check_beforecontent-->'.$this->beforecontent.$content.'<!---check_aftercontent-->'.$this->aftercontent;
            }
            else $content = $this->beforecontent.$content.$this->aftercontent;
            $this->beforecontent=$this->aftercontent='';
            $this->itex_debug('links in content worked');
        }
        else $this->itex_debug('beforecontent and aftercontent is empty');

        return $content;
    }

    /**
     *
     *
     * @param   string   $domnod   $text
     * @return  string	$text
     */
    function itex_m_widget_init()
    {
        //$this->itex_debug('All possible Widgets '.var_export($this->sidebar, true));
        //		if (count($this->sidebar))
        //		{
        //			foreach ($this->sidebar as $k => $v)
        //			{
        //				if (function_exists('register_sidebar_widget'))
        //				{
        //					//register_sidebar_widget($k, array(&$this, 'itex_m_widget'));
        //					//$newfunc = create_function('$arg', 'extract($args, EXTR_SKIP);
        //		//echo $before_widget.$before_title . $title . $after_title.
        //		//"<ul><li>".$this->sidebar[$widget_name]."</li></ul>".$after_widget;
        //		//$this->itex_debug("!widget init ".$widget_name);');
        //					//register_sidebar_widget($k, $newfunc);
        //
        //				}
        //				if (function_exists('register_widget_control'))
        //				{
        //					//register_widget_control($k, array(&$this, 'itex_m_widget_control'), 300, 200 );
        //					//$newfunc = create_function('$arg', 'echo "<p>Dynamic widget control for '.$k.' </p>";');
        //					//register_widget_control($k, $newfunc, 300, 200 );
        //
        //				}
        //				$this->itex_debug('Widget '.$k.'= '.$v);
        //
        //			}itex_m_widget_dynamic_control
        //		}

        if (function_exists('register_sidebar_widget')) register_sidebar_widget('iMoney Dynamic', array(&$this, 'itex_m_widget_dynamic'));
        if (function_exists('register_widget_control')) register_widget_control('iMoney Dynamic', array(&$this, 'itex_m_widget_dynamic_control'), 300, 200 );

        if (function_exists('register_sidebar_widget')) register_sidebar_widget('iMoney Links', array(&$this, 'itex_m_widget_links'));
        if (function_exists('register_widget_control')) register_widget_control('iMoney Links', array(&$this, 'itex_m_widget_links_control'), 300, 200 );
        //$ws = wp_get_sidebars_widgets();
        //$this->itex_debug('All registered Widgets '.var_export($ws, true));

    }

    /**
     * Dynamic widget
     *
     * @param   array   $args   arguments for widget
     */
    function itex_m_widget_dynamic($args)
    {
        extract($args, EXTR_SKIP);
        $this->itex_debug('All possible Widgets '.var_export($this->sidebar, true));
        $title = $this->get_option("itex_m_widget_dynamic_title");
        //$title = empty($title) ? urlencode('<a href="http://itex.name" title="iMoney">iMoney</a>') :$title;

        if (count($this->sidebar))
        {
            foreach ($this->sidebar as $k => $v)
            {
                echo $before_widget.$before_title .  $title . $after_title.
                    '<ul><li>'.$v.'</li></ul>'.$after_widget;
                $this->itex_debug('widget init '.$k);
            }
        }
    }

    /**
     * Dynamic widget control
     *
     */
    function itex_m_widget_dynamic_control()
    {
        echo '<p>Dynamic widget control for iMoney</p>';
        $title = $this->get_option("itex_m_widget_dynamic_title");
        //$itex = array('<a href="http://itex.name/imoney" title="iMoney">iMoney</a>','<a href="http://itex.name/" title="itex">itex</a>');

        //		$title_links = $this->get_option("itex_m_widget_links_title");
        //		if ((!eregi('itex.name',$title_links)) || empty($title_links)) $itex = array('<a href="http://itex.name/imoney" title="iMoney">iMoney</a>','<a href="http://itex.name/" title="itex">itex</a>');
        //		else $itex = array('','');
        //$title = empty($title) ? $itex[rand(0,count($itex)-1)] :$title;
        if ($_POST['itex_m_widget_dynamic_Submit'])
        {
            //$title = htmlspecialchars($_POST['itex_m_widget_title']);
            $title = stripslashes($_POST['itex_m_widget_dynamic_title']);
            $this->update_option("itex_m_widget_dynamic_title", $title);
        }
        echo '
  			<p>
    			<label for="itex_m_widget_dynamic">'.$this->__('Widget Title: ', 'iMoney').'</label>
    			<textarea name="itex_m_widget_dynamic_title" id="itex_m_widget_dynamic" rows="1" cols="20">'.$title.'</textarea>
    			<input type="hidden" id="" name="itex_m_widget_dynamic_Submit" value="1" />
  			</p>';
    }

    /**
     * Dynamic widget
     *
     * @param   array   $args   arguments for widget
     */
    function itex_m_widget($args)
    {
        extract($args, EXTR_SKIP);
        echo $before_widget.$before_title . $title . $after_title.
            '<ul><li>'.$this->sidebar[$widget_name].'</li></ul>'.$after_widget;
        $this->itex_debug('!widget init '.$widget_name);
    }

    /**
     * Dynamic widget control
     *
     */
    function itex_m_widget_control()
    {
        echo '<p>Dynamic widget control for iMoney</p>';
    }
    /**
     * Links widget
     *
     * @param   array   $args   arguments for widget
     */
    function itex_m_widget_links($args)
    {
        extract($args, EXTR_SKIP);
        $title = $this->get_option("itex_m_widget_links_title");
        //$title = empty($title) ? urlencode('<a href="http://itex.name" title="iMoney">iMoney</a>') :$title;
        $itex = array('<a href="http://itex.name/imoney" title="iMoney">iMoney</a>','<a href="http://itex.name/" title="itex">itex</a>');
        if (empty($title))
        {
            $title = $itex[rand(0,count($itex)-1)];
            $this->update_option("itex_m_widget_links_title", $title);
        }

        if (strlen($this->sidebar_links) >23) echo $before_widget.$before_title . $title . $after_title.
            '<ul><li>'.$this->sidebar_links.'</li></ul>'.$after_widget;
    }

    /**
     *  Links widget control
     *
     * @param   string   $domnod   $text
     */
    function itex_m_widget_links_control()
    {
        $title = $this->get_option("itex_m_widget_links_title");
        $itex = array('<a href="http://itex.name/imoney" title="iMoney">iMoney</a>','<a href="http://itex.name/" title="itex">itex</a>');
        $title = empty($title) ? $itex[rand(0,count($itex)-1)] :$title;
        if ($_POST['itex_m_widget_links_Submit'])
        {
            //$title = htmlspecialchars($_POST['itex_m_widget_title']);
            $title = stripslashes($_POST['itex_m_widget_links_title']);
            $this->update_option("itex_m_widget_links_title", $title);
        }
        echo '
  			<p>
    			<label for="itex_m_widget_links">'.$this->__('Widget Title: ', 'iMoney').'</label>
    			<textarea name="itex_m_widget_links_title" id="itex_m_widget_links" rows="1" cols="20">'.$title.'</textarea>
    			<input type="hidden" id="" name="itex_m_widget_links_Submit" value="1" />
  			</p>';
        //print_r($this->debuglog);//die();
    }

    /** Admin Functions  **/


    /**
     * Add admin menu to options
     *
     * @param   string   $domnod   $text
     * @return  string	$text
     */
    function itex_m_menu()
    {
        if (is_admin()) add_options_page('iMoney', 'iMoney', 10, basename(__FILE__), array(&$this, 'itex_m_admin'));
    }

    /**
     * Admin menu
     *
     */
    function itex_m_admin()
    {
        if ($this->wordpress)
        {
            if (!is_admin()) return 0;
        }

        //$this->lang_ru();
        $this->itex_m_admin_css();
        // Output the options page
        ?>
    <div class="wrap">

        <form method="post">
            <h2><?php echo $this->__('iMoney Options', 'iMoney');?></h2>
            <?php
            if ( '09_May' == date('d_F')) $this->itex_m_admin_9_may();
            if ( '30_December' == date('d_F') || '31_December' == date('d_F') || '01_January' == date('d_F') || '02_January' == date('d_F') || '03_January' == date('d_F')   )  $this->itex_m_admin_new_year();
            ?>

            <?php
            if (strlen($this->error))
            {
                echo '
				<div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">
					'.$this->error.'
				</div>';

            }
            if (isset($_POST['info_update']))
            {
                echo '<div style="margin:10px auto; border:3px  #55ff00 solid; background-color:#afa; padding:10px; text-align:center;">
				<a href="http://itex.name/donation">'.$this->__('Create and maintain a plugin take lot\'s of time. If you enjoy this plugin, do a Donation.', 'iMoney').'</div>';
            }

            ?>




            <!-- Main -->

            <?php
            ?>
            <ul style="text-align: center;font-weight: bold;font-size: 14px;">
                <li style="display: inline;"><a href="#itex_global" onclick='document.getElementById("itex_global").style.display="";'>Global</a></li>
                <li style="display: inline;"><a href="#itex_adsense" onclick='document.getElementById("itex_adsense").style.display="";'>Adsense</a></li>
                <li style="display: inline;"><a href="#itex_begun" onclick='document.getElementById("itex_begun").style.display="";'>Begun</a></li>
                <li style="display: inline;"><a href="#itex_admitad" onclick='document.getElementById("itex_admitad").style.display="";'>Admitad</a></li>
                <li style="display: inline;"><a href="#itex_html" onclick='document.getElementById("itex_html").style.display="";'>Html</a></li>
                <li style="display: inline;"><a href="#itex_ilinks" onclick='document.getElementById("itex_ilinks").style.display="";'>iLinks</a></li>
                <li style="display: inline;"><a href="#itex_php" onclick='document.getElementById("itex_php").style.display="";'>Php</a></li>
                <li style="display: inline;"><a href="#itex_sape" onclick='document.getElementById("itex_sape").style.display="";'>Sape</a></li>
                <li style="display: inline;"><a href="#itex_trustlink" onclick='document.getElementById("itex_trustlink").style.display="";'>Trustlink</a></li>
                <li style="display: inline;"><a href="#itex_tnx" onclick='document.getElementById("itex_tnx").style.display="";'>Tnx/Xap</a></li>
                <li style="display: inline;"><a href="#itex_mainlink" onclick='document.getElementById("itex_mainlink").style.display="";'>MainLink</a></li>
                <li style="display: inline;"><a href="#itex_linkfeed" onclick='document.getElementById("itex_linkfeed").style.display="";'>Linkfeed</a></li>
                <li style="display: inline;"><a href="#itex_adskape" onclick='document.getElementById("itex_adskape").style.display="";'>Adskape</a></li>
                <li style="display: inline;"><a href="#itex_setlinks" onclick='document.getElementById("itex_setlinks").style.display="";'>SetLinks</a></li>
                <li style="display: inline;"><a href="#itex_teasernet" onclick='document.getElementById("itex_teasernet").style.display="";'>Teasernet</a></li>
                <li style="display: inline;"><a href="#itex_zilla" onclick='document.getElementById("itex_zilla").style.display="";'>Serpzilla</a></li>

            </ul>
            <p class="submit">
                <input type='submit' name='info_update' value='<?php echo $this->__('Save Changes', 'iMoney'); ?>' />
            </p>

            <h3><a href="#itex_global" name="itex_global" onclick='document.getElementById("itex_global").style.display="";'>Global</a></h3>
            <div id="itex_global"><?php $this->itex_m_admin_global(); ?></div>

            <h3><a href="#itex_adsense" name="itex_adsense" onclick='document.getElementById("itex_adsense").style.display="";'>Adsense</a></h3>
            <div id="itex_adsense" ><?php $this->itex_m_admin_adsense(); ?></div>

            <h3><a href="#itex_begun" name="itex_begun" onclick='document.getElementById("itex_begun").style.display="";'>Begun</a></h3>
            <div id="itex_begun"><?php $this->itex_m_admin_begun(); ?></div>

            <h3><a href="#itex_begun" name="itex_admitad" onclick='document.getElementById("itex_admitad").style.display="";'>Admitad</a></h3>
            <div id="itex_admitad"><?php $this->itex_m_admin_admitad(); ?></div>

            <h3><a href="#itex_html" name="itex_html" onclick='document.getElementById("itex_html").style.display="";'>Html</a></h3>
            <div id="itex_html"><?php $this->itex_m_admin_html(); ?></div>

            <h3><a href="#itex_ilinks" name="itex_ilinks" onclick='document.getElementById("itex_ilinks").style.display="";'>iLinks</a></h3>
            <div id="itex_ilinks"><?php $this->itex_m_admin_ilinks(); ?></div>

            <h3><a href="#itex_php" name="itex_php" onclick='document.getElementById("itex_php").style.display="";'>Php</a></h3>
            <div id="itex_php"><?php $this->itex_m_admin_php(); ?></div>

            <h3><a href="#itex_sape" name="itex_sape" onclick='document.getElementById("itex_sape").style.display="";'>Sape</a></h3>
            <div id="itex_sape"><?php $this->itex_m_admin_sape(); ?></div>

            <h3><a href="#itex_trustlink" name="itex_trustlink" onclick='document.getElementById("itex_trustlink").style.display="";'>Trustlink</a></h3>
            <div id="itex_trustlink"><?php $this->itex_m_admin_trustlink(); ?></div>

            <h3><a href="#itex_tnx" name="itex_tnx" onclick='document.getElementById("itex_tnx").style.display="";'>Tnx/Xap</a></h3>
            <div id="itex_tnx"><?php $this->itex_m_admin_tnx(); ?></div>

            <h3><a href="#itex_mainlink" name="itex_mainlink" onclick='document.getElementById("itex_mainlink").style.display="";'>MainLink</a></h3>
            <div id="itex_mainlink"><?php $this->itex_m_admin_mainlink(); ?></div>

            <h3><a href="#itex_linkfeed" name="itex_linkfeed" onclick='document.getElementById("itex_linkfeed").style.display="";'>Linkfeed</a></h3>
            <div id="itex_linkfeed"><?php $this->itex_m_admin_linkfeed(); ?></div>

            <h3><a href="#itex_adskape" name="itex_adskape" onclick='document.getElementById("itex_adskape").style.display="";'>Adskape</a></h3>
            <div id="itex_adskape"><?php $this->itex_m_admin_adskape(); ?></div>

            <h3><a href="#itex_setlinks" name="itex_setlinks" onclick='document.getElementById("itex_setlinks").style.display="";'>SetLinks</a></h3>
            <div id="itex_setlinks"><?php $this->itex_m_admin_setlinks(); ?></div>

            <h3><a href="#itex_teasernet" name="itex_teasernet" onclick='document.getElementById("itex_teasernet").style.display="";'>Teasernet</a></h3>
            <div id="itex_teasernet"><?php $this->itex_m_admin_teasernet(); ?></div>

            <h3><a href="#itex_zilla" name="itex_zilla" onclick='document.getElementById("itex_zilla").style.display="";'>Serpzilla</a></h3>
            <div id="itex_zilla"><?php $this->itex_m_admin_zilla(); ?></div>

            <?php
            if(!$this->get_option('itex_m_global_collapse')){ ?>
                <script type="text/javascript">
                    document.getElementById("itex_adsense").style.display="none";
                    document.getElementById("itex_html").style.display="none";
                    document.getElementById("itex_php").style.display="none";
                    document.getElementById("itex_sape").style.display="none";
                    document.getElementById("itex_tnx").style.display="none";
                    document.getElementById("itex_begun").style.display="none";
                    document.getElementById("itex_admitad").style.display="none";
                    document.getElementById("itex_mainlink").style.display="none";
                    document.getElementById("itex_ilinks").style.display="none";
                    document.getElementById("itex_linkfeed").style.display="none";
                    document.getElementById("itex_adskape").style.display="none";
                    document.getElementById("itex_setlinks").style.display="none";
                    document.getElementById("itex_teasernet").style.display="none";
                    document.getElementById("itex_trustlink").style.display="none";
                    document.getElementById("itex_zilla").style.display="none";
                    document.getElementById("itex_global").style.display="none";
                </script>
                <?php } ?>
    </div>

    <p class="submit">
        <input type='submit' name='info_update' value='<?php echo $this->__('Save Changes', 'iMoney'); ?>' />
    </p>

    <ul style="text-align: center;font-weight: bold;font-size: 14px;">
        <li style="display: inline;"><a href="#itex_global" onclick='document.getElementById("itex_global").style.display="";'>Global</a></li>
        <li style="display: inline;"><a href="#itex_adsense" onclick='document.getElementById("itex_adsense").style.display="";'>Adsense</a></li>
        <li style="display: inline;"><a href="#itex_begun" onclick='document.getElementById("itex_begun").style.display="";'>Begun</a></li>
        <li style="display: inline;"><a href="#itex_admitad" onclick='document.getElementById("itex_admitad").style.display="";'>Admitad</a></li>
        <li style="display: inline;"><a href="#itex_html" onclick='document.getElementById("itex_html").style.display="";'>Html</a></li>
        <li style="display: inline;"><a href="#itex_ilinks" onclick='document.getElementById("itex_ilinks").style.display="";'>iLinks</a></li>
        <li style="display: inline;"><a href="#itex_php" onclick='document.getElementById("itex_php").style.display="";'>Php</a></li>
        <li style="display: inline;"><a href="#itex_sape" onclick='document.getElementById("itex_sape").style.display="";'>Sape</a></li>
        <li style="display: inline;"><a href="#itex_trustlink" onclick='document.getElementById("itex_trustlink").style.display="";'>Trustlink</a></li>
        <li style="display: inline;"><a href="#itex_tnx" onclick='document.getElementById("itex_tnx").style.display="";'>Tnx/Xap</a></li>
        <li style="display: inline;"><a href="#itex_mainlink" onclick='document.getElementById("itex_mainlink").style.display="";'>MainLink</a></li>
        <li style="display: inline;"><a href="#itex_linkfeed" onclick='document.getElementById("itex_linkfeed").style.display="";'>Linkfeed</a></li>
        <li style="display: inline;"><a href="#itex_adskape" onclick='document.getElementById("itex_adskape").style.display="";'>Adskape</a></li>
        <li style="display: inline;"><a href="#itex_setlinks" onclick='document.getElementById("itex_setlinks").style.display="";'>SetLinks</a></li>
        <li style="display: inline;"><a href="#itex_teasernet" onclick='document.getElementById("itex_teasernet").style.display="";'>Teasernet</a></li>
        <li style="display: inline;"><a href="#itex_zilla" onclick='document.getElementById("itex_zilla").style.display="";'>Serpzilla</a></li>

    </ul>
    <p align="center">
        <a href="http://itex.name/plugins/faq-po-imoney-i-isape.html">FAQ по iMoney и iSape</a>
    </p>
    <p align="center">
        <?php echo $this->__("Powered by ",'iMoney')."<a href='http://itex.name' title='iTex iMoney'>iTex iMoney</a> ".$this->__("Version:",'iMoney').$this->version; ?>
    </p>
    </form>

    </div>
    <?php
        //phpinfo();
        return true;
    }

    /**
     * Css fo admin menu
     *
     */
    function itex_m_admin_css()
    {
        ?>
    <style type='text/css'>
        #edit_tabs li {
            list-style-type: none;
            float: left;
            margin: 2px 5px 0 0;
            padding-left: 15px;
            text-align: center;
        }

        #edit_tabs li a {
            display: block;
            font-size: 85%;
            font-family: "Lucida Grande", "Verdana";
            font-weight: bold;
            float: left;
            color: #999;
            border-bottom: none;
            padding: 2px 15px 2px 0;
            width: auto !important;
            width: 50px;
            min-width: 50px;
            text-shadow: white 0 1px 0;
        }

        #edit_sections .section {
            background: url('images/bg_tab_section.gif') no-repeat top left;
            padding-left: 10px;
            padding-top: 15px;
            height: auto !important;
            height: 200px;
            min-height: 200px;
            display: none;
        }

        #edit_sections .section ul {
            padding-left: 10px;
            width: 500px;
        }

        #edit_sections .current {
            display: block;
        }

        #edit_sections .section .section_warn {
            background: #FFFFE0;
            border: 1px solid #EBEBA9;
            padding: 8px;
            float: right;
            width: 300px;
            font-size: 11px;
        }
    </style>
    <?php
    }

    /**
     * Admin menu input
     *
     */
    function itex_m_admin_input($name,$description)
    {
        //if (!is_admin()) return 0;
        if (isset($_POST['info_update']))
        {
            if (isset($_POST[$name]))
            {
                $this->update_option($name, $_POST[$name]);
            }
        }
        echo '<input type="text" size="50" ';
        echo 'name="'.$name.'" ';
        echo 'id="'.$name.'" ';
        echo 'value="'.$this->get_option($name).'" />'."\n";
        echo '<p style="margin: 5px 10px;">'.$description.'</p>';

    }

    /**
     * Admin menu input
     *
     */
    function itex_m_admin_select($name,$options,$description)
    {
        //if (!is_admin()) return 0;
        if (isset($_POST['info_update']))
        {
            if (isset($_POST[$name]))
            {
                $this->update_option($name, $_POST[$name]);
            }
        }

        echo '<select name="'.$name.'" id="'.$name.'">'."\n";

        foreach ($options as $k=>$v)
        {
            echo '<option value="'.$k.'"';
            if($this->get_option($name) == $k) echo ' selected="selected"';
            echo ">".$v."</option>\n";
        }
        echo "</select>\n";

        echo '<label for="">'.$description.'.</label>';

        echo "<br/>";
    }

    /**
     * Global section admin menu
     *
     */
    function itex_m_admin_global()
    {
        if (isset($_POST['info_update']))
        {

            if (isset($_POST['global_debugenable']))
            {
                $this->update_option('itex_m_global_debugenable', intval($_POST['global_debugenable']));
            }

            if (isset($_POST['global_debugenable_forall']))
            {
                $this->update_option('itex_m_global_debugenable_forall', intval($_POST['global_debugenable_forall']));
            }

            if (isset($_POST['global_masking']))
            {
                $this->update_option('itex_m_global_masking', intval($_POST['global_masking']));
            }

            if (isset($_POST['global_collapse']))
            {
                $this->update_option('itex_m_global_collapse', !intval($_POST['global_collapse']));
            }

            if ((isset($_POST['global_widget_links'])) || (isset($_POST['global_widget_dynamic'])))
            {
                $s_w = wp_get_sidebars_widgets();
                $ex = 0;
                if (count($s_w['sidebar-1'])) foreach ($s_w['sidebar-1'] as $k => $v)
                {
                    if ($v == 'imoney-links')
                    {
                        $ex = 1;
                        if (!$_POST['global_widget_links']) unset($s_w['sidebar-1'][$k]);
                    }
                }
                if (!$ex && $_POST['global_widget_links']) $s_w['sidebar-1'][] = 'imoney-links';
                $ex = 0;
                if (count($s_w['sidebar-1'])) foreach ($s_w['sidebar-1'] as $k => $v)
                {
                    if ($v == 'imoney-dynamic')
                    {
                        $ex = 1;
                        if (!$_POST['global_widget_dynamic']) unset($s_w['sidebar-1'][$k]);
                    }
                }
                if (!$ex && $_POST['global_widget_dynamic']) $s_w['sidebar-1'][] = 'imoney-dynamic';
                wp_set_sidebars_widgets( $s_w );
            }


            echo "<div class='updated fade'><p><strong>Settings saved.</strong></p></div>";
        }

        ?>
    <table class="form-table" cellspacing="2" cellpadding="5" width="100%">

        <tr>
            <th width="30%" valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('Masking of links', 'iMoney'); ?>:</label>
            </th>
            <td>
                <?php




                $o= array('1' => $this->__('Enabled', 'iMoney'),'0' => $this->__('Disabled', 'iMoney'),);
                $d = $this->__('Masking of links', 'iMoney');
                $this->itex_m_admin_select('itex_m_global_masking', $o, $d);
                /*
						echo "<select name='global_masking' id='global_masking'>\n";
						echo "<option value='1'";

						if($this->get_option('itex_m_global_masking')) echo " selected='selected'";
						echo $this->__(">Enabled</option>\n", 'iSape');

						echo "<option value='0'";
						if(!$this->get_option('itex_m_global_masking')) echo" selected='selected'";
						echo $this->__(">Disabled</option>\n", 'iSape');
						echo "</select>\n";

						echo '<label for="">'.$this->__('Masking of links', 'iMoney').'.</label>';*/

                ?>
            </td>
        </tr>
        <tr>
            <th width="30%" valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('Force_show:', 'iMoney'); ?></label>
            </th>
            <td>
                <?php
                $o= array('1' => $this->__('Enabled', 'iMoney'),'0' => $this->__('Disabled', 'iMoney'),);
                $d = $this->__('Force show code and link. Use it if cache pages', 'iMoney');
                $this->itex_m_admin_select('itex_m_global_force_show', $o, $d);

                ?>
            </td>
        </tr>
        <tr>
            <th width="30%" valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('Global debug:', 'iMoney'); ?></label>
            </th>
            <td>
                <?php
                $o= array('1' => $this->__('Enabled', 'iMoney'),'0' => $this->__('Disabled', 'iMoney'),);
                $d = $this->__('Debug log in footer. For see debug user must register', 'iMoney');
                $this->itex_m_admin_select('itex_m_global_debugenable', $o, $d);

                /*echo "<select name='global_debugenable' id='global_debugenable'>\n";
						echo "<option value='1'";

						if($this->get_option('itex_m_global_debugenable')) echo " selected='selected'";
						echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

						echo "<option value='0'";
						if(!$this->get_option('itex_m_global_debugenable')) echo" selected='selected'";
						echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
						echo "</select>\n";

						echo '<label for="">'.$this->__('Debug log in footer. For see debug user must register', 'iMoney').'.</label>';

						echo "<br/>";*/

                $o= array('1' => $this->__('Enabled', 'iMoney'),'0' => $this->__('Disabled', 'iMoney'),);
                $d = $this->__('Debug log in footer for all, who open the site. Dont leave this parameter switched Enabled for a long time, because in this case it will disclose your private data like SAPE UID', 'iMoney');
                $this->itex_m_admin_select('itex_m_global_debugenable_forall', $o, $d);

                /*	echo "<select name='global_debugenable_forall' id='global_debugenable_forall'>\n";
						echo "<option value='1'";

						if($this->get_option('itex_m_global_debugenable_forall')) echo " selected='selected'";
						echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

						echo "<option value='0'";
						if(!$this->get_option('itex_m_global_debugenable_forall')) echo" selected='selected'";
						echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
						echo "</select>\n";

						echo '<label for="">'.$this->__('Debug log in footer for all, who open the site. Dont leave this parameter switched Enabled for a long time, because in this case it will disclose your private data like SAPE UID', 'iMoney').'.</label>';
						*/
                ?>
            </td>
        </tr>



        <tr>
            <th width="30%" valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('Widgets settings:', 'iMoney'); ?></label>
            </th>
            <td>
                <?php
                if ($this->wordpress)
                {
                    $ws = wp_get_sidebars_widgets();

                    //вывод селектов не через функцию
                    echo "<select name='global_widget_links' id='global_widget_links'>\n";
                    echo "<option value='0'";
                    if (count($ws['sidebar-1'])) if(!in_array('imoney-links',$ws['sidebar-1'])) echo" selected='selected'";
                    echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

                    echo "<option value='1'";
                    if (count($ws['sidebar-1'])) if (in_array('imoney-links',$ws['sidebar-1'])) echo " selected='selected'";
                    echo ">".$this->__('Active','iMoney')."</option>\n";

                    echo "</select>\n";

                    echo '<label for="">'.$this->__('Widget Links Active', 'iMoney').'</label>';

                    echo "<br/>\n";

                    echo "<select name='global_widget_dynamic' id='global_widget_dynamic'>\n";
                    echo "<option value='0'";
                    if (count($ws['sidebar-1'])) if(!in_array('imoney-dynamic',$ws['sidebar-1'])) echo" selected='selected'";
                    echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

                    echo "<option value='1'";
                    if (count($ws['sidebar-1'])) if (in_array('imoney-dynamic',$ws['sidebar-1'])) echo " selected='selected'";
                    echo ">".$this->__('Active','iMoney')."</option>\n";

                    echo "</select>\n";

                    echo '<label for="">'.$this->__('Widget Dynamic Active', 'iMoney').'</label>';
                }
                ?>
            </td>
        </tr>

        <tr>
            <th width="30%" valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('Collapse headlines settings:', 'iMoney'); ?></label>
            </th>
            <td>
                <?php
                $o= array('1' => $this->__('Enabled', 'iMoney'),'0' => $this->__('Disabled', 'iMoney'),);
                $d = '';
                $this->itex_m_admin_select('itex_m_global_collapse', $o, $d);

                /*echo "<select name='global_collapse' id='global_collapse'>\n";
						echo "<option value='1'";

						if(!$this->get_option('itex_m_global_collapse')) echo " selected='selected'";
						echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

						echo "<option value='0'";
						if($this->get_option('itex_m_global_collapse')) echo" selected='selected'";
						echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
						echo "</select>\n";*/


                ?>
            </td>
        </tr>

    </table>
    <?php
    }

    /**
     * 9 may section admin menu
     *
     */
    function itex_m_admin_9_may()
    {
        if ( '09_May' == date('d_F'))
            echo '<center><h1><a href="http://itex.name/plugins/s-dnem-pobedy.html">С Праздником Победы!</a></h1><p><object width="640" height="505"><param name="movie" value="http://www.youtube-nocookie.com/v/TQrINrPzgmw&hl=ru_RU&fs=1&rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube-nocookie.com/v/TQrINrPzgmw&hl=ru_RU&fs=1&rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="640" height="505"></embed></object></p></center>';

    }

    /**
     * New Year section admin menu
     *
     */
    function itex_m_admin_new_year()
    {
        if ( '30_December' == date('d_F') || '31_December' == date('d_F') || '01_January' == date('d_F') || '02_January' == date('d_F') || '03_January' == date('d_F')   )
            echo '<center><h1><a href="http://itex.name/plugins/s-novym-godom.html">С Новым Годом!</a></h1><p><object width="640" height="505"><param name="movie" value="http://www.youtube-nocookie.com/v/dcLMH8pwusw&hl=ru_RU&fs=1&rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube-nocookie.com/v/dcLMH8pwusw&hl=ru_RU&fs=1&rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="640" height="505"></embed></object></p></center>';

    }

    /**
     * Sape section admin menu
     *
     */
    function itex_m_admin_sape()
    {
        if (isset($_POST['info_update']))
        {
            //if (!$this->isape_converted) //для совместимомти с isape, через несколько месяцев надо удалить
            //{
            //	$this->update_option('itex_m_isape_converted', 1);
            //}


            //phpinfo();die();
            if (isset($_POST['sape_sapeuser']))
            {
                $this->update_option('itex_m_sape_sapeuser', trim($_POST['sape_sapeuser']));
            }
            if (isset($_POST['sape_enable']))
            {
                $this->update_option('itex_m_sape_enable', intval($_POST['sape_enable']));
            }

            if (isset($_POST['sape_links_beforecontent']))
            {
                $this->update_option('itex_m_sape_links_beforecontent', $_POST['sape_links_beforecontent']);
            }

            if (isset($_POST['sape_links_aftercontent']))
            {
                $this->update_option('itex_m_sape_links_aftercontent', $_POST['sape_links_aftercontent']);
            }

            if (isset($_POST['sape_links_sidebar']))
            {
                $this->update_option('itex_m_sape_links_sidebar', $_POST['sape_links_sidebar']);
            }

            if (isset($_POST['sape_links_footer']))
            {
                $this->update_option('itex_m_sape_links_footer', $_POST['sape_links_footer']);
            }



            if (isset($_POST['sape_sapecontext_enable']) )
            {
                $this->update_option('itex_m_sape_sapecontext_enable', intval($_POST['sape_sapecontext_enable']));
            }

            if (isset($_POST['sape_sapecontext_pages_enable']) )
            {
                $this->update_option('itex_m_sape_sapecontext_pages_enable', intval($_POST['sape_sapecontext_pages_enable']));
            }

            if (isset($_POST['sape_pages_enable']) )
            {
                $this->update_option('itex_m_sape_pages_enable', intval($_POST['sape_pages_enable']));
            }


            if (isset($_POST['itex_m_sape_sapearticles_enable']) )
            {
                $this->update_option('itex_m_sape_sapearticles_enable', intval($_POST['itex_m_sape_sapearticles_enable']));
            }
            if (isset($_POST['itex_m_sape_sapearticles_template_url']) )
            {
                $this->update_option('itex_m_sape_sapearticles_template_url', $_POST['itex_m_sape_sapearticles_template_url']);
            }
            if (isset($_POST['itex_m_sape_sapearticles_beforecontent']))
            {
                $this->update_option('itex_m_sape_sapearticles_beforecontent', $_POST['itex_m_sape_sapearticles_beforecontent']);
            }

            if (isset($_POST['itex_m_sape_sapearticles_aftercontent']))
            {
                $this->update_option('itex_m_sape_sapearticles_aftercontent', $_POST['itex_m_sape_sapearticles_aftercontent']);
            }

            if (isset($_POST['itex_m_sape_sapearticles_sidebar']))
            {
                $this->update_option('itex_m_sape_sapearticles_sidebar', $_POST['itex_m_sape_sapearticles_sidebar']);
            }

            if (isset($_POST['itex_m_sape_sapearticles_footer']))
            {
                $this->update_option('itex_m_sape_sapearticles_footer', $_POST['itex_m_sape_sapearticles_footer']);
            }
            if (isset($_POST['itex_m_sape_sapearticles_pages_enable']) )
            {
                $this->update_option('itex_m_sape_sapearticles_pages_enable', intval($_POST['itex_m_sape_sapearticles_pages_enable']));
            }
            //			if ((isset($_POST['sape_widget'])) || (isset($_POST['itex_m_sape_visual_widget'])))
            //			{
            //				$s_w = wp_get_sidebars_widgets();
            //				$ex = 0;
            //				if (count($s_w['sidebar-1'])) foreach ($s_w['sidebar-1'] as $k => $v)
            //				{
            //					if ($v == 'imoney-links')
            //					{
            //						$ex = 1;
            //						if (!$_POST['sape_widget']) unset($s_w['sidebar-1'][$k]);
            //					}
            //				}
            //				if (!$ex && $_POST['sape_widget']) $s_w['sidebar-1'][] = 'imoney-links';
            //				wp_set_sidebars_widgets( $s_w );
            //
            //			}
            echo "<div class='updated fade'><p><strong>Settings saved.</strong></p></div>";
        }
        if (isset($_POST['sape_sapedir_create']))
        {
            if ($this->get_option('itex_m_sape_sapeuser'))  $this->itex_m_sape_install_file();
        }
        if ($this->get_option('itex_m_sape_sapeuser'))
        {
            $file = $this->document_root . '/' . _SAPE_USER . '/sape.php'; //<< Not working in multihosting.
            if (file_exists($file)) {}
            else
            {
                ?>
            <div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">
                Sape dir not exist!
            </div>
            <div style="margin:10px auto; border:3px #f00 solid; padding:10px; text-align:center;">
                Create new sapedir and sape.php? (<?php echo $file;?>)
                <p class="submit">
                    <input type='submit' name='sape_sapedir_create' value='<?php echo $this->__('Create', 'iMoney'); ?>' />
                </p>
                <?php
                //if (!$this->get_option('itex_m_sape_sapeuser')) echo $this->__('Enter your SAPE UID in this box!', 'iMoney');
                if (!$this->get_option('itex_m_sape_sapeuser')) echo '<a target="_blank" href="http://www.sape.ru/r.a5a429f57e.php">'.$this->__('Enter your SAPE UID in this box.', 'iMoney').'</a>';

                ?>
            </div>

            <?php
            }
        }
        ?>
    <table class="form-table" cellspacing="2" cellpadding="5" width="100%">
    <tr>
        <th valign="top" style="padding-top: 10px;">
            <label for=""><?php echo $this->__('Your SAPE UID:', 'iMoney');?></label>
        </th>
        <td>
            <?php
            echo "<input type='text' size='50' ";
            echo "name='sape_sapeuser'";
            echo "id='sapeuser' ";
            echo "value='".$this->get_option('itex_m_sape_sapeuser')."' />\n";
            ?>
            <p style="margin: 5px 10px;"><?php
                //echo $this->__('Enter your SAPE UID in this box.', 'iMoney');
                echo '<a target="_blank" href="http://www.sape.ru/r.a5a429f57e.php">'.$this->__('Enter your SAPE UID in this box!', 'iMoney').'</a>';

                ?></p>
        </td>
    </tr>
    <tr>
        <th width="30%" valign="top" style="padding-top: 10px;">
            <label for=""><?php echo $this->__('Sape links:', 'iMoney');?></label>
        </th>
        <td>
            <?php

            $o= array('1' => $this->__('Enabled', 'iMoney'),'0' => $this->__('Disabled', 'iMoney'),);
            $d = $this->__("Working", 'iMoney');
            $this->itex_m_admin_select('itex_m_sape_enable', $o, $d);

            /*echo "<select name='sape_enable' id='sape_enable'>\n";
						echo "<option value='1'";

						if($this->get_option('itex_m_sape_enable')) echo " selected='selected'";
						echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

						echo "<option value='0'";
						if(!$this->get_option('itex_m_sape_enable')) echo" selected='selected'";
						echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
						echo "</select>\n";

						echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
						echo "<br/>\n";*/

            $o = array('0' => $this->__('Disabled', 'iMoney'),'1' => '1','2' => '2','3' => '3','4' => '4','5' => '5',);
            $d = $this->__('Before content links', 'iMoney');
            $this->itex_m_admin_select('itex_m_sape_links_beforecontent', $o, $d);

            /*echo "<select name='sape_links_beforecontent' id='sape_links_beforecontent'>\n";

						echo "<option value='0'";
						if(!$this->get_option('itex_m_sape_links_beforecontent')) echo" selected='selected'";
						echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

						echo "<option value='1'";
						if($this->get_option('itex_m_sape_links_beforecontent') == 1) echo " selected='selected'";
						echo ">1</option>\n";

						echo "<option value='2'";
						if($this->get_option('itex_m_sape_links_beforecontent') == 2) echo " selected='selected'";
						echo ">2</option>\n";

						echo "<option value='3'";
						if($this->get_option('itex_m_sape_links_beforecontent') == 3) echo " selected='selected'";
						echo ">3</option>\n";

						echo "<option value='4'";
						if($this->get_option('itex_m_sape_links_beforecontent') == 4) echo " selected='selected'";
						echo ">4</option>\n";

						echo "<option value='5'";
						if($this->get_option('itex_m_sape_links_beforecontent') == 5) echo " selected='selected'";
						echo ">5</option>\n";

						echo "</select>\n";

						echo '<label for="">'.$this->__('Before content links', 'iMoney').'</label>';

						echo "<br/>\n";
						*/

            $o = array('0' => $this->__('Disabled', 'iMoney'),'1' => '1','2' => '2','3' => '3','4' => '4','5' => '5',);
            $d = $this->__('After content links', 'iMoney');
            $this->itex_m_admin_select('itex_m_sape_links_aftercontent', $o, $d);
            /*

						echo "<select name='sape_links_aftercontent' id='sape_links_aftercontent'>\n";

						echo "<option value='0'";
						if(!$this->get_option('itex_m_sape_links_aftercontent')) echo" selected='selected'";
						echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

						echo "<option value='1'";
						if($this->get_option('itex_m_sape_links_aftercontent') == 1) echo " selected='selected'";
						echo ">1</option>\n";

						echo "<option value='2'";
						if($this->get_option('itex_m_sape_links_aftercontent') == 2) echo " selected='selected'";
						echo ">2</option>\n";

						echo "<option value='3'";
						if($this->get_option('itex_m_sape_links_aftercontent') == 3) echo " selected='selected'";
						echo ">3</option>\n";

						echo "<option value='4'";
						if($this->get_option('itex_m_sape_links_aftercontent') == 4) echo " selected='selected'";
						echo ">4</option>\n";

						echo "<option value='5'";
						if($this->get_option('itex_m_sape_links_aftercontent') == 5) echo " selected='selected'";
						echo ">5</option>\n";

						echo "</select>\n";

						echo '<label for="">'.$this->__('After content links', 'iMoney').'</label>';

						echo "<br/>\n";*/

            $o = array('0' => $this->__('Disabled', 'iMoney'),'1' => '1','2' => '2','3' => '3','4' => '4','5' => '5','max' => $this->__('Max', 'iMoney'),);
            $d = $this->__('Sidebar links', 'iMoney');
            $this->itex_m_admin_select('itex_m_sape_links_sidebar', $o, $d);

            /*echo "<select name='sape_links_sidebar' id='sape_links_sidebar'>\n";

						echo "<option value='0'";
						if(!$this->get_option('itex_m_sape_links_sidebar')) echo" selected='selected'";
						echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

						echo "<option value='1'";
						if($this->get_option('itex_m_sape_links_sidebar') == 1) echo " selected='selected'";
						echo ">1</option>\n";

						echo "<option value='2'";
						if($this->get_option('itex_m_sape_links_sidebar') == 2) echo " selected='selected'";
						echo ">2</option>\n";

						echo "<option value='3'";
						if($this->get_option('itex_m_sape_links_sidebar') == 3) echo " selected='selected'";
						echo ">3</option>\n";

						echo "<option value='4'";
						if($this->get_option('itex_m_sape_links_sidebar') == 4) echo " selected='selected'";
						echo ">4</option>\n";

						echo "<option value='5'";
						if($this->get_option('itex_m_sape_links_sidebar') == 5) echo " selected='selected'";
						echo ">5</option>\n";

						echo "<option value='max'";
						if($this->get_option('itex_m_sape_links_sidebar') == 'max') echo " selected='selected'";
						echo ">".$this->__('Max', 'iMoney')."</option>\n";

						echo "</select>\n";

						echo '<label for="">'.$this->__('Sidebar links', 'iMoney').'</label>';

						echo "<br/>\n";*/

            $o = array('0' => $this->__('Disabled', 'iMoney'),'1' => '1','2' => '2','3' => '3','4' => '4','5' => '5','max' => $this->__('Max', 'iMoney'),);
            $d = $this->__('Footer links', 'iMoney');
            $this->itex_m_admin_select('itex_m_sape_links_footer', $o, $d);

            /*echo "<select name='sape_links_footer' id='sape_links_footer'>\n";
						echo "<option value='0'";
						if(!$this->get_option('itex_m_sape_links_footer')) echo" selected='selected'";
						echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

						echo "<option value='1'";
						if($this->get_option('itex_m_sape_links_footer') == 1) echo " selected='selected'";
						echo ">1</option>\n";

						echo "<option value='2'";
						if($this->get_option('itex_m_sape_links_footer') == 2) echo " selected='selected'";
						echo ">2</option>\n";

						echo "<option value='3'";
						if($this->get_option('itex_m_sape_links_footer') == 3) echo " selected='selected'";
						echo ">3</option>\n";

						echo "<option value='4'";
						if($this->get_option('itex_m_sape_links_footer') == 4) echo " selected='selected'";
						echo ">4</option>\n";

						echo "<option value='5'";
						if($this->get_option('itex_m_sape_links_footer') == 5) echo " selected='selected'";
						echo ">5</option>\n";

						echo "<option value='max'";
						if($this->get_option('itex_m_sape_links_footer') == 'max') echo " selected='selected'";
						echo ">".$this->__('Max', 'iMoney')."</option>\n";

						echo "</select>\n";

						echo '<label for="">'.$this->__('Footer links', 'iMoney').'</label>';

						echo "<br/>\n";*/

            $o= array('1' => $this->__('Enabled', 'iMoney'),'0' => $this->__('Disabled', 'iMoney'),);
            $d = $this->__('Show content links only on Pages and Posts.', 'iMoney');
            $this->itex_m_admin_select('itex_m_sape_pages_enable', $o, $d);

            /*echo "<select name='sape_pages_enable' id='sape_enable'>\n";
						echo "<option value='1'";

						if($this->get_option('itex_m_sape_pages_enable')) echo " selected='selected'";
						echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

						echo "<option value='0'";
						if(!$this->get_option('itex_m_sape_pages_enable')) echo" selected='selected'";
						echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
						echo "</select>\n";

						echo '<label for="">'.$this->__('Show content links only on Pages and Posts.', 'iMoney').'</label>';

						echo "<br/>\n";*/
            ?>
        </td>


    </tr>
        <?php
        ?>
    <tr>
        <th width="30%" valign="top" style="padding-top: 10px;">
            <label for=""><?php echo $this->__('Sape context:', 'iMoney'); ?></label>
        </th>
        <td>
            <?php

            $o= array('1' => $this->__('Enabled', 'iMoney'),'0' => $this->__('Disabled', 'iMoney'),);
            $d = $this->__('Context', 'iMoney');
            $this->itex_m_admin_select('itex_m_sape_sapecontext_enable', $o, $d);

            /*
						echo "<select name='sape_sapecontext_enable' id='sape_enable'>\n";
						echo "<option value='1'";

						if($this->get_option('itex_m_sape_sapecontext_enable')) echo " selected='selected'";
						echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

						echo "<option value='0'";
						if(!$this->get_option('itex_m_sape_sapecontext_enable')) echo" selected='selected'";
						echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
						echo "</select>\n";

						echo '<label for="">'.$this->__('Context', 'iMoney').'</label>';

						echo "<br/>\n";
						*/

            $o= array('1' => $this->__('Enabled', 'iMoney'),'0' => $this->__('Disabled', 'iMoney'),);
            $d = $this->__('Show context only on Pages and Posts.', 'iMoney');
            $this->itex_m_admin_select('itex_m_sape_sapecontext_pages_enable', $o, $d);

            /*
						echo "<select name='sape_sapecontext_pages_enable' id='sape_enable'>\n";
						echo "<option value='1'";

						if($this->get_option('itex_m_sape_sapecontext_pages_enable')) echo " selected='selected'";
						echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

						echo "<option value='0'";
						if(!$this->get_option('itex_m_sape_sapecontext_pages_enable')) echo" selected='selected'";
						echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
						echo "</select>\n";

						echo '<label for="">'.$this->__('Show context only on Pages and Posts.', 'iMoney').'</label>';

						echo "<br/>\n";
						*/

            ?>
        </td>
    </tr>

    <tr>
    <th width="30%" valign="top" style="padding-top: 10px;">
        <label for=""><a href="http://articles.sape.ru/r.a5a429f57e.php"><?php echo $this->__('Sape articles:', 'iMoney'); ?></a></label>
    </th>
    <td>
        <?php
        $o= array('1' => $this->__('Enabled', 'iMoney'),'0' => $this->__('Disabled', 'iMoney'),);
        $d = $this->__('<a href="http://articles.sape.ru/r.a5a429f57e.php">'.$this->__('Articles', 'iMoney').'</a>', 'iMoney');
        $this->itex_m_admin_select('itex_m_sape_sapearticles_enable', $o, $d);

        /*
						echo "<select name='itex_m_sape_sapearticles_enable' id='sape_enable'>\n";
						echo "<option value='1'";

						if($this->get_option('itex_m_sape_sapearticles_enable')) echo " selected='selected'";
						echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

						echo "<option value='0'";
						if(!$this->get_option('itex_m_sape_sapearticles_enable')) echo" selected='selected'";
						echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
						echo "</select>\n";

						echo '<label for=""><a href="http://articles.sape.ru/r.a5a429f57e.php">'.$this->__('Articles', 'iMoney').'</a></label>';

						echo "<br/>\n";
						*/

        echo "<input type='text' size='100' ";
        echo "name='itex_m_sape_sapearticles_template_url'";
        echo "value='".$this->get_option('itex_m_sape_sapearticles_template_url')."' />\n";
        echo '<label for="">'.$this->__('Sapearticles moderation url', 'iMoney').'</label>';
        echo "<br/>\n";


        $o = array('0' => $this->__('Disabled', 'iMoney'),'1' => '1','2' => '2','3' => '3','4' => '4','5' => '5');
        $d = $this->__('Before content links', 'iMoney');
        $this->itex_m_admin_select('itex_m_sape_sapearticles_beforecontent', $o, $d);

        /*
						echo "<select name='itex_m_sape_sapearticles_beforecontent' id='itex_m_sape_sapearticles_beforecontent'>\n";

						echo "<option value='0'";
						if(!$this->get_option('itex_m_sape_sapearticles_beforecontent')) echo" selected='selected'";
						echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

						echo "<option value='1'";
						if($this->get_option('itex_m_sape_sapearticles_beforecontent') == 1) echo " selected='selected'";
						echo ">1</option>\n";

						echo "<option value='2'";
						if($this->get_option('itex_m_sape_sapearticles_beforecontent') == 2) echo " selected='selected'";
						echo ">2</option>\n";

						echo "<option value='3'";
						if($this->get_option('itex_m_sape_sapearticles_beforecontent') == 3) echo " selected='selected'";
						echo ">3</option>\n";

						echo "<option value='4'";
						if($this->get_option('itex_m_sape_sapearticles_beforecontent') == 4) echo " selected='selected'";
						echo ">4</option>\n";

						echo "<option value='5'";
						if($this->get_option('itex_m_sape_sapearticles_beforecontent') == 5) echo " selected='selected'";
						echo ">5</option>\n";

						echo "</select>\n";

						echo '<label for="">'.$this->__('Before content links', 'iMoney').'</label>';

						echo "<br/>\n";
						*/

        $o = array('0' => $this->__('Disabled', 'iMoney'),'1' => '1','2' => '2','3' => '3','4' => '4','5' => '5');
        $d = $this->__('After content links', 'iMoney');
        $this->itex_m_admin_select('itex_m_sape_sapearticles_aftercontent', $o, $d);


        /*
						echo "<select name='itex_m_sape_sapearticles_aftercontent' id='itex_m_sape_sapearticles_aftercontent'>\n";

						echo "<option value='0'";
						if(!$this->get_option('itex_m_sape_sapearticles_aftercontent')) echo" selected='selected'";
						echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

						echo "<option value='1'";
						if($this->get_option('itex_m_sape_sapearticles_aftercontent') == 1) echo " selected='selected'";
						echo ">1</option>\n";

						echo "<option value='2'";
						if($this->get_option('itex_m_sape_sapearticles_aftercontent') == 2) echo " selected='selected'";
						echo ">2</option>\n";

						echo "<option value='3'";
						if($this->get_option('itex_m_sape_sapearticles_aftercontent') == 3) echo " selected='selected'";
						echo ">3</option>\n";

						echo "<option value='4'";
						if($this->get_option('itex_m_sape_sapearticles_aftercontent') == 4) echo " selected='selected'";
						echo ">4</option>\n";

						echo "<option value='5'";
						if($this->get_option('itex_m_sape_sapearticles_aftercontent') == 5) echo " selected='selected'";
						echo ">5</option>\n";

						echo "</select>\n";

						echo '<label for="">'.$this->__('After content links', 'iMoney').'</label>';

						echo "<br/>\n";
						*/

        $o = array('0' => $this->__('Disabled', 'iMoney'),'1' => '1','2' => '2','3' => '3','4' => '4','5' => '5','max' => $this->__('Max', 'iMoney'),);
        $d = $this->__('Sidebar links', 'iMoney');
        $this->itex_m_admin_select('itex_m_sape_sapearticles_sidebar', $o, $d);

        /*

						echo "<select name='itex_m_sape_sapearticles_sidebar' id='itex_m_sape_sapearticles_sidebar'>\n";

						echo "<option value='0'";
						if(!$this->get_option('itex_m_sape_sapearticles_sidebar')) echo" selected='selected'";
						echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

						echo "<option value='1'";
						if($this->get_option('itex_m_sape_sapearticles_sidebar') == 1) echo " selected='selected'";
						echo ">1</option>\n";

						echo "<option value='2'";
						if($this->get_option('itex_m_sape_sapearticles_sidebar') == 2) echo " selected='selected'";
						echo ">2</option>\n";

						echo "<option value='3'";
						if($this->get_option('itex_m_sape_sapearticles_sidebar') == 3) echo " selected='selected'";
						echo ">3</option>\n";

						echo "<option value='4'";
						if($this->get_option('itex_m_sape_sapearticles_sidebar') == 4) echo " selected='selected'";
						echo ">4</option>\n";

						echo "<option value='5'";
						if($this->get_option('itex_m_sape_sapearticles_sidebar') == 5) echo " selected='selected'";
						echo ">5</option>\n";

						echo "<option value='max'";
						if($this->get_option('itex_m_sape_sapearticles_sidebar') == 'max') echo " selected='selected'";
						echo ">".$this->__('Max', 'iMoney')."</option>\n";

						echo "</select>\n";

						echo '<label for="">'.$this->__('Sidebar links', 'iMoney').'</label>';

						echo "<br/>\n";
						*/

        $o = array('0' => $this->__('Disabled', 'iMoney'),'1' => '1','2' => '2','3' => '3','4' => '4','5' => '5','max' => $this->__('Max', 'iMoney'),);
        $d = $this->__('Footer links', 'iMoney');
        $this->itex_m_admin_select('itex_m_sape_sapearticles_footer', $o, $d);
        /*


						echo "<select name='itex_m_sape_sapearticles_footer' id='itex_m_sape_sapearticles_footer'>\n";
						echo "<option value='0'";
						if(!$this->get_option('itex_m_sape_sapearticles_footer')) echo" selected='selected'";
						echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

						echo "<option value='1'";
						if($this->get_option('itex_m_sape_sapearticles_footer') == 1) echo " selected='selected'";
						echo ">1</option>\n";

						echo "<option value='2'";
						if($this->get_option('itex_m_sape_sapearticles_footer') == 2) echo " selected='selected'";
						echo ">2</option>\n";

						echo "<option value='3'";
						if($this->get_option('itex_m_sape_sapearticles_footer') == 3) echo " selected='selected'";
						echo ">3</option>\n";

						echo "<option value='4'";
						if($this->get_option('itex_m_sape_sapearticles_footer') == 4) echo " selected='selected'";
						echo ">4</option>\n";

						echo "<option value='5'";
						if($this->get_option('itex_m_sape_sapearticles_footer') == 5) echo " selected='selected'";
						echo ">5</option>\n";

						echo "<option value='max'";
						if($this->get_option('itex_m_sape_sapearticles_footer') == 'max') echo " selected='selected'";
						echo ">".$this->__('Max', 'iMoney')."</option>\n";

						echo "</select>\n";

						echo '<label for="">'.$this->__('Footer links', 'iMoney').'</label>';

						echo "<br/>\n";
						*/

        $o= array('1' => $this->__('Enabled', 'iMoney'),'0' => $this->__('Disabled', 'iMoney'),);
        $d = $this->__('Show content links only on Pages and Posts.', 'iMoney');
        $this->itex_m_admin_select('itex_m_sape_sapearticles_pages_enable', $o, $d);

        /*
						echo "<select name='itex_m_sape_sapearticles_pages_enable' id='sape_enable'>\n";
						echo "<option value='1'";

						if($this->get_option('itex_m_sape_sapearticles_pages_enable')) echo " selected='selected'";
						echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

						echo "<option value='0'";
						if(!$this->get_option('itex_m_sape_sapearticles_pages_enable')) echo" selected='selected'";
						echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
						echo "</select>\n";

						echo '<label for="">'.$this->__('Show content links only on Pages and Posts.', 'iMoney').'</label>';

						echo "<br/>\n";
						*/

        //если есть сапеуид, то выводим примерный урл
        if ($this->get_option('itex_m_sape_sapeuser'))
        {
            echo '<label for="">'.$this->__('Sapearticles url template  ', 'iMoney').'</label>';
            echo "<br/>\n";
            echo '<label for=""><a href="/itex_sape_articles_template.'.$this->get_option('itex_m_sape_sapeuser').'.html">/itex_sape_articles_template.'.$this->get_option('itex_m_sape_sapeuser').'.html</a></label>';
            echo "<br/>\n";
        }

        ?>
    </td>
    </tr>


    <tr>
        <th width="30%" valign="top" style="padding-top: 10px;">
            <label for=""></label>
        </th>
        <td align="center">
            <br/><br/>
            <a target="_blank" href="http://www.sape.ru/r.a5a429f57e.php">www.sape.ru</a>
            <br/>
            <a target="_blank" href="http://www.sape.ru/r.a5a429f57e.php"><img src="http://img.sape.ru/bn/sape_001.gif" alt="www.sape.ru!" border="0" /></a>
        </td>
    </tr>
    </table>
    <?php
    }

    /**
     * Sape file installation
     *
     * @return  bool
     */
    function itex_m_sape_install_file()
    {

        $sape_php_content = $this->itex_m_datafiles('sape.php');

        $file = $this->document_root . '/' . _SAPE_USER . '/sape.php';

        $dir = dirname($file);
        if (!@mkdir($dir, 0777))
        {
            echo '

		<div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">
				'.$this->__('Can`t create Sape dir!', 'iMoney').'
		</div>';
            return 0;
        }
        chmod($dir, 0777);  //byli gluki s mkdir($dir, 0777)
        if (!file_put_contents($file,$sape_php_content))
        {
            echo '
		<div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">
				'.$this->__('Can`t create sape.php!', 'iMoney').'
		</div>';
            return 0;
        }
        //chmod($file, 0777);
        file_put_contents($dir.'/.htaccess',"deny from all\r\n");
        echo '
		<div style="margin:10px auto; border:3px  #55ff00 solid; background-color:#afa; padding:10px; text-align:center;">
				'.$this->__('Sapedir and sape.php created!', 'iMoney').'
		</div>';
        //die();
        return 1;
    }

    /**
     * Serpzilla section admin menu
     *
     */
    function itex_m_admin_zilla()
    {
        if (isset($_POST['info_update']))
        {
            if (isset($_POST['itex_m_zilla_user']))
            {
                $this->update_option('itex_m_zilla_user', trim($_POST['itex_m_zilla_user']));
            }
            echo "<div class='updated fade'><p><strong>Settings saved.</strong></p></div>";
        }

        ////////////////////////////////////////////////////////////////////////////////////////

        if (isset($_POST['itex_m_zilla_dir_create']))
        {
            if ($this->get_option('itex_m_zilla_user'))  $this->itex_m_zilla_install_file();
        }

        if ($this->get_option('itex_m_zilla_user'))
        {
            $file = $this->document_root . '/' . _ZILLA_USER . '/zilla.php'; //<< Not working in multihosting.
            if (file_exists($file)) {}
            else
            {
                ?>
            <div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">
                Serpzilla <?php echo $this->__('dir not exist!', 'iMoney'); ?>
            </div>
            <div style="margin:10px auto; border:3px #f00 solid; padding:10px; text-align:center;">
                <?php echo $this->__('Create new dir and', 'iMoney'); ?> zilla.php? (<?php echo $file;?>)
                <p class="submit">
                    <input type='submit' name='itex_m_zilla_dir_create' value='<?php echo $this->__('Create', 'iMoney'); ?>' />
                </p>
                <?php
                if (!$this->get_option('itex_m_zilla_user')) echo '<a target="_blank" href="http://beta.serpzilla.com/r/mbaJymKyWl/">'.$this->__('Enter your Serpzilla UID in this box.', 'iMoney').'</a>';

                ?>
            </div>

            <?php
            }
        }
        ?>
    <table class="form-table" cellspacing="2" cellpadding="5" width="100%">
        <tr>
            <th valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('Your Serpzilla UID:', 'iMoney');?></label>
            </th>
            <td>
                <?php
                echo "<input type='text' size='50' ";
                echo "name='itex_m_zilla_user'";
                echo "id='itex_m_zilla_user' ";
                echo "value='".$this->get_option('itex_m_zilla_user')."' />\n";
                ?>
                <p style="margin: 5px 10px;"><?php

                    echo '<a target="_blank" href="http://beta.serpzilla.com/r/mbaJymKyWl/">'.$this->__('Enter your Serpzilla UID in this box!', 'iMoney').'</a>';

                    ?></p>
            </td>
        </tr>
        <tr>
            <th width="30%" valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('Serpzilla links:', 'iMoney');?></label>
            </th>
            <td>
                <?php

                $o= array('1' => $this->__('Enabled', 'iMoney'),'0' => $this->__('Disabled', 'iMoney'),);
                $d = $this->__("Working", 'iMoney');
                $this->itex_m_admin_select('itex_m_zilla_enable', $o, $d);

                $o = array('0' => $this->__('Disabled', 'iMoney'),'1' => '1','2' => '2','3' => '3','4' => '4','5' => '5',);
                $d = $this->__('Before content links', 'iMoney');
                $this->itex_m_admin_select('itex_m_zilla_links_beforecontent', $o, $d);



                $o = array('0' => $this->__('Disabled', 'iMoney'),'1' => '1','2' => '2','3' => '3','4' => '4','5' => '5',);
                $d = $this->__('After content links', 'iMoney');
                $this->itex_m_admin_select('itex_m_zilla_links_aftercontent', $o, $d);


                $o = array('0' => $this->__('Disabled', 'iMoney'),'1' => '1','2' => '2','3' => '3','4' => '4','5' => '5','max' => $this->__('Max', 'iMoney'),);
                $d = $this->__('Sidebar links', 'iMoney');
                $this->itex_m_admin_select('itex_m_zilla_links_sidebar', $o, $d);


                $o = array('0' => $this->__('Disabled', 'iMoney'),'1' => '1','2' => '2','3' => '3','4' => '4','5' => '5','max' => $this->__('Max', 'iMoney'),);
                $d = $this->__('Footer links', 'iMoney');
                $this->itex_m_admin_select('itex_m_zilla_links_footer', $o, $d);


                $o= array('1' => $this->__('Enabled', 'iMoney'),'0' => $this->__('Disabled', 'iMoney'),);
                $d = $this->__('Show content links only on Pages and Posts.', 'iMoney');
                $this->itex_m_admin_select('itex_m_zilla_pages_enable', $o, $d);

                ?>
            </td>


        </tr>




        <tr>
            <th width="30%" valign="top" style="padding-top: 10px;">
                <label for=""></label>
            </th>
            <td align="center">
                <br/><br/>
                <a target="_blank" href="http://beta.serpzilla.com/r/mbaJymKyWl/">www.serpzilla.com</a>
                <br/>
                <a target="_blank" href="http://beta.serpzilla.com/r/mbaJymKyWl/"><img src="http://img.sape.ru/bn/sape_001.gif" alt="www.sape.ru!" border="0" /></a>
            </td>
        </tr>
    </table>
    <?php
    }

    /**
     * Serpzilla file installation
     *
     * @return  bool
     */
    function itex_m_zilla_install_file()
    {

        $sape_php_content = $this->itex_m_datafiles('zilla.php');

        $file = $this->document_root . '/' . _ZILLA_USER . '/zilla.php';

        $dir = dirname($file);
        if (!@mkdir($dir, 0777))
        {
            echo '

		<div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">
				'.$this->__('Can`t create Serpzilla dir!', 'iMoney').'
		</div>';
            return 0;
        }
        chmod($dir, 0777);  //byli gluki s mkdir($dir, 0777)
        if (!file_put_contents($file,$sape_php_content))
        {
            echo '
		<div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">
				'.$this->__('Can`t create', 'iMoney').' zilla.php!
		</div>';
            return 0;
        }
        //chmod($file, 0777);
        file_put_contents($dir.'/.htaccess',"deny from all\r\n");
        echo '
		<div style="margin:10px auto; border:3px  #55ff00 solid; background-color:#afa; padding:10px; text-align:center;">
				'.$this->__('Serpzilla dir and zilla.php created!', 'iMoney').'
		</div>';
        //die();
        return 1;
    }

    /**
     * Trustlink section admin menu
     *
     */
    function itex_m_admin_trustlink()
    {
        if (isset($_POST['info_update']))
        {
            //phpinfo();die();
            if (isset($_POST['itex_m_trustlink_user']))
            {
                $this->update_option('itex_m_trustlink_user', trim($_POST['itex_m_trustlink_user']));
            }
            if (isset($_POST['itex_m_trustlink_enable']))
            {
                $this->update_option('itex_m_trustlink_enable', intval($_POST['itex_m_trustlink_enable']));
            }

            if (isset($_POST['itex_m_trustlink_links_beforecontent']))
            {
                $this->update_option('itex_m_trustlink_links_beforecontent', $_POST['itex_m_trustlink_links_beforecontent']);
            }

            if (isset($_POST['itex_m_trustlink_links_aftercontent']))
            {
                $this->update_option('itex_m_trustlink_links_aftercontent', $_POST['itex_m_trustlink_links_aftercontent']);
            }

            if (isset($_POST['itex_m_trustlink_links_sidebar']))
            {
                $this->update_option('itex_m_trustlink_links_sidebar', $_POST['itex_m_trustlink_links_sidebar']);
            }

            if (isset($_POST['itex_m_trustlink_links_footer']))
            {
                $this->update_option('itex_m_trustlink_links_footer', $_POST['itex_m_trustlink_links_footer']);
            }
            if (isset($_POST['itex_m_trustlink_pages_enable']) )
            {
                $this->update_option('itex_m_trustlink_pages_enable', intval($_POST['itex_m_trustlink_pages_enable']));
            }


            echo "<div class='updated fade'><p><strong>Settings saved.</strong></p></div>";
        }
        if (isset($_POST['itex_m_trustlink_dir_create']))
        {
            if ($this->get_option('itex_m_trustlink_user'))  $this->itex_m_trustlink_install_file();
        }
        if ($this->get_option('itex_m_trustlink_user'))
        {
            $file = $this->document_root . '/' . $this->get_option('itex_m_trustlink_user') . '/trustlink.php'; //<< Not working in multihosting.
            if (file_exists($file))
            {
                ?>
            <div style="margin:10px auto; padding:10px; text-align:center;">
                <?php echo $this->__('Update from plagin', 'iMoney');?> trustlink.php? (<?php echo $file;?>)
                <p class="submit">
                    <input type='submit' name='itex_m_trustlink_dir_create' value='<?php echo $this->__('Update', 'iMoney'); ?>' />
                </p>
            </div>
            <?php
            }
            else
            {
                ?>
            <div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">
                Trustlink <?php echo $this->__('dir not exist!', 'iMoney');?>
            </div>
            <div style="margin:10px auto; border:3px #f00 solid; padding:10px; text-align:center;">
                <?php echo $this->__('Create new dir and', 'iMoney');?> trustlink.php? (<?php echo $file;?>)
                <p class="submit">
                    <input type='submit' name='itex_m_trustlink_dir_create' value='<?php echo $this->__('Create', 'iMoney'); ?>' />
                </p>
                <?php
                //if (!$this->get_option('itex_m_trustlink_sapeuser')) echo $this->__('Enter your Trustlink UID in this box!', 'iMoney');
                if (!$this->get_option('itex_m_trustlink_sapeuser')) echo '<a target="_blank" href="http://trustlink.ru/registration/106535">'.$this->__('Enter your Trustlink UID in this box!', 'iMoney').'</a>';


                ?>
            </div>

            <?php
            }
        }
        ?>
    <table class="form-table" cellspacing="2" cellpadding="5" width="100%">
        <tr>
            <th valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('Your Trustlink UID:', 'iMoney');?></label>
            </th>
            <td>
                <?php
                echo "<input type='text' size='50' ";
                echo "name='itex_m_trustlink_user'";
                echo "id='user' ";
                echo "value='".$this->get_option('itex_m_trustlink_user')."' />\n";
                ?>
                <p style="margin: 5px 10px;"><?php
                    //echo $this->__('Enter your Trustlink UID in this box.', 'iMoney');
                    echo '<a target="_blank" href="http://trustlink.ru/registration/106535">'.$this->__('Enter your Trustlink UID in this box.', 'iMoney').'</a>';

                    ?></p>
            </td>
        </tr>
        <tr>
            <th width="30%" valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('Trustlink links:', 'iMoney');?></label>
            </th>
            <td>
                <?php
                echo "<select name='itex_m_trustlink_enable' id='itex_m_trustlink_enable'>\n";
                echo "<option value='1'";

                if($this->get_option('itex_m_trustlink_enable')) echo " selected='selected'";
                echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

                echo "<option value='0'";
                if(!$this->get_option('itex_m_trustlink_enable')) echo" selected='selected'";
                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                echo "</select>\n";

                echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
                echo "<br/>\n";

                echo "<select name='itex_m_trustlink_links_beforecontent' id='itex_m_trustlink_links_beforecontent'>\n";

                echo "<option value='0'";
                if(!$this->get_option('itex_m_trustlink_links_beforecontent')) echo" selected='selected'";
                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

                echo "<option value='max'";
                if($this->get_option('itex_m_trustlink_links_beforecontent') == 'max') echo " selected='selected'";
                echo ">".$this->__('Max', 'iMoney')."</option>\n";

                echo "</select>\n";

                echo '<label for="">'.$this->__('Before content links', 'iMoney').'</label>';

                echo "<br/>\n";



                echo "<select name='itex_m_trustlink_links_aftercontent' id='itex_m_trustlink_links_aftercontent'>\n";

                echo "<option value='0'";
                if(!$this->get_option('itex_m_trustlink_links_aftercontent')) echo" selected='selected'";
                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

                echo "<option value='max'";
                if($this->get_option('itex_m_trustlink_links_aftercontent') == 'max') echo " selected='selected'";
                echo ">".$this->__('Max', 'iMoney')."</option>\n";

                echo "</select>\n";

                echo '<label for="">'.$this->__('After content links', 'iMoney').'</label>';

                echo "<br/>\n";

                echo "<select name='itex_m_trustlink_links_sidebar' id='itex_m_trustlink_links_sidebar'>\n";

                echo "<option value='0'";
                if(!$this->get_option('itex_m_trustlink_links_sidebar')) echo" selected='selected'";
                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

                echo "<option value='max'";
                if($this->get_option('itex_m_trustlink_links_sidebar') == 'max') echo " selected='selected'";
                echo ">".$this->__('Max', 'iMoney')."</option>\n";

                echo "</select>\n";

                echo '<label for="">'.$this->__('Sidebar links', 'iMoney').'</label>';

                echo "<br/>\n";


                echo "<select name='itex_m_trustlink_links_footer' id='itex_m_trustlink_links_footer'>\n";
                echo "<option value='0'";
                if(!$this->get_option('itex_m_trustlink_links_footer')) echo" selected='selected'";
                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

                echo "<option value='max'";
                if($this->get_option('itex_m_trustlink_links_footer') == 'max') echo " selected='selected'";
                echo ">".$this->__('Max', 'iMoney')."</option>\n";

                echo "</select>\n";

                echo '<label for="">'.$this->__('Footer links', 'iMoney').'</label>';

                echo "<br/>\n";
                echo "<select name='itex_m_trustlink_pages_enable' id='trustlink_enable'>\n";
                echo "<option value='1'";

                if($this->get_option('itex_m_trustlink_pages_enable')) echo " selected='selected'";
                echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

                echo "<option value='0'";
                if(!$this->get_option('itex_m_trustlink_pages_enable')) echo" selected='selected'";
                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                echo "</select>\n";

                echo '<label for="">'.$this->__('Show content links only on Pages and Posts.', 'iMoney').'</label>';

                echo "<br/>\n";
                ?>
            </td>


        </tr>
        <tr>
            <th width="30%" valign="top" style="padding-top: 10px;">
                <label for=""></label>
            </th>
            <td align="center">
                <br/><br/>
                <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="600" height="90"><param name="movie" value="http://trustlink.ru/banners/secretar_600x90.swf"/><param name="bgcolor" value="#FFFFFF"/><param name="quality" value="high"><param name="allowScriptAccess" value="Always"><param name="FlashVars" value="refLink=http://trustlink.ru/registration/106535"><embed type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" width="600" height="90" src="http://trustlink.ru/banners/secretar_600x90.swf" bgcolor="#FFFFFF" quality="high" allowScriptAccess="Always" flashvars="refLink=http://trustlink.ru/registration/106535" /></object>
                <br/>
                <a target="_blank" href="http://trustlink.ru/registration/106535">www.trustlink.ru</a>
            </td>
        </tr>
    </table>
    <?php
        //<a target="_blank" href="http://trustlink.ru/registration/106535"><img src="http://trustlink.ru/banners/secretar_600x90.swf" alt="www.trustlink.ru!" border="0" /></a>

    }

    /**
     * Trustlink file installation
     *
     * @return  bool
     */
    function itex_m_trustlink_install_file()
    {
        //http://www.trustlink.ru/user/get_php_code?orientation=0&block_color=ffffff&border_color=e0e0e0e&anchor_color=0000cc&text_color=000000&url_color=006600


        $file_php_content = $this->itex_m_datafiles('trustlink.php');

        $file = $this->document_root . '/' . $this->get_option('itex_m_trustlink_user') . '/trustlink.php'; //<< Not working in multihosting.

        $dir = dirname($file);
        if (!is_dir($dir) && !@mkdir($dir, 0777))
        {
            echo '

		<div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">
				'.$this->__('Can`t create Trustlink dir!', 'iMoney').'
		</div>';
            return 0;
        }
        chmod($dir, 0777);  //byli gluki s mkdir($dir, 0777)
        if (!file_put_contents($file,$file_php_content))
        {
            echo '
		<div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">
				'.$this->__('Can`t create', 'iMoney').'trustlink.php!
		</div>';
            return 0;
        }



        $file_php_content = $this->itex_m_datafiles('template.tpl.html');

        $file = $dir.DIRECTORY_SEPARATOR.'template.tpl.html';
        if (!file_put_contents($file,$file_php_content))
        {
            echo '
		<div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">
				'.$this->__('Can`t create', 'iMoney').'template.tpl.html !
		</div>';
            return 0;
        }


        //chmod($file, 0777);
        file_put_contents($dir.'/.htaccess',"deny from all\r\n");
        echo '
		<div style="margin:10px auto; border:3px  #55ff00 solid; background-color:#afa; padding:10px; text-align:center;">
				'.$this->__('Trustlink dir and trustlink.php created!', 'iMoney').'
		</div>';
        //die();
        return 1;
    }

    /**
     * Adsens section admin menu
     *
     */
    function itex_m_admin_adsense()
    {
        $maxblock = 4; //max  adsense blocks - 1
        if (isset($_POST['info_update']))
        {
            //phpinfo();die();
            if (isset($_POST['adsense_id']))
            {
                $this->update_option('itex_m_adsense_id', trim($_POST['adsense_id']));
            }
            if (isset($_POST['adsense_enable']))
            {
                $this->update_option('itex_m_adsense_enable', intval($_POST['adsense_enable']));
            }
            for ($block=1;$block<$maxblock;$block++)
            {
                if (isset($_POST['adsense_b'.$block.'_enable']))
                {
                    $this->update_option('itex_m_adsense_b'.$block.'_enable', trim($_POST['adsense_b'.$block.'_enable']));
                }

                if (isset($_POST['adsense_b'.$block.'_size']) && !empty($_POST['adsense_b'.$block.'_size']))
                {
                    $this->update_option('itex_m_adsense_b'.$block.'_size', trim($_POST['adsense_b'.$block.'_size']));
                }

                if (isset($_POST['adsense_b'.$block.'_pos']) && !empty($_POST['adsense_b'.$block.'_pos']))
                {
                    $this->update_option('itex_m_adsense_b'.$block.'_pos', trim($_POST['adsense_b'.$block.'_pos']));
                    //					$s_w = wp_get_sidebars_widgets();
                    //					$ex = 0;
                    //					if (count($s_w['sidebar-1'])) foreach ($s_w['sidebar-1'] as $k => $v)
                    //					{
                    //						if ($v == 'imoney_adsense_'.$block)
                    //						{
                    //							$ex = 1;
                    //							if ($_POST['adsense_b'.$block.'_pos'] != 'sidebar') unset($s_w['sidebar-1'][$k]);
                    //						}
                    //					}
                    //					if (!$ex && ($_POST['adsense_b'.$block.'_pos'] == 'sidebar')) $s_w['sidebar-1'][] = 'imoney_adsense_'.$block;
                    //					wp_set_sidebars_widgets($s_w);
                }
                if (isset($_POST['adsense_b'.$block.'_adslot']) && !empty($_POST['adsense_b'.$block.'_adslot']))
                {
                    $this->update_option('itex_m_adsense_b'.$block.'_adslot', trim($_POST['adsense_b'.$block.'_adslot']));
                }

            }

            echo "<div class='updated fade'><p><strong>Settings saved.</strong></p></div>";
        }
        ?>
    <table class="form-table" cellspacing="2" cellpadding="5" width="100%">
        <tr>
            <th valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('Your Adsense ID:', 'iMoney');?></label>
            </th>
            <td>
                <?php
                echo "<input type='text' size='50' ";
                echo "name='adsense_id'";
                echo "id='adsense_id' ";
                echo "value='".$this->get_option('itex_m_adsense_id')."' />\n";
                ?>
                <p style="margin: 5px 10px;"><?php echo $this->__('Enter your Adsence ID in this box.', 'iMoney');?></p>

                <?php
                echo "<select name='adsense_enable' id='adsense_enable'>\n";
                echo "<option value='1'";

                if($this->get_option('itex_m_adsense_enable')) echo " selected='selected'";
                echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

                echo "<option value='0'";
                if(!$this->get_option('itex_m_adsense_enable')) echo" selected='selected'";
                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                echo "</select>\n";

                echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
                echo "<br/>\n";
                ?>
            </td>
        </tr>
        <?php
        for ($block=1;$block<$maxblock;$block++)
        {
            ?>

            <tr>
                <th width="30%" valign="top" style="padding-top: 10px;">
                    <label for=""><?php echo $this->__('Adsense Block ', 'iMoney').$block.': ';?></label>
                </th>
                <td>
                    <?php
                    echo "<select name='adsense_b".$block."_enable' id='adsense_b".$block."_enable'>\n";
                    echo "<option value='1'";

                    if($this->get_option('itex_m_adsense_b'.$block.'_enable')) echo " selected='selected'";
                    echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

                    echo "<option value='0'";
                    if(!$this->get_option('itex_m_adsense_b'.$block.'_enable')) echo" selected='selected'";
                    echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                    echo "</select>\n";

                    echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
                    echo "<br/>\n";


                    echo "<select name='adsense_b".$block."_size' id='adsense_b".$block."_size'>\n";
                    echo "<option value='0'";
                    if(!$this->get_option('itex_m_adsense_b'.$block.'_size')) echo" selected='selected'";
                    echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

                    $size = array('728x90', '468x60', '234x60','120x600', '160x600', '120x240', '336x280', '300x250', '250x250', '200x200', '180x150', '125x125');

                    foreach ( $size as $k)
                    {
                        echo "<option value='".$k."'";
                        if($this->get_option('itex_m_adsense_b'.$block.'_size') == $k) echo " selected='selected'";
                        echo ">".$k."</option>\n";
                    }
                    echo "</select>\n";
                    echo '<label for="">'.$this->__('Block size ', 'iMoney').'</label>';
                    echo "<br/>\n";

                    echo "<select name='adsense_b".$block."_pos' id='adsense_b".$block."_pos'>\n";
                    echo "<option value='0'";
                    if(!$this->get_option('itex_m_adsense_b'.$block.'_pos')) echo" selected='selected'";
                    echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

                    $pos = array('sidebar', 'footer', 'beforecontent','aftercontent');
                    foreach ( $pos as $k)
                    {
                        echo "<option value='".$k."'";
                        if($this->get_option('itex_m_adsense_b'.$block.'_pos') == $k) echo " selected='selected'";
                        echo ">".$k."</option>\n";
                    }
                    echo "</select>\n";
                    echo '<label for="">'.$this->__('Block position', 'iMoney').'</label>';
                    echo "<br/>\n";


                    echo "<input type='text' size='20' ";
                    echo "name='adsense_b".$block."_adslot'";
                    echo "id='adsense_b".$block."_adslot' ";
                    echo "value='".$this->get_option('itex_m_adsense_b'.$block.'_adslot')."' />\n";
                    echo '<label for="">'.$this->__('Ad slot id', 'iMoney').'</label>';
                    echo "<br/>\n";

                    ?>
                </td>


            </tr>

            <?php
        }
        ?>


    </table>
    <?php
    }

    /**
     * Begun section admin menu
     *
     */
    function itex_m_admin_begun()
    {
        $maxblock = 4; //max  begun blocks - 1
        if (isset($_POST['info_update']))
        {
            //phpinfo();die();
            if (isset($_POST['begun_id']))
            {
                $this->update_option('itex_m_begun_id', trim($_POST['begun_id']));
            }
            //print_r($_POST['begun_enable']);
            if (isset($_POST['begun_enable']))
            {
                $this->update_option('itex_m_begun_enable', intval($_POST['begun_enable']));
            }
            for ($block=1;$block<$maxblock;$block++)
            {
                if (isset($_POST['begun_b'.$block.'_enable']))
                {
                    $this->update_option('itex_m_begun_b'.$block.'_enable', trim($_POST['begun_b'.$block.'_enable']));
                }

                if (isset($_POST['begun_b'.$block.'_pos']) && !empty($_POST['begun_b'.$block.'_pos']))
                {
                    $this->update_option('itex_m_begun_b'.$block.'_pos', trim($_POST['begun_b'.$block.'_pos']));

                    //					$s_w = wp_get_sidebars_widgets();
                    //					$ex = 0;
                    //					if (count($s_w['sidebar-1'])) foreach ($s_w['sidebar-1'] as $k => $v)
                    //					{
                    //						if ($v == 'imoney_begun_'.$block)
                    //						{
                    //							$ex = 1;
                    //							if ($_POST['begun_b'.$block.'_pos'] != 'sidebar') unset($s_w['sidebar-1'][$k]);
                    //						}
                    //					}
                    //					if (!$ex && ($_POST['begun_b'.$block.'_pos'] == 'sidebar')) $s_w['sidebar-1'][] = 'imoney_begun_'.$block;
                    //					wp_set_sidebars_widgets( $s_w );
                }
                if (isset($_POST['begun_b'.$block.'_block_id']) && !empty($_POST['begun_b'.$block.'_block_id']))
                {
                    $this->update_option('itex_m_begun_b'.$block.'_block_id', trim($_POST['begun_b'.$block.'_block_id']));
                }

            }

            echo "<div class='updated fade'><p><strong>Settings saved.</strong></p></div>";
        }
        ?>
    <table class="form-table" cellspacing="2" cellpadding="5" width="100%">
        <tr>
            <th valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('Your begun ID:', 'iMoney');?></label>
            </th>
            <td>
                <?php
                echo "<input type='text' size='50' ";
                echo "name='begun_id'";
                echo "id='begun_id' ";
                echo "value='".$this->get_option('itex_m_begun_id')."' />\n";
                ?>
                <p style="margin: 5px 10px;"><?php
                    //echo $this->__('Enter your Begun auto pad ID in this box (begun_auto_pad).', 'iMoney');
                    echo '<a target="_blank" href="http://referal.begun.ru/partner.php?oid=114115214">'.$this->__('Enter your Begun auto pad ID in this box (begun_auto_pad).', 'iMoney').'</a>';


                    ?></p>

                <?php
                echo "<select name='begun_enable' id='begun_enable'>\n";
                echo "<option value='1'";

                if($this->get_option('itex_m_begun_enable')) echo " selected='selected'";
                echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

                echo "<option value='0'";
                if(!$this->get_option('itex_m_begun_enable')) echo" selected='selected'";
                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                echo "</select>\n";

                echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
                echo "<br/>\n";
                ?>
            </td>
        </tr>
        <?php
        for ($block=1;$block<$maxblock;$block++)
        {
            ?>

            <tr>
                <th width="30%" valign="top" style="padding-top: 10px;">
                    <label for=""><?php echo $this->__('begun Block ', 'iMoney').$block.': ';?></label>
                </th>
                <td>
                    <?php
                    echo "<select name='begun_b".$block."_enable' id='begun_b".$block."_enable'>\n";
                    echo "<option value='1'";

                    if($this->get_option('itex_m_begun_b'.$block.'_enable')) echo " selected='selected'";
                    echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

                    echo "<option value='0'";
                    if(!$this->get_option('itex_m_begun_b'.$block.'_enable')) echo" selected='selected'";
                    echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                    echo "</select>\n";

                    echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
                    echo "<br/>\n";

                    echo "<select name='begun_b".$block."_pos' id='begun_b".$block."_pos'>\n";
                    echo "<option value='0'";
                    if(!$this->get_option('itex_m_begun_b'.$block.'_pos')) echo" selected='selected'";
                    echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

                    $pos = array('sidebar', 'footer', 'beforecontent','aftercontent');
                    foreach ( $pos as $k)
                    {
                        echo "<option value='".$k."'";
                        if($this->get_option('itex_m_begun_b'.$block.'_pos') == $k) echo " selected='selected'";
                        echo ">".$k."</option>\n";
                    }
                    echo "</select>\n";
                    echo '<label for="">'.$this->__('Block position', 'iMoney').'</label>';
                    echo "<br/>\n";


                    echo "<input type='text' size='20' ";
                    echo "name='begun_b".$block."_block_id'";
                    echo "id='begun_b".$block."_block_id' ";
                    echo "value='".$this->get_option('itex_m_begun_b'.$block.'_block_id')."' />\n";
                    echo '<label for="">'.$this->__('Ad slot id', 'iMoney').' (begun_block_id)</label>';
                    echo "<br/>\n";

                    ?>
                </td>


            </tr>

            <?php
        }
        ?>
        <tr>
            <th width="30%" valign="top" style="padding-top: 10px;">
                <label for=""></label>
            </th>
            <td align="center">
                <br/><br/>
                <a target="_blank" href="http://referal.begun.ru/partner.php?oid=114115214">begun.ru</a>
                <br/>
                <a target="_blank" href="http://referal.begun.ru/partner.php?oid=114115214">
                    <img src="http://promo.begun.ru/my/data/banners/107_04_partner.gif" alt="Покупаем рекламу. Дорого." border="0" height="60" width="468">
                </a>

            </td>
        </tr>

    </table>
    <?php
    }

    /**
     * admitad section admin menu
     *
     */
    function itex_m_admin_admitad()
    {
        $maxblock = 4; //max  admitad blocks - 1

        ?>
    <table class="form-table" cellspacing="2" cellpadding="5" width="100%">
        <tr>
            <th valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('Your admitad ID:', 'iMoney');?></label>
            </th>
            <td>
                <?php
                $o= array('1' => $this->__('Enabled', 'iMoney'),'0' => $this->__('Disabled', 'iMoney'),);
                $d = '<a target="_blank" href="http://www.admitad.com/ru/promo/?ref=f0fc9a3889">'.$this->__('Enter your admitad ID and block positions.', 'iMoney').'</a>';
                $this->itex_m_admin_select('itex_m_admitad_enable', $o, $d);



                //            echo "<select name='admitad_enable' id='admitad_enable'>\n";
                //            echo "<option value='1'";
                //
                //            if($this->get_option('itex_m_admitad_enable')) echo " selected='selected'";
                //            echo ">".$this->__("Enabled", 'iMoney')."</option>\n";
                //
                //            echo "<option value='0'";
                //            if(!$this->get_option('itex_m_admitad_enable')) echo" selected='selected'";
                //            echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                //            echo "</select>\n";
                //
                //            echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
                //            echo "<br/>\n";
                ?>
            </td>
        </tr>
        <?php
        for ($block=1;$block<$maxblock;$block++)
        {
            ?>

            <tr>
                <th width="30%" valign="top" style="padding-top: 10px;">
                    <label for=""><?php echo $this->__('admitad Block ', 'iMoney').$block.': ';?></label>
                </th>
                <td>
                    <?php
                    $o= array('1' => $this->__('Enabled', 'iMoney'),'0' => $this->__('Disabled', 'iMoney'),);
                    $d = $this->__('Block '.$block, 'iMoney');
                    $this->itex_m_admin_select('itex_m_admitad_b'.$block.'_enable', $o, $d);

                    //                echo "<select name='admitad_b".$block."_enable' id='admitad_b".$block."_enable'>\n";
                    //                echo "<option value='1'";
                    //
                    //                if($this->get_option('itex_m_admitad_b'.$block.'_enable')) echo " selected='selected'";
                    //                echo ">".$this->__("Enabled", 'iMoney')."</option>\n";
                    //
                    //                echo "<option value='0'";
                    //                if(!$this->get_option('itex_m_admitad_b'.$block.'_enable')) echo" selected='selected'";
                    //                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                    //                echo "</select>\n";
                    //
                    //                echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
                    //                echo "<br/>\n";


                    //                echo "<select name='admitad_b".$block."_id' id='admitad_b".$block."_id'>\n";
                    //                echo "<option value='0'";
                    //                if(!$this->get_option('itex_m_admitad_b'.$block.'_id')) echo" selected='selected'";
                    //                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                    //
                    //                //$size = array('728x90', '468x60', '234x60','120x600', '160x600', '120x240', '336x280', '300x250', '250x250', '200x200', '180x150', '125x125');
                    //                $size = array('1'=> '468×60', '2'=> '100×100', '3'=> 'RICH', '4'=> 'Topline', '5'=> '600×90', '6'=> '120×600', '7'=> '240×400',);
                    //                foreach ( $size as $k=>$v)
                    //                {
                    //                    echo "<option value='".$k."'";
                    //                    if($this->get_option('itex_m_admitad_b'.$block.'_id') == $k) echo " selected='selected'";
                    //                    echo ">".$size[$k]."</option>\n";
                    //                }
                    //                echo "</select>\n";
                    //                echo '<label for="">'.$this->__('Block size ', 'iMoney').'</label>';
                    //                echo "<br/>\n";

                    //$o = array('sidebar'=>'sidebar', 'footer'=>'footer', 'beforecontent'=>'beforecontent','aftercontent'=>'aftercontent');
                    $d = $this->__('Block id', 'iMoney');
                    $this->itex_m_admin_input('itex_m_admitad_b'.$block.'_id', $d);

                    //$o= array('1' => $this->__('Enabled', 'iMoney'),'0' => $this->__('Disabled', 'iMoney'),);
                    $o = array('sidebar'=>'sidebar', 'footer'=>'footer', 'beforecontent'=>'beforecontent','aftercontent'=>'aftercontent');
                    $d = $this->__('Block position', 'iMoney');
                    $this->itex_m_admin_select('itex_m_admitad_b'.$block.'_pos', $o, $d);

                    //                echo "<select name='admitad_b".$block."_pos' id='admitad_b".$block."_pos'>\n";
                    //                echo "<option value='0'";
                    //                if(!$this->get_option('itex_m_admitad_b'.$block.'_pos')) echo" selected='selected'";
                    //                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                    //
                    //                $pos = array('sidebar', 'footer', 'beforecontent','aftercontent');
                    //                foreach ( $pos as $k)
                    //                {
                    //                    echo "<option value='".$k."'";
                    //                    if($this->get_option('itex_m_admitad_b'.$block.'_pos') == $k) echo " selected='selected'";
                    //                    echo ">".$k."</option>\n";
                    //                }
                    //                echo "</select>\n";
                    //                echo '<label for="">'.$this->__('Block position', 'iMoney').'</label>';
                    //                echo "<br/>\n";



                    ?>
                </td>


            </tr>

            <?php
        }
        ?>
        <tr>
            <th width="30%" valign="top" style="padding-top: 10px;">
                <label for=""></label>
            </th>
            <td align="center">
                <br/><br/>
                <a target="_blank" href="http://www.admitad.com/ru/promo/?ref=f0fc9a3889">www.admitad.com</a>
                <br/>
                <a target="_blank" href="http://www.admitad.com/ru/promo/?ref=f0fc9a3889">

                </a>

            </td>
        </tr>

    </table>
    <?php
    }


    /**
     * Adskape section admin menu
     *
     */
    function itex_m_admin_adskape()
    {
        $maxblock = 4; //max  adskape blocks - 1
        if (isset($_POST['info_update']))
        {
            //phpinfo();die();
            if (isset($_POST['adskape_id']))
            {
                $this->update_option('itex_m_adskape_id', trim($_POST['adskape_id']));
            }
            if (isset($_POST['adskape_enable']))
            {
                $this->update_option('itex_m_adskape_enable', intval($_POST['adskape_enable']));
            }
            for ($block=1;$block<$maxblock;$block++)
            {
                if (isset($_POST['adskape_b'.$block.'_enable']))
                {
                    $this->update_option('itex_m_adskape_b'.$block.'_enable', trim($_POST['adskape_b'.$block.'_enable']));
                }

                if (isset($_POST['adskape_b'.$block.'_size']) && !empty($_POST['adskape_b'.$block.'_size']))
                {
                    $this->update_option('itex_m_adskape_b'.$block.'_size', trim($_POST['adskape_b'.$block.'_size']));
                }

                if (isset($_POST['adskape_b'.$block.'_pos']) && !empty($_POST['adskape_b'.$block.'_pos']))
                {
                    $this->update_option('itex_m_adskape_b'.$block.'_pos', trim($_POST['adskape_b'.$block.'_pos']));
                    //					$s_w = wp_get_sidebars_widgets();
                    //					$ex = 0;
                    //					if (count($s_w['sidebar-1'])) foreach ($s_w['sidebar-1'] as $k => $v)
                    //					{
                    //						if ($v == 'imoney_adskape_'.$block)
                    //						{
                    //							$ex = 1;
                    //							if ($_POST['adskape_b'.$block.'_pos'] != 'sidebar') unset($s_w['sidebar-1'][$k]);
                    //						}
                    //					}
                    //					if (!$ex && ($_POST['adskape_b'.$block.'_pos'] == 'sidebar')) $s_w['sidebar-1'][] = 'imoney_adskape_'.$block;
                    //					wp_set_sidebars_widgets($s_w);
                }
                if (isset($_POST['adskape_b'.$block.'_adslot']) && !empty($_POST['adskape_b'.$block.'_adslot']))
                {
                    $this->update_option('itex_m_adskape_b'.$block.'_adslot', trim($_POST['adskape_b'.$block.'_adslot']));
                }

            }

            echo "<div class='updated fade'><p><strong>Settings saved.</strong></p></div>";
        }
        ?>
    <table class="form-table" cellspacing="2" cellpadding="5" width="100%">
        <tr>
            <th valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('Your adskape ID:', 'iMoney');?></label>
            </th>
            <td>
                <?php
                echo "<input type='text' size='50' ";
                echo "name='adskape_id'";
                echo "id='adskape_id' ";
                echo "value='".$this->get_option('itex_m_adskape_id')."' />\n";
                ?>
                <p style="margin: 5px 10px;"><?php
                    echo '<a target="_blank" href="http://adskape.ru/unireg.php?ref=17729&d=1">'.$this->__('Enter your Adskape site ID in this box.', 'iMoney').'</a>';

                    ?></p>

                <?php
                echo "<select name='adskape_enable' id='adskape_enable'>\n";
                echo "<option value='1'";

                if($this->get_option('itex_m_adskape_enable')) echo " selected='selected'";
                echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

                echo "<option value='0'";
                if(!$this->get_option('itex_m_adskape_enable')) echo" selected='selected'";
                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                echo "</select>\n";

                echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
                echo "<br/>\n";
                ?>
            </td>
        </tr>
        <?php
        for ($block=1;$block<$maxblock;$block++)
        {
            ?>

            <tr>
                <th width="30%" valign="top" style="padding-top: 10px;">
                    <label for=""><?php echo $this->__('adskape Block ', 'iMoney').$block.': ';?></label>
                </th>
                <td>
                    <?php
                    echo "<select name='adskape_b".$block."_enable' id='adskape_b".$block."_enable'>\n";
                    echo "<option value='1'";

                    if($this->get_option('itex_m_adskape_b'.$block.'_enable')) echo " selected='selected'";
                    echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

                    echo "<option value='0'";
                    if(!$this->get_option('itex_m_adskape_b'.$block.'_enable')) echo" selected='selected'";
                    echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                    echo "</select>\n";

                    echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
                    echo "<br/>\n";


                    echo "<select name='adskape_b".$block."_size' id='adskape_b".$block."_size'>\n";
                    echo "<option value='0'";
                    if(!$this->get_option('itex_m_adskape_b'.$block.'_size')) echo" selected='selected'";
                    echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

                    //$size = array('728x90', '468x60', '234x60','120x600', '160x600', '120x240', '336x280', '300x250', '250x250', '200x200', '180x150', '125x125');
                    $size = array('1'=> '468×60', '2'=> '100×100', '3'=> 'RICH', '4'=> 'Topline', '5'=> '600×90', '6'=> '120×600', '7'=> '240×400',);
                    foreach ( $size as $k=>$v)
                    {
                        echo "<option value='".$k."'";
                        if($this->get_option('itex_m_adskape_b'.$block.'_size') == $k) echo " selected='selected'";
                        echo ">".$size[$k]."</option>\n";
                    }
                    echo "</select>\n";
                    echo '<label for="">'.$this->__('Block size ', 'iMoney').'</label>';
                    echo "<br/>\n";

                    echo "<select name='adskape_b".$block."_pos' id='adskape_b".$block."_pos'>\n";
                    echo "<option value='0'";
                    if(!$this->get_option('itex_m_adskape_b'.$block.'_pos')) echo" selected='selected'";
                    echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

                    $pos = array('sidebar', 'footer', 'beforecontent','aftercontent');
                    foreach ( $pos as $k)
                    {
                        echo "<option value='".$k."'";
                        if($this->get_option('itex_m_adskape_b'.$block.'_pos') == $k) echo " selected='selected'";
                        echo ">".$k."</option>\n";
                    }
                    echo "</select>\n";
                    echo '<label for="">'.$this->__('Block position', 'iMoney').'</label>';
                    echo "<br/>\n";



                    ?>
                </td>


            </tr>

            <?php
        }
        ?>
        <tr>
            <th width="30%" valign="top" style="padding-top: 10px;">
                <label for=""></label>
            </th>
            <td align="center">
                <br/><br/>
                <a target="_blank" href="http://adskape.ru/unireg.php?ref=17729&d=1">adskape.ru</a>
                <br/>
                <a target="_blank" href="http://adskape.ru/unireg.php?ref=17729&d=1">
                    <img src="http://adskape.ru/Banners/pr2-1.gif" alt="www.adskape.ru!">
                </a>

            </td>
        </tr>

    </table>
    <?php
    }

    /**
     * teasernet section admin menu
     *
     */
    function itex_m_admin_teasernet()
    {
        $maxblock = 4; //max  teasernet blocks - 1
        if (isset($_POST['info_update']))
        {
            //phpinfo();die();
            if (isset($_POST['teasernet_id']))
            {
                $this->update_option('itex_m_teasernet_padid', trim($_POST['teasernet_padid']));
            }
            if (isset($_POST['teasernet_enable']))
            {
                $this->update_option('itex_m_teasernet_enable', intval($_POST['teasernet_enable']));
            }
            for ($block=1;$block<$maxblock;$block++)
            {
                if (isset($_POST['teasernet_b'.$block.'_enable']))
                {
                    $this->update_option('itex_m_teasernet_b'.$block.'_enable', trim($_POST['teasernet_b'.$block.'_enable']));
                }

                if (isset($_POST['teasernet_b'.$block.'_blockid']) && !empty($_POST['teasernet_b'.$block.'_blockid']))
                {
                    $this->update_option('itex_m_teasernet_b'.$block.'_blockid', trim($_POST['teasernet_b'.$block.'_blockid']));
                }

                if (isset($_POST['teasernet_b'.$block.'_pos']) && !empty($_POST['teasernet_b'.$block.'_pos']))
                {
                    $this->update_option('itex_m_teasernet_b'.$block.'_pos', trim($_POST['teasernet_b'.$block.'_pos']));
                    //					$s_w = wp_get_sidebars_widgets();
                    //					$ex = 0;
                    //					if (count($s_w['sidebar-1'])) foreach ($s_w['sidebar-1'] as $k => $v)
                    //					{
                    //						if ($v == 'imoney_teasernet_'.$block)
                    //						{
                    //							$ex = 1;
                    //							if ($_POST['teasernet_b'.$block.'_pos'] != 'sidebar') unset($s_w['sidebar-1'][$k]);
                    //						}
                    //					}
                    //					if (!$ex && ($_POST['teasernet_b'.$block.'_pos'] == 'sidebar')) $s_w['sidebar-1'][] = 'imoney_teasernet_'.$block;
                    //					wp_set_sidebars_widgets($s_w);
                }
                /*if (isset($_POST['teasernet_b'.$block.'_adslot']) && !empty($_POST['teasernet_b'.$block.'_adslot']))
				{
				$this->update_option('itex_m_teasernet_b'.$block.'_adslot', trim($_POST['teasernet_b'.$block.'_adslot']));
				}*/

            }

            echo "<div class='updated fade'><p><strong>Settings saved.</strong></p></div>";
        }
        ?>
    <table class="form-table" cellspacing="2" cellpadding="5" width="100%">
        <tr>
            <th valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('Your teasernet padid:', 'iMoney');?></label>
            </th>
            <td>
                <?php
                echo "<input type='text' size='50' ";
                echo "name='teasernet_id'";
                echo "id='teasernet_id' ";
                echo "value='".$this->get_option('itex_m_teasernet_padid')."' />\n";
                ?>
                <p style="margin: 5px 10px;"><?php
                    echo '<a target="_blank" href="http://teasernet.com/?owner_id=18516">'.$this->__('Enter your teasernet site padid in this box.', 'iMoney').'</a>';
                    ?></p>

                <?php
                echo "<select name='teasernet_enable' id='teasernet_enable'>\n";
                echo "<option value='1'";

                if($this->get_option('itex_m_teasernet_enable')) echo " selected='selected'";
                echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

                echo "<option value='0'";
                if(!$this->get_option('itex_m_teasernet_enable')) echo" selected='selected'";
                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                echo "</select>\n";

                echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
                echo "<br/>\n";
                ?>
            </td>
        </tr>
        <?php
        for ($block=1;$block<$maxblock;$block++)
        {
            ?>

            <tr>
                <th width="30%" valign="top" style="padding-top: 10px;">
                    <label for=""><?php echo $this->__('teasernet Block ', 'iMoney').$block.': ';?></label>
                </th>
                <td>
                    <?php
                    echo "<select name='teasernet_b".$block."_enable' id='teasernet_b".$block."_enable'>\n";
                    echo "<option value='1'";

                    if($this->get_option('itex_m_teasernet_b'.$block.'_enable')) echo " selected='selected'";
                    echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

                    echo "<option value='0'";
                    if(!$this->get_option('itex_m_teasernet_b'.$block.'_enable')) echo" selected='selected'";
                    echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                    echo "</select>\n";

                    echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
                    echo "<br/>\n";

                    echo "<input type='text' size='20' ";
                    echo "name='teasernet_b".$block."_blockid'";
                    echo "id='teasernet_b".$block."_blockid' ";
                    echo "value='".$this->get_option('itex_m_teasernet_b'.$block.'_blockid')."' />\n";
                    echo '<label for="">'.$this->__('Teasernet blockid', 'iMoney').'</label>';
                    echo "<br/>\n";


                    ?>
                </td>


            </tr>

            <?php
        }
        ?>
        <tr>
            <th width="30%" valign="top" style="padding-top: 10px;">
                <label for=""></label>
            </th>
            <td align="center">
                <br/><br/>
                <a target="_blank" href="http://teasernet.com/?owner_id=18516">teasernet.com</a>
                <br/>
                <a target="_blank" href="http://teasernet.com/?owner_id=18516">
                    <img src="http://pic5.teasernet.com/tz/2-468_60.gif"></a>


            </td>
        </tr>

    </table>
    <?php
    }

    /**
     * Html section admin menu
     *
     */
    function itex_m_admin_html()
    {
        if (isset($_POST['info_update']))
        {
            if (isset($_POST['html_enable']))
            {
                $this->update_option('itex_m_html_enable', intval($_POST['html_enable']));
            }
            if (isset($_POST['html_footer']))
            {
                $this->update_option('itex_m_html_footer', $_POST['html_footer']);
            }
            if (isset($_POST['html_footer_enable']))
            {
                $this->update_option('itex_m_html_footer_enable', $_POST['html_footer_enable']);
            }
            if (isset($_POST['html_beforecontent']))
            {
                $this->update_option('itex_m_html_beforecontent', $_POST['html_beforecontent']);
            }
            if (isset($_POST['html_beforecontent_enable']))
            {
                $this->update_option('itex_m_html_beforecontent_enable', $_POST['html_beforecontent_enable']);
            }
            if (isset($_POST['html_aftercontent']))
            {
                $this->update_option('itex_m_html_aftercontent', $_POST['html_aftercontent']);
            }
            if (isset($_POST['html_aftercontent_enable']))
            {
                $this->update_option('itex_m_html_aftercontent_enable', $_POST['html_aftercontent_enable']);
            }

            if (isset($_POST['html_sidebar']))
            {
                $this->update_option('itex_m_html_sidebar', $_POST['html_sidebar']);
            }
            if (isset($_POST['html_sidebar_enable']))
            {
                $this->update_option('itex_m_html_sidebar_enable', $_POST['html_sidebar_enable']);
                //				$s_w = wp_get_sidebars_widgets();
                //				$ex = 0;
                //				if (count($s_w['sidebar-1'])) foreach ($s_w['sidebar-1'] as $k => $v)
                //				{
                //					if ($v == 'imoney_html')
                //					{
                //						$ex = 1;
                //						if (!$_POST['html_sidebar_enable']) unset($s_w['sidebar-1'][$k]);
                //					}
                //				}
                //				if (!$ex && ($_POST['html_sidebar_enable'])) $s_w['sidebar-1'][] = 'imoney_html';
                //				wp_set_sidebars_widgets( $s_w );
            }
            //wp_cache_flush();
            echo "<div class='updated fade'><p><strong>Settings saved.</strong></p></div>";
        }
        ?>
    <table class="form-table" cellspacing="2" cellpadding="5" width="100%">
        <tr>
            <th valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('Html inserts:', 'iMoney');?></label>
            </th>
            <td>
                <?php
                echo "<select name='html_enable' id='html_enable'>\n";
                echo "<option value='1'";

                if($this->get_option('itex_m_html_enable')) echo " selected='selected'";
                echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

                echo "<option value='0'";
                if(!$this->get_option('itex_m_html_enable')) echo" selected='selected'";
                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                echo "</select>\n";

                echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
                echo "<br/>\n";
                ?>
            </td>
        </tr>

        <tr>
            <th valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('Footer:', 'iMoney');?></label>
            </th>
            <td>
                <?php
                echo "<textarea rows='5' cols='80'";
                echo "name='html_footer'";
                echo "id='html_footer'>";
                echo stripslashes($this->get_option('itex_m_html_footer'))."</textarea>\n";
                ?>
                <p style="margin: 5px 10px;"><?php echo $this->__('Enter your html in this box.', 'iMoney');?></p>

                <?php
                echo "<select name='html_footer_enable' id='html_footer_enable'>\n";
                echo "<option value='1'";

                if($this->get_option('itex_m_html_footer_enable')) echo " selected='selected'";
                echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

                echo "<option value='0'";
                if(!$this->get_option('itex_m_html_footer_enable')) echo" selected='selected'";
                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                echo "</select>\n";

                echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
                echo "<br/>\n";
                ?>
            </td>
        </tr>

        <tr>
            <th valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('Before Content:', 'iMoney');?></label>
            </th>
            <td>
                <?php
                echo "<textarea rows='5' cols='80'";
                echo "name='html_beforecontent'";
                echo "id='html_beforecontent'>";
                echo stripslashes($this->get_option('itex_m_html_beforecontent'))."</textarea>\n";
                ?>
                <p style="margin: 5px 10px;"><?php echo $this->__('Enter your html in this box.', 'iMoney');?></p>

                <?php
                echo "<select name='html_beforecontent_enable' id='html_beforecontent_enable'>\n";
                echo "<option value='1'";

                if($this->get_option('itex_m_html_beforecontent_enable')) echo " selected='selected'";
                echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

                echo "<option value='0'";
                if(!$this->get_option('itex_m_html_beforecontent_enable')) echo" selected='selected'";
                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                echo "</select>\n";

                echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
                echo "<br/>\n";
                ?>
            </td>
        </tr>

        <tr>
            <th valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('After Content:', 'iMoney');?></label>
            </th>
            <td>
                <?php
                echo "<textarea rows='5' cols='80'";
                echo "name='html_aftercontent'";
                echo "id='html_aftercontent'>";
                echo stripslashes($this->get_option('itex_m_html_aftercontent'))."</textarea>\n";
                ?>
                <p style="margin: 5px 10px;"><?php echo $this->__('Enter your html in this box.', 'iMoney');?></p>

                <?php
                echo "<select name='html_aftercontent_enable' id='html_aftercontent_enable'>\n";
                echo "<option value='1'";

                if($this->get_option('itex_m_html_aftercontent_enable')) echo " selected='selected'";
                echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

                echo "<option value='0'";
                if(!$this->get_option('itex_m_html_aftercontent_enable')) echo" selected='selected'";
                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                echo "</select>\n";

                echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
                echo "<br/>\n";
                ?>
            </td>
        </tr>

        <tr>
            <th valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('Sidebar:', 'iMoney');?></label>
            </th>
            <td>
                <?php
                echo "<textarea rows='5' cols='80'";
                echo "name='html_sidebar'";
                echo "id='html_sidebar'>";
                echo stripslashes($this->get_option('itex_m_html_sidebar'))."</textarea>\n";
                ?>
                <p style="margin: 5px 10px;"><?php echo $this->__('Enter your html in this box.', 'iMoney');?></p>

                <?php
                echo "<select name='html_sidebar_enable' id='html_sidebar_enable'>\n";
                echo "<option value='1'";

                if($this->get_option('itex_m_html_sidebar_enable')) echo " selected='selected'";
                echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

                echo "<option value='0'";
                if(!$this->get_option('itex_m_html_sidebar_enable')) echo" selected='selected'";
                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                echo "</select>\n";

                echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
                echo "<br/>\n";
                ?>
            </td>
        </tr>

    </table>
    <?php
    }

    /**
     * php section admin menu
     *
     */
    function itex_m_admin_php()
    {
        if (isset($_POST['info_update']))
        {
            if (isset($_POST['php_enable']))
            {
                $this->update_option('itex_m_php_enable', intval($_POST['php_enable']));
            }
            if (isset($_POST['php_footer']))
            {
                $this->update_option('itex_m_php_footer', $_POST['php_footer']);
                //print_r($this->get_option('itex_m_php_footer'));
                if (!$this->itex_m_admin_php_syntax(stripslashes($this->get_option('itex_m_php_footer'))))
                    echo '<div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">php_footer wrong syntax!</div>';

            }
            if (isset($_POST['php_footer_enable']))
            {
                $this->update_option('itex_m_php_footer_enable', $_POST['php_footer_enable']);
            }
            if (isset($_POST['php_beforecontent']))
            {
                $this->update_option('itex_m_php_beforecontent', $_POST['php_beforecontent']);
                if (!$this->itex_m_admin_php_syntax(stripslashes($this->get_option('itex_m_php_beforecontent'))))
                    echo '<div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">php_beforecontent wrong syntax!</div>';

            }
            if (isset($_POST['php_beforecontent_enable']))
            {
                $this->update_option('itex_m_php_beforecontent_enable', $_POST['php_beforecontent_enable']);
            }
            if (isset($_POST['php_aftercontent']))
            {
                $this->update_option('itex_m_php_aftercontent', $_POST['php_aftercontent']);
                if (!$this->itex_m_admin_php_syntax(stripslashes($this->get_option('itex_m_php_aftercontent'))))
                    echo '<div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">php_aftercontent wrong syntax!</div>';

            }
            if (isset($_POST['php_aftercontent_enable']))
            {
                $this->update_option('itex_m_php_aftercontent_enable', $_POST['php_aftercontent_enable']);
            }

            if (isset($_POST['php_sidebar']))
            {
                $this->update_option('itex_m_php_sidebar', $_POST['php_sidebar']);
                if (!$this->itex_m_admin_php_syntax(stripslashes($this->get_option('itex_m_php_sidebar'))))
                    echo '<div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">php_sidebar wrong syntax!</div>';

            }
            if (isset($_POST['php_sidebar_enable']))
            {
                $this->update_option('itex_m_php_sidebar_enable', $_POST['php_sidebar_enable']);
                //				$s_w = wp_get_sidebars_widgets();
                //				$ex = 0;
                //				if (count($s_w['sidebar-1'])) foreach ($s_w['sidebar-1'] as $k => $v)
                //				{
                //					if ($v == 'imoney_php')
                //					{
                //						$ex = 1;
                //						if (!$_POST['php_sidebar_enable']) unset($s_w['sidebar-1'][$k]);
                //					}
                //				}
                //				if (!$ex && ($_POST['php_sidebar_enable'])) $s_w['sidebar-1'][] = 'imoney_php';
                //				wp_set_sidebars_widgets( $s_w );
            }



            //wp_cache_flush();
            echo "<div class='updated fade'><p><strong>Settings saved.</strong></p></div>";
        }
        ?>
    <table class="form-table" cellspacing="2" cellpadding="5" width="100%">
        <tr>
            <th valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('Php inserts:', 'iMoney');?></label>
            </th>
            <td>
                <?php
                echo "<select name='php_enable' id='php_enable'>\n";
                echo "<option value='1'";

                if($this->get_option('itex_m_php_enable')) echo " selected='selected'";
                echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

                echo "<option value='0'";
                if(!$this->get_option('itex_m_php_enable')) echo" selected='selected'";
                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                echo "</select>\n";

                echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
                echo "<br/>\n";
                ?>
            </td>
        </tr>

        <tr>
            <th valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('Footer:', 'iMoney');?></label>
            </th>
            <td>
                <?php
                echo "<textarea rows='5' cols='80'";
                echo "name='php_footer'";
                echo "id='php_footer'>";
                echo stripslashes($this->get_option('itex_m_php_footer'))."</textarea>\n";
                ?>
                <p style="margin: 5px 10px;"><?php echo $this->__('Enter your php in this box.', 'iMoney');?></p>

                <?php
                echo "<select name='php_footer_enable' id='php_footer_enable'>\n";
                echo "<option value='1'";

                if($this->get_option('itex_m_php_footer_enable')) echo " selected='selected'";
                echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

                echo "<option value='0'";
                if(!$this->get_option('itex_m_php_footer_enable')) echo" selected='selected'";
                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                echo "</select>\n";

                echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
                echo "<br/>\n";
                ?>
            </td>
        </tr>

        <tr>
            <th valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('Before Content:', 'iMoney');?></label>
            </th>
            <td>
                <?php
                echo "<textarea rows='5' cols='80'";
                echo "name='php_beforecontent'";
                echo "id='php_beforecontent'>";
                echo stripslashes($this->get_option('itex_m_php_beforecontent'))."</textarea>\n";
                ?>
                <p style="margin: 5px 10px;"><?php echo $this->__('Enter your php in this box.', 'iMoney');?></p>

                <?php
                echo "<select name='php_beforecontent_enable' id='php_beforecontent_enable'>\n";
                echo "<option value='1'";

                if($this->get_option('itex_m_php_beforecontent_enable')) echo " selected='selected'";
                echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

                echo "<option value='0'";
                if(!$this->get_option('itex_m_php_beforecontent_enable')) echo" selected='selected'";
                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                echo "</select>\n";

                echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
                echo "<br/>\n";
                ?>
            </td>
        </tr>

        <tr>
            <th valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('After Content:', 'iMoney');?></label>
            </th>
            <td>
                <?php
                echo "<textarea rows='5' cols='80'";
                echo "name='php_aftercontent'";
                echo "id='php_aftercontent'>";
                echo stripslashes($this->get_option('itex_m_php_aftercontent'))."</textarea>\n";
                ?>
                <p style="margin: 5px 10px;"><?php echo $this->__('Enter your php in this box.', 'iMoney');?></p>

                <?php
                echo "<select name='php_aftercontent_enable' id='php_aftercontent_enable'>\n";
                echo "<option value='1'";

                if($this->get_option('itex_m_php_aftercontent_enable')) echo " selected='selected'";
                echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

                echo "<option value='0'";
                if(!$this->get_option('itex_m_php_aftercontent_enable')) echo" selected='selected'";
                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                echo "</select>\n";

                echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
                echo "<br/>\n";
                ?>
            </td>
        </tr>

        <tr>
            <th valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('Sidebar:', 'iMoney');?></label>
            </th>
            <td>
                <?php
                echo "<textarea rows='5' cols='80'";
                echo "name='php_sidebar'";
                echo "id='php_sidebar'>";
                echo stripslashes($this->get_option('itex_m_php_sidebar'))."</textarea>\n";
                ?>
                <p style="margin: 5px 10px;"><?php echo $this->__('Enter your php in this box.', 'iMoney');?></p>

                <?php
                echo "<select name='php_sidebar_enable' id='php_sidebar_enable'>\n";
                echo "<option value='1'";

                if($this->get_option('itex_m_php_sidebar_enable')) echo " selected='selected'";
                echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

                echo "<option value='0'";
                if(!$this->get_option('itex_m_php_sidebar_enable')) echo" selected='selected'";
                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                echo "</select>\n";

                echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
                echo "<br/>\n";
                ?>
            </td>
        </tr>

    </table>
    <?php
    }

    /**
     * php section check syntax
     *
     */
    function itex_m_admin_php_syntax($code)
    {
        $braces = 0;
        $inString = 0;

        //echo "<b>1111111111111111111-</br>".$code."-2222</br>";die();
        // We need to know if braces are correctly balanced.
        // This is not trivial due to variable interpolation
        // which occurs in heredoc, backticked and double quoted strings
        if (function_exists('token_get_all')) foreach (token_get_all('<?php ' . $code) as $token)
        {
            if (is_array($token))
            {
                switch ($token[0])
                {
                    case T_CURLY_OPEN:
                    case T_DOLLAR_OPEN_CURLY_BRACES:
                    case T_START_HEREDOC: ++$inString; break;
                    case T_END_HEREDOC:   --$inString; break;
                }
            }
            else if ($inString & 1)
            {
                switch ($token)
                {
                    case '`':
                    case '"': --$inString; break;
                }
            }
            else
            {
                switch ($token)
                {
                    case '`':
                    case '"': ++$inString; break;

                    case '{': ++$braces; break;
                    case '}':
                        if ($inString) --$inString;
                        else
                        {
                            --$braces;
                            if ($braces < 0) return false;
                        }

                        break;
                }
            }
        }

        if ($braces) return false; // Unbalanced braces would break the eval below
        else
        {
            ob_start(); // Catch potential parse error messages
            //echo "<b>1111111111111111111-</br>";
            $code = 'if(0){' . $code . '}';
            $code = eval($code); // Put $code in a dead code sandbox to prevent its execution
            ob_end_clean();
            //print_r($code);
            //echo "<b>1111111111111111111-</br>".$code."-2222</br>";die();
            return false !== $code;
        }
    }

    /**
     * iLinks section admin menu
     *
     */
    function itex_m_admin_ilinks()
    {
        if (isset($_POST['info_update']))
        {
            if (isset($_POST['ilinks_enable']))
            {
                $this->update_option('itex_m_ilinks_enable', intval($_POST['ilinks_enable']));
            }
            if (isset($_POST['ilinks_separator']))
            {
                $separator = trim($_POST['ilinks_separator']);

                if (!empty($separator))
                    $this->update_option('itex_m_ilinks_separator', $separator);
            }
            if (isset($_POST['ilinks_footer']))
            {
                $this->update_option('itex_m_ilinks_footer', $_POST['ilinks_footer']);
            }
            if (isset($_POST['ilinks_footer_enable']))
            {
                $this->update_option('itex_m_ilinks_footer_enable', $_POST['ilinks_footer_enable']);
            }
            if (isset($_POST['ilinks_beforecontent']))
            {
                $this->update_option('itex_m_ilinks_beforecontent', $_POST['ilinks_beforecontent']);
            }
            if (isset($_POST['ilinks_beforecontent_enable']))
            {
                $this->update_option('itex_m_ilinks_beforecontent_enable', $_POST['ilinks_beforecontent_enable']);
            }
            if (isset($_POST['ilinks_aftercontent']))
            {
                $this->update_option('itex_m_ilinks_aftercontent', $_POST['ilinks_aftercontent']);
            }
            if (isset($_POST['ilinks_aftercontent_enable']))
            {
                $this->update_option('itex_m_ilinks_aftercontent_enable', $_POST['ilinks_aftercontent_enable']);
            }

            if (isset($_POST['ilinks_sidebar']))
            {
                $this->update_option('itex_m_ilinks_sidebar', $_POST['ilinks_sidebar']);
            }
            if (isset($_POST['ilinks_sidebar_enable']))
            {
                $this->update_option('itex_m_ilinks_sidebar_enable', $_POST['ilinks_sidebar_enable']);
                //				$s_w = wp_get_sidebars_widgets();
                //				$ex = 0;
                //				if (count($s_w['sidebar-1'])) foreach ($s_w['sidebar-1'] as $k => $v)
                //				{
                //					if ($v == 'imoney_ilinks')
                //					{
                //						$ex = 1;
                //						if (!$_POST['ilinks_sidebar_enable']) unset($s_w['sidebar-1'][$k]);
                //					}
                //				}
                //				if (!$ex && ($_POST['ilinks_sidebar_enable'])) $s_w['sidebar-1'][] = 'imoney_ilinks';
                //				wp_set_sidebars_widgets( $s_w );
            }
            //wp_cache_flush();
            echo "<div class='updated fade'><p><strong>Settings saved.</strong></p></div>";
        }
        ?>
    <table class="form-table" cellspacing="2" cellpadding="5" width="100%">
        <tr>
            <th valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('iLinks inserts:', 'iMoney');?></label>
            </th>
            <td>
                <?php
                echo "<select name='ilinks_enable' id='ilinks_enable'>\n";
                echo "<option value='1'";

                if($this->get_option('itex_m_ilinks_enable')) echo " selected='selected'";
                echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

                echo "<option value='0'";
                if(!$this->get_option('itex_m_ilinks_enable')) echo" selected='selected'";
                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                echo "</select>\n";

                echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
                echo "<br/>\n";

                echo "<input type='text' size='2' ";
                echo "name='ilinks_separator'";
                echo "id='ilinks_separator' ";
                $separator = ($this->get_option('itex_m_ilinks_separator')?($this->get_option('itex_m_ilinks_separator')):':');
                echo "value='".$separator."' />\n";
                echo '<label for="">'.$this->__('Separator', 'iMoney').'</label>';
                echo "<br/>\n";

                ?>
            </td>
        </tr>

        <tr>
            <th valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('Footer:', 'iMoney');?></label>
            </th>
            <td>
                <?php
                echo "<textarea rows='5' cols='80'";
                echo "name='ilinks_footer'";
                echo "id='ilinks_footer'>";
                echo stripslashes($this->get_option('itex_m_ilinks_footer'))."</textarea>\n";
                ?>
                <p style="margin: 5px 10px;"><?php echo $this->__('Enter your ilinks in this box.', 'iMoney');?></p>

                <?php
                echo "<select name='ilinks_footer_enable' id='ilinks_footer_enable'>\n";
                echo "<option value='1'";

                if($this->get_option('itex_m_ilinks_footer_enable')) echo " selected='selected'";
                echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

                echo "<option value='0'";
                if(!$this->get_option('itex_m_ilinks_footer_enable')) echo" selected='selected'";
                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                echo "</select>\n";

                echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
                echo "<br/>\n";
                ?>
            </td>
        </tr>

        <tr>
            <th valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('Before Content:', 'iMoney');?></label>
            </th>
            <td>
                <?php
                echo "<textarea rows='5' cols='80'";
                echo "name='ilinks_beforecontent'";
                echo "id='ilinks_beforecontent'>";
                echo stripslashes($this->get_option('itex_m_ilinks_beforecontent'))."</textarea>\n";
                ?>
                <p style="margin: 5px 10px;"><?php echo $this->__('Enter your ilinks in this box.', 'iMoney');?></p>

                <?php
                echo "<select name='ilinks_beforecontent_enable' id='ilinks_beforecontent_enable'>\n";
                echo "<option value='1'";

                if($this->get_option('itex_m_ilinks_beforecontent_enable')) echo " selected='selected'";
                echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

                echo "<option value='0'";
                if(!$this->get_option('itex_m_ilinks_beforecontent_enable')) echo" selected='selected'";
                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                echo "</select>\n";

                echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
                echo "<br/>\n";
                ?>
            </td>
        </tr>

        <tr>
            <th valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('After Content:', 'iMoney');?></label>
            </th>
            <td>
                <?php
                echo "<textarea rows='5' cols='80'";
                echo "name='ilinks_aftercontent'";
                echo "id='ilinks_aftercontent'>";
                echo stripslashes($this->get_option('itex_m_ilinks_aftercontent'))."</textarea>\n";
                ?>
                <p style="margin: 5px 10px;"><?php echo $this->__('Enter your ilinks in this box.', 'iMoney');?></p>

                <?php
                echo "<select name='ilinks_aftercontent_enable' id='ilinks_aftercontent_enable'>\n";
                echo "<option value='1'";

                if($this->get_option('itex_m_ilinks_aftercontent_enable')) echo " selected='selected'";
                echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

                echo "<option value='0'";
                if(!$this->get_option('itex_m_ilinks_aftercontent_enable')) echo" selected='selected'";
                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                echo "</select>\n";

                echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
                echo "<br/>\n";
                ?>
            </td>
        </tr>

        <tr>
            <th valign="top" style="padding-top: 10px;">
                <label for=""><?php echo $this->__('Sidebar:', 'iMoney');?></label>
            </th>
            <td>
                <?php
                echo "<textarea rows='5' cols='80'";
                echo "name='ilinks_sidebar'";
                echo "id='ilinks_sidebar'>";
                echo stripslashes($this->get_option('itex_m_ilinks_sidebar'))."</textarea>\n";
                ?>
                <p style="margin: 5px 10px;"><?php echo $this->__('Enter your ilinks in this box.', 'iMoney');?></p>

                <?php
                echo "<select name='ilinks_sidebar_enable' id='ilinks_sidebar_enable'>\n";
                echo "<option value='1'";

                if($this->get_option('itex_m_ilinks_sidebar_enable')) echo " selected='selected'";
                echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

                echo "<option value='0'";
                if(!$this->get_option('itex_m_ilinks_sidebar_enable')) echo" selected='selected'";
                echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
                echo "</select>\n";

                echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
                echo "<br/>\n";
                ?>
            </td>
        </tr>

    </table>
    <?php
    }

    /**
     * Tnx/Xap section admin menu
     *
     */
    function itex_m_admin_tnx()
    {
        if (isset($_POST['info_update']))
        {
            //phpinfo();die();
            if (isset($_POST['tnx_tnxuser']))
            {
                $this->update_option('itex_m_tnx_tnxuser', trim($_POST['tnx_tnxuser']));
            }
            if (isset($_POST['tnx_enable']))
            {
                $this->update_option('itex_m_tnx_enable', intval($_POST['tnx_enable']));
            }

            if (isset($_POST['tnx_links_beforecontent']))
            {
                $this->update_option('itex_m_tnx_links_beforecontent', $_POST['tnx_links_beforecontent']);
            }

            if (isset($_POST['tnx_links_aftercontent']))
            {
                $this->update_option('itex_m_tnx_links_aftercontent', $_POST['tnx_links_aftercontent']);
            }

            if (isset($_POST['tnx_links_sidebar']))
            {
                $this->update_option('itex_m_tnx_links_sidebar', $_POST['tnx_links_sidebar']);
            }

            if (isset($_POST['tnx_links_footer']))
            {
                $this->update_option('itex_m_tnx_links_footer', $_POST['tnx_links_footer']);
            }

            if (isset($_POST['tnx_pages_enable']) )
            {
                $this->update_option('itex_m_tnx_pages_enable', intval($_POST['tnx_pages_enable']));
            }

            if (isset($_POST['tnx_tnxcontext_enable']) )
            {
                $this->update_option('itex_m_tnx_tnxcontext_enable', intval($_POST['tnx_tnxcontext_enable']));
            }

            if (isset($_POST['tnx_tnxcontext_pages_enable']) )
            {
                $this->update_option('itex_m_tnx_tnxcontext_pages_enable', intval($_POST['tnx_tnxcontext_pages_enable']));
            }

            //			if (isset($_POST['tnx_widget']))
            //			{
            //				$s_w = wp_get_sidebars_widgets();
            //				$ex = 0;
            //				if (count($s_w['sidebar-1'])) foreach ($s_w['sidebar-1'] as $k => $v)
            //				{
            //					if ($v == 'imoney-links')
            //					{
            //						$ex = 1;
            //						if (!$_POST['tnx_widget']) unset($s_w['sidebar-1'][$k]);
            //					}
            //				}
            //				if (!$ex && $_POST['tnx_widget']) $s_w['sidebar-1'][] = 'imoney-links';
            //				wp_set_sidebars_widgets( $s_w );
            //
            //			}
            echo "<div class='updated fade'><p><strong>Settings saved.</strong></p></div>";
        }
        if (isset($_POST['tnx_tnxdir_create']))
        {
            if ($this->get_option('itex_m_tnx_tnxuser'))  $this->itex_m_tnx_install_file();
            //phpinfo();die();//dir();
        }
        if ($this->get_option('itex_m_tnx_tnxuser'))
        {
            $file = $this->document_root . '/' . 'tnxdir_'.md5($this->get_option('itex_m_tnx_tnxuser')) . '/tnx.php'; //<< Not working in multihosting.
            if (file_exists($file)) {}
            else {?>
            <div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">
                tnx dir not exist!
            </div>
            <div style="margin:10px auto; border:3px #f00 solid; padding:10px; text-align:center;">
                Create new tnxdir and tnx.php? (<?php echo $file;?>)
                <p class="submit">
                    <input type='submit' name='tnx_tnxdir_create' value='<?php echo $this->__('Create', 'iMoney'); ?>' />
                </p>
                <?php
                if (!$this->get_option('itex_m_tnx_tnxuser')) echo $this->__('Enter your tnx UID in this box!', 'iMoney');
                ?>
            </div>

            <?php }
        }
        ?>
    <table class="form-table" cellspacing="2" cellpadding="5" width="100%">
    <tr>
        <th valign="top" style="padding-top: 10px;">
            <label for=""><?php echo $this->__('Your tnx UID:', 'iMoney');?></label>
        </th>
        <td>
            <?php
            echo "<input type='text' size='50' ";
            echo "name='tnx_tnxuser'";
            echo "id='tnxuser' ";
            echo "value='".$this->get_option('itex_m_tnx_tnxuser')."' />\n";
            ?>
            <p style="margin: 5px 10px;"><?php
                echo '<a target="_blank" href="http://www.tnx.net/?p=119596309">'.$this->__('Enter your tnx UID in this box.', 'iMoney').'</a>';
                ?></p>
        </td>
    </tr>
    <tr>
        <th width="30%" valign="top" style="padding-top: 10px;">
            <label for=""><?php echo $this->__('tnx links:', 'iMoney');?></label>
        </th>
        <td>
            <?php
            echo "<select name='tnx_enable' id='tnx_enable'>\n";
            echo "<option value='1'";

            if($this->get_option('itex_m_tnx_enable')) echo " selected='selected'";
            echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

            echo "<option value='0'";
            if(!$this->get_option('itex_m_tnx_enable')) echo" selected='selected'";
            echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
            echo "</select>\n";

            echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
            echo "<br/>\n";

            echo "<select name='tnx_links_beforecontent' id='tnx_links_beforecontent'>\n";

            echo "<option value='0'";
            if(!$this->get_option('itex_m_tnx_links_beforecontent')) echo" selected='selected'";
            echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

            echo "<option value='1'";
            if($this->get_option('itex_m_tnx_links_beforecontent') == 1) echo " selected='selected'";
            echo ">1</option>\n";

            echo "<option value='2'";
            if($this->get_option('itex_m_tnx_links_beforecontent') == 2) echo " selected='selected'";
            echo ">2</option>\n";

            echo "<option value='3'";
            if($this->get_option('itex_m_tnx_links_beforecontent') == 3) echo " selected='selected'";
            echo ">3</option>\n";

            echo "<option value='4'";
            if($this->get_option('itex_m_tnx_links_beforecontent') == 4) echo " selected='selected'";
            echo ">4</option>\n";

            echo "<option value='5'";
            if($this->get_option('itex_m_tnx_links_beforecontent') == 5) echo " selected='selected'";
            echo ">5</option>\n";

            echo "</select>\n";

            echo '<label for="">'.$this->__('Before content links', 'iMoney').'</label>';

            echo "<br/>\n";



            echo "<select name='tnx_links_aftercontent' id='tnx_links_aftercontent'>\n";

            echo "<option value='0'";
            if(!$this->get_option('itex_m_tnx_links_aftercontent')) echo" selected='selected'";
            echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

            echo "<option value='1'";
            if($this->get_option('itex_m_tnx_links_aftercontent') == 1) echo " selected='selected'";
            echo ">1</option>\n";

            echo "<option value='2'";
            if($this->get_option('itex_m_tnx_links_aftercontent') == 2) echo " selected='selected'";
            echo ">2</option>\n";

            echo "<option value='3'";
            if($this->get_option('itex_m_tnx_links_aftercontent') == 3) echo " selected='selected'";
            echo ">3</option>\n";

            echo "<option value='4'";
            if($this->get_option('itex_m_tnx_links_aftercontent') == 4) echo " selected='selected'";
            echo ">4</option>\n";

            echo "<option value='5'";
            if($this->get_option('itex_m_tnx_links_aftercontent') == 5) echo " selected='selected'";
            echo ">5</option>\n";

            echo "</select>\n";

            echo '<label for="">'.$this->__('After content links', 'iMoney').'</label>';

            echo "<br/>\n";

            echo "<select name='tnx_links_sidebar' id='tnx_links_sidebar'>\n";

            echo "<option value='0'";
            if(!$this->get_option('itex_m_tnx_links_sidebar')) echo" selected='selected'";
            echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

            echo "<option value='1'";
            if($this->get_option('itex_m_tnx_links_sidebar') == 1) echo " selected='selected'";
            echo ">1</option>\n";

            echo "<option value='2'";
            if($this->get_option('itex_m_tnx_links_sidebar') == 2) echo " selected='selected'";
            echo ">2</option>\n";

            echo "<option value='3'";
            if($this->get_option('itex_m_tnx_links_sidebar') == 3) echo " selected='selected'";
            echo ">3</option>\n";

            echo "<option value='4'";
            if($this->get_option('itex_m_tnx_links_sidebar') == 4) echo " selected='selected'";
            echo ">4</option>\n";

            echo "<option value='5'";
            if($this->get_option('itex_m_tnx_links_sidebar') == 5) echo " selected='selected'";
            echo ">5</option>\n";

            echo "<option value='max'";
            if($this->get_option('itex_m_tnx_links_sidebar') == 'max') echo " selected='selected'";
            echo ">".$this->__('Max', 'iMoney')."</option>\n";

            echo "</select>\n";

            echo '<label for="">'.$this->__('Sidebar links', 'iMoney').'</label>';

            echo "<br/>\n";


            echo "<select name='tnx_links_footer' id='tnx_links_footer'>\n";
            echo "<option value='0'";
            if(!$this->get_option('itex_m_tnx_links_footer')) echo" selected='selected'";
            echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

            echo "<option value='1'";
            if($this->get_option('itex_m_tnx_links_footer') == 1) echo " selected='selected'";
            echo ">1</option>\n";

            echo "<option value='2'";
            if($this->get_option('itex_m_tnx_links_footer') == 2) echo " selected='selected'";
            echo ">2</option>\n";

            echo "<option value='3'";
            if($this->get_option('itex_m_tnx_links_footer') == 3) echo " selected='selected'";
            echo ">3</option>\n";

            echo "<option value='4'";
            if($this->get_option('itex_m_tnx_links_footer') == 4) echo " selected='selected'";
            echo ">4</option>\n";

            echo "<option value='5'";
            if($this->get_option('itex_m_tnx_links_footer') == 5) echo " selected='selected'";
            echo ">5</option>\n";

            echo "<option value='max'";
            if($this->get_option('itex_m_tnx_links_footer') == 'max') echo " selected='selected'";
            echo ">".$this->__('Max', 'iMoney')."</option>\n";

            echo "</select>\n";

            echo '<label for="">'.$this->__('Footer links', 'iMoney').'</label>';

            //						echo "<br/>\n";
            //						$ws = wp_get_sidebars_widgets();
            //						echo "<select name='tnx_widget' id='tnx_widget'>\n";
            //						echo "<option value='0'";
            //						if (count($ws['sidebar-1'])) if(!in_array('imoney-links',$ws['sidebar-1'])) echo" selected='selected'";
            //						echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
            //
            //						echo "<option value='1'";
            //						if (count($ws['sidebar-1'])) if (in_array('imoney-links',$ws['sidebar-1'])) echo " selected='selected'";
            //						echo ">".$this->__('Active','iMoney')."</option>\n";
            //
            //						echo "</select>\n";
            //
            //						echo '<label for="">'.$this->__('Widget Active', 'iMoney').'</label>';

            echo "<br/>\n";
            echo "<select name='tnx_pages_enable' id='tnx_enable'>\n";
            echo "<option value='1'";

            if($this->get_option('itex_m_tnx_pages_enable')) echo " selected='selected'";
            echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

            echo "<option value='0'";
            if(!$this->get_option('itex_m_tnx_pages_enable')) echo" selected='selected'";
            echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
            echo "</select>\n";

            echo '<label for="">'.$this->__('Show content links only on Pages and Posts.', 'iMoney').'</label>';

            echo "<br/>\n";
            ?>
        </td>


    </tr>

    <tr>
        <th width="30%" valign="top" style="padding-top: 10px;">
            <label for=""></label>
        </th>
        <td align="center">
            <br/><br/>
            <a target="_blank" href="http://www.tnx.net/?p=119596309">www.tnx.net</a>
            <br/>
            <a target="_blank" href="http://www.tnx.net/?p=119596309"><img border="0" alt="Sell links on every page of your site to thousands of advertisers!" src="http://us1.tnx.net/tnx_468_60.gif" width="468" height="60"></a>
        </td>
    </tr>
    </table>
    <?php
    }

    /**
     * Tnx file installation
     *
     * @return  bool
     */
    function itex_m_tnx_install_file()
    {

        $tnx_php_content = $this->itex_m_datafiles('tnx.php');

        $file = $this->document_root . '/' .'tnxdir_'.md5($this->get_option('itex_m_tnx_tnxuser')).'/tnx.php';

        $dir = dirname($file);
        //print_r($file.' '.$dir );die();
        if (!@mkdir($dir, 0777))
        {
            echo '

		<div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">
				'.$this->__('Can`t create Tnx/Xap dir!', 'iMoney').'
		</div>';
            return 0;
        }
        chmod($dir, 0777);  //byli gluki s mkdir($dir, 0777)
        if (!file_put_contents($file,$tnx_php_content))
        {
            echo '
		<div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">
				'.$this->__('Can`t create xap.php!', 'iMoney').'
		</div>';
            return 0;
        }
        file_put_contents($dir.'/.htaccess',"deny from all\r\n");
        //chmod($file, 0777);
        echo '
		<div style="margin:10px auto; border:3px  #55ff00 solid; background-color:#afa; padding:10px; text-align:center;">
				'.$this->__('Dir and xap.php created!', 'iMoney').'
		</div>';
        //die();
        return 1;
    }

    /**
     * mainlink section admin menu
     *
     */
    function itex_m_admin_mainlink()
    {
        if (isset($_POST['info_update']))
        {
            //phpinfo();die();
            if (isset($_POST['mainlink_mainlinkuser']))
            {
                $this->update_option('itex_m_mainlink_mainlinkuser', trim($_POST['mainlink_mainlinkuser']));
            }
            if (isset($_POST['mainlink_enable']))
            {
                $this->update_option('itex_m_mainlink_enable', intval($_POST['mainlink_enable']));
            }

            if (isset($_POST['mainlink_links_beforecontent']))
            {
                $this->update_option('itex_m_mainlink_links_beforecontent', $_POST['mainlink_links_beforecontent']);
            }

            if (isset($_POST['mainlink_links_aftercontent']))
            {
                $this->update_option('itex_m_mainlink_links_aftercontent', $_POST['mainlink_links_aftercontent']);
            }

            if (isset($_POST['mainlink_links_sidebar']))
            {
                $this->update_option('itex_m_mainlink_links_sidebar', $_POST['mainlink_links_sidebar']);
            }

            if (isset($_POST['mainlink_links_footer']))
            {
                $this->update_option('itex_m_mainlink_links_footer', $_POST['mainlink_links_footer']);
            }

            if (isset($_POST['mainlink_pages_enable']) )
            {
                $this->update_option('itex_m_mainlink_pages_enable', intval($_POST['mainlink_pages_enable']));
            }

            //			if (isset($_POST['mainlink_widget']))
            //			{
            //				$s_w = wp_get_sidebars_widgets();
            //				$ex = 0;
            //				if (count($s_w['sidebar-1'])) foreach ($s_w['sidebar-1'] as $k => $v)
            //				{
            //					if ($v == 'imoney-links')
            //					{
            //						$ex = 1;
            //						if (!$_POST['mainlink_widget']) unset($s_w['sidebar-1'][$k]);
            //					}
            //				}
            //				if (!$ex && $_POST['mainlink_widget']) $s_w['sidebar-1'][] = 'imoney-links';
            //				wp_set_sidebars_widgets( $s_w );
            //
            //			}
            echo "<div class='updated fade'><p><strong>Settings saved.</strong></p></div>";
        }
        if (isset($_POST['mainlink_mainlinkdir_create']))
        {
            if ($this->get_option('itex_m_mainlink_mainlinkuser'))  $this->itex_m_mainlink_install_file();
        }

        $file = $this->document_root . '/mainlink_'.$this->get_option('itex_m_mainlink_mainlinkuser').'/ML.php';
        if ($this->get_option('itex_m_mainlink_mainlinkuser'))
        {
            if (file_exists($file)) {}
            else {?>
            <div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">
                mainlink dir not exist!
            </div>
            <div style="margin:10px auto; border:3px #f00 solid; padding:10px; text-align:center;">
                Create new mainlinkdir and ML.php? (<?php echo $file;?>)
                <p class="submit">
                    <input type='submit' name='mainlink_mainlinkdir_create' value='<?php echo $this->__('Create', 'iMoney'); ?>' />
                </p>
                <?php
                if (!$this->get_option('itex_m_mainlink_mainlinkuser')) echo $this->__('Enter your mainlink UID in this box!', 'iMoney');
                ?>
            </div>

            <?php
            }
        }
        ?>
    <table class="form-table" cellspacing="2" cellpadding="5" width="100%">
    <tr>
        <th valign="top" style="padding-top: 10px;">
            <label for=""><?php echo $this->__('Your mainlink UID:', 'iMoney');?></label>
        </th>
        <td>
            <?php
            echo "<input type='text' size='50' ";
            echo "name='mainlink_mainlinkuser'";
            echo "id='mainlinkuser' ";
            echo "value='".$this->get_option('itex_m_mainlink_mainlinkuser')."' />\n";
            ?>
            <p style="margin: 5px 10px;"><?php
                echo '<a target="_blank" target="_blank" href="http://www.mainlink.ru/?partnerid=42851">'.$this->__('Enter your mainlink UID in this box.', 'iMoney').'</a>';

                ?></p>
        </td>
    </tr>
    <tr>
        <th width="30%" valign="top" style="padding-top: 10px;">
            <label for=""><?php echo $this->__('mainlink links:', 'iMoney');?></label>
        </th>
        <td>
            <?php
            echo "<select name='mainlink_enable' id='mainlink_enable'>\n";
            echo "<option value='1'";

            if($this->get_option('itex_m_mainlink_enable')) echo " selected='selected'";
            echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

            echo "<option value='0'";
            if(!$this->get_option('itex_m_mainlink_enable')) echo" selected='selected'";
            echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
            echo "</select>\n";

            echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
            echo "<br/>\n";

            echo "<select name='mainlink_links_beforecontent' id='mainlink_links_beforecontent'>\n";

            echo "<option value='0'";
            if(!$this->get_option('itex_m_mainlink_links_beforecontent')) echo" selected='selected'";
            echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

            echo "<option value='1'";
            if($this->get_option('itex_m_mainlink_links_beforecontent') == 1) echo " selected='selected'";
            echo ">1</option>\n";

            echo "<option value='2'";
            if($this->get_option('itex_m_mainlink_links_beforecontent') == 2) echo " selected='selected'";
            echo ">2</option>\n";

            echo "<option value='3'";
            if($this->get_option('itex_m_mainlink_links_beforecontent') == 3) echo " selected='selected'";
            echo ">3</option>\n";

            echo "<option value='4'";
            if($this->get_option('itex_m_mainlink_links_beforecontent') == 4) echo " selected='selected'";
            echo ">4</option>\n";

            echo "<option value='5'";
            if($this->get_option('itex_m_mainlink_links_beforecontent') == 5) echo " selected='selected'";
            echo ">5</option>\n";

            echo "</select>\n";

            echo '<label for="">'.$this->__('Before content links', 'iMoney').'</label>';

            echo "<br/>\n";



            echo "<select name='mainlink_links_aftercontent' id='mainlink_links_aftercontent'>\n";

            echo "<option value='0'";
            if(!$this->get_option('itex_m_mainlink_links_aftercontent')) echo" selected='selected'";
            echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

            echo "<option value='1'";
            if($this->get_option('itex_m_mainlink_links_aftercontent') == 1) echo " selected='selected'";
            echo ">1</option>\n";

            echo "<option value='2'";
            if($this->get_option('itex_m_mainlink_links_aftercontent') == 2) echo " selected='selected'";
            echo ">2</option>\n";

            echo "<option value='3'";
            if($this->get_option('itex_m_mainlink_links_aftercontent') == 3) echo " selected='selected'";
            echo ">3</option>\n";

            echo "<option value='4'";
            if($this->get_option('itex_m_mainlink_links_aftercontent') == 4) echo " selected='selected'";
            echo ">4</option>\n";

            echo "<option value='5'";
            if($this->get_option('itex_m_mainlink_links_aftercontent') == 5) echo " selected='selected'";
            echo ">5</option>\n";

            echo "</select>\n";

            echo '<label for="">'.$this->__('After content links', 'iMoney').'</label>';

            echo "<br/>\n";

            echo "<select name='mainlink_links_sidebar' id='mainlink_links_sidebar'>\n";

            echo "<option value='0'";
            if(!$this->get_option('itex_m_mainlink_links_sidebar')) echo" selected='selected'";
            echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

            echo "<option value='1'";
            if($this->get_option('itex_m_mainlink_links_sidebar') == 1) echo " selected='selected'";
            echo ">1</option>\n";

            echo "<option value='2'";
            if($this->get_option('itex_m_mainlink_links_sidebar') == 2) echo " selected='selected'";
            echo ">2</option>\n";

            echo "<option value='3'";
            if($this->get_option('itex_m_mainlink_links_sidebar') == 3) echo " selected='selected'";
            echo ">3</option>\n";

            echo "<option value='4'";
            if($this->get_option('itex_m_mainlink_links_sidebar') == 4) echo " selected='selected'";
            echo ">4</option>\n";

            echo "<option value='5'";
            if($this->get_option('itex_m_mainlink_links_sidebar') == 5) echo " selected='selected'";
            echo ">5</option>\n";

            echo "<option value='max'";
            if($this->get_option('itex_m_mainlink_links_sidebar') == 'max') echo " selected='selected'";
            echo ">".$this->__('Max', 'iMoney')."</option>\n";

            echo "</select>\n";

            echo '<label for="">'.$this->__('Sidebar links', 'iMoney').'</label>';

            echo "<br/>\n";


            echo "<select name='mainlink_links_footer' id='mainlink_links_footer'>\n";
            echo "<option value='0'";
            if(!$this->get_option('itex_m_mainlink_links_footer')) echo" selected='selected'";
            echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

            echo "<option value='1'";
            if($this->get_option('itex_m_mainlink_links_footer') == 1) echo " selected='selected'";
            echo ">1</option>\n";

            echo "<option value='2'";
            if($this->get_option('itex_m_mainlink_links_footer') == 2) echo " selected='selected'";
            echo ">2</option>\n";

            echo "<option value='3'";
            if($this->get_option('itex_m_mainlink_links_footer') == 3) echo " selected='selected'";
            echo ">3</option>\n";

            echo "<option value='4'";
            if($this->get_option('itex_m_mainlink_links_footer') == 4) echo " selected='selected'";
            echo ">4</option>\n";

            echo "<option value='5'";
            if($this->get_option('itex_m_mainlink_links_footer') == 5) echo " selected='selected'";
            echo ">5</option>\n";

            echo "<option value='max'";
            if($this->get_option('itex_m_mainlink_links_footer') == 'max') echo " selected='selected'";
            echo ">".$this->__('Max', 'iMoney')."</option>\n";

            echo "</select>\n";

            echo '<label for="">'.$this->__('Footer links', 'iMoney').'</label>';

            //						echo "<br/>\n";
            //						$ws = wp_get_sidebars_widgets();
            //						echo "<select name='mainlink_widget' id='mainlink_widget'>\n";
            //						echo "<option value='0'";
            //						if (count($ws['sidebar-1'])) if(!in_array('imoney-links',$ws['sidebar-1'])) echo" selected='selected'";
            //						echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
            //
            //						echo "<option value='1'";
            //						if (count($ws['sidebar-1'])) if (in_array('imoney-links',$ws['sidebar-1'])) echo " selected='selected'";
            //						echo ">".$this->__('Active','iMoney')."</option>\n";
            //
            //						echo "</select>\n";
            //
            //
            //						echo '<label for="">'.$this->__('Widget Active', 'iMoney').'</label>';
            //
            //						echo "<br/>\n";
            ?>
        </td>


    </tr>

    <tr>
        <th width="30%" valign="top" style="padding-top: 10px;">
            <label for=""></label>
        </th>
        <td align="center">
            <br/><br/>
            <a target="_blank" target="_blank" href="http://www.mainlink.ru/?partnerid=42851">www.mainlink.ru</a>
            <br/>
            <a target="_blank" target="_blank" href="http://www.mainlink.ru/?partnerid=42851"><img src='http://www.mainlink.ru/i/banner/partners/468x1.gif' alt="www.mainlink.ru!" border='0'></a>
        </td>
    </tr>
    </table>
    <?php
    }

    /**
     * mainlink file installation
     *
     * @return  bool
     */
    function itex_m_mainlink_install_file()
    {
        //if (!defined('SECURE_CODE')) return 0;
        if (!$this->get_option('itex_m_mainlink_mainlinkuser')) return 0;

        $mainlink_php_content = $this->itex_m_datafiles('ML.php');

        $file = $this->document_root . '/mainlink_'.$this->get_option('itex_m_mainlink_mainlinkuser').'/ML.php';
        $dir = dirname($file);
        //print_r($file.' '.$dir );die();
        if (!@mkdir($dir, 0777))
        {
            echo '

		<div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">
				'.$this->__('Can`t create mainlink dir!', 'iMoney').'
		</div>';
            return 0;
        }
        chmod($dir, 0777);  //byli gluki s mkdir($dir, 0777)
        if (!file_put_contents($file,$mainlink_php_content))
        {
            echo '
		<div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">
				'.$this->__('Can`t create ', 'iMoney').'ML.php!
		</div>';
            return 0;
        }
        file_put_contents($dir.'/.htaccess',"deny from all\r\n");
        file_put_contents($dir.'/'.$this->get_option('itex_m_mainlink_mainlinkuser').'.sec',$this->get_option('itex_m_mainlink_mainlinkuser')."\r\n");
        //chmod($file, 0777);
        echo '
		<div style="margin:10px auto; border:3px  #55ff00 solid; background-color:#afa; padding:10px; text-align:center;">
				'.$this->__('Dir and Ml.php created!', 'iMoney').'
		</div>';
        //die();
        return 1;
    }

    /**
     * linkfeed section admin menu
     *
     */
    function itex_m_admin_linkfeed()
    {
        if (isset($_POST['info_update']))
        {
            //phpinfo();die();
            if (isset($_POST['linkfeed_linkfeeduser']) && !empty($_POST['linkfeed_linkfeeduser']))
            {
                $this->update_option('itex_m_linkfeed_linkfeeduser', trim($_POST['linkfeed_linkfeeduser']));
            }
            if (isset($_POST['linkfeed_enable']))
            {
                $this->update_option('itex_m_linkfeed_enable', intval($_POST['linkfeed_enable']));
            }

            if (isset($_POST['linkfeed_links_beforecontent']))
            {
                $this->update_option('itex_m_linkfeed_links_beforecontent', $_POST['linkfeed_links_beforecontent']);
            }

            if (isset($_POST['linkfeed_links_aftercontent']))
            {
                $this->update_option('itex_m_linkfeed_links_aftercontent', $_POST['linkfeed_links_aftercontent']);
            }

            if (isset($_POST['linkfeed_links_sidebar']))
            {
                $this->update_option('itex_m_linkfeed_links_sidebar', $_POST['linkfeed_links_sidebar']);
            }

            if (isset($_POST['linkfeed_links_footer']))
            {
                $this->update_option('itex_m_linkfeed_links_footer', $_POST['linkfeed_links_footer']);
            }

            if (isset($_POST['linkfeed_pages_enable']) )
            {
                $this->update_option('itex_m_linkfeed_pages_enable', intval($_POST['linkfeed_pages_enable']));
            }

            //			if (isset($_POST['linkfeed_widget']))
            //			{
            //				$s_w = wp_get_sidebars_widgets();
            //				$ex = 0;
            //				if (count($s_w['sidebar-1'])) foreach ($s_w['sidebar-1'] as $k => $v)
            //				{
            //					if ($v == 'imoney-links')
            //					{
            //						$ex = 1;
            //						if (!$_POST['linkfeed_widget']) unset($s_w['sidebar-1'][$k]);
            //					}
            //				}
            //				if (!$ex && $_POST['linkfeed_widget']) $s_w['sidebar-1'][] = 'imoney-links';
            //				wp_set_sidebars_widgets( $s_w );
            //
            //			}
            echo "<div class='updated fade'><p><strong>Settings saved.</strong></p></div>";
        }
        if (isset($_POST['linkfeed_linkfeeddir_create']))
        {
            if ($this->get_option('itex_m_linkfeed_linkfeeduser'))  $this->itex_m_linkfeed_install_file();
        }

        $file = $this->document_root . '/linkfeed_'.$this->get_option('itex_m_linkfeed_linkfeeduser').'/linkfeed.php';
        if ($this->get_option('itex_m_linkfeed_linkfeeduser'))
        {
            if (file_exists($file)) {}
            else {?>
            <div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">
                linkfeed dir not exist!
            </div>
            <div style="margin:10px auto; border:3px #f00 solid; padding:10px; text-align:center;">
                Create new linkfeeddir and linkfeed.php? (<?php echo $file;?>)
                <p class="submit">
                    <input type='submit' name='linkfeed_linkfeeddir_create' value='<?php echo $this->__('Create', 'iMoney'); ?>' />
                </p>
                <?php
                if (!$this->get_option('itex_m_linkfeed_linkfeeduser')) echo $this->__('Enter your linkfeed UID in this box!', 'iMoney');
                ?>
            </div>

            <?php
            }
        }
        ?>
    <table class="form-table" cellspacing="2" cellpadding="5" width="100%">
    <tr>
        <th valign="top" style="padding-top: 10px;">
            <label for=""><?php echo $this->__('Your linkfeed UID:', 'iMoney');?></label>
        </th>
        <td>
            <?php
            echo "<input type='text' size='50' ";
            echo "name='linkfeed_linkfeeduser'";
            echo "id='linkfeeduser' ";
            echo "value='".$this->get_option('itex_m_linkfeed_linkfeeduser')."' />\n";
            ?>
            <p style="margin: 5px 10px;"><?php
                echo '<a target="_blank" target="_blank" href="http://www.linkfeed.ru/reg/38317">'.$this->__('Enter your linkfeed UID in this box.', 'iMoney').'</a>';
                ?></p>
        </td>
    </tr>
    <tr>
        <th width="30%" valign="top" style="padding-top: 10px;">
            <label for=""><?php echo $this->__('linkfeed links:', 'iMoney');?></label>
        </th>
        <td>
            <?php
            echo "<select name='linkfeed_enable' id='linkfeed_enable'>\n";
            echo "<option value='1'";

            if($this->get_option('itex_m_linkfeed_enable')) echo " selected='selected'";
            echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

            echo "<option value='0'";
            if(!$this->get_option('itex_m_linkfeed_enable')) echo" selected='selected'";
            echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
            echo "</select>\n";

            echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
            echo "<br/>\n";

            echo "<select name='linkfeed_links_beforecontent' id='linkfeed_links_beforecontent'>\n";

            echo "<option value='0'";
            if(!$this->get_option('itex_m_linkfeed_links_beforecontent')) echo" selected='selected'";
            echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

            echo "<option value='1'";
            if($this->get_option('itex_m_linkfeed_links_beforecontent') == 1) echo " selected='selected'";
            echo ">1</option>\n";

            echo "<option value='2'";
            if($this->get_option('itex_m_linkfeed_links_beforecontent') == 2) echo " selected='selected'";
            echo ">2</option>\n";

            echo "<option value='3'";
            if($this->get_option('itex_m_linkfeed_links_beforecontent') == 3) echo " selected='selected'";
            echo ">3</option>\n";

            echo "<option value='4'";
            if($this->get_option('itex_m_linkfeed_links_beforecontent') == 4) echo " selected='selected'";
            echo ">4</option>\n";

            echo "<option value='5'";
            if($this->get_option('itex_m_linkfeed_links_beforecontent') == 5) echo " selected='selected'";
            echo ">5</option>\n";

            echo "</select>\n";

            echo '<label for="">'.$this->__('Before content links', 'iMoney').'</label>';

            echo "<br/>\n";



            echo "<select name='linkfeed_links_aftercontent' id='linkfeed_links_aftercontent'>\n";

            echo "<option value='0'";
            if(!$this->get_option('itex_m_linkfeed_links_aftercontent')) echo" selected='selected'";
            echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

            echo "<option value='1'";
            if($this->get_option('itex_m_linkfeed_links_aftercontent') == 1) echo " selected='selected'";
            echo ">1</option>\n";

            echo "<option value='2'";
            if($this->get_option('itex_m_linkfeed_links_aftercontent') == 2) echo " selected='selected'";
            echo ">2</option>\n";

            echo "<option value='3'";
            if($this->get_option('itex_m_linkfeed_links_aftercontent') == 3) echo " selected='selected'";
            echo ">3</option>\n";

            echo "<option value='4'";
            if($this->get_option('itex_m_linkfeed_links_aftercontent') == 4) echo " selected='selected'";
            echo ">4</option>\n";

            echo "<option value='5'";
            if($this->get_option('itex_m_linkfeed_links_aftercontent') == 5) echo " selected='selected'";
            echo ">5</option>\n";

            echo "</select>\n";

            echo '<label for="">'.$this->__('After content links', 'iMoney').'</label>';

            echo "<br/>\n";

            echo "<select name='linkfeed_links_sidebar' id='linkfeed_links_sidebar'>\n";

            echo "<option value='0'";
            if(!$this->get_option('itex_m_linkfeed_links_sidebar')) echo" selected='selected'";
            echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

            echo "<option value='1'";
            if($this->get_option('itex_m_linkfeed_links_sidebar') == 1) echo " selected='selected'";
            echo ">1</option>\n";

            echo "<option value='2'";
            if($this->get_option('itex_m_linkfeed_links_sidebar') == 2) echo " selected='selected'";
            echo ">2</option>\n";

            echo "<option value='3'";
            if($this->get_option('itex_m_linkfeed_links_sidebar') == 3) echo " selected='selected'";
            echo ">3</option>\n";

            echo "<option value='4'";
            if($this->get_option('itex_m_linkfeed_links_sidebar') == 4) echo " selected='selected'";
            echo ">4</option>\n";

            echo "<option value='5'";
            if($this->get_option('itex_m_linkfeed_links_sidebar') == 5) echo " selected='selected'";
            echo ">5</option>\n";

            echo "<option value='max'";
            if($this->get_option('itex_m_linkfeed_links_sidebar') == 'max') echo " selected='selected'";
            echo ">".$this->__('Max', 'iMoney')."</option>\n";

            echo "</select>\n";

            echo '<label for="">'.$this->__('Sidebar links', 'iMoney').'</label>';

            echo "<br/>\n";


            echo "<select name='linkfeed_links_footer' id='linkfeed_links_footer'>\n";
            echo "<option value='0'";
            if(!$this->get_option('itex_m_linkfeed_links_footer')) echo" selected='selected'";
            echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

            echo "<option value='1'";
            if($this->get_option('itex_m_linkfeed_links_footer') == 1) echo " selected='selected'";
            echo ">1</option>\n";

            echo "<option value='2'";
            if($this->get_option('itex_m_linkfeed_links_footer') == 2) echo " selected='selected'";
            echo ">2</option>\n";

            echo "<option value='3'";
            if($this->get_option('itex_m_linkfeed_links_footer') == 3) echo " selected='selected'";
            echo ">3</option>\n";

            echo "<option value='4'";
            if($this->get_option('itex_m_linkfeed_links_footer') == 4) echo " selected='selected'";
            echo ">4</option>\n";

            echo "<option value='5'";
            if($this->get_option('itex_m_linkfeed_links_footer') == 5) echo " selected='selected'";
            echo ">5</option>\n";

            echo "<option value='max'";
            if($this->get_option('itex_m_linkfeed_links_footer') == 'max') echo " selected='selected'";
            echo ">".$this->__('Max', 'iMoney')."</option>\n";

            echo "</select>\n";

            echo '<label for="">'.$this->__('Footer links', 'iMoney').'</label>';

            //						echo "<br/>\n";
            //						$ws = wp_get_sidebars_widgets();
            //						echo "<select name='linkfeed_widget' id='linkfeed_widget'>\n";
            //						echo "<option value='0'";
            //						if (count($ws['sidebar-1'])) if(!in_array('imoney-links',$ws['sidebar-1'])) echo" selected='selected'";
            //						echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
            //
            //						echo "<option value='1'";
            //						if (count($ws['sidebar-1'])) if (in_array('imoney-links',$ws['sidebar-1'])) echo " selected='selected'";
            //						echo ">".$this->__('Active','iMoney')."</option>\n";
            //
            //						echo "</select>\n";
            //
            //
            //						echo '<label for="">'.$this->__('Widget Active', 'iMoney').'</label>';
            //
            //						echo "<br/>\n";
            ?>
        </td>


    </tr>

    <tr>
        <th width="30%" valign="top" style="padding-top: 10px;">
            <label for=""></label>
        </th>
        <td align="center">
            <br/><br/>
            <a target="_blank" target="_blank" href="http://www.linkfeed.ru/reg/38317">www.linkfeed.ru</a>
            <br/>
            <a target="_blank" target="_blank" href="http://www.linkfeed.ru/reg/38317"><img src="http://www.linkfeed.ru/banners/468x60_linkfeed.gif" alt="www.linkfeed.ru!"></a>
        </td>
    </tr>
    </table>
    <?php
    }

    /**
     * linkfeed file installation
     *
     * @return  bool
     */
    function itex_m_linkfeed_install_file()
    {
        //if (!defined('SECURE_CODE')) return 0;
        if (!$this->get_option('itex_m_linkfeed_linkfeeduser')) return 0;

        $linkfeed_php_content = $this->itex_m_datafiles('linkfeed.php');
        if (strlen($linkfeed_php_content)<10)
        {
            echo '
		<div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">
				'.$this->__('Can`t read datafile', 'iMoney').'
		</div>';
            return 0;
        }

        $file = $this->document_root . '/linkfeed_'.$this->get_option('itex_m_linkfeed_linkfeeduser').'/linkfeed.php';
        $dir = dirname($file);
        //print_r($file.' '.$dir );die();
        if (!@mkdir($dir, 0777))
        {
            echo '

		<div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">
				'.$this->__('Can`t create linkfeed dir!', 'iMoney').'
		</div>';
            return 0;
        }
        chmod($dir, 0777);  //byli gluki s mkdir($dir, 0777)
        if (!file_put_contents($file,$linkfeed_php_content))
        {
            echo '
		<div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">
				'.$this->__('Can`t create ', 'iMoney').'linkfeed.php!
		</div>';
            return 0;
        }
        file_put_contents($dir.'/.htaccess',"deny from all\r\n");
        echo '
		<div style="margin:10px auto; border:3px  #55ff00 solid; background-color:#afa; padding:10px; text-align:center;">
				'.$this->__('Dir and linkfeed.php created!', 'iMoney').'
		</div>';
        //die();
        return 1;
    }



    /**
     * SetLinks section admin menu
     * Author Zya
     *
     */
    function itex_m_admin_setlinks()
    {
        if (isset($_POST['info_update']))
        {
            //phpinfo();die();
            if (isset($_POST['setlinks_setlinksuser']))
            {
                $this->update_option('itex_m_setlinks_setlinksuser', trim($_POST['setlinks_setlinksuser']));
            }
            if (isset($_POST['setlinks_enable']))
            {
                $this->update_option('itex_m_setlinks_enable', intval($_POST['setlinks_enable']));
            }

            if (isset($_POST['setlinks_links_beforecontent']))
            {
                $this->update_option('itex_m_setlinks_links_beforecontent', $_POST['setlinks_links_beforecontent']);
            }

            if (isset($_POST['setlinks_links_aftercontent']))
            {
                $this->update_option('itex_m_setlinks_links_aftercontent', $_POST['setlinks_links_aftercontent']);
            }

            if (isset($_POST['setlinks_links_sidebar']))
            {
                $this->update_option('itex_m_setlinks_links_sidebar', $_POST['setlinks_links_sidebar']);
            }

            if (isset($_POST['setlinks_links_footer']))
            {
                $this->update_option('itex_m_setlinks_links_footer', $_POST['setlinks_links_footer']);
            }



            if (isset($_POST['setlinks_setlinkscontext_enable']) )
            {
                $this->update_option('itex_m_setlinks_setlinkscontext_enable', intval($_POST['setlinks_setlinkscontext_enable']));
            }

            if (isset($_POST['setlinks_setlinkscontext_pages_enable']) )
            {
                $this->update_option('itex_m_setlinks_setlinkscontext_pages_enable', intval($_POST['setlinks_setlinkscontext_pages_enable']));
            }

            if (isset($_POST['setlinks_pages_enable']) )
            {
                $this->update_option('itex_m_setlinks_pages_enable', intval($_POST['setlinks_pages_enable']));
            }


            //			if ((isset($_POST['setlinks_widget'])) || (isset($_POST['itex_m_setlinks_visual_widget'])))
            //			{
            //				$s_w = wp_get_sidebars_widgets();
            //				$ex = 0;
            //				if (count($s_w['sidebar-1'])) foreach ($s_w['sidebar-1'] as $k => $v)
            //				{
            //					if ($v == 'imoney-links')
            //					{
            //						$ex = 1;
            //						if (!$_POST['setlinks_widget']) unset($s_w['sidebar-1'][$k]);
            //					}
            //				}
            //				if (!$ex && $_POST['setlinks_widget']) $s_w['sidebar-1'][] = 'imoney-links';
            //				wp_set_sidebars_widgets( $s_w );
            //
            //			}
            echo "<div class='updated fade'><p><strong>Settings saved.</strong></p></div>";
        }

        if ($this->get_option('itex_m_setlinks_setlinksuser'))
        {
            $file = $this->document_root . '/setlinks_' . _setlinks_USER . '/slsimple.php'; //<< Not working in multihosting.
            if (file_exists($file)) {}
            else
            {
                ?>
            <div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">
                SetLinks dir not exist!
            </div>

            <?php
            }
        }
        ?>
    <table class="form-table" cellspacing="2" cellpadding="5" width="100%">
    <tr>
        <th valign="top" style="padding-top: 10px;">
            <label for=""><?php echo $this->__('Your SETLINKS UID:', 'iMoney');?></label>
        </th>
        <td>
            <?php
            echo "<input type='text' size='50' ";
            echo "name='setlinks_setlinksuser'";
            echo "id='setlinksuser' ";
            echo "value='".$this->get_option('itex_m_setlinks_setlinksuser')."' />\n";
            ?>
            <p style="margin: 5px 10px;"><?php
                echo '<a target="_blank" href="http://www.setlinks.ru/?pid=72567">'.$this->__('Enter your SETLINKS UID in this box.', 'iMoney').'</a>';

                ?></p>
        </td>
    </tr>
    <tr>
        <th width="30%" valign="top" style="padding-top: 10px;">
            <label for=""><?php echo $this->__('SETLINKS links:', 'iMoney');?></label>
        </th>
        <td>
            <?php
            echo "<select name='setlinks_enable' id='setlinks_enable'>\n";
            echo "<option value='1'";

            if($this->get_option('itex_m_setlinks_enable')) echo " selected='selected'";
            echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

            echo "<option value='0'";
            if(!$this->get_option('itex_m_setlinks_enable')) echo" selected='selected'";
            echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
            echo "</select>\n";

            echo '<label for="">'.$this->__("Working", 'iMoney').'</label>';
            echo "<br/>\n";

            echo "<select name='setlinks_links_beforecontent' id='setlinks_links_beforecontent'>\n";

            echo "<option value='0'";
            if(!$this->get_option('itex_m_setlinks_links_beforecontent')) echo" selected='selected'";
            echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

            echo "<option value='1'";
            if($this->get_option('itex_m_setlinks_links_beforecontent') == 1) echo " selected='selected'";
            echo ">1</option>\n";

            echo "<option value='2'";
            if($this->get_option('itex_m_setlinks_links_beforecontent') == 2) echo " selected='selected'";
            echo ">2</option>\n";

            echo "<option value='3'";
            if($this->get_option('itex_m_setlinks_links_beforecontent') == 3) echo " selected='selected'";
            echo ">3</option>\n";

            echo "<option value='4'";
            if($this->get_option('itex_m_setlinks_links_beforecontent') == 4) echo " selected='selected'";
            echo ">4</option>\n";

            echo "<option value='5'";
            if($this->get_option('itex_m_setlinks_links_beforecontent') == 5) echo " selected='selected'";
            echo ">5</option>\n";

            echo "</select>\n";

            echo '<label for="">'.$this->__('Before content links', 'iMoney').'</label>';

            echo "<br/>\n";



            echo "<select name='setlinks_links_aftercontent' id='setlinks_links_aftercontent'>\n";

            echo "<option value='0'";
            if(!$this->get_option('itex_m_setlinks_links_aftercontent')) echo" selected='selected'";
            echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

            echo "<option value='1'";
            if($this->get_option('itex_m_setlinks_links_aftercontent') == 1) echo " selected='selected'";
            echo ">1</option>\n";

            echo "<option value='2'";
            if($this->get_option('itex_m_setlinks_links_aftercontent') == 2) echo " selected='selected'";
            echo ">2</option>\n";

            echo "<option value='3'";
            if($this->get_option('itex_m_setlinks_links_aftercontent') == 3) echo " selected='selected'";
            echo ">3</option>\n";

            echo "<option value='4'";
            if($this->get_option('itex_m_setlinks_links_aftercontent') == 4) echo " selected='selected'";
            echo ">4</option>\n";

            echo "<option value='5'";
            if($this->get_option('itex_m_setlinks_links_aftercontent') == 5) echo " selected='selected'";
            echo ">5</option>\n";

            echo "</select>\n";

            echo '<label for="">'.$this->__('After content links', 'iMoney').'</label>';

            echo "<br/>\n";

            echo "<select name='setlinks_links_sidebar' id='setlinks_links_sidebar'>\n";

            echo "<option value='0'";
            if(!$this->get_option('itex_m_setlinks_links_sidebar')) echo" selected='selected'";
            echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

            echo "<option value='1'";
            if($this->get_option('itex_m_setlinks_links_sidebar') == 1) echo " selected='selected'";
            echo ">1</option>\n";

            echo "<option value='2'";
            if($this->get_option('itex_m_setlinks_links_sidebar') == 2) echo " selected='selected'";
            echo ">2</option>\n";

            echo "<option value='3'";
            if($this->get_option('itex_m_setlinks_links_sidebar') == 3) echo " selected='selected'";
            echo ">3</option>\n";

            echo "<option value='4'";
            if($this->get_option('itex_m_setlinks_links_sidebar') == 4) echo " selected='selected'";
            echo ">4</option>\n";

            echo "<option value='5'";
            if($this->get_option('itex_m_setlinks_links_sidebar') == 5) echo " selected='selected'";
            echo ">5</option>\n";

            echo "<option value='max'";
            if($this->get_option('itex_m_setlinks_links_sidebar') == 'max') echo " selected='selected'";
            echo ">".$this->__('Max', 'iMoney')."</option>\n";

            echo "</select>\n";

            echo '<label for="">'.$this->__('Sidebar links', 'iMoney').'</label>';

            echo "<br/>\n";


            echo "<select name='setlinks_links_footer' id='setlinks_links_footer'>\n";
            echo "<option value='0'";
            if(!$this->get_option('itex_m_setlinks_links_footer')) echo" selected='selected'";
            echo ">".$this->__("Disabled", 'iMoney')."</option>\n";

            echo "<option value='1'";
            if($this->get_option('itex_m_setlinks_links_footer') == 1) echo " selected='selected'";
            echo ">1</option>\n";

            echo "<option value='2'";
            if($this->get_option('itex_m_setlinks_links_footer') == 2) echo " selected='selected'";
            echo ">2</option>\n";

            echo "<option value='3'";
            if($this->get_option('itex_m_setlinks_links_footer') == 3) echo " selected='selected'";
            echo ">3</option>\n";

            echo "<option value='4'";
            if($this->get_option('itex_m_setlinks_links_footer') == 4) echo " selected='selected'";
            echo ">4</option>\n";

            echo "<option value='5'";
            if($this->get_option('itex_m_setlinks_links_footer') == 5) echo " selected='selected'";
            echo ">5</option>\n";

            echo "<option value='max'";
            if($this->get_option('itex_m_setlinks_links_footer') == 'max') echo " selected='selected'";
            echo ">".$this->__('Max', 'iMoney')."</option>\n";

            echo "</select>\n";

            echo '<label for="">'.$this->__('Footer links', 'iMoney').'</label>';

            echo "<br/>\n";
            echo "<select name='setlinks_pages_enable' id='setlinks_enable'>\n";
            echo "<option value='1'";

            if($this->get_option('itex_m_setlinks_pages_enable')) echo " selected='selected'";
            echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

            echo "<option value='0'";
            if(!$this->get_option('itex_m_setlinks_pages_enable')) echo" selected='selected'";
            echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
            echo "</select>\n";

            echo '<label for="">'.$this->__('Show content links only on Pages and Posts.', 'iMoney').'</label>';

            echo "<br/>\n";
            ?>
        </td>


    </tr>
        <?php
        ?>
    <tr>
        <th width="30%" valign="top" style="padding-top: 10px;">
            <label for=""><?php echo $this->__('SETLINKS context:', 'iMoney'); ?></label>
        </th>
        <td>
            <?php
            echo "<select name='setlinks_setlinkscontext_enable' id='setlinks_enable'>\n";
            echo "<option value='1'";

            if($this->get_option('itex_m_setlinks_setlinkscontext_enable')) echo " selected='selected'";
            echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

            echo "<option value='0'";
            if(!$this->get_option('itex_m_setlinks_setlinkscontext_enable')) echo" selected='selected'";
            echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
            echo "</select>\n";

            echo '<label for="">'.$this->__('Context', 'iMoney').'</label>';

            echo "<br/>\n";

            echo "<select name='setlinks_setlinkscontext_pages_enable' id='setlinks_enable'>\n";
            echo "<option value='1'";

            if($this->get_option('itex_m_setlinks_setlinkscontext_pages_enable')) echo " selected='selected'";
            echo ">".$this->__("Enabled", 'iMoney')."</option>\n";

            echo "<option value='0'";
            if(!$this->get_option('itex_m_setlinks_setlinkscontext_pages_enable')) echo" selected='selected'";
            echo ">".$this->__("Disabled", 'iMoney')."</option>\n";
            echo "</select>\n";

            echo '<label for="">'.$this->__('Show context only on Pages and Posts.', 'iMoney').'</label>';

            echo "<br/>\n";
            ?>
        </td>
    </tr>



    <tr>
        <th width="30%" valign="top" style="padding-top: 10px;">
            <label for=""></label>
        </th>
        <td align="center">
            <br/><br/>
            <a target="_blank" href="http://www.setlinks.ru/?pid=72567">www.setlinks.ru</a>
            <br/>
            <a target="_blank" href="http://www.setlinks.ru/?pid=72567"><img src="http://vip.setlinks.ru/images/38.gif" alt="www.setlinks.ru!" border="0" /></a>
        </td>
    </tr>
    </table>
    <?php

    }

    function itex_m_datafiles($filename)
    {
        $delimiter_1 = 'itex_imoney_datafiles_delimiter_1';
        $delimiter_2 = 'itex_imoney_datafiles_delimiter_2';
        $data = array();
        if ($file = file_get_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.'itex_imoney_datafiles.php'))
        {
            //
            $file = substr($file,16, strlen($file));
            $file = explode($delimiter_1,$file);
            foreach ($file as $v)
            {
                $v = trim($v);
                if (empty($v)) continue;
                $v = explode($delimiter_2,$v,3);
                $data[$v[1]] = $v[2];
            }
        }
        else return false;
        if (isset($data[$filename]))
        {
            $ret = $data[$filename];
            return $ret;
        }
        return false;

    }

}

if (function_exists(add_action)) $itex_money = & new itex_money();

?>