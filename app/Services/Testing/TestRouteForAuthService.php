<?php

namespace App\Services\Testing;

use Illuminate\Support\Str;
use App\Enum\TestRouteMethods;
use Exception;
use Illuminate\Testing\TestResponse;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TestRouteForAuthService
{

    public function __construct(protected User|null $user = null, protected TestRouteMethods $httpMethod = TestRouteMethods::GET)
    {
    }

    protected function resolveMethod(
        string $route,
        TestCase $testCase,
        User|null $user = null,
        bool $useApiRoutes = false,
        array $data = [],
        array $headers = []
    )
    {
        if (isset($user))
            $testCase = $testCase->actingAs($user);
        if ($useApiRoutes)
        {

            if ($this->httpMethod === TestRouteMethods::GET)
            {
                return $testCase->getJson($route, $headers);
            }
            else if ($this->httpMethod === TestRouteMethods::POST)
            {
                return $testCase->postJson($route, $data, $headers);
            }
            else if ($this->httpMethod === TestRouteMethods::PUT)
            {
                return $testCase->putJson($route, $data, $headers);
            }
            else if ($this->httpMethod === TestRouteMethods::PATCH)
            {
                return $testCase->patchJson($route, $data, $headers);
            }
            else if ($this->httpMethod === TestRouteMethods::DELETE)
            {
                return $testCase->deleteJson($route, $data, $headers);
            }
        }
        else
        {
            if ($this->httpMethod === TestRouteMethods::GET)
            {
                return $testCase->get($route, $headers);
            }
            else if ($this->httpMethod === TestRouteMethods::POST)
            {
                return $testCase->post($route, $data, $headers);
            }
            else if ($this->httpMethod === TestRouteMethods::PUT)
            {
                return $testCase->put($route, $data, $headers);
            }
            else if ($this->httpMethod === TestRouteMethods::PATCH)
            {
                return $testCase->patch($route, $data, $headers);
            }
            else if ($this->httpMethod === TestRouteMethods::DELETE)
            {
                return $testCase->delete($route, $data, $headers);
            }
        }
    }

    private function assertTestCaseFailed(TestCase $testCase, $response)
    {
        $testCase->assertTrue(
            $response->baseResponse->getStatusCode() === 401 ||
                $response->baseResponse->getStatusCode() === 404 ||
                $response->baseResponse->getStatusCode() === 403 ||
                $response->baseResponse->getStatusCode() === 422
        );
    }

    /**
     * @param string $route API action path
     * @param TestRouteMethods $httpMethod Route http method
     * @param array $data Data to pass to tested route
     * @param Illuminate\Foundation\Testing\TestCase Instance of the test case in which this function is used
     */
    public function testAPIJWTAuth(
        string $route,
        TestCase $testCase,
        bool $mustFail = false,
        array $data = [],
        array $headers = []
    ): TestResponse|null
    {
        if (!isset($this->user))
        {
            throw new Exception("Api user is not specified");
        }
        //Choose assert function method

        $response = $this->resolveMethod($route, $testCase, null, true, $data, $headers);

        $this->assertTestCaseFailed($testCase, $response);

        $token = auth('api')->attempt(['app_id' => $this->user->app_id, 'app_secret' => $this->user->app_secret]);

        $response = $this->resolveMethod($route, $testCase, $this->user, true, $data, array_merge($headers, ['Authorization' => "Bearer $token"]));

        if($mustFail)
            $this->assertTestCaseFailed($testCase, $response);
        else
            $response->assertOk();

        return $response;
    }

    /**
     * @param string $route API action path
     * @param TestRouteMethods $httpMethod Route http method
     * @param array $data Data to pass to tested route
     * @param Illuminate\Foundation\Testing\TestCase Instance of the test case in which this function is used
     */
    public function testAPIBasicAuth(
        string $route,
        TestCase $testCase,
        bool $mustFail = false,
        array $data = [],
        array $headers = []
    ): TestResponse|null
    {
        if (!isset($this->user))
        {
            throw new Exception("Api user is not specified");
        }
        //Choose assert function method

        $response = $this->resolveMethod($route, $testCase, null, true, $data, array_merge($headers, [
            'API-Secret' => '12345',
            'API-Key' => '12345'
        ]));

        $this->assertTestCaseFailed($testCase, $response);

        $response = $this->resolveMethod($route, $testCase, $this->user, true, $data, array_merge($headers, [
            'API-Secret' => $this->user->api_secret,
            'API-Key' => $this->user->api_key
        ]));

        if($mustFail)
            $this->assertTestCaseFailed($testCase, $response);
        else
            $response->assertOk();

        return $response;
    }


    /**
     * @param string $route API action path
     * @param TestRouteMethods $httpMethod Route http method
     * @param array $data Data to pass to tested route
     * @param Illuminate\Foundation\Testing\TestCase Instance of the test case in which this function is used
     */
    public function testWeb(
        string $route,
        TestCase $testCase,
        bool $mustFail = false,
        array $data = [],
        array $headers = []
    ): TestResponse
    {
        Auth::guard('web')->logout();

       if (!isset($this->apiUser))
        {
            throw new Exception("Api user is not specified");
        }
        //Choose assert function method

        $response = $this->resolveMethod($route, $testCase, null, true, $data, $headers);

        $this->assertTestCaseFailed($testCase, $response);

        $response = $this->resolveMethod($route, $testCase, $this->user, true, $data, $headers);

        if($mustFail)
            $this->assertTestCaseFailed($testCase, $response);
        else
            $response->assertOk();

        return $response;
    }
}
