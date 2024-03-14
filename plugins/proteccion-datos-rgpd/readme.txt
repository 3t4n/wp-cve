=== Protección de datos - RGPD ===
Contributors: ABCdatos
Tags: privacidad,rgpd,proteccion,datos,legal,cookies,lssi,lssice,politica,lopd,aviso,abcdatos
Requires at least: 4.2
Tested up to: 6.2
Stable tag: 0.65
Requires PHP: 5.3
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

En minutos cumplirás con la legislación vigente, RGPD LSSICE y LOPD, con los documentos legales de políticas y datos obligatorios en formularios.

== Description ==

Desde el 25 de mayo de 2018, las páginas web de la Unión Europea han de seguir las directrices del [Reglamento General de Protección de Datos (RGPD)](https://www.boe.es/doue/2016/119/L00001-00088.pdf).

Con este plugin para WordPress te facilitamos la adaptación al mismo, con muy poco esfuerzo tendrás configurados un aviso legal para cumplir con la LSSICE, una política de privacidad acorde al RGPD -ampliación europea de la LOPD- así como otras prestaciones relacionadas con el deber de información y los formularios.

El plugin genera el contenido de tus páginas de textos legales y te propociona etiquetas que puedes utilizar para adaptar tus formularios al RGPD sin que futuras actualizaciones o pequeñas correcciones requieran editar el contenido de las páginas generadas automáticamente.

Para generar el contenido de la página de aviso Legal si no la tienes, el plugin te genera una nueva y como contenido incorpora tan solo la siguiente etiqueta (shortag):

`[pdrgpd-aviso-legal]`

Si necesitas cambiar el contenido más allá de los valores configurables en ajustes, puedes remplazar esa etiqueta por estas que gestionan individualmente cada apartado del aviso legal, reordenando, agregando o quitando el texto que desees:

`[pdrgpd-aviso-legal-identificacion-titular]`
`[pdrgpd-aviso-legal-condiciones]`
`[pdrgpd-aviso-legal-propiedad-intelectual]`
`[pdrgpd-aviso-legal-disponibilidad]`
`[pdrgpd-aviso-legal-calidad]`
`[pdrgpd-aviso-legal-limitacion-responsabilidad]`
`[pdrgpd-aviso-legal-notificaciones]`
`[pdrgpd-aviso-legal-jurisdiccion]`
`[pdrgpd-aviso-legal-legislacion]`

Para la página de política de privacidad, inicialmente se emplea la siguiente etiqueta:

`[pdrgpd-politica-privacidad]`

Al igual que con el aviso legal, si necesitas cambiar el contenido más allá de los valores configurables en los ajustes, puedes remplazar esa etiqueta por estas que gestionan individualmente cada apartado de la política de privacidad, agregando, reordenando o quitando el texto que desees:

`[pdrgpd-politica-privacidad-presentacion]`
`[pdrgpd-politica-privacidad-responsable]`
`[pdrgpd-politica-privacidad-finalidad]`
`[pdrgpd-politica-privacidad-legitimacion]`
`[pdrgpd-politica-privacidad-transferencia]`
`[pdrgpd-politica-privacidad-derechos]`

Para la página de política de cookies, se utiliza la siguiente etiqueta:

`[pdrgpd-politica-cookies]`

Como con las anteriores, si necesitas cambiar el contenido más allá de los valores configurables en ajustes, puedes remplazar esa etiqueta por estas que gestionan individualmente cada apartado de la política de cookies, agregando, reordenando o quitando el texto que desees:

`[pdrgpd-politica-cookies-introduccion]`
`[pdrgpd-politica-cookies-tipos]`
`[pdrgpd-politica-cookies-gestionar]`
`[pdrgpd-politica-cookies-necesidad]`
`[pdrgpd-politica-cookies-actualizacion]`
`[pdrgpd-politica-cookies-contacto]`

Para que las páginas se creen automáticamente si no existen, deja marcada la casilla de crear páginas legales automáticamente en la página de ajustes, las que no existan en las direcciones que hayas indicado, se crearán al grabar los cambios. Si ya existen no temas, no va a modificar las existentes ¡sin miedo! Si existen las tres, no te aparecerá la casilla (¿para qué?).

Si quieres agregar nuevos apartados, los títulos van con cabeceras html h3 y el contenido en párrafos p, de este modo mantendrá el aspecto conforme a lo ya existente y tu tema.

Si en tu sitio permites comentarios, basta con que actives la casilla *Aplicar RGPD automáticamente en el formulario de comentar* en la configuración. Si el formulario se procesa con el plugin Akismet, se detecta su existencia y los textos se adaptan a esa circunstancia. Esta funcionalidad es incompatible con la opción de identificación mediante redes sociales de Jetpack que se configura o desactiva en su caso en Jetpack-> Ajustes -> Debate -> Comentarios -> Permite a los lectores usar cuentas de WordPress.com, Twitter, Facebook o Google+ para comentar.

En el formulario de contacto que eventualmente tengas, agrega además de una casilla que fuerce a aceptar tu política de privacidad, la siguiente etiqueta para cumplir el deber de información:

`[pdrgpd-aviso-formulario-contacto]`

Recuerda indicar en la configuración si empleas Akismet en el formulario de contacto.

Si utilizas el widget de suscripción a nuevas entradas de Jetpack, para incluir la casilla de aceptación a su política de privacidad y los datos de la primera capa del deber de información, puedes remplazarlo por un widget de código HTML que contenga el siguiente shortcode:

`[pdrgpd_jetpack_suscripcion]`

No olvides marcar en los ajustes que lo utilizas para que la política de privacidad lo mencione y el shortcode se active.

Para código adaptado a diferentes plugins de formularios, consulta las [Preguntas frecuentes](https://taller.abcdatos.net/plugin-rgpd-wordpress/#faq).

Si tu tema resulta compatible -la función está recién desarrollada y podría no serlo- puedes configurar remplazar otros avisos preexistentes a pie de página por una nota de copyright y enlaces a las páginas legales gestionados por el plugin. Actualmente soporta los siguientes temas:

* Twenty Twelve, Twenty Thirteen, Twenty Fourteen, Twenty Fifteen, Twenty Sixteen, Twenty Seventeen, Twenty Nineteen, Twenty Twenty, Twenty Twenty-One y Storefront de WordPress
* Flash, ColorMag, eStore, Spacious y Cenote de ThemeGrill
* Envo Shop de EnvoThemes
* Industro de OceanThemes
* GeneratePress de Tom Usborne

Si no funciona en tu tema, [avísanos](https://wordpress.org/support/plugin/proteccion-datos-rgpd) para que veamos si se puede adaptar en una próxima versión o incluirlo en una lista de no compatibles para advertirlo.

= Prestaciones pendientes de implementar =

* Indicación de las cookies utilizadas en la política de cookies que se introdujo en la v0.40.
* Aviso de cookies.

== Installation ==

1. Sube los archivos al directorio `wp-content/plugins/proteccion-datos-rgpd` o instala el plugin mediante el menú Plugins de tu WordPress directamente.
1. Activa el plugin en el menú Plugins de tu WordPress.
1. Accede al menú Protección Datos RGPD desde el menú de administrador de Wordpress para configurar el plugin.
1. Indica las direcciones url deseadas para las páginas legales, bien sean previamente existentes (a conservar) o pendientes de creación y deja marcada la casilla de crear automáticamente las páginas legales.
1. Si corresponde, marca la casilla que indica su existencia e inserta el código `[pdrgpd-aviso-formulario-contacto]` en tu formulario de contacto, además de una casilla de aceptación de tu política de privacidad que haya que marcar forzosamente. Consulta las [Preguntas frecuentes](https://taller.abcdatos.net/plugin-rgpd-wordpress/#faq) para más detalles.
1. Procede del mismo modo con el formulario de suscripción si existe.
1. Marca la casilla correspondiente si permites comentarios en tu sitio.
1. Si utilizas el widget de Jetpack para permitir la suscripción a nuevas entradas, remplázalo por un widget HTML con la etiqueta `[pdrgpd_jetpack_suscripcion]` y marca la casilla Existe formulario de suscripción de Jetpack.
1. Pulsa en Guardar los cambios.
1- Crea los enlaces a los documentos legales a pie de página o permite que el plugin lo haga por ti (solo en temas compatibles).

== Frequently Asked Questions ==

= ¿Puedo modificar algún fragmento del contenido generado en los avisos legales? =

Sí, utiliza la lista larga de códigos para aislar el que deseas modificar e introduce manualmente el texto que corresponda.

= ¿Puedo agregar más contenido al generado en los avisos legales? =

Sí, utiliza la lista larga de códigos y agrega el tuyo entre los apartados que desees.

= ¿Como integro la parte legal en un formulario de contacto realizado con Jetpack? =

En la página del formulario, inserta las siguientes etiquetas antes de la [/contact-form] :

`[pdrgpd-aviso-formulario-contacto]`
`[contact-field label='Acepto la política de privacidad' type='checkbox' required='1'/]`

= ¿Como integro la parte legal en un formulario de Contact Form 7? =

En el formulario afectado, inserta lo siguiente antes de la etiqueta del botón de enviar (submit):

`[acceptance Acepto-privacidad] Acepto la <a href="[pdrgpd-uri-privacidad]">política de privacidad</a>`
`[pdrgpd-aviso-formulario-contacto]`

No olvides incluir el campo en la pestaña Correo Electrónico para que llegue en el mail:

`Privacidad: [Acepto-privacidad]`

Adáptalo al diseño del formulario si es oportuno.

Para evitar que la casilla de aceptación y el texto queden en diferentes líneas, agrega el siguiente código en Apariencia > Personalización > CSS adicional:

`span.Acepto-privacidad {
    display: inline;
}`

= ¿Como integro la parte legal en un formulario de Pirate Forms? =

- En la página que ha de albergar el formulario en cuestión, incorpora la etiqueta `[pirate_forms]`, seguida de la etiqueta `[pdrgpd-aviso-formulario-contacto]`.
- En los ajustes de Pirate Forms, en la pestaña *Configurar Campos*, selecciona *Casilla de verificación*.
- En la siguiente pestaña *Etiquetas de campos*, para la *Casilla de verificación*, incorpora: `Acepto la <a href="/privacidad/">política de privacidad</a>.` remplazando */privacidad/* por la dirección de tu política de privacidad si es otra.
- Pulsa en *Guardar cambios*, obviamente.

= ¿Como reduzco el tamaño de la primera capa del deber de información? =

En tu panel de administración de WordPress, puedes dirígirte a *Apariencia* > *Editar CSS* > *CSS adicional* e introducir lo siguiente:

`.pdrgpd_primeracapa {
	font-size: small;
	line-height: 1.0;
	width: auto;
	padding: 0.2em;
}`

Navega hasta el formulario afectado por el marco principal, cambia los valores que consideres oportunos y una vez veas que el resultado es como te gusta, no olvides pulsar en Publicar.

= ¿Como hago para mostrar el típico banner de las cookies? =

El banner de cookies solo es obligatorio si las usas. En ese caso, además de mostrar el banner necesitarás impedir la carga de cookies si el usuario no las ha autorizado. Para esa función, te sugerimos que instales también el plugin [Cookies and Content Security Policy](https://es.wordpress.org/plugins/cookies-and-content-security-policy/) para crearlo.

== Screenshots ==

1. Vista de móvil de la configuración.
2. Ejemplo de aviso legal.
3. Ejemplo de política de privacidad.

== Changelog ==

= 0.65 =
*Jun 14 2023*
* Footnote for GeneratePress theme.
* Minor adjust in timezone of the preselected value for initial year.

= 0.64 =
*May 12 2023*
* Avoids warning on copyright notice not used.
* Improved form related internal code.
* Updated support for distinct versions of the WordPress Twenty Twenty-Two and Twenty Twenty-Three themes for the footnote.
* Improved some texts.
* Solved minor issue in desktop icon.
* Solved issue with languages using UTF-8 in footnote.
* Code styling changes.

= 0.63 =
*Mar 20 2023*
* It supports the WordPress Twenty Twenty-Two and Twenty Twenty-Three themes for the footnote.
* Removed cookies banner checkbox filtered from a development version, it never has been provided.
* Better Parabola theme support.

= 0.62 =
*Mar 13 2023*
* WordPress 6.2 basic compatibility checked.

= 0.61 =
*Oct 22 2022*
* Soporta el tema Parabola de Cryout Creations para la nota de pie de página.
* Verificada la compatibilidad con WordPress 6.1.
* Verificada la compatibilidad con PHP 8.2.

= 0.60 =
*Sep 12 2022*
* Posibilita la inclusión de código de Analytics.
* Correcciones menores a textos de administración.
* Código sobrante en enlaces a pie de página.
* Traducción cruzada del texto por defecto de la finalidad del tratamiento del formulario de contacto en la primera capa del deber de información para sitios multilenguaje.
* Corregida advertencia de PHP cuando no hay mail de contacto para la política de cookies.
* Verificada la compatibilidad con WordPress 6.0.

= 0.59 =
*Jun 15 2021*
* Corrección a textos en catalán de datos del responsable del sitio.
* Verificada la compatibilidad con WordPress 5.8.

= 0.58 =
*Mar 25 2021*
* Verificada la compatibilidad con WordPress 5.7.
* Correcciones sintácticas en política de cookies.
* Corrección sintáctica en política de privacidad.
* Retirado tooltip en enlaces de e-mail generados.

= 0.57 =
*Jan 08 2021*
* Retirado nombre incorrecto del aviso legal de la versión en catalán.

= 0.56 =
*Oct 25 2020*
* Soporta el tema Twenty Twenty-One de WordPress para la nota de pie de página.
* Soporta el tema Industro de OceanThemes para la nota de pie de página.
* Verificada la compatibilidad con WordPress 5.6.
* Verificada la compatibilidad con PHP 8.0.

= 0.55 =
*Sep 11 2020*
* Enlace en el número de teléfono si existe y tiene indicado el prefijo internacional con un +.
* Icono de administración integrado en el menú mediante SVG.
* Compatibilidad con el tema Envo Shop de EnvoThemes.

= 0.54 =
*Aug 09 2020*
* Verificada compatibilidad de la gestión del pie de página con el tema Storefront.
* Verificada la compatibilidad con WordPress 5.5.

= 0.53 =
*May 13 2020*
* Redacción de documentos legales en ausencia de dirección e-mail.
* Originales en inglés para las cadenas de texto traducibles de v0.52.

= 0.52 =
*Apr 23 2020*
* Mayor posibilidad de reducción del tamaño de la primera capa del deber de información en formularios mediante CSS.
* Verificada la comatibilidad con WordPress 5.4.
* Más cadenas de texto traducibles.

= 0.51 =
*Feb 14 2020*
* Soporte del tema Twenty Twenty de WordPress 5.3 para la nota de pie de página.
* Soporta indicar la exportación de datos por el uso de Akismet en el formulario de contacto.
* Modificado modo de detección de módulos de Jetpack para evitar notificaciones en modo de depuración.

= 0.50 =
*Oct 26 2019*
* Corrección a texto de primera capa de formularios cuando no hay cesiones de datos.
* Verificada la compatibilidad con WordPress 5.3.
* Verificada la compatibilidad con PHP 7.4.

= 0.49 =
*08 agosto 2019*
* Asegura los puntos finales en diversas frases del texto de la primera capa para formularios y evita la duplicidad de los mismos independientemente de si los textos del usuario los incluyen.
* Fallo de sintaxis en opciones del panel de administración.

= 0.48.1 =
*14 mayo 2019*
* Fallo en la indicación de la etiqueta (shortcode) mencionada en los ajustes para el formulario de suscripción de Jetpack.
* Control de la configuración requerida en Jetpack para utilizar el shortcode que remplaza al formulario de suscripción de Jetpack.

= 0.48 =
*12 mayo 2019*
* Fallo en apartado destinatarios de la primera capa del deber de información.
* Indicación opcional de formulario de suscripción de Jetpack en la política de privacidad.
* Nuevo shortag `[pdrgpd_jetpack_suscripcion]` para remplazar formulario de suscripción de nuevas entradas de Jetpack.
* Ligera actualización del contenido de la primera capa del deber de información en formularios.
* Más cadenas de texto traducibles.
* Correcciones menores de código HTML y código fuente.

= 0.47 =
*01 mayo 2019*
* Ampliación de cadenas traducibles.
* Versión en catalán de los textos legales generados.

= 0.46 =
*24 abril 2019*
* Corrección de fallos menores que emitían un warning con el modo debug activado.
* Globo notificativo de incidencias de configuración junto al título en el menú de administración. 
* Renombrada función para evitar colisiones con otros plugins en el almacenamiento de la configuración.
* Aumenta el proceso de la exigencia de aceptar la política de privacidad en el formulario de comentarios de Jetpack para ayudar a combatir el spam.

= 0.45 =
*17 febrero 2019*
* Confirmada compatibilidad con WordPress 5.1.
* Nueva opción de configuración para indicar inscripción en el registro de operadores intracomunitarios (censo VIES) y mención al NIF-IVA en el aviso legal en ese caso.

= 0.44 =
*01 noviembre 2018*
* Compatibilidad con varios temas de ThemeGrill: Flash, ColorMag, eStore, Spacious y Cenote.
* Comprobada compatibilidad de la gestión del pie de página con la versión de desarrollo a 01/11/2018 del tema Twenty Nineteen por defecto de WordPress 5.0.
* Corrección menor en la sección de contacto de la política de cookies.
* Reincorporados apartados presentación, fecha, sección y protocolo de los datos de inscripción en el registro mercantil que no aparecían.
* Corregido fallo en desinstalación.

= 0.43 =
*23 octubre 2018*
* Confirmada compatibilidad con WordPress 5.0.
* Configurada clase pdrgpd_primeracapa para poder cambiar el aspecto de la primera capa del deber de información mediante CSS.

= 0.42 =
*08 octubre 2018*
* Creación automática de documentos legales, compatibles con Gutenberg.
* En el panel de administración, corrige posible defecto del enlace a la página de ajustes desde la página de plugins.
* Año de la primera publicación como valor por defecto para la nota de copyright al pie.
* Mejor detección y gestión de la incompatibilidad con la opción de gestión de comentarios de Jetpack.
* Agregados Presentación, fecha, sección, folio, protocolo... a los datos de inscripción en el registro mercantil

= 0.41.1 =
*26 agosto 2018*
* Permite responder comentarios desde el panel de administración sin exigir aceptar condiciones donde no era posible.
* Advierte de la incompatibilidad de la gestión automática del formulario de comentar con al identificación mediante redes sociales de Jetpack.

= 0.41 =
*14 agosto 2018*
* En temas que resulten compatibles, permite remplazar otros avisos preexistentes a pie de página por una nota de copyright y enlaces a las páginas legales.
* Incorpora la indicación de inscripción en el registro mercantil, requerido para empresas.
* Corrige fallo en la versión en párrafo de la primera capa del deber de información.
* Mejorado ejemplo para CF7.
* Direcciones de páginas de documentos legales por defecto terminadas en / evitando redireccionamientos.

= 0.40 =
*08 agosto 2018*
* Corregido fallo en contenido de la finalidad en la primera capa del deber de información.
* Mejorada documentación.
* Primera capa del deber de información traducible.
* Nueva etiqueta [pdrgpd-politica-cookies] para generar el contenido de la página de política de cookies.
* La página de opciones controla la existencia y contenido del aviso legal y las política de privacidad y cookies.

= 0.39 =
*26 julio 2018*
* Idioma base para traducción en inglés y traducción a es_ES incorporada para facilitar futuras traducciones a otros idiomas.
* Corregidos enlaces a las FAQ en la página de ajustes.
* Corregido ejemplo de código de implementación en Contact Form 7.
* Habilita shortcodes en Contact Form 7 y widgets HTML.
* Implementada etiqueta [pdrgpd-aviso-boletin] para la primera capa del deber de información en suscripción a boletines/newsletters.
* Los enlaces a la política de privacidad se abren ahora en una nueva pestaña.

= 0.38 =
*24 julio 2018*
* Ampliada documentación.
* Corrección de etiqueta errónea "Población" en lugar de "Dirección" en la configuración.
* Mayor información para rellenar adecuadamente los campos en la configuración.
* Fallo en opciones de aspecto de la información resumida en formularios que impedían mostrarla. Formato tabla por defecto.
* Fallo en enlace a política de privacidad en la casilla de aceptación en formularios.

= 0.37 =
*23 julio 2018*
* Corregida desinstalación.
* Mejoras documentación.
* Inicio de traducción para indicar idioma es.

= 0.36 =
*15 julio 2018*
* Primera versión en WordPress.org.
* Versiones larga y resumida de las finalidades de formularios.
* Corregida detección de Akismet.
* La política de privacidad solo indica los apartados de formularios seleccionados.

= 0.35 =
*07 julio 2018*
* Corregido fallo en textos de la primera capa del deber de información cuando no hay cesiones de datos.

= 0.34 =
*20 junio 2018*
* Reconocimiento automático del filtrado de comentarios por Akismet e implementación de textos legales acordes.

= 0.33 =
*19 junio 2018*
* Añadida nota legal en el formulario de comentarios, forzando a aceptar la privacidad y grabando en la base de datos la aceptación.

= 0.32 =
*14 junio 2018*
* Nombres de funciones para evitar conflictos en WordPress.
* Opciones y etiquetas (shortcodes) renombradas también con el prefijo *pdrgpd*.

= 0.31 =
*13 junio 2018*
* Almacena el número de versión junto con el valor de las opciones.
* Enlace a ajustes desde la página de administración de plugins de WordPress.

= 0.30 =
*11 junio 2018*
* Mejor uso de la Settings API.

= 0.20 =
*10 junio 2018*
* Creación de contenido de la política de privacidad para cumplir con el RGPD.

= 0.10 =
*06 junio 2018*
* Aviso Legal para cumplir con la LSSICE española, con términos y condiciones comunes.

== Upgrade Notice ==


= 0.56 =
Compatibilidad con el tema Twenty Twenty-One de WordPress 5.6 y el tema Industro de OceanThemes.

= 0.51 =
Configurable uso de Akismet en formulario de contacto. Compatibilidad con el tema Twenty Twenty de WordPress 5.3.

= 0.50 =
Corrección a texto de la primera capa en formularios cuando no hay cesiones de datos.

= 0.48.2 =
Fallo de sintaxis en opciones del panel de administración.

= 0.48.1 =
Corregido fallo en documentación y mejoras el formulario de suscripción de Jetpack.

= 0.48 =
Soporte al formulario de suscripción de nuevas entradas de Jetpack y corrección de fallos.

= 0.44 =
Compatibilidad con más temas y corrección de fallos.

= 0.43 =
Permite cambiar el aspecto de la primera capa del deber de información mediante CSS.

= 0.42 =
Creación automática de documentos legales faltantes.

= 0.41.1 =
Corrige fallo en comentarios del administrador.

= 0.41 =
Corrige fallos, posibilita nota de copyright al pie y datos de inscripción en el registro mercantil para empresas.

= 0.40 =
Corrige fallos, facilita la configuración y posibilita crear contenido de política de cookies.

= 0.37 =
Resuelve problemas de desinstalación.

= 0.30 =
Mejor integración con Wordpress.

= 0.20 =
Incluida política de privacidad para el RGPD.
