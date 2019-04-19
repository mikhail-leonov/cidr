<!DOCTYPE html>
<?php
/**
 * Main CIDR range converter page
 *
 * IPv4/6 to CIDR range converter page 
 * Supports PHP 5.3+ (32 & 64 bit)
 * @author Mikhail Leonov <mikecommon@gmail.copm>
 *
 */
	require_once( 'utils.class.php' );
	require_once( 'url.class.php' );
	require_once( 'cidr4.class.php' );
	require_once( 'cidr6.class.php' );
	require_once( 'uuid.class.php' );
	$wise = Utils::get_attr_value( $_GET, 'wise', "" );
	$isWise = ( $wise === "on" );
	$focusInputCommand  = '<script>$( document ).ready(function() { focusInput() ; });</script>';
	$focusResultCommand = '<script>$( document ).ready(function() { focusResult(); });</script>';
?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
		<title>CIDR Calculator</title>
		<script src="/assets/js/jquery.js"></script>
		
		<!-- favicon -->
		<link rel="icon" type="image/png" href="/assets/icons/favicon-16x16.png" sizes="16x16">
		<link rel="icon" type="image/png" href="/assets/icons/favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="/assets/icons/favicon-64x64.png" sizes="64x64">
		<link rel="icon" type="image/png" href="/assets/icons/favicon-96x96.png" sizes="96x96">
		<link rel="icon" type="image/png" href="/assets/icons/favicon-128x128.png" sizes="128x128">
		<link rel="apple-touch-icon" sizes="57x57" href="/assets/icons/apple-touch-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="/assets/icons/apple-touch-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="/assets/icons/apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="/assets/icons/apple-touch-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="/assets/icons/apple-touch-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="/assets/icons/apple-touch-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="/assets/icons/apple-touch-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="/assets/icons/apple-touch-icon-152x152.png">
		<link rel="manifest" href="/site.webmanifest">
		<link rel="mask-icon" href="/assets/icons/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="msapplication-TileColor" content="#2b5797">
		<meta name="msapplication-TileImage" content="/assets/icons/mstile-144x144.png">
	</head>
	<body>
		<script>
			function focusInput() { document.getElementById("range").focus(); }
			function focusResult() { document.getElementById("holdtext").focus(); document.getElementById("holdtext").select(); }
			$( document ).ready(function() { 
				$( "#range" ).on( "keypress", function( event ) { if ( event.which == 13 && !event.shiftKey ) { event.preventDefault(); $("#form").submit(); } }); 
				$( "#range" ).mouseup(function() { $( "#holdtext" ).val(''); });
			});
		</script>
        <div id="container">
            <div class="row">
				<div class="col-xl-1"><br></div>
                <div class="col-xl-10">
					<br><br><br>
					<form id="form" action="index.php">
						<div class="card" id="cards">
							<div class="card-header text-white bg-primary">CIDR Calculator</div>
							<div class="card-body">
								<div class="form-group">
									<label for="exampleInputEmail1">Settings</label>
									<div class="checkbox">
										<label>
											<input name="wise" id="wise" type="checkbox" <?php if ($isWise) { echo "checked='checked'"; } ?>> Auto replace CIDR4 values: 1-254 to 0-255
										</label>
									</div>
								</div>
								<?php
                                    function cidrType( $range ) {
										$result = 4; 
										if ( strpos( $range, ":" ) !== false ) {
											$result = 6; 
										}
										return $result;
									}
                                    function removeProper( $range ) {
                                        $range = str_replace( "0", "", $range ); 
										$range = str_replace( "1", "", $range ); 
										$range = str_replace( "2", "", $range ); 
										$range = str_replace( "3", "", $range ); 
										$range = str_replace( "4", "", $range ); 
										$range = str_replace( "5", "", $range ); 
										$range = str_replace( "6", "", $range ); 
										$range = str_replace( "7", "", $range ); 
										$range = str_replace( "8", "", $range ); 
										$range = str_replace( "9", "", $range ); 
										$range = str_replace( ".", "", $range ); 
										$range = str_replace( "/", "", $range );
                                        return $range;
                                    }
                                    function isCIDRv4( $range ) {
                                        $dots  = ( substr_count ( $range, "." ) === 3 );
                                        $slash = ( substr_count ( $range, "/" ) === 1 );
                                        $range = removeProper( $range );
                                        $range = ( trim( $range ) === "" );
                                        return ( $dots && $slash && $range );
                                    }
									function isCIDRv6( $range ) {
										return ( Utils::isAIPv6CIDR( $range ) );
									}
                                    function isNetMask( $range ) {
                                        $slash = ( substr_count ( $range, "/" ) === 1 );
                                        $dots  = ( substr_count ( $range, "." ) === 6 );
                                        $range = removeProper( $range );
                                        $range = ( trim( $range ) === "" );
                                        return ( $dots && $slash && $range );
                                    }
                                    function mapNetMask( $range ) {
										$range = str_replace( "/128.0.0.0", 		"/1"	, $range ); 
										$range = str_replace( "/192.0.0.0", 		"/2" 	, $range ); 
										$range = str_replace( "/224.0.0.0", 		"/3" 	, $range ); 
										$range = str_replace( "/240.0.0.0", 		"/4" 	, $range ); 
										$range = str_replace( "/248.0.0.0", 		"/5" 	, $range ); 
										$range = str_replace( "/252.0.0.0", 		"/6" 	, $range ); 
										$range = str_replace( "/254.0.0.0", 		"/7" 	, $range ); 
										$range = str_replace( "/255.0.0.0", 		"/8" 	, $range ); 
										$range = str_replace( "/255.128.0.0", 		"/9" 	, $range ); 
										$range = str_replace( "/255.192.0.0", 		"/10" 	, $range ); 
										$range = str_replace( "/255.224.0.0", 		"/11" 	, $range ); 
										$range = str_replace( "/255.240.0.0", 		"/12" 	, $range ); 
										$range = str_replace( "/255.248.0.0", 		"/13" 	, $range ); 
										$range = str_replace( "/255.252.0.0", 		"/14" 	, $range ); 
										$range = str_replace( "/255.254.0.0", 		"/15" 	, $range ); 
										$range = str_replace( "/255.255.0.0", 		"/16" 	, $range ); 
										$range = str_replace( "/255.255.128.0", 	"/17" 	, $range ); 
										$range = str_replace( "/255.255.192.0", 	"/18" 	, $range ); 
										$range = str_replace( "/255.255.224.0", 	"/19" 	, $range ); 
										$range = str_replace( "/255.255.240.0", 	"/20" 	, $range ); 
										$range = str_replace( "/255.255.248.0", 	"/21" 	, $range ); 
										$range = str_replace( "/255.255.252.0", 	"/22" 	, $range ); 
										$range = str_replace( "/255.255.254.0", 	"/23" 	, $range ); 
										$range = str_replace( "/255.255.255.0", 	"/24" 	, $range ); 
										$range = str_replace( "/255.255.255.128", 	"/25" 	, $range ); 
										$range = str_replace( "/255.255.255.192", 	"/26" 	, $range ); 
										$range = str_replace( "/255.255.255.224", 	"/27" 	, $range ); 
										$range = str_replace( "/255.255.255.240", 	"/28" 	, $range ); 
										$range = str_replace( "/255.255.255.248", 	"/29" 	, $range ); 
										$range = str_replace( "/255.255.255.252", 	"/30" 	, $range ); 
										$range = str_replace( "/255.255.255.254", 	"/31" 	, $range ); 
										$range = str_replace( "/255.255.255.255", 	"/32" 	, $range ); 
                                        return $range;
                                    }
                                    
									$errorMessage = "";
									$url = new Url();
									$rangeRequest = $url->GetUrlParameterValue( "range", "" );
									$rangeRequest = trim( $rangeRequest ); 
									if ( "" !== $rangeRequest ) {
										$rangeRequest = str_replace( "x", "*", $rangeRequest ); 
										$rangeRequest = str_replace( "–", "-", $rangeRequest ); 
										$rangeRequest = str_replace( "and", "\n", $rangeRequest ); 
										$rangeRequest = str_replace( ";", "\n", $rangeRequest ); 
										$rangeRequest = str_replace( " ", "", $rangeRequest ); 
										$rangeRequest = str_replace( "|", "", $rangeRequest );
										$rangeRequest = str_replace( '\\', '', $rangeRequest );
										if ( 4 === cidrType( $rangeRequest ) ) {
											$rangeRequest = str_replace( ":", "-", $rangeRequest );
											$rangeRequest = preg_replace("/[A-Za-z()]/", '', $rangeRequest );
										}
										if ( 6 === cidrType( $rangeRequest ) ) {
											$rangeRequest = str_replace( "...", "-", $rangeRequest );
											$rangeRequest = preg_replace("/[G-Zg-z()]/", '', $rangeRequest );
										}
										
										if ( $rangeRequest === "" ) { $rangeRequest = ""; print( "\n{$focusInputCommand}\n" ); } else { print( "\n{$focusResultCommand}\n" ); }
										
										$result = array(); $ranges = Utils::explode2( array( ',', "\n" ), $rangeRequest );
										foreach( $ranges as $k => $range ) {
											$range = trim($range);
											if ( 4 === cidrType( $range ) ) {
												processCIDR4( $range, $result, $isWise );
											}
											if ( 6 === cidrType( $range ) ) {
												processCIDR6( $range, $result );
											}
										}
									}
									function processCIDR6( $range, &$result ) {
									
										if ( Utils::isAIPv6CIDR( $range ) ) {
                                            $result [ $range ] = array( "range" => $range, "error" => "" ); 
                                            error_log( "CIDR6( $range ) = Already cidr" );
										} else {
											if ( Utils::isATwoIPv6Range( $range ) ) {
												$ip1 = Utils::getFirstIP2( $range ); $ip1 = Utils::getFirstIP( $ip1 );
												$ip2 = Utils::getLastIP2( $range );  $ip2 = Utils::getLastIP( $ip2 );
												error_log( "CIDR6( $range ) = 2 IP Range( $ip1, $ip2 )" );
												$result [ $range ] = array( "range" => CIDR6::rangeToCIDRList( $ip1, $ip2 ), "error" => "" ); 
											}
										}
									}
									function processCIDR4( $range, &$result, $isWise ) {
									
                                        $range = trim( $range ); 
										$parts = explode( ".", $range );
										foreach( $parts as $k => &$part ) { if ( "" === $part) { $part = "*"; } }
										$range = implode( ".", $parts );
										if ( isNetMask( $range )) {
											$range1 = mapNetMask( $range );
											error_log( "NetMASK( $range ) => CIDR4($range1)" );
											$range = $range1;
										}
                                        error_log( "CIDR4( $range )" );
                                        if ( isCIDRv4( $range ) ) {
                                            error_log( "CIDR4( $range ) = Already cidr" );
                                            $result [ $range ] = array( "range" => $range, "error" => "" ); 
                                        } else {
                                            if ( $isWise ) { $range = str_replace( '.1-254', '.*', $range ); }
                                            if ( $range !== "" ) { 
                                                if ( Utils::isABubbleRange( $range ) ) {
                                                    error_log( "CIDR4( $range ) = Incorrect" );
                                                    $errorMessage = "Incorrect Range set: $range"; 
                                                    
                                                } else {
                                                    if ( Utils::isATwoIPv4Range( $range ) ) {
                                                        $ip1 = Utils::getFirstIP2( $range ); $ip1 = Utils::getFirstIP( $ip1 );
                                                        $ip2 = Utils::getLastIP2( $range );  $ip2 = Utils::getLastIP( $ip2 );
                                                        
                                                        if ( Utils::isLimitedIP( $ip1 ) && Utils::isLimitedIP( $ip2 ) ) {
                                                            $result [ $range ] = array( "range" => CIDR4::rangeToCIDRList( $ip1, $ip2 ), "error" => "" ); 
                                                        } else {
                                                            $result [ $range ] = array( "range" => "", "error" => "*INCORRECT RANGE* => {$range}" ); 
                                                            error_log( "CIDR4( $range ) = Incorrect" );
                                                        }
                                                        
                                                    } else {
                                                        if ( Utils::isANetMaskRange( $range ) ) {	
                                                            $ip1  = Utils::getFirstIP3( $range ); $ip1 = Utils::getFirstIP( $ip1 );
                                                            $mask = Utils::getLastIP3( $range ); 
                                                            
                                                            if ( Utils::isLimitedIP( $ip1 ) ) {
                                                                $result [ $range ] = array( "range" => array( CIDR4::alignedCIDR( $ip1, $mask ) ), "error" => "" ); 
                                                            } else {
                                                                $result [ $range ] = array( "range" => "", "error" => "*INCORRECT RANGE* => {$range}" ); 
                                                                error_log( "CIDR4( $range ) = Incorrect" );
                                                            }
                                                            
                                                        } else {
                                                            $ip1 = Utils::getFirstIP( $range );
                                                            $ip2 = Utils::getLastIP( $range );
                                                            
                                                            if ( Utils::isLimitedIP( $ip1 ) && Utils::isLimitedIP( $ip2 ) ) {
                                                                $result [ $range ] = array( "range" => CIDR4::rangeToCIDRList( $ip1, $ip2 ), "error" => "" ); 
                                                            } else {
                                                                $result [ $range ] = array( "range" => "", "error" => "*INCORRECT RANGE* => {$range}" ); 
                                                                error_log( "CIDR4( $range ) = Incorrect" );
                                                            }
                                                        }
                                                    }
                                                }
                                            }
										}
									}
								?>
								<div class="form-group">
									<label for="exampleInputEmail1">CIDR Mask</label>
									<textarea id="range" name="range" class="form-control" placeholder="127.1.10-31.*;187.17.2.2 - 192.11.4.29;" rows="5"><?php  print( $rangeRequest ); ?></textarea>
								</div>
								<div class="form-group">
									<label for="result">CIDR Ranges</label>
									<textarea id="holdtext" class="form-control" placeholder="Here will be a CIDR list" rows="20"><?php 
									if ( "" !== $rangeRequest ) {
										if ( $errorMessage === "" ) {
											foreach( $result as $range => $list ) { 
                                                //$range = str_replace( "*", '\*', $range ); 
												$range = trim( $range );
                                                print( "{$range} => \n"); 
                                                if ( isCIDRv4( $range ) ) {
                                                    print( "=> {$range} => {$range}\n" ); 
                                                } else {
													if ( isCIDRv6( $range ) ) {
														print( "=> {$range} => {$range}\n" ); 
													}
												}
                                                
                                                if ( $list["error"] !== "" ) {
                                                    print( "=> " . $list["error"] . "\n" );  
                                                } else {
                                                    foreach( $list["range"] as $k => $cidr ) { 
                                                        
														if ( isCIDRv4( $cidr ) ) {
															list( $s, $e ) = CIDR4::cidrToRange( $cidr ); 
															if ( ( $s !== "0.0.0.0" ) && ( $e !== "0.0.0.0" ) ) { 
																print( "=> {$s} - {$e} => {$cidr}\n" ); 
															} else { 
																print( "=> INCORRECT RANGE => {$range}\n" ); 
															} 
														} else {
															if ( isCIDRv6( $cidr ) ) {
																list( $s, $e ) = CIDR6::cidrToRange( $cidr ); 
																print( "=> {$s} - {$e} => {$cidr}\n" ); 
															}
														}
														
                                                    } 
                                                } 
                                                print( "\n" ); 
                                            }
										} else { 
											print( $errorMessage ); 
										}
									}
									?></textarea> 
								</div>
							</div>
							<div class="card-footer">
								<button type="submit" class="btn btn-default">Convert</button>
							</div>
						</div>					
					</form>
				</div>
				<div class="col-xl-1"><br></div>
			</div>
		</div>
		<script src="/assets/js/bootstrap.js"></script>
	</body>
</html>