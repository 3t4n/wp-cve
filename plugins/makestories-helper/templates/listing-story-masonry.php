<?php

    $getClassNames = [
        "largestory",
        "replaceStory",
        "smallStory",
        "smallStory",
        "smallStory",
        "mobilesmallStory",
        "bigmiddleStory",
        "mobilesmallStory"
    ];
    if(!isset($index)){
        $index = 0;
    }
    ?>
        <a class="story-thumb-card card <?php echo $getClassNames[$index]; ?>" data-story-url="<?php echo $permalink; ?>" href="<?php echo $permalink; ?>">
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
        <?php

?>