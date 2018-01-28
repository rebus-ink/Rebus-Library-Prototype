This is the code for
http://rlp.rebus.cc/

A prototype for a web-based ebook library for scholarly reading management.
The project was funded in 2017 with a planning grant from the The Andrew W. Mellon Foundation.

--------------------------------------------------------------------------------

A few things to note:

This codebase is provided as is, without promise of further support.
It was built, with minimal software development chops, using techniques and technologies generally out of style today.

It uses procedural PHP cobbled together as ideas came up.
It is served by Apache, and uses JSON files as a database.
These JSON files were generated from a Calibre ( https://calibre-ebook.com/ ) library database by some other scripts neither of which are included here.


IMAGES INSIDE HTML BOOKS
========================

The "images" folders from the HTML books have been removed from this repository to save on overall size.


BOOK DEPO
=========

Unzip "_depo.zip"


Apache
======

An example to add to your virtual host directive. Specifics depend on your environment.

<VirtualHost *:8080>
  ServerName rlp.rebus.local
  Options Indexes FollowSymLinks Multiviews

  CustomLog "/Path/to/logs/rlp.rebus.dev-access_log" combined
  ErrorLog "/Path/to/logs/rlp.rebus.dev-error_log"

  SetEnv APPLICATION_ENV "dev"
  SetEnv HOSTING_ENV "mac"

  DocumentRoot /Path/to/rlp.rebus
</VirtualHost>
