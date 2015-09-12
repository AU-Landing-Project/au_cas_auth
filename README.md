Overview
===========

The cas_auth plugin provides single signon capabilities for Elgg using Yale CAS (Central Authentication System).  

Requirements
============

The cas_auth plugin requires the following to be installed and enabled on the Elgg server:

* LDAP client (and LDAP-enabled PHP)
* curl
* Pear::DB module

Installation
============

1) Extract the contents of the zip to your mod/cas_auth.  
2) Log into Elgg as an administrator.
3) Enable the plugin in Tools Administraiton.
4) Configure your CAS and LDAP servers by clicking on Settings.  

NOTE: It is important that you enter a username into the "CAS User to set as admin" field.  When this user logs into Elgg for the first time, their account will set up with administrative privileges.  If this option is not configured, you will not have access to Elgg's administrative functions.  