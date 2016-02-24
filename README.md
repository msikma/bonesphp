Bones Framework – Microscopic MVC Framework for PHP
===================================================

This is an extremely simple example of an MVC framework for PHP. It could serve
as an educational sample, or you could use it in a real site if you don't like
your framework to do anything for you except basic MVC scaffolding.

*Note: this was made quite some time ago! I would not recommend
actually using it for anything serious! But it might be useful
to someone interested in learning how to build a basic MVC framework.*

Twig support is built in if you get the Git submodule for it.

There's some example content in the `application/` directory.

Installation
------------

Installation is fairly straightforward. After obtaining a copy of the system,
it will work out of the box except if you want to use
[Twig](https://github.com/fabpot/Twig/).

The most straightforward way to install Twig is to get the most recent
[release](https://github.com/fabpot/Twig/releases) in a zip file, extract it,
and then put the `lib/Twig/` directory into Bones's `system/externals/`
directory. Ultimately, you'd end up with `system/externals/Twig/` with all
the files inside there.

You may need to ensure that the `system/cache/` directory exists and is
writable.

License
-------

Copyright (C) 2013-2014. The usage of this programming code
is governed by the [MIT license](http://opensource.org/licenses/MIT).
