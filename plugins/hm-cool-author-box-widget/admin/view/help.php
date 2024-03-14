<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="wph-wrap-all" class="wrap hmcab-general-settings-page">

    <div class="settings-banner">
        <h2><i class="fa-solid fa-headset"></i>&nbsp;<?php _e('How it works', 'hm-cool-author-box-widget'); ?></h2>
    </div>

    <div class="hmcab-wrap">

        <div class="hmcab_personal_wrap hmcab_personal_help" style="width: 75%; float: left;">
            
            <div class="tab-content">
                
                <table class="hmcab-general-content-settings-table">
                    <tbody>
                        <tr height="50">
                            <th scope="row" style="text-align: left;">
                                <label>How Can I Display Author Box Widget?</label>
                            </th>
                        </tr>
                        <tr>
                            <td>
                                After activating the plugin, you will see "Cool Author Box” in Admin Dashboard Menu.
                                <br>
                                Go to “Settings” and apply your "Personal" and "Social" info first.
                                <br>
                                After that go to "Appearance" -> "Widget" and find the widget "HM Cool Author Box".
                                <br>
                                Now Drag and drop it to your preferred area.
                            </td>
                        </tr>
                        <tr height="50">
                            <th scope="row" style="text-align: left;">
                                <label>How Can I Display Author Box in a Post?</label>
                            </th>
                        </tr>
                        <tr>
                            <td>
                                Go to “User” -> "Profile" and set your "Display name publicly as" first.
                                <br>
                                Now fill up your "Website", "Biographical Info", "Profile Picture" etc.
                                <br>
                                After that scroll down and find "Cool Author Box" section.
                                <br>
                                Here you can setup your "Title" and "Social" info. 
                            </td>
                        </tr>
                        <tr height="50">
                            <th scope="row" style="text-align: left;">
                                <label>Video Tutorial</label>
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <iframe width="560" height="315" src="https://www.youtube.com/embed/W9_6--oeNzw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </td>
                        </tr>
                    </tbody>
                </table>
            
            </div>
        
        </div>
        
        <?php $this->load_admin_sidebar(); ?>

    </div>

</div>