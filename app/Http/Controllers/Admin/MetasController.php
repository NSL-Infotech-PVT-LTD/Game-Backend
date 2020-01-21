<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Meta;
use Illuminate\Http\Request;

class MetasController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request) {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $metas = Meta::where('meta_key', 'LIKE', "%$keyword%")
                            ->orWhere('meta_content', 'LIKE', "%$keyword%")
                            ->latest()->paginate($perPage);
        } else {
            $metas = Meta::latest()->paginate($perPage);
        }

        return view('admin.metas.index', compact('metas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        return view('admin.metas.create');
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

        Meta::create($requestData);

        return redirect('admin/metas')->with('flash_message', 'Meta added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id) {
        $meta = Meta::findOrFail($id);

        return view('admin.metas.show', compact('meta'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id) {
        $meta = Meta::findOrFail($id);

        return view('admin.metas.edit', compact('meta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request) {

        $requestData = $request->all();

        if ($request->meta_key == 'privacy_policy') {
            $meta = Meta::where('meta_key', 'privacy_policy')->first();
            $redirectUrl = 'privacy_policy';
        } elseif ($request->meta_key == 'terms_and_condition') {
            $meta = Meta::where('meta_key', 'terms_and_condition')->first();
            $redirectUrl = 'terms_and_condition';
        }


        $meta->update($requestData);

        return redirect('admin/' . $redirectUrl . '')->with('flash_message', 'Content updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id) {
        Meta::destroy($id);

        return redirect('admin/metas')->with('flash_message', 'Meta deleted!');
    }

    public function privacy_policy(Request $request) {
        $meta = Meta::where('meta_key', 'privacy_policy')->first();
        if (empty($meta)) {
            Meta::create(['meta_key' => 'privacy_policy']);
            $meta = Meta::where('meta_key', 'privacy_policy')->first();
        }
        return view('admin.metas.edit', compact('meta'));
    }

    public function terms_and_condition(Request $request) {



        $meta = Meta::where('meta_key', 'terms_and_condition')->first();
        if (empty($meta)) {
            Meta::create(['meta_key' => 'terms_and_condition']);
            $meta = Meta::where('meta_key', 'terms_and_condition')->first();
        }
        return view('admin.metas.edit', compact('meta'));
    }

}
