<?php

class Pd_ServiceMap {
	/**
	 * @var string
	 */
	protected $fromContainer;
	static protected $meta = array();
	/**
	 * @var string  $serviceDefinition
	 * Neoseeker related projects uses BaseServiceDefinition from neolib
	 * For all other projects, please point this to 'Pd_BaseServiceDefinition'
	 */
	static protected $serviceDefinition = '\neolib\Config\BaseServiceDefinition';

	/**
	 * Set the service definition class - you can only run this once unless you are in a unit test
	 * @static
	 * @param string $serviceDefinition
	 */
	static public function set_ServiceDefinition($serviceDefinition) {
		if (!isset(self::$meta['define_ServiceDefinition']) || GlobalDefines::is_in_unit_test()) {
			if (GlobalDefines::is_in_unit_test() && self::get_ServiceDefinition()) {
				self::$meta['orig_ServiceDefinition'] = self::get_ServiceDefinition();
			}
			self::$serviceDefinition = $serviceDefinition;
			self::$meta['define_ServiceDefinition'] = true;
		}
	}

	static protected function get_ServiceDefinition() {
		return self::$serviceDefinition;
	}

	/**
	 * Restore service definition in a unit test - ran in tearDownAfterClass()
	 * @static
	 */
	static public function restore_ServiceDefinition() {
		if (GlobalDefines::is_in_unit_test() && isset(self::$meta['define_ServiceDefinition']) && isset(self::$meta['orig_ServiceDefinition'])) {
			self::$serviceDefinition = self::$meta['orig_ServiceDefinition'];
			unset(self::$meta['orig_ServiceDefinition']);
		}
	}

	/**
	 * @static
	 * @param string $service           service name, ie. neoapi, db
	 * @param string $fromContainer     'main' is the default name used in Pd_Container - do not change
	 * @return mixed
	 */
	static public function get($service, $fromContainer = 'main') {
		$serviceMap = new self($fromContainer);
		$serviceObj = $serviceMap->getPdContainer()->get($service, true);
		if (!is_null($serviceObj)) {
			return $serviceObj;
		}
		return $serviceMap->buildService($service);
	}

	protected function __construct($fromContainer) {
		$this->fromContainer = $fromContainer;
	}

	public function getPdContainer() {
		return Pd_Container::get($this->fromContainer)->dependencies();
	}

	protected function buildService($service) {
		$serviceDefinition = self::get_ServiceDefinition();
		$serviceObj = $serviceDefinition::create($service);
		if (!is_null($serviceObj)) {
			$this->getPdContainer()->set($service, $serviceObj);
		}
		return $serviceObj;
	}
}

?>