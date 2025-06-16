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

## Registro de Usuário.

Como posso criar um usuário para autenticação em um sistema Laravel Multi-Tenancy com autenticação JWT?

### Para criar um usuário para autenticação em um sistema Laravel Multi-Tenancy com autenticação JWT, siga os passos abaixo:

1. Crie a migration para adicionar os novos campos à tabela de usuários:
```shell
    php artisan make:migration AddColumnsAtToUsersTable
```

com isso será criado um arquivo como `ano_mes_dia_horaminutosegundo_add_columns_at_to_users_table.php`

2. Adicione ao arquivo o conteúdo abaixo:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cpf', 15)->nullable(false)->after('name')->unique();
            $table->string('phone', 20)->nullable(false)->after('cpf');
            $table->tinyInteger('status')->nullable(false)->default(1); // 1 = Ativo, 0 = Inativo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cpf', 'phone', 'status']); // Remove as colunas adicionadas
        });
    }
};
```

3. Execute a migration:

```shell
    php artisan migrate --path=database/migration/ano_mes_dia_horaminutosegundo_add_columns_at_to_users_table.php
```

4. Crie uma controller para criar a funcionalidade de registro de usuário:

```shell
php artisan make:controller RegisterController
```

Na controlle, implemente a lógica de registro de usuário:

```php
<?php
namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Traits\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use Log;

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:users', // CPF validation with mask
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20', // Phone validation
            'password' => 'required|string|min:8|confirmed',
        ];

        $messages = [
            'name.required' => __('O campo nome é obrigatório.'),
            'cpf.required' => __('O campo CPF é obrigatório.'),
            'cpf.unique' => __('Já existe um usuário registrado com este CPF.'),
            'email.required' => __('O campo email é obrigatório.'),
            'email.email' => __('O campo email deve ser um endereço de email válido.'),
            'email.unique' => __('Já existe um usuário registrado com este email.'),
            'phone.required' => __('O campo telefone é obrigatório.'),
            'password.required' => __('O campo senha é obrigatório.'),
            'password.min' => __('A senha deve ter pelo menos 8 caracteres.'),
            'password.confirmed' => __('As senhas não conferem.'),
        ];

        return Validator::make($data, $rules, $messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  Request $request
     * @return \App\Models\Users
     */
    public function create(Request $request)
    {
        try {
            $response = [
                'success' => false,
                'data' => [],
                'messages' => []
            ];
            $data = array_map('trim', $request->all());
            $validator = $this->validator($data);
            if ($validator->fails()) {
                foreach ($validator->errors()->getMessages() as $message) {
                    $response['messages'] = [
                        'message' => $message[0],
                    ];
                }

                return response()->json($response, 422);
            }
    
            $isRegistered = Usuario::isRegistered($data['cpf']);
            if ($isRegistered) {
                $response['messages'] = [
                    'message' => __('Já existe um usuário registrado com este CPF. Faça login para continuar.'),
                ];

                return response()->json($response, 200);
            }
    
            $user = new Usuario();
            foreach ($data as $column => $value) {
                if ($column !== 'password_confirmation') {
                    $user->{$column} = $value;
                }
            }
    
            $user->email_verified_at = null;
            $user->remember_token = null;
            $user->status = 'ativo'; // Default status
            $user->created_at = Carbon::now();
            $user->updated_at = null;
            $user->deleted_at = null;
            $user->save();
            if (! $user->id) {
                $response['messages'] = [
                    'message' => __('Ocorreu um erro ao salvar o usuário.'),
                ];

                return response()->json($response, 200);
            }

            $response = [
                'success' => true,
                'data' => ['id' => $user->id],
                'messages' => ['message' => __('Usuário registrado com sucesso.')],
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            Log::save('error', $e);

            $response['messages'] = [
                'message' => __('Ops! Ocorreu um erro ao executar esta ação.'),
            ];

            return response()->json($response, 200);
        }
    }
}
```

5. Adiciona na model Usuário a função `isRegistered` para verificar se o usuário ja foi registrado

```php

<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Usuario extends Authenticatable implements JWTSubject
{
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
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
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

```

6. Agora só falta criar a rota para a criação do usuário. Adicione as rotas de autenticação no arquivo routes/api.php:

```php
<?php
use App\Http\Controllers\AuthController;

Route::post('register', [RegisterController::class, 'create']);
```

7. Testar
    1. Abra o client de requisições de API como Postman. Insomnia ou outro de sua preferência.
    2. Configure a url da requisição como.
    ```
    http://localhost:8000/api/auth/register
    ```

    3. Adicione o cabeçalho (header) da requisição
    ```
    Accept:application/json
    Content-Type:application/json
    ```

    4. No corpo (body) da requisição adicione as informações do usuário no formato json:
    ```json
    {
        "name": "Adele Vance",
        "cpf": "479.222.980-49",
        "email": "adele.vence@gmail.com",
        "phone": "(11) 99876-5432",
        "password": "Abc@1234",
        "password_confirmation": "Abc@1234"
    }
    ```

    5. Executando através do método POST, a resposta da requisição deverá ser:
    ```json
    {
        "success": true,
        "data": {
            "id": 4
        },
        "messages": {
            "message": "Usuário registrado com sucesso."
        }
    }
    ```

Com isso, você terá criado um usuário para um sistema Laravel Multi-Tenancy com autenticação JWT.

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

## Atribuir permissões específicas a diferentes papéis de usuários

Como posso atribuir permissões específicas a diferentes papéis de usuário em um sistema Laravel Multi-Tenancy com autenticação JWT?

### Para atribuir permissões específicas a diferentes papéis de usuário em um sistema Laravel Multi-Tenancy com autenticação JWT, siga os passos abaixo:

1. Instalar Pacote de Controle de Papéis e Permissões

Certifique-se de que o pacote `spatie/laravel-permission` está instalado e configurado. Caso ainda não tenha feito isso, instale o pacote:

```shell
composer require spatie/laravel-permission
```

Publique o arquivo de configuração e migre as tabelas:

```shell
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

2. Configurar o Modelo de Usuário.

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

3. Criar Papéis e Permissões.

Você pode criar papéis e permissões diretamente no código ou usando seeders. Por exemplo: Usando o `AppServiceProvider`:

```php
<?php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

public function boot()
{
    // Criar permissões
    Permission::create(['name' => 'manage tenants']);
    Permission::create(['name' => 'view resources']);
    Permission::create(['name' => 'edit resources']);

    // Criar papéis
    $adminRole = Role::create(['name' => 'admin']);
    $userRole = Role::create(['name' => 'user']);

    // Atribuir permissões aos papéis
    $adminRole->givePermissionTo(['manage tenants', 'view resources', 'edit resources']);
    $userRole->givePermissionTo(['view resources']);
}
```

Usando Seeders:

Crie um seeder para gerenciar papéis e permissões:

```shell
php artisan make:seeder RolesAndPermissionsSeeder
```

No arquivo gerado (`database/seeders/RolesAndPermissionsSeeder.php`), configure os papéis e permissões:

```php
?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Criar permissões
        Permission::create(['name' => 'manage tenants']);
        Permission::create(['name' => 'view resources']);
        Permission::create(['name' => 'edit resources']);

        // Criar papéis
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // Atribuir permissões aos papéis
        $adminRole->givePermissionTo(['manage tenants', 'view resources', 'edit resources']);
        $userRole->givePermissionTo(['view resources']);
    }
}
```

Execute o seeder:

```shell
php artisan db:seed --class=RolesAndPermissionsSeeder
```

4. Atribuir Papéis a Usuários

Você pode atribuir papéis a usuários diretamente no código ou via seeder. Por exemplo:

```php
<?php
use App\Models\Usuario;

$user = Usuario::find(1); // Encontre o usuário
$user->assignRole('admin'); // Atribuir papel de administrador
```

5. Proteger Rotas com Permissões
Use o middleware permission fornecido pelo pacote para proteger rotas específicas com base em permissões:

```php
<?php
use App\Http\Controllers\SomeController;

Route::middleware(['auth.jwt', 'identify.tenant'])->group(function () {
    Route::get('/manage-tenants', [SomeController::class, 'manageTenants'])
        ->middleware('permission:manage tenants');

    Route::get('/view-resources', [SomeController::class, 'viewResources'])
        ->middleware('permission:view resources');

    Route::post('/edit-resources', [SomeController::class, 'editResources'])
        ->middleware('permission:edit resources');
});
```

6. Testar

1. Atribuir Papéis e Permissões: Certifique-se de que os usuários têm os papéis e permissões corretos.
2. Acesso Protegido: Teste as rotas protegidas com diferentes usuários e permissões.
3. Verificar Tenant: Certifique-se de que o tenant correto está sendo identificado.

Com isso, você terá configurado permissões específicas para diferentes papéis em um sistema Laravel Multi-Tenancy com autenticação JWT.

## Verificar se o usuário possui determinada permissão

Como posso verificar se um usuário possui uma determinada permissão em um sistema Laravel Multi-Tenancy com autenticação JWT?

### Para verificar se um usuário possui uma determinada permissão em um sistema Laravel Multi-Tenancy com autenticação JWT, você pode usar os métodos fornecidos pelo pacote `spatie/laravel-permission`. Aqui está o passo a passo:

1. Certifique-se de que o Trait `HasRoles` Está no Modelo de Usuário
O modelo de usuário deve usar o trait `HasRoles` para habilitar o gerenciamento de papéis e permissões:

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

2. Verificar Permissão no Código
Você pode verificar se o usuário autenticado possui uma permissão específica usando o método `hasPermissionTo()` ou `can()`.

Exemplo com `hasPermissionTo()`:

```php
?php
use Illuminate\Support\Facades\Auth;

$user = Auth::user(); // Obter o usuário autenticado

if ($user->hasPermissionTo('manage tenants')) {
    return response()->json(['message' => 'Usuário tem permissão para gerenciar tenants.']);
} else {
    return response()->json(['message' => 'Usuário não tem permissão para gerenciar tenants.'], 403);
}
```

Mais um Exemplo:

```php
<?php
use Illuminate\Support\Facades\Auth;

// Obtenha o usuário autenticado
$user = Auth::user();

// Verifique se o usuário possui a permissão "create-posts"
if ($user->hasPermissionTo('create-posts')) {
    return response()->json(['message' => 'O usuário possui a permissão de criar posts.']);
} else {
    return response()->json(['message' => 'O usuário não possui a permissão de criar posts.']);
}
```

Explicação:

1. `Auth::user()`: Obtém o usuário autenticado.
2. `hasPermissionTo('nome_da_permissao')`: Verifica se o usuário possui a permissão especificada.

```
```

```
```

```
```

```
```

```
```

```
```

```
```
