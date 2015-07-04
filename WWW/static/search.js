(function() {
	$('.result .item').sortElements(function(a, b) {
		return $(a).data('date') > $(b).data('date') ? 1 : -1;
	});
	$('.sort-container .item').click(function(e) {
		var data = $(this).data('sort');
		var order = $(this).data('order') == 'desc' ? 1 : -1;
		$('.result .item').sortElements(function(a, b) {
			return ($(a).data(data) > $(b).data(data) ? 1 : -1) * order;
		});
	});
})();