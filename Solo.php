<?php
/*

Copyright (c) 2014 Dan Hollenbeck

Permission is hereby granted, free of charge, to any person obtaining a copy of this
software and associated documentation files (the "Software"), to deal in the Software
without restriction, including without limitation the rights to use, copy, modify, merge,
publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to
whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies
or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE
FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/

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