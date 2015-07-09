## TINGASO
## Author: Lester Ariel Mesa <lesterm@gmail.com>

A Laravel PHP Project that integrates with Asterisk PBX to create a flexible dialer that allows its users/admin to dial based on phone number ranges.

It currently uses the following technologies:
- Laravel - MVC PHP Framework
- MySQL - Backend database
- Charts.js - simple, clean and appealing charts and graphs generation
- Bootstrap - served from CDN for HTML, CSS layout and responsiveness
- jQuery - JavaScript voodoo
- Asterisk - PBX and VoIP phone calls interaction

Also theres a script that actually generates the call files that is executed by a cronjob
* * * * * /usr/bin/php /home/ubuntu/tingaso/scripts/caller.php
* * * * * ( sleep 30; /usr/bin/php /home/ubuntu/tingaso/scripts/caller.php )
with a little trick to run every 30 seconds since cron does now go into subminutes.
