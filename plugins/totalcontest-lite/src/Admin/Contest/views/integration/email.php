<div class="totalcontest-pro-badge-container">
    <div class="totalcontest-integration-steps">
        <div class="totalcontest-integration-steps-item">
            <div class="totalcontest-integration-steps-item-number">
                <div class="totalcontest-integration-steps-item-number-circle">1</div>
            </div>
            <div class="totalcontest-integration-steps-item-content">
                <h3 class="totalcontest-h3">
                    <?php  esc_html_e( 'Copy the link', 'totalcontest' ); ?>
                </h3>
                <p>
                    <?php  esc_html_e( 'Start by copying the following link:', 'totalcontest' ); ?>
                </p>
                <div class="totalcontest-integration-steps-item-copy">
                    <input name="" type="text" readonly onfocus="this.setSelectionRange(0, this.value.length)" value="<?php echo esc_attr( $this->contest->getPermalink() ); ?>" disabled>
                    <button type="button" class="button button-primary button-large" copy-to-clipboard="<?php echo esc_attr( $this->contest->getPermalink() ); ?>" disabled>
                        <?php  esc_html_e( 'Copy', 'totalcontest' ); ?>
                    </button>
                </div>
                <div class="totalcontest-integration-steps-item-copy">
                    <input name="" type="text" readonly onfocus="this.setSelectionRange(0, this.value.length)" value="<?php echo esc_attr( $this->contest->getParticipateUrl() ); ?>" disabled>
                    <button type="button" class="button button-primary button-large" copy-to-clipboard="<?php echo esc_attr( $this->contest->getParticipateUrl() ); ?>" disabled>
                        <?php  esc_html_e( 'Copy', 'totalcontest' ); ?>
                    </button>
                </div>
                <div class="totalcontest-integration-steps-item-copy">
                    <input name="" type="text" readonly onfocus="this.setSelectionRange(0, this.value.length)" value="<?php echo esc_attr( $this->contest->getSubmissionsUrl() ); ?>" disabled>
                    <button type="button" class="button button-primary button-large" copy-to-clipboard="<?php echo esc_attr( $this->contest->getSubmissionsUrl() ); ?>" disabled>
                        <?php  esc_html_e( 'Copy', 'totalcontest' ); ?>
                    </button>
                </div>
                <div class="totalcontest-integration-steps-item-copy" ng-repeat="page in editor.settings.pages.other">
                    <input name="" type="text" readonly onfocus="this.setSelectionRange(0, this.value.length)" value="<?php echo esc_attr( $this->contest->getCustomPageUrl('{{page.id}}') ); ?>" disabled>
                    <button type="button" class="button button-primary button-large" copy-to-clipboard="<?php echo esc_attr( $this->contest->getCustomPageUrl('{{page.id}}') ); ?>" disabled>
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
                    <?php  esc_html_e( 'Paste the link', 'totalcontest' ); ?>
                </h3>
                <p>
                    <?php  esc_html_e( 'Paste the copied link anywhere like pages and posts.', 'totalcontest' ); ?>
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
                    <?php  esc_html_e( 'Open the page which you have pasted the link in and test contest functionality.', 'totalcontest' ); ?>
                </p>
            </div>
        </div>
    </div>
    <?php TotalContest( 'upgrade-to-pro' ); ?>
</div>
