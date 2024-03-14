<?php
$perfitUrlBase = "https://app.myperfit.com/#optins";
/*
    [0] => stdClass Object
        (
            [id] => 2
            [pubId] => febyUMyP
            [name] => Facebook Optin
            [description] => Formulario de suscripción en Facebook
            [created] => 2015-07-03T03:03:16.000+0000
            [lastModified] => 2015-07-10T20:29:40.000+0000
            [lists] => Array
                (
                    [0] => 7
                )

        )

        [subscriptions] => stdClass Object
            (
                [total] => 0
                [lastMonth] => 0
                [lastWeek] => 0
            )
*/
?>

<style>
    .perfit-edit-link {
        margin-left: 10px;
        visibility: hidden;
    }

    tr:hover .perfit-edit-link {
        visibility: visible;
    }


</style>

<h3>Formularios</h3>
<p>Para incluir un formulario en alguna sección del sitio, debes ir a la <a href="<?= admin_url('widgets.php') ?>">configuración
        de widgets</a>.</p>
<div class="">
    <a target="_blank" href="<?php echo $perfitUrlBase; ?>/new" class="button">
        Crear Formulario
    </a>
</div>
<br/>
<table class="widefat">
    <thead>
    <tr>
        <th style="" class="manage-column column-name" id="name" scope="col">Nombre</th>
        <th style="" class="manage-column column-desc" id="desc" scope="col">Descripción</th>
        <th style="" class="manage-column column-created" id="created" scope="col">Fecha de creación</th>
        <th style="" class="manage-column column-created" id="created" scope="col">Suscripciones totales</th>
        <th style="" class="manage-column column-created" id="created" scope="col">Último mes</th>
        <th style="" class="manage-column column-created" id="created" scope="col">Última semana</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($optins->data)): ?>

        <?php foreach ($optins->data as $optin): ?>
            <tr>
                <td>
                    <?= $optin->name; ?> <a target="_blank" class="perfit-edit-link"
                                            href="<?= $perfitUrlBase . '/' . $optin->id ?>">editar</a>
                </td>
                <td>
                    <?= $optin->description ?>
                </td>
                <td>
                    <?= date_i18n(get_option('date_format'), strtotime($optin->created)) ?>
                </td>
                <td>
                    <?= $optin->subscriptions->total ?>
                </td>
                <td>
                    <?= $optin->subscriptions->lastMonth ?>
                </td>
                <td>
                    <?= $optin->subscriptions->lastWeek ?>
                </td>

            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <?php if ($optins->error->type == "UNAUTHORIZED"): ?>
            <!--Esta mal la apikey, la borro.-->
            <?php
            delete_option("api_key_perfit");
            Header('Location: ' . $_SERVER['PHP_SELF']);
            ?>
        <?php else: ?>
            <tr>
                <td colspan="4">
                    <p>No tienes formularios a&uacute;n. Crea un formulario para empezar a sumar suscriptores.</p>

                </td>
            </tr>
        <?php endif; ?>
    <?php endif; ?>
    </tbody>
</table>

<br/>

<form method="POST" data-form="colors"
      action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <input type="hidden" name="colors" value="1">

    <h3>Colores</h3>
    <p>Puedes personalizar los colores de tus formularios.</p>
    <table class="form-table">
        <tbody>
        <tr class="form-field">
            <th scope="row">
                <label>Fondo encabezado y botón</label>
            </th>
            <td>
                <input name="button-bg" type="text" value="<?= get_option('perfit-optin-button-bg', '#00AEE8') ?>"
                       class="color-picker" data-default-color="#00AEE8"/>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row">
                <label>Texto encabezado y botón</label>
            </th>
            <td>
                <input name="button-text" type="text" value="<?= get_option('perfit-optin-button-text', '#FFFFFF') ?>"
                       class="color-picker" data-default-color="#FFFFFF"/>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row">
                <label>Fondo formulario</label>
            </th>
            <td>
                <input name="form-bg" type="text" value="<?= get_option('perfit-optin-form-bg', '#FFFFFF') ?>"
                       class="color-picker" data-default-color="#FFFFFF"/>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row">
                <label>Texto formulario</label>
            </th>
            <td>
                <input name="form-text" type="text" value="<?= get_option('perfit-optin-form-text', '#696969') ?>"
                       class="color-picker" data-default-color="#696969"/>
            </td>
        </tr>
        </tbody>
    </table>
    <p class="submit">
        <button class="button-primary" type="submit">Guardar colores</button>
    </p>

</form>

<br/>

<form method="POST" data-form="login"
      action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    Estás conectado con la cuenta <strong><?php echo $perfit->account(); ?></strong>
    <input type="hidden" name="reset" value="1">
    <button class="button-link">desconectar</button>
</form>
