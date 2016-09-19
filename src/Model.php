<?php

/**
 * Date: 17.08.2016
 * Time: 14:50
 * 
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2016, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

namespace Rudra;

/**
 * Class Model
 * @package Rudra
 */
class Model
{
    /**
     * @var
     * Для экземпляра класса валидации
     */
    protected $v;

    /**
     * @var
     */
    protected $di;

    /**
     * Model constructor.
     * @param IContainer $di
     */
    public function __construct(IContainer $di)
    {
        $this->di = $di;
    }

    /**
     * @return mixed
     */
    public function getDi()
    {
        return $this->di;
    }
}
