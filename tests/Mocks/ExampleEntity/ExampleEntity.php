<?php

namespace EscolaLms\Core\Tests\Mocks\ExampleEntity;

use EscolaLms\Core\Models\Traits\QueryCacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExampleEntity extends Model
{
    use HasFactory, QueryCacheable;

    protected $table = 'example_entities';

    protected $guarded = ['id'];

    protected static function newFactory(): ExampleEntityFactory
    {
        return ExampleEntityFactory::new();
    }
}
