Krumo: PHP structured information display solution
=====

![KRUMO - version 2.0 of print_r(); and var_dump();](http://krumo.kaloyan.info/images/logo.png)

Krumo is a debugging tool, which displays structured information about any PHP variable.
It is a nice replacement for print_r() or var_dump() which are used by a lot of PHP developers.

The project was first hosted and maintained at http://www.sourceforge.net/projects/krumo/

OVERVIEW
------------------------------------------------------------------------------
To put it simply, Krumo is a replacement for print_r() and var_dump(). By definition Krumo
is a debugging tool, which displays structured information about any PHP variable.

A lot of developers use print_r() and var_dump() in the means of debugging
tools. Although they were intended to present human readble information about a
variable, we can all agree that in general they are not. Krumo is an
alternative: it does the same job, but it presents the information beautified
using CSS/JS/HTML.

EXAMPLES
------------------------------------------------------------------------------
Here's a basic example, which will return a report on the array variable passed
as argument to it:

	krumo(array('a1'=> 'A1', 3, 'red'));

You can dump simultaneously more then one variable - here's another example:

	krumo($_SERVER, $_REQUEST);

You probably saw from the examples above that some of the nodes are expandable,
so if you want to inspect the nested information, click on them and they will
expand; if you do not need that information shown simply click again on it to
collapse it. Here's an example to test this:

	$x1->x2->x3->x4->x5->x6->x7->x8->x9 = 'X10';
	krumo($x1);

The krumo() is the only standalone function from the package, and this is
because basic dumps about variables (like print_r() or var_dump()) are the most
common tasks such functionality is used for. The rest of the functionality can
be called using static calls to the Krumo class. Here are several more examples:

	// print a debug backgrace
 	krumo::backtrace();

	// print all the included(or required) files
	krumo::includes();

	// print all the included functions
	krumo::functions();

	// print all the declared classes
	krumo::classes();

	// print all the defined constants
	krumo::defines();

 ... and so on, etc.

If you want to get the output returned instead of printed, you can use
the `krumo::fetch()` method for that:

	$a = krumo::fetch($app, $env);

Please note that the first time you call `Krumo` the dump it produces also
prints the CSS and the JS code used to expand/collapse the dump nodes.

SKINS
------------------------------------------------------------------------------
There are several skins pre-installed with this package, but if you wish you can
create skins of your own. The skins are simply CSS files that are prepended to
the result that Krumo prints.

To the Krumo skin, you have to set it at `krumo::$skin`:

	krumo::$skin = 'blue';

If you want to use images in your CSS (for background, list-style, etc), you
have to put them inline inside the CSS class as data URIs:

	background-image: url(data:image/gif;base64,R0lGODlhCQAJALMAAP///wAAAP///wAA...AJAAkAAAQTEIAna33USpwt79vncRpZgpcGRAA7);

Here's an example:

	background: white url(data:image/gif;base64,R0lGODlhCgACALMAAP///8D...AIAAAQIEMhJA7D4gggAOw==) repeat-x;

Here is what the pre-installed skins look like:

* skins/default
	![skins/schablon.com](http://krumo.kaloyan.info/screenshots/krumo_default_theme.png)

* skins/blue
	![skins/schablon.com](http://krumo.kaloyan.info/screenshots/krumo_blue_theme.png)

* skins/orange
	![skins/schablon.com](http://krumo.kaloyan.info/screenshots/krumo_orange_theme.png)

* skins/green
	![skins/schablon.com](http://krumo.kaloyan.info/screenshots/krumo_green_theme.png)

* skins/schablon.com
	![skins/schablon.com](http://krumo.kaloyan.info/screenshots/krumo_schablon_com_theme.png)

LICENSE
------------------------------------------------------------------------------
This project is released under GNU Lesser General Public License v2.1
