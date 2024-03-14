<?php
/**
 * Mantiene el Aviso Legal
 *
 * @package   Protección de datos - RGPD
 * @author    ABCdatos
 * @license   GPLv2
 * @link      https://taller.abcdatos.net/
 */

defined( 'ABSPATH' ) || die( 'No se permite el acceso.' );

// Shortcodes

add_shortcode( 'pdrgpd-aviso-legal', 'pdrgpd_aviso_legal' );
function pdrgpd_aviso_legal() {
	$html  = "[pdrgpd-aviso-legal-identificacion-titular]\n";
	$html .= "[pdrgpd-aviso-legal-condiciones]\n";
	$html .= "[pdrgpd-aviso-legal-propiedad-intelectual]\n";
	$html .= "[pdrgpd-aviso-legal-disponibilidad]\n";
	$html .= "[pdrgpd-aviso-legal-calidad]\n";
	$html .= "[pdrgpd-aviso-legal-limitacion-responsabilidad]\n";
	$html .= "[pdrgpd-aviso-legal-notificaciones]\n";
	$html .= "[pdrgpd-aviso-legal-jurisdiccion]\n";
	$html .= "[pdrgpd-aviso-legal-legislacion]\n";
	return do_shortcode( $html );
}

add_shortcode( 'pdrgpd-aviso-legal-identificacion-titular', 'pdrgpd_aviso_legal_identificacion_titular' );
function pdrgpd_aviso_legal_identificacion_titular() {
	$locale = get_locale();
	if ( 'ca' === $locale ) {
		$html  = "<h3>DADES IDENTIFICATIVES DEL TITULAR DEL WEB</h3>\n";
		$html .= '<p>En compliment  del deure d\'informació estipulat en l\'article 10 de la Llei 34/2002 d\' 11 de juliol dels Serveis de la Societat de la Informació i del Comerç Electrònic, ';
		$html .= '<strong><em>[pdrgpd-titular]</em></strong>';
		$html .= ', amb ' . pdrgpd_nif_o_cif( pdrgpd_conf_nif() ) . ' [pdrgpd-nif]';
		$html .= pdrgpd_inscripcion_vies();
		$html .= pdrgpd_inscripcion_registro_mercantil();
		$html .= ', d\'ara endavant <strong>[pdrgpd-sitio]</strong>';
		$html .= ', en qualitat de titular del web <strong>[pdrgpd-dominio]</strong>';
		$html .= ', amb adreça a [pdrgpd-direccion]';
		if ( pdrgpd_conf_cp() ) {
			$html .= ', codi postal [pdrgpd-cp]';
		}
		$html .= ' de [pdrgpd-poblacion]';
		if ( pdrgpd_conf_provincia() && pdrgpd_conf_provincia() !== pdrgpd_conf_poblacion() ) {
			$html .= ', [pdrgpd-provincia]';
		}
		if ( pdrgpd_conf_email() ) {
			$html .= ', direcció de correu electrònic [pdrgpd-email]';
		}
		if ( pdrgpd_conf_telefono() ) {
			$html .= ' y teléfono [pdrgpd-telefono]';
		}
		$html .= ', procedeix a comunicar-los la informació que conforma i regula les condicions d\'ús d\'aquesta pàgina, les limitacions de responsabilitat';
		$html .= ' i les obligacions que els usuaris de la web que és publicat sota el domini <strong><i>';
		$html .= pdrgpd_conf_dominio();
		$html .= '</i></strong>, assumeixen i es comprometen a respectar.</p>';
	} else {
		$html  = "<h3>DATOS IDENTIFICATIVOS DEL TITULAR DEL SITIO WEB</h3>\n";
		$html .= '<p>En cumplimiento del deber de información estipulado en el artículo 10 de la Ley 34/2002 de 11 de julio de Servicios de la Sociedad de la Información y de Comercio Electrónico, ';
		$html .= '<strong><em>[pdrgpd-titular]</em></strong>';
		$html .= ', con ' . pdrgpd_nif_o_cif( pdrgpd_conf_nif() ) . ' [pdrgpd-nif]';
		$html .= pdrgpd_inscripcion_vies();
		$html .= pdrgpd_inscripcion_registro_mercantil();
		$html .= ', en adelante <strong>[pdrgpd-sitio]</strong>';
		$html .= ', en calidad de titular del sitio web <strong>[pdrgpd-dominio]</strong>';
		$html .= ', con domicilio en [pdrgpd-direccion]';
		if ( pdrgpd_conf_cp() ) {
			$html .= ', código postal [pdrgpd-cp]';
		}
		// Si se pone "de", puede requerir apostrofarlo.
		$html .= ' a [pdrgpd-poblacion]';
		if ( pdrgpd_conf_provincia() && pdrgpd_conf_provincia() !== pdrgpd_conf_poblacion() ) {
			$html .= ', [pdrgpd-provincia]';
		}
		if ( pdrgpd_conf_email() ) {
			$html .= ', dirección de correo electrónico [pdrgpd-email]';
		}
		if ( pdrgpd_conf_telefono() ) {
			$html .= ' y teléfono [pdrgpd-telefono]';
		}
		$html .= ', procede a comunicarles la presente información que conforma y regula las condiciones de uso en esta página, las limitaciones de responsabilidad';
		$html .= ' y las obligaciones que, los usuarios del sitio web que se publica bajo el nombre de dominio <strong><i>';
		$html .= pdrgpd_conf_dominio();
		$html .= '</i></strong>, asumen y se comprometen a respetar.</p>';
	}
	return do_shortcode( $html );
}

add_shortcode( 'pdrgpd-aviso-legal-condiciones', 'pdrgpd_aviso_legal_condiciones' );
function pdrgpd_aviso_legal_condiciones() {
	$locale = get_locale();
	if ( 'ca' === $locale ) {
		$html = '<h3>CONDICIONS D\'ÚS</h3>
<p>La utilització de <strong>[pdrgpd-dominio]</strong> atorga la condició d\'<strong>Usuari</strong> de <strong>[pdrgpd-dominio]</strong>, sigui persona física o jurídica i obligatòriament implica l\'acceptació completa, plena i sense reserves de totes i cadascuna de les clàusules i condicions generals incloses en l\'Avís Legal. Si l\'<strong>Usuari</strong> no estigués conforme amb elles, s\'abstindrà d\'utilitzar <strong>[pdrgpd-dominio]</strong>. Aquest Avís Legal està subjecte a canvis i actualitzacions pel que la versió publicada per <strong>[pdrgpd-sitio]</strong> pot ser diferent en cada moment en què l\'<strong>Usuari</strong> accedeixi al Portal. Per tant, l\'<strong>Usuari</strong> ha de llegir l\'Avís Legal en totes i cadascuna de les ocasions que accedeixi a <strong>[pdrgpd-dominio]</strong>.</p>

<p>A través de <strong>[pdrgpd-dominio]</strong>, <strong>[pdrgpd-sitio]</strong><b> </b>facilita a l\'<strong>Usuari</strong> l\'accés i utilització de diversos Continguts publicats per mitjà d\'Internet per <strong>[pdrgpd-sitio]</strong> o per tercers autoritzats.</p>

<p>L\'<strong>Usuari</strong> està obligat i es compromet a utilitzar <strong>[pdrgpd-dominio]</strong> i els continguts conforme a la legislació vigent, l\'Avís Legal i qualsevol altre avís o instrucció que es posin en coneixement, ja sigui per mitjà d\'aquest mateix Avís Legal o en qualsevol lloc dels Continguts que conformen <strong>[pdrgpd-dominio]</strong>, com són les normes de convivència, la moral i els bons costums generalment acceptades. En aquests termes, l\'<strong>Usuari</strong> s\'obliga i compromet a NO  emprar qualsevol dels Continguts amb fins i efectes il·lícits, prohibits en l\'Avís Legal o per la legislació vigent, contrari als drets i interessos de tercers o que de qualsevol manera puguin fer mal, inutilitzar, sobrecarregar, deteriorar o impedir el normal ús dels Continguts, els equips informàtics o els documents, arxius i tota classe de continguts magatzemats en qualsevol equip informàtic propi o contractats per <strong>[pdrgpd-sitio]</strong>, d\'un altre <strong>USUARI</strong> o de qualsevol usuari d\'Internet (hardware i software).</p>

<p>L\'<strong>Usuari</strong> s\'obliga i compromet a no transmetre, difondre o posar a disposició de tercers qualsevol mena de material que formi part de <strong>[pdrgpd-dominio]</strong>, com informacions, textos, dades, continguts, missatges, gràfiques, dibuixos, arxius de so i/o imatge, fotografies, gravacions, software, logos, marques, icones, tecnologia, enllaços, disseny gràfic i codis de fons, o qualsevol altre material al qual tingués accés en la seva condició d\'<strong>Usuari</strong> de <strong>[pdrgpd-dominio]</strong>, sense que aquesta enumeració tingui caràcter restringit. Així mateix, conforme a tot plegat, l\'<strong>Usuari</strong> no podrà:</p>
<ul>
<li>Reproduir, copiar, distribuir, posar a disposició o de qualsevol altra manera comunicar públicament, transformar o modificar els Continguts, excepte que tingui autorització escrita i explícita de <strong>[pdrgpd-sitio]</strong>, que és titular d\'aquests drets, o bé que estigui legalment permès.</li>
<li>Suprimir, manipular o de qualsevol manera alterar el “copyright” i altres dades identificatives de la reserva de drets de <strong>[pdrgpd-sitio]</strong> o dels seus titulars, de les empremptes i/o identificadors digitals, marques d\'aigua o qualsevol altre medi tècnic establerts pel seu reconeixement.</li>
</ul>
<p>L\'<strong>Usuari</strong> s\'abstindrà d\'obtenir i d\'intentar accedir als Continguts utilitzant mitjans o procediments diferents dels que, segons els casos, s\'hagin posat a la seva disposició per aquesta finalitat, o s\'hagin indicat per aquest fi en les pàgines web on es trobin els Continguts, o en general, els que s\'emprin habitualment a Internet per aquest efecte sempre que no provoquin cap risc de dany o inutilització de la web <strong>[pdrgpd-dominio]</strong>, i/o dels Continguts.</p>';
	} else {
		$html = '<h3>CONDICIONES DE USO</h3>
<p>La utilización de <strong>[pdrgpd-dominio]</strong> otorga la condición de <strong>Usuario</strong> de <strong>[pdrgpd-dominio]</strong>, bien sea persona física o jurídica y obligatoriamente implica la aceptación completa, plena y sin reservas de todas y cada una de las cláusulas y condiciones generales incluidas en el Aviso Legal. Si el <strong>Usuario</strong> no estuviera conforme con las cláusulas y condiciones de uso de este Aviso Legal, se abstendrá de utilizar <strong>[pdrgpd-dominio]</strong>. Este Aviso Legal está sujeto a cambios y actualizaciones por lo que la versión publicada por <strong>[pdrgpd-sitio]</strong> puede ser diferente en cada momento en que el <strong>Usuario</strong> acceda al Portal. Por tanto, el <strong>Usuario</strong> debe leer el Aviso Legal en todas y cada una de las ocasiones en que acceda a <strong>[pdrgpd-dominio]</strong>.</p>

<p>A través de <strong>[pdrgpd-dominio]</strong>, <strong>[pdrgpd-sitio]</strong><b> </b>facilita al <strong>Usuario</strong> el acceso y la utilización de diversos Contenidos publicados por medio de Internet por <strong>[pdrgpd-sitio]</strong> o por terceros autorizados.</p>

<p>El <strong>Usuario</strong> está obligado y se compromete a utilizar <strong>[pdrgpd-dominio]</strong> y los Contenidos de conformidad con la legislación vigente, el Aviso Legal, y cualquier otro aviso o instrucciones puestos en su conocimiento, bien sea por medio de este aviso legal o en cualquier otro lugar dentro de los Contenidos que conforman <strong>[pdrgpd-dominio]</strong>, como son las normas de convivencia, la moral y buenas costumbres generalmente aceptadas. A tal efecto, el <strong>Usuario</strong> se obliga y compromete a NO utilizar cualquiera de los Contenidos con fines o efectos ilícitos, prohibidos en el Aviso Legal o por la legislación vigente, lesivos de los derechos e intereses de terceros, o que de cualquier forma puedan dañar, inutilizar, sobrecargar, deteriorar o impedir la normal utilización de los Contenidos, los equipos informáticos o los documentos, archivos y toda clase de contenidos almacenados en cualquier equipo informático propios o contratados por <strong>[pdrgpd-sitio]</strong>, de otro <strong>USUARIO</strong> o de cualquier usuario de Internet (hardware y software).</p>

<p>El <strong>Usuario</strong> se obliga y se compromete a no transmitir, difundir o poner a disposición de terceros cualquier clase de material contenido en <strong>[pdrgpd-dominio]</strong>, tales como informaciones, textos, datos, contenidos, mensajes, gráficos, dibujos, archivos de sonido y/o imagen, fotografías, grabaciones, software, logotipos, marcas, iconos, tecnología, fotografías, software, enlaces, diseño gráfico y códigos fuente, o cualquier otro material al que tuviera acceso en su condición de <strong>Usuario</strong> de <strong>[pdrgpd-dominio]</strong>, sin que esta enumeración tenga carácter limitativo. Asimismo, de conformidad con todo ello, el <strong>Usuario</strong> no podrá:</p>
<ul>
<li>Reproducir, copiar, distribuir, poner a disposición o de cualquier otra forma comunicar públicamente, transformar o modificar los Contenidos, a menos que se cuente con la autorización escrita y explícita de <strong>[pdrgpd-sitio]</strong>, que es titular de los correspondientes derechos, o bien que ello resulte legalmente permitido.</li>
<li>Suprimir, manipular o de cualquier forma alterar el “copyright” y demás datos identificativos de la reserva de derechos de <strong>[pdrgpd-sitio]</strong> o de sus titulares, de las huellas y/o identificadores digitales, marcas de agua, o de cualesquiera otros medios técnicos establecidos para su reconocimiento.</li>
</ul>
<p>El <strong>Usuario</strong> deberá abstenerse de obtener e incluso de intentar obtener los Contenidos empleando para ello medios o procedimientos distintos de los que, según los casos, se hayan puesto a su disposición a este efecto o se hayan indicado a este efecto en las páginas web donde se encuentren los Contenidos o, en general, de los que se empleen habitualmente en Internet a este efecto siempre que no entrañen un riesgo de daño o inutilización de <strong>[pdrgpd-dominio]</strong>, y/o de los Contenidos.</p>';
	}
	return do_shortcode( $html );
}

add_shortcode( 'pdrgpd-aviso-legal-propiedad-intelectual', 'pdrgpd_aviso_legal_propiedad_intelectual' );
function pdrgpd_aviso_legal_propiedad_intelectual() {
	$locale = get_locale();
	if ( 'ca' === $locale ) {
		$html = '<h3>PROPIETAT INTEL·LECTUAL</h3>
<p>Totes les marques, noms comercials o signes distintius de qualsevol classe que apareixen a <strong>[pdrgpd-dominio]</strong> són propietat de <strong>[pdrgpd-sitio]</strong> o si no pot ser, dels seus respectius propietaris, sense que pugui entendre’s en cap  cas que l’ús o accés al Portal i/o als Continguts  doni a l’<strong>Usuari</strong> cap dret sobre les esmentades marques, noms comercials i/o signes distintius i sense que es puguin entendre cedits a l\'<strong>Usuari</strong>, com tampoc cap dret d’explotació que existeixin o puguin existir sobre aquests Continguts. De la mateixa manera els Continguts són propietat intel·lectual de <strong>[pdrgpd-sitio]</strong>, o de tercers en el seu cas, per tant, els drets de Propietat Intel·lectual són de titularitat de <strong>[pdrgpd-sitio]</strong> o de tercers a qui se’ls hagi autoritzat el seu ús, a qui correspon l’exercici exclusiu dels drets d’explotació dels mateixos en qualsevol manera, i en especial, els drets de reproducció, distribució, comunicació pública i transformació. L’ús no autoritzat de la informació continguda en aquesta Web, així com la lesió dels drets de la Propietat Intel·lectual o Industrial de <strong>[pdrgpd-sitio]</strong> o de tercers inclosos en <strong>[pdrgpd-dominio]</strong> que hagin cedit continguts donarà lloc a les responsabilitats legalment establertes.</p>';
	} else {
		$html = '<h3>PROPIEDAD INTELECTUAL</h3>
<p>Todas las marcas, nombres comerciales o signos distintivos de cualquier clase que aparecen en <strong>[pdrgpd-dominio]</strong> son propiedad de <strong>[pdrgpd-sitio]</strong> o, en su caso, de sus respectivos propietarios, sin que pueda entenderse que el uso o acceso al Portal y/o a los Contenidos atribuya al <strong>Usuario</strong> derecho alguno sobre las citadas marcas, nombres comerciales y/o signos distintivos y sin que puedan entenderse cedidos al <strong>Usuario</strong>, ninguno de los derechos de explotación que existen o puedan existir sobre dichos Contenidos. De igual modo los Contenidos son propiedad intelectual de <strong>[pdrgpd-sitio]</strong>, o de terceros en su caso, por tanto, los derechos de Propiedad Intelectual son titularidad de <strong>[pdrgpd-sitio]</strong> o de terceros que han autorizado su uso, a quienes corresponde el ejercicio exclusivo de los derechos de explotación de los mismos en cualquier forma y, en especial, los derechos de reproducción, distribución, comunicación pública y transformación. La utilización no autorizada de la información contenida en esta Web, así como la lesión de los derechos de Propiedad Intelectual o Industrial de <strong>[pdrgpd-sitio]</strong> o de terceros incluidos en <strong>[pdrgpd-dominio]</strong> que hayan cedido contenidos dará lugar a las responsabilidades legalmente establecidas.</p>';
	}
	return do_shortcode( $html );
}

add_shortcode( 'pdrgpd-aviso-legal-disponibilidad', 'pdrgpd_aviso_legal_disponibilidad' );
function pdrgpd_aviso_legal_disponibilidad() {
	$locale = get_locale();
	if ( 'ca' === $locale ) {
		$html = '<h3>DISPONIBILITAT DE ' . strtoupper( do_shortcode( '[pdrgpd-dominio]' ) ) . '</h3>
<p><strong>[pdrgpd-sitio]</strong> no garanteix la inexistència d\'interrupcions o errades en l’accés a <strong>[pdrgpd-dominio]</strong>, als seus continguts, ni que aquests es trobin actualitzats, encara que desenvoluparà esforços, per intentar evitar-los,  arranjar-los o actualitzar-los. Per tant,, <strong>[pdrgpd-sitio]</strong> no es responsabilitza dels danys o perjudicis de qualsevol classe produïts en l\'<strong>Usuari</strong> provocats per errades o desconnexions en les xarxes de telecomunicacions que produeixen la suspensió, cancel·lació o interrupció del servei del portal durant la prestació del mateix o amb caràcter previ.</p>

<p><strong>[pdrgpd-sitio]</strong> exclou, excepte les contemplades en la legislació vigent, qualsevol responsabilitat pels danys i perjudicis de tota naturalesa que esdevinguin de la falta de disponibilitat, continuïtat o qualitat del funcionament de <strong>[pdrgpd-dominio]</strong> i dels Continguts, a l’incompliment de l’expectativa d’utilitat que l\'<strong>USUARI</strong> hagués pogut atribuir a <strong>[pdrgpd-dominio]</strong> i als Continguts.</p>

<p>La funció dels Hiperenllaços que apareguin en aquesta Web és exclusivament la d’informar a l\'<strong>Usuari</strong> sobre  l’existència d’altres Web que tenen informació sobre la matèria. Aquests Hiperenllaços no constitueixen suggeriment ni recomanació.</p>

<p><strong>[pdrgpd-sitio]</strong>  no es fa responsable dels continguts de les pàgines enllaçades, del funcionament o ús dels Hiperenllaços ni del resultat dels mateixos, ni garanteix l’absència de virus o altres elements en els mateixos que puguin provocar alteracions en el sistema informàtic (hardware i software), els documents o els fitxers de l\'<strong>Usuari</strong>, excloent qualsevol responsabilitat pels danys de qualsevol classe causats a l\'<strong>Usuari</strong> per aquest motiu.</p>

<p>L\'accés a <strong>[pdrgpd-dominio]</strong> no implica l\'obligació per part de <strong>[pdrgpd-sitio]</strong> de controlar l\'absència de virus, cucs informàtics o qualsevol altre element informàtic danyat. Correspon a l\'<strong>Usuari</strong>, en tot cas, la disponibilitat d’eines adequades per la detecció i desinfecció de programes informàtics danyats, pel que <strong>[pdrgpd-sitio]</strong> no es fa responsable de les possibles errades de seguretat que es produeixin durant la prestació del servei de <strong>[pdrgpd-dominio]</strong>, ni dels possibles danys que puguin donar-se al sistema informàtic de l\'<strong>Usuari</strong> o de tercers (hardware i software), els fitxers o documents emmagatzemats en el mateix, com a conseqüència de la presència de virus en l’ordinador de l\'<strong>Usuari</strong> emprat per la connexió als serveis i continguts de la Web, d\'un mal funcionament del navegador o de l\'ús de versions no actualitzades d\'aquest.</p>

<p>La prestació del servei de <strong>[pdrgpd-dominio]</strong> i dels Continguts té, en principi, una durada indefinida. Tanmateix, <strong>[pdrgpd-sitio]</strong>, qi/o de qualsevol dels Continguts en qualsevol moment. Quan sigui possible, <strong>[pdrgpd-sitio]</strong> advertirà prèviament l\'acabament o suspensió de <strong>[pdrgpd-dominio]</strong>.</p>';
	} else {
		$html = '<h3>DISPONIBILIDAD DE ' . strtoupper( do_shortcode( '[pdrgpd-dominio]' ) ) . '</h3>
<p><strong>[pdrgpd-sitio]</strong> no garantiza la inexistencia de interrupciones o errores en el acceso a <strong>[pdrgpd-dominio]</strong>, a sus contenidos, ni que este se encuentren actualizados, aunque desarrollará sus mejores esfuerzos para, en su caso, evitarlos, subsanarlos o actualizarlos. Por consiguiente, <strong>[pdrgpd-sitio]</strong> no se responsabiliza de los daños o perjuicios de cualquier tipo producidos en el <strong>Usuario</strong> que traigan causa de fallos o desconexiones en las redes de telecomunicaciones que produzcan la suspensión, cancelación o interrupción del servicio del portal durante la prestación del mismo o con carácter previo.</p>

<p><strong>[pdrgpd-sitio]</strong> excluye, con las excepciones contempladas en la legislación vigente, cualquier responsabilidad por los daños y perjuicios de toda naturaleza que puedan deberse a la falta de disponibilidad, continuidad o calidad del funcionamiento de <strong>[pdrgpd-dominio]</strong> y de los Contenidos, al no cumplimiento de la expectativa de utilidad que el <strong>USUARIO</strong> hubiera podido atribuir a <strong>[pdrgpd-dominio]</strong> y a los Contenidos.</p>

<p>La función de los Hiperenlaces que aparecen en esta Web es exclusivamente la de informar al <strong>Usuario</strong> acerca de la existencia de otras Web que contienen información sobre la materia. Dichos Hiperenlaces no constituyen sugerencia ni recomendación alguna.</p>

<p><strong>[pdrgpd-sitio]</strong> no se hace responsable de los contenidos de dichas páginas enlazadas, del funcionamiento o utilidad de los Hiperenlaces ni del resultado de dichos enlaces, ni garantiza la ausencia de virus u otros elementos en los mismos que puedan producir alteraciones en el sistema informático (hardware y software), los documentos o los ficheros del <strong>Usuario</strong>, excluyendo cualquier responsabilidad por los daños de cualquier clase causados al <strong>Usuario</strong> por este motivo.</p>

<p>El acceso a <strong>[pdrgpd-dominio]</strong> no implica la obligación por parte de <strong>[pdrgpd-sitio]</strong> de controlar la ausencia de virus, gusanos o cualquier otro elemento informático dañino. Corresponde al <strong>Usuario</strong>, en todo caso, la disponibilidad de herramientas adecuadas para la detección y desinfección de programas informáticos dañinos, por lo tanto, <strong>[pdrgpd-sitio]</strong> no se hace responsable de los posibles errores de seguridad que se puedan producir durante la prestación del servicio de <strong>[pdrgpd-dominio]</strong>, ni de los posibles daños que puedan causarse al sistema informático del <strong>Usuario</strong> o de terceros (hardware y software), los ficheros o documentos almacenados en el mismo, como consecuencia de la presencia de virus en el ordenador del <strong>Usuario</strong> utilizado para la conexión a los servicios y contenidos de la Web, de un mal funcionamiento del navegador o del uso de versiones no actualizadas del mismo.</p>

<p>La prestación del servicio de <strong>[pdrgpd-dominio]</strong> y de los Contenidos tiene, en principio, duración indefinida. <strong>[pdrgpd-sitio]</strong>, no obstante, queda autorizado para dar por terminada o suspender la prestación del servicio de <strong>[pdrgpd-dominio]</strong> y/o de cualquiera de los Contenidos en cualquier momento. Cuando ello sea razonablemente posible, <strong>[pdrgpd-sitio]</strong> advertirá previamente la terminación o suspensión de <strong>[pdrgpd-dominio]</strong>.</p>';
	}
	return do_shortcode( $html );
}

add_shortcode( 'pdrgpd-aviso-legal-calidad', 'pdrgpd_aviso_legal_calidad' );
function pdrgpd_aviso_legal_calidad() {
	$locale = get_locale();
	if ( 'ca' === $locale ) {
		$html = '<h3>QUALITAT DE ' . strtoupper( do_shortcode( '[pdrgpd-dominio]' ) ) . '</h3>
<p>Donat l’entorn dinàmic i canviant de la informació i dels serveis que se subministren a través de <strong>[pdrgpd-dominio]</strong>, <strong>[pdrgpd-sitio]</strong> posa èmfasi, però no garanteix la completa veracitat, exactitud, fiabilitat, utilitat i/o actualitat dels Continguts. La informació de les pàgines d’aquest Portal només té caràcter informatiu, consultiu, divulgatiu i publicitari. En cap cas, ofereixen o tenen caràcter de compromís vinculant o contractual.</p>';
	} else {
		$html = '<h3>CALIDAD DE ' . strtoupper( do_shortcode( '[pdrgpd-dominio]' ) ) . '</h3>
<p>Dado el entorno dinámico y cambiante de la información y servicios que se suministran por medio de <strong>[pdrgpd-dominio]</strong>, <strong>[pdrgpd-sitio]</strong> realiza su mejor esfuerzo, pero no garantiza la completa veracidad, exactitud, fiabilidad, utilidad y/o actualidad de los Contenidos. La información contenida en las páginas que componen este Portal solo tiene carácter informativo, consultivo, divulgativo y publicitario. En ningún caso ofrecen ni tienen carácter de compromiso vinculante o contractual.</p>';
	}
	return do_shortcode( $html );
}

add_shortcode( 'pdrgpd-aviso-legal-limitacion-responsabilidad', 'pdrgpd_aviso_legal_limitacion_responsabilidad' );
function pdrgpd_aviso_legal_limitacion_responsabilidad() {
	$locale = get_locale();
	if ( 'ca' === $locale ) {
		$html = '<h3>LIMITACIÓ DE RESPONSABILITAT</h3>
<p><strong>[pdrgpd-sitio]</strong> exclou de tota responsabilitat per les decisions que l\'<strong>Usuari</strong> pugui prendre basant-se en aquesta informació, així com per les possibles errades tipogràfiques que puguin tenir els documents i gràfics de <strong>[pdrgpd-dominio]</strong>. La informació està sotmesa a possibles canvis periòdics sense previ avís del seu contingut per ampliació, millora, correcció o actualització dels Continguts.</p>';
	} else {
		$html = '<h3>LIMITACIÓN DE RESPONSABILIDAD</h3>
<p><strong>[pdrgpd-sitio]</strong> excluye toda responsabilidad por las decisiones que el <strong>Usuario</strong> pueda tomar basado en esta información, así como por los posibles errores tipográficos que puedan contener los documentos y gráficos de <strong>[pdrgpd-dominio]</strong>. La información está sometida a posibles cambios periódicos sin previo aviso de su contenido por ampliación, mejora, corrección o actualización de los Contenidos.</p>';
	}
	return do_shortcode( $html );
}

add_shortcode( 'pdrgpd-aviso-legal-notificaciones', 'pdrgpd_aviso_legal_notificaciones' );
function pdrgpd_aviso_legal_notificaciones() {
	$locale = get_locale();
	if ( 'ca' === $locale ) {
		$html = '<h3>NOTIFICACIONS</h3>
<p>Totes les notificacions i comunicacions per part de <strong>[pdrgpd-sitio]</strong> a l\'<strong>Usuari</strong> efectuades per qualsevol mitjà es consideraran eficaces a tots els efectes.</p>';
	} else {
		$html = '<h3>NOTIFICACIONES</h3>
<p>Todas las notificaciones y comunicaciones por parte de <strong>[pdrgpd-sitio]</strong> al <strong>Usuario</strong> realizados por cualquier medio se considerarán eficaces a todos los efectos.</p>';
	}
	return do_shortcode( $html );
}

add_shortcode( 'pdrgpd-aviso-legal-jurisdiccion', 'pdrgpd_aviso_legal_jurisdiccion' );
function pdrgpd_aviso_legal_jurisdiccion() {
	// Para fijar la jurisdicción, ha de estar definida la provincia.
	if ( pdrgpd_conf_provincia() ) {
		$locale = get_locale();
		if ( 'ca' === $locale ) {
			$html = '<h3>JURISDICCIÓ</h3>
<p>Per totes les qüestions que es plantegin sobre la interpretació, aplicació i compliment d’aquest Avís Legal, així com de les reclamacions que puguin derivar-se del seu ús, totes les parts que intervenen se sometent als Jutges i Tribunals de la província de [pdrgpd-provincia], renunciant de forma expressa a qualsevol  fur o jurisdicció que pugui correspondre-li.</p>';
		} else {
			$html = '<h3>JURISDICCIÓN</h3>
<p>Para cuantas cuestiones se susciten sobre la interpretación, aplicación y cumplimiento de este Aviso Legal, así como de las reclamaciones que puedan derivarse de su uso, todas las partes intervinientes se someten a los Jueces y Tribunales de la provincia de [pdrgpd-provincia], renunciando de forma expresa a cualquier otro fuero que pudiera corresponderles.</p>';
		}
		return do_shortcode( $html );
	}
}

add_shortcode( 'pdrgpd-aviso-legal-legislacion', 'pdrgpd_aviso_legal_legislacion' );
function pdrgpd_aviso_legal_legislacion() {
	$locale = get_locale();
	if ( 'ca' === $locale ) {
		$html = '<h3>LEGISLACIÓ APLICABLE</h3>
<p>El present Avís Legal es regeix per la normativa espanyola vigent. </p>';
	} else {
		$html = '<h3>LEGISLACIÓN APLICABLE</h3>
<p>El presente Aviso Legal se rige por la normativa española vigente.</p>';
	}
	return do_shortcode( $html );
}

// Lectura de valores configurados o por defecto.
add_shortcode( 'pdrgpd-titular', 'pdrgpd_conf_titular' );
function pdrgpd_conf_titular() {
	return esc_html( get_option( 'pdrgpd_titular', 'Perico de los Palotes' ) );
}

add_shortcode( 'pdrgpd-nif', 'pdrgpd_conf_nif' );
function pdrgpd_conf_nif() {
	return esc_html( get_option( 'pdrgpd_nif', 'X00000000T' ) );
}

// add_shortcode( 'pdrgpd-nif-iva' , 'pdrgpd_conf_nif' );
function pdrgpd_conf_vies() {
	return esc_html( get_option( 'pdrgpd_vies', '' ) );
}

add_shortcode( 'pdrgpd-direccion', 'pdrgpd_conf_direccion' );
function pdrgpd_conf_direccion() {
	return esc_html( get_option( 'pdrgpd_direccion', '13, Rue del Percebe' ) );
}

add_shortcode( 'pdrgpd-cp', 'pdrgpd_conf_cp' );
function pdrgpd_conf_cp() {
	return esc_html( get_option( 'pdrgpd_cp', '' ) );
}

add_shortcode( 'pdrgpd-poblacion', 'pdrgpd_conf_poblacion' );
function pdrgpd_conf_poblacion() {
	return esc_html( get_option( 'pdrgpd_poblacion', 'Villaconejos de Arriba' ) );
}

add_shortcode( 'pdrgpd-provincia', 'pdrgpd_conf_provincia' );
function pdrgpd_conf_provincia() {
	return esc_html( get_option( 'pdrgpd_provincia', '' ) );
}

function pdrgpd_conf_pais() {
	return esc_html( 'España' );
}

add_shortcode( 'pdrgpd-telefono', 'pdrgpd_html_telefono' );
function pdrgpd_html_telefono() {
	$telefono_conf = pdrgpd_conf_telefono();
	if ( preg_match( '/^\(?\+/', $telefono_conf ) ) {
		$html              = '<a href="tel:';
		$telefono_compacto = '+' . preg_replace( '/\D/', '', $telefono_conf );
		$html             .= $telefono_compacto;
		$html             .= '" title="' . __( 'telephone', 'proteccion-datos-rgpd' ) . '">';
		$html             .= $telefono_conf;
		$html             .= '</a>';
	} else {
		$html = $telefono_conf;
	}
	return $html;
}

function pdrgpd_conf_telefono() {
	return esc_html( get_option( 'pdrgpd_telefono', '' ) );
}

add_shortcode( 'pdrgpd-email', 'pdrgpd_html_email' );
function pdrgpd_html_email() {
	$html  = '<a href="mailto:';
	$html .= antispambot( pdrgpd_conf_email(), 1 );
	$html .= '">';
	$html .= antispambot( pdrgpd_conf_email(), 0 );
	$html .= '</a>';
	return $html;
}

function pdrgpd_conf_email() {
	return esc_html( get_option( 'pdrgpd_email', get_bloginfo( 'admin_email' ) ) );
}

function pdrgpd_inscripcion_vies() {
	if ( pdrgpd_conf_vies() ) {
		$locale = get_locale();
		$html   = ', ' . __( 'enrolled in the', 'proteccion-datos-rgpd' ) . ' ';
		$html  .= pdrgpd_enlace_nueva_ventana( 'https://www2.agenciatributaria.gob.es/viescoes.html', __( 'intra-comunnity operators regisry', 'proteccion-datos-rgpd' ) );
		$html  .= ' ' . __( 'with NIF-IVA', 'proteccion-datos-rgpd' ) . ' ' . pdrgpd_nif_iva( pdrgpd_conf_pais(), pdrgpd_conf_nif() );
		return $html;
	}
}

function pdrgpd_nif_iva( $pais, $iva ) {
	$vat = pdrgpd_codigo_pais( $pais ) . $iva;
	return $vat;
}

function pdrgpd_codigo_pais( $pais ) {
	switch ( $pais ) {
		case 'Alemania':
			$codigo = 'DE';
			break;
		case 'Austria':
			$codigo = 'AT';
			break;
		case 'Bélgica':
			$codigo = 'BE';
			break;
		case 'Bulgaria':
			$codigo = 'BG';
			break;
		case 'Chequia':
			$codigo = 'CZ';
			break;
		case 'Chipre':
			$codigo = 'CY';
			break;
		case 'Croacia':
			$codigo = 'HR';
			break;
		case 'Dinamarca':
			$codigo = 'DK';
			break;
		case 'Eslovaquia':
			$codigo = 'SK';
			break;
		case 'Eslovenia':
			$codigo = 'SI';
			break;
		case 'España':
			$codigo = 'ES';
			break;
		case 'Estonia':
			$codigo = 'EE';
			break;
		case 'Finlandia':
			$codigo = 'FI';
			break;
		case 'Francia':
			$codigo = 'FR';
			break;
		case 'Grecia':
			$codigo = 'EL';
			break;
		case 'Holanda':
			$codigo = 'NL';
			break;
		case 'Hungría':
			$codigo = 'HU';
			break;
		case 'Irlanda':
			$codigo = 'IE';
			break;
		case 'Italia':
			$codigo = 'IT';
			break;
		case 'Letonia':
			$codigo = 'LV';
			break;
		case 'Lituania':
			$codigo = 'LT';
			break;
		case 'Luxemburgo':
			$codigo = 'LU';
			break;
		case 'Malta':
			$codigo = 'MT';
			break;
		case 'Polonia':
			$codigo = 'PL';
			break;
		case 'Portugal':
			$codigo = 'PT';
			break;
		case 'Reino Unido':
			$codigo = 'GB';
			break;
		case 'Rumania':
			$codigo = 'RO';
			break;
		case 'Suecia':
			$codigo = 'SE';
			break;
	}
	return $codigo;
}

/** Inscripción en el Registro Mercantil
// add_shortcode( 'pdrgpd-inscripcion-registro-mercantil' , 'pdrgpd_inscripcion_registro_mercantil' ); */
function pdrgpd_inscripcion_registro_mercantil() {
	$locale = get_locale();
	if ( pdrgpd_conf_rmercant_poblacion() ) {
		$html = ', ' . __( 'registered in the commercial register of', 'proteccion-datos-rgpd' ) . ' ' . pdrgpd_conf_rmercant_poblacion();
		if ( pdrgpd_conf_rmercant_provincia() && pdrgpd_conf_rmercant_poblacion() !== pdrgpd_conf_rmercant_provincia() ) {
			$html .= ', ' . __( 'province of', 'proteccion-datos-rgpd' ) . ' ' . pdrgpd_conf_rmercant_provincia();
		}
		if ( pdrgpd_conf_rmercant_fecha() ) {
			$html .= ' ' . __( 'with date', 'proteccion-datos-rgpd' ) . ' ' . pdrgpd_conf_rmercant_fecha();
		}
		if ( pdrgpd_conf_rmercant_presentacion() ) {
			$html .= ', ' . __( 'presentation', 'proteccion-datos-rgpd' ) . ' ' . pdrgpd_conf_rmercant_presentacion();
		}
		if ( pdrgpd_conf_rmercant_seccion() ) {
			$html .= ', ' . __( 'section', 'proteccion-datos-rgpd' ) . ' ' . pdrgpd_conf_rmercant_seccion();
		}
		if ( pdrgpd_conf_rmercant_libro() ) {
			$html .= ', ' . __( 'book', 'proteccion-datos-rgpd' ) . ' ' . pdrgpd_conf_rmercant_libro();
		}
		if ( pdrgpd_conf_rmercant_tomo() ) {
			$html .= ', ' . __( 'volume', 'proteccion-datos-rgpd' ) . ' ' . pdrgpd_conf_rmercant_tomo();
		}
		if ( pdrgpd_conf_rmercant_folio() ) {
			$html .= ', ' . __( 'folio', 'proteccion-datos-rgpd' ) . ' ' . pdrgpd_conf_rmercant_folio();
		}
		if ( pdrgpd_conf_rmercant_hoja() ) {
			$html .= ', ' . __( 'sheet', 'proteccion-datos-rgpd' ) . ' ' . pdrgpd_conf_rmercant_hoja();
		}
		if ( pdrgpd_conf_rmercant_protocolo() ) {
			$html .= ', ' . __( 'protocol', 'proteccion-datos-rgpd' ) . ' ' . pdrgpd_conf_rmercant_protocolo();
		}
		if ( pdrgpd_conf_rmercant_num() ) {
			$html .= ', ' . __( 'inscription', 'proteccion-datos-rgpd' ) . ' ' . pdrgpd_conf_rmercant_num();
		}
		return $html;
	}
}

function pdrgpd_conf_rmercant_poblacion() {
	return esc_html( get_option( 'pdrgpd_rmercant_poblacion', '' ) );
}

function pdrgpd_conf_rmercant_provincia() {
	return esc_html( get_option( 'pdrgpd_rmercant_provincia', '' ) );
}

function pdrgpd_conf_rmercant_fecha() {
	return esc_html( get_option( 'pdrgpd_rmercant_fecha', '' ) );
}

function pdrgpd_conf_rmercant_presentacion() {
	return esc_html( get_option( 'pdrgpd_rmercant_presentacion', '' ) );
}

function pdrgpd_conf_rmercant_seccion() {
	return esc_html( get_option( 'pdrgpd_rmercant_seccion', '' ) );
}

function pdrgpd_conf_rmercant_libro() {
	return esc_html( get_option( 'pdrgpd_rmercant_libro', '' ) );
}

function pdrgpd_conf_rmercant_tomo() {
	return esc_html( get_option( 'pdrgpd_rmercant_tomo', '' ) );
}

function pdrgpd_conf_rmercant_folio() {
	return esc_html( get_option( 'pdrgpd_rmercant_folio', '' ) );
}

function pdrgpd_conf_rmercant_hoja() {
	return esc_html( get_option( 'pdrgpd_rmercant_hoja', '' ) );
}

function pdrgpd_conf_rmercant_protocolo() {
	return esc_html( get_option( 'pdrgpd_rmercant_protocolo', '' ) );
}

function pdrgpd_conf_rmercant_num() {
	return esc_html( get_option( 'pdrgpd_rmercant_num', '' ) );
}

add_shortcode( 'pdrgpd-sitio', 'pdrgpd_conf_sitio' );
function pdrgpd_conf_sitio() {
	return esc_html( get_option( 'pdrgpd_sitio', get_bloginfo( 'name' ) ) );
}

add_shortcode( 'pdrgpd-dominio', 'pdrgpd_conf_dominio' );
function pdrgpd_conf_dominio() {
	// Quitamos el protocolo al valor por defecto de la home.
	return esc_html( get_option( 'pdrgpd_dominio', explode( '//', get_bloginfo( 'url' ) )[1] ) );
}
add_shortcode( 'pdrgpd-uri-aviso', 'pdrgpd_conf_uri_aviso' );
function pdrgpd_conf_uri_aviso() {
	return esc_url( get_option( 'pdrgpd_uri_aviso', get_bloginfo( 'wpurl' ) . '/aviso-legal/' ) );
}

add_shortcode( 'pdrgpd-uri-privacidad', 'pdrgpd_conf_uri_privacidad' );
function pdrgpd_conf_uri_privacidad() {
	return esc_url( get_option( 'pdrgpd_uri_privacidad', get_bloginfo( 'wpurl' ) . '/privacidad/' ) );
}

add_shortcode( 'pdrgpd-uri-cookies', 'pdrgpd_conf_uri_cookies' );
function pdrgpd_conf_uri_cookies() {
	return esc_url( get_option( 'pdrgpd_uri_cookies', get_bloginfo( 'wpurl' ) . '/cookies/' ) );
}
