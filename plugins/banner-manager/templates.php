<?php

class templates
{
    /**
     * Template systems width fabpot symfony
     * @param unknown_type $p_strName
     */
    static public function load( $p_strName, $p_arrVars = array() )
    {
        // autoload
        require_once BM_PLUGIN_DIR .'/lib/fabpot-templating/lib/sfTemplateAutoloader.php';
        sfTemplateAutoloader::register();

        // configuration
        $loader = new sfTemplateLoaderFilesystem( BM_PLUGIN_DIR .'/templates/%name%.php');
        $engine = new sfTemplateEngine($loader);

        // render
        echo $engine->render($p_strName, $p_arrVars);
    }
}
