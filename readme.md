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

The respone conforms to this JSON schema:

```json
{
  "$schema": "http://json-schema.org/draft-07/schema#",
  "title": "Generated schema for Root",
  "type": "object",
  "properties": {
    "projects": {
      "type": "array",
      "items": {
        "type": "object",
        "properties": {
          "href": {
            "type": "string"
          },
          "title": {
            "type": "string"
          },
          "excerpt": {
            "type": "string"
          },
          "researchers": {
            "type": "string"
          },
          "research_status": {
            "type": "string"
          },
          "departmentName": {
            "type": "string"
          },
          "departmentPath": {
            "type": "string"
          },
          "departmentId": {
            "type": "string"
          }
        },
        "required": [
          "href",
          "title",
          "excerpt",
          "researchers",
          "research_status",
          "departmentName",
          "departmentPath",
          "departmentId"
        ]
      }
    },
    "departments": {
      "type": "array",
      "items": {
        "type": "object",
        "properties": {
          "name": {
            "type": "string"
          },
          "id": {
            "type": "string"
          }
        },
        "required": [
          "name",
          "id"
        ]
      }
    }
  },
  "required": [
    "projects",
    "departments"
  ]
}
```
