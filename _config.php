<?php

/**
 * BEGIN EXAMPLES. The following is better placed in your mysite/_config.php
 *   and left uncommented here. See example.config.php 
 *   
 *   BE SURE TO /dev/build after any changes.
 */

// Register Media Types (found in code/types)
// Feel free to create your own types and share!!
// SiteMediaRegistry::add_type('SitePhoto');
// SiteMediaRegistry::add_type('SiteYouTubeVideo');
// SiteMediaRegistry::add_type('SiteVideo');

// Register Classes you would like to add SiteMedia to. ** /dev/build after!! **
// Additionally, you can specify allowed media types by passing an array.
// To show the SiteMedia that has been added to these in the front-end, add
//   "<% include SiteMedia %>"  to their template file 
//   (e.g. themes/site/Layout/BlogEntry.ss)
// Override the template files to alter markup.

//   SiteMediaRegistry::decorate('Page');
//   SiteMediaRegistry::decorate('Page', array('SitePhoto','SiteYoutubeVideo');

//   SiteMediaRegistry::init(); // (MUST be called after all decorations)


// You may set the default width and height used by the built-in thumbnail and
// image generating routines. Alternatively supply your own methods and markup
// for a custom fit (see SiteMediaImageDecorator and the Media Templates)!

//   SiteMedia::$default_thumbnail_width = 96;
//   SiteMedia::$default_thumbnail_height = 96;
//   SiteMedia::$default_width = 640;
//   SiteMedia::$default_height = 360;


/**
 * END EXAMPLES. Do not edit or copy below.
 */

Object::add_extension('Image', 'SiteMediaImageDecorator');