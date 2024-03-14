<?php

if(!class_exists('EpekenCekResi')) {
	class EpekenCekResi extends WP_Widget{

 		function __construct() {
                        $widget_ops = array('description' => 'Tambahkan widget Epeken Cek Resi.' );
                        parent::__construct(false, __('Epeken Cek Resi', 'woocommerce'),$widget_ops);
                }

		   function widget($args, $instance) {
                        extract( $args );
                        $title = $instance['title'];
                        $placeholder = $instance['placeholder'];
                        $button = $instance['button'];
	        ?>
                        <?php echo $before_widget; ?>
                                <?php echo $before_title; ?>
                                        <?php echo $title; ?>
                                <?php echo $after_title; ?>
                                <form action="http://www.cekresi.com" method="GET" target="_blank">
                                        <input type="hidden" name="v" value="w1"/>
					<table width=100% style="border: 0">
					 <tr><td>
                                        <input type="text" name="noresi" placeholder="<?php echo $placeholder; ?>" style="font-size: 11px;"/>
					</td></tr>
					<tr><td style="padding: 0px;margin: 0px;">		
                          		<input type="submit" value="<?php echo $button; ?>" style = "margin-top: 5px; font-size: 90%;"/>
					</td></tr>
					</table>
                                </form>
                        <?php echo $after_widget; ?>
        	<?php
                }

		 function update($new_instance, $old_instance) {
                        return $new_instance;
                }
	
	function form($instance) {
                        global $wpdb;
                        $title = $instance['title'];
                        $placeholder = $instance['placeholder'];
                        $button = $instance['button'];
                        if(empty($placeholder))
                                $placeholder = 'Masukkan nomor resi JNE';
                        if(empty($button))
                                $button = 'Submit';
   	     ?>
                        <p>
                                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Judul:','woocommerce'); ?></label>
                                <input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
                        </p>
                        <p>
                                <label for="<?php echo $this->get_field_id('placeholder'); ?>"><?php _e('Placeholder:','woocommerce'); ?></label>
                                <input type="text" name="<?php echo $this->get_field_name('placeholder'); ?>" value="<?php echo $placeholder; ?>" class="widefat" id="<?php echo $this->get_field_id('placeholder'); ?>" />
                        </p>
                        <p>
                                <label for="<?php echo $this->get_field_id('placeholder'); ?>"><?php _e('Button Text:','woocommerce'); ?></label>
                                <input type="text" name="<?php echo $this->get_field_name('button'); ?>" value="<?php echo $button; ?>" class="widefat" id="<?php echo $this->get_field_id('button'); ?>" />
                        </p>
        <?php
                }
	} // End Class EpekenCekResi

	function register_cek_resi() {
                register_widget('EpekenCekResi');
        }
        add_action( 'widgets_init', 'register_cek_resi' );


}
?>
