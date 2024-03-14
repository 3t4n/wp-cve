<?php if ( !defined( 'ABSPATH' ) ) { exit; } ?>
<?php

global $pw_on_sale;
global $pwos_last_step;
$pwos_last_step = 3;

for ( $step = 1; $step <= $pwos_last_step; $step++ ) {
    ?>
    <div id="pwos-wizard-step-<?php echo $step; ?>" class="pwos-wizard-step pwos-bordered-container pwos-hidden">
        <?php
            require( 'step' . absint( $step ) . '.php' );
        ?>
    </div>
    <?php
}

?>
<div id="pwos-wizard-step-saving" class="pwos-wizard-step pwos-bordered-container pwos-hidden">
    <div style="text-align: center;">
        <div class="pwos-heading">Saving</div>
        <img src="<?php echo $pw_on_sale->relative_url( '/assets/images/spinner-2x.gif' ); ?>" class="pwos-spinner">
    </div>
</div>

<script>
    var pwosLastStep = <?php echo $pwos_last_step; ?>;

    function pwosWizardValidateStep(step) {
        switch (step) {
            <?php
                for ( $step = 1; $step <= $pwos_last_step; $step++ ) {
                    ?>
                    case <?php echo $step; ?>:
                        if (!pwosWizardValidateStep<?php echo $step; ?>()) {
                            return false;
                        }
                    break;
                    <?php
                }
            ?>
        }
    }
</script>