<?php include $this->getPath( 'views/shared/header.php' ); ?>
<?php if ( ! empty( $voteCasted ) ): ?>
    <div class="totalcontest-submission-thankyou">
        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd"
             clip-rule="evenodd">
            <path d="M12 0c6.623 0 12 5.377 12 12s-5.377 12-12 12-12-5.377-12-12 5.377-12 12-12zm0 1c6.071 0 11 4.929 11 11s-4.929 11-11 11-11-4.929-11-11 4.929-11 11-11zm7 7.457l-9.005 9.565-4.995-5.865.761-.649 4.271 5.016 8.24-8.752.728.685z"/>
        </svg>

		<?php echo empty( $content ) ? esc_html__( 'Thank you!', 'totalcontest' ) : $content; ?>
    </div>
<?php endif; ?>
    <div class="totalcontest-submission">
    <div class="totalcontest-submission-main">
		<?php if ( $contest->isUsingBlocks() ): ?>
            <div class="totalcontest-submission-content <?php echo $submission->isWinner() ? 'is-winner' : ''; ?>">
                <div class="totalcontest-submission-content-blocks">
					<?php foreach ( $submission->getBlocks() as $block ): ?>
						<?php $block['source'] = empty( $block['source'] ) ? '' : $block['source']; ?>
                        <div class="totalcontest-submission-content-block totalcontest-submission-content-block-type-<?php echo esc_attr( $block['type'] ); ?> <?php echo esc_attr( $block['class'] ); ?>">
							<?php if ( $block['type'] === 'text' ): ?>
                                <p><?php echo $block['html']; ?></p>
							<?php elseif ( $block['type'] === 'title' ): ?>
                                <h3><?php echo $block['html']; ?></h3>
							<?php elseif ( $block['type'] === 'subtitle' ): ?>
                                <p><?php echo $block['html']; ?></p>
							<?php elseif ( $block['type'] === 'image' ): ?>
								<?php if ( strtolower( $block['source'] ) === 'custom' ) : ?>
                                    <img src="<?php echo esc_html( $block['html'] ); ?>">
								<?php else: ?>
									<?php echo $block['html']; ?>
								<?php endif; ?>
							<?php elseif ( $block['type'] === 'embed' ): ?>
								<?php echo do_shortcode( $block['html'] ); ?>
							<?php else: ?>
								<?php echo $block['html']; ?>
							<?php endif; ?>
                        </div>
					<?php endforeach; ?>
                </div>
            </div>
			<?php if ( $submission->isWinner() ): ?>
                <div class="totalcontest-submission-winner">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24">
                        <path d="M5.991 9.656c.286.638.585 1.231.882 1.783-4.065-1.348-6.501-5.334-6.873-9.439h4.077c.036.482.08.955.139 1.405h-2.689c.427 2.001 1.549 4.729 4.464 6.251zm4.613 6.344c-.499-3.947-5.604-6.197-5.604-16h14c0 9.803-5.094 12.053-5.592 16h-2.804zm-3.254-14c.205 4.648 1.99 8.333 4.346 11.053-1.887-3.26-2.636-7.432-2.647-11.053h-1.699zm9.65 17.619v4.381h-10v-4.381c1.941 0 3.369-1.433 3.571-2.619h2.866c.193 1.187 1.565 2.619 3.563 2.619zm-1 1.381h-8v2h8v-2zm3.923-19c-.036.482-.08.955-.139 1.405h2.688c-.427 2.001-1.549 4.729-4.464 6.251-.286.638-.585 1.231-.882 1.783 4.066-1.348 6.502-5.334 6.874-9.439h-4.077z"/>
                    </svg>
                    <span><?php esc_html_e( 'Winner!', 'totalcontest' ); ?></span>
                </div>
			<?php endif; ?>
		<?php else: ?>
            <div class="totalcontest-submission-content <?php echo $submission->isWinner() ? 'is-winner' : ''; ?> <?php echo $submission->isEmbeddable() ? 'is-embed' : 'is-local'; ?>">
				<?php echo $submission->getContent(); ?>
				<?php if ( $submission->isWinner() ): ?>
                    <div class="totalcontest-submission-winner">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24">
                            <path d="M5.991 9.656c.286.638.585 1.231.882 1.783-4.065-1.348-6.501-5.334-6.873-9.439h4.077c.036.482.08.955.139 1.405h-2.689c.427 2.001 1.549 4.729 4.464 6.251zm4.613 6.344c-.499-3.947-5.604-6.197-5.604-16h14c0 9.803-5.094 12.053-5.592 16h-2.804zm-3.254-14c.205 4.648 1.99 8.333 4.346 11.053-1.887-3.26-2.636-7.432-2.647-11.053h-1.699zm9.65 17.619v4.381h-10v-4.381c1.941 0 3.369-1.433 3.571-2.619h2.866c.193 1.187 1.565 2.619 3.563 2.619zm-1 1.381h-8v2h8v-2zm3.923-19c-.036.482-.08.955-.139 1.405h2.688c-.427 2.001-1.549 4.729-4.464 6.251-.286.638-.585 1.231-.882 1.783 4.066-1.348 6.502-5.334 6.874-9.439h-4.077z"/>
                        </svg>
                        <span><?php esc_html_e( 'Winner!', 'totalcontest' ); ?></span>
                    </div>
				<?php endif; ?>
            </div>
		<?php endif; ?>
        <div class="totalcontest-submission-sidebar">
            <div class="totalcontest-submission-stats">
				<?php if ( $contest->isCountVoting() ): ?>
                    <div class="totalcontest-submission-stats-item">
                        <div class="totalcontest-submission-stats-item-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                 fill-rule="evenodd" clip-rule="evenodd">
                                <path d="M0 22h1v-5h4v5h2v-10h4v10h2v-15h4v15h2v-21h4v21h1v1h-24v-1zm4-4h-2v4h2v-4zm6-5h-2v9h2v-9zm6-5h-2v14h2v-14zm6-6h-2v20h2v-20z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="totalcontest-submission-stats-item-value"><?php echo number_format( $submission->getVotes() ); ?></div>
                            <div class="totalcontest-submission-stats-item-title"><?php esc_html_e( 'Votes',
							                                                                        'totalcontest' ); ?></div>
                        </div>
                    </div>
				<?php endif; ?>

                <div class="totalcontest-submission-stats-item">
                    <div class="totalcontest-submission-stats-item-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                             fill-rule="evenodd" clip-rule="evenodd">
                            <path d="M12.01 20c-5.065 0-9.586-4.211-12.01-8.424 2.418-4.103 6.943-7.576 12.01-7.576 5.135 0 9.635 3.453 11.999 7.564-2.241 4.43-6.726 8.436-11.999 8.436zm-10.842-8.416c.843 1.331 5.018 7.416 10.842 7.416 6.305 0 10.112-6.103 10.851-7.405-.772-1.198-4.606-6.595-10.851-6.595-6.116 0-10.025 5.355-10.842 6.584zm10.832-4.584c2.76 0 5 2.24 5 5s-2.24 5-5 5-5-2.24-5-5 2.24-5 5-5zm0 1c2.208 0 4 1.792 4 4s-1.792 4-4 4-4-1.792-4-4 1.792-4 4-4z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="totalcontest-submission-stats-item-value"><?php echo number_format( $submission->getViews() ); ?></div>
                        <div class="totalcontest-submission-stats-item-title"><?php esc_html_e( 'Views',
						                                                                        'totalcontest' ); ?></div>
                    </div>
                </div>
                <div class="totalcontest-submission-stats-item">
                    <div class="totalcontest-submission-stats-item-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                             fill-rule="evenodd" clip-rule="evenodd">
                            <path d="M24 23h-24v-19h4v-3h4v3h8v-3h4v3h4v19zm-1-15h-22v14h22v-14zm-16.501 8.794l1.032-.128c.201.93.693 1.538 1.644 1.538.957 0 1.731-.686 1.731-1.634 0-.989-.849-1.789-2.373-1.415l.115-.843c.91.09 1.88-.348 1.88-1.298 0-.674-.528-1.224-1.376-1.224-.791 0-1.364.459-1.518 1.41l-1.032-.171c.258-1.319 1.227-2.029 2.527-2.029 1.411 0 2.459.893 2.459 2.035 0 .646-.363 1.245-1.158 1.586.993.213 1.57.914 1.57 1.928 0 1.46-1.294 2.451-2.831 2.451-1.531 0-2.537-.945-2.67-2.206zm9.501 2.206h-1.031v-6.265c-.519.461-1.354.947-1.969 1.159v-.929c1.316-.576 2.036-1.402 2.336-1.965h.664v8zm7-14h-22v2h22v-2zm-16-3h-2v2h2v-2zm12 0h-2v2h2v-2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="totalcontest-submission-stats-item-value"
                             title="<?php echo esc_attr( $submission->getDate() ); ?>">
							<?php echo $submission->getDateDiffForHuman(); ?>
                        </div>
                        <div class="totalcontest-submission-stats-item-title"><?php esc_html_e( 'Since posted',
						                                                                        'totalcontest' ); ?></div>
                    </div>
                </div>
            </div>

			<?php
			if ( $contest->isRateVoting() ): ?>
                <div class="totalcontest-submission-stats totalcontest-submission-ratings">
                    <div class="totalcontest-submission-stats-item">
                        <div class="totalcontest-submission-stats-item-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                 fill-rule="evenodd" clip-rule="evenodd">
                                <path d="M15.668 8.626l8.332 1.159-6.065 5.874 1.48 8.341-7.416-3.997-7.416 3.997 1.481-8.341-6.064-5.874 8.331-1.159 3.668-7.626 3.669 7.626zm-6.67.925l-6.818.948 4.963 4.807-1.212 6.825 6.068-3.271 6.069 3.271-1.212-6.826 4.964-4.806-6.819-.948-3.002-6.241-3.001 6.241z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="totalcontest-submission-stats-item-value"><?php echo $submission->getRateNumber(); ?></div>
                            <div class="totalcontest-submission-stats-item-title"><?php esc_html_e( 'Average rate',
							                                                                        'totalcontest' ); ?></div>
                        </div>
                    </div>
					<?php foreach ( $submission->getRatings() as $rate ): ?>
                        <div class="totalcontest-submission-stats-item">
                            <div class="totalcontest-submission-stats-item-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                     fill-rule="evenodd" clip-rule="evenodd">
                                    <path d="M19 22h-19v-19h19v2h-1v-1h-17v17h17v-9.502h1v10.502zm5-19.315l-14.966 15.872-5.558-6.557.762-.648 4.833 5.707 14.201-15.059.728.685z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="totalcontest-submission-stats-item-value"><?php echo $rate['value']; ?></div>
                                <div class="totalcontest-submission-stats-item-title"><?php echo esc_html( $rate['name'] ); ?></div>
                            </div>
                        </div>
					<?php endforeach; ?>
                </div>
			<?php endif; ?>

			<?php if ( ! empty( $form ) ): ?>
                <div class="totalcontest-vote">
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
						<?php esc_html_e( 'Submitting', 'totalcontest' ); ?>
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
			<?php elseif ( ! empty( $message ) ): ?>
                <div class="totalcontest-message totalcontest-message-error">
                    <p><?php echo $message; ?></p>
                </div>
			<?php endif; ?>
        </div>
    </div>
<?php include $this->getPath( 'views/shared/footer.php' );
