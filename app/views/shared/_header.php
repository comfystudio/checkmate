<div class="header">
		<div class = "row">
			<div class = "col-md-4">
				<?php if (isset($this->pageSection) && $this->pageSection != 'Holding'){?>
					<ul class = "header-nav">
						<a href = "/about-us/"><li>About</li></a>
						<a href = "/faqs/"><li>FAQ'S</li></a>
						<a href = "/contact/"><li>Contact</li></a>
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
					<img src="/assets/images/logo.png" alt ="Checkmate Logo">
				</div>
			</div>

			<?php if (isset($this->pageSection) && $this->pageSection != 'Holding'){?>
				<div class="col-xs-2">
					<div class = "slide-out-menu">
						<div id="mySidenav" class="sidenav">
							<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
							<a href="/about-us/">About</a>
					  		<a href="/faqs/">FAQ'S</a>
					  		<a href="/contact/">Contact</a>
					  		<a href="/prices/">Prices</a>
  							<?php if(!isset($_SESSION['UserCurrentUserID'])){?>
					  			<a href="/users/login/">Login/Register</a>
				  			<?php } else {?>
								<a href = "/users/logout/">Logout</a>
							<?php } ?>
						</div>

						<span onclick="openNav()"><img src="/assets/images/slideout.png"></span>
					</div>
				</div>

				<div class="col-sm-4">				
					<div class = "mobile-search search">
						<a href="#" title="Search site" id="searchshow">
							<img src="/assets/images/search.png" alt="Search site" />
						</a>
					</div>
					<div class="mobile-list">
						<ul>	
							<a href="/about-us/"><li>About</li></a>
							<a href="/faqs/"><li>FAQ'S</li></a>
							<a href="/contact/"><li>Contact</li></a>
							<a href="/prices/"><li>Prices</li></a>
							<?php if(!isset($_SESSION['UserCurrentUserID'])){?>
								<a href="/users/login/"><li>Login/Register</li></a>
							<?php }	else {?>
								<a href = "/users/logout/"><li>Logout</li></a>
							<?php } ?>
							<li><div class = "mobile-header-phone header-phone">
									07568322383 / 07753137475
								</div>
							</li>
						</ul>
					</div>
				</div>

				<div class = "col-md-3">
					<div class = "header-phone">
						07568322383 / 07753137475
					</div>
				</div>

				<div class = "col-md-1">
					<div class = "search">
						<a href="#" title="Search site" id="searchshow">
							<img src="/assets/images/search.png" alt="Search site" />
						</a>
					</div>
				</div>
			<?php }?>
		</div>
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
                    <input type="text" class="form-control input-lg" placeholder="Search" />
                    <span class="input-group-btn">
                    </span>
                </div>
            </div>
		</div>
		<div class="searchback"></div>
