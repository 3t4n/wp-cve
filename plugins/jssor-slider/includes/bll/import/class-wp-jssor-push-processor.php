<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

/**
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WP_Jssor_Push_Processor
{
    private $rate = 0;
    private $inc_step = 1;

    /**
     * undocumented function
     *
     * @return void
     */
    public function __construct($context)
    {
        $context = array_merge(array(
            'rate' => 0,
            'inc_step' => 1,
            'sleep' => true
        ), $context);
        $this->jssor_push = $context['jssor_push'];
        $this->rate = $context['rate'];
        $this->inc_step = $context['inc_step'];
        $this->sleep = $context['sleep'];
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function set_rate($rate)
    {
        $this->rate = $rate;
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function get_rate()
    {
        return $this->rate;
    }


    /**
     * undocumented function
     *
     * @return void
     */
    public function set_inc_step($step = 1)
    {
        $this->inc_step = $step;
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function get_inc_step()
    {
        return $this->inc_step;
    }


    /**
     * undocumented function
     *
     * @return void
     */
    public function arrive_at($rate, $process_name, $additional_msg)
    {
        $this->set_rate($rate);
        $this->_set_process($process_name, $additional_msg);
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function increase($process_name, $additional_msg, $max_rate = 0, $inc_step = false)
    {
        $rate = $this->get_rate();

        if (!empty($inc_step) && is_numeric($inc_step)) {
            $this->set_inc_step($inc_step);
        }

        $rate = $rate + $this->get_inc_step();

        if (!empty($max_rate)) {
            $rate = $rate > $max_rate ? $max_rate : $rate;
        }

        $this->set_rate($rate);
        $this->_set_process($process_name, $additional_msg);
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function set_sleep($need_sleep)
    {
        $this->sleep = $need_sleep;
    }

    private function _set_process($process_name, $additional_msg)
    {
        $rate = $this->get_rate();
        $rate = $rate * 1.0 / 100;
        $this->jssor_push->push('progress', array($process_name, $rate, $additional_msg));
        if ($this->sleep) {
            //sleep(1);
        }
    }
}
