<?php

namespace DhlVendor\Octolize\Tracker\DeactivationTracker;

use DhlVendor\WPDesk\Tracker\Deactivation\Reason;
use DhlVendor\WPDesk\Tracker\Deactivation\ReasonsFactory;
class OctolizeReasonsFactory implements \DhlVendor\WPDesk\Tracker\Deactivation\ReasonsFactory
{
    private string $plugin_docs_url;
    private string $plugin_support_forum_url;
    private string $pro_plugin_title;
    private string $contact_us_url;
    public function __construct(string $plugin_docs_url = '', string $plugin_support_forum_url = '', $pro_plugin_title = '', $contact_us_url = '')
    {
        $this->plugin_docs_url = $plugin_docs_url === '' ? 'https://octol.io/docs-exit-pop-up' : $plugin_docs_url;
        $this->plugin_support_forum_url = $plugin_support_forum_url;
        $this->pro_plugin_title = $pro_plugin_title;
        $this->contact_us_url = $contact_us_url;
    }
    /**
     * Create reasons.
     *
     * @return Reason[]
     */
    public function createReasons() : array
    {
        return [new \DhlVendor\WPDesk\Tracker\Deactivation\Reason('not_selected', '', '', \false, '', \true, \true), new \DhlVendor\WPDesk\Tracker\Deactivation\Reason('i_had_difficulties', \__('I had difficulties configuring the plugin', 'wp-tracker-octolize'), \sprintf(\__('Sorry to hear that! We\'re certain that with a little help, configuring the plugin will be a breeze. Before you deactivate, try to find a solution in our %1$sdocumentation%2$s%3$s.', 'wp-tracker-octolize'), '<a href="' . \esc_url($this->plugin_docs_url) . '" target="_blank">', '</a>', $this->plugin_support_forum_url ? \sprintf(\__(' or post a question on the %1$sforum%2$s', 'wp-tracker-octolize'), '<a href="' . \esc_url($this->plugin_support_forum_url) . '" target="_blank">', '</a>') : '')), new \DhlVendor\WPDesk\Tracker\Deactivation\Reason('stopped_working', \__('The plugin stopped working', 'wp-tracker-octolize'), \sprintf(\__('We take any issues with our plugins very seriously. Try to find a reason in our %1$sdocumentation%2$s%3$s.', 'wp-tracker-octolize'), '<a href="' . \esc_url($this->plugin_docs_url) . '" target="_blank">', '</a>', $this->plugin_support_forum_url ? \sprintf(\__(' or post the problem on the %1$sforum%2$s', 'wp-tracker-octolize'), '<a href="' . \esc_url($this->plugin_support_forum_url) . '" target="_blank">', '</a>') : '')), new \DhlVendor\WPDesk\Tracker\Deactivation\Reason('found_another_plugin', \__('I have found another plugin', 'wp-tracker-octolize'), \__('That hurts a little bit, but we\'re tough! Can you let us know which plugin you are switching to?', 'wp-tracker-octolize'), \true, \__('Which plugin are you switching to?', 'wp-tracker-octolize')), new \DhlVendor\WPDesk\Tracker\Deactivation\Reason('missing_feature', \__('The plugin doesn\'t have the functionality I need', 'wp-tracker-octolize'), $this->pro_plugin_title ? \sprintf(\__('Good news! There\'s a great chance that the functionality you need is already implemented in the PRO version of the plugin. %1$sContact us%2$s to receive a discount for %3$s. Also, can you describe what functionality you\'re looking for?', 'wp-tracker-octolize'), '<a href="' . \esc_url($this->contact_us_url) . '" target="_blank">', '</a>', $this->pro_plugin_title) : \__('We\'re sorry to hear that. Can you describe what functionality you\'re looking for?', 'wp-tracker-octolize'), \true, \__('What functionality are you looking for?', 'wp-tracker-octolize')), new \DhlVendor\WPDesk\Tracker\Deactivation\Reason('dont_need_anymore', \__('I don\'t need the plugin anymore', 'wp-tracker-octolize'), \__('Sorry to hear that! Can you let us know why the plugin is not needed anymore?', 'wp-tracker-octolize'), \true, \__('Why is the plugin not needed anymore?', 'wp-tracker-octolize')), new \DhlVendor\WPDesk\Tracker\Deactivation\Reason('temporary_deactivation', \__('I\'m deactivating temporarily for debugging purposes', 'wp-tracker-octolize')), new \DhlVendor\WPDesk\Tracker\Deactivation\Reason('other', \__('Other reason', 'wp-tracker-octolize'), \__('Can you provide some details on the reason behind deactivation?', 'wp-tracker-octolize'), \true, \__('Please provide details', 'wp-tracker-octolize'))];
    }
}
