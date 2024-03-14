<?php
namespace YTP\Base;

use YTP\Model\Presets as PresetModel;

class Presets {

    private $preset_key = 'ytp_preset_inserted';

    function register(){
        $presetInserted = get_option($this->preset_key);
        if(!$presetInserted){
            $presets = $this->getPresets();
            $presetModelObject = new PresetModel();
            foreach($presets as $preset){
                $preset['preset'] = maybe_unserialize( $preset['preset']);
                $presetModelObject->createOrUpdate($preset);
            }
            update_option($this->preset_key, true);
        }
    }

    // default presets
    function getPresets(){
        return [
            [
                'name' => 'Simple',
                'preset' => 'a:11:{s:7:"options";a:8:{s:8:"controls";a:13:{i:0;s:10:"play-large";i:1;s:6:"rewind";i:2;s:4:"play";i:3;s:12:"fast-forward";i:4;s:8:"progress";i:5;s:12:"current-time";i:6;s:8:"duration";i:7;s:4:"mute";i:8;s:6:"volume";i:9;s:3:"pip";i:10;s:7:"airplay";i:11;s:8:"settings";i:12;s:10:"fullscreen";}s:6:"repeat";s:5:"false";s:8:"autoplay";s:5:"false";s:5:"muted";s:5:"false";s:10:"resetOnEnd";s:5:"false";s:15:"autoHideControl";s:4:"true";s:8:"seekTime";s:2:"10";s:5:"speed";a:2:{s:8:"selected";s:1:"1";s:7:"options";a:8:{i:0;s:3:"0.5";i:1;s:4:"0.75";i:2;s:1:"1";i:3;s:4:"1.25";i:4;s:3:"1.5";i:5;s:4:"1.75";i:6;s:1:"2";i:7;s:1:"4";}}}s:10:"brandColor";s:7:"#00B3FF";s:6:"radius";s:3:"5px";s:12:"thumbInPause";s:5:"false";s:10:"thumbStyle";s:7:"default";s:9:"endScreen";a:3:{s:7:"enabled";s:5:"false";s:4:"text";s:0:"";s:4:"link";s:0:"";}s:13:"hideYoutubeUI";s:4:"true";s:9:"saveState";s:4:"true";s:9:"plyrStyle";a:3:{s:12:"borderRadius";s:3:"3px";s:23:"plyr__control--overlaid";a:3:{s:7:"padding";a:4:{s:3:"top";s:4:"15px";s:5:"right";s:4:"15px";s:6:"bottom";s:4:"15px";s:4:"left";s:4:"15px";}s:12:"borderRadius";s:3:"50%";s:10:"background";s:7:"#00b2ff";}s:27:"plyr__control--overlaid svg";a:2:{s:6:"height";s:4:"25px";s:5:"width";s:4:"25px";}}s:9:"watermark";a:7:{s:7:"enabled";s:5:"false";s:4:"text";s:25:"Enter your watermark text";s:5:"color";s:4:"#fff";s:15:"backgroundColor";s:7:"#303030";s:7:"opacity";s:2:"70";s:8:"position";s:9:"top-right";s:8:"selector";s:9:"watermark";}s:21:"hideControlsWhenPause";s:5:"false";}'
            ]
        ];
    }



}