jQuery(document).ready(function() {
	"use strict";

	/* Color picker */
	jQuery(".color-field").wpColorPicker();

	/** Event **/
	jQuery("#start_time").timepicker({ format: "h:MM TT", autoclose: true });
	jQuery("#end_time").timepicker({ format: "h:MM TT", autoclose: true });
	jQuery("#late_time").timepicker({ format: "h:MM TT", autoclose: true });

	/* Settings */
	jQuery("#halfday_start").timepicker({ format: "h:MM TT", autoclose: true });
	jQuery("#halfday_end").timepicker({ format: "h:MM TT", autoclose: true });
	jQuery("#lunch_start").timepicker({ format: "h:MM TT", autoclose: true });
	jQuery("#lunch_end").timepicker({ format: "h:MM TT", autoclose: true });
});
