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
		
		el.addEvent('click', function(e) {
			e.stop();
			var form = e.target.form;
			var req = new Request.JSON({
				method: 'post',
				url: form.action+'&format=json',
				onSuccess: function(r) {
					if (r) {
						Joomla.replaceTokens(r.token);
						if (r.error === false) {
							document.id('rating-count').set('text', r.data.rating_count);
							$$('.vote-button').each(function(el) {
								if (el.value <= this.rating && !el.hasClass('star-on')) {
									el.addClass('star-on');
								}
							}, r.data);
						} else {
							alert(r.message);
						}
					}
				},
				onFailure: function(xhr) {
					var r = JSON.decode(xhr.responseText);
					if (r) {
						Joomla.replaceTokens(r.token);
						alert(r.message);
					}
				}
			});
			req.post(form.toQueryString()+'&user_rating='+e.target.value);
		});
	});
});