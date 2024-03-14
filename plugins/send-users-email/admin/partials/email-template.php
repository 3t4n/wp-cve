<?php
/**
 * @var $logo
 * @var $styles
 * @var $title
 * @var $tagline
 * @var $email_body
 * @var $footer
 * @var $social
 */
?>
<!DOCTYPE html>
<html lang="en" dir="auto">
<head>
    <title><?php bloginfo( 'name' ); ?></title>
    <meta charset="UTF-8"/>

    <style>
        .sue-logo td {
            text-align: center;
        }

        .sue-logo img {
            max-height: 75px;
        }

        .sue-title {
            text-align: center;
        }

        .sue-tagline {
            text-align: center;
        }

        .sue-footer td {
            text-align: center;
            padding-top: 30px;
        }

        .sue-footer-social td {
            text-align: center;
            padding-top: 30px;
        }

        .aligncenter {
            display: block;
            margin-left: auto !important;
            margin-right: auto !important;
        }

        .alignleft {
            float: left;
            margin-inline-start: 0;
            margin-inline-end: 2em;
        }

        .alignright {
            float: right;
            margin-inline-start: 2em;
            margin-inline-end: 0;
        }
    </style>

	<?php if ( $styles ): ?>
        <style>
            <?php echo stripslashes_deep( esc_html( $styles ) ); ?>
        </style>
	<?php endif; ?>

</head>
<body>

<table class="sue-main-table">
	<?php if ( esc_url_raw( $logo ) ): ?>
        <tr class="sue-logo">
            <td>
                <img src="<?php echo esc_url_raw( $logo ); ?>" alt="<?php bloginfo( 'name' ); ?>"/>
            </td>
        </tr>
	<?php endif; ?>

	<?php if ( ( $title ) || $tagline ): ?>
        <tr class="sue-title-tagline">
            <td>
				<?php if ( $title ): ?>
                    <h2 class="sue-title"><?php echo stripslashes_deep( esc_html( $title ) ); ?></h2>
				<?php endif; ?>

				<?php if ( $tagline ): ?>
                    <h5 class="sue-tagline"><?php echo stripslashes_deep( esc_html( $tagline ) ); ?></h5>
				<?php endif; ?>
            </td>
        </tr>
	<?php endif; ?>

    <tr class="sue-email-body">
        <td>
			<?php echo wp_kses_post( stripslashes_deep( $email_body ) ); ?>
        </td>
    </tr>

	<?php if ( $footer ): ?>
        <tr class="sue-footer">
            <td>
				<?php echo stripslashes_deep( $footer ); ?>
            </td>
        </tr>
	<?php endif; ?>

	<?php if ( ! empty( $social ) ): ?>
        <tr class="sue-footer-social">
            <td>
				<?php foreach ( Send_Users_Email_Admin::$social as $platform ): ?>
					<?php if ( isset( $social[ $platform ] ) ): ?>
						<?php if ( ! empty( $social[ $platform ] ) ): ?>
                            <a href="<?php echo esc_url_raw( $social[ $platform ] ); ?>" style="text-decoration: none;">
                                <img src="<?php echo sue_get_asset_url( $platform . '.png' ); ?>"
                                     alt="<?php echo $platform; ?>" width="30"
                                     style="display:inline-block;border-width:0;max-width: 35px;">
                            </a>
						<?php endif; ?>
					<?php endif; ?>
				<?php endforeach; ?>
            </td>
        </tr>
	<?php endif; ?>
</table>

</body>
</html>
