@props(['id', 'route' => null, 'title' => 'Title', 'show' => false, 'maxWidth' => '2xl'])


<div id="{{ $id }}" {!! $attributes->merge(['class' => 'modal fade']) !!} tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <x-form :route="$route" :button="false">
                <div class="modal-body">
                    <div class="row g-2">
                        {{ $slot }}
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </x-form>
        </div>
    </div>
</div>
