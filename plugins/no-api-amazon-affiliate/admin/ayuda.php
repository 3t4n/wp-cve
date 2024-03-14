<?php

?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php echo get_admin_page_title() ?></h1>
    <p><?php _e('En está sección encontrará ayuda útil sobre cómo utilizar plugin No API Amazon Affiliate', 'no-api-amazon-affiliate') ?></p>

    <h2 class="wp-heading-inline"><?php _e('Uso básico', 'no-api-amazon-affiliate') ?></h2>
    <p><?php _e('Usar el plugin es muy fácil y sencillo. Simplemente utilice un <strong>Sortcode</strong> con la etiqueta <strong>naaa</strong>.', 'no-api-amazon-affiliate') ?></p>
    <p><?php _e('A continuación, use la propiedad <strong>asin</strong> para indicar el producto de amazon que desea mostrar.', 'no-api-amazon-affiliate') ?></p>
    <p><?php _e('Por ejemplo, si quiere mostrar el producto de amazon con el asin B08B99D7FJ, debería usar el shortcode siguiente:', 'no-api-amazon-affiliate') ?></p>
    <code >[naaa asin="B08B99D7FJ"]</code>
    <br><br>
    <div>
        <img style="border: #e0e0e0; border-style: inset; border-width: 3px;"src="<?php echo NAAA_URL_IMG; ?>product_box_sample.jpg" alt="Amazon Product Sample" class="sp-rounded">
    </div>

    <h2 class="wp-heading-inline"><?php _e('Donde obtener el código ASIN', 'no-api-amazon-affiliate') ?></h2>
    <p>
        <?php _e('<strong>El código ASIN</strong> identifica a un producto en Amazon. Para obtener el código ASIN navegue hasta el producto deseado en la página de amazon.', 'no-api-amazon-affiliate') ?><br>
        <?php _e('En la url del navegador, podrá visualizar el ASIN después del identificador /dp/.', 'no-api-amazon-affiliate') ?><br>
        <?php _e('Es un código formado por 10 caracteres en mayúsculas.', 'no-api-amazon-affiliate') ?>
    </p>
    <div>
        <img style="border: #e0e0e0; border-style: inset; border-width: 3px;"src="<?php echo NAAA_URL_IMG; ?>/amazon_url_asin.jpg" alt="Amazon Asin Url" class="sp-rounded">
        <figcaption class="sp-text-neutral-80 sp-text-15px sp-text-center sp-mt-6">Ejemplo: Como Obtener ASIN de la url de amazon</figcaption>
    </div>

    <h2 class="wp-heading-inline"><?php _e('Mostrar varios productos', 'no-api-amazon-affiliate') ?></h2>
    <p><?php _e('Para mostrar varios productos indique los códigos ASIN separados con el carácter coma "<strong>,</strong>" tal como se muestra en el ejemplo:', 'no-api-amazon-affiliate') ?></p>
    <code>[naaa asin="B08B99D7FJ, B019U5T9K2, B073VJJRBT"]</code>
    <br><br>
    <div>
        <img style="border: #e0e0e0; border-style: inset; border-width: 3px;"src="<?php echo NAAA_URL_IMG; ?>product_box_3.jpg" alt="Product Box 3 items" class="sp-rounded">
    </div>

    <h2 class="wp-heading-inline"><?php _e('Mostrar productos en horizontal', 'no-api-amazon-affiliate') ?></h2>
    <p>
        <?php _e('Para mostrar los productos en horizontal, como si fuera un listado de 1 sola columna.', 'no-api-amazon-affiliate') ?><br>
        <?php _e('Utilizar la propiedad <strong>template="horizontal"</strong> tal como se muestra en el ejemplo:', 'no-api-amazon-affiliate') ?>
    </p>
    <code>[naaa asin="B08B99D7FJ, B01N4OKUB8, B008XI79XW" template="horizontal"]</code>
    <br><br>
    <div>
        <img style="border: #e0e0e0; border-style: inset; border-width: 3px;"src="<?php echo NAAA_URL_IMG; ?>product_box_h.jpg" alt="Amazon Product Horizontal" class="sp-rounded">
    </div>


    <h2 class="wp-heading-inline"><?php _e('Mostrar LOS MEJORES productos (BESTSELLER) de una búsqueda', 'no-api-amazon-affiliate') ?></h2>
    <p>
        <?php _e('Para mostrar los mejores productos que ofrece Amazon de una búsqueda utilice la etiqueta <strong>bestseller="Texto de búsqueda"</strong>.', 'no-api-amazon-affiliate') ?><br>
        <?php _e('Se mostrarán hasta <strong>10 elementos</strong> que irán actualizándose en el tiempo de forma automática según configuración de actulización.', 'no-api-amazon-affiliate') ?><br>
        <?php _e('Para limitar el número de elementos utilice la etiqueta <strong>max=xx</strong>, donde xx representa el número de elementos a mostrar.', 'no-api-amazon-affiliate') ?><br><br>
        <?php _e('En el ejemplo podrá observar como se buscan los mejores productos para la búsqueda "Afeitadora hombres", además se indica que se utilice una plantilla horizontal y se limita el número a 3 items.', 'no-api-amazon-affiliate') ?>
    </p>
    <code>[naaa bestseller="Afeitadora hombres" template="horizontal" max=3]</code>
    <br><br>
    <div>
        <img style="border: #e0e0e0; border-style: inset; border-width: 3px;"src="<?php echo NAAA_URL_IMG; ?>product_box_h_bestseller.jpg" alt="Amazon Bestseller" class="sp-rounded">
    </div>



    <h2 class="wp-heading-inline"><?php _e('Cambiar el TÍTULO por defecto de un producto', 'no-api-amazon-affiliate') ?></h2>
    <p>
        <?php _e('Desde el apartado de "Productos Amazon" podemos editar un producto para modificar el título original.', 'no-api-amazon-affiliate') ?><br>
    </p>
    <div>
        <img style="border: #e0e0e0; border-style: inset; border-width: 3px;"src="<?php echo NAAA_URL_IMG; ?>product_title_manual_edit.jpg" alt="Product Title Edit" class="sp-rounded">
    </div>
    
    <h2 class="wp-heading-inline"><?php _e('Cambiar el HEADING LEVEL del TÍTULO del producto', 'no-api-amazon-affiliate') ?></h2>
    <p>
        <?php _e('Desde el apartado de "Apariencia" podemos configurar el HEADING (H1, H2, H3, H4, H5, H6, H0) para los títulos de los productos.', 'no-api-amazon-affiliate') ?><br>
        <?php _e('También es posible modificiar el heading de forma invidual en cada shortcode indicándolo con la propiedad <strong>heading=X</strong> donde X será el nivel de heading deseado.', 'no-api-amazon-affiliate') ?><br>
    </p>
    <code>[naaa asin="B08B99D7FJ, B01N4OKUB8, B008XI79XW" template="horizontal" heading=2]</code>
    <br><br>
    <div>
        <img style="border: #e0e0e0; border-style: inset; border-width: 3px;"src="<?php echo NAAA_URL_IMG; ?>product_box_h_head.jpg" alt="Product Title Heading" class="sp-rounded">
    </div>

    <h2 class="wp-heading-inline"><?php _e('Mostrar productos de otro marketplaces', 'no-api-amazon-affiliate') ?></h2>
    <p>
        <?php _e('Para mostrar productos de un marketplace diferente al default, indiquelo con la etiqueta <strong>market="xx"</strong> donde xx es el indicador de país-marketplace.', 'no-api-amazon-affiliate') ?><br>
    </p>
        <code>[naaa bestseller="Afeitadora hombres" market="FR"]</code>
    <p>
        <?php _e('Si quiere especificar 1 producto de un marketplace diferente, indique después del código ASIN el indicador de país-marketplace separado con el carácter guion "<strong>-</strong>".', 'no-api-amazon-affiliate') ?><br>
        <?php _e('En el ejemplo podrá observar como el tercer producto pertenece al marketplace de Francia:', 'no-api-amazon-affiliate') ?>
    </p>
    <code>[naaa asin="B08B99D7FJ, B01F9RGJJO, B01APL9AQ8-FR"]</code>
    <br><br>
    <div>
        <img style="border: #e0e0e0; border-style: inset; border-width: 3px;"src="<?php echo NAAA_URL_IMG; ?>product_box_3_mp.jpg" alt="Amazon Marketplace" class="sp-rounded">
    </div>

        <h3 class="wp-heading-inline"><?php _e('Códigos de pais-marketplace', 'no-api-amazon-affiliate') ?></h2>
        <p>
            <?php _e('Los códigos de país-marketplace existentes son:', 'no-api-amazon-affiliate') ?>
        </p>
        <ul>
            <li><?php _e('Amazon Canadá: CA', 'no-api-amazon-affiliate') ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (Ej.=> B01APL9AQ8-CA)</li>
            <li><?php _e('Amazon Alemania: DE', 'no-api-amazon-affiliate') ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (Ej.=> B01APL9AQ8-DE)</li>
            <li><?php _e('Amazon España: ES', 'no-api-amazon-affiliate') ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (Ej.=> B01APL9AQ8-ES)</li>
            <li><?php _e('Amazon Francia: FR', 'no-api-amazon-affiliate') ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (Ej.=> B01APL9AQ8-FR)</li>
            <li><?php _e('Amazon Reino Unido: GB', 'no-api-amazon-affiliate') ?>&nbsp;&nbsp; (Ej.=> B01APL9AQ8-GB)</li>
            <li><?php _e('Amazon Italia: IT', 'no-api-amazon-affiliate') ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (Ej.=> B01APL9AQ8-IT)</li>
            <li><?php _e('Amazon Japón: JP', 'no-api-amazon-affiliate') ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (Ej.=> B01APL9AQ8-JP)</li>
            <li><?php _e('Amazon Estados Unidos: US', 'no-api-amazon-affiliate') ?> (Ej.=> B01APL9AQ8-US)</li>
            <li><del><?php _e('Amazon México: MX', 'no-api-amazon-affiliate') ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (Ej.=> B01APL9AQ8-MX)</del></li>
            <li><del><?php _e('Amazon Brasil: BR', 'no-api-amazon-affiliate') ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (Ej.=> B01APL9AQ8-BR)</del></li>
        </ul>
    </div>
