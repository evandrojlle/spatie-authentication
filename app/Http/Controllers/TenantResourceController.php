<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;

class TenantResourceController extends Controller
{
    public function index()
    {
        // Retrieve the tenant from the request context
        $tenant = Tenant::current();
        if (! $tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        // Return the tenant's resources or data
        return response()->json([
            'tenant_id' => $tenant->id,
            'name' => $tenant->name,
            'resources' => $tenant->resources, // Assuming a relationship exists
        ]);
    }
}
