<?php

function getUrlApi()
{
    return get_option('mpq_enviroment') == 0 ?
    'https://api-v2.mpr.mipaquete.com/' :
    'https://api-v2.dev.mpr.mipaquete.com/';
}
