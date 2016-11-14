<div class="greyback">
    <div class ="container">
        <div class="formintro">
            <div class="single_notification">
                <?php if (!empty($this->error)) { ?>
                    <div class="alert alert-info alert-labeled formerror">
                        <button type="button" class="close" data-dismiss="alert">
                            <span aria-hidden="true">×</span><span class="sr-only">Close</span>
                        </button>
                        <div class="alert-labeled-row">
                            <span class="alert-label alert-label-left alert-labelled-cell">
                                <i class="glyphicon glyphicon-info-sign"></i>
                            </span>
                            <h4>
                                <strong>Failure</strong>
                            </h4>
                            <p class="alert-body alert-body-right alert-labelled-cell">
                                <?php
                                foreach($this->error as $error){
                                    echo $error.'<br/>';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class = "row front-content">
                <div class = "col-md-offset-4 col-md-4 ">
                    <img src="/assets/images/logo-small.png" alt ="Check mate small logo" class = "logo-small">
                </div>
                <div class = "col-md-offset-4">
                </div>
            </div>

            <div class = "row">
                <div class = "col-md-offset-4 col-md-4 strapline-header">
                    Start Check In
                </div>
            </div>
            <div class = "row">
                <div class ="col-xs-12 welcome-message">
                    Enter the emails of tenants / Landlord / Agent, and check in / check out date below to begin process.
                </div>
            </div>
        </div>

        <form class="full" action="" method="post" enctype="multipart/form-data">
            <div class = "form-wrapper create-property">
                <div class = "row">
                    <?php if (!isset($this->leadTenantId) || empty($this->leadTenantId)){?>
                        <div class="form-group col-sm-6 right-border <?php if ((!empty($this->error)) && array_key_exists('lead_tenant_id', $this->error)) { echo 'has-error'; }?>">
                            <input type="name" class="form-control" id="lead_tenant_id" placeholder="Leading Tenant Email" name = "lead_tenant_id" value="<?php if ((!empty($this->error))) { echo Formatting::utf8_htmlentities($_POST['lead_tenant_id']);} elseif(!empty($this->stored_data['lead_tenant_id'])){echo $this->stored_data['lead_tenant_id'];}?>">
                        </div>
                    <?php } ?>

                    <?php if (!isset($this->lordId) || empty($this->lordId)){?>
                        <div class="form-group col-sm-6 right-border <?php if ((!empty($this->error)) && array_key_exists('lord_id', $this->error)) { echo 'has-error'; }?>">
                            <input type="name" class="form-control" id="lord_id" placeholder="LandLord / Agent Email" name = "lord_id" value="<?php if ((!empty($this->error))) { echo Formatting::utf8_htmlentities($_POST['lord_id']);} elseif(!empty($this->stored_data['lord_id'])){echo $this->stored_data['lord_id'];}?>">
                        </div>
                    <?php } ?>

                    <div class="form-group col-sm-6 <?php if ((!empty($this->error)) && array_key_exists('users[]', $this->error)) { echo 'has-error'; }?> add-tenant" id = "add-tenants_1">
                        <input type="email" class="form-control" id="users_1" placeholder="Add Other Tenant Email" name = "users[]">
                    </div>
                </div>

                <div class = "row">
                    <div class="form-group col-sm-6 right-border <?php if ((!empty($this->error)) && array_key_exists('check_in', $this->error)){echo 'has-error';}?>">
                        <input type="text" class="form-control input-datepicker" id="check_in" placeholder="Check In Date" name="check_in" data-date-format="yyyy-mm-dd" data-date-view-mode="days" data-date-min-view-mode="days" value="<?php if ((!empty($this->missing)) || (!empty($this->error))) { echo Formatting::utf8_htmlentities(date('Y-m-d', strtotime($_POST['check_in'])));}elseif(!empty($this->stored_data['check_in'])){echo date('Y-m-d', strtotime($this->stored_data['check_in']));}?>">
                        <img src ="/assets/images/calendar.png" class = "calendar-image" alt = "calender-image">
                    </div>


                    <div class="form-group col-sm-6 <?php if ((!empty($this->error)) && array_key_exists('check_out', $this->error)){echo 'has-error';}?>">
                        <input type="text" id="check_out" name="check_out" class="form-control input-datepicker" data-date-format="yyyy-mm-dd" data-date-view-mode="days" data-date-min-view-mode="days" placeholder="Check Out Date" value="<?php if ((!empty($this->missing)) || (!empty($this->error))) { echo Formatting::utf8_htmlentities(date('Y-m-d', strtotime($_POST['check_out'])));}elseif(!empty($this->stored_data['check_out'])){echo date('Y-m-d', strtotime($this->stored_data['check_out']));}?>">
                        <img src ="/assets/images/calendar.png" class = "calendar-image" alt = "calender-image">
                    </div>
                </div>

                <div class = "row">
                    <div class="col-md-6 form-group">
                        <a class="formbtn btn-default add-tenants block" id="add-tenants_1" title="Add More Tenants" data-id="1"></i> Add Another Tenant</a>
                    </div>
                    <div class = "col-md-2 hide">
                        <a data-toggle="tooltip" id= "remove-tenant" title="Remove Tenant" class="btn btn-effect-ripple btn-sm btn-danger remove-tenant"><i class="fa fa-minus"></i> Remove Tenant</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 form-spacing" style="text-align:center">
                <div class = "back-to-dash"><a href = "/users/dashboard/"><img src = "/assets/images/back-to-dash.png"/> <span>Back to dashboard</span></a></div>
                <button type="submit" class="formbtn btn-default" name="save" value = "save">Start</button>
            </div>
        </form>
    </div>
</div>