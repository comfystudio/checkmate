<div class="">
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
                    Contact Us
                </div>
            </div>

            <div class = "form-wrapper pages-padding">
                <div class = "row">
                    <div class ="col-xs-12 welcome-message">
                        <img src = "/assets/uploads/<?php echo $this->data[0]['image']?>" alt = "<?php echo $this->data[0]['image']?>"/>
                    </div>
                </div>

                <div class = "row">
                    <div class ="col-xs-12 welcome-message">
                        <p><?php echo $this->data[0]['text']?></p>

                        <?php if(isset($this->data[0]['location']) && !empty($this->data[0]['location'])){?>
                            <p><strong>Address</strong></p>
                            <p><?php echo $this->data[0]['location']?></p>
                        <?php } ?>

                        <p><strong>Information Email</strong></p>
                        <p><a href = "mailto:<?php echo SITE_EMAIL ?>"><?php echo SITE_EMAIL;?></a></p>

                        <p><strong>Support Email</strong></p>
                        <p><a href = "mailto:support@checkmatedeposit.com">support@checkmatedeposit.com</a></p>

                        <?php if(isset($this->data[0]['phone']) && !empty($this->data[0]['phone'])){?>
                            <p><strong>Contact Number</strong></p>
                            <p><a href = "tel:<?php echo $this->data[0]['phone']?>"><?php echo $this->data[0]['phone']?></a></p>
                        <?php } ?>

                        <?php if(isset($this->data[0]['phone_2']) && !empty($this->data[0]['phone_2'])){?>
                            <p><strong>Contact Number Two</strong></p>
                            <p><a href = "tel:<?php echo $this->data[0]['phone_2']?>"><?php echo $this->data[0]['phone_2']?></a></p>
                        <?php } ?>
                    </div>
                </div>

                <div class = "row">
                    <div class = "col-md-offset-4 col-md-4 strapline-header">
                        Have A Question?
                    </div>
                </div>

                <form class="full" action="" method="post">
                    <div class = "form-wrapper create-property">
                        <div class = "row">
                            <div class="form-group col-sm-6 right-border <?php if ((!empty($this->error)) && array_key_exists('name', $this->error)){echo 'has-error';}?>">
                                <input type="text" class="form-control" id="name" placeholder="Name" name="name" value="<?php if ((!empty($this->missing)) || (!empty($this->error))) { echo Formatting::utf8_htmlentities($_POST['name']);}elseif(!empty($this->stored_data['name'])){echo $this->stored_data['name'];}?>">
                            </div>


                            <div class="form-group col-sm-6 <?php if ((!empty($this->error)) && array_key_exists('email', $this->error)){echo 'has-error';}?>">
                                <input type="email" id="email" name="email" class="form-control " placeholder="Email" value="<?php if ((!empty($this->missing)) || (!empty($this->error))) { echo Formatting::utf8_htmlentities($_POST['email']);}elseif(!empty($this->stored_data['email'])){echo $this->stored_data['email'];}?>">
                            </div>
                        </div>

                        <div class = "row">
                            <div class="form-group col-sm-12 <?php if ((!empty($this->error)) && array_key_exists('question', $this->error)) { echo 'has-error'; }?>">
                                <textarea rows = "3" id="question" name="question" placeholder="Question" class="form-control"><?php if (!empty($this->error)) { echo Formatting::utf8_htmlentities($_POST['question']);} elseif(!empty($this->stored_data['question'])){echo $this->stored_data['question'];}?></textarea>
                            </div>
                        </div>
                    </div>

            </div>
                <div class="col-sm-12 form-spacing" style="text-align:center">
                    <div class = "back-to-dash"><a href = "/"><img src = "/assets/images/back-to-dash.png"/> <span>Back</span></a></div>
                    <button type="submit" class="formbtn btn-default" name="save" value = "save">Send Question</button>
                </div>
            </form>

            <div class="col-sm-12 form-spacing" style="text-align:center">
<!--                <div class = "back-to-dash"><a href = "/"><img src = "/assets/images/back-to-dash.png"/> <span>Back</span></a></div>-->
            </div>
        </div>
    </div>
</div>
