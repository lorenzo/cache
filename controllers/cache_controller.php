<?php

class CacheController extends CacheAppController {
	public $uses = array();

	public function delete() {
		$this->autoRender = false;

		if ($this->params['[method]'] !== 'DELETE') {
			$this->cakeError('error404');
		}

		$configured = Cache::configured();
		if (!in_array($this->params['config'], $configured)) {
			$this->cakeError('error404');
		}

		if (empty($this->params['key'])) {
			Cache::clear(false, $this->params['config']);
		} else {
			Cache::delete($this->params['key'], $this->params['config']);
		}
	}
}
?>