<?php

namespace Framework\Controller;

use Framework\Exceptions\HttpException;

class Controller
{
    public $defaultAction = 'index';

    /**
     * This method is executed before action.
     * You can overwrite it.
     */
    public function beforeAction()
    {

    }

    /**
     * This method is executed after action.
     * You can overwrite it.
     */
    public function afterAction()
    {

    }

    /**
     * Fires up action with [before/after]Action methods.
     *
     * @param string $action Action to execute
     * @param \Framework\Http\HttpRequest $request
     * @throws HttpException
     */
    public function run($action, $request)
    {
        if (!method_exists($this, $action)) {
            throw new HttpException("Method {$action} doesn't exist in " . get_class($this));
        }

        $this->beforeAction();
        $this->{$action}($request);
        $this->afterAction();
    }
} 