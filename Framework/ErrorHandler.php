<?php

namespace Framework;

use Framework\View\ViewFactory;
use Framework\ErrorException;

/**
 * Class ErrorHandler handles uncaught PHP errors and exceptions
 *
 * @package Framework
 */
class ErrorHandler
{
    public $discardExistingOutput = true;

    /**
     * This method is responsible for registering all handlers.
     */
    public function register()
    {
        ini_set('display_errors', false);
        set_exception_handler([$this, 'handleException']);
        set_error_handler([$this, 'handleError']);
        register_shutdown_function([$this, 'handleFatalError']);
    }

    /**
     * Handler for uncaught PHP exceptions
     *
     * @param $exception \Exception
     */
    public function handleException($exception)
    {
        restore_error_handler();
        restore_exception_handler();

        if ($this->discardExistingOutput) {
            $this->clearOutput();
        }
        $this->renderException($exception);
    }

    /**
     * Handler for PHP runtime errors.
     *
     * @param $code
     * @param $message
     * @param $file
     * @param $line
     * @throws \ErrorException To be handled by handleException
     */
    public function handleError($code, $message, $file, $line)
    {
        if (error_reporting() & $code) {
            throw new ErrorException($message, $code, $code, $file, $line);
        }
    }

    public function handleFatalError()
    {
        $error = error_get_last();

        if (ErrorException::isFatalError($error)) {
            $exception = new ErrorException(
                $error['message'],
                $error['type'],
                $error['type'],
                $error['file'],
                $error['line']
            );

            if ($this->discardExistingOutput) {
                $this->clearOutput();
            }
            $this->renderException($exception);

            exit(1);
        }
    }

    /**
     * This method renders exception
     *
     * @param $exception \Exception
     */
    public function renderException($exception)
    {
        $factory = new ViewFactory('\\Framework\\Http\\HtmlView', []);
        $view    = $factory->create('exception', [App::$app->frameworkPath . '/views']);

        $view->className = get_class($exception);
        $view->message   = $exception->getMessage();
        $view->file      = $exception->getFile();
        $view->line      = $exception->getLine();
        $view->fileBody  = $exception->getFile();
        $view->trace     = $exception->getTraceAsString();

        echo $view;
    }

    /**
     * Removes all output echoed before calling this method.
     */
    public function clearOutput()
    {
        // the following manual level counting is to deal with zlib.output_compression set to On
        for ($level = ob_get_level(); $level > 0; --$level) {
            if (!@ob_end_clean()) {
                ob_clean();
            }
        }
    }
}