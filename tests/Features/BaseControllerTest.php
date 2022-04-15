<?php

namespace EscolaLms\Core\Tests\Features;

use EscolaLms\Core\Http\Controllers\EscolaLmsBaseController;
use EscolaLms\Core\Models\User;
use EscolaLms\Core\Tests\Mocks\TestController;
use EscolaLms\Core\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected EscolaLmsBaseController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = app(TestController::class);
    }

    public function testSendResponse(): void
    {

        $response = $this->controller->sendResponse([
            'test-key' => 'test-value'
        ], 'test-message', 200);

        $this->assertJsonStringEqualsJsonString(json_encode([
            'success' => true,
            'message' => 'test-message',
            'data' => [
                'test-key' => 'test-value'
            ]
        ]), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->controller->sendResponse([
            'test-key' => 'test-value'
        ], 'test-message', 400);

        $this->assertJsonStringEqualsJsonString(json_encode([
            'success' => false,
            'message' => 'test-message',
            'data' => [
                'test-key' => 'test-value'
            ]
        ]), $response->getContent());
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testSendError(): void
    {
        $response = $this->controller->sendError('test-message');

        $this->assertJsonStringEqualsJsonString(json_encode([
            'success' => false,
            'message' => 'test-message',
        ]), $response->getContent());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testSendSuccess(): void
    {
        $response = $this->controller->sendSuccess('test-message');

        $this->assertJsonStringEqualsJsonString(json_encode([
            'success' => true,
            'message' => 'test-message',
        ]), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testSendResponseForResource(): void
    {
        $resource = new JsonResource([
            'test-key' => 'test-value'
        ]);

        $response = $this->controller->sendResponseForResource($resource, 'test-message');

        $this->assertJsonStringEqualsJsonString(json_encode([
            'success' => true,
            'message' => 'test-message',
            'data' => [
                'test-key' => 'test-value'
            ]
        ]), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testSendResponseForResourceUser(): void
    {
        $user = User::factory()->create();
        $resource = new JsonResource($user);
        $response = $this->controller->sendResponseForResource($resource, 'test-message');
        $this->assertJsonStringEqualsJsonString(json_encode([
            'success' => true,
            'message' => 'test-message',
            'data' => [
                'avatar_url' => $user->avatar_url,
                'created_at' => $user->created_at,
                'email_verified_at' => $user->email_verified_at,
                'email' => $user->email,
                'first_name' => $user->first_name,
                'id' => $user->id,
                'is_active' => $user->is_active,
                'last_name' => $user->last_name,
                'updated_at' => $user->updated_at,
            ]
        ]), $response->getContent());
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testSendResponseForResourcePaginator(): void
    {
        $users = User::factory()->count(10)->create();
        $paginator = User::paginate();
        $resource = ResourceCollection::make($paginator);
        $response = $this->controller->sendResponseForResource($resource, 'test-message');
        $json = $response->getContent();
        $array = json_decode($json, true);
        $this->assertCount(10, $array['data']);
        $this->assertEquals(10, $array['meta']['total']);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testUserAttribute(): void
    {
        $user = User::factory()->create();

        $this->assertEquals("{$user->first_name} {$user->last_name}", $user->name);
        $this->assertEquals(null, $user->avatarUrl);
        $this->assertEquals(! is_null($user->email_verified_at), $user->emailVerified);
    }
}
