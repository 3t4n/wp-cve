<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core\FB\Actions;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Libs\Registry;

class ActionsBase
{
    use ActionsTrait;
    
    /**
     * All extra actions that will run on the page.
     * 
     * @var  array
     */
    protected $actions;

    /**
     * Currently manipulating action item
     * 
     * @var  object
     */
    protected $item;

    public function __construct($classes = null)
    {
        $this->actions = $classes;

        // renders the Actions per box
        add_action('firebox/box/before_render', [$this, 'onFireBoxBeforeRender']);
    }

    /**
     * The BeforeRender event fires before the box's layout is ready.
     *
     * @param   object  $box  The box's settings object
     *
     * @return  void
     */
    public function onFireBoxBeforeRender($box)
    {
        if (!isset($box->ID))
        {
            return;
        }
        
        $js = '';

        // get actions
        $this->getActions($js);

        // output JS
        $this->outputActions($js, $box->ID);
    }

    /**
     * Append the box actions
     * 
     * @param   string   $js
     * 
     * @return  void
     */
    private function getActions(&$js)
    {
        if (!$this->actions)
        {
            return;
        }

        foreach ($this->actions as $action_item)
        {
            if (!$actions = $action_item->get_actions())
            {
                continue;
            }

            foreach ($actions as $action)
            {
                $this->item = $action;

                $action = new Registry($action);

                // Make sure the action is enabled
                if (!$action->get('enabled', true))
                {
                    continue;
                }
    
                // Validate we have a valid event type
                if (!$action->get('when'))
                {
                    continue;
                }

                // prepare delay
                if (isset($this->item['delay']))
                {
                    if (!$this->item['delay'])
                    {
                        $this->item['delay'] = 0;
                    }
                    
                    $this->item['delay'] *= 1000;
                }
    
                $data = [
                    'when' => $action->get('when'),
                    'action' => $action->get('action', $this->get_default_action())
                ];
    
                $js .= $this->prepare_action_output($data);
            }

            // clear action item
            $action_item->clear();
        }
    }

    /**
     * Tries to generate the action given the current action payload
     * 
     * @return  string
     */
    protected function get_default_action()
    {
        if (!isset($this->item['do']))
        {
            return;
        }
        
        // Validate action does exist
        $actionMethod = '_' . $this->item['do'];
        if (!method_exists($this, $actionMethod))
        {
            return;
        }

        return $this->$actionMethod();
    }

    /**
     * Prepares the action output
     * 
     * @param   array   $action
     * 
     * @return  string
     */
    private function prepare_action_output($action)
    {
        $action = new Registry($action);
        
        $when = $action->get('when');
        $action_script = $action->get('action');
        $wrap_result = $action->get('wrap_result', true);

        // Wrap the code with the event listener block
        $action_script = $wrap_result ? 'me.on("' . esc_html($when) . '", function() { ' . $action_script . ' });' : $action_script;

        // Anonymise code block
        return $this->anonymise($action_script);
    }

    /**
     * Outputs the final box actions javascript
     * 
     * @param   string   $js
     * @param   integer  $box_id
     * 
     * @return  void
     */
    private function outputActions($js, $box_id)
    {
        if (empty($js))
        {
            return;
        }

        $js = '
            <!-- FireBox #' . esc_html($box_id) . ' Actions Start -->
            ' . $this->anonymise(' 
                if (!FireBox) {
                    return;
                }

                FireBox.onReady(function() {
                    var me = FireBox.getInstance(' . esc_html($box_id) . ');
                    
                    if (!me) {
                        return;
                    }

                    ' . $js . '
                });
            ') . '
            <!-- FireBox #' . esc_html($box_id) . ' Actions End -->
        ';

        add_action('wp_enqueue_scripts', function() use ($js) {
            wp_register_script('firebox-actions', false, ['firebox']);
            wp_enqueue_script('firebox-actions');
            wp_add_inline_script('firebox-actions', $js);
        });
    }
}