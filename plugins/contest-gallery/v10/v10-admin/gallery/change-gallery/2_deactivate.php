<?php

if (!empty($_POST['cg_deactivate'])) {

    cg_deactivate_images($GalleryID,$wp_upload_dir,$_POST['cg_deactivate']);

    }