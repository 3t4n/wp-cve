<?php

namespace FSPoster\App\Pages\Accounts\Views;

use FSPoster\App\Providers\Pages;

defined( 'ABSPATH' ) or exit;
?>

<div id="fspActivateMenu" class="fsp-dropdown">
	<div id="fspActivatesDiv">
		<?php if ( current_user_can( 'administrator' ) ) { ?>
			<div id="fspActivateForAll" class="fsp-dropdown-item"><?php echo esc_html__( 'Activate', 'fs-poster' ); ?></div>
		<?php } ?>
	</div>
	<div id="fspActivateConditionally" class="fsp-dropdown-item"><?php echo esc_html__( 'Activate (condition)', 'fs-poster' ); ?></div>
	<div id="fspDeactivatesDiv">
		<?php if ( current_user_can( 'administrator' ) ) { ?>
			<div id="fspDeactivateForAll" class="fsp-dropdown-item"><?php echo esc_html__( 'Deactivate', 'fs-poster' ); ?></div>
		<?php } ?>
	</div>
</div>
<div id="fspMoreMenu" class="fsp-dropdown">
	<div id="fspDelete" class="fsp-dropdown-item"><?php echo esc_html__( 'Delete', 'fs-poster' ); ?></div>
	<div class="fsp-dropdown-item fspjs-hide-account" data-type="hide"><?php echo esc_html__( 'Hide', 'fs-poster' ); ?></div>
	<div class="fsp-dropdown-item fspjs-hide-account" data-type="unhide"><?php echo esc_html__( 'Unhide', 'fs-poster' ); ?></div>
</div>

<div class="fsp-row">
	<div class="fsp-col-12 fsp-title">
		<div class="fsp-title-text">
			<?php echo esc_html__( 'Accounts', 'fs-poster' ); ?>
			<span class="fsp-title-count"><?php echo esc_html( $fsp_params[ 'accounts_count' ][ 'total' ] ); ?></span>
		</div>
		<div class="fsp-title-button">
			<div class="fsp-form-input-has-icon fsp-node-search">
				<i class="fas fa-search"></i>
				<input id="fsp-node-search-input" autocomplete="off" class="fsp-form-input fsp-search-account" placeholder="Search">
			</div>
			<button class="fsp-button fsp-accounts-add-button">
				<i class="fas fa-plus"></i>
				<span><?php echo esc_html( $fsp_params[ 'button_text' ] ); ?></span>
			</button>
		</div>
	</div>

	<div class="fsp-col-12 fsp-row fsp-accounts-toolbar">
		<div class="fsp-layout-left fsp-col-12 fsp-col-md-5 fsp-col-lg-4">
			<div class="fsp-account-group-btns">
				<a class="active" href="?page=fs-poster-accounts">Social media</a>
				<a class="fsp-tab-title-crowned" href="#">Groups</a>
			</div>
		</div>

		<div class="fsp-layout-right fsp-col-12 fsp-col-md-7 fsp-col-lg-8">
			<div class="fsp-accounts-filter">
				<button id="fspCollapseAccounts" class="fsp-button fsp-is-info fsp-account-collapse">
					<i>
						<img src="<?php echo Pages::asset( 'Accounts', 'img/collapse.svg' ); ?>">
					</i>
					<span><?php echo esc_html__( 'COLLAPSE ALL', 'fs-poster' ); ?></span>
				</button>
				<div class="fsp-title-selector">
					<select id="fspAccountsFilterSelector" class="fsp-form-select">
						<option value="all" <?php echo $fsp_params[ 'filter' ] === 'all' ? 'selected' : ''; ?>><?php echo esc_html__( 'All accounts', 'fs-poster' ); ?></option>
						<option value="active" <?php echo $fsp_params[ 'filter' ] === 'active' ? 'selected' : ''; ?>><?php echo esc_html__( 'Active accounts', 'fs-poster' ); ?></option>
						<option value="inactive" <?php echo $fsp_params[ 'filter' ] === 'inactive' ? 'selected' : ''; ?>><?php echo esc_html__( 'Deactive accounts', 'fs-poster' ); ?></option>
						<option value="visible" <?php echo $fsp_params[ 'filter' ] === 'visible' ? 'selected' : ''; ?>><?php echo esc_html__( 'Visible accounts', 'fs-poster' ); ?></option>
						<option value="hidden" <?php echo $fsp_params[ 'filter' ] === 'hidden' ? 'selected' : ''; ?>><?php echo esc_html__( 'Hidden accounts', 'fs-poster' ); ?></option>
					</select>
				</div>
			</div>
		</div>
	</div>

	<div class="fsp-col-12 fsp-row">
		<div class="fsp-layout-left fsp-col-12 fsp-col-md-5 fsp-col-lg-4">
			<div class="fsp-card">
				<div class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'fb' ? 'fsp-is-active' : '' ); ?>" data-component="fb">
					<div class="fsp-tab-title">
						<i class="fab fa-facebook-f fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text">Facebook</span>
					</div>
					<div class="fsp-tab-badges <?php echo( $fsp_params[ 'accounts_count' ][ 'fb' ][ 'active' ] > 0 ? 'fsp-has-active-accounts' : '' ); ?>">
						<span class="fsp-tab-all"><?php echo esc_html( $fsp_params[ 'accounts_count' ][ 'fb' ][ 'total' ] ); ?></span>
					</div>
				</div>
				<div class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'linkedin' ? 'fsp-is-active' : '' ); ?>" data-component="linkedin">
					<div class="fsp-tab-title">
						<i class="fab fa-linkedin-in fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text">LinkedIn</span>
					</div>
					<div class="fsp-tab-badges <?php echo( $fsp_params[ 'accounts_count' ][ 'linkedin' ][ 'active' ] > 0 ? 'fsp-has-active-accounts' : '' ); ?>">
						<span class="fsp-tab-all"><?php echo esc_html( $fsp_params[ 'accounts_count' ][ 'linkedin' ][ 'total' ] ); ?></span>
					</div>
				</div>
				<div class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'vk' ? 'fsp-is-active' : '' ); ?>" data-component="vk">
					<div class="fsp-tab-title">
						<i class="fab fa-vk fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text">VK</span>
					</div>
					<div class="fsp-tab-badges <?php echo( $fsp_params[ 'accounts_count' ][ 'vk' ][ 'active' ] > 0 ? 'fsp-has-active-accounts' : '' ); ?>">
						<span class="fsp-tab-all"><?php echo esc_html( $fsp_params[ 'accounts_count' ][ 'vk' ][ 'total' ] ); ?></span>
					</div>
				</div>
				<div class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'reddit' ? 'fsp-is-active' : '' ); ?>" data-component="reddit">
					<div class="fsp-tab-title">
						<i class="fab fa-reddit-alien fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text">Reddit</span>
					</div>
					<div class="fsp-tab-badges <?php echo( $fsp_params[ 'accounts_count' ][ 'reddit' ][ 'active' ] > 0 ? 'fsp-has-active-accounts' : '' ); ?>">
						<span class="fsp-tab-all"><?php echo esc_html(  $fsp_params[ 'accounts_count' ][ 'reddit' ][ 'total' ] ); ?></span>
					</div>
				</div>
				<div class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'tumblr' ? 'fsp-is-active' : '' ); ?>" data-component="tumblr">
					<div class="fsp-tab-title">
						<i class="fab fa-tumblr fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text">Tumblr</span>
					</div>
					<div class="fsp-tab-badges <?php echo( $fsp_params[ 'accounts_count' ][ 'tumblr' ][ 'active' ] > 0 ? 'fsp-has-active-accounts' : '' ); ?>">
						<span class="fsp-tab-all"><?php echo esc_html( $fsp_params[ 'accounts_count' ][ 'tumblr' ][ 'total' ] ); ?></span>
					</div>
				</div>
				<div class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'ok' ? 'fsp-is-active' : '' ); ?>" data-component="ok">
					<div class="fsp-tab-title">
						<i class="fab fa-odnoklassniki fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text">Odnoklassniki</span>
					</div>
					<div class="fsp-tab-badges <?php echo( $fsp_params[ 'accounts_count' ][ 'ok' ][ 'active' ] > 0 ? 'fsp-has-active-accounts' : '' ); ?>">
						<span class="fsp-tab-all"><?php echo esc_html( $fsp_params[ 'accounts_count' ][ 'ok' ][ 'total' ] ); ?></span>
					</div>
				</div>
				<div class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'telegram' ? 'fsp-is-active' : '' ); ?>" data-component="telegram">
					<div class="fsp-tab-title">
						<i class="fab fa-telegram fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text">Telegram</span>
					</div>
					<div class="fsp-tab-badges <?php echo( $fsp_params[ 'accounts_count' ][ 'telegram' ][ 'active' ] > 0 ? 'fsp-has-active-accounts' : '' ); ?>">
						<span class="fsp-tab-all"><?php echo esc_html( $fsp_params[ 'accounts_count' ][ 'telegram' ][ 'total' ] ); ?></span>
					</div>
				</div>
				<div class="fsp-tab <?php echo( $fsp_params[ 'active_tab' ] === 'plurk' ? 'fsp-is-active' : '' ); ?>" data-component="plurk">
					<div class="fsp-tab-title">
						<i class="fas fa-parking fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text">Plurk</span>
					</div>
					<div class="fsp-tab-badges <?php echo( $fsp_params[ 'accounts_count' ][ 'plurk' ][ 'active' ] > 0 ? 'fsp-has-active-accounts' : '' ); ?>">
						<span class="fsp-tab-all"><?php echo esc_html( $fsp_params[ 'accounts_count' ][ 'plurk' ][ 'total' ] ); ?></span>
					</div>
				</div>
                <div class="fsp-tab fsp-require-premium">
                    <div class="fsp-tab-title">
                        <i class="fab fa-instagram fsp-tab-title-icon"></i>
                        <span class="fsp-tab-title-text fsp-tab-title-crowned">Instagram</span>
                    </div>
                    <div class="fsp-tab-badges">
                        <span class="fsp-tab-all">0</span>
                    </div>
                </div>
                <div class="fsp-tab  fsp-require-premium">
                    <div class="fsp-tab-title">
                        <i class="fab fa-pinterest-p fsp-tab-title-icon"></i>
                        <span class="fsp-tab-title-text fsp-tab-title-crowned">Pinterest</span>
                    </div>
                    <div class="fsp-tab-badges">
                        <span class="fsp-tab-all">0</span>
                    </div>
                </div>
				<div class="fsp-tab  fsp-require-premium">
					<div class="fsp-tab-title">
						<i class="fab fa-google fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text fsp-tab-title-crowned">Google My Business</span>
					</div>
					<div class="fsp-tab-badges">
						<span class="fsp-tab-all">0</span>
					</div>
				</div>
				<div class="fsp-tab  fsp-require-premium">
					<div class="fsp-tab-title">
						<i class="fab fa-blogger fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text fsp-tab-title-crowned">Blogger</span>
					</div>
					<div class="fsp-tab-badges">
						<span class="fsp-tab-all">0</span>
					</div>
				</div>
				<div class="fsp-tab  fsp-require-premium">
					<div class="fsp-tab-title">
						<i class="fab fa-medium-m fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text fsp-tab-title-crowned">Medium</span>
					</div>
					<div class="fsp-tab-badges">
						<span class="fsp-tab-all">0</span>
					</div>
				</div>
				<div class="fsp-tab  fsp-require-premium">
					<div class="fsp-tab-title">
						<i class="fab fa-wordpress-simple fsp-tab-title-icon"></i>
						<span class="fsp-tab-title-text fsp-tab-title-crowned">Wordpress</span>
					</div>
					<div class="fsp-tab-badges">
						<span class="fsp-tab-all">0</span>
					</div>
				</div>
			</div>
		</div>
		<div id="js-filter-mobile" class="fsp-accounts-filter-mobile"></div>
		<div id="fspComponent" class="fsp-layout-right fsp-col-12 fsp-col-md-7 fsp-col-lg-8"></div>
	</div>
</div>

<script>
	FSPObject.filter_by = '<?php echo esc_html( $fsp_params[ 'filter' ] ); ?>';
</script>