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

use App\Config\Config;

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
     * @var
     */
    protected $data;

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

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @param $key
     * @param $data
     */
    public function setDataItem($key, $data)
    {
        $this->data[$key] = $data;
    }

    /**
     * @param $value
     */
    public function redirect($value)
    {
        $this->getDi()->get('redirect')->run($value);
    }

    /**
     * @param $key
     */
    public function fileUpload($key)
    {
        if ($this->getDi()->isUploaded($key)) {
            $uploadedFile = '/uploaded/' . substr(md5(microtime()), 0, 5) . $this->getDi()->getUpload($key, 'name');
            $uploadPath   = Config::PUBLIC_PATH . $uploadedFile;
            $this->setDataItem($key, APP_URL . $uploadedFile);
            move_uploaded_file($this->getDi()->getUpload($key, 'tmp_name'), $uploadPath);
        } else {
            $this->setDataItem($key, $this->getDi()->getPost($key));
        }
    }

}
