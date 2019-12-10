<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Datatables;
class UsersController extends Controller {


    public static $_mediaBasePath = 'uploads/users/';
      protected $__rulesforindex = ['first_name' => 'required', 'email' => 'required'];
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index(Request $request) {
//        $keyword = $request->get('search');
//        $perPage = 15;

        if ($request->ajax()) {
            $user = User::all();
//               
            return Datatables::of($user)
//                            ->addIndexColumn()
//                            ->editColumn('image', function($item) {
//                                if (empty($item->image)) {
//                                    return "<img width='50' src=" . url('uploads/news/noimage.png') . ">";
//                                } else {
//                                    return "<img width='50' src=" . url('uploads/news/' . $item->image) . ">";
//                                }
//                            })
                            ->addColumn('action', function($item) {

                                $return = '';
            // BLOCK AND UN-BLOCK CODE==========> 
                                           
//                                if ($item->status == '0'):
//                                    $return .= "<button  class='btn btn-danger btn-sm changeStatus' title='UnBlock'  data-id=" . $item->id . " data-status='UnBlock'>UnBlock / Active</button>";
//                                else:
//                                    $return .= "<button class='btn btn-success btn-sm changeStatus' title='Block' data-id=" . $item->id . " data-status='Block' >Block / Inactive</button>";
//                                endif;
                               
            // END BLOCK AND UN-BLOCK CODE=======>                                
                                $return .= " <a href=" . url('/admin/users/' . $item->id) . " title='View News'><button class='btn btn-info btn-sm'><i class='fa fa-eye' aria-hidden='true'></i></button></a>";
//                                    <a href=" . url('/admin/users/' . $item->id . '/edit') . " title='Edit news'><button class='btn btn-primary btn-sm'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button></a>"
//                                        . " <button class='btn btn-danger btn-sm btnDelete' type='submit' data-remove='" . url('/admin/users/' . $item->id) . "'><i class='fa fa-trash-o' aria-hidden='true'></i></button>
                                return $return;
                            })
                            ->rawColumns(['action'])
                            ->make(true);
        }

//        return view('admin.users.index', compact('users'));
        return view('admin.users.index', ['rules' => array_keys($this->__rulesforindex)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create() {
        $roles = Role::select('id', 'name', 'label')->get();
        $roles = $roles->pluck('label', 'name');

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function store(Request $request) {
        $this->validate(
                $request,
                [
                    'first_name' => 'required',
                    'email' => 'required|string|max:255|email|unique:users',
                    'password' => 'required',
                    'roles' => 'required'
                ]
        );

        $data = $request->except('password');
        $data['password'] = bcrypt($request->password);
        $user = User::create($data);

        foreach ($request->roles as $role) {
            $user->assignRole($role);
        }

        return redirect('admin/users')->with('flash_message', 'User added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function show($id) {
        $user = User::findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function edit($id) {
        $roles = Role::select('id', 'name', 'label')->get();
        $roles = $roles->pluck('label', 'name');

        $user = User::with('roles')->findOrFail($id);
        $user_roles = [];
        foreach ($user->roles as $role) {
            $user_roles[] = $role->name;
        }

        return view('admin.users.edit', compact('user', 'roles', 'user_roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int      $id
     *
     * @return void
     */
    public function update(Request $request, $id) {
        $this->validate(
                $request,
                [
                    'first_name' => 'required',
                    'email' => 'required|string|max:255|email|unique:users,email,' . $id,
                    'roles' => 'required'
                ]
        );

        $data = $request->except('password');
        if ($request->has('password')) {
            if(!empty($request->password))
                $data['password'] = bcrypt($request->password);
        }

        $user = User::findOrFail($id);
        $user->update($data);

        $user->roles()->detach();
        foreach ($request->roles as $role) {
//            $user->assignRole('App-Users');
            $user->assignRole($role);
        }

        return redirect('admin/users')->with('flash_message', 'User updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function destroy($id) {
        User::destroy($id);

        return redirect('admin/users')->with('flash_message', 'User deleted!');
    }

    
    public function changeStatus(Request $request) {
       
        $user = User::findOrFail($request->id);
        $user->status = $request->status == 'Block' ? '0' : '1';
        $user->save();
        return response()->json(["success" => true, 'message' => 'User updated!']);
    }
    
    
//    function updateUserStatus(Request $request) {
//        $selectUser = User::where('id', $request->input('id'))->with('roles')->first();
////        dd($selectUser->roles->pluck('name'));
//        if($selectUser->roles->pluck('name')->count()===1)
//            return ['status'=>false,'message'=>'Assign user role first and then activate'];
//        if ($selectUser->status == 1) {
//            User::where('id', $request->input('id'))->update([
//                'status' => 0
//            ]);
//            return ['status'=>false,'message'=>'status deactivated'];
//        } else {
//            User::where('id', $request->input('id'))->update([
//                'status' => 1
//            ]);
//            return ['status'=>true,'message'=>'status activated'];
//        }
//    }

}
