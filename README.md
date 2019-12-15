phpnetbar
=========

[![Project Status: Concept – Minimal or no implementation has been done yet, or the repository is only intended to be a limited example, demo, or proof-of-concept.](https://www.repostatus.org/badges/latest/concept.svg)](https://www.repostatus.org/#concept)

This is a PHP implementation of the network utililization
bar on http://kernel.org. It measures outgoing bandwidth
on a /proc based operating system, like Linux. That means
it definately doesn't work on OpenBSD.

Change configuration information in daemon.php. Then you
might want to move daemon.php to a different directory
because it must run all the time to poll the network
services. Everything else should work fine if you have GD
installed with PHP. Invoke daemon.php like this:

php -q daemon.php &

Be sure to run daemon.php as a user that has permission
to write to the netload.inc file.
