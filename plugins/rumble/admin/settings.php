<?php
// Recomended by WP for security reasons
defined('ABSPATH') or die('Denied');
?>
<div class="rumble-settings">
    <div class="rumble-settings-header">
        <div class="rumble-settings-image">
            <img src="<?php echo plugins_url('images' . DIRECTORY_SEPARATOR . 'icon.png', __FILE__) ?>" />
        </div>
        <div class="rumble-settings-title">
            Rumble settings
        </div>
        <div style="clear: both"></div>
    </div>
    <hr/>
    <div>
        <h3>How to search and embed</h3>
        <p>
            1. Above the editor you'll find the <a class="button"><span class="rumble-button-img"></span>Rumble</a> button you can click to show the search form.
        </p>
        <p>
            2. Here you can enter a search term or video link and start searching by clicking on <button class="button">Search</button> button or pressing the <code>Enter</code> key.
        </p>
        <p>
            3. When search is completed and results are displayed, for each results you'll have the options to play or embed the video.
        </p>
        <p>
            4. If you want to play the video, click on <button class="button">Preview video</button> button, then play the video.
        </p>
        <p>
            5. If you want to embed the video, click on <button class="button">Insert video</button> button.
        </p>
    </div>
    <hr/>
    <?php if (empty($publisherId) === true) : ?>
        <p>Earn revenue when you embed videos with Rumble... <a href="mailto:bd@rumble.com">Apply Now</a></p>
    <?php endif ?>
    <form method="post">
        <input type="hidden" name="<?php echo Rumble::OPTIONS_RUMBLE_SETTINGS ?>" value="R">
        <h3>Options</h3>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for='<?php echo Rumble::OPTIONS_PUBLISHER_ID ?>'>Publisher ID</label>
                    </th>
                    <td>
                        <input type='text' name='<?php echo Rumble::OPTIONS_PUBLISHER_ID ?>' id='<?php echo Rumble::OPTIONS_PUBLISHER_ID ?>' value='<?php echo trim($optionValues[Rumble::OPTIONS_PUBLISHER_ID]); ?>' class='textinput'>
                        <p class="description">Required for the plugin to search on Rumble's servers. If not set, default publisher ID will be used.</p>
                        <?php if ($keySet === true && $keyValid === true) : ?>
                            <p class="description" style="color: #9ACD32">Publisher ID is valid.</p>
                        <?php elseif ($keySet === true && $keyValid === false) : ?>
                            <p class="description" style="color: #FF6347">Publisher ID is not valid.</p>
                        <?php endif ?>
                    </td>
		  </tr><tr>
		    <th scope="row">
		    	<label for='<?php echo Rumble::OPTIONS_PLAYER_TYPE ?>'>Player Type</label>
		    </th>
		    <td>
			<select name="<?php echo Rumble::OPTIONS_PLAYER_TYPE ?>">
				<option value="iframe">IFrame</option>
				<option value="js"<?php if($optionValues[Rumble::OPTIONS_PLAYER_TYPE]=='js') echo ' SELECTED'; ?>>JavaScript</option>
			</option>
		    </td>
                </tr>
            </tbody>
        </table>
        <hr/>
        <p>
            <input type="submit" class="button button-primary button-large" value="Save changes">
        </p>
    </form>
</div>
