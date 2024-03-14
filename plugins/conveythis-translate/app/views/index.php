<?php
    require_once(CONVEY_PLUGIN_ROOT_PATH . 'app/views/layout/loader.php');
?>

<div
    id="content"
    style="display: none"
>
    <?php

        if (empty($this->variables->api_key))
        {
            require_once(CONVEY_PLUGIN_ROOT_PATH . 'app/views/auth/login.php');
            require_once(CONVEY_PLUGIN_ROOT_PATH . 'app/views/auth/languages.php');
            require_once(CONVEY_PLUGIN_ROOT_PATH . 'app/views/auth/signup-modal.php');
        }
        elseif (
                empty($this->variables->api_key)
                &&
                (
                    empty($this->variables->source_language) || empty($this->variables->target_languages)
                )
        )
        {
            require_once(CONVEY_PLUGIN_ROOT_PATH . 'app/views/auth/languages.php');
        }
        elseif ($this->variables->new_user)
        {
            require_once(CONVEY_PLUGIN_ROOT_PATH . 'app/views/auth/congratulations.php');
        }
        else
        {
            require_once(CONVEY_PLUGIN_ROOT_PATH . 'app/views/main.php');
        }

    ?>
</div>

<?php
    require_once(CONVEY_PLUGIN_ROOT_PATH . 'app/views/styles.php');
?>