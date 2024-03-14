<?php namespace flow;
if ( ! defined( 'WPINC' ) ) die;

use flow\db\LADBManager;
use flow\settings\FFSettingsUtils;
use flow\settings\FFStreamSettings;

/**
 * Flow-Flow
 *
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `FlowFlowAdmin.php`
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>
 * @link      http://looks-awesome.com
 * @copyright Looks Awesome
 */
class FlowFlow extends LABase {
    /**
     * Initialize the plugin by setting localization and loading public scripts
     * and styles.
     *
     * @param array $context
     * @param $slug
     * @param $slug_down
     *
     * @since     1.0.0
     *
     */
    protected function __construct( $context, $slug, $slug_down ) {
        parent::__construct( $context, $slug, $slug_down );
    }

    protected function getShortcodePrefix() {
        return 'ff';
    }

    protected function getPublicContext( $stream, $context ) {
        $context['boosted'] = FFSettingsUtils::YepNope2ClassicStyleSafe( $stream, 'cloud', false );

        $context['moderation'] = false;
        if ( isset( $stream->feeds ) && ! empty( $stream->feeds ) ) {
            foreach ( $stream->feeds as $source ) {
                if ( FFSettingsUtils::YepNope2ClassicStyleSafe( $source, 'mod', false ) ) {
                    $context['moderation'] = true;
                }
            }
        }

        /** @var LADBManager $dbm */
        $dbm      = $context['db_manager'];
        $settings = new FFStreamSettings( $stream );
        $this->cache->setStream( $settings );
        $context['stream']       = $stream;
        $context['hashOfStream'] = $this->cache->transientHash( $stream->id );
        $context['can_moderate'] = $dbm->getGeneralSettings()->canModerate();
        $context['token']        = $context['can_moderate'] ? $dbm->getToken( true ) : '';

        if ($context['boosted'] && FF_USE_WP){
            $context = apply_filters('ff_build_public_context', $context, $settings);
        }

		return $context;
	}

    protected function enqueueStyles() {
    }

    protected function enqueueScripts() {
    }

    protected function getNameJSOptions() {
        return 'FlowFlowOpts';
    }
}
