<?php


echo "<div style='width:100%;display: flex;flex-flow: column;margin-bottom:15px;margin-top:15px;'>";
echo "<div  class='cg_do_not_remove_when_ajax_load' style='width:100%;margin-bottom: 15px;'>";
echo "<div style='width:180px;margin: 0 auto;'>";
echo "<a href='?page=".cg_get_version()."/index.php&users_management=true&option_id=$GalleryID' class='cg_load_backend_link'><input class='cg_backend_button cg_backend_button_general'  type='button' id='cgUsersManagement' value='Users management' /></a>";
echo "</div>";
echo "</div>";

echo "<div style='width:100%;'>";
echo "<div style=\"width:245px;margin: 0 auto;\"><a href=\"?page=".cg_get_version()."/index.php&amp;corrections_and_improvements=true&amp;option_id=$galeryNR\" class='cg_load_backend_link'><input class=\"cg_backend_button cg_backend_button_back\" type=\"button\" value=\"Status, repair and mail exceptions\" style=\"width:245px;padding-right: 0;\"></a>
</div></div></div>";


?>