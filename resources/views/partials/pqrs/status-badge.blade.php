@php
    $statusMap = [
        'created'         => ['class' => $ticket->status_color, 'icon' => 'fa-plus-circle'],
        'validated'       => ['class' => $ticket->status_color, 'icon' => 'fa-check-circle'],
        'assigned_pending_accept'       => ['class' => $ticket->status_color, 'icon' => 'fa-check-clock'],
        'assigned'        => ['class' => $ticket->status_color, 'icon' => 'fa-user-check'],
        'visit_scheduled' => ['class' => $ticket->status_color, 'icon' => 'fa-calendar-alt'],
        'visit_scheduled_confirmed' => ['class' => $ticket->status_color, 'icon' => 'fa-check-double'],
        'diagnosed'       => ['class' => $ticket->status_color, 'icon' => 'fa-clipboard-check'],
        'quoted_pending'  => ['class' => $ticket->status_color, 'icon' => 'fa-clipboard-clock'],
        'quoted'          => ['class' => $ticket->status_color, 'icon' => 'fa-file-invoice-dollar'],
        'approved'        => ['class' => $ticket->status_color, 'icon' => 'fa-thumbs-up'],
        'rejected'        => ['class' => $ticket->status_color, 'icon' => 'fa-times-circle'],
        'work_scheduled'  => ['class' => $ticket->status_color, 'icon' => 'fa-tools'],
        'work_scheduled_confirmed' => ['class' => $ticket->status_color, 'icon' => 'fa-check-double'],
        'in_progress'     => ['class' => $ticket->status_color, 'icon' => 'fa-hammer'],
        'finished'        => ['class' => $ticket->status_color, 'icon' => 'fa-check-double'],
        'closed'          => ['class' => $ticket->status_color, 'icon' => 'fa-archive'],
    ];

    $badge = $statusMap[$ticket->status] ?? ['class' => 'badge-secondary', 'icon' => 'fa-question-circle'];
@endphp

<span class="badge {{ $badge['class'] }} px-2 py-1">
    <i class="fas {{ $badge['icon'] }} mr-1"></i>
    {{ $ticket->status_label }}
</span>