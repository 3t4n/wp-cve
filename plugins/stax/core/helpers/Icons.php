<?php

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Icons {
    /**
     * @var null
     */
    public static $instance = null;

    /**
     * @return null|Icons
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return array
     */
    public function get() {
        return [
            [
                'class' => 'access-point',
                'code'  => '\F002'
            ],
            [
                'class' => 'access-point-network',
                'code'  => '\F003'
            ],
            [
                'class' => 'access-point-network-off',
                'code'  => '\FBBD'
            ],
            [
                'class' => 'account',
                'code'  => '\F004'
            ],
            [
                'class' => 'account-alert',
                'code'  => '\F005'
            ],
            [
                'class' => 'account-alert-outline',
                'code'  => '\FB2C'
            ],
            [
                'class' => 'account-arrow-left',
                'code'  => '\FB2D'
            ],
            [
                'class' => 'account-arrow-left-outline',
                'code'  => '\FB2E'
            ],
            [
                'class' => 'account-arrow-right',
                'code'  => '\FB2F'
            ],
            [
                'class' => 'account-arrow-right-outline',
                'code'  => '\FB30'
            ],
            [
                'class' => 'account-box',
                'code'  => '\F006'
            ],
            [
                'class' => 'account-box-multiple',
                'code'  => '\F933'
            ],
            [
                'class' => 'account-box-outline',
                'code'  => '\F007'
            ],
            [
                'class' => 'account-card-details',
                'code'  => '\F5D2'
            ],
            [
                'class' => 'account-check',
                'code'  => '\F008'
            ],
            [
                'class' => 'account-check-outline',
                'code'  => '\FBBE'
            ],
            [
                'class' => 'account-child',
                'code'  => '\FA88'
            ],
            [
                'class' => 'account-child-circle',
                'code'  => '\FA89'
            ],
            [
                'class' => 'account-circle',
                'code'  => '\F009'
            ],
            [
                'class' => 'account-circle-outline',
                'code'  => '\FB31'
            ],
            [
                'class' => 'account-clock',
                'code'  => '\FB32'
            ],
            [
                'class' => 'account-clock-outline',
                'code'  => '\FB33'
            ],
            [
                'class' => 'account-convert',
                'code'  => '\F00A'
            ],
            [
                'class' => 'account-details',
                'code'  => '\F631'
            ],
            [
                'class' => 'account-edit',
                'code'  => '\F6BB'
            ],
            [
                'class' => 'account-group',
                'code'  => '\F848'
            ],
            [
                'class' => 'account-group-outline',
                'code'  => '\FB34'
            ],
            [
                'class' => 'account-heart',
                'code'  => '\F898'
            ],
            [
                'class' => 'account-heart-outline',
                'code'  => '\FBBF'
            ],
            [
                'class' => 'account-key',
                'code'  => '\F00B'
            ],
            [
                'class' => 'account-key-outline',
                'code'  => '\FBC0'
            ],
            [
                'class' => 'account-minus',
                'code'  => '\F00D'
            ],
            [
                'class' => 'account-minus-outline',
                'code'  => '\FAEB'
            ],
            [
                'class' => 'account-multiple',
                'code'  => '\F00E'
            ],
            [
                'class' => 'account-multiple-check',
                'code'  => '\F8C4'
            ],
            [
                'class' => 'account-multiple-minus',
                'code'  => '\F5D3'
            ],
            [
                'class' => 'account-multiple-minus-outline',
                'code'  => '\FBC1'
            ],
            [
                'class' => 'account-multiple-outline',
                'code'  => '\F00F'
            ],
            [
                'class' => 'account-multiple-plus',
                'code'  => '\F010'
            ],
            [
                'class' => 'account-multiple-plus-outline',
                'code'  => '\F7FF'
            ],
            [
                'class' => 'account-network',
                'code'  => '\F011'
            ],
            [
                'class' => 'account-network-outline',
                'code'  => '\FBC2'
            ],
            [
                'class' => 'account-off',
                'code'  => '\F012'
            ],
            [
                'class' => 'account-off-outline',
                'code'  => '\FBC3'
            ],
            [
                'class' => 'account-outline',
                'code'  => '\F013'
            ],
            [
                'class' => 'account-plus',
                'code'  => '\F014'
            ],
            [
                'class' => 'account-plus-outline',
                'code'  => '\F800'
            ],
            [
                'class' => 'account-question',
                'code'  => '\FB35'
            ],
            [
                'class' => 'account-question-outline',
                'code'  => '\FB36'
            ],
            [
                'class' => 'account-remove',
                'code'  => '\F015'
            ],
            [
                'class' => 'account-remove-outline',
                'code'  => '\FAEC'
            ],
            [
                'class' => 'account-search',
                'code'  => '\F016'
            ],
            [
                'class' => 'account-search-outline',
                'code'  => '\F934'
            ],
            [
                'class' => 'account-settings',
                'code'  => '\F630'
            ],
            [
                'class' => 'account-star',
                'code'  => '\F017'
            ],
            [
                'class' => 'account-star-outline',
                'code'  => '\FBC4'
            ],
            [
                'class' => 'account-supervisor',
                'code'  => '\FA8A'
            ],
            [
                'class' => 'account-supervisor-circle',
                'code'  => '\FA8B'
            ],
            [
                'class' => 'account-switch',
                'code'  => '\F019'
            ],
            [
                'class' => 'account-tie',
                'code'  => '\FCBF'
            ],
            [
                'class' => 'accusoft',
                'code'  => '\F849'
            ],
            [
                'class' => 'adjust',
                'code'  => '\F01A'
            ],
            [
                'class' => 'adobe',
                'code'  => '\F935'
            ],
            [
                'class' => 'air-conditioner',
                'code'  => '\F01B'
            ],
            [
                'class' => 'airbag',
                'code'  => '\FBC5'
            ],
            [
                'class' => 'airballoon',
                'code'  => '\F01C'
            ],
            [
                'class' => 'airplane',
                'code'  => '\F01D'
            ],
            [
                'class' => 'airplane-landing',
                'code'  => '\F5D4'
            ],
            [
                'class' => 'airplane-off',
                'code'  => '\F01E'
            ],
            [
                'class' => 'airplane-takeoff',
                'code'  => '\F5D5'
            ],
            [
                'class' => 'airplay',
                'code'  => '\F01F'
            ],
            [
                'class' => 'airport',
                'code'  => '\F84A'
            ],
            [
                'class' => 'alarm',
                'code'  => '\F020'
            ],
            [
                'class' => 'alarm-bell',
                'code'  => '\F78D'
            ],
            [
                'class' => 'alarm-check',
                'code'  => '\F021'
            ],
            [
                'class' => 'alarm-light',
                'code'  => '\F78E'
            ],
            [
                'class' => 'alarm-light-outline',
                'code'  => '\FBC6'
            ],
            [
                'class' => 'alarm-multiple',
                'code'  => '\F022'
            ],
            [
                'class' => 'alarm-off',
                'code'  => '\F023'
            ],
            [
                'class' => 'alarm-plus',
                'code'  => '\F024'
            ],
            [
                'class' => 'alarm-snooze',
                'code'  => '\F68D'
            ],
            [
                'class' => 'album',
                'code'  => '\F025'
            ],
            [
                'class' => 'alert',
                'code'  => '\F026'
            ],
            [
                'class' => 'alert-box',
                'code'  => '\F027'
            ],
            [
                'class' => 'alert-box-outline',
                'code'  => '\FCC0'
            ],
            [
                'class' => 'alert-circle',
                'code'  => '\F028'
            ],
            [
                'class' => 'alert-circle-outline',
                'code'  => '\F5D6'
            ],
            [
                'class' => 'alert-decagram',
                'code'  => '\F6BC'
            ],
            [
                'class' => 'alert-decagram-outline',
                'code'  => '\FCC1'
            ],
            [
                'class' => 'alert-octagon',
                'code'  => '\F029'
            ],
            [
                'class' => 'alert-octagon-outline',
                'code'  => '\FCC2'
            ],
            [
                'class' => 'alert-octagram',
                'code'  => '\F766'
            ],
            [
                'class' => 'alert-octagram-outline',
                'code'  => '\FCC3'
            ],
            [
                'class' => 'alert-outline',
                'code'  => '\F02A'
            ],
            [
                'class' => 'alien',
                'code'  => '\F899'
            ],
            [
                'class' => 'all-inclusive',
                'code'  => '\F6BD'
            ],
            [
                'class' => 'alpha',
                'code'  => '\F02B'
            ],
            [
                'class' => 'alpha-a',
                'code'  => '0041'
            ],
            [
                'class' => 'alpha-a-box',
                'code'  => '\FAED'
            ],
            [
                'class' => 'alpha-a-box-outline',
                'code'  => '\FBC7'
            ],
            [
                'class' => 'alpha-a-circle',
                'code'  => '\FBC8'
            ],
            [
                'class' => 'alpha-a-circle-outline',
                'code'  => '\FBC9'
            ],
            [
                'class' => 'alpha-b',
                'code'  => '0042'
            ],
            [
                'class' => 'alpha-b-box',
                'code'  => '\FAEE'
            ],
            [
                'class' => 'alpha-b-box-outline',
                'code'  => '\FBCA'
            ],
            [
                'class' => 'alpha-b-circle',
                'code'  => '\FBCB'
            ],
            [
                'class' => 'alpha-b-circle-outline',
                'code'  => '\FBCC'
            ],
            [
                'class' => 'alpha-c',
                'code'  => '0043'
            ],
            [
                'class' => 'alpha-c-box',
                'code'  => '\FAEF'
            ],
            [
                'class' => 'alpha-c-box-outline',
                'code'  => '\FBCD'
            ],
            [
                'class' => 'alpha-c-circle',
                'code'  => '\FBCE'
            ],
            [
                'class' => 'alpha-c-circle-outline',
                'code'  => '\FBCF'
            ],
            [
                'class' => 'alpha-d',
                'code'  => '0044'
            ],
            [
                'class' => 'alpha-d-box',
                'code'  => '\FAF0'
            ],
            [
                'class' => 'alpha-d-box-outline',
                'code'  => '\FBD0'
            ],
            [
                'class' => 'alpha-d-circle',
                'code'  => '\FBD1'
            ],
            [
                'class' => 'alpha-d-circle-outline',
                'code'  => '\FBD2'
            ],
            [
                'class' => 'alpha-e',
                'code'  => '0045'
            ],
            [
                'class' => 'alpha-e-box',
                'code'  => '\FAF1'
            ],
            [
                'class' => 'alpha-e-box-outline',
                'code'  => '\FBD3'
            ],
            [
                'class' => 'alpha-e-circle',
                'code'  => '\FBD4'
            ],
            [
                'class' => 'alpha-e-circle-outline',
                'code'  => '\FBD5'
            ],
            [
                'class' => 'alpha-f',
                'code'  => '0046'
            ],
            [
                'class' => 'alpha-f-box',
                'code'  => '\FAF2'
            ],
            [
                'class' => 'alpha-f-box-outline',
                'code'  => '\FBD6'
            ],
            [
                'class' => 'alpha-f-circle',
                'code'  => '\FBD7'
            ],
            [
                'class' => 'alpha-f-circle-outline',
                'code'  => '\FBD8'
            ],
            [
                'class' => 'alpha-g',
                'code'  => '0047'
            ],
            [
                'class' => 'alpha-g-box',
                'code'  => '\FAF3'
            ],
            [
                'class' => 'alpha-g-box-outline',
                'code'  => '\FBD9'
            ],
            [
                'class' => 'alpha-g-circle',
                'code'  => '\FBDA'
            ],
            [
                'class' => 'alpha-g-circle-outline',
                'code'  => '\FBDB'
            ],
            [
                'class' => 'alpha-h',
                'code'  => '0048'
            ],
            [
                'class' => 'alpha-h-box',
                'code'  => '\FAF4'
            ],
            [
                'class' => 'alpha-h-box-outline',
                'code'  => '\FBDC'
            ],
            [
                'class' => 'alpha-h-circle',
                'code'  => '\FBDD'
            ],
            [
                'class' => 'alpha-h-circle-outline',
                'code'  => '\FBDE'
            ],
            [
                'class' => 'alpha-i',
                'code'  => '0049'
            ],
            [
                'class' => 'alpha-i-box',
                'code'  => '\FAF5'
            ],
            [
                'class' => 'alpha-i-box-outline',
                'code'  => '\FBDF'
            ],
            [
                'class' => 'alpha-i-circle',
                'code'  => '\FBE0'
            ],
            [
                'class' => 'alpha-i-circle-outline',
                'code'  => '\FBE1'
            ],
            [
                'class' => 'alpha-j',
                'code'  => '004A'
            ],
            [
                'class' => 'alpha-j-box',
                'code'  => '\FAF6'
            ],
            [
                'class' => 'alpha-j-box-outline',
                'code'  => '\FBE2'
            ],
            [
                'class' => 'alpha-j-circle',
                'code'  => '\FBE3'
            ],
            [
                'class' => 'alpha-j-circle-outline',
                'code'  => '\FBE4'
            ],
            [
                'class' => 'alpha-k',
                'code'  => '004B'
            ],
            [
                'class' => 'alpha-k-box',
                'code'  => '\FAF7'
            ],
            [
                'class' => 'alpha-k-box-outline',
                'code'  => '\FBE5'
            ],
            [
                'class' => 'alpha-k-circle',
                'code'  => '\FBE6'
            ],
            [
                'class' => 'alpha-k-circle-outline',
                'code'  => '\FBE7'
            ],
            [
                'class' => 'alpha-l',
                'code'  => '004C'
            ],
            [
                'class' => 'alpha-l-box',
                'code'  => '\FAF8'
            ],
            [
                'class' => 'alpha-l-box-outline',
                'code'  => '\FBE8'
            ],
            [
                'class' => 'alpha-l-circle',
                'code'  => '\FBE9'
            ],
            [
                'class' => 'alpha-l-circle-outline',
                'code'  => '\FBEA'
            ],
            [
                'class' => 'alpha-m',
                'code'  => '004D'
            ],
            [
                'class' => 'alpha-m-box',
                'code'  => '\FAF9'
            ],
            [
                'class' => 'alpha-m-box-outline',
                'code'  => '\FBEB'
            ],
            [
                'class' => 'alpha-m-circle',
                'code'  => '\FBEC'
            ],
            [
                'class' => 'alpha-m-circle-outline',
                'code'  => '\FBED'
            ],
            [
                'class' => 'alpha-n',
                'code'  => '004E'
            ],
            [
                'class' => 'alpha-n-box',
                'code'  => '\FAFA'
            ],
            [
                'class' => 'alpha-n-box-outline',
                'code'  => '\FBEE'
            ],
            [
                'class' => 'alpha-n-circle',
                'code'  => '\FBEF'
            ],
            [
                'class' => 'alpha-n-circle-outline',
                'code'  => '\FBF0'
            ],
            [
                'class' => 'alpha-o',
                'code'  => '004F'
            ],
            [
                'class' => 'alpha-o-box',
                'code'  => '\FAFB'
            ],
            [
                'class' => 'alpha-o-box-outline',
                'code'  => '\FBF1'
            ],
            [
                'class' => 'alpha-o-circle',
                'code'  => '\FBF2'
            ],
            [
                'class' => 'alpha-o-circle-outline',
                'code'  => '\FBF3'
            ],
            [
                'class' => 'alpha-p',
                'code'  => '0050'
            ],
            [
                'class' => 'alpha-p-box',
                'code'  => '\FAFC'
            ],
            [
                'class' => 'alpha-p-box-outline',
                'code'  => '\FBF4'
            ],
            [
                'class' => 'alpha-p-circle',
                'code'  => '\FBF5'
            ],
            [
                'class' => 'alpha-p-circle-outline',
                'code'  => '\FBF6'
            ],
            [
                'class' => 'alpha-q',
                'code'  => '0051'
            ],
            [
                'class' => 'alpha-q-box',
                'code'  => '\FAFD'
            ],
            [
                'class' => 'alpha-q-box-outline',
                'code'  => '\FBF7'
            ],
            [
                'class' => 'alpha-q-circle',
                'code'  => '\FBF8'
            ],
            [
                'class' => 'alpha-q-circle-outline',
                'code'  => '\FBF9'
            ],
            [
                'class' => 'alpha-r',
                'code'  => '0052'
            ],
            [
                'class' => 'alpha-r-box',
                'code'  => '\FAFE'
            ],
            [
                'class' => 'alpha-r-box-outline',
                'code'  => '\FBFA'
            ],
            [
                'class' => 'alpha-r-circle',
                'code'  => '\FBFB'
            ],
            [
                'class' => 'alpha-r-circle-outline',
                'code'  => '\FBFC'
            ],
            [
                'class' => 'alpha-s',
                'code'  => '0053'
            ],
            [
                'class' => 'alpha-s-box',
                'code'  => '\FAFF'
            ],
            [
                'class' => 'alpha-s-box-outline',
                'code'  => '\FBFD'
            ],
            [
                'class' => 'alpha-s-circle',
                'code'  => '\FBFE'
            ],
            [
                'class' => 'alpha-s-circle-outline',
                'code'  => '\FBFF'
            ],
            [
                'class' => 'alpha-t',
                'code'  => '0054'
            ],
            [
                'class' => 'alpha-t-box',
                'code'  => '\FB00'
            ],
            [
                'class' => 'alpha-t-box-outline',
                'code'  => '\FC00'
            ],
            [
                'class' => 'alpha-t-circle',
                'code'  => '\FC01'
            ],
            [
                'class' => 'alpha-t-circle-outline',
                'code'  => '\FC02'
            ],
            [
                'class' => 'alpha-u',
                'code'  => '0055'
            ],
            [
                'class' => 'alpha-u-box',
                'code'  => '\FB01'
            ],
            [
                'class' => 'alpha-u-box-outline',
                'code'  => '\FC03'
            ],
            [
                'class' => 'alpha-u-circle',
                'code'  => '\FC04'
            ],
            [
                'class' => 'alpha-u-circle-outline',
                'code'  => '\FC05'
            ],
            [
                'class' => 'alpha-v',
                'code'  => '0056'
            ],
            [
                'class' => 'alpha-v-box',
                'code'  => '\FB02'
            ],
            [
                'class' => 'alpha-v-box-outline',
                'code'  => '\FC06'
            ],
            [
                'class' => 'alpha-v-circle',
                'code'  => '\FC07'
            ],
            [
                'class' => 'alpha-v-circle-outline',
                'code'  => '\FC08'
            ],
            [
                'class' => 'alpha-w',
                'code'  => '0057'
            ],
            [
                'class' => 'alpha-w-box',
                'code'  => '\FB03'
            ],
            [
                'class' => 'alpha-w-box-outline',
                'code'  => '\FC09'
            ],
            [
                'class' => 'alpha-w-circle',
                'code'  => '\FC0A'
            ],
            [
                'class' => 'alpha-w-circle-outline',
                'code'  => '\FC0B'
            ],
            [
                'class' => 'alpha-x',
                'code'  => '0058'
            ],
            [
                'class' => 'alpha-x-box',
                'code'  => '\FB04'
            ],
            [
                'class' => 'alpha-x-box-outline',
                'code'  => '\FC0C'
            ],
            [
                'class' => 'alpha-x-circle',
                'code'  => '\FC0D'
            ],
            [
                'class' => 'alpha-x-circle-outline',
                'code'  => '\FC0E'
            ],
            [
                'class' => 'alpha-y',
                'code'  => '0059'
            ],
            [
                'class' => 'alpha-y-box',
                'code'  => '\FB05'
            ],
            [
                'class' => 'alpha-y-box-outline',
                'code'  => '\FC0F'
            ],
            [
                'class' => 'alpha-y-circle',
                'code'  => '\FC10'
            ],
            [
                'class' => 'alpha-y-circle-outline',
                'code'  => '\FC11'
            ],
            [
                'class' => 'alpha-z',
                'code'  => '005A'
            ],
            [
                'class' => 'alpha-z-box',
                'code'  => '\FB06'
            ],
            [
                'class' => 'alpha-z-box-outline',
                'code'  => '\FC12'
            ],
            [
                'class' => 'alpha-z-circle',
                'code'  => '\FC13'
            ],
            [
                'class' => 'alpha-z-circle-outline',
                'code'  => '\FC14'
            ],
            [
                'class' => 'alphabetical',
                'code'  => '\F02C'
            ],
            [
                'class' => 'altimeter',
                'code'  => '\F5D7'
            ],
            [
                'class' => 'amazon',
                'code'  => '\F02D'
            ],
            [
                'class' => 'amazon-alexa',
                'code'  => '\F8C5'
            ],
            [
                'class' => 'amazon-drive',
                'code'  => '\F02E'
            ],
            [
                'class' => 'ambulance',
                'code'  => '\F02F'
            ],
            [
                'class' => 'ammunition',
                'code'  => '\FCC4'
            ],
            [
                'class' => 'ampersand',
                'code'  => '\FA8C'
            ],
            [
                'class' => 'amplifier',
                'code'  => '\F030'
            ],
            [
                'class' => 'anchor',
                'code'  => '\F031'
            ],
            [
                'class' => 'android',
                'code'  => '\F032'
            ],
            [
                'class' => 'android-auto',
                'code'  => '\FA8D'
            ],
            [
                'class' => 'android-debug-bridge',
                'code'  => '\F033'
            ],
            [
                'class' => 'android-head',
                'code'  => '\F78F'
            ],
            [
                'class' => 'android-studio',
                'code'  => '\F034'
            ],
            [
                'class' => 'angle-acute',
                'code'  => '\F936'
            ],
            [
                'class' => 'angle-obtuse',
                'code'  => '\F937'
            ],
            [
                'class' => 'angle-right',
                'code'  => '\F938'
            ],
            [
                'class' => 'angular',
                'code'  => '\F6B1'
            ],
            [
                'class' => 'angularjs',
                'code'  => '\F6BE'
            ],
            [
                'class' => 'animation',
                'code'  => '\F5D8'
            ],
            [
                'class' => 'animation-outline',
                'code'  => '\FA8E'
            ],
            [
                'class' => 'animation-play',
                'code'  => '\F939'
            ],
            [
                'class' => 'animation-play-outline',
                'code'  => '\FA8F'
            ],
            [
                'class' => 'anvil',
                'code'  => '\F89A'
            ],
            [
                'class' => 'apple',
                'code'  => '\F035'
            ],
            [
                'class' => 'apple-finder',
                'code'  => '\F036'
            ],
            [
                'class' => 'apple-icloud',
                'code'  => '\F038'
            ],
            [
                'class' => 'apple-ios',
                'code'  => '\F037'
            ],
            [
                'class' => 'apple-keyboard-caps',
                'code'  => '\F632'
            ],
            [
                'class' => 'apple-keyboard-command',
                'code'  => '\F633'
            ],
            [
                'class' => 'apple-keyboard-control',
                'code'  => '\F634'
            ],
            [
                'class' => 'apple-keyboard-option',
                'code'  => '\F635'
            ],
            [
                'class' => 'apple-keyboard-shift',
                'code'  => '\F636'
            ],
            [
                'class' => 'apple-safari',
                'code'  => '\F039'
            ],
            [
                'class' => 'application',
                'code'  => '\F614'
            ],
            [
                'class' => 'apps',
                'code'  => '\F03B'
            ],
            [
                'class' => 'arch',
                'code'  => '\F8C6'
            ],
            [
                'class' => 'archive',
                'code'  => '\F03C'
            ],
            [
                'class' => 'arrange-bring-forward',
                'code'  => '\F03D'
            ],
            [
                'class' => 'arrange-bring-to-front',
                'code'  => '\F03E'
            ],
            [
                'class' => 'arrange-send-backward',
                'code'  => '\F03F'
            ],
            [
                'class' => 'arrange-send-to-back',
                'code'  => '\F040'
            ],
            [
                'class' => 'arrow-all',
                'code'  => '\F041'
            ],
            [
                'class' => 'arrow-bottom-left',
                'code'  => '\F042'
            ],
            [
                'class' => 'arrow-bottom-left-bold-outline',
                'code'  => '\F9B6'
            ],
            [
                'class' => 'arrow-bottom-left-thick',
                'code'  => '\F9B7'
            ],
            [
                'class' => 'arrow-bottom-right',
                'code'  => '\F043'
            ],
            [
                'class' => 'arrow-bottom-right-bold-outline',
                'code'  => '\F9B8'
            ],
            [
                'class' => 'arrow-bottom-right-thick',
                'code'  => '\F9B9'
            ],
            [
                'class' => 'arrow-collapse',
                'code'  => '\F615'
            ],
            [
                'class' => 'arrow-collapse-all',
                'code'  => '\F044'
            ],
            [
                'class' => 'arrow-collapse-down',
                'code'  => '\F791'
            ],
            [
                'class' => 'arrow-collapse-horizontal',
                'code'  => '\F84B'
            ],
            [
                'class' => 'arrow-collapse-left',
                'code'  => '\F792'
            ],
            [
                'class' => 'arrow-collapse-right',
                'code'  => '\F793'
            ],
            [
                'class' => 'arrow-collapse-up',
                'code'  => '\F794'
            ],
            [
                'class' => 'arrow-collapse-vertical',
                'code'  => '\F84C'
            ],
            [
                'class' => 'arrow-decision',
                'code'  => '\F9BA'
            ],
            [
                'class' => 'arrow-decision-auto',
                'code'  => '\F9BB'
            ],
            [
                'class' => 'arrow-decision-auto-outline',
                'code'  => '\F9BC'
            ],
            [
                'class' => 'arrow-decision-outline',
                'code'  => '\F9BD'
            ],
            [
                'class' => 'arrow-down',
                'code'  => '\F045'
            ],
            [
                'class' => 'arrow-down-bold',
                'code'  => '\F72D'
            ],
            [
                'class' => 'arrow-down-bold-box',
                'code'  => '\F72E'
            ],
            [
                'class' => 'arrow-down-bold-box-outline',
                'code'  => '\F72F'
            ],
            [
                'class' => 'arrow-down-bold-circle',
                'code'  => '\F047'
            ],
            [
                'class' => 'arrow-down-bold-circle-outline',
                'code'  => '\F048'
            ],
            [
                'class' => 'arrow-down-bold-hexagon-outline',
                'code'  => '\F049'
            ],
            [
                'class' => 'arrow-down-bold-outline',
                'code'  => '\F9BE'
            ],
            [
                'class' => 'arrow-down-box',
                'code'  => '\F6BF'
            ],
            [
                'class' => 'arrow-down-circle',
                'code'  => '\FCB7'
            ],
            [
                'class' => 'arrow-down-circle-outline',
                'code'  => '\FCB8'
            ],
            [
                'class' => 'arrow-down-drop-circle',
                'code'  => '\F04A'
            ],
            [
                'class' => 'arrow-down-drop-circle-outline',
                'code'  => '\F04B'
            ],
            [
                'class' => 'arrow-down-thick',
                'code'  => '\F046'
            ],
            [
                'class' => 'arrow-expand',
                'code'  => '\F616'
            ],
            [
                'class' => 'arrow-expand-all',
                'code'  => '\F04C'
            ],
            [
                'class' => 'arrow-expand-down',
                'code'  => '\F795'
            ],
            [
                'class' => 'arrow-expand-horizontal',
                'code'  => '\F84D'
            ],
            [
                'class' => 'arrow-expand-left',
                'code'  => '\F796'
            ],
            [
                'class' => 'arrow-expand-right',
                'code'  => '\F797'
            ],
            [
                'class' => 'arrow-expand-up',
                'code'  => '\F798'
            ],
            [
                'class' => 'arrow-expand-vertical',
                'code'  => '\F84E'
            ],
            [
                'class' => 'arrow-left',
                'code'  => '\F04D'
            ],
            [
                'class' => 'arrow-left-bold',
                'code'  => '\F730'
            ],
            [
                'class' => 'arrow-left-bold-box',
                'code'  => '\F731'
            ],
            [
                'class' => 'arrow-left-bold-box-outline',
                'code'  => '\F732'
            ],
            [
                'class' => 'arrow-left-bold-circle',
                'code'  => '\F04F'
            ],
            [
                'class' => 'arrow-left-bold-circle-outline',
                'code'  => '\F050'
            ],
            [
                'class' => 'arrow-left-bold-hexagon-outline',
                'code'  => '\F051'
            ],
            [
                'class' => 'arrow-left-bold-outline',
                'code'  => '\F9BF'
            ],
            [
                'class' => 'arrow-left-box',
                'code'  => '\F6C0'
            ],
            [
                'class' => 'arrow-left-circle',
                'code'  => '\FCB9'
            ],
            [
                'class' => 'arrow-left-circle-outline',
                'code'  => '\FCBA'
            ],
            [
                'class' => 'arrow-left-drop-circle',
                'code'  => '\F052'
            ],
            [
                'class' => 'arrow-left-drop-circle-outline',
                'code'  => '\F053'
            ],
            [
                'class' => 'arrow-left-right-bold-outline',
                'code'  => '\F9C0'
            ],
            [
                'class' => 'arrow-left-thick',
                'code'  => '\F04E'
            ],
            [
                'class' => 'arrow-right',
                'code'  => '\F054'
            ],
            [
                'class' => 'arrow-right-bold',
                'code'  => '\F733'
            ],
            [
                'class' => 'arrow-right-bold-box',
                'code'  => '\F734'
            ],
            [
                'class' => 'arrow-right-bold-box-outline',
                'code'  => '\F735'
            ],
            [
                'class' => 'arrow-right-bold-circle',
                'code'  => '\F056'
            ],
            [
                'class' => 'arrow-right-bold-circle-outline',
                'code'  => '\F057'
            ],
            [
                'class' => 'arrow-right-bold-hexagon-outline',
                'code'  => '\F058'
            ],
            [
                'class' => 'arrow-right-bold-outline',
                'code'  => '\F9C1'
            ],
            [
                'class' => 'arrow-right-box',
                'code'  => '\F6C1'
            ],
            [
                'class' => 'arrow-right-circle',
                'code'  => '\FCBB'
            ],
            [
                'class' => 'arrow-right-circle-outline',
                'code'  => '\FCBC'
            ],
            [
                'class' => 'arrow-right-drop-circle',
                'code'  => '\F059'
            ],
            [
                'class' => 'arrow-right-drop-circle-outline',
                'code'  => '\F05A'
            ],
            [
                'class' => 'arrow-right-thick',
                'code'  => '\F055'
            ],
            [
                'class' => 'arrow-split-horizontal',
                'code'  => '\F93A'
            ],
            [
                'class' => 'arrow-split-vertical',
                'code'  => '\F93B'
            ],
            [
                'class' => 'arrow-top-left',
                'code'  => '\F05B'
            ],
            [
                'class' => 'arrow-top-left-bold-outline',
                'code'  => '\F9C2'
            ],
            [
                'class' => 'arrow-top-left-thick',
                'code'  => '\F9C3'
            ],
            [
                'class' => 'arrow-top-right',
                'code'  => '\F05C'
            ],
            [
                'class' => 'arrow-top-right-bold-outline',
                'code'  => '\F9C4'
            ],
            [
                'class' => 'arrow-top-right-thick',
                'code'  => '\F9C5'
            ],
            [
                'class' => 'arrow-up',
                'code'  => '\F05D'
            ],
            [
                'class' => 'arrow-up-bold',
                'code'  => '\F736'
            ],
            [
                'class' => 'arrow-up-bold-box',
                'code'  => '\F737'
            ],
            [
                'class' => 'arrow-up-bold-box-outline',
                'code'  => '\F738'
            ],
            [
                'class' => 'arrow-up-bold-circle',
                'code'  => '\F05F'
            ],
            [
                'class' => 'arrow-up-bold-circle-outline',
                'code'  => '\F060'
            ],
            [
                'class' => 'arrow-up-bold-hexagon-outline',
                'code'  => '\F061'
            ],
            [
                'class' => 'arrow-up-bold-outline',
                'code'  => '\F9C6'
            ],
            [
                'class' => 'arrow-up-box',
                'code'  => '\F6C2'
            ],
            [
                'class' => 'arrow-up-circle',
                'code'  => '\FCBD'
            ],
            [
                'class' => 'arrow-up-circle-outline',
                'code'  => '\FCBE'
            ],
            [
                'class' => 'arrow-up-down-bold-outline',
                'code'  => '\F9C7'
            ],
            [
                'class' => 'arrow-up-drop-circle',
                'code'  => '\F062'
            ],
            [
                'class' => 'arrow-up-drop-circle-outline',
                'code'  => '\F063'
            ],
            [
                'class' => 'arrow-up-thick',
                'code'  => '\F05E'
            ],
            [
                'class' => 'artist',
                'code'  => '\F802'
            ],
            [
                'class' => 'artist-outline',
                'code'  => '\FCC5'
            ],
            [
                'class' => 'artstation',
                'code'  => '\FB37'
            ],
            [
                'class' => 'aspect-ratio',
                'code'  => '\FA23'
            ],
            [
                'class' => 'assistant',
                'code'  => '\F064'
            ],
            [
                'class' => 'asterisk',
                'code'  => '\F6C3'
            ],
            [
                'class' => 'at',
                'code'  => '\F065'
            ],
            [
                'class' => 'atlassian',
                'code'  => '\F803'
            ],
            [
                'class' => 'atom',
                'code'  => '\F767'
            ],
            [
                'class' => 'attachment',
                'code'  => '\F066'
            ],
            [
                'class' => 'audio-video',
                'code'  => '\F93C'
            ],
            [
                'class' => 'audiobook',
                'code'  => '\F067'
            ],
            [
                'class' => 'augmented-reality',
                'code'  => '\F84F'
            ],
            [
                'class' => 'auto-fix',
                'code'  => '\F068'
            ],
            [
                'class' => 'auto-upload',
                'code'  => '\F069'
            ],
            [
                'class' => 'autorenew',
                'code'  => '\F06A'
            ],
            [
                'class' => 'av-timer',
                'code'  => '\F06B'
            ],
            [
                'class' => 'axe',
                'code'  => '\F8C7'
            ],
            [
                'class' => 'azure',
                'code'  => '\F804'
            ],
            [
                'class' => 'babel',
                'code'  => '\FA24'
            ],
            [
                'class' => 'baby',
                'code'  => '\F06C'
            ],
            [
                'class' => 'baby-buggy',
                'code'  => '\F68E'
            ],
            [
                'class' => 'backburger',
                'code'  => '\F06D'
            ],
            [
                'class' => 'backspace',
                'code'  => '\F06E'
            ],
            [
                'class' => 'backspace-outline',
                'code'  => '\FB38'
            ],
            [
                'class' => 'backup-restore',
                'code'  => '\F06F'
            ],
            [
                'class' => 'badminton',
                'code'  => '\F850'
            ],
            [
                'class' => 'balloon',
                'code'  => '\FA25'
            ],
            [
                'class' => 'ballot',
                'code'  => '\F9C8'
            ],
            [
                'class' => 'ballot-outline',
                'code'  => '\F9C9'
            ],
            [
                'class' => 'ballot-recount',
                'code'  => '\FC15'
            ],
            [
                'class' => 'ballot-recount-outline',
                'code'  => '\FC16'
            ],
            [
                'class' => 'bandcamp',
                'code'  => '\F674'
            ],
            [
                'class' => 'bank',
                'code'  => '\F070'
            ],
            [
                'class' => 'bank-transfer',
                'code'  => '\FA26'
            ],
            [
                'class' => 'bank-transfer-in',
                'code'  => '\FA27'
            ],
            [
                'class' => 'bank-transfer-out',
                'code'  => '\FA28'
            ],
            [
                'class' => 'barcode',
                'code'  => '\F071'
            ],
            [
                'class' => 'barcode-scan',
                'code'  => '\F072'
            ],
            [
                'class' => 'barley',
                'code'  => '\F073'
            ],
            [
                'class' => 'barley-off',
                'code'  => '\FB39'
            ],
            [
                'class' => 'barn',
                'code'  => '\FB3A'
            ],
            [
                'class' => 'barrel',
                'code'  => '\F074'
            ],
            [
                'class' => 'baseball',
                'code'  => '\F851'
            ],
            [
                'class' => 'baseball-bat',
                'code'  => '\F852'
            ],
            [
                'class' => 'basecamp',
                'code'  => '\F075'
            ],
            [
                'class' => 'basket',
                'code'  => '\F076'
            ],
            [
                'class' => 'basket-fill',
                'code'  => '\F077'
            ],
            [
                'class' => 'basket-unfill',
                'code'  => '\F078'
            ],
            [
                'class' => 'basketball',
                'code'  => '\F805'
            ],
            [
                'class' => 'basketball-hoop',
                'code'  => '\FC17'
            ],
            [
                'class' => 'basketball-hoop-outline',
                'code'  => '\FC18'
            ],
            [
                'class' => 'bat',
                'code'  => '\FB3B'
            ],
            [
                'class' => 'battery',
                'code'  => '\F079'
            ],
            [
                'class' => 'battery-10',
                'code'  => '\F07A'
            ],
            [
                'class' => 'battery-10-bluetooth',
                'code'  => '\F93D'
            ],
            [
                'class' => 'battery-20',
                'code'  => '\F07B'
            ],
            [
                'class' => 'battery-20-bluetooth',
                'code'  => '\F93E'
            ],
            [
                'class' => 'battery-30',
                'code'  => '\F07C'
            ],
            [
                'class' => 'battery-30-bluetooth',
                'code'  => '\F93F'
            ],
            [
                'class' => 'battery-40',
                'code'  => '\F07D'
            ],
            [
                'class' => 'battery-40-bluetooth',
                'code'  => '\F940'
            ],
            [
                'class' => 'battery-50',
                'code'  => '\F07E'
            ],
            [
                'class' => 'battery-50-bluetooth',
                'code'  => '\F941'
            ],
            [
                'class' => 'battery-60',
                'code'  => '\F07F'
            ],
            [
                'class' => 'battery-60-bluetooth',
                'code'  => '\F942'
            ],
            [
                'class' => 'battery-70',
                'code'  => '\F080'
            ],
            [
                'class' => 'battery-70-bluetooth',
                'code'  => '\F943'
            ],
            [
                'class' => 'battery-80',
                'code'  => '\F081'
            ],
            [
                'class' => 'battery-80-bluetooth',
                'code'  => '\F944'
            ],
            [
                'class' => 'battery-90',
                'code'  => '\F082'
            ],
            [
                'class' => 'battery-90-bluetooth',
                'code'  => '\F945'
            ],
            [
                'class' => 'battery-alert',
                'code'  => '\F083'
            ],
            [
                'class' => 'battery-alert-bluetooth',
                'code'  => '\F946'
            ],
            [
                'class' => 'battery-bluetooth',
                'code'  => '\F947'
            ],
            [
                'class' => 'battery-bluetooth-variant',
                'code'  => '\F948'
            ],
            [
                'class' => 'battery-charging',
                'code'  => '\F084'
            ],
            [
                'class' => 'battery-charging-10',
                'code'  => '\F89B'
            ],
            [
                'class' => 'battery-charging-100',
                'code'  => '\F085'
            ],
            [
                'class' => 'battery-charging-20',
                'code'  => '\F086'
            ],
            [
                'class' => 'battery-charging-30',
                'code'  => '\F087'
            ],
            [
                'class' => 'battery-charging-40',
                'code'  => '\F088'
            ],
            [
                'class' => 'battery-charging-50',
                'code'  => '\F89C'
            ],
            [
                'class' => 'battery-charging-60',
                'code'  => '\F089'
            ],
            [
                'class' => 'battery-charging-70',
                'code'  => '\F89D'
            ],
            [
                'class' => 'battery-charging-80',
                'code'  => '\F08A'
            ],
            [
                'class' => 'battery-charging-90',
                'code'  => '\F08B'
            ],
            [
                'class' => 'battery-charging-outline',
                'code'  => '\F89E'
            ],
            [
                'class' => 'battery-charging-wireless',
                'code'  => '\F806'
            ],
            [
                'class' => 'battery-charging-wireless-10',
                'code'  => '\F807'
            ],
            [
                'class' => 'battery-charging-wireless-20',
                'code'  => '\F808'
            ],
            [
                'class' => 'battery-charging-wireless-30',
                'code'  => '\F809'
            ],
            [
                'class' => 'battery-charging-wireless-40',
                'code'  => '\F80A'
            ],
            [
                'class' => 'battery-charging-wireless-50',
                'code'  => '\F80B'
            ],
            [
                'class' => 'battery-charging-wireless-60',
                'code'  => '\F80C'
            ],
            [
                'class' => 'battery-charging-wireless-70',
                'code'  => '\F80D'
            ],
            [
                'class' => 'battery-charging-wireless-80',
                'code'  => '\F80E'
            ],
            [
                'class' => 'battery-charging-wireless-90',
                'code'  => '\F80F'
            ],
            [
                'class' => 'battery-charging-wireless-alert',
                'code'  => '\F810'
            ],
            [
                'class' => 'battery-charging-wireless-outline',
                'code'  => '\F811'
            ],
            [
                'class' => 'battery-minus',
                'code'  => '\F08C'
            ],
            [
                'class' => 'battery-negative',
                'code'  => '\F08D'
            ],
            [
                'class' => 'battery-outline',
                'code'  => '\F08E'
            ],
            [
                'class' => 'battery-plus',
                'code'  => '\F08F'
            ],
            [
                'class' => 'battery-positive',
                'code'  => '\F090'
            ],
            [
                'class' => 'battery-unknown',
                'code'  => '\F091'
            ],
            [
                'class' => 'battery-unknown-bluetooth',
                'code'  => '\F949'
            ],
            [
                'class' => 'battlenet',
                'code'  => '\FB3C'
            ],
            [
                'class' => 'beach',
                'code'  => '\F092'
            ],
            [
                'class' => 'beaker',
                'code'  => '\FCC6'
            ],
            [
                'class' => 'beaker-outline',
                'code'  => '\F68F'
            ],
            [
                'class' => 'beats',
                'code'  => '\F097'
            ],
            [
                'class' => 'bed-empty',
                'code'  => '\F89F'
            ],
            [
                'class' => 'beer',
                'code'  => '\F098'
            ],
            [
                'class' => 'behance',
                'code'  => '\F099'
            ],
            [
                'class' => 'bell',
                'code'  => '\F09A'
            ],
            [
                'class' => 'bell-off',
                'code'  => '\F09B'
            ],
            [
                'class' => 'bell-off-outline',
                'code'  => '\FA90'
            ],
            [
                'class' => 'bell-outline',
                'code'  => '\F09C'
            ],
            [
                'class' => 'bell-plus',
                'code'  => '\F09D'
            ],
            [
                'class' => 'bell-plus-outline',
                'code'  => '\FA91'
            ],
            [
                'class' => 'bell-ring',
                'code'  => '\F09E'
            ],
            [
                'class' => 'bell-ring-outline',
                'code'  => '\F09F'
            ],
            [
                'class' => 'bell-sleep',
                'code'  => '\F0A0'
            ],
            [
                'class' => 'bell-sleep-outline',
                'code'  => '\FA92'
            ],
            [
                'class' => 'beta',
                'code'  => '\F0A1'
            ],
            [
                'class' => 'betamax',
                'code'  => '\F9CA'
            ],
            [
                'class' => 'bible',
                'code'  => '\F0A2'
            ],
            [
                'class' => 'bike',
                'code'  => '\F0A3'
            ],
            [
                'class' => 'billiards',
                'code'  => '\FB3D'
            ],
            [
                'class' => 'billiards-rack',
                'code'  => '\FB3E'
            ],
            [
                'class' => 'bing',
                'code'  => '\F0A4'
            ],
            [
                'class' => 'binoculars',
                'code'  => '\F0A5'
            ],
            [
                'class' => 'bio',
                'code'  => '\F0A6'
            ],
            [
                'class' => 'biohazard',
                'code'  => '\F0A7'
            ],
            [
                'class' => 'bitbucket',
                'code'  => '\F0A8'
            ],
            [
                'class' => 'bitcoin',
                'code'  => '\F812'
            ],
            [
                'class' => 'black-mesa',
                'code'  => '\F0A9'
            ],
            [
                'class' => 'blackberry',
                'code'  => '\F0AA'
            ],
            [
                'class' => 'blender',
                'code'  => '\FCC7'
            ],
            [
                'class' => 'blender-software',
                'code'  => '\F0AB'
            ],
            [
                'class' => 'blinds',
                'code'  => '\F0AC'
            ],
            [
                'class' => 'block-helper',
                'code'  => '\F0AD'
            ],
            [
                'class' => 'blogger',
                'code'  => '\F0AE'
            ],
            [
                'class' => 'blood-bag',
                'code'  => '\FCC8'
            ],
            [
                'class' => 'bluetooth',
                'code'  => '\F0AF'
            ],
            [
                'class' => 'bluetooth-audio',
                'code'  => '\F0B0'
            ],
            [
                'class' => 'bluetooth-connect',
                'code'  => '\F0B1'
            ],
            [
                'class' => 'bluetooth-off',
                'code'  => '\F0B2'
            ],
            [
                'class' => 'bluetooth-settings',
                'code'  => '\F0B3'
            ],
            [
                'class' => 'bluetooth-transfer',
                'code'  => '\F0B4'
            ],
            [
                'class' => 'blur',
                'code'  => '\F0B5'
            ],
            [
                'class' => 'blur-linear',
                'code'  => '\F0B6'
            ],
            [
                'class' => 'blur-off',
                'code'  => '\F0B7'
            ],
            [
                'class' => 'blur-radial',
                'code'  => '\F0B8'
            ],
            [
                'class' => 'bolnisi-cross',
                'code'  => '\FCC9'
            ],
            [
                'class' => 'bomb',
                'code'  => '\F690'
            ],
            [
                'class' => 'bomb-off',
                'code'  => '\F6C4'
            ],
            [
                'class' => 'bone',
                'code'  => '\F0B9'
            ],
            [
                'class' => 'book',
                'code'  => '\F0BA'
            ],
            [
                'class' => 'book-lock',
                'code'  => '\F799'
            ],
            [
                'class' => 'book-lock-open',
                'code'  => '\F79A'
            ],
            [
                'class' => 'book-minus',
                'code'  => '\F5D9'
            ],
            [
                'class' => 'book-multiple',
                'code'  => '\F0BB'
            ],
            [
                'class' => 'book-multiple-minus',
                'code'  => '\FA93'
            ],
            [
                'class' => 'book-multiple-plus',
                'code'  => '\FA94'
            ],
            [
                'class' => 'book-multiple-remove',
                'code'  => '\FA95'
            ],
            [
                'class' => 'book-multiple-variant',
                'code'  => '\F0BC'
            ],
            [
                'class' => 'book-open',
                'code'  => '\F0BD'
            ],
            [
                'class' => 'book-open-outline',
                'code'  => '\FB3F'
            ],
            [
                'class' => 'book-open-page-variant',
                'code'  => '\F5DA'
            ],
            [
                'class' => 'book-open-variant',
                'code'  => '\F0BE'
            ],
            [
                'class' => 'book-outline',
                'code'  => '\FB40'
            ],
            [
                'class' => 'book-plus',
                'code'  => '\F5DB'
            ],
            [
                'class' => 'book-remove',
                'code'  => '\FA96'
            ],
            [
                'class' => 'book-variant',
                'code'  => '\F0BF'
            ],
            [
                'class' => 'bookmark',
                'code'  => '\F0C0'
            ],
            [
                'class' => 'bookmark-check',
                'code'  => '\F0C1'
            ],
            [
                'class' => 'bookmark-minus',
                'code'  => '\F9CB'
            ],
            [
                'class' => 'bookmark-minus-outline',
                'code'  => '\F9CC'
            ],
            [
                'class' => 'bookmark-music',
                'code'  => '\F0C2'
            ],
            [
                'class' => 'bookmark-off',
                'code'  => '\F9CD'
            ],
            [
                'class' => 'bookmark-off-outline',
                'code'  => '\F9CE'
            ],
            [
                'class' => 'bookmark-outline',
                'code'  => '\F0C3'
            ],
            [
                'class' => 'bookmark-plus',
                'code'  => '\F0C5'
            ],
            [
                'class' => 'bookmark-plus-outline',
                'code'  => '\F0C4'
            ],
            [
                'class' => 'bookmark-remove',
                'code'  => '\F0C6'
            ],
            [
                'class' => 'boombox',
                'code'  => '\F5DC'
            ],
            [
                'class' => 'bootstrap',
                'code'  => '\F6C5'
            ],
            [
                'class' => 'border-all',
                'code'  => '\F0C7'
            ],
            [
                'class' => 'border-all-variant',
                'code'  => '\F8A0'
            ],
            [
                'class' => 'border-bottom',
                'code'  => '\F0C8'
            ],
            [
                'class' => 'border-bottom-variant',
                'code'  => '\F8A1'
            ],
            [
                'class' => 'border-color',
                'code'  => '\F0C9'
            ],
            [
                'class' => 'border-horizontal',
                'code'  => '\F0CA'
            ],
            [
                'class' => 'border-inside',
                'code'  => '\F0CB'
            ],
            [
                'class' => 'border-left',
                'code'  => '\F0CC'
            ],
            [
                'class' => 'border-left-variant',
                'code'  => '\F8A2'
            ],
            [
                'class' => 'border-none',
                'code'  => '\F0CD'
            ],
            [
                'class' => 'border-none-variant',
                'code'  => '\F8A3'
            ],
            [
                'class' => 'border-outside',
                'code'  => '\F0CE'
            ],
            [
                'class' => 'border-right',
                'code'  => '\F0CF'
            ],
            [
                'class' => 'border-right-variant',
                'code'  => '\F8A4'
            ],
            [
                'class' => 'border-style',
                'code'  => '\F0D0'
            ],
            [
                'class' => 'border-top',
                'code'  => '\F0D1'
            ],
            [
                'class' => 'border-top-variant',
                'code'  => '\F8A5'
            ],
            [
                'class' => 'border-vertical',
                'code'  => '\F0D2'
            ],
            [
                'class' => 'bottle-wine',
                'code'  => '\F853'
            ],
            [
                'class' => 'bow-tie',
                'code'  => '\F677'
            ],
            [
                'class' => 'bowl',
                'code'  => '\F617'
            ],
            [
                'class' => 'bowling',
                'code'  => '\F0D3'
            ],
            [
                'class' => 'box',
                'code'  => '\F0D4'
            ],
            [
                'class' => 'box-cutter',
                'code'  => '\F0D5'
            ],
            [
                'class' => 'box-shadow',
                'code'  => '\F637'
            ],
            [
                'class' => 'boxing-glove',
                'code'  => '\FB41'
            ],
            [
                'class' => 'braille',
                'code'  => '\F9CF'
            ],
            [
                'class' => 'brain',
                'code'  => '\F9D0'
            ],
            [
                'class' => 'bread-slice',
                'code'  => '\FCCA'
            ],
            [
                'class' => 'bread-slice-outline',
                'code'  => '\FCCB'
            ],
            [
                'class' => 'bridge',
                'code'  => '\F618'
            ],
            [
                'class' => 'briefcase',
                'code'  => '\F0D6'
            ],
            [
                'class' => 'briefcase-account',
                'code'  => '\FCCC'
            ],
            [
                'class' => 'briefcase-account-outline',
                'code'  => '\FCCD'
            ],
            [
                'class' => 'briefcase-check',
                'code'  => '\F0D7'
            ],
            [
                'class' => 'briefcase-download',
                'code'  => '\F0D8'
            ],
            [
                'class' => 'briefcase-download-outline',
                'code'  => '\FC19'
            ],
            [
                'class' => 'briefcase-edit',
                'code'  => '\FA97'
            ],
            [
                'class' => 'briefcase-edit-outline',
                'code'  => '\FC1A'
            ],
            [
                'class' => 'briefcase-minus',
                'code'  => '\FA29'
            ],
            [
                'class' => 'briefcase-minus-outline',
                'code'  => '\FC1B'
            ],
            [
                'class' => 'briefcase-outline',
                'code'  => '\F813'
            ],
            [
                'class' => 'briefcase-plus',
                'code'  => '\FA2A'
            ],
            [
                'class' => 'briefcase-plus-outline',
                'code'  => '\FC1C'
            ],
            [
                'class' => 'briefcase-remove',
                'code'  => '\FA2B'
            ],
            [
                'class' => 'briefcase-remove-outline',
                'code'  => '\FC1D'
            ],
            [
                'class' => 'briefcase-search',
                'code'  => '\FA2C'
            ],
            [
                'class' => 'briefcase-search-outline',
                'code'  => '\FC1E'
            ],
            [
                'class' => 'briefcase-upload',
                'code'  => '\F0D9'
            ],
            [
                'class' => 'briefcase-upload-outline',
                'code'  => '\FC1F'
            ],
            [
                'class' => 'brightness-1',
                'code'  => '\F0DA'
            ],
            [
                'class' => 'brightness-2',
                'code'  => '\F0DB'
            ],
            [
                'class' => 'brightness-3',
                'code'  => '\F0DC'
            ],
            [
                'class' => 'brightness-4',
                'code'  => '\F0DD'
            ],
            [
                'class' => 'brightness-5',
                'code'  => '\F0DE'
            ],
            [
                'class' => 'brightness-6',
                'code'  => '\F0DF'
            ],
            [
                'class' => 'brightness-7',
                'code'  => '\F0E0'
            ],
            [
                'class' => 'brightness-auto',
                'code'  => '\F0E1'
            ],
            [
                'class' => 'brightness-percent',
                'code'  => '\FCCE'
            ],
            [
                'class' => 'broom',
                'code'  => '\F0E2'
            ],
            [
                'class' => 'brush',
                'code'  => '\F0E3'
            ],
            [
                'class' => 'buddhism',
                'code'  => '\F94A'
            ],
            [
                'class' => 'buffer',
                'code'  => '\F619'
            ],
            [
                'class' => 'bug',
                'code'  => '\F0E4'
            ],
            [
                'class' => 'bug-check',
                'code'  => '\FA2D'
            ],
            [
                'class' => 'bug-check-outline',
                'code'  => '\FA2E'
            ],
            [
                'class' => 'bug-outline',
                'code'  => '\FA2F'
            ],
            [
                'class' => 'bulldozer',
                'code'  => '\FB07'
            ],
            [
                'class' => 'bullet',
                'code'  => '\FCCF'
            ],
            [
                'class' => 'bulletin-board',
                'code'  => '\F0E5'
            ],
            [
                'class' => 'bullhorn',
                'code'  => '\F0E6'
            ],
            [
                'class' => 'bullhorn-outline',
                'code'  => '\FB08'
            ],
            [
                'class' => 'bullseye',
                'code'  => '\F5DD'
            ],
            [
                'class' => 'bullseye-arrow',
                'code'  => '\F8C8'
            ],
            [
                'class' => 'bus',
                'code'  => '\F0E7'
            ],
            [
                'class' => 'bus-alert',
                'code'  => '\FA98'
            ],
            [
                'class' => 'bus-articulated-end',
                'code'  => '\F79B'
            ],
            [
                'class' => 'bus-articulated-front',
                'code'  => '\F79C'
            ],
            [
                'class' => 'bus-clock',
                'code'  => '\F8C9'
            ],
            [
                'class' => 'bus-double-decker',
                'code'  => '\F79D'
            ],
            [
                'class' => 'bus-school',
                'code'  => '\F79E'
            ],
            [
                'class' => 'bus-side',
                'code'  => '\F79F'
            ],
            [
                'class' => 'cached',
                'code'  => '\F0E8'
            ],
            [
                'class' => 'cake',
                'code'  => '\F0E9'
            ],
            [
                'class' => 'cake-layered',
                'code'  => '\F0EA'
            ],
            [
                'class' => 'cake-variant',
                'code'  => '\F0EB'
            ],
            [
                'class' => 'calculator',
                'code'  => '\F0EC'
            ],
            [
                'class' => 'calculator-variant',
                'code'  => '\FA99'
            ],
            [
                'class' => 'calendar',
                'code'  => '\F0ED'
            ],
            [
                'class' => 'calendar-alert',
                'code'  => '\FA30'
            ],
            [
                'class' => 'calendar-blank',
                'code'  => '\F0EE'
            ],
            [
                'class' => 'calendar-blank-outline',
                'code'  => '\FB42'
            ],
            [
                'class' => 'calendar-check',
                'code'  => '\F0EF'
            ],
            [
                'class' => 'calendar-check-outline',
                'code'  => '\FC20'
            ],
            [
                'class' => 'calendar-clock',
                'code'  => '\F0F0'
            ],
            [
                'class' => 'calendar-edit',
                'code'  => '\F8A6'
            ],
            [
                'class' => 'calendar-export',
                'code'  => '\FB09'
            ],
            [
                'class' => 'calendar-heart',
                'code'  => '\F9D1'
            ],
            [
                'class' => 'calendar-import',
                'code'  => '\FB0A'
            ],
            [
                'class' => 'calendar-multiple',
                'code'  => '\F0F1'
            ],
            [
                'class' => 'calendar-multiple-check',
                'code'  => '\F0F2'
            ],
            [
                'class' => 'calendar-multiselect',
                'code'  => '\FA31'
            ],
            [
                'class' => 'calendar-outline',
                'code'  => '\FB43'
            ],
            [
                'class' => 'calendar-plus',
                'code'  => '\F0F3'
            ],
            [
                'class' => 'calendar-question',
                'code'  => '\F691'
            ],
            [
                'class' => 'calendar-range',
                'code'  => '\F678'
            ],
            [
                'class' => 'calendar-range-outline',
                'code'  => '\FB44'
            ],
            [
                'class' => 'calendar-remove',
                'code'  => '\F0F4'
            ],
            [
                'class' => 'calendar-remove-outline',
                'code'  => '\FC21'
            ],
            [
                'class' => 'calendar-search',
                'code'  => '\F94B'
            ],
            [
                'class' => 'calendar-star',
                'code'  => '\F9D2'
            ],
            [
                'class' => 'calendar-text',
                'code'  => '\F0F5'
            ],
            [
                'class' => 'calendar-text-outline',
                'code'  => '\FC22'
            ],
            [
                'class' => 'calendar-today',
                'code'  => '\F0F6'
            ],
            [
                'class' => 'calendar-week',
                'code'  => '\FA32'
            ],
            [
                'class' => 'calendar-week-begin',
                'code'  => '\FA33'
            ],
            [
                'class' => 'call-made',
                'code'  => '\F0F7'
            ],
            [
                'class' => 'call-merge',
                'code'  => '\F0F8'
            ],
            [
                'class' => 'call-missed',
                'code'  => '\F0F9'
            ],
            [
                'class' => 'call-received',
                'code'  => '\F0FA'
            ],
            [
                'class' => 'call-split',
                'code'  => '\F0FB'
            ],
            [
                'class' => 'camcorder',
                'code'  => '\F0FC'
            ],
            [
                'class' => 'camcorder-box',
                'code'  => '\F0FD'
            ],
            [
                'class' => 'camcorder-box-off',
                'code'  => '\F0FE'
            ],
            [
                'class' => 'camcorder-off',
                'code'  => '\F0FF'
            ],
            [
                'class' => 'camera',
                'code'  => '\F100'
            ],
            [
                'class' => 'camera-account',
                'code'  => '\F8CA'
            ],
            [
                'class' => 'camera-burst',
                'code'  => '\F692'
            ],
            [
                'class' => 'camera-control',
                'code'  => '\FB45'
            ],
            [
                'class' => 'camera-enhance',
                'code'  => '\F101'
            ],
            [
                'class' => 'camera-enhance-outline',
                'code'  => '\FB46'
            ],
            [
                'class' => 'camera-front',
                'code'  => '\F102'
            ],
            [
                'class' => 'camera-front-variant',
                'code'  => '\F103'
            ],
            [
                'class' => 'camera-gopro',
                'code'  => '\F7A0'
            ],
            [
                'class' => 'camera-image',
                'code'  => '\F8CB'
            ],
            [
                'class' => 'camera-iris',
                'code'  => '\F104'
            ],
            [
                'class' => 'camera-metering-center',
                'code'  => '\F7A1'
            ],
            [
                'class' => 'camera-metering-matrix',
                'code'  => '\F7A2'
            ],
            [
                'class' => 'camera-metering-partial',
                'code'  => '\F7A3'
            ],
            [
                'class' => 'camera-metering-spot',
                'code'  => '\F7A4'
            ],
            [
                'class' => 'camera-off',
                'code'  => '\F5DF'
            ],
            [
                'class' => 'camera-party-mode',
                'code'  => '\F105'
            ],
            [
                'class' => 'camera-rear',
                'code'  => '\F106'
            ],
            [
                'class' => 'camera-rear-variant',
                'code'  => '\F107'
            ],
            [
                'class' => 'camera-switch',
                'code'  => '\F108'
            ],
            [
                'class' => 'camera-timer',
                'code'  => '\F109'
            ],
            [
                'class' => 'cancel',
                'code'  => '\F739'
            ],
            [
                'class' => 'candle',
                'code'  => '\F5E2'
            ],
            [
                'class' => 'candycane',
                'code'  => '\F10A'
            ],
            [
                'class' => 'cannabis',
                'code'  => '\F7A5'
            ],
            [
                'class' => 'caps-lock',
                'code'  => '\FA9A'
            ],
            [
                'class' => 'car',
                'code'  => '\F10B'
            ],
            [
                'class' => 'car-battery',
                'code'  => '\F10C'
            ],
            [
                'class' => 'car-brake-abs',
                'code'  => '\FC23'
            ],
            [
                'class' => 'car-brake-parking',
                'code'  => '\FC24'
            ],
            [
                'class' => 'car-connected',
                'code'  => '\F10D'
            ],
            [
                'class' => 'car-convertible',
                'code'  => '\F7A6'
            ],
            [
                'class' => 'car-door',
                'code'  => '\FB47'
            ],
            [
                'class' => 'car-electric',
                'code'  => '\FB48'
            ],
            [
                'class' => 'car-esp',
                'code'  => '\FC25'
            ],
            [
                'class' => 'car-estate',
                'code'  => '\F7A7'
            ],
            [
                'class' => 'car-hatchback',
                'code'  => '\F7A8'
            ],
            [
                'class' => 'car-key',
                'code'  => '\FB49'
            ],
            [
                'class' => 'car-light-dimmed',
                'code'  => '\FC26'
            ],
            [
                'class' => 'car-light-fog',
                'code'  => '\FC27'
            ],
            [
                'class' => 'car-light-high',
                'code'  => '\FC28'
            ],
            [
                'class' => 'car-limousine',
                'code'  => '\F8CC'
            ],
            [
                'class' => 'car-multiple',
                'code'  => '\FB4A'
            ],
            [
                'class' => 'car-pickup',
                'code'  => '\F7A9'
            ],
            [
                'class' => 'car-side',
                'code'  => '\F7AA'
            ],
            [
                'class' => 'car-sports',
                'code'  => '\F7AB'
            ],
            [
                'class' => 'car-tire-alert',
                'code'  => '\FC29'
            ],
            [
                'class' => 'car-wash',
                'code'  => '\F10E'
            ],
            [
                'class' => 'caravan',
                'code'  => '\F7AC'
            ],
            [
                'class' => 'card',
                'code'  => '\FB4B'
            ],
            [
                'class' => 'card-bulleted',
                'code'  => '\FB4C'
            ],
            [
                'class' => 'card-bulleted-off',
                'code'  => '\FB4D'
            ],
            [
                'class' => 'card-bulleted-off-outline',
                'code'  => '\FB4E'
            ],
            [
                'class' => 'card-bulleted-outline',
                'code'  => '\FB4F'
            ],
            [
                'class' => 'card-bulleted-settings',
                'code'  => '\FB50'
            ],
            [
                'class' => 'card-bulleted-settings-outline',
                'code'  => '\FB51'
            ],
            [
                'class' => 'card-outline',
                'code'  => '\FB52'
            ],
            [
                'class' => 'card-text',
                'code'  => '\FB53'
            ],
            [
                'class' => 'card-text-outline',
                'code'  => '\FB54'
            ],
            [
                'class' => 'cards',
                'code'  => '\F638'
            ],
            [
                'class' => 'cards-club',
                'code'  => '\F8CD'
            ],
            [
                'class' => 'cards-diamond',
                'code'  => '\F8CE'
            ],
            [
                'class' => 'cards-heart',
                'code'  => '\F8CF'
            ],
            [
                'class' => 'cards-outline',
                'code'  => '\F639'
            ],
            [
                'class' => 'cards-playing-outline',
                'code'  => '\F63A'
            ],
            [
                'class' => 'cards-spade',
                'code'  => '\F8D0'
            ],
            [
                'class' => 'cards-variant',
                'code'  => '\F6C6'
            ],
            [
                'class' => 'carrot',
                'code'  => '\F10F'
            ],
            [
                'class' => 'cart',
                'code'  => '\F110'
            ],
            [
                'class' => 'cart-arrow-right',
                'code'  => '\FC2A'
            ],
            [
                'class' => 'cart-off',
                'code'  => '\F66B'
            ],
            [
                'class' => 'cart-outline',
                'code'  => '\F111'
            ],
            [
                'class' => 'cart-plus',
                'code'  => '\F112'
            ],
            [
                'class' => 'case-sensitive-alt',
                'code'  => '\F113'
            ],
            [
                'class' => 'cash',
                'code'  => '\F114'
            ],
            [
                'class' => 'cash-100',
                'code'  => '\F115'
            ],
            [
                'class' => 'cash-multiple',
                'code'  => '\F116'
            ],
            [
                'class' => 'cash-refund',
                'code'  => '\FA9B'
            ],
            [
                'class' => 'cash-register',
                'code'  => '\FCD0'
            ],
            [
                'class' => 'cash-usd',
                'code'  => '\F117'
            ],
            [
                'class' => 'cassette',
                'code'  => '\F9D3'
            ],
            [
                'class' => 'cast',
                'code'  => '\F118'
            ],
            [
                'class' => 'cast-connected',
                'code'  => '\F119'
            ],
            [
                'class' => 'cast-off',
                'code'  => '\F789'
            ],
            [
                'class' => 'castle',
                'code'  => '\F11A'
            ],
            [
                'class' => 'cat',
                'code'  => '\F11B'
            ],
            [
                'class' => 'cctv',
                'code'  => '\F7AD'
            ],
            [
                'class' => 'ceiling-light',
                'code'  => '\F768'
            ],
            [
                'class' => 'cellphone',
                'code'  => '\F11C'
            ],
            [
                'class' => 'cellphone-android',
                'code'  => '\F11D'
            ],
            [
                'class' => 'cellphone-arrow-down',
                'code'  => '\F9D4'
            ],
            [
                'class' => 'cellphone-basic',
                'code'  => '\F11E'
            ],
            [
                'class' => 'cellphone-dock',
                'code'  => '\F11F'
            ],
            [
                'class' => 'cellphone-erase',
                'code'  => '\F94C'
            ],
            [
                'class' => 'cellphone-iphone',
                'code'  => '\F120'
            ],
            [
                'class' => 'cellphone-key',
                'code'  => '\F94D'
            ],
            [
                'class' => 'cellphone-link',
                'code'  => '\F121'
            ],
            [
                'class' => 'cellphone-link-off',
                'code'  => '\F122'
            ],
            [
                'class' => 'cellphone-lock',
                'code'  => '\F94E'
            ],
            [
                'class' => 'cellphone-message',
                'code'  => '\F8D2'
            ],
            [
                'class' => 'cellphone-off',
                'code'  => '\F94F'
            ],
            [
                'class' => 'cellphone-screenshot',
                'code'  => '\FA34'
            ],
            [
                'class' => 'cellphone-settings',
                'code'  => '\F123'
            ],
            [
                'class' => 'cellphone-settings-variant',
                'code'  => '\F950'
            ],
            [
                'class' => 'cellphone-sound',
                'code'  => '\F951'
            ],
            [
                'class' => 'cellphone-text',
                'code'  => '\F8D1'
            ],
            [
                'class' => 'cellphone-wireless',
                'code'  => '\F814'
            ],
            [
                'class' => 'celtic-cross',
                'code'  => '\FCD1'
            ],
            [
                'class' => 'certificate',
                'code'  => '\F124'
            ],
            [
                'class' => 'chair-school',
                'code'  => '\F125'
            ],
            [
                'class' => 'charity',
                'code'  => '\FC2B'
            ],
            [
                'class' => 'chart-arc',
                'code'  => '\F126'
            ],
            [
                'class' => 'chart-areaspline',
                'code'  => '\F127'
            ],
            [
                'class' => 'chart-bar',
                'code'  => '\F128'
            ],
            [
                'class' => 'chart-bar-stacked',
                'code'  => '\F769'
            ],
            [
                'class' => 'chart-bell-curve',
                'code'  => '\FC2C'
            ],
            [
                'class' => 'chart-bubble',
                'code'  => '\F5E3'
            ],
            [
                'class' => 'chart-donut',
                'code'  => '\F7AE'
            ],
            [
                'class' => 'chart-donut-variant',
                'code'  => '\F7AF'
            ],
            [
                'class' => 'chart-gantt',
                'code'  => '\F66C'
            ],
            [
                'class' => 'chart-histogram',
                'code'  => '\F129'
            ],
            [
                'class' => 'chart-line',
                'code'  => '\F12A'
            ],
            [
                'class' => 'chart-line-stacked',
                'code'  => '\F76A'
            ],
            [
                'class' => 'chart-line-variant',
                'code'  => '\F7B0'
            ],
            [
                'class' => 'chart-multiline',
                'code'  => '\F8D3'
            ],
            [
                'class' => 'chart-pie',
                'code'  => '\F12B'
            ],
            [
                'class' => 'chart-scatterplot-hexbin',
                'code'  => '\F66D'
            ],
            [
                'class' => 'chart-timeline',
                'code'  => '\F66E'
            ],
            [
                'class' => 'chat',
                'code'  => '\FB55'
            ],
            [
                'class' => 'chat-alert',
                'code'  => '\FB56'
            ],
            [
                'class' => 'chat-processing',
                'code'  => '\FB57'
            ],
            [
                'class' => 'check',
                'code'  => '\F12C'
            ],
            [
                'class' => 'check-all',
                'code'  => '\F12D'
            ],
            [
                'class' => 'check-box-multiple-outline',
                'code'  => '\FC2D'
            ],
            [
                'class' => 'check-box-outline',
                'code'  => '\FC2E'
            ],
            [
                'class' => 'check-circle',
                'code'  => '\F5E0'
            ],
            [
                'class' => 'check-circle-outline',
                'code'  => '\F5E1'
            ],
            [
                'class' => 'check-decagram',
                'code'  => '\F790'
            ],
            [
                'class' => 'check-network',
                'code'  => '\FC2F'
            ],
            [
                'class' => 'check-network-outline',
                'code'  => '\FC30'
            ],
            [
                'class' => 'check-outline',
                'code'  => '\F854'
            ],
            [
                'class' => 'checkbook',
                'code'  => '\FA9C'
            ],
            [
                'class' => 'checkbox-blank',
                'code'  => '\F12E'
            ],
            [
                'class' => 'checkbox-blank-circle',
                'code'  => '\F12F'
            ],
            [
                'class' => 'checkbox-blank-circle-outline',
                'code'  => '\F130'
            ],
            [
                'class' => 'checkbox-blank-outline',
                'code'  => '\F131'
            ],
            [
                'class' => 'checkbox-intermediate',
                'code'  => '\F855'
            ],
            [
                'class' => 'checkbox-marked',
                'code'  => '\F132'
            ],
            [
                'class' => 'checkbox-marked-circle',
                'code'  => '\F133'
            ],
            [
                'class' => 'checkbox-marked-circle-outline',
                'code'  => '\F134'
            ],
            [
                'class' => 'checkbox-marked-outline',
                'code'  => '\F135'
            ],
            [
                'class' => 'checkbox-multiple-blank',
                'code'  => '\F136'
            ],
            [
                'class' => 'checkbox-multiple-blank-circle',
                'code'  => '\F63B'
            ],
            [
                'class' => 'checkbox-multiple-blank-circle-outline',
                'code'  => '\F63C'
            ],
            [
                'class' => 'checkbox-multiple-blank-outline',
                'code'  => '\F137'
            ],
            [
                'class' => 'checkbox-multiple-marked',
                'code'  => '\F138'
            ],
            [
                'class' => 'checkbox-multiple-marked-circle',
                'code'  => '\F63D'
            ],
            [
                'class' => 'checkbox-multiple-marked-circle-outline',
                'code'  => '\F63E'
            ],
            [
                'class' => 'checkbox-multiple-marked-outline',
                'code'  => '\F139'
            ],
            [
                'class' => 'checkerboard',
                'code'  => '\F13A'
            ],
            [
                'class' => 'chef-hat',
                'code'  => '\FB58'
            ],
            [
                'class' => 'chemical-weapon',
                'code'  => '\F13B'
            ],
            [
                'class' => 'chess-bishop',
                'code'  => '\F85B'
            ],
            [
                'class' => 'chess-king',
                'code'  => '\F856'
            ],
            [
                'class' => 'chess-knight',
                'code'  => '\F857'
            ],
            [
                'class' => 'chess-pawn',
                'code'  => '\F858'
            ],
            [
                'class' => 'chess-queen',
                'code'  => '\F859'
            ],
            [
                'class' => 'chess-rook',
                'code'  => '\F85A'
            ],
            [
                'class' => 'chevron-double-down',
                'code'  => '\F13C'
            ],
            [
                'class' => 'chevron-double-left',
                'code'  => '\F13D'
            ],
            [
                'class' => 'chevron-double-right',
                'code'  => '\F13E'
            ],
            [
                'class' => 'chevron-double-up',
                'code'  => '\F13F'
            ],
            [
                'class' => 'chevron-down',
                'code'  => '\F140'
            ],
            [
                'class' => 'chevron-down-box',
                'code'  => '\F9D5'
            ],
            [
                'class' => 'chevron-down-box-outline',
                'code'  => '\F9D6'
            ],
            [
                'class' => 'chevron-down-circle',
                'code'  => '\FB0B'
            ],
            [
                'class' => 'chevron-down-circle-outline',
                'code'  => '\FB0C'
            ],
            [
                'class' => 'chevron-left',
                'code'  => '\F141'
            ],
            [
                'class' => 'chevron-left-box',
                'code'  => '\F9D7'
            ],
            [
                'class' => 'chevron-left-box-outline',
                'code'  => '\F9D8'
            ],
            [
                'class' => 'chevron-left-circle',
                'code'  => '\FB0D'
            ],
            [
                'class' => 'chevron-left-circle-outline',
                'code'  => '\FB0E'
            ],
            [
                'class' => 'chevron-right',
                'code'  => '\F142'
            ],
            [
                'class' => 'chevron-right-box',
                'code'  => '\F9D9'
            ],
            [
                'class' => 'chevron-right-box-outline',
                'code'  => '\F9DA'
            ],
            [
                'class' => 'chevron-right-circle',
                'code'  => '\FB0F'
            ],
            [
                'class' => 'chevron-right-circle-outline',
                'code'  => '\FB10'
            ],
            [
                'class' => 'chevron-up',
                'code'  => '\F143'
            ],
            [
                'class' => 'chevron-up-box',
                'code'  => '\F9DB'
            ],
            [
                'class' => 'chevron-up-box-outline',
                'code'  => '\F9DC'
            ],
            [
                'class' => 'chevron-up-circle',
                'code'  => '\FB11'
            ],
            [
                'class' => 'chevron-up-circle-outline',
                'code'  => '\FB12'
            ],
            [
                'class' => 'chili-hot',
                'code'  => '\F7B1'
            ],
            [
                'class' => 'chili-medium',
                'code'  => '\F7B2'
            ],
            [
                'class' => 'chili-mild',
                'code'  => '\F7B3'
            ],
            [
                'class' => 'chip',
                'code'  => '\F61A'
            ],
            [
                'class' => 'christianity',
                'code'  => '\F952'
            ],
            [
                'class' => 'christianity-outline',
                'code'  => '\FCD2'
            ],
            [
                'class' => 'church',
                'code'  => '\F144'
            ],
            [
                'class' => 'circle',
                'code'  => '\F764'
            ],
            [
                'class' => 'circle-edit-outline',
                'code'  => '\F8D4'
            ],
            [
                'class' => 'circle-medium',
                'code'  => '\F9DD'
            ],
            [
                'class' => 'circle-outline',
                'code'  => '\F765'
            ],
            [
                'class' => 'circle-slice-1',
                'code'  => '\FA9D'
            ],
            [
                'class' => 'circle-slice-2',
                'code'  => '\FA9E'
            ],
            [
                'class' => 'circle-slice-3',
                'code'  => '\FA9F'
            ],
            [
                'class' => 'circle-slice-4',
                'code'  => '\FAA0'
            ],
            [
                'class' => 'circle-slice-5',
                'code'  => '\FAA1'
            ],
            [
                'class' => 'circle-slice-6',
                'code'  => '\FAA2'
            ],
            [
                'class' => 'circle-slice-7',
                'code'  => '\FAA3'
            ],
            [
                'class' => 'circle-slice-8',
                'code'  => '\FAA4'
            ],
            [
                'class' => 'circle-small',
                'code'  => '\F9DE'
            ],
            [
                'class' => 'cisco-webex',
                'code'  => '\F145'
            ],
            [
                'class' => 'city',
                'code'  => '\F146'
            ],
            [
                'class' => 'city-variant',
                'code'  => '\FA35'
            ],
            [
                'class' => 'city-variant-outline',
                'code'  => '\FA36'
            ],
            [
                'class' => 'clipboard',
                'code'  => '\F147'
            ],
            [
                'class' => 'clipboard-account',
                'code'  => '\F148'
            ],
            [
                'class' => 'clipboard-account-outline',
                'code'  => '\FC31'
            ],
            [
                'class' => 'clipboard-alert',
                'code'  => '\F149'
            ],
            [
                'class' => 'clipboard-alert-outline',
                'code'  => '\FCD3'
            ],
            [
                'class' => 'clipboard-arrow-down',
                'code'  => '\F14A'
            ],
            [
                'class' => 'clipboard-arrow-down-outline',
                'code'  => '\FC32'
            ],
            [
                'class' => 'clipboard-arrow-left',
                'code'  => '\F14B'
            ],
            [
                'class' => 'clipboard-arrow-left-outline',
                'code'  => '\FCD4'
            ],
            [
                'class' => 'clipboard-arrow-right',
                'code'  => '\FCD5'
            ],
            [
                'class' => 'clipboard-arrow-right-outline',
                'code'  => '\FCD6'
            ],
            [
                'class' => 'clipboard-arrow-up',
                'code'  => '\FC33'
            ],
            [
                'class' => 'clipboard-arrow-up-outline',
                'code'  => '\FC34'
            ],
            [
                'class' => 'clipboard-check',
                'code'  => '\F14C'
            ],
            [
                'class' => 'clipboard-check-outline',
                'code'  => '\F8A7'
            ],
            [
                'class' => 'clipboard-flow',
                'code'  => '\F6C7'
            ],
            [
                'class' => 'clipboard-outline',
                'code'  => '\F14D'
            ],
            [
                'class' => 'clipboard-play',
                'code'  => '\FC35'
            ],
            [
                'class' => 'clipboard-play-outline',
                'code'  => '\FC36'
            ],
            [
                'class' => 'clipboard-plus',
                'code'  => '\F750'
            ],
            [
                'class' => 'clipboard-pulse',
                'code'  => '\F85C'
            ],
            [
                'class' => 'clipboard-pulse-outline',
                'code'  => '\F85D'
            ],
            [
                'class' => 'clipboard-text',
                'code'  => '\F14E'
            ],
            [
                'class' => 'clipboard-text-outline',
                'code'  => '\FA37'
            ],
            [
                'class' => 'clipboard-text-play',
                'code'  => '\FC37'
            ],
            [
                'class' => 'clipboard-text-play-outline',
                'code'  => '\FC38'
            ],
            [
                'class' => 'clippy',
                'code'  => '\F14F'
            ],
            [
                'class' => 'clock',
                'code'  => '\F953'
            ],
            [
                'class' => 'clock-alert',
                'code'  => '\F954'
            ],
            [
                'class' => 'clock-alert-outline',
                'code'  => '\F5CE'
            ],
            [
                'class' => 'clock-end',
                'code'  => '\F151'
            ],
            [
                'class' => 'clock-fast',
                'code'  => '\F152'
            ],
            [
                'class' => 'clock-in',
                'code'  => '\F153'
            ],
            [
                'class' => 'clock-out',
                'code'  => '\F154'
            ],
            [
                'class' => 'clock-outline',
                'code'  => '\F150'
            ],
            [
                'class' => 'clock-start',
                'code'  => '\F155'
            ],
            [
                'class' => 'close',
                'code'  => '\F156'
            ],
            [
                'class' => 'close-box',
                'code'  => '\F157'
            ],
            [
                'class' => 'close-box-multiple',
                'code'  => '\FC39'
            ],
            [
                'class' => 'close-box-multiple-outline',
                'code'  => '\FC3A'
            ],
            [
                'class' => 'close-box-outline',
                'code'  => '\F158'
            ],
            [
                'class' => 'close-circle',
                'code'  => '\F159'
            ],
            [
                'class' => 'close-circle-outline',
                'code'  => '\F15A'
            ],
            [
                'class' => 'close-network',
                'code'  => '\F15B'
            ],
            [
                'class' => 'close-network-outline',
                'code'  => '\FC3B'
            ],
            [
                'class' => 'close-octagon',
                'code'  => '\F15C'
            ],
            [
                'class' => 'close-octagon-outline',
                'code'  => '\F15D'
            ],
            [
                'class' => 'close-outline',
                'code'  => '\F6C8'
            ],
            [
                'class' => 'closed-caption',
                'code'  => '\F15E'
            ],
            [
                'class' => 'cloud',
                'code'  => '\F15F'
            ],
            [
                'class' => 'cloud-alert',
                'code'  => '\F9DF'
            ],
            [
                'class' => 'cloud-braces',
                'code'  => '\F7B4'
            ],
            [
                'class' => 'cloud-check',
                'code'  => '\F160'
            ],
            [
                'class' => 'cloud-circle',
                'code'  => '\F161'
            ],
            [
                'class' => 'cloud-download',
                'code'  => '\F162'
            ],
            [
                'class' => 'cloud-download-outline',
                'code'  => '\FB59'
            ],
            [
                'class' => 'cloud-off-outline',
                'code'  => '\F164'
            ],
            [
                'class' => 'cloud-outline',
                'code'  => '\F163'
            ],
            [
                'class' => 'cloud-print',
                'code'  => '\F165'
            ],
            [
                'class' => 'cloud-print-outline',
                'code'  => '\F166'
            ],
            [
                'class' => 'cloud-question',
                'code'  => '\FA38'
            ],
            [
                'class' => 'cloud-search',
                'code'  => '\F955'
            ],
            [
                'class' => 'cloud-search-outline',
                'code'  => '\F956'
            ],
            [
                'class' => 'cloud-sync',
                'code'  => '\F63F'
            ],
            [
                'class' => 'cloud-tags',
                'code'  => '\F7B5'
            ],
            [
                'class' => 'cloud-upload',
                'code'  => '\F167'
            ],
            [
                'class' => 'cloud-upload-outline',
                'code'  => '\FB5A'
            ],
            [
                'class' => 'clover',
                'code'  => '\F815'
            ],
            [
                'class' => 'code-array',
                'code'  => '\F168'
            ],
            [
                'class' => 'code-braces',
                'code'  => '\F169'
            ],
            [
                'class' => 'code-brackets',
                'code'  => '\F16A'
            ],
            [
                'class' => 'code-equal',
                'code'  => '\F16B'
            ],
            [
                'class' => 'code-greater-than',
                'code'  => '\F16C'
            ],
            [
                'class' => 'code-greater-than-or-equal',
                'code'  => '\F16D'
            ],
            [
                'class' => 'code-less-than',
                'code'  => '\F16E'
            ],
            [
                'class' => 'code-less-than-or-equal',
                'code'  => '\F16F'
            ],
            [
                'class' => 'code-not-equal',
                'code'  => '\F170'
            ],
            [
                'class' => 'code-not-equal-variant',
                'code'  => '\F171'
            ],
            [
                'class' => 'code-parentheses',
                'code'  => '\F172'
            ],
            [
                'class' => 'code-string',
                'code'  => '\F173'
            ],
            [
                'class' => 'code-tags',
                'code'  => '\F174'
            ],
            [
                'class' => 'code-tags-check',
                'code'  => '\F693'
            ],
            [
                'class' => 'codepen',
                'code'  => '\F175'
            ],
            [
                'class' => 'coffee',
                'code'  => '\F176'
            ],
            [
                'class' => 'coffee-outline',
                'code'  => '\F6C9'
            ],
            [
                'class' => 'coffee-to-go',
                'code'  => '\F177'
            ],
            [
                'class' => 'coffin',
                'code'  => '\FB5B'
            ],
            [
                'class' => 'cogs',
                'code'  => '\F8D5'
            ],
            [
                'class' => 'coin',
                'code'  => '\F178'
            ],
            [
                'class' => 'coins',
                'code'  => '\F694'
            ],
            [
                'class' => 'collage',
                'code'  => '\F640'
            ],
            [
                'class' => 'collapse-all',
                'code'  => '\FAA5'
            ],
            [
                'class' => 'collapse-all-outline',
                'code'  => '\FAA6'
            ],
            [
                'class' => 'color-helper',
                'code'  => '\F179'
            ],
            [
                'class' => 'comment',
                'code'  => '\F17A'
            ],
            [
                'class' => 'comment-account',
                'code'  => '\F17B'
            ],
            [
                'class' => 'comment-account-outline',
                'code'  => '\F17C'
            ],
            [
                'class' => 'comment-alert',
                'code'  => '\F17D'
            ],
            [
                'class' => 'comment-alert-outline',
                'code'  => '\F17E'
            ],
            [
                'class' => 'comment-arrow-left',
                'code'  => '\F9E0'
            ],
            [
                'class' => 'comment-arrow-left-outline',
                'code'  => '\F9E1'
            ],
            [
                'class' => 'comment-arrow-right',
                'code'  => '\F9E2'
            ],
            [
                'class' => 'comment-arrow-right-outline',
                'code'  => '\F9E3'
            ],
            [
                'class' => 'comment-check',
                'code'  => '\F17F'
            ],
            [
                'class' => 'comment-check-outline',
                'code'  => '\F180'
            ],
            [
                'class' => 'comment-eye',
                'code'  => '\FA39'
            ],
            [
                'class' => 'comment-eye-outline',
                'code'  => '\FA3A'
            ],
            [
                'class' => 'comment-multiple',
                'code'  => '\F85E'
            ],
            [
                'class' => 'comment-multiple-outline',
                'code'  => '\F181'
            ],
            [
                'class' => 'comment-outline',
                'code'  => '\F182'
            ],
            [
                'class' => 'comment-plus',
                'code'  => '\F9E4'
            ],
            [
                'class' => 'comment-plus-outline',
                'code'  => '\F183'
            ],
            [
                'class' => 'comment-processing',
                'code'  => '\F184'
            ],
            [
                'class' => 'comment-processing-outline',
                'code'  => '\F185'
            ],
            [
                'class' => 'comment-question',
                'code'  => '\F816'
            ],
            [
                'class' => 'comment-question-outline',
                'code'  => '\F186'
            ],
            [
                'class' => 'comment-remove',
                'code'  => '\F5DE'
            ],
            [
                'class' => 'comment-remove-outline',
                'code'  => '\F187'
            ],
            [
                'class' => 'comment-search',
                'code'  => '\FA3B'
            ],
            [
                'class' => 'comment-search-outline',
                'code'  => '\FA3C'
            ],
            [
                'class' => 'comment-text',
                'code'  => '\F188'
            ],
            [
                'class' => 'comment-text-multiple',
                'code'  => '\F85F'
            ],
            [
                'class' => 'comment-text-multiple-outline',
                'code'  => '\F860'
            ],
            [
                'class' => 'comment-text-outline',
                'code'  => '\F189'
            ],
            [
                'class' => 'compare',
                'code'  => '\F18A'
            ],
            [
                'class' => 'compass',
                'code'  => '\F18B'
            ],
            [
                'class' => 'compass-off',
                'code'  => '\FB5C'
            ],
            [
                'class' => 'compass-off-outline',
                'code'  => '\FB5D'
            ],
            [
                'class' => 'compass-outline',
                'code'  => '\F18C'
            ],
            [
                'class' => 'console',
                'code'  => '\F18D'
            ],
            [
                'class' => 'console-line',
                'code'  => '\F7B6'
            ],
            [
                'class' => 'console-network',
                'code'  => '\F8A8'
            ],
            [
                'class' => 'console-network-outline',
                'code'  => '\FC3C'
            ],
            [
                'class' => 'contact-mail',
                'code'  => '\F18E'
            ],
            [
                'class' => 'contacts',
                'code'  => '\F6CA'
            ],
            [
                'class' => 'contain',
                'code'  => '\FA3D'
            ],
            [
                'class' => 'contain-end',
                'code'  => '\FA3E'
            ],
            [
                'class' => 'contain-start',
                'code'  => '\FA3F'
            ],
            [
                'class' => 'content-copy',
                'code'  => '\F18F'
            ],
            [
                'class' => 'content-cut',
                'code'  => '\F190'
            ],
            [
                'class' => 'content-duplicate',
                'code'  => '\F191'
            ],
            [
                'class' => 'content-paste',
                'code'  => '\F192'
            ],
            [
                'class' => 'content-save',
                'code'  => '\F193'
            ],
            [
                'class' => 'content-save-all',
                'code'  => '\F194'
            ],
            [
                'class' => 'content-save-edit',
                'code'  => '\FCD7'
            ],
            [
                'class' => 'content-save-edit-outline',
                'code'  => '\FCD8'
            ],
            [
                'class' => 'content-save-outline',
                'code'  => '\F817'
            ],
            [
                'class' => 'content-save-settings',
                'code'  => '\F61B'
            ],
            [
                'class' => 'content-save-settings-outline',
                'code'  => '\FB13'
            ],
            [
                'class' => 'contrast',
                'code'  => '\F195'
            ],
            [
                'class' => 'contrast-box',
                'code'  => '\F196'
            ],
            [
                'class' => 'contrast-circle',
                'code'  => '\F197'
            ],
            [
                'class' => 'controller-classic',
                'code'  => '\FB5E'
            ],
            [
                'class' => 'controller-classic-outline',
                'code'  => '\FB5F'
            ],
            [
                'class' => 'cookie',
                'code'  => '\F198'
            ],
            [
                'class' => 'copyright',
                'code'  => '\F5E6'
            ],
            [
                'class' => 'cordova',
                'code'  => '\F957'
            ],
            [
                'class' => 'corn',
                'code'  => '\F7B7'
            ],
            [
                'class' => 'counter',
                'code'  => '\F199'
            ],
            [
                'class' => 'cow',
                'code'  => '\F19A'
            ],
            [
                'class' => 'crane',
                'code'  => '\F861'
            ],
            [
                'class' => 'creation',
                'code'  => '\F1C9'
            ],
            [
                'class' => 'credit-card',
                'code'  => '\F19B'
            ],
            [
                'class' => 'credit-card-multiple',
                'code'  => '\F19C'
            ],
            [
                'class' => 'credit-card-off',
                'code'  => '\F5E4'
            ],
            [
                'class' => 'credit-card-plus',
                'code'  => '\F675'
            ],
            [
                'class' => 'credit-card-refund',
                'code'  => '\FAA7'
            ],
            [
                'class' => 'credit-card-scan',
                'code'  => '\F19D'
            ],
            [
                'class' => 'credit-card-settings',
                'code'  => '\F8D6'
            ],
            [
                'class' => 'crop',
                'code'  => '\F19E'
            ],
            [
                'class' => 'crop-free',
                'code'  => '\F19F'
            ],
            [
                'class' => 'crop-landscape',
                'code'  => '\F1A0'
            ],
            [
                'class' => 'crop-portrait',
                'code'  => '\F1A1'
            ],
            [
                'class' => 'crop-rotate',
                'code'  => '\F695'
            ],
            [
                'class' => 'crop-square',
                'code'  => '\F1A2'
            ],
            [
                'class' => 'crosshairs',
                'code'  => '\F1A3'
            ],
            [
                'class' => 'crosshairs-gps',
                'code'  => '\F1A4'
            ],
            [
                'class' => 'crown',
                'code'  => '\F1A5'
            ],
            [
                'class' => 'cryengine',
                'code'  => '\F958'
            ],
            [
                'class' => 'crystal-ball',
                'code'  => '\FB14'
            ],
            [
                'class' => 'cube',
                'code'  => '\F1A6'
            ],
            [
                'class' => 'cube-outline',
                'code'  => '\F1A7'
            ],
            [
                'class' => 'cube-scan',
                'code'  => '\FB60'
            ],
            [
                'class' => 'cube-send',
                'code'  => '\F1A8'
            ],
            [
                'class' => 'cube-unfolded',
                'code'  => '\F1A9'
            ],
            [
                'class' => 'cup',
                'code'  => '\F1AA'
            ],
            [
                'class' => 'cup-off',
                'code'  => '\F5E5'
            ],
            [
                'class' => 'cup-water',
                'code'  => '\F1AB'
            ],
            [
                'class' => 'cupcake',
                'code'  => '\F959'
            ],
            [
                'class' => 'curling',
                'code'  => '\F862'
            ],
            [
                'class' => 'currency-bdt',
                'code'  => '\F863'
            ],
            [
                'class' => 'currency-brl',
                'code'  => '\FB61'
            ],
            [
                'class' => 'currency-btc',
                'code'  => '\F1AC'
            ],
            [
                'class' => 'currency-chf',
                'code'  => '\F7B8'
            ],
            [
                'class' => 'currency-cny',
                'code'  => '\F7B9'
            ],
            [
                'class' => 'currency-eth',
                'code'  => '\F7BA'
            ],
            [
                'class' => 'currency-eur',
                'code'  => '\F1AD'
            ],
            [
                'class' => 'currency-gbp',
                'code'  => '\F1AE'
            ],
            [
                'class' => 'currency-ils',
                'code'  => '\FC3D'
            ],
            [
                'class' => 'currency-inr',
                'code'  => '\F1AF'
            ],
            [
                'class' => 'currency-jpy',
                'code'  => '\F7BB'
            ],
            [
                'class' => 'currency-krw',
                'code'  => '\F7BC'
            ],
            [
                'class' => 'currency-kzt',
                'code'  => '\F864'
            ],
            [
                'class' => 'currency-ngn',
                'code'  => '\F1B0'
            ],
            [
                'class' => 'currency-php',
                'code'  => '\F9E5'
            ],
            [
                'class' => 'currency-rub',
                'code'  => '\F1B1'
            ],
            [
                'class' => 'currency-sign',
                'code'  => '\F7BD'
            ],
            [
                'class' => 'currency-try',
                'code'  => '\F1B2'
            ],
            [
                'class' => 'currency-twd',
                'code'  => '\F7BE'
            ],
            [
                'class' => 'currency-usd',
                'code'  => '\F1B3'
            ],
            [
                'class' => 'currency-usd-off',
                'code'  => '\F679'
            ],
            [
                'class' => 'current-ac',
                'code'  => '\F95A'
            ],
            [
                'class' => 'current-dc',
                'code'  => '\F95B'
            ],
            [
                'class' => 'cursor-default',
                'code'  => '\F1B4'
            ],
            [
                'class' => 'cursor-default-click',
                'code'  => '\FCD9'
            ],
            [
                'class' => 'cursor-default-click-outline',
                'code'  => '\FCDA'
            ],
            [
                'class' => 'cursor-default-outline',
                'code'  => '\F1B5'
            ],
            [
                'class' => 'cursor-move',
                'code'  => '\F1B6'
            ],
            [
                'class' => 'cursor-pointer',
                'code'  => '\F1B7'
            ],
            [
                'class' => 'cursor-text',
                'code'  => '\F5E7'
            ],
            [
                'class' => 'database',
                'code'  => '\F1B8'
            ],
            [
                'class' => 'database-check',
                'code'  => '\FAA8'
            ],
            [
                'class' => 'database-edit',
                'code'  => '\FB62'
            ],
            [
                'class' => 'database-export',
                'code'  => '\F95D'
            ],
            [
                'class' => 'database-import',
                'code'  => '\F95C'
            ],
            [
                'class' => 'database-lock',
                'code'  => '\FAA9'
            ],
            [
                'class' => 'database-minus',
                'code'  => '\F1B9'
            ],
            [
                'class' => 'database-plus',
                'code'  => '\F1BA'
            ],
            [
                'class' => 'database-refresh',
                'code'  => '\FCDB'
            ],
            [
                'class' => 'database-remove',
                'code'  => '\FCDC'
            ],
            [
                'class' => 'database-search',
                'code'  => '\F865'
            ],
            [
                'class' => 'database-settings',
                'code'  => '\FCDD'
            ],
            [
                'class' => 'death-star',
                'code'  => '\F8D7'
            ],
            [
                'class' => 'death-star-variant',
                'code'  => '\F8D8'
            ],
            [
                'class' => 'deathly-hallows',
                'code'  => '\FB63'
            ],
            [
                'class' => 'debian',
                'code'  => '\F8D9'
            ],
            [
                'class' => 'debug-step-into',
                'code'  => '\F1BB'
            ],
            [
                'class' => 'debug-step-out',
                'code'  => '\F1BC'
            ],
            [
                'class' => 'debug-step-over',
                'code'  => '\F1BD'
            ],
            [
                'class' => 'decagram',
                'code'  => '\F76B'
            ],
            [
                'class' => 'decagram-outline',
                'code'  => '\F76C'
            ],
            [
                'class' => 'decimal-decrease',
                'code'  => '\F1BE'
            ],
            [
                'class' => 'decimal-increase',
                'code'  => '\F1BF'
            ],
            [
                'class' => 'delete',
                'code'  => '\F1C0'
            ],
            [
                'class' => 'delete-circle',
                'code'  => '\F682'
            ],
            [
                'class' => 'delete-circle-outline',
                'code'  => '\FB64'
            ],
            [
                'class' => 'delete-empty',
                'code'  => '\F6CB'
            ],
            [
                'class' => 'delete-forever',
                'code'  => '\F5E8'
            ],
            [
                'class' => 'delete-forever-outline',
                'code'  => '\FB65'
            ],
            [
                'class' => 'delete-outline',
                'code'  => '\F9E6'
            ],
            [
                'class' => 'delete-restore',
                'code'  => '\F818'
            ],
            [
                'class' => 'delete-sweep',
                'code'  => '\F5E9'
            ],
            [
                'class' => 'delete-sweep-outline',
                'code'  => '\FC3E'
            ],
            [
                'class' => 'delete-variant',
                'code'  => '\F1C1'
            ],
            [
                'class' => 'delta',
                'code'  => '\F1C2'
            ],
            [
                'class' => 'desk-lamp',
                'code'  => '\F95E'
            ],
            [
                'class' => 'deskphone',
                'code'  => '\F1C3'
            ],
            [
                'class' => 'desktop-classic',
                'code'  => '\F7BF'
            ],
            [
                'class' => 'desktop-mac',
                'code'  => '\F1C4'
            ],
            [
                'class' => 'desktop-mac-dashboard',
                'code'  => '\F9E7'
            ],
            [
                'class' => 'desktop-tower',
                'code'  => '\F1C5'
            ],
            [
                'class' => 'desktop-tower-monitor',
                'code'  => '\FAAA'
            ],
            [
                'class' => 'details',
                'code'  => '\F1C6'
            ],
            [
                'class' => 'developer-board',
                'code'  => '\F696'
            ],
            [
                'class' => 'deviantart',
                'code'  => '\F1C7'
            ],
            [
                'class' => 'dialpad',
                'code'  => '\F61C'
            ],
            [
                'class' => 'diameter',
                'code'  => '\FC3F'
            ],
            [
                'class' => 'diameter-outline',
                'code'  => '\FC40'
            ],
            [
                'class' => 'diameter-variant',
                'code'  => '\FC41'
            ],
            [
                'class' => 'diamond',
                'code'  => '\FB66'
            ],
            [
                'class' => 'diamond-outline',
                'code'  => '\FB67'
            ],
            [
                'class' => 'diamond-stone',
                'code'  => '\F1C8'
            ],
            [
                'class' => 'dice-1',
                'code'  => '\F1CA'
            ],
            [
                'class' => 'dice-2',
                'code'  => '\F1CB'
            ],
            [
                'class' => 'dice-3',
                'code'  => '\F1CC'
            ],
            [
                'class' => 'dice-4',
                'code'  => '\F1CD'
            ],
            [
                'class' => 'dice-5',
                'code'  => '\F1CE'
            ],
            [
                'class' => 'dice-6',
                'code'  => '\F1CF'
            ],
            [
                'class' => 'dice-d10',
                'code'  => '\F76E'
            ],
            [
                'class' => 'dice-d12',
                'code'  => '\F866'
            ],
            [
                'class' => 'dice-d20',
                'code'  => '\F5EA'
            ],
            [
                'class' => 'dice-d4',
                'code'  => '\F5EB'
            ],
            [
                'class' => 'dice-d6',
                'code'  => '\F5EC'
            ],
            [
                'class' => 'dice-d8',
                'code'  => '\F5ED'
            ],
            [
                'class' => 'dice-multiple',
                'code'  => '\F76D'
            ],
            [
                'class' => 'dictionary',
                'code'  => '\F61D'
            ],
            [
                'class' => 'dip-switch',
                'code'  => '\F7C0'
            ],
            [
                'class' => 'directions',
                'code'  => '\F1D0'
            ],
            [
                'class' => 'directions-fork',
                'code'  => '\F641'
            ],
            [
                'class' => 'disc',
                'code'  => '\F5EE'
            ],
            [
                'class' => 'disc-alert',
                'code'  => '\F1D1'
            ],
            [
                'class' => 'disc-player',
                'code'  => '\F95F'
            ],
            [
                'class' => 'discord',
                'code'  => '\F66F'
            ],
            [
                'class' => 'dishwasher',
                'code'  => '\FAAB'
            ],
            [
                'class' => 'disqus',
                'code'  => '\F1D2'
            ],
            [
                'class' => 'disqus-outline',
                'code'  => '\F1D3'
            ],
            [
                'class' => 'division',
                'code'  => '\F1D4'
            ],
            [
                'class' => 'division-box',
                'code'  => '\F1D5'
            ],
            [
                'class' => 'dlna',
                'code'  => '\FA40'
            ],
            [
                'class' => 'dna',
                'code'  => '\F683'
            ],
            [
                'class' => 'dns',
                'code'  => '\F1D6'
            ],
            [
                'class' => 'dns-outline',
                'code'  => '\FB68'
            ],
            [
                'class' => 'do-not-disturb',
                'code'  => '\F697'
            ],
            [
                'class' => 'do-not-disturb-off',
                'code'  => '\F698'
            ],
            [
                'class' => 'docker',
                'code'  => '\F867'
            ],
            [
                'class' => 'doctor',
                'code'  => '\FA41'
            ],
            [
                'class' => 'dog',
                'code'  => '\FA42'
            ],
            [
                'class' => 'dog-service',
                'code'  => '\FAAC'
            ],
            [
                'class' => 'dog-side',
                'code'  => '\FA43'
            ],
            [
                'class' => 'dolby',
                'code'  => '\F6B2'
            ],
            [
                'class' => 'domain',
                'code'  => '\F1D7'
            ],
            [
                'class' => 'donkey',
                'code'  => '\F7C1'
            ],
            [
                'class' => 'door',
                'code'  => '\F819'
            ],
            [
                'class' => 'door-closed',
                'code'  => '\F81A'
            ],
            [
                'class' => 'door-open',
                'code'  => '\F81B'
            ],
            [
                'class' => 'doorbell-video',
                'code'  => '\F868'
            ],
            [
                'class' => 'dot-net',
                'code'  => '\FAAD'
            ],
            [
                'class' => 'dots-horizontal',
                'code'  => '\F1D8'
            ],
            [
                'class' => 'dots-horizontal-circle',
                'code'  => '\F7C2'
            ],
            [
                'class' => 'dots-horizontal-circle-outline',
                'code'  => '\FB69'
            ],
            [
                'class' => 'dots-vertical',
                'code'  => '\F1D9'
            ],
            [
                'class' => 'dots-vertical-circle',
                'code'  => '\F7C3'
            ],
            [
                'class' => 'dots-vertical-circle-outline',
                'code'  => '\FB6A'
            ],
            [
                'class' => 'douban',
                'code'  => '\F699'
            ],
            [
                'class' => 'download',
                'code'  => '\F1DA'
            ],
            [
                'class' => 'download-multiple',
                'code'  => '\F9E8'
            ],
            [
                'class' => 'download-network',
                'code'  => '\F6F3'
            ],
            [
                'class' => 'download-network-outline',
                'code'  => '\FC42'
            ],
            [
                'class' => 'download-outline',
                'code'  => '\FB6B'
            ],
            [
                'class' => 'drag',
                'code'  => '\F1DB'
            ],
            [
                'class' => 'drag-horizontal',
                'code'  => '\F1DC'
            ],
            [
                'class' => 'drag-variant',
                'code'  => '\FB6C'
            ],
            [
                'class' => 'drag-vertical',
                'code'  => '\F1DD'
            ],
            [
                'class' => 'drama-masks',
                'code'  => '\FCDE'
            ],
            [
                'class' => 'drawing',
                'code'  => '\F1DE'
            ],
            [
                'class' => 'drawing-box',
                'code'  => '\F1DF'
            ],
            [
                'class' => 'dribbble',
                'code'  => '\F1E0'
            ],
            [
                'class' => 'dribbble-box',
                'code'  => '\F1E1'
            ],
            [
                'class' => 'drone',
                'code'  => '\F1E2'
            ],
            [
                'class' => 'dropbox',
                'code'  => '\F1E3'
            ],
            [
                'class' => 'drupal',
                'code'  => '\F1E4'
            ],
            [
                'class' => 'duck',
                'code'  => '\F1E5'
            ],
            [
                'class' => 'dumbbell',
                'code'  => '\F1E6'
            ],
            [
                'class' => 'dump-truck',
                'code'  => '\FC43'
            ],
            [
                'class' => 'ear-hearing',
                'code'  => '\F7C4'
            ],
            [
                'class' => 'ear-hearing-off',
                'code'  => '\FA44'
            ],
            [
                'class' => 'earth',
                'code'  => '\F1E7'
            ],
            [
                'class' => 'earth-box',
                'code'  => '\F6CC'
            ],
            [
                'class' => 'earth-box-off',
                'code'  => '\F6CD'
            ],
            [
                'class' => 'earth-off',
                'code'  => '\F1E8'
            ],
            [
                'class' => 'edge',
                'code'  => '\F1E9'
            ],
            [
                'class' => 'egg',
                'code'  => '\FAAE'
            ],
            [
                'class' => 'egg-easter',
                'code'  => '\FAAF'
            ],
            [
                'class' => 'eight-track',
                'code'  => '\F9E9'
            ],
            [
                'class' => 'eject',
                'code'  => '\F1EA'
            ],
            [
                'class' => 'eject-outline',
                'code'  => '\FB6D'
            ],
            [
                'class' => 'elephant',
                'code'  => '\F7C5'
            ],
            [
                'class' => 'elevation-decline',
                'code'  => '\F1EB'
            ],
            [
                'class' => 'elevation-rise',
                'code'  => '\F1EC'
            ],
            [
                'class' => 'elevator',
                'code'  => '\F1ED'
            ],
            [
                'class' => 'email',
                'code'  => '\F1EE'
            ],
            [
                'class' => 'email-alert',
                'code'  => '\F6CE'
            ],
            [
                'class' => 'email-box',
                'code'  => '\FCDF'
            ],
            [
                'class' => 'email-check',
                'code'  => '\FAB0'
            ],
            [
                'class' => 'email-check-outline',
                'code'  => '\FAB1'
            ],
            [
                'class' => 'email-lock',
                'code'  => '\F1F1'
            ],
            [
                'class' => 'email-mark-as-unread',
                'code'  => '\FB6E'
            ],
            [
                'class' => 'email-open',
                'code'  => '\F1EF'
            ],
            [
                'class' => 'email-open-outline',
                'code'  => '\F5EF'
            ],
            [
                'class' => 'email-outline',
                'code'  => '\F1F0'
            ],
            [
                'class' => 'email-plus',
                'code'  => '\F9EA'
            ],
            [
                'class' => 'email-plus-outline',
                'code'  => '\F9EB'
            ],
            [
                'class' => 'email-search',
                'code'  => '\F960'
            ],
            [
                'class' => 'email-search-outline',
                'code'  => '\F961'
            ],
            [
                'class' => 'email-variant',
                'code'  => '\F5F0'
            ],
            [
                'class' => 'ember',
                'code'  => '\FB15'
            ],
            [
                'class' => 'emby',
                'code'  => '\F6B3'
            ],
            [
                'class' => 'emoticon',
                'code'  => '\FC44'
            ],
            [
                'class' => 'emoticon-angry',
                'code'  => '\FC45'
            ],
            [
                'class' => 'emoticon-angry-outline',
                'code'  => '\FC46'
            ],
            [
                'class' => 'emoticon-cool',
                'code'  => '\FC47'
            ],
            [
                'class' => 'emoticon-cool-outline',
                'code'  => '\F1F3'
            ],
            [
                'class' => 'emoticon-cry',
                'code'  => '\FC48'
            ],
            [
                'class' => 'emoticon-cry-outline',
                'code'  => '\FC49'
            ],
            [
                'class' => 'emoticon-dead',
                'code'  => '\FC4A'
            ],
            [
                'class' => 'emoticon-dead-outline',
                'code'  => '\F69A'
            ],
            [
                'class' => 'emoticon-devil',
                'code'  => '\FC4B'
            ],
            [
                'class' => 'emoticon-devil-outline',
                'code'  => '\F1F4'
            ],
            [
                'class' => 'emoticon-excited',
                'code'  => '\FC4C'
            ],
            [
                'class' => 'emoticon-excited-outline',
                'code'  => '\F69B'
            ],
            [
                'class' => 'emoticon-happy',
                'code'  => '\FC4D'
            ],
            [
                'class' => 'emoticon-happy-outline',
                'code'  => '\F1F5'
            ],
            [
                'class' => 'emoticon-kiss',
                'code'  => '\FC4E'
            ],
            [
                'class' => 'emoticon-kiss-outline',
                'code'  => '\FC4F'
            ],
            [
                'class' => 'emoticon-neutral',
                'code'  => '\FC50'
            ],
            [
                'class' => 'emoticon-neutral-outline',
                'code'  => '\F1F6'
            ],
            [
                'class' => 'emoticon-outline',
                'code'  => '\F1F2'
            ],
            [
                'class' => 'emoticon-poop',
                'code'  => '\F1F7'
            ],
            [
                'class' => 'emoticon-poop-outline',
                'code'  => '\FC51'
            ],
            [
                'class' => 'emoticon-sad',
                'code'  => '\FC52'
            ],
            [
                'class' => 'emoticon-sad-outline',
                'code'  => '\F1F8'
            ],
            [
                'class' => 'emoticon-tongue',
                'code'  => '\F1F9'
            ],
            [
                'class' => 'emoticon-tongue-outline',
                'code'  => '\FC53'
            ],
            [
                'class' => 'emoticon-wink',
                'code'  => '\FC54'
            ],
            [
                'class' => 'emoticon-wink-outline',
                'code'  => '\FC55'
            ],
            [
                'class' => 'engine',
                'code'  => '\F1FA'
            ],
            [
                'class' => 'engine-off',
                'code'  => '\FA45'
            ],
            [
                'class' => 'engine-off-outline',
                'code'  => '\FA46'
            ],
            [
                'class' => 'engine-outline',
                'code'  => '\F1FB'
            ],
            [
                'class' => 'equal',
                'code'  => '\F1FC'
            ],
            [
                'class' => 'equal-box',
                'code'  => '\F1FD'
            ],
            [
                'class' => 'eraser',
                'code'  => '\F1FE'
            ],
            [
                'class' => 'eraser-variant',
                'code'  => '\F642'
            ],
            [
                'class' => 'escalator',
                'code'  => '\F1FF'
            ],
            [
                'class' => 'eslint',
                'code'  => '\FC56'
            ],
            [
                'class' => 'et',
                'code'  => '\FAB2'
            ],
            [
                'class' => 'ethereum',
                'code'  => '\F869'
            ],
            [
                'class' => 'ethernet',
                'code'  => '\F200'
            ],
            [
                'class' => 'ethernet-cable',
                'code'  => '\F201'
            ],
            [
                'class' => 'ethernet-cable-off',
                'code'  => '\F202'
            ],
            [
                'class' => 'etsy',
                'code'  => '\F203'
            ],
            [
                'class' => 'ev-station',
                'code'  => '\F5F1'
            ],
            [
                'class' => 'eventbrite',
                'code'  => '\F7C6'
            ],
            [
                'class' => 'evernote',
                'code'  => '\F204'
            ],
            [
                'class' => 'exclamation',
                'code'  => '\F205'
            ],
            [
                'class' => 'exit-run',
                'code'  => '\FA47'
            ],
            [
                'class' => 'exit-to-app',
                'code'  => '\F206'
            ],
            [
                'class' => 'expand-all',
                'code'  => '\FAB3'
            ],
            [
                'class' => 'expand-all-outline',
                'code'  => '\FAB4'
            ],
            [
                'class' => 'exponent',
                'code'  => '\F962'
            ],
            [
                'class' => 'exponent-box',
                'code'  => '\F963'
            ],
            [
                'class' => 'export',
                'code'  => '\F207'
            ],
            [
                'class' => 'export-variant',
                'code'  => '\FB6F'
            ],
            [
                'class' => 'eye',
                'code'  => '\F208'
            ],
            [
                'class' => 'eye-check',
                'code'  => '\FCE0'
            ],
            [
                'class' => 'eye-check-outline',
                'code'  => '\FCE1'
            ],
            [
                'class' => 'eye-circle',
                'code'  => '\FB70'
            ],
            [
                'class' => 'eye-circle-outline',
                'code'  => '\FB71'
            ],
            [
                'class' => 'eye-off',
                'code'  => '\F209'
            ],
            [
                'class' => 'eye-off-outline',
                'code'  => '\F6D0'
            ],
            [
                'class' => 'eye-outline',
                'code'  => '\F6CF'
            ],
            [
                'class' => 'eye-plus',
                'code'  => '\F86A'
            ],
            [
                'class' => 'eye-plus-outline',
                'code'  => '\F86B'
            ],
            [
                'class' => 'eye-settings',
                'code'  => '\F86C'
            ],
            [
                'class' => 'eye-settings-outline',
                'code'  => '\F86D'
            ],
            [
                'class' => 'eyedropper',
                'code'  => '\F20A'
            ],
            [
                'class' => 'eyedropper-variant',
                'code'  => '\F20B'
            ],
            [
                'class' => 'face',
                'code'  => '\F643'
            ],
            [
                'class' => 'face-outline',
                'code'  => '\FB72'
            ],
            [
                'class' => 'face-profile',
                'code'  => '\F644'
            ],
            [
                'class' => 'face-recognition',
                'code'  => '\FC57'
            ],
            [
                'class' => 'facebook',
                'code'  => '\F20C'
            ],
            [
                'class' => 'facebook-box',
                'code'  => '\F20D'
            ],
            [
                'class' => 'facebook-messenger',
                'code'  => '\F20E'
            ],
            [
                'class' => 'facebook-workplace',
                'code'  => '\FB16'
            ],
            [
                'class' => 'factory',
                'code'  => '\F20F'
            ],
            [
                'class' => 'fan',
                'code'  => '\F210'
            ],
            [
                'class' => 'fan-off',
                'code'  => '\F81C'
            ],
            [
                'class' => 'fast-forward',
                'code'  => '\F211'
            ],
            [
                'class' => 'fast-forward-30',
                'code'  => '\FCE2'
            ],
            [
                'class' => 'fast-forward-outline',
                'code'  => '\F6D1'
            ],
            [
                'class' => 'fax',
                'code'  => '\F212'
            ],
            [
                'class' => 'feather',
                'code'  => '\F6D2'
            ],
            [
                'class' => 'feature-search',
                'code'  => '\FA48'
            ],
            [
                'class' => 'feature-search-outline',
                'code'  => '\FA49'
            ],
            [
                'class' => 'fedora',
                'code'  => '\F8DA'
            ],
            [
                'class' => 'ferry',
                'code'  => '\F213'
            ],
            [
                'class' => 'file',
                'code'  => '\F214'
            ],
            [
                'class' => 'file-account',
                'code'  => '\F73A'
            ],
            [
                'class' => 'file-alert',
                'code'  => '\FA4A'
            ],
            [
                'class' => 'file-alert-outline',
                'code'  => '\FA4B'
            ],
            [
                'class' => 'file-cabinet',
                'code'  => '\FAB5'
            ],
            [
                'class' => 'file-chart',
                'code'  => '\F215'
            ],
            [
                'class' => 'file-check',
                'code'  => '\F216'
            ],
            [
                'class' => 'file-cloud',
                'code'  => '\F217'
            ],
            [
                'class' => 'file-compare',
                'code'  => '\F8A9'
            ],
            [
                'class' => 'file-delimited',
                'code'  => '\F218'
            ],
            [
                'class' => 'file-document',
                'code'  => '\F219'
            ],
            [
                'class' => 'file-document-box',
                'code'  => '\F21A'
            ],
            [
                'class' => 'file-document-box-multiple',
                'code'  => '\FAB6'
            ],
            [
                'class' => 'file-document-box-multiple-outline',
                'code'  => '\FAB7'
            ],
            [
                'class' => 'file-document-box-outline',
                'code'  => '\F9EC'
            ],
            [
                'class' => 'file-document-outline',
                'code'  => '\F9ED'
            ],
            [
                'class' => 'file-download',
                'code'  => '\F964'
            ],
            [
                'class' => 'file-download-outline',
                'code'  => '\F965'
            ],
            [
                'class' => 'file-excel',
                'code'  => '\F21B'
            ],
            [
                'class' => 'file-excel-box',
                'code'  => '\F21C'
            ],
            [
                'class' => 'file-export',
                'code'  => '\F21D'
            ],
            [
                'class' => 'file-find',
                'code'  => '\F21E'
            ],
            [
                'class' => 'file-find-outline',
                'code'  => '\FB73'
            ],
            [
                'class' => 'file-hidden',
                'code'  => '\F613'
            ],
            [
                'class' => 'file-image',
                'code'  => '\F21F'
            ],
            [
                'class' => 'file-import',
                'code'  => '\F220'
            ],
            [
                'class' => 'file-lock',
                'code'  => '\F221'
            ],
            [
                'class' => 'file-move',
                'code'  => '\FAB8'
            ],
            [
                'class' => 'file-multiple',
                'code'  => '\F222'
            ],
            [
                'class' => 'file-music',
                'code'  => '\F223'
            ],
            [
                'class' => 'file-outline',
                'code'  => '\F224'
            ],
            [
                'class' => 'file-pdf',
                'code'  => '\F225'
            ],
            [
                'class' => 'file-pdf-box',
                'code'  => '\F226'
            ],
            [
                'class' => 'file-percent',
                'code'  => '\F81D'
            ],
            [
                'class' => 'file-plus',
                'code'  => '\F751'
            ],
            [
                'class' => 'file-powerpoint',
                'code'  => '\F227'
            ],
            [
                'class' => 'file-powerpoint-box',
                'code'  => '\F228'
            ],
            [
                'class' => 'file-presentation-box',
                'code'  => '\F229'
            ],
            [
                'class' => 'file-question',
                'code'  => '\F86E'
            ],
            [
                'class' => 'file-remove',
                'code'  => '\FB74'
            ],
            [
                'class' => 'file-replace',
                'code'  => '\FB17'
            ],
            [
                'class' => 'file-replace-outline',
                'code'  => '\FB18'
            ],
            [
                'class' => 'file-restore',
                'code'  => '\F670'
            ],
            [
                'class' => 'file-search',
                'code'  => '\FC58'
            ],
            [
                'class' => 'file-search-outline',
                'code'  => '\FC59'
            ],
            [
                'class' => 'file-send',
                'code'  => '\F22A'
            ],
            [
                'class' => 'file-table',
                'code'  => '\FC5A'
            ],
            [
                'class' => 'file-table-outline',
                'code'  => '\FC5B'
            ],
            [
                'class' => 'file-tree',
                'code'  => '\F645'
            ],
            [
                'class' => 'file-undo',
                'code'  => '\F8DB'
            ],
            [
                'class' => 'file-upload',
                'code'  => '\FA4C'
            ],
            [
                'class' => 'file-upload-outline',
                'code'  => '\FA4D'
            ],
            [
                'class' => 'file-video',
                'code'  => '\F22B'
            ],
            [
                'class' => 'file-word',
                'code'  => '\F22C'
            ],
            [
                'class' => 'file-word-box',
                'code'  => '\F22D'
            ],
            [
                'class' => 'file-xml',
                'code'  => '\F22E'
            ],
            [
                'class' => 'film',
                'code'  => '\F22F'
            ],
            [
                'class' => 'filmstrip',
                'code'  => '\F230'
            ],
            [
                'class' => 'filmstrip-off',
                'code'  => '\F231'
            ],
            [
                'class' => 'filter',
                'code'  => '\F232'
            ],
            [
                'class' => 'filter-outline',
                'code'  => '\F233'
            ],
            [
                'class' => 'filter-remove',
                'code'  => '\F234'
            ],
            [
                'class' => 'filter-remove-outline',
                'code'  => '\F235'
            ],
            [
                'class' => 'filter-variant',
                'code'  => '\F236'
            ],
            [
                'class' => 'finance',
                'code'  => '\F81E'
            ],
            [
                'class' => 'find-replace',
                'code'  => '\F6D3'
            ],
            [
                'class' => 'fingerprint',
                'code'  => '\F237'
            ],
            [
                'class' => 'fire',
                'code'  => '\F238'
            ],
            [
                'class' => 'fire-truck',
                'code'  => '\F8AA'
            ],
            [
                'class' => 'firebase',
                'code'  => '\F966'
            ],
            [
                'class' => 'firefox',
                'code'  => '\F239'
            ],
            [
                'class' => 'fish',
                'code'  => '\F23A'
            ],
            [
                'class' => 'flag',
                'code'  => '\F23B'
            ],
            [
                'class' => 'flag-checkered',
                'code'  => '\F23C'
            ],
            [
                'class' => 'flag-minus',
                'code'  => '\FB75'
            ],
            [
                'class' => 'flag-outline',
                'code'  => '\F23D'
            ],
            [
                'class' => 'flag-plus',
                'code'  => '\FB76'
            ],
            [
                'class' => 'flag-remove',
                'code'  => '\FB77'
            ],
            [
                'class' => 'flag-triangle',
                'code'  => '\F23F'
            ],
            [
                'class' => 'flag-variant',
                'code'  => '\F240'
            ],
            [
                'class' => 'flag-variant-outline',
                'code'  => '\F23E'
            ],
            [
                'class' => 'flash',
                'code'  => '\F241'
            ],
            [
                'class' => 'flash-auto',
                'code'  => '\F242'
            ],
            [
                'class' => 'flash-circle',
                'code'  => '\F81F'
            ],
            [
                'class' => 'flash-off',
                'code'  => '\F243'
            ],
            [
                'class' => 'flash-outline',
                'code'  => '\F6D4'
            ],
            [
                'class' => 'flash-red-eye',
                'code'  => '\F67A'
            ],
            [
                'class' => 'flashlight',
                'code'  => '\F244'
            ],
            [
                'class' => 'flashlight-off',
                'code'  => '\F245'
            ],
            [
                'class' => 'flask',
                'code'  => '\F093'
            ],
            [
                'class' => 'flask-empty',
                'code'  => '\F094'
            ],
            [
                'class' => 'flask-empty-outline',
                'code'  => '\F095'
            ],
            [
                'class' => 'flask-outline',
                'code'  => '\F096'
            ],
            [
                'class' => 'flattr',
                'code'  => '\F246'
            ],
            [
                'class' => 'flickr',
                'code'  => '\FCE3'
            ],
            [
                'class' => 'flip-to-back',
                'code'  => '\F247'
            ],
            [
                'class' => 'flip-to-front',
                'code'  => '\F248'
            ],
            [
                'class' => 'floor-lamp',
                'code'  => '\F8DC'
            ],
            [
                'class' => 'floor-plan',
                'code'  => '\F820'
            ],
            [
                'class' => 'floppy',
                'code'  => '\F249'
            ],
            [
                'class' => 'floppy-variant',
                'code'  => '\F9EE'
            ],
            [
                'class' => 'flower',
                'code'  => '\F24A'
            ],
            [
                'class' => 'flower-outline',
                'code'  => '\F9EF'
            ],
            [
                'class' => 'flower-poppy',
                'code'  => '\FCE4'
            ],
            [
                'class' => 'flower-tulip',
                'code'  => '\F9F0'
            ],
            [
                'class' => 'flower-tulip-outline',
                'code'  => '\F9F1'
            ],
            [
                'class' => 'folder',
                'code'  => '\F24B'
            ],
            [
                'class' => 'folder-account',
                'code'  => '\F24C'
            ],
            [
                'class' => 'folder-account-outline',
                'code'  => '\FB78'
            ],
            [
                'class' => 'folder-clock',
                'code'  => '\FAB9'
            ],
            [
                'class' => 'folder-clock-outline',
                'code'  => '\FABA'
            ],
            [
                'class' => 'folder-download',
                'code'  => '\F24D'
            ],
            [
                'class' => 'folder-edit',
                'code'  => '\F8DD'
            ],
            [
                'class' => 'folder-google-drive',
                'code'  => '\F24E'
            ],
            [
                'class' => 'folder-image',
                'code'  => '\F24F'
            ],
            [
                'class' => 'folder-key',
                'code'  => '\F8AB'
            ],
            [
                'class' => 'folder-key-network',
                'code'  => '\F8AC'
            ],
            [
                'class' => 'folder-key-network-outline',
                'code'  => '\FC5C'
            ],
            [
                'class' => 'folder-lock',
                'code'  => '\F250'
            ],
            [
                'class' => 'folder-lock-open',
                'code'  => '\F251'
            ],
            [
                'class' => 'folder-move',
                'code'  => '\F252'
            ],
            [
                'class' => 'folder-multiple',
                'code'  => '\F253'
            ],
            [
                'class' => 'folder-multiple-image',
                'code'  => '\F254'
            ],
            [
                'class' => 'folder-multiple-outline',
                'code'  => '\F255'
            ],
            [
                'class' => 'folder-network',
                'code'  => '\F86F'
            ],
            [
                'class' => 'folder-network-outline',
                'code'  => '\FC5D'
            ],
            [
                'class' => 'folder-open',
                'code'  => '\F76F'
            ],
            [
                'class' => 'folder-outline',
                'code'  => '\F256'
            ],
            [
                'class' => 'folder-plus',
                'code'  => '\F257'
            ],
            [
                'class' => 'folder-plus-outline',
                'code'  => '\FB79'
            ],
            [
                'class' => 'folder-pound',
                'code'  => '\FCE5'
            ],
            [
                'class' => 'folder-pound-outline',
                'code'  => '\FCE6'
            ],
            [
                'class' => 'folder-remove',
                'code'  => '\F258'
            ],
            [
                'class' => 'folder-remove-outline',
                'code'  => '\FB7A'
            ],
            [
                'class' => 'folder-search',
                'code'  => '\F967'
            ],
            [
                'class' => 'folder-search-outline',
                'code'  => '\F968'
            ],
            [
                'class' => 'folder-star',
                'code'  => '\F69C'
            ],
            [
                'class' => 'folder-star-outline',
                'code'  => '\FB7B'
            ],
            [
                'class' => 'folder-sync',
                'code'  => '\FCE7'
            ],
            [
                'class' => 'folder-sync-outline',
                'code'  => '\FCE8'
            ],
            [
                'class' => 'folder-text',
                'code'  => '\FC5E'
            ],
            [
                'class' => 'folder-text-outline',
                'code'  => '\FC5F'
            ],
            [
                'class' => 'folder-upload',
                'code'  => '\F259'
            ],
            [
                'class' => 'font-awesome',
                'code'  => '\F03A'
            ],
            [
                'class' => 'food',
                'code'  => '\F25A'
            ],
            [
                'class' => 'food-apple',
                'code'  => '\F25B'
            ],
            [
                'class' => 'food-apple-outline',
                'code'  => '\FC60'
            ],
            [
                'class' => 'food-croissant',
                'code'  => '\F7C7'
            ],
            [
                'class' => 'food-fork-drink',
                'code'  => '\F5F2'
            ],
            [
                'class' => 'food-off',
                'code'  => '\F5F3'
            ],
            [
                'class' => 'food-variant',
                'code'  => '\F25C'
            ],
            [
                'class' => 'football',
                'code'  => '\F25D'
            ],
            [
                'class' => 'football-australian',
                'code'  => '\F25E'
            ],
            [
                'class' => 'football-helmet',
                'code'  => '\F25F'
            ],
            [
                'class' => 'forklift',
                'code'  => '\F7C8'
            ],
            [
                'class' => 'format-align-bottom',
                'code'  => '\F752'
            ],
            [
                'class' => 'format-align-center',
                'code'  => '\F260'
            ],
            [
                'class' => 'format-align-justify',
                'code'  => '\F261'
            ],
            [
                'class' => 'format-align-left',
                'code'  => '\F262'
            ],
            [
                'class' => 'format-align-middle',
                'code'  => '\F753'
            ],
            [
                'class' => 'format-align-right',
                'code'  => '\F263'
            ],
            [
                'class' => 'format-align-top',
                'code'  => '\F754'
            ],
            [
                'class' => 'format-annotation-minus',
                'code'  => '\FABB'
            ],
            [
                'class' => 'format-annotation-plus',
                'code'  => '\F646'
            ],
            [
                'class' => 'format-bold',
                'code'  => '\F264'
            ],
            [
                'class' => 'format-clear',
                'code'  => '\F265'
            ],
            [
                'class' => 'format-color-fill',
                'code'  => '\F266'
            ],
            [
                'class' => 'format-color-text',
                'code'  => '\F69D'
            ],
            [
                'class' => 'format-columns',
                'code'  => '\F8DE'
            ],
            [
                'class' => 'format-float-center',
                'code'  => '\F267'
            ],
            [
                'class' => 'format-float-left',
                'code'  => '\F268'
            ],
            [
                'class' => 'format-float-none',
                'code'  => '\F269'
            ],
            [
                'class' => 'format-float-right',
                'code'  => '\F26A'
            ],
            [
                'class' => 'format-font',
                'code'  => '\F6D5'
            ],
            [
                'class' => 'format-font-size-decrease',
                'code'  => '\F9F2'
            ],
            [
                'class' => 'format-font-size-increase',
                'code'  => '\F9F3'
            ],
            [
                'class' => 'format-header-1',
                'code'  => '\F26B'
            ],
            [
                'class' => 'format-header-2',
                'code'  => '\F26C'
            ],
            [
                'class' => 'format-header-3',
                'code'  => '\F26D'
            ],
            [
                'class' => 'format-header-4',
                'code'  => '\F26E'
            ],
            [
                'class' => 'format-header-5',
                'code'  => '\F26F'
            ],
            [
                'class' => 'format-header-6',
                'code'  => '\F270'
            ],
            [
                'class' => 'format-header-decrease',
                'code'  => '\F271'
            ],
            [
                'class' => 'format-header-equal',
                'code'  => '\F272'
            ],
            [
                'class' => 'format-header-increase',
                'code'  => '\F273'
            ],
            [
                'class' => 'format-header-pound',
                'code'  => '\F274'
            ],
            [
                'class' => 'format-horizontal-align-center',
                'code'  => '\F61E'
            ],
            [
                'class' => 'format-horizontal-align-left',
                'code'  => '\F61F'
            ],
            [
                'class' => 'format-horizontal-align-right',
                'code'  => '\F620'
            ],
            [
                'class' => 'format-indent-decrease',
                'code'  => '\F275'
            ],
            [
                'class' => 'format-indent-increase',
                'code'  => '\F276'
            ],
            [
                'class' => 'format-italic',
                'code'  => '\F277'
            ],
            [
                'class' => 'format-letter-case',
                'code'  => '\FB19'
            ],
            [
                'class' => 'format-letter-case-lower',
                'code'  => '\FB1A'
            ],
            [
                'class' => 'format-letter-case-upper',
                'code'  => '\FB1B'
            ],
            [
                'class' => 'format-line-spacing',
                'code'  => '\F278'
            ],
            [
                'class' => 'format-line-style',
                'code'  => '\F5C8'
            ],
            [
                'class' => 'format-line-weight',
                'code'  => '\F5C9'
            ],
            [
                'class' => 'format-list-bulleted',
                'code'  => '\F279'
            ],
            [
                'class' => 'format-list-bulleted-type',
                'code'  => '\F27A'
            ],
            [
                'class' => 'format-list-checkbox',
                'code'  => '\F969'
            ],
            [
                'class' => 'format-list-checks',
                'code'  => '\F755'
            ],
            [
                'class' => 'format-list-numbered',
                'code'  => '\F27B'
            ],
            [
                'class' => 'format-list-numbered-rtl',
                'code'  => '\FCE9'
            ],
            [
                'class' => 'format-page-break',
                'code'  => '\F6D6'
            ],
            [
                'class' => 'format-paint',
                'code'  => '\F27C'
            ],
            [
                'class' => 'format-paragraph',
                'code'  => '\F27D'
            ],
            [
                'class' => 'format-pilcrow',
                'code'  => '\F6D7'
            ],
            [
                'class' => 'format-quote-close',
                'code'  => '\F27E'
            ],
            [
                'class' => 'format-quote-open',
                'code'  => '\F756'
            ],
            [
                'class' => 'format-rotate-90',
                'code'  => '\F6A9'
            ],
            [
                'class' => 'format-section',
                'code'  => '\F69E'
            ],
            [
                'class' => 'format-size',
                'code'  => '\F27F'
            ],
            [
                'class' => 'format-strikethrough',
                'code'  => '\F280'
            ],
            [
                'class' => 'format-strikethrough-variant',
                'code'  => '\F281'
            ],
            [
                'class' => 'format-subscript',
                'code'  => '\F282'
            ],
            [
                'class' => 'format-superscript',
                'code'  => '\F283'
            ],
            [
                'class' => 'format-text',
                'code'  => '\F284'
            ],
            [
                'class' => 'format-text-wrapping-clip',
                'code'  => '\FCEA'
            ],
            [
                'class' => 'format-text-wrapping-overflow',
                'code'  => '\FCEB'
            ],
            [
                'class' => 'format-text-wrapping-wrap',
                'code'  => '\FCEC'
            ],
            [
                'class' => 'format-textbox',
                'code'  => '\FCED'
            ],
            [
                'class' => 'format-textdirection-l-to-r',
                'code'  => '\F285'
            ],
            [
                'class' => 'format-textdirection-r-to-l',
                'code'  => '\F286'
            ],
            [
                'class' => 'format-title',
                'code'  => '\F5F4'
            ],
            [
                'class' => 'format-underline',
                'code'  => '\F287'
            ],
            [
                'class' => 'format-vertical-align-bottom',
                'code'  => '\F621'
            ],
            [
                'class' => 'format-vertical-align-center',
                'code'  => '\F622'
            ],
            [
                'class' => 'format-vertical-align-top',
                'code'  => '\F623'
            ],
            [
                'class' => 'format-wrap-inline',
                'code'  => '\F288'
            ],
            [
                'class' => 'format-wrap-square',
                'code'  => '\F289'
            ],
            [
                'class' => 'format-wrap-tight',
                'code'  => '\F28A'
            ],
            [
                'class' => 'format-wrap-top-bottom',
                'code'  => '\F28B'
            ],
            [
                'class' => 'forum',
                'code'  => '\F28C'
            ],
            [
                'class' => 'forum-outline',
                'code'  => '\F821'
            ],
            [
                'class' => 'forward',
                'code'  => '\F28D'
            ],
            [
                'class' => 'fountain',
                'code'  => '\F96A'
            ],
            [
                'class' => 'fountain-pen',
                'code'  => '\FCEE'
            ],
            [
                'class' => 'fountain-pen-tip',
                'code'  => '\FCEF'
            ],
            [
                'class' => 'foursquare',
                'code'  => '\F28E'
            ],
            [
                'class' => 'freebsd',
                'code'  => '\F8DF'
            ],
            [
                'class' => 'fridge',
                'code'  => '\F290'
            ],
            [
                'class' => 'fridge-bottom',
                'code'  => '\F292'
            ],
            [
                'class' => 'fridge-outline',
                'code'  => '\F28F'
            ],
            [
                'class' => 'fridge-top',
                'code'  => '\F291'
            ],
            [
                'class' => 'fuel',
                'code'  => '\F7C9'
            ],
            [
                'class' => 'fullscreen',
                'code'  => '\F293'
            ],
            [
                'class' => 'fullscreen-exit',
                'code'  => '\F294'
            ],
            [
                'class' => 'function',
                'code'  => '\F295'
            ],
            [
                'class' => 'function-variant',
                'code'  => '\F870'
            ],
            [
                'class' => 'fuse',
                'code'  => '\FC61'
            ],
            [
                'class' => 'fuse-blade',
                'code'  => '\FC62'
            ],
            [
                'class' => 'gamepad',
                'code'  => '\F296'
            ],
            [
                'class' => 'gamepad-variant',
                'code'  => '\F297'
            ],
            [
                'class' => 'garage',
                'code'  => '\F6D8'
            ],
            [
                'class' => 'garage-alert',
                'code'  => '\F871'
            ],
            [
                'class' => 'garage-open',
                'code'  => '\F6D9'
            ],
            [
                'class' => 'gas-cylinder',
                'code'  => '\F647'
            ],
            [
                'class' => 'gas-station',
                'code'  => '\F298'
            ],
            [
                'class' => 'gate',
                'code'  => '\F299'
            ],
            [
                'class' => 'gate-and',
                'code'  => '\F8E0'
            ],
            [
                'class' => 'gate-nand',
                'code'  => '\F8E1'
            ],
            [
                'class' => 'gate-nor',
                'code'  => '\F8E2'
            ],
            [
                'class' => 'gate-not',
                'code'  => '\F8E3'
            ],
            [
                'class' => 'gate-or',
                'code'  => '\F8E4'
            ],
            [
                'class' => 'gate-xnor',
                'code'  => '\F8E5'
            ],
            [
                'class' => 'gate-xor',
                'code'  => '\F8E6'
            ],
            [
                'class' => 'gauge',
                'code'  => '\F29A'
            ],
            [
                'class' => 'gauge-empty',
                'code'  => '\F872'
            ],
            [
                'class' => 'gauge-full',
                'code'  => '\F873'
            ],
            [
                'class' => 'gauge-low',
                'code'  => '\F874'
            ],
            [
                'class' => 'gavel',
                'code'  => '\F29B'
            ],
            [
                'class' => 'gender-female',
                'code'  => '\F29C'
            ],
            [
                'class' => 'gender-male',
                'code'  => '\F29D'
            ],
            [
                'class' => 'gender-male-female',
                'code'  => '\F29E'
            ],
            [
                'class' => 'gender-transgender',
                'code'  => '\F29F'
            ],
            [
                'class' => 'gentoo',
                'code'  => '\F8E7'
            ],
            [
                'class' => 'gesture',
                'code'  => '\F7CA'
            ],
            [
                'class' => 'gesture-double-tap',
                'code'  => '\F73B'
            ],
            [
                'class' => 'gesture-pinch',
                'code'  => '\FABC'
            ],
            [
                'class' => 'gesture-spread',
                'code'  => '\FABD'
            ],
            [
                'class' => 'gesture-swipe-down',
                'code'  => '\F73C'
            ],
            [
                'class' => 'gesture-swipe-horizontal',
                'code'  => '\FABE'
            ],
            [
                'class' => 'gesture-swipe-left',
                'code'  => '\F73D'
            ],
            [
                'class' => 'gesture-swipe-right',
                'code'  => '\F73E'
            ],
            [
                'class' => 'gesture-swipe-up',
                'code'  => '\F73F'
            ],
            [
                'class' => 'gesture-swipe-vertical',
                'code'  => '\FABF'
            ],
            [
                'class' => 'gesture-tap',
                'code'  => '\F740'
            ],
            [
                'class' => 'gesture-two-double-tap',
                'code'  => '\F741'
            ],
            [
                'class' => 'gesture-two-tap',
                'code'  => '\F742'
            ],
            [
                'class' => 'ghost',
                'code'  => '\F2A0'
            ],
            [
                'class' => 'ghost-off',
                'code'  => '\F9F4'
            ],
            [
                'class' => 'gift',
                'code'  => '\F2A1'
            ],
            [
                'class' => 'git',
                'code'  => '\F2A2'
            ],
            [
                'class' => 'github-box',
                'code'  => '\F2A3'
            ],
            [
                'class' => 'github-circle',
                'code'  => '\F2A4'
            ],
            [
                'class' => 'github-face',
                'code'  => '\F6DA'
            ],
            [
                'class' => 'gitlab',
                'code'  => '\FB7C'
            ],
            [
                'class' => 'glass-cocktail',
                'code'  => '\F356'
            ],
            [
                'class' => 'glass-flute',
                'code'  => '\F2A5'
            ],
            [
                'class' => 'glass-mug',
                'code'  => '\F2A6'
            ],
            [
                'class' => 'glass-stange',
                'code'  => '\F2A7'
            ],
            [
                'class' => 'glass-tulip',
                'code'  => '\F2A8'
            ],
            [
                'class' => 'glass-wine',
                'code'  => '\F875'
            ],
            [
                'class' => 'glassdoor',
                'code'  => '\F2A9'
            ],
            [
                'class' => 'glasses',
                'code'  => '\F2AA'
            ],
            [
                'class' => 'globe-model',
                'code'  => '\F8E8'
            ],
            [
                'class' => 'gmail',
                'code'  => '\F2AB'
            ],
            [
                'class' => 'gnome',
                'code'  => '\F2AC'
            ],
            [
                'class' => 'gog',
                'code'  => '\FB7D'
            ],
            [
                'class' => 'golf',
                'code'  => '\F822'
            ],
            [
                'class' => 'gondola',
                'code'  => '\F685'
            ],
            [
                'class' => 'google',
                'code'  => '\F2AD'
            ],
            [
                'class' => 'google-adwords',
                'code'  => '\FC63'
            ],
            [
                'class' => 'google-allo',
                'code'  => '\F801'
            ],
            [
                'class' => 'google-analytics',
                'code'  => '\F7CB'
            ],
            [
                'class' => 'google-assistant',
                'code'  => '\F7CC'
            ],
            [
                'class' => 'google-cardboard',
                'code'  => '\F2AE'
            ],
            [
                'class' => 'google-chrome',
                'code'  => '\F2AF'
            ],
            [
                'class' => 'google-circles',
                'code'  => '\F2B0'
            ],
            [
                'class' => 'google-circles-communities',
                'code'  => '\F2B1'
            ],
            [
                'class' => 'google-circles-extended',
                'code'  => '\F2B2'
            ],
            [
                'class' => 'google-circles-group',
                'code'  => '\F2B3'
            ],
            [
                'class' => 'google-controller',
                'code'  => '\F2B4'
            ],
            [
                'class' => 'google-controller-off',
                'code'  => '\F2B5'
            ],
            [
                'class' => 'google-drive',
                'code'  => '\F2B6'
            ],
            [
                'class' => 'google-earth',
                'code'  => '\F2B7'
            ],
            [
                'class' => 'google-fit',
                'code'  => '\F96B'
            ],
            [
                'class' => 'google-glass',
                'code'  => '\F2B8'
            ],
            [
                'class' => 'google-hangouts',
                'code'  => '\F2C9'
            ],
            [
                'class' => 'google-home',
                'code'  => '\F823'
            ],
            [
                'class' => 'google-keep',
                'code'  => '\F6DB'
            ],
            [
                'class' => 'google-lens',
                'code'  => '\F9F5'
            ],
            [
                'class' => 'google-maps',
                'code'  => '\F5F5'
            ],
            [
                'class' => 'google-nearby',
                'code'  => '\F2B9'
            ],
            [
                'class' => 'google-pages',
                'code'  => '\F2BA'
            ],
            [
                'class' => 'google-photos',
                'code'  => '\F6DC'
            ],
            [
                'class' => 'google-physical-web',
                'code'  => '\F2BB'
            ],
            [
                'class' => 'google-play',
                'code'  => '\F2BC'
            ],
            [
                'class' => 'google-plus',
                'code'  => '\F2BD'
            ],
            [
                'class' => 'google-plus-box',
                'code'  => '\F2BE'
            ],
            [
                'class' => 'google-spreadsheet',
                'code'  => '\F9F6'
            ],
            [
                'class' => 'google-street-view',
                'code'  => '\FC64'
            ],
            [
                'class' => 'google-translate',
                'code'  => '\F2BF'
            ],
            [
                'class' => 'google-wallet',
                'code'  => '\F2C0'
            ],
            [
                'class' => 'gpu',
                'code'  => '\F8AD'
            ],
            [
                'class' => 'gradient',
                'code'  => '\F69F'
            ],
            [
                'class' => 'graphql',
                'code'  => '\F876'
            ],
            [
                'class' => 'grave-stone',
                'code'  => '\FB7E'
            ],
            [
                'class' => 'grease-pencil',
                'code'  => '\F648'
            ],
            [
                'class' => 'greater-than',
                'code'  => '\F96C'
            ],
            [
                'class' => 'greater-than-or-equal',
                'code'  => '\F96D'
            ],
            [
                'class' => 'grid',
                'code'  => '\F2C1'
            ],
            [
                'class' => 'grid-large',
                'code'  => '\F757'
            ],
            [
                'class' => 'grid-off',
                'code'  => '\F2C2'
            ],
            [
                'class' => 'group',
                'code'  => '\F2C3'
            ],
            [
                'class' => 'guitar-acoustic',
                'code'  => '\F770'
            ],
            [
                'class' => 'guitar-electric',
                'code'  => '\F2C4'
            ],
            [
                'class' => 'guitar-pick',
                'code'  => '\F2C5'
            ],
            [
                'class' => 'guitar-pick-outline',
                'code'  => '\F2C6'
            ],
            [
                'class' => 'guy-fawkes-mask',
                'code'  => '\F824'
            ],
            [
                'class' => 'hackernews',
                'code'  => '\F624'
            ],
            [
                'class' => 'hail',
                'code'  => '\FAC0'
            ],
            [
                'class' => 'halloween',
                'code'  => '\FB7F'
            ],
            [
                'class' => 'hamburger',
                'code'  => '\F684'
            ],
            [
                'class' => 'hammer',
                'code'  => '\F8E9'
            ],
            [
                'class' => 'hand',
                'code'  => '\FA4E'
            ],
            [
                'class' => 'hand-okay',
                'code'  => '\FA4F'
            ],
            [
                'class' => 'hand-peace',
                'code'  => '\FA50'
            ],
            [
                'class' => 'hand-peace-variant',
                'code'  => '\FA51'
            ],
            [
                'class' => 'hand-pointing-down',
                'code'  => '\FA52'
            ],
            [
                'class' => 'hand-pointing-left',
                'code'  => '\FA53'
            ],
            [
                'class' => 'hand-pointing-right',
                'code'  => '\F2C7'
            ],
            [
                'class' => 'hand-pointing-up',
                'code'  => '\FA54'
            ],
            [
                'class' => 'hanger',
                'code'  => '\F2C8'
            ],
            [
                'class' => 'hard-hat',
                'code'  => '\F96E'
            ],
            [
                'class' => 'harddisk',
                'code'  => '\F2CA'
            ],
            [
                'class' => 'hat-fedora',
                'code'  => '\FB80'
            ],
            [
                'class' => 'hazard-lights',
                'code'  => '\FC65'
            ],
            [
                'class' => 'headphones',
                'code'  => '\F2CB'
            ],
            [
                'class' => 'headphones-bluetooth',
                'code'  => '\F96F'
            ],
            [
                'class' => 'headphones-box',
                'code'  => '\F2CC'
            ],
            [
                'class' => 'headphones-off',
                'code'  => '\F7CD'
            ],
            [
                'class' => 'headphones-settings',
                'code'  => '\F2CD'
            ],
            [
                'class' => 'headset',
                'code'  => '\F2CE'
            ],
            [
                'class' => 'headset-dock',
                'code'  => '\F2CF'
            ],
            [
                'class' => 'headset-off',
                'code'  => '\F2D0'
            ],
            [
                'class' => 'heart',
                'code'  => '\F2D1'
            ],
            [
                'class' => 'heart-box',
                'code'  => '\F2D2'
            ],
            [
                'class' => 'heart-box-outline',
                'code'  => '\F2D3'
            ],
            [
                'class' => 'heart-broken',
                'code'  => '\F2D4'
            ],
            [
                'class' => 'heart-broken-outline',
                'code'  => '\FCF0'
            ],
            [
                'class' => 'heart-circle',
                'code'  => '\F970'
            ],
            [
                'class' => 'heart-circle-outline',
                'code'  => '\F971'
            ],
            [
                'class' => 'heart-half',
                'code'  => '\F6DE'
            ],
            [
                'class' => 'heart-half-full',
                'code'  => '\F6DD'
            ],
            [
                'class' => 'heart-half-outline',
                'code'  => '\F6DF'
            ],
            [
                'class' => 'heart-multiple',
                'code'  => '\FA55'
            ],
            [
                'class' => 'heart-multiple-outline',
                'code'  => '\FA56'
            ],
            [
                'class' => 'heart-off',
                'code'  => '\F758'
            ],
            [
                'class' => 'heart-outline',
                'code'  => '\F2D5'
            ],
            [
                'class' => 'heart-pulse',
                'code'  => '\F5F6'
            ],
            [
                'class' => 'helicopter',
                'code'  => '\FAC1'
            ],
            [
                'class' => 'help',
                'code'  => '\F2D6'
            ],
            [
                'class' => 'help-box',
                'code'  => '\F78A'
            ],
            [
                'class' => 'help-circle',
                'code'  => '\F2D7'
            ],
            [
                'class' => 'help-circle-outline',
                'code'  => '\F625'
            ],
            [
                'class' => 'help-network',
                'code'  => '\F6F4'
            ],
            [
                'class' => 'help-network-outline',
                'code'  => '\FC66'
            ],
            [
                'class' => 'help-rhombus',
                'code'  => '\FB81'
            ],
            [
                'class' => 'help-rhombus-outline',
                'code'  => '\FB82'
            ],
            [
                'class' => 'hexagon',
                'code'  => '\F2D8'
            ],
            [
                'class' => 'hexagon-multiple',
                'code'  => '\F6E0'
            ],
            [
                'class' => 'hexagon-outline',
                'code'  => '\F2D9'
            ],
            [
                'class' => 'hexagon-slice-1',
                'code'  => '\FAC2'
            ],
            [
                'class' => 'hexagon-slice-2',
                'code'  => '\FAC3'
            ],
            [
                'class' => 'hexagon-slice-3',
                'code'  => '\FAC4'
            ],
            [
                'class' => 'hexagon-slice-4',
                'code'  => '\FAC5'
            ],
            [
                'class' => 'hexagon-slice-5',
                'code'  => '\FAC6'
            ],
            [
                'class' => 'hexagon-slice-6',
                'code'  => '\FAC7'
            ],
            [
                'class' => 'hexagram',
                'code'  => '\FAC8'
            ],
            [
                'class' => 'hexagram-outline',
                'code'  => '\FAC9'
            ],
            [
                'class' => 'high-definition',
                'code'  => '\F7CE'
            ],
            [
                'class' => 'high-definition-box',
                'code'  => '\F877'
            ],
            [
                'class' => 'highway',
                'code'  => '\F5F7'
            ],
            [
                'class' => 'hinduism',
                'code'  => '\F972'
            ],
            [
                'class' => 'history',
                'code'  => '\F2DA'
            ],
            [
                'class' => 'hockey-puck',
                'code'  => '\F878'
            ],
            [
                'class' => 'hockey-sticks',
                'code'  => '\F879'
            ],
            [
                'class' => 'hololens',
                'code'  => '\F2DB'
            ],
            [
                'class' => 'home',
                'code'  => '\F2DC'
            ],
            [
                'class' => 'home-account',
                'code'  => '\F825'
            ],
            [
                'class' => 'home-alert',
                'code'  => '\F87A'
            ],
            [
                'class' => 'home-assistant',
                'code'  => '\F7CF'
            ],
            [
                'class' => 'home-automation',
                'code'  => '\F7D0'
            ],
            [
                'class' => 'home-circle',
                'code'  => '\F7D1'
            ],
            [
                'class' => 'home-city',
                'code'  => '\FCF1'
            ],
            [
                'class' => 'home-city-outline',
                'code'  => '\FCF2'
            ],
            [
                'class' => 'home-currency-usd',
                'code'  => '\F8AE'
            ],
            [
                'class' => 'home-heart',
                'code'  => '\F826'
            ],
            [
                'class' => 'home-lock',
                'code'  => '\F8EA'
            ],
            [
                'class' => 'home-lock-open',
                'code'  => '\F8EB'
            ],
            [
                'class' => 'home-map-marker',
                'code'  => '\F5F8'
            ],
            [
                'class' => 'home-minus',
                'code'  => '\F973'
            ],
            [
                'class' => 'home-modern',
                'code'  => '\F2DD'
            ],
            [
                'class' => 'home-outline',
                'code'  => '\F6A0'
            ],
            [
                'class' => 'home-plus',
                'code'  => '\F974'
            ],
            [
                'class' => 'home-variant',
                'code'  => '\F2DE'
            ],
            [
                'class' => 'home-variant-outline',
                'code'  => '\FB83'
            ],
            [
                'class' => 'hook',
                'code'  => '\F6E1'
            ],
            [
                'class' => 'hook-off',
                'code'  => '\F6E2'
            ],
            [
                'class' => 'hops',
                'code'  => '\F2DF'
            ],
            [
                'class' => 'horseshoe',
                'code'  => '\FA57'
            ],
            [
                'class' => 'hospital',
                'code'  => '\F2E0'
            ],
            [
                'class' => 'hospital-building',
                'code'  => '\F2E1'
            ],
            [
                'class' => 'hospital-marker',
                'code'  => '\F2E2'
            ],
            [
                'class' => 'hot-tub',
                'code'  => '\F827'
            ],
            [
                'class' => 'hotel',
                'code'  => '\F2E3'
            ],
            [
                'class' => 'houzz',
                'code'  => '\F2E4'
            ],
            [
                'class' => 'houzz-box',
                'code'  => '\F2E5'
            ],
            [
                'class' => 'hubspot',
                'code'  => '\FCF3'
            ],
            [
                'class' => 'hulu',
                'code'  => '\F828'
            ],
            [
                'class' => 'human',
                'code'  => '\F2E6'
            ],
            [
                'class' => 'human-child',
                'code'  => '\F2E7'
            ],
            [
                'class' => 'human-female',
                'code'  => '\F649'
            ],
            [
                'class' => 'human-female-boy',
                'code'  => '\FA58'
            ],
            [
                'class' => 'human-female-female',
                'code'  => '\FA59'
            ],
            [
                'class' => 'human-female-girl',
                'code'  => '\FA5A'
            ],
            [
                'class' => 'human-greeting',
                'code'  => '\F64A'
            ],
            [
                'class' => 'human-handsdown',
                'code'  => '\F64B'
            ],
            [
                'class' => 'human-handsup',
                'code'  => '\F64C'
            ],
            [
                'class' => 'human-male',
                'code'  => '\F64D'
            ],
            [
                'class' => 'human-male-boy',
                'code'  => '\FA5B'
            ],
            [
                'class' => 'human-male-female',
                'code'  => '\F2E8'
            ],
            [
                'class' => 'human-male-girl',
                'code'  => '\FA5C'
            ],
            [
                'class' => 'human-male-male',
                'code'  => '\FA5D'
            ],
            [
                'class' => 'human-pregnant',
                'code'  => '\F5CF'
            ],
            [
                'class' => 'humble-bundle',
                'code'  => '\F743'
            ],
            [
                'class' => 'ice-cream',
                'code'  => '\F829'
            ],
            [
                'class' => 'iframe',
                'code'  => '\FC67'
            ],
            [
                'class' => 'iframe-outline',
                'code'  => '\FC68'
            ],
            [
                'class' => 'image',
                'code'  => '\F2E9'
            ],
            [
                'class' => 'image-album',
                'code'  => '\F2EA'
            ],
            [
                'class' => 'image-area',
                'code'  => '\F2EB'
            ],
            [
                'class' => 'image-area-close',
                'code'  => '\F2EC'
            ],
            [
                'class' => 'image-broken',
                'code'  => '\F2ED'
            ],
            [
                'class' => 'image-broken-variant',
                'code'  => '\F2EE'
            ],
            [
                'class' => 'image-filter',
                'code'  => '\F2EF'
            ],
            [
                'class' => 'image-filter-black-white',
                'code'  => '\F2F0'
            ],
            [
                'class' => 'image-filter-center-focus',
                'code'  => '\F2F1'
            ],
            [
                'class' => 'image-filter-center-focus-weak',
                'code'  => '\F2F2'
            ],
            [
                'class' => 'image-filter-drama',
                'code'  => '\F2F3'
            ],
            [
                'class' => 'image-filter-frames',
                'code'  => '\F2F4'
            ],
            [
                'class' => 'image-filter-hdr',
                'code'  => '\F2F5'
            ],
            [
                'class' => 'image-filter-none',
                'code'  => '\F2F6'
            ],
            [
                'class' => 'image-filter-tilt-shift',
                'code'  => '\F2F7'
            ],
            [
                'class' => 'image-filter-vintage',
                'code'  => '\F2F8'
            ],
            [
                'class' => 'image-move',
                'code'  => '\F9F7'
            ],
            [
                'class' => 'image-multiple',
                'code'  => '\F2F9'
            ],
            [
                'class' => 'image-off',
                'code'  => '\F82A'
            ],
            [
                'class' => 'image-outline',
                'code'  => '\F975'
            ],
            [
                'class' => 'image-plus',
                'code'  => '\F87B'
            ],
            [
                'class' => 'image-search',
                'code'  => '\F976'
            ],
            [
                'class' => 'image-search-outline',
                'code'  => '\F977'
            ],
            [
                'class' => 'image-size-select-actual',
                'code'  => '\FC69'
            ],
            [
                'class' => 'image-size-select-large',
                'code'  => '\FC6A'
            ],
            [
                'class' => 'image-size-select-small',
                'code'  => '\FC6B'
            ],
            [
                'class' => 'import',
                'code'  => '\F2FA'
            ],
            [
                'class' => 'inbox',
                'code'  => '\F686'
            ],
            [
                'class' => 'inbox-arrow-down',
                'code'  => '\F2FB'
            ],
            [
                'class' => 'inbox-arrow-up',
                'code'  => '\F3D1'
            ],
            [
                'class' => 'inbox-multiple',
                'code'  => '\F8AF'
            ],
            [
                'class' => 'inbox-multiple-outline',
                'code'  => '\FB84'
            ],
            [
                'class' => 'incognito',
                'code'  => '\F5F9'
            ],
            [
                'class' => 'infinity',
                'code'  => '\F6E3'
            ],
            [
                'class' => 'information',
                'code'  => '\F2FC'
            ],
            [
                'class' => 'information-outline',
                'code'  => '\F2FD'
            ],
            [
                'class' => 'information-variant',
                'code'  => '\F64E'
            ],
            [
                'class' => 'instagram',
                'code'  => '\F2FE'
            ],
            [
                'class' => 'instapaper',
                'code'  => '\F2FF'
            ],
            [
                'class' => 'internet-explorer',
                'code'  => '\F300'
            ],
            [
                'class' => 'invert-colors',
                'code'  => '\F301'
            ],
            [
                'class' => 'ip',
                'code'  => '\FA5E'
            ],
            [
                'class' => 'ip-network',
                'code'  => '\FA5F'
            ],
            [
                'class' => 'ip-network-outline',
                'code'  => '\FC6C'
            ],
            [
                'class' => 'ipod',
                'code'  => '\FC6D'
            ],
            [
                'class' => 'islam',
                'code'  => '\F978'
            ],
            [
                'class' => 'itunes',
                'code'  => '\F676'
            ],
            [
                'class' => 'jeepney',
                'code'  => '\F302'
            ],
            [
                'class' => 'jira',
                'code'  => '\F303'
            ],
            [
                'class' => 'jquery',
                'code'  => '\F87C'
            ],
            [
                'class' => 'jsfiddle',
                'code'  => '\F304'
            ],
            [
                'class' => 'json',
                'code'  => '\F626'
            ],
            [
                'class' => 'judaism',
                'code'  => '\F979'
            ],
            [
                'class' => 'karate',
                'code'  => '\F82B'
            ],
            [
                'class' => 'keg',
                'code'  => '\F305'
            ],
            [
                'class' => 'kettle',
                'code'  => '\F5FA'
            ],
            [
                'class' => 'key',
                'code'  => '\F306'
            ],
            [
                'class' => 'key-change',
                'code'  => '\F307'
            ],
            [
                'class' => 'key-minus',
                'code'  => '\F308'
            ],
            [
                'class' => 'key-plus',
                'code'  => '\F309'
            ],
            [
                'class' => 'key-remove',
                'code'  => '\F30A'
            ],
            [
                'class' => 'key-variant',
                'code'  => '\F30B'
            ],
            [
                'class' => 'keyboard',
                'code'  => '\F30C'
            ],
            [
                'class' => 'keyboard-backspace',
                'code'  => '\F30D'
            ],
            [
                'class' => 'keyboard-caps',
                'code'  => '\F30E'
            ],
            [
                'class' => 'keyboard-close',
                'code'  => '\F30F'
            ],
            [
                'class' => 'keyboard-off',
                'code'  => '\F310'
            ],
            [
                'class' => 'keyboard-outline',
                'code'  => '\F97A'
            ],
            [
                'class' => 'keyboard-return',
                'code'  => '\F311'
            ],
            [
                'class' => 'keyboard-settings',
                'code'  => '\F9F8'
            ],
            [
                'class' => 'keyboard-settings-outline',
                'code'  => '\F9F9'
            ],
            [
                'class' => 'keyboard-tab',
                'code'  => '\F312'
            ],
            [
                'class' => 'keyboard-variant',
                'code'  => '\F313'
            ],
            [
                'class' => 'kickstarter',
                'code'  => '\F744'
            ],
            [
                'class' => 'knife',
                'code'  => '\F9FA'
            ],
            [
                'class' => 'knife-military',
                'code'  => '\F9FB'
            ],
            [
                'class' => 'kodi',
                'code'  => '\F314'
            ],
            [
                'class' => 'label',
                'code'  => '\F315'
            ],
            [
                'class' => 'label-off',
                'code'  => '\FACA'
            ],
            [
                'class' => 'label-off-outline',
                'code'  => '\FACB'
            ],
            [
                'class' => 'label-outline',
                'code'  => '\F316'
            ],
            [
                'class' => 'label-variant',
                'code'  => '\FACC'
            ],
            [
                'class' => 'label-variant-outline',
                'code'  => '\FACD'
            ],
            [
                'class' => 'ladybug',
                'code'  => '\F82C'
            ],
            [
                'class' => 'lambda',
                'code'  => '\F627'
            ],
            [
                'class' => 'lamp',
                'code'  => '\F6B4'
            ],
            [
                'class' => 'lan',
                'code'  => '\F317'
            ],
            [
                'class' => 'lan-connect',
                'code'  => '\F318'
            ],
            [
                'class' => 'lan-disconnect',
                'code'  => '\F319'
            ],
            [
                'class' => 'lan-pending',
                'code'  => '\F31A'
            ],
            [
                'class' => 'language-c',
                'code'  => '\F671'
            ],
            [
                'class' => 'language-cpp',
                'code'  => '\F672'
            ],
            [
                'class' => 'language-csharp',
                'code'  => '\F31B'
            ],
            [
                'class' => 'language-css3',
                'code'  => '\F31C'
            ],
            [
                'class' => 'language-go',
                'code'  => '\F7D2'
            ],
            [
                'class' => 'language-haskell',
                'code'  => '\FC6E'
            ],
            [
                'class' => 'language-html5',
                'code'  => '\F31D'
            ],
            [
                'class' => 'language-java',
                'code'  => '\FB1C'
            ],
            [
                'class' => 'language-javascript',
                'code'  => '\F31E'
            ],
            [
                'class' => 'language-lua',
                'code'  => '\F8B0'
            ],
            [
                'class' => 'language-php',
                'code'  => '\F31F'
            ],
            [
                'class' => 'language-python',
                'code'  => '\F320'
            ],
            [
                'class' => 'language-python-text',
                'code'  => '\F321'
            ],
            [
                'class' => 'language-r',
                'code'  => '\F7D3'
            ],
            [
                'class' => 'language-ruby-on-rails',
                'code'  => '\FACE'
            ],
            [
                'class' => 'language-swift',
                'code'  => '\F6E4'
            ],
            [
                'class' => 'language-typescript',
                'code'  => '\F6E5'
            ],
            [
                'class' => 'laptop',
                'code'  => '\F322'
            ],
            [
                'class' => 'laptop-chromebook',
                'code'  => '\F323'
            ],
            [
                'class' => 'laptop-mac',
                'code'  => '\F324'
            ],
            [
                'class' => 'laptop-off',
                'code'  => '\F6E6'
            ],
            [
                'class' => 'laptop-windows',
                'code'  => '\F325'
            ],
            [
                'class' => 'laravel',
                'code'  => '\FACF'
            ],
            [
                'class' => 'lastfm',
                'code'  => '\F326'
            ],
            [
                'class' => 'lastpass',
                'code'  => '\F446'
            ],
            [
                'class' => 'launch',
                'code'  => '\F327'
            ],
            [
                'class' => 'lava-lamp',
                'code'  => '\F7D4'
            ],
            [
                'class' => 'layers',
                'code'  => '\F328'
            ],
            [
                'class' => 'layers-off',
                'code'  => '\F329'
            ],
            [
                'class' => 'layers-off-outline',
                'code'  => '\F9FC'
            ],
            [
                'class' => 'layers-outline',
                'code'  => '\F9FD'
            ],
            [
                'class' => 'lead-pencil',
                'code'  => '\F64F'
            ],
            [
                'class' => 'leaf',
                'code'  => '\F32A'
            ],
            [
                'class' => 'leaf-maple',
                'code'  => '\FC6F'
            ],
            [
                'class' => 'led-off',
                'code'  => '\F32B'
            ],
            [
                'class' => 'led-on',
                'code'  => '\F32C'
            ],
            [
                'class' => 'led-outline',
                'code'  => '\F32D'
            ],
            [
                'class' => 'led-strip',
                'code'  => '\F7D5'
            ],
            [
                'class' => 'led-variant-off',
                'code'  => '\F32E'
            ],
            [
                'class' => 'led-variant-on',
                'code'  => '\F32F'
            ],
            [
                'class' => 'led-variant-outline',
                'code'  => '\F330'
            ],
            [
                'class' => 'less-than',
                'code'  => '\F97B'
            ],
            [
                'class' => 'less-than-or-equal',
                'code'  => '\F97C'
            ],
            [
                'class' => 'library',
                'code'  => '\F331'
            ],
            [
                'class' => 'library-books',
                'code'  => '\F332'
            ],
            [
                'class' => 'library-movie',
                'code'  => '\FCF4'
            ],
            [
                'class' => 'library-music',
                'code'  => '\F333'
            ],
            [
                'class' => 'library-plus',
                'code'  => '\F334'
            ],
            [
                'class' => 'library-shelves',
                'code'  => '\FB85'
            ],
            [
                'class' => 'library-video',
                'code'  => '\FCF5'
            ],
            [
                'class' => 'lifebuoy',
                'code'  => '\F87D'
            ],
            [
                'class' => 'light-switch',
                'code'  => '\F97D'
            ],
            [
                'class' => 'lightbulb',
                'code'  => '\F335'
            ],
            [
                'class' => 'lightbulb-on',
                'code'  => '\F6E7'
            ],
            [
                'class' => 'lightbulb-on-outline',
                'code'  => '\F6E8'
            ],
            [
                'class' => 'lightbulb-outline',
                'code'  => '\F336'
            ],
            [
                'class' => 'lighthouse',
                'code'  => '\F9FE'
            ],
            [
                'class' => 'lighthouse-on',
                'code'  => '\F9FF'
            ],
            [
                'class' => 'link',
                'code'  => '\F337'
            ],
            [
                'class' => 'link-box',
                'code'  => '\FCF6'
            ],
            [
                'class' => 'link-box-outline',
                'code'  => '\FCF7'
            ],
            [
                'class' => 'link-box-variant',
                'code'  => '\FCF8'
            ],
            [
                'class' => 'link-box-variant-outline',
                'code'  => '\FCF9'
            ],
            [
                'class' => 'link-off',
                'code'  => '\F338'
            ],
            [
                'class' => 'link-plus',
                'code'  => '\FC70'
            ],
            [
                'class' => 'link-variant',
                'code'  => '\F339'
            ],
            [
                'class' => 'link-variant-off',
                'code'  => '\F33A'
            ],
            [
                'class' => 'linkedin',
                'code'  => '\F33B'
            ],
            [
                'class' => 'linkedin-box',
                'code'  => '\F33C'
            ],
            [
                'class' => 'linux',
                'code'  => '\F33D'
            ],
            [
                'class' => 'linux-mint',
                'code'  => '\F8EC'
            ],
            [
                'class' => 'litecoin',
                'code'  => '\FA60'
            ],
            [
                'class' => 'loading',
                'code'  => '\F771'
            ],
            [
                'class' => 'lock',
                'code'  => '\F33E'
            ],
            [
                'class' => 'lock-alert',
                'code'  => '\F8ED'
            ],
            [
                'class' => 'lock-clock',
                'code'  => '\F97E'
            ],
            [
                'class' => 'lock-open',
                'code'  => '\F33F'
            ],
            [
                'class' => 'lock-open-outline',
                'code'  => '\F340'
            ],
            [
                'class' => 'lock-outline',
                'code'  => '\F341'
            ],
            [
                'class' => 'lock-pattern',
                'code'  => '\F6E9'
            ],
            [
                'class' => 'lock-plus',
                'code'  => '\F5FB'
            ],
            [
                'class' => 'lock-question',
                'code'  => '\F8EE'
            ],
            [
                'class' => 'lock-reset',
                'code'  => '\F772'
            ],
            [
                'class' => 'lock-smart',
                'code'  => '\F8B1'
            ],
            [
                'class' => 'locker',
                'code'  => '\F7D6'
            ],
            [
                'class' => 'locker-multiple',
                'code'  => '\F7D7'
            ],
            [
                'class' => 'login',
                'code'  => '\F342'
            ],
            [
                'class' => 'login-variant',
                'code'  => '\F5FC'
            ],
            [
                'class' => 'logout',
                'code'  => '\F343'
            ],
            [
                'class' => 'logout-variant',
                'code'  => '\F5FD'
            ],
            [
                'class' => 'looks',
                'code'  => '\F344'
            ],
            [
                'class' => 'loop',
                'code'  => '\F6EA'
            ],
            [
                'class' => 'loupe',
                'code'  => '\F345'
            ],
            [
                'class' => 'lumx',
                'code'  => '\F346'
            ],
            [
                'class' => 'lyft',
                'code'  => '\FB1D'
            ],
            [
                'class' => 'magnet',
                'code'  => '\F347'
            ],
            [
                'class' => 'magnet-on',
                'code'  => '\F348'
            ],
            [
                'class' => 'magnify',
                'code'  => '\F349'
            ],
            [
                'class' => 'magnify-close',
                'code'  => '\F97F'
            ],
            [
                'class' => 'magnify-minus',
                'code'  => '\F34A'
            ],
            [
                'class' => 'magnify-minus-cursor',
                'code'  => '\FA61'
            ],
            [
                'class' => 'magnify-minus-outline',
                'code'  => '\F6EB'
            ],
            [
                'class' => 'magnify-plus',
                'code'  => '\F34B'
            ],
            [
                'class' => 'magnify-plus-cursor',
                'code'  => '\FA62'
            ],
            [
                'class' => 'magnify-plus-outline',
                'code'  => '\F6EC'
            ],
            [
                'class' => 'mail-ru',
                'code'  => '\F34C'
            ],
            [
                'class' => 'mailbox',
                'code'  => '\F6ED'
            ],
            [
                'class' => 'map',
                'code'  => '\F34D'
            ],
            [
                'class' => 'map-clock',
                'code'  => '\FCFA'
            ],
            [
                'class' => 'map-clock-outline',
                'code'  => '\FCFB'
            ],
            [
                'class' => 'map-legend',
                'code'  => '\FA00'
            ],
            [
                'class' => 'map-marker',
                'code'  => '\F34E'
            ],
            [
                'class' => 'map-marker-check',
                'code'  => '\FC71'
            ],
            [
                'class' => 'map-marker-circle',
                'code'  => '\F34F'
            ],
            [
                'class' => 'map-marker-distance',
                'code'  => '\F8EF'
            ],
            [
                'class' => 'map-marker-minus',
                'code'  => '\F650'
            ],
            [
                'class' => 'map-marker-multiple',
                'code'  => '\F350'
            ],
            [
                'class' => 'map-marker-off',
                'code'  => '\F351'
            ],
            [
                'class' => 'map-marker-outline',
                'code'  => '\F7D8'
            ],
            [
                'class' => 'map-marker-path',
                'code'  => '\FCFC'
            ],
            [
                'class' => 'map-marker-plus',
                'code'  => '\F651'
            ],
            [
                'class' => 'map-marker-radius',
                'code'  => '\F352'
            ],
            [
                'class' => 'map-minus',
                'code'  => '\F980'
            ],
            [
                'class' => 'map-outline',
                'code'  => '\F981'
            ],
            [
                'class' => 'map-plus',
                'code'  => '\F982'
            ],
            [
                'class' => 'map-search',
                'code'  => '\F983'
            ],
            [
                'class' => 'map-search-outline',
                'code'  => '\F984'
            ],
            [
                'class' => 'mapbox',
                'code'  => '\FB86'
            ],
            [
                'class' => 'margin',
                'code'  => '\F353'
            ],
            [
                'class' => 'markdown',
                'code'  => '\F354'
            ],
            [
                'class' => 'marker',
                'code'  => '\F652'
            ],
            [
                'class' => 'marker-check',
                'code'  => '\F355'
            ],
            [
                'class' => 'mastodon',
                'code'  => '\FAD0'
            ],
            [
                'class' => 'mastodon-variant',
                'code'  => '\FAD1'
            ],
            [
                'class' => 'material-design',
                'code'  => '\F985'
            ],
            [
                'class' => 'material-ui',
                'code'  => '\F357'
            ],
            [
                'class' => 'math-compass',
                'code'  => '\F358'
            ],
            [
                'class' => 'math-cos',
                'code'  => '\FC72'
            ],
            [
                'class' => 'math-sin',
                'code'  => '\FC73'
            ],
            [
                'class' => 'math-tan',
                'code'  => '\FC74'
            ],
            [
                'class' => 'matrix',
                'code'  => '\F628'
            ],
            [
                'class' => 'maxcdn',
                'code'  => '\F359'
            ],
            [
                'class' => 'medal',
                'code'  => '\F986'
            ],
            [
                'class' => 'medical-bag',
                'code'  => '\F6EE'
            ],
            [
                'class' => 'medium',
                'code'  => '\F35A'
            ],
            [
                'class' => 'meetup',
                'code'  => '\FAD2'
            ],
            [
                'class' => 'memory',
                'code'  => '\F35B'
            ],
            [
                'class' => 'menu',
                'code'  => '\F35C'
            ],
            [
                'class' => 'menu-down',
                'code'  => '\F35D'
            ],
            [
                'class' => 'menu-down-outline',
                'code'  => '\F6B5'
            ],
            [
                'class' => 'menu-left',
                'code'  => '\F35E'
            ],
            [
                'class' => 'menu-left-outline',
                'code'  => '\FA01'
            ],
            [
                'class' => 'menu-open',
                'code'  => '\FB87'
            ],
            [
                'class' => 'menu-right',
                'code'  => '\F35F'
            ],
            [
                'class' => 'menu-right-outline',
                'code'  => '\FA02'
            ],
            [
                'class' => 'menu-swap',
                'code'  => '\FA63'
            ],
            [
                'class' => 'menu-swap-outline',
                'code'  => '\FA64'
            ],
            [
                'class' => 'menu-up',
                'code'  => '\F360'
            ],
            [
                'class' => 'menu-up-outline',
                'code'  => '\F6B6'
            ],
            [
                'class' => 'message',
                'code'  => '\F361'
            ],
            [
                'class' => 'message-alert',
                'code'  => '\F362'
            ],
            [
                'class' => 'message-alert-outline',
                'code'  => '\FA03'
            ],
            [
                'class' => 'message-bulleted',
                'code'  => '\F6A1'
            ],
            [
                'class' => 'message-bulleted-off',
                'code'  => '\F6A2'
            ],
            [
                'class' => 'message-draw',
                'code'  => '\F363'
            ],
            [
                'class' => 'message-image',
                'code'  => '\F364'
            ],
            [
                'class' => 'message-outline',
                'code'  => '\F365'
            ],
            [
                'class' => 'message-plus',
                'code'  => '\F653'
            ],
            [
                'class' => 'message-processing',
                'code'  => '\F366'
            ],
            [
                'class' => 'message-reply',
                'code'  => '\F367'
            ],
            [
                'class' => 'message-reply-text',
                'code'  => '\F368'
            ],
            [
                'class' => 'message-settings',
                'code'  => '\F6EF'
            ],
            [
                'class' => 'message-settings-variant',
                'code'  => '\F6F0'
            ],
            [
                'class' => 'message-text',
                'code'  => '\F369'
            ],
            [
                'class' => 'message-text-outline',
                'code'  => '\F36A'
            ],
            [
                'class' => 'message-video',
                'code'  => '\F36B'
            ],
            [
                'class' => 'meteor',
                'code'  => '\F629'
            ],
            [
                'class' => 'metronome',
                'code'  => '\F7D9'
            ],
            [
                'class' => 'metronome-tick',
                'code'  => '\F7DA'
            ],
            [
                'class' => 'micro-sd',
                'code'  => '\F7DB'
            ],
            [
                'class' => 'microphone',
                'code'  => '\F36C'
            ],
            [
                'class' => 'microphone-minus',
                'code'  => '\F8B2'
            ],
            [
                'class' => 'microphone-off',
                'code'  => '\F36D'
            ],
            [
                'class' => 'microphone-outline',
                'code'  => '\F36E'
            ],
            [
                'class' => 'microphone-plus',
                'code'  => '\F8B3'
            ],
            [
                'class' => 'microphone-settings',
                'code'  => '\F36F'
            ],
            [
                'class' => 'microphone-variant',
                'code'  => '\F370'
            ],
            [
                'class' => 'microphone-variant-off',
                'code'  => '\F371'
            ],
            [
                'class' => 'microscope',
                'code'  => '\F654'
            ],
            [
                'class' => 'microsoft',
                'code'  => '\F372'
            ],
            [
                'class' => 'microsoft-dynamics',
                'code'  => '\F987'
            ],
            [
                'class' => 'microwave',
                'code'  => '\FC75'
            ],
            [
                'class' => 'midi',
                'code'  => '\F8F0'
            ],
            [
                'class' => 'midi-port',
                'code'  => '\F8F1'
            ],
            [
                'class' => 'minecraft',
                'code'  => '\F373'
            ],
            [
                'class' => 'mini-sd',
                'code'  => '\FA04'
            ],
            [
                'class' => 'minidisc',
                'code'  => '\FA05'
            ],
            [
                'class' => 'minus',
                'code'  => '\F374'
            ],
            [
                'class' => 'minus-box',
                'code'  => '\F375'
            ],
            [
                'class' => 'minus-box-outline',
                'code'  => '\F6F1'
            ],
            [
                'class' => 'minus-circle',
                'code'  => '\F376'
            ],
            [
                'class' => 'minus-circle-outline',
                'code'  => '\F377'
            ],
            [
                'class' => 'minus-network',
                'code'  => '\F378'
            ],
            [
                'class' => 'minus-network-outline',
                'code'  => '\FC76'
            ],
            [
                'class' => 'mixcloud',
                'code'  => '\F62A'
            ],
            [
                'class' => 'mixed-reality',
                'code'  => '\F87E'
            ],
            [
                'class' => 'mixer',
                'code'  => '\F7DC'
            ],
            [
                'class' => 'molecule',
                'code'  => '\FB88'
            ],
            [
                'class' => 'monitor',
                'code'  => '\F379'
            ],
            [
                'class' => 'monitor-cellphone',
                'code'  => '\F988'
            ],
            [
                'class' => 'monitor-cellphone-star',
                'code'  => '\F989'
            ],
            [
                'class' => 'monitor-dashboard',
                'code'  => '\FA06'
            ],
            [
                'class' => 'monitor-multiple',
                'code'  => '\F37A'
            ],
            [
                'class' => 'more',
                'code'  => '\F37B'
            ],
            [
                'class' => 'mother-nurse',
                'code'  => '\FCFD'
            ],
            [
                'class' => 'motorbike',
                'code'  => '\F37C'
            ],
            [
                'class' => 'mouse',
                'code'  => '\F37D'
            ],
            [
                'class' => 'mouse-bluetooth',
                'code'  => '\F98A'
            ],
            [
                'class' => 'mouse-off',
                'code'  => '\F37E'
            ],
            [
                'class' => 'mouse-variant',
                'code'  => '\F37F'
            ],
            [
                'class' => 'mouse-variant-off',
                'code'  => '\F380'
            ],
            [
                'class' => 'move-resize',
                'code'  => '\F655'
            ],
            [
                'class' => 'move-resize-variant',
                'code'  => '\F656'
            ],
            [
                'class' => 'movie',
                'code'  => '\F381'
            ],
            [
                'class' => 'movie-roll',
                'code'  => '\F7DD'
            ],
            [
                'class' => 'muffin',
                'code'  => '\F98B'
            ],
            [
                'class' => 'multiplication',
                'code'  => '\F382'
            ],
            [
                'class' => 'multiplication-box',
                'code'  => '\F383'
            ],
            [
                'class' => 'mushroom',
                'code'  => '\F7DE'
            ],
            [
                'class' => 'mushroom-outline',
                'code'  => '\F7DF'
            ],
            [
                'class' => 'music',
                'code'  => '\F759'
            ],
            [
                'class' => 'music-box',
                'code'  => '\F384'
            ],
            [
                'class' => 'music-box-outline',
                'code'  => '\F385'
            ],
            [
                'class' => 'music-circle',
                'code'  => '\F386'
            ],
            [
                'class' => 'music-circle-outline',
                'code'  => '\FAD3'
            ],
            [
                'class' => 'music-note',
                'code'  => '\F387'
            ],
            [
                'class' => 'music-note-bluetooth',
                'code'  => '\F5FE'
            ],
            [
                'class' => 'music-note-bluetooth-off',
                'code'  => '\F5FF'
            ],
            [
                'class' => 'music-note-eighth',
                'code'  => '\F388'
            ],
            [
                'class' => 'music-note-half',
                'code'  => '\F389'
            ],
            [
                'class' => 'music-note-off',
                'code'  => '\F38A'
            ],
            [
                'class' => 'music-note-quarter',
                'code'  => '\F38B'
            ],
            [
                'class' => 'music-note-sixteenth',
                'code'  => '\F38C'
            ],
            [
                'class' => 'music-note-whole',
                'code'  => '\F38D'
            ],
            [
                'class' => 'music-off',
                'code'  => '\F75A'
            ],
            [
                'class' => 'nas',
                'code'  => '\F8F2'
            ],
            [
                'class' => 'nativescript',
                'code'  => '\F87F'
            ],
            [
                'class' => 'nature',
                'code'  => '\F38E'
            ],
            [
                'class' => 'nature-people',
                'code'  => '\F38F'
            ],
            [
                'class' => 'navigation',
                'code'  => '\F390'
            ],
            [
                'class' => 'near-me',
                'code'  => '\F5CD'
            ],
            [
                'class' => 'needle',
                'code'  => '\F391'
            ],
            [
                'class' => 'netflix',
                'code'  => '\F745'
            ],
            [
                'class' => 'network',
                'code'  => '\F6F2'
            ],
            [
                'class' => 'network-off',
                'code'  => '\FC77'
            ],
            [
                'class' => 'network-off-outline',
                'code'  => '\FC78'
            ],
            [
                'class' => 'network-outline',
                'code'  => '\FC79'
            ],
            [
                'class' => 'network-strength-1',
                'code'  => '\F8F3'
            ],
            [
                'class' => 'network-strength-1-alert',
                'code'  => '\F8F4'
            ],
            [
                'class' => 'network-strength-2',
                'code'  => '\F8F5'
            ],
            [
                'class' => 'network-strength-2-alert',
                'code'  => '\F8F6'
            ],
            [
                'class' => 'network-strength-3',
                'code'  => '\F8F7'
            ],
            [
                'class' => 'network-strength-3-alert',
                'code'  => '\F8F8'
            ],
            [
                'class' => 'network-strength-4',
                'code'  => '\F8F9'
            ],
            [
                'class' => 'network-strength-4-alert',
                'code'  => '\F8FA'
            ],
            [
                'class' => 'network-strength-off',
                'code'  => '\F8FB'
            ],
            [
                'class' => 'network-strength-off-outline',
                'code'  => '\F8FC'
            ],
            [
                'class' => 'network-strength-outline',
                'code'  => '\F8FD'
            ],
            [
                'class' => 'new-box',
                'code'  => '\F394'
            ],
            [
                'class' => 'newspaper',
                'code'  => '\F395'
            ],
            [
                'class' => 'nfc',
                'code'  => '\F396'
            ],
            [
                'class' => 'nfc-tap',
                'code'  => '\F397'
            ],
            [
                'class' => 'nfc-variant',
                'code'  => '\F398'
            ],
            [
                'class' => 'ninja',
                'code'  => '\F773'
            ],
            [
                'class' => 'nintendo-switch',
                'code'  => '\F7E0'
            ],
            [
                'class' => 'nodejs',
                'code'  => '\F399'
            ],
            [
                'class' => 'not-equal',
                'code'  => '\F98C'
            ],
            [
                'class' => 'not-equal-variant',
                'code'  => '\F98D'
            ],
            [
                'class' => 'note',
                'code'  => '\F39A'
            ],
            [
                'class' => 'note-multiple',
                'code'  => '\F6B7'
            ],
            [
                'class' => 'note-multiple-outline',
                'code'  => '\F6B8'
            ],
            [
                'class' => 'note-outline',
                'code'  => '\F39B'
            ],
            [
                'class' => 'note-plus',
                'code'  => '\F39C'
            ],
            [
                'class' => 'note-plus-outline',
                'code'  => '\F39D'
            ],
            [
                'class' => 'note-text',
                'code'  => '\F39E'
            ],
            [
                'class' => 'notebook',
                'code'  => '\F82D'
            ],
            [
                'class' => 'notification-clear-all',
                'code'  => '\F39F'
            ],
            [
                'class' => 'npm',
                'code'  => '\F6F6'
            ],
            [
                'class' => 'npm-variant',
                'code'  => '\F98E'
            ],
            [
                'class' => 'npm-variant-outline',
                'code'  => '\F98F'
            ],
            [
                'class' => 'nuke',
                'code'  => '\F6A3'
            ],
            [
                'class' => 'null',
                'code'  => '\F7E1'
            ],
            [
                'class' => 'numeric',
                'code'  => '\F3A0'
            ],
            [
                'class' => 'numeric-0',
                'code'  => '0030'
            ],
            [
                'class' => 'numeric-0-box',
                'code'  => '\F3A1'
            ],
            [
                'class' => 'numeric-0-box-multiple-outline',
                'code'  => '\F3A2'
            ],
            [
                'class' => 'numeric-0-box-outline',
                'code'  => '\F3A3'
            ],
            [
                'class' => 'numeric-0-circle',
                'code'  => '\FC7A'
            ],
            [
                'class' => 'numeric-0-circle-outline',
                'code'  => '\FC7B'
            ],
            [
                'class' => 'numeric-1',
                'code'  => '0031'
            ],
            [
                'class' => 'numeric-1-box',
                'code'  => '\F3A4'
            ],
            [
                'class' => 'numeric-1-box-multiple-outline',
                'code'  => '\F3A5'
            ],
            [
                'class' => 'numeric-1-box-outline',
                'code'  => '\F3A6'
            ],
            [
                'class' => 'numeric-1-circle',
                'code'  => '\FC7C'
            ],
            [
                'class' => 'numeric-1-circle-outline',
                'code'  => '\FC7D'
            ],
            [
                'class' => 'numeric-2',
                'code'  => '0032'
            ],
            [
                'class' => 'numeric-2-box',
                'code'  => '\F3A7'
            ],
            [
                'class' => 'numeric-2-box-multiple-outline',
                'code'  => '\F3A8'
            ],
            [
                'class' => 'numeric-2-box-outline',
                'code'  => '\F3A9'
            ],
            [
                'class' => 'numeric-2-circle',
                'code'  => '\FC7E'
            ],
            [
                'class' => 'numeric-2-circle-outline',
                'code'  => '\FC7F'
            ],
            [
                'class' => 'numeric-3',
                'code'  => '0033'
            ],
            [
                'class' => 'numeric-3-box',
                'code'  => '\F3AA'
            ],
            [
                'class' => 'numeric-3-box-multiple-outline',
                'code'  => '\F3AB'
            ],
            [
                'class' => 'numeric-3-box-outline',
                'code'  => '\F3AC'
            ],
            [
                'class' => 'numeric-3-circle',
                'code'  => '\FC80'
            ],
            [
                'class' => 'numeric-3-circle-outline',
                'code'  => '\FC81'
            ],
            [
                'class' => 'numeric-4',
                'code'  => '0034'
            ],
            [
                'class' => 'numeric-4-box',
                'code'  => '\F3AD'
            ],
            [
                'class' => 'numeric-4-box-multiple-outline',
                'code'  => '\F3AE'
            ],
            [
                'class' => 'numeric-4-box-outline',
                'code'  => '\F3AF'
            ],
            [
                'class' => 'numeric-4-circle',
                'code'  => '\FC82'
            ],
            [
                'class' => 'numeric-4-circle-outline',
                'code'  => '\FC83'
            ],
            [
                'class' => 'numeric-5',
                'code'  => '0035'
            ],
            [
                'class' => 'numeric-5-box',
                'code'  => '\F3B0'
            ],
            [
                'class' => 'numeric-5-box-multiple-outline',
                'code'  => '\F3B1'
            ],
            [
                'class' => 'numeric-5-box-outline',
                'code'  => '\F3B2'
            ],
            [
                'class' => 'numeric-5-circle',
                'code'  => '\FC84'
            ],
            [
                'class' => 'numeric-5-circle-outline',
                'code'  => '\FC85'
            ],
            [
                'class' => 'numeric-6',
                'code'  => '0036'
            ],
            [
                'class' => 'numeric-6-box',
                'code'  => '\F3B3'
            ],
            [
                'class' => 'numeric-6-box-multiple-outline',
                'code'  => '\F3B4'
            ],
            [
                'class' => 'numeric-6-box-outline',
                'code'  => '\F3B5'
            ],
            [
                'class' => 'numeric-6-circle',
                'code'  => '\FC86'
            ],
            [
                'class' => 'numeric-6-circle-outline',
                'code'  => '\FC87'
            ],
            [
                'class' => 'numeric-7',
                'code'  => '0037'
            ],
            [
                'class' => 'numeric-7-box',
                'code'  => '\F3B6'
            ],
            [
                'class' => 'numeric-7-box-multiple-outline',
                'code'  => '\F3B7'
            ],
            [
                'class' => 'numeric-7-box-outline',
                'code'  => '\F3B8'
            ],
            [
                'class' => 'numeric-7-circle',
                'code'  => '\FC88'
            ],
            [
                'class' => 'numeric-7-circle-outline',
                'code'  => '\FC89'
            ],
            [
                'class' => 'numeric-8',
                'code'  => '0038'
            ],
            [
                'class' => 'numeric-8-box',
                'code'  => '\F3B9'
            ],
            [
                'class' => 'numeric-8-box-multiple-outline',
                'code'  => '\F3BA'
            ],
            [
                'class' => 'numeric-8-box-outline',
                'code'  => '\F3BB'
            ],
            [
                'class' => 'numeric-8-circle',
                'code'  => '\FC8A'
            ],
            [
                'class' => 'numeric-8-circle-outline',
                'code'  => '\FC8B'
            ],
            [
                'class' => 'numeric-9',
                'code'  => '0039'
            ],
            [
                'class' => 'numeric-9-box',
                'code'  => '\F3BC'
            ],
            [
                'class' => 'numeric-9-box-multiple-outline',
                'code'  => '\F3BD'
            ],
            [
                'class' => 'numeric-9-box-outline',
                'code'  => '\F3BE'
            ],
            [
                'class' => 'numeric-9-circle',
                'code'  => '\FC8C'
            ],
            [
                'class' => 'numeric-9-circle-outline',
                'code'  => '\FC8D'
            ],
            [
                'class' => 'numeric-9-plus-box',
                'code'  => '\F3BF'
            ],
            [
                'class' => 'numeric-9-plus-box-multiple-outline',
                'code'  => '\F3C0'
            ],
            [
                'class' => 'numeric-9-plus-box-outline',
                'code'  => '\F3C1'
            ],
            [
                'class' => 'numeric-9-plus-circle',
                'code'  => '\FC8E'
            ],
            [
                'class' => 'numeric-9-plus-circle-outline',
                'code'  => '\FC8F'
            ],
            [
                'class' => 'nut',
                'code'  => '\F6F7'
            ],
            [
                'class' => 'nutrition',
                'code'  => '\F3C2'
            ],
            [
                'class' => 'oar',
                'code'  => '\F67B'
            ],
            [
                'class' => 'octagon',
                'code'  => '\F3C3'
            ],
            [
                'class' => 'octagon-outline',
                'code'  => '\F3C4'
            ],
            [
                'class' => 'octagram',
                'code'  => '\F6F8'
            ],
            [
                'class' => 'octagram-outline',
                'code'  => '\F774'
            ],
            [
                'class' => 'odnoklassniki',
                'code'  => '\F3C5'
            ],
            [
                'class' => 'office',
                'code'  => '\F3C6'
            ],
            [
                'class' => 'office-building',
                'code'  => '\F990'
            ],
            [
                'class' => 'oil',
                'code'  => '\F3C7'
            ],
            [
                'class' => 'oil-temperature',
                'code'  => '\F3C8'
            ],
            [
                'class' => 'omega',
                'code'  => '\F3C9'
            ],
            [
                'class' => 'one-up',
                'code'  => '\FB89'
            ],
            [
                'class' => 'onedrive',
                'code'  => '\F3CA'
            ],
            [
                'class' => 'onenote',
                'code'  => '\F746'
            ],
            [
                'class' => 'onepassword',
                'code'  => '\F880'
            ],
            [
                'class' => 'opacity',
                'code'  => '\F5CC'
            ],
            [
                'class' => 'open-in-app',
                'code'  => '\F3CB'
            ],
            [
                'class' => 'open-in-new',
                'code'  => '\F3CC'
            ],
            [
                'class' => 'open-source-initiative',
                'code'  => '\FB8A'
            ],
            [
                'class' => 'openid',
                'code'  => '\F3CD'
            ],
            [
                'class' => 'opera',
                'code'  => '\F3CE'
            ],
            [
                'class' => 'orbit',
                'code'  => '\F018'
            ],
            [
                'class' => 'origin',
                'code'  => '\FB2B'
            ],
            [
                'class' => 'ornament',
                'code'  => '\F3CF'
            ],
            [
                'class' => 'ornament-variant',
                'code'  => '\F3D0'
            ],
            [
                'class' => 'outlook',
                'code'  => '\FCFE'
            ],
            [
                'class' => 'owl',
                'code'  => '\F3D2'
            ],
            [
                'class' => 'pac-man',
                'code'  => '\FB8B'
            ],
            [
                'class' => 'package',
                'code'  => '\F3D3'
            ],
            [
                'class' => 'package-down',
                'code'  => '\F3D4'
            ],
            [
                'class' => 'package-up',
                'code'  => '\F3D5'
            ],
            [
                'class' => 'package-variant',
                'code'  => '\F3D6'
            ],
            [
                'class' => 'package-variant-closed',
                'code'  => '\F3D7'
            ],
            [
                'class' => 'page-first',
                'code'  => '\F600'
            ],
            [
                'class' => 'page-last',
                'code'  => '\F601'
            ],
            [
                'class' => 'page-layout-body',
                'code'  => '\F6F9'
            ],
            [
                'class' => 'page-layout-footer',
                'code'  => '\F6FA'
            ],
            [
                'class' => 'page-layout-header',
                'code'  => '\F6FB'
            ],
            [
                'class' => 'page-layout-sidebar-left',
                'code'  => '\F6FC'
            ],
            [
                'class' => 'page-layout-sidebar-right',
                'code'  => '\F6FD'
            ],
            [
                'class' => 'page-next',
                'code'  => '\FB8C'
            ],
            [
                'class' => 'page-next-outline',
                'code'  => '\FB8D'
            ],
            [
                'class' => 'page-previous',
                'code'  => '\FB8E'
            ],
            [
                'class' => 'page-previous-outline',
                'code'  => '\FB8F'
            ],
            [
                'class' => 'palette',
                'code'  => '\F3D8'
            ],
            [
                'class' => 'palette-advanced',
                'code'  => '\F3D9'
            ],
            [
                'class' => 'palette-swatch',
                'code'  => '\F8B4'
            ],
            [
                'class' => 'pan',
                'code'  => '\FB90'
            ],
            [
                'class' => 'pan-bottom-left',
                'code'  => '\FB91'
            ],
            [
                'class' => 'pan-bottom-right',
                'code'  => '\FB92'
            ],
            [
                'class' => 'pan-down',
                'code'  => '\FB93'
            ],
            [
                'class' => 'pan-horizontal',
                'code'  => '\FB94'
            ],
            [
                'class' => 'pan-left',
                'code'  => '\FB95'
            ],
            [
                'class' => 'pan-right',
                'code'  => '\FB96'
            ],
            [
                'class' => 'pan-top-left',
                'code'  => '\FB97'
            ],
            [
                'class' => 'pan-top-right',
                'code'  => '\FB98'
            ],
            [
                'class' => 'pan-up',
                'code'  => '\FB99'
            ],
            [
                'class' => 'pan-vertical',
                'code'  => '\FB9A'
            ],
            [
                'class' => 'panda',
                'code'  => '\F3DA'
            ],
            [
                'class' => 'pandora',
                'code'  => '\F3DB'
            ],
            [
                'class' => 'panorama',
                'code'  => '\F3DC'
            ],
            [
                'class' => 'panorama-fisheye',
                'code'  => '\F3DD'
            ],
            [
                'class' => 'panorama-horizontal',
                'code'  => '\F3DE'
            ],
            [
                'class' => 'panorama-vertical',
                'code'  => '\F3DF'
            ],
            [
                'class' => 'panorama-wide-angle',
                'code'  => '\F3E0'
            ],
            [
                'class' => 'paper-cut-vertical',
                'code'  => '\F3E1'
            ],
            [
                'class' => 'paperclip',
                'code'  => '\F3E2'
            ],
            [
                'class' => 'parachute',
                'code'  => '\FC90'
            ],
            [
                'class' => 'parachute-outline',
                'code'  => '\FC91'
            ],
            [
                'class' => 'parking',
                'code'  => '\F3E3'
            ],
            [
                'class' => 'passport',
                'code'  => '\F7E2'
            ],
            [
                'class' => 'patreon',
                'code'  => '\F881'
            ],
            [
                'class' => 'pause',
                'code'  => '\F3E4'
            ],
            [
                'class' => 'pause-circle',
                'code'  => '\F3E5'
            ],
            [
                'class' => 'pause-circle-outline',
                'code'  => '\F3E6'
            ],
            [
                'class' => 'pause-octagon',
                'code'  => '\F3E7'
            ],
            [
                'class' => 'pause-octagon-outline',
                'code'  => '\F3E8'
            ],
            [
                'class' => 'paw',
                'code'  => '\F3E9'
            ],
            [
                'class' => 'paw-off',
                'code'  => '\F657'
            ],
            [
                'class' => 'paypal',
                'code'  => '\F882'
            ],
            [
                'class' => 'peace',
                'code'  => '\F883'
            ],
            [
                'class' => 'pen',
                'code'  => '\F3EA'
            ],
            [
                'class' => 'pencil',
                'code'  => '\F3EB'
            ],
            [
                'class' => 'pencil-box',
                'code'  => '\F3EC'
            ],
            [
                'class' => 'pencil-box-outline',
                'code'  => '\F3ED'
            ],
            [
                'class' => 'pencil-circle',
                'code'  => '\F6FE'
            ],
            [
                'class' => 'pencil-circle-outline',
                'code'  => '\F775'
            ],
            [
                'class' => 'pencil-lock',
                'code'  => '\F3EE'
            ],
            [
                'class' => 'pencil-off',
                'code'  => '\F3EF'
            ],
            [
                'class' => 'pencil-outline',
                'code'  => '\FC92'
            ],
            [
                'class' => 'pentagon',
                'code'  => '\F6FF'
            ],
            [
                'class' => 'pentagon-outline',
                'code'  => '\F700'
            ],
            [
                'class' => 'percent',
                'code'  => '\F3F0'
            ],
            [
                'class' => 'periodic-table',
                'code'  => '\F8B5'
            ],
            [
                'class' => 'periodic-table-co2',
                'code'  => '\F7E3'
            ],
            [
                'class' => 'periscope',
                'code'  => '\F747'
            ],
            [
                'class' => 'perspective-less',
                'code'  => '\FCFF'
            ],
            [
                'class' => 'perspective-more',
                'code'  => '\FD00'
            ],
            [
                'class' => 'pharmacy',
                'code'  => '\F3F1'
            ],
            [
                'class' => 'phone',
                'code'  => '\F3F2'
            ],
            [
                'class' => 'phone-bluetooth',
                'code'  => '\F3F3'
            ],
            [
                'class' => 'phone-classic',
                'code'  => '\F602'
            ],
            [
                'class' => 'phone-forward',
                'code'  => '\F3F4'
            ],
            [
                'class' => 'phone-hangup',
                'code'  => '\F3F5'
            ],
            [
                'class' => 'phone-in-talk',
                'code'  => '\F3F6'
            ],
            [
                'class' => 'phone-incoming',
                'code'  => '\F3F7'
            ],
            [
                'class' => 'phone-lock',
                'code'  => '\F3F8'
            ],
            [
                'class' => 'phone-log',
                'code'  => '\F3F9'
            ],
            [
                'class' => 'phone-minus',
                'code'  => '\F658'
            ],
            [
                'class' => 'phone-missed',
                'code'  => '\F3FA'
            ],
            [
                'class' => 'phone-outgoing',
                'code'  => '\F3FB'
            ],
            [
                'class' => 'phone-paused',
                'code'  => '\F3FC'
            ],
            [
                'class' => 'phone-plus',
                'code'  => '\F659'
            ],
            [
                'class' => 'phone-return',
                'code'  => '\F82E'
            ],
            [
                'class' => 'phone-rotate-landscape',
                'code'  => '\F884'
            ],
            [
                'class' => 'phone-rotate-portrait',
                'code'  => '\F885'
            ],
            [
                'class' => 'phone-settings',
                'code'  => '\F3FD'
            ],
            [
                'class' => 'phone-voip',
                'code'  => '\F3FE'
            ],
            [
                'class' => 'pi',
                'code'  => '\F3FF'
            ],
            [
                'class' => 'pi-box',
                'code'  => '\F400'
            ],
            [
                'class' => 'piano',
                'code'  => '\F67C'
            ],
            [
                'class' => 'pickaxe',
                'code'  => '\F8B6'
            ],
            [
                'class' => 'pier',
                'code'  => '\F886'
            ],
            [
                'class' => 'pier-crane',
                'code'  => '\F887'
            ],
            [
                'class' => 'pig',
                'code'  => '\F401'
            ],
            [
                'class' => 'pill',
                'code'  => '\F402'
            ],
            [
                'class' => 'pillar',
                'code'  => '\F701'
            ],
            [
                'class' => 'pin',
                'code'  => '\F403'
            ],
            [
                'class' => 'pin-off',
                'code'  => '\F404'
            ],
            [
                'class' => 'pin-off-outline',
                'code'  => '\F92F'
            ],
            [
                'class' => 'pin-outline',
                'code'  => '\F930'
            ],
            [
                'class' => 'pine-tree',
                'code'  => '\F405'
            ],
            [
                'class' => 'pine-tree-box',
                'code'  => '\F406'
            ],
            [
                'class' => 'pinterest',
                'code'  => '\F407'
            ],
            [
                'class' => 'pinterest-box',
                'code'  => '\F408'
            ],
            [
                'class' => 'pinwheel',
                'code'  => '\FAD4'
            ],
            [
                'class' => 'pinwheel-outline',
                'code'  => '\FAD5'
            ],
            [
                'class' => 'pipe',
                'code'  => '\F7E4'
            ],
            [
                'class' => 'pipe-disconnected',
                'code'  => '\F7E5'
            ],
            [
                'class' => 'pipe-leak',
                'code'  => '\F888'
            ],
            [
                'class' => 'pirate',
                'code'  => '\FA07'
            ],
            [
                'class' => 'pistol',
                'code'  => '\F702'
            ],
            [
                'class' => 'piston',
                'code'  => '\F889'
            ],
            [
                'class' => 'pizza',
                'code'  => '\F409'
            ],
            [
                'class' => 'play',
                'code'  => '\F40A'
            ],
            [
                'class' => 'play-box-outline',
                'code'  => '\F40B'
            ],
            [
                'class' => 'play-circle',
                'code'  => '\F40C'
            ],
            [
                'class' => 'play-circle-outline',
                'code'  => '\F40D'
            ],
            [
                'class' => 'play-network',
                'code'  => '\F88A'
            ],
            [
                'class' => 'play-network-outline',
                'code'  => '\FC93'
            ],
            [
                'class' => 'play-pause',
                'code'  => '\F40E'
            ],
            [
                'class' => 'play-protected-content',
                'code'  => '\F40F'
            ],
            [
                'class' => 'play-speed',
                'code'  => '\F8FE'
            ],
            [
                'class' => 'playlist-check',
                'code'  => '\F5C7'
            ],
            [
                'class' => 'playlist-edit',
                'code'  => '\F8FF'
            ],
            [
                'class' => 'playlist-minus',
                'code'  => '\F410'
            ],
            [
                'class' => 'playlist-music',
                'code'  => '\FC94'
            ],
            [
                'class' => 'playlist-music-outline',
                'code'  => '\FC95'
            ],
            [
                'class' => 'playlist-play',
                'code'  => '\F411'
            ],
            [
                'class' => 'playlist-plus',
                'code'  => '\F412'
            ],
            [
                'class' => 'playlist-remove',
                'code'  => '\F413'
            ],
            [
                'class' => 'playstation',
                'code'  => '\F414'
            ],
            [
                'class' => 'plex',
                'code'  => '\F6B9'
            ],
            [
                'class' => 'plus',
                'code'  => '\F415'
            ],
            [
                'class' => 'plus-box',
                'code'  => '\F416'
            ],
            [
                'class' => 'plus-box-outline',
                'code'  => '\F703'
            ],
            [
                'class' => 'plus-circle',
                'code'  => '\F417'
            ],
            [
                'class' => 'plus-circle-multiple-outline',
                'code'  => '\F418'
            ],
            [
                'class' => 'plus-circle-outline',
                'code'  => '\F419'
            ],
            [
                'class' => 'plus-minus',
                'code'  => '\F991'
            ],
            [
                'class' => 'plus-minus-box',
                'code'  => '\F992'
            ],
            [
                'class' => 'plus-network',
                'code'  => '\F41A'
            ],
            [
                'class' => 'plus-network-outline',
                'code'  => '\FC96'
            ],
            [
                'class' => 'plus-one',
                'code'  => '\F41B'
            ],
            [
                'class' => 'plus-outline',
                'code'  => '\F704'
            ],
            [
                'class' => 'pocket',
                'code'  => '\F41C'
            ],
            [
                'class' => 'podcast',
                'code'  => '\F993'
            ],
            [
                'class' => 'podium',
                'code'  => '\FD01'
            ],
            [
                'class' => 'podium-bronze',
                'code'  => '\FD02'
            ],
            [
                'class' => 'podium-gold',
                'code'  => '\FD03'
            ],
            [
                'class' => 'podium-silver',
                'code'  => '\FD04'
            ],
            [
                'class' => 'pokeball',
                'code'  => '\F41D'
            ],
            [
                'class' => 'pokemon-go',
                'code'  => '\FA08'
            ],
            [
                'class' => 'poker-chip',
                'code'  => '\F82F'
            ],
            [
                'class' => 'polaroid',
                'code'  => '\F41E'
            ],
            [
                'class' => 'poll',
                'code'  => '\F41F'
            ],
            [
                'class' => 'poll-box',
                'code'  => '\F420'
            ],
            [
                'class' => 'polymer',
                'code'  => '\F421'
            ],
            [
                'class' => 'pool',
                'code'  => '\F606'
            ],
            [
                'class' => 'popcorn',
                'code'  => '\F422'
            ],
            [
                'class' => 'postage-stamp',
                'code'  => '\FC97'
            ],
            [
                'class' => 'pot',
                'code'  => '\F65A'
            ],
            [
                'class' => 'pot-mix',
                'code'  => '\F65B'
            ],
            [
                'class' => 'pound',
                'code'  => '\F423'
            ],
            [
                'class' => 'pound-box',
                'code'  => '\F424'
            ],
            [
                'class' => 'power',
                'code'  => '\F425'
            ],
            [
                'class' => 'power-cycle',
                'code'  => '\F900'
            ],
            [
                'class' => 'power-off',
                'code'  => '\F901'
            ],
            [
                'class' => 'power-on',
                'code'  => '\F902'
            ],
            [
                'class' => 'power-plug',
                'code'  => '\F6A4'
            ],
            [
                'class' => 'power-plug-off',
                'code'  => '\F6A5'
            ],
            [
                'class' => 'power-settings',
                'code'  => '\F426'
            ],
            [
                'class' => 'power-sleep',
                'code'  => '\F903'
            ],
            [
                'class' => 'power-socket',
                'code'  => '\F427'
            ],
            [
                'class' => 'power-socket-au',
                'code'  => '\F904'
            ],
            [
                'class' => 'power-socket-eu',
                'code'  => '\F7E6'
            ],
            [
                'class' => 'power-socket-uk',
                'code'  => '\F7E7'
            ],
            [
                'class' => 'power-socket-us',
                'code'  => '\F7E8'
            ],
            [
                'class' => 'power-standby',
                'code'  => '\F905'
            ],
            [
                'class' => 'powershell',
                'code'  => '\FA09'
            ],
            [
                'class' => 'prescription',
                'code'  => '\F705'
            ],
            [
                'class' => 'presentation',
                'code'  => '\F428'
            ],
            [
                'class' => 'presentation-play',
                'code'  => '\F429'
            ],
            [
                'class' => 'printer',
                'code'  => '\F42A'
            ],
            [
                'class' => 'printer-3d',
                'code'  => '\F42B'
            ],
            [
                'class' => 'printer-alert',
                'code'  => '\F42C'
            ],
            [
                'class' => 'printer-settings',
                'code'  => '\F706'
            ],
            [
                'class' => 'printer-wireless',
                'code'  => '\FA0A'
            ],
            [
                'class' => 'priority-high',
                'code'  => '\F603'
            ],
            [
                'class' => 'priority-low',
                'code'  => '\F604'
            ],
            [
                'class' => 'professional-hexagon',
                'code'  => '\F42D'
            ],
            [
                'class' => 'progress-alert',
                'code'  => '\FC98'
            ],
            [
                'class' => 'progress-check',
                'code'  => '\F994'
            ],
            [
                'class' => 'progress-clock',
                'code'  => '\F995'
            ],
            [
                'class' => 'progress-download',
                'code'  => '\F996'
            ],
            [
                'class' => 'progress-upload',
                'code'  => '\F997'
            ],
            [
                'class' => 'progress-wrench',
                'code'  => '\FC99'
            ],
            [
                'class' => 'projector',
                'code'  => '\F42E'
            ],
            [
                'class' => 'projector-screen',
                'code'  => '\F42F'
            ],
            [
                'class' => 'publish',
                'code'  => '\F6A6'
            ],
            [
                'class' => 'pulse',
                'code'  => '\F430'
            ],
            [
                'class' => 'pumpkin',
                'code'  => '\FB9B'
            ],
            [
                'class' => 'puzzle',
                'code'  => '\F431'
            ],
            [
                'class' => 'puzzle-outline',
                'code'  => '\FA65'
            ],
            [
                'class' => 'qi',
                'code'  => '\F998'
            ],
            [
                'class' => 'qqchat',
                'code'  => '\F605'
            ],
            [
                'class' => 'qrcode',
                'code'  => '\F432'
            ],
            [
                'class' => 'qrcode-edit',
                'code'  => '\F8B7'
            ],
            [
                'class' => 'qrcode-scan',
                'code'  => '\F433'
            ],
            [
                'class' => 'quadcopter',
                'code'  => '\F434'
            ],
            [
                'class' => 'quality-high',
                'code'  => '\F435'
            ],
            [
                'class' => 'quality-low',
                'code'  => '\FA0B'
            ],
            [
                'class' => 'quality-medium',
                'code'  => '\FA0C'
            ],
            [
                'class' => 'quicktime',
                'code'  => '\F436'
            ],
            [
                'class' => 'quora',
                'code'  => '\FD05'
            ],
            [
                'class' => 'rabbit',
                'code'  => '\F906'
            ],
            [
                'class' => 'radar',
                'code'  => '\F437'
            ],
            [
                'class' => 'radiator',
                'code'  => '\F438'
            ],
            [
                'class' => 'radiator-disabled',
                'code'  => '\FAD6'
            ],
            [
                'class' => 'radiator-off',
                'code'  => '\FAD7'
            ],
            [
                'class' => 'radio',
                'code'  => '\F439'
            ],
            [
                'class' => 'radio-am',
                'code'  => '\FC9A'
            ],
            [
                'class' => 'radio-fm',
                'code'  => '\FC9B'
            ],
            [
                'class' => 'radio-handheld',
                'code'  => '\F43A'
            ],
            [
                'class' => 'radio-tower',
                'code'  => '\F43B'
            ],
            [
                'class' => 'radioactive',
                'code'  => '\F43C'
            ],
            [
                'class' => 'radiobox-blank',
                'code'  => '\F43D'
            ],
            [
                'class' => 'radiobox-marked',
                'code'  => '\F43E'
            ],
            [
                'class' => 'radius',
                'code'  => '\FC9C'
            ],
            [
                'class' => 'radius-outline',
                'code'  => '\FC9D'
            ],
            [
                'class' => 'raspberrypi',
                'code'  => '\F43F'
            ],
            [
                'class' => 'ray-end',
                'code'  => '\F440'
            ],
            [
                'class' => 'ray-end-arrow',
                'code'  => '\F441'
            ],
            [
                'class' => 'ray-start',
                'code'  => '\F442'
            ],
            [
                'class' => 'ray-start-arrow',
                'code'  => '\F443'
            ],
            [
                'class' => 'ray-start-end',
                'code'  => '\F444'
            ],
            [
                'class' => 'ray-vertex',
                'code'  => '\F445'
            ],
            [
                'class' => 'react',
                'code'  => '\F707'
            ],
            [
                'class' => 'read',
                'code'  => '\F447'
            ],
            [
                'class' => 'receipt',
                'code'  => '\F449'
            ],
            [
                'class' => 'record',
                'code'  => '\F44A'
            ],
            [
                'class' => 'record-player',
                'code'  => '\F999'
            ],
            [
                'class' => 'record-rec',
                'code'  => '\F44B'
            ],
            [
                'class' => 'recycle',
                'code'  => '\F44C'
            ],
            [
                'class' => 'reddit',
                'code'  => '\F44D'
            ],
            [
                'class' => 'redo',
                'code'  => '\F44E'
            ],
            [
                'class' => 'redo-variant',
                'code'  => '\F44F'
            ],
            [
                'class' => 'reflect-horizontal',
                'code'  => '\FA0D'
            ],
            [
                'class' => 'reflect-vertical',
                'code'  => '\FA0E'
            ],
            [
                'class' => 'refresh',
                'code'  => '\F450'
            ],
            [
                'class' => 'regex',
                'code'  => '\F451'
            ],
            [
                'class' => 'registered-trademark',
                'code'  => '\FA66'
            ],
            [
                'class' => 'relative-scale',
                'code'  => '\F452'
            ],
            [
                'class' => 'reload',
                'code'  => '\F453'
            ],
            [
                'class' => 'reminder',
                'code'  => '\F88B'
            ],
            [
                'class' => 'remote',
                'code'  => '\F454'
            ],
            [
                'class' => 'remote-desktop',
                'code'  => '\F8B8'
            ],
            [
                'class' => 'rename-box',
                'code'  => '\F455'
            ],
            [
                'class' => 'reorder-horizontal',
                'code'  => '\F687'
            ],
            [
                'class' => 'reorder-vertical',
                'code'  => '\F688'
            ],
            [
                'class' => 'repeat',
                'code'  => '\F456'
            ],
            [
                'class' => 'repeat-off',
                'code'  => '\F457'
            ],
            [
                'class' => 'repeat-once',
                'code'  => '\F458'
            ],
            [
                'class' => 'replay',
                'code'  => '\F459'
            ],
            [
                'class' => 'reply',
                'code'  => '\F45A'
            ],
            [
                'class' => 'reply-all',
                'code'  => '\F45B'
            ],
            [
                'class' => 'reproduction',
                'code'  => '\F45C'
            ],
            [
                'class' => 'resistor',
                'code'  => '\FB1F'
            ],
            [
                'class' => 'resistor-nodes',
                'code'  => '\FB20'
            ],
            [
                'class' => 'resize',
                'code'  => '\FA67'
            ],
            [
                'class' => 'resize-bottom-right',
                'code'  => '\F45D'
            ],
            [
                'class' => 'responsive',
                'code'  => '\F45E'
            ],
            [
                'class' => 'restart',
                'code'  => '\F708'
            ],
            [
                'class' => 'restore',
                'code'  => '\F99A'
            ],
            [
                'class' => 'restore-clock',
                'code'  => '\F6A7'
            ],
            [
                'class' => 'rewind',
                'code'  => '\F45F'
            ],
            [
                'class' => 'rewind-10',
                'code'  => '\FD06'
            ],
            [
                'class' => 'rewind-outline',
                'code'  => '\F709'
            ],
            [
                'class' => 'rhombus',
                'code'  => '\F70A'
            ],
            [
                'class' => 'rhombus-medium',
                'code'  => '\FA0F'
            ],
            [
                'class' => 'rhombus-outline',
                'code'  => '\F70B'
            ],
            [
                'class' => 'rhombus-split',
                'code'  => '\FA10'
            ],
            [
                'class' => 'ribbon',
                'code'  => '\F460'
            ],
            [
                'class' => 'rice',
                'code'  => '\F7E9'
            ],
            [
                'class' => 'ring',
                'code'  => '\F7EA'
            ],
            [
                'class' => 'road',
                'code'  => '\F461'
            ],
            [
                'class' => 'road-variant',
                'code'  => '\F462'
            ],
            [
                'class' => 'robot',
                'code'  => '\F6A8'
            ],
            [
                'class' => 'robot-industrial',
                'code'  => '\FB21'
            ],
            [
                'class' => 'robot-vacuum',
                'code'  => '\F70C'
            ],
            [
                'class' => 'robot-vacuum-variant',
                'code'  => '\F907'
            ],
            [
                'class' => 'rocket',
                'code'  => '\F463'
            ],
            [
                'class' => 'roller-skate',
                'code'  => '\FD07'
            ],
            [
                'class' => 'rollerblade',
                'code'  => '\FD08'
            ],
            [
                'class' => 'rollupjs',
                'code'  => '\FB9C'
            ],
            [
                'class' => 'room-service',
                'code'  => '\F88C'
            ],
            [
                'class' => 'rotate-3d',
                'code'  => '\F464'
            ],
            [
                'class' => 'rotate-left',
                'code'  => '\F465'
            ],
            [
                'class' => 'rotate-left-variant',
                'code'  => '\F466'
            ],
            [
                'class' => 'rotate-right',
                'code'  => '\F467'
            ],
            [
                'class' => 'rotate-right-variant',
                'code'  => '\F468'
            ],
            [
                'class' => 'rounded-corner',
                'code'  => '\F607'
            ],
            [
                'class' => 'router-wireless',
                'code'  => '\F469'
            ],
            [
                'class' => 'router-wireless-settings',
                'code'  => '\FA68'
            ],
            [
                'class' => 'routes',
                'code'  => '\F46A'
            ],
            [
                'class' => 'rowing',
                'code'  => '\F608'
            ],
            [
                'class' => 'rss',
                'code'  => '\F46B'
            ],
            [
                'class' => 'rss-box',
                'code'  => '\F46C'
            ],
            [
                'class' => 'ruby',
                'code'  => '\FD09'
            ],
            [
                'class' => 'ruler',
                'code'  => '\F46D'
            ],
            [
                'class' => 'ruler-square',
                'code'  => '\FC9E'
            ],
            [
                'class' => 'run',
                'code'  => '\F70D'
            ],
            [
                'class' => 'run-fast',
                'code'  => '\F46E'
            ],
            [
                'class' => 'sack',
                'code'  => '\FD0A'
            ],
            [
                'class' => 'sack-percent',
                'code'  => '\FD0B'
            ],
            [
                'class' => 'safe',
                'code'  => '\FA69'
            ],
            [
                'class' => 'safety-goggles',
                'code'  => '\FD0C'
            ],
            [
                'class' => 'sale',
                'code'  => '\F46F'
            ],
            [
                'class' => 'salesforce',
                'code'  => '\F88D'
            ],
            [
                'class' => 'sass',
                'code'  => '\F7EB'
            ],
            [
                'class' => 'satellite',
                'code'  => '\F470'
            ],
            [
                'class' => 'satellite-uplink',
                'code'  => '\F908'
            ],
            [
                'class' => 'satellite-variant',
                'code'  => '\F471'
            ],
            [
                'class' => 'sausage',
                'code'  => '\F8B9'
            ],
            [
                'class' => 'saxophone',
                'code'  => '\F609'
            ],
            [
                'class' => 'scale',
                'code'  => '\F472'
            ],
            [
                'class' => 'scale-balance',
                'code'  => '\F5D1'
            ],
            [
                'class' => 'scale-bathroom',
                'code'  => '\F473'
            ],
            [
                'class' => 'scanner',
                'code'  => '\F6AA'
            ],
            [
                'class' => 'scanner-off',
                'code'  => '\F909'
            ],
            [
                'class' => 'school',
                'code'  => '\F474'
            ],
            [
                'class' => 'scissors-cutting',
                'code'  => '\FA6A'
            ],
            [
                'class' => 'screen-rotation',
                'code'  => '\F475'
            ],
            [
                'class' => 'screen-rotation-lock',
                'code'  => '\F476'
            ],
            [
                'class' => 'screwdriver',
                'code'  => '\F477'
            ],
            [
                'class' => 'script',
                'code'  => '\FB9D'
            ],
            [
                'class' => 'script-outline',
                'code'  => '\F478'
            ],
            [
                'class' => 'script-text',
                'code'  => '\FB9E'
            ],
            [
                'class' => 'script-text-outline',
                'code'  => '\FB9F'
            ],
            [
                'class' => 'sd',
                'code'  => '\F479'
            ],
            [
                'class' => 'seal',
                'code'  => '\F47A'
            ],
            [
                'class' => 'search-web',
                'code'  => '\F70E'
            ],
            [
                'class' => 'seat',
                'code'  => '\FC9F'
            ],
            [
                'class' => 'seat-flat',
                'code'  => '\F47B'
            ],
            [
                'class' => 'seat-flat-angled',
                'code'  => '\F47C'
            ],
            [
                'class' => 'seat-individual-suite',
                'code'  => '\F47D'
            ],
            [
                'class' => 'seat-legroom-extra',
                'code'  => '\F47E'
            ],
            [
                'class' => 'seat-legroom-normal',
                'code'  => '\F47F'
            ],
            [
                'class' => 'seat-legroom-reduced',
                'code'  => '\F480'
            ],
            [
                'class' => 'seat-outline',
                'code'  => '\FCA0'
            ],
            [
                'class' => 'seat-recline-extra',
                'code'  => '\F481'
            ],
            [
                'class' => 'seat-recline-normal',
                'code'  => '\F482'
            ],
            [
                'class' => 'seatbelt',
                'code'  => '\FCA1'
            ],
            [
                'class' => 'security',
                'code'  => '\F483'
            ],
            [
                'class' => 'security-network',
                'code'  => '\F484'
            ],
            [
                'class' => 'select',
                'code'  => '\F485'
            ],
            [
                'class' => 'select-all',
                'code'  => '\F486'
            ],
            [
                'class' => 'select-color',
                'code'  => '\FD0D'
            ],
            [
                'class' => 'select-compare',
                'code'  => '\FAD8'
            ],
            [
                'class' => 'select-drag',
                'code'  => '\FA6B'
            ],
            [
                'class' => 'select-inverse',
                'code'  => '\F487'
            ],
            [
                'class' => 'select-off',
                'code'  => '\F488'
            ],
            [
                'class' => 'selection',
                'code'  => '\F489'
            ],
            [
                'class' => 'selection-drag',
                'code'  => '\FA6C'
            ],
            [
                'class' => 'selection-ellipse',
                'code'  => '\FD0E'
            ],
            [
                'class' => 'selection-off',
                'code'  => '\F776'
            ],
            [
                'class' => 'send',
                'code'  => '\F48A'
            ],
            [
                'class' => 'send-lock',
                'code'  => '\F7EC'
            ],
            [
                'class' => 'serial-port',
                'code'  => '\F65C'
            ],
            [
                'class' => 'server',
                'code'  => '\F48B'
            ],
            [
                'class' => 'server-minus',
                'code'  => '\F48C'
            ],
            [
                'class' => 'server-network',
                'code'  => '\F48D'
            ],
            [
                'class' => 'server-network-off',
                'code'  => '\F48E'
            ],
            [
                'class' => 'server-off',
                'code'  => '\F48F'
            ],
            [
                'class' => 'server-plus',
                'code'  => '\F490'
            ],
            [
                'class' => 'server-remove',
                'code'  => '\F491'
            ],
            [
                'class' => 'server-security',
                'code'  => '\F492'
            ],
            [
                'class' => 'set-all',
                'code'  => '\F777'
            ],
            [
                'class' => 'set-center',
                'code'  => '\F778'
            ],
            [
                'class' => 'set-center-right',
                'code'  => '\F779'
            ],
            [
                'class' => 'set-left',
                'code'  => '\F77A'
            ],
            [
                'class' => 'set-left-center',
                'code'  => '\F77B'
            ],
            [
                'class' => 'set-left-right',
                'code'  => '\F77C'
            ],
            [
                'class' => 'set-none',
                'code'  => '\F77D'
            ],
            [
                'class' => 'set-right',
                'code'  => '\F77E'
            ],
            [
                'class' => 'set-top-box',
                'code'  => '\F99E'
            ],
            [
                'class' => 'settings',
                'code'  => '\F493'
            ],
            [
                'class' => 'settings-box',
                'code'  => '\F494'
            ],
            [
                'class' => 'settings-helper',
                'code'  => '\FA6D'
            ],
            [
                'class' => 'settings-outline',
                'code'  => '\F8BA'
            ],
            [
                'class' => 'shape',
                'code'  => '\F830'
            ],
            [
                'class' => 'shape-circle-plus',
                'code'  => '\F65D'
            ],
            [
                'class' => 'shape-outline',
                'code'  => '\F831'
            ],
            [
                'class' => 'shape-plus',
                'code'  => '\F495'
            ],
            [
                'class' => 'shape-polygon-plus',
                'code'  => '\F65E'
            ],
            [
                'class' => 'shape-rectangle-plus',
                'code'  => '\F65F'
            ],
            [
                'class' => 'shape-square-plus',
                'code'  => '\F660'
            ],
            [
                'class' => 'share',
                'code'  => '\F496'
            ],
            [
                'class' => 'share-outline',
                'code'  => '\F931'
            ],
            [
                'class' => 'share-variant',
                'code'  => '\F497'
            ],
            [
                'class' => 'sheep',
                'code'  => '\FCA2'
            ],
            [
                'class' => 'shield',
                'code'  => '\F498'
            ],
            [
                'class' => 'shield-account',
                'code'  => '\F88E'
            ],
            [
                'class' => 'shield-account-outline',
                'code'  => '\FA11'
            ],
            [
                'class' => 'shield-airplane',
                'code'  => '\F6BA'
            ],
            [
                'class' => 'shield-airplane-outline',
                'code'  => '\FCA3'
            ],
            [
                'class' => 'shield-check',
                'code'  => '\F565'
            ],
            [
                'class' => 'shield-check-outline',
                'code'  => '\FCA4'
            ],
            [
                'class' => 'shield-cross',
                'code'  => '\FCA5'
            ],
            [
                'class' => 'shield-cross-outline',
                'code'  => '\FCA6'
            ],
            [
                'class' => 'shield-half-full',
                'code'  => '\F77F'
            ],
            [
                'class' => 'shield-home',
                'code'  => '\F689'
            ],
            [
                'class' => 'shield-home-outline',
                'code'  => '\FCA7'
            ],
            [
                'class' => 'shield-key',
                'code'  => '\FBA0'
            ],
            [
                'class' => 'shield-key-outline',
                'code'  => '\FBA1'
            ],
            [
                'class' => 'shield-link-variant',
                'code'  => '\FD0F'
            ],
            [
                'class' => 'shield-link-variant-outline',
                'code'  => '\FD10'
            ],
            [
                'class' => 'shield-lock',
                'code'  => '\F99C'
            ],
            [
                'class' => 'shield-lock-outline',
                'code'  => '\FCA8'
            ],
            [
                'class' => 'shield-off',
                'code'  => '\F99D'
            ],
            [
                'class' => 'shield-off-outline',
                'code'  => '\F99B'
            ],
            [
                'class' => 'shield-outline',
                'code'  => '\F499'
            ],
            [
                'class' => 'shield-plus',
                'code'  => '\FAD9'
            ],
            [
                'class' => 'shield-plus-outline',
                'code'  => '\FADA'
            ],
            [
                'class' => 'shield-remove',
                'code'  => '\FADB'
            ],
            [
                'class' => 'shield-remove-outline',
                'code'  => '\FADC'
            ],
            [
                'class' => 'ship-wheel',
                'code'  => '\F832'
            ],
            [
                'class' => 'shoe-formal',
                'code'  => '\FB22'
            ],
            [
                'class' => 'shoe-heel',
                'code'  => '\FB23'
            ],
            [
                'class' => 'shopify',
                'code'  => '\FADD'
            ],
            [
                'class' => 'shopping',
                'code'  => '\F49A'
            ],
            [
                'class' => 'shopping-music',
                'code'  => '\F49B'
            ],
            [
                'class' => 'shovel',
                'code'  => '\F70F'
            ],
            [
                'class' => 'shovel-off',
                'code'  => '\F710'
            ],
            [
                'class' => 'shower',
                'code'  => '\F99F'
            ],
            [
                'class' => 'shower-head',
                'code'  => '\F9A0'
            ],
            [
                'class' => 'shredder',
                'code'  => '\F49C'
            ],
            [
                'class' => 'shuffle',
                'code'  => '\F49D'
            ],
            [
                'class' => 'shuffle-disabled',
                'code'  => '\F49E'
            ],
            [
                'class' => 'shuffle-variant',
                'code'  => '\F49F'
            ],
            [
                'class' => 'sigma',
                'code'  => '\F4A0'
            ],
            [
                'class' => 'sigma-lower',
                'code'  => '\F62B'
            ],
            [
                'class' => 'sign-caution',
                'code'  => '\F4A1'
            ],
            [
                'class' => 'sign-direction',
                'code'  => '\F780'
            ],
            [
                'class' => 'sign-text',
                'code'  => '\F781'
            ],
            [
                'class' => 'signal',
                'code'  => '\F4A2'
            ],
            [
                'class' => 'signal-2g',
                'code'  => '\F711'
            ],
            [
                'class' => 'signal-3g',
                'code'  => '\F712'
            ],
            [
                'class' => 'signal-4g',
                'code'  => '\F713'
            ],
            [
                'class' => 'signal-5g',
                'code'  => '\FA6E'
            ],
            [
                'class' => 'signal-cellular-1',
                'code'  => '\F8BB'
            ],
            [
                'class' => 'signal-cellular-2',
                'code'  => '\F8BC'
            ],
            [
                'class' => 'signal-cellular-3',
                'code'  => '\F8BD'
            ],
            [
                'class' => 'signal-cellular-outline',
                'code'  => '\F8BE'
            ],
            [
                'class' => 'signal-hspa',
                'code'  => '\F714'
            ],
            [
                'class' => 'signal-hspa-plus',
                'code'  => '\F715'
            ],
            [
                'class' => 'signal-off',
                'code'  => '\F782'
            ],
            [
                'class' => 'signal-variant',
                'code'  => '\F60A'
            ],
            [
                'class' => 'silo',
                'code'  => '\FB24'
            ],
            [
                'class' => 'silverware',
                'code'  => '\F4A3'
            ],
            [
                'class' => 'silverware-fork',
                'code'  => '\F4A4'
            ],
            [
                'class' => 'silverware-fork-knife',
                'code'  => '\FA6F'
            ],
            [
                'class' => 'silverware-spoon',
                'code'  => '\F4A5'
            ],
            [
                'class' => 'silverware-variant',
                'code'  => '\F4A6'
            ],
            [
                'class' => 'sim',
                'code'  => '\F4A7'
            ],
            [
                'class' => 'sim-alert',
                'code'  => '\F4A8'
            ],
            [
                'class' => 'sim-off',
                'code'  => '\F4A9'
            ],
            [
                'class' => 'sina-weibo',
                'code'  => '\FADE'
            ],
            [
                'class' => 'sitemap',
                'code'  => '\F4AA'
            ],
            [
                'class' => 'skate',
                'code'  => '\FD11'
            ],
            [
                'class' => 'skew-less',
                'code'  => '\FD12'
            ],
            [
                'class' => 'skew-more',
                'code'  => '\FD13'
            ],
            [
                'class' => 'skip-backward',
                'code'  => '\F4AB'
            ],
            [
                'class' => 'skip-forward',
                'code'  => '\F4AC'
            ],
            [
                'class' => 'skip-next',
                'code'  => '\F4AD'
            ],
            [
                'class' => 'skip-next-circle',
                'code'  => '\F661'
            ],
            [
                'class' => 'skip-next-circle-outline',
                'code'  => '\F662'
            ],
            [
                'class' => 'skip-previous',
                'code'  => '\F4AE'
            ],
            [
                'class' => 'skip-previous-circle',
                'code'  => '\F663'
            ],
            [
                'class' => 'skip-previous-circle-outline',
                'code'  => '\F664'
            ],
            [
                'class' => 'skull',
                'code'  => '\F68B'
            ],
            [
                'class' => 'skull-crossbones',
                'code'  => '\FBA2'
            ],
            [
                'class' => 'skull-crossbones-outline',
                'code'  => '\FBA3'
            ],
            [
                'class' => 'skull-outline',
                'code'  => '\FBA4'
            ],
            [
                'class' => 'skype',
                'code'  => '\F4AF'
            ],
            [
                'class' => 'skype-business',
                'code'  => '\F4B0'
            ],
            [
                'class' => 'slack',
                'code'  => '\F4B1'
            ],
            [
                'class' => 'slackware',
                'code'  => '\F90A'
            ],
            [
                'class' => 'sleep',
                'code'  => '\F4B2'
            ],
            [
                'class' => 'sleep-off',
                'code'  => '\F4B3'
            ],
            [
                'class' => 'smog',
                'code'  => '\FA70'
            ],
            [
                'class' => 'smoke-detector',
                'code'  => '\F392'
            ],
            [
                'class' => 'smoking',
                'code'  => '\F4B4'
            ],
            [
                'class' => 'smoking-off',
                'code'  => '\F4B5'
            ],
            [
                'class' => 'snapchat',
                'code'  => '\F4B6'
            ],
            [
                'class' => 'snowflake',
                'code'  => '\F716'
            ],
            [
                'class' => 'snowman',
                'code'  => '\F4B7'
            ],
            [
                'class' => 'soccer',
                'code'  => '\F4B8'
            ],
            [
                'class' => 'soccer-field',
                'code'  => '\F833'
            ],
            [
                'class' => 'sofa',
                'code'  => '\F4B9'
            ],
            [
                'class' => 'solar-power',
                'code'  => '\FA71'
            ],
            [
                'class' => 'solid',
                'code'  => '\F68C'
            ],
            [
                'class' => 'sort',
                'code'  => '\F4BA'
            ],
            [
                'class' => 'sort-alphabetical',
                'code'  => '\F4BB'
            ],
            [
                'class' => 'sort-ascending',
                'code'  => '\F4BC'
            ],
            [
                'class' => 'sort-descending',
                'code'  => '\F4BD'
            ],
            [
                'class' => 'sort-numeric',
                'code'  => '\F4BE'
            ],
            [
                'class' => 'sort-variant',
                'code'  => '\F4BF'
            ],
            [
                'class' => 'sort-variant-lock',
                'code'  => '\FCA9'
            ],
            [
                'class' => 'sort-variant-lock-open',
                'code'  => '\FCAA'
            ],
            [
                'class' => 'soundcloud',
                'code'  => '\F4C0'
            ],
            [
                'class' => 'source-branch',
                'code'  => '\F62C'
            ],
            [
                'class' => 'source-commit',
                'code'  => '\F717'
            ],
            [
                'class' => 'source-commit-end',
                'code'  => '\F718'
            ],
            [
                'class' => 'source-commit-end-local',
                'code'  => '\F719'
            ],
            [
                'class' => 'source-commit-local',
                'code'  => '\F71A'
            ],
            [
                'class' => 'source-commit-next-local',
                'code'  => '\F71B'
            ],
            [
                'class' => 'source-commit-start',
                'code'  => '\F71C'
            ],
            [
                'class' => 'source-commit-start-next-local',
                'code'  => '\F71D'
            ],
            [
                'class' => 'source-fork',
                'code'  => '\F4C1'
            ],
            [
                'class' => 'source-merge',
                'code'  => '\F62D'
            ],
            [
                'class' => 'source-pull',
                'code'  => '\F4C2'
            ],
            [
                'class' => 'source-repository',
                'code'  => '\FCAB'
            ],
            [
                'class' => 'source-repository-multiple',
                'code'  => '\FCAC'
            ],
            [
                'class' => 'soy-sauce',
                'code'  => '\F7ED'
            ],
            [
                'class' => 'spa',
                'code'  => '\FCAD'
            ],
            [
                'class' => 'spa-outline',
                'code'  => '\FCAE'
            ],
            [
                'class' => 'space-invaders',
                'code'  => '\FBA5'
            ],
            [
                'class' => 'speaker',
                'code'  => '\F4C3'
            ],
            [
                'class' => 'speaker-bluetooth',
                'code'  => '\F9A1'
            ],
            [
                'class' => 'speaker-multiple',
                'code'  => '\FD14'
            ],
            [
                'class' => 'speaker-off',
                'code'  => '\F4C4'
            ],
            [
                'class' => 'speaker-wireless',
                'code'  => '\F71E'
            ],
            [
                'class' => 'speedometer',
                'code'  => '\F4C5'
            ],
            [
                'class' => 'spellcheck',
                'code'  => '\F4C6'
            ],
            [
                'class' => 'spider-web',
                'code'  => '\FBA6'
            ],
            [
                'class' => 'spotify',
                'code'  => '\F4C7'
            ],
            [
                'class' => 'spotlight',
                'code'  => '\F4C8'
            ],
            [
                'class' => 'spotlight-beam',
                'code'  => '\F4C9'
            ],
            [
                'class' => 'spray',
                'code'  => '\F665'
            ],
            [
                'class' => 'spray-bottle',
                'code'  => '\FADF'
            ],
            [
                'class' => 'square',
                'code'  => '\F763'
            ],
            [
                'class' => 'square-edit-outline',
                'code'  => '\F90B'
            ],
            [
                'class' => 'square-inc',
                'code'  => '\F4CA'
            ],
            [
                'class' => 'square-inc-cash',
                'code'  => '\F4CB'
            ],
            [
                'class' => 'square-medium',
                'code'  => '\FA12'
            ],
            [
                'class' => 'square-medium-outline',
                'code'  => '\FA13'
            ],
            [
                'class' => 'square-outline',
                'code'  => '\F762'
            ],
            [
                'class' => 'square-root',
                'code'  => '\F783'
            ],
            [
                'class' => 'square-root-box',
                'code'  => '\F9A2'
            ],
            [
                'class' => 'square-small',
                'code'  => '\FA14'
            ],
            [
                'class' => 'squeegee',
                'code'  => '\FAE0'
            ],
            [
                'class' => 'ssh',
                'code'  => '\F8BF'
            ],
            [
                'class' => 'stack-exchange',
                'code'  => '\F60B'
            ],
            [
                'class' => 'stack-overflow',
                'code'  => '\F4CC'
            ],
            [
                'class' => 'stadium',
                'code'  => '\F71F'
            ],
            [
                'class' => 'stairs',
                'code'  => '\F4CD'
            ],
            [
                'class' => 'stamper',
                'code'  => '\FD15'
            ],
            [
                'class' => 'standard-definition',
                'code'  => '\F7EE'
            ],
            [
                'class' => 'star',
                'code'  => '\F4CE'
            ],
            [
                'class' => 'star-box',
                'code'  => '\FA72'
            ],
            [
                'class' => 'star-box-outline',
                'code'  => '\FA73'
            ],
            [
                'class' => 'star-circle',
                'code'  => '\F4CF'
            ],
            [
                'class' => 'star-circle-outline',
                'code'  => '\F9A3'
            ],
            [
                'class' => 'star-face',
                'code'  => '\F9A4'
            ],
            [
                'class' => 'star-four-points',
                'code'  => '\FAE1'
            ],
            [
                'class' => 'star-four-points-outline',
                'code'  => '\FAE2'
            ],
            [
                'class' => 'star-half',
                'code'  => '\F4D0'
            ],
            [
                'class' => 'star-off',
                'code'  => '\F4D1'
            ],
            [
                'class' => 'star-outline',
                'code'  => '\F4D2'
            ],
            [
                'class' => 'star-three-points',
                'code'  => '\FAE3'
            ],
            [
                'class' => 'star-three-points-outline',
                'code'  => '\FAE4'
            ],
            [
                'class' => 'steam',
                'code'  => '\F4D3'
            ],
            [
                'class' => 'steam-box',
                'code'  => '\F90C'
            ],
            [
                'class' => 'steering',
                'code'  => '\F4D4'
            ],
            [
                'class' => 'steering-off',
                'code'  => '\F90D'
            ],
            [
                'class' => 'step-backward',
                'code'  => '\F4D5'
            ],
            [
                'class' => 'step-backward-2',
                'code'  => '\F4D6'
            ],
            [
                'class' => 'step-forward',
                'code'  => '\F4D7'
            ],
            [
                'class' => 'step-forward-2',
                'code'  => '\F4D8'
            ],
            [
                'class' => 'stethoscope',
                'code'  => '\F4D9'
            ],
            [
                'class' => 'sticker',
                'code'  => '\F5D0'
            ],
            [
                'class' => 'sticker-emoji',
                'code'  => '\F784'
            ],
            [
                'class' => 'stocking',
                'code'  => '\F4DA'
            ],
            [
                'class' => 'stop',
                'code'  => '\F4DB'
            ],
            [
                'class' => 'stop-circle',
                'code'  => '\F666'
            ],
            [
                'class' => 'stop-circle-outline',
                'code'  => '\F667'
            ],
            [
                'class' => 'store',
                'code'  => '\F4DC'
            ],
            [
                'class' => 'store-24-hour',
                'code'  => '\F4DD'
            ],
            [
                'class' => 'stove',
                'code'  => '\F4DE'
            ],
            [
                'class' => 'strava',
                'code'  => '\FB25'
            ],
            [
                'class' => 'subdirectory-arrow-left',
                'code'  => '\F60C'
            ],
            [
                'class' => 'subdirectory-arrow-right',
                'code'  => '\F60D'
            ],
            [
                'class' => 'subtitles',
                'code'  => '\FA15'
            ],
            [
                'class' => 'subtitles-outline',
                'code'  => '\FA16'
            ],
            [
                'class' => 'subway',
                'code'  => '\F6AB'
            ],
            [
                'class' => 'subway-variant',
                'code'  => '\F4DF'
            ],
            [
                'class' => 'summit',
                'code'  => '\F785'
            ],
            [
                'class' => 'sunglasses',
                'code'  => '\F4E0'
            ],
            [
                'class' => 'surround-sound',
                'code'  => '\F5C5'
            ],
            [
                'class' => 'surround-sound-2-0',
                'code'  => '\F7EF'
            ],
            [
                'class' => 'surround-sound-3-1',
                'code'  => '\F7F0'
            ],
            [
                'class' => 'surround-sound-5-1',
                'code'  => '\F7F1'
            ],
            [
                'class' => 'surround-sound-7-1',
                'code'  => '\F7F2'
            ],
            [
                'class' => 'svg',
                'code'  => '\F720'
            ],
            [
                'class' => 'swap-horizontal',
                'code'  => '\F4E1'
            ],
            [
                'class' => 'swap-horizontal-bold',
                'code'  => '\FBA9'
            ],
            [
                'class' => 'swap-horizontal-variant',
                'code'  => '\F8C0'
            ],
            [
                'class' => 'swap-vertical',
                'code'  => '\F4E2'
            ],
            [
                'class' => 'swap-vertical-bold',
                'code'  => '\FBAA'
            ],
            [
                'class' => 'swap-vertical-variant',
                'code'  => '\F8C1'
            ],
            [
                'class' => 'swim',
                'code'  => '\F4E3'
            ],
            [
                'class' => 'switch',
                'code'  => '\F4E4'
            ],
            [
                'class' => 'sword',
                'code'  => '\F4E5'
            ],
            [
                'class' => 'sword-cross',
                'code'  => '\F786'
            ],
            [
                'class' => 'symfony',
                'code'  => '\FAE5'
            ],
            [
                'class' => 'sync',
                'code'  => '\F4E6'
            ],
            [
                'class' => 'sync-alert',
                'code'  => '\F4E7'
            ],
            [
                'class' => 'sync-off',
                'code'  => '\F4E8'
            ],
            [
                'class' => 'tab',
                'code'  => '\F4E9'
            ],
            [
                'class' => 'tab-minus',
                'code'  => '\FB26'
            ],
            [
                'class' => 'tab-plus',
                'code'  => '\F75B'
            ],
            [
                'class' => 'tab-remove',
                'code'  => '\FB27'
            ],
            [
                'class' => 'tab-unselected',
                'code'  => '\F4EA'
            ],
            [
                'class' => 'table',
                'code'  => '\F4EB'
            ],
            [
                'class' => 'table-border',
                'code'  => '\FA17'
            ],
            [
                'class' => 'table-column',
                'code'  => '\F834'
            ],
            [
                'class' => 'table-column-plus-after',
                'code'  => '\F4EC'
            ],
            [
                'class' => 'table-column-plus-before',
                'code'  => '\F4ED'
            ],
            [
                'class' => 'table-column-remove',
                'code'  => '\F4EE'
            ],
            [
                'class' => 'table-column-width',
                'code'  => '\F4EF'
            ],
            [
                'class' => 'table-edit',
                'code'  => '\F4F0'
            ],
            [
                'class' => 'table-large',
                'code'  => '\F4F1'
            ],
            [
                'class' => 'table-merge-cells',
                'code'  => '\F9A5'
            ],
            [
                'class' => 'table-of-contents',
                'code'  => '\F835'
            ],
            [
                'class' => 'table-plus',
                'code'  => '\FA74'
            ],
            [
                'class' => 'table-remove',
                'code'  => '\FA75'
            ],
            [
                'class' => 'table-row',
                'code'  => '\F836'
            ],
            [
                'class' => 'table-row-height',
                'code'  => '\F4F2'
            ],
            [
                'class' => 'table-row-plus-after',
                'code'  => '\F4F3'
            ],
            [
                'class' => 'table-row-plus-before',
                'code'  => '\F4F4'
            ],
            [
                'class' => 'table-row-remove',
                'code'  => '\F4F5'
            ],
            [
                'class' => 'table-search',
                'code'  => '\F90E'
            ],
            [
                'class' => 'table-settings',
                'code'  => '\F837'
            ],
            [
                'class' => 'tablet',
                'code'  => '\F4F6'
            ],
            [
                'class' => 'tablet-android',
                'code'  => '\F4F7'
            ],
            [
                'class' => 'tablet-cellphone',
                'code'  => '\F9A6'
            ],
            [
                'class' => 'tablet-ipad',
                'code'  => '\F4F8'
            ],
            [
                'class' => 'taco',
                'code'  => '\F761'
            ],
            [
                'class' => 'tag',
                'code'  => '\F4F9'
            ],
            [
                'class' => 'tag-faces',
                'code'  => '\F4FA'
            ],
            [
                'class' => 'tag-heart',
                'code'  => '\F68A'
            ],
            [
                'class' => 'tag-heart-outline',
                'code'  => '\FBAB'
            ],
            [
                'class' => 'tag-minus',
                'code'  => '\F90F'
            ],
            [
                'class' => 'tag-multiple',
                'code'  => '\F4FB'
            ],
            [
                'class' => 'tag-outline',
                'code'  => '\F4FC'
            ],
            [
                'class' => 'tag-plus',
                'code'  => '\F721'
            ],
            [
                'class' => 'tag-remove',
                'code'  => '\F722'
            ],
            [
                'class' => 'tag-text-outline',
                'code'  => '\F4FD'
            ],
            [
                'class' => 'tank',
                'code'  => '\FD16'
            ],
            [
                'class' => 'tape-measure',
                'code'  => '\FB28'
            ],
            [
                'class' => 'target',
                'code'  => '\F4FE'
            ],
            [
                'class' => 'target-account',
                'code'  => '\FBAC'
            ],
            [
                'class' => 'target-variant',
                'code'  => '\FA76'
            ],
            [
                'class' => 'taxi',
                'code'  => '\F4FF'
            ],
            [
                'class' => 'teach',
                'code'  => '\F88F'
            ],
            [
                'class' => 'teamviewer',
                'code'  => '\F500'
            ],
            [
                'class' => 'telegram',
                'code'  => '\F501'
            ],
            [
                'class' => 'telescope',
                'code'  => '\FB29'
            ],
            [
                'class' => 'television',
                'code'  => '\F502'
            ],
            [
                'class' => 'television-box',
                'code'  => '\F838'
            ],
            [
                'class' => 'television-classic',
                'code'  => '\F7F3'
            ],
            [
                'class' => 'television-classic-off',
                'code'  => '\F839'
            ],
            [
                'class' => 'television-guide',
                'code'  => '\F503'
            ],
            [
                'class' => 'television-off',
                'code'  => '\F83A'
            ],
            [
                'class' => 'temperature-celsius',
                'code'  => '\F504'
            ],
            [
                'class' => 'temperature-fahrenheit',
                'code'  => '\F505'
            ],
            [
                'class' => 'temperature-kelvin',
                'code'  => '\F506'
            ],
            [
                'class' => 'tennis',
                'code'  => '\F507'
            ],
            [
                'class' => 'tent',
                'code'  => '\F508'
            ],
            [
                'class' => 'terrain',
                'code'  => '\F509'
            ],
            [
                'class' => 'test-tube',
                'code'  => '\F668'
            ],
            [
                'class' => 'test-tube-empty',
                'code'  => '\F910'
            ],
            [
                'class' => 'test-tube-off',
                'code'  => '\F911'
            ],
            [
                'class' => 'text',
                'code'  => '\F9A7'
            ],
            [
                'class' => 'text-shadow',
                'code'  => '\F669'
            ],
            [
                'class' => 'text-short',
                'code'  => '\F9A8'
            ],
            [
                'class' => 'text-subject',
                'code'  => '\F9A9'
            ],
            [
                'class' => 'text-to-speech',
                'code'  => '\F50A'
            ],
            [
                'class' => 'text-to-speech-off',
                'code'  => '\F50B'
            ],
            [
                'class' => 'textbox',
                'code'  => '\F60E'
            ],
            [
                'class' => 'textbox-password',
                'code'  => '\F7F4'
            ],
            [
                'class' => 'texture',
                'code'  => '\F50C'
            ],
            [
                'class' => 'theater',
                'code'  => '\F50D'
            ],
            [
                'class' => 'theme-light-dark',
                'code'  => '\F50E'
            ],
            [
                'class' => 'thermometer',
                'code'  => '\F50F'
            ],
            [
                'class' => 'thermometer-lines',
                'code'  => '\F510'
            ],
            [
                'class' => 'thermostat',
                'code'  => '\F393'
            ],
            [
                'class' => 'thermostat-box',
                'code'  => '\F890'
            ],
            [
                'class' => 'thought-bubble',
                'code'  => '\F7F5'
            ],
            [
                'class' => 'thought-bubble-outline',
                'code'  => '\F7F6'
            ],
            [
                'class' => 'thumb-down',
                'code'  => '\F511'
            ],
            [
                'class' => 'thumb-down-outline',
                'code'  => '\F512'
            ],
            [
                'class' => 'thumb-up',
                'code'  => '\F513'
            ],
            [
                'class' => 'thumb-up-outline',
                'code'  => '\F514'
            ],
            [
                'class' => 'thumbs-up-down',
                'code'  => '\F515'
            ],
            [
                'class' => 'ticket',
                'code'  => '\F516'
            ],
            [
                'class' => 'ticket-account',
                'code'  => '\F517'
            ],
            [
                'class' => 'ticket-confirmation',
                'code'  => '\F518'
            ],
            [
                'class' => 'ticket-outline',
                'code'  => '\F912'
            ],
            [
                'class' => 'ticket-percent',
                'code'  => '\F723'
            ],
            [
                'class' => 'tie',
                'code'  => '\F519'
            ],
            [
                'class' => 'tilde',
                'code'  => '\F724'
            ],
            [
                'class' => 'timelapse',
                'code'  => '\F51A'
            ],
            [
                'class' => 'timeline',
                'code'  => '\FBAD'
            ],
            [
                'class' => 'timeline-outline',
                'code'  => '\FBAE'
            ],
            [
                'class' => 'timeline-text',
                'code'  => '\FBAF'
            ],
            [
                'class' => 'timeline-text-outline',
                'code'  => '\FBB0'
            ],
            [
                'class' => 'timer',
                'code'  => '\F51B'
            ],
            [
                'class' => 'timer-10',
                'code'  => '\F51C'
            ],
            [
                'class' => 'timer-3',
                'code'  => '\F51D'
            ],
            [
                'class' => 'timer-off',
                'code'  => '\F51E'
            ],
            [
                'class' => 'timer-sand',
                'code'  => '\F51F'
            ],
            [
                'class' => 'timer-sand-empty',
                'code'  => '\F6AC'
            ],
            [
                'class' => 'timer-sand-full',
                'code'  => '\F78B'
            ],
            [
                'class' => 'timetable',
                'code'  => '\F520'
            ],
            [
                'class' => 'toaster-oven',
                'code'  => '\FCAF'
            ],
            [
                'class' => 'toggle-switch',
                'code'  => '\F521'
            ],
            [
                'class' => 'toggle-switch-off',
                'code'  => '\F522'
            ],
            [
                'class' => 'toggle-switch-off-outline',
                'code'  => '\FA18'
            ],
            [
                'class' => 'toggle-switch-outline',
                'code'  => '\FA19'
            ],
            [
                'class' => 'toilet',
                'code'  => '\F9AA'
            ],
            [
                'class' => 'toolbox',
                'code'  => '\F9AB'
            ],
            [
                'class' => 'toolbox-outline',
                'code'  => '\F9AC'
            ],
            [
                'class' => 'tooltip',
                'code'  => '\F523'
            ],
            [
                'class' => 'tooltip-account',
                'code'  => '\F00C'
            ],
            [
                'class' => 'tooltip-edit',
                'code'  => '\F524'
            ],
            [
                'class' => 'tooltip-image',
                'code'  => '\F525'
            ],
            [
                'class' => 'tooltip-image-outline',
                'code'  => '\FBB1'
            ],
            [
                'class' => 'tooltip-outline',
                'code'  => '\F526'
            ],
            [
                'class' => 'tooltip-plus',
                'code'  => '\FBB2'
            ],
            [
                'class' => 'tooltip-plus-outline',
                'code'  => '\F527'
            ],
            [
                'class' => 'tooltip-text',
                'code'  => '\F528'
            ],
            [
                'class' => 'tooltip-text-outline',
                'code'  => '\FBB3'
            ],
            [
                'class' => 'tooth',
                'code'  => '\F8C2'
            ],
            [
                'class' => 'tooth-outline',
                'code'  => '\F529'
            ],
            [
                'class' => 'tor',
                'code'  => '\F52A'
            ],
            [
                'class' => 'tortoise',
                'code'  => '\FD17'
            ],
            [
                'class' => 'tournament',
                'code'  => '\F9AD'
            ],
            [
                'class' => 'tower-beach',
                'code'  => '\F680'
            ],
            [
                'class' => 'tower-fire',
                'code'  => '\F681'
            ],
            [
                'class' => 'towing',
                'code'  => '\F83B'
            ],
            [
                'class' => 'track-light',
                'code'  => '\F913'
            ],
            [
                'class' => 'trackpad',
                'code'  => '\F7F7'
            ],
            [
                'class' => 'trackpad-lock',
                'code'  => '\F932'
            ],
            [
                'class' => 'tractor',
                'code'  => '\F891'
            ],
            [
                'class' => 'trademark',
                'code'  => '\FA77'
            ],
            [
                'class' => 'traffic-light',
                'code'  => '\F52B'
            ],
            [
                'class' => 'train',
                'code'  => '\F52C'
            ],
            [
                'class' => 'train-car',
                'code'  => '\FBB4'
            ],
            [
                'class' => 'train-variant',
                'code'  => '\F8C3'
            ],
            [
                'class' => 'tram',
                'code'  => '\F52D'
            ],
            [
                'class' => 'transcribe',
                'code'  => '\F52E'
            ],
            [
                'class' => 'transcribe-close',
                'code'  => '\F52F'
            ],
            [
                'class' => 'transfer',
                'code'  => '\F530'
            ],
            [
                'class' => 'transit-connection',
                'code'  => '\FD18'
            ],
            [
                'class' => 'transit-connection-variant',
                'code'  => '\FD19'
            ],
            [
                'class' => 'transit-transfer',
                'code'  => '\F6AD'
            ],
            [
                'class' => 'transition',
                'code'  => '\F914'
            ],
            [
                'class' => 'transition-masked',
                'code'  => '\F915'
            ],
            [
                'class' => 'translate',
                'code'  => '\F5CA'
            ],
            [
                'class' => 'transmission-tower',
                'code'  => '\FD1A'
            ],
            [
                'class' => 'trash-can',
                'code'  => '\FA78'
            ],
            [
                'class' => 'trash-can-outline',
                'code'  => '\FA79'
            ],
            [
                'class' => 'treasure-chest',
                'code'  => '\F725'
            ],
            [
                'class' => 'tree',
                'code'  => '\F531'
            ],
            [
                'class' => 'trello',
                'code'  => '\F532'
            ],
            [
                'class' => 'trending-down',
                'code'  => '\F533'
            ],
            [
                'class' => 'trending-neutral',
                'code'  => '\F534'
            ],
            [
                'class' => 'trending-up',
                'code'  => '\F535'
            ],
            [
                'class' => 'triangle',
                'code'  => '\F536'
            ],
            [
                'class' => 'triangle-outline',
                'code'  => '\F537'
            ],
            [
                'class' => 'triforce',
                'code'  => '\FBB5'
            ],
            [
                'class' => 'trophy',
                'code'  => '\F538'
            ],
            [
                'class' => 'trophy-award',
                'code'  => '\F539'
            ],
            [
                'class' => 'trophy-outline',
                'code'  => '\F53A'
            ],
            [
                'class' => 'trophy-variant',
                'code'  => '\F53B'
            ],
            [
                'class' => 'trophy-variant-outline',
                'code'  => '\F53C'
            ],
            [
                'class' => 'truck',
                'code'  => '\F53D'
            ],
            [
                'class' => 'truck-check',
                'code'  => '\FCB0'
            ],
            [
                'class' => 'truck-delivery',
                'code'  => '\F53E'
            ],
            [
                'class' => 'truck-fast',
                'code'  => '\F787'
            ],
            [
                'class' => 'truck-trailer',
                'code'  => '\F726'
            ],
            [
                'class' => 'tshirt-crew',
                'code'  => '\FA7A'
            ],
            [
                'class' => 'tshirt-crew-outline',
                'code'  => '\F53F'
            ],
            [
                'class' => 'tshirt-v',
                'code'  => '\FA7B'
            ],
            [
                'class' => 'tshirt-v-outline',
                'code'  => '\F540'
            ],
            [
                'class' => 'tumble-dryer',
                'code'  => '\F916'
            ],
            [
                'class' => 'tumblr',
                'code'  => '\F541'
            ],
            [
                'class' => 'tumblr-box',
                'code'  => '\F917'
            ],
            [
                'class' => 'tumblr-reblog',
                'code'  => '\F542'
            ],
            [
                'class' => 'tune',
                'code'  => '\F62E'
            ],
            [
                'class' => 'tune-vertical',
                'code'  => '\F66A'
            ],
            [
                'class' => 'turnstile',
                'code'  => '\FCB1'
            ],
            [
                'class' => 'turnstile-outline',
                'code'  => '\FCB2'
            ],
            [
                'class' => 'turtle',
                'code'  => '\FCB3'
            ],
            [
                'class' => 'twitch',
                'code'  => '\F543'
            ],
            [
                'class' => 'twitter',
                'code'  => '\F544'
            ],
            [
                'class' => 'twitter-box',
                'code'  => '\F545'
            ],
            [
                'class' => 'twitter-circle',
                'code'  => '\F546'
            ],
            [
                'class' => 'twitter-retweet',
                'code'  => '\F547'
            ],
            [
                'class' => 'two-factor-authentication',
                'code'  => '\F9AE'
            ],
            [
                'class' => 'uber',
                'code'  => '\F748'
            ],
            [
                'class' => 'ubisoft',
                'code'  => '\FBB6'
            ],
            [
                'class' => 'ubuntu',
                'code'  => '\F548'
            ],
            [
                'class' => 'ultra-high-definition',
                'code'  => '\F7F8'
            ],
            [
                'class' => 'umbraco',
                'code'  => '\F549'
            ],
            [
                'class' => 'umbrella',
                'code'  => '\F54A'
            ],
            [
                'class' => 'umbrella-closed',
                'code'  => '\F9AF'
            ],
            [
                'class' => 'umbrella-outline',
                'code'  => '\F54B'
            ],
            [
                'class' => 'undo',
                'code'  => '\F54C'
            ],
            [
                'class' => 'undo-variant',
                'code'  => '\F54D'
            ],
            [
                'class' => 'unfold-less-horizontal',
                'code'  => '\F54E'
            ],
            [
                'class' => 'unfold-less-vertical',
                'code'  => '\F75F'
            ],
            [
                'class' => 'unfold-more-horizontal',
                'code'  => '\F54F'
            ],
            [
                'class' => 'unfold-more-vertical',
                'code'  => '\F760'
            ],
            [
                'class' => 'ungroup',
                'code'  => '\F550'
            ],
            [
                'class' => 'unity',
                'code'  => '\F6AE'
            ],
            [
                'class' => 'unreal',
                'code'  => '\F9B0'
            ],
            [
                'class' => 'untappd',
                'code'  => '\F551'
            ],
            [
                'class' => 'update',
                'code'  => '\F6AF'
            ],
            [
                'class' => 'upload',
                'code'  => '\F552'
            ],
            [
                'class' => 'upload-multiple',
                'code'  => '\F83C'
            ],
            [
                'class' => 'upload-network',
                'code'  => '\F6F5'
            ],
            [
                'class' => 'upload-network-outline',
                'code'  => '\FCB4'
            ],
            [
                'class' => 'usb',
                'code'  => '\F553'
            ],
            [
                'class' => 'van-passenger',
                'code'  => '\F7F9'
            ],
            [
                'class' => 'van-utility',
                'code'  => '\F7FA'
            ],
            [
                'class' => 'vanish',
                'code'  => '\F7FB'
            ],
            [
                'class' => 'variable',
                'code'  => '\FAE6'
            ],
            [
                'class' => 'vector-arrange-above',
                'code'  => '\F554'
            ],
            [
                'class' => 'vector-arrange-below',
                'code'  => '\F555'
            ],
            [
                'class' => 'vector-bezier',
                'code'  => '\FAE7'
            ],
            [
                'class' => 'vector-circle',
                'code'  => '\F556'
            ],
            [
                'class' => 'vector-circle-variant',
                'code'  => '\F557'
            ],
            [
                'class' => 'vector-combine',
                'code'  => '\F558'
            ],
            [
                'class' => 'vector-curve',
                'code'  => '\F559'
            ],
            [
                'class' => 'vector-difference',
                'code'  => '\F55A'
            ],
            [
                'class' => 'vector-difference-ab',
                'code'  => '\F55B'
            ],
            [
                'class' => 'vector-difference-ba',
                'code'  => '\F55C'
            ],
            [
                'class' => 'vector-ellipse',
                'code'  => '\F892'
            ],
            [
                'class' => 'vector-intersection',
                'code'  => '\F55D'
            ],
            [
                'class' => 'vector-line',
                'code'  => '\F55E'
            ],
            [
                'class' => 'vector-point',
                'code'  => '\F55F'
            ],
            [
                'class' => 'vector-polygon',
                'code'  => '\F560'
            ],
            [
                'class' => 'vector-polyline',
                'code'  => '\F561'
            ],
            [
                'class' => 'vector-radius',
                'code'  => '\F749'
            ],
            [
                'class' => 'vector-rectangle',
                'code'  => '\F5C6'
            ],
            [
                'class' => 'vector-selection',
                'code'  => '\F562'
            ],
            [
                'class' => 'vector-square',
                'code'  => '\F001'
            ],
            [
                'class' => 'vector-triangle',
                'code'  => '\F563'
            ],
            [
                'class' => 'vector-union',
                'code'  => '\F564'
            ],
            [
                'class' => 'venmo',
                'code'  => '\F578'
            ],
            [
                'class' => 'vhs',
                'code'  => '\FA1A'
            ],
            [
                'class' => 'vibrate',
                'code'  => '\F566'
            ],
            [
                'class' => 'vibrate-off',
                'code'  => '\FCB5'
            ],
            [
                'class' => 'video',
                'code'  => '\F567'
            ],
            [
                'class' => 'video-3d',
                'code'  => '\F7FC'
            ],
            [
                'class' => 'video-4k-box',
                'code'  => '\F83D'
            ],
            [
                'class' => 'video-account',
                'code'  => '\F918'
            ],
            [
                'class' => 'video-image',
                'code'  => '\F919'
            ],
            [
                'class' => 'video-input-antenna',
                'code'  => '\F83E'
            ],
            [
                'class' => 'video-input-component',
                'code'  => '\F83F'
            ],
            [
                'class' => 'video-input-hdmi',
                'code'  => '\F840'
            ],
            [
                'class' => 'video-input-svideo',
                'code'  => '\F841'
            ],
            [
                'class' => 'video-minus',
                'code'  => '\F9B1'
            ],
            [
                'class' => 'video-off',
                'code'  => '\F568'
            ],
            [
                'class' => 'video-off-outline',
                'code'  => '\FBB7'
            ],
            [
                'class' => 'video-outline',
                'code'  => '\FBB8'
            ],
            [
                'class' => 'video-plus',
                'code'  => '\F9B2'
            ],
            [
                'class' => 'video-stabilization',
                'code'  => '\F91A'
            ],
            [
                'class' => 'video-switch',
                'code'  => '\F569'
            ],
            [
                'class' => 'video-vintage',
                'code'  => '\FA1B'
            ],
            [
                'class' => 'view-agenda',
                'code'  => '\F56A'
            ],
            [
                'class' => 'view-array',
                'code'  => '\F56B'
            ],
            [
                'class' => 'view-carousel',
                'code'  => '\F56C'
            ],
            [
                'class' => 'view-column',
                'code'  => '\F56D'
            ],
            [
                'class' => 'view-dashboard',
                'code'  => '\F56E'
            ],
            [
                'class' => 'view-dashboard-outline',
                'code'  => '\FA1C'
            ],
            [
                'class' => 'view-dashboard-variant',
                'code'  => '\F842'
            ],
            [
                'class' => 'view-day',
                'code'  => '\F56F'
            ],
            [
                'class' => 'view-grid',
                'code'  => '\F570'
            ],
            [
                'class' => 'view-headline',
                'code'  => '\F571'
            ],
            [
                'class' => 'view-list',
                'code'  => '\F572'
            ],
            [
                'class' => 'view-module',
                'code'  => '\F573'
            ],
            [
                'class' => 'view-parallel',
                'code'  => '\F727'
            ],
            [
                'class' => 'view-quilt',
                'code'  => '\F574'
            ],
            [
                'class' => 'view-sequential',
                'code'  => '\F728'
            ],
            [
                'class' => 'view-split-horizontal',
                'code'  => '\FBA7'
            ],
            [
                'class' => 'view-split-vertical',
                'code'  => '\FBA8'
            ],
            [
                'class' => 'view-stream',
                'code'  => '\F575'
            ],
            [
                'class' => 'view-week',
                'code'  => '\F576'
            ],
            [
                'class' => 'vimeo',
                'code'  => '\F577'
            ],
            [
                'class' => 'violin',
                'code'  => '\F60F'
            ],
            [
                'class' => 'virtual-reality',
                'code'  => '\F893'
            ],
            [
                'class' => 'visual-studio',
                'code'  => '\F610'
            ],
            [
                'class' => 'visual-studio-code',
                'code'  => '\FA1D'
            ],
            [
                'class' => 'vk',
                'code'  => '\F579'
            ],
            [
                'class' => 'vk-box',
                'code'  => '\F57A'
            ],
            [
                'class' => 'vk-circle',
                'code'  => '\F57B'
            ],
            [
                'class' => 'vlc',
                'code'  => '\F57C'
            ],
            [
                'class' => 'voice',
                'code'  => '\F5CB'
            ],
            [
                'class' => 'voicemail',
                'code'  => '\F57D'
            ],
            [
                'class' => 'volleyball',
                'code'  => '\F9B3'
            ],
            [
                'class' => 'volume-high',
                'code'  => '\F57E'
            ],
            [
                'class' => 'volume-low',
                'code'  => '\F57F'
            ],
            [
                'class' => 'volume-medium',
                'code'  => '\F580'
            ],
            [
                'class' => 'volume-minus',
                'code'  => '\F75D'
            ],
            [
                'class' => 'volume-mute',
                'code'  => '\F75E'
            ],
            [
                'class' => 'volume-off',
                'code'  => '\F581'
            ],
            [
                'class' => 'volume-plus',
                'code'  => '\F75C'
            ],
            [
                'class' => 'vote',
                'code'  => '\FA1E'
            ],
            [
                'class' => 'vote-outline',
                'code'  => '\FA1F'
            ],
            [
                'class' => 'vpn',
                'code'  => '\F582'
            ],
            [
                'class' => 'vuejs',
                'code'  => '\F843'
            ],
            [
                'class' => 'walk',
                'code'  => '\F583'
            ],
            [
                'class' => 'wall',
                'code'  => '\F7FD'
            ],
            [
                'class' => 'wall-sconce',
                'code'  => '\F91B'
            ],
            [
                'class' => 'wall-sconce-flat',
                'code'  => '\F91C'
            ],
            [
                'class' => 'wall-sconce-variant',
                'code'  => '\F91D'
            ],
            [
                'class' => 'wallet',
                'code'  => '\F584'
            ],
            [
                'class' => 'wallet-giftcard',
                'code'  => '\F585'
            ],
            [
                'class' => 'wallet-membership',
                'code'  => '\F586'
            ],
            [
                'class' => 'wallet-outline',
                'code'  => '\FBB9'
            ],
            [
                'class' => 'wallet-travel',
                'code'  => '\F587'
            ],
            [
                'class' => 'wan',
                'code'  => '\F588'
            ],
            [
                'class' => 'washing-machine',
                'code'  => '\F729'
            ],
            [
                'class' => 'watch',
                'code'  => '\F589'
            ],
            [
                'class' => 'watch-export',
                'code'  => '\F58A'
            ],
            [
                'class' => 'watch-export-variant',
                'code'  => '\F894'
            ],
            [
                'class' => 'watch-import',
                'code'  => '\F58B'
            ],
            [
                'class' => 'watch-import-variant',
                'code'  => '\F895'
            ],
            [
                'class' => 'watch-variant',
                'code'  => '\F896'
            ],
            [
                'class' => 'watch-vibrate',
                'code'  => '\F6B0'
            ],
            [
                'class' => 'watch-vibrate-off',
                'code'  => '\FCB6'
            ],
            [
                'class' => 'water',
                'code'  => '\F58C'
            ],
            [
                'class' => 'water-off',
                'code'  => '\F58D'
            ],
            [
                'class' => 'water-percent',
                'code'  => '\F58E'
            ],
            [
                'class' => 'water-pump',
                'code'  => '\F58F'
            ],
            [
                'class' => 'watermark',
                'code'  => '\F612'
            ],
            [
                'class' => 'waves',
                'code'  => '\F78C'
            ],
            [
                'class' => 'waze',
                'code'  => '\FBBA'
            ],
            [
                'class' => 'weather-cloudy',
                'code'  => '\F590'
            ],
            [
                'class' => 'weather-fog',
                'code'  => '\F591'
            ],
            [
                'class' => 'weather-hail',
                'code'  => '\F592'
            ],
            [
                'class' => 'weather-hurricane',
                'code'  => '\F897'
            ],
            [
                'class' => 'weather-lightning',
                'code'  => '\F593'
            ],
            [
                'class' => 'weather-lightning-rainy',
                'code'  => '\F67D'
            ],
            [
                'class' => 'weather-night',
                'code'  => '\F594'
            ],
            [
                'class' => 'weather-partlycloudy',
                'code'  => '\F595'
            ],
            [
                'class' => 'weather-pouring',
                'code'  => '\F596'
            ],
            [
                'class' => 'weather-rainy',
                'code'  => '\F597'
            ],
            [
                'class' => 'weather-snowy',
                'code'  => '\F598'
            ],
            [
                'class' => 'weather-snowy-rainy',
                'code'  => '\F67E'
            ],
            [
                'class' => 'weather-sunny',
                'code'  => '\F599'
            ],
            [
                'class' => 'weather-sunset',
                'code'  => '\F59A'
            ],
            [
                'class' => 'weather-sunset-down',
                'code'  => '\F59B'
            ],
            [
                'class' => 'weather-sunset-up',
                'code'  => '\F59C'
            ],
            [
                'class' => 'weather-windy',
                'code'  => '\F59D'
            ],
            [
                'class' => 'weather-windy-variant',
                'code'  => '\F59E'
            ],
            [
                'class' => 'web',
                'code'  => '\F59F'
            ],
            [
                'class' => 'webcam',
                'code'  => '\F5A0'
            ],
            [
                'class' => 'webhook',
                'code'  => '\F62F'
            ],
            [
                'class' => 'webpack',
                'code'  => '\F72A'
            ],
            [
                'class' => 'wechat',
                'code'  => '\F611'
            ],
            [
                'class' => 'weight',
                'code'  => '\F5A1'
            ],
            [
                'class' => 'weight-gram',
                'code'  => '\FD1B'
            ],
            [
                'class' => 'weight-kilogram',
                'code'  => '\F5A2'
            ],
            [
                'class' => 'weight-pound',
                'code'  => '\F9B4'
            ],
            [
                'class' => 'whatsapp',
                'code'  => '\F5A3'
            ],
            [
                'class' => 'wheelchair-accessibility',
                'code'  => '\F5A4'
            ],
            [
                'class' => 'whistle',
                'code'  => '\F9B5'
            ],
            [
                'class' => 'white-balance-auto',
                'code'  => '\F5A5'
            ],
            [
                'class' => 'white-balance-incandescent',
                'code'  => '\F5A6'
            ],
            [
                'class' => 'white-balance-iridescent',
                'code'  => '\F5A7'
            ],
            [
                'class' => 'white-balance-sunny',
                'code'  => '\F5A8'
            ],
            [
                'class' => 'widgets',
                'code'  => '\F72B'
            ],
            [
                'class' => 'wifi',
                'code'  => '\F5A9'
            ],
            [
                'class' => 'wifi-off',
                'code'  => '\F5AA'
            ],
            [
                'class' => 'wifi-strength-1',
                'code'  => '\F91E'
            ],
            [
                'class' => 'wifi-strength-1-alert',
                'code'  => '\F91F'
            ],
            [
                'class' => 'wifi-strength-1-lock',
                'code'  => '\F920'
            ],
            [
                'class' => 'wifi-strength-2',
                'code'  => '\F921'
            ],
            [
                'class' => 'wifi-strength-2-alert',
                'code'  => '\F922'
            ],
            [
                'class' => 'wifi-strength-2-lock',
                'code'  => '\F923'
            ],
            [
                'class' => 'wifi-strength-3',
                'code'  => '\F924'
            ],
            [
                'class' => 'wifi-strength-3-alert',
                'code'  => '\F925'
            ],
            [
                'class' => 'wifi-strength-3-lock',
                'code'  => '\F926'
            ],
            [
                'class' => 'wifi-strength-4',
                'code'  => '\F927'
            ],
            [
                'class' => 'wifi-strength-4-alert',
                'code'  => '\F928'
            ],
            [
                'class' => 'wifi-strength-4-lock',
                'code'  => '\F929'
            ],
            [
                'class' => 'wifi-strength-alert-outline',
                'code'  => '\F92A'
            ],
            [
                'class' => 'wifi-strength-lock-outline',
                'code'  => '\F92B'
            ],
            [
                'class' => 'wifi-strength-off',
                'code'  => '\F92C'
            ],
            [
                'class' => 'wifi-strength-off-outline',
                'code'  => '\F92D'
            ],
            [
                'class' => 'wifi-strength-outline',
                'code'  => '\F92E'
            ],
            [
                'class' => 'wii',
                'code'  => '\F5AB'
            ],
            [
                'class' => 'wiiu',
                'code'  => '\F72C'
            ],
            [
                'class' => 'wikipedia',
                'code'  => '\F5AC'
            ],
            [
                'class' => 'window-close',
                'code'  => '\F5AD'
            ],
            [
                'class' => 'window-closed',
                'code'  => '\F5AE'
            ],
            [
                'class' => 'window-maximize',
                'code'  => '\F5AF'
            ],
            [
                'class' => 'window-minimize',
                'code'  => '\F5B0'
            ],
            [
                'class' => 'window-open',
                'code'  => '\F5B1'
            ],
            [
                'class' => 'window-restore',
                'code'  => '\F5B2'
            ],
            [
                'class' => 'windows',
                'code'  => '\F5B3'
            ],
            [
                'class' => 'windows-classic',
                'code'  => '\FA20'
            ],
            [
                'class' => 'wiper',
                'code'  => '\FAE8'
            ],
            [
                'class' => 'wordpress',
                'code'  => '\F5B4'
            ],
            [
                'class' => 'worker',
                'code'  => '\F5B5'
            ],
            [
                'class' => 'wrap',
                'code'  => '\F5B6'
            ],
            [
                'class' => 'wrap-disabled',
                'code'  => '\FBBB'
            ],
            [
                'class' => 'wrench',
                'code'  => '\F5B7'
            ],
            [
                'class' => 'wrench-outline',
                'code'  => '\FBBC'
            ],
            [
                'class' => 'wunderlist',
                'code'  => '\F5B8'
            ],
            [
                'class' => 'xamarin',
                'code'  => '\F844'
            ],
            [
                'class' => 'xamarin-outline',
                'code'  => '\F845'
            ],
            [
                'class' => 'xaml',
                'code'  => '\F673'
            ],
            [
                'class' => 'xbox',
                'code'  => '\F5B9'
            ],
            [
                'class' => 'xbox-controller',
                'code'  => '\F5BA'
            ],
            [
                'class' => 'xbox-controller-battery-alert',
                'code'  => '\F74A'
            ],
            [
                'class' => 'xbox-controller-battery-charging',
                'code'  => '\FA21'
            ],
            [
                'class' => 'xbox-controller-battery-empty',
                'code'  => '\F74B'
            ],
            [
                'class' => 'xbox-controller-battery-full',
                'code'  => '\F74C'
            ],
            [
                'class' => 'xbox-controller-battery-low',
                'code'  => '\F74D'
            ],
            [
                'class' => 'xbox-controller-battery-medium',
                'code'  => '\F74E'
            ],
            [
                'class' => 'xbox-controller-battery-unknown',
                'code'  => '\F74F'
            ],
            [
                'class' => 'xbox-controller-off',
                'code'  => '\F5BB'
            ],
            [
                'class' => 'xda',
                'code'  => '\F5BC'
            ],
            [
                'class' => 'xing',
                'code'  => '\F5BD'
            ],
            [
                'class' => 'xing-box',
                'code'  => '\F5BE'
            ],
            [
                'class' => 'xing-circle',
                'code'  => '\F5BF'
            ],
            [
                'class' => 'xml',
                'code'  => '\F5C0'
            ],
            [
                'class' => 'xmpp',
                'code'  => '\F7FE'
            ],
            [
                'class' => 'yahoo',
                'code'  => '\FB2A'
            ],
            [
                'class' => 'yammer',
                'code'  => '\F788'
            ],
            [
                'class' => 'yeast',
                'code'  => '\F5C1'
            ],
            [
                'class' => 'yelp',
                'code'  => '\F5C2'
            ],
            [
                'class' => 'yin-yang',
                'code'  => '\F67F'
            ],
            [
                'class' => 'youtube',
                'code'  => '\F5C3'
            ],
            [
                'class' => 'youtube-creator-studio',
                'code'  => '\F846'
            ],
            [
                'class' => 'youtube-gaming',
                'code'  => '\F847'
            ],
            [
                'class' => 'youtube-subscription',
                'code'  => '\FD1C'
            ],
            [
                'class' => 'youtube-tv',
                'code'  => '\F448'
            ],
            [
                'class' => 'z-wave',
                'code'  => '\FAE9'
            ],
            [
                'class' => 'zend',
                'code'  => '\FAEA'
            ],
            [
                'class' => 'zigbee',
                'code'  => '\FD1D'
            ],
            [
                'class' => 'zip-box',
                'code'  => '\F5C4'
            ],
            [
                'class' => 'zip-disk',
                'code'  => '\FA22'
            ],
            [
                'class' => 'zodiac-aquarius',
                'code'  => '\FA7C'
            ],
            [
                'class' => 'zodiac-aries',
                'code'  => '\FA7D'
            ],
            [
                'class' => 'zodiac-cancer',
                'code'  => '\FA7E'
            ],
            [
                'class' => 'zodiac-capricorn',
                'code'  => '\FA7F'
            ],
            [
                'class' => 'zodiac-gemini',
                'code'  => '\FA80'
            ],
            [
                'class' => 'zodiac-leo',
                'code'  => '\FA81'
            ],
            [
                'class' => 'zodiac-libra',
                'code'  => '\FA82'
            ],
            [
                'class' => 'zodiac-pisces',
                'code'  => '\FA83'
            ],
            [
                'class' => 'zodiac-sagittarius',
                'code'  => '\FA84'
            ],
            [
                'class' => 'zodiac-scorpio',
                'code'  => '\FA85'
            ],
            [
                'class' => 'zodiac-taurus',
                'code'  => '\FA86'
            ],
            [
                'class' => 'zodiac-virgo',
                'code'  => '\FA87'
            ]
        ];
    }
}
