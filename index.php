<html>
<head>
<title>IMAP Headers Fetch</title>
<meta http-equiv="Content-Type" content="text/html; charset="UTF-8">
<link rel="stylesheet" type="text/css" href="/include/style.css">
<link rel="SHORTCUT ICON" href="favicon.ico"> 
<script  src="/include/ajaxsbmt.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/prototype/1.7.0.0/prototype.js" type="text/javascript" charset="utf-8"></script>
<script src="https://ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/scriptaculous.js?load=effects" type="text/javascript" charset="utf-8"></script>
</head>
<body>
<h1 style="margin:2">IMAP Headers Fetch</h1>
<p style="text-align: right; margin:0">Hello <b><?php echo $_SERVER["REMOTE_USER"];?></b> - Need an <a href="#popuphelp" title="HELP">help</a>?</p>
<p>Use this tool to find the mail headers starting from its IMAP UID or headers.</p>
<?php

print <<<END
 <form method="POST" accept-charset="UTF-8" name="Richiestadati" action="result.php" onSubmit="xmlhttpPost('result.php', 'Richiestadati', 'Risultato', '<img src=\'/include/pleasewait.gif\'>'); return false;">
<table align="center" cellspacing=1>
<thead>
<tr><td class="form">Username </td><td colspan="5"><input type="email" name="username" size="80" class="input_text" id="1" required></td></tr>
</thead>
<tbody>
<tr><td class="form">Folder </td><td colspan="5"><input type="text" name="folder" value="INBOX" size="50" class="input_text" id="1"></td></tr>
<tr><td>Type</td>
<td><input type="radio" required name="key" value="uid" onclick="xmlhttpPost('uid.htm', 'Richiestadati', 'keysearch', '<td colspan=\'2\'><img src=\'/include/pleasewait.gif\'></td>'); return true;">UID</td>
<td><input type="radio" required name="key" value="Message-ID" onclick="xmlhttpPost('msgid.htm', 'Richiestadati', 'keysearch', '<td colspan=\'2\'><img src=\'/include/pleasewait.gif\'></td>'); return true;">Message-ID</td>
<td><input type="radio" required name="key" value="From" onclick="xmlhttpPost('from.htm', 'Richiestadati', 'keysearch', '<td colspan=\'2\'><img src=\'/include/pleasewait.gif\'></td>'); return true;">From</td>
<td><input type="radio" required name="key" value="To" onclick="xmlhttpPost('to.htm', 'Richiestadati', 'keysearch', '<td colspan=\'2\'><img src=\'/include/pleasewait.gif\'></td>'); return true;">To</td>
<td><input type="radio" required name="key" value="Subject" onclick="xmlhttpPost('subj.htm', 'Richiestadati', 'keysearch', '<td colspan=\'2\'><img src=\'/include/pleasewait.gif\'></td>'); return true;">Subject</td></tr>
<tr id="keysearch"></tr>
</tbody>
<tfoot>
<tr style= "margin-top: 3"><td><input type="reset" value="Reset" name="Reset" class="btn"></td>
<td colspan="5"><input type="submit" value="View Header"name="View Header" class="btn"></td></tr>
</tfoot></table></form>


<div id="Risultato"></div>
<div id="popuphelp" class="overlay">
	<div class="popup">
END;

readfile('help.htm');
print <<<END
<a class="close" href="#">&times;</a>
	</div>
</div>
END;

?>
<p style="text-align: right; margin:0">IMAP Search is presented in HTML5.</p>
</body>
</html>
