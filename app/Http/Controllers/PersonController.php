<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;
use Illuminate\Support\Facades\Storage;

class PersonController extends Controller
{
    //
    public function index(Request $request){
        return view('pages.person.index');
    }

    public function getDtPerson(Request $request){
        $draw = $request->draw;
        $start = $request->start;
        $length = $request->length;
        $search = $request->search['value'];
        $data = Person::leftJoin('organization', 'persons.fk_organization_id', 'organization.id')
        ->where(function($q) use ($search){
            if($search != '' && $search != null){
                $q->whereRaw('lower(organization.name) like "%'.strtolower($search).'%"');
                $q->orWhereRaw('lower(persons.name) like "%'.strtolower($search).'%"');
            }
        })
        ->offset($start)->limit($length)->selectRaw('persons.*, organization.name as org_name, organization.fk_accountmanager_id');
        
        $countdata = $data->count();
        $data = $data->get();
        foreach($data as $i => $person){
            $person->avatarPath = null;
            if($person->avatar)
                $person->avatarPath = Storage::url($person->avatar);
        }

        return response()->json([
            'data' => $data,
            'draw' => $draw,
            'recordsTotal' => $countdata,
            'recordsFiltered' => $countdata,
        ]);
    }

    public function create(Request $request){
        return view('pages.person.form');
    }

    public function store(Request $request){
        try{
            $path = null;
            if ($request->hasFile('avatar')) {
                $path = $request->avatar->store('public/person');
            }
            $res = Person::create([
                'name' => $request->name, 
                'email'=> $request->email, 
                'phone' => $request->phone,
                'avatar' => $path,
                'fk_organization_id' => $request->organization_id
            ]);

            if($res){
                $resData = [
                    'status' => 'S',
                    'message' => 'Berhasil simpan data'
                ];
            } else {
                $resData = [
                    'status' => 'E',
                    'message' => 'Gagal simpan data'
                ];
            }
        } catch(\Exception $e){
            $resData = [
                'status' => 'E',
                'message' => $e->getMessage(),
            ];
            
        }
        return response()->json($resData);
    }

    public function edit($id){
        $data = Person::with('organization')->find($id);
        $data->avatarPath = null;
        if($data->avatar)
            $data->avatarPath = Storage::url($data->avatar);
        
        return view('pages.person.form',['data' => $data]);
    }

    public function update(Request $request){
        try{
            $data = Person::find($request->id);
            if($data){
                $path = $data->avatar;
                if ($request->hasFile('avatar')) {
                    $path = $request->avatar->store('public/person');
                }
                $res = $data->update([
                    'name' => $request->name, 
                    'email'=> $request->email, 
                    'phone' => $request->phone,
                    'avatar' => $path,
                    'fk_organization_id' => $request->organization_id
                ]);
            }

            if($data && $res){
                $resData = [
                    'status' => 'S',
                    'message' => 'Berhasil update data'
                ];
            } else {
                $resData = [
                    'status' => 'E',
                    'message' => 'Gagal update data'
                ];
            }
        
        } catch(\Exception $e){
            $resData = [
                'status' => 'E',
                'message' => $e->getMessage(),
            ];
            
        }
        return response()->json($resData);
    }

    public function delete($id){
        try{
            $res = Person::find($id)->delete();
            if($res){
                $resData = [
                    'status' => 'S',
                    'message' => 'Berhasil hapus data'
                ];
            } else {
                $resData = [
                    'status' => 'E',
                    'message' => 'Gagal hapus data'
                ];
            }
            
        } catch(\Exception $e){
            $resData = [
                'status' => 'E',
                'message' => $e->getMessage(),
            ];
            
        }
        return response()->json($resData);
    }
}
