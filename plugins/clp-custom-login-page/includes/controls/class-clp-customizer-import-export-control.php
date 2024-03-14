<?php
	/**
	 * Import / Export / Reset functions for CLP Customizer
     * @author chozze
	 * @version 1.0.0
	 */
class CLP_Customizer_Import_Export_Control extends \WP_Customize_Control {
	
    public $type = 'import_export';
    
	public function enqueue() {
        wp_enqueue_script( 'customizer-clp-import_export', CLP_PLUGIN_PATH . 'assets/js/customizer-clp-import-export.js', array('jquery'), CLP_VERSION, true );
	}


	public function render_content() {

	    ?>

        <label class="clp-customizer-btn" id="clp-customizer-import">
            <span style="display:inline-block"><?php echo _e( 'Import Settings', 'clp-custom-login-page' ); ?></span>
            <input type="file" name="clp-import-json" id="clp-import-json" accept=".json" data-nonce="<?php echo wp_create_nonce( 'clp-import-settings' );?>" style="display:none" />
        </label>
        <br>
        <br>

        <button 
            id="clp-customizer-export" 
            class="clp-customizer-btn" 
            data-nonce="<?php echo wp_create_nonce( 'clp-export-settings' );?>">
            <?php echo _e( 'Export Settings', 'clp-custom-login-page' ); ?>
        </button>

        <br>
        <br>

        <button 
            id="clp-customizer-reset" 
            class="clp-customizer-btn btn-warning" 
            data-nonce="<?php echo wp_create_nonce( 'clp-reset-settings' );?>">
            <?php echo _e( 'Reset Settings', 'clp-custom-login-page' ); ?>
        </button>
        
		<?php
	}

}