<?php
include $this->getPath( 'views/shared/header.php' );
?>
    <div class="totalcontest-page-content totalcontest-page-content-thank-you">
        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
            <path d="M12 0c6.623 0 12 5.377 12 12s-5.377 12-12 12-12-5.377-12-12 5.377-12 12-12zm0 1c6.071 0 11 4.929 11 11s-4.929 11-11 11-11-4.929-11-11 4.929-11 11-11zm7 7.457l-9.005 9.565-4.995-5.865.761-.649 4.271 5.016 8.24-8.752.728.685z"/>
        </svg>

		<?php echo empty( $content ) ? esc_html__( 'Thank you!', 'totalcontest' ) : $content; ?>
		<?php if ( $contest->getMenuVisibility() && $contest->getMenuItemVisibility( 'submissions' ) ): ?>
            <a class="totalcontest-button" href="<?php echo esc_attr( $menuItems['submissions']['url'] ); ?>"><?php  esc_html_e( 'Browse submissions', 'totalcontest' ); ?></a>
		<?php endif; ?>
    </div>
<?php
include $this->getPath( 'views/shared/footer.php' );
