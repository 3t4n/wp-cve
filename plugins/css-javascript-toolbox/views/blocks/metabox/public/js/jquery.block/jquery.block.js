/**
*
*/

/**
*
*/
(function($) {

	/**
	* Override CJTBlockPlugin class.
	*
	* @param node
	* @param args
	*/
	CJTBlockPlugin = function(node, args) {

		// Code has been removed to CJTe Editor Toolbox extension
		// .
		// .
		// .


        this._onclosepanelwindow = function() {

            var codeArea = this.block.box.find('.cjcontainer');
            var panelArea = this.block.box.find('.cjpageblock');

            codeArea.css('margin-right', '0px');
            panelArea.css('width', '0px').hide();

            return false;
        };


        /**
        *
        */
        this._onPaneledItems = function(event) {

            var link = $(event.target);
            var windowName = link.prop('href').match(/#(.+)/)[1];
            var panelWindow = this.block.box.find('.cjt-panel-item.cjt-panel-window-' + windowName);
            var codeArea = this.block.box.find('.cjcontainer');
            var panelArea = this.block.box.find('.cjpageblock');
            var closeButton = panelArea.find('.close-panel-window');

            closeButton.show();

            // Hide all panel windows
            panelArea.find('.cjt-panel-item').hide();

            panelWindow.show();

            codeArea.css('margin-right', '310px');
            panelArea.css('width', '300px').show();

        };

		// Initialize base class.
		this.initCJTPluginBase(node, args);

	} // End class.

	// Extend CJTBlockPlugin class.
	CJTBlockPlugin.prototype = new CJTBlockPluginBase();

})(jQuery);