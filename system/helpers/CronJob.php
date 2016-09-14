<?php
if( !ini_get('safe_mode') ){
	set_time_limit(18000);
}
ini_set('memory_limit','512M');

/** Cron Job */
class CronJob {
	
	/** __construct */
	public function __construct(){
	}
	
	/**
	 * FUNCTION: Get Currency
	 * This method handles the get currency rates from
	 */
	public function GetCurrency($currency_code){
		$result[0] = false;
		$xml_file = "https://api.fixer.io/latest?base=$currency_code";
		// check to see if the file exists by trying to open it for read only
		if (fopen($xml_file, "r")) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $xml_file);
			$xml = curl_exec($ch);
			curl_close($ch);
		} else {
			$xml = false;
		}
		if($xml === false) {
			$result[1] = "Fail: Unable to open fixer.io file for $currency_code (/cronjobs/import-currency/) .\n";
		}else{
			// check if file is empty
			if(empty($xml)){
				$result[1] = "Fail: No data found in open fixer.io file for $currency_code (/cronjobs/import-currency/).\n";
			}else{
				$result[0] = true;
				$result[1] = $xml;				
			}
		}
		return $result;
	}
}
?>