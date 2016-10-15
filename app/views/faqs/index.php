<div class="">
    <div class ="container">
        <div class="formintro">
            <div class = "row front-content">
                <div class = "col-md-offset-4 col-md-4 ">
                    <img src="/assets/images/logo-small.png" alt ="Check mate small logo" class = "logo-small">
                </div>
                <div class = "col-md-offset-4">
                </div>
            </div>

            <div class = "row">
                <div class = "col-md-offset-4 col-md-4 strapline-header">
                    FAQS
                </div>
            </div>

            <div class = "form-wrapper pages-padding">
                <div class = "row">
                    <div class ="col-xs-12 welcome-message" style="padding-bottom: 0;">
                        <?php foreach($this->data as $data){?>
                            <div class = "row">
                                <p class = "faq-question"><strong><?php echo $this->data[0]['question']?></strong></p>
                                <p class = "faq-answer"><?php echo $this->data[0]['answer']?></p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 form-spacing" style="text-align:center">
                <div class = "back-to-dash"><a href = "/"><img src = "/assets/images/back-to-dash.png"/> <span>Back</span></a></div>
            </div>
        </div>
    </div>
</div>
