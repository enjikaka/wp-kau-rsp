## Step 1 - Department Sites and posting

First issue was getting some sort of WordPress environment to run locally. I'm using Project Bluefin which is an immutable linux distribution and it wants everything containerized.

I first tried getting WordPress Playground to work in VSCode. I had some issues at first when submitting custom post types, getting a “The Link You Followed Has Expired” error. Nice and helpful error message... I threw all I did in the trash and re-made the plugin again following some other resources online. And now it worked. Wouldn't hurt if WordPress had a more descriptive error message... haha.

WordPress Playground doesn't seem to support multisite; so I abandoned developing the plugin with that. It was nice for single site development though! Quick and easy to test.

I spent and hour or so getting WordPress multisite to work with docker and docker-compose. Met some struggles with the `.htaccess` needing updates to enable multisites on sub-paths; something WordPress did not tell me after configuring it.... Bad developer experience...

Adding the custom post type, metaboxes and the saving was otherwise quite easy. Once again had some struggles trying to get the sub-site/department id. I asked the [deepseek-r1 AI model](https://ollama.com/library/deepseek-r1) why I was given no value by WordPress. Apparently, it was not available when add_meta_boxes runs. A fun quirk I guess you know about already when you do WordPress development on the daily, but not so obvious for new developers or someone like me that hasn't touched WordPress in a while... My first idea was to pre-fill the value initially in the HTMLInputElement before the initial draft/publish in the WP UI has been done - instead of having an empty field. But since I cannot call the method in there I resorted to setting the value in the save_post hook instead. (I suppose there are alterative ways to get the value, but I'll continue on the over all task instead of polishing that).

## Step 2 - Centralized Research Hub

I need a view for a list of research projects, but it's also nice if the project has a small view of it's own so I'm trying to add that. Creating a `single-research-project.php` in the directory of the plugin did not work. Some googling seems to indicate I need to register a template myself instead. Attempting that!

Got it to work. Moving on to making the list. I think I'll do a shortcode for that.

Shortcode is working. I need some nice designs for so I'll encapsulate that in a web component. Gotta use wp_enqueue_scripts for that it seems, so now we're starting to have quite a few bits to do for each step. So I'm thinking it's time to start extracting bits from the main plugin.php file into other files; and use classes to organize everything...

Moved everything into a WPKauRSP class now. Also excracted the shortcode-code to it's own class in its own file.

Bummer, the WordPress sanitizer is doing weird stuff and moving the code returned by the shortcode method around when using a custom element or div as the outer element returned in the string... I give up and use UL instead.

Managed to get some web components involved if I render them inside the `<li>`; so I created a `<research-project-card>` element to contain style and scripts needed on a per-card basis.

Managed to get a `<research-projects-list>` web component in after a while. Added the filtering logic to that.

## Step 3 - Publishing and Visibility

Modified the shortcode to list all published projects for site id 1, and all drafts and published projects for departments.

## Sidestep

Tried getting namespaces to work, instead of only doing classes. But WordPress would crash when I used imported classes from a namespace... ChatGPT or google didn't help so I just continued using classes for now.

Filled in 5 research projects each for Handelshögskolan and Institutionen för konstnärliga studier, generated with ChatGPT.

## Step 4 - Role-Based Access

The roles specified in the task already overlaps quite well with the default roles in WordPress. I'll create special permissions for research project-posting and assing it to the roles.

Researchers -> Författare
Coordinator -> Redaktör
Admin -> Admin
View -> a logged out user should be enough?

# Step 5

I believe the custom post type, the metaboxes and native listing of these in the WP UI basically covers this? 

# Step 6

I don't see where sync comes in? I just retrieve and render the data.

# Step 7

I set "show_in_rest" to true in the beginning. Also added "rest_base". The endpoints work on each sub-site; e.g. /handelshogskolan/wp-json/wp/v2/research-projects but not on the main site. I need to research how to get that one working properly...

Created a custom endpoint with `register_rest_route` that returns all published projects from all departments.

# Step 8

TODO

# Step 9

TODO
