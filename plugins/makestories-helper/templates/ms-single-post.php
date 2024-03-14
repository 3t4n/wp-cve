<?php $index = 0; ?>
<a class="single-story-col" id="single-story" data-story-url="<?php echo $permalink; ?>" href="<?php echo $permalink; ?>">
    <div class="single-story">
        <div class="background-cards">
            <div class="background-card-1"></div>
            <div class="background-card-2"></div>

        </div>
        <span class="play-btn-icon"> 
            <svg width="15%" height="15%" preserveAspectRatio="none" viewBox="0 0 30 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M30 23L0 45.5167L0 0.483339L30 23Z" fill="#FAF9F9"></path>
            </svg> 
        </span>

        <div class="image-outerWrapper">
            <?php if ($posterLandscape) { ?>
                <img src="<?php echo str_replace("storage.googleapis.com/makestories-202705.appspot.com","cdn.storyasset.link", $posterLandscape) ?>" alt="Avatar" class="single-story-image" /> 
            <?php } else { ?>
                <img src="<?php echo MS_PLUGIN_BASE_FILE_PATH."assets/images/default-poster.jpeg" ?>" alt="Avatar" class="single-story-image"/> 
            <?php } ?>
                <div class="story-block-title-div">
                    <?php if (isset($title)): ?> <span class="story-block-title"><?php echo esc_html($title); ?></span> <?php endif; ?>
                </div>
        </div>
    </div>
</a>