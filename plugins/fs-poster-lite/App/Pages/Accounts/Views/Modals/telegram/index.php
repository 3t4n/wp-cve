<?php

namespace FSPoster\App\Pages\Accounts\Views;

use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;

defined( 'ABSPATH' ) or exit;
?>

<div>
	<?php foreach ( $fsp_params[ 'accounts_list' ] as $account_info )
	{
		$node_list = $account_info[ 'node_list' ];
		$username  = get_userdata( $account_info[ 'user_id' ] ) !== FALSE ? get_userdata( $account_info[ 'user_id' ] )->nickname : esc_html__( 'deleted user', 'fs-poster' ); ?>
		<div class="fsp-account">
			<div class="fsp-card fsp-account-item" data-id="<?php echo esc_html( $account_info[ 'id' ] ); ?>" data-type="account" data-active="<?php echo esc_html( $account_info[ 'is_active' ] ); ?>" data-hidden="<?php echo esc_html( $account_info[ 'is_hidden' ] ); ?>">
				<div class="fsp-account-inline">
					<?php echo ! empty( $node_list ) ? '<div class="fsp-account-caret fsp-is-rotated"><i class="fas fa-angle-up"></i></div>' : ''; ?>
					<div class="fsp-account-image">
						<img src="<?php echo Helper::profilePic( $account_info ); ?>" onerror="FSPoster.no_photo( this );">
					</div>
					<div class="fsp-account-name">
						<?php echo esc_html( $account_info[ 'name' ] ); ?>
						<span><?php echo esc_html( $account_info[ 'chats' ] ); ?>&nbsp;<?php echo esc_html__( 'chats', 'fs-poster' ); ?></span>
					</div>
					<?php if ( $account_info[ 'status' ] !== 'error' ) { ?>
						<button type="button" class="fsp-button" data-load-modal="telegram_add_chat" data-parameter-account_id="<?php echo (int) $account_info[ 'id' ] ?>"><?php echo esc_html__( 'ADD A CHAT', 'fs-poster' ); ?></button>
					<?php } ?>
				</div>
				<div class="fsp-account-inline fsp-is-buttons-container">
					<div class="fsp-account-is-public fsp-tooltip <?php echo ! $account_info[ 'is_public' ] ? 'fsp-hide' : ''; ?>" data-title="<?php echo ! empty( [ $username ] ) ? esc_html__( vsprintf( 'It\'s public for all the users by %s.', [ $username ] ) ) : esc_html__( 'It\'s public for all the users by %s.', 'fs-poster' ); ?>">
						<i class="far fa-star"></i>
					</div>
					<?php if ( ! empty( $account_info[ 'proxy' ] ) ) { ?>
						<div class="fsp-account-proxy fsp-tooltip" data-title="<?php echo esc_html__( 'Proxy', 'fs-poster' ); ?>: <?php echo esc_html( $account_info[ 'proxy' ] ); ?>">
							<i class="fas fa-globe"></i>
						</div>
					<?php } ?>
					<a class="fsp-account-link fsp-tooltip" href="<?php echo Helper::profileLink( $account_info ); ?>" data-title="<?php echo esc_html__( 'Profile link', 'fs-poster' ); ?>" target="_blank">
						<i class="fas fa-external-link-alt"></i>
					</a>
					<div class="fsp-account-more">
						<i class="fas fa-ellipsis-h"></i>
					</div>
				</div>
				<div class="fsp-account-inline fsp-is-select-container">
					<input type="checkbox" class="fsp-form-checkbox fsp-account-selectbox" data-id="<?php echo esc_html( $account_info[ 'id' ] ); ?>" data-type="account">
				</div>
			</div>
			<div class="fsp-account-nodes-container">
				<div class="fsp-account-nodes">
					<?php foreach ( $node_list as $node_info ) { ?>
						<div class="fsp-card fsp-account-item" data-name="<?php echo esc_html( $node_info[ 'name' ] ); ?>" data-id="<?php echo esc_html( $node_info[ 'id' ] ); ?>" data-type="community" data-active="<?php echo esc_html( $node_info[ 'is_active' ] ); ?>" data-hidden="<?php echo esc_html( $node_info[ 'is_hidden' ] ); ?>"">
							<div class="fsp-account-inline">
								<div class="fsp-account-image">
									<img src="<?php echo Helper::profilePic( $node_info ); ?>" onerror="FSPoster.no_photo( this );">
								</div>
								<div class="fsp-account-name">
									<?php echo esc_html( $node_info[ 'name' ] ); ?>
									<span><?php echo ucfirst( esc_html( empty( $node_info[ 'category' ] ) ? 'chat' : $node_info[ 'category' ] ) ); ?></span>
								</div>
							</div>
							<div class="fsp-account-inline fsp-is-buttons-container">
								<div class="fsp-account-is-public fsp-tooltip <?php echo ! $node_info[ 'is_public' ] ? 'fsp-hide' : ''; ?>" data-title="<?php echo ! empty( [ $username ] ) ? esc_html__( vsprintf( 'It\'s public for all the users by %s.', [ $username ] ) ) : esc_html__( 'It\'s public for all the users by %s.', 'fs-poster' ); ?>">
									<i class="far fa-star"></i>
								</div>
								<div class="fsp-account-checkbox">
									<i class="<?php echo empty( $node_info[ 'is_active' ] ) ? 'far fa-check-square' : 'fas fa-check-square fsp-is-checked'; ?>"></i>
								</div>
								<a class="fsp-account-link fsp-tooltip" href="<?php echo Helper::profileLink( $node_info ); ?>" data-title="<?php echo esc_html__( 'Profile link', 'fs-poster' ); ?>" target="_blank">
									<i class="fas fa-external-link-alt"></i>
								</a>
								<div class="fsp-account-more">
									<i class="fas fa-ellipsis-h"></i>
								</div>
							</div>
							<div class="fsp-account-inline fsp-is-select-container">
								<input type="checkbox" class="fsp-form-checkbox fsp-account-selectbox" data-id="<?php echo esc_html( $node_info[ 'id' ] ); ?>" data-type="node">
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	<?php } ?>

	<div class="fsp-card fsp-emptiness <?php echo( empty( $fsp_params[ 'accounts_list' ] ) && empty( $fsp_params[ 'public_communities' ] ) ? '' : 'fsp-hide' ); ?>">
		<div class="fsp-emptiness-image">
			<img src="<?php echo Pages::asset( 'Base', 'img/empty.svg' ); ?>">
		</div>
		<div class="fsp-emptiness-text">
			<?php echo  ! empty( [ $fsp_params[ 'err_text' ] ] ) ? esc_html__( vsprintf( 'No %s found!', [ $fsp_params[ 'err_text' ] ] ), 'fs-poster' ) : esc_html__( 'No %s found!', 'fs-poster' ); ?>
		</div>
		<div class="fsp-emptiness-button">
			<button class="fsp-button fsp-accounts-add-button">
				<i class="fas fa-plus"></i>
				<span><?php echo esc_html( $fsp_params[ 'button_text' ] ); ?></span>
			</button>
		</div>
	</div>
</div>

<?php
$count = count( $fsp_params[ 'accounts_list' ] );
?>

<script>
	FSPObject.modalURL = 'add_telegram_bot';
	FSPObject.accountsCount = <?php echo esc_html( $count ); ?>;
</script>