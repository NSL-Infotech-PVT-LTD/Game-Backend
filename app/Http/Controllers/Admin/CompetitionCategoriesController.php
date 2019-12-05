<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\CompetitionCategory;
use Illuminate\Http\Request;

class CompetitionCategoriesController extends Controller
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
            $competitioncategories = CompetitionCategory::where('name', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $competitioncategories = CompetitionCategory::latest()->paginate($perPage);
        }

        return view('admin.competition-categories.index', compact('competitioncategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.competition-categories.create');
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
        
        CompetitionCategory::create($requestData);

        return redirect('admin/competition-categories')->with('flash_message', 'CompetitionCategory added!');
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
        $competitioncategory = CompetitionCategory::findOrFail($id);

        return view('admin.competition-categories.show', compact('competitioncategory'));
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
        $competitioncategory = CompetitionCategory::findOrFail($id);

        return view('admin.competition-categories.edit', compact('competitioncategory'));
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
        
        $competitioncategory = CompetitionCategory::findOrFail($id);
        $competitioncategory->update($requestData);

        return redirect('admin/competition-categories')->with('flash_message', 'CompetitionCategory updated!');
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
        CompetitionCategory::destroy($id);

        return redirect('admin/competition-categories')->with('flash_message', 'CompetitionCategory deleted!');
    }
}
