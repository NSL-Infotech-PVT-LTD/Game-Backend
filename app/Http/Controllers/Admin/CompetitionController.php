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
    protected $__rulesforindex = ['name' => 'required', 'image' => 'required', 'start_time' => 'required'];
    protected $__rulesforshow = ['player_id' => 'required', 'score' => 'required', 'created_at' => 'required'];

    public function index(Request $request) {

        if ($request->ajax()) {
            $competition = Competition::all();
//               dd($competition);
            return Datatables::of($competition)
                            ->addIndexColumn()
                            ->editColumn('image', function($item) {

                                if (!file_exists(public_path(self::$_mediaBasePath . $item->image))) {
                                    return "<img width='50' src=" . url('noimage.png') . ">";
                                } else {
                                    return "<img width='50' src=" . url('uploads/competition/' . $item->image) . ">";
                                }
                            })
                            ->editColumn('start_time', function($item) {
                                return $item->start_time;
                            })
                            ->addColumn('hot', function($item) {
                                $return = '';
                                if ($item->hot_competitions == 1) {

                                    $return .= "<label class='switch'>
     <input type='checkbox' name='hot_competition'  class='hot_competition' data-id='" . $item->id . "' checked data-status='$item->hot_competitions'>
  <span class='slider round'></span>
</label>";
                                } else {

                                    $return .= "<label class='switch'>
     <input type='checkbox' name='hot_competition' class='hot_competition' data-status='$item->hot_competitions' data-id='" . $item->id . "' >
  <span class='slider round'></span>
</label>";
                                }

                                return $return;
                            })
                            ->addColumn('action', function($item) {

                                $return = '';

                                if ($item->state == '0'):
                                    $return .= "<button class='btn btn-danger btn-sm changeStatus' title='UnBlock'  data-id=" . $item->id . " data-status='UnBlock'>Unblock / Active</button>";
                                else:
                                    $return .= "<button class='btn btn-success btn-sm changeStatus' title='Block' data-id=" . $item->id . " data-status='Block' >Block / Inactive</button>";
                                endif;
                                $return .= " <a href=" . url('/admin/competition/' . $item->id) . " title='View Competition'><button class='btn btn-info btn-sm'><i class='fa fa-eye' aria-hidden='true'></i></button></a>
                                        <a href=" . url('/admin/competition/' . $item->id . '/edit') . " title='Edit competition'><button class='btn btn-primary btn-sm'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button></a>";
//                                        . " <button class='btn btn-danger btn-sm btnDelete' type='submit' data-remove='" . url('/admin/competition/' . $item->id) . "'><i class='fa fa-trash-o' aria-hidden='true'></i></button>";
                                return $return;
                            })
                            ->rawColumns(['action', 'image', 'start_time', 'hot'])
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
            'date' => 'required'
        ]);

//        dd($request->all());
        $requestData = $request->all();
        if (isset($request->hot_competition)):
            $requestData['hot_competition'] = $request->hot_competition;
        endif;
        $requestData['image'] = ApiController::__uploadImage($request->file('image'), public_path(self::$_mediaBasePath));

        if (isset($request->start_time))
            $requestData['start_time'] = date("H:i:s", strtotime($request->start_time));
//        $requestData['prize_image'] = \App\Http\Controllers\API\ApiController::__uploadImage($request->file('prize_image'), public_path('uploads/competition/prize_details'));
//        dd($requestData);
        $requestData['state'] = '1';
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
        if ($request->ajax()) {
            $leadBoard = \App\CompetitionUser::where('competition_id', $id)->get();
            return Datatables::of($leadBoard)
                            ->addIndexColumn()
                            ->editColumn('player_id', function($item) {
                                return isset($item->player_id) ? \App\User::where('id', $item->player_id)->first()->first_name : '';
                            })
                            ->addColumn('action', function($item) {
                                $return = '';
                                if ($item->status == 'not_yet'):
                                    $return .= "<button class='btn btn-warning btn-sm changeStatus'   data-id=" . $item->id . " data-status='confirm'>Mark as winner</button>";
                                elseif (($item->status == 'winner')):
                                    $return .= "<button class='btn btn-info btn-sm '   data-status='Block' >Game Winner</button>";
                                elseif (($item->status == 'looser')):
                                    $return .= "<button class='btn btn-danger btn-sm ' title='Block'  data-status='Block' >Better luck next time</button>";
                                endif;
                                return $return;
                            })
                            ->rawColumns(['action', 'image'])
                            ->make(true);
        }
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
//        dd($requestData);
        $competition = Competition::findOrFail($id);

        if (isset($request->start_time))
            $requestData['start_time'] = date("H:i:s", strtotime($request->start_time));
//dd($requestData);
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

    public function hotCompetition(Request $request) {
        $competition = Competition::findOrFail($request->url);
        if ($request->status == 0) {
            $competition->hot_competitions = $request->status == '1' ? '0' : '1';
        } else if ($request->status == 1) {
            $competition->hot_competitions = $request->status == '0' ? '1' : '0';
        }

//    dd($competition->hot_competitions);
        $competition->save();
        return response()->json(["success" => true, 'message' => 'Competition updated as hot!']);
    }

    public function AllhotCompetition(Request $request) {

        if ($request->ajax()) {
            $competition = Competition::where('hot_competitions', '1')->get();
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
                            ->addColumn('hot', function($item) {
                                $return = '';
                                if ($item->hot_competitions == 1) {

                                    $return .= "<label class='switch'>
     <input type='checkbox' name='hot_competition'  class='hot_competition' data-id='" . $item->id . "' checked data-status='$item->hot_competitions'>
  <span class='slider round'></span>
</label>";
                                }

                                return $return;
                            })
                            ->rawColumns(['image', 'hot'])
                            ->make(true);
        }

        return view('admin.competition.hotcompetition', ['rules' => array_keys($this->__rulesforindex)]);
    }

//        public function hotcompetitionindex(Request $request) {
//        return view('admin.competition.hotcompetition', ['rules' => array_keys($this->__rulesforindex)]);
//    }




    public function confirmWinner(Request $request) {

//        dd('found u');
        $leadBorad = \App\CompetitionUser::findOrFail($request->id);
        $competitionUser = \App\CompetitionUser::where('competition_id', $leadBorad->competition_id)->get();
        $losserIds = $competitionUser->pluck('player_id')->toArray();
        $winnerId = $leadBorad->player_id;
        if (($key = array_search($winnerId, $losserIds)) !== false) {
            unset($losserIds[$key]);
        }
        \App\CompetitionUser::whereIn('id', $competitionUser->pluck('id')->toArray())->update(['status' => 'looser']);
        \App\Http\Controllers\API\ApiController::pushNotificationsMultipleUsers(['title' => "Competition Result Declare", 'body' => "Oh !!! You Lose the Game, Better Luck Next time"], $losserIds, ['target_id' => $leadBorad->competition_id, 'target_type' => 'LeaderBoard'], 'FCM');
        $leadBorad->status = 'winner';
        $leadBorad->save();
        \App\Http\Controllers\API\ApiController::pushNotificationsMultipleUsers(['title' => "Competition Result Declare", 'body' => "Yeah !!! You Won the Game"], [$winnerId], ['target_id' => $leadBorad->competition_id, 'target_type' => 'LeaderBoard'], 'FCM');
        return response()->json(["success" => true, 'message' => 'Competition updated!']);
    }

}
