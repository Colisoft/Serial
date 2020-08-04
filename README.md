### Install via Composer

`composer require thesilvacesar/serial`

### Install manually

- [Download](https://github.com/colisoft/Serial/archive/master.zip) the project
- Import the **lib** folder for your project
- Import the files of class using `require_once 'lib/Serial/autoload.php';`

### Sample usage
An sample of use the class.

```php
require_once 'vendor/autoload.php';
//require_once 'lib/Serial/autoload.php';

$Serial = new Colisoft\Serial; // Instance the class with default settings
echo $Serial->create(); // Generate the serial
$Serial->get_settings(); // Get all setting's of the class
$Serial->validate('59GFF-RRMCB-JC55Y-FTU8Y-UUL8K'); // For validate the serial generated by $Serial->create();
```

### Advanced usage
An advanced example of use the class.

```php
require_once 'vendor/autoload.php';
//require_once 'lib/Serial/autoload.php';

$Serial = new Colisoft\Serial([
    'number_chunks' => 5,
    'chars_per_chunks' => 5,
    'separate_chunk_text' => '|',
    'serial_mask' => '##-###-###-##',
    'prefix_seriar' => 'EXAMPLE',
    'output_json' => true,
    'hash_prefix' => true,
    'includes_symbols' => true,
    'convert_to_hex' => true,
    'lower_case' => true,
]); // Instance the class with all settings customizable

echo $Serial->create(); // Generate the serial
```

### All settings
All these of settings with can be used.

|  Variable | Type | Default | Description |
| :------------: | :------------: | :------------: | :------------: |
| number_chunks | `int` | *5* | Number of total chunks |
| chars_per_chunks | `int` | *5* | Number of total letters per chunk |
| separate_chunk_text | `string` | *-* | Separate chunk by text |
| prefix_serial | `string` | *NULL* | Prefix of the serial |
| hash_prefix | `boolean` | *false* | Hash the prefix of serial |
| output_json | `boolean` | *false* | Convert output for json |
| lower_case | `boolean` | *false* | Convert to lower case |
| convert_to_hex | `boolean` | *false* | Convert to hexadecimal |
| includes_symbols | `boolean` | *false* | Includes symbols on serial |
| serial_mask | `string` | *NULL* | Defines the format of serial |
