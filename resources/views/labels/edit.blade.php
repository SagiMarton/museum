@extends('layouts.app')
@section('title', 'Edit label: ' . $label->name)

@section('content')
<div class="container">
    <h1>Edit label</h1>
    <div class="mb-4">
        {{-- TODO: Link --}}
        <a href="{{ route('items.index') }}"><i class="fas fa-long-arrow-alt-left"></i> Back to the homepage</a>
    </div>

    {{-- TODO: Session flashes --}}
    @if (Session::has('label_updated'))
        <div class="alert alert-success" role="alert">
            Label ({{ Session::get('label_updated') }}) successfully updated!
        </div>
    @endif

    {{-- TODO: action, method --}}
    <form action="{{ route('labels.update',$label) }}" method="POST">
        @method('PUT')
        @csrf
        <div class="form-group row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Name*</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('name') is-invalid @enderror"  id="name" name="name" value=" {{ old('name',$label->name) }}">
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div class="form-check">
                {{-- TODO: Checked --}}
                <input type="checkbox" class="hidden" value="1"@checked(old('hidden') === 1 || !$label->display) id="hidden" name="hidden">
                <label for="hidden" class="form-check-label">Hidden</label>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="color" class="col-sm-2 col-form-label py-0">Color*</label>
            <div class="col-sm-10">
                    <div class="form-check">
                        <input
                            class="form-control form-control-color @error('color') is-invalid @enderror"
                            type="color"
                            name="color"
                            id="color"
                            value="{{ old('color',rtrim($label->color,'ff')) }}"
                            {{-- TODO: checked --}}
                        >
                        @error('color')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                {{-- TODO: Error handling --}}

            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Store</button>
        </div>

    </form>
</div>
@endsection
