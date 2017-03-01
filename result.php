<?php
$conf = parse_ini_file("search.conf", true);
require_once('function.php');
openlog('IMAP Headers Fetch', LOG_PID, LOG_MAIL);

$client_user = username();

$user = strtolower(utf8_encode($_POST['username']));

if ( !empty($_POST['folder']) ) $folder = mb_convert_encoding(utf8_encode($_POST['folder']), "UTF7-IMAP", "UTF-8");
else print '<p>'.htmlspecialchars('You insert no folder. I try to looking for from all folders I found!') . '</p>';

$imapsearch = array();

if ( isset($_POST['uid']) ) {
	$imapsearch['uid'] = $_POST['uid'];
	syslog(LOG_INFO, $client_user.': Looking for a known UID of value <'.$_POST['uid'].'>.');
	if (filter_var($imapsearch['uid'], FILTER_VALIDATE_INT) === FALSE)
		exit ('<p>'.htmlspecialchars('Please, insert a number, not <'.$imapsearch['uid'].'>').'.</p>');
}
else
	$_POST['uid'] = NULL;

if ( isset($_POST['msgid']) ) 
        $imapsearch['Message-ID'] = $_POST['msgid'];
if ( isset($_POST['from']) )
        $imapsearch['FROM'] = $_POST['from'];
if ( isset($_POST['to']) )
        $imapsearch['TO'] = $_POST['to'];
if ( isset($_POST['subject']) )
        $imapsearch['SUBJECT'] = $_POST['subject'];


if (filter_var($user, FILTER_VALIDATE_EMAIL) === FALSE)
 exit ('<p>'.htmlspecialchars('Please, insert a valid email address, not <'.$user.'>').'.</p>');



/* Determine the popserver    */
$ldapconn = conn_ldap($client_user, $conf['ldap']['server'], $conf['ldap']['port'], $conf['ldap']['dnlog'], $conf['ldap']['password']);
if (!$ldapconn) {
	$err = 'LDAP connection error. Exiting...';
        syslog(LOG_ERR, $client_user.': Error: '.$err);
        exit('Unable to proceed: <pre>'.htmlentities($err).'/pre>');
}



list($_,$dom) = explode('@',$user);
$mailhost = searchAttr($ldapconn,$conf['ldap']['host'],$conf['ldap']['uid'],$user,"o=$dom,".$conf['ldap']['base']);
ldap_close($ldapconn);

if (!isset($mailhost)) exit ('<p>ERROR: '.htmlspecialchars("I can't determine the popserver where <$user> lives.").'</p>');
/*****************************/

print "<hr>";
// connecting to imap mailserver
$openString = '{'.$mailhost.':143/imap/authuser='.$conf['imap']['authuser'].'}';

if ( empty($folder) ) {
	$connection = imap_open($openString, $user,$conf['imap']['authpassword'], OP_READONLY)
        	or exit('<p>'.htmlspecialchars("ERROR connecting to <$mailhost>: " . imap_last_error()).'</p>');
	$list = imap_list($connection, $openString, "*");
	imap_close($connection);
	if (is_array($list)) {
		foreach ($list as $val)
			print_msg ($client_user, $val,$user,$conf['imap']['authpassword'],$mailhost,$imapsearch,
					imap_utf7_decode( ltrim(strstr($val,'}'),'}') ),$_POST['uid']);
	} else {
		exit( "Imap_list failed: " . imap_last_error() . "\n" );
	}
}
else 
	// get imap_fetch header from folder
	print_msg ($client_user, $openString.$folder, $user,$conf['imap']['authpassword'],$mailhost,$imapsearch, $folder, $_POST['uid']);

closelog();
?> 
