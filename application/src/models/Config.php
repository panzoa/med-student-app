<?php
class Config {
    protected static $_settings = null;  // a field to hold a Zend_Config_Ini instance
  
    public static function getConfig() {
        if (is_null(self::$_settings)) {
            self::$_settings = new Zend_Config_Ini(
                APPLICATION_PATH . '/config/config.ini',
                APPLICATION_ENV
            );
        }

        return self::$_settings;
    }
}
