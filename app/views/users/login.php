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
                    Login
                </div>
            </div>
            <div class = "row">
                <div class ="col-xs-12 welcome-message">
                    Welcome Back. Please login to access your account
                </div>
            </div>
        </div>

        <form class="full" action="/users/login" method="post" id="LoginForm">
            <div class="form-wrapper">
                <div class = "row">
                    <div class="form-group col-sm-6 right-border">
                        <input type="email" class="form-control" id="email" placeholder="Email Address" name="email">
                    </div>

                    <div class="form-group col-sm-6">
                        <input type="password" class="form-control" id="pwd" placeholder="Password" name="password">
                    </div>
                </div>
            </div>
             <div class="col-sm-12 form-spacing" style="text-align:center">
                <button type="submit" class="formbtn btn-default">Login</button>
                
                <div class="lostpassword">
                    <a href="/users/register/">Register </a>/ <a href="/users/forgot-password">Lost Password</a>
                </div>   
            </div>
        </form>
    </div>
</div>
