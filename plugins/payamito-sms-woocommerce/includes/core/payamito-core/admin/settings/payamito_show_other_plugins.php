<?php
/**
 * Field: payamito_show_other_plugins
 *
 * @since   1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'KIANFR_Field_payamito_show_other_plugins' ) ) {
	class KIANFR_Field_payamito_show_other_plugins extends KIANFR_Fields
	{

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' )
		{
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render()
		{
			echo $this->field_before();

			$this->show_plugins();

			echo $this->field_after();
		}

		public function show_plugins()
		{
			?>

            <div class="payamito-notify" style="
                      width: 23%;
                      overflow: hidden;
                      position: fixed;
                      top: 12%;
                      z-index: 999999999999999999999999999;">
            </div>

			<?php

			echo '<div class="payamito">';
			echo '<div class="container">';
			echo '<div class="row align-items-start">';

			?>


            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6>
							<?php
							esc_html_e( 'Lovely tip!', 'payamito' ); ?>
                        </h6>
                    </div>
                    <div class="card-body">
                        <blockquote class="blockquote mb-0">
                            <p><?php
								esc_html_e( 'The following plugins are designed with the best programming quality and very light and safe in Payamito\'s programming team, there will be no problem in the speed of your site by installing them.', 'payamito' ); ?></p>
                        </blockquote>
                    </div>
                </div>
            </div>


			<?php

			foreach ( payamito_other_plugins()->get_plugins() as $plugin ) {
				?>
                <div class="col-12 col-lg-6">
                    <div class="card mb-3 text-capitalize" style="max-width: 540px;">
                        <div class="row pt-2">

                            <div class="col-3">
                                <img width="50" src="<?php
								echo $this->get_arr( $plugin, 'image', '' ); ?>" class="img-fluid rounded-start">
                            </div>
                            <div class="col-8 align-self-center">

                                <a href="<?php
								echo $this->get_arr( $plugin, 'url', '' ); ?>" class="text-decoration-none">
                                    <h5 class=" card-title"><?php
										echo $this->get_arr( $plugin, 'name', '' ); ?></h5>
                                </a>
                            </div>

                        </div>
                        <div class="row g-0">

                            <div class="col">
                                <div class="card-body">

                                    <div class="row">
                                        <p class="card-text"><?php
											echo $this->get_arr( $plugin, 'description', '' ); ?></p>
                                    </div>

                                    <div class="mt-4 d-flex justify-content-end">

                                        <div class="p2 m-1">
                                            <a href="<?php
											echo $this->get_arr( $plugin, 'url', '' ); ?>" target="_blank" type="button"
                                               class="btn btn-outline-secondary btn-sm"><?php
												_e( 'More details', 'payamito' ) ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

				<?php
			}

			echo '</div>';

			?>

            <div class="col-12">

                <div class="card border-success mb-3" style="max-width: 18rem;">
                    <div class="card-body text-success">


                        <h5 class="card-title">هنوزم داریم افزونه های خفن واستون طراحی میکنیم!</h5>
                        <p class="card-text">فقط کافیه ما را توی شبکه های اجتماعی دنبال کنید تا از خبرای شگفت انگیز و
                            افزونه های جدید مطلع شوید.</p>

                    </div>
                </div>

            </div>

			<?php

			echo '</div>';
			echo '</div>';
		}

		public function get_arr( $array, $index, $default = null )
		{
			return isset( $array[ $index ] ) ? esc_html( $array[ $index ] ) : $default;
		}

	}
}