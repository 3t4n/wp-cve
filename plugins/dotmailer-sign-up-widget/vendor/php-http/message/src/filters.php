<?php

namespace Dotdigital_WordPress_Vendor;

// Register chunk filter if not found
if (!\array_key_exists('chunk', \stream_get_filters())) {
    \stream_filter_register('chunk', 'Dotdigital_WordPress_Vendor\\Http\\Message\\Encoding\\Filter\\Chunk');
}
