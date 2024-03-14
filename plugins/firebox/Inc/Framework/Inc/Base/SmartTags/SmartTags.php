<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Base\SmartTags;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Libs\Registry;
use FPFramework\Helpers\StringHelper;

class SmartTags
{
	/**
	 * Factory Class
	 *
	 * @var object
	 */
	protected $factory;

	/**
	 * Path where each extension stores
	 * their Smart Tags.
	 * 
	 * @var  array
	 */
	protected $paths;

	/**
	 * Tags Array
	 *
	 * @var array
	 */
	protected $tags = [];

	/**
	 * All the options that we were given.
	 * This is stored in case we were given options
	 * other then the prefix/placeholder such as a user.
	 * This is useful for other plugins to manipulate the user, etc...
	 * 
	 * @var  array
	 */
	protected $options;

	/**
	 * The Smart Tags pattern used to find all available Smart Tags in a subject.
	 * 
	 * @var  string
	 */
	protected $pattern;

	/**
	 * The Smart Tag prefix
	 * 
	 * @var  string
	 */
	protected $prefix = 'fpf';

	/**
	 * The Smart Tag placeholder
	 * 
	 * @var  string
	 */
	private $placeholder = '{}';
	
	/**
	 * Indicates whether the calculated value will be converted to text using a layout or keep the original type as returned by the value method.
	 * This is supposed to be set to true when the result is supposed to be used later in the code or in a API call, just like we do in FireForms Webhooks.
	 *
	 * @var bool
	 */
	private $prepareValue = true;

	/**
	 * List of excluded files within the FPFramework\Base\SmartTags namespace
	 * 
	 * @var  array
	 */
	private $excluded_smart_tags_files = [
		'.',
		'..',
		'index.php',
		'SmartTag.php',
		'SmartTags.php'
	];

	/**
	 * Smart Tags Constructor
	 * 
	 * @param   array    $opts		An array of options(prefix, placeholder)
	 * @param   Factory  $factory   FPFramework Factory
	 */
	public function __construct($opts = [], $factory = null)
	{
		$this->options = $opts;
		
		// set options
		if (is_array($opts))
		{
			$this->prefix = isset($opts['prefix']) ? $opts['prefix'] : $this->prefix;
			$this->placeholder = isset($opts['placeholder']) ? $opts['placeholder'] : $this->placeholder;
			$this->prepareValue = isset($opts['prepareValue']) ? $opts['prepareValue'] : $this->prepareValue;
		}

		$this->pattern = $this->getPattern();

		// Set Factory
		if (!$factory)
		{
			$factory = new \FPFramework\Base\Factory();
		}

		$this->factory = $factory;

		// register FPFramework Smart Tags
		$this->register('\FPFramework\Base\SmartTags', dirname(__DIR__) . '/SmartTags');
	}

	/**
	 * Get a cache instance of the class
	 * 
	 * @param   array	$opts		An array of options(prefix, placeholder)
	 * @param   object	$factory   	The framework's factory class
	 * 
	 * @return  object
	 */
    static public function getInstance($opts = [], $factory = null)
    {
        static $instance = null;

		if ($instance === null)
		{
            $instance = new SmartTags($opts, $factory);
		}
		
        return $instance;
    }

	/**
	 * Registers a namespace, path and some data where Smart Tags are stored.
	 * 
	 * @param   string  $namespace
	 * @param   string  $path
	 * @param   array   $data
	 * 
	 * @return  void
	 */
	public function register($namespace, $path, $data = [])
	{
		if (!$namespace || !$path)
		{
			return;
		}

		if (isset($this->paths[$namespace]))
		{
			return;
		}

		$this->paths[$namespace] = [
			'path' => $path
		];

		if (isset($data))
		{
			$this->paths[$namespace]['data'] = $data;
		}
	}

	/**
	 * Remove all tags starting with the given prefix.
	 *
	 * @param  string $prefix	The prefix
	 * 
	 * @return void
	 */
	public function removeTagsByPrefix($prefix)
	{
		foreach ($this->tags as $key => $value)
		{
			if (substr($key, 0, strlen($prefix)) !== $prefix)
			{
				continue;
			}

			unset($this->tags[$key]);
		}
		
		return $this;
	}
	
	/**
	 * Adds Custom Tags to the list
	 *
	 * @param  mixed   $tags    Tags list (Array or Object)
	 * @param  string  $prefix  A string to prefix all keys
	 */
	public function add($tags, $prefix = null)
	{
		if (!$tags || !is_array($tags))
		{
			return;
		}

		// Add Prefix to keys
		if ($prefix)
		{
			foreach ($tags as $key => $value)
			{
		        $newKey = strtolower($prefix . $key);
		        $tags[$newKey] = $value;
				unset($tags[$key]);
			}
		}

		$this->tags = array_merge($this->tags, $tags);
		
		return $this;
	}

	/**
	 *  Returns placeholder in 2 pieces
	 *
	 *  @return  array
	 */
	protected function getPlaceholder()
	{
		return str_split($this->placeholder, strlen($this->placeholder) / 2);
	}

	/**
	 *  Replace tags in object
	 *
	 *  @param   mixed  $obj  The data object for search for smarttags
	 *
	 *  @return  mixed
	 */
	public function replace($subject)
	{
		if (is_null($subject))
		{
			return $subject;
		}

		if (is_scalar($subject))
		{
			$this->replaceFoundSmartTags($subject);
		} 
		else 
		{
			foreach ($subject as $key => $subject_item)
			{
				$value = $this->replace($subject_item);

				if ($subject instanceof Registry)
				{
					$subject->set($key, $value);
					continue;
				}

				if (is_object($subject))
				{
					$subject->$key = $value;
					continue;
				}

				if (is_array($subject))
				{
					$subject[$key] = $value;
				}
			}
		}

		return $subject;
	}

	/**
	 * Finds and replaces found Smart Tags in given content
	 * 
	 * @param   string  $content
	 * 
	 * @return  void
	 */
	private function replaceFoundSmartTags(&$content)
	{
		// if no smart tags exist in content, abort
		if (!$this->textHasShortcode($content))
		{
			return;
		}

		// find all Smart Tags
		preg_match_all($this->pattern, $content, $matches);

		// find all Smart Tags and keep the unique only
		$foundSmartTags = array_unique($matches[0]);

		// replaces all Smart Tags in given content
		$this->replaceSmartTagsInContent($content, $foundSmartTags);

		return $content;
	}

	/**
	 * Replaces all Smart Tags in given content
	 * 
	 * @param   string   $string
	 * @param   array    $foundSmartTags
	 * 
	 * @return  void
	 */
	private function replaceSmartTagsInContent(&$content, $foundSmartTags)
	{
		$tag_value_pairs = [];

		// find values for each Smart Tag
		foreach ($foundSmartTags as $tag)
		{
			// prepare the smart tag that is going to be processed
			if (!$shortCodeObject = $this->parseShortcode($tag))
			{
				continue;
			}

			$smartTagName = $shortCodeObject['name'];
			$smartTagClassName = $shortCodeObject['group'];

			// Check if the tag is already processed by a previous operation or its value provided in the payload.
			if (isset($this->tags[$smartTagName]))
			{
				$tag_value_pairs[$tag] = $this->tags[$smartTagName];
				continue;
			}

			// OK, we don't know the value yet. Let's see if there's a method available we can call to get a value. 
			$smartTagNamespace = $shortCodeObject['namespace'];
			
			// get the Smart Tag class
			if (!$smartTag = $this->getSmartTagClassByName($smartTagNamespace, $smartTagClassName, $shortCodeObject['options']))
			{
				/**
				 * No method found to call. If the current Smart Tag was added via add(), remove it, otherwise, leave it as is.
				 * 
				 * This is due to without this check, a Smart Tag may be given i.e. {convertforms 1} which would be removed and thus Convert Forms
				 * wouldn't be able to replace it. We must only remove Smart Tags that were added by add().
				 */
				if (count($this->tags))
				{
					foreach ($this->tags as $key => $value)
					{
						if (strpos($key, $shortCodeObject['group']) !== 0)
						{
							continue;
						}

						$tag_value_pairs[$tag] = '';
						break;
					}
				}

				continue;
			}

			// Set data for Smart Tag if they exist in the path data. 
			if (isset($this->paths[$smartTagNamespace]['data']))
			{
				$smartTag->setData($this->paths[$smartTagNamespace]['data']);
			}

			// Make sure the Smart Tag can do replacements.
			if (!$smartTag->canRun())
			{
				continue;
			}

			// Get the Smart Tag value
			$value = $this->getSmartTagValue($smartTag, $shortCodeObject);

			$value = apply_filters('fpframework/smarttags/value', $value, $smartTag, $shortCodeObject);

			// parse the value to ensure we can save it
			$layout = $shortCodeObject['options'] ? $shortCodeObject['options']->get('layout', '') : null;
			
			if ($this->prepareValue)
			{
				$this->prepareSmartTagValue($value, $layout);
			}
			
			// cache value
			$this->tags[$smartTagName] = $value;
			
			// replace all instances of Smart Tag with its value
			$tag_value_pairs[$tag] = $value;
		}
		
		if (!$tag_value_pairs)
		{
			return;
		}

		// Replace all found Smart Tags in a string
		foreach ($tag_value_pairs as $tag => $value)
		{
			// int, float, string, bool, empty, null
			if (is_scalar($value) || empty($value))
			{
				$content = str_ireplace($tag, (string) $value, $content);
				continue;
			}
			
			$content = $value;
		}
	}

	/**
	 * Prepares the Smart Tag value prior to saving it
	 * 
	 * @param   string   $value
	 * 
	 * @return  void
	 */
	protected function prepareSmartTagValue(&$value, $layout = '')
	{
		if (!$value)
		{
			return $value;
		}

		// Convert string or objects to array
		$values = (array) $value;

		if (empty($layout))
		{
			$value = implode(',', $values);
			return;
		}

		$result = '';

		foreach ($values as $value)
		{
			$result .= str_replace('%value%', $value, $layout);
		}

		$value = $result;
	}

	/**
	 * Parse shortcode and return an array of the shortcode information like, classname, method name e.t.c.
	 * 
	 * The expected shortcode syntax is as follow: {GROUP[.NAME]}
	 * 
	 * The GROUP part is required and must be pointing to \FPFramework\Base\SmartTags\GROUP file which must declare a class with the name GROUP.
	 * Eg: The shortcode {customer} will try to find a class with the name Customer in the \FPFramework\Base\SmartTags\Customer namespace.
	 * 
	 * The NAME part represents the name of the method in the called class. 
	 * For example, the shortcode {customer.name} will call the getName() method in the \FPFramework\Base\SmartTags\Customer class.
	 * 
	 * If the NAME part is ommitted or is invalid, Smart Tags fallbacks to a method with the same name as the class.
	 * For example, the shortcode {customer} will call the getCustomer() method in the \FPFramework\Base\SmartTags\Customer class.
	 *
	 * @param  string $text
	 *
	 * @return array
	 */
	private function parseShortcode($text)
	{
		if (empty($text))
		{
			return;
		}

		// Remove placeholders and prefix from the shortcode. {device} becomes device
		$placeholder = $this->getPlaceholder();
		$text = ltrim($text, $placeholder[0] . $this->prefix);
		$text = rtrim($text, $placeholder[1]);
		$text = trim($text);

		$shortcodeTag = $text;
		$shortcodeOptions = null;

		// Split shortcode into 2 parts. First part should be the Smart Tag itself and the 2nd part should be the parameters.
		$firstOptionPos = strpos($text, '--');

		if ($firstOptionPos !== false)
		{
			$shortcodeOptions = substr($text, $firstOptionPos - strlen($text));
			$shortcodeTag = substr($text, 0, $firstOptionPos - 1);
		}

		// We expect a shortcode in 2 parts separated by a dot. 
		// The 1st part is the Smart Tags Group (Class Name) and the 2nd part is the Name of the actual Smart Tag (Method name, optional). 
		$textParts = explode('.', $shortcodeTag, 2);

		$group = trim($textParts[0]);
		$key = isset($textParts[1]) ? $textParts[1] : $textParts[0];

		// Find shortcode options --option=value
		if (!is_null($shortcodeOptions))
		{
			$shortcodeOptions = $this->parseOptions($shortcodeOptions);
		}

		return [
			'name' => $text, // Rename to shortcode
			'group' => $group,
			'key' => $key,
			'method_name' => 'get' . $key,
			'namespace' => $this->getSmartTagNamespace($group),
			'options' => $shortcodeOptions
		];
	}

	/**
	 * Parase shortcode options
	 *
	 * @param  string $text	The original short code
	 * 
	 * @return mixed Null when no options are found, Registry object otherwise.
	 */
	public function parseOptions($text)
	{
		// A quick test to determine whether to proceed or not.
		if (strpos($text, '--') === false)
		{
			return;
		}

		$regex = '--(.*?)[\W]';

        preg_match_all('/' . $regex . '/is', $text, $params);

		$options = [];

		// @Todo use Regex to parse both option name and value.
		for ($i = 0; $i < count($params[1]); $i++)
		{ 
			$paramName = $params[0][$i];

			$thisParamPosition = mb_strpos($text, $params[0][$i]);
			$nextParamPosition = isset($params[0][$i + 1]) ? mb_strpos($text, $params[0][$i + 1]) - strlen($text) : null;

			$paramValue = \mb_substr($text, $thisParamPosition + strlen($paramName), $nextParamPosition);

			$options[strtolower($params[1][$i])] = trim($paramValue);
		}

		return new Registry($options);
	}

	/**
	 * Returns the Smart Tags Value
	 * 
	 * @param   SmartTag  $smartTag
	 * @param   array     $shortCodeObject  The parsed shortcode object
	 * 
	 * @return  mixed
	 */
	protected function getSmartTagValue($smartTag, $shortCodeObject)
	{
		// Smart Tags method name
		$smartTagMethod = $shortCodeObject['method_name'];

		// make sure method exists in the Smart Tag class
		if (method_exists($smartTag, $smartTagMethod))
		{
			return $smartTag->{$smartTagMethod}();
		}

		/**
		 * Check if the Smart Tag contains a method
		 * to fetch the Smart Tag we are trying to replace.
		 */
		if (method_exists($smartTag, 'fetchValue'))
		{
			return $smartTag->fetchValue($shortCodeObject['key']);
		}
	}

	/**
	 * Returns the Smart Tag Class given the name of the Smart Tag
	 * 
	 * @param   string   $smartTagNamespace
	 * @param   string   $smartTagClassName
	 * @param   array    $shortcodeOptions
	 * 
	 * @return  mixed
	 */
	private function getSmartTagClassByName($smartTagNamespace, $smartTagClassName, $shortcodeOptions = null)
	{
		// get namespace classes
		$namespace_classes = $this->getNamespaceClasses($smartTagNamespace);
		
		if (!isset($namespace_classes[strtolower($smartTagClassName)]))
		{
			return false;
		}

		$smartTagClass = $smartTagNamespace . '\\' . $namespace_classes[strtolower($smartTagClassName)];

		$options = $this->options;
		$options['options'] = $shortcodeOptions;

		// return smart class
		return new $smartTagClass($this->factory, $options);
	}

	/**
	 * Retrieves the cached namespace clases or finds them in the given path
	 * 
	 * @param   string  $namespace
	 * @param   string  $path
	 * 
	 * @return  array
	 */
	private function getNamespaceClasses($namespace, $path = null)
	{
		$hash = md5('fpf_smarttags_' . $namespace);

		// if namespace classes are cached, retrieve them
		if ($classes = wp_cache_get($hash))
		{
			return $classes;
		}
		
		// if no cached namespace classes exist, ensure we were given a valid path
		if (!$path && !is_string($path))
		{
			return [];	
		}

		// find namespace classes
		$namespace_classes = array_diff(scandir($path), $this->excluded_smart_tags_files);

		// stores the final strtolower(class name) => actual class file name data
		$classes_data = [];

		// retrieve the strtolower(class name) => class file name array
		foreach ($namespace_classes as $className)
		{
			$base_class_name = str_replace('.php', '', $className);

			$classes_data[strtolower($base_class_name)] = $base_class_name;
		}
		
		// set cache
		wp_cache_set($hash, $classes_data);

		// cache it
		return $classes_data;
	}

	/**
	 * Find the namespace of the class in the path list
	 * 
	 * @param   string  $class_name
	 * 
	 * @return  mixed
	 */
	private function getSmartTagNamespace($class_name)
	{
		if (!$class_name && !is_string($class_name))
		{
			return false;
		}

		foreach ($this->paths as $namespace => $path_data)
		{
			// get namespace classes
			$namespace_classes = $this->getNamespaceClasses($namespace, $path_data['path']);

			if (!isset($namespace_classes[strtolower($class_name)]))
			{
				continue;
			}
			
			return $namespace;
		}
		
		return false;
	}

	/**
	 * Return the regular expression pattern that will be used for searches
	 *
	 * @return string
	 */
	private function getPattern()
	{
		$placeholder = $this->getPlaceholder();
		$prefix = $this->prefix ? preg_quote($this->prefix) . '.' : '';
		
		return '#(\\' . $placeholder[0] . $prefix . '([a-zA-Z]\\' . $placeholder[0] . '??[^\\' . $placeholder[0] . ']*?\\' . $placeholder[1] . '))#';
	}

	/**
	 * Super fast way to determine whether given text includes shortcodes
	 *
	 * @param  string $text
	 *
	 * @return boolean
	 */
	private function textHasShortcode($text)
	{
		return StringHelper::strpos($text, $this->getPlaceholder()[0] . $this->prefix) !== false;
	}

	/**
	 *  Returns list of all tags found in given paths
	 * 
	 *  @return  array
	 */
	public function get()
	{
		$placeholder = $this->getPlaceholder();

		// get all tags that have already been added to the list
		$smart_tags_data = $this->tags;

		// loop all registered paths
		foreach ($this->paths as $namespace => $path_data)
		{
			if (!isset($path_data['path']))
			{
				continue;
			}

			if (!is_dir($path_data['path']))
			{
				continue;
			}
			
			// find all smart tags
			$files = array_diff(scandir($path_data['path']), $this->excluded_smart_tags_files);

			// search all files
			foreach ($files as $className)
			{
				$baseClassName = rtrim($className, '.php');
				$className = $namespace . '\\' . $baseClassName;

				if (!class_exists($className))
				{
					continue;
				}

				// reflection class of smart tag
				$reflectionSmartTag = new \ReflectionClass($className);

				$hasGet = false;
				$hasFetch = false;

				// search all methods
				foreach($reflectionSmartTag->getMethods() as $method)
				{
					// Only parse Smart Tags of current class and not from its parent
					if ($method->class != ltrim($className, '\\'))
					{
						continue;
					}

					if (strpos($method->name, 'fetchValue') === 0)
					{
						$hasFetch = true;
					}
					
					// get smart tag name from each getSmartTag method
					if (strpos($method->name, 'get') !== 0)
					{
						continue;
					}

					$hasGet = true;

					$smartTagPrefix = '';

					// Starts with getCart
					if (strpos($method->name, 'getCart') === 0)
					{
						$funcNameSplit = array_values(array_filter(explode('getCart', $method->name)));
						$smartTagPrefix = strtolower($baseClassName) . '.cart.' . strtolower($funcNameSplit[0]);
					}
					// Others
					else
					{
						$funcNameSplit = explode('get', $method->name);
	
						$suffix = '';
						if (strtolower($funcNameSplit[1]) != strtolower($reflectionSmartTag->getShortName()))
						{
							$suffix = '.' . $funcNameSplit[1];
						}
						
						$smartTagPrefix = strtolower($reflectionSmartTag->getShortName() . $suffix);
	
					}
					
					$smartTagPrefix = $placeholder[0] . $this->prefix . ' ' . $smartTagPrefix . $placeholder[1];

					$smart_tags_data[$smartTagPrefix] = '';
				}

				if (!$hasGet && $hasFetch)
				{
					$smartTagPrefix = $placeholder[0] . $this->prefix . ' ' . strtolower($reflectionSmartTag->getShortName()) . '.KEY' . $placeholder[1];
					$smart_tags_data[$smartTagPrefix] = '';
				}
			}
		}
		
		return $smart_tags_data;
	}
}