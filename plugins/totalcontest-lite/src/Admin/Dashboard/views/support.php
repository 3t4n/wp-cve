<script type="text/ng-template" id="dashboard-support-component-template">
    <div class="totalcontest-box totalcontest-box-support-search">
        <div class="totalcontest-box-title"><?php  esc_html_e( 'How can we help you?', 'totalcontest' ); ?></div>
        <div class="totalcontest-box-description"><?php  esc_html_e( 'Search our knowledge base for detailed answers and tutorials.', 'totalcontest' ); ?></div>
        <form action="<?php echo esc_attr( $this->env['links.search'] ); ?>" method="get" target="_blank" class="totalcontest-box-composed-form">
            <input type="text" name="search" class="totalcontest-box-composed-form-field" placeholder="<?php esc_attr_e( 'Enter some keywords', 'totalcontest' ); ?>">
            <input type="hidden" name="search_product" value="totalcontest">
            <input type="hidden" name="search_source" value="in-app">
            <button type="submit" class="button button-primary button-large totalcontest-box-composed-form-button"><?php  esc_html_e( 'Search', 'totalcontest' ); ?></button>
        </form>
    </div>
    <div class="totalcontest-row">
        <div class="totalcontest-column">
            <div class="totalcontest-box totalcontest-box-support-channel">
                <div class="totalcontest-box-section">
                    <img class="totalcontest-box-support-channel-image" src="<?php echo esc_attr( $this->env['url'] ); ?>assets/dist/images/support/community-support.svg">
                    <div class="totalcontest-box-title"><?php  esc_html_e( 'Community Support', 'totalcontest' ); ?></div>
                    <div class="totalcontest-box-description"><?php  esc_html_e( 'Join and ask TotalSuite community for help.', 'totalcontest' ); ?></div>
                    <a href="<?php echo esc_attr( $this->env['links.forums'] ); ?>" target="_blank" class="button button-primary button-large"><?php  esc_html_e( 'Visit Forums', 'totalcontest' ); ?></a>
                </div>
            </div>
        </div>
        <div class="totalcontest-column">
            <div class="totalcontest-box totalcontest-box-support-channel">
                <div class="totalcontest-box-section">
                    <img class="totalcontest-box-support-channel-image" src="<?php echo esc_attr( $this->env['url'] ); ?>assets/dist/images/support/customer-support.svg">
                    <div class="totalcontest-box-title"><?php  esc_html_e( 'Customer Support', 'totalcontest' ); ?></div>
                    <div class="totalcontest-box-description"><?php  esc_html_e( 'Our support team is here to help you.', 'totalcontest' ); ?></div>
                    <a href="<?php echo esc_attr( $this->env['links.support'] ); ?>" target="_blank" class="button button-primary button-large"><?php  esc_html_e( 'Send Ticket', 'totalcontest' ); ?></a>
                </div>
            </div>
        </div>
        <div class="totalcontest-column">
            <div class="totalcontest-box totalcontest-box-support-channel">
                <div class="totalcontest-box-section">
                    <img class="totalcontest-box-support-channel-image" src="<?php echo esc_attr( $this->env['url'] ); ?>assets/dist/images/support/instant-support.svg">
                    <div class="totalcontest-box-title"><?php  esc_html_e( 'Instant Support', 'totalcontest' ); ?></div>
                    <div class="totalcontest-box-description"><?php  esc_html_e( 'You\'re in a hurry? We\'ve got your back!', 'totalcontest' ); ?></div>
                    <a href="<?php echo esc_attr( $this->env['links.support'] ); ?>" target="_blank" class="button button-primary button-large"><?php  esc_html_e( 'Learn More', 'totalcontest' ); ?></a>
                </div>
            </div>
        </div>
    </div>
    <div class="totalcontest-row">
        <div class="totalcontest-column totalcontest-column-6" ng-repeat="section in $ctrl.sections">
            <dashboard-links
                    heading="section.title"
                    description="section.description"
                    links="section.links">
            </dashboard-links>
        </div>
    </div>
</script>
<script type="text/ng-template" id="dashboard-links-component-template">
    <div class="totalcontest-box totalcontest-box-links">
        <div class="totalcontest-box-section">
            <div class="totalcontest-box-title">{{ $ctrl.heading }}</div>
            <div class="totalcontest-box-description">{{ $ctrl.description }}</div>
        </div>
        <div class="totalcontest-box-links-item" ng-repeat="link in $ctrl.links">
            <a href="{{ link.url }}" target="_blank" title="{{link.title}}">{{ link.title }}</a>
        </div>
    </div>
</script>
