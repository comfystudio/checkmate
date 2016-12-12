<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.min.css">

<div class="greyback">
    <div class ="container">
        <div class="formintro">
            <div class="single_notification">
                <?php if (!empty($this->error)) { ?>
                    <div class="alert alert-info alert-labeled formerror">
                        <button type="button" class="close" data-dismiss="alert">
                            <span aria-hidden="true">×</span><span class="sr-only">Close</span>
                        </button>
                        <div class="alert-labeled-row">
                            <span class="alert-label alert-label-left alert-labelled-cell">
                                <i class="glyphicon glyphicon-info-sign"></i>
                            </span>
                            <h4>
                                <strong>Failure</strong>
                            </h4>
                            <p class="alert-body alert-body-right alert-labelled-cell">
                                <?php
                                foreach($this->error as $error){
                                    echo $error.'<br/>';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class = "row front-content">
                <div class = "col-md-offset-4 col-md-4 ">
                    <img src="/assets/images/logo-small.png" alt ="Check mate small logo" class = "logo-small">
                </div>
                <div class = "col-md-offset-4">
                </div>
            </div>

            <div class = "row">
                <div class = "col-md-offset-4 col-md-4 strapline-header">
                    Create Property Layout
                </div>
            </div>
            <div class = "row">
                <div class ="col-xs-12 welcome-message">
                    <?php if(isset($this->stored_data['id'])){echo "Edit"; }else{ echo "Add";}?> Templates
                </div>
            </div>
        </div>

        <form class="full" action="" method="post" enctype="multipart/form-data">
            <div class = "form-wrapper create-property" style="overflow: visible;">
                <div class = "row">
                    <div class="form-group col-sm-6 <?php if ((!empty($this->error)) && array_key_exists('title', $this->error)) { echo 'has-error'; }?>">
                        <input type="text" id="title" name="title" class="form-control" placeholder="Template Title" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['title']);} elseif(!empty($this->stored_data['title'])){echo $this->stored_data['title'];}?>">
                    </div>
                </div>

                <div class = "row">
                    <div class="form-group col-sm-12 <?php if ((!empty($this->error)) && array_key_exists('description', $this->error)) { echo 'has-error'; }?>">
                        <textarea rows = "3" id="description" name="description" placeholder="Template Description" class="form-control"><?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['description']);} elseif(!empty($this->stored_data['description'])){echo $this->stored_data['description'];}?></textarea>
                    </div>
                </div>

                <?php if(isset($this->stored_data['room_ids']) && !empty($this->stored_data['room_ids'])){?>
                    <?php $room_ids = explode(',', $this->stored_data['room_ids']) ?>
                    <?php $count = 1;?>
                    <?php foreach($room_ids as $key => $room_id){?>
                        <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('type', $this->error)) { echo 'has-error'; }?>" id = "room-group_<?php echo $count?>">
                            <label class="col-md-2 control-label" for="rooms[]">Room <?php echo $count?></label>
                            <div class="col-md-5">
                                <select id="rooms[<?php echo $count?>]" name="rooms[<?php echo $count?>]" class="form-control template-add-rooms selectpicker" data-live-search="true">
                                    <option value="0">-- Please Select Room --</option>
                                    <?php foreach($this->rooms as $key => $room){?>
                                        <option value="<?php echo $room['id'] ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['rooms'] == $key)) {echo 'selected="selected"';} elseif(!empty($room_id) && $room_id == $key){echo 'selected="selected"';}?> > <?php echo $room['name'].": (".$room['items'].")"?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <a data-toggle="tooltip" id= "remove-room_<?php echo $count?>" title="Remove Room" class="btn btn-effect-ripple btn-sm btn-danger remove-room" data-id="<?php echo $count?>"><i class="fa fa-times"></i></a>
                        </div>

                        <?php $count++;?>
                    <?php } ?>
                    <div class="form-group">
                        <a href="#template-add-room" class="btn btn-block btn-info" id="template-add-room" data-id="<?php echo $count?>"><i class="fa fa-plus"></i> Add Another Room</a>
                    </div>
                <?php }else{ ?>
                    <div class = "row">
                        <div class="col-md-12 form-group <?php if ((!empty($this->error)) && array_key_exists('type', $this->error)) { echo 'has-error'; }?>" id = "room-group_1">
                            <label class="col-md-2 control-label form-control-2" for="rooms[1]">Room 1</label>
                            <div class="col-md-6">
                                <select id="rooms1" name="rooms[1]" class="form-control template-add-rooms selectpicker" data-live-search="true">
                                    <option value="0">-- Please Select Room --</option>
                                    <?php foreach($this->rooms as $key => $room){?>
                                        <option value="<?php echo $room['id'] ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['rooms'] == $key)) {echo 'selected="selected"';} elseif(!empty($this->stored_data['rooms']) && $this->stored_data['rooms'] == $key){echo 'selected="selected"';}?> > <?php echo $room['name'].": (".$room['items'].")"?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class = "col-md-2">
                                <a data-toggle="tooltip" id= "remove-room_1" title="Remove Room" class="btn btn-effect-ripple btn-sm btn-danger remove-room" data-id="1"><i class="fa fa-minus"></i> Remove Room</a>
                            </div>
                        </div>
                        <div class = "col-md-1 col-md-offset-3 form-group clear">
                            <a data-toggle="tooltip" id= "add-items_1" title="Add More Items" class="btn btn-effect-ripple btn-sm btn-success add-items" data-id="1">Add another item <i class="fa fa-plus"></i></a>
                        </div>
                        <div class = "border-bottom"></div>
                    </div>


                    <div class = "row">
                        <div class="col-md-12">
                            <a class="formbtn btn-default" id="template-add-room" data-id="2"></i> Add Another Room</a>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="col-sm-12 form-spacing" style="text-align:center">
                <div class = "back-to-dash"><a href = "/users/dashboard/"><img src = "/assets/images/back-to-dash.png"/> <span>Back to dashboard</span></a></div>
                <button type="submit" class="formbtn btn-default template-add-save" name="save" value = "save">Save</button>
            </div>
        </form>
    </div>
</div>