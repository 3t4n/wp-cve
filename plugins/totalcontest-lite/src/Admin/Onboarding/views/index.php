<style>
  .contest_page_onboarding {
    overflow: hidden !important;
  }
</style>

<template id="totalcontest-onboarding-template">
    <style>
      @import "<?php echo esc_attr( $this->env['url'] ); ?>/assets/dist/styles/admin-onboarding.css";
    </style>
    <div id="totalcontest-onboarding" class="onboarding-window">
        <div class="wizard" ng-controller="MainController as $ctrl">
            <div class="totalcontest-loading" ng-if="OnboardingService.isLoading()">
                <div class="totalcontest-loading-spinner"></div>
            </div>
            <div class="row no-gutters">
                <!-- Side -->
                <menu class="col-md-auto side"></menu>

                <!-- Main -->
                <main class="col-md content">

                    <button type="button" class="close button -outline -icon" ng-if="!OnboardingService.isFinished()" ng-click="OnboardingService.close()">
                        <span class="material-icons">close</span>
                    </button>

                    <!-- Body -->
                    <div class="body">
                        <!-- Step 1 -->
                        <welcome class="welcome" ng-if="OnboardingService.isStep(1)"></welcome>

                        <!-- Step 2 -->
                        <introduction class="introduction"  ng-if="OnboardingService.isStep(2)"></introduction>

                        <!-- Step 3 -->
                        <connect class="connect"  ng-if="OnboardingService.isStep(3)"></connect>

                        <!-- Step 4  -->
                        <finish class="finish" ng-if="OnboardingService.isStep(4)"></finish>
                    </div>

                    <!-- Footer -->
                    <div class="footer">
                        <button type="button" class="button -outline" ng-click="OnboardingService.previousStep()" ng-if="OnboardingService.isStarted()">
                            <span class="icon material-icons">arrow_back</span>
                            <span>Back</span>
                        </button>
                        <button type="button" class="button -primary" ng-click="OnboardingService.nextStep()" ng-if="!OnboardingService.isFinished()">
                            <span>Continue</span>
                            <span class="icon material-icons">arrow_forward</span>
                        </button>
                        <button type="button" class="button -primary" ng-if="OnboardingService.isFinished()"
                                ng-click="OnboardingService.finish()">
                            Finish
                        </button>
                    </div>

                </main>
            </div>
        </div>


        <?php include __DIR__ . '/steps/menu.php'; ?>
        <?php include __DIR__ . '/steps/welcome.php'; ?>
        <?php include __DIR__ . '/steps/introduction.php'; ?>
        <?php include __DIR__ . '/steps/connect.php'; ?>
        <?php include __DIR__ . '/steps/finish.php'; ?>
    </div>
</template>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const template = document.querySelector('template#totalcontest-onboarding-template');
        const host = document.createElement('div');

        // @ts-ignore
        // @ts-ignore
        host.attachShadow({
            mode: 'open'
        }).append(template.content);

        // @ts-ignore
        template.after(host);
        template.remove();

        angular.bootstrap(host.shadowRoot.querySelector('#totalcontest-onboarding'), ['onboarding'])
    })
</script>
