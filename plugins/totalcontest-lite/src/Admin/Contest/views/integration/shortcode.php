<div class="totalcontest-integration-steps">
    <div class="totalcontest-integration-steps-item">
        <div class="totalcontest-integration-steps-item-number">
            <div class="totalcontest-integration-steps-item-number-circle">1</div>
        </div>
        <div class="totalcontest-integration-steps-item-content">
            <h3 class="totalcontest-h3">
                <?php  esc_html_e( 'Copy shortcode', 'totalcontest' ); ?>
            </h3>
            <p>
                <?php  esc_html_e( 'Start by copying one of the following shortcodes:', 'totalcontest' ); ?>
            </p>
			<?php $shortcode = esc_attr( sprintf( '[totalcontest contest="%d"]', get_the_ID() ) ); ?>
			<?php $shortcodeWithoutMenu = esc_attr( sprintf( '[totalcontest contest="%d" menu="false"]', get_the_ID() ) ); ?>
			<?php $participateShortcode = esc_attr( sprintf( '[totalcontest contest="%d" screen="contest.participate"]', get_the_ID() ) ); ?>
			<?php $submissionsShortcode = esc_attr( sprintf( '[totalcontest contest="%d" screen="contest.submissions"]', get_the_ID() ) ); ?>
			<?php $pageShortcode = esc_attr( sprintf( '[totalcontest contest="%d" screen="contest.content" page-id="home"]', get_the_ID() ) ); ?>
			<?php $submissionShortcode = esc_attr( sprintf( '[totalcontest contest="%d" screen="submission.view" submission="%s"]', get_the_ID(), 'SUBMISSION-ID' ) ); ?>
			<?php $countdownShortcode = esc_attr( sprintf( '[totalcontest contest="%d" screen="contest.countdown"]', get_the_ID() ) ); ?>

            <div class="totalcontest-integration-steps-item-copy">
                <input name="" type="text" readonly onfocus="this.setSelectionRange(0, this.value.length)" value="<?php echo $shortcode; ?>">
                <button type="button" class="button button-primary" copy-to-clipboard="<?php echo $shortcode; ?>">
                    <?php  esc_html_e( 'Copy', 'totalcontest' ); ?>
                </button>
            </div>
            <div class="totalcontest-integration-steps-item-copy">
                <input name="" type="text" readonly onfocus="this.setSelectionRange(0, this.value.length)" value="<?php echo $shortcodeWithoutMenu; ?>">
                <button type="button" class="button button-primary" copy-to-clipboard="<?php echo $shortcodeWithoutMenu; ?>">
                    <?php  esc_html_e( 'Copy', 'totalcontest' ); ?>
                </button>
            </div>
            <div class="totalcontest-integration-steps-item-copy">
                <input name="" type="text" readonly onfocus="this.setSelectionRange(0, this.value.length)" value="<?php echo $participateShortcode; ?>">
                <button type="button" class="button button-primary" copy-to-clipboard="<?php echo $participateShortcode; ?>">
                    <?php  esc_html_e( 'Copy', 'totalcontest' ); ?>
                </button>
            </div>
            <div class="totalcontest-integration-steps-item-copy">
                <input name="" type="text" readonly onfocus="this.setSelectionRange(0, this.value.length)" value="<?php echo $submissionsShortcode; ?>">
                <button type="button" class="button button-primary" copy-to-clipboard="<?php echo $submissionsShortcode; ?>">
                    <?php  esc_html_e( 'Copy', 'totalcontest' ); ?>
                </button>
            </div>
            <div class="totalcontest-integration-steps-item-copy">
                <input name="" type="text" readonly onfocus="this.setSelectionRange(0, this.value.length)" value="<?php echo $pageShortcode; ?>">
                <button type="button" class="button button-primary" copy-to-clipboard="<?php echo $pageShortcode; ?>">
                    <?php  esc_html_e( 'Copy', 'totalcontest' ); ?>
                </button>
            </div>
            <div class="totalcontest-integration-steps-item-copy">
                <input name="" type="text" readonly onfocus="this.setSelectionRange(0, this.value.length)" value="<?php echo $submissionShortcode; ?>">
                <button type="button" class="button button-primary" copy-to-clipboard="<?php echo $submissionShortcode; ?>">
                    <?php  esc_html_e( 'Copy', 'totalcontest' ); ?>
                </button>
            </div>
            <div class="totalcontest-integration-steps-item-copy">
                <input name="" type="text" readonly onfocus="this.setSelectionRange(0, this.value.length)" value="<?php echo $countdownShortcode; ?>">
                <button type="button" class="button button-primary" copy-to-clipboard="<?php echo $countdownShortcode; ?>">
                    <?php  esc_html_e( 'Copy', 'totalcontest' ); ?>
                </button>
            </div>
        </div>
    </div>
    <div class="totalcontest-integration-steps-item">
        <div class="totalcontest-integration-steps-item-number">
            <div class="totalcontest-integration-steps-item-number-circle">2</div>
        </div>
        <div class="totalcontest-integration-steps-item-content">
            <h3 class="totalcontest-h3">
                <?php  esc_html_e( 'Paste the shortcode', 'totalcontest' ); ?>
            </h3>
            <p>
                <?php  esc_html_e( 'Paste the copied shortcode into an area that support shortcodes like pages and posts.', 'totalcontest' ); ?>
            </p>
        </div>
    </div>
    <div class="totalcontest-integration-steps-item">
        <div class="totalcontest-integration-steps-item-number">
            <div class="totalcontest-integration-steps-item-number-circle">3</div>
        </div>
        <div class="totalcontest-integration-steps-item-content">
            <h3 class="totalcontest-h3">
                <?php  esc_html_e( 'Preview', 'totalcontest' ); ?>
            </h3>
            <p>
                <?php  esc_html_e( 'Open the page which you have pasted the shortcode in and test contest functionality.', 'totalcontest' ); ?>
            </p>
        </div>
    </div>
</div>
