# IMAP-Search
IMAP Search tool for web by many criteria, such as uid, message-ID, From, To.
![A screenshot](/doc/screenshot.jpg?raw=true "Screenshot")

## Abstract
This is a little client for Email Administrator which have distribuited popservers profiled over LDAP.

You provide a username. Starting from username, this tool searches over LDAP the popserver. Then it makes an IMAP call over popserver to search mails. It finally shows the headers of mail found.

## Require
I developed this little tool on PHP7. Maybe it could work also with previous releases.

- php-ldap
- php-mbstring
- php-imap

The username to search must be in the form `<name>@<domain>` (like Cyrus virtual domains) and it must be located under the LDAP tree `o=<domain>`.

The web server must run with authenticated access, to grant log tracking. I really suggest to add SSL layer (such as Apache httpd+mod_ssl+mod_auth...).

## Config
Move `style.css` and `ajaxsbmt.js` in `DOCUMENT_ROOT/include` dir.
Copy search.conf-default in search.conf.
Arrange the values:
```
[imap]
authuser: the proxy IMAP administrator with full read access to all mailboxes
authpassword = the proxy IMAP administrator password

[ldap]
server = the LDAP server
port = the LDAP port
dnlog = the LDAP user with read access to the base three and subtrees too
password = the LDAP user password
base = the base root with all mailboxes usernames.
host = the attribute name which value is the popserver of username
uid = the attribute name which value is the username to look for.
```

This is an instance of LDAP profile you should have to use this tool:
```
dn: "uid"=[...,...,]o=<domain>,<base>
"uid"=username
...
"host"=popserver.example.com
...
```
