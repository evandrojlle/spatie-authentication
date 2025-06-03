<?php

namespace App\Models;

use App\Models\TenantModel;

class Tenant extends TenantModel
{
    protected $table = 'tenants'; // Define o nome da tabela.

    public $timestamps = true; // Habilita os timestamps (created_at e updated_at).

    protected $primaryKey = 'id'; // Define a chave primária da tabela.

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'social_name',
        'fantasy_name',
        'cnpj',
        'email',
        'phone',
        'status',
        'domain', // Adicione o campo para identificar o tenant pelo domínio
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
            'deleted_at' => 'datetime:Y-m-d H:i:s',
            'status' => 'boolean',
        ];
    }

    public function getDatabaseName(): string
    {
        if ($this->tenantDatabaseConnectionName() !== $this->database) {
            config(
                [
                    "multitenancy.tenant_database_connection_name" => $this->database,
                ]
            );
        }

        return $this->tenantDatabaseConnectionName();
    }

    public function getDomainAttribute($value)
    {
        return strtolower($value); // Garante que o domínio seja sempre armazenado em minúsculas
    }

    public function setDomainAttribute($value)
    {
        $this->attributes['domain'] = strtolower($value); // Garante que o domínio seja sempre armazenado em minúsculas
    }

    /**
     * Find a tenant by its domain.
     *
     * @param string $domain
     * @return Tenant|null
     */
    public static function findByDomain(string $domain)
    {
        return static::where('domain', $domain)->first();
    }

    public function isActive(): bool
    {
        return $this->status ? true : false; // Verifica se o status do tenant é ativo (true)
    }
}
