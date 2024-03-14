<?php
/*
    "WordPress Plugin Template" Copyright (C) 2018 Michael Simpson  (email : michael.d.simpson@gmail.com)

    This file is part of WordPress Plugin Template for WordPress.

    WordPress Plugin Template is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    WordPress Plugin Template is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see http://www.gnu.org/licenses/gpl-3.0.html
*/

class WoocommerceAliexpressDropshipping_OptionsManager
{

    public function getOptionNamePrefix()
    {
        return get_class($this) . '_';
    }



    public function getOptionMetaData()
    {
        return array();
    }

    /**
     * @return array of string name of options
     */
    public function getOptionNames()
    {
        return array_keys($this->getOptionMetaData());
    }

    /**
     * Override this method to initialize options to default values and save to the database with add_option
     * @return void
     */
    protected function initOptions()
    { }

    /**
     * Cleanup: remove all options from the DB
     * @return void
     */
    protected function deleteSavedOptions()
    {
        $optionMetaData = $this->getOptionMetaData();
        if (is_array($optionMetaData)) {
            foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
                $prefixedOptionName = $this->prefix($aOptionKey); // how it is stored in DB
                delete_option($prefixedOptionName);
            }
        }
    }

    /**
     * @return string display name of the plugin to show as a name/title in HTML.
     * Just returns the class name. Override this method to return something more readable
     */
    public function getPluginDisplayName()
    {
        return get_class($this);
    }

    /**
     * Get the prefixed version input $name suitable for storing in WP options
     * Idempotent: if $optionName is already prefixed, it is not prefixed again, it is returned without change
     * @param  $name string option name to prefix. Defined in settings.php and set as keys of $this->optionMetaData
     * @return string
     */
    public function prefix($name)
    {
        $optionNamePrefix = $this->getOptionNamePrefix();
        if (strpos($name, $optionNamePrefix) === 0) { // 0 but not false
            return $name; // already prefixed
        }
        return $optionNamePrefix . $name;
    }


    public function &unPrefix($name)
    {
        $optionNamePrefix = $this->getOptionNamePrefix();
        if (strpos($name, $optionNamePrefix) === 0) {
            return substr($name, strlen($optionNamePrefix));
        }
        return $name;
    }


    public function getOption($optionName, $default = null)
    {
        $prefixedOptionName = $this->prefix($optionName); // how it is stored in DB
        $retVal = get_option($prefixedOptionName);
        if (!$retVal && $default) {
            $retVal = $default;
        }
        return $retVal;
    }

    public function deleteOption($optionName)
    {
        $prefixedOptionName = $this->prefix($optionName); // how it is stored in DB
        return delete_option($prefixedOptionName);
    }

    /**
     * A wrapper function delegating to WP add_option() but it prefixes the input $optionName
     * to enforce "scoping" the options in the WP options table thereby avoiding name conflicts
     * @param  $optionName string defined in settings.php and set as keys of $this->optionMetaData
     * @param  $value mixed the new value
     * @return null from delegated call to delete_option()
     */
    public function addOption($optionName, $value)
    {
        $prefixedOptionName = $this->prefix($optionName); // how it is stored in DB
        return add_option($prefixedOptionName, $value);
    }



    /**
     * A Role Option is an option defined in getOptionMetaData() as a choice of WP standard roles, e.g.
     * 'CanDoOperationX' => array('Can do Operation X', 'Administrator', 'Editor', 'Author', 'Contributor', 'Subscriber')
     * The idea is use an option to indicate what role level a user must minimally have in order to do some operation.
     * So if a Role Option 'CanDoOperationX' is set to 'Editor' then users which role 'Editor' or above should be
     * able to do Operation X.
     * Also see: canUserDoRoleOption()
     * @param  $optionName
     * @return string role name
     */
    public function getRoleOption($optionName)
    {
        $roleAllowed = $this->getOption($optionName);
        if (!$roleAllowed || $roleAllowed == '') {
            $roleAllowed = 'Administrator';
        }
        return $roleAllowed;
    }

    /**
     * Given a WP role name, return a WP capability which only that role and roles above it have
     * http://codex.wordpress.org/Roles_and_Capabilities
     * @param  $roleName
     * @return string a WP capability or '' if unknown input role
     */
    protected function roleToCapability($roleName)
    {
        switch ($roleName) {
            case 'Super Admin':
                return 'manage_options';
            case 'Administrator':
                return 'manage_options';
            case 'Editor':
                return 'publish_pages';
            case 'Author':
                return 'publish_posts';
            case 'Contributor':
                return 'edit_posts';
            case 'Subscriber':
                return 'read';
            case 'Anyone':
                return 'read';
        }
        return '';
    }

    /**
     * @param $roleName string a standard WP role name like 'Administrator'
     * @return bool
     */
    public function isUserRoleEqualOrBetterThan($roleName)
    {
        if ('Anyone' == $roleName) {
            return true;
        }
        $capability = $this->roleToCapability($roleName);
        return current_user_can($capability);
    }

    /**
     * @param  $optionName string name of a Role option (see comments in getRoleOption())
     * @return bool indicates if the user has adequate permissions
     */
    public function canUserDoRoleOption($optionName)
    {
        $roleAllowed = $this->getRoleOption($optionName);
        if ('Anyone' == $roleAllowed) {
            return true;
        }
        return $this->isUserRoleEqualOrBetterThan($roleAllowed);
    }

    /**
     * see: http://codex.wordpress.org/Creating_Options_Pages
     * @return void
     */
    public function createSettingsMenu()
    {
        $pluginName = $this->getPluginDisplayName();
        //create new top-level menu
        // add_menu_page(
        //     $pluginName . ' Plugin Settings',
        //     $pluginName,
        //     'administrator',
        //     get_class($this),
        //     array(&$this, 'settingsPage')
        //     /*,plugins_url('/images/icon.png', __FILE__)*/
        // ); // if you call 'plugins_url; be sure to "require_once" it

        // //call register settings function
        // add_action('admin_init', array(&$this, 'registerSettings'));
    }

    public function registerSettings()
    {
        $settingsGroup = get_class($this) . '-settings-group';
        $optionMetaData = $this->getOptionMetaData();
        foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
            register_setting($settingsGroup, $aOptionMeta);
        }
    }



    /**
     * Creates HTML for the Administration page to set options for this plugin.
     * Override this method to create a customized page.
     * @return void
     */
    public function settingsPage()
    {
        // wp_enqueue_script('startup', plugin_dir_url(__FILE__) . 'js/startup.js', array('jquery'), NULL, false);
        // wp_enqueue_script('ebay', plugin_dir_url(__FILE__) . 'js/ebay-import.js', array('jquery'), NULL, false);
        wp_enqueue_script('toast', plugin_dir_url(__FILE__) . 'js/jquery.toast.min.js', array('jquery'), NULL, false);
        wp_enqueue_script('bootstrap', plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array('jquery'), NULL, false);
        wp_enqueue_style('toastCss', plugin_dir_url(__FILE__) . 'css/jquery.toast.min.css');
        wp_enqueue_style('customcss', plugin_dir_url(__FILE__) . 'css/main.css');
        wp_enqueue_style('bootstrapCss', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css');
        wp_enqueue_script('quill', plugin_dir_url(__FILE__) . 'js/quill.js', array('jquery'), NULL, false);
        wp_enqueue_style('quillCss', plugin_dir_url(__FILE__) . 'css/quill.css');
        wp_enqueue_script('math', plugin_dir_url(__FILE__) . 'js/math.js', array('jquery'), NULL, false);
        wp_enqueue_style('awesome', plugin_dir_url(__FILE__) . 'css/font-awesome.css');
        wp_enqueue_style('mdbcss', plugin_dir_url(__FILE__) . 'css/mdb.min.css');
        wp_enqueue_script('fontawJs', plugin_dir_url(__FILE__) . 'js/font-aesome.min.js', array('jquery'), NULL, false);



        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'wooshark-aliexpress-importer'));
        }

        wp_localize_script(
            'startup',
            'wooshark_params',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('ajax-nonce')
            )
        );


        ?>

            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset;padding-top: 5px; margin-top: 8px; background-color: #d899e6; border-top-left-radius: 10px; border-top-right-radius: 14px">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">AliExpress</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" id="pills-ebay-tab" data-bs-toggle="pill" href="#pills-ebay" role="tab" aria-controls="pills-ebay" aria-selected="true">eBay</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" id="pills-etsy-tab" data-bs-toggle="pill" href="#pills-etsy" role="tab" aria-controls="pills-etsy" aria-selected="true">Etsy</a>
                </li>


                <!-- <li class="nav-item">
        <a class="nav-link" id="etsy-products" href="#etsy-products" role="tab" aria-controls="etsy-products" aria-selected="true">ETSY</a>
    </li> -->

                <li class="nav-item">
                    <a class="nav-link" id="Amazon-products" href="#Amazon-products" role="tab" aria-controls="Amazon-products" aria-selected="true" target="_blank" href="https://chrome.google.com/webstore/detail/wooshark-for-aliexpresseb/ajbncoijgeclkangiahiphilnolbdmmh">
                        <small style="color: red"> Amazon (available in chrome extension)</small>
                    </a>
                </li>

                <!-- <li class="nav-item">
                    <a class="nav-link" id="pills-connect-products" data-bs-toggle="pill" href="#pills-products" role="tab" aria-controls="pills-connect" aria-selected="false">Products <span id="productsCount"></span><span class="loaderImporttoShopProducts" style="display:none"></span></a>
                </li> -->

                <li class="nav-item">
                    <a class="nav-link" id="pills-config-tab" data-bs-toggle="pill" href="#pills-config" role="tab" aria-controls="pills-config" aria-selected="false">Configuration <i class="fa fa-cogs"></i></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" id="go-pro-tab" data-bs-toggle="pill" href="#go-pro" role="tab" aria-controls="go-pro" aria-selected="true" style="color: red">Go Pro</a>
                </li>
            </ul>



            <div class="tab-content" id="pills-tabContent" style="background-color:#f3f5f6">
                <div style="height:20px; color:grey"></div>
                <div class="tab-pane active in" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

                    <div class="wrap">

                        <div style="box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;display: flex;justify-content: space-between;padding:20px; background-color: white; margin: 20px;">
                            <span style="font-weight: bold;font-size: larger;">unlock all the advanced features and unlimited import by getting the chrome extension from here <a targer="_blank" href="https://sharkdropship.com/wooshark-dropshipping"> <i class="fas fa-download fa-1x"></i> </a></span>
                            <button target="_blank" href="https://sharkdropship.com/wooshark-dropshipping" class="btn btn-danger" style="float:right" type="button">
                                <a style="color:white" target="_blank" href="https://sharkdropship.com/wooshark-dropshipping"> <small> GO PRO</small> </a>
                            </button>
                        </div>

                        <div class="first-line-search">


                            <div style="box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;display:flex; padding:2%;  margin: 1%;border-radius: 10px; background-color:white">

                                <div class="loader2" style="display:none">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>

                                <div class="loader3" style="display:none">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>

                                <!-- <div style="flex: 1 1 48%; margin-right:1%">
                                    <h4 style="font-weight:bold" for="productSku"> Insert by Sku</h4>

                                    <div style="display:flex">
                                        <input style="flex: 1 1 65%; border-radius: 10px;" placeholder='paste AliExpress product Sku' type='text' class="custom-form-control" style="border: 1px solid grey;border-radius: 10px; !important" id="productSku">
                                        <button style="flex: 1 1 35%; margin:0; margin-left:5px; border-radius: 10px;    " class="btn btn-primary" id="importProductToShopBySky"> Import </button>
                                    </div>

                                </div> -->

                                <div style="flex: 1 1 100%; margin-left:1%">

                                    <h4 style="font-weight:bold" for="productUrl"> Insert by Url</h4>



                                    <div style="display:flex">
                                        <input style="flex: 1 1 65%; border-radius: 10px;" placeholder='paste AliExpress product url' type='text' class="custom-form-control" style="border: 1px solid grey;border-radius: 10px; !important" id="productUrl">
                                        <button style="flex: 1 1 35%; margin:0; margin-left:5px; border-radius: 10px;    " class="btn btn-primary" id="importProductToShopByUrl"> Import </button>
                                    </div>

                                </div>

                            </div>


                            <div class="search-form" style="box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;padding:2%;  margin: 1%;border-radius: 10px; background-color:white">
                                <h4 style="font-weight:bold">Search by keyword</h4>

                                <div style="display:flex">
                                    <input style="border-radius: 10px; flex: 1 1 83%" placeholder='Search keyword example, shoes, smartphones, etc..' type='text' class="custom-form-control" style="border: 1px solid grey;border-radius: 10px; !important" id="searchKeyword">
                                    <button style="border-radius: 10px; flex: 1 1 17%; margin:0; margin-left:5px;     " class="btn btn-primary" id="seachProductsButton"> Search Products</button>
                                </div>

                            </div>

                            <div class="currencyDetails" style="box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;padding:1%;  margin: 1%;border-radius: 10px; background-color:white">
                            </div>
                        </div>

                        <div class="form-check">
                        </div>

                        <div id="product-search-container" style="display:flex; -justify-content: space-between;flex-wrap:wrap; margin-top:1%; box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px;">

                        </div>

                        <nav aria-label="pagination" style="text-align:center;">
                            <ul id="pagination" class="pagination pagination-lg justify-content-center">
                            </ul>
                        </nav>

                        <hr>
                        <div style="display:flex">
                        </div>

                    </div>

                </div>


                <!-- ///////////////////////////////////////////// -->
                <!-- ///////////////////////////////////////////// -->
                <!-- ///////////////////////////////////////////// -->
                <!-- ///////////////////////////////////////////// -->
                <!-- ///////////ETSY ETSYY////////////////////////////////// -->


                <!--  -->

                <!-- <div class="tab-pane fade" id="etsy-products" role="tabpanel" aria-labelledby="etsy-products">
                    
                </div> -->


                <!-- ///////////////////////////////////////////// -->
                <!-- ///////////////////////////////////////////// -->
                <!-- ///////////////////////////////////////////// -->



                <div class="tab-pane fade" id="pills-etsy" role="tabpanel" aria-labelledby="pills-etsy-products">

                 

                </div>





                <!-- ///////////////////////////////////////////// -->
                <!-- ///////////EBAY EBAY////////////////////////////////// -->

                <div class="tab-pane fade" id="pills-ebay" role="tabpanel" aria-labelledby="pills-ebay-products">


                    <div style="display: flex;justify-content: space-between;padding:20px; background-color: white; margin: 20px;">
                        <span style="font-weight: bold;font-size: larger;">unlock all the advanced features and unlimited import by getting the chrome extension from here <a targer="_blank" href="https://sharkdropship.com/wooshark-dropshipping"> <i class="fas fa-download fa-1x"></i> </a></span>
                        <button target="_blank" href="https://sharkdropship.com/wooshark-dropshipping" class="btn btn-danger" style="float:right" type="button">
                            <a style="color:white" target="_blank" href="https://sharkdropship.com/wooshark-dropshipping"> <small> GO PRO</small> </a>
                        </button>
                    </div>


                    <div class="wrap">

                        <div style="display:flex; padding:2%;  margin: 1%;border-radius: 10px; background-color:white">
                            <div class="loader2" style="display:none">
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                            <div class="loader3" style="display:none">
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>

                            <!-- <div style="flex: 1 1 48%; margin-right:1%">
                                <h4 style="font-weight:bold" for="productSku_ebay"> Insert by Id :</h4>
                                <div style="display:flex">
                                    <input style="flex: 1 1 65%; border-radius: 10px;" placeholder='paste eBay product ID' type='text' class="custom-form-control" style="border: 1px solid grey;border-radius: 10px; !important" id="productSku_ebay">
                                    <button style="flex: 1 1 35%; margin:0; margin-left:5px; border-radius: 10px;    " class="btn btn-primary" id="importProductToShopBySky_ebay"> Import </button>
                                </div>
                            </div> -->
                            <div style="flex: 1 1 100%; margin-left:1%">
                                <h4 style="font-weight:bold" for="productUrl"> Insert by Url :</h4>
                                <div style="display:flex">
                                    <input style="flex: 1 1 65%; border-radius: 10px;" placeholder='paste eBay product url' type='text' class="custom-form-control" style="border: 1px solid grey;border-radius: 10px; !important" id="productUrl_ebay">
                                    <button style="flex: 1 1 35%; margin:0; margin-left:5px; border-radius: 10px;    " class="btn btn-primary" id="importProductToShopByUrl_ebay"> Import </button>
                                </div>
                            </div>
                        </div>

                        <div class="search-form" style=" padding:2%;  margin: 1%;border-radius: 10px; background-color:white">
                            <h4 style="font-weight:bold">Search by keyword</h4>

                            <div style="display:flex">
                                <input style="border-radius: 10px; flex: 1 1 83%" placeholder='Search keyword example, shoes, smartphones, etc..' type='text' class="custom-form-control" style="border: 1px solid grey;border-radius: 10px; !important" id="searchKeyword_ebay">
                                <button style="border-radius: 10px; flex: 1 1 17%; margin:0; margin-left:5px;     " class="btn btn-primary" id="seacheBayProductsButton"> Search Products</button>
                            </div>

                            <h4 style="color:blue; margin-top:10px; font-weight:bold">
                                Sorting Preferences
                            </h4>
                            <div style="display:flex">

                                <div style=" flex: 1 1 50%; padding:10px; margin-top:10px;    color: grey;"> <input type="radio" name="sort" value="WatchCountDecreaseSort"> Default<br></div>
                                <div style=" flex: 1 1 50%; padding:10px; margin-top:10px;    color: grey;"> <input type="radio" name="sort" value="PricePlusShippingLowest"> Price ascending<br></div>
                                <div style="flex: 1 1 50%; padding:10px; margin-top:10px;     color: grey;"><input type="radio" name="sort" value="CurrentPriceHighest"> Price descending<br></div>
                                <div style=" flex: 1 1 50%; padding:10px; margin-top:10px;    color: grey;"> <input type="radio" name="sort" value="CountryAscending"> Country ascending<br></div>
                            </div>

                            <h4 style="color:blue; margin-top:10px; font-weight:bold">Shipping preference</h4>

                            <div style="margin-left:10px">
                                <span style="color: grey;"><input style="padding:10px; margin-top:10px" id="isFreeShipping" type="checkbox" /> Free shipping</span>
                            </div>


                        </div>

                        <div class="search-form" style=" padding:2%;  margin: 1%;border-radius: 10px; background-color:white">
                            <h4 style="font-weight:bold">Language</h4>


                            <div style="display:flex">

                                <div style=" flex: 1 1 50%; padding:10px; margin-top:10px;    color: grey;"> <input type="radio" name="ebayLanguage" checked value="EBAY-US"> United states<br></div>
                                <div style=" flex: 1 1 50%; padding:10px; margin-top:10px;    color: grey;"> <input type="radio" name="ebayLanguage" value="EBAY-FR"> France<br></div>
                                <div style="flex: 1 1 50%; padding:10px; margin-top:10px;     color: grey;"><input type="radio" name="ebayLanguage" value="EBAY-GB"> UK<br></div>
                                <div style=" flex: 1 1 50%; padding:10px; margin-top:10px;    color: grey;"> <input type="radio" name="ebayLanguage" value="EBAY-IT"> Italy<br></div>
                                <div style=" flex: 1 1 50%; padding:10px; margin-top:10px;    color: grey;"> <input type="radio" name="ebayLanguage" value="EBAY-AU"> Australia<br></div>
                                <div style=" flex: 1 1 50%; padding:10px; margin-top:10px;    color: grey;"> <input type="radio" name="ebayLanguage" value="EBAY-AT"> Austria<br></div>
                                <div style="flex: 1 1 50%; padding:10px; margin-top:10px;     color: grey;"><input type="radio" name="ebayLanguage" value="EBAY-DE"> Germany<br></div>
                                <div style=" flex: 1 1 50%; padding:10px; margin-top:10px;    color: grey;"> <input type="radio" name="ebayLanguage" value="EBAY-IN"> India<br></div>
                                <div style=" flex: 1 1 50%; padding:10px; margin-top:10px;    color: grey;"> <input type="radio" name="ebayLanguage" value="EBAY-ES"> Spain<br></div>
                                <div style=" flex: 1 1 50%; padding:10px; margin-top:10px;    color: grey;"> <input type="radio" name="ebayLanguage" value="EBAY-ENCA"> Canada (English)<br></div>
                                <div style=" flex: 1 1 50%; padding:10px; margin-top:10px;    color: grey;"> <input type="radio" name="ebayLanguage" value="EBAY-FRCA"> Canada (French)<br></div>
                                <div style=" flex: 1 1 50%; padding:10px; margin-top:10px;    color: grey;"> <input type="radio" name="ebayLanguage" value="EBAY-FRBE"> Belguim (French)<br></div>
                                <div style=" flex: 1 1 50%; padding:10px; margin-top:10px;    color: grey;"> <input type="radio" name="ebayLanguage" value="EBAY-NL"> Netherlands<br></div>
                                <div style=" flex: 1 1 50%; padding:10px; margin-top:10px;    color: grey;"> <input type="radio" name="ebayLanguage" value="EBAY-CH"> Switzerland<br></div>
                                <div style=" flex: 1 1 50%; padding:10px; margin-top:10px;    color: grey;"> <input type="radio" name="ebayLanguage" value="EBAY-HK"> Hong Kong<br></div>
                                <div style=" flex: 1 1 50%; padding:10px; margin-top:10px;    color: grey;"> <input type="radio" name="ebayLanguage" value="EBAY-SG"> Singapore<br></div>
                            </div>


                        </div>



                        <div id="ebay-product-search-container" style="display:flex; -justify-content: space-between;flex-wrap:wrap">
                        </div>

                        <nav aria-label="pagination" style="text-align:center;">
                            <ul id="ebay-pagination" class="pagination pagination-lg justify-content-center">
                                <li id="ebay-page-1" class="ebay-page-item"><a class="page-link active active">1</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>



                <div class="tab-pane fade" id="go-pro" role="tabpanel" aria-labelledby="pills-advanced-tab">

                    <button class="btn btn-primary" style="width:30%; margin-left:40%; margin-top:15px; "><a style="color:white" href="https://sharkdropship.com/wooshark-dropshipping" target="_blank"> GO PRO from here <i class="fas fa-star"></i> </a> </button>

                    <div style="margin-top:10px; background-color:white" role="alert" id="section-2">
                        <div style="display: flex;justify-content: space-between;padding:20px; background-color: white; margin: 20px;">
                            <span style="font-weight: bold;font-size: larger;">unlock all the advanced features and unlimited import by getting the chrome extension from here <a targer="_blank" href="https://sharkdropship.com/wooshark-dropshipping"> <i class="fas fa-download fa-1x"></i> </a></span>
                            <button target="_blank" href="https://sharkdropship.com/wooshark-dropshipping" class="btn btn-danger" style="float:right" type="button">
                                <a style="color:white" target="_blank" href="https://sharkdropship.com/wooshark-dropshipping"> <small> GO PRO</small> </a>
                            </button>
                        </div>
                    </div>


                    <div style="display:flex; -justify-content: space-between;">
                        <div class="card text-center" style="flex: 1 1 20%; margin:30px; padding:50px">

                            <div class="card-body">
                                <h5 class="card-title"> Unlimited import <i class="far fa-file fa-2x"></i></h5>
                                <p class="card-text" style="min-height: 90px;">No import limit we don't have limits on the number of products you import. No extra fees! we guarantee this.</p>
                                <div>
                                </div>
                            </div>

                        </div>

                        <div class="card text-center" style="flex: 1 1 20%; margin:30px; padding:50px">
                            <div class="card-body">
                                <h5 class="card-title"> Direct import from website <i class="fas fa-clone fa-2x"></i></h5>
                                <p class="card-text" style="min-height: 90px;"> our chrome extension allow direct import from aliexpress, ebay,etsy and amazon websites .
                                    <div>
                                    </div>
                            </div>

                        </div>


                        <div class="card text-center" style="flex: 1 1 20%; margin:30px; padding:50px">

                            <div class="card-body">
                                <h5 class="card-title"> Bulk import <i class="far fa-copy fa-2x"></i> </h5>
                                <p class="card-text" style="min-height: 90px;">The shark alloww to select and import many products including all product details with one single click.</p>
                                <div>
                                </div>
                            </div>

                        </div>



                        <div class="card text-center" style="flex: 1 1 20%; margin:30px; padding:50px">

                            <div class="card-body">
                                <h5 class="card-title"> Reviews management <i class="far fa-edit fa-3x"></i></h5>

                                <p class="card-text" style="min-height: 90px;">The shark allow import and customize reviews from aliexpress, including images, text content, date and rating.</p>
                                <div>
                                </div>
                            </div>

                        </div>




                    </div>

                    <div style="display:flex; -justify-content: space-between;">

                        <div class="card text-center" style="flex: 1 1 20%; margin:30px; padding:50px">

                            <div class="card-body">
                                <h5 class="card-title"> Current language and currency <i class="fab fa-cc-paypal fa-3x"></i> </h5>
                                <p class="card-text" style="min-height: 90px;"> The Shark offer the possibility to import the product price and language according the user preferences</p>
                                <div>
                                    <!-- <a href="https://www.youtube.com/watch?v=SzMEfaqAVps" class="btn btn-primary">watch the video</a> -->
                                </div>

                            </div>
                        </div>

                        <div class="card text-center" style="flex: 1 1 20%; margin:30px; padding:50px">

                            <div class="card-body">
                                <h5 class="card-title"> Advanced description editor <i class="fas fa-spell-check fa-3x"></i> </h5>
                                <p class="card-text" style="min-height: 90px;">The Shark offers an advaned description editor that allow to edit the description in real time and see the expected result.</p>
                                <div>
                                    <!-- <a href="#" class="btn btn-primary">watch the video</a> -->
                                </div>

                            </div>
                        </div>

                        <div class="card text-center" style="flex: 1 1 20%; margin:30px; padding:50px">

                            <div class="card-body">
                                <h5 class="card-title"> Advanced image editor <i class="fas fa-image fa-3x"></i> </h5>
                                <p class="card-text" style="min-height: 90px;">The shark offer an advaned image editor that allows to editor pictures and add/remove some effects.</p>
                                <div>
                                    <!-- <a href="#"  class="btn btn-primary">watch the video</a> -->
                                </div>

                            </div>
                        </div>

                        <div class="card text-center" style="flex: 1 1 20%; margin:30px; padding:50px">

                            <div class="card-body">
                                <h5 class="card-title"> variations editor <i class="fab fa-buromobelexperte  fa-3x"></i> </h5>
                                <p class="card-text" style="min-height: 90px;">The shark allow import and customize reviews from aliexpress, including images, text content, date and rating.</p>
                                <div>
                                    <!-- <a href="#"  class="btn btn-primary">watch the video</a> -->
                                </div>

                            </div>
                        </div>

                    </div>

                    <div style="margin-top:10%; margin-left: 30%; margin-right:30%">
                        <h3> How the premuim version works ? How to import products </h3>
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/EYluHMUQB8g" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div style="margin-top:10%; margin-left: 30%; margin-right:30%">
                        <h3> How to import products in bulk from AliExpress?</h3>
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/i8mXaDCmhUw" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>

                    <div style="margin-top:10%; margin-left: 30%; margin-right:30%">


                        <h3> LOOKING FOR A READY-TO-USE DROPSHIPPING STORE?</h3>
                        No more questions on what to do with drop-shipping; our experts are ready to provide you with a Ready Made Online Dropshipping store.

                        Everything you need will be available in our ready-to-use dropshipping stores which are full of high-demand products.
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/ubFODBKip-E" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>


                </div>



                <!-- ///////////////////////////////////////////// -->
                <!-- ///////////////////////////////////////////// -->
                <!-- ///////////////////////////////////////////// -->
                <!-- ///////////////////////////////////////////// -->
                <!-- ///////////////////////////////////////////// -->
                <!-- ///////////////////////////////////////////// -->
                <!-- /////////////////////ORDER//////////////////////// -->
                <!-- //////////////////////////ORDER/////////////////// -->
                <!-- ///////////////////////////////ORDER////////////// -->
                <!-- ///////////////////////////////////////////// -->
                <!-- ///////////////////////////////////////////// -->




                <div class="tab-pane fade" id="pills-orders" role="tabpanel" aria-labelledby="pills-orders">

                    <div class="loader2" style="display:none; z-index:9999">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>

                    <table id="orders" class="table table-striped">
                        <thead>
                            <tr>
                                <th width="10%">Order id</th>
                                <th width="10%">Status</th>
                                <th width="10%">date creation</th>
                                <th width="25%">Customer shipping name</th>
                                <th width="15%">Customer shipping country</th>
                                <th width="15%">Number of products</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>

                    </table>


                    <div class="loader2" style="display:none">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>

                </div>

                <div class="tab-pane fade" id="pills-draft" role="tabpanel" aria-labelledby="pills-draft-products" style="background-color:#f3f5f6">
                    <div style="height:20px; color:grey"></div>

                    <div style="background-color:white; padding:2%; margin:2%">

                        <div class="loader2" style="display:none; z-index:9999">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>

                        <table id="products-wooshark-draft" class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="10%">image</th>
                                    <th width="10%">sku</th>
                                    <th width="10%">id</th>
                                    <th width="25%">title</th>
                                    <th width="15%">link to original page</th>
                                    <th width="15%">Delete Product</th>
                                    <!-- <th width="14%">Set to draft</th> -->

                                </tr>
                            </thead>

                        </table>


                        <div class="loader2" style="display:none">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>



                        <nav aria-label="product-pagination-draft" style="text-align:center;">
                            <ul id="product-pagination-draft" class="pagination pagination-lg justify-content-center">
                            </ul>
                        </nav>
                    </div>
                </div>


                <div class="tab-pane fade" id="pills-config" role="tabpanel" aria-labelledby="pills-config-tab" style="background-color:#f3f5f6">
                    <div class="global-configuration-section">

                        <div style="height:20px; color:grey"></div>


                        <div class="switch-text" style="margin:2%; padding:3%; border-radius:10px; background-color:white">
                            <div style=display:flex>
                                <div style="flex: 1 1 40%">

                                    <h4 style="font-weight:bold">
                                        Define Price markup formula
                                    </h4>
                                </div>
                                <div style="flex: 1 1 48%">
                                </div>

                                <div style="flex: 1 1 10%;">
                                    <label style="margin-right:3px">Add Intervall</label><button id="addInterval" class="btn btn-primary" style=" margin-dight:5px;color:black"> <i class="fa fa-plus"></i> </button>
                                </div>
                            </div>

                            <div class="">
                                <div id="formula">

                                    <table id="table-formula" class="table table-striped" style="margin-top:20px">
                                        <thead>

                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th style='width:15%'> <input class="custom-form-control" name="min" placeholder="Min price"></th>
                                                <th style='width:2%'>-</th>
                                                <th style='width:15%'><input class="custom-form-control" name="max" placeholder="Max price"></th>


                                                <th style='width:16%'>
                                                    <div style="display:flex"><button style="flex: 1 1 10%;border-radius: 1px;margin-top: 0;margin-bottom: 0;margin-right:5px" class="btn btn-light"> Increase by </button><input style="flex: 1 1 78%; border: 1px solid #ccc;" class="multiply custom-form-control" type="number" name="multiply" placeholder="Increase percentage"><button style="flex: 1 1 10%;border-radius: 1px;margin-top: 0;margin-bottom: 0;margin-right:5px" class="btn btn-light"> <i class="fa fa-percent fa-2x"></i> </button></div>
                                                </th>


                                                <th style='width:15%'>
                                                    <div style="display:flex"><button style="flex: 1 1 10%;border-radius: 1px;margin-top: 0;margin-bottom: 0;margin-right:5px" class="btn btn-light"> <i class="fa fa-plus"></i> </button><input style="flex: 1 1 90%; border: 1px solid #ccc;" class="addition custom-form-control" type="number" name="addition" placeholder="Add number"></div>
                                                </th>
                                                <th style="width:3%"><button style="border-radius: 1px;margin-top: 0;margin-bottom: 0;margin-right:5px" id="removeFormulaLine" class="btn btn-danger"> <i class="fa fa-trash"></i> </button></th>

                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                            <h4 style="display: none; color:red" id="reload"> Formula saved, reload product page</h4>
                            <!-- </div> -->
                        </div>

                        <div class="switch-text" style="margin-left:2%; margin-right:2%; margin-top:2%; padding:2%; border-radius:10px; background-color:white">

                            <h4 style="font-weight:bold">
                                Replace text in description and title
                            </h4>
                            <div style="display:flex">
                                <div style="flex:1 1 44%; margin-right:1%">
                                    <label style="margin-bottom:10px; color:#899195">Text to be replaced</label>
                                    <input id="textToBeReplaced" style="margin-bottom:10px" placeholder="text to replece" class="form  form-control" />
                                </div>
                                <div style="flex:1 1 44%;  margin-left:1%">
                                    <label style="margin-bottom:10px; color:#899195">New text</label>
                                    <input id="textToReplace" style="margin-bottom:10px" placeholder="text to replece" class="form  form-control" />
                                </div>


                            </div>
                        </div>

                        <!--  -->
                        <!--  -->
                        <!--  FIRST -->

                        <div class="second-level-section" style="display:flex">
                            <div class="single-import-configuration-section" style="flex: 1 1 48%; margin-left:2%; margin-right:1%; margin-top:2%; padding:2%; border-radius:10px; background-color:white">

                                <h4 style="font-weight:bold"> Import configuration
                                </h4>
                                <div style="margin-bottom:15px; margin-top:20px">

                                    <span style='color:#899195; font-size:'><input id="isImportReviewsSingleImport" type="checkbox" checked name="isImportReviewsSingleImport"> Import reviews </span>
                                </div>

                                <!-- <div style="margin-bottom:15px">

                                    <span style='color:#899195; font-size:'><input id="isImportProductSpecificationSingleImport" type="checkbox" checked name="isImportProductSpecificationSingleImport"> Import product specification </span>
                                </div> -->

                                <!-- <div style="margin-bottom:15px">

                                    <span style='color:#899195; font-size:'><input id="isImportProductDescriptionSingleImport" type="checkbox" checked name="isImportProductDescriptionSingleImport"> Import product description </span>
                                </div> -->

                                <!-- <div style="margin-bottom:15px">

                                    <span style='color:#899195; font-size:'><input id="isPublishProductSingleImport" type="checkbox" checked name="isPublishProductSingleImport"> Publish/draft (if option enabled, products will be published automatically to your shop) </span>
                                </div>

                                <div style="margin-bottom:15px">
                                    <span style='color:#899195; font-size:'><input id="isFeaturedProduct" type="checkbox" checked name="isFeaturedProduct"> Is featured product </span>
                                </div> -->

                                <div style="margin-bottom:15px">
                                    <span style='color:#899195; font-size:'><input id="applyPriceFormulawhileImporting" type="checkbox" checked name="applyPriceFormulawhileImporting"> Apply markup price formula </span>
                                </div>

                            </div>
                        </div>
                    </div>

                    <button id="saveGlobalConfiguration" class="btn btn-primary" style="margin-top:20px; width:20%; margin-left:40%"> Save configuration</button>
                    <div id="savedCorrectlySection" style="color:red; display: none"> Configuration has been saved correctly </div>

                </div>


                <div class="tab-pane fade" id="pills-activation" role="tabpanel" aria-labelledby="pills-activation-tab" style="background-color:#f3f5f6">
                    <div style="height:20px; color:grey"></div>

                    <div style="background-color:white; padding:2%; margin:2%">

                        <div style="margin-tpp:20px">
                            <h4> Activate your license from here</h4>
                            <input id="licenseValue" placeholder="please paste your license received by email here" class="form-control" style="width:100% margin-top:20px" />
                            <button class="btn btn-primary" style="width:100%" id="titiToto"> Check and Activate </button>
                        </div>
                    </div>
                    <div style="height:20px; color:grey"></div>

                </div>



                


                <!-- <button type="button" style="margin:10px; "  class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg"> Import reviews to product</button> -->
                <div class="modal fade bd-example-modal-lg" id="myModalReviews" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="z-index:99999">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <button type="button" style="width:25%; margin-top:10px; display:block" class="btn btn-primary" id="addReview" style="width:50%;margin-top:10px"> Add Review</button>

                                <div id="customReviews" style="overflow-y:scroll;height:500px">
                                    <table id="table-reviews" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Review</th>
                                                <th>Username</th>
                                                <th>email</th>
                                                <th>Date creation</th>
                                                <th>Rating</th>
                                                <th>Remove</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="confirmReviewInsertion" class="btn btn-primary" data-dismiss="modal">Insert Reviews</button>

                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>


            <?php

                }


                protected function createFormControl($aOptionKey, $aOptionMeta, $savedOptionValue)
                { }


                protected function getOptionValueI18nString($optionValue)
                {
                    switch ($optionValue) {
                        case 'true':
                            return __('true', 'woocommerce-aliexpress-dropshipping');
                        case 'false':
                            return __('false', 'woocommerce-aliexpress-dropshipping');

                        case 'Administrator':
                            return __('Administrator', 'woocommerce-aliexpress-dropshipping');
                        case 'Editor':
                            return __('Editor', 'woocommerce-aliexpress-dropshipping');
                        case 'Author':
                            return __('Author', 'woocommerce-aliexpress-dropshipping');
                        case 'Contributor':
                            return __('Contributor', 'woocommerce-aliexpress-dropshipping');
                        case 'Subscriber':
                            return __('Subscriber', 'woocommerce-aliexpress-dropshipping');
                        case 'Anyone':
                            return __('Anyone', 'woocommerce-aliexpress-dropshipping');
                    }
                    return $optionValue;
                }

                /**
                 * Query MySQL DB for its version
                 * @return string|false
                 */
                protected function getMySqlVersion()
                {
                    global $wpdb;
                    $rows = $wpdb->get_results('select version() as mysqlversion');
                    if (!empty($rows)) {
                        return $rows[0]->mysqlversion;
                    }
                    return false;
                }


                public function getEmailDomain()
                {
                    // Get the site domain and get rid of www.
                    $sitename = strtolower($_SERVER['SERVER_NAME']);
                    if (substr($sitename, 0, 4) == 'www.') {
                        $sitename = substr($sitename, 4);
                    }
                    return $sitename;
                }
            }
