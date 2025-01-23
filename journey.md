First issue was getting some sort of WordPress environment to run locally. I'm using Project Bluefin which is an immutable linux distribution and it wants everything containerized.

I first tried getting WordPress Playground to work in VSCode. I had some issues at first when submitting custom post types, getting a “The Link You Followed Has Expired” error. Nice and helpful error message... I threw all I did in the trash and re-made the plugin again following some other resources online. And now it worked. So it must have beens something weird in the saving step. Wouldn't hurt if WordPress had a more descriptive error message... haha.

WordPress Playground doesn't seem to support multisite; so I abandoned developing the plugin with that. It was nice for single site development though! Quick and easy to test.

I instead spend and hour or so getting WordPress multi-site to work with docker and docker-compose. Met some struggles with the `.htaccess` needing updates for multisites on sub-paths; something WordPress did not tell me after configuring it.... Bad on their end!

Adding the custom post type, metaboxes and the saving was otherwise quite trivial. Once again had some struggles trying to get the sub-site/department id. I asked the [deepseek-r1 AI model](https://ollama.com/library/deepseek-r1) why I was given no value by WordPress. Apparently, it was not available when add_meta_boxes runs. A fun quirk I guess you know about already when you do WordPress development on the daily, but not so obvious for new developers or someone like me that hasn't touched WordPress in a while... My first idea was to pre-fill the value initially in the HTMLInputElement before the initial draft/publish in the WP UI has been done - instead of having an empty field. But since I cannot call the method in there I resorted to setting the value in the save_post hook instead. (I suppose there are alterative ways to get the value, but I'll continue on the over all task instead of polishing that).


