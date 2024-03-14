<?php
// Recomended by WP for security reasons
defined('ABSPATH') or die('Denied');

add_thickbox();
?>
<div id="rumble-popup" style="display:none;">
    <div class="rumble-scroll-to-top">
        <span class="dashicons dashicons-arrow-up-alt2"></span>
    </div>
    <div class="rumble-search-options">
        <div>
            <label for='rumble-search'>Search on rumble:</label>
            <input class='text' type="text" id='rumble-search'>
            <button class="button" id='rumble-submit'>Search</button>
            <button class="button" id='rumble-clear'>Clear</button>
        </div>
    </div>
    <div class="rumble-tabs">
        <a id="tab-rumble-results">Search Results</a>
        <a id="tab-rumble-editor-picks">Editor Picks</a>
        <a id="tab-rumble-newest-videos">Newest Videos</a>
        <a id="tab-rumble-your-videos">Your Videos</a>
        <a id="tab-rumble-how-to" class="rumble-pull-right">How to</a>
    </div>
    <div style="clear: both"></div>
    <div class="rumble-tabs-content">
        <div id="rumble-results"></div>
        <div id="rumble-editor-picks"></div>
        <div id="rumble-newest-videos"></div>
        <div id="rumble-your-videos"></div>
        <div id="rumble-how-to" class="rumble-how-to">
            <h3>How to search and embed</h3>
            <p>
                1. On top of this page you can enter a search term or video link and start searching by clicking on <button class="button">Search</button> button or pressing the <code>Enter</code> key.
            </p>
            <p>
                2. When search is completed and results are displayed, for each results you'll have the options to play or embed the video.
            </p>
            <p>
                3. If you want to play the video, click on <button class="button">Preview video</button> button, then play the video.
            </p>
            <p>
                4. If you want to embed the video, click on <button class="button">Insert video</button> button.
            </p>
        </div>
    </div>
    <?php if (empty($publisherId) === true) : ?>
        <div class="rumble-footer">
            <p>
                Earn revenue when you embed videos with Rumble... <a href="mailto:bd@rumble.com">Apply Now</a>
            </p>
        </div>
    <?php endif ?>
</div>
<a href="#TB_inline?mode=extended&width=753&height=550&inlineId=rumble-popup" title='Embed rumble video' class="button thickbox">
    <span class='rumble-button-img'></span>
    Rumble
</a>
