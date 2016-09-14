<!-- Page content -->
<div id="page-content">
	<!-- Header -->
    <div class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <div class="header-section">
                    <h1>Rooms</h1>
                </div>
            </div>
            <div class="col-sm-6 hidden-xs">
                <div class="header-section">
                    <ul class="breadcrumb breadcrumb-top">
                        <li><a href="/backoffice/rooms/index">Rooms</a></li>
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
            <h2><?php if(isset($this->stored_data['id'])){echo "Edit"; }else{ echo "Add";}?> Rooms</h2>
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
                <label class="col-md-2 control-label" for="meta_title">Room Name <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <input type="text" id="name" name="name" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['name']);} elseif(!empty($this->stored_data['name'])){echo $this->stored_data['name'];}?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-2 control-label" for="item_ids">Select Items</label>
                <div class="col-md-5">
                    <select class="select-chosen" name="items[]" data-placeholder="Please Select Items..." multiple>
                        <?php foreach($this->items as $key => $item){?>
                            <option value="<?php echo $item['id']?>"
                                <?php if ((!empty($this->missing) || !empty($this->error)) && isset($_POST['items']) && (in_array($item['id'], $_POST['items']))) {
                                    echo 'selected="selected"';
                                } elseif((empty($this->missing) && empty($this->error)) && !empty($this->stored_data['items']) && in_array($item['id'], $this->stored_data['items'])){
                                    echo 'selected="selected"';
                                }?> > <?php echo $item['name']?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-2 control-label" for="is_active">Is Active</label>
                <input type="hidden" name="is_active" value="0">
                <div class="col-md-5">
                    <div class="checkbox">
                        <label for="is_active" class="switch switch-primary"><input type="checkbox" name="is_active" id="is_active" value="1" <?php if((!empty($_POST['is_active']) && $_POST['is_active'] != 0)  || (!empty($this->stored_data['is_active']) && $this->stored_data['is_active'] != 0) || (!isset($this->stored_data['id']))) {echo 'checked="checked"';}?>><span></span></label>
                    </div>
                </div>
            </div>

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