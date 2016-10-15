<div id="sidebar">
    <!-- Sidebar Brand -->

    <div id="sidebar-brand" class="themed-background">
        <a href="/" class="sidebar-title">
	        <i class="fa fa-cube"></i><span class="sidebar-nav-mini-hide"><?php echo SITE_NAME; ?></span>
	    </a>
    </div><!-- END Sidebar Brand -->
    <!-- Wrapper for scrolling functionality -->

    <div id="sidebar-scroll">
        <!-- Sidebar Content -->

        <div class="sidebar-content">
            <!-- Sidebar Navigation -->

            <ul class="sidebar-nav">
                <li>
                	<a href="/backoffice/admin-users/" <?php echo isset($this->pageSection) && $this->pageSection == 'Admin Users' ? ' class="active"' : ''; ?>>
						<i class="fa fa-user sidebar-nav-icon"></i>
						<span class="sidebar-nav-mini-hide">Admin Users</span>
					</a>
				</li>

                <li>
                    <a href="#" class="sidebar-nav-menu <?php echo isset($this->pageSection) && $this->pageSection == 'Users' ? 'open' : ''; ?>">
                        <i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i>
                        <i class="fa fa-users sidebar-nav-icon"></i>
                        <span class="sidebar-nav-mini-hide">Users</span>
                    </a>
                    <ul>
                        <li><a href="/backoffice/users/" <?php echo isset($this->pageSubSection) && $this->pageSubSection == 'Users' ? ' class="active"' : ''; ?>>Manage Users</a></li>
                        <li><a href="/backoffice/payments/" <?php echo isset($this->pageSubSection) && $this->pageSubSection == 'Payments' ? ' class="active"' : ''; ?>>Manage Payments</a></li>
                    </ul>
                </li>

                <li>
                    <a href="#" class="sidebar-nav-menu <?php echo isset($this->pageSection) && $this->pageSection == 'Rooms' ? 'open' : ''; ?>">
                        <i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i>
                        <i class="fa fa-bed sidebar-nav-icon"></i>
                        <span class="sidebar-nav-mini-hide">Templates</span>
                    </a>
                    <ul>
                        <li><a href="/backoffice/templates/" <?php echo isset($this->pageSubSection) && $this->pageSubSection == 'Templates' ? ' class="active"' : ''; ?>>Manage Templates</a></li>
                        <li><a href="/backoffice/rooms/" <?php echo isset($this->pageSubSection) && $this->pageSubSection == 'Rooms' ? ' class="active"' : ''; ?>>Manage Rooms</a></li>
                        <li><a href="/backoffice/items/" <?php echo isset($this->pageSubSection) && $this->pageSubSection == 'Items' ? ' class="active"' : ''; ?>>Manage Items</a></li>
                    </ul>
                </li>

                <li>
                    <a href="/backoffice/properties/" <?php echo isset($this->pageSection) && $this->pageSection == 'Properties' ? ' class="active"' : ''; ?>>
                        <i class="fa fa-building sidebar-nav-icon"></i>
                        <span class="sidebar-nav-mini-hide">Properties</span>
                    </a>
                </li>

                <li>
                    <a href="/backoffice/reports/" <?php echo isset($this->pageSection) && $this->pageSection == 'Reports' ? ' class="active"' : ''; ?>>
                        <i class="fa fa-book sidebar-nav-icon"></i>
                        <span class="sidebar-nav-mini-hide">Reports</span>
                    </a>
                </li>

                <li>
                    <a href="#" class="sidebar-nav-menu <?php echo isset($this->pageSection) && $this->pageSection == 'Pages' ? 'open' : ''; ?>">
                        <i class="fa fa-chevron-left sidebar-nav-indicator sidebar-nav-mini-hide"></i>
                        <i class="fa fa-flash sidebar-nav-icon"></i>
                        <span class="sidebar-nav-mini-hide">General Pages</span>
                    </a>
                    <ul>
                        <li><a href="/backoffice/queries/" <?php echo isset($this->pageSubSection) && $this->pageSubSection == 'Queries' ? ' class="active"' : ''; ?>>Queries</a></li>
                        <li><a href="/backoffice/faqs/" <?php echo isset($this->pageSubSection) && $this->pageSubSection == 'Faqs' ? ' class="active"' : ''; ?>>Faqs</a></li>
                        <li><a href="/backoffice/about-us/" <?php echo isset($this->pageSubSection) && $this->pageSubSection == 'AboutUs' ? ' class="active"' : ''; ?>>About</a></li>
                        <li><a href="/backoffice/prices/" <?php echo isset($this->pageSubSection) && $this->pageSubSection == 'Prices' ? ' class="active"' : ''; ?>>Price</a></li>
                        <li><a href="/backoffice/news/" <?php echo isset($this->pageSubSection) && $this->pageSubSection == 'News' ? ' class="active"' : ''; ?>>News</a></li>
                        <li><a href="/backoffice/contact-us/" <?php echo isset($this->pageSubSection) && $this->pageSubSection == 'Contact Us' ? ' class="active"' : ''; ?>>Contact Us</a></li>
                    </ul>
                </li>

            </ul><!-- END Sidebar Navigation -->
        </div><!-- END Sidebar Content -->
    </div><!-- END Wrapper for scrolling functionality -->
</div><!-- END Main Sidebar -->