<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="epaka-card">
    <div class="epaka-card-container">
        <center><a href="https://www.epaka.pl" target="_blank"><img width="100" height="auto" src="<?php echo plugins_url("assets/img/epaka.png",str_replace("admin/","",dirname(__FILE__))) ?>"></img></a></center>
        <h2>Logowanie do API epaka.pl</h2>
        <form method="POST">
            <?php include_once('epaka-alerts.php');?>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="api_email">E-mail:</label>
                        </th>
                        <td class="forminp forminp-text">
                            <input type="text" class="woocommerce-input-wrapper" name="api_email"/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="api_password">Hasło API:</label>
                        </th>
                        <td class="forminp forminp-text">
                            <input type="password" class="woocommerce-input-wrapper" name="api_password"/>
                        </td>
                    </tr>
                    <tr> 
                        <th scope="row" class="titledesc">
                            <input type="submit" value="Zaloguj" class="button-primary woocommerce-save-button"/>
                            <a href="https://www.epaka.pl/uzytkownik/rejestracja" target="_blank" class="button-secondary woocommerce-save-button">Zarejestruj</a>
                        </th>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>

<?php if(!empty($error)){ ?>
    <script>
        (function() {
            window.addAlert("<?php echo $error?>","danger");
        })();
    </script>
<?php }?>