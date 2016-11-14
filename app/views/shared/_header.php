<div class="header">
	<div class="container-fluid">
		<div class = "row">
			<div class = "col-md-4">
				<?php if (isset($this->pageSection) && $this->pageSection != 'Holding'){?>
					<ul class = "header-nav">
						<a href = "/about-us/"><li>About</li></a>
						<a href = "/faqs/"><li>Faqs</li></a>
						<a href = "/contact-us/"><li>Contact</li></a>
						<a href = "/prices/"><li>Prices</li></a>
						<?php if(!isset($_SESSION['UserCurrentUserID'])){?>
							<a href = "/users/login/"><li>Login/Register</li></a>
						<?php } else {?>
							<a href = "/users/logout/"><li>Logout</li></a>
                        <?php } ?>
					<ul>
				<?php } ?>
			</div>

			<div class = "col-sm-offset-4 col-sm-4 col-md-offset-0 col-xs-9 col-md-4">
				<div class = "logo">
<!--					<a href = "/"><img src="/assets/images/logo.png" alt ="Checkmate Logo"></a>-->
                    <a href = "/"><img src="/assets/images/checkmate-logo.svg" alt ="Checkmate Logo"></a>
                </div>
			</div>

			<?php if (isset($this->pageSection) && $this->pageSection != 'Holding'){?>
				<div class="col-xs-2">
					<div class = "slide-out-menu">
						<div id="mySidenav" class="sidenav">
							<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
							<a href="/about-us/">About</a>
					  		<a href="/faqs/">Faqs</a>
					  		<a href="/contact-us/">Contact</a>
					  		<a href="/prices/">Prices</a>
  							<?php if(!isset($_SESSION['UserCurrentUserID'])){?>
					  			<a href="/users/login/">Login/Register</a>
				  			<?php } else {?>
								<a href = "/users/logout/">Logout</a>
                                <a href = "/users/dashboard/">Dashboard</a>
                                <a class = "search">Search</a>

                            <?php } ?>
                        </div>

						<span onclick="openNav()"><img src="/assets/images/menu-lines.svg"></span>
					</div>
				</div>

                <?php if(isset($this->contactInfo[0]['phone']) && !empty($this->contactInfo[0]['phone'])){ $phone = $this->contactInfo[0]['phone'];}else{ $phone = '07522635219';}?>
                <?php if(isset($this->contactInfo[0]['phone_2']) && !empty($this->contactInfo[0]['phone_2'])){ $phone2 = $this->contactInfo[0]['phone_2'];}else{ $phone2 = '07522635220';}?>


                <div class="col-sm-4">
                    <?php if(isset($_SESSION['UserCurrentUserID']) && !empty($_SESSION['UserCurrentUserID'])){?>
                        <div class = "mobile-search search">
                            <a href="#" title="Search site" id="searchshow">
                                <img src="/assets/images/search.png" alt="Search site" />
                            </a>
                        </div>
                    <?php } ?>
					<div class="mobile-list">
						<ul>	
							<a href="/about-us/"><li>About</li></a>
							<a href="/faqs/"><li>Faqs</li></a>
							<a href="/contact-us/"><li>Contact</li></a>
							<a href="/prices/"><li>Prices</li></a>
							<?php if(!isset($_SESSION['UserCurrentUserID'])){?>
								<a href="/users/login/"><li>Login/Register</li></a>
							<?php }	else {?>
								<a href = "/users/logout/"><li>Logout</li></a>
                                <a href = "/users/dashboard/"><li>Back to Dashboard</li></a>
                                <a class = "search"><li>Search</li></a>
							<?php } ?>
							<li><div class = "mobile-header-phone header-phone">
                                    <a href="tel:<?php echo $phone?>"><?php echo $phone?></a> / <a href="tel:<?php echo $phone2?>"><?php echo $phone2?></a>
								</div>
							</li>
						</ul>
					</div>
				</div>

				<div class = "col-md-3 col-xs-4">
					<div class = "header-phone">
                        <a href="tel:<?php echo $phone?>"><?php echo $phone?></a> / <a href="tel:<?php echo $phone2?>"><?php echo $phone2?></a>
					</div>
				</div>

				<div class = "col-md-1">
                    <?php if(isset($_SESSION['UserCurrentUserID']) && !empty($_SESSION['UserCurrentUserID'])){?>
                        <div class = "search" id = "search-large">
                            <a href="#" title="Search site" id="searchshow">
                                <img src="/assets/images/search.png" alt="Search site" />
                            </a>
                        </div>
                        <a class = "back-to-dash-header" href = "/users/dashboard/">Back to Dashboard</a>
                    <?php } ?>
				</div>
			<?php }?>
		</div>
	</div>
	<div class="container-fluid">
		<div class = "row">
			<div class = "col-md-12">
				<div class = "logo-text">
					online inventory check in report for tenants, landlords & letting agents
				</div>
			</div>
		</div>
	</div>
    <div class="searchformholder">
        <div id="custom-search-input">
            <div class="input-group col-xs-12">
                <form class="form-wrap" method='get' action='/users/dashboard'>
                    <input type="text" class="form-control input-lg" placeholder="Search Your properties" name="keywords" id="search_term" <?php if (isset($_GET["keywords"])) {echo 'value="'.htmlentities($_GET["keywords"]).'"';}?>/>
                    <a id = "search-close"><i class="fa fa-times"></i></a>
                    <span class="input-group-btn">
                        </span>
                </form>
            </div>
        </div>
    </div>
    <div class="searchback"></div>
