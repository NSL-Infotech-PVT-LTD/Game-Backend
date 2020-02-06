<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\API\ApiController;
use Datatables;


class BannersController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public static $_mediaBasePath = 'uploads/banner/';
    protected $__rulesforindex = ['image' => 'required', 'type' => 'required'];

    public function index(Request $request) {

        if ($request->ajax()) {
            $competitionCategory = Banner::all();
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
//                                $return .= "&nbsp;<a href=" . url('admin/competition-categories/' . $item->id . '/edit') . " title='Edit competition'><button class='btn btn-primary btn-sm'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button></a>";
//                                        . " <button class='btn btn-danger btn-sm btnDelete' type='submit' data-remove='" . url('/admin/competition-categories/' . $item->id) . "'><i class='fa fa-trash-o' aria-hidden='true'></i></button>";
                                return $return;
                            })
                            ->rawColumns(['action', 'image'])
                            ->make(true);
        }
        return view('admin.banners.index', ['rules' => array_keys($this->__rulesforindex)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        $user_id = \App\User::get();
//       dd($user_id->toArray());
        return view('admin.banners.create', compact('user_id'));
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
            'type' => 'required'
        ]);
        $requestData = $request->all();
//        dd($requestData);
        $requestData['image'] = ApiController::__uploadImage($request->file('image'), public_path(self::$_mediaBasePath));
        $requestData['user_id'] = Auth::id();
        Banner::create($requestData);

        return redirect('admin/banners')->with('flash_message', 'Banner added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id) {
        $banner = Banner::findOrFail($id);

        return view('admin.banners.show', compact('banner'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id) {
        $banner = Banner::findOrFail($id);
//dd($banner);
        return view('admin.banners.edit', compact('banner'));
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

        $banner = Banner::findOrFail($id);
        if ($request->image)
            $requestData['image'] = ApiController::__uploadImage($request->file('image'), public_path(self::$_mediaBasePath));
        $banner->update($requestData);

        return redirect('admin/banners')->with('flash_message', 'Banner updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id) {
        Banner::destroy($id);

        return redirect('admin/banners')->with('flash_message', 'Banner deleted!');
    }

    public function changeStatus(Request $request) {
        $CompetitionCategory = Banner::findOrFail($request->id);
        $CompetitionCategory->state = $request->status == 'Block' ? '0' : '1';
        $CompetitionCategory->save();
        return response()->json(["success" => true, 'message' => 'Banner updated!']);
    }

}
