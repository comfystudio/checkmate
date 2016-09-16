<!-- Page content -->
<div id="page-content">
	<!-- Header -->
    <div class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <div class="header-section">
                    <h1>News</h1>
                </div>
            </div>
            <div class="col-sm-6 hidden-xs">
                <div class="header-section">
                    <ul class="breadcrumb breadcrumb-top">
                        <li><a href="/backoffice/news/index">News</a></li>
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
            <h2><?php if(isset($this->stored_data['id'])){echo "Edit"; }else{ echo "Add";}?> News</h2>
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
                <label class="col-md-2 control-label" for="meta_title">News Title <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <input type="text" id="title" name="title" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['title']);} elseif(!empty($this->stored_data['title'])){echo $this->stored_data['title'];}?>">
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('text', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="headline">Text <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <textarea id="text" name="text" rows="7" class="ckeditor" ><?php if ((!empty($this->missing)) || (!empty($this->error))) { echo  html_entity_decode($_POST['text']);}elseif(!empty($this->stored_data['text'])){echo $this->stored_data['text'];}?></textarea>
                </div>
            </div>

            <?php if(isset($this->stored_data['id']) && $this->stored_data['id'] != null && !empty($this->stored_data['image'])){?>
                <div class="form-group">
                    <label class="col-md-2 control-label" for="current file">Current News Image</label>
                    <div class="col-md-10 double-input">
                        <div class="col-md-5">
                            <td><img src="/image.php?width=120&height=120&image=/assets/uploads/<?php echo $this->stored_data['image']?>" alt="<?php echo $this->stored_data['image']?>"></td>
                        </div>

                        <div class="col-xs-6">
                            <div class="edit-download-wrap">
                                <a href="/backoffice/news/download/<?php echo $this->stored_data['id'];?>/" class="btn btn-primary">Download Current News Image <i class="fa fa-cloud-download"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('image', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="file">News Image </label>
                <div class="col-md-5">
                    <input type="file" name="image" id="image">
                    <input type="hidden" id="imagebase64" name="imagebase64">
                    <span class = "help-block">Note: Width:600px Height:700px</span>
                    <?php if(isset($this->stored_data['image']) && !empty($this->stored_data['image'])){?>
                        <span class = "help-block">Note: Uploading a new Image will remove the previous one.</span>
                    <?php } ?>
                </div>

                <div class="col-md-5">
                    <div id = "news-image"></div>
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('date', $this->error)){echo 'has-error';}?>">
                <label class="col-md-2 control-label" for="date">News Date <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <input type="text" id="date" name="date" class="form-control input-datepicker" data-date-format="yyyy-mm-dd"  placeholder="yyyy-mm-dd" value="<?php if ((!empty($this->missing)) || (!empty($this->error))) { echo Formatting::utf8_htmlentities(date('Y-m-d', strtotime($_POST['date'])));}elseif(!empty($this->stored_data['date'])){echo date('Y-m-d', strtotime($this->stored_data['date']));}?>">
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