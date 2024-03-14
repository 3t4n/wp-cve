<?php include $this->getPath( 'views/shared/header.php' ); ?>
<?php if ( $contest->isAcceptingSubmissions() ): ?>
    <div class="totalcontest-participate">
		<?php echo $form->open(); ?>
        <div class="totalcontest-form-loading" style="display: none;">
            <svg width="38" height="38" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient x1="8.042%" y1="0%" x2="65.682%" y2="23.865%" id="a">
                        <stop stop-color="#000000" stop-opacity="0" offset="0%"/>
                        <stop stop-color="#000000" stop-opacity=".631" offset="63.146%"/>
                        <stop stop-color="#000000" offset="100%"/>
                    </linearGradient>
                </defs>
                <g fill="none" fill-rule="evenodd">
                    <g transform="translate(1 1)">
                        <path d="M36 18c0-9.94-8.06-18-18-18" id="Oval-2" stroke="url(#a)" stroke-width="2">
                            <animateTransform
                                    attributeName="transform"
                                    type="rotate"
                                    from="0 18 18"
                                    to="360 18 18"
                                    dur="0.9s"
                                    repeatCount="indefinite"/>
                        </path>
                        <circle fill="#fff" cx="36" cy="18" r="1">
                            <animateTransform
                                    attributeName="transform"
                                    type="rotate"
                                    from="0 18 18"
                                    to="360 18 18"
                                    dur="0.9s"
                                    repeatCount="indefinite"/>
                        </circle>
                    </g>
                </g>
            </svg>
			<?php  esc_html_e( 'Submitting', 'totalcontest' ); ?>
        </div>
        <div class="totalcontest-form-hidden-fields">
			<?php echo $form->hiddenFields(); ?>
        </div>
        <div class="totalcontest-form-custom-fields">
			<?php echo $form->fields(); ?>
        </div>
        <div class="totalcontest-buttons">
			<?php echo $form->buttons(); ?>
        </div>
		<?php echo $form->close(); ?>
    </div>
<?php else: ?>
    <div class="totalcontest-message totalcontest-message-error">
		<?php echo $contest->getErrorMessage(); ?>
    </div>
<?php endif; ?>

<?php
include $this->getPath( 'views/shared/footer.php' );
