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
                    <?php echo date('F jS, Y', strtotime($this->data[0]['created']))?>
                </div>
            </div>
            <div class = "row">
                <div class ="col-xs-12 welcome-message">
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
