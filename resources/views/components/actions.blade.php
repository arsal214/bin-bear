@props([
    'showRoute' => null,
    'editRoute' => null,
    'deleteRoute' => null,
    'canDelete' => null,
    'canEdit' => null,
    'canShow' => null,
])

<div class="d-inline-flex">
    <div class="dropdown">
        <a href="#" class="text-body" data-bs-toggle="dropdown">
            <i class="ph-list"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-end">

            {{ $slot }}

            @isset($showRoute)
                @if(is_null($canShow) || auth()->user()->can($canShow))
                <a href="{{ $showRoute }}" class="dropdown-item">
                    <i class="ph-eye me-2"></i> Show
                </a>
                @endif
            @endisset

            @isset($editRoute)
                @if(is_null($canEdit) || auth()->user()->can($canEdit))
                <a href="{{ $editRoute }}" class="dropdown-item">
                    <i class="ph-note-pencil me-2"></i> Edit
                </a>
                @endif
            @endisset

            @isset($deleteRoute)
                @if(is_null($canDelete) || auth()->user()->can($canDelete))
                <form action="{{ $deleteRoute }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="dropdown-item sa-confirm">
                        <i class="ph-trash me-2"></i> Delete
                    </button>
                </form>
                @endif
            @endisset
        </div>
    </div>
</div>
