<?php

	class Templater {
		const SIMPLE_ARRAY = 1;
		const ASSOCIATIVE_ARRAY = 2;

		private $assign = [];
		private $_error = [];
		private $templateData = null;
		private $charLimiter;

		/**
		 * Templater constructor.
		 *
		 * @param string $charLimiter
		 */
		public function __construct ($charLimiter = '{|}') {
			$this->charLimiter = $charLimiter;
		}

		public function loadFromStr ($str) {
			$this->templateData = $str;
		}

		public function loadFromFile ($path) {
			if(file_exists($path) && is_file($path)) {
				$this->templateData = file_get_contents($path);
			}
		}

		public function assign ($in, $to = [], $type = self::SIMPLE_ARRAY) {
			if ( !is_array( $in ) or count( $in ) == 0 ) {
				if(is_string($in) and is_string($to)) {
					$this->fill($in, $to);
				} else {
					$this->_error[] = "Error, first argument don't is array or is empty array.";
				}
			} else if ( !is_array( $to ) ) {
				if ($type === self::ASSOCIATIVE_ARRAY) {
					$this->assign = array_merge($this->assign, $in);
				} else {
					$this->_error[] = "Error, second argument don't is array.";
				}
			} else {
				if ( count( $in ) == count( $to ) ) {
					foreach ( $in as $key => $value ) {
						$this->fill( $value, $to[$key] );
					}
				} else {
					$this->_error[] = "Quantity elements in array 'Replace' and 'Replacement' is not equals.";
				}
			}

			$this->showErrors();
		}

		public function toString () {
			$temp = [];
			$limiter = explode("|", $this->charLimiter);

			$this->callFunction();

			foreach ($this->assign as $key => $value) {
				$temp[sprintf("%s%s%s", $limiter[0], strtoupper($key), $limiter[1])] = $value;
			}

			$this->showErrors();
			return str_replace(array_keys($temp), array_values($temp), $this->templateData);
		}


		private function callFunction () {
			preg_match_all('/(@)?{{(\w+\(.*?\))/i', $this->templateData, $out);
			
			$n = count($out[0]);
			for($i = 0; $i < $n; $i++) {
				if(substr($out[0][$i], 0, 1) != "@") {
					$result = eval("return " . $out[2][$i] . ";");

					$this->templateData = str_replace($out[0][$i] . "}}", $result, $this->templateData);
				}
			}

			$this->templateData = str_replace("@{{", "{{", $this->templateData);
		}

		private function fill ($in, $to) {
			$this->assign[$in] = $to;
		}

		private function showErrors () {
			if ( count( $this->_error ) ) {
				throw new Exception( array_shift( $this->_error ) );
			}
		}
	}