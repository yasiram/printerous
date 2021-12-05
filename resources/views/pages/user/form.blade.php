@extends('layouts.app')
@section('title', 'User')
@section('content')
<div class="row justify-content-md-center">
<div class="col-8 ">
   <div class="row mt-5">
      <div class="col-10">
         <h4>User Form</h4>
      </div>
   </div>
   <div class="row mt-3">
      <form id="form-org">
         <div class="mb-3 row">
            <input type="hidden" id="url" value="{{url('users')}}"/>
            <input type="hidden" id="url-org" value="{{url('organization')}}"/>
            <label for="name" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
            <input type="text" class="form-control" required id="name" name="name" value="{{isset($data) ? $data->name : ''}}">
            </div>
         </div>
         <div class="mb-3 row">
            <label for="email" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
            <input type="text" class="form-control" required autocomplete="off" id="email" name="email" value="{{isset($data) ? $data->email : ''}}">
            </div>
         </div>
         <div class="mb-3 row">
            <label for="password" class="col-sm-2 col-form-label">Password</label>
            <div class="col-sm-10">
            <input type="password" class="form-control" {{!isset($data)?'required':''}} autocomplete="off" id="password" name="password">
            </div>
         </div>
         <div class="mb-3 row">
            <label for="password" class="col-sm-2 col-form-label">Role</label>
            <div class="col-sm-10">
            <select class="form-select" id="role" name="role" autocomplete="off">
               @foreach($roles as $role)
                  <option value="{{$role->id}}" {{isset($data) && $data->roles[0]->id == $role->id ? 'selected' : ''}}>{{$role->name}}</option>
               @endforeach
            </select>
            </div>
         </div>
         <div class="mb-3 row" id="div-org">
            <label for="organization_name" class="col-sm-10 col-form-label">Assign Organization</label>
            {{-- <div class="col-sm-5">
            <input type="text" readonly class="form-control" id="organization_name" name="organization_name" value="{{isset($data) && $data->organization ? $data->organization->name : ''}}">
            <input type="hidden" class="form-control" id="organization_id" name="organization_id" value="{{isset($data) && $data->organization ? $data->organization->id : ''}}">
            </div> --}}
            <div class="col">
               <button type="button" onclick="showOrg()" class="btn btn-primary mb-3">Tambah</button>
             </div>
             <div class="offset-sm-2 col-sm-8">
               <table id="table-assign-org" class="table" width="100%">
                  <thead>
                     <tr>
                        <th class="text-center">Organization</th>
                        <th class="text-center">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     @if(isset($data))
                        @foreach($data->organizations as $org)
                           <tr id="{{"org_".$org->id}}">
                              <td>{{$org->name}}</td>
                              <td class="text-center"><button type="button" onclick="hapusOrg({{$org->id}})" class="btn btn-danger">Hapus</button></td>
                              <input type="hidden" name="organization_id[]" value="{{$org->id}}">
                           </tr>
                        @endforeach
                     @endif
                  </tbody>
                 </table>
             </div>
         </div>
         <div class="mb-3 row justify-content-md-center">
            @if(isset($data))
            <input type="hidden" id="id" value="{{$data->id}}">
            <button type="button" onclick="update()" class="col-4 btn btn-warning">Edit</button>
            @else
            <button type="button" onclick="store()" class="col-4 btn btn-primary">Submit</button>
            @endif
         </div>
      </form>
   </div>
</div>
</div>
<div class="modal fade" id="modal_org" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg">
     <div class="modal-content">
       <div class="modal-header">
           <h5 class="modal-title">Organization List</h5>
           <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
       <div class="modal-body">
           <table id="table-org" class="table" width="100%">
            <thead>
               <tr>
                  <th class="text-center">Name</th>
                  <th class="text-center">Email</th>
                  <th class="text-center">Phone</th>
                  <th class="text-center">Website</th>
                  <th class="text-center">Action</th>
               </tr>
            </thead>
           </table>
       </div>
       <div class="modal-footer">
           <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
       </div>        
     </div>
   </div>
 </div>
<script>
   $( document ).ready(function() {
      if($('#role').val() != 1){
            $('#div-org').hide(500)
         } else {
            $('#div-org').show(500)
         }
      $('#role').on('change', function(){
         if($(this).val() != 1){
            $('#div-org').hide(500)
         } else {
            $('#div-org').show(500)
         }
      })

         let baseUrl = $('#url-org').val();
         var dtOrg = $('#table-org').DataTable({
            //scrollX: true,
            bFilter: true,
            serverSide: true,
            processing: true,
            ordering: false,
            searching: true,
            ajax: {
                url: baseUrl+"/getdt",
            },
            columns: [
                { data: 'name', defaultContent: '-' },
                { data: 'email', defaultContent: '-'},
                { data: 'phone', defaultContent: '-'},
                { data: 'website', defaultContent: '-'},
                {
                    className: 'dt-body-center text-nowrap',
                    sortable: false,
                    render: function (data, type, full) {
                        var button = '';

                            button += '<button' +
                                ' data-toggle="tooltip" data-placement="top" title="Pilih" ' +
                                ' class="btn btn-sm btn-success" onclick="pilihOrg('+full.id+',\''+full.name+'\')"> ' +
                            ' Pilih </button> ';

                        return button;
                    }
                },
            ],
            initComplete: function () {
            },
            drawCallback: function () {
            }
        });
      });
   function showOrg(){
      $('#modal_org').modal('show')
   }
   function pilihOrg(id, orgName){
      // $('#organization_name').val(orgName)
      // $('#organization_id').val(id)
      let row = '<tr id="org_'+id+'">'+
         '<td>'+orgName+'</td>'+
         '<td class="text-center"><button type="button" onclick="hapusOrg('+id+')" class="btn btn-danger">Hapus</button></td>'+
         '<input type="hidden" name="organization_id[]" value="'+id+'">'+
         '</tr>';
      $('#table-assign-org').append(row);
      $('#modal_org').modal('hide')
   }
   function hapusOrg(id){
      $('#org_'+id).remove()
   }
   function store(){
      $('#loading').modal('show')
      let baseUrl = $('#url').val()
      let formData = new FormData()
      formData.append('name', $('#name').val())
      formData.append('email', $('#email').val())
      formData.append('password', $('#password').val())
      formData.append('role', $('#role').val())
      let elOrgArr = $('input[name^="organization_id"]')
      elOrgArr.each((i,val)=>{
         formData.append('organization_id['+i+']', $('input[name^="organization_id"]')[i].value)
      })
      $.ajax({
         url: baseUrl+"/create",
         type: "POST",
         processData: false,
         contentType: false,
         data: formData,
         dataType: 'json',
         success: (response) => {
            setTimeout(() => {
               $('#loading').modal('hide')
               if(response.status == 'S'){
                  $('#popup_title').text('Sukses')   
               } else {
                  $('#popup_title').text('Gagal')
               }
               $('#popup_body').text(response.message)
               $('#popup_info').modal('show')
               $('#form-org').trigger("reset");
            }, 300);
         },
         error: (xhr, txtStatus, errThrown) => {
            setTimeout(() => {
               $('#loading').modal('hide')
               $('#popup_title').text('Error')
               $('#popup_body').text('Gagal simpan data organization')
               $('#popup_info').modal('show')
            }, 300);
         }
      })
   }
   function update(){
      $('#loading').modal('show')
      let baseUrl = $('#url').val()
      let idUser = $('#id').val()
      let formData = new FormData()
      formData.append('id', parseInt(idUser))
      formData.append('name', $('#name').val())
      formData.append('email', $('#email').val())
      formData.append('password', $('#password').val())
      formData.append('role', $('#role').val())
      let elOrgArr = $('input[name^="organization_id"]')
      elOrgArr.each((i,val)=>{
         formData.append('organization_id['+i+']', $('input[name^="organization_id"]')[i].value)
      })
      console.log(formData)
      $.ajax({
         url: baseUrl+"/edit/"+idUser,
         type: "POST",
         processData: false,
         contentType: false,
         data: formData,
         dataType: 'json',
         success: (response) => {
            setTimeout(() => {
               $('#loading').modal('hide')
               if(response.status == 'S'){
                  $('#popup_title').text('Sukses')   
               } else {
                  $('#popup_title').text('Gagal')
               }
               $('#popup_body').text(response.message)
               $('#popup_info').modal('show')
               
            }, 300);
         },
         error: (xhr, txtStatus, errThrown) => {
            setTimeout(() => {
               $('#loading').modal('hide')
               $('#popup_title').text('Error')
               $('#popup_body').text('Gagal simpan data organization')
               $('#popup_info').modal('show')
            }, 300);
         }
      })
   }
</script>
@stop