@extends('layouts.app')
@section('title', 'Label: ' . $label->name)

@section('content')
<div class="container">

    @if (Session::has('label_created'))
        <div class="alert alert-success" role="alert">
            Label ({{ Session::get('label_created') }}) successfully created!
        </div>
    @endif
    @if (Session::has('label_updated'))
        <div class="alert alert-success" role="alert">
            Label ({{ Session::get('label_updated') }}) successfully updated!
        </div>
    @endif



    <div class="row justify-content-between">
        <div class="col-12 col-md-8">
            <h1>Items for <span class="badge" style="background-color: {{$label->color}}">{{ $label->name }}</span></h1>
        </div>

        <div class="col-12 col-md-4">
            <div class="float-lg-end">
                {{-- TODO: Links, policy --}}
                @can('update',$label)
                <a href="{{ route('labels.edit',$label)}}" role="button" class="btn btn-sm btn-primary">
                    <i class="far fa-edit"></i> Edit label
                </a>
                @endcan

                @can('delete',$label)
                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#delete-confirm-modal">
                    <i class="far fa-trash-alt"></i> Delete label
                </button>
                @endcan

            </div>
        </div>
        <a href="{{ route('items.index') }}"><i class="fas fa-long-arrow-alt-left"></i> Back to the homepage</a>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="delete-confirm-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Confirm delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- TODO: name --}}
                    Are you sure you want to delete label <strong>{{ $label->name }}</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button
                        type="button"
                        class="btn btn-danger"
                        onclick="document.getElementById('delete-label-form').submit();"
                    >
                        Yes, delete this label
                    </button>

                    {{-- TODO: Route, directives --}}
                    <form id="delete-label-form" action="{{ route('labels.destroy',$label)}}" method="POST" class="d-none">
                        @method('DELETE')
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- TODO: Session flashes --}}

    <div class="row mt-3">
        <div class="col-12 col-lg-9">
            <div class="row">
                {{-- TODO: Read posts from DB --}}

                @forelse ($label->items->sortByDesc('obtained')->sortBy('name') as $item)
                    <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex align-self-stretch">
                        <div class="card w-100">
                            <img
                                src="{{
                                asset($item->image
                                    ? 'storage/' . $item->image
                                    : 'images/default_item_cover.webp')
                                }}"
                                class="card-img-top"
                                alt="Post cover"
                            >
                            <div class="card-body">
                                {{-- TODO: Title --}}
                                <h5 class="card-title mb-0">{{ $item->name }}</h5>
                                <p class="small mb-0">
                                    <span class="me-2">
                                        <i class="fa fa-comment"></i>
                                        {{-- TODO: Author --}}
                                        <span>Comments: {{ $item->comments->count()}}</span>
                                    </span>

                                    <span>
                                        <i class="far fa-calendar-alt"></i>
                                        {{-- TODO: Date --}}
                                        <span>Obtained:{{ $item->obtained }}</span>
                                    </span>
                                </p>

                                {{-- TODO: Read post categories from DB --}}
                                @foreach ($item->labels as $label)
                                    @if ($label->display || (Auth::check() && Auth::user()->is_admin))
                                        <a href="{{ route('labels.show', $label) }}" class="text-decoration-none">
                                        {{--<span class="badge bg-{{ $category }}">{{ $category }}</span>--}}
                                        <span class="badge" style="background-color: {{$label->color}}">{{ $label->name }}</span>
                                        </a>
                                    @endif
                                @endforeach

                                {{-- TODO: Short desc --}}
                                <p class="card-text mt-1">{{ Str::words($item->description,5,'...')}}</p>
                            </div>
                            <div class="card-footer">
                                {{-- TODO: Link --}}
                                <a href="{{ route('items.show', $item) }}" class="btn btn-primary">
                                    <span>View item</span> <i class="fas fa-angle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning" role="alert">
                            No items with label {{ $label->name }} on display! Check back later!
                        </div>
                    </div>
                @endforelse
            </div>


        </div>

    </div>
</div>
@endsection
