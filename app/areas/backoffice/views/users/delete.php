<!-- Page content -->
<div id="page-content">
	<!-- Header -->
    <div class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <div class="header-section">
                    <h1>Members</h1>
                </div>
            </div>
            <div class="col-sm-6 hidden-xs">
                <div class="header-section">
                    <ul class="breadcrumb breadcrumb-top">
                        <li><a href="/backoffice/users/index">Members</a></li>
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
            <h2>Delete '<?php echo $this->selectedData[0]['email']; ?>'</h2>
        </div>
        <!-- END General Elements Title -->
		<!-- General Elements Content -->
		<div class="alert alert-danger alert-dismissable">
	        <h4><strong>Warning</strong></h4>
            <?php if(isset($this->conflict) && !empty($this->conflict)){?>
                <p>Can't remove this user as they are part of an ongoing check-in check-out process</p>
            <?php } else {?>
	           <p>Please note that this will remove this member. You can add them again at a later time.</p>
           <?php } ?>
	    </div>
		<form action="" method="post" class="form-horizontal form-bordered">
        	<input type="hidden" name="id" value="<?php echo $this->selectedData[0]['id']; ?>" />
			<div class="form-group">
                <div class="col-md-9">
                    <p class="form-control-static">You are removing: <strong><?php echo $this->selectedData[0]['email']; ?></strong></p>
                </div>
            </div>
			<div class="form-group form-actions">
	            <div class="col-md-5">
                    <?php if(!isset($this->conflict) || empty($this->conflict)){?>
	                   <input type="submit" name="delete" class="btn btn-effect-ripple btn-primary loader" value="Delete">
                    <?php } ?>
	                <input type="submit" name="cancel" class="btn btn-effect-ripple btn-danger loader" value="Cancel">
	            </div>
	        </div>
		</form>
        <!-- END General Elements Content -->
    </div>
    <!-- END General Elements Block -->
</div>
<!-- END Page Content -->