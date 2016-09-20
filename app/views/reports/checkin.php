<div class = "container">
    <div class = "row">
        <div class = "col-md-offset-3 col-md-3">
            Property Title:
        </div>
        <div class = "col-md-3">
            <?php echo $this->property[0]['title']?>
        </div>

        <div class = "col-md-offset-3 col-md-3">
            House Number:
        </div>
        <div class = "col-md-3">
            <?php echo $this->property[0]['house_number']?>
        </div>

        <div class = "col-md-offset-3 col-md-3">
            Address Line 1:
        </div>
        <div class = "col-md-3">
            <?php echo $this->property[0]['address_1']?>
        </div>

        <div class = "col-md-offset-3 col-md-3">
            Address Line 2:
        </div>
        <div class = "col-md-3">
            <?php echo $this->property[0]['address_2']?>
        </div>

        <div class = "col-md-offset-3 col-md-3">
            Address Line 3:
        </div>
        <div class = "col-md-3">
            <?php echo $this->property[0]['address_3']?>
        </div>

        <div class = "col-md-offset-3 col-md-3">
            Address Line 4
        </div>
        <div class = "col-md-3">
            <?php echo $this->property[0]['address_4']?>
        </div>

        <div class = "col-md-offset-3 col-md-3">
            Postcode:
        </div>
        <div class = "col-md-3">
            <?php echo $this->property[0]['postcode']?>
        </div>
    </div>
</div>

<br/>
<br/>

<div class = "container">
    <div class = "row">
        <?php foreach($this->users as $key => $user){?>
            <div class = "col-md-offset-3 col-md-3">
                <?php if ($user[0]['id'] == $this->report[0]['lord_id']){?>
                    Landlord / Letting Agent:
                <?php }elseif($user[0]['id'] == $this->report[0]['lead_tenant_id']){?>
                    Lead Tenant:
                <?php }else {?>
                    Other Tenants:
                <?php }?>
            </div>
            <div class = "col-md-3">
                <?php echo $user[0]['firstname'].' '.$user[0]['surname'].' ('.$user[0]['email'].')';?>
            </div>
        <?php }?>
    </div>
</div>

<br/>
<br/>

<form id="form" action="" method="post" class="form-horizontal form-bordered" enctype="multipart/form-data">
    <div class = "container">
        <div class = "row">
             <div class = "col-md-offset-3 col-md-3">
                Current Status
            </div>
            <div class = "col-md-3">
                <?php echo $this->status[$this->report[0]['status']]?>        
            </div>

            <div class = "col-md-offset-3 col-md-3">
                Check In Date
            </div>
            <div class = "col-md-3">
                <?php echo date("F j, Y", strtotime($this->report[0]['check_in']))?>
            </div>

            <div class = "col-md-offset-3 col-md-3">
                Check Out Date
            </div>
            <div class = "col-md-3">
                <?php echo date("F j, Y", strtotime($this->report[0]['check_out']))?>        
            </div>

            <div class = "col-md-offset-3 col-md-3">
                Meter Type
            </div>
            <div class = "col-md-3">
                <select <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID'] && $this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> id="type" name="meter_type" class="form-control">
                    <?php foreach($this->meter_type as $key => $meter_type){?>
                        <option value="<?php echo $key ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['meter_type'] == $key)) {echo 'selected="selected"';} elseif(!empty($this->stored_data['meter_type']) && $this->stored_data['meter_type'] == $key){echo 'selected="selected"';}?> > <?php echo $meter_type?></option>
                    <?php } ?>
                </select>            
            </div>

            <div class = "col-md-offset-3 col-md-3">
                Meter Reading
            </div>
            <div class = "col-md-3">
                <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID'] && $this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="text" id="meter_reading" name="meter_reading" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['meter_reading']);} elseif(!empty($this->stored_data['meter_reading'])){echo $this->stored_data['meter_reading'];}?>">
            </div>

            <div class = "col-md-offset-3 col-md-3">
                Meter Measurement Type
            </div>
            <div class = "col-md-3">
                <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID'] && $this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="text" id="meter_measurement" name="meter_measurement" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['meter_measurement']);} elseif(!empty($this->stored_data['meter_measurement'])){echo $this->stored_data['meter_measurement'];}?>">
            </div>

            <?php if(isset($this->report[0]['meter_image']) && !empty($this->report[0]['meter_image'])){?>
                <div class = "col-md-offset-3 col-md-3">
                    Meter Image
                </div>
                <div class = "col-md-3">
                    <img src="/image.php?width=120&height=120&image=/assets/uploads/<?php echo $this->report[0]['meter_image']?>" alt="<?php echo $this->report[0]['meter_image']?>">
                    <a href="/reports/download/<?php echo $this->report[0]['id'];?>/meter" class="btn btn-primary">Download Meter Image<i class="fa fa-cloud-download"></i></a>
                </div>
            <?php } else {?>
                <div class = "col-md-offset-3 col-md-3">
                    Upload Meter Image
                </div>
                <div class = "col-md-3">
                    <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID'] && $this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="file" name="meter_image" id="meter_image">
                </div>
            <?php } ?>

            <?php if(isset($this->report[0]['tenant_agreement']) && !empty($this->report[0]['tenant_agreement'])){?>
                <div class = "col-md-offset-3 col-md-3">
                    Tenant Agreement
                </div>
                <div class = "col-md-3">
                    <a href="/reports/download/<?php echo $this->report[0]['id'];?>/tenant" class="btn btn-primary">Download Tenant Agreement<i class="fa fa-cloud-download"></i></a>
                </div>
            <?php } else {?>
                <div class = "col-md-offset-3 col-md-3">
                    Upload Tenant Agreement
                </div>
                <div class = "col-md-3">
                    <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID'] && $this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="file" name="tenant_agreement" id="tenant_agreement">
                </div>
            <?php } ?>

            <div class = "col-md-offset-3 col-md-3">
                Oil Level
            </div>
            <div class = "col-md-3">
                <input <?php if($this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="text" id="oil_level" name="oil_level" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['oil_level']);} elseif(!empty($this->report[0]['oil_level'])){echo $this->report[0]['oil_level'];}?>">
            </div>

            <div class = "col-md-offset-3 col-md-3">
                Keys Acquired
            </div>
            <div class = "col-md-3">
                <select <?php if($this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> id="keys_acquired" name="keys_acquired" class="form-control">
                    <?php foreach($this->key_status as $key3 => $key_status){?>
                        <option value="<?php echo $key3 ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['keys_acquired'] == $key3)) {echo 'selected="selected"';} elseif(!empty($this->report[0]['keys_acquired']) && $this->report[0]['keys_acquired'] == $key3){echo 'selected="selected"';}?> > <?php echo $key_status?></option>
                    <?php } ?>
                </select>            
            </div>
        </div>
    </div>

    <br/>
    <br/>

    <div class = "container">
        <div class = "row">
            <div class = "col-md-6">
                <h2>Lead Tenant</h2>
            </div>

            <div class = "col-md-6">
                <h2>Landlord Letting Agent</h2>
            </div>
        </div>
        <div class = "row">
            <div class = "col-md-3">
                Lead Tenant Approval
            </div>

            <div class = "col-md-3">
                <select <?php if($this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> id="tenant_approved_check_in" name="tenant_approved_check_in" class="form-control">
                    <?php foreach($this->YesNo as $key3 => $type){?>
                        <option value="<?php echo $key ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['tenant_approved_check_in'] == $key3)) {echo 'selected="selected"';} elseif(!empty($this->report[0]['tenant_approved_check_in']) && $this->report[0]['tenant_approved_check_in'] == $key3){echo 'selected="selected"';}?> > <?php echo $type?></option>
                    <?php } ?>
                </select>
            </div>

            <div class = "col-md-3">
                Landlord / Letting Agent Approval
            </div>

            <div class = "col-md-3">
                <select <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> id="lord_approved_check_in" name="lord_approved_check_in" class="form-control">
                    <?php foreach($this->YesNo as $key3 => $type){?>
                        <option value="<?php echo $key3 ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['lord_approved_check_in'] == $key3)) {echo 'selected="selected"';} elseif(!empty($this->report[0]['lord_approved_check_in']) && $this->report[0]['lord_approved_check_in'] == $key3){echo 'selected="selected"';}?> > <?php echo $type?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <br/>
        <br/>

        <?php if(isset($this->checkInData) && !empty($this->checkInData)){?>
            <?php foreach($this->checkInData as $key => $room){?>
                <div class = "row">
                    <div class = "col-md-offset-3 col-md-3">
                        Room Name
                    </div>
                    <div class = "col-md-3">
                        <?php echo $room['name'];?>
                        <?php if($this->report[0]['lord_id'] == $_SESSION['UserCurrentUserID'] || $this->report[0]['lead_tenant_id'] == $_SESSION['UserCurrentUserID']){?>
                            <a data-toggle="tooltip" title="Add More items" class="btn btn-effect-ripple btn-sm btn-success check-in-add-items" data-id="<?php echo $room['id']?>" data-role = "<?php echo $this->userRole?>"><i class="fa fa-plus"></i></a>
                        <?php } ?>
                    </div>
                </div>

                <br/>
                <br/>

                <div class = "row">
                    <div class = "col-md-3">
                        Clean Status
                    </div>
                    <div class = "col-md-3">
                        <select <?php if($this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> id="rooms_clean_<?php echo $key?>" name="rooms[<?php echo $room['id']?>][clean]" class="form-control">
                            <?php foreach($this->clean_status as $key => $type){?>
                                <option value="<?php echo $key ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['rooms'][$room['id']]['clean'] == $key)) {echo 'selected="selected"';} elseif(!empty($room['clean']) && $room['clean'] == $key){echo 'selected="selected"';}?> > <?php echo $type?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class = "row">
                    <div class = "col-md-3">
                        Lead Tenant Comment
                    </div>

                    <div class = "col-md-3">
                        <input <?php if($this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="text" id="rooms_tenant_comment_<?php echo $key?>" name="rooms[<?php echo $room['id']?>]['tenant_comment']" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['rooms']['<?php echo $room["id"]?>']['tenant_comment']);} elseif(!empty($room['tenant_comment'])){echo $room['tenant_comment'];}?>">
                    </div>

                    <div class = "col-md-3">
                        Landlord / Letting Agent Comment
                    </div>

                    <div class = "col-md-3">
                        <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="text" id="rooms_lord_comment_<?php echo $key?>" name="rooms[<?php echo $room['id']?>]['lord_comment']" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['rooms']['<?php echo $room["id"]?>']['lord_comment']);} elseif(!empty($room['lord_comment'])){echo $room['lord_comment'];}?>">
                    </div>
                </div>

                <br/>
                <br/>

                <div id = "new-item_<?php echo $room['id']?>">

                </div>

                <?php if(isset($room['items']) && !empty($room['items'])){?>
                    <?php foreach($room['items'] as $key2 => $item){?>
                        <div class = "row">
                            <div class = "col-md-offset-3 col-md-3">
                                Item Name:
                            </div>
                            <div class = "col-md-3">
                                <?php echo $item['name']?>
                            </div>
                        </div>

                        <div class = "row">
                            <div class = "col-md-offset-3 col-md-3">
                                Item Status:
                            </div>
                            <div class = "col-md-3">
                                <?php echo $this->status[$this->report[0]['status']]?>        
                            </div>
                        </div>
                        

                        <?php if(isset($item['image']) && !empty($item['image'])){?>
                            <div class= "row">
                                <div class = "col-md-3">
                                    Item Image
                                </div>
                                <div class = "col-md-3">
                                    <img src="/image.php?width=120&height=120&image=/assets/uploads/<?php echo $item['image']?>" alt="<?php echo $item['image']?>">
                                    <a href="/reports/download/<?php echo $item['id'];?>/item" class="btn btn-primary">Download Meter Image<i class="fa fa-cloud-download"></i></a>
                                </div>
                            </div>
                        <?php } else {?>
                            <div class = "row">
                                <div class = "col-md-3">
                                    Upload Item Image
                                </div>
                                <div class = "col-md-3">
                                    <input <?php if($this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="file" name="image" id="image">
                                </div>
                            </div>
                        <?php } ?>
                        <div class = "row">
                            <div class = "col-md-3">
                                Lead Tenant Comment
                            </div>

                            <div class = "col-md-3">
                                <input <?php if($this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="text" id="items_tenant_comment_<?php echo $key2?>" name="rooms[<?php echo $room['id']?>]['items'][<?php echo $item['id']?>]['tenant_comment']" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['rooms']['<?php echo $room["id"]?>']['items']['<?php echo $item["id"]?>']['tenant_comment']);} elseif(!empty($item['tenant_comment'])){echo $item['tenant_comment'];}?>">
                            </div>

                            <div class = "col-md-3">
                                Landlord / Letting Agent Comment
                            </div>

                            <div class = "col-md-3">
                                <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="text" id="items_lord_comment_<?php echo $key2?>" name="rooms[<?php echo $room['id']?>]['items'][<?php echo $item['id']?>]['lord_comment']" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['rooms']['<?php echo $room["id"]?>']['items']['<?php echo $item["id"]?>']['lord_comment']);} elseif(!empty($item['lord_comment'])){echo $item['lord_comment'];}?>">
                            </div>
                        </div>

                        <div class = "row">
                            <div class = "col-md-3">
                                Lead Tenant Approval
                            </div>

                            <div class = "col-md-3">
                                <select <?php if($this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> id="tenant_approved_check_in_<?php echo $key2?>" name="rooms[<?php echo $room['id']?>]['items'][<?php echo $item['id']?>]['tenant_approved']" class="form-control">
                                    <?php foreach($this->YesNo as $key3 => $type){?>
                                        <option value="<?php echo $key3 ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['rooms'][$room['id']]['items'][$item['id']]['tenant_approved'] == $key3)) {echo 'selected="selected"';} elseif(!empty($item['tenant_approved']) && $item['tenant_approved'] == $key3){echo 'selected="selected"';}?> > <?php echo $type?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class = "col-md-3">
                                Landlord / Letting Agent Approval
                            </div>

                            <div class = "col-md-3">
                                <select <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> id="lord_approved_check_in_<?php echo $key2?>" name="rooms[<?php echo $room['id']?>]['items'][<?php echo $item['id']?>]['lord_approved']" class="form-control">
                                    <?php foreach($this->YesNo as $key3 => $type){?>
                                        <option value="<?php echo $key3 ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['rooms'][$room['id']]['items'][$item['id']]['lord_approved'] == $key3)) {echo 'selected="selected"';} elseif(!empty($item['lord_approved']) && $item['lord_approved'] == $key3){echo 'selected="selected"';}?> > <?php echo $type?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>


                    <?php } ?>
                <?php } ?>
            <?php }?>
        <?php } ?>

    </div>
</form>




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