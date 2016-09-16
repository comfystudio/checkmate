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
                    Edit Account
                </div>
            </div>
            <div class = "row">
                <div class ="col-xs-12 welcome-message">
                    Make any desired changes below.
                </div>
            </div>
        </div>

        <form class="full" action="" method="post" enctype="multipart/form-data">
            <div class="col-sm-offset-2">
                <div class="form-group col-sm-5 <?php if ((!empty($this->missing)) && in_array('firstname', $this->missing)) { echo 'error'; }?>">
                    <input type="name" class="form-control" id="firstname" placeholder="First name" name = "firstname" value="<?php if ((!empty($this->error))) { echo Formatting::utf8_htmlentities($_POST['firstname']);} elseif(!empty($this->stored_data['firstname'])){echo $this->stored_data['firstname'];}?>">
                </div>
                <div class="form-group col-sm-5 <?php if ((!empty($this->missing)) && in_array('surname', $this->missing)) { echo 'error'; }?>">
                    <input type="name" class="form-control" id="name" placeholder="Last name" name = "surname" value="<?php if ((!empty($this->error))) { echo Formatting::utf8_htmlentities($_POST['surname']);} elseif(!empty($this->stored_data['surname'])){echo $this->stored_data['surname'];}?>">
                </div>

                <div class="form-group col-sm-5 <?php if ((!empty($this->missing)) && in_array('email', $this->missing)) { echo 'error'; }?>">
                    <input type="email" class="form-control" id="email" placeholder="Email Address" name="email" value="<?php if ((!empty($this->error))) { echo Formatting::utf8_htmlentities($_POST['email']);} elseif(!empty($this->stored_data['email'])){echo $this->stored_data['email'];}?>">
                </div>


                <div class="form-group col-sm-5 <?php if ((!empty($this->missing)) && in_array('password', $this->missing)) { echo 'error'; }?>">
                    <input type="password" class="form-control" id="password" placeholder="Password" name="password" value="<?php echo isset($_POST['password']) ? $_POST['password'] : ''; ?>">
                </div>

                <div class="form-group col-sm-5 <?php if ((!empty($this->missing)) && in_array('password_again', $this->missing)) { echo 'error'; }?>">
                    <input type="password" class="form-control" id="password_again" placeholder="Confirm Password" name="password_again" value="<?php echo isset($_POST['password_again']) ? $_POST['password_again'] : ''; ?>">
                </div>

                <div class="form-group col-sm-5 <?php if ((!empty($this->missing)) && in_array('contact_num', $this->missing)) { echo 'error'; }?>">
                    <input type="text" class="form-control" id="contact_num" placeholder="Contact Number" name="contact_num" value="<?php if ((!empty($this->error))) { echo Formatting::utf8_htmlentities($_POST['contact_num']);} elseif(!empty($this->stored_data['contact_num'])){echo $this->stored_data['contact_num'];}?>">
                </div>

                <input type="hidden" name="is_active" value="1">


                <div class="form-group col-sm-5 <?php if ((!empty($this->missing)) && in_array('type', $this->missing)) { echo 'error'; }?>">
                    <select id="type" name="type" class="form-control">
                        <?php foreach($this->userTypes as $key => $type){?>
                            <option value="<?php echo $key ?>" <?php if ((!empty($this->missing) || !empty($this->error)) && ($_POST['type'] == $key)) {echo 'selected="selected"';} elseif(!empty($this->stored_data['type']) && $this->stored_data['type'] == $key){echo 'selected="selected"';}?> > <?php echo $type?></option>
                        <?php } ?>
                    </select>                
                </div>

                <?php if (isset( $this->stored_data['logo_image']) && !empty( $this->stored_data['logo_image'])){?>
                    <div class="form-group col-sm-5">
                        <input type="file" class="form-control" name="logo_image" id="logo_image">
                    </div>
                <?php } ?>

            </div>
             <div class="col-sm-12 form-spacing" style="text-align:center">
                <button type="submit" class="formbtn btn-default" name="save" value = "save">Update</button>
                <button type="submit" class="formbtn btn-default" name="cancel" value = "Cancel">Cancel</button>
            </div>
        </form>
    </div>
</div>
