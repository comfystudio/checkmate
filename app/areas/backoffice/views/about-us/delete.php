<!-- Page content -->
<div id="page-content">
	<!-- Header -->
    <div class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <div class="header-section">
                    <h1>About Us</h1>
                </div>
            </div>
            <div class="col-sm-6 hidden-xs">
                <div class="header-section">
                    <ul class="breadcrumb breadcrumb-top">
                        <li><a href="/backoffice/about-us/index">About Us</a></li>
                        <li>Delete</li>
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
            <h2>Delete '<?php echo substr(FormInput::checkInput($this->selectedData[0]['title']), 0, 50)?>...'</h2>
        </div>
        <!-- END General Elements Title -->
		<!-- General Elements Content -->
		<div class="alert alert-danger alert-dismissable">
	        <h4><strong>Warning</strong></h4>
	        <p>Please note that this will remove this About Us. You can add them again at a later time.</p>
	    </div>
		<form action="" method="post" class="form-horizontal form-bordered">
        	<input type="hidden" name="id" value="<?php echo $this->selectedData[0]['id']; ?>" />
			<div class="form-group">
                <div class="col-md-9">
                    <p class="form-control-static">You are removing: <strong><?php echo substr(FormInput::checkInput($this->selectedData[0]['title']), 0, 50)?>...</strong></p>
                </div>
            </div>
			<div class="form-group form-actions">
	            <div class="col-md-5">
	                <input type="submit" name="delete" class="btn btn-effect-ripple btn-primary loader" value="Delete">
	                <input type="submit" name="cancel" class="btn btn-effect-ripple btn-danger loader" value="Cancel">
	            </div>
	        </div>
		</form>
        <!-- END General Elements Content -->
    </div>
    <!-- END General Elements Block -->
</div>
<!-- END Page Content -->