<?php

include $this->getPath( 'views/shared/header.php' );

$layoutType = $contest->getSettingsItem( 'design.layout.type' );
$submissionsRows = $contest->getSubmissionsRows();
$sortBy = $contest->getSortByItems();
$sortDirections = $contest->getSortDirectionItems();
$filterBy = $contest->getFilterByItems();
$pages = $contest->getPaginationItems();
$previousPage = $contest->getPreviousPagePaginationItem();
$nextPage = $contest->getNextPagePaginationItem();
?>
    <div class="totalcontest-submissions">
        <div class="totalcontest-submissions-toolbar" totalcontest-mobile-scrollable>
            <div class="totalcontest-submissions-toolbar-items">
				<?php if ( ! empty( $filterBy ) && count( $filterBy ) > 1 ): ?>
                    <div class="totalcontest-submissions-toolbar-item totalcontest-submissions-toolbar-filter">
                        <span class="totalcontest-submissions-toolbar-title"><?php esc_html_e( 'Filter by',
                                                                                               'totalcontest' ); ?></span>
                        <select class="totalcontest-submissions-toolbar-select" totalcontest-submissions-filter>
							<?php foreach ( $filterBy as $filterByField ): ?>
                                <option value="<?php echo esc_attr( $filterByField['url'] ); ?>"
                                        totalcontest-ajax-url="<?php echo esc_attr( $filterByField['ajax'] ); ?>" <?php selected( true,
								                                                                                                  $filterByField['active'] ); ?>><?php esc_html_e( $filterByField['label'],
								                                                                                                                                                   'totalcontest' ); ?></option>
							<?php endforeach; ?>
                        </select>
                    </div>
				<?php endif; ?>

				<?php if ( ! empty( $sortBy ) ): ?>
                    <div class="totalcontest-submissions-toolbar-item totalcontest-submissions-toolbar-sort">
                        <span class="totalcontest-submissions-toolbar-title"><?php esc_html_e( 'Sort by',
                                                                                               'totalcontest' ); ?></span>
                        <select class="totalcontest-submissions-toolbar-select" totalcontest-submissions-filter>
							<?php foreach ( $sortBy as $sortByField ): ?>
                                <option value="<?php echo esc_attr( $sortByField['url'] ); ?>" <?php selected( true,
								                                                                               $sortByField['active'] ); ?>
                                        totalcontest-ajax-url="<?php echo esc_attr( $sortByField['ajax'] ); ?>"><?php echo esc_html_e( $sortByField['label'],
								                                                                                                       'totalcontest' ); ?></option>
							<?php endforeach; ?>
                        </select>
                    </div>
				<?php endif; ?>

                <div class="totalcontest-submissions-toolbar-item totalcontest-submissions-toolbar-sort-direction">
                    <select class="totalcontest-submissions-toolbar-select">
						<?php foreach ( $sortDirections as $sortDirection ): ?>
                            <option value="<?php echo esc_attr( $sortDirection['url'] ); ?>" <?php selected( true,
							                                                                                 $sortDirection['active'] ); ?>
                                    totalcontest-ajax-url="<?php echo esc_attr( $sortDirection['ajax'] ); ?>"><?php echo esc_html_e( $sortDirection['label'],
							                                                                                                         'totalcontest' ); ?></option>
						<?php endforeach; ?>
                    </select>
                </div>

				<?php do_action( 'totalcontest/actions/contest/template/toolbar/right', $contest ); ?>

                <div class="totalcontest-submissions-toolbar-item totalcontest-submissions-toolbar-toggle <?php echo $layoutType === 'grid' ? 'totalcontest-submissions-toolbar-active' : ''; ?>"
                     totalcontest-submissions-toggle-layout="grid">
                    <svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 8h4V4H4v4zm6 12h4v-4h-4v4zm-6 0h4v-4H4v4zm0-6h4v-4H4v4zm6 0h4v-4h-4v4zm6-10v4h4V4h-4zm-6 4h4V4h-4v4zm6 6h4v-4h-4v4zm0 6h4v-4h-4v4z"/>
                        <path d="M0 0h24v24H0z" fill="none"/>
                    </svg>
                </div>
				<?php if ( ! $contest->isUsingBlocks() ): ?>
                    <div class="totalcontest-submissions-toolbar-item totalcontest-submissions-toolbar-toggle <?php echo $layoutType === 'list' ? 'is-active' : ''; ?>"
                         totalcontest-submissions-toggle-layout="list">
                        <svg fill="#000000" height="24" viewBox="0 0 24 24" width="24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 0h24v24H0z" fill="none"/>
                            <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
                        </svg>
                    </div>
				<?php endif; ?>
            </div>
        </div>

        <div class="totalcontest-submissions-items totalcontest-submissions-items-layout-<?php echo $layoutType; ?>"
             totalcontest-submissions>
			<?php if ( empty( $submissionsRows ) ): ?>
                <div class="totalcontest-submissions-items-empty">
                    <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                         fill-rule="evenodd" clip-rule="evenodd">
                        <path d="M12 0c6.623 0 12 5.377 12 12s-5.377 12-12 12-12-5.377-12-12 5.377-12 12-12zm0 1c6.071 0 11 4.929 11 11s-4.929 11-11 11-11-4.929-11-11 4.929-11 11-11zm.5 17h-1v-9h1v9zm-.5-12c.466 0 .845.378.845.845 0 .466-.379.844-.845.844-.466 0-.845-.378-.845-.844 0-.467.379-.845.845-.845z"/>
                    </svg>

					<?php esc_html_e( 'There are no submissions yet.', 'totalcontest' ); ?>
                </div>
			<?php endif; ?>
			<?php foreach ( $submissionsRows as $submissionsRow ): ?>
				<?php if ( $layoutType === 'grid' ): ?>
                    <div class="totalcontest-submissions-row">
				<?php endif; ?>

				<?php foreach ( $submissionsRow as $submission ): ?>
                    <div class="totalcontest-submissions-item <?php echo $submission->isWinner() ? 'is-winner' : ''; ?>"
                         totalcontest-submissions-item style="width: <?php echo $contest->getColumnWidth(); ?>%;">
                        <a href="<?php echo esc_attr( $submission->getPermalink() ); ?>"
                           totalcontest-ajax-url="<?php echo esc_attr( $submission->getAjaxUrl() ) ?>"
                           class="totalcontest-submissions-item-link">
							<?php if ( $contest->isUsingBlocks() ): ?>
                                <div class="totalcontest-submissions-item-blocks">
									<?php foreach ( $submission->getBlocks() as $block ): ?>
										<?php $block['source'] = empty( $block['source'] ) ? '' : $block['source']; ?>
										<?php $block = apply_filters( 'totalcontest/filters/submission/image-block',
										                              $block,
										                              $submission ); ?>
                                        <div class="totalcontest-submissions-item-block totalcontest-submissions-item-block-type-<?php echo esc_attr( $block['type'] ); ?> <?php echo esc_attr( $block['class'] ); ?>">
											<?php if ( $block['type'] === 'text' ): ?>
                                                <p><?php echo $block['html']; ?></p>
											<?php elseif ( $block['type'] === 'title' ): ?>
                                                <h3><?php echo $block['html']; ?></h3>
											<?php elseif ( $block['type'] === 'subtitle' ): ?>
                                                <p><?php echo $block['html']; ?></p>
											<?php elseif ( $block['type'] === 'image' ): ?>
												<?php if ( strtolower( $block['source'] ) === 'custom' ) : ?>
                                                    <img loading="lazy" src="<?php echo esc_html( $block['html'] ); ?>">
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
							<?php else: ?>
                                <div class="totalcontest-submissions-item-preview">
									<?php echo $submission->getPreview(); ?>
                                </div>
                                <div class="totalcontest-submissions-item-details">
                                    <div class="totalcontest-submissions-item-title"><?php echo $submission->getTitle(); ?></div>
                                    <div class="totalcontest-submissions-item-meta">
                                        <div class="totalcontest-submissions-item-meta-content"><?php echo $submission->getSubtitle(); ?></div>
                                    </div>
                                </div>
								<?php if ( $submission->isWinner() ): ?>
                                    <div class="totalcontest-submissions-item-winner">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                             viewBox="0 0 24 24">
                                            <path d="M5.991 9.656c.286.638.585 1.231.882 1.783-4.065-1.348-6.501-5.334-6.873-9.439h4.077c.036.482.08.955.139 1.405h-2.689c.427 2.001 1.549 4.729 4.464 6.251zm4.613 6.344c-.499-3.947-5.604-6.197-5.604-16h14c0 9.803-5.094 12.053-5.592 16h-2.804zm-3.254-14c.205 4.648 1.99 8.333 4.346 11.053-1.887-3.26-2.636-7.432-2.647-11.053h-1.699zm9.65 17.619v4.381h-10v-4.381c1.941 0 3.369-1.433 3.571-2.619h2.866c.193 1.187 1.565 2.619 3.563 2.619zm-1 1.381h-8v2h8v-2zm3.923-19c-.036.482-.08.955-.139 1.405h2.688c-.427 2.001-1.549 4.729-4.464 6.251-.286.638-.585 1.231-.882 1.783 4.066-1.348 6.502-5.334 6.874-9.439h-4.077z"/>
                                        </svg>
                                    </div>
								<?php endif; ?>
							<?php endif; ?>
                        </a>
                    </div>
				<?php endforeach; ?>

				<?php if ( $layoutType === 'grid' ): ?>
                    </div>
				<?php endif; ?>

			<?php endforeach; ?>
        </div>

		<?php if ( ! empty( $pages ) && count( $pages ) !== 1 ): ?>
            <div class="totalcontest-pagination">
				<?php if ( $previousPage['active'] ): ?>
                    <a class="totalcontest-pagination-item totalcontest-pagination-previous"
                       href="<?php echo esc_attr( $previousPage['url'] ); ?>"
                       totalcontest-ajax-url="<?php echo esc_attr( $previousPage['ajax'] ); ?>"><?php echo $previousPage['label']; ?></a>
				<?php else: ?>
                    <span class="totalcontest-pagination-item totalcontest-pagination-item-disabled totalcontest-pagination-previous"><?php echo $previousPage['label']; ?></span>
				<?php endif; ?>
                <select class="totalcontest-pagination-item">
					<?php foreach ( $pages as $page ): ?>
                        <option href="<?php echo esc_attr( $page['url'] ); ?>"
                                totalcontest-ajax-url="<?php echo esc_attr( $page['ajax'] ); ?>" <?php selected( $page['active'] ); ?>><?php echo $page['label']; ?></option>
					<?php endforeach; ?>
                </select>
				<?php foreach ( $pages as $page ): ?>
					<?php if ( $page['active'] ): ?>
                        <span class="totalcontest-pagination-item totalcontest-pagination-item-active"><?php echo $page['label']; ?></span>
					<?php else: ?>
                        <a class="totalcontest-pagination-item" href="<?php echo esc_attr( $page['url'] ); ?>"
                           totalcontest-ajax-url="<?php echo esc_attr( $page['ajax'] ); ?>"><?php echo $page['label']; ?></a>
					<?php endif; ?>
				<?php endforeach; ?>

				<?php if ( $nextPage['active'] ): ?>
                    <a class="totalcontest-pagination-item totalcontest-pagination-next"
                       href="<?php echo esc_attr( $nextPage['url'] ); ?>"
                       totalcontest-ajax-url="<?php echo esc_attr( $nextPage['ajax'] ); ?>"><?php echo $nextPage['label']; ?></a>
				<?php else: ?>
                    <span class="totalcontest-pagination-item totalcontest-pagination-item-disabled totalcontest-pagination-next"><?php echo $nextPage['label']; ?></span>
				<?php endif; ?>
            </div>
		<?php endif; ?>
    </div>
<?php
include $this->getPath( 'views/shared/footer.php' );
