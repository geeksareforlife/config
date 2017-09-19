# Config
A simple configuration library

This library stores config in two files - a default file that you ship with your project with the configuration defaults, and a user-defined file that overrides and/or adds to these defaults. **Files will be created if they don't exist**


## Usage

First, create a config object and load the files:

```
$config = new Config();
$config->load('/path/to/userConfig.json', '/path/to/defaultConfig.json');
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