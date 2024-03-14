<?php defined( 'ABSPATH' ) || die; ?>

<?php $this->template( 'card-fields' ); ?>

<?php $this->template( 'charge-amount-field' ); ?>

<?php $this->template( 'stripe/notice-templates/moto-incorrectly-enabled' ); ?>

<?php $this->template( 'stripe/notice-templates/auth-required-moto-disabled' ); ?>

<?php $this->template( 'stripe/notice-templates/auth-required-moto-enabled' ); ?>

<?php $this->template( 'stripe/notice-templates/partials/invoice-instructions' ); ?>
