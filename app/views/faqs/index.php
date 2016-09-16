<div class="greyback">
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
            <div class = "row">
                <div class ="col-xs-12 welcome-message">
                </div>
            </div>

            <?php foreach($this->data as $data){?>
                <div class = "row">
                    <div class ="col-sm-offset-2">
                        <?php echo $this->data[0]['question']?>
                    </div>
                    <div class ="col-sm-offset-2">
                        <?php echo $this->data[0]['answer']?>
                    </div>
                    <br/>
                    <br/>
                </div>
            <?php } ?>
        </div>

    </div>
</div>
