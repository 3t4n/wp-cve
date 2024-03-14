<?php
/**
 * Definitive Addon Elements
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://wordpress.org/support/article/administration-screens/
 */
namespace Definitive_Addons_Elementor\Elements;

if (!class_exists('Definitive_Addon_Elements')) {
    /**
     * Definitive Addon Elements
     *
     * @category Definitive,element,elementor,widget,addons
     * @package  Definitive_Addons_Elementor
     * @author   Softfirm <contacts@softfirm.net>
     * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
     * @link     https://wordpress.org/support/article/administration-screens/
     */
    class Definitive_Addon_Elements
    {
        
        /**
         * Constructor
         *
         * @since 1.5.0
         *
         * @access public
         */
        public function __construct()
        {
        }
        
        
            /**
             * Get all definitive elements
             *
             * @since Definitive Addons for Elementor 1.5.0
             *
             * @return array.
             */
        public static function definitive_addons()
        {
     
            return $elements = [
                    'title'  =>__('Definitive Elements', 'definitive-addons-for-elementor'),
                    
            'elements' => [
                        [
                            'file_name'      => 'Testimonial',
                            'title'    =>__('Testimonial', 'definitive-addons'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Testimonial',
                          
                          
                        ],
                        [
                            'file_name'      => 'Testimonial_Slider',
                            'title'    =>__('Testimonial Slider', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Testimonial_Slider',
                          
                          
                        ],
                        [
                            'file_name'      => 'CTA',
                            'title'    =>__('Call to Action', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\CTA',
                           
                           
                        ],
                        [
                            'file_name'      => 'Flip_Box',
                            'title'    =>__('Flip Box', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Flip_Box',
                          
                           
                        ],
                        [
                            'file_name'      => 'Slider',
                            'title'    =>__('Slider', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Slider',
                          
                           
                        ],
                        [
                            'file_name'      => 'Post_Grid',
                            'title'    =>__('Post Grid', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Post_Grid',
                          
                           
                        ],
                        
                        [
                            'file_name'      => 'Post_Carousel',
                            'title'    =>__('Post Carousel', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Post_Carousel',
                          
                           
                        ],
                        [
                            'file_name'      => 'Accordion',
                            'title'    =>__('Accordion', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Accordion',
                          
                           
                        ],
                        [
                            'file_name'      => 'Category_Box',
                            'title'    =>__('Category Box', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Product_Category_Box',
                          
                           
                        ],
                        [
                            'file_name'      => 'Category_List',
                            'title'    =>__('Category List', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Category_List',
                          
                           
                        ],
                        [
                            'file_name'      => 'heading-with-separator',
                            'title'    =>__('Heading With Separator', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Heading_With_Separator',
                          
                           
                        ],
                        
                        [
                            'file_name'      => 'Contact_form_7',
                            'title'    =>__('Contact Form 7', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Contact_Form7',
                          
                           
                        ],
                        [
                            'file_name'      => 'Counter',
                            'title'    =>__('Counter', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Counter',
                          
                           
                        ],
                        [
                            'file_name'      => 'Creative_Button',
                            'title'    =>__('Creative Button', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Creative_Button',
                          
                           
                        ],
                        [
                            'file_name'      => 'Feature_list',
                            'title'    =>__('Feature List', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Feature_list',
                          
                           
                        ],
                        [
                            'file_name'      => 'Filterable_Portfolio',
                            'title'    =>__('Filterable Portfolio/Post', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Filterable_Post',
                          
                           
                        ],
                        [
                            'file_name'      => 'Icon_Box',
                            'title'    =>__('Icon Box', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Icon_Box',
                          
                           
                        ],
                        [
                            'file_name'      => 'Icon_List',
                            'title'    =>__('Icon List', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Icon_List',
                          
                           
                        ],
                        [
                            'file_name'      => 'Image_Overlay',
                            'title'    =>__('Image Overlay', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Image_Overlay',
                          
                           
                        ],
                        [
                            'file_name'      => 'Ninja_Forms',
                            'title'    =>__('Ninja Forms', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Ninja_Forms',
                          
                           
                        ],
                        [
                            'file_name'      => 'Popular_Post',
                            'title'    =>__('Popular Post', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Popular_Post',
                          
                           
                        ],
                        [
                            'file_name'      => 'Pricing_Table',
                            'title'    =>__('Pricing Table', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Pricing_Table',
                          
                           
                        ],
						
                        [
                            'file_name'      => 'Products',
                            'title'    =>__('Product Slider', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Product_Slider',
                          
                           
                        ],
						
                        [
                            'file_name'      => 'Promo-box',
                            'title'    =>__('Promo Box', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Promo_Box',
                          
                           
                        ],
                        [
                            'file_name'      => 'Skillbar',
                            'title'    =>__('Skillbar', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Skillbar',
                          
                           
                        ],
                        [
                            'file_name'      => 'Social_Icon',
                            'title'    =>__('Social Icon', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Social_Icon',
                          
                           
                        ],
                        [
                            'file_name'      => 'Staff_Member',
                            'title'    =>__('Team Member', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Staff_Member',
                          
                           
                        ],
                        [
                            'file_name'      => 'Subscription',
                            'title'    =>__('Subscription', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Subscription',
                          
                           
                        ],
                        [
                            'file_name'      => 'Tabs',
                            'title'    =>__('Tabs', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Tabs',
                          
                           
                        ],
                        [
                            'file_name'      => 'Teaser_Box',
                            'title'    =>__('Card', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Teaser_Box',
                          
                           
                        ],
                        [
                            'file_name'      => 'Type',
                            'title'    =>__('Type', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Type',
                          
                           
                        ],
                        [
                            'file_name'      => 'Wording',
                            'title'    =>__('Multi-Color Text', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Wording',
                          
                           
                        ],
                        [
                            'file_name'      => 'WpForm',
                            'title'    =>__('WPForms', 'definitive-addons-for-elementor'),
                            'class_path'    => 'Definitive_Addons_Elementor\Elements\Wp_Form',
                          
                           
                        ],

                    ]
                ];
            
            
        }

      
    }
  
}

$definitive_addons = new Definitive_Addon_Elements();