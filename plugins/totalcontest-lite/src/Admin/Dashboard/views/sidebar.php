<script type="text/ng-template" id="dashboard-announcement-component-template">
    <div class="totalcontest-box totalcontest-box-announcement">
        <!-- @TODO Update this banner -->
    </div>
</script>
<script type="text/ng-template" id="dashboard-translate-component-template">
    <div class="totalcontest-box totalcontest-box-translate">
        <div class="totalcontest-box-section">
            <div class="totalcontest-box-title"><?php  esc_html_e( 'Translate', 'totalcontest' ); ?></div>
            <div class="totalcontest-box-description"><?php  esc_html_e( 'Help us translate TotalContest to your language and get an item from our store for free.', 'totalcontest' ); ?></div>
			<?php
			$url = add_query_arg(
				[
					'utm_source'   => 'in-app',
					'utm_medium'   => 'translation-box',
					'utm_campaign' => 'totalcontest',
				],
				$this->env['links.translate']
			);
			?>
            <a href="<?php echo esc_attr( $url ); ?>" target="_blank" class="button button-primary button-large"><?php  esc_html_e( 'Translate', 'totalcontest' ); ?></a>
        </div>
    </div>
</script>
<script type="text/ng-template" id="dashboard-subscribe-component-template">
    <div class="totalcontest-box totalcontest-box-subscribe">
        <div class="totalcontest-box-section">
            <div class="totalcontest-box-title"><?php  esc_html_e( 'Stay in the loop', 'totalcontest' ); ?></div>
            <div class="totalcontest-box-description">
                <?php echo wp_kses( __('Get latest news about new features, products and deals plus a <strong>10% discount</strong> to use in our store!', 'totalcontest' ), ['a' => ['href' => [], 'target' => []]]); ?>
            </div>
            <form class="totalcontest-box-composed-form" action="<?php echo esc_attr( $this->env['links']['subscribe'] ); ?>" target="_blank">
                <input type="text" class="totalcontest-box-composed-form-field" name="email"
                       placeholder="<?php echo esc_attr_e( 'Your email', 'totalcontest' ); ?>">
                <button type="submit"
                        class="button button-primary button-large totalcontest-box-composed-form-button"><?php  esc_html_e( 'Subscribe', 'totalcontest' ); ?></button>
            </form>
        </div>
        <div class="totalcontest-box-section totalcontest-box-subscribe-social">
            <div class="totalcontest-box-title"><?php  esc_html_e( 'Follow us', 'totalcontest' ); ?></div>
            <div class="totalcontest-box-subscribe-social-icons">
                <a href="<?php echo esc_attr( $this->env['links.twitter'] ); ?>" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="#55acee">
                        <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm6.066 9.645c.183 4.04-2.83 8.544-8.164 8.544-1.622 0-3.131-.476-4.402-1.291 1.524.18 3.045-.244 4.252-1.189-1.256-.023-2.317-.854-2.684-1.995.451.086.895.061 1.298-.049-1.381-.278-2.335-1.522-2.304-2.853.388.215.83.344 1.301.359-1.279-.855-1.641-2.544-.889-3.835 1.416 1.738 3.533 2.881 5.92 3.001-.419-1.796.944-3.527 2.799-3.527.825 0 1.572.349 2.096.907.654-.128 1.27-.368 1.824-.697-.215.671-.67 1.233-1.263 1.589.581-.07 1.135-.224 1.649-.453-.384.578-.87 1.084-1.433 1.489z"></path>
                    </svg>
                </a>

                <a href="<?php echo esc_attr( $this->env['links.facebook'] ); ?>" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="#3b5998">
                        <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm3 8h-1.35c-.538 0-.65.221-.65.778v1.222h2l-.209 2h-1.791v7h-3v-7h-2v-2h2v-2.308c0-1.769.931-2.692 3.029-2.692h1.971v3z"></path>
                    </svg>
                </a>

                <a href="<?php echo esc_attr( $this->env['links.youtube'] ); ?>" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#ff0000">
                        <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm4.441 16.892c-2.102.144-6.784.144-8.883 0-2.276-.156-2.541-1.27-2.558-4.892.017-3.629.285-4.736 2.558-4.892 2.099-.144 6.782-.144 8.883 0 2.277.156 2.541 1.27 2.559 4.892-.018 3.629-.285 4.736-2.559 4.892zm-6.441-7.234l4.917 2.338-4.917 2.346v-4.684z"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</script>
<script type="text/ng-template" id="dashboard-review-component-template">
    <div class="totalcontest-box totalcontest-box-review">
        <div class="totalcontest-box-section">
            <div class="totalcontest-box-title"><?php  esc_html_e( 'Spread the word', 'totalcontest' ); ?></div>
            <div class="totalcontest-box-description">
	            <?php echo wp_kses( __('Please consider <a href="https://codecanyon.net/downloads/">leaving a review</a>. You could also get a chance to win a free item from our store by tweeting about TotalContest!', 'totalcontest' ), ['a' => ['href' => [], 'target' => []]]); ?>
            </div>
            <form action="https://twitter.com/intent/tweet" target="_blank" method="get" class="totalcontest-box-composed-form">
                <textarea name="text" class="totalcontest-box-composed-form-field" rows="3" ng-value="$ctrl.randomTweet"></textarea>
                <input type="hidden" name="related" value="totalsuite">
                <input type="hidden" name="via" value="totalsuite">
                <button type="submit" class="button button-primary button-large totalcontest-box-composed-form-button">
					<?php  esc_html_e( 'Tweet', 'totalcontest' ); ?>
                </button>
            </form>
        </div>
    </div>
</script>
