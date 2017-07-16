Movies Api (Web Scrapping)
===================

This API is used to create applications based movie information. Build using **Slim Framework** [https://www.slimframework.com](https://www.slimframework.com).

----------

Getting Started
-------------

First, you need **Composer** [(https://getcomposer.org/)
](https://getcomposer.org/) to get all dependencies.
Then simply run:

> composer install

Take a look **configuration** file at:

> /config.php

Usage
-------------
####  List Cinema
>/cinema[/ string $type]

`$type` is optional. (all **[default]**, featured, now-playing, coming-soon)

To using pagination, add `?page=[number]` as a suffix.

####  Detail Cinema

>/cinema/detail/[string $id]