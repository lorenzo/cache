<?php
/**
 * Tests of Cache Shell
 *
 * @link          http://github.com/lorenzo/cache
 * @package       cache
 * @subpackage    cache.tests.cases.vendors.shells
 * @since         v0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

// Importing classes
App::import('Shell', 'Shell', false);

if (!defined('DISABLE_AUTO_DISPATCH')) {
	define('DISABLE_AUTO_DISPATCH', true);
}

if (!class_exists('ShellDispatcher')) {
	ob_start();
	$argv = false;
	require CAKE . 'console' . DS . 'cake.php';
	ob_end_clean();
}

$pluginRoot = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
require_once $pluginRoot . DS . 'vendors' . DS . 'shells' . DS . 'cache.php';

Mock::generatePartial(
	'ShellDispatcher', 'CacheShellMockShellDispatcher',
	array('getInput', 'stdout', 'stderr', '_stop', '_initEnvironment')
);
Mock::generatePartial(
	'CacheShell', 'MockCacheShell',
	array('in', 'out', 'err', 'hr', '_stop')
);

class CacheShellTestCase extends CakeTestCase {
/**
 * Cache Shell
 *
 * @var object
 * @access public
 */
	var $Shell;

/**
 * setUp function
 *
 * @return void
 * @access public
 */
	function setUp() {
		$this->Dispatcher =& new CacheShellMockShellDispatcher();
		$this->Shell =& new MockCacheShell($this->Dispatcher);
		$this->Shell->Dispatch =& $this->Dispatcher;
	}

/**
 * testDelete function
 *
 * @return void
 * @access public
 */
	function testDelete() {
		// Without params
		$this->Shell->delete();
		$this->Shell->expectAt(0, 'out', array(new PatternExpectation('/Usage/')));

		// Invalid config
		$this->Shell->args = array('cache_test');
		$this->Shell->delete();
		$this->Shell->expectAt(0, 'err', array(new PatternExpectation('/not found/')));
		$this->Shell->expectCallCount('_stop', 2);

		Cache::config('cache_test', Cache::config('default'));

		// With config
		Cache::write('anything', array('a'), 'cache_test');
		Cache::write('anything2', array('b'), 'cache_test');
		$this->assertTrue(Cache::read('anything', 'cache_test') !== false);
		$this->Shell->args = array('cache_test');
		$this->Shell->delete();
		$this->assertFalse(Cache::read('anything', 'cache_test'));

		// With config
		Cache::write('anything', array('a'), 'cache_test');
		Cache::write('anything2', array('b'), 'cache_test');
		$this->assertTrue(Cache::read('anything', 'cache_test') !== false);
		$this->Shell->args = array('cache_test', 'anything');
		$this->Shell->delete();
		$this->assertFalse(Cache::read('anything', 'cache_test'));
		$this->assertTrue(Cache::read('anything2', 'cache_test') !== false);
	}
}
?>