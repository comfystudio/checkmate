<!-- Page content -->
<div id="page-content">
	<!-- Header -->
    <div class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <div class="header-section">
                    <h1>Admin Users</h1>
                </div>
            </div>
            <div class="col-sm-6 hidden-xs">
                <div class="header-section">
                    <ul class="breadcrumb breadcrumb-top">
                        <li><a href="/backoffice/admin-users/index">Admin User</a></li>
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
            <h2><?php if(isset($this->stored_data['id'])){echo "Edit"; }else{ echo "Add";}?> Admin User</h2>
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
        <form action="" method="post" class="form-horizontal form-bordered">
	        <input type="hidden" name="stored_password" value="<?php if(!empty($this->stored_data['password'])){echo $this->stored_data['password'];} ?>" />
			<input type="hidden" name="stored_salt" value="<?php if(!empty($this->stored_data['salt'])){echo $this->stored_data['salt'];} ?>" />

	        <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('user_name', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="user_name">Username <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <input type="text" id="user_name" name="user_name" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['user_name']);} elseif(!empty($this->stored_data['user_name'])){echo $this->stored_data['user_name'];}?>">
                </div>
            </div>
            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('display_name', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="display_name">Display Name <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <input type="text" id="display_name" name="display_name" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['display_name']);} elseif(!empty($this->stored_data['display_name'])){echo $this->stored_data['display_name'];}?>">
                </div>
            </div>
            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('user_email', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="user_email">Email <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <input type="text" id="user_email" name="user_email" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['user_email']);} elseif(!empty($this->stored_data['user_email'])){echo $this->stored_data['user_email'];}?>">
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
            <div class="form-group">
                <input type = "hidden" name = "is_super" value = "0">
				<label class="col-md-2 control-label" for="is_super">Is Super Admin?</label>
					<div class="col-md-5">
						<div class="checkbox">
                            <label for="is_super" class="switch switch-primary"><input type="checkbox" name="is_super" id="is_super" value="1" <?php if((!empty($_POST['is_super']) && $_POST['is_super'] != 0)  || (!empty($this->stored_data['is_super']) && $this->stored_data['is_super'] != 0) || (!isset($this->stored_data['id']))) {echo 'checked="checked"';}?>><span></span></label>
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