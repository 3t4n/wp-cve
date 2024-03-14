class ControlsHook extends $e.modules.hookUI.After {

    getCommand() {
        // Command to listen.
        return 'document/elements/settings';
    }

    getId() {
        // Unique id for the hook.
        return 'woo-ready-editor-controls-handler';
    }
  
    get_shop_seady_controls() {
        return {

            shop_ready_header_logo_type: {
                selector: '.site-header .site-branding',
                callback: ($element, args) => {
                    const classPrefix = 'show-',
                        inputOptions = args.container.controls.shop_ready_header_logo_type.options,
                        inputValue = args.settings.shop_ready_header_logo_type;

                    this.toggleLayoutClass($element, classPrefix, inputOptions, inputValue);
                },
            },
     
            shop_ready_header_menu_layout: {
                selector: '.site-header',
                callback: ($element, args) => {
                    const classPrefix = 'menu-layout-',
                        inputOptions = args.container.controls.shop_ready_header_menu_layout.options,
                        inputValue = args.settings.shop_ready_header_menu_layout;

                    // No matter what, close the mobile menu
                    $element.find('.site-navigation-toggle-holder').removeClass('elementor-active');
                    $element.find('.site-navigation-dropdown').removeClass('show');

                    this.toggleLayoutClass($element, classPrefix, inputOptions, inputValue);
                },
            },
        
            shop_ready_footer_copyright_display: {
                selector: '.site-footer .copyright',
                callback: ($element, args) => {
                    const $footerContainer = $element.closest('#site-footer'),
                        inputValue = args.settings.shop_ready_footer_copyright_display;

                    this.toggleShowHideClass($element, inputValue);

                    $footerContainer.toggleClass('footer-has-copyright', 'yes' === inputValue);
                },
            }
         
        
          
        };
    }

    /**
     * Toggle show and hide classes on containers
     *
     * This will remove the .show and .hide clases from the element, then apply the new class
     *
     */
    toggleShowHideClass(element, inputValue) {
        element.removeClass('hide').removeClass('show').addClass(inputValue ? 'show' : 'hide');
    }

    /**
     * Toggle layout classes on containers
     *
     * This will cleanly set classes onto which ever container we want to target, removing the old classes and adding the new one
     *
     */
    toggleLayoutClass(element, classPrefix, inputOptions, inputValue) {
        // Loop through the possible classes and remove the one that's not in use
        Object.entries(inputOptions).forEach(([key]) => {
            element.removeClass(classPrefix + key);
        });

        // Append the class which we want to use onto the element
        if ('' !== inputValue) {
            element.addClass(classPrefix + inputValue);
        }
    }

    /**
     * Set the conditions under which the hook will run.
     */
    getConditions(args) {
        const isKit = 'kit' === elementor.documents.getCurrent().config.type,
            changedControls = Object.keys(args.settings),
            isSingleSetting = 1 === changedControls.length;

        // If the document is not a kit, or there are no changed settings, or there is more than one single changed
        // setting, don't run the hook.
        if (!isKit || !args.settings || !isSingleSetting) {
            return false;
        }

        // If the changed control is in the list of theme controls, return true to run the hook.
        // Otherwise, return false so the hook doesn't run.
        return !!Object.keys(this.get_shop_seady_controls()).includes(changedControls[0]);
    }

    /**
     * The hook logic.
     */
    apply(args) {
        const allThemeControls = this.get_shop_seady_controls(),
            // Extract the control ID from the passed args
            controlId = Object.keys(args.settings)[0],
            controlConfig = allThemeControls[controlId],
            // Find the element that needs to be targeted by the control.
            $element = elementor.$previewContents.find(controlConfig.selector);

        controlConfig.callback($element, args);
    }
}


class Shop_Ready_H_Component extends $e.modules.ComponentBase {

    pages = {};
 
    getNamespace() {
        return 'shopready-elementor-addon';
    }

    defaultHooks() {
        return this.importHooks({ ControlsHook });
    }
   
}

$e.components.register(new Shop_Ready_H_Component());
