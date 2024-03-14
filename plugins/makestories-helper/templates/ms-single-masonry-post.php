<?php $index = 0; ?>
<div class="design-1">
<a class="story-thumb-card card bigmiddleStory" data-story-url="<?php echo $permalink; ?>" href="<?php echo $permalink; ?>">
    <div class="cardin">
        <div class="cardimage">
            <?php if ($index%8<=1) { ?>
                <?php if ($posterLandscape) { ?>
                    <img src="<?php echo $posterLandscape ?>" alt="Avatar"  class="story-img" />
                <?php } else { ?>
                    <img src="https://www.onl.st/dev-stamps/default.jpeg" alt="Avatar-def-one" class="story-img" />
                <?php
                        }
                }
            ?>

            <?php if ($index%8 > 1 && $index%8 < 8) { ?>
                <?php if ($posterLandscape) { ?>
                    <img src="<?php echo $posterLandscape ?>" alt="Avatar"  class="story-img" />
                <?php } else { ?>
                    <img src="https://www.onl.st/dev-stamps/default.jpeg" alt="Avatar-def-one" class="story-img" />
                <?php
                    }
                }
            ?>
        </div>
        <div class="container">
            <p><?php echo $publishDate; ?></p>
            <h2><?php echo $title; ?></h2>
        </div>
    </div>
</a>
</div>