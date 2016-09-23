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

<?php $action  = $this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID'] && $this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID'] ? '/reports/sign/'.$this->report[0]['id'].'/checkout' : ''?>
<form id="form" action="<?php echo $action?>" method="post" class="form-horizontal form-bordered" enctype="multipart/form-data">
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
                        <option value="<?php echo $key ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['meter_type'] == $key)) {echo 'selected="selected"';} elseif(!empty($this->report[0]['meter_type']) && $this->report[0]['meter_type'] == $key){echo 'selected="selected"';}?> > <?php echo $meter_type?></option>
                    <?php } ?>
                </select>            
            </div>

            <div class = "col-md-offset-3 col-md-3">
                Meter Reading
            </div>
            <div class = "col-md-3">
                <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID'] && $this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="text" id="meter_reading" name="meter_reading" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['meter_reading']);} elseif(!empty($this->report[0]['meter_reading'])){echo $this->report[0]['meter_reading'];}?>">
            </div>

            <div class = "col-md-offset-3 col-md-3">
                Meter Measurement Type
            </div>
            <div class = "col-md-3">
                <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID'] && $this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="text" id="meter_measurement" name="meter_measurement" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['meter_measurement']);} elseif(!empty($this->report[0]['meter_measurement'])){echo $this->report[0]['meter_measurement'];}?>">
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

            <div class = "col-md-offset-3 col-md-3">
                Fire Extinguishers
            </div>
            <div class = "col-md-3">
                <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="text" id="fire_extin" name="fire_extin" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['fire_extin']);} elseif(!empty($this->report[0]['fire_extin'])){echo $this->report[0]['fire_extin'];}?>">
            </div>

            <div class = "col-md-offset-3 col-md-3">
                Fire Blankets
            </div>
            <div class = "col-md-3">
                <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="text" id="fire_blanket" name="fire_blanket" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['fire_blanket']);} elseif(!empty($this->report[0]['fire_blanket'])){echo $this->report[0]['fire_blanket'];}?>">
            </div>

            <div class = "col-md-offset-3 col-md-3">
                Smoke Alarms
            </div>
            <div class = "col-md-3">
                <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="text" id="smoke_alarm" name="smoke_alarm" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['smoke_alarm']);} elseif(!empty($this->report[0]['smoke_alarm'])){echo $this->report[0]['smoke_alarm'];}?>">
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
                <select <?php if($this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID'] || $this->report[0]['tenant_approved_check_out'] == 1){echo 'disabled';}?> id="tenant_approved_check_out" name="tenant_approved_check_out" class="form-control">
                    <?php foreach($this->YesNo as $key3 => $type){?>
                        <option value="<?php echo $key3 ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['tenant_approved_check_out'] == $key3)) {echo 'selected="selected"';} elseif(!empty($this->report[0]['tenant_approved_check_out']) && $this->report[0]['tenant_approved_check_out'] == $key3){echo 'selected="selected"';}?> > <?php echo $type?></option>
                    <?php } ?>
                </select>
            </div>

            <div class = "col-md-3">
                Landlord / Letting Agent Approval
            </div>

            <div class = "col-md-3">
                <select <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID'] || $this->report[0]['lord_approved_check_out'] == 1){echo 'disabled';}?> id="lord_approved_check_out" name="lord_approved_check_out" class="form-control">
                    <?php foreach($this->YesNo as $key3 => $type){?>
                        <option value="<?php echo $key3 ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['lord_approved_check_out'] == $key3)) {echo 'selected="selected"';} elseif(!empty($this->report[0]['lord_approved_check_out']) && $this->report[0]['lord_approved_check_out'] == $key3){echo 'selected="selected"';}?> > <?php echo $type?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <br/>
        <br/>

        <?php if(isset($this->checkOutData) && !empty($this->checkOutData)){?>
            <?php foreach($this->checkOutData as $key => $room){?>
                <div class = "row">
                    <div class = "col-md-offset-3 col-md-3">
                        Room Name
                    </div>
                    <div class = "col-md-3">
                        <?php echo $room['name'];?>
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
                        <input <?php if($this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="text" id="rooms_tenant_comment_<?php echo $key?>" name="rooms[<?php echo $room['id']?>][tenant_comment]" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['rooms']['<?php echo $room["id"]?>']['tenant_comment']);} elseif(!empty($room['tenant_comment'])){echo $room['tenant_comment'];}?>">
                    </div>

                    <div class = "col-md-3">
                        Landlord / Letting Agent Comment
                    </div>

                    <div class = "col-md-3">
                        <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="text" id="rooms_lord_comment_<?php echo $key?>" name="rooms[<?php echo $room['id']?>][lord_comment]" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['rooms']['<?php echo $room["id"]?>']['lord_comment']);} elseif(!empty($room['lord_comment'])){echo $room['lord_comment'];}?>">
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

                                <?php echo $this->status[$item['status']]?>        
                            </div>
                        </div>
                        

                        <?php if(isset($item['image']) && !empty($item['image'])){?>
                            <div class= "row">
                                <div class = "col-md-3">
                                    Item Image
                                </div>
                                <div class = "col-md-3">
                                    <img src="/image.php?width=120&height=120&image=/assets/uploads/<?php echo $item['image']?>" alt="<?php echo $item['image']?>">
                                    <a href="/reports/download/<?php echo $item['id'];?>/checkoutItem" class="btn btn-primary">Download Item Image<i class="fa fa-cloud-download"></i></a>
                                </div>
                            </div>
                        <?php } else {?>
                            <div class = "row">
                                <div class = "col-md-3">
                                    Upload Item Image
                                </div>
                                <div class = "col-md-3">
                                    <input <?php if($this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="file" name="item_<?php echo $item['id']?>">
                                </div>
                            </div>
                        <?php } ?>
                        <div class = "row">
                            <div class = "col-md-3">
                                Lead Tenant Comment
                            </div>

                            <div class = "col-md-3">
                                <input <?php if($this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="text" id="items_tenant_comment_<?php echo $key2?>" name="rooms[<?php echo $room['id']?>][items][<?php echo $item['id']?>][tenant_comment]" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['rooms']['<?php echo $room["id"]?>']['items']['<?php echo $item["id"]?>']['tenant_comment']);} elseif(!empty($item['tenant_comment'])){echo $item['tenant_comment'];}?>">
                            </div>

                            <div class = "col-md-3">
                                Landlord / Letting Agent Comment
                            </div>

                            <div class = "col-md-3">
                                <input <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> type="text" id="items_lord_comment_<?php echo $key2?>" name="rooms[<?php echo $room['id']?>][items][<?php echo $item['id']?>][lord_comment]" class="form-control" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['rooms']['<?php echo $room["id"]?>']['items']['<?php echo $item["id"]?>']['lord_comment']);} elseif(!empty($item['lord_comment'])){echo $item['lord_comment'];}?>">
                            </div>
                        </div>

                        <div class = "row">
                            <div class = "col-md-3">
                                Lead Tenant Approval
                            </div>

                            <div class = "col-md-3">
                                <select <?php if($this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> id="tenant_approved_check_in_<?php echo $key2?>" name="rooms[<?php echo $room['id']?>][items][<?php echo $item['id']?>][tenant_approved]" class="form-control">
                                    <?php foreach($this->YesNo as $key3 => $type){?>
                                        <option value="<?php echo $key3 ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['rooms'][$room['id']]['items'][$item['id']]['tenant_approved'] == $key3)) {echo 'selected="selected"';} elseif(!empty($item['tenant_approved']) && $item['tenant_approved'] == $key3){echo 'selected="selected"';}?> > <?php echo $type?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class = "col-md-3">
                                Landlord / Letting Agent Approval
                            </div>

                            <div class = "col-md-3">
                                <select <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID']){echo 'disabled';}?> id="lord_approved_check_in_<?php echo $key2?>" name="rooms[<?php echo $room['id']?>][items][<?php echo $item['id']?>][lord_approved]" class="form-control">
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

    <div class="form-group form-actions">
        <div class="col-md-5 col-md-offset-2">
            <?php if($this->report[0]['lord_id'] != $_SESSION['UserCurrentUserID'] && $this->report[0]['lead_tenant_id'] != $_SESSION['UserCurrentUserID']){?>
                <input id = "other-tenant-sign" type="submit" name="save" class="btn btn-effect-ripple btn-primary loader" value="Sign">
            <?php } else {?>
                <input id = "save-check-out" type="submit" name="save" class="btn btn-effect-ripple btn-primary loader" value="Save">
            <?php } ?>
            <input type="submit" name="cancel" class="btn btn-effect-ripple btn-danger loader" value="Cancel">
        </div>
    </div>
</form>

<div id="page-cover"></div>
<script src="/assets/js/signature_pad.js"></script>