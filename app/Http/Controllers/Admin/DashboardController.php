<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Charts\InventoryChart;
use App\Models\Inventario;
use App\Models\User;
use App\Models\AgenteInmobiliario;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function home()
    {
        $mes_actual = Carbon::now()->month;

        $colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#99FF99'];
        $colors1 = [ '#4BC0C0', '#99FF99','#FF6384', '#36A2EB', '#FFCE56'];
        
        $inventarios_apartamentos = Inventario::query()
                                    ->where('tipo_de_propiedad', 'Apartamento')
                                    ->whereMonth('created_at', $mes_actual)
                                    ->count();

        $inventarios_casas = Inventario::where('tipo_de_propiedad', 'Casa')
                            ->whereMonth('created_at', $mes_actual)
                            ->count();

        $chart = new InventoryChart;

        $chart->labels(['Casas', 'Apartamentos']);
        $chart->dataset('Inventarios por tipo de propiedad en el ultimo mes', 'pie', [$inventarios_apartamentos, $inventarios_casas])->options([
            'backgroundColor' => $colors1,
        ]);


        $agent_chart = new InventoryChart;

        $inventario_por_agente = AgenteInmobiliario::whereHas('user.inventarios', function ($query) {
            $query->where('created_at', '>', Carbon::now()->subDays(30));
        })->get();

        $data = [];

        foreach ($inventario_por_agente as $agente) {
            $data[] = [$agente->user->name, $agente->user->inventarios->count()];
        }

        $labels = array_column($data, 0);
        $inventario_cantidades = array_column($data, 1);

        $agent_chart->labels($labels);
        $agent_chart->dataset('Inventarios por asesor', 'bar', $inventario_cantidades)->options([
            'backgroundColor' => $colors,
        ]);

        /* $inventarios = Inventario::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get(); */
        $inventarios = Inventario::where('created_at', '>=', Carbon::now()->startOfWeek())
                         ->where('created_at', '<=', Carbon::now()->endOfWeek())
                         ->get();

        return view('admin.home', compact('chart', 'agent_chart', 'inventarios'));
    }
}
