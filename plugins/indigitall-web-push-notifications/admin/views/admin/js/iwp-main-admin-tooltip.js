document.addEventListener('DOMContentLoaded', function() {
	iwpCustomTooltip();
});

function iwpCustomTooltip() {
	let tags = document.getElementsByTagName('*');
	let	base = document.createElement('tooltip');
	let boxLength;
	let tooltipLeftPosition;
	let position;
	Array.from(tags).forEach(function(tag) {
		if (tag.getAttribute('tooltip') != null) {
			tag.onmouseover = function (event) {
				base.innerHTML = this.getAttribute('tooltip');
				if (document.querySelectorAll('tooltip')) {
					Array.from(document.querySelectorAll('tooltip')).forEach(function(t) {
						t.remove();
					});
				}
				document.body.appendChild(base);

				// Cambiamos la posición del tooltip después de que se muestre porque antes no tenemos datos suficientes
				boxLength = base.offsetWidth;
				position = tag.getBoundingClientRect();
				tooltipLeftPosition = position.left - (boxLength / 2);
				base.style.top = (position.top + 30) + 'px';
				if (tooltipLeftPosition < 0) {
					tooltipLeftPosition = position.left + 20;
				}
				base.style.left = (tooltipLeftPosition + 10) + 'px';
			};
			tag.onmouseout = function () {
				Array.from(document.querySelectorAll('tooltip')).forEach(function(t) {
					t.remove();
				});
			};
		}
	});
}
