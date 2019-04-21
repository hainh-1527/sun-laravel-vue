<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Throwable;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use wataridori\ChatworkSDK\ChatworkRoom;
use wataridori\ChatworkSDK\ChatworkSDK;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];
    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);

        $this->handleNotification($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }

    /**
     * Handle notification
     *
     * @param \Exception $exception
     * @return void
     */
    public function handleNotification(Exception $exception)
    {
        if ($this->shouldntReport($exception)) {
            return;
        }

        $message = $exception->getMessage();
        $e = $this->convertException($exception);
        $auth = $this->getAuth();
        $url = $this->getUrl();
        $context = $e + $auth + $url;

        // Send exception to slack
        $this->toSlack($message, $context);

        // Send exception to chatwork
        $this->toChatwork($message, $context);
    }

    /**
     * Send exception to slack.
     *
     * @param $message
     * @param $context
     * @return void
     */
    public function toSlack($message, $context)
    {
        if (config('logging.channels.slack.url')) {
            Log::channel('slack')->critical($message, $context);
        }
    }

    /**
     * Send exception to chatwork.
     *
     * @param $message
     * @param $context
     * @return void
     */
    public function toChatwork($message, $context)
    {
        $token = config('services.chatwork.token');
        $room = config('services.chatwork.room');

        if ($token && $room) {
            ChatworkSDK::setApiKey($token);
            $cr = new ChatworkRoom($room);

            $cr->sendMessageToAll($cr->buildInfo($this->buildInfo($context), $message));
        }
    }

    /**
     * Build info chatwork
     *
     * @param $context
     * @return string
     */
    public function buildInfo($context)
    {
        $info = '';

        foreach ($context as $key => $value) {
            $key = ucwords($key);
            $info .= "[info]{$key}: {$value}[/info]";
        }

        return $info;
    }

    /**
     * Convert Exception.
     *
     * @param \Exception $exception
     * @return array
     */
    public function convertException(Exception $exception)
    {
        try {
            return array_filter([
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);
        } catch (Throwable $e) {
            return [];
        }
    }

    /**
     * Get url.
     *
     * @return array
     */
    public function getUrl()
    {
        try {
            return array_filter([
                'route url' => URL::current(),
            ]);
        } catch (Throwable $e) {
            return [];
        }
    }

    /**
     * Get auth.
     *
     * @return array
     */
    public function getAuth()
    {
        try {
            return array_filter([
                'user_id' => Auth::id(),
                'email' => Auth::user() ? Auth::user()->email : null,
                'user_type' => Auth::user() ? Auth::user()->user_type : null,
            ]);
        } catch (Throwable $e) {
            return [];
        }
    }
}
