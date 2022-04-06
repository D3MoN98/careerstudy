<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgrammeClass;
use Illuminate\Http\Request;

class ProgrammeClassController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programme_classes = ProgrammeClass::all();
        return view('backend.programme-classes.index', compact('programme_classes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.programme-classes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $programme_class = $request->all();
        ProgrammeClass::create($programme_class);

        return redirect()->route('admin.programme_class.index')->withFlashSuccess(__('alerts.backend.general.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $programme_class = ProgrammeClass::find($id);
        return view('backend.programme-classes.show', compact('programme_class'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $programme_class = ProgrammeClass::find($id);
        return view('backend.programme-classes.edit', compact('programme_class'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $programme_class = $request->all();
        ProgrammeClass::find($id)->update($programme_class);

        return redirect()->route('admin.programme_class.index')->withFlashSuccess(__('alerts.backend.general.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ProgrammeClass::find($id)->delete();

        return redirect()->route('admin.programme_class.index')->withFlashSuccess(__('alerts.backend.general.deleted'));

    }
}
