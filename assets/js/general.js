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
    //Close alert when close is pressed
    $('.alert-success .close').click(function(){;
        $('.alert-success .close').parent().hide();
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
                    removeItems();
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
    	jQuery("#room-group_"+current).parent().remove();
    })

	function removeRooms(){
        jQuery(".remove-room").off();
        jQuery(".remove-room").click(function(){
            var current =  jQuery(this).data('id');
            jQuery("#room-group_"+current).parent().remove();
        })
    }

    function removeItems(){
        jQuery(".remove-item").off();
        jQuery(".remove-item").click(function(){
            jQuery(this).parent().parent().remove();
        })
    }

	// Add a new input box for the items
	jQuery(".add-items").click(function(){
    	var currentItem =  jQuery(this).data('id');
    	var html = '<div class="form-group row additional-items items_'+currentItem+'">';
	    		html += '<label class="col-md-2 control-label">Additional Item</label>';
	    		html += '<div class="col-md-4">';
		    		html += '<input type="text" name="items['+currentItem+'][]" class="form-control">';
				html += '</div>';
                html += '<div class="col-md-2">';
                    html += '<a data-toggle="tooltip" id= "remove-item_'+currentItem+'" title="Remove Item" class="btn btn-effect-ripple btn-sm btn-danger remove-item" data-id="'+currentItem+'"><i class="fa fa-minus"></i> Remove Item</a>';
                html += '</div>';
    	html += '</div>';

    	jQuery('#room-group_'+currentItem).after(html);
        removeItems();
	})

	function addItems(){
		jQuery(".add-items").off();
		jQuery(".add-items").click(function(){
	    	var currentItem =  jQuery(this).data('id');
	    	var html = '<div class="form-group row additional-items items_'+currentItem+'">';
		    		html += '<label class="col-md-2 control-label">Additional Item</label>';
		    		html += '<div class="col-md-4">';
		    			html += '<input type="text" name="items['+currentItem+'][]" class="form-control">';
	    			html += '</div>';
                    html += '<div class="col-md-2">';
                        html += '<a data-toggle="tooltip" id= "remove-item_'+currentItem+'" title="Remove Item" class="btn btn-effect-ripple btn-sm btn-danger remove-item" data-id="'+currentItem+'"><i class="fa fa-minus"></i> Remove Item</a>';
                    html += '</div>';
	    	html += '</div>';

	    	jQuery('#room-group_'+currentItem).after(html);
            removeItems();
		})
	}

	// Add 
	jQuery(".add-tenants").click(function(){
    	var currentItem =  jQuery(this).data('id');
        jQuery(this).data('id', currentItem+1);
        if((currentItem % 2) == 0){
            var aClass = "";
        }else{
            var aClass = "right-border";
        }
        var html = '<div class="form-group col-sm-6 '+aClass+'" id = "add-tenants_'+(currentItem+1)+'">';
            html += '<input type="email" class="form-control" id="users[]" placeholder="Add Other Tenant Email" name = "users[]">';
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

		var html = '';
            html += '<div class = "row row-space">';
                html += '<div class = "col-sm-3 form-group">';
                    html += '<h2>New Item</h2>';
                html += '</div>';
                html += '<div class = "col-sm-9 form-group">';
                html += '</div>';
            html += '</div>';

            html += '<div class = "item-background">';
                html += '<div class = "row">';
                    html += '<div class="form-group col-sm-6 right-border">';
                        html += '<input type="text" name="rooms['+currentRoom+'][items][new_'+count+'][name]" class ="form-control form-group-3-label" placeholder = "Item Name">';
                     html += '</div>';
                html += '</div>';

                html += '<div class = "row">';
                    html += '<div class="form-group col-sm-6 right-border">';
                        html += '<label class = "form-control form-group-3-label">';
                            html += 'Item Status: Green';
                        html += '</label>';
                    html += '</div>';
                html += '</div>';

                html += '<div class="row">';
                    html += '<div class="form-group col-sm-6 right-border">';
                        html += '<input '+disabledTenant+' type = "file" name = "new_'+count+'" class = "form-control file-background">';
                    html += '</div>';
                html += '</div>';

                html += '<div class = "row">';
                    html += '<div class = "form-group col-sm-6 right-border">';
                        html += '<input '+disabledTenant+' type="text" class = "form-control form-group-3-label" id="items_tenant_comment_new_'+count+'" name="rooms['+currentRoom+'][items][new_'+count+'][tenant_comment]" placeholder = "Tenant Comment">';
                    html += '</div>';

                    html += '<div class = "form-group col-sm-6">';
                        html += '<input '+disabledLord+' type="text" class = "form-control form-group-3-label" id="items_lord_comment_new_'+count+'" name="rooms['+currentRoom+'][items][new_'+count+'][lord_comment]" placeholder = "Lord Comment">';
                    html += '</div>';
                html += '</div>';

                html += '<div class = "row">';
                    html += '<div class="form-group col-sm-6 right-border">';
                        html += '<label class = "form-control form-group-3-label" style = "width:70%; float: left;">';
                            html += 'Tenant Approval';
                        html += '</label>';
                        html += '<select '+disabledTenant+' class="form-control form-group-2-select tenant-item-approval" data-id = "new_'+count+'" name="rooms['+currentRoom+'][items][new_'+count+'][tenant_approved]">';
                            html += '<option value="0">No</option>';
                            html += '<option value="1" selected = "selected">Yes</option>';
                        html += '</select>';
                    html += '</div>';

                    html += '<div class="form-group col-sm-6">';
                        html += '<label class = "form-control form-group-3-label" style = "width:70%; float: left;">';
                            html += 'Landlord Approval';
                        html += '</label>';
                        html += '<select '+disabledLord+' class="form-control form-group-2-select lord-item-approval" data-id = "new_'+count+'" name="rooms['+currentRoom+'][items][new_'+count+'][lord_approved]">';
                            html += '<option value="0">No</option>';
                            html += '<option value="1" selected = "selected">Yes</option>';
                        html += '</select>';
                    html += '</div>';
                html += '</div>';
            html += '</div>';

    	count = count + 1;
		jQuery(this).data('items', count);

    	jQuery('#new-item_'+currentRoom).after(html);
        blockTenantApproval();
        blockLordApproval();
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

    //If user changes the item approval to NO then they must give a comment for Tenant
    jQuery('.tenant-item-approval').change(function(){
        if(jQuery(this).val() == 0){
            var currentItem = jQuery(this).data('id');
            if(!jQuery('#items_tenant_comment_'+currentItem).val()){
                alert('A comment is required for disapproval of an item');
                jQuery(this).val(1);
            }
        }
    })

    function blockTenantApproval(){
        jQuery('.tenant-item-approval').off();
        jQuery('.tenant-item-approval').change(function(){
            if(jQuery(this).val() == 0){
                var currentItem = jQuery(this).data('id');
                if(!jQuery('#items_tenant_comment_'+currentItem).val()){
                    alert('A comment is required for disapproval of an item');
                    jQuery(this).val(1);
                }
            }
        })
    }

    //If user changes the item approval to NO then they must give a comment for landLord
    jQuery('.lord-item-approval').change(function(){
        if(jQuery(this).val() == 0){
            var currentItem = jQuery(this).data('id');
            if(!jQuery('#items_lord_comment_'+currentItem).val()){
                alert('A comment is required for disapproval of an item');
                jQuery(this).val(1);
            }
        }
    })

    function blockLordApproval() {
        jQuery('.lord-item-approval').off();
        jQuery('.lord-item-approval').change(function () {
            if (jQuery(this).val() == 0) {
                var currentItem = jQuery(this).data('id');
                if (!jQuery('#items_lord_comment_' + currentItem).val()) {
                    alert('A comment is required for disapproval of an item');
                    jQuery(this).val(1);
                }
            }
        })
    }

    //if search is selected show form
    jQuery('.search').click(function(){
        jQuery('.searchformholder').show();
        jQuery('.searchback').show();
    })

    //if search-close is selected hide search modal
    jQuery('#search-close').click(function(){
        jQuery('.searchformholder').hide();
        jQuery('.searchback').hide();
    })

    //forcing alert close
    jQuery('.alert-failure .close').click(function(){
        jQuery(this).parent().parent().parent().parent().remove();
    })
});