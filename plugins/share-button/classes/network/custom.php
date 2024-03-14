<?php
namespace MBSocial;

/** Remote Custom Network Class */
class customNetwork extends mbNetwork
{

  protected $priority = 'readmore';
  protected $is_native = false;

  /** Function to set default for custom networks to the data that was imported from remote.
  *
  * Start state of custom network is all these defaults, but no save on the main settings branch
  */
  public function get_all_defaults()
  {
     $network = MBSocial()->networks()->getNetworkSettings();

     if (isset($network['custom'][$this->network]))
     {

       $settings = $network['custom'][$this->network];

       $all_options = $this->get_all_options();

       foreach($all_options as $option => $value)
       {
         if (isset($settings[$option]))
         {
           $all_options[$option] = $settings[$option];
         }
       }
     }
     return $all_options;
  }

  public function load_settings($settings)
  {
     parent::load_settings($settings);

     if (isset($settings['name']))
     {
       $this->network = $settings['name'];
     }
  }
}
