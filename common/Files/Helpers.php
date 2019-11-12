<?php

/**
 * @Filename:  functions_helper.php
 * @Author:  assad
 * @Email:  rlk002@gmail.com
 * @Date:  2019-07-18 11:00:45
 * @Synopsis:  函数库
 * @Version:  1.0
 * @Last Modified by:   assad
 * @Last Modified time: 2019-11-12 23:40:05
 */

/**
 * 截取字符串函数
 *
 * @param      string   $string  待截字符串
 * @param      integer  $length  长度
 * @param      string   $dot     末尾拼接
 *
 * @return     string   返回已截的字符
 */
function cutStr($string, $length, $dot = '...') {
	$strlen = strlen($string);
	if ($strlen <= $length) {
		return $string;
	}

	$string = str_replace(
		[' ', '&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'],
		[' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'],
		$string
	);

	$strcut = '';
	if (is_utf8($string)) {
		$length = $length - strlen($dot);
		$n = $tn = $noc = 0;
		while ($n < strlen($string)) {
			$t = ord($string[$n]);
			if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1;
				$n++;
				$noc++;
			} elseif (194 <= $t && $t <= 223) {
				$tn = 2;
				$n += 2;
				$noc += 2;
			} elseif (224 <= $t && $t <= 239) {
				$tn = 3;
				$n += 3;
				$noc += 2;
			} elseif (240 <= $t && $t <= 247) {
				$tn = 4;
				$n += 4;
				$noc += 2;
			} elseif (248 <= $t && $t <= 251) {
				$tn = 5;
				$n += 5;
				$noc += 2;
			} elseif ($t == 252 || $t == 253) {
				$tn = 6;
				$n += 6;
				$noc += 2;
			} else {
				$n++;
			}
			if ($noc >= $length) {
				break;
			}
		}
		if ($noc > $length) {
			$n -= $tn;
		}
		$strcut = substr($string, 0, $n);
		$strcut = str_replace(
			['∵', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'],
			[' ', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'],
			$strcut);
	} else {
		$dotlen = strlen($dot);
		$maxi = $length - $dotlen - 1;
		$current_str = '';
		$search_arr = ['&', ' ', '"', "'", '“', '”', '—', '<', '>', '·', '…', '∵'];
		$replace_arr = ['&amp;', '&nbsp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;', ' '];
		$search_flip = array_flip($search_arr);
		for ($i = 0; $i < $maxi; $i++) {
			$current_str = ord($string[$i]) > 127 ? $string[$i] . $string[++$i] : $string[$i];
			if (in_array($current_str, $search_arr)) {
				$key = $search_flip[$current_str];
				$current_str = str_replace($search_arr[$key], $replace_arr[$key], $current_str);
			}
			$strcut .= $current_str;
		}
	}
	return $strcut . $dot;
}

/**
 * 计算文件的行数
 *
 * @param      string   $filepath  文件地址
 *
 * @return     integer  Number of file lines.
 *
 * @author     assad
 * @since      2019-06-29T16:12
 */
function countFileLines($filepath) {
	$fp = fopen($filepath, "r");
	$line = 0;
	while (fgets($fp)) {
		$line++;
	}

	fclose($fp);
	return $line;
}

/**
 * 重写addslashes
 *
 * @param      string|array
 * @param      integer  $force   The force
 *
 * @return     <type>   ( description_of_the_return_value )
 */
function ciAddslashes($string, $force = 1) {
	if (is_array($string)) {
		$keys = array_keys($string);
		foreach ($keys as $key) {
			$val = $string[$key];
			unset($string[$key]);
			$string[addslashes($key)] = ci_addslashes($val, $force);
		}
	} else {
		$string = addslashes($string);
	}
	return $string;
}

/**
 * 格式化时间
 *
 * @param      integer|string  $timestamp  时间戳
 * @param      string          $format     格式
 * @param      integer         $convert    The convert
 *
 * @return     array|string    ( description_of_the_return_value )
 */
function ciGmdate($timestamp = "", $format = "Y-n-d H:i", $convert = 1) {
	global $timeoffset;
	$todaytime = strtotime("today");
	$timeoffset = $timeoffset ? $timeoffset : 8;
	$timeformat = 'H:i';
	$s = gmdate($format, $timestamp + $timeoffset * 3600);
	if (!$convert) {
		return $s;
	}
	$lang = [
		0 => '前',
		1 => '天',
		2 => '前天',
		3 => '昨天',
		4 => '今天',
		5 => '小时',
		6 => '半',
		7 => '分',
		8 => '秒',
		9 => '刚才',
	];
	$timenow = time();
	$time = $timenow - $timestamp;
	$getdate = getdate();
	$thisyr_timestamp = mktime(0, 0, 0, 1, 1, $getdate["year"]);
	if ($time > ($timenow - $thisyr_timestamp)) {
		$s = gmdate("Y-n-d H:i", $timestamp + $timeoffset * 3600);
	}
	if ($todaytime <= $timestamp) {
		if (10800 < $time) {
			$d = date('n-d H:i', $timestamp);
			return $lang[4] . "&nbsp;" . gmdate($timeformat, $timestamp + $timeoffset * 3600);
		}
		if (3600 < $time) {
			return intval($time / 3600) . "&nbsp;" . $lang[5] . $lang[0];
		}
		if (1800 < $time) {
			return $lang[6] . $lang[5] . $lang[0];
		}
		if (60 < $time) {
			return intval($time / 60) . "&nbsp;" . $lang[7] . $lang[0];
		}
		if (0 < $time) {
			return $time . "&nbsp;" . $lang[8] . $lang[0];
		}
		if ($time == 0) {
			return $lang[9];
		}
		return $s;
	}
	if (0 <= ($days = intval(($todaytime - $timestamp) / 86400)) && $days < 2) {
		if ($days == 0) {
			return $lang[3] . "&nbsp;" . gmdate($timeformat, $timestamp + $timeoffset * 3600);
		}
		if ($days == 1) {
			return $lang[2] . "&nbsp;" . gmdate($timeformat, $timestamp + $timeoffset * 3600);
		}
	} else {
		return $s;
	}
}

/**
 * 调试函数，会终止程序运行
 *
 * @param      <type>   $var    调试的信息
 * @param      integer  $type   The type
 */
function debug($var = null, $type = 2) {
	if ($var === null) {
		$var = $GLOBALS;
	}
	header("Content-type:text/html;charset=utf-8");
	if ($type == 1) {
		echo '<pre>';
		print_r($var);
	} elseif ($type == 2) {
		dump_r($var);
	}
	exit();
}

/**
 * 下载本地文件
 *
 * @param      string  $file      本地文件地址
 * @param      string  $filename  下载的文件名称
 */
function downloadFile($file, $fileName = "") {
	$downFileName = $fileName ?: basename($file);
	if (file_exists($file)) {
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=' . $downFileName);
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		ob_clean();
		flush();
		readfile($file);
		exit(0);
	} else {
		exit(0);
	}
}

/**
 * 程序执行时间
 *
 * @param      boolean  $sec    false 则返回毫秒，否则返回秒
 *
 * @return     flaot
 */
function executeTime($sec = false) {
	$stime = explode(' ', SYS_START_TIME);
	$etime = explode(' ', microtime());
	$exe_time = number_format(($etime[1] + $etime[0] - $stime[1] - $stime[0]), 6);
	if ($sec) {
		return $exe_time;
	} else {
		return $exe_time * 1000;
	}
}

/**
 * 获得一个文件的内容
 *
 * @param      string  $url    文件URL
 *
 * @return     string  ( 文件内容)
 */
function fileGetContents($url) {
	$ctx = stream_context_create(
		[
			'http' => [
				'timeout' => 3, //设置一个超时时间，单位为秒
			],
		]
	);
	$content = file_get_contents($url, 0, $ctx);
	unset($ctx);
	return $content;
}

/**
 * 获得文件的扩展名
 *
 * @param      string  $fileName  文件名
 *
 * @return     string  文件扩展名
 */
function getFileExt($fileName) {
	return addslashes(strtolower(substr(strrchr($fileName, '.'), 1, 10)));
}

/**
 * 得到一个订单ID
 *
 * @param      string   $type    类型
 * @param      integer  $seqId   混淆ID
 * @param      integer  $lenth   长度
 *
 * @return     string
 */
function getOrderId($type = 'CI', $seqId = 0, $lenth = 18) {
	list($usec, $sec) = explode(" ", microtime());
	$orderId = date('ymdHis', $sec);
	$orderId .= substr($seqId * rand(11, 55), 0, 5);
	$orderId .= ceil($usec * pow(10, 7));
	$orderId = substr($orderId, 0, $lenth);
	return $type . $orderId;
}

/**
 * 得到JSON数据，AJAX使用
 *
 * @param      array    $data   The data
 * @param      string   $tip    The tip
 * @param      integer  $code   The code
 *
 * @return     array   The json data.
 */
function getJsonData($data = [], $tip = 'success', $code = 0) {
	$responseData = [];
	$responseData['code'] = $code;
	$responseData['msg'] = $tip;
	$responseData['data'] = $data;
	$responseData['execute_time'] = (string) executeTime() . ' ms';
	$ret = jsonEncode($responseData);
	return $ret;
}

/**
 * 得到数组概率精度
 *
 * @param      array  $proArr  [10,20]
 *
 * @return     string  The random.
 *
 * @author     assad
 * @since      2019-06-29T16:09
 */
function getRandPro($proArr) {
	$result = '';
	//概率数组的总概率精度
	$proSum = array_sum($proArr);

	//概率数组循环
	foreach ($proArr as $key => $proCur) {
		$randNum = mt_rand(1, $proSum);
		if ($randNum <= $proCur) {
			$result = $key;
			break;
		} else {
			$proSum -= $proCur;
		}
	}
	unset($proArr);

	return $result;
}

/**
 * belongsto Helpers.php
 * 重写fopen
 *
 * @param      string   $url       The url
 * @param      integer  $limit     读取字节数
 * @param      string   $post      是否为post
 * @param      string   $cookie    cookie参数
 * @param      string   $ip        IP
 * @param      integer  $timeout   超时时间
 * @param      integer  $block     阻塞模式0非阻塞 1阻塞
 *
 * @return     string   ( description_of_the_return_value )
 *
 * @author     assad
 * @since      2019-07-02T17:58
 */
function dfopen($url, $limit = 0, $post = '', $cookie = '', $ip = '', $timeout = 3, $block = 0) {
	$return = '';
	$matches = parse_url($url);
	$host = $matches['host'];
	$path = $matches['path'] ? $matches['path'] . ($matches['query'] ? '?' . $matches['query'] : '') : '/';
	$port = !empty($matches['port']) ? $matches['port'] : 80;

	if ($post) {
		$out = "POST $path HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		//$out .= "Referer: $boardurl\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$out .= "Host: $host\r\n";
		$out .= 'Content-Length: ' . strlen($post) . "\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cache-Control: no-cache\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
		$out .= $post;
	} else {
		$out = "GET $path HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		//$out .= "Referer: $boardurl\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$out .= "Host: $host\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
	}
	$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
	if (!$fp) {
		return '';
	} else {
		stream_set_blocking($fp, $block);
		stream_set_timeout($fp, $timeout);
		@fwrite($fp, $out);
		$status = stream_get_meta_data($fp);
		if (!$status['timed_out']) {
			while (!feof($fp)) {
				if (($header = @fgets($fp)) && ($header == "\r\n" || $header == "\n")) {
					break;
				}
			}

			$stop = false;
			while (!feof($fp) && !$stop) {
				$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
				$return .= $data;
				if ($limit) {
					$limit -= strlen($data);
					$stop = $limit <= 0;
				}
			}
		}
		@fclose($fp);
		return $return;
	}
}

/**
 * belongsto functions_helper.php
 * 得到字符串集合
 *
 * @return     string  The charset.
 *
 * @author     assad
 * @since      2019-11-11T17:16
 */
function getCharset() {
	return '0123456789abcdefghijklmnopqrstuvwxyz-';
}

/**
 * 得到远程文件的大小
 *
 * @param      string  $url    文件URL
 *
 * @return     integer  大小
 *
 * @author     assad
 * @since      2019-06-29T16:08
 */
function getRemoteFileSize($url) //远程获取文件长度
{
	$url = parse_url($url);
	if ($fp = @dfopen($url['host'], empty($url['port']) ? 80 : $url['port'], $error)) {
		fputs($fp, "GET " . (empty($url['path']) ? '/' : $url['path']) . " HTTP/1.1\r\n");
		fputs($fp, "Host:$url[host]\r\n\r\n");
		while (!feof($fp)) {
			$tmp = fgets($fp);
			if (trim($tmp) == '') {
				break;
			} else if (preg_match('/Content-Length:(.*)/si', $tmp, $arr)) {
				return trim($arr[1]);
			}
		}
		return FALSE;
	} else {
		return FALSE;
	}
}

/**
 * 得到本次访问的URL
 *
 * @return     string  The url.
 */
function getCurrentPageUrl() {
	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	$php_self = $_SERVER['PHP_SELF'] ? safeReplace($_SERVER['PHP_SELF']) : safeReplace($_SERVER['SCRIPT_NAME']);
	$path_info = isset($_SERVER['PATH_INFO']) ? safeReplace($_SERVER['PATH_INFO']) : '';
	$relate_url = isset($_SERVER['REQUEST_URI']) ? safeReplace($_SERVER['REQUEST_URI']) : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . safeReplace($_SERVER['QUERY_STRING']) : $path_info);
	return $sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url;
}

/**
 * belongsto Helpers.php
 * 得到两点的距离
 *
 * @param      string  $location1  The location 1
 * @param      string $location2  The location 2
 * @param      int  $unitType 单位 1米 2千米
 * @param      int  $decimal 保留小数位
 *
 * @author     assad
 * @since      2019-08-05T11:26
 */
function getDistance($location1, $location2, $unitType = 1, $decimal = 2) {

	if (!$location1 || !$location2) {
		return 0;
	}

	list($longitude1, $latitude1) = explode(",", $location1);
	list($longitude2, $latitude2) = explode(",", $location2);

	$EARTH_RADIUS = 6370.996; // 地球半径系数
	$PI = 3.1415926;

	$radLat1 = $latitude1 * $PI / 180.0;
	$radLat2 = $latitude2 * $PI / 180.0;

	$radLng1 = $longitude1 * $PI / 180.0;
	$radLng2 = $longitude2 * $PI / 180.0;

	$a = $radLat1 - $radLat2;
	$b = $radLng1 - $radLng2;

	$distance = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));

	$distance = $distance * $EARTH_RADIUS * 1000;

	if ($unitType == 2) {
		$distance = $distance / 1000;
	}

	return round($distance, $decimal);
}

/**
 * 得到随机字符串
 *
 * @param      integer  $tokenLen  长度
 *
 * @return     string
 */
function genRandomStr($tokenLen = 60) {
	if (file_exists('/dev/urandom')) {
		$randomData = file_get_contents('/dev/urandom', false, null, 0, 100) . uniqid(mt_rand(), true);
	} else {
		$randomData = mt_rand() . mt_rand() . mt_rand() . mt_rand() . microtime(true) . uniqid(mt_rand(), true);
	}
	return substr(hash('sha512', $randomData), 0, $tokenLen);
}

/**
 * belongsto Helpers.php
 * 得到时间范围
 *
 * @param      integer  $range  The range
 *
 * @return     array    The time range.
 *
 * @author     assad
 * @since      2019-09-26T18:00
 */
function getTimeRange($range = 1) {
	$startTime = 0;
	$endTime = time();
	$year = date('Y');
	$month = date('m');

	switch ($range) {
	case 1: //最近7日
		$startTime = strtotime('- 7 days');
		break;
	case 2: //本月
		$t = date('t');
		$startTime = mktime(0, 0, 0, $month, 1, $year);
		$endTime = mktime(23, 59, 59, $month, $t, $year);
		break;
	case 3: //最近半年
		$startTime = strtotime('- 180 days');
		break;
	default:
		// code...
		break;
	}
	return ['start' => $startTime, 'end' => $endTime];
}

/**
 * belongsto Helpers.php
 * 获得时间戳范围
 *
 * @param      integer  $type   The type
 *
 * @author     assad
 * @since      2019-09-19T10:34
 */
function getRangeTimestamp($type) {
	switch ($type) {
	case 1: //今日
		$startTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		$endTime = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1;
		break;
	case 2: //昨日
		$startTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
		$endTime = mktime(0, 0, 0, date('m'), date('d'), date('Y')) - 1;
		break;
	case 3: //本周
		$startTime = mktime(0, 0, 0, date('m'), date('d') - date('w') + 1, date('y'));
		$endTime = time();
		break;
	case 4: //上周
		$startTime = mktime(0, 0, 0, date('m'), date('d') - date('w') + 1 - 7, date('Y'));
		$endTime = mktime(23, 59, 59, date('m'), date('d') - date('w') + 7 - 7, date('Y'));
		break;
	case 5: //本月
		$startTime = mktime(0, 0, 0, date('m'), 1, date('Y'));
		$endTime = mktime(23, 59, 59, date('m'), date('t'), date('Y'));
		break;
	case 6: //上月
		$startTime = strtotime(date('Y-m-01 00:00:00', strtotime('-1 month')));
		$endTime = strtotime(date("Y-m-d 23:59:59", strtotime(-date('d') . 'day')));
		break;
	default:
		// code...
		break;
	}
	return [$startTime, $endTime];
}

/**
 * 得到JSON数据，AJAX使用
 *
 * @param      array    $data   The data
 * @param      string   $tip    The tip
 * @param      integer  $code   The code
 *
 * @return     array   The json data.
 */
function getResponseData($data = [], $tip = 'success', $code = 0) {
	$responseData = [];
	$responseData['code'] = $code;
	$responseData['msg'] = $tip;
	$responseData['data'] = $data;
	$responseData['executeTime'] = (string) executeTime() . ' ms';
	return $responseData;
}

/**
 * belongsto Helpers.php
 *  URL重定向
 *
 * @param      string   $uri     需要跳转的URL
 * @param      string   $method  auto,refresh
 * @param      integer  $code    HTTP状态码
 *
 * @author     assad
 * @since      2019-07-09T16:27
 */
function jump($uri = '', $method = 'auto', $code = NULL) {
	if (!filter_var($uri, FILTER_VALIDATE_URL)) {
		$url = base_url();
		if ($uri != '/') {
			$uri = $url . '/' . $uri;
		}
	}
	// IIS environment likely? Use 'refresh' for better compatibility
	if ($method === 'auto' && isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== FALSE) {
		$method = 'refresh';
	} elseif ($method !== 'refresh' && (empty($code) OR !is_numeric($code))) {
		if (isset($_SERVER['SERVER_PROTOCOL'], $_SERVER['REQUEST_METHOD']) && $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.1') {
			$code = ($_SERVER['REQUEST_METHOD'] !== 'GET')
			? 303// reference: http://en.wikipedia.org/wiki/Post/Redirect/Get
			 : 307;
		} else {
			$code = 302;
		}
	}

	switch ($method) {
	case 'refresh':
		header('Refresh:0;url=' . $uri);
		break;
	default:
		header('Location: ' . $uri, TRUE, $code);
		break;
	}
	exit;
}

/**
 * belongsto Helpers.php
 * json encode 去掉转移以及unicode
 *
 * @param      array   $array  The array
 *
 * @return     <type>  ( description_of_the_return_value )
 *
 * @author     assad
 * @since      2019-09-26T18:03
 */
function jsonEncode($array = [], $numberCheck = 0) {
	if ($numberCheck) {
		$jsonNumericCheck = JSON_NUMERIC_CHECK;
	} else {
		$jsonNumericCheck = 1;
	}
	return json_encode($array, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | $jsonNumericCheck);
}

/**
 * belongsto functions_helper.php
 * 数字转字符串
 *
 * @param      integer  $num    The number
 *
 * @return     string   转换后的字符串
 *
 * @author     assad
 * @since      2019-11-11T17:18
 */
function intToStr($num) {
	$chars = getCharset();
	$string = '';
	$len = strlen($chars);
	while ($num >= $len) {
		$mod = bcmod($num, $len);
		$num = bcdiv($num, $len);
		$string = $chars[$mod] . $string;
	}
	$string = $chars[$num] . $string;
	return $string;
}

/**
 * 检测是否是email地址
 *
 * @param      string   $email  email地址
 *
 * @return     boolean  True if email, False otherwise.
 */
function isEmail($email) {
	return strlen($email) > 6 && strlen($email) <= 32 && preg_match("/^([A-Za-z0-9\-_.+]+)@([A-Za-z0-9\-]+[.][A-Za-z0-9\-.]+)$/", $email);
}

/**
 * 检测字符串是否为UTF8编码
 *
 * @param      string   $string  待检字符串
 *
 * @return     boolean  True if utf 8, False otherwise.
 */
function isUtf8($string) {
	return preg_match('%^(?:
                    [\x09\x0A\x0D\x20-\x7E] # ASCII
                    | [\xC2-\xDF][\x80-\xBF] # non-overlong 2-byte
                    | \xE0[\xA0-\xBF][\x80-\xBF] # excluding overlongs
                    | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
                    | \xED[\x80-\x9F][\x80-\xBF] # excluding surrogates
                    | \xF0[\x90-\xBF][\x80-\xBF]{2} # planes 1-3
                    | [\xF1-\xF3][\x80-\xBF]{3} # planes 4-15
                    | \xF4[\x80-\x8F][\x80-\xBF]{2} # plane 16
                    )*$%xs', $string);
}

/**
 * 获得IP地址
 *
 * @return     string  IP地址
 */
function ip() {
	if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$ip = getenv('HTTP_CLIENT_IP');
	} elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$ip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$ip = getenv('REMOTE_ADDR');
	} elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return preg_match('/[\d\.]{7,15}/', $ip, $matches) ? $matches[0] : '';
}

/**
 * 返回微秒
 *
 * @return     flaot
 */
function microtimeFloat() {
	list($usec, $sec) = explode(" ", microtime());
	return ((float) $usec + (float) $sec);
}

/**
 * 创建文件目录
 *
 * @param      string   $pathname  要创建的目录
 * @param      <type>   $mode      权限
 *
 * @return     boolean  ( 是否创建成功 )
 */
function rMkdir($pathname, $mode = 0777) {
	if (strpos($pathname, '..') !== false) {
		return false;
	}
	$pathname = rtrim(preg_replace(array('/\\{1,}/', '/\/{2,}/'), '/', $pathname), '/');
	if (is_dir($pathname)) {
		return true;
	}

	is_dir(dirname($pathname)) || rMkdir(dirname($pathname), $mode);
	return is_dir($pathname) || @mkdir($pathname, $mode);
}

/**
 * belongsto functions_helper.php
 * 多为数组排序
 *
 * @param      array  $multi_array  数组
 * @param      string  $sort_key     排序的KEY
 * @param      string  $sort         排序方式
 * @param      string  $sort_key1    排序KEY2
 * @param      string  $sort1        排序方式
 *
 * @return     array  返回排序好的数组
 *
 * @author     assad
 * @since      2019-06-29T16:10
 */
function multiArraySort($multi_array, $sort_key, $sort = SORT_DESC, $sort_key1 = '', $sort1 = SORT_DESC) {
	if (is_array($multi_array)) {
		foreach ($multi_array as $row_array) {
			if (is_array($row_array)) {
				$key_array[] = $row_array[$sort_key];
				if ($sort_key1) {
					$key_array1[] = $row_array[$sort_key1];
				}
			} else {
				return FALSE;
			}
		}
	} else {
		return FALSE;
	}
	if ($key_array1) {
		array_multisort($key_array, $sort, SORT_NUMERIC, $key_array1, $sort1, SORT_NUMERIC, $multi_array);
	} else {
		array_multisort($key_array, $sort, SORT_NUMERIC, $multi_array);
	}
	return $multi_array;
}

/**
 * belongsto Helpers.php
 * 输出JSON
 *
 * @param      array    $data   The data
 * @param      string   $msg    The message
 * @param      integer  $code   The code
 *
 * @author     assad
 * @since      2019-07-27T10:23
 */
function outPutJson($data = [], $msg = 'success', $code = 0) {
	$data = getResponseData($data, $msg, $code);
	$jsonData = jsonEncode($data);
	header("Content-type: application/json; charset=utf-8");
	echo $jsonData;
	exit(0);
}

/**
 * 随机字符串
 *
 * @param      integer  $length   长度
 * @param      integer  $numeric  是否只返回数字
 *
 * @return     string
 */
function random($length, $numeric = 0) {
	$seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
	$seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
	if ($numeric) {
		$hash = '';
	} else {
		$hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
		$length--;
	}
	$max = strlen($seed) - 1;
	for ($i = 0; $i < $length; $i++) {
		$hash .= $seed{mt_rand(0, $max)};
	}
	return $hash;
}

/**
 * RC4算法
 *
 * @param      string   $string     要加密的字符串
 * @param      string   $operation  ENCODE加密，DECODE解密
 * @param      string   $key        加密使用的KEY
 * @param      integer  $expiry     过期时间
 *
 * @return     string   返回加密或者解决的字符串，加密结果为base64
 */
function RC4($string, $operation = 'DECODE', $key = 'ci', $expiry = 0) {
	$ckey_length = 4;
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya . md5($keya . $keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for ($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for ($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for ($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if ($operation == 'DECODE') {
		if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc . str_replace('=', '', base64_encode($result));
	}
}

/**
 * 无需等待执行一条Linux命令
 *
 * @param      string  $cmd    要执行的命令
 */
function rumcmdnowait($cmd) {
	pclose(popen($cmd, 'r'));
}

/**
 * 执行一条Linux命令
 *
 * @param      string  $cmd    要执行的命令
 */
function rumcmd($cmd) {
	passthru($cmd);
}

/**
 * belongsto Helpers.php
 * 随机一个浮点数
 *
 * @param      integer  $min    The minimum
 * @param      integer  $max    The maximum
 * @param      integer  $decimals  小数位
 *
 * @return     <type>   ( description_of_the_return_value )
 *
 * @author     assad
 * @since      2019-07-27T10:24
 */
function randomDecimals($min, $max, $decimals = 2) {
	$scale = pow(10, $decimals);
	return mt_rand($min * $scale, $max * $scale) / $scale;
}

/**
 * belongsto functions_helper.php
 * 字符串反序列化
 *
 * @param      string   $str    The string
 * @param      array    $array  The array
 * @param      integer  $i      { parameter_description }
 *
 * @return     array    ( description_of_the_return_value )
 *
 * @author     assad
 * @since      2019-11-12T23:36
 */
function Runserialize($str, $array = array(), $i = 1) {
	$str = explode("\n$i\n", $str);
	foreach ($str as $key => $value) {
		$k = substr($value, 0, strpos($value, "\t"));
		$v = substr($value, strpos($value, "\t") + 1);
		if (strpos($v, "\n") !== false) {
			$next = $i + 1;
			$array[$k] = Runserialize($v, $array[$k], $next);
		} elseif (strpos($v, "\t") !== false) {
			$array[$k] = Rarray($array[$k], $v);
		} else {
			$array[$k] = $v;
		}
	}
	return $array;
}

/**
 * belongsto functions_helper.php
 * 序列化数组
 *
 * @param      array   $array   The array
 * @param      string  $string  The string
 *
 * @return     array   ( description_of_the_return_value )
 *
 * @author     assad
 * @since      2019-11-12T23:37
 */
function Rarray($array, $string) {
	$k = substr($string, 0, strpos($string, "\t"));
	$v = substr($string, strpos($string, "\t") + 1);
	if (strpos($v, "\t") !== false) {
		$array[$k] = Rarray($array[$k], $v);
	} else {
		$array[$k] = $v;
	}
	return $array;
}

/**
 * belongsto functions_helper.php
 * 数组序列化
 *
 * @param      array    $array  The array
 * @param      string   $ret    The ret
 * @param      integer  $i      { parameter_description }
 *
 * @return     string   ( description_of_the_return_value )
 *
 * @author     assad
 * @since      2019-11-12T23:38
 */
function Rserialize($array, $ret = '', $i = 1) {
	if (!is_array($array)) {
		return null;
	}
	foreach ($array as $k => $v) {
		if (is_array($v)) {
			$next = $i + 1;
			$ret .= "$k\t";
			$ret = Rserialize($v, $ret, $next);
			$ret .= "\n$i\n";
		} else {
			$ret .= "$k\t$v\n$i\n";
		}
	}
	if (substr($ret, -3) == "\n$i\n") {
		$ret = substr($ret, 0, -3);
	}
	return $ret;
}

/**
 * belongsto functions_helper.php
 * 字符串转数字
 *
 * @param      string   $string  待转字符串
 *
 * @return     integer  转换后的数字
 *
 * @author     assad
 * @since      2019-11-11T17:17
 */
function strToInt($string) {
	$chars = getCharset();
	$integer = 0;
	$string = strrev($string);
	$baselen = strlen($chars);
	$inputlen = strlen($string);
	for ($i = 0; $i < $inputlen; $i++) {
		$index = strpos($chars, $string[$i]);
		$integer = bcadd($integer, bcmul($index, bcpow($baselen, $i)));
	}
	$integer = explode('.', $integer)[0];
	return $integer;
}

/**
 * 安全的URLENCODE
 *
 * @param      string  $url    The url
 *
 * @return     string  得到安全的URL
 */
function scUrlEncode($url) {
	static $fix = ['%21', '%2A', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D'];
	static $replacements = ['!', '*', ';', ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]"];
	return str_replace($fix, $replacements, urlencode($url));
}

/**
 * 写一条日志
 *
 * @param      string   $string  待写内容
 * @param      string   $t       切割类型day按天
 *
 * @return     boolean  ( description_of_the_return_value )
 */
function sendLog($string, $saveDir = 'sendlog', $t = 'day') {
	if (!$string) {
		return false;
	}
	if (is_array($string)) {
		$string = jsonEncode($string);
	}
	$timestamp = time();
	if ($t == 'day') {
		$f = date('Ymd', $timestamp);
		$fileName = FCPATH . 'data/logs/' . $saveDir . '/' . $f . '.log';
	}
	$logTime = date('Y/m/d H:i:s', $timestamp);
	$record = $logTime . ' - ' . $string . "\n";
	writeLog($fileName, $record, 'ab');
}

/**
 * 安全字符串替换
 *
 * @param      string   $string  待转换字符串
 *
 * @return     string  返回安全的字符串
 */
function safeReplace($string) {
	$search = ['%20', '%27', '%2527', '*', '"', "'", '"', ';', '<', '>', "{", '}', '\\'];
	$replace = ['', '', '', '', '&quot;', '', '', '', '&lt;', '&gt;', '', '', ''];
	$string = str_replace($search, $replace, $string);
	return $string;
}

/**
 * belongsto Helpers.php
 * js encodeURIComponent
 *
 * @param      string  $string  The string
 *
 * @return     string  ( description_of_the_return_value )
 *
 * @author     assad
 * @since      2019-08-17T16:30
 */
function urlencode4js($string) {
	$fix = ['%27', '%21', '%2A', '%28', '%29'];
	$replacements = ["'", '!', '*', '(', ')'];
	return str_replace($fix, $replacements, rawurlencode($string));
}

/**
 * belongsto functions_helper.php
 * IP地址校验
 *
 * @param      string  $ip     IP地址
 *
 * @return     boolean          ( description_of_the_return_value )
 *
 * @author     assad
 * @since      2019-11-12T23:38
 */
function validIp($ip) {
	if (strtolower($ip) === 'unknown') {
		return false;
	}
	$ip = ip2long($ip);
	if ($ip !== false && $ip !== -1) {
		$ip = sprintf('%u', $ip);
		if ($ip >= 0 && $ip <= 50331647) {
			return false;
		}
		if ($ip >= 167772160 && $ip <= 184549375) {
			return false;
		}
		if ($ip >= 2130706432 && $ip <= 2147483647) {
			return false;
		}
		if ($ip >= 2851995648 && $ip <= 2852061183) {
			return false;
		}
		if ($ip >= 2886729728 && $ip <= 2887778303) {
			return false;
		}
		if ($ip >= 3221225984 && $ip <= 3221226239) {
			return false;
		}
		if ($ip >= 3232235520 && $ip <= 3232301055) {
			return false;
		}
		if ($ip >= 4294967040) {
			return false;
		}
	}
	return true;
}

/**
 * 写日志内容到文件
 *
 * @param      integer   $fileName  写入文件路径
 * @param      integer   $data       内容
 * @param      string   $method     打开文件方法
 * @param      integer  $ifLock     是否加锁
 * @param      integer  $check      The check
 * @param      integer  $chmod      The chmod
 *
 * @return     boolean
 */
function writeLog($fileName, $data, $method = 'wb+', $ifLock = 1, $check = 1, $chmod = 1) {
	if (!$fileName) {
		return false;
	}

	if ($check && strpos($fileName, '..') !== false) {
		return false;
	}

	if (!is_dir(dirname($fileName)) && !rMkdir(dirname($fileName), 0777)) {
		return false;
	}

	$ret = writeFile($fileName, $data, $method, $ifLock, $chmod);
	return $ret;
}

/**
 * 写文件
 *
 * @param      string   $filePath  文件路径
 * @param      string   $data       要写入的内容
 * @param      string   $method     打开文件方法
 * @param      integer  $ifLock     是否加锁
 * @param      integer  $chmod      权限
 *
 * @return     boolean
 */
function writeFile($filePath, $data, $method = 'wb+', $ifLock = 1, $chmod = 1) {
	if (($handle = fopen($filePath, $method)) == false) {
		return false;
	}

	if ($ifLock) {
		flock($handle, LOCK_EX);
	}

	if (fwrite($handle, $data) === false) {
		return false;
	}

	if ($method == "wb+") {
		ftruncate($handle, strlen($data));
	}

	if ($ifLock) {
		flock($handle, LOCK_UN);
	}

	fclose($handle);
	$chmod && @chmod($filename, 0777);
	return true;
}