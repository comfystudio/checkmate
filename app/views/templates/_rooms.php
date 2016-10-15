<div class = "row">
    <div class="col-md-12 form-group <?php if ((!empty($this->error)) && array_key_exists('type', $this->error)) { echo 'has-error'; }?>" id = "room-group_<?php echo $this->current?>">
        <label class="col-md-2 control-label form-control-2" for="rooms[<?php echo $this->current ?>]">Room <?php echo $this->current ?></label>
        <div class="col-md-6">
            <select id="rooms[<?php echo $this->current?>]" name="rooms[<?php echo $this->current?>]" class="form-control">
                <option value="0">-- Please Select Room --</option>
                <?php foreach($this->rooms as $key => $room){?>
                    <option value="<?php echo $room['id'] ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['rooms'] == $key)) {echo 'selected="selected"';} elseif(!empty($this->stored_data['rooms']) && $this->stored_data['rooms'] == $key){echo 'selected="selected"';}?> > <?php echo $room['name'].": (".$room['items'].")"?></option>
                <?php } ?>
            </select>
        </div>
        <div class = "col-md-2">
            <a data-toggle="tooltip" id= "remove-room_<?php echo $this->current?>" title="Remove Room" class="btn btn-effect-ripple btn-sm btn-danger remove-room" data-id="<?php echo $this->current?>"><i class="fa fa-times"></i> Remove Room</a>
        </div>
    </div>
    <div class = "col-md-1 col-md-offset-3 form-group clear">
        <a data-toggle="tooltip" id= "add-items_<?php echo $this->current?>" title="Add More Items" class="btn btn-effect-ripple btn-sm btn-success add-items" data-id="<?php echo $this->current?>">Add another item <i class="fa fa-plus"></i></a>
    </div>
    <div class = "border-bottom"></div>
</div>
