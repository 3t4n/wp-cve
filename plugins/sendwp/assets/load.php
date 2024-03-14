<?php

include 'class.assets.php';

\SendWP\Assets::set_base_url(trailingslashit(plugin_dir_url( __FILE__)));
\SendWP\Assets::set_base_path(trailingslashit(plugin_dir_path( __FILE__)));
