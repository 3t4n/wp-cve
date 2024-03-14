<script type="text/ng-template" id="introduction-component-template">
    <h2 class="title">🎓<br>Get started</h2>
    <p class="lead">We've prepared some materials for you to ease your learning curve.</p>

    <div class="row equal-height">
        <div class="col-md-4">
            <article class="card onboarding-card">
                <a href="https://totalsuite.net/documentation/totalcontest/basics-totalcontest/create-first-contest-using-totalcontest/" target="_blank" class="link"></a>

                <header class="header">
                    <i class="material-icons icon">open_in_new</i>
                    <img class="thumbnail"
                         src="<?php echo esc_attr( $this->env['url'] ); ?>assets/dist/images/onboarding/create.svg"/>
                </header>
                <div class="body">
                    <h4 class="title">How to create a contest</h4>
                    <p class="description">Learn how to create a contest in no time using TotalContest.</p>
                </div>
            </article>
        </div>
        <div class="col-md-4">
            <article class="card onboarding-card">
                <a href="https://totalsuite.net/documentation/totalcontest/basics-totalcontest/contest-publishing-totalcontest/" target="_blank" class="link"></a>
                <header class="header">
                    <i class="material-icons icon">open_in_new</i>
                    <img class="thumbnail"
                         src="<?php echo esc_attr( $this->env['url'] ); ?>assets/dist/images/onboarding/integrate.svg"/>
                </header>
                <div class="body">
                    <h4 class="title">How to integrate the contest</h4>
                    <p class="description">Once your contest is ready, we'll show you how to integrate it into your
                        site.</p>
                </div>
            </article>
        </div>
        <div class="col-md-4">
            <article class="card onboarding-card">
                <a href="https://totalsuite.net/documentation/totalcontest/basics-totalcontest/design-customization/" target="_blank" class="link"></a>
                <header class="header">
                    <i class="material-icons icon">open_in_new</i>
                    <img class="thumbnail"
                         src="<?php echo esc_attr( $this->env['url'] ); ?>assets/dist/images/onboarding/customize.svg"/>
                </header>
                <div class="body">
                    <h4 class="title">How to customize the appearance of the contest</h4>
                    <p class="description">Learn how to customize the appearance of the contest to match your brand.</p>
                </div>
            </article>
        </div>
    </div>

    <div class="more-box">
        <img src="<?php echo esc_attr( $this->env['url'] ); ?>assets/dist/images/onboarding/reading.svg" alt="Reading">

        <h3 class="title">Looking for more?</h3>
        <p class="description">Visit our knowledge base and learn more about TotalContest.</p>
        <a href="https://totalsuite.net/product/totalcontest/documentation/" target="_blank"
           class="button -primary">Browse TotalSuite.net
        </a>
    </div>
</script>