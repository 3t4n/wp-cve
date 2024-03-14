<?php

if(!isset($cgLoginFormButtonGallery)){
    $cgLoginFormButtonGallery = '';
}

if(!isset($cgRegistryFormButtonGallery)){
    $cgRegistryFormButtonGallery = '';
}

echo "<div class='cgLoginFormButton $cgLoginFormButtonGallery $cgFeControlsStyle $BorderRadiusClass' data-cg-gid='$galeryIDuserForJs'><span>Sign in</span></div>";
echo "<div class='cgRegistryFormButton $cgRegistryFormButtonGallery $cgFeControlsStyle $BorderRadiusClass' data-cg-gid='$galeryIDuserForJs'><span>Create account</span></div>";

?>