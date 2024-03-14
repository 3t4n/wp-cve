<?php include $this->getPath( 'views/shared/header.php' ); ?>
    <div class="totalcontest-page-content <?php echo empty( $customPage['id'] ) ?: "totalcontest-page-content-{$customPage['id']}"; ?>">
		<?php do_action( 'totalcontest/actions/contest/before/page/' . $customPage['id'], $contest ); ?>
		<?php echo apply_filters( 'totalcontest/filters/contest/custom-page-content/' . $customPage['id'], empty( $customPage['content'] ) ? '' : $customPage['content'], $contest, $customPage ); ?>
		<?php do_action( 'totalcontest/actions/contest/after/page/' . $customPage['id'], $contest ); ?>
    </div>
<?php include $this->getPath( 'views/shared/footer.php' );
