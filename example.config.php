<?php 
/* EXAMPLE CONFIGURATION OF SITEMEDIA MODULE. APPEND TO YOUR mysite/_config.php
 * FILE AND
 */

// BEGIN EXAMPLE - see _config.php for more options


// Site Media
SiteMediaRegistry::add_type('SitePhoto');
SiteMediaRegistry::add_type('SiteYouTubeVideo');
SiteMediaRegistry::add_type('SiteVideo');

SiteMediaRegistry::decorate('Trainer');
SiteMediaRegistry::decorate('Breeder');
SiteMediaRegistry::decorate('Dog');
SiteMediaRegistry::decorate('NewsItem', array('SitePhoto'));

SiteMediaRegistry::init();

