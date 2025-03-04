<?php
class ArfBeaverModule extends FLBuilderModule {

    public function __construct()
    {
        $arformslogo = '<svg width="24" height="24"  viewBox="-3 -1 23 20.22" aria-hidden="true"  focusable="false" style="fill: rgb(73, 107, 222);">
        <path d="M17.076,11.591c-0.12,0.427-0.021,0.64,0.3,0.64c0.4,0,0.9-0.358,1.5-1.078 c0.041-0.044,0.08-0.067,0.121-0.067c0.14,0,0.295,0.096,0.465,0.287c0.17,0.19,0.255,0.354,0.255,0.488	c0,0.045-0.011,0.078-0.03,0.101c-1.52,2.021-2.98,3.032-4.381,3.032c-0.76,0-1.141-0.494-1.141-1.482 	c0-0.674,0.211-1.504,0.631-2.493l-0.18-0.101c-0.821,1.145-1.736,2.111-2.746,2.897c-1.011,0.786-1.855,1.179-2.536,1.179	c-0.72,0-1.29-0.303-1.71-0.909c-0.42-0.606-0.63-1.358-0.63-2.258c0-2.245,0.86-4.616,2.581-7.109 	c1.841-2.695,3.801-4.043,5.882-4.043c0.619,0,1.141,0.168,1.561,0.505l0.3-0.809C17.396,0.124,17.687,0,18.187,0 	c0.42,0,0.84,0.079,1.26,0.236c0.421,0.157,0.61,0.315,0.57,0.472L17.076,11.591z M15.06,2.055c-1.299,0-2.479,1.292-3.537,3.875 	c-0.859,2.157-1.288,3.942-1.288,5.357c0,0.36,0.065,0.69,0.194,0.994c0.131,0.304,0.295,0.454,0.496,0.454 	c0.7,0,1.57-0.773,2.611-2.324c1.04-1.549,1.801-3.2,2.281-4.952l0.71-2.632C16.228,2.313,15.737,2.055,15.06,2.055z M3.485,4.998 	c-0.261,0-0.472-0.179-0.472-0.4V3.391c0-0.221,0.211-0.4,0.472-0.4h5.072c0.243,0,0.441,0.157,0.467,0.357 	C8.407,3.764,7.793,4.324,7.22,4.998H3.485z M3.505,6.999h3.501C6.682,7.446,6.393,7.981,6.161,8.575 	C6.092,8.708,6.026,8.843,5.966,8.979H3.505c-0.274,0-0.497-0.176-0.497-0.395v-1.19C3.009,7.177,3.231,6.999,3.505,6.999z 	 M1.612,8.979H0.403C0.181,8.979,0,8.804,0,8.585v-1.19c0-0.218,0.181-0.396,0.403-0.396h1.209c0.222,0,0.403,0.178,0.403,0.396  	v1.19C2.015,8.804,1.834,8.979,1.612,8.979z M1.604,4.998H0.401C0.18,4.998,0,4.818,0,4.598V3.391c0-0.221,0.18-0.4,0.401-0.4h1.203 	c0.222,0,0.401,0.18,0.401,0.4v1.207C2.005,4.818,1.826,4.998,1.604,4.998z M0.403,10.981H1.61c0.223,0,0.403,0.18,0.403,0.4v1.207 c0,0.221-0.18,0.4-0.403,0.4H0.403C0.18,12.989,0,12.81,0,12.589v-1.207C0,11.161,0.18,10.981,0.403,10.981z M3.567,10.981h2.21	c-0.081,0.72,0.003,1.402,0.228,2.008H3.567c-0.306,0-0.554-0.18-0.554-0.4v-1.207C3.013,11.161,3.261,10.981,3.567,10.981z"></path>
                        </svg>';
        parent::__construct(array(
            'name'          => __('ARForms', 'arforms-form-builder'),
            'description'   => __('Select and display one of your forms.', 'arforms-form-builder'),
            'category'		=> __('ARForms', 'arforms-form-builder'),
            'dir'           => ARFLITE_FORMPATH . '/integrations/Beaver_Builder/modules/beaver_builder_module/',
            'url'           => ARFLITEURL . '/integrations/Beaver_Builder/modules/beaver_builder_module/',
            'editor_export' => true,
            'enabled'       => true,
            'icon'          =>$arformslogo
        ));

        $this->add_css('arflitedisplaycss');
        $this->add_css('arformslite_selectpicker');
    }
}

global $arf_beaver_builder;

FLBuilder::register_module('ArfBeaverModule', array(
    'general'       => array( 
        'title'         => __('General', 'arforms-form-builder'), 
        'sections'      => array( 
            'general'       => array(
                'title'         => __('Form Settings', 'arforms-form-builder'), 
                'fields'        => array( 
                    'form_id'   => array(
                        'type'          => 'select',
                        'label'         => __('Select Form', 'arforms-form-builder'),
                        'options'       => $arf_beaver_builder->arflite_enqueue_beaver_builder_assets(),
                    )
                )
            )
        )
    )
));