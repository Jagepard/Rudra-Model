<?php

declare(strict_types = 1);

namespace Rudra;


use App\Config;


/**
 * Class Model
 *
 * @package Rudra
 */
class Model
{

    use ContainerTrait, DataTrait;

    /**
     * @var
     */
    protected $container;

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
