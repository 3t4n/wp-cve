<?php
namespace Tussendoor\OpenRDW\Includes;

/**
 * All category types
 *
 * @since    2.0.0
 */

class VehiclePlateInformationFields {

    /**
     * All response ID's and labels
     *
     * @since    2.0.0
     */
    public static function fields(){
        $categories = [
            'miscellaneous' => esc_html__('Gemengd', 'open-rdw-kenteken-voertuiginformatie'),
            'history' => esc_html__('Geschiedenis', 'open-rdw-kenteken-voertuiginformatie'),
            'vehicle' => esc_html__('Voertuig', 'open-rdw-kenteken-voertuiginformatie'),
            'capacity' => esc_html__('Gewicht en capaciteit', 'open-rdw-kenteken-voertuiginformatie'),
            'maxtow' => esc_html__('Maximaal te trekken massa', 'open-rdw-kenteken-voertuiginformatie'),
            'engine' => esc_html__('Motor', 'open-rdw-kenteken-voertuiginformatie'),
            'design' => esc_html__('Ontwerp', 'open-rdw-kenteken-voertuiginformatie'),
            'moped' => esc_html__('Bromfiets', 'open-rdw-kenteken-voertuiginformatie'),
            'axels' => esc_html__('Axels', 'open-rdw-kenteken-voertuiginformatie'),
            'fuel' => esc_html__('Brandstofinformatie', 'open-rdw-kenteken-voertuiginformatie'),
            'body' => esc_html__('Lichaamswerk', 'open-rdw-kenteken-voertuiginformatie')
        ];
        $fields = [
            'merk' => [
                'category' => $categories['vehicle'],
                'label' => esc_html__('Merk', 'open-rdw-kenteken-voertuiginformatie')
            ],
            'handelsbenaming' => [
                'category' => $categories['vehicle'],
                'label' => esc_html__('Commerciele naam', 'open-rdw-kenteken-voertuiginformatie')
            ],
            'voertuigsoort' => [
                'category' => $categories['vehicle'],
                'label' => esc_html__('Voertuigtype', 'open-rdw-kenteken-voertuiginformatie')
            ],
            'eerste_kleur' => [
                'category' => $categories['design'],
                'label' => esc_html__('Eerste kleur', 'open-rdw-kenteken-voertuiginformatie')
            ],
            'tweede_kleur' => [
                'category' => $categories['design'],
                'label' => esc_html__('Secundaire kleur', 'open-rdw-kenteken-voertuiginformatie')
            ],
            'aantal_zitplaatsen' => [
                'category' => $categories['design'],
                'label' => esc_html__('Aantal zitplaatsen', 'open-rdw-kenteken-voertuiginformatie')
            ],
            'aantal_staanplaatsen' => [
                'category' => $categories['design'],
                'label' => esc_html__('Aantal staanplaatsen', 'open-rdw-kenteken-voertuiginformatie')
            ],
            'brandstof_omschrijving' => [
                'category' => $categories['engine'],
                'label' => esc_html__('Brandstofbeschrijving', 'open-rdw-kenteken-voertuiginformatie')
            ],
            'aantal_cilinders' => [
                'category' => $categories['engine'],
                'label' => esc_html__('Aantal cilinders', 'open-rdw-kenteken-voertuiginformatie')
            ],
            'cilinderinhoud' => [
                'category' => $categories['engine'],
                'label' => esc_html__('Motorinhoud', 'open-rdw-kenteken-voertuiginformatie')
            ],
            'massa_ledig_voertuig' => [
                'category' => $categories['capacity'],
                'label' => esc_html__('Lege voertuigmassa', 'open-rdw-kenteken-voertuiginformatie')
            ],
            'laadvermogen' => [
                'category' => $categories['capacity'],
                'label' => esc_html__('Capaciteit', 'open-rdw-kenteken-voertuiginformatie')
            ],
            'toegestane_maximum_massa_voertuig' => [
                'category' => $categories['capacity'],
                'label' => esc_html__('Maximaal toegestane massa van het voertuig', 'open-rdw-kenteken-voertuiginformatie')
            ],
            'massa_rijklaar' => [
                'category' => $categories['capacity'],
                'label' => esc_html__('Massa rijklaar', 'open-rdw-kenteken-voertuiginformatie')
            ],
            'maximum_massa_trekken_ongeremd' => [
                'category' => $categories['maxtow'],
                'label' => esc_html__('Maximaal getrokken massa, ongeremd', 'open-rdw-kenteken-voertuiginformatie')
            ],
            'maximum_trekken_massa_geremd' => [
                'category' => $categories['maxtow'],
                'label' => esc_html__('Maximaal getrokken massa geremd', 'open-rdw-kenteken-voertuiginformatie')
            ],
            'oplegger_geremd' => [
                'category' => $categories['maxtow'],
                'label' => esc_html__('Aanhangwagen geremd', 'open-rdw-kenteken-voertuiginformatie')
            ],
            'aanhangwagen_autonoom_geremd' => [
                'category' => $categories['maxtow'],
                'label' => esc_html__('Aanhangwagen autonoom geremd', 'open-rdw-kenteken-voertuiginformatie')
            ],
            'aanhangwagen_middenas_geremd' => [
                'category' => $categories['maxtow'],
                'label' => esc_html__('Middenas van aanhanger geremd', 'open-rdw-kenteken-voertuiginformatie')
            ],
            'datum_eerste_toelating' => [
                'category' => $categories['history'],
                'label' => esc_html__('Datum eerste registratie', 'open-rdw-kenteken-voertuiginformatie')
            ],
            'datum_eerste_afgifte_nederland' => [
                'category' => $categories['history'],
                'label' => esc_html__('Datum eerste uitgave Nederland', 'open-rdw-kenteken-voertuiginformatie')
            ],
            'datum_tenaamstelling' => [
                'category' => $categories['history'],
                'label' => esc_html__('Datum toeschrijving', 'open-rdw-kenteken-voertuiginformatie')
            ],
            'vervaldatum_apk' => [
                'category' => $categories['history'],
                'label' => esc_html__('APK verlopen', 'open-rdw-kenteken-voertuiginformatie')
            ],
        ];    

        $fields = apply_filters('open_rdw_vehicle_plate_information_fields', $fields);
        return $fields;
    }
}