<?php if (isset($this->pageSection) && $this->pageSection != 'Holding'){?>
	<footer id="footer" class="clearfix">
		<div class="container">
	       <div class="col-sm-5 footernews">
		       	<h1>latest news</h1>
		       	<?php foreach($this->footerNews as $news){?>
			       	<div class="news-item-1">
			       		<span><?php echo date('F jS, Y', strtotime($news['date']))?></span>
			       		<h3><a href = "/news/view/<?php echo $news['slug']?>" style = "color:#333;"><?php echo $news['title']?></a></h3>
			       		<p><?php echo substr($news['text_clean'], 0, 125)?> [...]</p>
			       	</div>
		       	<?php } ?>
	       </div>

	       <div class="col-sm-offset-2 col-sm-5 footercontact">
	       		<h1>contact details</h1>
		       	<div class="contactdetails">
		       		<h3>CheckMate Deposit</h3>
		       		<p><?php if(isset($this->contactInfo[0]['location']) && !empty($this->contactInfo[0]['location'])){ echo $this->contactInfo[0]['location'];}else{ echo 'Glengorm Avenue Coleraine Bt52 1tf';}?></p>

                    <?php if(isset($this->contactInfo[0]['phone']) && !empty($this->contactInfo[0]['phone'])){ $phone = $this->contactInfo[0]['phone'];}else{ $phone = '07522635219';}?>
                    <?php if(isset($this->contactInfo[0]['phone_2']) && !empty($this->contactInfo[0]['phone_2'])){ $phone2 = $this->contactInfo[0]['phone_2'];}else{ $phone2 = '07522635220';}?>

                    <p class="phonenumber"><a href="tel:<?php echo $phone?>"><?php echo $phone?></a><span>|</span><a href="tel:<?php echo $phone2?>"><?php echo $phone2?></a></p>
		       		<img src="/assets/images/footer-phone.png" alt ="Phone Us"/>
		       	</div>
		       	<div class="social-icon">
		       			<a href = "<?php if(isset($this->contactInfo[0]['facebook']) && !empty($this->contactInfo[0]['facebook'])){ echo $this->contactInfo[0]['facebook'];}else{ echo 'https://www.facebook.com/checkmatedeposit';}?>" target="_blank"><img class="fbicon"src="/assets/images/facebook-icon.png" alt ="Facebook"/></a>
		       			<a href = "<?php if(isset($this->contactInfo[0]['instagram']) && !empty($this->contactInfo[0]['instagram'])){ echo $this->contactInfo[0]['instagram'];}else{ echo 'https://www.instagram.com/checkmate_deposit/';}?>" target="_blank"><img class ="instaicon"src="/assets/images/instagram-icon.png" alt ="Instagram"/></a>
                        <a href = "<?php if(isset($this->contactInfo[0]['twitter']) && !empty($this->contactInfo[0]['twitter'])){ echo $this->contactInfo[0]['twitter'];}else{ echo 'https://twitter.com/checkm8_deposit';}?>" target="_blank"><img class ="twittericon"src="/assets/images/twitter.png" alt ="Twitter"/></a>
                </div>
		       	<div class="websiteni">
		       		<a href="http://websiteni.com/" target="_blank" title="Web Design Belfast" class="websiteni">
						<img src="/assets/images/websiteni-icon.png" alt="" />
						<p>Website by WebsiteNI</p>
					</a>
		       	</div>
               <div class = "terms">
                   <a href = "/terms/">Terms and Conditions</a>
                   <a href = "/policy/">Policy</a>
               </div>
	       </div>
	    </div>
	</footer>
<?php } ?>




