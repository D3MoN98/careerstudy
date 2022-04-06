<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollegeStream;
use Illuminate\Http\Request;

class CollegeStreamController extends Controller
{
    /**
      * Display a listing of the resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function index()
     {
         $college_streams = CollegeStream::all();
         return view('backend.college-streams.index', compact('college_streams'));
     }
 
     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create()
     {
         return view('backend.college-streams.create');
     }
 
     /**
      * Store a newly created resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
     public function store(Request $request)
     {
         $college_stream = $request->all();
         CollegeStream::create($college_stream);
 
         return redirect()->route('admin.college_stream.index')->withFlashSuccess(__('alerts.backend.general.created'));
     }
 
     /**
      * Display the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function show($id)
     {
         $college_stream = CollegeStream::find($id);
         return view('backend.college-streams.show', compact('college_stream'));
     }
 
     /**
      * Show the form for editing the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function edit($id)
     {
         $college_stream = CollegeStream::find($id);
         return view('backend.college-streams.edit', compact('college_stream'));
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
         $college_stream = $request->all();
         CollegeStream::find($id)->update($college_stream);
 
         return redirect()->route('admin.college_stream.index')->withFlashSuccess(__('alerts.backend.general.updated'));
     }
 
     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function destroy($id)
     {
         CollegeStream::find($id)->delete();
 
         return redirect()->route('admin.college_stream.index')->withFlashSuccess(__('alerts.backend.general.deleted'));
 
     }

    /**
     * Get list of college streams by college id.
     * @param Request $request
     * @return json
     */
    public function getCollegeStreamByCollegeId(Request $request)
    {
        $college_streams = CollegeStream::where('college_id', $request->college_id)->orWhere('college_id', null)->get();

        return response()->json(['success' => true, 'data' => $college_streams]);
    }
 }
 