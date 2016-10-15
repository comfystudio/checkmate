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
                    Forgot Password
                </div>
            </div>
            <div class = "row">
                <div class ="col-xs-12 welcome-message">
                    Please enter your email address to receive reset email.
                </div>
            </div>
        </div>

        <form class="full" action="" method="post" id="LoginForm">
            <div class = "form-wrapper">
                <div class = "row">
                    <div class="form-group col-sm-12 <?php if ((!empty($this->missing)) && in_array('email', $this->missing)) { echo 'error'; }?>">
                        <input type="email" class="form-control" id="email" placeholder="Email Address" name="email">
                    </div>
                </div>
            </div>
             <div class="col-sm-12 form-spacing" style="text-align:center">
                <button type="submit" class="formbtn btn-default">Reset Password</button>  
            </div>
        </form>
    </div>
</div>
