jQuery(document).ready(function($) {
$(".datepicker").datepicker();

function initialize() {

var input = document.getElementById('d4events_location');
var autocomplete = new google.maps.places.Autocomplete(input);
}

google.maps.event.addDomListener(window, 'load', initialize);

var repeating_event_fields = $('.events-meta-row:nth-last-child(-n+3)');
$(repeating_event_fields).slideUp();

if ($('#d4events_repeating').is(':checked')) {
	$(repeating_event_fields).slideDown();
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
})

$('#d4events_frequency').change(function() {
		var frequency_value = $('#d4events_frequency').val();
		if (frequency_value == 'Weekly') {
			$('.row-d4events_repeat_days').slideDown();
			$('.row-d4events_monthly_repeat_by').slideUp();
		} else {
			$('.row-d4events_repeat_days').slideUp();
			$('.row-d4events_monthly_repeat_by').slideDown();
		}
})

});