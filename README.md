# IMAP-Search
IMAP Search tool by many criteria, such as uid, message-ID, From, To.

## Require
- I developed this little tool on PHP7. Maybe it could work also with previous release.
- php-ldap
- php-mbstring

The username to search must be in the form `<name>@<domain>` and it must be located under the LDAP tree `o=<domain>`.

## Abstract
This is a little client for Email Administrator which have distribuited popservers profiled over LDAP.
You provide a username. Starting from username, this tool search over LDAP the popserver. Then make a IMAP call over popserver to search mails.

## Config
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

This is an instance of LDIF file you should have to use this tool:
```
dn: "uid"=[...,...,]o=<domain>,<base>
"uid"=username
...
"host"=popserver.example.com
...
```
