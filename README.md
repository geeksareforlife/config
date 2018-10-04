# Config
A simple configuration library

This library stores config in two files - a normal config file and a defaults file.

When retrieving a value from the config, the normal config is checked first and if nothing is found the value from the defaults file is used.

## Getting Started

Install [Composer](https://getcomposer.org/download/) and run the following command to get the latest version:

```
composer require "geeksareforlife/config:^0.1"
```

## Basic Usage

First, create a config object and load the files (**Files will be created if they don't exist**):

```
$config = new Config();
$config->load('/path/to/config.json', '/path/to/defaultConfig.json');
```

Then you can get or set values into the config object.  Keys are dot-seperated.

```
$value = $config->getValue('key.name');
$config->setValue('key.name', $value);
```

When you have finished, you need to call the save function to commit the config to disk.

```
$config->save();
```

## More Details

Values can be stored in two config files. Each file is a single JSON object.

Keys are in dot-notation, and an optional "module" can be used for each key. This allows you to have identical keys for different areas of your system.

### Loading your config files

Once you have created the config object, you need to load the two config files. The config file needs to be writable, and both files will be created if they don't exist.

### Getting a value

The `getValue` function returns the value of a given key:

```
getValue(string $key, [string $module]);
```

If the key is found in the config file, the value from there is returned. If not, the default file is checked and a value returned from there if available.

If the key is not found in either file, the function will return `false`

### Setting a value

the `setValue` function behaves very similarly to the `getValue`

```
setValue(string $key, mixed $value, [string $module]);
```

The value is only ever stored in the config - the defaults are never touched.

At this point, the file itself it not saved, the value is only stored in-memory.

### Saving the config

The `save` function saves the in-memory config to the config file.
