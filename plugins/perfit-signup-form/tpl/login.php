
<form method="POST" data-form="login"
      action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <input type="hidden" name="login" value="1">
    <h3>Formularios de Suscripción de Perfit</h3>
    <p>Para conectarte con Perfit, ingresa el API key de tu cuenta. Si no sabes como
        obtenerla, visita el <a target="_blank"
                             href="http://docs.myperfit.com/integraciones/como-obtener-mi-api-key">centro de ayuda</a>.</p>
    <table class="form-table">
        <tbody>
        <tr class="form-field form-required <?php echo ($_SESSION['error']) ? 'form-invalid' : '' ?>">
            <th scope="row">
                <label for="apiKey">Api key</label>
            </th>

            <td>
                <input class="form-control" name="apiKey" id="apiKey" type="text" value="" placeholder="API key de tu cuenta" style="width: 400px;">
            </td>
        </tr>
        </tbody>
    </table>
    <p class="submit"><button class="button-primary" type="submit" >Ingresar</button></p>

</form>
<?php unset($_SESSION['error']); ?>
