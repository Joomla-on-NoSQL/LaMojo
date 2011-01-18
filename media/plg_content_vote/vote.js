// Only define the Joomla namespace if not defined.
if (typeof(Joomla) === 'undefined') {
	var Joomla = {};
}

window.addEvent('domready', function() {
	$$('.vote-button').each(function(el) {
		el.addEvent('mouseenter', function(e) {
			this.addClass('star-hover');
			$$('.vote-button').each(function(el) {
				if (el.value < this.value) {
					el.addClass('star-hover');
				}
			}, this);
		});

		el.addEvent('mouseleave', function(e) {
			this.removeClass('star-hover');
			$$('.vote-button').each(function(el) {
				if (el.value < this.value) {
					el.removeClass('star-hover');
				}
			}, this);
		});
	});
});