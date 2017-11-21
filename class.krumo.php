<?php
/**
* Krumo: Structured information display solution
*
* Krumo is a debugging tool, which displays structured information about any
* PHP variable. It is a nice replacement for print_r() or var_dump() which are
* used by a lot of PHP developers.
*
* @author Kaloyan K. Tsvetkov <kaloyan@kaloyan.info>
* @license http://opensource.org/licenses/lgpl-license.php GNU Lesser General public License Version 2.1
*
* @package Krumo
* @version 0.4
*/

//////////////////////////////////////////////////////////////////////////////

/**
* Set the KRUMO_DIR constant up with the absolute path to Krumo files. If it
* is not defined, include_path will be used. Set KRUMO_DIR only if any other
* module or application has not already set it up.
*/
if (!defined('KRUMO_DIR'))
{
	define('KRUMO_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);
}

/**
* This constant sets the maximum strings of strings that will be shown
* as they are. Longer strings will be truncated with this length, and
* their `full form` will be shown in a child node.
*/
if (!defined('KRUMO_TRUNCATE_LENGTH'))
{
	define('KRUMO_TRUNCATE_LENGTH', 50);
}

//////////////////////////////////////////////////////////////////////////////

/**
* Krumo API
*
* This class stores the Krumo API for rendering and
* displaying the structured information it is reporting
*
* @package Krumo
*/
class krumo
{
	/**
	* Return Krumo version
	* @return string
	*/
	public static function version()
	{
		return '0.4';
	}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	* Prints a debug backtrace
	*/
	public static function backtrace()
	{
		// disabled ?
		//
		if (!self::_debug())
		{
			return false;
		}

		// render it
		//
		return self::dump(debug_backtrace());
	}

	/**
	* Prints a list of all currently declared classes.
	*/
	public static function classes()
	{
		// disabled ?
		//
		if (!self::_debug())
		{
			return false;
		}

		// render it
		//
		?>
<div class="krumo-title">
This is a list of all currently declared classes.
</div>
		<?php
		return self::dump(get_declared_classes());
	}

	/**
	* Prints a list of all currently declared interfaces (PHP5 only).
	*/
	public static function interfaces()
	{
		// disabled ?
		//
		if (!self::_debug())
		{
			return false;
		}

		// render it
		//
		?>
<div class="krumo-title">
This is a list of all currently declared interfaces.
</div>
		<?php
		return self::dump(get_declared_interfaces());
	}

	/**
	* Prints a list of all currently included (or required) files.
	*/
	public static function includes()
	{
		// disabled ?
		//
		if (!self::_debug())
		{
			return false;
		}

		// render it
		//
		?>
<div class="krumo-title">
This is a list of all currently included (or required) files.
</div>
		<?php
		return self::dump(get_included_files());
	}

	/**
	* Prints a list of all currently declared functions.
	*/
	public static function functions()
	{
		// disabled ?
		//
		if (!self::_debug())
		{
			return false;
		}

		// render it
		//
		?>
<div class="krumo-title">
This is a list of all currently declared functions.
</div>
		<?php
		return self::dump(get_defined_functions());
	}

	/**
	* Prints a list of all currently declared constants.
	*/
	public static function defines()
	{
		// disabled ?
		//
		if (!self::_debug())
		{
			return false;
		}

		// render it
		//
		?>
<div class="krumo-title">
This is a list of all currently declared constants (defines).
</div>
		<?php
		return self::dump(get_defined_constants());
	}

	/**
	* Prints a list of all currently loaded PHP extensions.
	*/
	public static function extensions()
	{
		// disabled ?
		//
		if (!self::_debug())
		{
			return false;
		}

		// render it
		//
		?>
<div class="krumo-title">
This is a list of all currently loaded PHP extensions.
</div>
		<?php
		return self::dump(get_loaded_extensions());
	}

	/**
	* Prints a list of all HTTP request headers.
	*/
	public static function headers()
	{
		// disabled ?
		//
		if (!self::_debug())
		{
			return false;
		}

		if (!function_exists('getallheaders'))
		{
			function getallheaders()
			{
				$headers = array ();
				foreach ($_SERVER as $name => $value)
				{
					if (substr($name, 0, 5) == 'HTTP_')
					{
						$key = str_replace(
							' ',
							'-',
							ucwords(strtolower(
								str_replace('_', ' ', substr($name, 5))
								))
							);
						$headers[$key] = $value;
					}
				}
				return $headers;
			}
		}

		// render it
		//
		?>
<div class="krumo-title">
This is a list of all HTTP request headers.
</div>
		<?php
		return self::dump(getallheaders());
	}

	/**
	* Prints a list of the configuration settings read from <i>php.ini</i>
	*/
	public static function phpini()
	{
		// disabled ?
		//
		if (!self::_debug())
		{
			return false;
		}

		if (!readable(get_cfg_var('cfg_file_path')))
		{
			return false;
		}

		// render it
		//
		?>
<div class="krumo-title">
This is a list of the configuration settings read from <code><b><?php echo get_cfg_var('cfg_file_path');?></b></code>.
</div>
		<?php
		return self::dump(parse_ini_file(get_cfg_var('cfg_file_path'), true));
	}

	/**
	* Prints a list of all your configuration settings.
	*/
	public static function conf()
	{
		// disabled ?
		//
		if (!self::_debug())
		{
			return false;
		}

		// render it
		//
		?>
<div class="krumo-title">
This is a list of all your configuration settings.
</div>
		<?php
		return self::dump(ini_get_all());
	}

	/**
	* Prints a list of the specified directories under your <i>include_path</i> option.
	*/
	public static function path()
	{
		// disabled ?
		//
		if (!self::_debug())
		{
			return false;
		}

		// render it
		//
		?>
<div class="krumo-title">
This is a list of the specified directories under your <code><b>include_path</b></code> option.
</div>
		<?php
		return self::dump(explode(PATH_SEPARATOR, ini_get('include_path')));
	}

	/**
	* Prints a list of all the values from the <i>$_REQUEST</i> array.
	*/
	public static function request()
	{
		// disabled ?
		//
		if (!self::_debug())
		{
			return false;
		}

		// render it
		//
		?>
<div class="krumo-title">
This is a list of all the values from the <code><b>$_REQUEST</b></code> array.
</div>
		<?php
		return self::dump($_REQUEST);
	}

	/**
	* Prints a list of all the values from the <i>$_GET</i> array.
	*/
	public static function get()
	{
		// disabled ?
		//
		if (!self::_debug())
		{
			return false;
		}

		// render it
		//
		?>
<div class="krumo-title">
This is a list of all the values from the <code><b>$_GET</b></code> array.
</div>
		<?php
		return self::dump($_GET);
	}

	/**
	* Prints a list of all the values from the <i>$_POST</i> array.
	*/
	public static function post()
	{
		// disabled ?
		//
		if (!self::_debug())
		{
			return false;
		}

		// render it
		//
		?>
<div class="krumo-title">
This is a list of all the values from the <code><b>$_POST</b></code> array.
</div>
		<?php
		return self::dump($_POST);
	}

	/**
	* Prints a list of all the values from the <i>$_SERVER</i> array.
	*/
	public static function server()
	{
		// disabled ?
		//
		if (!self::_debug())
		{
			return false;
		}

		// render it
		//
		?>
<div class="krumo-title">
This is a list of all the values from the <code><b>$_SERVER</b></code> array.
</div>
		<?php
		return self::dump($_SERVER);
	}

	/**
	* Prints a list of all the values from the <i>$_COOKIE</i> array.
	*/
	public static function cookie()
	{
		// disabled ?
		//
		if (!self::_debug())
		{
			return false;
		}

		// render it
		//
		?>
<div class="krumo-title">
This is a list of all the values from the <code><b>$_COOKIE</b></code> array.
</div>
		<?php
		return self::dump($_COOKIE);
	}

	/**
	* Prints a list of all the values from the <i>$_ENV</i> array.
	*/
	public static function env()
	{
		// disabled ?
		//
		if (!self::_debug())
		{
			return false;
		}

		// render it
		//
		?>
<div class="krumo-title">
This is a list of all the values from the <code><b>$_ENV</b></code> array.
</div>
		<?php
		return self::dump($_ENV);
	}

	/**
	* Prints a list of all the values from the <i>$_SESSION</i> array.
	*/
	public static function session()
	{
		// disabled ?
		//
		if (!self::_debug())
		{
			return false;
		}

		// render it
		//
		?>
<div class="krumo-title">
This is a list of all the values from the <code><b>$_SESSION</b></code> array.
</div>
		<?php
		return self::dump($_SESSION);
	}

	/**
	* Prints a list of all the values from an INI file.
	* @param string $ini_file
	*/
	public static function ini($ini_file)
	{
		// disabled ?
		//
		if (!self::_debug())
		{
			return false;
		}

		// read it
		//
		if (!$_ = @parse_ini_file($ini_file, 1))
		{
			return false;
		}

		// render it
		//
		?>
<div class="krumo-title">
This is a list of all the values from the <code><b><?php
	echo realpath($ini_file)
		? realpath($ini_file)
		: $ini_file;
	?></b></code> INI file.
</div>
		<?php
		return self::dump($_);
	}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	* Dump information about a variable
	* @param mixed $data,...
	*/
	public static function dump($data)
	{
		// disabled ?
		//
		if (!self::_debug())
		{
			return false;
		}

		// more arguments ?
		//
		if (func_num_args() > 1)
		{
			$_ = func_get_args();
			foreach($_ as $d)
			{
				self::dump($d);
			}

			return true;
		}

		// the css ?
		//
		self::_css();

		// find caller
		//
		$_ = debug_backtrace();
		while($d = array_pop($_))
		{
			if (0 === strcasecmp($d['function'], 'krumo'))
			{
				break;
			}

			if (!empty($d['class']))
			{
				if (0 === strcasecmp($d['class'], 'krumo'))
				{
					break;
				}
			}
		}

		// find what the argument was ?
		//
		$name = '';
		if (!empty($d['file']))
		{
			$f = file($d['file']);
			$name = trim($f[$d['line']-1]);

			// multi-line call ?
			//
			if (false === stripos($name, 'krumo'))
			{
				for ($i=$d['line']-2; $i >=0; $i--)
				{
					$name = $f[$i] . $name;
					if (false !== stripos($name, 'krumo'))
					{
						break;
					}
				}
			}
			$name = preg_replace(array(
					'~^.+(krumo)~Uis',
					'~\?>$~',
					),
				array(
					'\\1',
					''
					),
				$name);

			unset($f);
		}


		// the content
		//
		?>
			<div class="krumo-root">
				<ul class="krumo-node krumo-first">
					<?php echo self::_dump($data, $name);?>
					<li class="krumo-footnote">
						<div class="krumo-version" style="white-space:nowrap;">
							<h6>Krumo version <?php echo self::version();?></h6> | <a
								href="https://github.com/kktsvetkov/krumo"
								target="_blank">github.com/kktsvetkov/krumo</a>
						</div>

					<?php if (!empty($d['file']))
					{
						?>
					<span class="krumo-call" style="white-space:nowrap;">
						Called from <code><?php echo $d['file']?></code>,
							line <code><?php echo $d['line']?></code></span>
						<?php
					} ?>
					&nbsp;
					</li>
				</ul>
			</div>
		<?php

		// flee the hive
		//
		$_recursion_marker = self::_marker();
		if ($hive =& self::_hive($dummy))
		{
			foreach($hive as $i=>$bee)
			{
				if (is_object($bee))
				{
					unset($hive[$i]->$_recursion_marker);
				} else
				{
					unset($hive[$i][$_recursion_marker]);
				}
			}
		}
	}

	/**
	* Return the dump information about a variable
	* @param mixed $data,...
	*/
	public static function fetch($data)
	{
		// disabled ?
		//
		if (!self::_debug())
		{
			return false;
		}

		ob_start();
                call_user_func_array(
			array(__CLASS__, 'dump'),
			func_get_args()
			);

                return ob_get_clean();
	}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	* @var string name of the selected skin; must be the
	*	name of one of the folders inside the skins/
	*	folder that contains "skin.css" inside it
	*/
	public static $skin = 'schablon.com';

	/**
	* Print the skin (CSS)
	* @return boolean
	*/
	private static function _css()
	{
		static $_css = false;

		// already printed ?
		//
		if ($_css)
		{
			return true;
		}

		$css = '';
		$skin = self::$skin;

		// custom selected skin ?
		//
		$_ = KRUMO_DIR . "skins/{$skin}/skin.css";
		if (!file_exists($_))
		{
			trigger_error(
				"Unable to find \"{$_}\"",
				E_USER_WARNING);
		} else
		{
			$css = file_get_contents($_);
		}

		// default skin ?
		//
		if (!$css && ($skin != 'default'))
		{
			$skin = 'default';
			$_ = KRUMO_DIR . "skins/default/skin.css";
			$css = file_get_contents($_);
		}

		// print ?
		//
		if ($_css = ($css != ''))
		{
			?>
<style type="text/css">
<?php echo $css?>
/* Using Krumo Skin: <?php echo preg_replace(
	'~^' . preg_quote(realpath(KRUMO_DIR) . DIRECTORY_SEPARATOR) . '~Uis',
	'',
	realpath($_));?> */
</style><?php
			self::_js();
		}

		return $_css;
	}

	/**
	* Print the JS
	*/
	protected static function _js()
	{
		?><script type="text/javascript"> krumo = {

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
		</script><?php
	}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	* Enable Krumo
	*
	* @return boolean
	*/
	public static function enable()
	{
		return true === self::_debug(true);
	}

	/**
	* Disable Krumo
	*
	* @return boolean
	*/
	public static function disable()
	{
		return false === self::_debug(false);
	}

	/**
	* Get\Set Krumo state: whether it is enabled or disabled
	*
	* @param boolean $state
	* @return boolean
	*/
	private static function _debug($state=null)
	{
		static $_ = true;

		// set
		//
		if (isset($state))
		{
			$_ = (boolean) $state;
		}

		// get
		//
		return $_;
	}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	* Dump information about a variable
	*
	* @param mixed $data
	* @param string $name
	*/
	private static function _dump(&$data, $name='...')
	{

		// object ?
		//
		if (is_object($data))
		{
			return self::_object($data, $name);
		}

		// array ?
		//
		if (is_array($data))
		{
			return self::_array($data, $name);
		}

		// resource ?
		//
		if (is_resource($data))
		{
			return self::_resource($data, $name);
		}

		// scalar ?
		//
		if (is_string($data))
		{
			return self::_string($data, $name);
		}

		if (is_float($data))
		{
			return self::_float($data, $name);
		}

		if (is_integer($data))
		{
			return self::_integer($data, $name);
		}

		if (is_bool($data))
		{
			return self::_boolean($data, $name);
		}

		// null ?
		//
		if (is_null($data))
		{
			return self::_null($name);
		}
	}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	* Render a dump for a NULL value
	*
	* @param string $name
	* @return string
	*/
	private static function _null($name)
	{
		?>
		<li class="krumo-child">
			<div class="krumo-element">
				<a class="krumo-name"><?php echo $name;?></a>
				(<em class="krumo-type krumo-null">NULL</em>)
			</div>
		</li>
		<?php
	}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	* Return the marked used to stain arrays
	* and objects in order to detect recursions
	*
	* @return string
	*/
	private static function _marker()
	{
		static $_recursion_marker;
		if (!isset($_recursion_marker))
		{
			$_recursion_marker = uniqid('krumo');
		}

		return $_recursion_marker;
	}

	/**
	* Adds a variable to the hive of arrays and objects which
	* are tracked for whether they have recursive entries
	*
	* @param mixed &$bee either array or object, not a scallar vale
	* @return array all the bees
	*/
	private static function &_hive(&$bee)
	{
		static $_ = array();

		// new bee ?
		//
		if (!is_null($bee))
		{

			// stain it
			//
			$_recursion_marker = self::_marker();
			(is_object($bee))
				? (empty($bee->$_recursion_marker)
					? $bee->$_recursion_marker = 1
					: $bee->$_recursion_marker++
					)
				: (empty($bee[$_recursion_marker])
					? $bee[$_recursion_marker] = 1
					: $bee[$_recursion_marker]++
					);

			$_[0][] =& $bee; // KT: stupid PHP4 static reference hack
		}

		// return all bees
		//
		return $_[0];
	}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	* Render a dump for the properties of an array or objeect
	*
	* @param mixed &$data
	*/
	private static function _vars(&$data)
	{
		$_is_object = is_object($data);

		// test for references in order to
		// prevent endless recursion loops
		//
		$_recursion_marker = self::_marker();
		$_r = ($_is_object)
			? !empty($data->$_recursion_marker)
			: !empty($data[$_recursion_marker]) ;
		$_r = (integer) $_r;

		// recursion detected
		//
		if ($_r > 0)
		{
			return self::_recursion();
		}

		// stain it
		//
		self::_hive($data);

		// render it
		//
		?>
		<div class="krumo-nest" style="display:none;">
			<ul class="krumo-node">
		<?php

		// keys ?
		//
		$keys = ($_is_object)
			? array_keys(get_object_vars($data))
			: array_keys($data);

		// itterate
		//
		foreach($keys as $k)
		{
			// skip marker
			//
			if ($k === $_recursion_marker)
			{
				continue;
			}

			// get real value
			//
			if ($_is_object)
			{
				$v =& $data->$k;
			} else
			{
				$v =& $data[$k];
			}

			self::_dump($v,$k);
		} ?>
			</ul>
		</div>
		<?php
	}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	* Render a block that detected recursion
	*/
	private static function _recursion()
	{
		?>
		<div class="krumo-nest" style="display:none;">
			<ul class="krumo-node">
				<li class="krumo-child">
					<div class="krumo-element">
						<a class="krumo-name"><big>&#8734;</big></a>
						(<em class="krumo-type">Recursion</em>)
					</div>
				</li>
			</ul>
		</div>
		<?php
	}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	* Render a dump for an array
	*
	* @param mixed $data
	* @param string $name
	*/
	private static function _array(&$data, $name)
	{
		?>
		<li class="krumo-child">

			<div <?php if (count($data) > 0) {?> onClick="krumo.toggle(this);"<?php } ?>
				class="krumo-element<?php echo count($data) > 0 ? ' krumo-expand' : '';?>">

					<a class="krumo-name"><?php echo $name;?></a>
					(<em class="krumo-type">Array, <strong class="krumo-array-length"><?php echo
						(count($data)==1)
							? ('1 element')
							: (count($data) . ' elements');
						?></strong></em>)
					<?php

					// callback ?
					//
					if (is_callable($data))
					{
						$_ = array_values($data);
						?>
						<span class="krumo-callback"> |
							(<em class="krumo-type">Callback</em>)
							<strong class="krumo-string"><?php
								echo htmlSpecialChars($_[0]);?>::<?php
								echo htmlSpecialChars($_[1]);?>();</strong></span>
						<?php
					}
				?>
			</div>

			<?php if (count($data))
			{
				self::_vars($data);
			} ?>
		</li>
		<?php
	}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	* Render a dump for an object
	*
	* @param mixed $data
	* @param string $name
	*/
	private static function _object(&$data, $name)
	{
		?>
		<li class="krumo-child">

			<div <?php if (count($data) > 0) {?> onClick="krumo.toggle(this);"<?php } ?>
				class="krumo-element<?php echo count($data) > 0 ? ' krumo-expand' : '';?>" >

					<a class="krumo-name"><?php echo $name;?></a>
					(<em class="krumo-type">Object</em>)
					<strong class="krumo-class"><?php echo get_class($data);?></strong>
			</div>

			<?php if (count($data))
			{
				self::_vars($data);
			} ?>
		</li>
		<?php
	}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	* Render a dump for a resource
	*
	* @param mixed $data
	* @param string $name
	*/
	private static function _resource($data, $name)
	{
		?>
		<li class="krumo-child">
			<div class="krumo-element">
				<a class="krumo-name"><?php echo $name;?></a>
				(<em class="krumo-type">Resource</em>)
				<strong class="krumo-resource"><?php echo get_resource_type($data);?></strong>
			</div>
		</li>
		<?php
	}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	* Render a dump for a boolean value
	*
	* @param mixed $data
	* @param string $name
	*/
	private static function _boolean($data, $name)
	{
		?>
		<li class="krumo-child">
			<div class="krumo-element">
				<a class="krumo-name"><?php echo $name;?></a>
				(<em class="krumo-type">Boolean</em>)
				<strong class="krumo-boolean"><?php
					echo $data
						? 'TRUE'
						: 'FALSE';
					?></strong>
			</div>
		</li>
		<?php
	}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	* Render a dump for a integer value
	*
	* @param mixed $data
	* @param string $name
	*/
	private static function _integer($data, $name)
	{
		?>
		<li class="krumo-child">
			<div class="krumo-element">
				<a class="krumo-name"><?php echo $name;?></a>
				(<em class="krumo-type">Integer</em>)
				<strong class="krumo-integer"><?php echo $data;?></strong>
			</div>
		</li>
		<?php
	}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	* Render a dump for a float value
	*
	* @param mixed $data
	* @param string $name
	*/
	private static function _float($data, $name)
	{
		?>
		<li class="krumo-child">
			<div class="krumo-element">
				<a class="krumo-name"><?php echo $name;?></a>
				(<em class="krumo-type">Float</em>)
				<strong class="krumo-float"><?php echo $data;?></strong>
			</div>
		</li>
		<?php
	}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	* Render a dump for a string value
	*
	* @param mixed $data
	* @param string $name
	*/
	private static function _string($data, $name)
	{
		// extra ?
		//
		$_extra = false;
		$_ = $data;
		if (strLen($data) > KRUMO_TRUNCATE_LENGTH)
		{
			$_ = substr($data, 0, KRUMO_TRUNCATE_LENGTH - 3) . '...';
			$_extra = true;
		}

		?>
		<li class="krumo-child">
			<div <?php if ($_extra) {?> onClick="krumo.toggle(this);"<?php } ?>
				class="krumo-element<?php echo $_extra ? ' krumo-expand' : '';?>" >

					<a class="krumo-name"><?php echo $name;?></a>
					(<em class="krumo-type">String,
						<strong class="krumo-string-length"><?php
							echo strlen($data) ?> characters</strong> </em>)
					<strong class="krumo-string"><?php echo htmlSpecialChars($_);?></strong>

					<?php

					// callback ?
					//
					if (is_callable($data))
					{
						?>
						<span class="krumo-callback"> |
							(<em class="krumo-type">Callback</em>)
							<strong class="krumo-string"><?php
								echo htmlSpecialChars($_);
							?>();</strong></span>
						<?php
					} ?>

			</div>

			<?php if ($_extra)
			{
				?>
				<div class="krumo-nest" style="display:none;">
					<ul class="krumo-node">
						<li class="krumo-child">
							<div class="krumo-preview"><?php
								echo htmlSpecialChars($data);?></div>
						</li>
					</ul>
				</div>
				<?php
			} ?>
		</li>
		<?php
	}
}

//////////////////////////////////////////////////////////////////////////////

/**
* Alias of {@link krumo::dump()}
*
* @param mixed $data,...
* @see kurmo::dump()
*/
function krumo()
{
	$_ = func_get_args();
	return call_user_func_array(
		array('krumo', 'dump'), $_
		);
}
