//Document ready javascript
jQuery(document).ready(function($){
	jQuery('input.custom_date').datepicker();
});


function areyousure(text){
	return confirm(text);
}

function resetAdminForm(){
	jQuery('form#zij_searchform').find('input[type="text"]').val('');
	jQuery('form#zij_searchform').find('select').val('');
}