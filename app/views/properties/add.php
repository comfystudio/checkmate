<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.min.css">

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
                    <?php if(isset($this->stored_data) && !empty($this->stored_data)){?>
                        Edit Property
                    <?php }else {?>
                        Create Property
                    <?php } ?>
                </div>
            </div>
            <div class = "row">
                <div class ="col-xs-12 welcome-message">
                    Enter property details below.
                </div>
            </div>
        </div>

        <form class="full" action="" method="post" enctype="multipart/form-data">                
                <div class = "form-wrapper" style = "overflow: visible;">
                    <div class = "row">
                        <?php if(isset($this->templates) && !empty($this->templates)){?>
                            <div class="form-group col-sm-6 right-border <?php if ((!empty($this->missing)) && in_array('template_id', $this->missing)) { echo 'error'; }?>">
                                <select id="template_id" name="template_id" class="form-control selectpicker" data-live-search="true" data-dropup-auto="false">
                                    <?php foreach($this->templates as $key => $template){?>
                                        <option value="<?php echo $template['id'] ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['template_id'] == $template['id'])) {echo 'selected="selected"';} elseif(!empty($this->stored_data['template_id']) && $this->stored_data['template_id'] == $key){echo 'selected="selected"';}?> > <?php echo $template['title']?></option>
                                    <?php } ?>
                                </select>                
                            </div>
                        <?php } ?>

<!--                        <div class="form-group col-sm-6 --><?php //if ((!empty($this->error)) && array_key_exists('title', $this->error)) { echo 'has-error'; }?><!--">-->
<!--                            <input type="name" class="form-control" id="title" placeholder="Title" name = "title" value="--><?php //if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['title']);} elseif(!empty($this->stored_data['title'])){echo $this->stored_data['title'];}?><!--">-->
<!--                        </div>-->
                    </div>

                    <div class = "row">
                        <div class="form-group col-sm-6 right-border <?php if ((!empty($this->error)) && array_key_exists('house_number', $this->error)) { echo 'has-error'; }?>">
                            <input type="name" class="form-control" id="house_number" placeholder="House Number" name = "house_number" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['house_number']);} elseif(!empty($this->stored_data['house_number'])){echo $this->stored_data['house_number'];}?>">
                        </div>
                    
                        <div class="form-group col-sm-6 <?php if ((!empty($this->error)) && array_key_exists('address_1', $this->error)) { echo 'has-error'; }?>">
                            <input type="name" class="form-control" id="address_1" placeholder="Address Line 1" name = "address_1" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['address_1']);} elseif(!empty($this->stored_data['address_1'])){echo $this->stored_data['address_1'];}?>">
                        </div>
                    </div>

                    <div class = "row">
                        <div class="form-group col-sm-6  right-border <?php if ((!empty($this->error)) && array_key_exists('address_2', $this->error)) { echo 'has-error'; }?>">
                            <input type="name" class="form-control" id="address_2" placeholder="Address Line 2" name = "address_2" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['address_2']);} elseif(!empty($this->stored_data['address_2'])){echo $this->stored_data['address_2'];}?>">
                        </div>

                    
                        <div class="form-group col-sm-6 <?php if ((!empty($this->error)) && array_key_exists('address_3', $this->error)) { echo 'has-error'; }?>">
                            <input type="name" class="form-control" id="address_3" placeholder="Address Line 3" name = "address_3" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['address_3']);} elseif(!empty($this->stored_data['address_3'])){echo $this->stored_data['address_3'];}?>">
                        </div>
                    </div>

                    <div class = "row">
<!--                        <div class="form-group col-sm-6 right-border --><?php //if ((!empty($this->error)) && array_key_exists('address_4', $this->error)) { echo 'has-error'; }?><!--">-->
<!--                            <input type="name" class="form-control" id="address_4" placeholder="Address Line 4" name = "address_4" value="--><?php //if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['address_4']);} elseif(!empty($this->stored_data['address_4'])){echo $this->stored_data['address_4'];}?><!--">-->
<!--                        </div>-->

                        <div class="form-group col-sm-6 right-border <?php if ((!empty($this->error)) && array_key_exists('postcode', $this->error)) { echo 'has-error'; }?>">
                            <input type="name" class="form-control" id="postcode" placeholder="Postcode" name = "postcode" value="<?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['postcode']);} elseif(!empty($this->stored_data['postcode'])){echo $this->stored_data['postcode'];}?>">
                        </div>
                    </div>
                    
                    <div class = "row">
                        <div class="form-group col-sm-6 right-border">
                            <input type="file" class="form-control filestyle" data-buttonText="Property Image" data-buttonBefore="true" name="image" id="image">
                        </div>
                    </div>
                </div>

            <div class="col-sm-12 form-spacing" style="text-align:center">
                <div class = "back-to-dash"><a href = "/users/dashboard/"><img src = "/assets/images/back-to-dash.png"/> <span>Back to dashboard</span></a></div>
                <button type="submit" class="formbtn btn-default" name="save" value = "save">Save Property</button>
<!--                 <button type="submit" class="formbtn btn-default" name="cancel" value = "Cancel">Cancel</button>
 -->            </div>
        </form>
    </div>
</div>
