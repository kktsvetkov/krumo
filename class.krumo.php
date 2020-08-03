<?php
/**
* Krumo: Structured information display solution
*
* Krumo is a debugging tool, which displays structured information about any
* PHP variable. It is a nice replacement for print_r() or var_dump() which are
* used by a lot of PHP developers.
*
* @author Kaloyan K. Tsvetkov <kaloyan@kaloyan.info>
* @license https://opensource.org/licenses/LGPL-2.1 GNU Lesser General public License Version 2.1
*/

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
	const version = '0.4.4';

	/**
	* Prints a debug backtrace
	*/
	static function backtrace()
	{
		// disabled ?
		//
		if (!self::_debug())
		{
			return false;
		}

		// render it
		//
		self::$pre_name = 'debug_backtrace()';
		return self::dump(debug_backtrace());
	}

	/**
	* Prints a list of all currently declared classes.
	*/
	static function classes()
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
		self::$pre_name = 'get_declared_classes()';
		return self::dump(get_declared_classes());
	}

	/**
	* Prints a list of all currently declared interfaces (PHP5 only).
	*/
	static function interfaces()
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
		self::$pre_name = 'get_declared_interfaces()';
		return self::dump(get_declared_interfaces());
	}

	/**
	* Prints a list of all currently included (or required) files.
	*/
	static function includes()
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
		self::$pre_name = 'get_included_files()';
		return self::dump(get_included_files());
	}

	/**
	* Prints a list of all currently declared functions.
	*/
	static function functions()
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
		self::$pre_name = 'get_defined_functions()';
		return self::dump(get_defined_functions());
	}

	/**
	* Prints a list of all currently declared constants.
	*/
	static function defines()
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
		self::$pre_name = 'get_defined_constants()';
		return self::dump(get_defined_constants());
	}

	/**
	* Prints a list of all currently loaded PHP extensions.
	*/
	static function extensions()
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
		self::$pre_name = 'get_loaded_extensions()';
		return self::dump(get_loaded_extensions());
	}

	/**
	* Prints a list of all HTTP request headers.
	*/
	static function headers()
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
		self::$pre_name = 'getallheaders()';
		return self::dump(getallheaders());
	}

	/**
	* Prints a list of the configuration settings read from <i>php.ini</i>
	*/
	static function phpini()
	{
		// disabled ?
		//
		if (!self::_debug())
		{
			return false;
		}

		if (!is_readable(get_cfg_var('cfg_file_path')))
		{
			return false;
		}

		// render it
		//
		?>
<div class="krumo-title">
This is a list of the configuration settings read from <code><b><?php
	echo htmlspecialchars( get_cfg_var('cfg_file_path') );
	?></b></code>.
</div>
		<?php
		self::$pre_name = get_cfg_var('cfg_file_path');
		return self::dump(parse_ini_file(get_cfg_var('cfg_file_path'), true));
	}

	/**
	* Prints a list of all your configuration settings.
	*/
	static function conf()
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
		self::$pre_name = 'ini_get_all()';
		return self::dump(ini_get_all());
	}

	/**
	* Prints a list of the specified directories under your <i>include_path</i> option.
	*/
	static function path()
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
		self::$pre_name = 'ini_get("include_path")';
		return self::dump(explode(PATH_SEPARATOR, ini_get('include_path')));
	}

	/**
	* Prints a list of all the values from the <i>$_REQUEST</i> array.
	*/
	static function request()
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
		self::$pre_name = '$_REQUEST';
		return self::dump($_REQUEST);
	}

	/**
	* Prints a list of all the values from the <i>$_GET</i> array.
	*/
	static function get()
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
		self::$pre_name = '$_GET';
		return self::dump($_GET);
	}

	/**
	* Prints a list of all the values from the <i>$_POST</i> array.
	*/
	static function post()
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
		self::$pre_name = '$_POST';
		return self::dump($_POST);
	}

	/**
	* Prints a list of all the values from the <i>$_SERVER</i> array.
	*/
	static function server()
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
		self::$pre_name = '$_SERVER';
		return self::dump($_SERVER);
	}

	/**
	* Prints a list of all the values from the <i>$_COOKIE</i> array.
	*/
	static function cookie()
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
		self::$pre_name = '$_COOKIE';
		return self::dump($_COOKIE);
	}

	/**
	* Prints a list of all the values from the <i>$_ENV</i> array.
	*/
	static function env()
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
		self::$pre_name = '$_ENV';
		return self::dump($_ENV);
	}

	/**
	* Prints a list of all the values from the <i>$_SESSION</i> array.
	*/
	static function session()
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
		self::$pre_name = '$_SESSION';
		return self::dump( isset($_SESSION) ? $_SESSION : array());
	}

	/**
	* Prints a list of all the values from an INI file.
	* @param string $ini_file
	*/
	static function ini($ini_file)
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
		self::$pre_name = $ini_file;
		return self::dump($_);
	}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	* @var string preemptive name for some of the lazy dumps
	*/
	protected static $pre_name = '';

	/**
	* Dump information about a variable
	* @param mixed $data,...
	*/
	static function dump($data)
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
			$args = func_get_args();
			foreach($args as $arg)
			{
				self::dump( $arg );
			}

			return true;
		}

		// the css ?
		//
		self::_css();

		// find caller
		//
		$trace = debug_backtrace(1); // "1" is DEBUG_BACKTRACE_IGNORE_ARGS
		while($d = array_pop($trace))
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
		if (!empty(self::$pre_name))
		{
			$name = self::$pre_name;
			self::$pre_name = '';
		} else
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
							<h6>Krumo v<?php echo self::version;?></h6> | <a
								href="https://github.com/kktsvetkov/krumo"
								target="_blank">github.com/kktsvetkov/krumo</a>
						</div>

					<?php if (!empty($d['file']))
					{
						?>
					<span class="krumo-call">
						<?php printf(
							'Called from <code>%s</code>, line <code>%d</code>',
							$d['file'],
							$d['line']
						); ?>
					</span>
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
	* Return the dump information about variable\variables
	* @param mixed $data,... pass as many arguments as you want
	* @return string
	*/
	static function fetch($data)
	{
		// disabled ?
		//
		if (!self::_debug())
		{
			return false;
		}

		ob_start();
                call_user_func_array(
			array(get_called_class(), 'dump'),
			func_get_args()
			);

                return ob_get_clean();
	}

	/**
	* Prints the dump information about variable\variables at end of a script
	* @param mixed $data,... pass as many arguments as you want
	* @return string
	*/
	static function queue($data)
	{
		$output = call_user_func_array(
			array(get_called_class(), 'fetch'),
			func_get_args()
			);

		register_shutdown_function('printf', '%s', $output);
		return $output;
	}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	* @var string name of the selected skin; must be the
	*	name of one of the folders inside the skins/
	*	folder that contains "skin.css" inside it
	*/
	static $skin = 'kaloyan.info';

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

		// legacy skin names
		//
		switch ($skin)
		{
			case 'schablon.com':
				$skin = 'kaloyan.info';
				break;
		}

		// custom selected skin ?
		//
		$skin_file = __DIR__ . "/skins/{$skin}/skin.css";
		if (!file_exists($skin_file))
		{
			trigger_error(
				"Unable to find \"{$skin_file}\"",
				E_USER_WARNING);

			// use default skin
			//
			$skin_file = __DIR__ . "/skins/default/skin.css";
		}

		$css = file_get_contents($skin_file);
		if (empty($css))
		{
			return false;
		}

		// print ?
		//
		?>
<style type="text/css">
<?php echo $css, "\n", '/* Using Krumo Skin: ', str_replace(__DIR__, '', $skin_file), ' */'; ?>
</style>
<?php
		self::_js();

		return true;
	}

	/**
	* Print the JS
	*/
	protected static function _js()
	{
		?><script type="text/javascript"> krumo = {

			/**
			* Add a CSS class to an HTML element
			* @param {HtmlElement} el
			* @param {String} className
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
			* @param {HtmlElement} el
			* @param {String} className
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
			* @param {HtmlElement} el
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
	static function enable()
	{
		return true === self::_debug(true);
	}

	/**
	* Disable Krumo
	*
	* @return boolean
	*/
	static function disable()
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
		if (null === $data)
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
		static $_bee_hive = array();

		// new bee ?
		//
		if (null === $bee)
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

			$_bee_hive[0][] =& $bee; // KT: stupid static reference hack
		}

		// return all bees
		//
		return $_bee_hive[0];
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
		$has_recursion = ($_is_object)
			? !empty($data->$_recursion_marker)
			: !empty($data[$_recursion_marker]) ;

		// recursion detected
		//
		if ($has_recursion)
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
		$has_properties = !empty(get_object_vars($data));
		?>
		<li class="krumo-child">

			<div <?php if ($has_properties) {?> onClick="krumo.toggle(this);"<?php } ?>
				class="krumo-element<?php echo $has_properties ? ' krumo-expand' : '';?>" >

					<a class="krumo-name"><?php echo $name;?></a>
					(<em class="krumo-type">Object</em>)
					<strong class="krumo-class">
						<?php
						echo htmlSpecialChars( get_class($data) );
						?>
					</strong>
			</div>

			<?php if ($has_properties)
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
	* This constant is the default value for the maximum length of strings
	* that will be shown as they are. Longer strings will be truncated to
	* length of {@link Krumo::$truncate_length}, and their `full form` will
	* be shown in a child node.
	*/
	const TRUNCATE_LENGTH = 50;

	/**
	* @var integer
	* @see Krumo::_string()
	*/
	static $truncate_length = self::TRUNCATE_LENGTH;

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

		$truncate_length = (int) self::$truncate_length;
		if (defined('KRUMO_TRUNCATE_LENGTH'))
		{
			// use legacy setting instead
			//
			$truncate_length = KRUMO_TRUNCATE_LENGTH;
		}

		if (strlen($data) > $truncate_length)
		{
			$_ = substr($data, 0, $truncate_length - 3) . '...';
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
* @see krumo::dump()
*/
function krumo()
{
	$_ = func_get_args();
	return call_user_func_array(
		array('krumo', 'dump'), $_
		);
}
