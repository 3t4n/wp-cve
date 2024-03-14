<?php

function ammap_tablegen( $shortcode_attributes = null ) {
    
    $anno = $shortcode_attributes['anno'];
    $tipo =  $shortcode_attributes['tipo'];
    $incarico =  $shortcode_attributes['incarico'];
?>

    <p>Anno di Riferimento:
    <?php if ($anno=="all") {
        echo '<b>Tutti</b>';
    } else {
        echo '<b>' . $anno . '</b>';
    }
    ?>
    </p>

<table id="amministrazione-aperta" class="order-table table display">
    <thead>
        <tr>
            <input type="search" id="s" class="light-table-filter" data-table="order-table" placeholder="Cerca...">
        </tr>
        <tr>
            <?php if ($tipo == "incarico") { ?>
                <th colspan="2">Ragione dell'Incarico</th>
                <th>Soggetto percettore</th>
                <th>Compenso lordo</th>
                <th style="min-width: 90px;">Data inizio e fine prestazione</th>
            <?php } else { ?>
                <th colspan="2">Titolo</th>
                <th>Importo</th>
                <th>Beneficiario</th>
                <th>Dati Fiscali</th>
                <th>Norma</th>
                <th>Modalità</th>
                <th style="display:none;">Responsabile</th>
                <th>Determina</th>
                <th>Data</th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>

<?php query_posts( array( 'post_type' => $tipo, 'orderby' => 'date', 'order' => 'DESC', 'posts_per_page' => -1  ) ); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post();

    if ($tipo == "spesa") {
        $a = get_post_meta(get_the_ID(), 'ammap_data', true);
        $b = str_replace( ',', '', $a );
        $a = $b;
        $yearToCompare = date("Y", strtotime($a));
            if ($yearToCompare != $anno && $anno != "all") {
                continue;
            }
        ?>

        <tr>
            <td colspan="2"><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></td>
            <td><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>">€ <?php echo esc_html( get_post_meta(get_the_ID(), 'ammap_importo', true) ); ?></a></td>
            <td><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php echo esc_html( get_post_meta(get_the_ID(), 'ammap_beneficiario', true) ); ?></a></td>
            <td><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php echo esc_html( get_post_meta(get_the_ID(), 'ammap_fiscale', true) ); ?></a></td>
            <td><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php echo esc_html( get_post_meta(get_the_ID(), 'ammap_norma', true) ); ?></a></td>
            <td><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php echo esc_html( get_post_meta(get_the_ID(), 'ammap_assegnazione', true) ); ?></a></td>
            <td style="display:none;"><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php echo esc_html( get_post_meta(get_the_ID(), 'ammap_responsabile', true) ); ?></a></td>
            <td><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php echo esc_html( get_post_meta(get_the_ID(), 'ammap_determina', true) ); ?></a></td>
            <td><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php echo esc_html( date("d/m/Y", strtotime($a)) ); ?></a></td>
       </tr>
       <?php
    } else if ($tipo == "incarico") {

        if ($incarico != 0) {
            if ($incarico == 1) {
                if(!(has_term( 'Incarichi conferiti o autorizzati ai propri dipendenti', 'tipo_incarico' ))) { continue; }
            } else if ($incarico == 2) {
                if(!(has_term( 'Incarichi conferiti a dipendenti di altra Amministrazione', 'tipo_incarico' ))) { continue; }
            } else if ($incarico == 3) {
                if(!(has_term( 'Incarichi conferiti a soggetti estranei alla Pubblica Amministrazione', 'tipo_incarico' ))) { continue; }
            } else {
                echo 'Impossibile valorizzare il campo "incarico"';
                die();
            }
        }

        $yearToCompareInizio = date("Y",strtotime(str_replace('/', '-', get_post_meta(get_the_ID(), 'ammap_data_inizio', true))));
        $yearToCompareFine = date("Y",strtotime(str_replace('/', '-', get_post_meta(get_the_ID(), 'ammap_data_fine', true))));
            if($anno != "all") {
                if ($yearToCompareInizio != $anno && $yearToCompareFine != $anno) {
                    continue;
                }
            }
        ?>

        <tr>
            <td colspan="2"><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></td>
            <td><?php echo esc_html( get_post_meta(get_the_ID(), 'ammap_beneficiario', true) ); ?></td>
            <td><small>Previsto:</small><br>
            € <?php echo esc_html( get_post_meta(get_the_ID(), 'ammap_importo_previsto', true) ); ?>
            <br><small>Erogato:</small><br>€ <?php echo esc_html( get_post_meta(get_the_ID(), 'ammap_importo', true) ); ?></td>
            <td>
                Dal <?php echo esc_html( date("d/m/Y", strtotime(get_post_meta(get_the_ID(), 'ammap_data_inizio', true) ) ) ); ?>
                <?php
                if ( get_post_meta(get_the_ID(), 'ammap_data_fine', true) ) {
                    echo '<br>al '.esc_html( date("d/m/Y", strtotime(get_post_meta(get_the_ID(), 'ammap_data_fine', true) ) ) );
                }
                ?>
            </td>
        </tr>
        <?php

    } else {
        echo '<hr>Parametro "tipo" errato. Impossibile valorizzare il campo<hr>';
    }

endwhile; else: ?>
 <p>Errore query.<br/>
 <small>Si è verificato un errore durante l'esecuzione della query scelta. E' possibile che siano stati impostati parametri errati o che non ci siano dati da elaborare</small></p>
<?php endif; ?>
<?php wp_reset_query(); ?>



    </tbody>
</table>

<?php
echo '<a download="' . get_bloginfo('name') . '-opendata' . $anno . '.xls" href="#" onclick="return ExcellentExport.excel(this, \'amministrazione-aperta\', \'Gare\');"><button>EXCEL</button></a>
<a download="' . get_bloginfo('name') . '-opendata' . $anno . '.csv" href="#" onclick="return ExcellentExport.csv(this, \'amministrazione-aperta\');"><button>CSV</button></a>';
?>
<hr>
<div class="clear"></div>

<?php } ?>