<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
</p>
<p align="center">
    <a href="https://github.com/laravel/framework/actions">
        <img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status">
    </a>
    <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads">
    </a>
    <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version">
    </a>
    <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
    </a>
</p>

## API REST em Laravel com Multi-Tenancy.

Plano para criar a API REST em Laravel com Arquitetura Multi-Tenancy

- Configuração inicial do projeto Laravel:
    - Instalar Laravel 11.x.
    - Configurar o banco de dados MySQL no arquivo .env.
- Instalar pacotes necessários:
    - Instalar pacotes para Multi-Tenancy, como spatie/laravel-multitenancy.
    - Instalar pacotes para autenticação, como laravel/passport ou laravel/sanctum.
- Estrutura de banco de dados:
    - Criar tabelas para empresas, usuários, perfis, módulos e funcionalidades.
    - Configurar relacionamentos entre as tabelas.
- Implementação de Multi-Tenancy:
    - Configurar o pacote spatie/laravel-multitenancy para gerenciar empresas como tenants.
    - Criar middleware para identificar o tenant ativo com base no domínio ou cabeçalho.
- Autenticação:
    - Implementar autenticação com Laravel Sanctum.
    - Criar middleware para carregar empresa, perfil, módulos e funcionalidades do usuário autenticado.
- Controle de permissões:
    - Implementar lógica para verificar permissões de acesso às funcionalidades.
    - Retornar erro 403 caso o usuário não tenha permissão.
- Estrutura de módulos e funcionalidades:
    - Criar tabelas para vincular módulos às empresas e funcionalidades aos módulos e perfis.
- Endpoints da API:
    - Criar endpoints para CRUD de empresas, usuários, perfis, módulos e funcionalidades.
    - Criar endpoints para autenticação e verificação de permissões.
- Testes:
    - Implementar testes unitários e de integração para garantir o funcionamento correto da API.

### Código Inicial:
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Usuario;
use App\Models\Perfil;
use App\Models\Modulo;
use App\Models\Funcionalidade;

class EmpresaController extends Controller
{
    // Endpoint para criar uma empresa
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome_social' => 'required|string|max:255',
            'nome_fantasia' => 'required|string|max:255',
            'cnpj' => 'required|string|unique:empresas|max:14',
            'email' => 'required|email|unique:empresas|max:255',
            'telefone' => 'required|string|max:15',
            'status' => 'required|in:ativo,pendente,cancelado,inativo,excluído',
        ]);

        $empresa = Empresa::create($validated);

        return response()->json($empresa, 201);
    }

    // Endpoint para listar empresas
    public function index()
    {
        $empresas = Empresa::all();
        return response()->json($empresas);
    }
}

// Modelo Empresa
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome_social',
        'nome_fantasia',
        'cnpj',
        'email',
        'telefone',
        'status',
    ];
}
```

### Próximos passos:
1. Criar os modelos e controladores para Usuario, Perfil, Modulo e Funcionalidade.
2. Configurar middleware para Multi-Tenancy.
3. Implementar autenticação e controle de permissões.
4. Testar os endpoints e ajustar conforme necessário.
5. Instalar o pacote: Execute o seguinte comando no terminal para instalar o pacote:

```shell

```

## Configuração do pacote `spatie/laravel-multitenancy`

Como posso configurar o pacote `spatie/laravel-multitenancy` para implementar a arquitetura Multi-Tenancy no Laravel?

### Passos para configurar o pacote `spatie/laravel-multitenancy` no Laravel

1. Instalar o pacote: Execute o seguinte comando no terminal para instalar o pacote:

```shell
composer require spatie/laravel-multitenancy
```

2. Publicar as configurações: Após a instalação, publique os arquivos de configuração do pacote:

```shell
php artisan vendor:publish --provider="Spatie\Multitenancy\MultitenancyServiceProvider"
```

Isso criará o arquivo de configuração `config/multitenancy.php`.

3. Configurar o arquivo `config/multitenancy.php`: Edite o arquivo `config/multitenancy.php` para definir como os tenants serão identificados. Por exemplo:

```php
<?php
return [
    'tenant_finder' => Spatie\Multitenancy\TenantFinder\DomainTenantFinder::class,

    'tenant_model' => App\Models\Empresa::class,

    'current_tenant_container_key' => 'currentTenant',

    'switch_tenant_tasks' => [
        Spatie\Multitenancy\Tasks\SwitchTenantDatabaseTask::class,
    ],

    'tenant_artisan_search_fields' => [
        'id',
    ],

    'queues_are_tenant_aware_by_default' => true,
];
```

Aqui, estamos configurando o pacote para usar o modelo `Empresa` como tenant e identificar o tenant com base no domínio.

4. Criar o modelo de Tenant: Crie o modelo Empresa que será usado como tenant:

```shell
php artisan make:model Empresa -m
```

No modelo, configure o uso do trait `UsesTenantModel`:

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class Empresa extends Model
{
    use HasFactory, UsesTenantModel;

    protected $fillable = [
        'nome_social',
        'nome_fantasia',
        'cnpj',
        'email',
        'telefone',
        'status',
    ];
}
```

5. Configurar o banco de dados para Multi-Tenancy: Certifique-se de que o banco de dados seja configurado para suportar tenants. No arquivo config/database.php, configure a conexão padrão para ser dinâmica:

```php
<?php
'connections' => [
    'tenant' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => null, // Será definido dinamicamente
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'unix_socket' => env('DB_SOCKET', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'strict' => true,
        'engine' => null,
    ],
],
```

6. Configurar o Tenant Finder: O pacote oferece diferentes formas de identificar o tenant (por domínio, cabeçalho, etc.). Por exemplo, para usar o DomainTenantFinder, configure o domínio no modelo Empresa:

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class Empresa extends Model
{
    use HasFactory, UsesTenantModel;

    protected $fillable = [
        'nome_social',
        'nome_fantasia',
        'cnpj',
        'email',
        'telefone',
        'status',
        'domain', // Adicione o campo para identificar o tenant pelo domínio
    ];
}
```

Certifique-se de adicionar o campo `domain` na tabela `empresas` com uma migration:

```shell
php artisan make:migration add_domain_to_empresas_table --table=empresas
```

No arquivo da migration:

```php
<?php
public function up()
{
    Schema::table('empresas', function (Blueprint $table) {
        $table->string('domain')->unique()->nullable();
    });
}
```

Execute a migration:

```shell
php artisan migrate
```

Middleware para identificar o Tenant: Adicione o middleware para identificar o tenant com base no domínio:

```php
<?php
namespace App\Http\Middleware;

use Closure;
use Spatie\Multitenancy\Models\Tenant;

class IdentifyTenant
{
    public function handle($request, Closure $next)
    {
        Tenant::findByDomain($request->getHost());

        return $next($request);
    }
}
```

Registre o middleware no kernel:
```php
<?php
protected $middleware = [
    \App\Http\Middleware\IdentifyTenant::class,
];
```

Com isso, o pacote `spatie/laravel-multitenancy` estará configurado para gerenciar a arquitetura Multi-Tenancy no Laravel.

## Configuração do Laravel para Multi-Tenancy com JWT

Como posso configurar o Laravel Multi-Tenancy com autenticação JWT?

### Configuração do Laravel para Multi-Tenancy com JWT

Para implementar autenticação com JWT em um sistema Multi-Tenancy usando o pacote `spatie/laravel-multitenancy`, siga os passos abaixo:

1. Instalar pacotes necessários

Instale o pacote `spatie/laravel-multitenancy` e o pacote de autenticação JWT:

```shell
composer require spatie/laravel-multitenancy
composer require tymon/jwt-auth
```

2. Publicar as configurações

Publique os arquivos de configuração dos pacotes:

```shell
php artisan vendor:publish --provider="Spatie\Multitenancy\MultitenancyServiceProvider"
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
```

Isso criará os arquivos de configuração:

- `config/multitenancy.php`
- `config/jwt.php`

3. Configurar JWT

No arquivo .env, adicione a chave secreta para JWT:

```
JWT_SECRET=your_secret_key
```

Gere a chave secreta:

```shell
php artisan jwt:secret
```

4. Configurar Multi-Tenancy

Edite o arquivo `config/multitenancy.php` para usar o modelo `Empresa` como tenant:

```php
<?php
return [
    'tenant_finder' => Spatie\Multitenancy\TenantFinder\DomainTenantFinder::class,

    'tenant_model' => App\Models\Empresa::class,

    'current_tenant_container_key' => 'currentTenant',

    'switch_tenant_tasks' => [
        Spatie\Multitenancy\Tasks\SwitchTenantDatabaseTask::class,
    ],

    'tenant_artisan_search_fields' => [
        'id',
    ],

    'queues_are_tenant_aware_by_default' => true,
];
```

5. Criar o modelo de Tenant

Crie o modelo `Empresa`:

```shell
php artisan make:model Empresa -m
```

No modelo, configure o uso do trait `UsesTenantModel`:

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class Empresa extends Model
{
    use HasFactory, UsesTenantModel;

    protected $fillable = [
        'nome_social',
        'nome_fantasia',
        'cnpj',
        'email',
        'telefone',
        'status',
        'domain',
    ];
}
```

Adicione o campo `domain` na tabela `empresas` com uma migration:

```shell
php artisan make:migration add_domain_to_empresas_table --table=empresas
```

No arquivo da migration:

```php
<?php
public function up()
{
    Schema::table('empresas', function (Blueprint $table) {
        $table->string('domain')->unique()->nullable();
    });
}
```

Execute a migration:

```shell
php artisan migrate
```

6. Configurar autenticação JWT

Crie o modelo `Usuario` para autenticação:

```shell
php artisan make:model Usuario -m
```

No modelo, configure o uso do trait `Authenticatable`:

```php
<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Usuario extends Authenticatable implements JWTSubject
{
    protected $fillable = [
        'nome',
        'cpf',
        'email',
        'telefone',
        'senha',
        'status',
    ];

    protected $hidden = ['senha'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
```

Atualize a migration para incluir os campos necessários:

```php
<?php
public function up()
{
    Schema::create('usuarios', function (Blueprint $table) {
        $table->id();
        $table->string('nome');
        $table->string('cpf')->unique();
        $table->string('email')->unique();
        $table->string('telefone');
        $table->string('senha');
        $table->enum('status', ['ativo', 'pendente', 'cancelado', 'inativo', 'excluído']);
        $table->timestamps();
    });
}
```

Execute a migration:

```shell
php artisan migrate
```

7. Criar controlador de autenticação

Crie o controlador para autenticação:

```shell
php artisan make:controller AuthController
```

No controlador, implemente os métodos de login e logout:

```php
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'senha');

        if (!$token = JWTAuth::attempt(['email' => $credentials['email'], 'password' => $credentials['senha']])) {
            return response()->json(['error' => 'Credenciais inválidas'], 401);
        }

        return response()->json(['token' => $token]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    public function me()
    {
        return response()->json(JWTAuth::user());
    }
}
```

8. Configurar rotas

Adicione as rotas de autenticação no arquivo `routes/api.php`:

```php
<?php
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api');
```

9. Middleware para identificar Tenant

Crie um middleware para identificar o tenant com base no domínio:

```php
<?php
namespace App\Http\Middleware;

use Closure;
use Spatie\Multitenancy\Models\Tenant;

class IdentifyTenant
{
    public function handle($request, Closure $next)
    {
        Tenant::findByDomain($request->getHost());

        return $next($request);
    }
}
```

Registre o middleware no `kernel`:

```php
<?php
protected $middleware = [
    \App\Http\Middleware\IdentifyTenant::class,
];
```

10. Testar
    1. Crie empresas no banco de dados com diferentes domínios.
    2. Configure o servidor local para usar subdomínios ou diferentes domínios.
    3. Teste os endpoints de autenticação e verifique se o tenant correto está sendo carregado.

Com isso, você terá um sistema Multi-Tenancy com autenticação JWT configurado no Laravel

## Configurar Rotas Protegidas

Como posso configurar as rotas protegidas por autenticação JWT em um sistema Laravel Multi-Tenancy?

### Para configurar rotas protegidas por autenticação JWT em um sistema Laravel Multi-Tenancy, siga os passos abaixo:

1. Adicionar Middleware de Autenticação JWT

Certifique-se de que o middleware de autenticação JWT está registrado no kernel do Laravel. No arquivo `app/Http/Kernel.php`, adicione o middleware `auth:api`:

```php
<?php
protected $routeMiddleware = [
    // Outros middlewares...
    'auth' => \App\Http\Middleware\Authenticate::class,
    'auth.jwt' => \Tymon\JWTAuth\Http\Middleware\Authenticate::class,
];
```

2. Criar Middleware para Identificar Tenant

Crie um middleware para identificar o tenant com base no domínio ou outro identificador. Por exemplo:

```shell
php artisan make:middleware IdentifyTenant
```

No middleware `IdentifyTenant`, configure a lógica para identificar o tenant:

```php
<?php
namespace App\Http\Middleware;

use Closure;
use Spatie\Multitenancy\Models\Tenant;

class IdentifyTenant
{
    public function handle($request, Closure $next)
    {
        // Identificar o tenant pelo domínio
        Tenant::findByDomain($request->getHost());

        if (!Tenant::current()) {
            return response()->json(['error' => 'Tenant não encontrado'], 404);
        }

        return $next($request);
    }
}
```

Registre o middleware no kernel:

```php
<?php
protected $middleware = [
    // Outros middlewares...
    \App\Http\Middleware\IdentifyTenant::class,
];
```

3. Configurar Rotas Protegidas

No arquivo `routes/api.php`, configure as rotas protegidas por autenticação JWT e pelo middleware de identificação de tenant:

```php
<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TenantResourceController;

Route::middleware(['auth.jwt', 'IdentifyTenant'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/tenant-resource', [TenantResourceController::class, 'index']);
});
```

4. Criar Controladores

Crie controladores para gerenciar os recursos protegidos. Por exemplo, um controlador para retornar informações do tenant:

```shell
php artisan make:controller TenantResourceController
```

No controlador, implemente a lógica para acessar recursos do tenant:

```php
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Multitenancy\Models\Tenant;

class TenantResourceController extends Controller
{
    public function index()
    {
        $tenant = Tenant::current();

        if (!$tenant) {
            return response()->json(['error' => 'Tenant não encontrado'], 404);
        }

        return response()->json(['tenant' => $tenant]);
    }
}
```

5. Testar

1. Certifique-se de que o tenant está sendo identificado corretamente com base no domínio ou outro identificador.
2. Faça login para obter o token JWT.
3. Use o token JWT para acessar as rotas protegidas.

Com isso, suas rotas estarão protegidas por autenticação JWT e vinculadas ao tenant correto no sistema Multi-Tenancy.

## Geração de Tokens

Configuração da geração de Tokens para autenticação em um sistema Multi-Tenancy com Laravel?

### Para configurar a geração de tokens JWT para autenticação em um sistema Laravel Multi-Tenancy, siga os passos abaixo:

1. Instalar o Pacote JWT

Certifique-se de que o pacote JWT está instalado no seu projeto Laravel. Execute o comando:

```shell
composer require tymon/jwt-auth
```

2. Publicar Configuração do JWT

Publique o arquivo de configuração do JWT:

```shell
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
```

Isso criará o arquivo `config/jwt.php`.

3. Gerar a Chave Secreta JWT

Gere a chave secreta para o JWT no arquivo `.env`:

```shell
php artisan jwt:secret
```
Configurar o Modelo de Usuário

Isso adicionará a chave JWT_SECRET ao seu arquivo `.env`.

Certifique-se de que o modelo de usuário implementa a interface JWTSubject. Por exemplo:


```php
?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Usuario extends Authenticatable implements JWTSubject
{
    protected $fillable = [
        'nome',
        'email',
        'senha',
    ];

    protected $hidden = ['senha'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
```

5. Criar Controlador de Autenticação

Crie um controlador para gerenciar a geração de tokens JWT:

```shell
php artisan make:controller AuthController
```

1 vulnerabilidade
- hardcoded-credentials Embedding credentials in source code risks unauthorized access.

No controlador, implemente os métodos de login e logout:

```php
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'senha');

        if (!$token = JWTAuth::attempt(['email' => $credentials['email'], 'password' => $credentials['senha']])) {
            return response()->json(['error' => 'Credenciais inválidas'], 401);
        }

        return response()->json(['token' => $token]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    public function me()
    {
        return response()->json(JWTAuth::user());
    }
}
```

6. Configurar Middleware para Identificar Tenant

Crie um middleware para identificar o tenant com base no domínio ou outro identificador:

```shell
php artisan make:middleware IdentifyTenant
```
No middleware, implemente a lógica para identificar o tenant:

```php
<?php
namespace App\Http\Middleware;

use Closure;
use Spatie\Multitenancy\Models\Tenant;

class IdentifyTenant
{
    public function handle($request, Closure $next)
    {
        Tenant::findByDomain($request->getHost());

        if (!Tenant::current()) {
            return response()->json(['error' => 'Tenant não encontrado'], 404);
        }

        return $next($request);
    }
}
```

Registre o middleware no kernel:

```php
<?php
protected $middleware = [
    \App\Http\Middleware\IdentifyTenant::class,
];
```

7. Configurar Rotas

Adicione as rotas de autenticação no arquivo `routes/api.php`:

```php
<?php
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth.jwt');
Route::get('/me', [AuthController::class, 'me'])->middleware(['auth.jwt', 'IdentifyTenant']);
```

8. Testar
1. Crie usuários no banco de dados.
2. Configure tenants com diferentes domínios.
3. Faça login para gerar o token JWT.
4. Use o token JWT para acessar rotas protegidas e verificar se o tenant correto está sendo identificado.

Com isso, você terá configurado a geração de tokens JWT para autenticação em um sistema Laravel Multi-Tenancy.

## Proteger Rotas específicas

Como posso configurar a autenticação JWT para proteger rotas específicas em um sistema Laravel Multi-Tenancy?

### Para configurar a autenticação JWT para proteger rotas específicas em um sistema Laravel Multi-Tenancy, siga os passos abaixo:

1. Instalar o Pacote JWT

Certifique-se de que o pacote JWT está instalado no seu projeto Laravel:

```shell
composer require tymon/jwt-auth
```

2. Publicar Configuração do JWT

Publique o arquivo de configuração do JWT:
```shell
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
```

1 vulnerabilidade

- hardcoded-credentials Embedding credentials in source code risks unauthorized access

Isso criará o `arquivo config/jwt.php`.

3. Gerar a Chave Secreta JWT

Gere a chave secreta para o JWT no arquivo `.env`:
```shell
php artisan jwt:secret
```

Isso adicionará a chave `JWT_SECRET` ao seu arquivo `.env`.

4. Configurar o Modelo de Usuário

Certifique-se de que o modelo de usuário implementa a interface JWTSubject. Por exemplo:

```php
<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Usuario extends Authenticatable implements JWTSubject
{
    protected $fillable = ['nome', 'email', 'senha'];

    protected $hidden = ['senha'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
```

5. Criar Middleware para Identificar Tenant

Crie um middleware para identificar o tenant com base no domínio ou outro identificador:

```shell
php artisan make:middleware IdentifyTenant
```

No middleware, implemente a lógica para identificar o tenant:

```php
<?php
namespace App\Http\Middleware;

use Closure;
use Spatie\Multitenancy\Models\Tenant;

class IdentifyTenant
{
    public function handle($request, Closure $next)
    {
        Tenant::findByDomain($request->getHost());

        if (!Tenant::current()) {
            return response()->json(['error' => 'Tenant não encontrado'], 404);
        }

        return $next($request);
    }
}
```

Registre o middleware no kernel:

```php
<?php
protected $routeMiddleware = [
    'identify.tenant' => \App\Http\Middleware\IdentifyTenant::class,
];
```

6. Criar Controlador de Autenticação

Crie um controlador para gerenciar autenticação JWT:

```shell
php artisan make:controller AuthController
```

No controlador, implemente os métodos de login e logout:

```php
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'senha');
        $data = [
            'email' => $credentials['email'], 
            'password' => $credentials['senha']
        ];

        if (! $token = JWTAuth::attempt($data)) {
            return response()->json(['error' => 'Credenciais inválidas'], 401);
        }

        return response()->json(['token' => $token]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    public function me()
    {
        return response()->json(JWTAuth::user());
    }
}
```

7. Configurar Rotas Protegidas

No arquivo `routes/api.php`, configure as rotas protegidas por autenticação JWT e pelo middleware de identificação de tenant:

```php
<?php
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware(['auth.jwt']);
Route::get('/me', [AuthController::class, 'me'])->middleware(['auth.jwt', 'identify.tenant']);

Route::middleware(['auth.jwt', 'identify.tenant'])->group(function () {
    Route::get('/tenant-resource', function () {
        return response()->json(['message' => 'Recurso protegido para o tenant atual']);
    });
});
```

8. Testar
1. Login: Use a rota `/login` para gerar o token JWT.
2. Acesso Protegido: Use o token JWT para acessar rotas protegidas, como `/me` ou `/tenant-resource`.
3. Verificar Tenant: Certifique-se de que o tenant correto está sendo identificado com base no domínio ou outro identificador.

Com isso, suas rotas específicas estarão protegidas por autenticação JWT e vinculadas ao tenant correto no sistema Laravel Multi-Tenancy.

## Autorização de acesso baseada em papéis.

Como posso configurar a autorização de acesso baseada em papéis (roles) em um sistema Laravel Multi-Tenancy com autenticação JWT?

### Para configurar a autorização baseada em papéis (roles) em um sistema Laravel Multi-Tenancy com autenticação JWT, siga os passos abaixo:

1. Instalar Pacote de Controle de Papéis

Use um pacote como spatie/laravel-permission para gerenciar papéis e permissões. Instale o pacote:

```shell
composer require spatie/laravel-permission
```

2. Publicar Configuração e Migrar Tabelas

Publique o arquivo de configuração e migre as tabelas necessárias:

```shell
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

Isso criará as tabelas `roles`, `permissions`, `model_has_roles` e `model_has_permissions`.

3. Configurar o Modelo de Usuário

Adicione o trait `HasRoles` ao modelo de usuário para habilitar o gerenciamento de papéis e permissões:

```php
<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class Usuario extends Authenticatable implements JWTSubject
{
    use HasRoles;

    protected $fillable = ['nome', 'email', 'senha'];

    protected $hidden = ['senha'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
```

4. Criar Middleware para Verificar Papéis

Crie um middleware para verificar se o usuário tem o papel necessário:


```shell
php artisan make:middleware RoleMiddleware
```

No middleware, implemente a lógica de verificação de papéis:


```php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        $user = Auth::user();

        if (!$user || !$user->hasRole($role)) {
            return response()->json(['error' => 'Acesso negado: papel insuficiente'], 403);
        }

        return $next($request);
    }
}
```
Registre o middleware no kernel:

```php
<?php
protected $routeMiddleware = [
    'role' => \App\Http\Middleware\RoleMiddleware::class,
];
```

5. Configurar Middleware para Identificar Tenant

Certifique-se de que o middleware de identificação de tenant já está configurado (como mostrado anteriormente).

6. Adicionar Papéis e Permissões

No AppServiceProvider ou em um seeder, configure os papéis e permissões iniciais:

```php
<?php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

public function boot()
{
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'user']);

    Permission::create(['name' => 'manage tenants']);
    Permission::create(['name' => 'view resources']);

    $adminRole = Role::findByName('admin');
    $adminRole->givePermissionTo('manage tenants');
}

```

7. Proteger Rotas com Papéis

No arquivo `routes/api.php`, proteja rotas específicas com o middleware de papéis:

```php
<?php
use App\Http\Controllers\AuthController;

Route::middleware(['auth.jwt', 'identify.tenant'])->group(function () {
    Route::get('/admin-resource', function () {
        return response()->json(['message' => 'Recurso protegido para administradores']);
    })->middleware('role:admin');

    Route::get('/user-resource', function () {
        return response()->json(['message' => 'Recurso protegido para usuários']);
    })->middleware('role:user');
});
```

8. Testar

1. Atribuir Papéis: Use o método `$user->assignRole('admin')` ou `$user->assignRole('user')` para atribuir papéis aos usuários.

2. Acesso Protegido: Teste as rotas protegidas com diferentes usuários e papéis.

3. Verificar Tenant: Certifique-se de que o tenant correto está sendo identificado.

Com isso, você terá configurado a autorização baseada em papéis em um sistema Laravel Multi-Tenancy com autenticação JWT.


```
```
```
```
```
```
```
