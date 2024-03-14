<?php  
return array( 
    'shiptimizecolumntitle' => 'Estado / Acción',
    'automaticexport' => 'Exportación automática',
    'automaticexportdescription' => 'Enviar pedidos para Shiptimize cuando el estado coincida con',
    'A new token will be automatically requested when this one expires' => 'Se solicitará automáticamente un nuevo token cuando este caduque',
    "api sent status" => "API enviado a status",
    "api sent trackingId:" => "API enviado a trackingId:",
    'Carriers Available In your contract' => 'Operadores disponibles en su contrato',
    'Click' => 'Clic',
    'Choose Pickup Location' => 'Elija la ubicación de recogida',
    'geolocationfailed' => 'No se pudo obtener la latitud, la longitud de la dirección',
    'Credentials' => 'Credenciales',
    "Diferent carrier selected by the api" => "Operador diferente seleccionado por la api",
    "Don't forget to set the appropriate cost for each carrier if you don't have free shipping for all orders"=>"No se olvide de establecer el costo apropiado para cada transportista si no tiene envío gratis para todos los pedidos" ,
    "Error on Export" => "Error on Export",
    "Exported" => 'Exportado',
    'Export Preset Orders' => 'Exportar Pedidos preestablecidos',
    'Export Preset Orders to' => 'Exportar Pedidos preestablecidos a',
    'Export to' => 'Exportar a',

    'expires at' => 'expira en',
    'Invalid Credentials' => 'Credenciales no válidas',
    'If a google key is provided the map served will be a google map else an openmap will be shown' => "Si se proporciona una clave de Google el mapa servido en el check-out para ofrecer las ubicaciones de Servicepoint será un mapa de Google en lugar de un OpenMap por defecto. Asegúrese de activar <a href='%s'>Geocodificación</a>.",
    'if not opened' => 'si no está abierto',
    'No pickup points returned by the carrier for this address' => 'No hay puntos de recogida devueltos por el transportista para esta dirección',
    'Not Exported' => 'No exportado',
    'Pickup Point' => 'Punto de recogida',
    'Private Key' => 'Clave Privada',
    'Public Key' => 'Clave Pública',  
    'maptitle' => 'Seleccione un punto de entrega',  
    'Select' => 'Seleccionar',
    'Selected Pickup' => 'Recogida seleccionada',
    'shipping zones' => 'zonas de envio',
    'Sent %d orders. <br/>Exported: %d <br/> With Errors: %d' => 'Enviado: %d pedidos. <br/> Exportado: %d <br/> Con errores: %d ',
    "Unknown status of id" => 'estado desconocido',
    "You can add them to" => "Puede agregarlos a",
    'When you click "Export All" in the orders view, export all orders not exported successfully, with status' => 'Al hacer clic en "Exportar Pedidos preestablecidos" en la vista de pedidos, exporte todos los pedidos que no se hayan exportado con éxito, con el estado',
    'printlabel'  => 'imprimir la etiqueta', 
    'printlabeltitle'  => 'imprimir la etiqueta', 
    'order'  => 'Pedido', 
    'label'  => 'Etiqueta',  
    'exportdescription' => 'En la lista de pedidos puede exportar los pedidos por:
<ul>
<li>Exportar todos los pedidos - Exportará sólo los pedidos que no hayan sido exportados y que tengan uno de los estados que haya configurado en Exportar todo en la pestaña de ajustes.
</li>
<li>Exportar los pedidos seleccionados - Enviará a la aplicación cualquier pedido seleccionado independientemente del estado. Esto le permite reexportar los pedidos si los borra en la aplicación.</li></ul>',
    'statusdescription' => 'En la lista de pedidos puede ver el estado de exportación del pedido. Si hay un error durante la exportación o la impresión de la etiqueta, el icono de estado se volverá rojo.
<br/>Pase el ratón por encima del icono de estado para ver el historial de exportación del pedido
<br/>Los estados de exportación se enumeran a continuación',
    'ordernotexported' => 'Pedido no exportado',
    'successdescription' => 'Pedido exportado con éxito',
    'exporterrordescription' => 'El pedido se exportó con errores',
    'printsuccesseddescription' => 'Etiqueta impresa con éxito',
    'printerrordescription' => 'La solicitud de etiqueta devuelve errores',
    'labellocked' => 'Imprimir etiqueta deshabilitada. Vaya a la configuración de envío y compruebe que comprende cómo funciona la impresión de etiquetas',
    'labelagree' => 'He leído la sección de ayuda en la siguiente pestaña y entiendo cómo se imprimen las etiquetas desde woocommerce.',
    'requestinglabel' => 'Creando etiquetas',
    'labelclick' => 'haga clic en % para obtener su etiqueta si no se ha abierto una nueva ventana', 
    'labelprinted' => 'Etiqueta impresa',
    'labeltermsintro' => '', 
    'labelterms' => 'En cuanto se hace clic en el botón "imprimir etiqueta" perteneciente a un pedido de la vista general de pedidos, ocurre lo siguiente
    <ol class="shiptimize-list">
    <li>  El pedido se exporta a Shiptimize y los detalles del pedido se transmiten al transportista para que, si el envío es exitoso, podamos enviar una etiqueta de envío de vuelta a la plataforma de compras.</li>
    <li>  Puedes configurar el formato de la etiqueta en la app de Shiptimize "Ajustes" > "Impresión" > "Imprimir formato de etiqueta"</li>
    <li>  En caso de que no se pueda exportar el pedido, se devuelve un mensaje de error con información adicional de por qué no se ha podido exportar.</li>
    <li>  En caso de que el pedido se haya exportado previamente, pero no tenga ninguna etiqueta asociada, se vuelven a enviar los datos del pedido. Cualquier cambio en la dirección se actualiza. </li>
    <li>  Si su cliente elige uno de los transportistas de Shiptimize en el momento de la compra, el transportista y las opciones que haya establecido para él en woocommerce, siempre que sean válidas para el envío, se asignarán al mismo </li>
    <li> En caso de que no haya ningún transportista de Shiptimize asociado al pedido, se elegirá automáticamente el transportista establecido en el portal de envíos de Shiptimize en "Configuración" > "Configuración por defecto" > "Transportista por defecto"</li>
    <li> Si no ha seleccionado un transportista en "Transportista estándar". Se seleccionará automáticamente el primer transportista disponible que envíe al destino indicado.</li>
    <li> Si se ha equivocado, puede contactar con Shiptimize a través de la sección de soporte la app de Shiptimize y le ayudaremos. El tiempo es primordial, por favor confirme si la etiqueta es correcta después de recibirla.</li>
    </ol>',
    'labelbuttondescription' => 'Para activar la impresión de etiquetas, en la lista de pedidos, haga clic en el botón de etiquetas',

    'labelbulkprintitle' => 'Etiqueta de impresión en lote',
    'labelbulkprint' => 'Si desea imprimir etiquetas para más de un pedido a la vez. En la lista de pedidos:
    <ol class="shiptimize-list">
    <li>Seleccione los pedidos para los que desea imprimir una etiqueta </li>
    <li>Seleccione Shiptimize: imprimir etiqueta, en el desplegable de acciones en lote</li>
    <li>Haga clic en "aplicar"</li>
    </ol>',

    'pickupbehaviour' => 'Seleccionar un punto de recogida es',
    'pickuppointbehavior0' => 'opcional',
    'pickuppointbehavior1' => 'obligatorio',
    'pickuppointbehavior2' => 'no es posible',
    'mandatorypointmsg' => "Seleccione un punto de recogida o elija otro método de envío",
    
// CREATE ACCOUNT FORM    
    'Name' => 'Nombre',
    'Phone' => 'Teléfono',
    'Do you Ship internationally?' => '¿Envía internacionalmente?',
    'Average Monthly Shipments' => 'Envíos mensuales promedio',
    'Create a %s Account' => 'Crear una cuenta de %s',
    'Please, complete this form so we can create a %s account customized to your store. We will send suggested carriers and negotiated rates directly to your e-mail within 24-48 hours (working days). Have questions? Contact us directly via' => 'Por favor, complete este formulario para que podamos crear una cuenta %s personalizada para su tienda. Enviaremos las transportadoras sugeridas y las tarifas negociadas directamente a su correo electrónico dentro de 24-48 horas (días laborables). Tiene preguntas? Póngase en contacto con nosotros directamente através de',
    'Shop Url' => 'url de la tienda',
    'Yes' => 'Si',
    'No' => 'No',
    'Request account' => 'Solicitar cuenta',
    'If you do not have a %s account' => 'Si no tiene una cuenta %s', 
    'Click Here'=>'haga clic aquí',
    'service_level' => 'servicio',
//  MANUALS 
    'Download Manual' => 'Descargar manual',
    'Dutch' =>'Holandés',
    'English' => 'Inglés', 
//  WOO SPECIFIC STUFF 
    'settings' => 'Ajustes',
    'help' => 'Apoyo',
    'PostNL uses a custom format for Netherlands addresses which will cause export errors. Please disable PostNL to use' => 'PostNL utiliza un formato personalizado para las direcciones de los Países Bajos que causará errores de exportación. Deshabilite PostNL para usar',
    'setcredentials' => 'claves inválidas! Confirme en Configuración de Shiptimize si los copió correctamente',
    'pickuppointsoptions' => "Puntos de Recogida",
    'pickuppointsdisable' => "No muestre esta opción a sus clientes, no incluya el botón o el mapa al finalizar la compra",
    'yes'=>'si',
    'no'=>'no',
    'service_level'=>'Service Level',
    'cashservice' => 'Cash on Delivery',
    'sendinsured' => 'Insured',
    'extraoptions' => 'Extra Options',

    'useapititle' => 'Utilizar la API de WP',
    'usewpapi' => "Utilice la API de woordpress para enviar actualizaciones de pedidos.<br/><small>Consulta la pestaña de ayuda para más información</small> ",
    'useapihelp' => "Algunos plugins, como wpml, pueden causar problemas con las rutas, dando como resultado errores de NO ENCONTRADO al enviar las actualizaciones desde la aplicación a woocommerce. 
    <br/>Si la API está habilitada en su sitio web, este es un método preferible para recibir actualizaciones. 
<br/>Compruebe si la API está habilitada en su sitio web aquí: %s. 
<br/>Si ya tienes un Token, crea nuevas claves en la app y luego actualízalas en tu sitio web. 
<br/>La url utilizada para enviar actualizaciones es única para una clave y una vez establecida no puede ser cambiada.", 
//  Marketplace 
    'sending' => 'Enviando ...',
    'requestaccount' => 'Solicitar cuenta Shiptimize para este vendedor',
    'submitrequest' => 'Solicitar cuenta ',
    'streetname' => 'Dirección de la calle',
    'zipcode' => 'Código postal',
    'city' => 'Localidad / Ciudad',
    'province' => 'Provincia', 
    'country' => 'País', 
    'fiscal' => 'CIF',
    'requestsent' => 'Enviado con éxito. Nuestro equipo se pondrá en contacto con usted.',
    'connect2shiptimize' => 'Conectar con Shiptimize',
    'disconnectshiptimize' => 'Desconectarse de Shiptimize',
    'welcometitle' => 'Bienvenido a Shiptimize & PakketMail',
    'welcomedescription' => '¡El software de envíos con múltiples transportistas que te ahorra tiempo y dinero!',
    'welcomeskip' => 'Saltar esto, ya tengo una cuenta',
    'start'  => "Empecemos",
    'stepback' => 'Volver',
    'continue' => 'Continue', 
    'feature1title' => 'Ahorra tiempo con la automatización',
    'feature1description' => 'Optimiza tus entregas automatizando algunas de las partes que más tiempo consumen en el proceso.  <br/><br/>Etiquetas de Envío, Email Track&Trace de localización,    Etiquetas de Devolución, Estado del Envío',
    'feature2title' => 'Aggregation makes your life easier',
   'feature2description' => 'Reúne diferentes opiniones y posibilidades en una sencilla plataforma online.  <br/><br/>Múltiples transportistas, Registro de envíos, Todos los canales de venta',
    'feature3title' => 'Consigue un asistente de primera clase',
    'feature3description' => 'Cuenta con nuestro servicio al cliente de primera clase para ayudarte con todos los asuntos relacionados con los envíos.
    <br/><br/>Soporte telefónico y online, Puente entre tu tienda y los transportistas, Mejora tu servicio al cliente',
    'averageshipments' => 'Media de envíos por mes',
    'companyname' =>  'Nombre de la empresa',
    'contactemail' => 'Email de contacto',
    'contactperson' => 'Nombre de contacto',
    'contactphone' => 'Teléfono de contacto',
    'origincountry' => 'País desde el que envío',
    'contriesship' => 'País al que envío actualmente',
    'finishsetup' => 'Finalizar Instalación',
    'shiptimizesettings' => 'Finalizar Instalación',
    'whopays' => 'Quién paga Shiptimize?',
    'you' => 'Usted',
    'yourvendors' => 'Sus Vendedores',
    'whopaysdescription' => 'Si paga Shiptimize, todos los proveedores que realicen envíos con shiptimize heredarán las tarifas que defina.',
    'inheritadminrates' => 'Las reglas de envío las define el administrador',
    'errors' => 'Errores', 
    'exportvendorsbtn' => 'Exportar lista de proveedores a archivo Csv',
    'defaultshipping' => 'Tipo de envío predeterminado',
    'by_weight' => 'Envío por Peso',
    'by_country' => 'Envío por País',
    'step1description' => 'con estas tres claves mejoradas para tu proceso de envíos',
    'step1title' => 'Envía más rápido y mejor',
    'step2description' => '¿Por qué necesitas esto? Para servirte mejor y darte las mejores opciones.',
    'step2title' => 'Tu Información de Envíos',
    'step3description' => '¡Gracias por unirte a nosotros! Recibirás todos los detalles al email proporcionado.',
    'step3title' => 'La información de tu cuenta está en camino',
    'provincesdescription' => 'Declarar provincias adicionales',
    'hidenotfree' => 'Si hay al menos un método de envío disponible con coste 0, ocultar cualquier método de envío con coste > 0',
    'hidenotfreetitle' => 'Ocultar métodos de envío',
    'hideifclasspresent' => 'Si al menos un artículo en el carrito contiene de estas clases, no muestre este método',
    'exportvirtualtitle' => 'Productos y pedidos virtuales',
    'exportvirtualorders' => 'Exportar pedidos que sólo contengan productos virtuales',
    'exportvirtualproducts' => 'Al exportar, añadir productos virtuales a los pedidos',
    'mapfieldmandatory' => 'obligatorio. Defina un valor.',
    'multiorderlabelwarn' => 'Si desea imprimir más de una etiqueta a la vez, utilice la aplicación.' 
);  