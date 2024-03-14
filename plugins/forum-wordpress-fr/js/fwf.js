/* fwf */

function fwf_copy()
{
	this.copier = null;

	this.init = function() {
		this.copier = new ClipboardJS( '#fwf_copy' );
		this.copier.on( 'success', this.success );
		this.copier.on( 'error',   this.error   );
	};

	this.success = function(e) {
		e.clearSelection();
		console.info('Action:',e.action);
		console.info('Text:',e.text);
		console.info('Trigger:',e.trigger);
		//showTooltip(e.trigger, fwf_L10n.ok);});
		alert(fwf_L10n.ok);

	};

	this.error = function(e) {
		console.error('Action:',e.action);
		console.error('Trigger:',e.trigger);
		//showTooltip(e.trigger,fallbackMessage(e.action));});
		alert(fwf_L10n.ko);
	};

	this.init();
}

jQuery(document).ready( function() { new fwf_copy(); } );