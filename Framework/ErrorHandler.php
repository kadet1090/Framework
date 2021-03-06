<?php

namespace Framework;

/**
 * Class ErrorHandler handles uncaught PHP errors and exceptions
 *
 * @package Framework
 */
class ErrorHandler
{
    /**
     * @var bool $discardExistingOutput determines if output before error display should be erased
     */
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
     * Handles uncaught PHP exceptions
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
     * Handles PHP runtime errors.
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

    /**
     * Handles fatal errors
     */
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
     * Renders exception
     *
     * @param $exception \Exception
     */
    public function renderException($exception)
    {
        $view = App::$app->viewFactory->create('exception', [App::$app->frameworkPath . '/views']);

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