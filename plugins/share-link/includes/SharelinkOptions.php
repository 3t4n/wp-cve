<?php

class SharelinkOptions {

    public static function getLicenseIsActivated() {
        return get_option('sharelink-license-activated');
    }

    public static function getLicense() {
        return get_option('sharelink-license');
    }
}