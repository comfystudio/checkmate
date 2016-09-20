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
		var currentRoom =  jQuery(this).data('id');
		var role = jQuery(this).data('role');
		if(role == 'lead_tenant'){
			var disabled = '';
		}else{
			var disabled = 'disabled';
		}

		var html = '<div class="row">';
	    		html += '<div class="col-md-offset-3 col-md-3">';
		    		html += 'Item Name:';
				html += '</div>';
				html += '<div class="col-md-3">';
		    		html += '<input type="text" name="rooms['+currentRoom+'][\'items\'][][\'name\']">';
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
		    		//if(role = '')
	    			html += '<input '+disabled+' type = "file" name = "rooms['+currentRoom+'][\'items\'][][\'image\']" >';
		    		//<input <?php if($this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="file" name="image" id="image">
				html += '</div>';
    		html += '</div>';

    	jQuery('#new-item_'+currentRoom).after(html);
	})


	// <div class = "row">
 //        <div class = "col-md-offset-3 col-md-3">
 //            Item Name:
 //        </div>
 //        <div class = "col-md-3">
 //            <?php echo $item['name']?>
 //        </div>
 //    </div>

 //    <div class = "row">
 //        <div class = "col-md-offset-3 col-md-3">
 //            Item Status:
 //        </div>
 //        <div class = "col-md-3">
 //            <?php echo $this->status[$this->report[0]['status']]?>        
 //        </div>
 //    </div>
    

 //    <?php if(isset($item['image']) && !empty($item['image'])){?>
 //        <div class= "row">
 //            <div class = "col-md-3">
 //                Item Image
 //            </div>
 //            <div class = "col-md-3">
 //                <img src="/image.php?width=120&height=120&image=/assets/uploads/<?php echo $item['image']?>" alt="<?php echo $item['image']?>">
 //                <a href="/reports/download/<?php echo $item['id'];?>/item" class="btn btn-primary">Download Meter Image<i class="fa fa-cloud-download"></i></a>
 //            </div>
 //        </div>
 //    <?php } else {?>
 //        <div class = "row">
 //            <div class = "col-md-3">
 //                Upload Item Image
 //            </div>
 //            <div class = "col-md-3">
 //                <input <?php if($this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="file" name="image" id="image">
 //            </div>
 //        </div>
 //    <?php } ?>
 //    <div class = "row">
 //        <div class = "col-md-3">
 //            Lead Tenant Comment
 //        </div>

 //        <div class = "col-md-3">
 //            <input <?php if($this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="text" id="items_tenant_comment_<?php echo $key2?>" name="rooms[<?php echo $room['id']?>]['items'][<?php echo $item['id']?>]['tenant_comment']" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['rooms']['<?php echo $room["id"]?>']['items']['<?php echo $item["id"]?>']['tenant_comment']);} elseif(!empty($item['tenant_comment'])){echo $item['tenant_comment'];}?>">
 //        </div>

 //        <div class = "col-md-3">
 //            Landlord / Letting Agent Comment
 //        </div>

 //        <div class = "col-md-3">
 //            <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="text" id="items_lord_comment_<?php echo $key2?>" name="rooms[<?php echo $room['id']?>]['items'][<?php echo $item['id']?>]['lord_comment']" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['rooms']['<?php echo $room["id"]?>']['items']['<?php echo $item["id"]?>']['lord_comment']);} elseif(!empty($item['lord_comment'])){echo $item['lord_comment'];}?>">
 //        </div>
 //    </div>

 //    <div class = "row">
 //        <div class = "col-md-3">
 //            Lead Tenant Approval
 //        </div>

 //        <div class = "col-md-3">
 //            <select <?php if($this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> id="tenant_approved_check_in_<?php echo $key2?>" name="rooms[<?php echo $room['id']?>]['items'][<?php echo $item['id']?>]['tenant_approved']" class="form-control">
 //                <?php foreach($this->YesNo as $key3 => $type){?>
 //                    <option value="<?php echo $key3 ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['rooms'][$room['id']]['items'][$item['id']]['tenant_approved'] == $key3)) {echo 'selected="selected"';} elseif(!empty($item['tenant_approved']) && $item['tenant_approved'] == $key3){echo 'selected="selected"';}?> > <?php echo $type?></option>
 //                <?php } ?>
 //            </select>
 //        </div>

 //        <div class = "col-md-3">
 //            Landlord / Letting Agent Approval
 //        </div>

 //        <div class = "col-md-3">
 //            <select <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> id="lord_approved_check_in_<?php echo $key2?>" name="rooms[<?php echo $room['id']?>]['items'][<?php echo $item['id']?>]['lord_approved']" class="form-control">
 //                <?php foreach($this->YesNo as $key3 => $type){?>
 //                    <option value="<?php echo $key3 ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['rooms'][$room['id']]['items'][$item['id']]['lord_approved'] == $key3)) {echo 'selected="selected"';} elseif(!empty($item['lord_approved']) && $item['lord_approved'] == $key3){echo 'selected="selected"';}?> > <?php echo $type?></option>
 //                <?php } ?>
 //            </select>
 //        </div>
 //    </div>






});