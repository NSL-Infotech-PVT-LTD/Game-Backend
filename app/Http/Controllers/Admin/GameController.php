<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Game;
use Datatables;
use Illuminate\Http\Request;

class GameController extends Controller {

    public static $_mediaBasePath = 'uploads/games/';
    protected $__rulesforindex = ['name' => 'required','image'=>'required'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
public function index(Request $request) {
        if ($request->ajax()) {
            $game = Game::all();
//               dd($competition);
            return Datatables::of($game)
                            ->addIndexColumn()
                            ->editColumn('image', function($item) {
                                if(empty($item->image)){
                                    return "<img width='50' src=".url('uploads/games/noimage.png').">";
                                }else{
                                return "<img width='50' src=".url('uploads/games/'.$item->image).">";
                                }
                            })
                            ->addColumn('action', function($item) {
                                
                                $return = '';

                                if ($item->state == '0'):
                                    $return .= "<button class='btn btn-danger btn-sm changeStatus' title='UnBlock'  data-id=" . $item->id . " data-status='UnBlock'>UnBlock / Active</button>";
                                else:
                                    $return .= "<button class='btn btn-success btn-sm changeStatus' title='Block' data-id=" . $item->id . " data-status='Block' >Block / Inactive</button>";
                                endif;
                                $return .= " <a href=" . url('/admin/game/' . $item->id) . " title='View game'><button class='btn btn-info btn-sm'><i class='fa fa-eye' aria-hidden='true'></i></button></a>
                                        <a href=" . url('/admin/game/' . $item->id . '/edit') . " title='Edit game'><button class='btn btn-primary btn-sm'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button></a>"
                                        . " <button class='btn btn-danger btn-sm btnDelete' type='submit' data-remove='" . url('/admin/competition/' . $item->id) . "'><i class='fa fa-trash-o' aria-hidden='true'></i></button>";
                                return $return;
                            })
                        
                            ->rawColumns(['action','image'])
                            ->make(true);
        }
        return view('admin.game.index', ['rules' => array_keys($this->__rulesforindex)]);
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
        return response()->json(["success" => true, 'message' => 'Game has been deleted']);
//        return redirect('admin/game')->with('flash_message', 'Game deleted!');
    }
    
    public function changeStatus(Request $request) {
        $game = Game::findOrFail($request->id);
        $game->state = $request->status == 'Block' ? '0' : '1';
        $game->save();
        return response()->json(["success" => true, 'message' => 'Game updated!']);
    }
}
