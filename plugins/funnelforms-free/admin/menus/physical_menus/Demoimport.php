<?php

require_once FNSF_AF2_MENU_PARENTS_CLASS;

class Fnsf_Af2DemoImport extends Fnsf_Af2MenuCustom {

    protected function fnsf_get_heading() { return __('Demo import', 'funnelforms-free'); }
    protected function fnsf_get_menu_custom_template() { return FNSF_AF2_CUSTOM_MENU_DEMO_IMPORT; }

    protected function fnsf_get_menu_blur_option_() { return true; }
    
    protected function fnsf_get_af2_custom_contents_() { 
        return array(
            array(
                'name' => 'Lawyer-Demo',     
                'description' => 'This demo contains 6 lead generation questions specifically for lawyers. The interested party indicates whether he is inquiring as a private individual or as a company. It will also be asked whether the interested party would like to sue.',
                'filename' => 'lawyer',
                'active' => 1
            ),
            array(
                'name' => 'Financial Services-Demo',     
                'description' => 'This demo contains 12 questions about objects to be financed (house, car, other). A built-in logic ensures that only the data that is relevant for the initial contact is queried.',
                'filename' => 'financial',
                'active' => 1
            ),
            array(
                'name' => 'Fitness-Coach-Demo',     
                'description' => 'This demo contains 6 lead generation questions specifically for a fitness coach. The interested party indicates whether he wants to lose weight, build muscle or improve his condition.',
                'filename' => 'fitness',
                'active' => 1
            ),
            array(
                'name' => 'Tiler-Demo',     
                'description' => 'This demo contains 6 lead generation questions specifically for tilers. Exact information is requested, e.g. where to tile (inside or outside) and which materials should be used.',
                'filename' => 'tiler',
                'active' => 1
            ),
            array(
                'name' => 'Gardening-Demo',     
                'description' => 'This demo contains 9 lead generation questions specific to gardening/landscaping. The prospect is asked specific questions that are made concrete by the built-in logic.',
                'filename' => 'gardening',
                'active' => 1
            ),
            array(
                'name' => 'Real Estate Agent-Demo',     
                'description' => 'This demo contains 16 lead generation questions specifically for real estate agents. The visitor is asked specific questions based on the information he has given (house, apartment, property).',
                'filename' => 'realestate',
                'active' => 1
            ),
            array(
                'name' => 'Kitchen Studio-Demo',     
                'description' => 'This demo contains 9 specific questions for kitchen studios. Among other things, questions are asked about the style and desired materials as well as the installation location of the kitchen.',
                'filename' => 'kitchen',
                'active' => 1
            ),
            array(
                'name' => 'Recruitment-Demo',     
                'description' => 'This demo contains 7 recruiting questions using the example of a bricklaying company. A distinction is made between training, job offers for journeymen and master craftsmen.',
                'filename' => 'recruitment',
                'active' => 1
            ),
            array(
                'name' => 'Nursing Service-Demo',     
                'description' => 'This demo contains 9 lead generation questions for Ambulatory Care Services. Information is requested on the care level, whether the person being cared for is bedridden, and on the communication skills of the person being cared for.',
                'filename' => 'nursing',
                'active' => 1
            ),
            array(
                'name' => 'Terraceroof-Demo',     
                'description' => 'This demo contains 7 lead generation questions specifically for patio cover tradesmen. It will ask whether a commercial or residential roofing is to be installed and the type of roof.',
                'filename' => 'terrace',
                'active' => 1
            ),
            array(
                'name' => 'Moving Company-Demo',     
                'description' => 'This demo contains 8 lead generation questions for moving companies. Information is requested on the size of the household, whether furniture needs to be put up or taken down, the distance between locations and when the move is to take place.',
                'filename' => 'moving',
                'active' => 1
            ),
            array(
                'name' => 'Insurance Broker-Demo',     
                'description' => 'This demo contains 6 lead generation questions for insurance brokers. By querying the current situation, the inquirer is shown in this demo that independent support from an insurance broker is worthwhile for him.',
                'filename' => 'insurance',
                'active' => 1
            ),
            array(
                'name' => 'Video Production-Demo',     
                'description' => 'This demo contains 5 lead generation questions specifically for video production by film, television, or marketing agencies. With a slider, the customer can set his budget individually.',
                'filename' => 'video',
                'active' => 1
            ),
            array(
                'name' => 'Web Agency-Demo',     
                'description' => 'This demo contains 5 questions from the field of web design and online marketing. It serves as the basis for a project request form / lead generation form for internet, web and online agencies.',
                'filename' => 'web',
                'active' => 1
            ),
            array(
                'name' => 'Ad Agency-Demo',     
                'description' => 'This demo contains 8 questions from the field of an advertising agency. It serves as the basis for a project request form / lead generation form for advertising agencies.',
                'filename' => 'ad',
                'active' => 1
            ),
            array(
                'name' => '',                     
                'description' => '',                       
                'active' => -1
            ),
        );
    }

    protected function fnsf_load_resources() {
        wp_enqueue_style('af2_demoimport_style');
        wp_localize_script( 'af2_demoimport', 'af2_demoimport_object', array(
            'strings' => array(
                'success' => __('Imported successfully', 'funnelforms-free'),
                'import' => __('Import', 'funnelforms-free'),
                'wait' => __('Please wait', 'funnelforms-free'),
            ),
        ));
        wp_enqueue_script('af2_demoimport');

        parent::fnsf_load_resources();
    }
}