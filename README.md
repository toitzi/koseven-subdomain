# Koseven Subdomain Module
![License](https://img.shields.io/badge/license-BSD--3--Clause-green.svg)

This is a simple Subdomain module for Koseven, which started as a port 
from jeanmasks sbdomain module for Kohana 3.x

## :sparkles: Features

* Different Routes for diffrent Subdomains

## :page_facing_up: Configuration

After enabling the module in `Kohana::modules`, set base_url to an absolute value in your bootstrap:

```
Kohana::init(array(  
   'base_url'   => 'http://your_domain.com/',  
));
```
		
### :clipboard: Route Examples

Let's say your base_url is "http://example.com".  
The following Route will route every request to "http://example.com" and "http://www.example.com":

```
Route::$default_subdomains = array(Route::SUBDOMAIN_EMPTY, 'www');  
Route::set('default', '(<controller>(/<action>(/<id>)))')  
  ->defaults(array(  
     'controller' => 'welcome',  
     'action'     => 'index',  
  ));
```

While this Route only routes requests sent to "http://test.example.com" and "http://test2.example.com":

```
Route::set('subdomain', '(<controller>(/<action>(/<id>)))')  
  ->subdomains(array('test', 'test2'))  
  ->defaults(array(  
     'controller' => 'test',  
     'action'     => 'index',  
  ));
```

## :thumbsup: Special Thanks

Thanks a lot to [jeanmask](https://github.com/jeanmask)!

The module is maintained by [toitzi](https://github.com/toitzi).

## :clap: Contributing

As usual, [fork and send pull requests](https://help.github.com/articles/fork-a-repo)

## :beginner: Getting Help

* Open issues in this project.
