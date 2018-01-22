<?php

function conn_ldap($username,$host,$port,$user,$pwd) {
        $ldapconn = ldap_connect($host, $port);
        ldap_set_option($ldapconn, LDAP_OPT_NETWORK_TIMEOUT, 5);
        if ($ldapconn) {
                // binding to ldap server
                syslog(LOG_INFO,  "$username: Info: LDAP: Successfully connected to $host:$port");
                $ldapbind = ldap_bind($ldapconn, $user, $pwd);
                // verify binding
                if ($ldapbind) {
                        syslog(LOG_INFO,  "$username: Info: LDAP: Successfully BIND as <".$user.'>.');
                        return $ldapconn;
                }
                else {
                        $err = 'LDAP: Error trying to bind as <'.$user.'>: '.ldap_error($ldapconn);
                        syslog(LOG_ERR, "$username: Error: $err.");
                        ldap_unbind($ldapconn);
                        return FALSE;
                }
        }
        else {
                $err = 'LDAP: Could not connect to LDAP server '.$host.':'.$port;
                syslog(LOG_ERR, $username.": Error: $err.");
                return FALSE;
        }
}


function searchAttr ($conn,$attr,$attrnamefilt,$attrvaluefilt,$base) {
        if (!($sr=ldap_list($conn, $base, "(&($attrnamefilt=$attrvaluefilt)(objectclass=mailrecipient))",array("$attr"))))
	print (htmlentities("Failed query <$attrnamefilt=$attrvaluefilt> over <$base>. Error: ".ldap_error($conn)."."));
        $entry = ldap_get_entries($conn, $sr);
        if ($entry['count'] > 1) {
                print '<p>'.htmlspecialchars('ERROR: multiple account with name <$attrname>.').'</p>';
                return NULL;
        }
        if ($entry['count'] == 0) {
                print '<p>'.htmlspecialchars("ERROR: value <$attrvaluefilt> of <$attrnamefilt> doesn't exist over LDAP.").'</p>';
                return NULL;
        }
        if ($entry['count'] == 1) {
                print '<p>'.htmlspecialchars("The account <$attrvaluefilt> is on popserver <".$entry[0]["$attr"][0].'>').'.</p>';
                return $entry[0]["$attr"][0];
        }
}


/* IMAP */
function search_imap_uid($username, $imapconn, $flag) {
/* Return IMAP UID of desired mail */
	if (isset($flag['uid']))
		return array( $flag['uid'] );
	switch ( $name = array_pop( array_keys($flag) ) ) {
		case 'Message-ID':
			$string = sprintf('%s "%s: <%s>"','TEXT', $name, $flag["$name"]);
			syslog(LOG_INFO,"$username: Looking for: <$string>");
			return imap_search($imapconn, $string, SE_UID);
		case 'FROM':
		case 'TO':
		case 'SUBJECT':
			$string = sprintf('%s "%s"',$name, $flag["$name"]);
			syslog(LOG_INFO,"$username: Looking for: <$string>");
			return imap_search($imapconn, $string, SE_UID);
		default:
			return array();
	}
}

function print_msg ($username, $mbox,$user,$authpassword,$mailhost,$imapsearch,$folder,$uid=NULL) {
	$connection = imap_open($mbox, $user,$authpassword, OP_READONLY)
        	or exit('<p>'.htmlspecialchars("ERROR connecting to <$mailhost>: " . imap_last_error()).'</p>');
	$imapUIDs = search_imap_uid($username, $connection, $imapsearch);
	if ( is_null($uid) ) {
		if ( $imapUIDs !== FALSE )
			printf ("<h3>Found %d messages on folder %s</h3>", count($imapUIDs), $folder);
		else
			syslog (LOG_INFO,"$username: No message found on folder <$folder>");
	}
	foreach ( $imapUIDs as $imapUID ) {
		$header = imap_fetchheader($connection, $imapUID,FT_UID);
		if ( $header ) {
			printf ("<p>Message with UID %s on folder %s.</p><pre>%s</pre>",$imapUID,htmlentities($folder),htmlentities($header));
			syslog(LOG_INFO,"$username: Found Message with UID <$imapUID> on folder <$folder>");
		}
	}
	imap_close($connection);
}


function username() {
        if (isset ($_SERVER['REMOTE_USER'])) $user = $_SERVER['REMOTE_USER'];
                else if (isset ($_SERVER['USER'])) $user = $_SERVER['USER'];
                else if ( isset($_SERVER['PHP_AUTH_USER']) ) $user = $_SERVER['PHP_AUTH_USER'];
                else {
                        syslog(LOG_ALERT, "No user given by connection from {$_SERVER['REMOTE_ADDR']}. Exiting");
                        exit(0);
                }
	syslog(LOG_INFO,"User $user successfully connected from IP <{$_SERVER['REMOTE_ADDR']}>.");
        return $user;
}

?>
