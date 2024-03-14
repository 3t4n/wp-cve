<div class="wrap about-wrap">
    <?php list( $display_version ) = explode( '-', wphr_get_version() ); ?>

    <h1><?php printf( esc_html__( 'Welcome to WPHR Manager', 'wphr' ) ); ?></h1>

    <div class="about-text">
        <?php printf( esc_html__( 'Thank you for installing WPHR Manager %s!', 'wphr' ), $display_version ); ?>
    </div>

    <h2><?php _e( 'Getting Started', 'wphr' ); ?></h2>

    <ol>
        <li><?php printf( __( 'Setup %s', 'wphr' ), sprintf( '<a target="_blank" href="' . admin_url( 'admin.php?page=wphr-company' ) . '">%s</a>', __( 'Company Information', 'wphr' ) ) ); ?></li>
        <li><?php printf( __( 'Create %s', 'wphr' ), sprintf( '<a target="_blank" href="' . admin_url( 'admin.php?page=wphr-hr-depts' ) . '">%s</a>', __( 'Departments', 'wphr' ) ) ); ?></li>
        <li><?php printf( __( 'Setup %s', 'wphr' ), sprintf( '<a target="_blank" href="' . admin_url( 'admin.php?page=wphr-hr-designation' ) . '">%s</a>', __( 'Roles', 'wphr' ) ) ); ?></li>
        <li><?php printf( __( 'Create %s', 'wphr' ), sprintf( '<a target="_blank" href="' . admin_url( 'admin.php?page=wphr-hr-employee' ) . '">%s</a>', __( 'Employees', 'wphr' ) ) ); ?></li>
    </ol>

    <p>&nbsp;</p>

    <a class="button button-primary button-large" href="<?php echo admin_url( 'admin.php?page=wphr-hr' ); ?>"><?php _e( 'Go to HR Dashboard &rarr;', 'wphr' ); ?></a>
</div>
