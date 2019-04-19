<?php
/**
 * URL helper class.
 *
 * Utility Functions to manage Url parts
 * Supports PHP 5.3+ (32 & 64 bit)
 * @author Mikhail Leonov <mikecommon@gmail.copm>
 *
 * Requires utils.class.php 
 */
class Url
{
	/// <summary>
	/// Url holder
	/// </summary>
	var $url = "";

	/// <summary>
	/// Parameters
	/// </summary>
	var $parameters = array();

	/// <summary>
	/// Path
	/// </summary>
	var $path = "";
	
	/// <summary>
	/// Filename
	/// </summary>
	var $filename = "";

	/// <summary>
	/// Filename
	/// </summary>
	var $defaultfilename = "index.html";
	
	/// <summary>
	/// File extention
	/// </summary>
	var $fileext = "";
	
	/// <summary>
	/// Script = path/filename
	/// </summary>
	var $script = "";
	
	/// <summary>
	/// Port
	/// </summary>
	var $port = "";
	
	/// <summary>
	/// Protocol
	/// </summary>
	var $protocol = "";

	/// <summary>
	/// Host
	/// </summary>
	var $host = "";

	/// <summary>
	/// Username
	/// </summary>
	var $username = "";

	/// <summary>
	/// Password
	/// </summary>
	var $password = "";
	
	/// <summary>
	/// Constructor
	/// </summary>
	function Url( $url = false )
	{
		if ( $url === false ) 
		{ 
			$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		}
		$this->Assign( $url );
	}
	
	/// <summary>
	/// Assign
	/// </summary>
	function FixUrlEntities( $url )
	{
		$result = $url;
		
		Utils::ChangeSymbol( $result, "&amp;", "&" );
		
		return $result;
	}
	
	/// <summary>
	/// Assign
	/// </summary>
	function Assign( $url )
	{
		$this->Clear();
		if ( $url !== "" ) 
		{
			$this->url = $this->FixUrlEntities( $url );
			$this->Parse();
		}
	}
	
	/// <summary>
	/// Compare two objects
	/// </summary>
	function Compare( $urlManager )
	{
		$result = true;
		
		if ( count($this->parameters) != count($urlManager->parameters) ) { $result = false; }
		foreach( $this->parameters as $key => $val ) { if ( $this->parameters[ $key ] != $urlManager->parameters[ $key ] ) { $result = false; } }
		foreach( $urlManager->parameters as $key => $val ) { if ( $this->parameters[ $key ] != $urlManager->parameters[ $key ] ) { $result = false; } }
		if ( $this->path != $urlManager->path ) { $result = false; }
		if ( $this->filename != $urlManager->filename ) { $result = false; }
		if ( $this->fileext != $urlManager->fileext ) { $result = false; }
		if ( $this->script != $urlManager->script ) { $result = false; }
		if ( $this->port != $urlManager->port ) { $result = false; }
		if ( $this->protocol != $urlManager->protocol ) { $result = false; }
		if ( $this->host != $urlManager->host ) { $result = false; }
		if ( $this->username != $urlManager->username ) { $result = false; }
		if ( $this->password != $urlManager->password ) { $result = false; }
		
		return $result;
	}
	
	/// <summary>
	/// Clear
	/// </summary>
	function Clear()
	{
		$this->url = "";
		$this->parameters = array();
		$this->path = "";
		$this->filename = "";
		$this->fileext = "";
		$this->port = "";
		$this->protocol = "";
		$this->host = "";
		$this->username = "";
		$this->password = "";
		$this->script = "";
	}
	
	/// <summary>
	/// Parse Url
	/// </summary>
	function Parse()
	{
		$parts = explode( "?", $this->url );
		$url = $parts[ 0 ];
		unset( $parts[ 0 ] );
		$params = implode( "?", $parts );
		
		$arr = explode( "#", $params );
		$params = $arr[ 0 ];
		$parameters = explode( "&", $params );
		foreach( $parameters as $index => $parameter )
		{
			$items = explode( "=", $parameter );
			$p_name = $items[ 0 ] ;
			unset( $items[ 0 ] );
			$p_value = implode( "=", $items ); 
			$this->parameters[ urldecode( $p_name ) ] = urldecode( $p_value );
		}
		
		$parts = parse_url( $url );
		
		if ( array_key_exists( "scheme", $parts ) ) 
		{ 
			$this->protocol = $parts["scheme"];
		}
		if ( array_key_exists( "host", $parts ) ) 
		{ 
			$this->host = $parts["host"];
		}
		if ( array_key_exists( "user", $parts ) ) 
		{ 
			$this->username = $parts["user"];
		}
		if ( array_key_exists( "pass", $parts ) ) 
		{ 
			$this->password = $parts["pass"];
		}
		if ( array_key_exists( "path", $parts ) ) 
		{ 
			$this->script = $parts["path"];
			$name = $parts["path"];
			$arr = explode( "/", $name );
			
			if ( strpos( $name, ".") !== false )
			{
				$this->filename = $arr [ count( $arr ) - 1 ];
				unset( $arr [ count( $arr ) - 1 ] );
			}
			$this->path = implode( "/", $arr );
			$this->path = Utils::Slash( $this->path );
			
		}
		if ( array_key_exists( "port", $parts ) ) 
		{ 
			$port = $parts["port"];
			$this->port = "$port";
		}
		
		if (  $this->filename === "" ) 
		{ 
			$this->SetFileName( $this->defaultfilename ); 
		}
		$this->SetFileExt();
	}
	
	
	/// <summary>
	/// Set url parts section
	/// </summary>

	
	
	/// <summary>
	/// Set file ext
	/// </summary>
	function SetFileExt()
	{
		if ( strpos( $this->filename, "." ) !== false )
		{
			$arr = explode( ".", $this->filename );
			$this->fileext = $arr[ count( $arr ) - 1 ];
		}
	}
	
	/// <summary>
	/// Set file name
	/// </summary>
	function SetFileName( $fileName )
	{
		$this->filename = $fileName;
		$this->SetFileExt();
		$this->script = $this->path . $fileName;
	}

	



	
	/// <summary>
	/// Url to different string section
	/// </summary>


	
	
	
	/// <summary>
	/// UrlToString Encoded
	/// </summary>
	function UrlToStringEncoded( )
	{
		return urlencode( $this->UrlToString() );
	}
	
	/// <summary>
	/// UrlToString
	/// </summary>
	function UrlToString( $encode = false )
	{
		$base = $this->UrlToStringNP( $encode );
		
		$params = "";
		if ( count( $this->parameters ) > 0 )
		{
			$split = "";
			foreach( $this->parameters as $k => $v )
			{
				if ( trim($k) != "" )
				{
					if ($encode) 
					{
						$params .= "{$split}{$k}=" . urlencode("{$v}") ;
					} 
					else 
					{
						$params .= "{$split}{$k}=" . "{$v}" ;
					}
					$split = "&";
				}
			}
			if ( trim($params) != "" )
			{
				$params = "?" . $params ;
			}
		}
		
		return "{$base}{$params}";
	}
	
	/// <summary>
	/// UrlToString NO parameters, NO anchors
	/// </summary>
	function UrlToStringNP( $encode = false )
	{
		$protocol = $this->protocol;
		
		$splitter = "://";
		
		$host = $this->host;
		
		$script = $this->script;
		
		$pass = "";	
		if ( $this->password !== "" && $this->username !== "" )
		{
			$pass = "{$this->username}:{$this->password}@";	
		}
		$port = "";
		if ( $this->port !== "" )
		{
			$port = ":{$this->port}";
		}
		
		return "{$protocol}{$splitter}{$pass}{$host}{$port}{$script}";
	}
	
	/// <summary>
	/// UrlToRelative NO parameters, NO anchors
	/// </summary>
	function UrlToRelative( $encode = false )
	{
		$base = $this->UrlToRelativeNP( $encode );
		
		$params = "";
		if ( count( $this->parameters ) > 0 )
		{
			$split = "";
			foreach( $this->parameters as $k => $v )
			{
				if ( trim($k) != "" )
				{
					if ($encode) 
					{
						$params .= "{$split}{$k}=" . urlencode("{$v}") ;
					} 
					else 
					{
						$params .= "{$split}{$k}=" . "{$v}" ;
					}
					$split = "&";
				}
			}
			if ( trim($params) != "" )
			{
				$params = "?" . $params ;
			}
		}
		
		return "{$base}{$params}";
	}
	
	/// <summary>
	/// UrlToRelative NO parameters, NO anchors
	/// </summary>
	function UrlToRelativeNP( $encode = false )
	{
		$script = $this->script;
		return "{$script}";
	}




	
	/// <summary>
	/// Parameter related functions
	/// </summary>

	
	
	/// <summary>
	/// Get Parameter Value
	/// </summary>
	function GetUrlParameterValue( $name, $def = null )
	{
		$result = $def;
		if ( $this->IsUrlParameterExists( $name ) )
		{
			$result = $this->parameters[ $name ] ;
		}
		return $result;
	}
	
	/// <summary>
	/// Is Parameter Exists
	/// </summary>
	function IsUrlParameterExists( $name )
	{
		return array_key_exists( $name, $this->parameters );
	}
	
	/// <summary>
	/// Set Parameter Value
	/// </summary>
	function SetUrlParameterValue( $name, $value )
	{
		$this->parameters[ $name ] = $value;
	}
	
	/// <summary>
	/// Delete al Url parameters
	/// </summary>
	function ClearParameters()
	{
		$this->parameters = array();
	}






	/// <summary>
	/// Is something exist in host/path/filename section
	/// </summary>




	/// <summary>
	/// Does something contain $value
	/// </summary>
	function IsContainString( $data, $value )
	{
		return ( strpos( strtolower( $data ), strtolower( $value ) ) !== false );
	}

	/// <summary>
	/// Does something regexp $pattern
	/// </summary>
	function IsContainRegExp( $data, $pattern )
	{
		$matches = array();
		$reg_result = preg_match ( $pattern, $data, $matches );
		return ( $reg_result === 1 ) && ( count( $matches ) == 1 );
	}
	
	/// <summary>
	/// Does path contain $value
	/// </summary>
	function IsPathContainString( $value )
	{
		return $this->IsContainString( $this->path , $value );
	}
	
	/// <summary>
	/// Does file name contain $value
	/// </summary>
	function IsFileNameContainString( $value )
	{
		return $this->IsContainString( $this->filename , $value );
	}
	
	/// <summary>
	/// Does host contain $value
	/// </summary>
	function IsHostContainString( $value )
	{
		return $this->IsContainString( $this->host , $value );
	}
	
	/// <summary>
	/// Does path regexp $pattern
	/// </summary>
	function IsPathContainRegExp( $pattern )
	{
		return $this->IsContainRegExp( $this->path, $pattern );
	}
	
	/// <summary>
	/// Does file name regexp $pattern
	/// </summary>
	function IsFileNameContainRegExp( $value )
	{
		return $this->IsContainRegExp( $this->filename, $pattern );
	}
	
	/// <summary>
	/// Does host regexp $pattern
	/// </summary>
	function IsHostContainRegExp( $pattern )
	{
		return $this->IsContainRegExp( $this->host, $pattern );
	}
}

?>