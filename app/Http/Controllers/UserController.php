<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Models\Organization;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function index(Request $request){
        return view('pages.user.index');
    }

    public function getDtUser(Request $request){
        $draw = $request->draw;
        $start = $request->start;
        $length = $request->length;
        $search = $request->search['value'];
        $data = User::where(function($q) use ($search){
            if($search != '' && $search != null){
                $q->whereRaw('lower(user.name) like "%'.strtolower($search).'%"');
            }
        })
        ->offset($start)->limit($length);
        
        $countdata = $data->count();
        $data = $data->get();

        return response()->json([
            'data' => $data,
            'draw' => $draw,
            'recordsTotal' => $countdata,
            'recordsFiltered' => $countdata,
        ]);
    }

    public function create(Request $request){
        $roles = Role::get();
        return view('pages.user.form',['roles' => $roles]);
    }

    public function store(Request $request){
        DB::beginTransaction();
        try{
            $res = User::create([
                'name' => $request->name, 
                'email'=> $request->email, 
                'password' => Hash::make($request->password), 
                'email_verified_at' => now(),
            ]);
            $role = Role::find((int)$request->role);
            $res->assignRole($role);
            if($request->role == '1'){
                foreach($request->organization_id as $orgId){
                    Organization::find((int)$orgId)->update([
                        'fk_accountmanager_id' => $res->id
                    ]);
                }
            }

            if($res){
                $resData = [
                    'status' => 'S',
                    'message' => 'Berhasil simpan data'
                ];
                DB::commit();
            } else {
                $resData = [
                    'status' => 'E',
                    'message' => 'Gagal simpan data'
                ];
                DB::rollBack();
            }
        } catch(\Exception $e){
            DB::rollBack();
            $resData = [
                'status' => 'E',
                'message' => $e->getMessage(),
            ];
            
        }
        return response()->json($resData);
    }

    public function edit($id){
        $data = User::with('roles','organizations')->find($id);
        $roles = Role::get();
        
        return view('pages.user.form',['data' => $data, 'roles' => $roles]);
    }

    public function update(Request $request){
        DB::beginTransaction();
        try{
            $res = null;
            $data = User::with('roles')->find($request->id);
            if($data){
                $hashPass = $data->password;
                if($request->password != null && $request->password != ''){
                    $hashPass = Hash::make($request->password);
                }
                $role = Role::find((int)$request->role);
                $data->removeRole($data->roles[0]->name);
                $data->assignRole($role);
                Organization::where('fk_accountmanager_id', $data->id)->update([
                    'fk_accountmanager_id' => null
                ]);
                if($request->role == '1'){
                    foreach($request->organization_id as $orgId){
                        Organization::find((int)$orgId)->update([
                            'fk_accountmanager_id' => $data->id
                        ]);
                    }
                }
                $res = $data->update([
                    'name' => $request->name, 
                    'email'=> $request->email, 
                    'password' => $hashPass,
                ]);
            }

            if($data && $res){
                $resData = [
                    'status' => 'S',
                    'message' => 'Berhasil update data'
                ];
                DB::commit();
            } else {
                $resData = [
                    'status' => 'E',
                    'message' => 'Gagal update data'
                ];
                DB::rollBack();
            }
        
        } catch(\Exception $e){
            DB::rollBack();
            $resData = [
                'status' => 'E',
                'message' => $e->getMessage(),
            ];
            
        }
        return response()->json($resData);
    }

    public function delete($id){
        DB::beginTransaction();
        try{
            $user = User::with('roles')->find($id);
            Organization::where('fk_accountmanager_id', $user->id)->update([
                'fk_accountmanager_id' => null
            ]);
            $user->removeRole($user->roles[0]->name);
            $res = $user->delete();
            if($res){
                $resData = [
                    'status' => 'S',
                    'message' => 'Berhasil hapus data'
                ];
                DB::commit();
            } else {
                $resData = [
                    'status' => 'E',
                    'message' => 'Gagal hapus data'
                ];
                DB::rollBack();
            }
            
        } catch(\Exception $e){
            DB::rollBack();
            $resData = [
                'status' => 'E',
                'message' => $e->getMessage(),
            ];
            
        }
        return response()->json($resData);
    }
}
