export default {
	bindings: {
		tzOffset: '<',
		name: '<',
		value: '<',
	},
	template: `
		<input 
			class="time datetimepicker" 
			value="{{ $ctrl.value }}" 
			name="{{ $ctrl.name }}" 
			data-tz-offset="{{ $ctrl.tzOffset }}" 
			type="text"
		>
	`,
	controller: function( $element, $timeout ) {
		let now      = new Date();
		let userTime = new Date(
			now.getUTCFullYear(), 
			now.getUTCMonth(), 
			now.getUTCDate(),  
			now.getUTCHours(), 
			now.getUTCMinutes() + 1, 
			now.getUTCSeconds(), 
			now.getUTCMilliseconds() + ( this.tzOffset * 1000 ) 
		);

		$timeout( () => {
			$element.children('input').datetimepicker({
				dateFormat: 'dd-mm-yy',
				ampm: true,
				minDate: userTime
			});
		});
	}
}