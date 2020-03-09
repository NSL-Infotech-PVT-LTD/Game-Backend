<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\ApiController;
use App\News;
use Datatables;
use Illuminate\Http\Request;

class newsController extends Controller {

    public static $_mediaBasePath = 'uploads/news/';
    protected $__rulesforindex = ['title' => 'required', 'image' => 'required'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request) {
        if ($request->ajax()) {
            $news = News::all();
//               
            return Datatables::of($news)
                            ->addIndexColumn()
                            ->editColumn('image', function($item) {
                                if (empty($item->image)) {
                                    return "<img width='50' src=" . url('noimage.png') . ">";
                                } else {
                                    return "<img width='50' src=" . url('uploads/news/' . $item->image) . ">";
                                }
                            })
                            ->addColumn('action', function($item) {

                                $return = '';

                                if ($item->state == '0'):
                                    $return .= "<button class='btn btn-danger btn-sm changeStatus' title='UnBlock'  data-id=" . $item->id . " data-status='UnBlock'>UnBlock / Active</button>";
                                else:
                                    $return .= "<button class='btn btn-success btn-sm changeStatus' title='Block' data-id=" . $item->id . " data-status='Block' >Block / Inactive</button>";
                                endif;
                                $return .= " <a href=" . url('/admin/news/' . $item->id) . " title='View News'><button class='btn btn-info btn-sm'><i class='fa fa-eye' aria-hidden='true'></i></button></a>
                                        <a href=" . url('/admin/news/' . $item->id . '/edit') . " title='Edit news'><button class='btn btn-primary btn-sm'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button></a>";
//                                        . " <button class='btn btn-danger btn-sm btnDelete' type='submit' data-remove='" . url('/admin/news/' . $item->id) . "'><i class='fa fa-trash-o' aria-hidden='true'></i></button>";
                                return $return;
                            })
                            ->rawColumns(['action', 'image'])
                            ->make(true);
        }
        return view('admin.news.index', ['rules' => array_keys($this->__rulesforindex)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        return view('admin.news.create');
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
        $requestData['state'] ='1';
        News::create($requestData);
        return redirect('admin/news')->with('flash_message', 'news added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id) {
        $news = News::findOrFail($id);

        return view('admin.news.show', compact('news'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id) {
        $news = News::findOrFail($id);

        return view('admin.news.edit', compact('news'));
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

        $news = News::findOrFail($id);
        if (isset($request->image))
            $requestData['image'] = ApiController::__uploadImage($request->file('image'), public_path(self::$_mediaBasePath));
        $news->update($requestData);

        return redirect('admin/news')->with('flash_message', 'news updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id) {
        News::destroy($id);
        return response()->json(["success" => true, 'message' => 'News has been deleted']);
//        return redirect('admin/news')->with('flash_message', 'news deleted!');
    }

    public function changeStatus(Request $request) {
        $news = News::findOrFail($request->id);
        $news->state = $request->status == 'Block' ? '0' : '1';
        $news->save();
        return response()->json(["success" => true, 'message' => 'News updated!']);
    }

}
