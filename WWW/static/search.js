(function() {
	function sortList(event) {
		var date = $(this).data('date');
		$(this).data('running', true);
		var moved = true;
		console.log(date);
		while (moved) {
			var previous = $(this).prev('.list-group-item');

			if (previous.data('running')) {
				break;
			}

			if (previous.data('date') < date) {
				$(this).insertBefore(previous);
			} else {
				moved = false;
			}
		}
		$(this).data('running', false);
		$(this).next('.list-group-item').trigger('load');
	}
	$('.list-group-item').livequery(sortList);
})();