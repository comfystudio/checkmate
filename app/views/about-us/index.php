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
                    <?php echo $this->data[0]['title'];?>
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
                    </div>
                </div>

                <div class = "row">
                    <div class = "col-xs-12 welcome-message">
                        <video width="90%" controls>
                          <source src="/assets/videos/checkmate-wb.mp4" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 form-spacing" style="text-align:center">
                <div class = "back-to-dash"><a href = "/"><img src = "/assets/images/back-to-dash.png"/> <span>Back</span></a></div>
            </div>
        </div>
    </div>
</div>


