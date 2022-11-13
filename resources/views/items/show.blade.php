@extends('layouts.app')
{{-- TODO: Post title --}}
@section('title', 'View item: ' . $item->name)

@section('content')
<div class="container">

    {{-- TODO: Session flashes --}}

    @if (Session::has('comment_created'))
        <div class="alert alert-success" role="alert">
            Comment successfully created!
        </div>
    @endif
    @if (Session::has('item_created'))
        <div class="alert alert-success" role="alert">
            Item ({{ Session::get('item_created') }}) successfully created!
        </div>
    @endif
    @if (Session::has('item_updated'))
        <div class="alert alert-success" role="alert">
            Item ({{ Session::get('item_updated') }}) successfully updated!
        </div>
    @endif

    <div class="row justify-content-between">
        <div class="col-12 col-md-8">
            {{-- TODO: Title --}}
            <h1> {{ $item->name}}  </h1>


            <p class="small text-secondary mb-0">
                <i class="far fa-calendar-alt"></i>
                {{-- TODO: Date --}}
                <span>{{ $item->obtained }}</span>
            </p>

            <div class="mb-2">
                {{-- TODO: Read post categories from DB --}}
                @foreach ($item->labels as $label)
                    @if ($label->display)
                        <a href="{{ route('labels.show', $label) }}" class="text-decoration-none">
                        {{--<span class="badge bg-{{ $category }}">{{ $category }}</span>--}}
                            <span class="badge" style="background-color: {{$label->color}}">{{ $label->name }}</span>
                        </a>
                    @endif
                @endforeach
            </div>

            {{-- TODO: Link --}}
            <a href="{{ route('items.index') }}"><i class="fas fa-long-arrow-alt-left"></i> Back to the homepage</a>

        </div>

        <div class="col-12 col-md-4">
            <div class="float-lg-end">

                {{-- TODO: Links, policy --}}
                @can('update',$item)
                    <a role="button" class="btn btn-sm btn-primary" href="{{ route('items.edit',$item)}}"><i class="far fa-edit"></i> Edit item</a>
                @endcan
                @can('delete', $item)
                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#delete-confirm-modal"><i class="far fa-trash-alt">
                        <span></i> Delete item</span>
                    </button>
                @endcan
            </div>
        </div>
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
                    {{-- TODO: Title --}}
                    Are you sure you want to delete item <strong>{{ $item->name }}</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button
                        type="button"
                        class="btn btn-danger"
                        onclick="document.getElementById('delete-item-form').submit();"
                    >
                        Yes, delete this item
                    </button>

                    {{-- TODO: Route, directives --}}
                    <form id="delete-item-form" action="{{ route('items.destroy',$item)}}" method="POST" class="d-none">
                        @method('DELETE')
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

    <img
        id="cover_preview_image"
        {{-- TODO: Cover --}}
        src="{{
            asset($item->image
                ? 'storage/' . $item->image
                : 'images/default_item_cover.webp')
            }}"
        alt="Cover preview"
        width="350px"
        class="my-3"
    >

    <div class="mt-3">
        {{-- TODO: Post paragraphs --}}
         {!! nl2br(e($item->description))!!}
    </div>
    @can('create',App\Comment::class)
    <form action="{{ route('comments.store') }}" method="POST">
        @csrf
        <input type="hidden" id="item_id" name="item_id" value="{{ $item->id }}">
        <div class="form-group">
            <label for="text" class="col-sm-2 col-form-label h2">New Comment</label>
            <div class="col-sm-10">
                <textarea rows="5" class="form-control @error('text') is-invalid @enderror" id="text" name="text">{{ old('text') }}</textarea>

                @error('text')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <br>
        <div class="row col-sm-10">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Submit</button>
        </div>
    </form>
    @else
    <br>
    <h3>Sign in to comment!</h3>
    @endcan

    <div class="row">
        <div class="col-sm-5 col-md-6 col-12 pb-4">
            <h1>Comments</h1>
            @forelse ($item->comments->sortByDesc('created_at') as $comment)
            <div class="comment">
                <div class="col-12 col-md-4">
                    <div class="float-lg-end">
                        {{-- TODO: Links, policy --}}
                        @can('update',$comment)
                        <a href="{{ route('labels.edit',$label)}}" role="button" class="btn btn-sm btn-primary">
                            <i class="far fa-edit"></i> Edit Comment
                        </a>
                        @endcan

                        @can('delete',$comment)
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#delete-confirm-comment-modal">
                            <i class="far fa-trash-alt"></i> Delete Comment
                        </button>
                        @endcan

                    </div>
                </div>
                <h4>{{ $comment->user->name }}</h4>
                <span>- {{ $comment->created_at }}</span>
                <br>
                <p>{!! nl2br(e($comment->text))!!}</p>
            </div>
            <div class="modal fade" id="delete-confirm-comment-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropComment">Confirm delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            {{-- TODO: Title --}}
                            Are you sure you want to delete this comment?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button
                                type="button"
                                class="btn btn-danger"
                                onclick="document.getElementById('delete-comment-form').submit();"
                            >
                                Yes, delete this comment
                            </button>

                            {{-- TODO: Route, directives --}}
                            <form id="delete-comment-form" action="{{ route('comments.destroy', $comment) }}" method="POST" class="d-none">
                                @method('DELETE')
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            @empty
            <p>There are no comments yet!</p>
            @endforelse
        </div>

    </div>
</div>
@endsection
