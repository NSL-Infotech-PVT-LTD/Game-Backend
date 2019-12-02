<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Game;
use Illuminate\Http\Request;

class GameController extends Controller {

    public static $_mediaBasePath = 'uploads/games/';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request) {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $game = Game::where('name', 'LIKE', "%$keyword%")
                            ->orWhere('image', 'LIKE', "%$keyword%")
                            ->latest()->paginate($perPage);
        } else {
            $game = Game::latest()->paginate($perPage);
        }

        return view('admin.game.index', compact('game'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        return view('admin.game.create');
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
            'name' => 'required'
        ]);
        $requestData = $request->all();
        if (isset($request->image))
            $requestData['image'] = \App\Http\Controllers\API\ApiController::__uploadImage($request->file('image'), public_path(self::$_mediaBasePath));
        Game::create($requestData);

        return redirect('admin/game')->with('flash_message', 'Game added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id) {
        $game = Game::findOrFail($id);

        return view('admin.game.show', compact('game'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id) {
        $game = Game::findOrFail($id);

        return view('admin.game.edit', compact('game'));
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
            'name' => 'required'
        ]);
        $requestData = $request->all();

        $game = Game::findOrFail($id);
        if (isset($request->image))
            $requestData['image'] = \App\Http\Controllers\API\ApiController::__uploadImage($request->file('image'), public_path(self::$_mediaBasePath));

        $game->update($requestData);

        return redirect('admin/game')->with('flash_message', 'Game updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id) {
        Game::destroy($id);

        return redirect('admin/game')->with('flash_message', 'Game deleted!');
    }

}
