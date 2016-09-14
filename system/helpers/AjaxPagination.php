<?php
/** PHP Pagination Class */
class AjaxPagination{
    /**
	 * set the number of items per page.
	 *
	 * @var numeric
	*/
	private $_perPage;

	/**
	 * set get parameter for fetching the page number
	 *
	 * @var string
	*/
	private $_instance;

	/**
	 * sets the page number.
	 *
	 * @var numeric
	*/
	private $_page;

	/**
	 * set the limit for the data source
	 *
	 * @var string
	*/
	private $_limit;

	/**
	 * set the total number of records/items.
	 *
	 * @var numeric
	*/
	private $_totalRows = 0;



	/**  __construct
	 *  
	 *  pass values when class is istantiated 
	 *  
	 * @param numeric  $_perPage  sets the number of iteems per page
	 * @param numeric  $_instance sets the instance for the GET parameter
	 */
	public function __construct($perPage,$instance,$page){
		$this->_instance = $instance;		
		$this->_perPage = $perPage;
		$this->_page = ($page == 0 ? 1 : $page);	
	}

	/**
	 * get_start
	 *
	 * creates the starting point for limiting the dataset
	 * @return numeric
	*/
	private function get_start(){
		return ($this->_page * $this->_perPage) - $this->_perPage;
	}

	/**
	 * set_total
	 *
	 * collect a numberic value and assigns it to the totalRows
	 *
	 * @var numeric
	*/
	public function set_total($_totalRows){
		$this->_totalRows = $_totalRows;
	}

	/**
	 * get_limit
	 *
	 * returns the limit for the data source, calling the get_start method and passing in the number of items perp page
	 * 
	 * @return string
	*/
	public function get_limit(){
		return $this->get_start().",$this->_perPage";
	}

	/**
	 * page_links
	 *
	 * create the html links for navigating through the dataset
	 * 
	 * @var sting $path optionally set the path for the link
	 * @var sting $ext optionally pass in extra parameters to the GET
	 * @return string returns the html menu
	*/
	public function page_links($path='?',$ext=null){
	    $stages = "3";
	    $prev = $this->_page - 1;
	    $next = $this->_page + 1;
	    $lastpage = ceil($this->_totalRows/$this->_perPage);
	    $lpm1 = $lastpage - 1;

	    $pagination = '';
	
		if($lastpage > 1){   
		    $pagination .= '<ul class="pagination">';
			
			// Prev
			if ($this->_page > 1){
				$pagination.= '<li class="prev"><a href="#" data-id="'.$prev.'"><i class="icon-angle-left"></i></a></li>';
			}else{
				$pagination.= '<li class="prev disabled"><i class="icon-angle-left"></i></li>';
			}
			
			// Pages
			if ($lastpage < 7 + ($stages * 2)){
				for ($counter = 1; $counter <= $lastpage; $counter++){
					if ($counter == $this->_page){
						$pagination.= '<li class="active"><a href="#">'.$counter.'</a></li>';
					}else{
						$pagination.= '<li><a href="#" data-id="'.$counter.'">'.$counter.'</a></li>';}
				}
			}elseif($lastpage > 5 + ($stages * 2)){
				// Beginning only hide later pages
				if($this->_page < 1 + ($stages * 2)){
					for ($counter = 1; $counter < 4 + ($stages * 2); $counter++){
						if ($counter == $this->_page){
							$pagination.= '<li class="active"><a href="#">'.$counter.'</a></li>';
						}else{
							$pagination.= '<li><a href="#" data-id="'.$counter.'">'.$counter.'</a></li>';}
					}
					$pagination.= '<li class="pagination_break">...</li>';
					$pagination.= '<li><a href="#" data-id="'.$lpm1.'">'.$lpm1.'</a></li>';
					$pagination.= '<li><a href="#" data-id="'.$lastpage.'">'.$lastpage.'</a></li>';
				}
				// Middle hide some front and some back
				elseif($lastpage - ($stages * 2) > $this->_page && $this->_page > ($stages * 2)){
					$pagination.= '<li><a href="#" data-id="1">1</a></li>';
					$pagination.= '<li><a href="#" data-id="2">2</a></li>';
					$pagination.= '<li class="pagination_break">...</li>';
					for ($counter = $this->_page - $stages; $counter <= $this->_page + $stages; $counter++){
						if ($counter == $this->_page){
							$pagination.= '<li class="active"><a href="#">'.$counter.'</a></li>';
						}else{
							$pagination.= '<li><a href="#" data-id="'.$counter.'">'.$counter.'</a></li>';}
					}
					$pagination.= '<li class="pagination_break">...</li>';
					$pagination.= '<li><a href="#" data-id="'.$lpm1.'">'.$lpm1.'</a></li>';
					$pagination.= '<li><a href="#" data-id="'.$lastpage.'">'.$lastpage.'</a></li>';
				}
				// End only hide early pages
				else{
					$pagination.= '<li><a href="#" data-id="1">1</a></li>';
					$pagination.= '<li><a href="#" data-id="2">2</a></li>';
					$pagination.= '<li class="pagination_break">...</li>';
					for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++){
						if ($counter == $this->_page){
							$pagination.= '<li class="active"><a href="#">'.$counter.'</a></li>';
						}else{
							$pagination.= '<li><a href="#" data-id="'.$counter.'">'.$counter.'</a></li>';}
					}
				}
			}
			
			// Next
			if ($this->_page < $counter - 1){
				$pagination.= '<li class="next"><a href="#" data-id="'.$next.'"><i class="icon-angle-right"></i></a></li>';
			}else{
				$pagination.= '<li class="next disabled"><i class="icon-angle-right"></i></li>';
			}
			
		    $pagination.= '</ul>';
		}
	
	return $pagination;
	}
}?>