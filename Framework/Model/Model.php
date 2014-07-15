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


abstract class Model
{
    protected $dao = 'Framework\\Model\\Dao';

    public abstract function __construct($config);

    /**
     * @return Dao
     */
    public function create()
    {
        $dao = $this->dao;

        return new $dao($this);
    }

    public abstract function insert(Dao $dao);

    public abstract function update(Dao $dao);

    public abstract function delete($id);
} 