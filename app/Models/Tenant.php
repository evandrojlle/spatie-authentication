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
}
