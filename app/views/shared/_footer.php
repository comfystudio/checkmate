<?php if (isset($this->pageSection) && $this->pageSection != 'Holding'){?>
	<footer id="footer" class="clearfix">
		<div class="container">
	       <div class="col-sm-5 footernews">
		       	<h1>latest news</h1>

		       	<?php foreach($this->footerNews as $news){?>
			       	<div class="news-item-1">
			       		<span><?php echo date('F jS, Y', strtotime($news['date']))?></span>
			       		<h3><a href = "/news/view/<?php echo $news['slug']?>" style = "color:#333;"><?php echo $news['title']?></a></h3>
			       		<p><?php echo substr($news['text_clean'], 0, 50)?>...</p>
			       	</div>
		       	<?php } ?>

	       </div>

	       <div class="col-sm-offset-2 col-sm-5 footercontact">
	       		<h1>contact details</h1>
		       	<div class="contactdetails">
		       		<h3>CheckMate Deposit</h3>
		       		<p>Priory House West, 14 Main Street, Benburb, Dungannon Co. Tyrone, BT71 7LA</p>
		       		<p class="phonenumber">07568322383     |     07753137475</p>
		       		<img src="/assets/images/footer-phone.png" alt ="Phone Us"/>
		       	</div>
		       	<div class="social-icon">
		       			<img class="fbicon"src="/assets/images/facebook-icon.png" alt ="Facebook"/>
		       			<img class ="instaicon"src="/assets/images/instagram-icon.png" alt ="Instagram"/>
		       	</div>
		       	<div class="websiteni">
		       		<a href="http://websiteni.com/" target="_blank" title="Web Design Belfast" class="websiteni">
						<img src="/assets/images/websiteni-icon.png" alt="" />
						<p>Website by WebsiteNI</p>
					</a>
		       	
		       	</div>
	       </div>
	    </div>
	</footer>
<?php } ?>




