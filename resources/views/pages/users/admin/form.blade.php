<div class="row g-3">

    <div class="col-md-8">

        <div class="row g-2">

            <input type="hidden" name="old_image" value="{{ $staff->image ?? '' }}">

            <x-input col="6" name="first_name" :value="$staff->first_name ?? ''" :required="true" />
            <x-input col="6" name="last_name" :value="$staff->last_name ?? ''" :required="true" />

            <x-input col="12" name="email" type="email" :value="$staff->email ?? ''" :required="true" />



            <x-input col="6" name="password" type="password" />

            <x-input col="6" name="confirm_password" type="password" />

            <x-input col="12" title="roles" name="roles[]" type="select" class="select" :required="true"
                :multiple="true">
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}"
                        {{ isset($staffRole) && in_array($role->id, $staffRole) ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </x-input>
        </div>
    </div>
    <div class="col-md-4">
        <div class="row">
            <x-input name="image" type="dropify" :defaultFile="$staff->image ?? ''" dropifyHeight="245" />
        </div>
    </div>

</div>
