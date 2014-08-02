<?php

class Solo {
	private $fp;
	private $token;

	public function __construct($token){
		$this->token = $token;
		$this->open();
	}

	public function __destruct(){
		$this->release();
		$this->close();
	}

	public function release(){
		flock($this->fp, LOCK_EX | LOCK_NB);
	}

	public function isLocked(){
		// do an exclusive and non-blocking lock
		return flock($this->fp, LOCK_EX | LOCK_NB);
	}

	public function isRunning(){
		return !$this->isLocked();
	}

	private function path(){
		return "/tmp/" . $this->token . ".lock";
	}

	private function open(){
		$this->fp = fopen($this->path(), "w+");
	}

	private function close(){
		fclose($this->fp);
	}
}
?>