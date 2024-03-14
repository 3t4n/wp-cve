<?php
if ( ! class_exists( 'wcpdf_WC_Piva_Cf_Invoice_Ita' )) :

	class wcpdf_WC_Piva_Cf_Invoice_Ita{
		private $parent;
		public function __construct($parent) {
			$this->parent = $parent;
			$this->settings = $parent->settings;
			//ACTION
			add_action('admin_head', array($this, 'custom_admin_script'),5);
			add_action('woocommerce_payment_complete', array($this, 'generate_invoice_number'),5);
			add_action('woocommerce_order_status_completed', array($this, 'generate_invoice_number'),5);
			add_action('woocommerce_order_status_processing', array($this, 'generate_invoice_number'),5);
			add_action('wpo_wcpdf_process_template_order' , array( $this, 'wcpdf_process_template_order'), 1,2);

			//FILTER
			add_filter( 'wpo_wcpdf_meta_box_actions' , array( $this, 'wcpdf_meta_box_actions') );
			add_filter( 'wpo_wcpdf_listing_actions' , array( $this, 'wcpdf_listing_actions'), 10, 2 );
			
			add_filter( 'wpo_wcpdf_invoice_title' , array( $this, 'wcpdf_invoice_title'), 20);
			add_filter( 'wpo_wcpdf_invoice_number_label' , array( $this, 'wcpdf_invoice_number_label'), 20,2);
			add_filter( 'wpo_wcpdf_invoice_date_label' , array( $this, 'wcpdf_invoice_date_label'), 20,2);
			add_filter( 'wpo_wcpdf_custom_email_condition' , array( $this, 'wcpdf_custom_email_condition'), 20,3);
			add_filter( 'wpo_wcpdf_myaccount_actions', array( $this, 'wcpdf_my_account'), 10, 2 );
			add_filter( 'wpo_wcpdf_template_file', array( $this, 'wcpdf_template_file'), 20, 3 );
			add_filter( 'wpo_wcpdf_filename', array( $this, 'wcpdf_filename'), 20, 3 );
			
			add_filter( 'option_wpo_wcpdf_general_settings', array( $this, 'custom_wcpdf_settings'), 20, 1 );
			add_filter( 'wpo_wcpdf_myaccount_allowed_order_statuses', array( $this, 'wcpdf_myaccount_allowed_order_statuses'), 20, 1 );
		}
		
		/**
		 * CUSTOM ADMIN SCRIPT
		 * @access public
		 * @return void
		 */
		public function custom_admin_script(){
			?>
			<script>
                jQuery(function($){
                    $('a.wpo_wcpdf.receipt,a.wpo_wcpdf.invoice').on('click',function(event){
                        if($(this).parents('td.order_actions').find('a.button.complete').length){
                            var label = ($(this).hasClass('receipt')) ? 'ricevuta':'fattura';
                            if(confirm('ATTENZIONE! L\'ordine non è completato, verrà generato il numero di '+label+' (se non esistente). Sei sicuro di voler procedere?') == false){
                                event.preventDefault();
                                event.stopPropagation();
                                return false;
                            }
                        }
                    })
                })
			</script>
			<?php
		}
		/**
		 * CUSTOM SETTINGS - set my_account_buttons to available.
		 * @access public
		 * @param $option
		 * @return void
		 */
		public function custom_wcpdf_settings($option){
			if (isset($option['my_account_buttons'])) {
				$option['my_account_buttons'] = 'available';
			}
			return $option;
		}
		/**
		 * Change order statuses on wcpdf plugin for download pdf
		 *
		 * @access public
		 * @return array
		 */
		public function wcpdf_myaccount_allowed_order_statuses() {
			return array('completed','refunded');
		}

		/**
		 * GET NEXT RECEIPT NUMBER
		 * @access public
		 * @return void
		 */
		public function get_next_receipt_number(){
			global $wpdb;
			return $wpdb->get_var( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", $this->parent->settings->wc_cfpiva_next_receipt_number )) ;
		}
		/**
		 * UPDATE NEXT RECEIPT NUMBER
		 * @access public
		 * @return void
		 */
		public function update_next_receipt_number($next_receipt_number){
			return update_option( $this->parent->settings->wc_cfpiva_next_receipt_number, $next_receipt_number );
		}
		/**
		 * Format invoice number
		 * @access public
		 *
		 * @param string $invoice_prefix
		 * @param int $invoice_number
		 * @param int $padding
		 *
		 * @return string
		 */
		public function format_receipt_number($invoice_prefix = '', $invoice_number, $padding = 0) {
			// Padding - minimum of 3 for safety
			if ( ctype_digit( (string) $padding ) && $padding > 3 ) {
				$invoice_number = sprintf( '%0' . $padding . 'd', $invoice_number );
			}
			return $invoice_prefix . $invoice_number;
		}
		/**
		 * Set invoice number and date
		 * @access public
		 * @param int $order_id
		 * @return void
		 */
		public function generate_invoice_number($order_id){
		    if ( ! $order_id ) {
				return;
			}
			//generate invoice number woocommerce-pdf-invoices-packing-slips
			$order = wc_get_order($order_id);
			// FIX WOO 3 - wcpdf_invoice_number was called incorrectly. Order properties should not be accessed directly
			//$wcpdfin = get_post_meta( $order_id, '_wcpdf_invoice_number', true );
			// WC > 3
			$wcpdfin = $order->get_meta('_wcpdf_invoice_number', true);
			
			//GENERATE RECEIPT NUMBER ONLY IF IT DOESN'T EXISTS
			if(!$wcpdfin){

				// TODO AVOID INVOICE NUMBERING FOR ORDER WITH TOTAL TO 0
				if(!$this->parent->set_receipt_for_zero_order){
					if($order->get_total() <= 0) return;
				}
								
				if($order->get_meta('_billing_invoice_type') == 'receipt'){
					//FIX generate number when coloumn invoice number is visible in admin
					if($order->status == 'on-hold' && !isset($_GET['document_type'])){
						return;
					}
					if($order->status == 'cancelled' && !isset($_GET['document_type'])){
						return;
					}
					if($this->parent->receipt_status == 'processing'){
						if($order->get_status() != "processing" && $_GET["action"] != "generate_wpo_wcpdf")
							return;
					}elseif($this->parent->receipt_status == 'completed'){
						if($order->get_status() != "completed" && $_GET["action"] != "generate_wpo_wcpdf")
							return;
					}
					
                    //GET RECEIPT SEQUENTIAL NUMBER. DIRECT QUERY TO AVOID CACHE
                    $receipt_number = apply_filters( 'wcpicfi_next_receipt_number', $this->get_next_receipt_number(), $order_id );
                    $receipt_prefix = $this->parent->receipt_prefix;
                    // variabili custom
                    $orderyear = $order->get_date_created()->format ('Y');
                    $receipt_prefix = str_replace("[order_year]", $orderyear, $receipt_prefix );
					$receipt_number_padding = $this->parent->receipt_number_padding;
					$receipt_date = current_time( 'mysql' );
                    if(empty($receipt_number))
                        $receipt_number = 1;

					$formatted_number = $this->format_receipt_number($receipt_prefix,$receipt_number, $receipt_number_padding);
					//WC < 3
					//update_post_meta( $order_id, '_wcpdf_invoice_number', $formatted_number );
					//WC > 3
					$order->update_meta_data('_wcpdf_invoice_number', $formatted_number );
					//WC < 3
					//update_post_meta( $order_id, '_wcpdf_invoice_date', strtotime($receipt_date) );
					//WC > 3
					$order->update_meta_data('_wcpdf_invoice_date', strtotime($receipt_date) );
					//WC < 3
					//update_post_meta( $order_id, '_wcpdf_invoice_date_formatted', $receipt_date );
					//WC > 3
					$order->update_meta_data('_wcpdf_invoice_date_formatted', $receipt_date );
					//WC < 3
					/*update_post_meta( $order_id, '_wcpdf_invoice_number_data', array(
						'number' => $receipt_number,
						'formatted_number' => $formatted_number,
						'prefix' => $receipt_prefix,
						'suffix' => '',
						'document_type' => 'receipt',
						'order_id' => $order_id,
						'padding' => $receipt_number_padding
					) );*/
					//WC > 3
					$order->update_meta_data('_wcpdf_invoice_number_data', array(
						'number' => $receipt_number,
						'formatted_number' => $formatted_number,
						'prefix' => $receipt_prefix,
						'suffix' => '',
						'document_type' => 'receipt',
						'order_id' => $order_id,
						'padding' => $receipt_number_padding
					) );
					$order->save();
					
					$this->update_next_receipt_number($receipt_number+1);
					// BUG FIXING 16/11/2017 FIX NUMERAZIONE E MAIL RICEVUTA CON NUMERO DELLA FATTURA
					$document = wcpdf_get_document( 'receipt', $order, true );
                    //INCREMENT VALUE
                   
                    
				}else{
					
					$document = wcpdf_get_document( 'invoice', $order, true );
				}
			}
		}

		/**
		 * MODIFY LINK ON META BOX ON SINGLE ORDER
		 * @param $meta_actions
		 *
		 * @return array
		 */
		public function wcpdf_meta_box_actions( $meta_actions ) {
			global $post_id;
			$invoicetype = get_post_meta($post_id,"_billing_invoice_type",true);
			if($invoicetype == "receipt") {
				$meta_actions['invoice']['alt'] = __( 'PDF Receipt', WCPIVACF_IT_DOMAIN );
				$meta_actions['invoice']['title'] = __( 'PDF Receipt', WCPIVACF_IT_DOMAIN );
			}
			return $meta_actions;
		}

		/**
		 * MODIFY LINK ON ADMIN ORDER LISTS
		 * @param $listing_actions
		 * @param $order
		 *
		 * @return array
		 */
		public function wcpdf_listing_actions( $listing_actions, $order) {
			switch($order->get_meta('_billing_invoice_type')){
				case 'receipt':
					$listing_actions['invoice']['img'] = plugins_url() . '/woo-piva-codice-fiscale-e-fattura-pdf-per-italia/images/receipt.png';
					$listing_actions['invoice']['alt'] = __( 'PDF Receipt', WCPIVACF_IT_DOMAIN );
					break;
				case 'professionist_invoice':
					$listing_actions['invoice']['img'] = plugins_url() . '/woo-piva-codice-fiscale-e-fattura-pdf-per-italia/images/professionist_invoice.png';
					break;
				case 'private_invoice':
					$listing_actions['invoice']['img'] = plugins_url() . '/woo-piva-codice-fiscale-e-fattura-pdf-per-italia/images/private_invoice.png';
					break;
			}
			return $listing_actions;
		}

		public function wcpdf_bulk_actions( $bulk_actions) {
			$bulk_actions['receipt'] = __( 'PDF Receipts', WCPIVACF_IT_DOMAIN );
			return $bulk_actions;
		}

		/**
         * Set invoice number on create pdf document
		 * @param $template_type
		 * @param $order_id
		 */
		public function wcpdf_process_template_order($template_type, $order_id) {
			if(get_post_meta($order_id,'_billing_invoice_type',true) == 'receipt')
			    $this->generate_invoice_number($order_id);
		}


		//TODO: da controllare se questa funzione ha senso. se la tipologia è una di quelle allora return. Il resto quindi non verrà mai eseguito probabilmente
		public function wcpdf_process_order_ids( $order_ids, $template_type) {
			$oids = array();
			if( in_array( $template_type, array('invoice', 'receipt','professionist_invoice','private_invoice','packing-slip') ) ) return $order_ids;

			foreach ($order_ids as $order_id) {
				$invoicetype = get_post_meta($order_id,"_billing_invoice_type",true);
				if((empty($invoicetype) && in_array( $template_type, array('invoice', 'professionist_invoice','private_invoice')) ) || ($invoicetype == $template_type)) $oids[] = $order_id;
			}
			return $oids;
		}

		/**
         * Filter invoice title label
		 * @param $label
         * @return string
		 */
		public function wcpdf_invoice_title($label) {
		    if(isset($_GET['order_ids'])){
		        $order_id = explode('x',$_GET['order_ids']);
                if(count($order_id) == 1){
                    $order_id = reset($order_id);
	                if(get_post_meta($order_id,'_billing_invoice_type',true) == 'receipt')
		                $label = __( 'Receipt', WCPIVACF_IT_DOMAIN );
                }
            }
			return $label;
		}
		/**
         * Filter invoice number label
		 * @param $label
		 * @param object $order
		 * @return string
		 */
		public function wcpdf_invoice_number_label($label, $order) {
			if($order->get_meta('_billing_invoice_type') == 'receipt')
				$label = __( 'Receipt number:', WCPIVACF_IT_DOMAIN );
			return $label;
		}
		/**
         * Filter invoice date label
		 * @param $label
		 * @param object $order
		 * @return string
		 */
		public function wcpdf_invoice_date_label($label, $order) {
			if($order->get_meta('_billing_invoice_type') == 'receipt')
				$label = __( 'Receipt date:', WCPIVACF_IT_DOMAIN );
			return $label;
		}

		public function wcpdf_custom_email_condition($flag, $order, $status) {
			return (in_array( $order->get_meta('_billing_invoice_type'), array('invoice', 'professionist_invoice','private_invoice'))) ? true : false;
		}

		/**
		 * ADD receipt data to my account order actions
		 * @param $actions
		 * @param $order
		 *
		 * @return mixed
		 */
		public function wcpdf_my_account( $actions, $order ) {
			if ( isset($actions['invoice']) && $order->get_meta('_billing_invoice_type') == 'receipt') {
				$actions['invoice']['name'] = __( 'Download Receipt (PDF)', WCPIVACF_IT_DOMAIN );
			}
			return $actions;
		}

		/**
         * Custom template path
		 * @param $template
		 * @param $type
		 * @param $order
		 *
		 * @return string
		 */
		public function wcpdf_template_file( $template, $type, $order ) {
			if ( $order->get_meta('_billing_invoice_type') == 'receipt') {
			    global $wcpivacf_IT;
				$o_path = pathinfo($template);
				$n_path = $wcpivacf_IT->plugin_path . 'templates/pdf/Simple/';
				$file_path = $n_path.$o_path['basename'];
				if( file_exists( $file_path ) ) {
					$template = $file_path;
				}
			}
			return $template;
		}
		/**
         * Custom filename
		 * @param $filename
		 * @param $type
		 * @param $order_ids
		 *
		 * @return string
		 */
		public function wcpdf_filename( $filename, $type, $order_ids ) {
			$order_count = isset($order_ids) ? count($order_ids) : 1;
            if($order_count == 1){
	            $order_id = reset($order_ids);
	            if(get_post_meta($order_id,'_billing_invoice_type',true) == 'receipt'){
		            $name = __( 'receipt', WCPIVACF_IT_DOMAIN);
		            $old_name = explode('-',$filename);
		            $old_name[0] = $name;
		            $filename = implode('-',$old_name);
                }
            }
			return $filename;
		}

		public function wcpdf_attach_receipt( $documents ) {
			global $wpo_wcpdf;
			if ( $wpo_wcpdf->export->order->billing_invoice_type == 'receipt') {
				$documents['receipt'] = $documents['invoice'];
				unset( $documents['invoice'] );
			}
		}

	}
endif;