<?php

namespace GeeksAreForLife\Config;

use KHerGe\JSON\JSON;

/**
 * Config
 *
 * The config object loads in the provided config files and provides an
 * interface to the config values
 */
class Config
{
    private $config;
    private $configFile;
    private $default;

    public function __construct()
    {
    }

    /**
     * Load the config and defaults file.
     *
     * Neither of these files have to exist!
     * The defaults file will never be edited
     *
     * File format is a single json object. 
     * keys are dot(.) seperated for easy nesting.
     * Keys can also have a "module" that nests them in a modules object
     * 
     * @param  string $configFile  The local config
     * @param  string $defaultFile Defaults
     * @return bool
     */
    public function load($configFile, $defaultFile)
    {
        $config = $this->readConfig($configFile);
        $default = $this->readConfig($defaultFile);

        $this->configFile = $configFile;

        if ($default !== false) {
            $this->default = $default;
        }

        if ($config !== false) {
            $this->config = $config;

            return true;
        } else {
            return false;
        }
    }

    /**
     * Saves the config file
     */
    public function save()
    {
        $this->saveFile($this->config, $this->configFile);
    }

    /**
     * Get a value
     * 
     * @param string  $key    dot seperated key
     * @param string|null $module the module that this key is part of
     * @return mixed the vakkue of the key
     */
    public function getValue($key, $module = false)
    {
        $keys = $this->getKeys($key, $module);

        $config = $this->config;
        $default = $this->default;

        foreach ($keys as $key) {
            if (isset($config[$key])) {
                $config = $config[$key];
            } else {
                $config = '';
            }

            if (isset($default[$key])) {
                $default = $default[$key];
            } else {
                $default = '';
            }
        }

        if ($config !== '') {
            return $config;
        } elseif ($default !== '') {
            return $default;
        } else {
            return false;
        }
    }

    /**
     * Set a value
     * 
     * @param string  $key    dot seperated key
     * @param mixed  $value  value
     * @param string|null $module the module that this key is part of
     */
    public function setValue($key, $value, $module = false)
    {
        $keys = $this->getKeys($key, $module);

        $config = &$this->config;
        foreach ($keys as $key) {
            if (!isset($config[$key])) {
                $config[$key] = [];
            }
            $config = &$config[$key];
        }
        //var_dump($config);
        if (empty($config) || !is_array($config)) {
            $config = $value;
        } else {
            $config[] = $value;
        }
    }

    private function getKeys($key, $module)
    {
        if ($module) {
            $key = 'modules.'.strtolower($module).'.'.$key;
        }

        if (strpos($key, '.') !== false) {
            $keys = explode('.', $key);
        } else {
            $keys = [$key];
        }

        return $keys;
    }

    private function readConfig($file)
    {
        if (file_exists($file)) {
            $configJson = file_get_contents($file);

            $json = new JSON();

            try {
                $json->lint($configJson);

                return $json->decode($configJson, true);
            } catch (\Exception $e) {
                return false;
            }
        } else {
            // create a blank file
            $this->saveFile([], $file);

            return [];
        }
    }

    private function saveFile($content, $file)
    {
        $json = new JSON();

        $json->encodeFile($content, $file, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
    }
}
