<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.ticketself.com
 * @since      1.0.0
 *
 * @package    Wp_Reservas
 * @subpackage Wp_Reservas/admin/partials
 */

?>
<?php
$allOptions = wp_load_alloptions();
$options = get_option($this->plugin_name);
$username = !empty($allOptions['wpReservas-email']) ? $allOptions['wpReservas-email'] : '';
$password = !empty($allOptions['wpReservas-password']) ? $allOptions['wpReservas-password'] : '';
?>
<?php
if (empty($username) || empty($password)) {?>
<div style="margin-top: 35px; background: linear-gradient(to right, #a6313c 0%, #a12d63 52%, #bf2735 100%);  padding: 30px; color: #fff; text-align: center; font-size:22px;">Introduce los datos de tu cuenta de <a style="color: white; text-decoration: dotted underline; " target="_blank" href="https://www.ticketself.com">ticketself.</a></div>
<?php } ?>

<div class="wrap">
    <h2>Configuración de acceso a sus reservas</h2>
    <div><br><br><a style="padding: 10px; background-color: #0095ff; color: #fff; text-decoration: none; border-radius: 3px; font-size: 20px;" href="admin.php?page=reservas-online%2Freservas-online-admin-page.php">Para empezar a utilizar el plugin haga click aquí</a><br><br></div>
    <h3>Le recomendamos que <u>no actualice los datos de ésta sección.</u></h3>
    <hr>
    <h4>En caso de tener una cuenta antigua y querer acceder, modifique los datos del siguiente formulario.</h4>
    <form method="post" name="cleanup_options" action="options.php">
        <?php settings_fields($this->plugin_name); ?>
        <?php do_settings_sections($this->plugin_name); ?>
        <!-- remove some meta and generators from the <head> -->
        <fieldset>
            <legend class="screen-reader-text"><span>ticketself username</span></legend>
            <span>Username:</span>
            <label for="<?php echo $this->plugin_name; ?>-username">
                <input type="text" id="<?php echo $this->plugin_name; ?>-username" name="<?php echo $this->plugin_name; ?> [username]" value="<?php echo $username;?>"/>
            </label>
        </fieldset>


		 <fieldset>
            <legend class="screen-reader-text"><span>ticketself password</span></legend>
            <span>Password:</span>
            <label for="<?php echo $this->plugin_name; ?>-password">
                <input type="text" id="<?php echo $this->plugin_name; ?>-password" name="<?php echo $this->plugin_name; ?> [password]" value="<?php echo $password;?>""/>
            </label>
        </fieldset>
          <span style="font-size: 11px;"><a target="_blank" href="https://www.ticketself.com/forgotpassword">No recuerdo mis datos de acceso</a></span>
        <?php submit_button('Guardar cambios', 'primary','submit', TRUE); ?>
    </form>
</div>