<div class="totalcontest-tabs-container">
    <div class="totalcontest-tabs-content">
        <!-- Customization -->
        <div class="totalcontest-tab-content active">
            <div class="totalcontest-customization">
                <img src="<?php echo TotalContest()->env( 'url' ) . 'assets/dist/images/editor/customization.svg'; ?>" alt="Customization">

                <div class="title">
	                <?php echo wp_kses( __('Customization?<br>We have got your back!', 'totalcontest' ), ['a' => ['href' => [], 'target' => []]]); ?>
                </div>
                <div class="copy"><?php  esc_html_e( 'If you need custom feature just let us know we will be happy to serve you!', 'totalcontest' ); ?></div>

                <a href="<?php echo TotalContest()->env( 'links.customization' ); ?>" target="_blank" class="button button-primary button-large"><?php  esc_html_e( 'Get a quote', 'totalcontest' ); ?></a>
            </div>
        </div>
    </div>
</div>
