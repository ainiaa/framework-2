<?php

namespace mako\event;

use \mako\utility\String;

/**
 * Observable trait.
 *
 * @author     Frederic G. Østby
 * @copyright  (c) 2008-2013 Frederic G. Østby
 * @license    http://www.makoframework.com/license
 */

trait ObservableTrait
{
	//---------------------------------------------
	// Trait properties
	//---------------------------------------------

	/**
	 * Observers.
	 * 
	 * @var array
	 */

	protected $_observers = array();

	/**
	 * Static (global) observers.
	 * 
	 * @var array
	 */

	protected static $_staticObservers = array();

	//---------------------------------------------
	// Trait methods
	//---------------------------------------------

	/**
	 * Attach an observer.
	 * 
	 * @access  public
	 * @param   object  $observer  Observer instance
	 */

	public function attachObserver($observer)
	{
		$this->_observers[] = $observer;
	}

	/**
	 * Attach a static observer.
	 * 
	 * @access  public
	 * @param   object  $observer  Observer instance
	 */

	public static function attachStaticObserver($observer)
	{
		static::$_staticObservers[] = $observer;
	}

	/**
	 * Detach an observer.
	 * 
	 * @access  public
	 * @param   object|string  $observer  Observer instance or observer class name
	 */

	public function detachObserver($observer)
	{
		foreach($this->_observers as $key => $_observer)
		{
			if($_observer instanceof $observer)
			{
				unset($this->_observers[$key]);
			}
		}
	}

	/**
	 * Detach a static observer.
	 * 
	 * @access  public
	 * @param   object|string  $observer  Observer instance or observer class name
	 */

	public static function detachStaticObserver($observer)
	{
		foreach(static::$_staticObservers as $key => $_observer)
		{
			if($_observer instanceof $observer)
			{
				unset(static::$_staticObservers[$key]);
			}
		}
	}

	/**
	 * Notify all observers.
	 * 
	 * @access  public
	 * @param   string  $event       Event
	 * @param   array   $parameters  (optional) Parameters
	 */

	protected function notifyObservers($event, array $parameters = array())
	{
		$event = String::underscored2camel(str_replace('.', '_', $event));

		foreach(array_merge($this->_observers, static::$_staticObservers) as $observer)
		{
			if(method_exists($observer, $event))
			{
				call_user_func_array(array($observer, $event), $parameters);
			}
		}
	}
}

/** -------------------- End of file -------------------- **/