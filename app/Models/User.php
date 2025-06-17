<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'cpf',
        'phone',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'created_at' => 'datetime:d/m/Y à\s H:i:s',
            'updated_at' => 'datetime:d/m/Y à\s H:i:s',
            'deleted_at' => 'datetime:Y-m-d H:i:s',
            'cpf' => 'App\Casts\CpfMask', // Cast CPF with mask
            'phone' => 'App\Casts\PhoneMask', // Cast phone with mask
        ];
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

    public static function isRegistered(string $pDoc, ?int $id = null): bool
    {
        $query = self::query()->filters(['cpf' => $pDoc]);
        if (null !== $id) {
            $query->where('id', '!=', $id);
        }

        $fetchRow = $query->first();

        return (! $fetchRow) ? false : true;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
