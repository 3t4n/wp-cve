<?php

use luckywp\glossary\core\Core;
use luckywp\glossary\plugin\Term;

/**
 * Страница архива
 * @return bool
 */
function lwpgls_is_archive()
{
    return Core::$plugin->archivePageId && is_page(Core::$plugin->archivePageId);
}

/**
 * Страница термина
 * @return bool
 */
function lwpgls_is_single()
{
    return is_singular(Term::POST_TYPE);
}

/**
 * Страница плагина
 * @return bool
 */
function lwpgls_is()
{
    return lwpgls_is_archive() || lwpgls_is_single();
}
