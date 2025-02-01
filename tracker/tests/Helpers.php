<?php

use App\Clients\StockStatus;
use Facades\App\Clients\ClientFactory;
use Cknow\Money\Money;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Inertia\Testing\AssertableInertia;
use Illuminate\Testing\TestResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Clients\Client;
use App\Models\Stock;

function mockClientRequest($available = true, $price = '1000', $pattern = '/(.*)/')
{
    ClientFactory::shouldReceive('make->checkAvailability')
        ->andReturn(new StockStatus($available, Money::parse($price)));

    ClientFactory::shouldReceive('make->getProductURLPatterns')
        ->andReturns([
           $pattern
        ]);
    ClientFactory::shouldReceive('make->endpoint')
        ->andReturn('http://example.com');

}

function mockClient($pattern)
{
    ClientFactory::shouldReceive('make')->andReturn(new class implements Client {
        public function checkAvailability(Stock $stock): StockStatus
        {
            return new StockStatus(
                true,
                Money::GBP(1000)
            );
        }

        public function getProductURLPatterns(): array
        {
            return [
                'test'
            ];
        }

        // implement the endpoint method
        public function endpoint(Stock $stock): string
        {
            return 'http://example.com';

        }

    });

}

AssertableInertia::macro('hasResource', function (string $key, JsonResource $resource) {
    $this->has($key);
    expect($this->prop($key))
        ->toEqual($resource->response()->getData(true), message: "The resource for key \"{$key}\" was not passed to Inertia.");

    return $this;
});

AssertableInertia::macro('hasPaginatedResource', function (string $key, ResourceCollection $resource) {
    $this->hasResource("{$key}.data", $resource);
    expect($this->prop($key))->toHaveKeys(['data', 'links', 'meta']);

    return $this;
});

TestResponse::macro('assertHasResource', function (string $key, JsonResource $resource) {
    return $this->assertInertia(fn(AssertableInertia $inertia) => $inertia->hasResource($key, $resource));
});

TestResponse::macro('assertHasPaginatedResource', function (string $key, ResourceCollection $resource) {
    return $this->assertInertia(fn(AssertableInertia $inertia) => $inertia->hasPaginatedResource($key, $resource));
});

TestResponse::macro('assertComponent', function (string $component) {
    return $this->assertInertia(fn(AssertableInertia $inertia) => $inertia->component($component, true));
});
