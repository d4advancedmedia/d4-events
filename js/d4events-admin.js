jQuery('body').on('focus',".datepicker_recurring_start", function(){
    jQuery(this).datepicker({ dateFormat: 'mm/dd/yy'});
});

jQuery(document).ready(function($) {

function initialize() {

var input = document.getElementById('d4events_location');
var autocomplete = new google.maps.places.Autocomplete(input);
}

google.maps.event.addDomListener(window, 'load', initialize);

var repeating_event_fields = $('.row-d4events_frequency, .row-d4events_repeat_days, .row-d4events_monthly_repeat_by, .row-d4events_repeat_end_date, .row-d4events_blackout_dates');
$(repeating_event_fields).slideUp(0);

if ($('#d4events_repeating').is(':checked')) {
	$(repeating_event_fields).slideDown(0);
}

if ( ($('#d4events_frequency').val() == "Weekly") && ($('#d4events_repeating').is(':checked')) ) {
	$('.row-d4events_repeat_days').slideDown(0);
	$('.row-d4events_monthly_repeat_by').slideUp(0).find('input').prop('checked', false);
} else if ($('#d4events_repeating').is(':checked')) {
	$('.row-d4events_repeat_days').slideUp(0).find('input').prop('checked', false);
	$('.row-d4events_monthly_repeat_by, .row-d4events_repeat_end_date, .row-d4events_blackout_dates').slideDown(0);
}

$('#d4events_repeating').change(function() {
	if ($('#d4events_repeating').is(':checked')) {
		$('.row-d4events_frequency').slideDown();
		var frequency_value = $('#d4events_frequency').val();
		if (frequency_value == 'Weekly') {
			$('.row-d4events_repeat_days').slideDown();
		}
	} else {
		$(repeating_event_fields).slideUp();
	}
});

$('#d4events_frequency').change(function() {
		var frequency_value = $('#d4events_frequency').val();

		if (frequency_value == 'Weekly') {
			$('.row-d4events_repeat_days').slideDown();
			$('.row-d4events_monthly_repeat_by').slideUp().find('input').prop('checked', false);
		} else {
			$('.row-d4events_repeat_days').slideUp().find('input').prop('checked', false);
			$('.row-d4events_monthly_repeat_by').slideDown();
		}
});


});

datefield = jQuery('#blackout_dates input:last-of-type').clone().attr('id','');
(function($) {
	$('.multi-date-add').click(function() {
		var newfield = $(datefield).clone();
		$('#blackout_dates input:last-of-type').after(newfield);
	});
})( jQuery );

new_singlepass = jQuery('.singlepass:first-child').clone();

(function($) {
	$('.multi-add').click(function() {
		var multipass_wrap = $(this).parent();
		var total = $(multipass_wrap).attr('total');

		// get the last singlepass to prep for cloning
		var lastpass = $(multipass_wrap).find('.singlepass:last');
		var increase_one = parseInt(total) + 1;
		$(multipass_wrap).attr('total' , increase_one);

		var newpass = $(new_singlepass).clone();

		$(newpass).find('select').val( '' ).attr('id','type_d4events_file_' + increase_one).attr('name','d4events_file_' + increase_one + '[0]');
		$(newpass).find('.event-filename').val( '' ).attr('id','name_d4events_file_' + increase_one).attr('name','d4events_file_' + increase_one + '[2]');
		$(newpass).find('.event-fileurl').val( '' ).attr('id','d4events_file_' + increase_one).attr('name','d4events_file_' + increase_one + '[1]');
		$(newpass).find('input[type=button]').attr('id','d4events_file_' + increase_one + '_multipass_upload').attr('name','d4events_file_' + increase_one + '_multipass_upload');

		$(this).before(newpass);
	});


	$(document).on('click', '.multi-delete', function(){ 
		var multipass_wrap = $(this).parents('.multipass-wrap');
		if ($('.singlepass').length == 1) {
			$('.singlepass').find('select').val( '' );
			$('.singlepass').find('input[type=text]').val( '' );
		} else { 
			$(this).parent().remove();
			var total = 1;
			$('.singlepass').each(function() {
				$(this).find('select').attr('id','type_d4events_file_' + total).attr('name','d4events_file_' + total + '[0]');
				$(this).find('.event-filename').attr('id','name_d4events_file_' + total).attr('name','d4events_file_' + total + '[2]');
				$(this).find('.event-fileurl').attr('id','d4events_file_' + total).attr('name','d4events_file_' + total + '[1]');
				$(this).find('input[type=button]').attr('id','d4events_file_' + total + '_multipass_upload').attr('name','d4events_file_' + total + '_multipass_upload');
				total++;
			});
			var newtotal = $('.singlepass').length;
			$(multipass_wrap).attr('total' , newtotal);
		}
	});

	var _custom_media = true,
	_orig_send_attachment = wp.media.editor.send.attachment;

	$(document).on('click', '.multipass_upload', function(){ 
		var send_attachment_bkp = wp.media.editor.send.attachment;
		var button = $(this);
		var id = button.attr('id').replace('_multipass_upload', '');
		_custom_media = true;
		wp.media.editor.send.attachment = function(props, attachment){
			if ( _custom_media ) {
				$("#"+id).val(attachment.url);
			} else {
				return _orig_send_attachment.apply( this, [props, attachment] );
			};
	}

	wp.media.editor.open(button);
		return false;
	});

	$(document).on('click', '.add_media', function(){ 
		_custom_media = false;
	});

})( jQuery );	