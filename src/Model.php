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

use App\Config;

/**
 * Class Model
 *
 * @package Rudra
 */
class Model
{

    /**
     * @var
     */
    protected $container;

    /**
     * @var
     */
    protected $data;


    /**
     * Model constructor.
     *
     * @param \Rudra\IContainer $container
     */
    public function __construct(IContainer $container)
    {
        $this->container = $container;
//        Container::$app->get('debugbar')['time']->stopMeasure('Controller');
//        Container::$app->get('debugbar')['time']->startMeasure('Model', __CLASS__);
    }

    /**
     * @return mixed
     */
    public function container()
    {
        return $this->container;
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
        $this->container()->get('redirect')->run($value);
    }

    /**
     * @param $key
     *
     * @return string
     */
    public function fileUpload($key)
    {
        if ($this->container()->isUploaded($key)) {
            $uploadedFile = '/uploaded/' . substr(md5(microtime()), 0, 5) . $this->container()->getUpload($key, 'name');
            $uploadPath   = Config::PUBLIC_PATH . $uploadedFile;
            move_uploaded_file($this->container()->getUpload($key, 'tmp_name'), $uploadPath);

            return APP_URL . $uploadedFile;
        }

        return $this->container()->getPost($key);
    }
}
