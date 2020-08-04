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

	abstract class Settings {

		/** @var string Return the version of class */
		public $version = '1.0';

		/** @var integer Number of total chunks. Default is 5. */
		public $number_chunks = 5;

		/** @var string Defines the format of serial. Default/ is NULL */
		public $serial_mask = null;

		/** @var bool Convert to lower case. Default is FALSE. */
		public $lower_case = false;

		/** @var bool Hash the prefix of serial. Default is FALSE. */
		public $hash_prefix = false;

		/** @var bool Convert output for json. Default is FALSE. */
		public $output_json = false;

		/** @var string Prefix of the serial. Default is NULL. */
		public $prefix_serial = null;

		/** @var integer Number of total letters per chunk. Default is 5. */
		public $chars_per_chunks = 5;

		/** @var bool Convert to hexadecimal. Default is FALSE. */
		public $convert_to_hex = false;

		/** @var bool Includes symbols on serial. Default is FALSE. */
		public $includes_symbols = false;

		/** @var string Separate chunk by text. Default is '-' (without single quote). */
		public $separate_chunk_text = '-';

		/** @var array Available characters. */
		protected $available_characters = [];

		/** @var integer Total available characters. This is for calculation only. */
		protected $total_available_characters = 0;

		/** @return array Get setting's of the class. */
		public function get_settings() {
			return json_encode([
				'lower_case'			=>	$this->lower_case,
				'serial_mask'			=>	$this->serial_mask,
				'output_json'			=>	$this->output_json,
				'hash_prefix'			=>	$this->hash_prefix,
				'generate_log'			=>	$this->generate_log,
				'prefix_serial'			=>	$this->prefix_serial,
				'number_chunks'			=>	$this->number_chunks,
				'convert_to_hex'		=>	$this->convert_to_hex,
				'includes_symbols'		=>	$this->includes_symbols,
				'chars_per_chunks'		=>	$this->chars_per_chunks,
				'separate_chunk_text'	=>	$this->separate_chunk_text,
			]);
		}

	}