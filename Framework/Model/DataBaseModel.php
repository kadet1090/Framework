<?php
/**
 * Copyright (C) 2014, Some right reserved.
 * @author  Kacper "Kadet" Donat <kadet1090@gmail.com>
 * @license http://creativecommons.org/licenses/by-sa/4.0/legalcode CC BY-SA
 *
 * Contact with author:
 * Xmpp: kadet@jid.pl
 * E-mail: kadet1090@gmail.com
 *
 * From Kadet with love.
 */

namespace Framework\Model;


class DataBaseModel
{
    /**
     * @var \PDO[]
     */
    protected static $connections = [];

    /**
     * @var \PDO
     */
    protected $connection;

    public function init($config, $id = 'database')
    {
        $this->connection = self::getConnection($config['model']['database']);
    }

    protected static function getConnection($config)
    {
        $cid = md5(serialize($config));

        if (!isset(self::$connections[$cid])) {
            if (!isset($config['options'])) {
                $config['options'] = [];
            }

            $options = [];
            foreach ($config['options'] as $key => $value) {
                $options[constant("\\PDO::{$key}")] = $value;
            }

            self::$connections[$cid] = new \PDO(
                $config['dsn'],
                isset($config['username']) ? $config['username'] : null,
                isset($config['password']) ? $config['password'] : null,
                $options
            );

            if (!isset($config['attributes'])) {
                $config['attributes'] = [];
            }

            // this setting we want always, but user can still override this if he want
            self::$connections[$cid]->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $attributes = [];
            foreach ($config['attributes'] as $key => $value) {
                self::$connections[$cid]->setAttribute(constant("\\PDO::{$key}"), constant("\\PDO::{$value}"));
            }
        }

        return self::$connections[$cid];
    }
} 