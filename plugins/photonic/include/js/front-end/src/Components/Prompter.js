/**
 * Photonic Prompter
 * Used for interactive dialogs, e.g. the Photonic password prompter.
 *
 * GPL v3.0
 */

/*
 * var myPrompter = Prompter('htmlID');
 *
 * id: The HTML id of the object
 */
export class Prompter {
	constructor(id) {
		this.events = {
			onShow    : new Event('onShow'),
			onConfirm : new Event('onConfirm'),
			onHide    : new Event('onHide')
		};
		this.modal            = document.getElementById(id);
		this.classClose       = '.close';
		this.classCancel      = '.cancel';
		this.classConfirm     = '.confirm';
		this.btnsOpen         = [];
	}

	/*
	 * Prompter.show() :
	 *
	 * Shows the modal
	 */
	show() {
		this.modal.dispatchEvent(this.events.onShow);
		this.modal.style.display = "block";
		return this;
	}

	/* Prompter.hide() :
	 *
	 * Hides the modal
	 */
	hide() {
		this.modal.dispatchEvent(this.events.onHide);
		this.modal.style.display = "none";
		return this;
	}

	/*
	* Prompter.removeEvents() :
	*
	* Removes the events (by cloning the modal)
	*/
	removeEvents() {
		const clone = this.modal.cloneNode(true);
		this.modal.parentNode.replaceChild(clone, this.modal);
		this.modal = clone;
		return this;
	}

	/*
	 * Prompter.on(event, callback):
	 *
	 * Connect an event.
	 *
	 * event:
	 *     - 'onShow': Called when the modal is shown (via Prompter.show() or a bound button)
	 *     - 'onConfirm': Called when the modal when the user sends the data (via the element with the class '.confirm')
	 *     - 'onHide': Called when the modal is hidden (via Prompter.hide() or a bound button)
	 * callback: The function to call on the event
	 *
	 */
	on(event, callback) {
		this.modal.addEventListener(event, callback);
		return this;
	}

	/*
	* Prompter.attach() :
	*
	* Attaches the click events on the elements with classes ".confirm", ".hide", ".cancel" plus the elements to show the modal
	*/
	attach() {
		let i;
		let items = [];
		const self = this;

		items = this.modal.querySelectorAll(self.classClose);
		for (i = items.length - 1; i >= 0; i--) {
			items[i].addEventListener('click', function(){
				self.hide();
			});
		}

		items = self.modal.querySelectorAll(self.classCancel);
		for (i = items.length - 1; i >= 0; i--) {
			items[i].addEventListener('click', function(){
				self.hide();
			});
		}

		items = self.modal.querySelectorAll(self.classConfirm);
		for (i = items.length - 1; i >= 0; i--) {
			items[i].addEventListener('click', function(){
				self.modal.dispatchEvent(self.events.onConfirm);
				self.hide();
			});
		}

		for (i = self.btnsOpen.length - 1; i >= 0; i--) {
			self.btnsOpen[i].addEventListener('click', function(){
				self.show();
			});
		}
		return self;
	}

	/*
	 * Attach an external element that will open the modal.
	 * Prompter.addOpenBtn(element)
	 *
	 * element: Any HTML element a button, div, span,...
	 */
	addOpenBtn(element) {
		this.btnsOpen.push(element);
	}
}

