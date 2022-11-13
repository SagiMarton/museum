<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('labels.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create',Label::class);
        $validated = $request->validate(
            [
                'name' => 'required|min:3',
                'color' => ['required','regex:/#([0-9]|[a-f]){6}/'],
                'hidden' => 'nullable|boolean'
            ],
            [
                'name.required' => 'Name is required',
            ]
        );

        $display = isset($validated['hidden']);

        $label = Label::factory()->create([
            'name' => $validated['name'],
            'color' => $validated['color'] . 'ff',
            'display' => !$display,
        ]);

        Session::flash("label_created", $validated['name']);

        return Redirect::route('labels.show',$label);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function show(Label $label)
    {
        return view('labels.show',['label' => $label]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function edit(Label $label)
    {
        return view('labels.edit',['label' => $label]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Label $label)
    {
        $this->authorize('update',$label);
        $validated = $request->validate(
            [
                'name' => 'required|min:3',
                'color' => ['required','regex:/#([0-9]|[a-f]){6}/'],
                'hidden' => 'nullable|boolean'
            ],
            [
                'name.required' => 'Name is required',
            ]
        );

        /*$label = Label::factory()->create([
            'name' => $validated['name'],
            'color' => $validated['color'] . 'ff',
            'display' => $validated['hidden'] ? true : false,



        ]);*/
        $display = isset($validated['hidden']);

        $label->name = $validated['name'];
        $label->color = $validated['color'] . 'ff';
        $label->display = !$display;
        $label->save();

        Session::flash("label_updated", $validated['name']);

        return Redirect::route('labels.show',$label);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function destroy(Label $label)
    {
        $this->authorize('delete',$label);

        $label->delete();

        Session::flash("label_deleted", $label->name);

        return Redirect::route('items.index');
    }
}
