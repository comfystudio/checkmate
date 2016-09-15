<div class="header">
		<div class = "row">
			<div class = "col-md-4">
				<?php if (isset($this->pageSection) && $this->pageSection != 'Holding'){?>
					<ul class = "header-nav">
						<li>About</li>
						<li>FAQ'S</li>
						<li>Contact</li>
						<li>Prices</li>
						<li>Login/Register</li>
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
						  <a href="#">About</a>
						  <a href="#">FAQ'S</a>
						  <a href="#">Contact</a>
						  <a href="#">Prices</a>
						  <a href="#">Login/Register</a>
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
							<li>About</li>
							<li>FAQ'S</li>
							<li>Contact</li>
							<li>Prices</li>
							<li>Login/Register</li>
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
