<!-- Page content -->
<div id="page-content">
    <!-- Header -->
    <div class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <div class="header-section">
                    <h1>Properties</h1>
                </div>
            </div>
            <div class="col-sm-6 hidden-xs">
                <div class="header-section">
                    <ul class="breadcrumb breadcrumb-top">
                        <li><a href="/backoffice/properties/index">Properties</a></li>
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
            <h2><?php if(isset($this->stored_data['id'])){echo "Edit"; }else{ echo "Add";}?> Properties</h2>
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

            <?php if(isset($this->stored_data['id']) && $this->stored_data['id'] != null && !empty($this->stored_data['image'])){?>
                <div class="form-group">
                    <label class="col-md-2 control-label" for="current file">Current Image</label>
                    <div class="col-md-10 double-input">
                        <div class="col-md-5">
                            <td><img src="/image.php?width=120&height=120&image=/assets/uploads/<?php echo $this->stored_data['image']?>" alt="<?php echo $this->stored_data['image']?>"></td>
                        </div>

                        <div class="col-xs-6">
                            <div class="edit-download-wrap">
                                <a href="/backoffice/properties/download/<?php echo $this->stored_data['id'];?>/" class="btn btn-primary">Download Current Image <i class="fa fa-cloud-download"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('image', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="file">Image </label>
                <div class="col-md-5">
                    <input type="file" name="image" id="image">
                    <input type="hidden" id="imagebase64" name="imagebase64">

                    <!--<span class = "help-block">Note: Width:1200px x Height:800px recommended.</span>-->
                    <?php if(isset($this->stored_data['image']) && !empty($this->stored_data['image'])){?>
                        <span class = "help-block">Note: Uploading a new Image will remove the previous one.</span>
                    <?php } ?>
                </div>

                <div class="col-md-5">
                    <div id = "result-image"></div>
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('template_id', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="template_id">Property Layout Type </label>
                <div class="col-md-5">
                    <select id="type" name="template_id" class="form-control">
                        <option value="0">-- Please Select Property Layout --</option>
                        <?php foreach($this->templates as $key => $template){?>
                            <option value="<?php echo $template['id'] ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['template_id'] == $template['id'])) {echo 'selected="selected"';} elseif(!empty($this->stored_data['template_id']) && $this->stored_data['template_id'] == $key){echo 'selected="selected"';}?> > <?php echo $template['title']?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

<!--            <div class="form-group --><?php //if ((!empty($this->error)) && array_key_exists('title', $this->error)) { echo 'has-error'; }?><!--">-->
<!--                <label class="col-md-2 control-label" for="meta_title">Title <span class="text-danger">*</span></label>-->
<!--                <div class="col-md-5">-->
<!--                    <input type="text" id="title" name="title" class="form-control" value="--><?php //if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['title']);} elseif(!empty($this->stored_data['title'])){echo $this->stored_data['title'];}?><!--">-->
<!--                </div>-->
<!--            </div>-->

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('house_number', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="meta_title">House Number <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <input type="text" id="house_number" name="house_number" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['house_number']);} elseif(!empty($this->stored_data['house_number'])){echo $this->stored_data['house_number'];}?>">
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('address_1', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="meta_title">Address Line 1 <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <input type="text" id="address_1" name="address_1" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['address_1']);} elseif(!empty($this->stored_data['address_1'])){echo $this->stored_data['address_1'];}?>">
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('address_2', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="meta_title">Address Line 2 </label>
                <div class="col-md-5">
                    <input type="text" id="address_2" name="address_2" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['address_2']);} elseif(!empty($this->stored_data['address_2'])){echo $this->stored_data['address_2'];}?>">
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('address_3', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="meta_title">Address Line 3 </label>
                <div class="col-md-5">
                    <input type="text" id="address_3" name="address_3" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['address_3']);} elseif(!empty($this->stored_data['address_3'])){echo $this->stored_data['address_3'];}?>">
                </div>
            </div>

<!--            <div class="form-group --><?php //if ((!empty($this->error)) && array_key_exists('address_4', $this->error)) { echo 'has-error'; }?><!--">-->
<!--                <label class="col-md-2 control-label" for="meta_title">Address Line 4 </label>-->
<!--                <div class="col-md-5">-->
<!--                    <input type="text" id="address_4" name="address_4" class="form-control" value="--><?php //if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['address_4']);} elseif(!empty($this->stored_data['address_4'])){echo $this->stored_data['address_4'];}?><!--">-->
<!--                </div>-->
<!--            </div>-->

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('postcode', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="meta_title">Postcode <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <input type="text" id="postcode" name="postcode" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['postcode']);} elseif(!empty($this->stored_data['postcode'])){echo $this->stored_data['postcode'];}?>">
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