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
                    Check Out
                </div>
            </div>
            <div class = "row">
                <div class ="col-xs-12 welcome-message">
                    Please complete the details below.
                </div>
            </div>
        </div>

        <?php $action  = $this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID'] && $this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID'] ? '/reports/sign/'.$this->report[0]['id'].'/checkout' : ''?>
        <form id="form" action="<?php echo $action?>" method="post" class="form-horizontal form-bordered check-in-form" enctype="multipart/form-data">
            <div class = "form-wrapper check-in">
                <div class = "row">
                    <div class = "col-md-6 right-border">
                        <div class = "row">
                            <div class = "form-group col-md-6 label-check-in">
                                Property Title
                            </div>
                            <div class = "form-group col-md-6 answer-check-in">
                                <?php echo $this->property[0]['title']?>
                            </div>
                        </div>

                        <div class = "row">
                            <div class = "form-group col-md-6 label-check-in">
                                Number
                            </div>
                            <div class = "form-group col-md-6 answer-check-in">
                                <?php echo $this->property[0]['house_number']?>
                            </div>
                        </div>

                        <div class = "row">
                            <div class = "form-group col-md-6 label-check-in">
                                Address Line 1
                            </div>
                            <div class = "form-group col-md-6 answer-check-in">
                                <?php echo $this->property[0]['address_1']?>
                            </div>
                        </div>

                        <div class = "row">
                            <div class = "form-group col-md-6 label-check-in">
                                Address Line 2
                            </div>
                            <div class = "form-group col-md-6 answer-check-in">
                                <?php echo $this->property[0]['address_2']?>
                            </div>
                        </div>

                        <div class = "row">
                            <div class = "form-group col-md-6 label-check-in">
                                Address Line 3
                            </div>
                            <div class = "form-group col-md-6 answer-check-in">
                                <?php echo $this->property[0]['address_3']?>
                            </div>
                        </div>

                        <div class = "row">
                            <div class = "form-group col-md-6 label-check-in">
                                Address Line 4
                            </div>
                            <div class = "form-group col-md-6 answer-check-in">
                                <?php echo $this->property[0]['address_4']?>
                            </div>
                        </div>

                        <div class = "row">
                            <div class = "form-group col-md-6 label-check-in">
                                Postcode
                            </div>
                            <div class = "form-group col-md-6 answer-check-in">
                                <?php echo $this->property[0]['postcode']?>
                            </div>
                        </div>
                    </div>

                    <div class = "col-md-6">
                        <?php foreach($this->users as $key => $user){?>
                            <div class = "row">
                                <div class = "form-group col-md-4 label-check-in">
                                    <?php if ($user[0]['id'] == $this->report[0]['lord_id']){?>
                                        Landlord / Agent:
                                    <?php }elseif($user[0]['id'] == $this->report[0]['lead_tenant_id']){?>
                                        Lead Tenant:
                                    <?php }else {?>
                                        Other Tenants:
                                    <?php }?>
                                </div>
                                <div class = "form-group col-md-8 answer-check-in">
                                    <?php echo $user[0]['firstname'].' '.$user[0]['surname'].' ('.$user[0]['email'].')';?>
                                </div>
                            </div>
                        <?php }?>
                    </div>
                </div>

                <div class = "row">
                    <div class = "col-md-12 check-in-status">
                        <div class = "col-md-4">
                            <span class="label-check-in">Current Status</span>
                            <?php //echo $this->status[$this->report[0]['tenant_approved_check_out'] + $this->report[0]['lord_approved_check_out']]?>
                            <?php $class = $this->report[0]['tenant_approved_check_out'] + $this->report[0]['lord_approved_check_out']?>
                            <i class="fa fa-circle status-<?php echo $class?>" aria-hidden="true"></i>
                        </div>
                        <div class = "col-md-4">
                            <span class="label-check-in">Check In Date</span>
                            <?php echo date("F j, Y", strtotime($this->report[0]['check_in']))?>
                        </div>
                        <div class = "col-md-4">
                            <span class="label-check-in">Check Out Date</span>
                            <?php echo date("F j, Y", strtotime($this->report[0]['check_out']))?>
                        </div>
                    </div>
                </div>
            </div>
            <div class ="form-wrapper check-in-2">
                <div class = "row">
                    <div class="form-group col-sm-6 right-border">
                        <select id="meter_type" name="meter_type" class="form-control" <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID'] && $this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?>>
                            <?php foreach($this->meter_type as $key => $meter_type){?>
                                <option value="<?php echo $key ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['meter_type'] == $key)) {echo 'selected="selected"';} elseif(!empty($this->report[0]['meter_type']) && $this->report[0]['meter_type'] == $key){echo 'selected="selected"';}?> > <?php echo $meter_type?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class = "form-group col-sm-6">
                        <input <?php if($this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="text" id="oil_level" name="oil_level" class="form-control" placeholder="Oil Level" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['oil_level']);} elseif(!empty($this->report[0]['oil_level'])){echo $this->report[0]['oil_level'];}?>">
                    </div>
                </div>

                <div class = "row">
                    <div class="form-group col-sm-6 right-border">
                        <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID'] && $this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="text" id="meter_measurement" name="meter_measurement" class="form-control" placeholder="Meter Measurement" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['meter_measurement']);} elseif(!empty($this->report[0]['meter_measurement'])){echo $this->report[0]['meter_measurement'];}?>">
                    </div>

                    <div class="form-group col-sm-6 <?php if ((!empty($this->error)) && array_key_exists('meter_reading', $this->error)){echo 'has-error';}?>">
                        <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID'] && $this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="text" id="meter_reading" name="meter_reading" class="form-control" placeholder="Meter Reading" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['meter_reading']);} elseif(!empty($this->report[0]['meter_reading'])){echo $this->report[0]['meter_reading'];}?>">
                    </div>
                </div>

                <div class = "row">
                    <div class="form-group col-sm-6 right-border">
                        <?php if(isset($this->report[0]['tenant_agreement']) && !empty($this->report[0]['tenant_agreement'])){?>
                            <a href="/reports/download/<?php echo $this->report[0]['id'];?>/tenant" class="btn btn-primary form-control">Download Tenant Agreement <i class="fa fa-cloud-download"></i></a>
                        <?php } else {?>
                            <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID'] && $this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="file" name="tenant_agreement" id="tenant_agreement" class = "form-control filestyle" data-buttonText="Tenant Agreement" data-buttonBefore="true">
                        <?php } ?>
                    </div>

                    <div class = "form-group col-sm-6">
                        <?php if(isset($this->report[0]['meter_image']) && !empty($this->report[0]['meter_image'])){?>
                            <img src="/image.php?width=90&height=90&image=/assets/uploads/<?php echo $this->report[0]['meter_image']?>" alt="<?php echo $this->report[0]['meter_image']?>">
                            <a href="/reports/download/<?php echo $this->report[0]['id'];?>/meter" class="btn btn-primary check-in-download">Download Meter Image <i class="fa fa-cloud-download"></i></a>
                        <?php } else {?>
                            <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID'] && $this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="file" name="meter_image" id="meter_image" class = "form-control filestyle" data-buttonText="Meter Image" data-buttonBefore="true">
                        <?php } ?>
                    </div>
                </div>

                <div class = "row">
                    <div class="form-group col-sm-6 right-border">
                        <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID'] && $this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="number" id="keys_front_door" name="keys_front_door" class="form-control" placeholder="Keys Front Door" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['keys_front_door']);} elseif(!empty($this->report[0]['keys_front_door'])){echo $this->report[0]['keys_front_door'];}?>">
                    </div>

                    <div class = "form-group col-sm-6">
                        <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID'] && $this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="number" id="keys_bedroom_door" name="keys_bedroom_door" class="form-control" placeholder="Keys Bedroom Door" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['keys_bedroom_door']);} elseif(!empty($this->report[0]['keys_bedroom_door'])){echo $this->report[0]['keys_bedroom_door'];}?>">
                    </div>
                </div>

                <div class = "row">
                    <div class="form-group col-sm-6 right-border">
                        <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID'] && $this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="number" id="keys_block_door" name="keys_block_door" class="form-control" placeholder="Keys Block Door" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['keys_block_door']);} elseif(!empty($this->report[0]['keys_block_door'])){echo $this->report[0]['keys_block_door'];}?>">
                    </div>

                    <div class = "form-group col-sm-6">
                        <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID'] && $this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="number" id="keys_back_door" name="keys_back_door" class="form-control" placeholder="Keys Back Door" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['keys_back_door']);} elseif(!empty($this->report[0]['keys_back_door'])){echo $this->report[0]['keys_back_door'];}?>">
                    </div>
                </div>

                <div class = "row">
                    <div class="form-group col-sm-6 right-border">
                        <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID'] && $this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="number" id="keys_garage_door" name="keys_garage_door" class="form-control" placeholder="Keys Garage Door" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['keys_garage_door']);} elseif(!empty($this->report[0]['keys_garage_door'])){echo $this->report[0]['keys_garage_door'];}?>">
                    </div>

                    <div class = "form-group col-sm-6">
                        <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID'] && $this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="number" id="keys_other_door" name="keys_other_door" class="form-control" placeholder="Keys Other Door" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['keys_other_door']);} elseif(!empty($this->report[0]['keys_other_door'])){echo $this->report[0]['keys_other_door'];}?>">
                    </div>
                </div>

                <div class = "row">
                    <div class="form-group col-sm-6 right-border">
                        <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID'] && $this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="text" id="fire_blanket" name="fire_blanket" class="form-control" placeholder="Fire Blankets" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['fire_blanket']);} elseif(!empty($this->report[0]['fire_blanket'])){echo $this->report[0]['fire_blanket'];}?>">
                    </div>

                    <div class = "form-group col-sm-6">
                        <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID'] && $this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="text" id="smoke_alarm" name="smoke_alarm" class="form-control" placeholder="Carbon Monoxide / Smoke Alarms" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['smoke_alarm']);} elseif(!empty($this->report[0]['smoke_alarm'])){echo $this->report[0]['smoke_alarm'];}?>">
                    </div>
                </div>

                <div class = "row">
                    <div class = "form-group col-sm-6 right-border">
                        <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID'] && $this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="text" id="fire_extin" name="fire_extin" class="form-control" placeholder="Fire Extinguishers" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['fire_extin']);} elseif(!empty($this->report[0]['fire_extin'])){echo $this->report[0]['fire_extin'];}?>">
                    </div>
                </div>
            </div>

            <div class = "row"><!-- Key for statuses -->
                <div class = "col-sm-6" style = "padding-right: 30px;">
                    <div class = "form-wrapper" style = "padding:10px 0;">
                        <div class="form-group-2 col-sm-12" style = "min-height:310px;">
                            <h1 class = "centre">Report Statuses</h1>
                            <p class = "status-0">Red means neither the Landlord / Agent nor Lead Tenant have approved.</p>
                            <p class = "status-1">Amber means either the Landlord / Agent or Lead Tenant have approved.</p>
                            <p class = "status-2">Green means both the Landlord / Agent and Lead Tenant have approved.</p>
                        </div>
                    </div>
                </div>

                <div class = "col-sm-6">
                    <div class = "form-wrapper" style = "padding:10px 0;">
                        <div class="form-group-2 col-sm-12" style = "min-height:310px;">
                            <h1 class = "centre">Item Statuses</h1>
                            <p class = "status-0">Not working condition, marked or damaged</p>
                            <p class = "status-1">Working condition, marked or damaged</p>
                            <p class = "status-2">Full working condition. No marks or damage</p>
                        </div>
                    </div>
                </div>
            </div><!-- END OF STATUS ROW -->

            <?php if(isset($this->checkOutData) && !empty($this->checkOutData)){?>
                <?php foreach($this->checkOutData as $key => $room){?>
                    <div class = "row">
                        <div class = "col-sm-12">
                            <h3 class = "check-in-h3"><?php echo $room['name'];?></h3>
                        </div>
                    </div>

                    <div class = "form-wrapper">
                        <div class = "row">
                            <div class="form-group col-sm-6 right-border">
                                <label class = "form-control form-group-2-label" style = "width:40%">
                                    Clean Status
                                </label>
                                <select <?php if($this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> id="rooms_clean_<?php echo $key?>" name="rooms[<?php echo $room['id']?>][clean]" class="form-control form-group-2-select" style = "width:60%">
                                    <?php foreach($this->clean_status as $key => $type){?>
                                        <option value="<?php echo $key ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['rooms'][$room['id']]['clean'] == $key)) {echo 'selected="selected"';} elseif(!empty($room['clean']) && $room['clean'] == $key){echo 'selected="selected"';}?> > <?php echo $type?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class = "row">
                            <div class="form-group col-sm-6 right-border">
                                <textarea <?php if($this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> id="rooms_tenant_comment_<?php echo $key?>" name="rooms[<?php echo $room['id']?>][tenant_comment]" class="form-control" placeholder="Tenant Comment" ><?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['rooms']['<?php echo $room["id"]?>']['tenant_comment']);} elseif(!empty($room['tenant_comment'])){echo $room['tenant_comment'];}?></textarea>
                            </div>

                            <div class="form-group col-sm-6">
                                <textarea <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> id="rooms_lord_comment_<?php echo $key?>" name="rooms[<?php echo $room['id']?>][lord_comment]" class="form-control" placeholder="Landlord / Agent comment"><?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['rooms']['<?php echo $room["id"]?>']['lord_comment']);} elseif(!empty($room['lord_comment'])){echo $room['lord_comment'];}?></textarea>
                            </div>
                        </div>

                        <div id = "new-item_<?php echo $room['id']?>">

                        </div>

                        <?php if(isset($room['items']) && !empty($room['items'])){?>
                            <?php $count = 1;?>
                            <?php foreach($room['items'] as $key2 => $item){?>
                                <div class = "row row-space">
                                    <div class = "col-sm-3 form-group">
                                        <h2>Item <?php echo $count?></h2>
                                    </div>
                                    <div class = "col-sm-9 form-group">
                                    </div>
                                </div>


                                <div class = "item-background">
                                    <div class = "row">
                                        <div class="form-group col-sm-6 right-border">
                                            <label class = "form-control form-group-3-label">
                                                <?php echo $item['name']?>
                                            </label>
                                        </div>
                                    </div>

                                    <div class = "row">
                                        <div class="form-group col-sm-6 right-border">
                                            <label class = "form-control form-group-3-label">
                                                <!-- Item Status: <?php echo $this->status[$item['tenant_approved'] + $item['lord_approved']]?> -->
                                                <?php $class = max($item['tenant_approved'], $item['lord_approved']);?>
                                                Item Status: <i class="fa fa-circle status-<?php echo $class?>" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>

                                    <div class = "row">
                                        <div class = "form-group col-sm-6 right-border">
                                            <?php if(isset($item['image']) && !empty($item['image'])){?>
                                                <img src="/image.php?width=90&height=90&image=/assets/uploads/<?php echo $item['image']?>" alt="<?php echo $item['image']?>">
                                                <a href="/reports/download/<?php echo $item['id'];?>/checkoutItem" class="btn btn-primary check-in-download">Download Item Image <i class="fa fa-cloud-download"></i></a>
                                            <?php } else {?>
                                                <input <?php if($this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="file" name="item_<?php echo $item['id']?>" class = "form-control file-background filestyle" data-buttonText="Item Image" data-buttonBefore="true">
                                            <?php } ?>
                                        </div>

                                        <div class = "form-group col-sm-6">
                                            <?php if(isset($item['lord_image']) && !empty($item['lord_image'])){?>
                                                <img src="/image.php?width=90&height=90&image=/assets/uploads/<?php echo $item['lord_image']?>" alt="<?php echo $item['lord_image']?>">
                                                <a href="/reports/download/<?php echo $item['id'];?>/checkoutLordItem" class="btn btn-primary check-in-download">Download Item Image <i class="fa fa-cloud-download"></i></a>
                                            <?php } else {?>
                                                <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="file" name="lord_item_<?php echo $item['id']?>" class = "form-control file-background filestyle" data-buttonText="LL / Agent Approval Item Image" data-buttonBefore="true">
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class = "row">
                                        <div class = "form-group col-sm-6 right-border">
                                            <textarea <?php if($this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> id="items_tenant_comment_<?php echo $item['id']?>" name="rooms[<?php echo $room['id']?>][items][<?php echo $item['id']?>][tenant_comment]" class="form-control form-group-3-label" placeholder="Tenant Comment"><?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['rooms']['<?php echo $room["id"]?>']['items']['<?php echo $item["id"]?>']['tenant_comment']);} elseif(!empty($item['tenant_comment'])){echo $item['tenant_comment'];}?></textarea>
                                        </div>

                                        <div class = "form-group col-sm-6">
                                            <textarea <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> id="items_lord_comment_<?php echo $item['id']?>" name="rooms[<?php echo $room['id']?>][items][<?php echo $item['id']?>][lord_comment]" class="form-control form-group-3-label" placeholder="Landlord / Agent comment"><?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['rooms']['<?php echo $room["id"]?>']['items']['<?php echo $item["id"]?>']['lord_comment']);} elseif(!empty($item['lord_comment'])){echo $item['lord_comment'];}?></textarea>
                                        </div>
                                    </div>

                                    <div class = "row">
                                        <div class="form-group col-sm-6 right-border">
                                            <label class = "form-control form-group-3-label" style = "width:70%; float: left;">
                                                Tenant Item Status
                                            </label>
                                            <select <?php if($this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> data-id = "<?php echo $item['id']?>" id="tenant_approved_check_in_<?php echo $item['id']?>" name="rooms[<?php echo $room['id']?>][items][<?php echo $item['id']?>][tenant_approved]" class="form-control form-group-2-select tenant-item-approval">
                                                <?php foreach($this->item_status as $key3 => $type){?>
                                                    <option value="<?php echo $key3 ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['rooms'][$room['id']]['items'][$item['id']]['tenant_approved'] == $key3)) {echo 'selected="selected"';} elseif(!empty($item['tenant_approved']) && $item['tenant_approved'] == $key3){echo 'selected="selected"';}?> > <?php echo $type?></option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-sm-6">
                                            <label class = "form-control form-group-3-label" style = "width:70%; float: left;">
                                                LL / Agent Item Status
                                            </label>
                                            <select <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> data-id = "<?php echo $item['id']?>" id="lord_approved_check_in_<?php echo $item['id']?>" name="rooms[<?php echo $room['id']?>][items][<?php echo $item['id']?>][lord_approved]" class="form-control form-group-2-select lord-item-approval">
                                                <?php foreach($this->item_status as $key3 => $type){?>
                                                    <option value="<?php echo $key3 ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['rooms'][$room['id']]['items'][$item['id']]['lord_approved'] == $key3)) {echo 'selected="selected"';} elseif(!empty($item['lord_approved']) && $item['lord_approved'] == $key3){echo 'selected="selected"';}?> > <?php echo $type?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <?php $count++;?>
                            <?php } ?>
                        <?php } ?>
                    </div>
                <?php }?>
            <?php } ?>
            <input type = "hidden" name = "signature" id="signature-input">

            <div id="signature-pad" class="m-signature-pad" style="display:none;">
                <div class="m-signature-pad--body">
                    <canvas></canvas>
                </div>
                <div class="m-signature-pad--footer">
                    <div class="description">Sign name above</div>
                    <button type="button" class="button clear" data-action="clear">Clear</button>
                    <button type="button" class="button save" data-action="save">Save</button>
                </div>
            </div>

            <div class = "row">
                <div class = "col-sm-6">
                    <h1 class = "check-in-h1">Lead Tenant</h1>
                </div>

                <div class = "col-sm-6">
                    <h1 class = "check-in-h1">Landlord / Agent</h1>
                </div>
            </div>

            <div class = "row">
                <div class = "col-sm-6" style = "padding-right: 30px;">
                    <div class = "form-wrapper">
                        <div class="form-group-2 col-sm-12">
                            <label class = "form-control form-group-2-label">
                                Lead Tenant Approval
                            </label>
                            <select <?php if($this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID'] || $this->report[0]['tenant_approved_check_out'] == 1){echo 'disabled';}?> id="tenant_approved_check_out" name="tenant_approved_check_out" class="form-control form-group-2-select">
                                <?php foreach($this->YesNo as $key3 => $type){?>
                                    <option value="<?php echo $key3 ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['tenant_approved_check_out'] == $key3)) {echo 'selected="selected"';} elseif(!empty($this->report[0]['tenant_approved_check_out']) && $this->report[0]['tenant_approved_check_out'] == $key3){echo 'selected="selected"';}?> > <?php echo $type?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class = "col-sm-6">
                    <div class = "form-wrapper">
                        <div class="form-group-2 col-sm-12">
                            <label class = "form-control form-group-2-label">
                                Landlord / Agent
                            </label>
                            <select <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID'] || $this->report[0]['lord_approved_check_out'] == 1){echo 'disabled';}?> id="lord_approved_check_out" name="lord_approved_check_out" class="form-control form-group-2-select">
                                <?php foreach($this->YesNo as $key3 => $type){?>
                                    <option value="<?php echo $key3 ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['lord_approved_check_out'] == $key3)) {echo 'selected="selected"';} elseif(!empty($this->report[0]['lord_approved_check_out']) && $this->report[0]['lord_approved_check_out'] == $key3){echo 'selected="selected"';}?> > <?php echo $type?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group form-actions">
                <div class="col-sm-12 form-spacing" style="text-align:center">
                    <div class = "back-to-dash"><a href = "/users/dashboard/"><img src = "/assets/images/back-to-dash.png"/> <span>Back to dashboard</span></a></div>
                    <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID'] && $this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){?>
                        <input id = "other-tenant-sign" type="submit" name="save" class="formbtn btn-default loader" value="Sign">
                    <?php } else {?>
                        <input id = "save-check-out" type="submit" name="save" class="formbtn btn-default loader" value="Save">
                    <?php }?>
                </div>
            </div>

            <div id="page-cover"></div>
            <script src="/assets/js/signature_pad.js"></script>
        </form>
    </div>
</div>