<?php

namespace EscolaLms\Core\Tests\Features;

use Carbon\Carbon;
use EscolaLms\Core\Dtos\OrderDto;
use EscolaLms\Core\Dtos\PaginationDto;
use EscolaLms\Core\Dtos\PeriodDto;
use EscolaLms\Core\Enums\UserRole;
use EscolaLms\Core\Models\User;
use EscolaLms\Core\Repositories\Criteria\Primitives\DoesntHasCriterion;
use EscolaLms\Core\Repositories\Criteria\Primitives\EqualCriterion;
use EscolaLms\Core\Repositories\Criteria\Primitives\HasCriterion;
use EscolaLms\Core\Repositories\Criteria\Primitives\ModelCriterion;
use EscolaLms\Core\Repositories\Criteria\Primitives\NotNullCriterion;
use EscolaLms\Core\Repositories\Criteria\Primitives\WhereCriterion;
use EscolaLms\Core\Repositories\Criteria\Primitives\WhereNotInOrIsNullCriterion;
use EscolaLms\Core\Repositories\Criteria\RoleCriterion;
use EscolaLms\Core\Repositories\Criteria\UserCriterion;
use EscolaLms\Core\Repositories\Criteria\UserSearchCriterion;
use EscolaLms\Core\Tests\Mocks\BaseRepository;
use EscolaLms\Core\Tests\Mocks\CompareDto;
use EscolaLms\Core\Tests\Mocks\UpdateDto;
use EscolaLms\Core\Tests\Repositories\TestUser;
use EscolaLms\Core\Tests\Repositories\UserRepository;
use EscolaLms\Core\Tests\TestCase;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BaseRepositoryTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected BaseRepository $repository;
    protected User $user;
    protected User $last_user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(BaseRepository::class);
        $this->user = User::factory()->create([
            'first_name' => $this->faker->firstName . $this->faker->numberBetween(),
            'last_name' => $this->faker->lastName . $this->faker->numberBetween(),
            'country' => 'Old Country'
        ]);
        $this->user->assignRole(UserRole::ADMIN);
        $users = User::factory()->count(8)->create();
        $this->last_user = User::factory()->create();
    }

    public function testPaginate(): void
    {
        /** @var LengthAwarePaginator $paginator */
        $paginator = $this->repository->paginate(10);
        $this->assertTrue($paginator instanceof LengthAwarePaginator);

        $firstResult = $paginator->getCollection()->first();

        $this->assertEquals(10, $paginator->total());
        $this->assertEquals($this->user->getKey(), $firstResult->getKey());
    }

    public function testAllQuery(): void
    {
        $query = $this->repository->allQuery(['email' => $this->user->email]);
        $this->assertTrue($query instanceof Builder);

        $collection = $query->get();
        $this->assertEquals(1, $collection->count());
        $this->assertEquals($this->user->email, $collection->first()->email);
    }

    public function testAll(): void
    {
        $collection = $this->repository->all([], null, null, ['*'], 'desc', 'id');
        $this->assertTrue($collection instanceof Collection);

        $this->assertEquals(10, $collection->count());
        $this->assertEquals($this->last_user->email, $collection->first()->email);
        $this->assertEquals($this->user->email, $collection->last()->email);
    }

    public function testAllPaginated(): void
    {
        $dto = new PaginationDto(0, 5);
        $collection = $this->repository->allPaginated($dto);

        $this->assertEquals(5, $collection->count());
        $this->assertEquals($this->user->email, $collection->first()->email);
        $this->assertNotEquals($this->last_user->email, $collection->last()->email);

        $dto = new PaginationDto(null, 10);
        $collection = $this->repository->allPaginated($dto);

        $this->assertEquals(10, $collection->count());
    }

    public function testPaginatedGetPage(): void
    {
        $dto = new PaginationDto(10, 50);

        $this->assertEquals(5, $dto->getPage());

        $this->assertEquals(["skip" => 10, "limit" => 50], $dto->toArray());

        $dto = new PaginationDto(null, 50);

        $this->assertEquals(0, $dto->getPage());
    }

    // TODO: Check why all() and allWithOrder() do same thing, just have different order of parameters... BaseRepository is in dire need of some cleanup and refactor.
    public function testAllWithOrder(): void
    {
        $collection = $this->repository->allWithOrder([], 0, 5, 'id', 'desc');
        $this->assertTrue($collection instanceof Collection);

        $this->assertEquals(5, $collection->count());
        $this->assertEquals($this->last_user->email, $collection->first()->email);
        $this->assertNotEquals($this->user->email, $collection->last()->email);
    }

    public function testOrderDtoToArray(): void
    {
        $dto = new OrderDto('id', 'desc');

        $this->assertEquals(["order_by" => "id", "order" => "desc"], $dto->toArray());
    }

    public function testPeriodDtoToArray(): void
    {
        $from = Carbon::now()->addDays(5);
        $to = Carbon::now()->addDays(10);
        $dto = new PeriodDto($from, $to);

        $this->assertEquals(["from" => $from, "to" => $to], $dto->toArray());
    }

    public function testAllInUserContextQuery(): void
    {
        $query = $this->repository->allInUserContextQuery($this->user, [], null, null, ['*'], 'id');
        $this->assertTrue($query instanceof Builder);

        $collection = $query->get();
        $this->assertEquals(1, $collection->count());
        $this->assertEquals($this->user->email, $collection->first()->email);
    }

    public function testAllInUserContext(): void
    {
        $collection = $this->repository->allInUserContext($this->user, [], null, null, ['*'], 'id');

        $this->assertTrue($collection instanceof Collection);
        $this->assertEquals(1, $collection->count());
        $this->assertEquals($this->user->email, $collection->first()->email);
    }

    public function testAllIn(): void
    {
        $collection = $this->repository->allIn('id', new Collection([$this->user->id, $this->last_user->id]));

        $this->assertTrue($collection instanceof Collection);
        $this->assertEquals(2, $collection->count());
        $this->assertTrue($collection->contains('id', '=', $this->user->getKey()));
        $this->assertTrue($collection->contains('id', '=', $this->last_user->getKey()));
    }

    public function testCreate(): void
    {
        $user = User::factory()->make();

        $user2 = $this->repository->create($user->toArray());

        $this->assertTrue($user2 instanceof User);
        $this->assertTrue($user2->exists());
        $this->assertEquals($user->email, $user2->email);
    }

    // This test makes zero sense in this context ¯\_(ツ)_/¯ because user has no relation to other users
    public function testCreateAsUser(): void
    {
        /** @var User */
        $user = User::factory()->make();

        $user2 = $this->repository->createAsUser($user, $user->toArray(), 'id');

        $this->assertTrue($user2 instanceof User);
        $this->assertTrue($user2->exists());
        $this->assertEquals($user->email, $user2->email);
    }

    public function testCreateUsingModel(): void
    {
        /** @var User */
        $user = User::factory()->make();

        $user2 = $this->repository->createUsingModel($user);

        $this->assertTrue($user2 instanceof User);
        $this->assertTrue($user2->exists());
        $this->assertEquals($user->email, $user2->email);
    }

    public function testSearchByCriteria(): void
    {
        $criteria = [new EqualCriterion('email', $this->user->email)];

        $collection = $this->repository->searchByCriteria($criteria);

        $this->assertTrue($collection instanceof Collection);
        $this->assertEquals(1, $collection->count());
        $this->assertEquals($this->user->email, $collection->first()->email);
    }

    public function testSearchByNotNullCriterion(): void
    {
        User::query()->whereNotNull('phone')->update(['phone' => null]);
        $criteria = [new NotNullCriterion('phone')];

        $collection = $this->repository->searchByCriteria($criteria);

        $this->assertEquals(0, $collection->count());

        $this->user = User::factory()->create(['phone' => '123123123']);

        $collection = $this->repository->searchByCriteria($criteria);

        $this->assertEquals(1, $collection->count());
    }

    public function testSearchByWhereCriterion(): void
    {
        User::query()->whereNotNull('phone')->update(['phone' => null]);
        $criteria = [new WhereCriterion('phone', '123123123', '=')];

        $collection = $this->repository->searchByCriteria($criteria);

        $this->assertEquals(0, $collection->count());

        $this->user = User::factory()->create(['phone' => '123123123']);

        $collection = $this->repository->searchByCriteria($criteria);

        $this->assertEquals(1, $collection->count());
    }

    public function testSearchByWhereNotInOrIsNullCriterion(): void
    {
        User::query()->update(['phone' => '123123123']);
        $criteria = [new WhereNotInOrIsNullCriterion('phone', ['123123123'])];

        $collection = $this->repository->searchByCriteria($criteria);

        $this->assertEquals(0, $collection->count());

        $this->user = User::factory()->create(['phone' => null]);
        $this->user = User::factory()->create(['phone' => '333444555']);

        $collection = $this->repository->searchByCriteria($criteria);

        $this->assertEquals(2, $collection->count());
    }

    public function testSearchByRoleCriterion(): void
    {
        $criteria = [new RoleCriterion(UserRole::STUDENT)];

        $collection = $this->repository->searchByCriteria($criteria);

        $this->assertEquals(0, $collection->count());

        $this->last_user->assignRole(UserRole::STUDENT);

        $collection = $this->repository->searchByCriteria($criteria);

        $this->assertEquals(1, $collection->count());
    }

    public function testUserGetActive(): void
    {
        $userRepository = \App::make(UserRepository::class);
        User::query()->update(['is_active' => false]);

        $collection = $userRepository->getActive();

        $this->assertEquals(0, $collection->count());

        User::query()->first()->update(['is_active' => true]);

        $collection = $userRepository->getActive();

        $this->assertEquals(1, $collection->count());
    }

    public function testSearchByUserCriteria(): void
    {
        $criteria = [
            new UserCriterion('id', $this->user)
        ];
        $result = $this->repository->searchByCriteria($criteria);
        $this->assertCount(1, $result);
        $this->assertEquals($this->user->getKey(), $result->first()->getKey());

        $criteria = [
            new UserSearchCriterion($this->user->first_name)
        ];
        $result = $this->repository->searchByCriteria($criteria);
        $this->assertCount(1, $result);
        $this->assertEquals($this->user->first_name, $result->first()->first_name);

        $criteria = [
            new UserSearchCriterion($this->user->last_name)
        ];
        $result = $this->repository->searchByCriteria($criteria);
        $this->assertCount(1, $result);
        $this->assertEquals($this->user->last_name, $result->first()->last_name);

        $criteria = [
            new UserSearchCriterion($this->user->email)
        ];
        $result = $this->repository->searchByCriteria($criteria);
        $this->assertCount(1, $result);
        $this->assertEquals($this->user->email, $result->first()->email);

        $criteria = [
            new ModelCriterion('id', $this->user)
        ];
        $result = $this->repository->searchByCriteria($criteria);
        $this->assertCount(1, $result);
        $this->assertEquals($this->user->getKey(), $result->first()->getKey());
    }

    public function testSearchByRoles(): void
    {
        $criteria = [
            new HasCriterion('roles', fn ($query) => $query->where('name', UserRole::ADMIN))
        ];
        $result = $this->repository->searchByCriteria($criteria);
        $this->assertCount(1, $result);
        $this->assertEquals($this->user->getKey(), $result->first()->getKey());

        $criteria = [
            new HasCriterion('roles', fn ($query) => $query->where('name', UserRole::STUDENT))
        ];
        $result = $this->repository->searchByCriteria($criteria);
        $this->assertCount(0, $result);

        $this->last_user->assignRole(UserRole::STUDENT);
        $criteria = [
            new DoesntHasCriterion('roles', fn ($query) => $query->where('name', UserRole::STUDENT))
        ];
        $result = $this->repository->searchByCriteria($criteria);
        $this->assertCount(9, $result);
    }

    public function testQueryWithAppliedCriteria(): void
    {

        $criteria = [
            new EqualCriterion('email', $this->user->email)
        ];

        $query = $this->repository->queryWithAppliedCriteria($criteria);

        $this->assertTrue($query instanceof Builder);

        $collection = $query->get();

        $this->assertTrue($collection instanceof Collection);
        $this->assertEquals(1, $collection->count());
        $this->assertEquals($this->user->email, $collection->first()->email);
    }

    public function testRemove(): void
    {
        $this->assertDatabaseHas($this->user->getTable(), [$this->user->getKeyName() => $this->user->getKey()]);
        $this->repository->remove($this->user);
        $this->assertDatabaseMissing($this->user->getTable(), [$this->user->getKeyName() => $this->user->getKey()]);
    }

    // TODO: What even is the purpose / use-case of this method? And why does it return object and not array?
    public function testGetEmptyColumns(): void
    {
        $columns = $this->repository->getEmptyColumns();

        $this->assertObjectHasAttribute('email', $columns);
        $this->assertObjectHasAttribute('first_name', $columns);
        $this->assertObjectHasAttribute('last_name', $columns);
    }

    public function testFind(): void
    {
        $model = $this->repository->find($this->user->getKey());

        $this->assertTrue($model instanceof User);
        $this->assertEquals($this->user->getKey(), $model->getKey());
        $this->assertEquals($this->user->email, $model->email);
    }

    public function testUpdate(): void
    {
        $new_email = 'new-email@test.test';
        $this->assertNotEquals($new_email, $this->user->email);

        $model = $this->repository->update(['email' => $new_email], $this->user->getKey());

        $this->assertTrue($model instanceof User);

        $this->user->refresh();
        $this->assertEquals($this->user->getKey(), $model->getKey());
        $this->assertEquals($this->user->email, $model->email);
        $this->assertEquals($new_email, $this->user->email);
    }

    public function testUpdateUsingDto(): void
    {
        $new_email = 'new-email@test.test';
        $this->assertNotEquals($new_email, $this->user->email);

        $old_country = $this->user->country;

        $dto = new UpdateDto($new_email);
        $model = $this->repository->updateUsingDto($dto, $this->user);

        $this->assertTrue($model instanceof User);

        $this->user->refresh();
        $this->assertEquals($this->user->getKey(), $model->getKey());
        $this->assertEquals($this->user->email, $model->email);
        $this->assertEquals($new_email, $this->user->email);
        $this->assertEquals($old_country, $this->user->country);
    }

    public function testUpdateUsingDtoIgnoreEmpty(): void
    {
        $new_email = 'new-email@test.test';
        $this->assertNotEquals($new_email, $this->user->email);

        $old_country = $this->user->country;

        $dto = new UpdateDto($new_email);
        $model = $this->repository->updateUsingDto($dto, $this->user, true);

        $this->assertTrue($model instanceof User);

        $this->user->refresh();
        $this->assertEquals($this->user->getKey(), $model->getKey());
        $this->assertEquals($this->user->email, $model->email);
        $this->assertEquals($new_email, $this->user->email);
        $this->assertNotEquals($old_country, $this->user->country);
        $this->assertEmpty($this->user->old_country);
    }

    // Another test that makes zero sense in this context
    public function testUpdateAsUser(): void
    {
        /** @var User */
        $user = User::factory()->make();

        $old_email = $this->user->email;

        $model = $this->repository->updateAsUser($this->user, $user->toArray(), $this->user->getKey(), 'id');

        $this->assertTrue($model instanceof User);
        $this->assertNotEquals($old_email, $model->email);
        $this->assertEquals($user->email, $model->email);
    }

    public function testDelete(): void
    {
        $this->assertDatabaseHas($this->user->getTable(), [$this->user->getKeyName() => $this->user->getKey()]);
        $this->repository->delete($this->user->getKey());
        $this->assertDatabaseMissing($this->user->getTable(), [$this->user->getKeyName() => $this->user->getKey()]);
    }

    // Another test that makes zero sense in this context
    public function testDeleteAsUser(): void
    {
        $this->assertDatabaseHas($this->user->getTable(), [$this->user->getKeyName() => $this->user->getKey()]);
        $this->repository->deleteAsUser($this->user->getKey(), $this->user, 'id');
        $this->assertDatabaseMissing($this->user->getTable(), [$this->user->getKeyName() => $this->user->getKey()]);
    }

    // TODO: check if this method is even used anywhere, because it's naming has nothing to do with how HTTP Patch should work. This is just an updateOrCreate with identyfying data set in DTO instead of array. And it can't hold partial data because create will fail on required/not null fields.
    public function testPatch(): void
    {
        $new_country = 'New Country';
        $this->assertNotEquals($new_country, $this->user->country);

        $dto = new CompareDto($this->user->email, $new_country);
        $model = $this->repository->patch($dto);

        $this->assertTrue($model instanceof User);

        $this->user->refresh();
        $this->assertEquals($this->user->getKey(), $model->getKey());
        $this->assertEquals($this->user->email, $model->email);
        $this->assertEquals($new_country, $model->country);
        $this->assertEquals($new_country, $this->user->country);
    }

    public function testDeleteWhere(): void
    {
        $this->assertDatabaseHas($this->user->getTable(), [$this->user->getKeyName() => $this->user->getKey()]);
        $this->repository->deleteWhere(['email' => $this->user->email]);
        $this->assertDatabaseMissing($this->user->getTable(), [$this->user->getKeyName() => $this->user->getKey()]);
    }

    // TODO: this method was probably not even used anywhere as it didn't return anything in BaseRepository because of an error
    public function testCount(): void
    {
        $this->assertEquals(10, $this->repository->count());
    }

    public function testCountByCriteria(): void
    {
        $criteria = [
            new EqualCriterion('email', $this->user->email)
        ];
        $this->assertEquals(1, $this->repository->countByCriteria($criteria));

        $criteria = [
            new EqualCriterion('email', 'no-such-email@test.test')
        ];
        $this->assertEquals(0, $this->repository->countByCriteria($criteria));
    }

    // TODO: we probably should just create a `whereLike` method in query builder that uses always correct LIKE method
    public function testLikeParam(): void
    {
        $dbDriver = DB::connection()->getPdo()->getAttribute(\PDO::ATTR_DRIVER_NAME);
        $result = $this->repository->likeParam('lorem');
        if ($dbDriver === 'pgsql') {
            $this->assertEquals('ILIKE', $result[0]);
        } else {
            $this->assertEquals('LIKE', $result[0]);
        }
        $this->assertEquals('%lorem%', $result[1]);
    }
}
