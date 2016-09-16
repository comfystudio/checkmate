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
                    <?php echo $this->data[0]['title'];?>
                </div>
            </div>
            <div class = "row">
                <div class ="col-xs-12 welcome-message">
                    <img src = "/assets/uploads/<?php echo $this->data[0]['image']?>" alt = "<?php echo $this->data[0]['image']?>"/>
                </div>
            </div>

            <div class = "row">
                <div class ="col-xs-12 welcome-message">
                    <?php echo $this->data[0]['text']?>
                </div>
            </div>
        </div>
    </div>
</div>
