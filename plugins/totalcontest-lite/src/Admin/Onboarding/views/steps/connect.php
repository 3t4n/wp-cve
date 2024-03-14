<script type="text/ng-template" id="connect-component-template">
    <h2 class="title"><?php echo $this->getContent('connect.title') ?></h2>
    <p class="lead"><?php echo $this->getContent('connect.description') ?></p>

    <div class="row">
        <form class="left-side col-md-7">
            <div class="form-group">
                <label class="label" for="email">My email is</label>
                <input id="email" type="email" class="input" name="firstname" placeholder="Your email address"
                 ng-model="$ctrl.OnBoarding.data.email">
                <small>Used only when needed, no spam, we promise.</small>
            </div>
            <div class="form-group">
                <label class="label">and I'm a ...</label>
                <div class="row">
                    <div class="col" ng-repeat="position in $ctrl.positions">
                        <button class="button -preset"
                                type="button"
                                ng-class="{'is-active' : $ctrl.OnBoarding.data.audience == position.value}"
                                ng-click="$ctrl.OnBoarding.data.audience = position.value">
                            <span class="icon material-icons">{{ position.icon}}</span>
                            <span class="name">{{ position.label}}</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="label">and I'll be using TotalContest for ...</label>
                <div class="row">
                    <div class="col" ng-repeat="usage in $ctrl.usages">
                        <button class="button -preset" type="button"
                                ng-class="{'is-active' : $ctrl.OnBoarding.data.usage == usage.value}"
                                ng-click="$ctrl.OnBoarding.data.usage = usage.value">
                            <span class="icon material-icons">{{ usage.icon }}</span>
                            <span class="name">{{ usage.label }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <div class="col-md-5 right-side information-box">
            <h4>Why these information?</h4>
            <hr>
            <p><span class="material-icons">check</span> Let you know about the upcoming features.</p>
            <p><span class="material-icons">check</span> Inform you about important updates.</p>
            <p><span class="material-icons">check</span> Adjust recommendations.</p>
            <p><span class="material-icons">check</span> Adapt product settings.</p>
            <p><span class="material-icons">check</span> Send you exclusive offers.</p>

            <img src="<?php echo esc_attr( $this->env['url'] ); ?>assets/dist/images/onboarding/why.svg"
                 alt="Wondering">
        </div>
    </div>
</script>