<?php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as EloquentModel;
use MongoDB\Laravel\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Pagination\LengthAwarePaginator;

class MongoDBModel extends EloquentModel
{
    use HasFactory;

    public static $perpage = 25;

    public $timestamps = true;

    protected $primaryKey = 'id';

    protected static function getColumns(string $pTable): array
    {
        $fetchRows = self::query()
        ->select('column_name')
        ->from('information_schema.columns')
        ->where('table_name', $pTable)
        ->get();

        if (! $fetchRows) {
            return [];
        }

        $columns = [];
        foreach ($fetchRows->toArray() as $fetchRow) {
            $columns[] = $fetchRow['column_name'];
        }

        return mapping('strtolower', $columns);
    }

    public function scopeFilters(Builder $pQuery, array $pFilters = [], array $pLiked = []): void
    {
        foreach ($pFilters as $key => $value) {
            $compareSignal = in_array($key, $pLiked) ? 'LIKE' : '=';
            $value = (in_array($key, $pLiked)) ? "%{$value}%" : $value;
            $table = (str_contains($key, '.')) ? $key : ((isset($this->table) && ! empty($this->table)) ? "{$this->table}.{$key}" : $key);
            $pQuery->where($table, $compareSignal, $value);
        }
    }

    /**
     * Format generated query.
     */
    public function scopeSqlDebug($model)
    {
        $query = str_replace(['%', '?'], ['%%', '\'%s\''], $model->toSql());
        $query = vsprintf($query, $model->getBindings());

        dd($query);
    }

    /**
     * Show generated query and die.
     */
    public function scopeDd($model)
    {
        $dd = $this->sqlDebug($model);

        dd($dd);
    }

    /**
     * Show generated query and continue.
     */
    public function scopeDump($model)
    {
        $dd = $this->sqlDebug($model);

        dump($dd);
    }

    public static function getAll($pColumns = ['*']): Collection
    {
        return self::all($pColumns);
    }

    public static function getById(int $pId)
    {
        return self::find($pId);
    }

    public static function filtered(array $pFilters, ?string $pOrderBy, $pDirection = 'ASC'): LengthAwarePaginator
    {
        $currentPage = request('page', 1);
        $query = self::query()->filters($pFilters);
        if ($pOrderBy) {
            $query->orderBy($pOrderBy, $pDirection);
        }

        $fetchRows = $query->get();

        $paginated = new LengthAwarePaginator(
            $fetchRows->forPage($currentPage, self::$perpage),
            $fetchRows->count(),
            self::$perpage,
            $currentPage
        );

        return $paginated;
    }

    public static function allPaginated(array $pColumns = ['*'])
    {
        $currentPage = request('page', 1);
        $all = self::getAll($pColumns);
        $paginated = new LengthAwarePaginator(
            $all->forPage($currentPage, self::$perpage),
            $all->count(),
            self::$perpage,
            $currentPage
        );

        return $paginated;
    }

    public static function paginationByFilters(array $pFilters, ?string $pOrderBy, $pDirection = 'ASC')
    {
        $fetchRows = self::query()->filters($pFilters);
        if ($pOrderBy) {
            $fetchRows->orderBy($pOrderBy, $pDirection);
        }

        $fetchRows->paginate(self::$perPage);

        return $fetchRows;
    }

    public static function firstFiltered(array $pFilters): self|null
    {
        $fetchRow = self::query()->filters($pFilters)->first();

        return $fetchRow;
    }

    public static function isExists(int $pId): bool
    {
        return self::getById($pId) ? true : false;
    }

    public static function byStatus(int $pStatusId): Collection
    {
        $filter = ['status_id' => $pStatusId];
        $fetchRows = self::query()->filters($filter)->get();

        return $fetchRows;
    }

    public static function deletedBy(object $pFetchRow): bool
    {
        /**
         * @disregard
         */
        $authUser = auth()->user();
        $id = $pFetchRow->id;
        if (auth('sanctum')->user()) {
            $user = auth('sanctum')->user()->cpf;
        } elseif ($authUser) {
            $user = $authUser->matricula;
        } else {
            $user = 'undefined';
        }

        $update = ['deleted_by' => $user];
        self::query()->where('id', $id)->update($update);

        if (! $pFetchRow->delete()) {
            return false;
        }

        return true;
    }

    public static function show(int $id): ?Model
    {
        return self::find($id);
    }

    public static function showWith(int $id, array $relations = []): ?Model
    {
        return self::with($relations)->find($id);
    }

    public static function updateWith(int $id, array $data, array $additionalConditions = []): ?bool
    {
        $query = self::where('id', $id);
        foreach ($additionalConditions as $key => $value) {
            $query->where($key, $value);
        }

        return $query->update($data);
    }
}
