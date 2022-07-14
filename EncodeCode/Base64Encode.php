<?php

define('OLDGROUPNUM', 3);
define('OLDGROUPBISNUM', 8);
define('NEWGROUPNUM', 4);
define('NEWGROUPBISNUM', 6);

function SelfBase64Encode($needEncodeString = '') {
	if (!is_string($needEncodeString)) {
		$needEncodeString = settype($needEncodeString, 'string');
	}
	if (empty($needEncodeString)) {
		return '';
	}
	$str_len = strlen($needEncodeString);

	$char_bin = '';
	$base64_encode_string = '';
	$base64_encode_end = '';
	for ($i = 0; $i < $str_len; $i++) {
		$char_bin .= StringToBin($needEncodeString[$i]);

		if (($i + 1) % OLDGROUPNUM == 0 || $i == ($str_len - 1)) {
			$bit_num = OLDGROUPNUM * OLDGROUPBISNUM;
			if (strlen($char_bin) % $bit_num > 0) {
				// str_repeat(字符串, 重复打印的次数)
				$char_bin .= str_repeat('0', NEWGROUPBISNUM - strlen($char_bin) % NEWGROUPBISNUM);
				$base64_encode_end = str_repeat('=', ($bit_num - strlen($char_bin)) / NEWGROUPBISNUM);
			}
			// str_split(字符串, 每段的长度) 把字符串按照每段长度分成数组 str_split('1234', 2) -> ['12', '34']
			$char_bins = str_split($char_bin, NEWGROUPBISNUM);
			echo $char_bin . PHP_EOL;
			$base64_encode_string .= implode('', array_map('ToString', $char_bins));

			// 每隔三个字符重新计算
			$char_bin = '';
		}
	}
	$base64_encode_string = $base64_encode_string . $base64_encode_end;
	return $base64_encode_string;
}

function StringToBin($char) {
	// 字符 转 ascii码
	$char_ascii = ord($char);
	// base_convert(数据, 原始进制, 要转换成的进制) 进制转换函数
	$char_ascii_bin = base_convert($char_ascii, 10, 2); // 10 -> 2
	$char_ascii_bin = sprintf('%08d', $char_ascii_bin);
	return $char_ascii_bin;
}

function ToString($bin) {
	$encode = [
		'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P','Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '+', '/'
	];
	return $encode[base_convert($bin, 2, 10)];
}

var_dump(SelfBase64Encode('this is apple'));
