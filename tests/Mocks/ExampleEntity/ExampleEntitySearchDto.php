<?php

namespace EscolaLms\Core\Tests\Mocks\ExampleEntity;

use Carbon\Carbon;
use EscolaLms\Core\Dtos\Contracts\DtoContract;
use EscolaLms\Core\Dtos\Contracts\InstantiateFromRequest;
use EscolaLms\Core\Dtos\CriteriaDto;
use EscolaLms\Core\Repositories\Criteria\PeriodCriterion;
use EscolaLms\Core\Repositories\Criteria\Primitives\DateCriterion;
use EscolaLms\Core\Repositories\Criteria\Primitives\EqualCriterion;
use EscolaLms\Core\Repositories\Criteria\Primitives\InCriterion;
use EscolaLms\Core\Repositories\Criteria\Primitives\IsNullCriterion;
use EscolaLms\Core\Repositories\Criteria\Primitives\LikeCriterion;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ExampleEntitySearchDto extends CriteriaDto implements DtoContract, InstantiateFromRequest
{
    public static function instantiateFromRequest(Request $request): self
    {
        $criteria = new Collection();

        if ($request->get('name')) {
            $criteria->push(new LikeCriterion('name', $request->get('name')));
        }
        if ($request->get('status')) {
            $criteria->push(new EqualCriterion('status', $request->get('status')));
        }
        if ($request->get('gt_created_at')) {
            $criteria->push(new DateCriterion(
                'created_at',
                new Carbon($request->get($request->get('gt_created_at'))),
                '>'
            ));
        }
        if ($request->get('from') || $request->get('to')) {
            $criteria->push(new PeriodCriterion(
                new Carbon($request->get('from') ?? 0),
                new Carbon($request->get('to') ?? null),
                'date_time'
            ));
        }
        if ($request->get('ids')) {
            $criteria->push(new InCriterion('id', $request->get('ids')));
        }
        if ($request->get('nullable')) {
            $criteria->push(new IsNullCriterion('description', $request->get('nullable')));
        }

        return new static($criteria);
    }
}
