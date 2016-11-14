<!-- Page content -->
<div id="page-content">
    <!-- Header -->
    <div class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <div class="header-section">
                    <h1>Reports</h1>
                </div>
            </div>
            <div class="col-sm-6 hidden-xs">
                <div class="header-section">
                    <ul class="breadcrumb breadcrumb-top">
                        <li><a href="/backoffice/reports/index">Reports</a></li>
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
            <h2><?php if(isset($this->stored_data['id'])){echo "Edit"; }else{ echo "Add";}?> Reports</h2>
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

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('property_title', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="property_title">Property Title</label>
                <div class="col-md-5">
                    <input disabled type="text" id="property_title" name="property_title" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['property_title']);} elseif(!empty($this->stored_data['property_title'])){echo $this->stored_data['property_title'];}?>">
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('lord_firstname', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="property_title">LandLord / Letting Agent Name</label>
                <div class="col-md-5">
                    <input disabled type="text" id="lord_firstname" name="lord_firstname" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['lord_firstname']);} elseif(!empty($this->stored_data['lord_firstname'])){echo $this->stored_data['lord_firstname'].' '.$this->stored_data['lord_surname'];}?>">
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('tenant_firstname', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="property_title">Leading Tenant Name</label>
                <div class="col-md-5">
                    <input disabled type="text" id="tenant_firstname" name="tenant_firstname" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['tenant_firstname']);} elseif(!empty($this->stored_data['tenant_firstname'])){echo $this->stored_data['tenant_firstname'].' '.$this->stored_data['tenant_surname'];}?>">
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('status', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="status">Current Report Status</label>
                <div class="col-md-5">
                    <select id="type" name="status" class="form-control">
                        <?php foreach($this->status as $key => $status){?>
                            <option value="<?php echo $key ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['status'] == $key)) {echo 'selected="selected"';} elseif(!empty($this->stored_data['status']) && $this->stored_data['status'] == $key){echo 'selected="selected"';}?> > <?php echo $status?></option>
                        <?php } ?>
                    </select>
                </div>
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

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('meter_type', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="status">Meter Type</label>
                <div class="col-md-5">
                    <select id="type" name="meter_type" class="form-control">
                        <?php foreach($this->meter_type as $key => $meter_type){?>
                            <option value="<?php echo $key ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['meter_type'] == $key)) {echo 'selected="selected"';} elseif(!empty($this->stored_data['meter_type']) && $this->stored_data['meter_type'] == $key){echo 'selected="selected"';}?> > <?php echo $meter_type?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('meter_reading', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="meta_title">Meter Reading</label>
                <div class="col-md-5">
                    <input type="text" id="meter_reading" name="meter_reading" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['meter_reading']);} elseif(!empty($this->stored_data['meter_reading'])){echo $this->stored_data['meter_reading'];}?>">
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('meter_measurement', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="meta_title">Meter Measurement</label>
                <div class="col-md-5">
                    <input type="text" id="meter_measurement" name="meter_measurement" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['meter_measurement']);} elseif(!empty($this->stored_data['meter_measurement'])){echo $this->stored_data['meter_measurement'];}?>">
                </div>
            </div>

            <?php if(isset($this->stored_data['id']) && $this->stored_data['id'] != null && !empty($this->stored_data['meter_image'])){?>
                <div class="form-group">
                    <label class="col-md-2 control-label" for="current file">Current Meter Image</label>
                    <div class="col-md-10 double-input">
                        <div class="col-md-5">
                            <td><img src="/image.php?width=120&height=120&image=/assets/uploads/<?php echo $this->stored_data['meter_image']?>" alt="<?php echo $this->stored_data['meter_image']?>"></td>
                        </div>

                        <div class="col-xs-6">
                            <div class="edit-download-wrap">
                                <a href="/backoffice/reports/download/<?php echo $this->stored_data['id'];?>/meter/" class="btn btn-primary">Download Current Meter Image <i class="fa fa-cloud-download"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php if(isset($this->stored_data['id']) && $this->stored_data['id'] != null && !empty($this->stored_data['tenant_agreement'])){?>
                <div class="form-group">
                    <label class="col-md-2 control-label" for="current file">Download Tenant Agreement</label>
                        <div class="col-md-offset-4 col-xs-6">
                            <div class="edit-download-wrap">
                                <a href="/backoffice/reports/download/<?php echo $this->stored_data['id'];?>/agreement/" class="btn btn-primary">Download Current Tenant Agreement <i class="fa fa-cloud-download"></i></a>
                            </div>
                        </div>
                </div>
            <?php } ?>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('oil_level', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="oil_level">Oil Level</label>
                <div class="col-md-5">
                    <input type="text" id="oil_level" name="oil_level" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['oil_level']);} elseif(!empty($this->stored_data['oil_level'])){echo $this->stored_data['oil_level'];}?>">
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('keys_acquired', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="status">Key Status</label>
                <div class="col-md-5">
                    <select id="type" name="keys_acquired" class="form-control">
                        <?php foreach($this->key_status as $key => $key_status){?>
                            <option value="<?php echo $key ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['keys_acquired'] == $key)) {echo 'selected="selected"';} elseif(!empty($this->stored_data['keys_acquired']) && $this->stored_data['keys_acquired'] == $key){echo 'selected="selected"';}?> > <?php echo $key_status?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('fire_extin', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="fire_extin">Fire Extinguishers</label>
                <div class="col-md-5">
                    <input type="text" id="fire_extin" name="fire_extin" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['fire_extin']);} elseif(!empty($this->stored_data['fire_extin'])){echo $this->stored_data['fire_extin'];}?>">
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('fire_blanket', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="fire_blanket">Fire Blankets</label>
                <div class="col-md-5">
                    <input type="text" id="fire_blanket" name="fire_blanket" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['fire_blanket']);} elseif(!empty($this->stored_data['fire_blanket'])){echo $this->stored_data['fire_blanket'];}?>">
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('smoke_alarm', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="smoke_alarm">Carbon Monoxide / Smoke Alarms</label>
                <div class="col-md-5">
                    <input type="text" id="smoke_alarm" name="smoke_alarm" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['smoke_alarm']);} elseif(!empty($this->stored_data['smoke_alarm'])){echo $this->stored_data['smoke_alarm'];}?>">
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('tenant_approved_check_in', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="tenant_approved_check_in">Check In Status</label>
                <div class="col-md-5">
                    <?php
                        if(isset($this->stored_data['tenant_approved_check_in']) && $this->stored_data['tenant_approved_check_in'] == 1 && isset($this->stored_data['lord_approved_check_in']) && $this->stored_data['lord_approved_check_in'] == 1){
                            echo '<input disabled type="text" id="tenant_approved_check_in" name="tenant_approved_check_in" class="form-control" value="Both Tenant and LandLord have approved">';
                        }elseif(isset($this->stored_data['tenant_approved_check_in']) && $this->stored_data['tenant_approved_check_in'] == 1){
                            echo '<input disabled type="text" id="tenant_approved_check_in" name="tenant_approved_check_in" class="form-control" value="Only Tenant has approved">';
                        }elseif(isset($this->stored_data['lord_approved_check_in']) && $this->stored_data['lord_approved_check_in'] == 1){
                            echo '<input disabled type="text" id="tenant_approved_check_in" name="tenant_approved_check_in" class="form-control" value="Only Landlord has approved">';
                        }else{
                            echo '<input disabled type="text" id="tenant_approved_check_in" name="tenant_approved_check_in" class="form-control" value="Neither the Landlord nor Tenant has approved">';
                        }
                    ?>
                </div>
            </div>

            <div class="form-group <?php if ((!empty($this->error)) && array_key_exists('tenant_approved_check_out', $this->error)) { echo 'has-error'; }?>">
                <label class="col-md-2 control-label" for="tenant_approved_check_out">Check Out Status</label>
                <div class="col-md-5">
                    <?php
                        if(isset($this->stored_data['tenant_approved_check_out']) && $this->stored_data['tenant_approved_check_out'] == 1 && isset($this->stored_data['lord_approved_check_out']) && $this->stored_data['lord_approved_check_out'] == 1){
                            echo '<input disabled type="text" id="tenant_approved_check_out" name="tenant_approved_check_out" class="form-control" value="Both Tenant and LandLord have approved">';
                        }elseif(isset($this->stored_data['tenant_approved_check_out']) && $this->stored_data['tenant_approved_check_out'] == 1){
                            echo '<input disabled type="text" id="tenant_approved_check_out" name="tenant_approved_check_out" class="form-control" value="Only Tenant has approved">';
                        }elseif(isset($this->stored_data['lord_approved_check_out']) && $this->stored_data['lord_approved_check_out'] == 1){
                            echo '<input disabled type="text" id="tenant_approved_check_out" name="tenant_approved_check_out" class="form-control" value="Only Landlord has approved">';
                        }else{
                            echo '<input disabled type="text" id="tenant_approved_check_out" name="tenant_approved_check_out" class="form-control" value="Neither the Landlord nor Tenant has approved">';
                        }
                    ?>
                </div>
            </div>

            <div class="form-group form-actions">
                <div class="col-md-5 col-md-offset-2">
                    <input id = "save" type="submit" name="save" class="btn btn-effect-ripple btn-primary loader" value="Save">
                    <input type="submit" name="cancel" class="btn btn-effect-ripple btn-danger loader" value="Cancel">
                </div>
            </div>

            <?php if(isset($this->checkInData) && !empty($this->checkInData)){?>
                <!-- Table Styles Block -->
                <div class="block full">
                    <div class="block-title">
                        <h2>Check In Details</h2>
                    </div>
                    <div id="order_intro" class="col-sm-12 col-xs-12">
                        <?php foreach($this->checkInData as $key => $data){?>
                            <div class="order_row">
                                <p class="lead"><strong><?php echo $data['name']?></strong></p>
                            </div><!-- end order_row -->

                             <table class="table table-bordered table-striped table-vcenter table-hover">
                                <tr>
                                    <th>Clean Status</th>
                                    <td><?php echo $this->clean_status[$data['clean']]?></td>
                                </tr>

                                <tr>
                                    <th>Tenant Comment</th>
                                    <td><?php echo $data['tenant_comment']?></td>
                                </tr>

                                <tr>
                                    <th>Lord Comment</th>
                                    <td><?php echo $data['lord_comment']?></td>
                                </tr>

                                <?php if(isset($data['items']) && !empty($data['items'])){?>
                                    <?php foreach($data['items'] as $key2 => $item){?>
                                        <tr>
                                            <th colspan = "2" style="text-align:center;"><?php echo $item['name'] ?></th>
                                        </tr>

                                        <tr>
                                            <th>Item Status</th>
                                            <td><?php echo $this->status[$item['status']]?></td>
                                        </tr>

                                        <tr>
                                            <th>Item Image</th>
                                            <td>
                                                <?php 
                                                    if(isset($item['image']) && !empty($item['image'])){
                                                       echo '<img src="/image.php?width=120&height=120&image=/assets/uploads/'.$item['image'].'" alt="'.$item['image'].'">';

                                                    }else{
                                                        echo 'No Image Supplied';
                                                    }
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Tenant Comment</th>
                                            <td><?php echo $item['tenant_comment']?></td>
                                        </tr>

                                        <tr>
                                            <th>LandLord / Letting Agent Comment</th>
                                            <td><?php echo $item['lord_comment']?></td>
                                        </tr>

                                        <tr>
                                            <th>    &nbsp;  </th>
                                            <td>    &nbsp;  </td>
                                        </tr>

                                    <?php } ?>
                                <?php } ?>
                            </table>
                        <?php } ?>
                    </div>

                    <div id="back-btn" class="block-section">
                        <a href="/backoffice/properties/" class="btn btn-effect-ripple btn-primary" style="overflow: hidden; position: relative;"><i class="fa fa-chevron-circle-left"></i> Back</a>
                    </div>
                    <!-- </div> -->
                </div>
                <!-- END Table Styles Block -->
            <?php } ?>

            <?php if(isset($this->checkOutData) && !empty($this->checkOutData)){?>
                <!-- Table Styles Block -->
                <div class="block full">
                    <div class="block-title">
                        <h2>Check Out Details</h2>
                    </div>
                    <div id="order_intro" class="col-sm-12 col-xs-12">
                        <?php foreach($this->checkOutData as $key => $data){?>
                            <div class="order_row">
                                <p class="lead"><strong><?php echo $data['name']?></strong></p>
                            </div><!-- end order_row -->

                             <table class="table table-bordered table-striped table-vcenter table-hover">
                                <tr>
                                    <th>Clean Status</th>
                                    <td><?php echo $this->clean_status[$data['clean']]?></td>
                                </tr>

                                <tr>
                                    <th>Tenant Comment</th>
                                    <td><?php echo $data['tenant_comment']?></td>
                                </tr>

                                <tr>
                                    <th>Lord Comment</th>
                                    <td><?php echo $data['lord_comment']?></td>
                                </tr>

                                <?php if(isset($data['items']) && !empty($data['items'])){?>
                                    <?php foreach($data['items'] as $key2 => $item){?>
                                        <tr>
                                            <th colspan = "2" style="text-align:center;"><?php echo $item['name'] ?></th>
                                        </tr>

                                        <tr>
                                            <th>Item Status</th>
                                            <td><?php echo $this->status[$item['status']]?></td>
                                        </tr>

                                        <tr>
                                            <th>Item Image</th>
                                            <td>
                                                <?php 
                                                    if(isset($item['image']) && !empty($item['image'])){
                                                       echo '<img src="/image.php?width=120&height=120&image=/assets/uploads/'.$item['image'].'" alt="'.$item['image'].'">';

                                                    }else{
                                                        echo 'No Image Supplied';
                                                    }
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Tenant Comment</th>
                                            <td><?php echo $item['tenant_comment']?></td>
                                        </tr>

                                        <tr>
                                            <th>LandLord / Letting Agent Comment</th>
                                            <td><?php echo $item['lord_comment']?></td>
                                        </tr>

                                        <tr>
                                            <th>    &nbsp;  </th>
                                            <td>    &nbsp;  </td>
                                        </tr>

                                    <?php } ?>
                                <?php } ?>

                            </table>
                        <?php } ?>
                    </div>


                    <div id="back-btn" class="block-section">
                        <a href="/backoffice/reports/" class="btn btn-effect-ripple btn-primary" style="overflow: hidden; position: relative;"><i class="fa fa-chevron-circle-left"></i> Back</a>
                    </div>
                    <!-- </div> -->
                </div>
                <!-- END Table Styles Block -->
            <?php } ?>


        </form>
        <!-- END General Elements Content -->
    </div>
    <!-- END General Elements Block -->
</div>
<!-- END Page Content -->