<?php

// author: chesskim <chesekim@nate.com>
// blog: chess72.tistory.com
// encode: UTF-8
// Date: 2013. 05. 16
// License: GPL, http://www.gnu.org/licenses/

/**
 * This class is.
 * This block is a block cipher algorithm, type the password should be an exact multiple of the size.
 * If you, or an exact multiple of plaintext to be encrypted,
 * encrypting the data before adding padding to support the operation.
 * 'ANSI X.923' padding bytes are supported.
 * 
 * @author devhome.tistory.com
 *
 */

require_once 'BlockPadding.php';

class AnsiX923Padding extends BlockPadding {

	public static $PADDING_VALUE = 0x00;

	/**
	 * 
	 * @Override
	 * @return string
	 */
	public function getPaddingType() {
		return BlockPadding::$ANSIX;
	}

	/**
	 * 요청한 Block Size를 맞추기 위해 Padding을 추가한다.
	 *
	 * @Override
	 * @param $source
	 *            byte[] 패딩을 추가할 bytes
	 * @param $blockSize
	 *            int block size
	 * @return byte[] 패딩이 추가 된 결과 bytes
	 */
	public function addPadding($source, $blockSize) {

		$paddingResult = null;
		$paddingCount = $blockSize - (count($source) % $blockSize);
		if ( $paddingCount == 0 || $paddingCount == $blockSize ) {
			$paddingResult = $source;
		}
		else {
			$paddingResult = $source;
			
			// 패딩해야 할 갯수 - 1 (마지막을 제외)까지 0x00 값을 추가한다.
			for($i=0;$i<$paddingCount-1;$i++) {
				$paddingResult[] = self::$PADDING_VALUE;
			}
			
			// 마지막 패딩 값은 패딩 된 Count를 추가한다.
			$paddingResult[] = $paddingCount;
		}

		return $paddingResult;
	}

	/**
	 * 요청한 Block Size를 맞추기 위해 추가 된 Padding을 제거한다.
	 *
	 * @Override
	 * @param $source
	 *            byte[] 패딩을 제거할 bytes
	 * @param $blockSize
	 *            int block size
	 * @return byte[] 패딩이 제거 된 결과 bytes
	 */
	public function removePadding($source, $blockSize) {
		$paddingResult = $source;
		$isPadding = false;
		$byte_length = count($source);

		// 패딩 된 count를 찾는다.
		$lastValue = $source[$byte_length - 1];
		if ($lastValue < ($blockSize - 1)) {
			$zeroPaddingCount = $lastValue - 1;
				
			for ($i=2;$i<($zeroPaddingCount + 2);$i++) {
				if ($source[$byte_length - $i] != self::$PADDING_VALUE) {
					$isPadding = false;
					break;
				}
			}
				
			$isPadding = true;
		} 
		else {
			// 마지막 값이 block size 보다 클 경우 패딩 된것이 없음.
			$isPadding = false;
		}

		if ($isPadding) {
			$paddingCnt = $byte_length - $lastValue;
			for ($i=0; $i<$lastValue; $i++) {
				array_pop($paddingResult);
			}
		}

		return $paddingResult;
	}

	/**
	 * 
	 * @param int $blockSize
	 * @return byte[]
	 */
	public static function assignPadding($blockSize) {
		return array_fill(0, $blockSize-1, AnsiX923Padding::$PADDING_VALUE);
	}
}


// End of file AnsiX923Padding.php