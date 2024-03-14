<?php

namespace OXI_FLIP_BOX_PLUGINS\Page;

/**
 * Description of Admin_Render
 *
 * @author biplo
 */

use OXI_FLIP_BOX_PLUGINS\Classes\Controls as Controls;

class Admin_Render
{

    use \OXI_FLIP_BOX_PLUGINS\Inc_Helper\CSS_JS_Loader;
    use \OXI_FLIP_BOX_PLUGINS\Inc_Helper\Sanitization;

    /**
     * Current Elements Name
     *
     * @since 2.0.0
     */
    public $oxiid;

    /**
     * Current Elements Style Data
     *
     * @since 2.0.0
     */
    public $style = [];

    /**
     * Current Elements Style Full
     *
     * @since 2.0.0
     */
    public $dbdata;

    /**
     * Current Elements Style Data
     *
     * @since 2.0.0
     */
    public $child;

    /**
     * Current Elements Style Data
     *
     * @since 2.0.0
     */
    public $child_editable;

    /**
     * Database Parent Table
     *
     * @since 2.0.0
     */
    public $parent_table;

    /**
     * Database Import Table
     *
     * @since 2.0.0
     */
    public $child_table;

    /**
     * Database Import Table
     *
     * @since 2.0.0
     */
    public $itemid;

    /**
     * Define $wpdb
     *
     * @since 2.0.0
     */
    public $wpdb;

    /**
     * Define $wpdb
     *
     * @since 3.1.0
     */
    public $nonce;

    /**
     * Define Flipbox Plugins Elements Type
     *
     * @since 2.0.0
     */
    public $StyleName;

    public function style()
    {
        return '';
    }

    public function register_style()
    {
        return '';
    }

    public function register_child()
    {
        return '';
    }

   

    public function child_save()
    {
        if (!empty($_POST['oxi-flip-template-modal-submit']) && $_POST['oxi-flip-template-modal-submit'] == 'Submit') {
            if (!wp_verify_nonce($this->nonce, 'oxiflipchildnonce')) {
                die('You do not have sufficient permissions to access this page.');
            } else {
                $id = $_POST['item-id'];
                $child = $this->register_child();
                if ($id == '') {
                    $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->child_table} ( files, styleid) VALUES ( %s, %d)", array($child, $this->oxiid)));
                } else if ($id != '' && is_numeric($id)) {
                    $item_id = (int) $id;
                    $this->wpdb->update("$this->child_table", array("files" => $child), array('id' => $item_id), array('%s'), array('%d'));
                }
            }
        }
    }



    public function rename_shortcode()
    {
        if (!empty($_POST['addonsstylenamechange']) && $_POST['addonsstylenamechange'] == 'Save') {
            if (!wp_verify_nonce($this->nonce, 'oxi-addons-name-change')) {
                die('You do not have sufficient permissions to access this page.');
            } else {
                $name = sanitize_text_field($_POST['oxi-addons-name']);
                $this->wpdb->query($this->wpdb->prepare("UPDATE $this->parent_table SET name = %s WHERE id = %d", $name, $this->oxiid));
            }
        }
    }
    public function register_controls()
    {
        return '';
    }

    public function modal_form_data()
    {
        return '';
    }

    public function style_data()
    {

        if (!empty($_POST['oxi-addons-flip-templates-submit']) && $_POST['oxi-addons-flip-templates-submit'] == 'Submit') {
            if (!wp_verify_nonce($this->nonce, 'oxiflipstylecss')) {
                die('You do not have sufficient permissions to access this page.');
            } else {
                $data = $this->register_style();
                $this->wpdb->query($this->wpdb->prepare("UPDATE $this->parent_table SET css = %s WHERE id = %d", $data, $this->oxiid));
            }
        }
    }

    public function clild()
    {
        return ['title' => '', 'files' => ''];
    }

    /**
     * Template Parent Render
     *
     * @since 2.0.0
     */
    public function render()
    {

        wp_enqueue_script('flipbox-admin' . strtolower($this->dbdata['style_name']), OXI_FLIP_BOX_URL . 'asset/backend/js-files/' . strtolower($this->dbdata['style_name']) . '.js', false, OXI_FLIP_BOX_PLUGIN_VERSION);
?>
        <div class="wrap">
            <div class="oxi-addons-wrapper">
                <?php
                apply_filters('oxi-flip-box-plugin/admin_menu', TRUE);
                ?>
                <div class="oxi-addons-style-20-spacer"></div>
                <div class="oxi-addons-row">
                    <?php
                    apply_filters('oxi-flip-box-support-and-comments', TRUE);
                    ?>
                    <div class="oxi-addons-wrapper oxi-addons-flip-tabs-mode">
                        <div class="oxi-addons-settings" id="oxisettingsreload">
                            <div class="oxi-addons-style-left">
                                <form method="post" id="oxi-addons-form-submit">
                                    <div class="oxi-addons-style-settings">
                                        <div class="oxi-addons-tabs-wrapper">
                                            <div class="oxi-addons-tabs-wrapper">
                                                <ul class="oxi-addons-tabs-ul">
                                                    <li ref="#oxilab-tabs-id-5" class="">
                                                        General
                                                    </li>
                                                    <li ref="#oxilab-tabs-id-4" class="">
                                                        Front
                                                    </li>
                                                    <li ref="#oxilab-tabs-id-3" class="">
                                                        Backend
                                                    </li>
                                                    <li ref="#oxilab-tabs-id-2" class="">
                                                        Custom CSS
                                                    </li>
                                                    <li ref="#oxilab-tabs-id-1">
                                                        Support
                                                    </li>
                                                </ul>
                                                <div class="oxi-addons-tabs-content">
                                                    <?php
                                                    $this->register_controls();
                                                    ?>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="oxi-addons-setting-save">
                                            <?php wp_nonce_field("oxiflipstylecss") ?>
                                            <input type="hidden" id="style-id" name="style-id" value="<?php echo (int) $this->oxiid; ?>">
                                            <button type="button" class="btn btn-danger" id="oxi-addons-setting-reload">Reload</button>
                                            <input type="submit" class="btn btn-primary" name="oxi-addons-flip-templates-submit" value="Submit">
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="oxi-addons-style-right">
                                <div class="oxi-addons-item-form shortcode-addons-templates-right-panel ">
                                    <div class="oxi-addons-item-form-heading shortcode-addons-templates-right-panel-heading">
                                        Add New Flip Boxes
                                        <div class="oxi-head-toggle"></div>
                                    </div>
                                    <div class="oxi-addons-item-form-item shortcode-addons-templates-right-panel-body" id="oxi-addons-list-data-modal-open">
                                        <span>
                                            <i class="dashicons dashicons-plus-alt oxi-icons"></i>
                                            Open Flip Boxes Form
                                        </span>
                                    </div>
                                </div>
                                <div class="oxi-addons-shortcode  shortcode-addons-templates-right-panel ">
                                    <div class="oxi-addons-shortcode-heading  shortcode-addons-templates-right-panel-heading">
                                        Shortcode Name
                                        <div class="oxi-head-toggle"></div>
                                    </div>
                                    <div class="oxi-addons-shortcode-body  shortcode-addons-templates-right-panel-body">
                                        <form method="post">
                                            <div class="input-group my-2">
                                                <input type="text" class="form-control" name="oxi-addons-name" placeholder=" Set Your Shortcode Name" value="<?php echo esc_attr($this->dbdata['name']); ?>">
                                                <div class="input-group-append">
                                                    <input type="submit" class="btn btn-success" name="addonsstylenamechange" value="Save">
                                                </div>
                                            </div>
                                            <?php wp_nonce_field("oxi-addons-name-change") ?>
                                        </form>
                                    </div>
                                </div>
                                <div class="oxi-addons-shortcode shortcode-addons-templates-right-panel ">
                                    <div class="oxi-addons-shortcode-heading  shortcode-addons-templates-right-panel-heading">
                                        Shortcode
                                        <div class="oxi-head-toggle"></div>
                                    </div>
                                    <div class="oxi-addons-shortcode-body shortcode-addons-templates-right-panel-body">
                                        <em>Shortcode for posts/pages/plugins</em>
                                        <p>Copy &amp;
                                            paste the shortcode directly into any WordPress post, page or Page Builder.</p>
                                        <input type="text" class="form-control" onclick="this.setSelectionRange(0, this.value.length)" value="[oxilab_flip_box id=&quot;<?php echo (int) $this->oxiid; ?>&quot;]">
                                        <span></span>
                                        <em>Shortcode for templates/themes</em>
                                        <p>Copy &amp;
                                            paste this code into a template file to include the slideshow within your theme.</p>
                                        <input type="text" class="form-control" onclick="this.setSelectionRange(0, this.value.length)" value="<?php echo '<?php echo do_shortcode(\'[oxilab_flip_box  id=&quot;' . (int) $this->oxiid . '&quot;]\'); ?>'; ?>">
                                        <span></span>
                                    </div>
                                </div>
                                <div class="oxi-addons-item-form shortcode-addons-templates-right-panel ">
                                    <div class="oxi-addons-item-form-heading shortcode-addons-templates-right-panel-heading">
                                        Flipbox Rearrange
                                        <div class="oxi-head-toggle"></div>
                                    </div>
                                    <div class="oxi-addons-item-form-item shortcode-addons-templates-right-panel-body" id="oxi-addons-rearrange-data-modal-open">
                                        <span>
                                            <i class="dashicons dashicons-plus-alt oxi-icons"></i>
                                            Flip Data Rearrange
                                        </span>
                                    </div>
                                </div>
                                <div id="oxi-addons-list-rearrange-modal" class="modal fade bd-example-modal-sm" role="dialog">
                                    <div class="modal-dialog modal-sm">
                                        <form id="oxi-addons-form-rearrange-submit">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Flipbox Rearrange</h4>
                                                    <button type="button" class="close" data-dismiss="modal">×</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="col-12 alert text-center" id="oxi-addons-list-rearrange-saving">
                                                        <i class="fa fa-spinner fa-spin"></i>
                                                    </div>
                                                    <ul class="col-12 list-group oxi-addons-modal-rearrange" id="oxi-addons-modal-rearrange">
                                                        <?php
                                                        $r = $this->Rearrange();
                                                        foreach ($this->child as $value) {
                                                            $val = explode('{#}|{#}', $value['files']);
                                                            if ($r['tag'] == 'title') :
                                                                echo '<li class="list-group-item" id="' . esc_attr($value['id']) . '">' . esc_html($val[$r['id']]) . '</li>';
                                                            endif;
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" id="oxi-addons-list-rearrange-data">
                                                    <button type="button" id="oxi-addons-list-rearrange-close" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                    <input type="submit" id="oxi-addons-list-rearrange-submit" class="btn btn-primary" value="Save">
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="oxi-addons-list-data-modal">
                                <div class="modal-dialog">
                                    <form method="post" id="oxi-flip-template-modal-form">
                                        <div class="modal-content">
                                            <?php $this->modal_form_data(); ?>
                                            <div class="modal-footer">
                                                <input type="hidden" id="item-id" name="item-id" value="<?php echo (int) $this->itemid ?>">
                                                <input type="hidden" id="shortcodeitemid" name="shortcodeitemid" value="">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                <input type="submit" id="oxi-flip-template-modal-submit" name="oxi-flip-template-modal-submit" class="btn btn-success" value="Submit">
                                            </div>
                                        </div>
                                        <?php wp_nonce_field("oxiflipchildnonce") ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="oxi-addons-Preview" id="oxipreviewreload">
                            <div class="oxi-addons-wrapper">
                                <div class="oxi-addons-style-left-preview">
                                    <div class="oxi-addons-style-left-preview-heading">
                                        <div class="oxi-addons-style-left-preview-heading-left oxi-addons-flip-tabs-sortable-title">
                                            Preview
                                        </div>
                                        <div class="oxi-addons-style-left-preview-heading-right">
                                            <input type="text" data-format="rgb" data-opacity="TRUE" class="oxilab-vendor-color" id="oxi-addons-flip-2-0-color" value="#FFF" oxiexportid="#oxi-addons-preview-data" oxiexporttype="background">
                                        </div>
                                    </div>
                                    <div class="oxi-addons-preview-data" id="oxi-addons-preview-data">
                                        <?php
                                        $cls = '\OXI_FLIP_BOX_PLUGINS\Public_Render\\' . $this->StyleName . '';
                                        new $cls($this->dbdata, $this->child, 'admin');
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
<?php
    }
    public function child_edit()
    {
        if (!empty($_POST['edit']) && is_numeric($_POST['item-id'])) {
            if (!wp_verify_nonce($this->nonce, 'oxiflipeditdata')) {
                die('You do not have sufficient permissions to access this page.');
            } else {
                $item_id = (int) $_POST['item-id'];
                $child = $this->wpdb->get_row($this->wpdb->prepare('SELECT * FROM ' . $this->child_table . ' WHERE id = %d ', $item_id), ARRAY_A);
                $demos = '{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}';
                $hild_editable = explode('{#}|{#}', $demos);
                $d_editable = explode('{#}|{#}', $child['files']);
                $this->child_editable = array_merge($d_editable, $hild_editable);
                $this->itemid = $child['id'];
                echo '<script type="text/javascript"> jQuery(document).ready(function () {setTimeout(function() { jQuery("#oxi-addons-list-data-modal").modal("show")  }, 500); });</script>';
            }
        }
    }

    public function Delete_child_data()
    {
        if (!empty($_POST['delete']) && is_numeric($_POST['item-id'])) {
            if (!wp_verify_nonce($this->nonce, 'oxiflipdeletedata')) {
                die('You do not have sufficient permissions to access this page.');
            } else {
                $item_id = (int) $_POST['item-id'];
                $this->wpdb->query($this->wpdb->prepare("DELETE FROM {$this->child_table} WHERE id = %d ", $item_id));
            }
        }
    }

    public function import_font_family()
    {
        $this->font_family = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM $this->import_table WHERE type = %s ORDER by id ASC", 'oxi-addons-flip'), ARRAY_A);
        $google = $custom = '';
        foreach ($this->font_family as $key => $value) {
            if ($value['name'] == 'custom') {
                $custom .= '|' . $value['font'];
            } else {
                $google .= '|' . $value['font'];
                $this->google_font[$value['font']] = $value['font'];
            }
        }
        if ($google == '') :
            $google = '|ABeeZee|Abel|Abhaya+Libre|Abril+Fatface|Aclonica|Acme|Actor|Adamina|Advent+Pro|Aguafina+Script|Akronim|Aladin|Aldrich|Alef|Alegreya|Alegreya+SC|Alegreya+Sans|Alegreya+Sans+SC|Aleo|Alex+Brush|Alfa+Slab+One|Alice|Alike|Alike+Angular|Allan|Allerta|Allerta+Stencil|Allura|Almarai|Almendra|Almendra+Display|Almendra+SC|Amarante|Amaranth|Amatic+SC|Amethysta|Amiko|Amiri|Amita|Anaheim|Andada|Andika|Angkor|Annie+Use+Your+Telescope|Anonymous+Pro|Antic|Antic+Didone|Antic+Slab|Anton|Arapey|Arbutus|Arbutus+Slab|Architects+Daughter|Archivo|Archivo+Black|Archivo+Narrow|Aref+Ruqaa|Arima+Madurai|Arimo|Arizonia|Armata|Arsenal|Artifika|Arvo|Arya|Asap|Asap+Condensed|Asar|Asset|Assistant|Astloch|Asul|Athiti|Atma|Atomic+Age|Aubrey|Audiowide|Autour+One|Average|Average+Sans|Averia+Gruesa+Libre|Averia+Libre|Averia+Sans+Libre|Averia+Serif+Libre|B612|B612+Mono|Bad+Script|Bahiana|Bahianita|Bai+Jamjuree|Baloo|Baloo+Bhai|Baloo+Bhaijaan|Baloo+Bhaina|Baloo+Chettan|Baloo+Da|Baloo+Paaji|Baloo+Tamma|Baloo+Tammudu|Baloo+Thambi|Balthazar|Bangers|Barlow|Barlow+Condensed|Barlow+Semi+Condensed|Barriecito|Barrio|Basic|Battambang|Baumans|Bayon|Be+Vietnam|Belgrano|Bellefair|Belleza|BenchNine|Bentham|Berkshire+Swash|Beth+Ellen|Bevan|Big+Shoulders+Display|Big+Shoulders+Text|Bigelow+Rules|Bigshot+One|Bilbo|Bilbo+Swash+Caps|BioRhyme|BioRhyme+Expanded|Biryani|Bitter|Black+And+White+Picture|Black+Han+Sans|Black+Ops+One|Blinker|Bokor|Bonbon|Boogaloo|Bowlby+One|Bowlby+One+SC|Brawler|Bree+Serif|Bubblegum+Sans|Bubbler+One|Buda|Buenard|Bungee|Bungee+Hairline|Bungee+Inline|Bungee+Outline|Bungee+Shade|Butcherman|Butterfly+Kids|Cabin|Cabin+Condensed|Cabin+Sketch|Caesar+Dressing|Cagliostro|Cairo|Calligraffitti|Cambay|Cambo|Candal|Cantarell|Cantata+One|Cantora+One|Capriola|Cardo|Carme|Carrois+Gothic|Carrois+Gothic+SC|Carter+One|Catamaran|Caudex|Caveat|Caveat+Brush|Cedarville+Cursive|Ceviche+One|Chakra+Petch|Changa|Changa+One|Chango|Charm|Charmonman|Chathura|Chau+Philomene+One|Chela+One|Chelsea+Market|Chenla|Cherry+Cream+Soda|Cherry+Swash|Chewy|Chicle|Chilanka|Chivo|Chonburi|Cinzel|Cinzel+Decorative|Clicker+Script|Coda|Coda+Caption|Codystar|Coiny|Combo|Comfortaa|Coming+Soon|Concert+One|Condiment|Content|Contrail+One|Convergence|Cookie|Copse|Corben|Cormorant|Cormorant+Garamond|Cormorant+Infant|Cormorant+SC|Cormorant+Unicase|Cormorant+Upright|Courgette|Cousine|Coustard|Covered+By+Your+Grace|Crafty+Girls|Creepster|Crete+Round|Crimson+Pro|Crimson+Text|Croissant+One|Crushed|Cuprum|Cute+Font|Cutive|Cutive+Mono|DM+Sans|DM+Serif+Display|DM+Serif+Text|Damion|Dancing+Script|Dangrek|Darker+Grotesque|David+Libre|Dawning+of+a+New+Day|Days+One|Dekko|Delius|Delius+Swash+Caps|Delius+Unicase|Della+Respira|Denk+One|Devonshire|Dhurjati|Didact+Gothic|Diplomata|Diplomata+SC|Do+Hyeon|Dokdo|Domine|Donegal+One|Doppio+One|Dorsa|Dosis|Dr+Sugiyama|Duru+Sans|Dynalight|EB+Garamond|Eagle+Lake|East+Sea+Dokdo|Eater|Economica|Eczar|El+Messiri|Electrolize|Elsie|Elsie+Swash+Caps|Emblema+One|Emilys+Candy|Encode+Sans|Encode+Sans+Condensed|Encode+Sans+Expanded|Encode+Sans+Semi+Condensed|Encode+Sans+Semi+Expanded|Engagement|Englebert|Enriqueta|Erica+One|Esteban|Euphoria+Script|Ewert|Exo|Exo+2|Expletus+Sans|Fahkwang|Fanwood+Text|Farro|Farsan|Fascinate|Fascinate+Inline|Faster+One|Fasthand|Fauna+One|Faustina|Federant|Federo|Felipa|Fenix|Finger+Paint|Fira+Code|Fira+Mono|Fira+Sans|Fira+Sans+Condensed|Fira+Sans+Extra+Condensed|Fjalla+One|Fjord+One|Flamenco|Flavors|Fondamento|Fontdiner+Swanky|Forum|Francois+One|Frank+Ruhl+Libre|Freckle+Face|Fredericka+the+Great|Fredoka+One|Freehand|Fresca|Frijole|Fruktur|Fugaz+One|GFS+Didot|GFS+Neohellenic|Gabriela|Gaegu|Gafata|Galada|Galdeano|Galindo|Gamja+Flower|Gayathri|Gentium+Basic|Gentium+Book+Basic|Geo|Geostar|Geostar+Fill|Germania+One|Gidugu|Gilda+Display|Give+You+Glory|Glass+Antiqua|Glegoo|Gloria+Hallelujah|Goblin+One|Gochi+Hand|Gorditas|Gothic+A1|Goudy+Bookletter+1911|Graduate|Grand+Hotel|Gravitas+One|Great+Vibes|Grenze|Griffy|Gruppo|Gudea|Gugi|Gurajada|Habibi|Halant|Hammersmith+One|Hanalei|Hanalei+Fill|Handlee|Hanuman|Happy+Monkey|Harmattan|Headland+One|Heebo|Henny+Penny|Hepta+Slab|Herr+Von+Muellerhoff|Hi+Melody|Hind|Hind+Guntur|Hind+Madurai|Hind+Siliguri|Hind+Vadodara|Holtwood+One+SC|Homemade+Apple|Homenaje|IBM+Plex+Mono|IBM+Plex+Sans|IBM+Plex+Sans+Condensed|IBM+Plex+Serif|IM+Fell+DW+Pica|IM+Fell+DW+Pica+SC|IM+Fell+Double+Pica|IM+Fell+Double+Pica+SC|IM+Fell+English|IM+Fell+English+SC|IM+Fell+French+Canon|IM+Fell+French+Canon+SC|IM+Fell+Great+Primer|IM+Fell+Great+Primer+SC|Iceberg|Iceland|Imprima|Inconsolata|Inder|Indie+Flower|Inika|Inknut+Antiqua|Irish+Grover|Istok+Web|Italiana|Italianno|Itim|Jacques+Francois|Jacques+Francois+Shadow|Jaldi|Jim+Nightshade|Jockey+One|Jolly+Lodger|Jomhuria|Josefin+Sans|Josefin+Slab|Joti+One|Jua|Judson|Julee|Julius+Sans+One|Junge|Jura|Just+Another+Hand|Just+Me+Again+Down+Here|K2D|Kadwa|Kalam|Kameron|Kanit|Kantumruy|Karla|Karma|Katibeh|Kaushan+Script|Kavivanar|Kavoon|Kdam+Thmor|Keania+One|Kelly+Slab|Kenia|Khand|Khmer|Khula|Kirang+Haerang|Kite+One|Knewave|KoHo|Kodchasan|Kosugi|Kosugi+Maru|Kotta+One|Koulen|Kranky|Kreon|Kristi|Krona+One|Krub|Kumar+One|Kumar+One+Outline|Kurale|La+Belle+Aurore|Lacquer|Laila|Lakki+Reddy|Lalezar|Lancelot|Lateef|Lato|League+Script|Leckerli+One|Ledger|Lekton|Lemon|Lemonada|Lexend+Deca|Lexend+Exa|Lexend+Giga|Lexend+Mega|Lexend+Peta|Lexend+Tera|Lexend+Zetta|Libre+Barcode+128|Libre+Barcode+128+Text|Libre+Barcode+39|Libre+Barcode+39+Extended|Libre+Barcode+39+Extended+Text|Libre+Barcode+39+Text|Libre+Baskerville|Libre+Caslon+Display|Libre+Caslon+Text|Libre+Franklin|Life+Savers|Lilita+One|Lily+Script+One|Limelight|Linden+Hill|Literata|Liu+Jian+Mao+Cao|Livvic|Lobster|Lobster+Two|Londrina+Outline|Londrina+Shadow|Londrina+Sketch|Londrina+Solid|Long+Cang|Lora|Love+Ya+Like+A+Sister|Loved+by+the+King|Lovers+Quarrel|Luckiest+Guy|Lusitana|Lustria|M+PLUS+1p|M+PLUS+Rounded+1c|Ma+Shan+Zheng|Macondo|Macondo+Swash+Caps|Mada|Magra|Maiden+Orange|Maitree|Major+Mono+Display|Mako|Mali|Mallanna|Mandali|Manjari|Mansalva|Manuale|Marcellus|Marcellus+SC|Marck+Script|Margarine|Markazi+Text|Marko+One|Marmelad|Martel|Martel+Sans|Marvel|Mate|Mate+SC|Maven+Pro|McLaren|Meddon|MedievalSharp|Medula+One|Meera+Inimai|Megrim|Meie+Script|Merienda|Merienda+One|Merriweather|Merriweather+Sans|Metal|Metal+Mania|Metamorphous|Metrophobic|Michroma|Milonga|Miltonian|Miltonian+Tattoo|Mina|Miniver|Miriam+Libre|Mirza|Miss+Fajardose|Mitr|Modak|Modern+Antiqua|Mogra|Molengo|Molle|Monda|Monofett|Monoton|Monsieur+La+Doulaise|Montaga|Montez|Montserrat|Montserrat+Alternates|Montserrat+Subrayada|Moul|Moulpali|Mountains+of+Christmas|Mouse+Memoirs|Mr+Bedfort|Mr+Dafoe|Mr+De+Haviland|Mrs+Saint+Delafield|Mrs+Sheppards|Mukta|Mukta+Mahee|Mukta+Malar|Mukta+Vaani|Muli|Mystery+Quest|NTR|Nanum+Brush+Script|Nanum+Gothic|Nanum+Gothic+Coding|Nanum+Myeongjo|Nanum+Pen+Script|Neucha|Neuton|New+Rocker|News+Cycle|Niconne|Niramit|Nixie+One|Nobile|Nokora|Norican|Nosifer|Notable|Nothing+You+Could+Do|Noticia+Text|Noto+Sans|Noto+Sans+HK|Noto+Sans+JP|Noto+Sans+KR|Noto+Sans+SC|Noto+Sans+TC|Noto+Serif|Noto+Serif+JP|Noto+Serif+KR|Noto+Serif+SC|Noto+Serif+TC|Nova+Cut|Nova+Flat|Nova+Mono|Nova+Oval|Nova+Round|Nova+Script|Nova+Slim|Nova+Square|Numans|Nunito|Nunito+Sans|Odor+Mean+Chey|Offside|Old+Standard+TT|Oldenburg|Oleo+Script|Oleo+Script+Swash+Caps|Open+Sans|Open+Sans+Condensed|Oranienbaum|Orbitron|Oregano|Orienta|Original+Surfer|Oswald|Over+the+Rainbow|Overlock|Overlock+SC|Overpass|Overpass+Mono|Ovo|Oxygen|Oxygen+Mono|PT+Mono|PT+Sans|PT+Sans+Caption|PT+Sans+Narrow|PT+Serif|PT+Serif+Caption|Pacifico|Padauk|Palanquin|Palanquin+Dark|Pangolin|Paprika|Parisienne|Passero+One|Passion+One|Pathway+Gothic+One|Patrick+Hand|Patrick+Hand+SC|Pattaya|Patua+One|Pavanam|Paytone+One|Peddana|Peralta|Permanent+Marker|Petit+Formal+Script|Petrona|Philosopher|Piedra|Pinyon+Script|Pirata+One|Plaster|Play|Playball|Playfair+Display|Playfair+Display+SC|Podkova|Poiret+One|Poller+One|Poly|Pompiere|Pontano+Sans|Poor+Story|Poppins|Port+Lligat+Sans|Port+Lligat+Slab|Pragati+Narrow|Prata|Preahvihear|Press+Start+2P|Pridi|Princess+Sofia|Prociono|Prompt|Prosto+One|Proza+Libre|Puritan|Purple+Purse|Quando|Quantico|Quattrocento|Quattrocento+Sans|Questrial|Quicksand|Quintessential|Qwigley|Racing+Sans+One|Radley|Rajdhani|Rakkas|Raleway|Raleway+Dots|Ramabhadra|Ramaraja|Rambla|Rammetto+One|Ranchers|Rancho|Ranga|Rasa|Rationale|Ravi+Prakash|Red+Hat+Display|Red+Hat+Text|Redressed|Reem+Kufi|Reenie+Beanie|Revalia|Rhodium+Libre|Ribeye|Ribeye+Marrow|Righteous|Risque|Roboto|Roboto+Condensed|Roboto+Mono|Roboto+Slab|Rochester|Rock+Salt|Rokkitt|Romanesco|Ropa+Sans|Rosario|Rosarivo|Rouge+Script|Rozha+One|Rubik|Rubik+Mono+One|Ruda|Rufina|Ruge+Boogie|Ruluko|Rum+Raisin|Ruslan+Display|Russo+One|Ruthie|Rye|Sacramento|Sahitya|Sail|Saira|Saira+Condensed|Saira+Extra+Condensed|Saira+Semi+Condensed|Saira+Stencil+One|Salsa|Sanchez|Sancreek|Sansita|Sarabun|Sarala|Sarina|Sarpanch|Satisfy|Sawarabi+Gothic|Sawarabi+Mincho|Scada|Scheherazade|Schoolbell|Scope+One|Seaweed+Script|Secular+One|Sedgwick+Ave|Sedgwick+Ave+Display|Sevillana|Seymour+One|Shadows+Into+Light|Shadows+Into+Light+Two|Shanti|Share|Share+Tech|Share+Tech+Mono|Shojumaru|Short+Stack|Shrikhand|Siemreap|Sigmar+One|Signika|Signika+Negative|Simonetta|Single+Day|Sintony|Sirin+Stencil|Six+Caps|Skranji|Slabo+13px|Slabo+27px|Slackey|Smokum|Smythe|Sniglet|Snippet|Snowburst+One|Sofadi+One|Sofia|Song+Myung|Sonsie+One|Sorts+Mill+Goudy|Source+Code+Pro|Source+Sans+Pro|Source+Serif+Pro|Space+Mono|Special+Elite|Spectral|Spectral+SC|Spicy+Rice|Spinnaker|Spirax|Squada+One|Sree+Krushnadevaraya|Sriracha|Srisakdi|Staatliches|Stalemate|Stalinist+One|Stardos+Stencil|Stint+Ultra+Condensed|Stint+Ultra+Expanded|Stoke|Strait|Stylish|Sue+Ellen+Francisco|Suez+One|Sumana|Sunflower|Sunshiney|Supermercado+One|Sura|Suranna|Suravaram|Suwannaphum|Swanky+and+Moo+Moo|Syncopate|Tajawal|Tangerine|Taprom|Tauri|Taviraj|Teko|Telex|Tenali+Ramakrishna|Tenor+Sans|Text+Me+One|Thasadith|The+Girl+Next+Door|Tienne|Tillana|Timmana|Tinos|Titan+One|Titillium+Web|Trade+Winds|Trirong|Trocchi|Trochut|Trykker|Tulpen+One|Turret+Road|Ubuntu|Ubuntu+Condensed|Ubuntu+Mono|Ultra|Uncial+Antiqua|Underdog|Unica+One|UnifrakturCook|UnifrakturMaguntia|Unkempt|Unlock|Unna|VT323|Vampiro+One|Varela|Varela+Round|Vast+Shadow|Vesper+Libre|Vibes|Vibur|Vidaloka|Viga|Voces|Volkhov|Vollkorn|Vollkorn+SC|Voltaire|Waiting+for+the+Sunrise|Wallpoet|Walter+Turncoat|Warnes|Wellfleet|Wendy+One|Wire+One|Work+Sans|Yanone+Kaffeesatz|Yantramanav|Yatra+One|Yellowtail|Yeon+Sung|Yeseva+One|Yesteryear|Yrsa|ZCOOL+KuaiLe|ZCOOL+QingKe+HuangYou|ZCOOL+XiaoWei|Zeyada|Zhi+Mang+Xing|Zilla+Slab|Zilla+Slab+Highlight';
            $g = explode('|', $google);
            foreach ($g as $value) {
                if (!empty($value)) :
                    $this->google_font[$value] = $value;
                endif;
            }
        endif;

        $custom .= '|Arial|Helvetica+Neue|Courier+New|Times+New+Roman|Comic+Sans+MS|Verdana|Impact|cursive|inherit';

        $data = 'jQuery.noConflict();
                (function($){   setTimeout(function () {
                                    $("#oxi-addons-modal-rearrange").sortable({
                                        axis: "y",
                                        update: function (event, ui) {
                                            var list_sortable = jQuery(this).sortable("toArray").toString();
                                            console.log(list_sortable);
                                            jQuery("#oxi-addons-list-rearrange-data").val(list_sortable);
                                        }
                                    });
                                }, 2000);
                                $("#oxi-addons-form-rearrange-submit").on("submit", function (e) {
                                    e.preventDefault();
                                    var rawdata = $("#oxi-addons-list-rearrange-data").val();
                                    $("#oxi-addons-modal-rearrange").hide();
                                    $("#oxi-addons-list-rearrange-saving").show();
                                    $.ajax({
                                        url: "' . admin_url('admin-ajax.php') . '",
                                        type: "post",
                                        data: {
                                            action: "oxi_flip_box_data",
                                            _wpnonce: "' . wp_create_nonce('oxi-flip-box-editor') . '",
                                            functionname: "addons_rearrange",
                                            rawdata: rawdata,
                                            styleid: "",
                                            childid: "",
                                        },
                                        success: function (response) {
                                            console.log(response);
                                            setTimeout(function () {
                                                location.reload();
                                            }, 1000);
                                        }
                                    });
                                });
                                var fontsLoaded = {};
                                $.fn.fontselect = function(options) {
                                        var __bind = function(fn, me) { return function(){ return fn.apply(me, arguments); }; };

                                        var settings = {
                                                style: \'font-select\',
                                                placeholder: \'Select a font\',
                                                placeholderSearch: \'Search...\',
                                                searchable: true,
                                                lookahead: 2,
                                                googleApi: \'https://fonts.googleapis.com/css?family=\',
                                                localFontsUrl: \'/fonts/\',
                                                systemFonts: \'' . $this->str_replace_first('|', '', $custom) . '\'.split(\'|\'),
                                                googleFonts: \'' . $this->str_replace_first('|', '', $google) . '\'.split(\'|\')
                                        };

                                        var Fontselect = (function(){

                                                function Fontselect(original, o) {
                                                        if (!o.systemFonts) { o.systemFonts = []; }
                                                        if (!o.localFonts) { o.localFonts = []; }
                                                        if (!o.googleFonts) { o.googleFonts = []; }
                                                        this.options = o;
                                                        this.$original = $(original);
                                                        this.setupHtml();
                                                        this.getVisibleFonts();
                                                        this.bindEvents();
                                                        this.query = \'\';
                                                        this.keyActive = false;
                                                        this.searchBoxHeight = 0;

                                                        var font = this.$original.val();
                                                        if (font) {
                                                                this.updateSelected();
                                                                this.addFontLink(font);
                                                        }
                                                }

                                                Fontselect.prototype = {
                                                        keyDown: function(e) {

                                                                function stop(e) {
                                                                        e.preventDefault();
                                                                        e.stopPropagation();
                                                                }

                                                                this.keyActive = true;
                                                                if (e.keyCode == 27) {// Escape
                                                                        stop(e);
                                                                        this.toggleDropdown(\'hide\');
                                                                        return;
                                                                }
                                                                if (e.keyCode == 38) {// Cursor up
                                                                        stop(e);
                                                                        var $li = $(\'li.active\', this.$results), $pli = $li.prev(\'li\');
                                                                        if ($pli.length > 0) {
                                                                                $li.removeClass(\'active\');
                                                                                this.$results.scrollTop($pli.addClass(\'active\')[0].offsetTop - this.searchBoxHeight);
                                                                        }
                                                                        return;
                                                                }
                                                                if (e.keyCode == 40) {// Cursor down
                                                                        stop(e);
                                                                        var $li = $(\'li.active\', this.$results), $nli = $li.next(\'li\');
                                                                        if ($nli.length > 0) {
                                                                                $li.removeClass(\'active\');
                                                                                this.$results.scrollTop($nli.addClass(\'active\')[0].offsetTop - this.searchBoxHeight);
                                                                        }
                                                                        return;
                                                                }
                                                                if (e.keyCode == 13) {// Enter
                                                                        stop(e);
                                                                        $(\'li.active\', this.$results).trigger(\'click\');
                                                                        return;
                                                                }
                                                                this.query += String.fromCharCode(e.keyCode).toLowerCase();
                                                                var $found = $("li[data-query^=\'"+ this.query +"\']").first();
                                                                if ($found.length > 0) {
                                                                        $(\'li.active\', this.$results).removeClass(\'active\');
                                                                        this.$results.scrollTop($found.addClass(\'active\')[0].offsetTop);
                                                                }
                                                        },

                                                        keyUp: function(e) {
                                                                this.keyActive = false;
                                                        },

                                                        bindEvents: function() {
                                                                var self = this;

                                                                $(\'li\', this.$results)
                                                                .click(__bind(this.selectFont, this))
                                                                .mouseover(__bind(this.activateFont, this));

                                                                this.$select.click(__bind(function() { self.toggleDropdown(\'show\') }, this));

                                                                // Call like so: $("input[name=\'ffSelect\']").trigger(\'setFont\', [fontFamily, fontWeight]);
                                                                this.$original.on(\'setFont\', function(evt, fontFamily, fontWeight) {
                                                                        fontWeight = fontWeight || 400;

                                                                        var fontSpec = fontFamily.replace(/ /g, \'+\') + (fontWeight == 400 ? \'\' : \':\'+fontWeight);

                                                                        var $li = $("li[data-value=\'"+ fontSpec +"\']", self.$results);
                                                                        if ($li.length == 0) {
                                                                                fontSpec = fontFamily.replace(/ /g, \'+\');
                                                                        }
                                                                        $li = $("li[data-value=\'"+ fontSpec +"\']", self.$results);
                                                                        $(\'li.active\', self.$results).removeClass(\'active\');
                                                                        $li.addClass(\'active\');

                                                                        self.$original.val(fontSpec);
                                                                        self.updateSelected();
                                                                        self.addFontLink($li.data(\'value\'));
                                                                        $li.trigger(\'click\');
                                                                });
                                                                this.$original.on(\'change\', function() {
                                                                        self.updateSelected();
                                                                        self.addFontLink($(\'li.active\', self.$results).data(\'value\'));
                                                                });

                                                                if (this.options.searchable) {
                                                                        this.$input.on(\'keyup\', function() {
                                                                                var q = this.value.toLowerCase();
                                                                                // Hide options that don\'t match query:
                                                                                $(\'li\', self.$results).each(function() {
                                                                                        if ($(this).text().toLowerCase().indexOf(q) == -1) {
                                                                                                $(this).hide();
                                                                                        }
                                                                                        else {
                                                                                                $(this).show();
                                                                                        }
                                                                                })
                                                                        })
                                                                }

                                                                $(document).on(\'click\', function(e) {
                                                                        if ($(e.target).closest(\'.\'+self.options.style).length === 0) {
                                                                                self.toggleDropdown(\'hide\');
                                                                        }
                                                                });
                                                        },

                                                        toggleDropdown: function(hideShow) {
                                                                if (hideShow === \'hide\') {
                                                                        // Make inactive
                                                                        this.$element.off(\'keydown keyup\');
                                                                        this.query = \'\';
                                                                        this.keyActive = false;
                                                                        this.$element.removeClass(\'font-select-active\');
                                                                        this.$drop.hide();
                                                                        clearInterval(this.visibleInterval);
                                                                } else {
                                                                        // Make active
                                                                        this.$element.on(\'keydown\', __bind(this.keyDown, this));
                                                                        this.$element.on(\'keyup\', __bind(this.keyUp, this));
                                                                        this.$element.addClass(\'font-select-active\');
                                                                        this.$drop.show();

                                                                        this.visibleInterval = setInterval(__bind(this.getVisibleFonts, this), 500);
                                                                        this.searchBoxHeight = this.$search.outerHeight();
                                                                        this.moveToSelected();

                                                                        /*
                                                                        if (this.options.searchable) {
                                                                                // Focus search box
                                                                                $this.$input.focus();
                                                                        }
                                                                        */
                                                                }
                                                        },

                                                        selectFont: function() {
                                                                var font = $(\'li.active\', this.$results).data(\'value\');
                                                                this.$original.val(font).change();
                                                                this.updateSelected();
                                                                this.toggleDropdown(\'hide\'); // Hide dropdown
                                                        },

                                                        moveToSelected: function() {
                                                                var font = this.$original.val().replace(/ /g, \'+\');
                                                                var $li = font ? $("li[data-value=\'"+ font +"\']", this.$results) : $li = $(\'li\', this.$results).first();
                                                                this.$results.scrollTop($li.addClass(\'active\')[0].offsetTop - this.searchBoxHeight);
                                                        },

                                                        activateFont: function(e) {
                                                                if (this.keyActive) { return; }
                                                                $(\'li.active\', this.$results).removeClass(\'active\');
                                                                $(e.target).addClass(\'active\');
                                                        },

                                                        updateSelected: function() {
                                                                var font = this.$original.val();
                                                                $(\'span\', this.$element).text(this.toReadable(font)).css(this.toStyle(font));
                                                        },

                                                        setupHtml: function() {
                                                                this.$original.hide();
                                                                this.$element = $(\'<div>\', {\'class\': this.options.style});
                                                                this.$select = $(\'<span tabindex="0">\' + this.options.placeholder + \'</span>\');
                                                                this.$search = $(\'<div>\', {\'class\': \'fs-search\'});
                                                                this.$input = $(\'<input>\', {type:\'text\'});
                                                                if (this.options.placeholderSearch) {
                                                                        this.$input.attr(\'placeholder\', this.options.placeholderSearch);
                                                                }
                                                                this.$search.append(this.$input);
                                                                this.$drop = $(\'<div>\', {\'class\': \'fs-drop\'});
                                                                this.$results = $(\'<ul>\', {\'class\': \'fs-results\'});
                                                                this.$original.after(this.$element.append(this.$select, this.$drop));
                                                                this.options.searchable && this.$drop.append(this.$search);
                                                                this.$drop.append(this.$results.append(this.fontsAsHtml())).hide();
                                                        },

                                                        fontsAsHtml: function() {
                                                                var i, r, s, style, h = \'\';
                                                                var systemFonts = this.options.systemFonts;
                                                                var localFonts = this.options.localFonts;
                                                                var googleFonts = this.options.googleFonts;

                                                                for (i = 0; i < systemFonts.length; i++){
                                                                        r = this.toReadable(systemFonts[i]);
                                                                        s = this.toStyle(systemFonts[i]);
                                                                        style = \'font-family:\' + s[\'font-family\'];
                                                                        if ((localFonts.length > 0 || googleFonts.length > 0) && i == systemFonts.length-1) {
                                                                                style += \';border-bottom:1px solid #444\'; // Separator after last system font
                                                                        }
                                                                        h += \'<li data-value="\'+ systemFonts[i] +\'" data-query="\' + systemFonts[i].toLowerCase() + \'" style="\' + style + \'">\' + r + \'</li>\';
                                                                }

                                                                for (i = 0; i < localFonts.length; i++){
                                                                        r = this.toReadable(localFonts[i]);
                                                                        s = this.toStyle(localFonts[i]);
                                                                        style = \'font-family:\' + s[\'font-family\'];
                                                                        if (googleFonts.length > 0 && i == localFonts.length-1) {
                                                                                style += \';border-bottom:1px solid #444\'; // Separator after last local font
                                                                        }
                                                                        h += \'<li data-value="\'+ localFonts[i] +\'" data-query="\' + localFonts[i].toLowerCase() + \'" style="\' + style + \'">\' + r + \'</li>\';
                                                                }

                                                                for (i = 0; i < googleFonts.length; i++){
                                                                        r = this.toReadable(googleFonts[i]);
                                                                        s = this.toStyle(googleFonts[i]);
                                                                        style = \'font-family:\' + s[\'font-family\'] + \';font-weight:\' + s[\'font-weight\'];
                                                                        h += \'<li data-value="\'+ googleFonts[i] +\'" data-query="\' + googleFonts[i].toLowerCase() + \'" style="\' + style + \'">\' + r + \'</li>\';
                                                                }

                                                                return h;
                                                        },

                                                        toReadable: function(font) {
                                                                return font.replace(/[\+|:]/g, \' \');
                                                        },

                                                        toStyle: function(font) {
                                                                var t = font.split(\':\');
                                                                return {\'font-family\':"\'"+this.toReadable(t[0])+"\'", \'font-weight\': (t[1] || 400)};
                                                        },

                                                        getVisibleFonts: function() {
                                                                if(this.$results.is(\':hidden\')) { return; }

                                                                var fs = this;
                                                                var top = this.$results.scrollTop();
                                                                var bottom = top + this.$results.height();

                                                                if (this.options.lookahead){
                                                                        var li = $(\'li\', this.$results).first().height();
                                                                        bottom += li * this.options.lookahead;
                                                                }

                                                                $(\'li\', this.$results).each(function(){
                                                                        var ft = $(this).position().top+top;
                                                                        var fb = ft + $(this).height();

                                                                        if ((fb >= top) && (ft <= bottom)){
                                                                                fs.addFontLink($(this).data(\'value\'));
                                                                        }
                                                                });
                                                        },

                                                        addFontLink: function(font) {
                                                                if (fontsLoaded[font]) { return; }
                                                                fontsLoaded[font] = true;

                                                                if (this.options.googleFonts.indexOf(font) > -1) {
                                                                        $(\'link:last\').after(\'<link href="\' + this.options.googleApi + font + \'" rel="stylesheet" type="text/css">\');
                                                                }
                                                                else if (this.options.localFonts.indexOf(font) > -1) {
                                                                        font = this.toReadable(font);
                                                                        $(\'head\').append("<style> @font-face { font-family:\'" + font + "\'; font-style:normal; font-weight:400; src:local(\'" + font + "\'), url(\'" + this.options.localFontsUrl + font + ".woff\') format(\'woff\'); } </style>");
                                                                }
                                                                // System fonts need not be loaded!
                                                        }
                                                }; // End prototype

                                                return Fontselect;
                                        })();

                                        return this.each(function() {
                                                // If options exist, merge them
                                                if (options) { $.extend(settings, options); }

                                                return new Fontselect(this, settings);
                                        });
                                };
                        })(jQuery);
                        jQuery(\'.oxilab-admin-font\').fontselect();';

        if (apply_filters('oxi-flip-box-plugin/pro_version', false) == false) :
            $data .= 'jQuery(".oxilab-vendor-color").each(function (index, value) {
                            jQuery(this).parent().parent().siblings(".col-form-label").append(" <span class=\"oxi-pro-only\">Pro</span>");
                            var datavalue = jQuery(this).val();
                            jQuery(this).attr("oxilabvalue", datavalue);
                        });
                        jQuery(".oxilab-admin-font").each(function (index, value) {
                            jQuery(this).parent().siblings(".col-form-label").append(" <span class=\"oxi-pro-only\">Pro</span>");
                            var datavalue = jQuery(this).val();
                            jQuery(this).attr("oxilabvalue", datavalue);
                        });
                        jQuery(".custom-css").each(function (index, value) {
                            jQuery(this).append(" <span class=\"oxi-pro-only\">Pro Only</span>");
                        });
                        jQuery("#oxi-addons-form-submit").on("submit", function (e) {
                            jQuery(".oxilab-vendor-color").each(function (index, value) {
                                jQuery(this).val(jQuery(this).attr("oxilabvalue"));
                            });
                            jQuery(".oxilab-admin-font").each(function (index, value) {
                                jQuery(this).val(jQuery(this).attr("oxilabvalue"));
                            });
                            jQuery("#custom-css").val("");
                        });';
        endif;
        wp_add_inline_script('oxi-flip-box-addons-vendor', $data);
    }
    /**
     * Template hooks
     *
     * @since 2.0.0
     */
    public function hooks()
    {
        $this->admin_elements_frontend_loader();
        $this->dbdata = $this->wpdb->get_row($this->wpdb->prepare('SELECT * FROM ' . $this->parent_table . ' WHERE id = %d ', $this->oxiid), ARRAY_A);
        $this->child = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM $this->child_table WHERE styleid = %d ORDER by id ASC", $this->oxiid), ARRAY_A);
        if (!empty($this->dbdata['css'])) :
            $this->style = explode('|', stripslashes($this->dbdata['css']));
        endif;
        $this->StyleName = ucfirst(str_replace('-', '_', $this->dbdata['style_name']));
        $this->oxitype = ucfirst($this->dbdata['type']);
        $this->import_font_family();
    }

    /**
     * Template Parent Item Data Rearrange
     *
     * @since 2.0.0
     */
    public function Rearrange()
    {
        echo '';
    }

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->parent_table = $this->wpdb->prefix . 'oxi_div_style';
        $this->child_table = $this->wpdb->prefix . 'oxi_div_list';
        $this->import_table = $this->wpdb->prefix . 'oxi_div_import';
        $this->oxiid = (!empty($_GET['styleid']) ? (int) $_GET['styleid'] : '');
        $this->WRAPPER = '.oxi-addons-flip-wrapper-' . $this->oxiid;
        if (!empty($_REQUEST['_wpnonce'])) {
            $this->nonce = $_REQUEST['_wpnonce'];
        }
        $this->saving();
        $this->hooks();
        $this->render();
    }

    public function saving()
    {
        $demos = '{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}';
        $this->child_editable = explode('{#}|{#}', $demos);
        $this->style_data();
        $this->child_save();
        $this->child_edit();
        $this->rename_shortcode();
        $this->Delete_child_data();
    }
}
