<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = Tenant::findByDomain($request->getHost()); // Este método encontrará a empresa com base no domínio do host solicitado.
        
        // Se a empresa não for encontrada, você pode lidar com isso adequadamente, por exemplo, lançar uma exceção ou retornar uma resposta.
        // Exemplo: Se você quiser abortar com um 404 se a empresa não for encontrado.
        $currentTenant = Tenant::current();
        if (! $currentTenant) {
            return response()->json(
                [
                    'error' => __('Tenant not found')
                ], 401
            );
        }

        // Se a empresa for encontrada, ela será definida como a empresa atual para a request.
        // Você também pode registrar a identificação da empresa ou executar qualquer outra ação necessária aqui.
        // Opcionalmente, você pode registrar a identificação do inquilino
        Log::info(__('Tenant identified'), ['tenant_id' => $currentTenant->id]);

        // Continuar com a request.
        // A empresa agora é identificada e definida como a empresa atual para a request.
        // Você pode acessar a empresa atual usando Tenant::current() na aplicação.
        // Isso garantirá que a empresa seja identificada antes de prosseguir com a request.

        // Se você quiser realizar quaisquer ações ou verificações adicionais, pode fazê-lo aqui.
        // Por exemplo, você pode verificar se a empresa está ativa ou realizar qualquer outra validação.
        // Se precisar executar alguma ação ou verificação adicional, você pode fazê-lo aqui.
        // Exemplo: Verificar se a empresa está ativa
        if (! $currentTenant->isActive()) {
            abort(403, __('Tenant is not active'));
        }

        return $next($request);
    }
}
