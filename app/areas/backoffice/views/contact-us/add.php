<!-- Page content -->
<div id="page-content">
	<!-- Header -->
    <div class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <div class="header-section">
                    <h1>Contacts</h1>
                </div>
            </div>
            <div class="col-sm-6 hidden-xs">
                <div class="header-section">
                    <ul class="breadcrumb breadcrumb-top">
                        <li><a href="/backoffice/contact-us/index">Contacts</a></li>
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
            <h2><?php if(isset($this->stored_data['id'])){echo "Edit"; }else{ echo "Add";}?> Contacts</h2>
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
        <form action="" method="post" class="form-horizontal form-bordered" enctype="multipart/form-data">
            <div class="form-group">
                <label class="col-md-2 control-label" for="view">View Page</label>
                <div class="col-md-5">
                    <a href = "/contact-us/" title="View Page" class = "btn btn-effect-ripple btn-sm btn-primary" target="_blank"> View Page</a>
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('facebook', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="facebook">Facebook Link <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <input type="text" id="facebook" name="facebook" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['facebook']);} elseif(!empty($this->stored_data['facebook'])){echo $this->stored_data['facebook'];}?>">
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('instagram', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="instagram">Instagram Link <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <input type="text" id="instagram" name="instagram" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['instagram']);} elseif(!empty($this->stored_data['instagram'])){echo $this->stored_data['instagram'];}?>">
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('twitter', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="twitter">Twitter Link <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <input type="text" id="twitter" name="twitter" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['twitter']);} elseif(!empty($this->stored_data['twitter'])){echo $this->stored_data['twitter'];}?>">
                </div>
            </div>

            <?php if(isset($this->stored_data['id']) && $this->stored_data['id'] != null && !empty($this->stored_data['image'])){?>
                <div class="form-group">
                    <label class="col-md-2 control-label" for="current file">Current Image</label>
                    <div class="col-md-10 double-input">
                        <div class="col-md-5">
                            <td><img src="/image.php?width=120&height=120&image=/assets/uploads/<?php echo $this->stored_data['image']?>" alt="<?php echo $this->stored_data['image']?>"></td>
                        </div>

                        <div class="col-xs-6">
                            <div class="edit-download-wrap">
                                <a href="/backoffice/about-us/download/<?php echo $this->stored_data['id'];?>/" class="btn btn-primary">Download Current Image <i class="fa fa-cloud-download"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('image', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="file">Image</label>
                <div class="col-md-5">
                    <input type="file" name="image" id="image">

                    <!-- <span class = "help-block">Note: Width:450px x Height:600px recommended.</span> -->
                    <?php if(isset($this->stored_data['image']) && !empty($this->stored_data['image'])){?>
                        <span class = "help-block">Note: Uploading a new Image will remove the previous one.</span>
                    <?php } ?>
                </div>

                <div class="col-md-5">
                    <div id = "result-image"></div>
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('location', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="location">Location <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <textarea id="location" name="location" rows="7" class="ckeditor" ><?php if ((!empty($this->missing)) || (!empty($this->error))) { echo  html_entity_decode($_POST['location']);}elseif(!empty($this->stored_data['location'])){echo $this->stored_data['location'];}?></textarea>
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('text', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="text">Text</label>
                <div class="col-md-5">
                    <textarea id="text" name="text" rows="7" class="ckeditor" ><?php if ((!empty($this->missing)) || (!empty($this->error))) { echo  html_entity_decode($_POST['text']);}elseif(!empty($this->stored_data['text'])){echo $this->stored_data['text'];}?></textarea>
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('phone', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="phone">Phone <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <input type="text" id="phone" name="phone" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['phone']);} elseif(!empty($this->stored_data['phone'])){echo $this->stored_data['phone'];}?>">
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('phone_2', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="phone_2">Phone 2 <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <input type="text" id="phone_2" name="phone_2" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['phone_2']);} elseif(!empty($this->stored_data['phone_2'])){echo $this->stored_data['phone_2'];}?>">
                </div>
            </div>


            <div class="form-group form-actions">
                <div class="col-md-5 col-md-offset-2">
                    <input type="submit" name="save" class="btn btn-effect-ripple btn-primary loader" value="Save">
                    <input type="submit" name="cancel" class="btn btn-effect-ripple btn-danger loader" value="Cancel">
                </div>
            </div>
        </form>
        <!-- END General Elements Content -->
    </div>
    <!-- END General Elements Block -->
</div>
<!-- END Page Content -->