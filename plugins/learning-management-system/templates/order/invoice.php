<?php
/**
 * Order Invoice PDF.
 *
 * @since 1.8.0
 */

defined( 'ABSPATH' ) || exit;


?>
<section class="masteriyo-invoice" style="background: #ffffff; padding: 60px;">
	<div class="container">
		<div class="masteriyo-invoice-header" style="width: 100%; margin-bottom: 32px;">
			<img style="float: left;" width="150px" height="27px" src="<?php echo esc_url( plugins_url( 'assets/img/masteriyo-logo-horizontal.png', MASTERIYO_PLUGIN_FILE ) ); ?>" alt="Masteriyo Logo">
			<div class="masteriyo-invoice-header--invoice-number" style="float: right; text-align: right; color: #383838; font-size: 14px; font-weight: 700; line-height: 24px;">
				<strong>
					<?php
					/* translators: %d is the order ID */
					echo esc_html( sprintf( __( '#Invoice: %d', 'masteriyo' ), $invoice_data['order_id'] ) );
					?>
				</strong>
			</div>
		</div>

		<div class="masteriyo-invoice-body" style="margin-bottom: 32px;">
			<div class="masteriyo-invoice-body--form-data" style="margin-bottom: 10px;">
				<div style="float: left; width: 180px; color: #222222; font-size: 14px; font-weight: 500; line-height: 24px;">
					<?php echo esc_html( __( 'Customer ID:', 'masteriyo' ) ); ?>
				</div>

				<div class="masteriyo-invoice-body--form-data__content" style="float: right; color: #383838; font-size: 14px; font-weight: 400; line-height: 24px;">	
					<?php
					/* translators: %d is the Customer ID */
					echo esc_html( sprintf( '%d', $invoice_data['customer_id'] ) );
					?>
				</div>
			</div>

			<div class="masteriyo-invoice-body--form-data" style="margin-bottom: 10px;">
				<div style="float: left; width: 180px; color: #222222; font-size: 14px; font-weight: 500; line-height: 24px;">
					<?php echo esc_html( __( 'First Name:', 'masteriyo' ) ); ?>
				</div>

				<div class="masteriyo-invoice-body--form-data__content" style="float: right; color: #383838; font-size: 14px; font-weight: 400; line-height: 24px;">	
					<?php
					/* translators: %s is the firstname */
					echo esc_html( sprintf( '%s', $invoice_data['first_name'] ) );
					?>
				</div>
			</div>

			<div class="masteriyo-invoice-body--form-data" style="margin-bottom: 10px;">
				<div style="float: left; width: 180px; color: #222222; font-size: 14px; font-weight: 500; line-height: 24px;">
					<?php echo esc_html( __( 'Last Name:', 'masteriyo' ) ); ?>
				</div>

				<div class="masteriyo-invoice-body--form-data__content" style="float: right; color: #383838; font-size: 14px; font-weight: 400; line-height: 24px;">	
					<?php
					/* translators: %s is the lastname */
					echo esc_html( sprintf( '%s', $invoice_data['last_name'] ) );
					?>
				</div>
			</div>

			<div class="masteriyo-invoice-body--form-data" style="margin-bottom: 10px;">
				<div style="float: left; width: 180px; color: #222222; font-size: 14px; font-weight: 500; line-height: 24px;">
					<?php echo esc_html( __( 'Company:', 'masteriyo' ) ); ?>
				</div>

				<div class="masteriyo-invoice-body--form-data__content" style="float: right; color: #383838; font-size: 14px; font-weight: 400; line-height: 24px;">	
						<?php
						/* translators: %s is the Company Name*/
						echo esc_html( sprintf( '%s', $invoice_data['company_name'] ) );
						?>
				</div>
			</div>

			<div class="masteriyo-invoice-body--form-data" style="margin-bottom: 10px;">
				<div style="float: left; width: 180px; color: #222222; font-size: 14px; font-weight: 500; line-height: 24px;">
					<?php echo esc_html( __( 'Address 1:', 'masteriyo' ) ); ?>
				</div>

				<div class="masteriyo-invoice-body--form-data__content" style="float: right; color: #383838; font-size: 14px; font-weight: 400; line-height: 24px;">	
						<?php
						/* translators: %s is the address */
						echo esc_html( sprintf( '%s', $invoice_data['address_one'] ) );
						?>
				</div>
			</div>

			<div class="masteriyo-invoice-body--form-data" style="margin-bottom: 10px;">
				<div style="float: left; width: 180px; color: #222222; font-size: 14px; font-weight: 500; line-height: 24px;">
					<?php echo esc_html( __( 'Address 2:', 'masteriyo' ) ); ?>
				</div>

				<div class="masteriyo-invoice-body--form-data__content" style="float: right; color: #383838; font-size: 14px; font-weight: 400; line-height: 24px;">	
						<?php
						/* translators: %s is the address */
						echo esc_html( sprintf( '%s', $invoice_data['address_two'] ) );
						?>
				</div>
			</div>

			<div class="masteriyo-invoice-body--form-data" style="margin-bottom: 10px;">
				<div style="float: left; width: 180px; color: #222222; font-size: 14px; font-weight: 500; line-height: 24px;">
					<?php echo esc_html( __( 'City:', 'masteriyo' ) ); ?>
				</div>

				<div class="masteriyo-invoice-body--form-data__content" style="float: right; color: #383838; font-size: 14px; font-weight: 400; line-height: 24px;">	
						<?php
						/* translators: %s is the city */
						echo esc_html( sprintf( '%s', $invoice_data['city_name'] ) );
						?>
				</div>
			</div>

			<div class="masteriyo-invoice-body--form-data" style="margin-bottom: 10px;">
				<div style="float: left; width: 180px; color: #222222; font-size: 14px; font-weight: 500; line-height: 24px;">
					<?php echo esc_html( __( 'PostCode:', 'masteriyo' ) ); ?>
				</div>

				<div class="masteriyo-invoice-body--form-data__content" style="float: right; color: #383838; font-size: 14px; font-weight: 400; line-height: 24px;">	
						<?php
						/* translators: %d is the postcode */
						echo esc_html( sprintf( '%s', $invoice_data['postcode'] ) );
						?>
				</div>
			</div>

			<div class="masteriyo-invoice-body--form-data" style="margin-bottom: 10px;">
				<div style="float: left; width: 180px; color: #222222; font-size: 14px; font-weight: 500; line-height: 24px;">
					<?php echo esc_html( __( 'Country:', 'masteriyo' ) ); ?>
				</div>

				<div class="masteriyo-invoice-body--form-data__content" style="float: right; color: #383838; font-size: 14px; font-weight: 400; line-height: 24px;">	
						<?php
						/* translators: %s is the country name */
						echo esc_html( sprintf( '%s', $invoice_data['country_name'] ) );
						?>
				</div>
			</div>

			<div class="masteriyo-invoice-body--form-data" style="margin-bottom: 10px;">
				<div style="float: left; width: 180px; color: #222222; font-size: 14px; font-weight: 500; line-height: 24px;">
					<?php echo esc_html( __( 'Phone:', 'masteriyo' ) ); ?>
				</div>

				<div class="masteriyo-invoice-body--form-data__content" style="float: right; color: #383838; font-size: 14px; font-weight: 400; line-height: 24px;">	
						<?php
						/* translators: %d is the phone */
						echo esc_html( sprintf( '%s', $invoice_data['phone'] ) );
						?>
				</div>
			</div>

			<div class="masteriyo-invoice-body--form-data" style="margin-bottom: 10px;">
				<div style="float: left; width: 180px; color: #222222; font-size: 14px; font-weight: 500; line-height: 24px;">
					<?php echo esc_html( __( 'State:', 'masteriyo' ) ); ?>
				</div>

				<div class="masteriyo-invoice-body--form-data__content" style="float: right; color: #383838; font-size: 14px; font-weight: 400; line-height: 24px;">	
						<?php
						/* translators: %s is the state */
						echo esc_html( sprintf( '%s', $invoice_data['state'] ) );
						?>
				</div>
			</div>

			<div class="masteriyo-invoice-body--form-data" style="margin-bottom: 10px;">
				<div style="float: left; width: 180px; color: #222222; font-size: 14px; font-weight: 500; line-height: 24px;">
					<?php echo esc_html( __( 'Transaction ID:', 'masteriyo' ) ); ?>
				</div>

				<div class="masteriyo-invoice-body--form-data__content" style="float: right; color: #383838; font-size: 14px; font-weight: 400; line-height: 24px;">	
						<?php
						/* translators: %d is the transaction id */
						echo esc_html( sprintf( '%s', $invoice_data['transaction_id'] ) );
						?>
				</div>
			</div>

			<div class="masteriyo-invoice-body--form-data" style="margin-bottom: 10px;">
				<div style="float: left; width: 180px; color: #222222; font-size: 14px; font-weight: 500; line-height: 24px;">
					<?php echo esc_html( __( 'Payment Method:', 'masteriyo' ) ); ?>
				</div>

				<div class="masteriyo-invoice-body--form-data__content" style="float: right; color: #383838; font-size: 14px; font-weight: 400; line-height: 24px;">	
						<?php
						/* translators: %s is the payment method */
						echo esc_html( sprintf( '%s', $invoice_data['payment_method'] ) );
						?>
				</div>
			</div>

			<div class="masteriyo-invoice-body--form-data" style="margin-bottom: 10px;">
				<div style="float: left; width: 180px; color: #222222; font-size: 14px; font-weight: 500; line-height: 24px;">
					<?php echo esc_html( __( 'Purchase Date:', 'masteriyo' ) ); ?>
				</div>

				<div class="masteriyo-invoice-body--form-data__content" style="float: right; color: #383838; font-size: 14px; font-weight: 400; line-height: 24px;">	
						<?php
						/* translators: %s is the created at date */
						echo esc_html( sprintf( '%s', $invoice_data['created_at'] ) );
						?>
				</div>
			</div>

			<div class="masteriyo-invoice-body--form-data" style="margin-bottom: 10px;">
				<div style="float: left; width: 180px; color: #222222; font-size: 14px; font-weight: 500; line-height: 24px;">
					<?php echo esc_html( __( 'Customer Note:', 'masteriyo' ) ); ?>
				</div>

				<div class="masteriyo-invoice-body--form-data__content" style="float: right; color: #383838; font-size: 14px; font-weight: 400; line-height: 24px;">	
						<?php
						/* translators: %s is the customer note */
						echo esc_html( sprintf( '%s', $invoice_data['customer_note'] ) );
						?>
				</div>
			</div>
		</div>

		<div class="masteriyo-invoice-body--table" style="margin-bottom: 20px;">
			<table style="width: 100%; border-collapse: collapse;">
				<thead>
					<tr>
						<th style="text-align: left; color: #999999; font-size: 14px; font-weight: 600; line-height: 24px; text-transform: uppercase; padding: 0px 4px 12px; border-bottom: 1px solid #999999;"><strong><?php echo esc_html( __( 'Course ID', 'masteriyo' ) ); ?> </strong></th>
						<th style="text-align: left; color: #999999; font-size: 14px; font-weight: 600; line-height: 24px; text-transform: uppercase; padding: 0px 4px 12px; border-bottom: 1px solid #999999;"><strong><?php echo esc_html( __( 'Course Name', 'masteriyo' ) ); ?> </strong></th>
						<th style="text-align: center; color: #999999; font-size: 14px; font-weight: 600; line-height: 24px; text-transform: uppercase; padding: 0px 4px 12px; border-bottom: 1px solid #999999;"><strong><?php echo esc_html( __( 'Quantity', 'masteriyo' ) ); ?> </strong></th>
						<th style="text-align: right; color: #999999; font-size: 14px; font-weight: 600; line-height: 24px; text-transform: uppercase; padding: 0px 4px 12px; border-bottom: 1px solid #999999;"><strong><?php echo esc_html( __( 'Amount', 'masteriyo' ) ); ?> </strong></th>
					</tr>
				</thead>
				<tbody>
				<?php if ( isset( $invoice_data['course_data'] ) && ! empty( $invoice_data['course_data'] ) ) : ?>
					<?php foreach ( $invoice_data['course_data'] as $course ) : ?>
						<tr style="margin-bottom: 12px;">
							<td style="color: #383838; font-size: 14px; font-weight: 400; line-height: 24px; padding: 20px 6px 6px;"><?php echo esc_html( $course['course_id'] ); ?></td>
							<td style="color: #383838; font-size: 14px; font-weight: 400; line-height: 24px; padding: 20px 6px 6px;"><?php echo esc_html( $course['name'] ); ?></td>
							<td style="color: #383838; font-size: 14px; font-weight: 400; line-height: 24px; padding: 20px 6px 6px; text-align: center;"><?php echo esc_html( $course['quantity'] ); ?></td>
							<td style="color: #383838; font-size: 14px; font-weight: 400; line-height: 24px; padding: 20px 6px 6px; text-align: right;"><?php echo esc_html( $course['subtotal'] ); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
					<!-- Add additional rows if there are multiple courses -->
				</tbody>
			</table>
		</div>

		<div class="masteriyo-invoice-footer" style="padding-top: 28px; border-top: 1px solid #999999;">
			<div class="masteriyo-invoice-footer--wrapper" style="background: #f8f8fa;">
				<div class="masteriyo-invoice-footer--payment-status" style="float: left; width: 60%; padding: 26px;">
					<div style="color: #999999; font-size: 14px; font-weight: 500; line-height: 16px;"><?php echo esc_html( __( 'Payment Status', 'masteriyo' ) ); ?></div>
					<h5 style="color: #383838; font-size: 16px; font-weight: 500; line-height: 14px; padding: 0px; margin: 16px 0px 0px;"><strong><?php echo esc_html( $invoice_data['status'] ); ?></strong></h5>
				</div>

				<div class="masteriyo-invoice-footer--total" style="float: right; width: 20%; text-align: right; padding: 26px; background: #383838;">
					<div style="color: #ffffff; font-size: 14px; font-weight: 500; line-height: 16px; "><?php echo esc_html( __( 'Total', 'masteriyo' ) ); ?></div>
					<h4 style="color: #ffffff; text-align: right; font-size: 18px; font-weight: 600; line-height: 18px; padding: 0px; margin: 16px 0px 0px;"><strong><?php echo esc_html( $invoice_data['total'] ); ?></strong></h4>
				</div>
			</div>
		</div>
	</div>
</section>
