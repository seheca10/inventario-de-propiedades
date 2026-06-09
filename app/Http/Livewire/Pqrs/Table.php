<?php

namespace App\Http\Livewire\Pqrs;

use Livewire\Component;
use App\Models\PqrsTicket;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    public string $search    = '';
    public string $status    = '';
    public string $priority  = '';
    public string $sortField = 'created_at';
    public string $sortDir   = 'desc';

    // Resetear paginación al filtrar
    public function updatingSearch()   { $this->resetPage(); }
    public function updatingStatus()   { $this->resetPage(); }
    public function updatingPriority() { $this->resetPage(); }

    public function sortBy(string $field): void
    {
        $this->sortDir   = ($this->sortField === $field && $this->sortDir === 'asc') ? 'desc' : 'asc';
        $this->sortField = $field;
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'status', 'priority']);
        $this->resetPage();
    }

    public function render()
    {
        $query = PqrsTicket::query()
            ->when($this->search, fn($q) =>
                $q->where('ticket_number', 'like', "%{$this->search}%")
                  ->orWhere('tenant_name',   'like', "%{$this->search}%")
                  ->orWhere('contract_number','like', "%{$this->search}%")
            )
            ->when($this->status,   fn($q) => $q->where('status',   $this->status))
            ->when($this->priority, fn($q) => $q->where('priority', $this->priority))
            ->orderBy($this->sortField, $this->sortDir);

        // Conteos para el semáforo
        $counts = [
            'critical'       => PqrsTicket::where('priority', 'critical')->whereNotIn('status', ['closed','finished'])->count(),
            'high'           => PqrsTicket::where('priority', 'high')->whereNotIn('status', ['closed','finished'])->count(),
            'pending_action' => PqrsTicket::whereIn('status', ['created','validated'])->count(),
            'in_progress'    => PqrsTicket::whereIn('status', ['assigned','visit_scheduled','diagnosed','quoted','approved','work_scheduled','in_progress'])->count(),
            'finished'       => PqrsTicket::whereIn('status', ['finished','closed'])->count(),
            'total'          => PqrsTicket::count(),
        ];

        // Conteos por estado para las tarjetas de estado
        $byStatus = PqrsTicket::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return view('livewire.pqrs.table', [
            'tickets'  => $query->paginate(10),
            'counts'   => $counts,
            'byStatus' => $byStatus,
        ]);
    }
}