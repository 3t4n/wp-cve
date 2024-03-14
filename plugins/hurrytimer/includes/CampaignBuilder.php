<?php

namespace Hurrytimer;

use Hurrytimer\Dependencies\Carbon\Carbon;
use Exception;
use Hurrytimer\Placeholders\Placeholder_Factory;

class CampaignBuilder
{
    use CampaignBuilderLegacy;

    /**
     * Build campaign Template.
     *
     * @return string
     */

    /**
     * @var \Hurrytimer\Campaign
     */
    protected $campaign;

    public function __construct( $campaign )
    {
        $this->campaign = $campaign;
        $this->campaign->loadSettings();
    }

    /**
     * Returns built template.
     *
     * @param string
     * @param boolean
     *
     * @return string
     */
    public function build( $content = '', $options = [] )
    {
        $config      = $this->getClientConfig( $options );
        $json        = htmlspecialchars( json_encode( $config ), ENT_QUOTES, 'UTF-8' );
        $legacyClass = $this->legacyCampaignClass( $this->campaign->get_id() );

        if ( $this->campaign->enableSticky === C::YES && $options[ 'sticky' ] ) {

            return '<div class="hurrytimer-sticky hurryt-loading hurrytimer-sticky-' . $this->campaign->get_id() . '"><div class="hurrytimer-sticky-inner"><div class="' . $legacyClass . ' hurrytimer-campaign hurrytimer-campaign-' . $this->campaign->get_id() . '" data-config="' . $json . '" >' . $content . '</div></div>' . $this->stickyBarCloseButton()
                . '</div>';

        }

        return '<div class="' . $legacyClass . ' hurrytimer-campaign hurryt-loading hurrytimer-campaign-' . $this->campaign->get_id() . '"'
            . ' data-config="' . $json . '" >' . $content . '</div>';
    }

    /**
     * The timer elements template.
     */
    public function template()
    {

        return ( $this->campaign->headlinePosition == C::HEADLINE_POSITION_ABOVE_TIMER
                ? $this->headline()
                : '' )
            . '<div class="' . $this->legacyTimerClass() . ' hurrytimer-timer"></div>'
            . ( $this->campaign->headlinePosition == C::HEADLINE_POSITION_BELOW_TIMER
                ? $this->headline() : '' )
            . $this->callToActionButton();

    }

    /**
     * Returns common client config.
     *
     * @return array
     */
    private function commonClientConfig()
    {

        $actions = $this->campaign->actions;
        foreach ( $actions as &$action ) {
          
            $foceLineBreaks = apply_filters('hurryt_action_message_force_line_breaks', false);
            $rawMessage = $action['message'];
            
             if(!preg_match('#\<br(\s*)?\/?|<script|<style\>#i', $action['message'] ) || $foceLineBreaks ){
                $action[ 'message' ] =  nl2br( $action[ 'message' ]);
            }

            $action[ 'message' ] =  do_shortcode( $action[ 'message' ] );

            $action['message'] = apply_filters('hurryt_action_message', $action[ 'message' ],  $this->campaign->get_id(), $rawMessage);

            $action['coupon'] = apply_filters('hurryt_action_coupon', $action['coupon'], $this->campaign->get_id());
            $action['redirectUrl'] = apply_filters('hurryt_action_redirect_url', $action['redirectUrl'], $this->campaign->get_id());

        }

        return [
            'id'                      => $this->campaign->get_id(),
            'product_ids'=> $this->campaign->getWcProductsSelection(),
            'actions'                 => $actions,
            'template'                => $this->timer(),
            'methods'                 => $this->campaign->detectionMethods,
            'mode'                    => $this->campaign->get_mode_slug(),
            'sticky_bar_hide_timeout' => apply_filters( 'sticky_bar_hide_timeout',
                intval( $this->campaign->stickyBarDismissTimeout ), $this->campaign->get_id() ),
        ];
    }

    private function evergreenClientConfig()
    {
        $evergreenCompaign = new EvergreenCampaign( $this->campaign->get_id() );
        $evergreenCompaign->loadSettings();
        return [
            'isRegular'    => false,
            'restart_duration'=> $this->campaign->getRestartDuration(true),
            'duration'     => $this->campaign->durationInSeconds(),
            'should_reset' => $evergreenCompaign->shouldResetTimer(),
            'reset_token'  => $evergreenCompaign->getInitiatedResetToken(),
            'restart'      => apply_filters( 'hurryt_evergreen_restart', $this->campaign->getRestart() ),
            'endDate'      => $evergreenCompaign->getEndDate(),
            'cookieName'   => Cookie_Detection::cookieName( $this->campaign->get_id() ),
            'reload_reset' => $evergreenCompaign->reloadReset,
        ];

    }

    /**
     * Returns regular config.
     *
     * @return array
     */
    private function regularClientConfig()
    {
        try {
            $endDate = Carbon::parse( $this->campaign->getEndDatetime(), hurryt_tz() )->getBrowserTimestamp();
            if ( $this->campaign->is_recurring() ) {
                $endDate = $this->campaign->getRecurrenceEndDate();
                if ( $endDate ) {
                    $endDate = $endDate->getBrowserTimestamp();
                } else {
                    $endDate = null;
                }
            }

            $timeToNextRecurrence = $this->campaign->is_recurring() ? $this->campaign->timeToNextRecurrence() : 0;

            return [
                'recurr'               => $this->campaign->is_recurring(),
                'timeToNextRecurrence' => $timeToNextRecurrence,
                'isRegular'            => true,
                'endDate'              => $endDate,
            ];

        } catch ( Exception $e ) {
            echo __( sprintf( 'HurryTimer Error: Invalid campaign (ID: %d). Please double check your settings.',
                $this->campaign->get_id() ), 'hurrytimer' );
        }
    }

    /**
     * Returns client config for the compaign.
     *
     * @param array $options
     * @return array|null
     */
    public function getClientConfig( $options = [] )
    {
        $config = $options;

        if ( $this->campaign->is_evergreen() ) {
            $config = array_merge( $config, $this->commonClientConfig(), $this->evergreenClientConfig() );

        } else {
            $config = array_merge( $config, $this->commonClientConfig(), $this->regularClientConfig() );
        }

        return $config;
    }

    /**
     * Returns timer.
     *
     * @return string
     */
    public function timer()
    {
        $blocks = array_filter( [
            $this->monthsBlock(),
            $this->daysBlock(),
            $this->hoursBlock(),
            $this->minutesBlock(),
            $this->secondsBlock(),
        ] );

        $template = implode( $this->separator(), $blocks );

        $template = apply_filters( "hurryt_{$this->campaign->get_id()}_campaign_timer_template",
            $template, $this->campaign );

        $template = apply_filters( "hurryt_timer_template", $template, $this->campaign->get_id() );


        return $template;

    }

    /**
     * Returns separator.
     *
     * @return string
     */
    public function separator()
    {

        $separator = apply_filters( 'hurryt_block_separator', ':', $this->campaign->get_id() );

        return $this->campaign->blockSeparatorVisibility === C::YES
            ? '<div class="' . $this->legacySeparatorClass() . ' hurrytimer-timer-sep">' . $separator . '</div>'
            : '';
    }

    /**
     * Returns days block.
     *
     * @return string
     */
    public function monthsBlock()
    {
        $label = $this->campaign->labels[ 'months' ];
        $label = apply_filters( 'hurryt_months_label', $label, $this->campaign->get_id() );

        $zero_padded = (bool)apply_filters('hurrytimer_zero_padded_digits', true,  $this->campaign->get_id());

        if( apply_filters('hurrytimer_auto_pluralize', false ) ){
            $label.='%!m';
        }

        $directive = $zero_padded ? "%m": "%-m";
        return $this->campaign->monthsVisibility === C::YES ? $this->block($directive , $label ) : '';
    }


    /**
     * Returns days block.
     *
     * @return string
     */
    public function daysBlock()
    {
        $label = $this->campaign->labels[ 'days' ];

        /**
         * @deprecated  Use `hurryt_days_label` instead.
         */
        $label = apply_filters( "hurryt_{$this->campaign->get_id()}_campaign_timer_days_label",
            $label, $this->campaign );

        $zero_padded = (bool)apply_filters('hurrytimer_zero_padded_digits', true,  $this->campaign->get_id());

         $directive = $zero_padded ? '%D' : '%-D';
        if ( $this->campaign->monthsVisibility == C::YES ) {
            $directive = $zero_padded ? '%n' : '%-n';
        }

        $directive = apply_filters( 'hurryt_days_directive', $directive, $this->campaign->get_id() );
        if( apply_filters('hurrytimer_auto_pluralize', false ) ){
            $label.='%!D';
        }
        $label     = apply_filters( 'hurryt_days_label', $label, $this->campaign->get_id() );
        return $this->campaign->daysVisibility === C::YES ? $this->block( $directive, $label ) : '';


    }

    /**
     * Returns hours block.
     *
     * @return string
     */
    public function hoursBlock()
    {
        $label = $this->campaign->labels[ 'hours' ];

        /**
         * @deprecated  Use `hurryt_hours_label` instead.
         */

        $label = apply_filters( "hurryt_{$this->campaign->get_id()}_campaign_timer_hours_label",
            $label, $this->campaign );


        $zero_padded = (bool)apply_filters('hurrytimer_zero_padded_digits', true, $this->campaign->get_id());

        $directive = $zero_padded ? '%H' : '%-H';

        if ( $this->campaign->daysVisibility == C::NO ) {
            $directive = $zero_padded ? '%I': '%-I';
        }

        if( apply_filters('hurrytimer_auto_pluralize', false ) ){
            $label.='%!H';
        }

        $label = apply_filters( 'hurryt_hours_label', $label, $this->campaign->get_id() );

        return $this->campaign->hoursVisibility === C::YES ? $this->block($directive, $label ) : '';


    }

    /**
     * Returns minutes block.
     *
     * @return string
     */
    public function minutesBlock()
    {
        $label = $this->campaign->labels[ 'minutes' ];

        /** @deprecated Use `hurryt_minutes_label` instead. */
        $label = apply_filters( "hurryt_{$this->campaign->get_id()}_campaign_timer_minutes_label",
            $label, $this->campaign );


        $zero_padded = (bool)apply_filters('hurrytimer_zero_padded_digits', true,  $this->campaign->get_id());

        $directive = $zero_padded ?'%M': '%-M';
        
        if ( $this->campaign->hoursVisibility == C::NO ) {
            $directive = $zero_padded ? '%N': '%-N';
        }

        if( apply_filters('hurrytimer_auto_pluralize', false ) ){
            $label.='%!M';
        }

        $label = apply_filters( 'hurryt_minutes_label', $label, $this->campaign->get_id() );

        return $this->campaign->minutesVisibility === C::YES
            ? $this->block( $directive, $label )
            : '';

    }

    /**
     * Returns seconds block.
     *
     * @return string
     */
    public function secondsBlock()
    {

        $label = $this->campaign->labels[ 'seconds' ];
         
        /** @deprecated Use `hurryt_seconds_label` instead. */
        $label = apply_filters( "hurryt_{$this->campaign->get_id()}_campaign_timer_seconds_label",
            $label, $this->campaign );


        $zero_padded = (bool)apply_filters('hurrytimer_zero_padded_digits', true,  $this->campaign->get_id());

        $directive = $zero_padded ?'%S': '%-S';
        
        if ( $this->campaign->minutesVisibility == C::NO ) {
            $directive = $zero_padded ? '%T': '%-T';
        }

        if( apply_filters('hurrytimer_auto_pluralize', false ) ){
            $label.='%!S';
        }
        
        $label = apply_filters( 'hurryt_seconds_label', $label, $this->campaign->get_id() );

        return $this->campaign->secondsVisibility === C::YES
            ? $this->block( $directive, $label )
            : '';

    }

    /**
     * Returns block.
     *
     * @param $digitFormat
     * @param $label
     *
     * @return string
     */
    public function block( $digitFormat, $label )
    {
        return '<div class="hurrytimer-timer-block ' . $this->legacyBlockClass() . '">'
            . $this->digit( $digitFormat )
            . $this->label( $label )
            . '</div>';
    }

    public function digit( $format )
    {
        return '<div class="hurrytimer-timer-digit ' . $this->legacyDigitClass() . '">' . $format
            . '</div>';
    }

    public function label( $text )
    {
        if ( $this->campaign->labelVisibility === "no" ) {
            return '';
        }

        return '<div class="hurrytimer-timer-label ' . $this->legacyLabelClass() . '" >'
            . $text . '</div>';

    }

    public function headline()
    {
        $headline = $this->campaign->headline;

        /**
         * @deprecated Use `hurryt_campaign_headline` instead.
         */
        $headline = apply_filters( "hurryt_{$this->campaign->get_id()}_campaign_headline",
            $headline, $this->campaign);

        $headline = apply_filters( "hurryt_campaign_headline", $headline, $this->campaign->get_id());

        $headline = nl2br( Placeholder_Factory::parse( $headline, $this->campaign ) );

        $headline = do_shortcode($headline);
        
        return $this->campaign->headlineVisibility === C::YES
            ? '<div class="' . $this->legacyHeadlineClass() . ' hurrytimer-headline">'
            . $headline . '</div>'
            : '';

    }

    public function callToActionButton()
    {
        if ( $this->campaign->callToAction[ 'enabled' ] === C::NO ) {
            return '';
        }

        $cta_text = $this->campaign->callToAction[ 'text' ];

        $cta_url = $this->campaign->callToAction[ 'url' ];

        /** @deprecated Use `hurryt_cta_text` instead. */
        $cta_text = apply_filters( "hurryt_{$this->campaign->get_id()}_campaign_cta_text",
            $cta_text, $this->campaign );
        
        $cta_text = apply_filters('hurryt_cta_text', $cta_text, $this->campaign->get_id());

        /** @deprecated Use `hurryt_cta_url` instead. */
        $cta_url = apply_filters( "hurryt_{$this->campaign->get_id()}_campaign_cta_url", $cta_url,
            $this->campaign );

        $cta_url = apply_filters('hurryt_cta_url', $cta_url, $this->campaign->get_id());


        $target   = $this->campaign->callToAction[ 'new_tab' ] === C::YES ? '_blank' : '_self';
        $template = "<a class='hurrytimer-button' target='" . $target . "' href='" . $cta_url
            . "' >" . $cta_text . "</a>";

        /** @deprecated Use `hurryt_cta_template` instead. */
        $template = apply_filters( "hurryt_{$this->campaign->get_id()}_campaign_cta_template",
            $template, $this->campaign );
        
        $template = apply_filters('hurryt_cta_template', $template, $this->campaign->get_id());

        return "<div class='hurrytimer-button-wrap'>" . $template . "</div>";

    }

    public function stickyBarCloseButton()
    {
        if ( $this->campaign->stickyBarDismissible === C::NO
            || isset( $_COOKIE[ '_dismissed_sticky_' . $this->campaign->get_id() ] )
        ) {
            return '';
        }

        if ( $this->campaign->stickyBarDismissible === C::YES ) {
            return '<button type="button" class="hurrytimer-sticky-close"><svg  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 357 357">
<polygon points="357,35.7 321.3,0 178.5,142.8 35.7,0 0,35.7 142.8,178.5 0,321.3 35.7,357 178.5,214.2 321.3,357 357,321.3
        214.2,178.5"/></svg></button>';

        }

    }

}
