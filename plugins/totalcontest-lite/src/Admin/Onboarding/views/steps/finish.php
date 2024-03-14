<script type="text/ng-template" id="finish-component-template">
    <h2 class="title"><?php echo $this->getContent('finish.title') ?></h2>
    <p class="lead"><?php echo $this->getContent('finish.description') ?></p>

    <div class="row no-gutters">
        <div class="left-side col-md-auto">
            <div class="feature-switch" ng-class="{'is-on' : $ctrl.OnBoarding.data.tracking }">
                <label class="toggle">
                    <span class="icon material-icons">analytics</span>
                    <div class="body">
                        <h4 class="title">Help us improve our products</h4>
                        <p class="description">Share your usage anonymously with us.</p>
                    </div>
                    <input type="checkbox" class="switch" ng-model="$ctrl.OnBoarding.data.tracking" ng-true-value="1" ng-false-value="0">
                </label>
            </div>
            <div class="feature-switch" ng-class="{'is-on' : $ctrl.OnBoarding.data.signup }">
                <label class="toggle">
                    <span class="icon material-icons">person</span>
                    <div class="body">
                        <h4 class="title">Sign up to TotalSuite.net</h4>
                        <p class="description">Get access to free add-ons and exclusive content.</p>
                    </div>
                    <input type="checkbox" name="" class="switch" ng-model="$ctrl.OnBoarding.data.signup" ng-true-value="1" ng-false-value="0">
                </label>
            </div>
            <div class="feature-switch" ng-class="{'is-on' : $ctrl.OnBoarding.data.newsletter }">
                <label class="toggle">
                    <span class="icon material-icons">email</span>
                    <div class="body">
                        <h4 class="title">Subscribe to our newsletter</h4>
                        <p class="description">We'll keep you in the loop plus exclusive offers!</p>
                    </div>
                    <input type="checkbox" name="" class="switch" ng-model="$ctrl.OnBoarding.data.newsletter" ng-true-value="1" ng-false-value="0">
                </label>
            </div>
        </div>
        <div class="col-md information-box">
            <h4>Usage tracking will help...</h4>
            <hr>
            <p><span class="material-icons">check</span> Make TotalContest stable and bug-free.</p>
            <p><span class="material-icons">check</span> Get an overview of environments.</p>
            <p><span class="material-icons">check</span> Optimize performance.</p>
            <p><span class="material-icons">check</span> Adjust default parameters.</p>
            <p>
                <a href="https://totalsuite.net/usage-tracking" class="button -alt">
                    <span class="icon material-icons">open_in_new</span>
                    <span class="text">Learn more</span>.
                </a>
            </p>

            <img src="<?php
            echo esc_attr($this->env['url']); ?>assets/dist/images/onboarding/why.svg" alt="Wondering">
        </div>
    </div>
    <div class="social-box">
        <h4>Follow us and let's keep you in the loop!</h4>
        <nav class="social-icons">
            <a class="icon twitter-icon" href="https://www.twitter.com/totalsuite" target="_blank">
                <svg viewBox="0 0 24 24">
                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                </svg>
            </a>
            <a class="icon youtube-icon" href="https://www.youtube.com/channel/UCJC_A6lyCMaklpoDErNEgNw" target="_blank">
                <svg viewBox="0 0 24 24">
                    <path d="M23.495 6.205a3.007 3.007 0 0 0-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 0 0 .527 6.205a31.247 31.247 0 0 0-.522 5.805 31.247 31.247 0 0 0 .522 5.783 3.007 3.007 0 0 0 2.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 0 0 2.088-2.088 31.247 31.247 0 0 0 .5-5.783 31.247 31.247 0 0 0-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"/>
                </svg>
            </a>
        </nav>
    </div>
</script>