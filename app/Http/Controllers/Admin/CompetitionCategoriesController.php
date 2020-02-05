<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\CompetitionCategory;
use Illuminate\Http\Request;
use Datatables;

class CompetitionCategoriesController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    protected $__rulesforindex = ['name' => 'required'];

    public function index(Request $request) {
        if ($request->ajax()) {
            $competitionCategory = CompetitionCategory::all();
//               dd($competition);
            return Datatables::of($competitionCategory)
                            ->addIndexColumn()
//                            ->editColumn('image', function($item) {
//                            if(empty($item->image)) {
//                                return "<img width='50' src=".url('uploads/competition/noimage.png').">";
//                            }else{
//                            return "<img width='50' src=".url('uploads/competition/'.$item->image).">";   
//                            }
//                                
//                            })
                            ->addColumn('action', function($item) {

                                $return = '';

                                if ($item->state == '0'):
                                    $return .= "<button class='btn btn-danger btn-sm changeStatus' title='UnBlock'  data-id=" . $item->id . " data-status='UnBlock'>Unblock / Active</button>";
                                else:
                                    $return .= "<button class='btn btn-success btn-sm changeStatus' title='Block' data-id=" . $item->id . " data-status='Block' >Block / Inactive</button>";
                                endif;
//                                $return .= " <a href=" . url('admin/competition-categories/' . $item->id) . " title='View Competition'><button class='btn btn-info btn-sm'><i class='fa fa-eye' aria-hidden='true'></i></button></a>
                                $return .= "<a href=" . url('admin/competition-categories/' . $item->id . '/edit') . " title='Edit competition'><button class='btn btn-primary btn-sm'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button></a>  "
                                        . " <button class='btn btn-danger btn-sm btnDelete' type='submit' data-remove='" . url('/admin/competition-categories/' . $item->id) . "'><i class='fa fa-trash-o' aria-hidden='true'></i></button>";
                                return $return;
                            })
                            ->rawColumns(['action', 'image'])
                            ->make(true);
        }
        return view('admin.competition-categories.index', ['rules' => array_keys($this->__rulesforindex)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        return view('admin.competition-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request) {

        $requestData = $request->all();
        $requestData['state'] = '1';
        CompetitionCategory::create($requestData);
//        dd($requestData);

        return redirect('admin/competition-categories')->with('flash_message', 'CompetitionCategory added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id) {
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
    public function edit($id) {
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
    public function update(Request $request, $id) {

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
    public function destroy($id) {
        CompetitionCategory::destroy($id);

//        return redirect('admin/competition-categories')->with('flash_message', 'CompetitionCategory deleted!');
        return response()->json(["success" => true, 'message' => 'Competition Category has been deleted']);
    }

    public function changeStatus(Request $request) {
        $CompetitionCategory = CompetitionCategory::findOrFail($request->id);
        $CompetitionCategory->state = $request->status == 'Block' ? '0' : '1';
        $CompetitionCategory->save();
        return response()->json(["success" => true, 'message' => 'Competition Category updated!']);
    }

}
