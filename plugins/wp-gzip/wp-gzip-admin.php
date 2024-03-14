<?php
class WGZ_Admin
{

    function __construct()
    {
        add_action( 'admin_menu',	array( $this, 'wgz_add_page' ) );
    }

    public function wgz_add_page() {
		add_options_page( 'WordPress GZip', 'WordPress GZip', 'manage_options', basename(__FILE__), array( $this, 'wgz_admin_page' ) );
	}

    public function wgz_admin_page()
	{
		$this->wgz_save();
        $tools = new WGZ_Toolbox;

        $value      = get_option( 'wgz_active' );
        $values     = ( is_array( $value ) ) ? $value : array( $value );
        $checked    = ( in_array( 'yes', $values ) ) ? 'checked="checked"' : '';

		$html .= '
			<div class="wrap">
				<h2>WordPress GZip</h2>
		';
        if ( $tools->is_apache() ) {
            if ( $tools->is_gzip() ) {
                if ( $tools->is_gzip() != '1' ) {
                    $html .= '
                        <div class="notice is-dismissible error">
                            <p>Click "Save Changes" to update the GZip your site</p>
                            <a href="#" type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></a>
                        </div>
                    ';
                }
            } else {
                $html .= '
                    <div class="notice is-dismissible error">
                        <p>Click "Save Changes" to install the GZip your site</p>
                        <a href="#" type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></a>
                    </div>
                ';
            }

    		$html .= '
    				<form method="post">
    					' . wp_nonce_field( 'wgz_options', 'wgz_options_nonce' ) . '
    					<table class="form-table">
    						<tbody>
    						<tr>
    							<th scope="row"><label for="wgz_active"></label></th>
    							<td>
                                    <label><input type="checkbox" name="wgz_active" class="tog" value="yes" ' . $checked . '>Active GZip</label>
    							</td>
    						</tr>
    						</tbody>
    					</table>
    					<p class="submit"><input name="submit" id="submit" class="button button-primary" value="Save Changes" type="submit"></p>
    				</form>
    			</div>
    		';
        } else {
            $html .= '
                <p>Sorry, the plugin only works on Apache servers =(</p>
            ';
        }

		echo $html;
	}

    public function wgz_save()
    {
        if ( $_POST ) {
			if ( !isset( $_POST[ 'wgz_options_nonce' ] ) ) {
				wp_die( 'Você não possui permissões suficientes para editar essa página!' );
			}
			if ( !wp_verify_nonce( $_POST[ 'wgz_options_nonce' ], 'wgz_options' ) ) {
				wp_die( 'Você não possui permissões suficientes para editar essa página!' );
			}

			$fields = array(
				'wgz_active'
			);

			for ( $i=0; $i < count( $fields ); $i++ ) {
                $val = get_option( $fields[ $i ] );
				if ( $val ) {
                    if ( $val == 'yes' && $_POST[ $fields[ $i ] ] == '' ) {
                        update_option( $fields[ $i ], 'no' );
                    } else {
                        update_option( $fields[ $i ], $_POST[ $fields[ $i ] ] );
                    }

				} else {
					add_option( $fields[ $i ], $_POST[ $fields[ $i ] ] );
				}
			}

            $tools = new WGZ_Toolbox;
            $tools->install_gzip();

			echo '<div class="updated"><p>Opções atualizadas com sucesso!</p></div>';
		}
    }
}
