<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    //
    public function index(Request $request){
        return view('pages.organization.index');
    }

    public function getDtOrganization(Request $request){
        $draw = $request->draw;
        $start = $request->start;
        $length = $request->length;
        $search = $request->search['value'];
        $data = Organization::leftJoin('persons', 'persons.fk_organization_id', 'organization.id')
        ->where(function($q) use ($search){
            if($search != '' && $search != null){
                $q->whereRaw('lower(organization.name) like "%'.strtolower($search).'%"');
                $q->orWhereRaw('lower(persons.name) like "%'.strtolower($search).'%"');
            }
        })
        ->offset($start)->limit($length)->selectRaw('organization.*');
        
        $countdata = $data->count();
        $data = $data->get();

        return response()->json([
            'data' => $data,
            'draw' => $draw,
            'recordsTotal' => $countdata,
            'recordsFiltered' => $countdata,
        ]);
    }

    public function getDtOrganizationPerson(Request $request){
        $draw = $request->draw;
        $start = $request->start;
        $length = $request->length;
        $search = $request->search['value'];
        $data = Organization::leftJoin('persons', 'persons.fk_organization_id', 'organization.id')
        ->where(function($q) use ($search){
            if($search != '' && $search != null){
                $q->whereRaw('lower(organization.name) like "%'.strtolower($search).'%"');
                $q->orWhereRaw('lower(persons.name) like "%'.strtolower($search).'%"');
            }
            $q->where('fk_accountmanager_id', Auth::user()->id);
        })
        ->offset($start)->limit($length)->selectRaw('organization.*');
        
        $countdata = $data->count();
        $data = $data->get();

        return response()->json([
            'data' => $data,
            'draw' => $draw,
            'recordsTotal' => $countdata,
            'recordsFiltered' => $countdata,
        ]);
    }

    public function detail($id){
        $data = Organization::with('persons')->find($id);
        $data->logoPath = null;
        if($data->logo)
            $data->logoPath = Storage::url($data->logo);
        foreach($data->persons as $person){
            $person->avatarPath = null;
            if($person->avatar)
                $person->avatarPath = Storage::url($person->avatar);
        }
        return view('pages.organization.detail',['data' => $data]);
    }

    public function create(Request $request){
        return view('pages.organization.form');
    }

    public function store(Request $request){
        try{
            $path = null;
            $accountManagerId = null;
            if(Auth::user()->getRoleNames()->contains('Account Manager')){
                $accountManagerId = Auth::user()->id;
            }
            if ($request->hasFile('logo')) {
                $path = $request->logo->store('public/organization');
            }
            $res = Organization::create([
                'name' => $request->name, 
                'email'=> $request->email, 
                'phone' => $request->phone, 
                'website' => $request->website, 
                'logo' => $path,
                'fk_accountmanager_id' => $accountManagerId
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
        $data = Organization::with('persons')->find($id);
        $data->logoPath = null;
        if($data->logo)
            $data->logoPath = Storage::url($data->logo);
        foreach($data->persons as $person){
            $person->avatarPath = null;
            if($person->avatar)
                $person->avatarPath = Storage::url($person->avatar);
        }
        return view('pages.organization.form',['data' => $data]);
    }

    public function update(Request $request){
        try{
            $data = Organization::find($request->id);
            if($data){
                $path = $data->logo;
                if ($request->hasFile('logo')) {
                    $path = $request->logo->store('public/organization');
                }
                $res = $data->update([
                    'name' => $request->name, 
                    'email'=> $request->email, 
                    'phone' => $request->phone, 
                    'website' => $request->website, 
                    'logo' => $path,
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
            $res = Organization::find($id)->delete();
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
