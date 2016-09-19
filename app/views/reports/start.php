<!-- Page content -->
<div id="page-content">
    <!-- Header -->
    <div class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <div class="header-section">
                    <h1>Check In</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- END Header -->
    <!-- General Elements Block -->
    <div class="block">
        <!-- General Elements Title -->
        <div class="block-title">
            <h2>
                <?php echo $this->property[0]['title'].'<br/>';?>
                <?php echo $this->property[0]['house_number'].'<br/>';?>
                <?php echo $this->property[0]['address_1'].'<br/>';?>
                <?php echo $this->property[0]['address_2'].'<br/>';?>
                <?php echo $this->property[0]['address_3'].'<br/>';?>
                <?php echo $this->property[0]['address_4'].'<br/>';?>
                <?php echo $this->property[0]['postcode'];?>

            </h2>
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


            <?php if (!isset($this->leadTenantId) || empty($this->leadTenantId)){?>
                 <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('lead_tenant_id', $this->error)) { echo 'has-error'; }?>">
                    <label class="col-md-2 control-label" for="property_title">Leading Tenants Email</label>
                    <div class="col-md-5">
                        <input type="email" id="lead_tenant_id" name="lead_tenant_id" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['lead_tenant_id']);} elseif(!empty($this->stored_data['lead_tenant_id'])){echo $this->stored_data['lead_tenant_id'];}?>">
                    </div>
                </div>
            <?php } ?>

            <?php if (!isset($this->lordId) || empty($this->leadTenantId)){?>
                 <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('lord_id', $this->error)) { echo 'has-error'; }?>">
                    <label class="col-md-2 control-label" for="property_title">LandLords Email</label>
                    <div class="col-md-5">
                        <input type="email" id="lord_id" name="lord_id" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['lord_id']);} elseif(!empty($this->stored_data['lord_id'])){echo $this->stored_data['lord_id'];}?>">
                    </div>
                </div>
            <?php } ?>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('users[]', $this->error)) { echo 'has-error'; }?>" id = "add-tenants_1">
                <label class="col-md-2 control-label" for="property_title">Add Other Tenants</label>
                <div class="col-md-5">
                    <input type="email" id="users_1" name="users[]" class="form-control" placeholder = "email" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['users[1]']);} elseif(!empty($this->stored_data['users[1]'])){echo $this->stored_data['users[1]'];}?>">
                </div>
                <a data-toggle="tooltip" id= "add-tenants_1" title="Add More Tenants" class="btn btn-effect-ripple btn-sm btn-success add-tenants" data-id="1"><i class="fa fa-plus"></i></a>
            </div>


            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('check_in', $this->error)){echo 'has-error';}?>">
                <label class="col-md-2 control-label" for="date">Check In Date</label>
                <div class="col-md-5">
                    <input type="text" id="check_in" name="check_in" class="form-control input-datepicker" data-date-format="yyyy-mm-dd" data-date-view-mode="days" data-date-min-view-mode="days" placeholder="yyyy-mm-dd" value="<?php if ((!empty($this->missing)) || (!empty($this->error))) { echo Formatting::utf8_htmlentities(date('Y-m-d', strtotime($_POST['check_in'])));}elseif(!empty($this->stored_data['check_in'])){echo date('Y-m-d', strtotime($this->stored_data['check_in']));}?>">
                </div>
            </div>


            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('check_out', $this->error)){echo 'has-error';}?>">
                <label class="col-md-2 control-label" for="date">Check Out Date</label>
                <div class="col-md-5">
                    <input type="text" id="check_out" name="check_out" class="form-control input-datepicker" data-date-format="yyyy-mm-dd" data-date-view-mode="days" data-date-min-view-mode="days" placeholder="yyyy-mm-dd" value="<?php if ((!empty($this->missing)) || (!empty($this->error))) { echo Formatting::utf8_htmlentities(date('Y-m-d', strtotime($_POST['check_out'])));}elseif(!empty($this->stored_data['check_out'])){echo date('Y-m-d', strtotime($this->stored_data['check_out']));}?>">
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