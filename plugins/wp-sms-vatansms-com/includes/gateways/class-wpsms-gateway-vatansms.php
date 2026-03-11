<?php

namespace WP_SMS\Gateway;

class vatansms extends \WP_SMS\Gateway {
	private $wsdl_link = "http://www.oztekbayi.com/webservis/service.php?wsdl";
	public $tariff = "http://www.oztekbayi.com/";
	public $unitrial = false;
	public $unit;
	public $flash = "disabled";
	public $isflash = false;

	public function __construct() {
		parent::__construct();
		$this->validateNumber = "90xxxxxxxxxx";
  		}

	public function SendSMS() {

		/**
		 * Modify sender number
		 *
		 * @since 3.4
		 *
		 * @param string $this ->from sender number.
		 */

		$this->from = apply_filters( 'wp_sms_from', $this->from );

		/**
		 * Modify Receiver number
		 *
		 * @since 3.4
		 *
		 * @param array $this ->to receiver number
		 */
		$this->to = apply_filters( 'wp_sms_to', $this->to );

		/**
		 * Modify text message
		 *
		 * @since 3.4
		 *
		 * @param string $this ->msg text message.
		 */
		$this->msg = apply_filters( 'wp_sms_msg', $this->msg );

		// Get the credit.
		$credit = $this->GetCredit();

		// Check gateway credit
		if ( is_wp_error( $credit ) ) {
			// Log the result
			$this->log( $this->from, $this->msg, $this->to, $credit->get_error_message(), 'error' );

			return $credit;
		}

		try {

			$SOAP = new \SoapClient("http://www.oztekbayi.com/webservis/service.php?wsdl", array(
			"trace"      => 1,
			"exceptions" => 0));
			$KULLANICINO= $this->kno; 
			$KULLANICIADI= $this->username;
			$SIFRE=$this->password;       
			$ORGINATOR=$this->from;	
			$TUR='Normal';  // Normal yada Turkce
			$ZAMAN='';  	// İleri tarih için kullanabilirsiniz 2014-04-07 10:00:00
			$ZAMANASIMI=''; // Sms ömrünü belirtir 2014-04-07 15:00:00

			$mesaj= $this->msg;
			$numaralar=implode( $this->to, "," ); 

			$result = $SOAP->TekSmsiBirdenCokNumarayaGonder($KULLANICINO,$KULLANICIADI,$SIFRE,$ORGINATOR,$numaralar,$mesaj,$ZAMAN,$ZAMANASIMI,$TUR); 
		
			return $result;
	



			// Log the result
			$this->log( $this->from, $this->msg, $this->to, $result );

			/**
			 * Run hook after send sms.
			 *
			 * @since 2.4
			 *
			 * @param string $result result output.
			 */
			do_action( 'wp_sms_send', $result );

			return $result;
		}
		catch ( \Exception $e ) {
			// Log th result
			$this->log( $this->from, $this->msg, $this->to, $e->getMessage(), 'error' );

			return new \WP_Error( 'send-sms', $e->getMessage() );
		}
		
	}

public function GetCredit() {
		
		
			$SOAP = new \SoapClient("http://www.oztekbayi.com/webservis/service.php?wsdl", array(
			"trace"      => 1,
			"exceptions" => 0));

			$KULLANICINO= $this->kno; 
			$KULLANICIADI= $this->username;
			$SIFRE=$this->password; 
			$orji='';
			$SONUC = $SOAP->UyeBilgisiSorgula($KULLANICINO,$KULLANICIADI,$SIFRE); 


			if($SONUC == 'HATA:Kullanici bulunamadi'){

			return new \WP_Error( 'account-credit', __( 'Username/Password does not set for this gateway', 'wp-sms' ) );
			}
			else{
			$array = (explode("\n",$SONUC));
  
				$bakiye = $array[4];
                return $bakiye;
			}

			
	}
}