@foreach ($categories as $key => $category)
    <tr  @if($category->children->isNotEmpty()) style="background-color: #d0e9ff; border: 1px solid #fff" @endif>
        <td style="width: 221px;"
            @if ($category->children->isNotEmpty())
             data-bs-toggle="collapse"
            data-bs-target="#collapse{{ $category->id }}"
            class="clickable dropdown-toggle"
            @endif >
        </td>
        <td style="width: 165px">{{ $key + 1 }}</td>
        <td>{{ $category->name }}</td>
        <td style="width: 374px;">
            <x-actions
                :editRoute="route('catalog.category.edit', $category->id)"
                :deleteRoute="route('catalog.category.destroy', $category->id)"
            >
            </x-actions>

        </td>
    </tr>

    @if ($category->children->isNotEmpty())
        <tr>
            <td colspan="4" class="p-0 m-0">
                <div id="collapse{{ $category->id }}" class="collapse">
                    <table class="table">
                        <tbody>
                        <x-category-tree :categories="$category->children" :level="$level + 1" />
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
    @endif
@endforeach
