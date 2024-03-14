<div class="fsp-box-info">
    <i class="fas fa-info-circle"></i><?php use FSPoster\App\Providers\Pages;
    echo esc_html__( 'The OPT code has been sent to your email address. Please copy and paste the code below.', 'fs-poster' ); ?>
</div>
<div class="fsp-box-logo">
    <img class="fsp-img-is-center" src="<?php echo Pages::asset( 'Base', 'img/logo_new.png' ); ?>">
</div>
<div id="fspInstallForm">
    <div class="fsp-form-group">
        <input autocomplete="off" id="fspOtpCode" class="fsp-form-input" placeholder="<?php echo esc_html__( 'Enter the code', 'fs-poster' ); ?>">
    </div>
    <div class="fsp-form-group">
        <button type="button" class="fsp-button" id="fspInstallBtn"><?php echo esc_html__( 'REGISTER & ACTIVATE', 'fs-poster' ); ?></button>
    </div>
</div>