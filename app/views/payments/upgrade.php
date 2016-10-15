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
                    <?php if(isset($this->user[0]['payment_type']) && !empty($this->user[0]['payment_type'])){?>
                        Upgrade Membership
                    <?php }else{?>
                        Become a Member
                    <?php }?>
                </div>
            </div>
            <div class = "row">
                <div class ="col-xs-12 welcome-message">
                    Please carefully select the membership type you want and enter correct card details below.
                </div>
            </div>

            <form id="payment-form" method="post">
                <div class = "form-wrapper create-property">
                    <!-- Separate because Stripe validation appends directly to .payment-errors -->
                    <div id="payment_errors" class="alert alert alert-info alert-labeled formerror" style="display: none;">
                        <h4>
                            <strong>Failure</strong>
                        </h4>
                        <p class = "alert-body alert-body-right alert-labelled-cell">
                            <ul>
                                <li>
                                    <span class="payment-errors"></span>
                                </li>
                            </ul>
                        </p>
                    </div>

                    <div class = "row">
                        <div class = "form-group col-sm-6 right-border">
                            <p><img src="/assets/images/stripe-solid.png" alt="Powered By Stripe" /></p>
                        </div>
                    </div>

                    <div class = "row">
                        <?php if(!isset($this->user[0]['payment_type']) || $this->user[0]['payment_type'] < 1){?>
                            <div class = "form-group col-sm-6 right-border">
                                <input class="form-control" type="radio" name="type" value="1" <?php if(isset($this->user[0]['payment_type']) && $this->user[0]['payment_type'] == 1){echo 'checked="checked"';}?> >
                                <label class="form-control form-group-4-label border-none">Pay & Go - £20 a year for one property</label>
                            </div>
                        <?php } ?>
                    </div>

                    <div class = "row">
                        <?php if(!isset($this->user[0]['payment_type']) || $this->user[0]['payment_type'] < 2){?>
                            <div class = "form-group col-sm-6 right-border">
                                <input class="form-control" type="radio" name="type" value="2" <?php if(isset($this->user[0]['payment_type']) && $this->user[0]['payment_type'] == 2){echo 'checked="checked"';}else{echo 'checked="checked"';}?>>
                                <label class="form-control form-group-4-label border-none">Bronze - £25 a month for 50 properties</label>
                            </div>
                        <?php } ?>
                    </div>


                    <div class = "row">
                        <?php if(!isset($this->user[0]['payment_type']) || $this->user[0]['payment_type'] < 3){?>
                            <div class = "form-group col-sm-6 right-border">
                                <input class="form-control" type="radio" name="type" value="3" <?php if(isset($this->user[0]['payment_type']) && $this->user[0]['payment_type'] == 3){echo 'checked="checked"';}?> >
                                <label class="form-control form-group-4-label border-none">Silver - £45 a month for 100 properties</label>
                            </div>
                        <?php } ?>
                    </div>

                    <div class = "row">
                        <?php if(!isset($this->user[0]['payment_type']) || $this->user[0]['payment_type'] < 4){?>
                            <div class = "form-group col-sm-6 right-border">
                                <input class="form-control" type="radio" name="type" value="4" <?php if(isset($this->user[0]['payment_type']) && $this->user[0]['payment_type'] == 4){echo 'checked="checked"';}?> >
                                <label class="form-control form-group-4-label border-none">Gold - £60 a month for 200 properties</label>
                            </div>
                        <?php } ?>
                    </div>

                    <div class = "row">
                        <?php if(!isset($this->user[0]['payment_type']) || $this->user[0]['payment_type'] < 5){?>
                            <div class = "form-group col-sm-6 right-border">
                                <input class="form-control" type="radio" name="type" value="5" <?php if(isset($this->user[0]['payment_type']) && $this->user[0]['payment_type'] == 5){echo 'checked="checked"';}?> >
                                <label class="form-control form-group-4-label border-none">Platinum - £80 a month for 400 properties</label>
                            </div>
                        <?php } ?>
                    </div>

                    <input type = "hidden" data-stripe="email" name = "stripeEmail" value = "<?php echo $this->user[0]['email']?>">

                    <div class = "row">
                        <div class="form-group col-sm-6 right-border">
                            <input class="form-control" type="text" data-stripe="name" id="cardholder" placeholder="Cardholder's Name" value=""  />
                        </div><!-- end form_item -->

                        <div class="form-group col-sm-6">
                            <input class="form-control" type="text" size="20" data-stripe="number" id="card_number" placeholder="Card Number" value=""  />
                        </div><!-- end form_item -->
                    </div>


                    <div class = "row">
                        <div class="form-group col-sm-6 right-border">
                            <label for="expiry" class = "form-control form-group-2-label width-30">Expiry:</label>
                            <input class="form-control width-35 float-left"  size="2" data-stripe="exp-month" id="expiry_month" class="month" placeholder="MM" value=""  />
                            <input class="form-control width-35" size="4" data-stripe="exp-year" id="expiry_year" class="year" placeholder="YYYY" value=""  />
                        </div><!-- end form_item -->

                        <div class="form-group col-sm-6">
                            <input class="form-control" type="text" size="4" data-stripe="cvc" id="cvc" placeholder="CVC" value=""  />
                        </div><!-- end form_item -->
                    </div>

                    <div class = "row">
                        <div class = "form-group col-sm-12">
                            <div class="card_icons">
                                <ul>
                                    <li><img src="/assets/images/mastercard.png" alt="Mastercard" /></li>
                                    <li><img src="/assets/images/visa.png" alt="Visa" /></li>
                                    <li><img src="/assets/images/amex.png" alt="American Express" /></li>
                                </ul>
                            </div>
                        </div>
                    </div>

<!--                    <div class="proceed_btn">-->
<!--                        <button type="submit" class="btn duckegg solid loader">Pay Now</button>-->
<!--                    </div>-->
                </div>
                <div class="col-sm-12 form-spacing" style="text-align:center">
                    <div class = "back-to-dash"><a href = "/users/dashboard/"><img src = "/assets/images/back-to-dash.png"/> <span>Back to dashboard</span></a></div>
                    <button type="submit" class="formbtn btn-default" name="save" value = "save">Pay Now</button>
                </div>
            </form>
        </div><!-- formintro -->
    </div><!-- Container -->
</div><!-- Greyback -->
