<?php

	/**
	 * 
	 * Serial
	 * This class is used for generate serial numbers for softwares paid
	 * 
	 * @version 1.0
	 * @author Colisoft
	 * @author César Silva <cesar98silva@outlook.com>
	 * @link https://github.com/colisoft/Serial
	 * 
	*/
	
	namespace Colisoft;
	require_once 'class.settings.php';

	class Serial extends Settings {

		/**
		 * Count slices of $serial
		 * @var string $serial
		 * @return boolean Return if total of slices is equal to $this->chars_per_chunks
		*/
		protected function count_slice($serial) {
			foreach (explode($this->separate_chunk_text, $serial) as $slice) {
				if (strlen($slice) != $this->chars_per_chunks) { return false; }
			}
			
			return true;
		}

		/**
		 * Count chunks of $serial
		 * @var string $serial
		 * @return boolean Return if total of chunks is equal to $this->number_chunks
		*/
		protected function count_chunks($serial) {
			$count_chunks	=	count(
				explode($this->separate_chunk_text, $serial)
			);
			
			if ($count_chunks == $this->number_chunks) {
				return true;
			} else {
				return false;
			}
		}

		/** 
		 * Class constructor. 
		 * @var array $settings
		*/
		public function __construct($settings = []) {
			// Hide all errors
		   	error_reporting(0);

			// Custom setting's
			if ($settings['hash_prefix'] == true) { $this->hash_prefix = true; }
			if ($settings['lower_case'] == true) { $this->lower_case = $settings['lower_case']; }
			if ($settings['serial_mask'] != null) { $this->serial_mask = $settings['serial_mask']; }
			if ($settings['output_json'] == true) { $this->output_json = $settings['output_json']; }
			if ($settings['generate_log'] == true) { $this->generate_log = $settings['generate_log']; }
			if ($settings['prefix_serial'] != null) { $this->prefix_serial = $settings['prefix_serial']; }
			if ($settings['number_chunks'] != null) { $this->number_chunks = $settings['number_chunks']; }
			if ($settings['convert_to_hex'] == true) { $this->convert_to_hex = $settings['convert_to_hex']; }
			if ($settings['includes_symbols'] == true) { $this->includes_symbols = $settings['includes_symbols']; }
			if ($settings['chars_per_chunks'] != null) { $this->chars_per_chunks = $settings['chars_per_chunks']; }
			if ($settings['separate_chunk_text'] != null) { $this->separate_chunk_text = $settings['separate_chunk_text']; }

			// Define the default some variables
			$this->available_characters = $this->available_characters();
			$this->total_available_characters = count($this->available_characters);
		}

		/**
		 * Small sample convert crc32 to character map
		 * @var string $data
		 * @return string Return string with the crc32 hash
		 * @source http://www.php.net/manual/en/function.crc32.php#105703
		*/
		protected function hash_prefix($data) {
			$string = '';
			$hash = bcadd(sprintf('%u', crc32($data)), 0x100000000);
			$map = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			
			do {
				$string = $map[ bcmod($hash, 62) ] . $string;
				$hash = bcdiv($hash, 62);
			}

			while ($hash >= 1);
			return str_replace('-', '', strtoupper($string));
		}

		/**
		 * Get all available characters.
		 * @return array Return array of available characters.
		*/
		protected function available_characters() {
			if ($this->includes_symbols == true) {
				// number come first. any symbols and after letters.
				return [
					'', '#', '$', '&', '%', '_', '+', '~', '?', '!', // symbols
					'0', '1', '2', '3', '4', '5', '6', '7', '8', '9', // numbers
					'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', // letters
				];
			}

			// number come first. any letters.
			return [
				'0', '1', '2', '3', '4', '5', '6', '7', '8', '9', // numbers
				'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', // letters
			];
		}

		/** 
		 * Serial mask function 
		 * @var string $string
		 * @var string $mask
		 * @return string Return string with the serial maskared
		 * @link http://blog.clares.com.br/php-mascara-cnpj-cpf-data-e-qualquer-outra-coisa/ (Portuguese)
		*/
		protected function serial_mask($string, $mask) {
			$k = 0;
			$maskared = '';

			for ($i = 0; $i <= strlen($mask) - 1; $i++) {
				if (in_array($mask[$i], ['#', '*', '?', 'X', 'x', 'Y', 'y', 'Z', 'z'])) {
					if (isset($string[$k])){ $maskared .= $string[$k++]; }
				} else {
					if(isset($mask[$i])){ $maskared .= $mask[$i]; }
				}
			}

			return $maskared;
		}

		/** Reset all properties and begins again. */
		protected function reset() {
			$this->number_chunks = 5;
			$this->chars_per_chunks = 5;
			$this->separate_chunk_text = '-';
			$this->available_characters = $this->available_characters();
			$this->total_available_characters = count($this->available_characters);
		}

		/**
		 * Validate properties that it contain valid format and value.
		 * If something invalid, it will automatically call to reset() method.
		*/
		protected function validateProperties() {
			if (!is_numeric($this->total_available_characters)) { $this->reset(); }
			if (!is_array($this->available_characters) || empty($this->available_characters)) { $this->reset(); }
			if (!is_int($this->number_chunks) || !is_int($this->chars_per_chunks) || $this->number_chunks < 1 || $this->chars_per_chunks < 1) { $this->reset(); }
		}

		/**
		 * Convert string to hexadecimal
		 * @var string $string
		 * @return string Return string the serial converted to hexadecimal
		 * @link https://stackoverflow.com/questions/14674834/php-convert-string-to-hex-and-hex-to-string/18506801#18506801
		*/
		public function convert_to_hex($string) {
			$hex = '';

			for ($i = 0; $i < strlen($string); $i++){
				$ord = ord($string[$i]);
				$hex .= substr('0' . dechex($ord), -2);
			}
			
			return strtoupper($hex);
		}

		/**
		 * Validate an serial key
		 * @var string $serial
		 * @return boolean Return if serial is valid
		*/
		public function validate($serial) {
			if (strpos($serial, $this->separate_chunk_text)) {
				if ($this->count_chunks($serial) == true) {
					if ($this->includes_symbols == true) {
						if (preg_match('/[^a-zA-Z\d]/', $serial) == true && $this->count_slice($serial) == true) {
							return true;
						} else {
							return false;
						}
					} else {
						if ($this->count_slice($serial) == true) {
							return true;
						} else {
							return false;
						}
					}
				} else {
					return false;
				}
			} else {
				return false;
			}
		}

		/**
		 * Generate the serial key.
		 * @return string Return generated serial key.
		*/
		public function create() {
			$output = '';
			$this->validateProperties();

			for ($chunk = 1; $chunk <= $this->number_chunks; $chunk++) {
				for ($letter = 1; $letter <= $this->chars_per_chunks; $letter++) {
					if (function_exists('mt_rand')) {
						$output .= $this->available_characters[
							mt_rand(
								0, ($this->total_available_characters - 1)
							)
						];
					} else {
						$output .= $this->available_characters[
							array_rand($this->available_characters)
						];
					}
				}

				unset($letter);
				if ($chunk < $this->number_chunks) { $output .= $this->separate_chunk_text; }
			}
			
			if ($this->prefix_serial != null && $this->hash_prefix == true) { $this->prefix_serial = $this->hash_prefix($this->prefix_serial); }
			if ($this->prefix_serial != null) { $output = substr($this->prefix_serial, 0, $this->chars_per_chunks) . $this->separate_chunk_text . $output; }
			if ($this->serial_mask != null) { $output = $this->serial_mask(str_replace(['-', ' ', $this->separate_chunk_text], '', $output), $this->serial_mask); }
			if ($this->convert_to_hex == true) { $output = $this->convert_to_hex($output); }
			if ($this->lower_case == true) { $output = strtolower($output); }

			unset($chunk);
			return ($this->output_json) ? json_encode([
				'serial' 	=>	$output,
				'settings'	=>	json_decode($this->get_settings())
			]) : $output;
		}

	}
