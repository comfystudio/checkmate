<div class="content_left">
    <div class="post_item">
        <div class="white_area">
            <h2 class="floatleft"><?php if(isset($this->stored_id)){echo "Edit a user"; }else{ echo "Add a new user";}?></h2>
            <div class="form_surround">
                <form action="" method="post" class="form-horizontal form-bordered">
                    <input type="hidden" name="stored_password" value="<?php if(!empty($this->stored_password)){echo $this->stored_password;} ?>" />
			        <input type="hidden" name="stored_salt" value="<?php if(!empty($this->stored_salt)){echo $this->stored_salt;} ?>" />

                    <!--FIRST NAME-->
                    <div class="form_item">
                        <label class ="<?php if ((!empty($this->missing)) && in_array('firstname', $this->missing)) { echo 'error'; }?>">
                            First Name <span class="red">*</span>
                        </label>
                        <input class = "<?php if ((!empty($this->missing)) && in_array('firstname', $this->missing)) { echo 'error'; }?>"
                           value = "<?php if ((!empty($this->missing)) || (!empty($this->error))) {
                               echo Formatting::utf8_htmlentities($_POST['firstname']);
                           } elseif(!empty($this->stored_firstname)){
                               echo $this->stored_firstname;
                           }?>"type="text" name="firstname"
                        >
                    </div>

                    <!--SURNAME-->
                    <div class="form_item">
                        <label class ="<?php if ((!empty($this->missing)) && in_array('surname', $this->missing)) { echo 'error'; }?>">
                            Surname <span class="red">*</span>
                        </label>
                        <input class = "<?php if ((!empty($this->missing)) && in_array('surname', $this->missing)) { echo 'error'; }?>"
                           value = "<?php if ((!empty($this->missing)) || (!empty($this->error))) {
                               echo Formatting::utf8_htmlentities($_POST['surname']);
                           } elseif(!empty($this->stored_surname)){
                               echo $this->stored_surname;
                           }?>"type="text" name="surname"
                        >
                    </div>

                    <!--EMAIL-->
                    <div class="form_item">
                        <label class ="<?php if ((!empty($this->missing)) && in_array('email', $this->missing)) { echo 'error'; }?>">
                            Email <span class="red">*</span>
                        </label>
                        <input class = "<?php if ((!empty($this->missing)) && in_array('email', $this->missing)) { echo 'error'; }?>"
                           value = "<?php if ((!empty($this->missing)) || (!empty($this->error))) {
                               echo Formatting::utf8_htmlentities($_POST['email']);
                           } elseif(!empty($this->stored_email)){
                               echo $this->stored_email;
                           }?>"type="text" name="email"
                        >
                    </div>

                    <!--Password-->
                    <div class="form_item">
                        <label class ="<?php if ((!empty($this->missing)) && in_array('password', $this->missing)) { echo 'error'; }?>">
                            Password <?php if(isset($this->stored_id)){ }else{ echo "<span class = 'red'>*</span>";}?>
                        </label>
                        <input id = "password" class = "<?php if ((!empty($this->missing)) && in_array('password', $this->missing)) { echo 'error'; }?>"
                           type="password" name="password"
                        >
                        <span class = "help-block">Note: Password must contain at least one number, one uppercase letter and at least 8 characters.</span>
                    </div>

                    <!--GENERATE PASSWORD-->
                    <div class="form_item">
                        <div class="checkbox">
                            <label for="generate_password">Generate</label>
                        </div>
                        <input type="checkbox" id="generate_password" name="generate_password" value="1" /><span id="generated_password"></span>
                    </div>

                    <!--Confirm Password-->
                    <div class="form_item">
                        <label class ="<?php if ((!empty($this->missing)) && in_array('password_again', $this->missing)) { echo 'error'; }?>">
                            Confirm Password <?php if(isset($this->stored_id)){ }else{ echo "<span class = 'red'>*</span>";}?>
                        </label>
                        <input id = "password_again" class = "<?php if ((!empty($this->missing)) && in_array('password_again', $this->missing)) { echo 'error'; }?>"
                           type="password" name="password_again"
                        >
                    </div>

                    <!--Is_Active-->
                    <div class="form_item">
                        <label>Is Active</label>
                        <div class="switch_surround">
                            <div class="onoffswitch">
                                <input type="hidden" name="is_active" id="" value="0">
                                <input type="checkbox" name="is_active" class="onoffswitch-checkbox" id="myonoffswitch" <?php if((!empty($_POST['is_active']) && $_POST['is_active'] != 0)  || (!empty($this->stored_is_active) && $this->stored_is_active != 0) || (!isset($this->stored_id))) {echo 'checked="checked"';}?>>
<!--                                <label class="onoffswitch-label" for="myonoffswitch"></label>-->
                            </div>
                        </div>
                    </div>

                    <input type="submit" class="submitbtn" name="save" value = "Save">
                    <input type="submit" class="submitbtn cancel" name="cancel" value = "Cancel">

                </form>
            </div>
        </div><!--white_area-->
    </div><!--post_item-->
</div><!--content_left-->
