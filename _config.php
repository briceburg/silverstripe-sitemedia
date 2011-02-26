<?php

// Register Media Types (found in code/types)
SiteMediaRegistry::add_type('SitePhoto');
SiteMediaRegistry::add_type('SiteVideo');


// Register Classes you would like to add SiteMedia to
// Additionally, you can specify allowed media types by passing an array
//   SiteMediaRegistry::decorate('Page');
//   SiteMediaRegistry::decorate('Page', array('SitePhoto','SiteYoutubeVideo');


// Do not edit below
SiteMediaRegistry::init();

// TODO: Create a SiteMediaPage where all uploaded and non private SiteMedia
//   is displayed.
	
// TODO: Add Comments to SiteMedia (deferred until new Comments Module)
	
// TODO: Documentation, example usage.

// TODO: Add CSS, JavaScript, Markup of a Sample Media Browser