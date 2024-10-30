(function($){
	"use strict";
	$(document).ready(function(){
		$('body').on('change', '.c4d-woo-cpp-form select', function(){
			$(this).parents('form').trigger('submit');
		});
	});
})(jQuery);