<!-- Page content -->
<div id="page-content">
	<!-- Header -->
    <div class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <div class="header-section">
                    <h1>Templates</h1>
                </div>
            </div>
            <div class="col-sm-6 hidden-xs">
                <div class="header-section">
                    <ul class="breadcrumb breadcrumb-top">
                        <li><a href="/backoffice/templates/index">Templates</a></li>
                        <li><?php if(isset($this->stored_data['id'])){echo "Edit"; }else{ echo "Add";}?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- END Header -->
    <!-- General Elements Block -->
    <div class="block">
        <!-- General Elements Title -->
        <div class="block-title">
            <h2><?php if(isset($this->stored_data['id'])){echo "Edit"; }else{ echo "Add";}?> Templates</h2>
        </div>
        <!-- END General Elements Title -->
        <?php if (!empty($this->error)) { ?>
		<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><strong>Error</strong></h4>
            <?php
			    echo Html::formatBackofficeSuccess($this->error);
			?>
        </div>
        <?php } ?>
        <!-- General Elements Content -->
        <form id="form" action="" method="post" class="form-horizontal form-bordered" enctype="multipart/form-data">

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('title', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="meta_title">Template Title <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <input type="text" id="title" name="title" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['title']);} elseif(!empty($this->stored_data['title'])){echo $this->stored_data['title'];}?>">
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('title', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="meta_title">Template Description <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <textarea rows = "7" id="description" name="description" class="form-control"> <?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['description']);} elseif(!empty($this->stored_data['description'])){echo $this->stored_data['description'];}?></textarea>
                </div>
            </div>

            <?php if(isset($this->stored_data['room_ids']) && !empty($this->stored_data['room_ids'])){?>
                <?php $room_ids = explode(',', $this->stored_data['room_ids']) ?>
                    <?php $count = 1;?>
                    <?php foreach($room_ids as $key => $room_id){?>
                        <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('type', $this->error)) { echo 'has-error'; }?>" id = "room-group_<?php echo $count?>">
                            <label class="col-md-2 control-label" for="rooms[]">Room <?php echo $count?></label>
                            <div class="col-md-5">
                                <select id="rooms[<?php echo $count?>]" name="rooms[<?php echo $count?>]" class="form-control">
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

                <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('type', $this->error)) { echo 'has-error'; }?>" id = "room-group_1">
                    <label class="col-md-2 control-label" for="rooms[]">Room 1</label>
                    <div class="col-md-5">
                        <select id="rooms1" name="rooms[]" class="form-control">
                            <option value="0">-- Please Select Room --</option>
                            <?php foreach($this->rooms as $key => $room){?>
                                <option value="<?php echo $room['id'] ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['rooms'] == $key)) {echo 'selected="selected"';} elseif(!empty($this->stored_data['rooms']) && $this->stored_data['rooms'] == $key){echo 'selected="selected"';}?> > <?php echo $room['name'].": (".$room['items'].")"?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <a data-toggle="tooltip" id= "remove-room_1" title="Remove Room" class="btn btn-effect-ripple btn-sm btn-danger remove-room" data-id="1"><i class="fa fa-times"></i></a>
                </div>

                <div class="form-group">
                    <a href="#template-add-room" class="btn btn-block btn-info" id="template-add-room" data-id="2"><i class="fa fa-plus"></i> Add Another Room</a>
                </div>

            <?php } ?>

            <div class="form-group form-actions">
                <div class="col-md-5 col-md-offset-2">
                    <input id = "save" type="submit" name="save" class="btn btn-effect-ripple btn-primary loader" value="Save">
                    <input type="submit" name="cancel" class="btn btn-effect-ripple btn-danger loader" value="Cancel">
                </div>
            </div>
        </form>
        <!-- END General Elements Content -->
    </div>
    <!-- END General Elements Block -->
</div>
<!-- END Page Content -->