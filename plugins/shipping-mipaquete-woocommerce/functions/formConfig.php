<?php
require_once ( 'adminDb.php' );
// form config for conect api
if (!function_exists('mpqOptionsPage')) {
    function mpqOptionsPage(){
        add_menu_page(
            'Mipaquete configuración',
            'Mipaquete configuración',
            'manage_options',
            'mpq_options_shipping',
            'mpqOptionsShippingPageDisplay',
            plugin_dir_url( __FILE__ ) . '../assets/img/icon-mipaquete.png',
            15,
        );
    }
    add_action('admin_menu', 'mpqOptionsPage');
    add_action('admin_init', 'mpqAdminInit');
}

if (!function_exists('mpqAdminInit')) {
    function mpqAdminInit(){
        register_setting( 'mpq_options_group', 'mpq_id' );
        register_setting( 'mpq_options_group', 'mpq_email' );
        register_setting( 'mpq_options_group', 'mpq_password' );
        register_setting( 'mpq_options_group', 'mpq_enviroment' );
        register_setting( 'mpq_options_group', 'mpq_value_select' );
        register_setting( 'mpq_options_group', 'mpq_pickup' );
        createTableMipaquete();
    }
}

if (!function_exists('mpqOptionsShippingPageDisplay')) {
    function mpqOptionsShippingPageDisplay()
    {
        $infoUser = returnGetUser();
        
?>
    <div class="wrap">
        <form action="options.php" method="POST" >
            <?php settings_fields( 'mpq_options_group');
                wp_enqueue_style( 'my-style-mpq', plugins_url('../assets/css/style.css', __FILE__), false, '1.0', 'all' );
            ?>
            
            <center>
                <img class="img" src="https://recaudos.mipaquete.com/banner-wp.png" style="width:100%;" alt="mipaquete">
                
                
                
                <div id="contenedor">
                    <h3>Tus datos</h3>
                    <div id="contenidos">
                        <div id="columna1">
                            <span class="mpq" style="margin:auto 0px; position:absolute;" >Nombre: </span>
                        </div>
                        <div id="columna2" class="right mpq position">
                            <?php
                                if (($infoUser[0]) != '') {
                                    echo $infoUser[0] ." - ". $infoUser[7];
                                    echo "<script>alert('Conexión correcta. Bienvenido ". $infoUser[0] . "')</script>";
                                } else {
                                    echo "No se encontraron datos, verifique el usuario y contraseña";
                                    echo "<script>alert('No se encuentra un usuario con los datos ingresados ')
                                    </script>";
                                }
                            ?>
                            
                        </div>
                    </div>
                    <br>
                    <div id="contenidos">
                        <div id="columna1">
                            <span class="mpq" style="margin:auto 0px; position:absolute;">Dirección de recogida: </span>
                        </div>
                        <div id="columna2" class="righ mpq position"  >
                        <?php
                            if (!is_null($infoUser[1])) {
                                echo $infoUser[1];
                            } else {
                                echo "No se encontraron datos, verifique el usuario y contraseña";
                            }
                        ?>
                        </div>
                    </div>
                 </div>

                <div id="contenedor">
                    <div id="contenidos">
                        <div id="columna1">
                            <label name="mpq_email" id="mpq_email" class="mpq" >Email</label>
                        </div>
                        <div id="columna2">
                            <input type="email"
                            name="mpq_email"
                            class="input"
                            placeholder="email"
                            value=<?php echo get_option('mpq_email')?>>
                            <br>
                            Correo con el que te registraste en mipaquete
                        </div>
                        
                    </div>
                    
                    <div id="contenidos">
                        <div id="columna1">
                            <label name="mpq_password" id="mpq_password" class="mpq" >Contraseña</label>
                        </div>
                        <div id="columna2">
                            <input type="password"
                            name="mpq_password"
                            id="mpq_password"
                            class="input"
                            placeholder="contraseña"
                            value=<?php echo get_option('mpq_password') ?>>
                            <br>
                            Contraseña con la que te registraste en mipaquete
                        </div>
                    </div>

                    <div id="contenidos">
                        <div id="columna1">
                            <label name="mpq_pickup"
                            id="mpq_pickup"
                            class="mpq" >Deseo llevar mis paquetes a una oficina cercana</label>
                        </div>
                        <div id="columna2">
                            <select name="mpq_pickup" id="mpq_pickup" class="input" >
                            <?php
                                if (get_option('mpq_pickup') == 0) {
                                    $valuePickup = "NO";
                                } else {
                                    $valuePickup = "SI";
                                }
                            ?>
                                <option value="<?php echo get_option('mpq_pickup');?>">Valor actual: <strong>
                                    <?php echo $valuePickup; ?></strong></option>
                                <option value="0">NO</option>
                                <option value="1">SI</option>
                            </select>
                        </div>
                    </div>

                    <div id="contenidos">
                        <div id="columna1">
                            <label name="mpq_enviroment"
                                id="mpq_enviroment"
                                class="mpq" >¿Deseas habilitar el ambiente de pruebas?
                            </label>
                        </div>
                        <div id="columna2">
                            <select name="mpq_enviroment" id="mpq_enviroment" class="input" >
                            <?php
                                if (get_option('mpq_enviroment') == 0) {
                                    $valueEnviroment = "NO";
                                } else {
                                    $valueEnviroment = "SI";
                                }
                            ?>
                                <option
                                    value ="<?php echo get_option('mpq_enviroment') ?>" >Valor actual:
                                    <strong>
                                        <?php echo $valueEnviroment?>
                                    </strong>
                                </option>
                                <option value="0">NO</option>
                                <option value="1">SI</option>
                            </select>
                        </div>
                    </div>
                    <div id="contenidos">
                        <div id="columna1">
                            <label name="mpq_value_select"
                                id="mpq_value_select"
                                class="mpq" >Escoge el criterio de busqueda de la transportadora
                            </label>
                        </div>
                        <div id="columna2">
                        <select name="mpq_value_select" id="mpq_value_select" class="input" >
                            <?php
                            switch (get_option('mpq_value_select')) {
                                case 1:
                                    $valueActual = "Más económica";
                                    break;
                                case 2:
                                    $valueActual = "Menor tiempo de entrega";
                                    break;
                                case 3:
                                    $valueActual = "Mejor calificación del servicio";
                                    break;
                                default:
                                    $valueActual = "Más económica";
                                    break;
                            }
                            ?>
                            <option value="<?php echo get_option('mpq_value_select');?>">
                            Valor actual: <strong><?php echo $valueActual?></strong></option>
                            <option value="1">Más económica</option>
                            <option value="2">Menor tiempo de entrega</option>
                            <option value="3">Mejor calificación del servicio</option>
                        </select>
                        <br>
                        
                        </div>
                    </div>
                </div>
                                  

                <?php @submit_button('Conectar'); ?>
                <br>
                <h3>
                    Completa tus datos de perfil y cuenta bancaria en la app de mipaquete.com
                    en caso que no te conecte la aplicación
                </h3>
                <h3>
                    Ya no es necesario el plugin de departamentos y ciudades de Colombia
                    para el funcionamiento del plugin de Mipaquete,
                    porfavor desactivalo y realiza pruebas nuevamente
                </h3>
                <h3>
                    Aún no tienes tu cuenta en mipaquete.com? Regístrate aquí:
                </h3>
                <h4>
                <a href="https://app.mipaquete.com/registro" target="_blank" rel="noopener">
                    https://app.mipaquete.com/registro</a>
                </h4><br><br>
                <h3>
                    Configurar nuestro plugin de envíos es muy fácil, conoce el paso a paso en:
                </h3>
                <a href="https://www.mipaquete.com/conecta-tu-tiendavirtual/tienda-en-woocommerce"
                target="_blank" rel="noopener">
                    https://www.mipaquete.com/conecta-tu-tiendavirtual/tienda-en-woocommerce
                </a>
                <br><br>
                <h3>
                    Si deseas realizar pruebas primero, registrate en esta url
                    <a href="https://app.dev.mpr.mipaquete.com/registro" target="_blank" rel="noopener">
                        https://app.dev.mpr.mipaquete.com/registro
                    </a>
                <br>
                Una vez lo hayas hecho escríbenos a soporte@mipaquete.com con el correo que te registraste y
                procederemos a asignarte un saldo para que hagas pruebas.
                </h3>
            </center>
        </form>
    </div>
    <?php
        
    }
}
