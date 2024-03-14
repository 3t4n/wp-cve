<?php

include_once plugin_dir_path(__FILE__) . '/banner.widget.php';

class Goracash_Banner
{
    public static $default_params = array(
        'thematic' => '',
        'tracker' => '',
        'advertiser' => '',
        'defaultLanguage' => '',
        'defaultMarket' => '',
        'minWidth' => '',
        'maxWidth' => '',
        'minHeight' => '',
        'maxHeight' => '',
    );

    public function __construct()
    {
        add_action('widgets_init', function() {
            register_widget('Goracash_Banner_Widget');
        });
        add_action('wp_head', array($this, 'add_front_javascript'));
        add_shortcode('goracash_banner', array($this, 'add_shortcode'));
    }

    /**
     * @return array
     */
    public static function get_thematics()
    {
        return array(
            'ASTRO' => __('Astrology / Fortune Telling', 'goracash'),
            'PSYCHO' => __('Psychology', 'goracash'),
            'TEACH' => __('In-Home Tutoring', 'goracash'),
            'DEVIS' => __('Home Renovation Quote', 'goracash'),
            'HEALTH' => __('Health', 'goracash'),
            'JURI' => __('Law', 'goracash'),
            'SPONSORSHIP' => __('Sponsorship', 'goracash'),
        );
    }

    /**
     * @return array
     */
    public static function get_advertisers()
    {
        return array(
            '' => __('All', 'goracash'),
            'pro.vos-devis.com' => 'pro.vos-devis.com',
            'www.123-pognon.com' => 'www.123-pognon.com',
            'www.abc-reves.com' => 'www.abc-reves.com',
            'www.astrologie-facile.com' => 'www.astrologie-facile.com',
            'www.auto-voyance.com' => 'www.auto-voyance.com',
            'www.avenir-amoureux.com' => 'www.avenir-amoureux.com',
            'www.bienvenue-voyance.com' => 'www.bienvenue-voyance.com',
            'www.bonne-note.com' => 'www.bonne-note.com',
            'www.bonne-voyance.com' => 'www.bonne-voyance.com',
            'www.chic-voyance.com' => 'www.chic-voyance.com',
            'www.couleur-voyance.com' => 'www.couleur-voyance.com',
            'www.couplomancie.com' => 'www.couplomancie.com',
            'www.devispresto.com' => 'www.devispresto.com',
            'www.divinastreet.com' => 'www.divinastreet.com',
            'www.echec-professionnel.com' => 'www.echec-professionnel.com',
            'www.envoutage.com' => 'www.envoutage.com',
            'www.etoilia.com' => 'www.etoilia.com',
            'www.extranaturel.com' => 'www.extranaturel.com',
            'www.femme-voyance.com' => 'www.femme-voyance.com',
            'www.giga-voyance.com' => 'www.giga-voyance.com',
            'www.goracash.com' => 'www.goracash.com',
            'www.grande-voyance.com' => 'www.grande-voyance.com',
            'www.grand-tarot.com' => 'www.grand-tarot.com',
            'www.histoires-reelles.com' => 'www.histoires-reelles.com',
            'www.horoscope-facile.com' => 'www.horoscope-facile.com',
            'www.juritravail.com' => 'www.juritravail.com',
            'www.kissvoyance.com' => 'www.kissvoyance.com',
            'www.longue-vie.com' => 'www.longue-vie.com',
            'www.mediums-de-naissance.com' => 'www.mediums-de-naissance.com',
            'www.mediums-land.com' => 'www.mediums-land.com',
            'www.oracle-numerique.com' => 'www.oracle-numerique.com',
            'www.oui-voyance.com' => 'www.oui-voyance.com',
            'www.passvoyance.com' => 'www.passvoyance.com',
            'www.poisse.com' => 'www.poisse.com',
            'www.predictions-amoureuses.com' => 'www.predictions-amoureuses.com',
            'www.preditavi.com' => 'www.preditavi.com',
            'www.problemes-amoureux.com' => 'www.problemes-amoureux.com',
            'www.rdvmedicaux.com' => 'www.rdvmedicaux.com',
            'www.reponse-immediate.com' => 'www.reponse-immediate.com',
            'www.retour-amour.com' => 'www.retour-amour.com',
            'www.tarot-amoureux.com' => 'www.tarot-amoureux.com',
            'www.une-reponse.com' => 'www.une-reponse.com',
            'www.voslitiges.com' => 'www.voslitiges.com',
            'www.voyants-de-naissance.com' => 'www.voyants-de-naissance.com',
            'www.voyantsducoeur.com' => 'www.voyantsducoeur.com',
            'www.wengo.fr/psycho/' => 'www.wengo.fr/psycho/',
        );
    }

    /**
     * @return array
     */
    public static function get_langs()
    {
        return array(
            'fr_FR' => __('French', 'goracash'),
            'es_ES' => __('Spanish', 'goracash'),
        );
    }

    /**
     * @return array
     */
    public static function get_markets()
    {
        return array(
            'france' => __('French', 'goracash'),
            'spain' => __('Spanish', 'goracash'),
        );
    }

    /**
     * @param $atts
     * @return string
     */
    public function add_shortcode($atts)
    {
        $data = array();
        foreach (Goracash_Banner::$default_params as $key => $default) {
            $shortcode_key = strtolower($key);
            $data[$key] = isset($atts[$shortcode_key]) ? $atts[$shortcode_key] : $default;
        }

        $args = array(
            'before_widget' => '<div>',
            'after_widget'  => '</div>',
            'before_title'  => '<div>',
            'after_title'   => '</div>',
        );

        ob_start();
        the_widget('Goracash_Banner_Widget', $data, $args);
        $output = ob_get_clean();
        return $output;
    }

    public function add_front_javascript()
    {
        $advertiser = get_option('goracash_ads_advertiser', '');

        printf("<script type='text/javascript'>
                    (function(w, d, s, u, o, e, c){
                        w['GoracashObject'] = o; w[o] = w[o] || function() { (w[o].q = w[o].q || []).push(arguments) },
                        w[o].l = 1 * new Date(); e = d.createElement(s), c = d.getElementsByTagName(s)[0]; e.async = 1;
                        e.src = u; c.parentNode.insertBefore(e, c);
                    }) (window, document, 'script', '//cdn.goracash.com/general.js', 'goracash');
                    goracash('create', 'GCO-%s');
                    goracash('set', 'forceSSL', %s);
                    goracash('set', 'thematic', '%s');
                    goracash('set', 'defaultLanguage', '%s');
                    goracash('set', 'defaultMarket', '%s');
                    %s
                    %s
                    %s
                </script>",
            get_option('goracash_idw', '1234'),
            get_option('goracash_ads_force_ssl') ? 'true' : 'false',
            get_option('goracash_ads_thematic', 'ASTRO'),
            get_option('goracash_ads_default_lang', 'fr_FR'),
            get_option('goracash_ads_default_market', 'france'),
            $advertiser ? sprintf("goracash('set', 'advertiser', '%s');", $advertiser) : '',
            get_option('goracash_ads_popexit') ? "goracash('exit');" : '',
            get_option('goracash_ads_top_bar') ? "goracash('top-bar');" : ''
        );
    }
}