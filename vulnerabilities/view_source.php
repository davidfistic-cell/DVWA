<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ] .= 'Source' . $page[ 'title_separator' ].$page[ 'title' ];

if (array_key_exists ("id", $_GET) && array_key_exists ("security", $_GET)) {
	$id       = $_GET[ 'id' ];
	$security = $_GET[ 'security' ];


	switch ($id) {
		case "fi" :
			$vuln = 'File Inclusion';
			break;
		case "brute" :
			$vuln = 'Brute Force';
			break;
		case "csrf" :
			$vuln = 'CSRF';
			break;
		case "exec" :
			$vuln = 'Command Injection';
			break;
		case "sqli" :
			$vuln = 'SQL Injection';
			break;
		case "sqli_blind" :
			$vuln = 'SQL Injection (Blind)';
			break;
		case "upload" :
			$vuln = 'File Upload';
			break;
		case "xss_r" :
			$vuln = 'Reflected XSS';
			break;
		case "xss_s" :
			$vuln = 'Stored XSS';
			break;
		case "weak_id" :
			$vuln = 'Weak Session IDs';
			break;
		case "javascript" :
			$vuln = 'JavaScript';
			break;
		case "authbypass" :
			$vuln = 'Authorisation Bypass';
			break;
		case "open_redirect" :
			$vuln = 'Open HTTP Redirect';
			break;
		case "bac":
			$vuln = 'Vulnerability: Broken Access Control';
			break;
		default:
			$vuln = "Unknown Vulnerability";
	}

	$base_dir = realpath( DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities" ) . DIRECTORY_SEPARATOR;

	$source = "";
	$source_path = realpath( DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/{$id}/source/{$security}.php" );
	if ($source_path !== false && strpos( $source_path, $base_dir ) === 0) {
		$source = @file_get_contents( $source_path );
	}
	$source = str_replace( array( '$html .=' ), array( 'echo' ), $source );

	$id_html       = htmlspecialchars( $id, ENT_QUOTES, 'UTF-8' );
	$security_html = htmlspecialchars( $security, ENT_QUOTES, 'UTF-8' );
	$id_url        = htmlspecialchars( urlencode( $id ), ENT_QUOTES, 'UTF-8' );

	$js_html = "";
	$js_path = realpath( DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/{$id}/source/{$security}.js" );
	if ($js_path !== false && strpos( $js_path, $base_dir ) === 0) {
		$js_source = @file_get_contents( $js_path );
		$js_html = "
		<h2>vulnerabilities/{$id_html}/source/{$security_html}.js</h2>
		<div id=\"code\">
			<table width='100%' bgcolor='white' style=\"border:2px #C0C0C0 solid\">
				<tr>
					<td><div id=\"code\">" . highlight_string( $js_source, true ) . "</div></td>
				</tr>
			</table>
		</div>
		";
	}

	$page[ 'body' ] .= "
	<div class=\"body_padded\">
		<h1>{$vuln} Source</h1>

		<h2>vulnerabilities/{$id_html}/source/{$security_html}.php</h2>
		<div id=\"code\">
			<table width='100%' bgcolor='white' style=\"border:2px #C0C0C0 solid\">
				<tr>
					<td><div id=\"code\">" . highlight_string( $source, true ) . "</div></td>
				</tr>
			</table>
		</div>
		{$js_html}
		<br /> <br />

		<form>
			<input type=\"button\" value=\"Compare All Levels\" onclick=\"window.location.href='view_source_all.php?id={$id_url}'\">
		</form>
	</div>\n";
} else {
	$page['body'] = "<p>Not found</p>";
}

dvwaSourceHtmlEcho( $page );

?>
