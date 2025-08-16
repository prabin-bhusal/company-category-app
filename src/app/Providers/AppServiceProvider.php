<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\CompanyRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(CompanyRepositoryInterface::class, CompanyRepository::class);

        $this->exceptionHandling();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    protected function exceptionHandling(): void
    {
        $this->app->bind(ExceptionHandler::class, function ($app) {
            return new class($app) extends \Illuminate\Foundation\Exceptions\Handler {

                public function render($request, \Throwable $exception): \Illuminate\Http\JsonResponse
                {
                    if ($exception instanceof ModelNotFoundException) {
                        return response()->json([
                            'message' => 'Requested resource not found',
                        ], 404);
                    }

                    if ($exception instanceof ValidationException) {
                        $response = response()->json([
                            'message' => 'Validation failed',
                            'errors' => $exception->errors()
                        ], 422);
                    }

                    if ($exception instanceof AuthenticationException) {
                        Log::error($exception->getMessage());
                        return response()->json(['message' => 'Unauthenticated'], 401);
                    }

                    if ($exception instanceof AuthorizationException) {
                        Log::error($exception->getMessage());
                        return response()->json(['message' => 'Forbidden'], 403);
                    }

                    if ($exception instanceof MethodNotAllowedHttpException) {
                        Log::error($exception->getMessage());
                        return response()->json(['message' => 'Method not allowed'], 405);
                    }

                    if ($exception instanceof QueryException) {
                        Log::error($exception->getMessage());
                        return response()->json(['message' => 'Database error', "error" => $exception->getMessage()], 500);
                    }

                    if ($exception instanceof NotFoundHttpException) {
                        Log::error($exception->getMessage());
                        return response()->json(['message' => 'Endpoint not found'], 404);
                    }

                     return response()->json([
                         'message' => 'Server Error',
                         'error' => $exception->getMessage()
                     ], 500);

                }
            };
        });
    }

}
