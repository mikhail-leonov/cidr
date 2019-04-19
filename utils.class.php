<?php
/**
 * Utils class.
 *
 * Utility Functions to manage IPv4/6 
 * Supports PHP 5.3+ (32 & 64 bit)
 * @author Mikhail Leonov <mikecommon@gmail.copm>
 *
 */
class Utils
{
	/// <summary>
	/// Is A Bubble Range like 127.*.0.* 
	/// </summary>
	function isABubbleRange( $range ) {
		$result = false;
		
		$parts = explode( ".", $range );
		$masks = array();
		foreach( $parts as $k => $v ) {
			$pos1 = ( strpos( $v, "-" ) !== false ); 
			$pos2 = ( strpos( $v, "*" ) !== false ); 
			if ( $pos1 || $pos2 ) { $masks[] = $k; }
		}
		if ( count( $masks ) > 1 ) {
			for( $index = 0; $index < count( $masks ) - 1; $index++ ) {
				if ( $masks[ $index ] + 1 <  $masks[ $index + 1 ] ) {
					$result = true;
				}
			}
		}
		return $result;
	}
	
	/// <summary>
	/// Is A Net Mask Range
	/// </summary>
	function isANetMaskRange( $range ) {
		$result = false;
		$arr = explode( "/", $range ); 
		if ( count( $arr ) === 2 ) {
			$arr1 = explode( ".", $arr[ 0 ] );
			$arr2 = explode( ".", $arr[ 1 ] );
			$result = ( count($arr1) === 4 ) && ( count($arr2) === 4 );
		}
		return $result;
	}
	
	/// <summary>
	/// Is A Two IPv4 Range like 12.23.34.45-23.34.45.65
	/// </summary>
	function isATwoIPv4Range( $range ) {
		$result = false;
		$arr = explode( "-", $range ); 
		if ( count( $arr ) === 2 ) {
			$arr1 = explode( ".", $arr[ 0 ] );
			$arr2 = explode( ".", $arr[ 1 ] );
			$result = ( count($arr1) === 4 ) && ( count($arr2) === 4 );
		}
		return $result;
	}
	
	/// <summary>
	/// Is A Two IPv6 Range like dc00:7c4:cd04:9e9e:8891:91b:b5f1:70d6-dc00:7c4:cd04:9e9e:8891:91b:b5f1:ffff
	/// </summary>
	function isATwoIPv6Range( $range ) {
		$arr = explode( "-", $range ); 
		$result = ( count( $arr ) === 2 );
		return $result;
	}
	
	/// <summary>
	/// Is Already IPv6 CIDR like dc00:7c4:cd04:9e9e:8891:91b:b5f1:70d6/128
	/// </summary>
	function isAIPv6CIDR( $range ) {
		$result = false;
		$arr = explode( "/", $range ); 
		if ( count( $arr ) === 2 ) {
			if ( strlen(trim($arr[0])) > 0 ) {
				if ( strlen(trim($arr[1])) > 0 ) {
					$result = true;
				}
			}
		}
		return $result;
	}
	
	/// <summary>
	/// Check IP octect to be non alpha aand not more than 255
	/// </summary>
	function isLimitedIP( $ip ) {
		$result = true;
		$arr = explode( ".", $ip ); 
		foreach( $arr as $k => $v ) {
			if ( is_numeric ( $v ) ) { 
				$num = intval( $v );
				if ( $num > 255 ) { $result = false; }
			} else {
				$result = false;
			}
		}
		return $result;
	}
	
	/// <summary>
	/// Get first IP in the 2 IP range string
	/// </summary>
	function getFirstIP2( $range ) {
		$arr = explode( "-", $range ); 
		return trim( $arr[ 0 ] );
	}
	
	/// <summary>
	/// Get first IP in the 2 IP range string
	/// </summary>
	function getLastIP2( $range ) {
		$arr = explode( "-", $range ); 
		return trim( $arr[ 1 ] );
	}
	/// <summary>
	/// Get first IP in the 2 IP range string
	/// </summary>
	function getFirstIP3( $range ) {
		$arr = explode( "/", $range ); 
		return trim( $arr[ 0 ] );
	}
	
	/// <summary>
	/// Get first IP in the 2 IP range string
	/// </summary>
	function getLastIP3( $range ) {
		$arr = explode( "/", $range ); 
		return trim( $arr[ 1 ] );
	}
	
	/// <summary>
	/// Get first IP in the range string
	/// </summary>
	function getFirstIP( $range ) {
		$result = "";
		$splitter = "";
		$arr = explode( ".", $range ); 
		foreach( $arr as $k => &$v ) {
			$r = $v ;
			if ( $r === "*" ) { $r = 0; }
			$p = explode( "-", $r );
			$r = $p[0];
			$result = $result . $splitter . $r;
			$splitter = ".";
		}
		return $result;
	}
	
	/// <summary>
	/// Get last IP in the range string
	/// </summary>
	function getLastIP( $range ) {
		$result = "";
		$splitter = "";
		$arr = explode( ".", $range ); 
		foreach( $arr as $k => &$v ) {
			$r = $v ;
			if ( $r === "*" ) { $r = 255; }
			$p = explode( "-", $r );
			if (count($p) > 1) { $r = $p[1]; } else { $r = $p[0]; }
			$result = $result . $splitter . $r;
			$splitter = ".";
		}
		return $result;
	}
	
	/// <summary>
	/// Explode multiple chars
	/// </summary>
	function explode2( $delimiters, $string ) {
		$result = array( $string );
		$delimitersCount = count( $delimiters );
		if ( $delimitersCount > 0 ) {
			$ready = $string;
			if ( $delimitersCount > 1 ) {
				$ready = str_replace( $delimiters, $delimiters[ 0 ], $string );
			}
			$result = explode( $delimiters[0], $ready );
		}
		return  $result;
	}	
	/// <summary>
	/// Check value and replace it by default value if it's null
	/// </summary>
	function checkForNull($val, $def)
	{
		$result = $val; if (!isset($result)) { $result = $def; } return $result;
	}
	/// <summary>
	/// Add left and right slash
	/// </summary>
	function Slash($url) {
		return utils::LSlash( utils::RSlash( $url ) );
	}
	
	/// <summary>
	/// Add left slash
	/// </summary>
	function LSlash($url) {
		$result = $url;
		if ( substr( $result, 0, 1 ) !== "/" ) {
			$result = $result . "/";
		}
		return $result;
	}
	
	/// <summary>
	/// Del left slash
	/// </summary>
	function UnLSlash($url) {
		$result = $url;
		if ( substr( $result, 0, 1 ) === "/" ) {
			$result = substr( $result, 1, strlen( $result ) - 1 );
		}
		return $result;
	}
	
	/// <summary>
	/// Add right slash
	/// </summary>
	function RSlash($url) {
		$result = $url;
		if ( substr( $result, -1 ) !== "/" ) {
			$result = $result . "/";
		}
		return $result;
	}
	
	/// <summary>
	/// Del right slash
	/// </summary>
	function UnRSlash($url) {
		$result = $url;
		if ( substr( $result, -1 ) === "/" ) {
			$result = substr( $result, 0, strlen( $result ) - 1 );
		}
		return $result;
	}
	
	/// <summary>
	/// Del left and right slash
	/// </summary>
	function UnSlash($url) {
		return Utils::UnRSlash( Utils::UnLSlash( $url ) );
	}
	
	/// <summary>
	/// Encode Char
	/// </summary>
	function EncodeChar($char) {
		return chr( ord("A") + $char );
	}
	
	/// <summary>
	/// Encode String
	/// </summary>
	function EncodeString( $str ) {
		$result = "";
		for ( $i = 0; $i < strlen($str); $i++ ) {
			$ch = substr( $str, $i, 1 ); $ech = Utils::EncodeChar( $ch ); $result = $result . $ech;
		}
		return $result;
	}
	
	/// <summary>
	/// Decode Char
	/// </summary>
	function DecodeChar($char) {
		$result = ord($char) - ord("A");
		if ($result > 9) { $result = 0; }
		return "$result";
	}
	
	/// <summary>
	/// Decode String
	/// </summary>
	function DecodeString( $str ) {
		$result = "";
		for ( $i = 0; $i < strlen( $str ); $i++ ) {
			$ch = substr( $str, $i, 1 ); $ech = Utils::DecodeChar( $ch ); $result = $result . $ech;
		}
		return $result;
	}
	
	/// <summary>
	/// Change token symbol repeatedly
	/// </summary>
	function ChangeSymbol( &$result, $symbol, $new ) {
		while ( strpos( $result, $symbol ) !== false ) {
			$result = str_replace( $symbol, $new, $result );
		}
	}
	
	/// <summary>
	/// Get value from $attributes by name or return default value
	/// </summary>
	function get_attr_value($attributes, $name, $default) {
		$result = $default; 
		if (isset($attributes)) {
			if (isset($attributes[$name])) {
				$result = $attributes[$name];
			}
		}
		return $result;
	}
}
