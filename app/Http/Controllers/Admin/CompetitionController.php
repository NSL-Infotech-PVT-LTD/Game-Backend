<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Competition;
use App\Game;
use App\CompitionLeadBoard;
use Datatables;
use Illuminate\Http\Request;
use App\Http\Controllers\API\ApiController;

class CompetitionController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public static $_mediaBasePath = 'uploads/competition/';
    protected $__rulesforindex = ['name' => 'required', 'image' => 'required'];
    protected $__rulesforshow = ['user_id' => 'required', 'score' => 'required', 'created_at' => 'required'];

    public function index(Request $request) {

        if ($request->ajax()) {
            $competition = Competition::all();
//               dd($competition);
            return Datatables::of($competition)
                            ->addIndexColumn()
                            ->editColumn('image', function($item) {
                                if (empty($item->image)) {
                                    return "<img width='50' src=" . url('noimage.png') . ">"; 
                                } else {
                                    return "<img width='50' src=" . url('uploads/competition/' . $item->image) . ">";
                                }
                            })
                            ->addColumn('action', function($item) {

                                $return = '';

                                if ($item->state == '0'):
                                    $return .= "<button class='btn btn-danger btn-sm changeStatus' title='UnBlock'  data-id=" . $item->id . " data-status='UnBlock'>Unblock / Active</button>";
                                else:
                                    $return .= "<button class='btn btn-success btn-sm changeStatus' title='Block' data-id=" . $item->id . " data-status='Block' >Block / Inactive</button>";
                                endif;
                                $return .= " <a href=" . url('/admin/competition/' . $item->id) . " title='View Competition'><button class='btn btn-info btn-sm'><i class='fa fa-eye' aria-hidden='true'></i></button></a>
                                        <a href=" . url('/admin/competition/' . $item->id . '/edit') . " title='Edit competition'><button class='btn btn-primary btn-sm'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button></a>"
                                        . " <button class='btn btn-danger btn-sm btnDelete' type='submit' data-remove='" . url('/admin/competition/' . $item->id) . "'><i class='fa fa-trash-o' aria-hidden='true'></i></button>";
                                return $return;
                            })
                            ->rawColumns(['action', 'image'])
                            ->make(true);
        }
        return view('admin.competition.index', ['rules' => array_keys($this->__rulesforindex)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        $game = Game::get()->pluck('name', 'id');
        $competition_category = \App\CompetitionCategory::get()->pluck('name', 'id');
        return view('admin.competition.create', compact('game', 'competition_category'));
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
            'name' => 'required',
            'image' => 'required',
            'fee' => 'required',
            'prize_details' => 'required',
            'date' => 'required',
//            'prize_image' => 'required',
        ]);

        $requestData = $request->all();
        $requestData['image'] = ApiController::__uploadImage($request->file('image'), public_path(self::$_mediaBasePath));
//        $requestData['prize_image'] = \App\Http\Controllers\API\ApiController::__uploadImage($request->file('prize_image'), public_path('uploads/competition/prize_details'));
//        dd($requestData);
        Competition::create($requestData);

        return redirect('admin/competition')->with('flash_message', 'Competition added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show(Request $request, $id) {
 
        $competition = Competition::findOrFail($id);
//        $orderDetails = \App\CompitionLeadBoard::whereCompetitionId($id)->get();
        return view('admin.competition.show', compact('competition', 'orderDetails', 'user'), ['rules' => array_keys($this->__rulesforshow)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit(Request $request, $id) {
        $competition = Competition::findOrFail($id);
        $game = Game::get()->pluck('name', 'id');
        $competition_category = \App\CompetitionCategory::get()->pluck('name', 'id');

        return view('admin.competition.edit', compact('competition', 'game', 'competition_category'));
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
        $this->validate($request, [
            'name' => 'required',
            'fee' => 'required'
        ]);
        $requestData = $request->all();

        $competition = Competition::findOrFail($id);

        if (isset($request->image))
            $requestData['image'] = \App\Http\Controllers\API\ApiController::__uploadImage($request->file('image'), public_path(self::$_mediaBasePath));
        if (isset($request->prize_image))
            $requestData['prize_image'] = \App\Http\Controllers\API\ApiController::__uploadImage($request->file('prize_image'), public_path('uploads/competition/prize_details'));
        $competition->update($requestData);
        return redirect('admin/competition')->with('flash_message', 'Competition updated!', compact('game'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id) {
        Competition::destroy($id);
        return response()->json(["success" => true, 'message' => 'Competition has been deleted']);
    }

    public function changeStatus(Request $request) {
        $competition = Competition::findOrFail($request->id);

        $competition->state = $request->status == 'Block' ? '0' : '1';
        $competition->save();
        return response()->json(["success" => true, 'message' => 'Competition updated!']);
    }

    public function confirmWinner(Request $request) {

//        dd('found u');
        $leadBorad = CompitionLeadBoard::findOrFail($request->id);
        $ids = CompitionLeadBoard::where('competition_id', $leadBorad->competition_id)->get()->pluck('id')->toArray();
        CompitionLeadBoard::whereIn('id', $ids)->update(['winner' => '2']);
        $leadBorad->winner = '1';
        $leadBorad->save();
        return response()->json(["success" => true, 'message' => 'Competition updated!']);
    }

}
