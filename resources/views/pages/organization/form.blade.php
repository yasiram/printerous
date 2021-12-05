@extends('layouts.app')
@section('title', 'Organization')
@section('content')
<div class="row justify-content-md-center">
<div class="col-8 ">
   <div class="row mt-5">
      <div class="col-10">
         <h4>Organization Form</h4>
      </div>
   </div>
   <div class="row mt-3">
      <form id="form-org">
         <div class="mb-3 row">
            <input type="hidden" id="url" value="{{url('organization')}}"/>
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
            <label for="website" class="col-sm-2 col-form-label">Website</label>
            <div class="col-sm-10">
            <input type="text" class="form-control" id="website" name="website" value="{{isset($data) ? $data->website : ''}}">
            </div>
         </div>
         <div class="mb-3 row">
            <label for="logo" class="col-sm-2 col-form-label">Logo</label>
            <div class="col-sm-10">
               <input class="form-control" type="file" accept="image/png, image/gif, image/jpeg" id="logo" name="logo">
            </div>
         </div>
         @if(isset($data))
         <div class="mb-3 row">
            <div class="col-sm-6 offset-sm-2">
            <img src="{{$data->logoPath}}" id="logo" width="100">
            </div>
         </div>
         @endif
         
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
<script>
   function store(){
      $('#loading').modal('show')
      let baseUrl = $('#url').val()
      let formData = new FormData()
      formData.append('name', $('#name').val())
      formData.append('email', $('#email').val())
      formData.append('phone', $('#phone').val())
      formData.append('website', $('#website').val())
      formData.append('logo', $('#logo')[0].files[0])
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
      let idOrg = $('#id').val()
      let formData = new FormData()
      formData.append('id', parseInt(idOrg))
      formData.append('name', $('#name').val())
      formData.append('email', $('#email').val())
      formData.append('phone', $('#phone').val())
      formData.append('website', $('#website').val())
      formData.append('logo', $('#logo')[0].files[0])
      console.log(formData)
      $.ajax({
         url: baseUrl+"/edit/"+idOrg,
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