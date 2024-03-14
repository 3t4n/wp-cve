<div class="better-team style-6">
    <div class="item cir md-mb50">
        <div class="img">
            <img src="<?php echo esc_url($settings['better_team_image']['url']); ?>" alt="">
            <div id="circle">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="300px"
                    height="300px" viewBox="0 0 300 300" enable-background="new 0 0 300 300"
                    xml:space="preserve">
                    <defs>
                        <path id="circlePath"
                            d="M 150, 150 m -60, 0 a 60,60 0 1,1 120,0 a 60,60 0 1,1 -120,0" />
                    </defs>
                    <circle cx="150" cy="100" r="75" fill="none" />
                    <g>
                        <use xlink:href="#circlePath" fill="none" />
                        <text fill="#c5a47e">
                            <textPath xlink:href="#circlePath">
                                <?php 
                                // Repeating designation text enough to fill the circle
                                echo str_repeat(esc_html($settings['better_team_desg']) . ' ', 3); ?>
                            </textPath>
                        </text>
                    </g>
                </svg>
            </div>
            <div class="info">
                <h6><?php echo esc_html($settings['better_team_title']); ?></h6>
                <span><?php echo esc_html($settings['better_team_desg']); ?></span>
            </div>
        </div>
    </div>
</div>
