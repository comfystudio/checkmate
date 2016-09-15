<script src='https://www.google.com/recaptcha/api.js'></script>
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
                    Create an Account
                </div>
            </div>
            <div class = "row">
                <div class ="col-xs-12 welcome-message">
                	<?php if (isset($this->userType) && $this->userType == 1){?>
                    	Welcome new Letting Agent. Pleae register your details below
                	<?php }else{ ?>
                    	Welcome new Tenant. Pleae register your details below
                	<?php } ?>
                </div>
            </div>
        </div>

        <form class="full" action="" method="post" enctype="multipart/form-data">
            <div class="col-sm-offset-2">
                <div class="form-group col-sm-5 <?php if ((!empty($this->missing)) && in_array('firstname', $this->missing)) { echo 'error'; }?>">
                    <input type="name" class="form-control" id="firstname" placeholder="First name" name = "firstname" value="<?php echo isset($_POST['firstname']) ? $_POST['firstname'] : ''; ?>">
                </div>
                <div class="form-group col-sm-5 <?php if ((!empty($this->missing)) && in_array('surname', $this->missing)) { echo 'error'; }?>">
                    <input type="name" class="form-control" id="name" placeholder="Last name" name = "surname" value="<?php echo isset($_POST['surname']) ? $_POST['surname'] : ''; ?>">
                </div>

                <div class="form-group col-sm-5 <?php if ((!empty($this->missing)) && in_array('email', $this->missing)) { echo 'error'; }?>">
                    <input type="email" class="form-control" id="email" placeholder="Email Address" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                </div>


                <div class="form-group col-sm-5 <?php if ((!empty($this->missing)) && in_array('password', $this->missing)) { echo 'error'; }?>">
                    <input type="password" class="form-control" id="password" placeholder="Password" name="password" value="<?php echo isset($_POST['password']) ? $_POST['password'] : ''; ?>">
                </div>

                <div class="form-group col-sm-5 <?php if ((!empty($this->missing)) && in_array('confirm_password', $this->missing)) { echo 'error'; }?>">
                    <input type="password" class="form-control" id="confirm_password" placeholder="Confirm Password" name="confirm_password" value="<?php echo isset($_POST['confirm_password']) ? $_POST['confirm_password'] : ''; ?>">
                </div>

                <div class="form-group col-sm-5 <?php if ((!empty($this->missing)) && in_array('contact_num', $this->missing)) { echo 'error'; }?>">
                    <input type="text" class="form-control" id="contact_num" placeholder="Contact Number" name="contact_num" value="<?php echo isset($_POST['contact_num']) ? $_POST['contact_num'] : ''; ?>">
                </div>

            	<?php if (isset($this->userType) && $this->userType == 1){?>
	                <div class="form-group col-sm-5">
	                    <input type="file" class="form-control" name="logo_image" id="logo_image">
	            	</div>
            	<?php } ?>

                <div class="col-sm-5 g-recaptcha <?php if ((!empty($this->missing)) && in_array('captcha', $this->missing)) { echo 'error'; }?>" data-sitekey="6LceKioTAAAAAGMIgGPIR25MLHHQvEcubqHVBk6a"></div>
            </div>
             <div class="col-sm-12 form-spacing" style="text-align:center">
                <button type="submit" class="formbtn btn-default">Register</button>
                
                <div class="lostpassword">
                    <a href="/users/login/">Log In </a>/<a href="/users/forgot-password/"> Lost Password</a>
                </div>    
            </div>
        </form>
    </div>
</div>