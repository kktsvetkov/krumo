/**
* JavaScript routines for Krumo
* @link https://github.com/kktsvetkov/krumo
*/
krumo = {

	/**
	* Add a CSS class to an HTML element
	* @param HtmlElement el
	* @param string className
	*/
	"reclass": function(el, className)
	{
		if (el.className.indexOf(className) < 0)
		{
			el.className += (' ' + className);
		}
	},

	/**
	* Remove a CSS class to an HTML element
	* @param HtmlElement el
	* @param string className
	*/
	"unclass": function(el, className)
	{
		if (el.className.indexOf(className) > -1)
		{
			el.className = el.className.replace(className, '');
		}
	},

	/**
	* Toggle the nodes connected to an HTML element
	* @param HtmlElement el
	*/
	"toggle": function(el)
	{
		var ul = el.parentNode.getElementsByTagName('ul');
		for (var i=0; i<ul.length; i++)
		{
			if (ul[i].parentNode.parentNode == el.parentNode)
			{
				ul[i].parentNode.style.display = (ul[i].parentNode.style.display == 'none')
					? 'block'
					: 'none';
			}
		}

		(ul[0].parentNode.style.display == 'block')
			? krumo.reclass(el, 'krumo-opened')
			: krumo.unclass(el, 'krumo-opened');
	}
}
