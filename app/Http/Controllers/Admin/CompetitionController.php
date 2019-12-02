<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Competition;
use App\Game;
use Illuminate\Http\Request;
use App\Http\Controllers\API\ApiController;

class CompetitionController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public static $_mediaBasePath = 'uploads/competition/';

    public function index(Request $request) {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $competition = Competition::where('image', 'LIKE', "%$keyword%")
                            ->orWhere('description', 'LIKE', "%$keyword%")
                            ->orWhere('name', 'LIKE', "%$keyword%")
                            ->orWhere('date', 'LIKE', "%$keyword%")
                            ->orWhere('fee', 'LIKE', "%$keyword%")
                            ->orWhere('prize_image', 'LIKE', "%$keyword%")
                            ->orWhere('prize_details', 'LIKE', "%$keyword%")
                            ->orWhere('game_id', 'LIKE', "%$keyword%")
                            ->latest()->paginate($perPage);
        } else {
            $competition = Competition::latest()->paginate($perPage);
        }

        return view('admin.competition.index', compact('competition'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        $game = Game::get()->pluck('id','name');
        return view('admin.competition.create', compact('game'));
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
            'prize_image' => 'required',
        ]);

        $requestData['image'] = ApiController::__uploadImage($request->file('image'), public_path(self::$_mediaBasePath));
        $requestData['prize_image'] = \App\Http\Controllers\API\ApiController::__uploadImage($request->file('prize_image'), public_path('uploads/competition/prize_details'));
        $requestData = $request->all();
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
    public function show($id) {
        $competition = Competition::findOrFail($id);

        return view('admin.competition.show', compact('competition'));
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
        $game = Game::get()->pluck('name','id');

        return view('admin.competition.edit', compact('competition', 'game'));
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

        return redirect('admin/competition')->with('flash_message', 'Competition deleted!');
    }

}
