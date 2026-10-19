@if ($fine->status === 'active')
    @can('fines.pay')
        <form method="POST" action="{{ route('fines.pay', $fine) }}" class="d-inline" data-confirm="Отметить штраф как оплаченный?">@csrf @method('PATCH')
            <button class="btn btn-sm btn-light text-success" title="Оплачен"><i class="bi bi-check2-circle"></i></button></form>
    @endcan
    @can('fines.manage')
        <a href="{{ route('fines.edit', $fine) }}" class="btn btn-sm btn-light" title="Изменить"><i class="bi bi-pencil"></i></a>
        <form method="POST" action="{{ route('fines.cancel', $fine) }}" class="d-inline" data-confirm="Отменить штраф?">@csrf @method('PATCH')
            <button class="btn btn-sm btn-light text-secondary" title="Отменить"><i class="bi bi-x-circle"></i></button></form>
    @endcan
@endif
@can('fines.manage')
    <form method="POST" action="{{ route('fines.destroy', $fine) }}" class="d-inline" data-confirm="Удалить запись о штрафе?">@csrf @method('DELETE')
        <button class="btn btn-sm btn-light text-danger" title="Удалить"><i class="bi bi-trash"></i></button></form>
@endcan
