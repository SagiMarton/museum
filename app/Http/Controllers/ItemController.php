<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('items.index',[
            'users_count' => User::count(),
            'items' => Item::all(),
            'labels' => Label::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('items.create',['labels' => Label::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => 'required|min:3',
                'description' => 'required|min:10',
                'labels' => 'nullable|array',
                'categories.*' => 'numeric|integer|exists:labels,id|',
                'cover_image' => 'nullable|file|image|max:4096'
            ],
            [
                 'name.required' => 'Name is required',
            ]
        );

        $fn = null;

        if($request->hasFile('cover_image'))
        {
            $file = $request->file('cover_image');
            $fn = 'ci_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->put($fn, $file->get());
        }

        $item = Item::factory()->create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'obtained' => now()->format('Y-m-d'),
            'image' => $fn,
        ]);

        if (isset($validated['labels']))
        {
            $item->labels()->sync($validated['labels']);
        }

        Session::flash("item_created", $validated['name']);

        return Redirect::route('items.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return view('items.show', ['item' => $item]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        //
    }
}
