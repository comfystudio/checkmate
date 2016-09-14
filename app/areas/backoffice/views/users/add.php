Page content -->
<div id="page-content">
	<!-- Header -->
    <div class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <div class="header-section">
                    <h1>Users</h1>
                </div>
            </div>
            <div class="col-sm-6 hidden-xs">
                <div class="header-section">
                    <ul class="breadcrumb breadcrumb-top">
                        <li><a href="/backoffice/users/">Users</a></li>
                        <li>Add</li>
                        
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
            <h2>Add User</h2>
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
	        <input type="hidden" name="stored_password" value="<?php if(!empty($this->stored_data['password'])){echo $this->stored_data['password'];} ?>" />
			<input type="hidden" name="stored_salt" value="<?php if(!empty($this->stored_data['salt'])){echo $this->stored_data['salt'];} ?>" />

	        <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('firstname', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="firstname">First Name <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <input type="text" id="firstname" name="firstname" class="form-control" value="<?php if ((!empty($this->error))) { echo Formatting::utf8_htmlentities($_POST['firstname']);} elseif(!empty($this->stored_data['firstname'])){echo $this->stored_data['firstname'];}?>">
                </div>
            </div>
            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('surname', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="surname">Surname <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <input type="text" id="surname" name="surname" class="form-control" value="<?php if ((!empty($this->error))) { echo Formatting::utf8_htmlentities($_POST['surname']);} elseif(!empty($this->stored_data['surname'])){echo $this->stored_data['surname'];}?>">
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('email', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="email">Email <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <input type="text" id="email" name="email" class="form-control" value="<?php if ((!empty($this->error))) { echo Formatting::utf8_htmlentities($_POST['email']);} elseif(!empty($this->stored_data['email'])){echo $this->stored_data['email'];}?>">
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('contact_num', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="email">Contact Number <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <input type="text" id="contact_num" name="contact_num" class="form-control" value="<?php if ((!empty($this->error))) { echo Formatting::utf8_htmlentities($_POST['contact_num']);} elseif(!empty($this->stored_data['contact_num'])){echo $this->stored_data['contact_num'];}?>">
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('password', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="password">Password <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <input type="password" id="password" name="password" class="form-control">
                    <span class = "help-block">Note: Password must contain at least one number, one uppercase letter and atleast 8 characters.</span>
                </div>
                <div class="col-md-1">
	                <span id="password_result"></span>
                </div>
                <div class="col-md-1">
                    <div class="checkbox">
                        <label for="generate_password">
                           <input type="checkbox" id="generate_password" name="generate_password" value="1" /> Generate
                        </label>
                    </div>
                </div>
                <div class="col-md-2">
	                <div id="generated_password"></div>
                </div>
            </div>
            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('password_again', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="password_again">Confirm Password <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <input type="password" id="password_again" name="password_again" class="form-control">
                </div>
            </div>

           <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('type', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="country_id">User Type <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <select id="type" name="type" class="form-control">
                        <option value="0">-- Please Select User Type --</option>
                        <?php foreach($this->userTypes as $key => $type){?>
                            <option value="<?php echo $key ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['type'] == $key)) {echo 'selected="selected"';} elseif(!empty($this->stored_data['type']) && $this->stored_data['type'] == $key){echo 'selected="selected"';}?> > <?php echo $type?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <?php if(isset($this->stored_data['id']) && $this->stored_data['id'] != null && !empty($this->stored_data['logo_image'])){?>
                <div class="form-group">
                    <label class="col-md-2 control-label" for="current file">Current Logo Image</label>
                    <div class="col-md-10 double-input">
                        <div class="col-md-5">
                            <td><img src="/image.php?width=120&height=120&image=/assets/uploads/<?php echo $this->stored_data['logo_image']?>" alt="<?php echo $this->stored_data['logo_image']?>"></td>
                        </div>

                        <div class="col-xs-6">
                            <div class="edit-download-wrap">
                                <a href="/backoffice/users/download/<?php echo $this->stored_data['id'];?>/" class="btn btn-primary">Download Current Logo Image <i class="fa fa-cloud-download"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('image', $this->error)) { echo 'has-error'; }?>" id = "letting-image">
                <label class="col-md-2 control-label" for="file">Letting Agent Image</label>
                <div class="col-md-5">
                    <input type="file" name="image" id="image">
                    <input type="hidden" id="imagebase64" name="imagebase64">

                    <!-- <span class = "help-block">Note: Width:450px x Height:600px recommended.</span> -->
                    <?php if(isset($this->stored_data['image']) && !empty($this->stored_data['image'])){?>
                        <span class = "help-block">Note: Uploading a new Image will remove the previous one.</span>
                    <?php } ?>
                </div>

                <div class="col-md-5">
                    <div id = "result-image"></div>
                </div>
            </div>

            <div class="form-group">
				<label class="col-md-2 control-label" for="is_active">Is Active</label>
                    <input type="hidden" name="is_active" value="0">
					<div class="col-md-5">
						<div class="checkbox">
<!--                            --><?php //if ((!empty($this->error))) { if(!empty($_POST['is_active']) && $_POST['is_active'] == 1) {echo 'checked="checked"';}}elseif(!empty($this->stored_data['is_active']) && $this->stored_data['is_active'] == 1){echo 'checked="checked"';}?>
							<label for="is_active" class="switch switch-primary"><input type="checkbox" name="is_active" id="is_active" value="1" <?php if((!empty($_POST['is_active']) && $_POST['is_active'] != 0)  || (!empty($this->stored_data['is_active']) && $this->stored_data['is_active'] != 0) || (!isset($this->stored_data['id']))) {echo 'checked="checked"';}?>><span></span></label>
						</div>
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