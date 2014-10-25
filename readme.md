Sandra - billing notifier
=========================

[![Build Status - develop](https://travis-ci.org/jurasm2/sandra.svg?branch=develop)](https://travis-ci.org/jurasm2/sandra)

Installing
----------

The best way to install Sandra is create new project using Composer:

1. Install Composer: (see http://getcomposer.org/download)

		curl -s http://getcomposer.org/installer | php

2. Create new project via Composer:

		php composer.phar create-project jurasm2/sandra sandraApplication
		cd sandraApplication

Make directories `temp` and `log` writable. Navigate your browser
to the `www` directory and you will see a Sandra dashboard page.


It is CRITICAL that file `app/config/config.neon` & whole `app`, `log`
and `temp` directory are NOT accessible directly via a web browser! If you
don't protect this directory from direct web access, anybody will be able to see
your sensitive data. See [security warning](http://nette.org/security-warning).
