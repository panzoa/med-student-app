<?php
class Db {
    protected static $_db = null;

    public static function getDb() {
        $config = Config::getConfig();

        if (is_null(self::$_db)) {
            self::$_db = new Zend_Db_Adapter_Pdo_Mysql(array(
                'host'     => $config->db->host,
                'username' => $config->db->login,
                'password' => $config->db->password,
                'dbname'   => $config->db->schema
            ));

            self::$_db->setFetchMode(Zend_Db::FETCH_OBJ);
        }

        return self::$_db;
    }
}
