# Research Sharing Platform

Submit and handle research on WP Multi Sites.

## Short Code

The `[list-research-projects]` shortcode will list posts with the custom post type `research-project` in a filterable view.

On the main site in your multisite network the shortcode with render with sorting by subsite/department:

<img src="https://i.imgur.com/vtW0wKy.png" width="300">

If the short code is used on a sub-site the filter by department option will not be rendered:

<img src="https://i.imgur.com/OxegDPD.png" width="300">


## Single Page

Each research project gets it's own single page view:

<img src="https://i.imgur.com/BduODlh.png" width="300">

## REST Endpoint

You can get a list of projects and departments on `/wp-json/wp-kau-rsp/v1/research-project`.
