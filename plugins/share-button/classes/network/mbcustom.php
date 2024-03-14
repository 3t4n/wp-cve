<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

use MaxButtons\maxButton as maxButton;

/** MaxButtons Custom Button **/
class MBCustomNetwork extends MBnetwork
{

  protected $network = 'maxbutton';
//  protected $icon;
  protected $icon_type = 'png';
  protected $priority = 'readmore';
  protected $color = '#88C5C2';

  //protected $popup = false;

  protected $last_index = -1;
  protected $currentNetwork = null;

  protected $css = array();

  protected $is_popup = false;

  protected $is_limitedpro = true;
  protected $is_editable = false;
  protected $is_custom = true; // maybe should be gone.


  public function __construct()
  {
    $this->icon = MBSocial()->get_plugin_url() . 'images/mb-blue-network.png';
    $this->label = __('No config', 'mbsocial');
    parent::__construct();

    $w = MBSocial()->whistle();
    $w->listen('collection/new', array($this, 'resetIndex'), 'tell');
    add_filter('mbsocial/parsecss/', array($this, 'fixIconCSS'));

  }

  /** Reset the count index when a new collection is put up */
  public function resetIndex()
  {
    $this->last_index = -1;
  }

  public function get($name)
  {

      if (! is_null($this->currentNetwork))
      {
        return $this->currentNetwork->get($name);
      }
      else {
         return parent::get($name);
      }
  }



  public function is_popup()
  {
    if (! is_null($this->currentNetwork))
    {
      return $this->currentNetwork->is_popup();
    }
    else {
       return parent::is_popup();
    }
  }

  public function createButton($args = array())
  {
    $defaults = array('link' => 'javascript:void(0)',
          'preview' => false,
          'index' => -1,
          'name' => '',
          'data' => array(),
      );

    $this->currentNetwork = null;

    $args = wp_parse_args($args, $defaults);
    $data = $args['data'];

    if (isset($data['network']) && isset($data['network']['mbcustom']))
    {
        $customs = $data['network']['mbcustom'];

        $this->last_index++;
        $settings = isset($customs[$this->last_index]) ? $customs[$this->last_index] : false;

        $button_id = isset($settings['button_id']) ? $settings['button_id'] : false;

        $mode = 'normal';
        if ($args['preview'])
          $mode = 'preview';

        if($button_id > 0)
        {
          $button_args = array(
              'echo' => false,
              'load_css' => 'inline',
              'compile' => true,
              'mode' => $mode,
          );

          $use = isset($settings['use_network']) ? $settings['use_network'] : false;
          $network = isset($settings['network']) ? $settings['network'] : false;
          $url = isset($settings['url']) ? $settings['url'] : false;
          $text = isset($settings['text']) ? $settings['text'] : false;

          $button = MB()->getClass("button");
          $button->set($button_id);

           if ($url != '')
           {
              $button->setData('basic', array('url' => $url));
           }
           if ($text != '')
           {
              $button->setData('text', array('text' => $text));
           }

          $button_html = "<span class='mb-item item-" . $args['index']  . "'>";
          $button_html .=  $button->display($button_args);
          $button_html .= "</span>";
          $buttonObj = \MaxButtons\str_get_html($button_html);

          // fix URL *after* button output since MB filters {url} type of URL's needed for ApplyVars
          if ($use && $network)
          {
              $networkObj = MBSocial()->networks()->get($network);
              $this->currentNetwork = $networkObj;
              $url = $networkObj->get_url();

              $itemObj = $buttonObj->find('a', 0);
              $itemObj->href = $url;

          }

          return $buttonObj;

        }
    }

    if ($args['preview'])
    {
      return parent::createButton($args);
    }

    return false;
  }

  public function fixIconCSS($css)
  {
    $w = MBSocial()->whistle();
    $network = $w->ask('display/parse/network');

    $classname = get_class($network); // hacky
    if (strpos(strtolower($classname), 'mbcustom') !== false )
    {
        if (isset($css['mb-icon']))
        {
          unset($css['mb-icon']); // overrides mb icons, should not happen
        }
    }

    return $css;

  }
}
