<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\CompitionLeadBoard;
use Illuminate\Http\Request;

class CompitionLeadBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $compitionleadboard = CompitionLeadBoard::where('score', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $compitionleadboard = CompitionLeadBoard::latest()->paginate($perPage);
        }

        return view('admin.compition-lead-board.index', compact('compitionleadboard'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.compition-lead-board.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
        $requestData = $request->all();
        
        CompitionLeadBoard::create($requestData);

        return redirect('admin/compition-lead-board')->with('flash_message', 'CompitionLeadBoard added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $compitionleadboard = CompitionLeadBoard::findOrFail($id);

        return view('admin.compition-lead-board.show', compact('compitionleadboard'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $compitionleadboard = CompitionLeadBoard::findOrFail($id);

        return view('admin.compition-lead-board.edit', compact('compitionleadboard'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        
        $requestData = $request->all();
        
        $compitionleadboard = CompitionLeadBoard::findOrFail($id);
        $compitionleadboard->update($requestData);

        return redirect('admin/compition-lead-board')->with('flash_message', 'CompitionLeadBoard updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        CompitionLeadBoard::destroy($id);

        return redirect('admin/compition-lead-board')->with('flash_message', 'CompitionLeadBoard deleted!');
    }
}
