<?php 

// author: chesskim <chesekim@nate.com>
// blog: chess72.tistory.com
// encode: UTF-8
// Date: 2013. 05. 16
// License: GPL, http://www.gnu.org/licenses/

// Seed128 + CBC mode + PKCS7 + base64 암호화 & 복호화

require_once 'Seed128Core.php';

class Seed128Cipher extends Seed128Core {
	
	private $DefaultCharset = 'UTF-8';

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 
	 * @param string $strData
	 * @param string $strUserKey
	 * @param string $charset
	 */
	public function encode($strData, $strUserKey, $charset="") {
		
		if ( trim($charset) && strcasecmp($charset, $this->DefaultCharset) ) {
			//$byteUserKey = iconv($this->charset, $charset, $strUserKey);
			$strData = iconv($this->DefaultCharset, $charset, $strData) or die();
		}
		
		$byteUserKey = array_slice(unpack('c*',$strUserKey), 0);
		$byteData = array_slice(unpack('c*',$strData), 0);
		
		$encryptBytes = $this->encrypt($byteData, $byteUserKey);
		return call_user_func_array("pack", array_merge(array("c*"), $encryptBytes));
	}

	/**
	 * 
	 * @param string $strData
	 * @param string $strUserKey
	 * @param string $charset
	 */
	public function decode($strData, $strUserKey) {
		
		$byteUserKey = array_slice(unpack('c*',$strUserKey), 0);
		$byteData = array_slice(unpack('c*',$strData), 0);
		
		$decryptBytes = $this->decrypt($byteData, $byteUserKey);
		return call_user_func_array("pack", array_merge(array("c*"), $decryptBytes));
	}

	/**
	 * 
	 * @param string $strData
	 * @param string $strUserKey
	 * @param string $charset
	 */
	public function base64_encrypt($strData, $strUserKey, $charset="") {
		$planeText = $this->encode($strData, $strUserKey, $charset);
		return base64_encode($planeText);
	}
	
	/**
	 * 
	 * @param string $strData
	 * @param string $strUserKey
	 * @param string $charset
	 */
	public function base64_decrypt($strData, $strUserKey) {
		$strData = base64_decode($strData);
		return $this->decode($strData, $strUserKey);
	}
}

// End of file Seed128Cipher.php