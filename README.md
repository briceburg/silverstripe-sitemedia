# Silverstripe SiteMedia Module

## Maintainer Contact 
 * Brice Burgess (Nickname: briceburg, brice, briceburgess)
   <brice (at) digome (dot) com>
	
## Requirements
 * SilverStripe 2.4.x (For SilverStripe 3, see the -master branch)

## Overview
Add and manage a common library of Media from your Pages and DataObjects. 

Version aardvark PR-2


### Features

 * Easily add Photos, Videos, and more to your Objects
 
 * Decorate only the Objects you want Media on
 
 * Control the order of Media on an Object
 
 * Leverages Uploadify and DataObjectManager modules for an enhanced CMS experience. Falls back to core SilverStripe HasManyComplexTableField & FileIframeField in their absence.

 * Define custom Media Types using standard SilverStripe practices
 
 * Support for any custom field in your Types, e.g. Caption, Poster Image, YouTubeVideoID, etc.
    
 * Limit types per Decoration (e.g. Photos only on Pages, Photos and Video on Artists)
 
 * [coming very soon] Includes designer and developer friendly streamlined media player.

 * [coming soon] Viewers can browse all uploaded Media on your site from a single Page
 
 * Ability to mark uploaded Media as Private (excludes it from the Media Page)
 

 
### Demonstration

  * coming soon
  
	
### Configuration & Usage

 * Coming soon. See _config.php for an example.
 * Reference the templates and media types

### Known Issues, TODO
 
 * Add CSS, JavaScript for the included Media Gallery Markup sample.
 * Add Comments to SiteMedia (deferred until new Comments Module)
 * Documentation, example usage.
 * Add Demo YouTube Video Type
 * Add Demo Audio Player
 * Create a SiteMediaPage where all uploaded and non private SiteMedia is displayed.
