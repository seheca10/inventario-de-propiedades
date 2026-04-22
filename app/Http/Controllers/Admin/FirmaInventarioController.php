<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FirmaInventario;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class FirmaInventarioController extends Controller
{
    // ─── Guardar firmas ───────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'inventario_id'           => 'required|exists:inventarios,id',
            'tipo_firma_arrendatario' => 'required|in:digital,imagen',
            'tipo_firma_arrendador'   => 'required|in:digital,imagen',
        ]);

        $firmaArrendatario = $this->procesarFirma(
            $request,
            'arrendatario',
            $request->tipo_firma_arrendatario
        );

        $firmaArrendador = $this->procesarFirma(
            $request,
            'arrendador',
            $request->tipo_firma_arrendador
        );

        FirmaInventario::updateOrCreate(
            ['inventario_id' => $request->inventario_id],
            [
                'firma_arrendatario'      => $firmaArrendatario,
                'firma_arrendador'        => $firmaArrendador,
                'tipo_firma_arrendatario' => $request->tipo_firma_arrendatario,
                'tipo_firma_arrendador'   => $request->tipo_firma_arrendador,
            ]
        );

        Inventario::findOrFail($request->inventario_id)->update(['estado' => 1]);

        return redirect()->route('inventarios.index')
            ->with('success', 'El inventario ha sido firmado correctamente.');
    }

    /**
     * Procesa una firma y devuelve siempre base64 puro (sin prefijo data:image).
     * — Si es digital: limpia el prefijo data:image/png;base64, del canvas
     * — Si es imagen:  lee el archivo subido y lo convierte a base64
     *
     * @return string|null  Base64 puro listo para usar en <img src="data:...">
     */
    private function procesarFirma(Request $request, string $parte, string $tipo): ?string
    {
        if ($tipo === 'digital') {
            $raw = $request->input("firma_{$parte}");

            if (empty($raw)) {
                return null;
            }

            // Quitar el prefijo data:image/png;base64, si viene del canvas
            $base64 = preg_replace('/^data:image\/\w+;base64,/', '', $raw);
            $base64 = str_replace(' ', '+', $base64);

            // Validar que sea base64 válido
            if (base64_decode($base64, true) === false) {
                return null;
            }

            return $base64; // guardamos base64 puro en BD
        }

        // Tipo imagen: convertir a base64 para consistencia
        $fileKey = "imagen_firma_{$parte}";
        if ($request->hasFile($fileKey)) {
            $file     = $request->file($fileKey);
            $contents = file_get_contents($file->getRealPath());
            return base64_encode($contents);
        }

        return null;
    }

    // ─── Vistas ───────────────────────────────────────────────────────────────
    public function signDocument(Inventario $inventario)
    {
        return view('inventarios.sign-document', compact('inventario'));
    }

    public function viewSignedDocument(Inventario $inventario)
    {
        return view('inventarios.view-document', compact('inventario'));
    }

    // ─── Exportar PDF ─────────────────────────────────────────────────────────
    public function exportToPdf(Inventario $inventario)
    {
        $pdf = Pdf::loadView('inventarios.download-file', compact('inventario'))
            ->setPaper('a4', 'portrait');

        $filename = sprintf(
            '%s-%s-inventario.pdf',
            str_replace(' ', '-', strtolower($inventario->arrendatario)),
            Carbon::today()->format('Y-m-d')
        );

        return $pdf->download($filename);
    }
}