<?php
 
// author: chesskim <chesekim@nate.com>
// blog: chess72.tistory.com 
// encode: UTF-8 
	
if ( ! function_exists('Unsigned_right_shift') ) {
	function Unsigned_right_shift($a, $b) { return (is_numeric($a)&&$a<0)?(($a>>$b)+(2<<~$b)):$a>>$b; }
}

/**
 * This class is.
 * This block is a block cipher algorithm, type the password should be an exact multiple of the size.
 * If you, or an exact multiple of plaintext to be encrypted,
 * encrypting the data before adding padding to support the operation.
 * 
 * @author devhome.tistory.com
 */

abstract class BlockPadding {

	public static $ANSIX = "ANSIX923";
	public static $ISO   = "ISO10126";
	public static $PKCS7 = "PKCS7";

	private static $ins = null;

	/**
	 * To provide byte padding to create an instance.
	 * Byte padding to provide that kind of 'ANSI X.923', 'ISO 10126', 'PKCS7' is.
	 * @param type The type of padding bytes (ANSI X.923 : ANSIX923, ISO 10126 : ISO10126, PKCS7 : PKCS7)
	 * @return Byte padding instance, if that does not support the null of the padding bytes is returned.
	 */
	public static function getInstance($type) {

		$isCreate = false;

		if ( $ins == null ) {
			$isCreate = true;
		} else {
			if ( $ins::getPaddingType() != $type ) {
				$isCreate = true;
			}
		}

		if ( $isCreate == true ) {
				
			if ( BlockPadding::$ANSIX == $type ) {
				$ins = new AnsiX923Padding();
			} else if ( BlockPadding::$ISO == $type ) {
				$ins = null;
			} else if ( BlockPadding::$PKCS7 == $type ) {
				$ins = null;
			} else {
				$ins = null;
			}
		}

		return $ins;
	}

	/**
	 * Type of the current instance, brings paeting bytes.
	 * @return The type of padding bytes
	 */
	public abstract function getPaddingType();

	/**
	 * Block size is short, add enough padding.
	 * @param source Target Data Encryption
	 * @param blockSize block size
	 * @return Added padding target data encryption
	*/
	public abstract function addPadding($source, $blockSize);


	public abstract function removePadding($source, $blockSize);
}

// End of file BlockPadding.php