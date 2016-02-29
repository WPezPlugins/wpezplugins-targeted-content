###Org: WPezPlugins
#Product: Targeted Content

#####Injects content (via a shortcode) from a custom post type (Targeted Content) based on a shortcode atts; that atts can also map to URL query string args. 


============================================================

Special thanks for JetBrains (https://www.jetbrains.com/) and PHPStorm (https://www.jetbrains.com/phpstorm/) for their support of these OSS orgs: 

 - WPezClasses - https://github.com/WPezClasses
 - WPezPlugins - https://github.com/WPezPlugins
 - WPezBoilerStrap - https://github.com/WPezBoilerStrap
 - ezWebDevTools - https://github.com/ezWebDevTools

============================================================


####Examples

Before we begin, please add three new posts to (the custom post type): Targeted Content. The titles and content don't matter per se, just make sure you're able to tell one from the other by the content. Also, *this is important for these exmaples*, please make sure the slugs (aka names) are: red, blue, and green.

Now in a regular post (or page) and these shortcodes. If you want to add content between the shortcodes, please do so. 

[eztc key="blue"]

[eztc key="green"]

[eztc key="red"]

Yeah, you're right, "Big deal! It just pulled that Targeted Content post of that color (i.e., slug / name. So what!?" 

Hold on, please. The magic is about to begin.

With the [eztc key="red"] still in place, to the frontend URL add a query string: ?red=blue (or &red=blue if you already have a ?... going.)

Now refresh the page...Boom! 

The original shortcode used the key to pull directly from the CPT. But now, when using the URL query string, the shortcode now asks: Is the shortcode key (e.g., red) in the URL? If it is, what is its value (e.g., blue)? Use *that* value (i.e., blue) - not the key's value - to do the WP_Query().

Simple but effective. 


#####But wait! There's more!!

Change the query string bit to: red=bluexyz
 
Refresh the page again...Boom!

This time we queried using 'bluexyz' and (obviously) found nothing. However, the plugin then uses the key's value i(in this case, red) as the value in the WP_Query(). In others words, you don't have to define every possibility for red=' '. Instead, you can have a default to fall back on. 


#####But wait! Yes Virginia!! There's even more!!!

Change the shortcode to: [eztc key="red" default="false"]

Refresh the page again...Boom!

Huh? Nothing??!?! Yeah, we're able to override the setting for using the key as a default. 

Note: You can also set the default (e.g., ?default=false) via the query string but keep in mind that will effect all shortcodes. 


####"Why would I want to do this?"

Think of Targeted Content as primarily a simple landing page building tool. And instead of red, blue and/or green in the query string you used: utm_source, utm_medium, utm_term, utm_content, utm_campaign.

For example, perhaps those coming from utm_source=twitter get a targeted section of content that's different from visits from utm_source=facebook. 

The point being you can deliver different content on the post / page based on different query string pairs. 


============================================================

#####Please follow WPezDeveloper

 - Twitter: https://twitter.com/WPezDeveloper
 - Facebook: https://facebook.com/WPezDeveloper

============================================================


####p.s. Dear Developers,

There's some bonus magic under the hood that isn't documented here (yet.) Please feel free to have a look. If you have any question of comments please use the GitHub issues for this repo. 

Thanks. 
