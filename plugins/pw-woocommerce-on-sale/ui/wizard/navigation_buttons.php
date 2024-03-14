<?php if ( !defined( 'ABSPATH' ) ) { exit; } ?>

<?php
    global $pwos_step;
    global $pwos_last_step;
?>
<div class="pwos-wizard-button-container">
    <?php
        echo '<a href="#" onClick="if (confirm(\'Cancel the wizard?\\n\\nYour changes will not be saved.\')) { pwosWizardClose(); } return false;" class="pwos-wizard-cancel-button">Cancel</a>';

        if ( $pwos_step != $pwos_last_step ) {
            echo '<a href="#" onClick="pwosWizardLoadStep(' . ( $pwos_step + 1 ) . ', true); return false;" class="pwos-wizard-next-previous-button pwos-wizard-next-button">Next</a>';
        } else {
            echo '<a href="#" onClick="pwosWizardFinish(); return false;" class="pwos-wizard-next-previous-button pwos-wizard-finish-button">Finish</a>';
        }

        if ( $pwos_step > 1 ) {
            echo '<a href="#" onClick="pwosWizardLoadStep(' . ( $pwos_step - 1 ) . '); return false;" class="pwos-wizard-next-previous-button pwos-wizard-previous-button">Previous</a>';
        }    ?>
</div>
