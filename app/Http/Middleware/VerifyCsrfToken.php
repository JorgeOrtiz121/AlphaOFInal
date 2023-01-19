<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [];

    public function __construct(Application $app, Encrypter $encrypter)
    {
        parent::__construct($app, $encrypter);

        $this->except[] = "{$this->app->environment('APP_URL')}/api/*";
    }

}
