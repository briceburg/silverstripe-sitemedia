<?php

/**
 * BEGIN EXAMPLES. The following is better placed in your mysite/_config.php
 *   and uncommented there.
 */

// Register Media Types (found in code/types)
// Feel free to create your own types and share!!
SiteMediaRegistry::add_type('SitePhoto');
SiteMediaRegistry::add_type('SiteVideo');

// Register Classes you would like to add SiteMedia to. ** /dev/build after!! **
// Additionally, you can specify allowed media types by passing an array.
// In the templates of these, add <% include SiteMedia %> and once tested,
// <% include CachedSiteMedia %> for performance.

//   SiteMediaRegistry::decorate('Page');
//   SiteMediaRegistry::decorate('Page', array('SitePhoto','SiteYoutubeVideo');

//   SiteMediaRegistry::init(); // (MUST be called after all decorations)


// You may set the default width and height used by the built-in thumbnail and
// image generating routines. Alternatively supply your own methods and markup
// for a custom fit!

//   SiteMedia::$default_thumbnail_width = 96;
//   SiteMedia::$default_thumbnail_height = 96;
//   SiteMedia::$default_width = 640;
//   SiteMedia::$default_height = 360;


/**
 * END EXAMPLES. Do not edit or copy from below.
 */

Object::add_extension('Image', 'SiteMediaImageDecorator');

// TODO: Create a SiteMediaPage where all uploaded and non private SiteMedia
//   is displayed.
	
// TODO: Add Comments to SiteMedia (deferred until new Comments Module)
	
// TODO: Documentation, example usage.

// TODO: Add CSS, JavaScript, Markup of a Sample Media Browser