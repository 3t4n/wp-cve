<script type="text/ng-template" id="menu-component-template">
        <!-- Steps -->
        <div class="progress-steps">
            <div class="step"
                 ng-repeat="step in $ctrl.steps"
                 ng-click="$ctrl.OnboardingService.setStep($index + 1)"
                 ng-class="{
                    'is-current' : $ctrl.OnboardingService.isStep($index + 1),
                    'is-completed' : $ctrl.OnboardingService.isStepCompleted($index + 1)
                 }">
                <span class="number">{{$index + 1}}</span>
                <div class="body">
                    <h5 class="title">{{step.title}}</h5>
                    <p class="description">{{step.description}}</p>
                </div>
            </div>
        </div>
</script>
