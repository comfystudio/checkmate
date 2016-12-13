<!-- Page content -->
<div id="page-content">
    <!-- Header -->
    <div class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <div class="header-section">
                    <h1>Bonus Credits</h1>
                </div>
            </div>
            <div class="col-sm-6 hidden-xs">
                <div class="header-section">
                    <ul class="breadcrumb breadcrumb-top">
                        <li><a href="/backoffice/users/index">Bonus</a></li>
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
            <h2><?php if(isset($this->stored_data['id'])){echo "Edit"; }else{ echo "Add";}?> Bonus</h2>
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
            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('bonus_credits', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="bonus_credits">Bonus Credit <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <input type="text" id="bonus_credits" name="bonus_credits" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['bonus_credits']);} elseif(!empty($this->stored_data['bonus_credits'])){echo $this->stored_data['bonus_credits'];}?>">
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