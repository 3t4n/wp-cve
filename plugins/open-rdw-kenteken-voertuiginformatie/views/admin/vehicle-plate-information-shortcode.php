<div id="rdw-shortcode-box">
    <div class="rdw-shortcode-box-content">
        <div id="rdw-shortcode-box-header">
            <h3>
                <?php echo esc_html__('Shortcode genereren', 'open-rdw-kenteken-voertuiginformatie'); ?>
            </h3>

            <p>
                <?php echo esc_html__('Zodra u een pagina of bericht toevoegt of bewerkt, kunt u een shortcode toevoegen in een blok. Via het onderstaande kunt u zelf een shortcode genereren.', 'open-rdw-kenteken-voertuiginformatie'); ?>
            </p>
            <p>
                <?php echo esc_html__('Als u op de titel van een categorie klikt, schuift deze naar beneden en worden alle onderliggende velden weergegeven.', 'open-rdw-kenteken-voertuiginformatie'); ?>
                <?php echo esc_html__('Wanneer u de velden aangevinkt die u wilt weergeven, worden ze toegevoegd aan de shortcode. Klik op het veld onderaan om de shortcode te kopiëren.', 'open-rdw-kenteken-voertuiginformatie'); ?>
            </p>
            <p>
                <?php echo esc_html__('Nadat u uw pagina of bericht heeft opgeslagen, is uw shortcode onmiddellijk actief.', 'open-rdw-kenteken-voertuiginformatie'); ?>
            </p>
            <p>
                <?php echo esc_html__('Selecteer welk veld u wilt weergeven:', 'open-rdw-kenteken-voertuiginformatie'); ?>
            </p>
        </div>
        <div id="rdw-shortcode-box-fields">
            <div class="rdw-sort-fields rdw-expand-fields">
                <ul>
                    <?php
                    $categories = array();

                    foreach($fields as $value) {

                        if(!in_array($value['category'], $categories)) {

                            $categories[] = $value['category'];

                            echo '<li class="ui-sortable">';
                            echo '<a>'.$value['category'].'</a>';
                            echo '<ul style="display:none;">';

                            foreach($fields as $key => $value) {

                                if(end($categories) == $value['category']) {

                                    echo '<li class="ui-sortable-handle">';
                                    echo '<label style="display: block;">';
                                    echo '<input type="checkbox" class="checkbox-field" id="'.$key.'" name="'.$key.'" value="'.$key.'">'.$value['label'];
                                    echo '</label>';
                                    echo '</li>';

                                }

                            }

                            echo '</ul>';
                            echo '</li>';

                        }

                    }
                    ?>
                </ul>
            </div>
        </div>
        <div id="rdw-shortcode-box-footer">
            <div class="generated-shortcode-box">
                <p><strong>Shortcode:</strong></p>
                <input type="text" class="generated-shortcode-text" name="open_rdw_shortcode"
                    class="tag code mx-width-80-pt" readonly="readonly" value="[open_rdw_check]" />
                <span class="copy-tooltip">
                    <?php esc_html_e('Gekopieerd!', 'open-rdw-kenteken-voertuiginformatie') ?>
                </span>
            </div>
        </div>
    </div>
</div>