jQuery(document).ready(function(){

	// Initialize Datepicker
	$('.input-datepicker, .input-daterange').datepicker({weekStart: 1}).on('changeDate', function(e){ $(this).datepicker('hide'); });

    // Generate a random password for users
	jQuery("#generate_password").change(function() {
		var generate = jQuery(this).prop('checked');
		if(generate == true){
			jQuery.post(
				'/users/generate-random-password/',
				{generate: 1},
				function(data) {
					jQuery("#generated_password").html(data);
					var new_pw = jQuery("#new_pw").val();
					jQuery("#password").val(new_pw).trigger('keyup');
					jQuery("#password_again").val(new_pw);
				}
			);
		}else{
			var new_pw = '';
			jQuery("#generated_password").html('');
			jQuery("#new_pw").val(new_pw);
			jQuery("#password").val(new_pw).trigger('keyup');
			jQuery("#password_again").val(new_pw);
		}
	});

    //Close alert when close is pressed
    $('.alert-danger .close').click(function(){;
        $('.alert-danger .close').parent().hide();
    })

    //Adding more images to a blog post
    jQuery("#template-add-room").click(function(){
        var current =  jQuery(this).data('id');

        jQuery.post(
			'/templates/getRooms/',
			{current: current},
			function(data) {
				if(data) {
					jQuery("#template-add-room").parent().before(data);
			        removeRooms();
			        addItems();
				} else {
				}
			}
		);
        current = current+1;
        jQuery(this).data('id', current);
    })

    // Remove room if remove room is selected
    jQuery(".remove-room").click(function(){
    	var current =  jQuery(this).data('id');
    	jQuery("#room-group_"+current).remove();

	})

	function removeRooms(){
		jQuery(".remove-room").off();
		jQuery(".remove-room").click(function(){
	    	var current =  jQuery(this).data('id');
	    	jQuery("#room-group_"+current).remove();
		})
	}

	// Add a new input box for the items
	jQuery(".add-items").click(function(){
    	var currentItem =  jQuery(this).data('id');
    	var html = '<div class="form-group ">';
	    		html += '<label class="col-md-3 control-label">Additional Item</label>';
	    		html += '<div class="col-md-4">';
		    		html += '<input type="text" name="items['+currentItem+'][]" class="form-control">';
				html += '</div>';
    	html += '</div>';

    	jQuery('#room-group_'+currentItem).after(html);
	})

	function addItems(){
		jQuery(".add-items").off();
		jQuery(".add-items").click(function(){
	    	var currentItem =  jQuery(this).data('id');
	    	var html = '<div class="form-group ">';
		    		html += '<label class="col-md-3 control-label">Additional Item</label>';
		    		html += '<div class="col-md-4">';
		    			html += '<input type="text" name="items['+currentItem+'][]" class="form-control">';
	    			html += '</div>';
	    	html += '</div>';

	    	jQuery('#room-group_'+currentItem).after(html);
		})
	}

	// Add 
	jQuery(".add-tenants").click(function(){
    	var currentItem =  jQuery(this).data('id');
    	var html = '<div class="form-group ">';
	    		html += '<label class="col-md-2 control-label">Add Other Tenants</label>';
	    		html += '<div class="col-md-5">';
		    		html += '<input type="email" placeholder = "email" name="users[]" class="form-control">';
				html += '</div>';
    	html += '</div>';

    	jQuery('#add-tenants_'+currentItem).after(html);
	})

	// adding check in items
	jQuery(".check-in-add-items").click(function(){
		var currentRoom = jQuery(this).data('id');
		var role = jQuery(this).data('role');
		var count = jQuery(this).data('items');


		if(role == 'lead_tenant'){
			var disabledTenant = '';
		}else{
			var disabledTenant = 'disabled';
		}

		if(role == 'lord'){
			var disabledLord = '';
		}else{
			var disabledLord = 'disabled';
		}

		var html = '<div class="row">';
	    		html += '<div class="col-md-offset-3 col-md-3">';
		    		html += 'Item Name:';
				html += '</div>';
				html += '<div class="col-md-3">';
		    		html += '<input type="text" name="rooms['+currentRoom+'][items][new_'+count+'][name]">';
				html += '</div>';
    		html += '</div>';

    		html += '<div class="row">';
	    		html += '<div class="col-md-offset-3 col-md-3">';
		    		html += 'Item Status:';
				html += '</div>';
				html += '<div class="col-md-3">';
		    		html += 'Red';
				html += '</div>';
    		html += '</div>';

    		html += '<div class="row">';
	    		html += '<div class="col-md-3">';
		    		html += 'Upload Item Image';
				html += '</div>';
				html += '<div class="col-md-3">';
	    			// html += '<input '+disabledTenant+' type = "file" name = "rooms['+currentRoom+'][\'items\'][new_'+count+'][\'image\']" >';
	    			html += '<input '+disabledTenant+' type = "file" name = "new_'+count+'" >';

				html += '</div>';
    		html += '</div>';

    		html += '<div class = "row">';
		        html += '<div class = "col-md-3">';
		            html += 'Lead Tenant Comment';
		        html += '</div>';

		        html += '<div class = "col-md-3">';
		            html += '<input '+disabledTenant+' type="text" class = "form-control" name="rooms['+currentRoom+'][items][new_'+count+'][tenant_comment]">';
		        html += '</div>';

		        html += '<div class = "col-md-3">';
		            html += 'Landlord / Letting Agent Comment';
		        html += '</div>';

		        html += '<div class = "col-md-3">';
		            html += '<input '+disabledLord+' type="text" class = "form-control" name="rooms['+currentRoom+'][items][new_'+count+'][lord_comment]">';
		        html += '</div>';
		    html += '</div>';


       		html += '<div class = "row">';
		        html += '<div class = "col-md-3">';
		            html += 'Lead Tenant Approval';
		        html += '</div>';

		        html += '<div class = "col-md-3">';
		            html += '<select '+disabledTenant+' class="form-control" name="rooms['+currentRoom+'][items][new_'+count+'][tenant_approved]">';
		            	html += '<option value="0">No</option>';
		            	html += '<option value="1">Yes</option>';
		            html += '</select>';
		        html += '</div>';

		        html += '<div class = "col-md-3">';
		            html += 'Landlord / Letting Agent Approval';
		        html += '</div>';

		        html += '<div class = "col-md-3">';
		            html += '<select '+disabledLord+' class="form-control" name="rooms['+currentRoom+'][items][new_'+count+'][lord_approved]">';
		            	html += '<option value="0">No</option>';
		            	html += '<option value="1">Yes</option>';
		            html += '</select>';
		        html += '</div>';
    		html += '</div>';

    	count = count + 1;
		jQuery(this).data('items', count);

    	jQuery('#new-item_'+currentRoom).after(html);
	})

	//If the check out has been changed for tenant approved check in
	jQuery('#tenant_approved_check_in').change(function(){
		//if the save button has been selected
		jQuery("#save-check-in").click(function(event){
			//if the tenant has set the check out to be approved we begin the signing process
			if(jQuery('#tenant_approved_check_in').val() == 1){
				signatureSign(event);
			}
		});
	})

	//If the check out has been changed for tenant approved check in
	jQuery('#lord_approved_check_in').change(function(){
		//if the save button has been selected
		jQuery("#save-check-in").click(function(event){
			//if the tenant has set the check out to be approved we begin the signing process
			if(jQuery('#lord_approved_check_in').val() == 1){
				signatureSign(event);
			}
		});
	})
	
	//If the check out has been changed for tenant approved check out
	jQuery('#tenant_approved_check_out').change(function(){
		//if the save button has been selected
		jQuery("#save-check-out").click(function(event){
			//if the tenant has set the check out to be approved we begin the signing process
			if(jQuery('#tenant_approved_check_out').val() == 1){
				signatureSign(event);
			}
		});
	})

	//If the check out has been changed for tenant approved check out
	jQuery('#lord_approved_check_out').change(function(){
		//if the save button has been selected
		jQuery("#save-check-out").click(function(event){
			//if the tenant has set the check out to be approved we begin the signing process
			if(jQuery('#lord_approved_check_out').val() == 1){
				signatureSign(event);
			}
		});
	})

	//If other tenant signs contract
	jQuery("#other-tenant-sign").click(function(event){
		signatureSign(event);
	});

	function signatureSign(event){
		event.preventDefault();
		jQuery('#signature-pad').show();
		jQuery('html, body').animate({ scrollTop: 0 }, 'slow');
		jQuery("#page-cover").css("opacity",0.6).fadeIn(300, function () {            
			jQuery('#signature-pad').css({'z-index':9999});
     	});
		var wrapper = document.getElementById("signature-pad"),
	    clearButton = wrapper.querySelector("[data-action=clear]"),
	    saveButton = wrapper.querySelector("[data-action=save]"),
	    canvas = wrapper.querySelector("canvas"),
	    signaturePad;

		function resizeCanvas() {
		    var ratio =  Math.max(window.devicePixelRatio || 1, 1);
		    canvas.width = canvas.offsetWidth * ratio;
		    canvas.height = canvas.offsetHeight * ratio;
		    canvas.getContext("2d").scale(ratio, ratio);
		}

		window.onresize = resizeCanvas;
		resizeCanvas();

		signaturePad = new SignaturePad(canvas);

		clearButton.addEventListener("click", function (event) {
		    signaturePad.clear();
		});

		saveButton.addEventListener("click", function (event) {
		    if (signaturePad.isEmpty()) {
		        alert("Please provide signature first.");
		    } else {
		    	var code = signaturePad.toDataURL();
		    	jQuery('#signature-input').val(code);
		        jQuery('#form').submit();
		    }
		});
	}
});