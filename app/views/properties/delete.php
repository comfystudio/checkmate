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
                    <?php echo $this->selectedData[0]['title'];?>
                </div>
            </div>

            <div class = "form-wrapper">
                <div class = "row">
                    <div class ="col-xs-12 form-group form-text">
                        <?php if(isset($this->conflict) && !empty($this->conflict)){?>
                            <p>Can't remove this property as it is part of an ongoing check-in check-out process</p>
                        <?php } else {?>
                            <p>Please note that this will remove this property.</p>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <form action="" method="post" class="form-horizontal form-bordered">
                <input type="hidden" name="id" value="<?php echo $this->selectedData[0]['id']; ?>" />
                <div class="col-sm-12 form-spacing" style="text-align:center">
                    <div class = "back-to-dash"><a href = "/users/dashboard/"><img src = "/assets/images/back-to-dash.png"/> <span>Back to dashboard</span></a></div>
                    <?php if(!isset($this->conflict) || empty($this->conflict)){?>
                        <button type="submit" class="formbtn btn-default" name="delete" value = "delete">Delete</button>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>
</div>