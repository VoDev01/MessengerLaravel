@error($field)
    <span {{ $attributes->merge(['class' => "text-danger error mb-2"]) }}>{{ $message }}</span>
@enderror