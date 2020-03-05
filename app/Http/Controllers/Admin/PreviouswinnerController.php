<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\ApiController;
use App\Previouswinner;
use App\CompetitionUser;
use App\Competition;
use Datatables;
use Illuminate\Http\Request;

class PreviouswinnerController extends Controller {

    public static $_mediaBasePath = 'uploads/previouswinner/';
    protected $__rulesforindex = ['Player' => 'required', 'Competition' => 'required', 'Score' => 'required', 'Description'=>'required'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request) {
        if ($request->ajax()) {
            // $previouswinner = Previouswinner::all();
            $previouswinner = CompetitionUser::where('status', 'winner')->get();
           
            return Datatables::of($previouswinner)
                            // ->addIndexColumn()
                            //     ->editColumn('image', function($item) {
                            //      if (!file_exists(public_path(self::$_mediaBasePath . $item->image))) {
                            //         return "<img width='50' src=" . url('noimage.png') . ">";
                            //     } else {
                            //         return "<img width='50' src=" . url(self::$_mediaBasePath . $item->image) . ">";
                            //     }
                            // })
                            ->addColumn('Player', function($item) {
                                    
                                    $competitionUserData = \App\User::where('id', $item->player_id)->first();

                                    $firstName = ucfirst($competitionUserData->first_name);
                                    return "<a href=" . url('/admin/users/' . $item->player_id) . " title='View User'>$firstName</a>";
                                
                            })
                            ->addColumn('Competition', function($item) {
                                    $competitiondata = \App\Competition::where('id', $item->competition_id)->first();

                                    $competitionName = ucfirst($competitiondata->name);
                                    return "<a href=" . url('/admin/competition/' . $item->competition_id) . " title='View Competition'>$competitionName</a>";
                                    
                                
                            })
                            ->addColumn('Score', function($item) {
                                    
                                    return $item->score;
                                    
                                
                            })
                            ->addColumn('Description', function($item) {
                                    // $item =  json_decode($item->params);
                                if($item->params!==null)
                                    if(isset(json_decode($item->params)->description))
                                        return json_decode($item->params)->description;
                                    return "NAN";


                                    // return isset(json_decode($item->params)->description)?json_decode($item->params)->description:"NAN";
                                    
                                
                            })
                            // ->addColumn('action', function($item) {

                            //     $return = '';

                            //     if ($item->state == '0'):
                            //         $return .= "<button class='btn btn-danger btn-sm changeStatus' title='UnBlock'  data-id=" . $item->id . " data-status='UnBlock'>UnBlock / Active</button>";
                            //     else:
                            //         $return .= "<button class='btn btn-success btn-sm changeStatus' title='Block' data-id=" . $item->id . " data-status='Block' >Block / Inactive</button>";
                            //     endif;
//                                $return .= " <a href=" . url('/admin/previouswinner/' . $item->id) . " title='View Previous winner'><button class='btn btn-info btn-sm'><i class='fa fa-eye' aria-hidden='true'></i></button></a>
                                // $return .= "
                                //         <a href=" . url('/admin/previouswinner/' . $item->id . '/edit') . " title='Edit Previous winner'><button class='btn btn-primary btn-sm'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button></a>";
//                                        . " <button class='btn btn-danger btn-sm btnDelete' type='submit' data-remove='" . url('/admin/previouswinner/' . $item->id) . "'><i class='fa fa-trash-o' aria-hidden='true'></i></button>";
                                // return $return;
                            // })
//                            ->rawColumns(['action'])
                            ->rawColumns(['Player','Competition', 'Score', 'Description'])
                            ->make(true);
        }
        return view('admin.previouswinner.index', ['rules' => array_keys($this->__rulesforindex)]);
//        return view('admin.previouswinner.index', compact('previouswinner'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        return view('admin.previouswinner.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request) {
        $this->validate($request, [
            'image' => 'required',
        ]);
        $requestData = $request->all();
        if (isset($request->image))
            $requestData['image'] = ApiController::__uploadImage($request->file('image'), public_path(self::$_mediaBasePath));
        $requestData['state'] = '1';
        Previouswinner::create($requestData);

        return redirect('admin/previouswinner')->with('flash_message', 'Previouswinner added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id) {
        $previouswinner = Previouswinner::findOrFail($id);

        return view('admin.previouswinner.show', compact('previouswinner'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id) {
        $previouswinner = Previouswinner::findOrFail($id);

        return view('admin.previouswinner.edit', compact('previouswinner'));
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
        $previouswinner = Previouswinner::findOrFail($id);
        if (isset($request->image))
            $requestData['image'] = \App\Http\Controllers\API\ApiController::__uploadImage($request->file('image'), public_path(self::$_mediaBasePath));
        $previouswinner->update($requestData);

        return redirect('admin/previouswinner')->with('flash_message', 'Previouswinner updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id) {
        Previouswinner::destroy($id);

//        return redirect('admin/previouswinner')->with('flash_message', 'Previouswinner deleted!');
        return response()->json(["success" => true, 'message' => 'Previous Winner deleted!']);
    }

    public function changeStatus(Request $request) {
        $previouswinner = Previouswinner::findOrFail($request->id);
        $previouswinner->state = $request->status == 'Block' ? '0' : '1';
        $previouswinner->save();
        return response()->json(["success" => true, 'message' => 'Previous Winner updated!']);
    }

}
