@extends('layouts.app')
@section('title', 'Person')
@section('content')
<div class="row justify-content-md-center">
<div class="col-8 ">
   <div class="row mt-5">
      <div class="col-10">
         <h4>Person Form</h4>
      </div>
   </div>
   <div class="row mt-3">
      <form id="form-org">
         <div class="mb-3 row">
            <input type="hidden" id="url" value="{{url('person')}}"/>
            <input type="hidden" id="url-org" value="{{url('organization')}}"/>
            <label for="name" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
            <input type="text" class="form-control" id="name" name="name" value="{{isset($data) ? $data->name : ''}}">
            </div>
         </div>
         <div class="mb-3 row">
            <label for="email" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
            <input type="text" class="form-control" id="email" name="email" value="{{isset($data) ? $data->email : ''}}">
            </div>
         </div>
         <div class="mb-3 row">
            <label for="phone" class="col-sm-2 col-form-label">Phone</label>
            <div class="col-sm-10">
            <input type="text" class="form-control" id="phone" name="phone" value="{{isset($data) ? $data->phone : ''}}">
            </div>
         </div>
         <div class="mb-3 row">
            <label for="logo" class="col-sm-2 col-form-label">Avatar</label>
            <div class="col-sm-10">
               <input class="form-control" type="file" accept="image/png, image/gif, image/jpeg" id="avatar" name="avatar">
            </div>
         </div>
         @if(isset($data) && $data->avatarPath)
         <div class="mb-3 row">
            <div class="col-sm-6 offset-sm-2">
            <img src="{{$data->avatarPath}}" id="logo" width="100">
            </div>
         </div>
         @endif
         <div class="mb-3 row">
            <label for="phone" class="col-sm-2 col-form-label">Organization</label>
            <div class="col-sm-8">
            <input type="text" readonly class="form-control" id="organization_name" name="organization_name" value="{{isset($data) && $data->organization ? $data->organization->name : ''}}">
            <input type="hidden" class="form-control" id="organization_id" name="organization_id" value="{{isset($data) && $data->organization ? $data->organization->id : ''}}">
            </div>
            <div class="col">
               <button type="button" onclick="showOrg()" class="btn btn-primary mb-3">Cari</button>
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
           <table id="table-org" width="100%">
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
         let baseUrl = $('#url-org').val();
         var dtOrg = $('#table-org').DataTable({
            //scrollX: true,
            bFilter: true,
            serverSide: true,
            processing: true,
            ordering: false,
            searching: true,
            ajax: {
                url: baseUrl+"/getdtPerson",
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
      $('#organization_name').val(orgName)
      $('#organization_id').val(id)
      $('#modal_org').modal('hide')
   }
   function store(){
      $('#loading').modal('show')
      let baseUrl = $('#url').val()
      let formData = new FormData()
      formData.append('name', $('#name').val())
      formData.append('email', $('#email').val())
      formData.append('phone', $('#phone').val())
      formData.append('organization_id', $('#organization_id').val())
      formData.append('avatar', $('#avatar')[0].files[0])
      console.log(formData)
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
      let idPerson = $('#id').val()
      let formData = new FormData()
      formData.append('id', parseInt(idPerson))
      formData.append('name', $('#name').val())
      formData.append('email', $('#email').val())
      formData.append('phone', $('#phone').val())
      formData.append('organization_id', $('#organization_id').val())
      formData.append('avatar', $('#avatar')[0].files[0])
      console.log(formData)
      $.ajax({
         url: baseUrl+"/edit/"+idPerson,
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