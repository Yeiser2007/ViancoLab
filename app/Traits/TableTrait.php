<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\{Concept, ConceptCategory};

trait TableTrait
{

    public static function sortAndPaginate(Request $request, Builder $builder)
    {
        return self::paginate($request, self::sort($request, $builder));
    }

    public static function sort(Request $request, Builder $builder)
    {
        if ($request->exists('sort')) {
            list($sortCol, $sortDir) = explode('|', $request->sort);
            $listCol = explode(',', $sortCol);
            $listDir = explode(',', $sortDir);
            for ($i=0; $i < count($listCol) ; $i++) { 
                $builder = $builder->orderBy($listCol[$i], $listDir[$i]);
            }           
        }

        return $builder;
    }

    public static function paginate(Request $request, Builder $builder)
    {
        if ($request->exists('per_page')) {
            $builder = $builder->paginate((int)$request->per_page);
        } else {
            $builder = $builder->paginate($builder->count());
        }

        return $builder;
    }
}
