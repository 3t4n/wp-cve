<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$otherLangsShortcodes = $this->getOtherLangsLegaltextsFromDatabase();
$typeMapping          = [
	'dse'      => 'Datenschutzerklärung',
	'imprint'  => 'Impressum',
	'widerruf' => 'Widerrufsbelehrung',
	'agb'      => 'AGB',
];

$langFullNames = [
	'en' => 'Englisch',
	'fr' => 'Französisch',
	'it' => 'Italienisch',
	'es' => 'Spanisch',
	'nl' => 'Niederländisch',
];

$otherLangsCodes = array_unique(
	array_map( function( $langLegaltext ) {
		return explode( '_', $langLegaltext->type )[1];
	}, $otherLangsShortcodes )
);
?>

<style>
    .pc-tab > input,
    .pc-tab section > div {
        display: none;
    }

    #tab-de:checked ~ section .tab-de <?php echo empty($otherLangsCodes) ? '' : ','; ?><?php echo implode(',', array_map(function ($code) {
        return "#tab-" . esc_attr( $code ) . ":checked~section .tab-" . esc_attr( $code );
    }, $otherLangsCodes));
    ?> {
        display: block;
    }

    #tab-de:checked ~ nav .tab-de <?php echo empty($otherLangsCodes) ? '' : ','; ?><?php echo implode(',', array_map(function ($code) {
        return "#tab-" . esc_attr( $code ) . ":checked~nav .tab-". esc_attr( $code );
    }, $otherLangsCodes));
    ?> {
        color: red;
    }

    /* Visual Styles */
    *,
    *:after,
    *:before {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    body {
        -webkit-font-smoothing: antialiased;
        background: #ecf0f1;
    }

    .pc-tab {
        width: 100%;
        margin: 0 auto;
    }

    .pc-tab ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .pc-tab ul li label {
        float: left;
        padding: 15px 25px;
        border: 1px solid #ddd;
        border-bottom: 0;
        background: #eeeeee;
        color: #444;
    }

    .pc-tab ul li label:hover {
        background: #dddddd;
    }

    .pc-tab ul li label:active {
        background: #ffffff;
    }

    .pc-tab ul li:not(:last-child) label {
        border-right-width: 0;
    }

    .pc-tab section {
        clear: both;
    }

    .pc-tab section > div {
        padding: 20px;
        width: 100%;
        border: 1px solid #ddd;
        background: #fff;
        line-height: 1.5em;
        letter-spacing: 0.3px;
        color: #444;
    }

    .pc-tab section div h2 {
        margin: 0;
        letter-spacing: 1px;
        color: #34495e;
    }

    #tab-de:checked ~ nav .tab-de label <?php echo empty($otherLangsCodes) ? '' : ','; ?><?php echo implode(',', array_map(function ($code) {
        return "#tab-" . esc_attr( $code ) . ":checked~nav .tab-" . esc_attr( $code ) . " label";
    }, $otherLangsCodes));
    ?> {
        background: white;
        color: #111;
        position: relative;
    }

    #tab-de:checked ~ nav .tab-de label:after <?php echo empty($otherLangsCodes) ? '' : ','; ?><?php echo implode(',', array_map(function ($code) {
        return "#tab-" . esc_attr( $code ) . ":checked~nav .tab-" . esc_attr( $code ) . " label:after";
    }, $otherLangsCodes));
    ?> {
        content: "";
        display: block;
        position: absolute;
        height: 2px;
        width: 100%;
        background: #ffffff;
        left: 0;
        bottom: -1px;
    }
</style>

<div class="wrap">
    <style>
        .avlex-api-input-box {
            display: inline-block;
            position: relative;
        }

        .avlex-api-input-box > span.dashicons {
            position: absolute;
            right: 5px;
            top: 3px;
            color: green;
            font-size: 1.4rem;
        }

        .debug-info {
            margin-top: 50px;
        }

        .dse-preview {
            background-color: #fff;
            padding: 20px;
            overflow: auto;
            height: 300px;
        }

        @media only screen and (max-width: 780px) {
            .avlex-api-input-box > span.dashicons {
                position: absolute;
                right: 5px;
                top: 10px;
                color: green;
                font-size: 1.4rem;
            }
        }

        .avlex-api-input-box > input {
            padding-right: 20px;
            width: 200px;
        }
    </style>
    <h1>avalex Einstellungen</h1>

	<?php $this->addNotice(); ?>

    <form method="post" novalidate="novalidate">
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="avalex_api_key">API Key</label>
                </th>
                <td>
                    <div class="avlex-api-input-box">
                        <input type="text" name="avalex_api_key" id="avalex_api_key" value="<?php $this->showApiKey(); ?>">
						<?php if ( $this->isKeyValid ) : ?>
                            <span class="dashicons dashicons-yes"></span>
						<?php endif; ?>
                        <div style="margin-top:5px">
							<?php if ( $this->isKeyValid ) : ?>
                                <span style="color:#008000"> Der API Key ist gültig.</span>
							<?php endif; ?>
                        </div>
                </td>
            </tr>
            </tbody>
        </table>
        <p class="submit">
            <input type="hidden" id="save_avalex" name="save_avalex" value="1"/>
			<?php wp_nonce_field( 'save_avalex' ); ?>
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Speichern"/>
        </p>
        <h2>Shortcodes</h2>
        <style>
            .wp-list-table input {
                max-width: 210px;
                width: 100%;
            }
        </style>
        <table class="wp-list-table widefat fixed striped">
            <thead>
            <tr>
                <th>Shortcode</th>
                <th>Typ</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <input type="text" readonly value="[avalex_de_datenschutz]" onfocus="this.select()">
                </td>
                <td>
                    Datenschutzerklärung
                </td>
            </tr>
            <tr>
                <td>
                    <input type="text" readonly value="[avalex_de_impressum]" onfocus="this.select()">
                </td>
                <td>
                    Impressum
                </td>
            </tr>
			<?php if ( $this->getDseFromDatabase( 'agb' ) ) : ?>
                <tr>
                    <td>
                        <input type="text" readonly value="[avalex_de_agb]" onfocus="this.select()">
                    </td>
                    <td>
                        AGB
                    </td>
                </tr>
			<?php endif; ?>
			<?php if ( $this->getDseFromDatabase( 'widerruf' ) ) : ?>
                <tr>
                    <td>
                        <input type="text" readonly value="[avalex_de_widerrufsbelehrung]" onfocus="this.select()">
                    </td>
                    <td>
                        Widerrufsbelehrung
                    </td>
                </tr>
			<?php endif; ?>

            <!-- Also display other languages shortcodes dynamically from DB -->
			<?php
			foreach ( $otherLangsShortcodes as $otherLangsShortcode ) :
				$typeParts = explode( '_', $otherLangsShortcode->type )
				?>
                <tr>
                    <td>
                        <input type="text" readonly value="[avalex_<?php echo esc_attr( $typeParts[1] ) ?>_<?php echo esc_attr( array_search( $typeParts[0], $this->shortcodes ) ) ?>]" onfocus="this.select()">
                    </td>
                    <td>
						<?php echo esc_html( $typeMapping[$typeParts[0]] ) . " (<strong>" . esc_html( $typeParts[1] ) . "</strong>)" ?>
                    </td>
                </tr>
			<?php endforeach; ?>
            </tbody>
        </table>
    </form>

    <div class="debug-info">
        <h2><?php esc_html_e( 'Debug-Informationen', 'Avalex' ); ?>
        </h2>
        <p>
            <strong>Übermittelte Domain:</strong>
			<?php echo esc_html( $this->trimDomain( home_url( '/' ) ) ); ?>
        </p>


		<?php if ( $this->isKeyValid ) : ?>
            <p>
                <strong>Letztes Update der avalex Rechtstexte: </strong>
				<?php $this->getDseTime(); ?>
            </p>

            <p>
                <strong>Plugin Version:</strong>
				<?php echo esc_html( $this::PLUGIN_VERSION ) ?>
            </p>

            <h1>Rechtstexte Vorschau</h1>

            <div class="pc-tab">
                <input checked="checked" id="tab-de" type="radio" name="pct"/>

				<?php
				foreach ( $otherLangsCodes as $code ) :
					?>
                    <input id="tab-<?php echo esc_attr( $code ) ?>" type="radio" name="pct"/>
				<?php endforeach; ?>

                <nav>
                    <ul>
                        <li class="tab-de">
                            <label for="tab-de">Deutsch</label>
                        </li>
						<?php
						foreach ( $otherLangsCodes as $code ) :
							?>
                            <li class="tab-<?php echo esc_attr( $code ) ?>">
                                <label for="tab-<?php echo esc_attr( $code ) ?>"><?php echo esc_html( $langFullNames[$code] ) ?></label>
                            </li>
						<?php endforeach; ?>
                    </ul>
                </nav>
                <section>
                    <div class="tab-de">
						<?php if ( $this->getDseFromDatabase( 'dse' ) ) : ?>
                            <h3>Vorschau der Datenschutzerklärung (Design kann abweichen)</h3>
                            <hr>
                            <div class="dse-preview">
								<?php echo wp_kses_post( $this->getDseFromDatabase( 'dse' ) ); ?>
                            </div>
                            <hr>
						<?php endif; ?>
						<?php if ( $this->getDseFromDatabase( 'imprint' ) ) : ?>
                            <h3>Vorschau des Impressums (Design kann abweichen)</h3>
                            <div class="dse-preview">
								<?php echo wp_kses_post( $this->getDseFromDatabase( 'imprint' ) ); ?>
                            </div>
						<?php endif; ?>
                        <hr>
						<?php if ( $this->getDseFromDatabase( 'agb' ) ) : ?>
                            <h3>Vorschau der AGB (Design kann abweichen)</h3>
                            <div class="dse-preview">
								<?php echo wp_kses_post( $this->getDseFromDatabase( 'agb' ) ); ?>
                            </div>
                            <hr>
						<?php endif; ?>
						<?php if ( $this->getDseFromDatabase( 'widerruf' ) ) : ?>
                            <h3>Vorschau der Widerrufsbelehrung (Design kann abweichen)</h3>
                            <div class="dse-preview">
								<?php echo wp_kses_post( $this->getDseFromDatabase( 'widerruf' ) ); ?>
                            </div>
                            <hr>
						<?php endif; ?>
                    </div>

					<?php
					foreach ( $otherLangsCodes as $code ) :
						?>
                        <div class="tab-<?php echo esc_attr( $code ) ?>">

							<?php
							$legaltext = array_filter( $otherLangsShortcodes, function( $langLegaltext ) use ( $code ) {
								return ( $langLegaltext->type == "dse_$code" );
							} );
							$legaltext = array_pop( $legaltext );

							if ( $legaltext ) : ?>
                                <h3>Vorschau der Datenschutzerklärung (Design kann abweichen)</h3>
                                <hr>
                                <div class="dse-preview">
									<?php echo wp_kses_post( $legaltext->data ) ?>
                                </div>
                                <hr>
							<?php endif; ?>

							<?php
							$legaltext = array_filter( $otherLangsShortcodes, function( $langLegaltext ) use ( $code ) {
								return ( $langLegaltext->type == "imprint_$code" );
							} );
							$legaltext = array_pop( $legaltext );

							if ( $legaltext ) : ?>
                                <h3>Vorschau des Impressums (Design kann abweichen)</h3>
                                <hr>
                                <div class="dse-preview">
									<?php echo wp_kses_post( $legaltext->data ) ?>
                                </div>
                                <hr>
							<?php endif; ?>

							<?php
							$legaltext = array_filter( $otherLangsShortcodes, function( $langLegaltext ) use ( $code ) {
								return ( $langLegaltext->type == "agb_$code" );
							} );
							$legaltext = array_pop( $legaltext );

							if ( $legaltext ) : ?>
                                <h3>Vorschau der AGB (Design kann abweichen)</h3>
                                <hr>
                                <div class="dse-preview">
									<?php echo wp_kses_post( $legaltext->data ) ?>
                                </div>
                                <hr>
							<?php endif; ?>

							<?php
							$legaltext = array_filter( $otherLangsShortcodes, function( $langLegaltext ) use ( $code ) {
								return ( $langLegaltext->type == "widerruf_$code" );
							} );
							$legaltext = array_pop( $legaltext );

							if ( $legaltext ) : ?>
                                <h3>Vorschau der Widerrufsbelehrung (Design kann abweichen)</h3>
                                <hr>
                                <div class="dse-preview">
									<?php echo wp_kses_post( $legaltext->data ) ?>
                                </div>
                                <hr>
							<?php endif; ?>

                        </div>
					<?php endforeach; ?>

                </section>
            </div>

		<?php endif; ?>
    </div>
</div>