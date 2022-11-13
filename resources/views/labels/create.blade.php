@extends('layouts.app')
@section('title', 'Create label')

@section('content')
<div class="container">
    <h1>Create label</h1>
    <div class="mb-4">
        {{-- TODO: Link --}}
        <a href="{{ route('items.index') }}"><i class="fas fa-long-arrow-alt-left"></i> Back to the homepage</a>
    </div>

    {{-- TODO: Session flashes --}}
    @if (Session::has('label_created'))
        <div class="alert alert-success" role="alert">
            Label ({{ Session::get('label_created') }}) successfully created!
        </div>
    @endif

    {{-- TODO: action, method --}}
    <form action="{{ route('labels.store') }}" method="POST">
        @csrf
        <div class="form-group row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Name*</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('name') is-invalid @enderror"  id="name" name="name" value=" {{ old('name') }}">
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
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
                            value="{{ old('color') }}"
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
