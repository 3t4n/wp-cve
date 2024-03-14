<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Map
 *
 * @author CMSHelplive
 */
class Element_Address extends Element
{

    public $default_add = array (
                            'rm_field_type' => '',
                            'original' => '',
                            'st_number' => '',
                            'st_route' => '',
                            'city' => '',
                            'state' => '',
                            'zip' => '',
                            'country' => ''
                          );
    public $_attributes = array();
    public $jQueryOptions = "";
    public $properties= array();
    public $api_key;
    

    public function getCSSFiles()
    {
       if(isset($this->_attributes['country_search_enabled']) && $this->_attributes['country_search_enabled']){
           return array(
                'style_rm_select2' => RM_BASE_URL . 'public/css/style_rm_select2.css',
           );
       } 
    }  

    public function __construct($label, $name, $api_key, array $properties = null)
    {
       
        parent::__construct($label, $name, $properties);
        $this->api_key = $api_key;
        $this->_attributes['id'] = 'autocomplete'.$name;
    }
    
    public function show_advance_search($field_id){
        if(isset($this->_attributes['country_search_enabled']) && $this->_attributes['country_search_enabled']){
           echo '<script>
                jQuery(document).ready(function() {
                        jQuery("#'.wp_kses_post((string)$field_id).'").select2();
                });
            </script>'; 
         }
    }

    public function getJSFiles()
    {
         if($this->_attributes['address_type']=="ca"){
            $scripts= array('script_rm_address' => RM_BASE_URL . 'public/js/script_rm_address.js');
            if(isset($this->_attributes['country_search_enabled']) && $this->_attributes['country_search_enabled'])
                    $scripts['script_rm_select2']=RM_BASE_URL."public/js/script_rm_select2.js";
            return $scripts;
         }
        
        return array(
            'script_rm_address' => RM_BASE_URL . 'public/js/script_rm_address.js',
            'google_map_api' => "https://maps.googleapis.com/maps/api/js?key=" . $this->api_key . "&libraries=places&callback=rmInitGoogleApi",
        );
    }

    public function getJSDeps()
    {
        return array(
            'script_rm_address',
            'script_rm_select2'
        );
    }

    public function jQueryDocumentReady()
    {
        parent::jQueryDocumentReady();
    }
    
    public function localizeJS(){
       return array('script_rm_address'=>array('name'=>'rm_admin_vars','value'=>array('rm_sec_nonce'=>wp_create_nonce('rm_ajax_secure'))));
    }

    public function render()
    {
        @ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
        $name = $this->_attributes['name'];
        $value = wp_parse_args(maybe_unserialize($this->getAttribute('value')), $this->default_add);
        if($this->isRequired())
            $required = 'required';
        else
            $required = '';
 
        if(isset($this->_attributes['style'])){
            $style = "style='".$this->_attributes["style"]."'";
           unset($this->_attributes["style"]);
        }
        else
            $style = '';

        $field_options = "";
        if(isset($this->conditions)) {
            foreach($this->conditions as $index => $val) {
                $field_options .= "$index=$val ";
            }
        }
        
        if($this->_attributes['address_type']!="ca") : ?>
            
            <div id="locationField">
                <input type="hidden" name="<?php echo esc_attr($name); ?>[rm_field_type]" value="Address" class="<?php echo !empty($field_options) ? 'data-conditional' : ''; ?>" <?php echo esc_attr($field_options); ?>>
                <input type="text" id="<?php echo esc_attr($name); ?>" placeholder="<?php echo wp_kses_post((string)RM_UI_Strings::get("LABEL_GMAP_ADDRESS")); ?>" class="rmgoogleautocompleteapi<?php echo !empty($field_options) ? ' data-conditional' : ''; ?>" onFocus="(new rmAutocomplete('<?php echo esc_attr($name); ?>')).geolocate()" onkeydown="rm_prevent_submission(event)" <?php echo wp_kses_post((string)$style); ?> type="text" <?php echo esc_attr($required); ?> name="<?php echo esc_attr($name); ?>[original]" value="<?php echo esc_attr($value['original']); ?>" <?php echo esc_attr($field_options); ?>></input>
                <span><?php echo wp_kses_post((string)RM_UI_Strings::get("LABEL_POWERED_GMAP")); ?></span>
            </div>

            <div id="address" class="rm_address_type_<?php echo esc_attr($this->_attributes['address_type']); ?>">
                <div class="rm_ad_container">       
                        <div class="slimField rm-address-fw">
                            <input type="text" <?php echo wp_kses_post((string)$style); ?> class="field<?php echo !empty($field_options) ? ' data-conditional' : ''; ?>" id="<?php echo esc_attr($name); ?>_street_number"
                                   name="<?php echo esc_attr($name); ?>[st_number]" value="<?php echo esc_attr($value['st_number']); ?>" <?php echo esc_attr($field_options); ?>></input>
                            <div class="label"><?php echo esc_html($this->_attributes['street_no_label']); ?></div>
                        </div>

                        <div class="wideField rm-semi-field rm-address-fw" colspan="2">
                            <input type="text" <?php echo wp_kses_post((string)$style); ?> class="field<?php echo !empty($field_options) ? ' data-conditional' : ''; ?>" id="<?php echo esc_attr($name); ?>_route"
                                   name="<?php echo esc_attr($name); ?>[st_route]" value="<?php echo esc_attr($value['st_route']); ?>" <?php echo esc_attr($field_options); ?>></input>
                            <div class="label"><?php echo esc_html($this->_attributes['street_label']); ?></div>
                        </div>
                </div>

                <div class="rm_ad_container">
                    <div class="wideField rm-alone rm-address-hw" colspan="3">
                        <input type="text" class="<?php echo !empty($field_options) ? 'data-conditional' : ''; ?>"<?php echo wp_kses_post((string)$style); ?> id="<?php echo esc_attr($name); ?>_locality" name="<?php echo esc_attr($name); ?>[city]" value="<?php echo esc_attr($value['city']); ?>" <?php echo esc_attr($field_options); ?>/>                                                       
                        <div class="label"><?php echo esc_html($this->_attributes['city_label']); ?></div>
                    </div>

                    <div class="slimField rm-address-hw">
                        <input type="text" <?php echo wp_kses_post((string)$style); ?> class="field<?php echo !empty($field_options) ? ' data-conditional' : ''; ?>" id="<?php echo esc_attr($name); ?>_administrative_area_level_1"  name="<?php echo esc_attr($name); ?>[state]" value="<?php echo esc_attr($value['state']); ?>" <?php echo esc_attr($field_options); ?>/>
                        <div class="label"><?php echo esc_html($this->_attributes['state_label']); ?></div>
                    </div>
                </div>

                <div class="rm_ad_container">
                    <div class="wideField rm-alone rm-address-hw" colspan="3">
                        <input type="text" <?php echo wp_kses_post((string)$style); ?> class="field<?php echo !empty($field_options) ? ' data-conditional' : ''; ?>" id="<?php echo esc_attr($name); ?>_country"   name="<?php echo esc_attr($name); ?>[country]" value="<?php echo esc_attr($value['country']); ?>" <?php echo esc_attr($field_options); ?>></input>
                        <div class="label"><?php echo esc_html($this->_attributes['country_label']); ?></div>
                    </div>
                    
                     <div class="wideField rm-semi-field-with-label rm-address-hw">
                        <input type="text" <?php echo wp_kses_post((string)$style); ?> class="field<?php echo !empty($field_options) ? ' data-conditional' : ''; ?>" id="<?php echo esc_attr($name); ?>_postal_code" name="<?php echo esc_attr($name); ?>[zip]" value="<?php echo esc_attr($value['zip']); ?>" <?php echo esc_attr($field_options); ?>></input> 
                        <div class="label label-short"><?php echo esc_html($this->_attributes['zip_label']); ?></div>
                    </div>
                </div>
        </div> 

        <?php else : ?>

        <div id="address" class="rm_address_type_<?php echo esc_attr($this->_attributes['address_type']); ?>">
            <div class="rm_ad_container">       
                        <?php if ($this->_attributes['address1_en']) : ?>
                            <div class="slimField rm-address-fw">
                                <input type="text" <?php echo wp_kses_post((string)$style); ?> class="field<?php echo !empty($field_options) ? ' data-conditional' : ''; ?>" id="<?php echo esc_attr($name); ?>_address1"
                                       placeholder="<?php echo !empty($this->_attributes['label_as_placeholder']) ? esc_attr($this->_attributes['address1_label']) : '' ?>"
                                       <?php echo esc_attr($this->_attributes['address1_req']) ?>
                                       name="<?php echo esc_attr($name); ?>[address1]" value="<?php echo isset($value['address1']) ? esc_attr($value['address1']) : ''; ?>" <?php echo esc_attr($field_options); ?>></input>
                                <?php if(empty($this->_attributes['label_as_placeholder'])) : ?>
                                    <div class="label"><?php echo esc_html($this->_attributes['address1_label']); ?>
                                    <?php if(!empty($this->_attributes['address1_req'])): ?>    
                                    <sup class="address_req required">&nbsp;*</sup>
                                <?php endif; ?> 
                                    
                                    </div>
                                <?php endif;  ?> 
                           
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($this->_attributes['address2_en']) : ?>
                            <div class="wideField rm-semi-field rm-address-fw" colspan="2">
                                <input type="text" <?php echo wp_kses_post((string)$style); ?> class="field<?php echo !empty($field_options) ? ' data-conditional' : ''; ?>" id="<?php echo esc_attr($name); ?>_route"
                                       <?php echo esc_attr($this->_attributes['address2_req']) ?>
                                       placeholder="<?php echo !empty($this->_attributes['label_as_placeholder']) ? esc_attr($this->_attributes['address2_label']) : '' ?>"
                                       name="<?php echo esc_attr($name); ?>[address2]" value="<?php echo isset($value['address2']) ? esc_attr($value['address2']) : ''; ?>" <?php echo esc_attr($field_options); ?>></input>
                                <?php if(empty($this->_attributes['label_as_placeholder'])) : ?>
                                    <div class="label"><?php echo esc_html($this->_attributes['address2_label']); ?>
                                    <?php if(!empty($this->_attributes['address2_req'])): ?>    
                                    <sup class="address_req required">&nbsp;*</sup>
                                <?php endif; ?>  
                                    
                                    </div>
                                <?php endif; ?>    
                            
                            </div>
                        <?php endif; ?>
            </div>
            
            <?php if ($this->_attributes['lmark_en']) : ?>
                <div class="rm_ad_container">
                        <div class="wideField rm-alone rm-address-fw" colspan="2">
                            <input type="text" class="<?php echo !empty($field_options) ? 'data-conditional' : ''; ?>"<?php echo wp_kses_post((string)$style); ?> name="<?php echo esc_attr($name); ?>[lmark]"
                                   <?php echo esc_attr($this->_attributes['lmark_req']) ?>
                                   placeholder="<?php echo !empty($this->_attributes['label_as_placeholder']) ? esc_attr($this->_attributes['lmark_label']) : '' ?>"
                                   value="<?php echo esc_attr($value['lmark']); ?>" <?php echo esc_attr($field_options); ?>/>    
                            <?php if(empty($this->_attributes['label_as_placeholder'])) : ?>
                                <div class="label"><?php echo esc_html($this->_attributes['lmark_label']); ?>
                                <?php if(!empty($this->_attributes['lmark_req'])): ?>    
                                <sup class="address_req required">&nbsp;*</sup>
                            <?php endif; ?> 
                                
                                </div>
                            <?php endif; ?>
                       
                        </div>
                </div>
             <?php endif; ?>
            
            <div class="rm_ad_container">
                    <?php if ($this->_attributes['city_en']) : ?>
                        <div class="wideField rm-alone rm-address-hw" colspan="3">
                            <input type="text" class="<?php echo !empty($field_options) ? 'data-conditional' : ''; ?>"<?php echo wp_kses_post((string)$style); ?> name="<?php echo esc_attr($name); ?>[city]" 
                                   id="<?php echo esc_attr($name); ?>_locality" name="<?php echo esc_attr($name); ?>[city]"                      
                                   <?php echo esc_attr($this->_attributes['city_req']) ?>
                                   placeholder="<?php echo !empty($this->_attributes['label_as_placeholder']) ? esc_attr($this->_attributes['city_label']) : '' ?>"
                                   value="<?php echo esc_attr($value['city']); ?>" <?php echo esc_attr($field_options); ?>/>    
                            <?php if(empty($this->_attributes['label_as_placeholder'])) : ?>
                                <div class="label"><?php echo esc_html($this->_attributes['city_label']); ?>
                                <?php if(!empty($this->_attributes['city_req'])): ?>    
                                <sup class="address_req required">&nbsp;*</sup>
                            <?php endif; ?>                                   
                                </div>
                            <?php endif; ?>
                         
                        </div>
                    <?php endif; ?>
                    
                    <?php  if ($this->_attributes['state_en']) : ?>
                        <?php if($this->_attributes['state_type']=='all'): ?>
                            <div class="slimField rm-address-hw">
                                <input type="text" <?php echo wp_kses_post((string)$style); ?> class="field<?php echo !empty($field_options) ? ' data-conditional' : ''; ?>" id="<?php echo esc_attr($name); ?>_administrative_area_level_1"  
                                        <?php echo esc_attr($this->_attributes['state_req']) ?>
                                        placeholder="<?php echo !empty($this->_attributes['label_as_placeholder']) ? esc_attr($this->_attributes['state_label']) : '' ?>"
                                       name="<?php echo esc_attr($name); ?>[state]" value="<?php echo esc_attr($value['state']); ?>" <?php echo esc_attr($field_options); ?>/>
                                <?php if(empty($this->_attributes['label_as_placeholder'])) : ?>
                                    <div class="label"><?php echo esc_html($this->_attributes['state_label']); ?>
                                    <?php if(!empty($this->_attributes['state_req'])): ?>    
                                    <sup class="address_req required">&nbsp;*</sup>
                                <?php endif; ?> 
                                    </div>
                                <?php endif; ?>
                          
                            </div>
                        <?php endif; ?>
                    
                
                        <?php if($this->_attributes['state_type']=='america'): ?>
                            <div class="slimField rm-address-hw">
                                <select <?php echo wp_kses_post((string)$style); ?> class="field" <?php echo esc_attr($this->_attributes['state_req']) ?>
                                        id="<?php echo esc_attr($name); ?>_administrative_area_level_1"  
                                        name="<?php echo esc_attr($name); ?>[state]" value="<?php echo esc_attr($value['state']); ?>" <?php echo esc_attr($field_options); ?>>
                                    <option value=""><?php echo esc_html($this->_attributes['state_label']); ?></option>
                                    <?php $usa_states= RM_Utilities::get_usa_states(); ?>
                                    <?php foreach($usa_states as $code=>$state): ?>
                                          <?php if($this->_attributes["state_as_code"]==1) : $state= $code; endif;?>
                                            <?php if($value['state']==$state) : ?>
                                                 <option selected><?php echo esc_html($state); ?></option>
                                            <?php else:?>     
                                                 <option><?php echo esc_html($state); ?></option>
                                            <?php endif; ?>     
                                    <?php endforeach; ?>   
                                </select>
                                <?php if(empty($this->_attributes['label_as_placeholder'])) : ?>
                                    <div class="label"><?php echo esc_html($this->_attributes['state_label']); ?>
                                            <?php if(!empty($this->_attributes['state_req'])): ?>    
                                    <sup class="address_req required">&nbsp;*</sup>
                                <?php endif; ?> 
                                    </div>
                                <?php endif; ?> 
                           
                            </div>
                        <?php endif; ?>

                        <?php if($this->_attributes['state_type']=='limited'): ?>
                            <div class="slimField rm-address-hw">
                                <input type="text" <?php echo wp_kses_post((string)$style); ?> class="field<?php echo !empty($field_options) ? ' data-conditional' : ''; ?>" id="<?php echo esc_attr($name); ?>_administrative_area_level_1"  
                                        <?php echo esc_attr($this->_attributes['state_req']) ?>
                                        placeholder="<?php echo !empty($this->_attributes['label_as_placeholder']) ? esc_attr($this->_attributes['state_label']) : '' ?>"
                                       name="<?php echo esc_attr($name); ?>[state]" value="<?php echo esc_attr($value['state']); ?>" <?php echo esc_attr($field_options); ?>/>
                                <?php if(empty($this->_attributes['label_as_placeholder'])) : ?>
                                    <div class="label"><?php echo esc_html($this->_attributes['state_label']); ?>
                                    <?php if(!empty($this->_attributes['state_req'])): ?>    
                                    <sup class="address_req required">&nbsp;*</sup>
                                <?php endif; ?>  
                                    </div>
                                <?php endif; ?>
                           
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>    
                
                     <?php if($this->_attributes['state_type']=='america_can'): ?>
                            <div class="slimField rm-address-hw">
                                <select <?php echo wp_kses_post((string)$style); ?> class="field<?php echo !empty($field_options) ? ' data-conditional' : ''; ?>" <?php echo esc_attr($this->_attributes['state_req']) ?>
                                        id="<?php echo esc_attr($name); ?>_administrative_area_level_1"  
                                        name="<?php echo esc_attr($name); ?>[state]" value="<?php echo esc_attr($value['state']); ?>" <?php echo esc_attr($field_options); ?>>
                                    <option value=""><?php echo esc_html($this->_attributes['state_label']); ?></option> 
                                </select>
                                <?php if(empty($this->_attributes['label_as_placeholder'])) : ?>
                                    <div class="label"><?php echo esc_html($this->_attributes['state_label']); ?>
                                    <?php if(!empty($this->_attributes['state_req'])): ?>    
                                    <sup class="address_req required">&nbsp;*</sup>
                                <?php endif; ?> 
                                    </div>
                                <?php endif; ?>
   
                            </div>
                        <?php endif; ?>
            </div>
            
            <div class="rm_ad_container">
                    <?php if ($this->_attributes['country_en']) : ?>
                        <?php if($this->_attributes['state_type']=='all'): ?>
                            <div  class="wideField rm-alone rm-address-hw" colspan="3">
                                <select <?php echo wp_kses_post((string)$style); ?> id="<?php echo esc_attr($name); ?>_country" <?php echo esc_attr($this->_attributes['country_req']) ?>
                                        name="<?php echo esc_attr($name); ?>[country]" class="<?php echo !empty($field_options) ? ' data-conditional' : ''; ?>" <?php echo esc_attr($field_options); ?>>
                                    <?php $countries= RM_Utilities::get_countries(); ?>
                                    <option value=""><?php echo!empty($this->_attributes['label_as_placeholder']) ? esc_html($this->_attributes['country_label']) : '' ?></option>
                                    <?php foreach($countries as $index=>$country) : if(empty($index)) continue; ?>
                                            <?php if($value['country']==$country) : ?>
                                                 <option selected><?php echo esc_html($country); ?></option>
                                            <?php else:?>     
                                                 <option><?php echo esc_html($country); ?></option>
                                            <?php endif; ?> 
                                        
                                    <?php endforeach; ?>    
                                </select>
                                <?php $this->show_advance_search($name.'_country'); ?>
                                <?php if(empty($this->_attributes['label_as_placeholder'])) : ?>
                                    <div class="label"><?php echo esc_html($this->_attributes['country_label']); ?>
                                    <?php if(!empty($this->_attributes['country_req'])): ?>    
                                    <sup class="address_req required">&nbsp;*</sup>
                                <?php endif; ?> 
                                    </div>
                                <?php endif; ?>
                             
                            </div>
                        <?php endif; ?>        
                
                        <?php if($this->_attributes['state_type']=='america'): ?>
                            <div class="wideField rm-alone rm-address-hw" colspan="3">
                                <input type="text" <?php echo wp_kses_post((string)$style); ?> class="field<?php echo !empty($field_options) ? ' data-conditional' : ''; ?>" id="<?php echo esc_attr($name); ?>_country"   
                                       <?php echo esc_attr($this->_attributes['country_req']) ?>
                                       name="<?php echo esc_attr($name); ?>[country]" value="United States" readonly <?php echo esc_attr($field_options); ?>></input>
                                <?php if(empty($this->_attributes['label_as_placeholder'])) : ?>
                                    <div class="label"><?php echo esc_html($this->_attributes['country_label']); ?>
                                        <?php if(!empty($this->_attributes['country_req'])): ?>    
                                    <sup class="address_req required">&nbsp;*</sup>
                                <?php endif; ?> 
                                    </div>
                                <?php endif; ?>
                             
                            </div>
                        <?php endif; ?>    
                
                        <?php if($this->_attributes['state_type']=='america_can'):  ?>
                            <div class="wideField rm-alone rm-address-hw" colspan="3">
                                <select <?php echo wp_kses_post((string)$style); ?> onchange="rm_load_states(this.value,'<?php echo esc_attr($name); ?>_administrative_area_level_1','<?php echo esc_attr($this->_attributes["state_as_code"]) ?>'); rm_validate_zipcode('<?php echo esc_attr($name); ?>')" class="field<?php echo !empty($field_options) ? ' data-conditional' : ''; ?>" id="<?php echo esc_attr($name); ?>_country" 
                                         name="<?php echo esc_attr($name); ?>[country]" <?php echo esc_attr($field_options); ?>>
                                    <option value="US" <?php echo $value['country']=="US" ? 'selected' :'' ?>>United States</option>
                                    <option value="Canada" <?php echo $value['country']=="Canada" ? 'selected' :'' ?>>Canada</option>
                                </select>
                                <?php //!empty($value['country']) && !empty($value['state']) ?>
                                <script>
                                    jQuery(document).ready(function(){
                                        var selected_country= jQuery("#<?php echo wp_kses_post((string)$name); ?>_country").val();
                                        rm_load_states(selected_country,'<?php echo wp_kses_post((string)$name); ?>_administrative_area_level_1','<?php echo wp_kses_post((string)$this->_attributes["state_as_code"]) ?>',"<?php echo wp_kses_post((string)$value['state']) ?>");
                                    });
                                </script>
                            </div>
                        <?php endif; ?> 
                
                        <?php if($this->_attributes['state_type']=='limited'): ?>
                        <?php if(empty($this->_attributes['countries'])) : ?>
                                <div class="wideField rm-alone rm-address-hw" colspan="3">
                                    <input type="text" <?php echo wp_kses_post((string)$style); ?> class="field<?php echo !empty($field_options) ? ' data-conditional' : ''; ?>" id="<?php echo esc_attr($name); ?>_country"   
                                           <?php echo esc_attr($this->_attributes['country_req']) ?>
                                           placeholder="<?php echo !empty($this->_attributes['label_as_placeholder']) ? esc_attr($this->_attributes['country_label']) : '' ?>"
                                           name="<?php echo esc_attr($name); ?>[country]" value="<?php echo esc_attr($value['country']); ?>" <?php echo esc_attr($field_options); ?>></input>
                                    <?php if(empty($this->_attributes['label_as_placeholder'])) : ?>
                                        <div class="label"><?php echo esc_html($this->_attributes['country_label']); ?>
                                            <?php if(!empty($this->_attributes['country_req'])): ?>    
                                        <sup class="address_req required">&nbsp;*</sup>
                                    <?php endif; ?>   
                                        
                                        </div>
                                    <?php endif; ?>
                                 
                                </div>
                        <?php else: ?>
                              <div class="wideField rm-alone rm-address-hw" colspan="3">
                                <select <?php echo wp_kses_post((string)$style); ?> <?php echo esc_attr($this->_attributes['country_req']) ?> class="field<?php echo !empty($field_options) ? ' data-conditional' : ''; ?>" id="<?php echo esc_attr($name); ?>_country" 
                                         name="<?php echo esc_attr($name); ?>[country]" value="United States" <?php echo esc_attr($field_options); ?>>
                                    <option value=""><?php echo esc_html($this->_attributes['country_label']); ?></option>
                                    <?php foreach($this->_attributes['countries'] as $country) : ?>
                                            <option><?php echo esc_html($country); ?></option>
                                    <?php endforeach; ?>        
                                </select>
                                <?php $this->show_advance_search($name.'_country'); ?>
                                <?php if(!empty($this->_attributes['country_req'])): ?>    
                                    <sup class="address_req required">&nbsp;*</sup>
                                <?php endif; ?>  
                             </div>  
                        <?php endif; ?>
                      <?php endif; ?>  
                
                       
                        
                    <?php endif; ?> 
                    
                    <?php if ($this->_attributes['zip_en']) : ?>
                        <div class="wideField rm-semi-field-with-label rm-address-hw">
                                <input type="text" onchange="rm_validate_zipcode('<?php echo esc_attr($name); ?>')" <?php echo wp_kses_post((string)$style); ?> class="field<?php echo !empty($field_options) ? ' data-conditional' : ''; ?>" id="<?php echo esc_attr($name); ?>_postal_code" 
                                        <?php echo esc_attr($this->_attributes['zip_req']) ?>
                                        placeholder="<?php echo !empty($this->_attributes['label_as_placeholder']) ? esc_attr($this->_attributes['zip_label']) : '' ?>"
                                        name="<?php echo esc_attr($name); ?>[zip]" value="<?php echo esc_attr($value['zip']); ?>" <?php echo esc_attr($field_options); ?>></input> 
                                <?php if(empty($this->_attributes['label_as_placeholder'])) : ?>
                                    <div class="label label-short"><?php echo esc_html($this->_attributes['zip_label']); ?>
                                    
                                           <?php if(!empty($this->_attributes['zip_req'])): ?>    
                                    <sup class="address_req required">&nbsp;*</sup>
                                <?php endif; ?>   
                                    </div>
                                <?php endif; ?>
                          
                        </div>
                    <?php endif; ?> 
                    
            </div>
            
        </div>

        <?php endif; 
        //@ini_set('error_reporting', E_ALL);
    }

}
