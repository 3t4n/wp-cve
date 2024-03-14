<?php
/**
 *
 * @package ESIG_NINJAYFORM_DOCUMENT_VIEW
 * @author  Abu Shoaib <abushoaib73@gmail.com>
 */



if (! class_exists('esig-ninjaform-document-view')) :
class esig_ninjaform_document_view {
    
    
            /**
        	 * Initialize the plugin by loading admin scripts & styles and adding a
        	 * settings page and menu.
        	 * @since     0.1
        	 */
        	final function __construct() {
                        
        	}
        	
        	/**
        	 *  This is add document view which is used to load content in 
        	 *  esig view document page
        	 *  @since 1.1.0
        	 */
        	
        	final function add_document_view()
        	{
        	  
        	    $assets_dir = ESIGN_ASSETS_DIR_URI;
        	    
                    
        	   $more_option_page = ''; 
        	   
        	    
        	    $more_option_page .= '<div id="esig-ninja-option" class="esign-form-panel" style="display:none;">
        	        
        	        
                	               <div align="center"><img src="' . esc_url($assets_dir) .'/images/logo.png" width="200px" height="45px" alt="Sign Documents using WP E-Signature" width="100%" style="text-align:center;"></div>
                    			
                                    
                    				<div id="esig-ninja-form-first-step">
                        				
                                        	<div align="center" class="esig-popup-header esign-form-header">'.__('What Are You Trying To Do?', 'esig').'</div>
                                            	
                        				<p id="create_ninja" align="center">';
                                	    
                                	    $more_option_page .=	'
                        			
                        				<p id="select-ninja-form-list" align="center">
                                	    
                        		        <select data-placeholder="Choose a Option..." class="chosen-select" tabindex="2" id="esig-ninja-form-id" name="esig_ninja_form_id">
                        			     <option value="sddelect">'.__('Select a Ninja Form', 'esig').'</option>';
                                	    
                                	   
                                	       
                                	        $more_option_page .= ESIG_NF_SETTING::ninja_form_option();
                                	    
                                	    
                                	    $more_option_page .='</select>
                                	    
                        				</p>
                         	  
                                	    </p>
                                	    
                                        <p id="upload_ninja_button" align="center">
                                           <a href="#" id="esig-ninja-create" class="button-primary esig-button-large">'.__('Next Step', 'esig').'</a>
                                         </p>
                                     
                                    </div>  <!-- Frist step end here  --> ';
                            
                                    
                 $more_option_page .='<!-- Ninja form second step start here -->
                                            <div id="esig-nf-second-step" style="display:none;">
                                            
                                        	<div align="center" class="esig-popup-header esign-form-header">'.__('What ninja form field data would you like to insert?', 'esig').'</div>
                                            
                                            <p id="esig-nf-field-option" align="center"> </p>
                                            
                                        <p id="select-ninja-field-display-type" align="center">
                                	    
                        		        <select data-placeholder="Choose a Option..." class="chosen-select" tabindex="2" id="esig-ninja-form-id" name="esig_ninja_value_display_type">
                        			     <option value="value">'.__('Select a display type', 'esig').'</option>
                                          
                                         
                                           <option value="value">'.__('Display value', 'esig').'</option>
                                           <option value="label">'.__('Display label', 'esig').'</option>
                                           <option value="label_value">'.__('Display label + value', 'esig').'</option>';
                                	   
                                           
                                	    $more_option_page .='</select>
                                	    
                        				</p>
                                            
                                             <p id="upload_ninja_button" align="center">
                                           <a href="#" id="esig-ninja-insert" class="button-primary esig-button-large">'.__('Add to Document', 'esig').'</a>
                                         </p>
                                            
                                            </div>
                                    <!-- Ninja form second step end here -->';           
                                    
                                    
        	    
        	    $more_option_page .= '</div><!--- ninja option end here -->' ;
        	    
        	    
        	    return $more_option_page ; 
        	}
        	
        	
        	final function add_document_view_modal()
        	{
                    
                    
        	    if(! function_exists('WP_E_Sig'))
        	        return ;
        	    
        	    $assets_dir = ESIGN_ASSETS_DIR_URI;
        	    
        	    $more_option_page = '' ; 
        	    
        	    // first modal button start here 
        	    
        	    // first modal start here 
        	    
        	 $more_option_page .= '   <div style="display:none;" class="modal fade esig-ninja-modal-md" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        	    <div class="modal-dialog modal-md">
        	    <div class="modal-content">
        	        
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   
        	     <!--  modal content start  here -->
        	       <p>&nbsp;</p> 
        	     <div align="center"><img src="' . esc_url($assets_dir) .'/images/logo.png" width="200px" height="45px" alt="Sign Documents using WP E-Signature" width="100%" style="text-align:center;"></div>
                 <h2 class="esign-form-header">'.__('What Are You Trying To Do?', 'esig').'</h2>
                            	    
        	     <p id="create_ninja" align="center">';
                            	    
                            	    $more_option_page .=	'
                    				<form id="esig_create_template" name="esig-view-document" action="" method="post" >
                    				<p id="select-ninja-form-list" align="center">
                            	    
                    		        <select data-placeholder="Choose a Option..." class="chosen-select" width="100%" tabindex="2" id="esig-ninja-form-field" name="esig_ninja_form_id">
                    			<option value="sddelect">'.__('Select Ninja Form Field', 'esig').'</option>';
                                         $form_id = ESIG_POST('form_id');
                            	         
                                                        
                                                        $more_option_page .=ESIG_NF_SETTING::ninja_form_fields($form_id);
                                                   
                                	    
                                	    $more_option_page .='</select>
                            	    
                    				</p>
                            	    </form>
                   </p>
        	       <p>&nbsp;</p>
                           	        
                   <p id="upload_ninja_button" align="center">
                            <a href="#" id="esig-ninja-create" class="button-primary esig-button-large">'.__('Next Step', 'esig').'</a>
                    </p>
                    <p>&nbsp;</p>             
        	     <!-- modal content end here -->
        	    </div>
        	    </div>
        	    </div>';
        	    
        	    
        	    
        	    return $more_option_page ;
        	    
        	}
	   
    }
endif ; 

