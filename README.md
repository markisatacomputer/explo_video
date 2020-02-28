# Explo Video Module

#### Working with Media Objects

The Media Object node type can be wrapped with PHP classes found in the "classes" folder of this module.  This is to ensure all media objects have generic properties and methods.  

To load a media object into it's appropriate php class, use the explo_video_load function.

```
$media = explo_video_load($node);
//  Print a player.
$player = $media->getPlayer();
?>
    <div class="player"><?php print $player; ?></div>
<?php
//  Get a rendered thumb.
$thumb = $media->getThumb();
//  Get a rendered thumb that controls a player via js.
$thumbControl = $media->getThumbControl();
```

#### JS

Similar to the PHP wrapper classes, there are Javascript wrapper classes for Media Object players.  They can be found in the "js" folder.


#### DEVELOPING - Preprocessing

This module uses some build tools to make keeping track of requirements from older browsers a little easier.  It requires [Nodejs](https://nodejs.org/en/) >= version 8.

##### DEVELOPING - JS
This module is using [BABELjs](https://babeljs.io/) to compile Javacript into a form compatible with Browsers used by >0.25% of exploratorium.edu users.  The browserslist-stats.json was compiled using a tool called [browserslist-ga](https://github.com/browserslist/browserslist-ga) with data from our Google Analytics and should be updated from time to time.

##### DEVELOPING - CSS
This module is styled using [LESS CSS preprocessor](http://lesscss.org/) to compile stylesheets.  It does not use autoprefixer because that would interfere with the workarounds used for CSS grid in older browsers.

##### DEVEOPING - SET UP
This will install all required packages:
```
npm install -g gulp-cli
npm install
```

If you are developing and want to have scripts and css compiled automatically when files are saved, run this gulp task:
```
gulp watch
```

If you want to build css and js once, run this gulp task:
```
gulp build
```
