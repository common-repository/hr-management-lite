/* Admin End Scripts */
jQuery(document).ready(function () {
	'use strict';

	/* Color picker */
	jQuery('.color-field').wpColorPicker();

	/* Popover js */
	jQuery('[data-toggle="tooltip"]').tooltip();

	/* Add more field for Leaves option */
	var i = 2;
	jQuery(".add_leave_fields").on("click", function(e){
		e.preventDefault();
	    var x = document.createElement("INPUT");
	    x.setAttribute("type", "text");
	    x.setAttribute("id", "leave_name_"+ i);
	    x.setAttribute("class", "form-control leave_name");
	    x.setAttribute("name", "leave_name[]");
	    x.setAttribute("placeholder", "Name");
	    document.getElementById("dynamic_leave_fields").appendChild(x);

	    var y = document.createElement("INPUT");
	    y.setAttribute("type", "text");
	    y.setAttribute("id", "leave_value_"+ i);
	    y.setAttribute("class", "form-control leave_value");
	    y.setAttribute("name", "leave_value[]");
	    y.setAttribute("placeholder", "Leaves");
	    document.getElementById("dynamic_leave_fields").appendChild(y);
	    i++;
	});

	jQuery(".remove_leave_fields").on("click", function(e){
		e.preventDefault();
		i--;
		jQuery("#leave_name_"+ i ).remove();
		jQuery("#leave_value_"+ i ).remove();
	});

	/* Add more field for Depertments option */
	var i = 2;
	jQuery(".add_depart_fields").on("click", function(e){
		e.preventDefault();
	    var x = document.createElement("INPUT");
	    x.setAttribute("type", "text");
	    x.setAttribute("id", "department_name_"+ i);
	    x.setAttribute("class", "form-control department_name");
	    x.setAttribute("name", "department_name[]");
	    x.setAttribute("placeholder", "Name");
	    document.getElementById("dynamic_depart_fields").appendChild(x);
	    i++;
	});

	jQuery(".remove_depart_fields").on("click", function(e){
		e.preventDefault();
		i--;
		jQuery("#department_name_"+ i ).remove();
	});

	jQuery(".remove-department-single").on("click", function(e){
		e.preventDefault();
		jQuery(this).parent().remove();
	});

});