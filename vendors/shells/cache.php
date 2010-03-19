<?php
/**
 * Shell for Cache
 *
 * @link          http://github.com/lorenzo/cache
 * @package       cache
 * @subpackage    cache.vendors.shells
 * @since         v0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * CacheShell class
 */
class CacheShell extends Shell {
/**
 * Startup script
 *
 * @return void
 * @access public
 */
	function startup() {
		parent::startup();
	}

/**
 * Delete cache
 *
 * @return void
 * @access protected
 */
	function delete() {
		if (empty($this->args) || count($this->args) > 2) {
			$this->help();
			return $this->_stop();
		}
		$configured = Cache::configured();
		if (!in_array($this->args[0], $configured)) {
			$this->err(sprintf(__d('cache', 'Configuration "%s" not found.', true), $this->args[0]));
			return $this->_stop();
		}
		if (isset($this->args[1])) {
			Cache::delete($this->args[1], $this->args[0]);
		} else {
			Cache::clear(false, $this->args[0]);
		}
	}

/**
 * Main
 *
 * @return void
 * @access public
 */
	function main() {
		$this->help();
	}

/**
 * Help
 *
 * @return void
 * @access public
 */
	function help() {
		$this->out(__d('cache', 'Usage: cake cache delete <config> [<key>]', true));
		$this->hr();
	}

}

?>