<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	private $currencyApiKey;
	
	//15.05.22 __construct()
	public function __construct()
	{
		parent::__construct();
		$this->config->load('currency_converter', true);
		$this->currencyApiKey = $this->config->item('currency_api_key', 'currency_converter');
	}
	//default
	public function index()
	{
		$data['apikey'] = $this->currencyApiKey;		
		$this->load->view('currencies_list', $data);
	}
	
	public function convertPage(){
		$this->load->view('convert_pagepz');
	}
	
	//--------------------15.05.22
	public function convert(){
		$apikey = $this->currencyApiKey;
		function currencyConverter($amount,$fromCurrency,$toCurrency,$apikey)
		{	
			$fromCurrency = urlencode($fromCurrency);
			$toCurrency = urlencode($toCurrency);	
			$encode_amount = 1;
			$query =  "{$fromCurrency}_{$toCurrency}";
			$url = "https://free.currconv.com/api/v7/convert?q={$query}&compact=ultra&apiKey={$apikey}";
			$get = file_get_contents($url);			
			$data = preg_split('/\D\s(.*?)\s=\s/',$get);			
			$exhangeRate = (float) substr($data[0],11,8);			
			$convertedAmount = $amount*$exhangeRate;
			$data = array( 'exhangeRate' => $exhangeRate, 'convertedAmount' =>$convertedAmount, 'fromCurrency' => strtoupper($fromCurrency), 'toCurrency' => strtoupper($toCurrency));
			echo json_encode( $data );	
		}
		if(1==1) {
			$from_currency = trim($_POST['from_currency']);
			$to_currency = trim($_POST['to_currency']);
			$amount = trim($_POST['amount']);	
			if($from_currency == $to_currency) {
				$data = array('error' => '1');
				echo json_encode( $data );	
				exit;
		}

		$converted_currency=currencyConverter($amount, $from_currency, $to_currency, $apikey);
		echo $converted_currency;
	}
  }//---------------------------------------------function convert()
  
}//class Welcome
