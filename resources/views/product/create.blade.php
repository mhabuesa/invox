@extends('layouts.app')
@section('title', 'Add New Product')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-10 m-auto">
                    <!-- general form elements -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title my-1">Add New Product</h3>
                            <a href="{{ route('product.index') }}" class="btn btn-dark btn-sm float-right"> <i
                                    class="fas fa-arrow-left"></i> &nbsp; Back to List</a>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name"
                                        placeholder="Enter product name" name="name" value="{{ old('name') }}">
                                    @error('name')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="category">Category</label>
                                        <div class="input-group mb-3">
                                            <select name="category_id" id="category" class="form-control">
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <div class="input-group-text" data-toggle="modal"
                                                    data-target="#categoryModal">
                                                    <span class="fas fa-plus text-info"></span>
                                                </div>
                                            </div>
                                            @error('category_id')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <div class="form-group">
                                            <label for="code">Code</label>
                                            <input type="code" class="form-control" id="code"
                                                placeholder="Enter product code" name="code"
                                                value="{{ old('code') ?? $code }}">
                                            @error('code')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <div class="form-group">
                                            <label for="unit_price">Unit Price</label>
                                            <input type="unit_price" class="form-control" id="unit_price"
                                                placeholder="Enter product Price" name="unit_price"
                                                value="{{ old('unit_price') }}">
                                            @error('unit_price')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <div class="form-group">
                                            <label for="quantity">Quantity</label>
                                            <input type="quantity" class="form-control" id="quantity"
                                                placeholder="Enter product quantity" name="quantity"
                                                value="{{ old('quantity') }}">
                                            @error('quantity')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <div class="form-group text-center mb-4">
                                            <label class="mb-3 font-weight-bold">Product Image</label>
                                            <div class="mb-3">
                                                <img src="https://placehold.co/300x300?font=roboto" id="photo"
                                                    alt="Product Image" class="shadow" height="300">
                                            </div>
                                            <div class="custom-file w-25 mx-auto">
                                                <input type="file" class="custom-file-input" name="image"
                                                    id="inputImage"
                                                    onchange="document.getElementById('photo').src = window.URL.createObjectURL(this.files[0])">
                                                <label class="custom-file-label d-flex" for="inputImage">Choose file</label>
                                            </div>
                                            @error('image')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <div class="form-group mb-4">
                                            <label class="mb-3 font-weight-bold">Description</label>
                                            <textarea name="description" id="description" cols="30" rows="5" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-info float-right">Submit</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>

            </div>
            <!-- /.row -->
        </div>
    </section>

    <!-- Modal for Add new category -->
    <div class="modal fade" id="categoryModal" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add New Category</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="categoryForm" method="POST" action="{{ route('product.addCategoryAjax') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="category_name"
                                placeholder="Enter Category name">
                            <div class="text-danger mt-1 error-message" id="category_name_error"></div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button class="btn btn-info" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('footer')
<script>
  $('#categoryForm').on('submit', function (e) {
    e.preventDefault();

    let form = $(this);
    let url = form.attr('action');
    let formData = form.serialize();

    // Clear previous error
    $('#category_name_error').text('');

    $.ajax({
      type: "POST",
      url: url,
      data: formData,
      success: function (response) {
        if (response.success) {
          // Toast or alert
          showToast('Category Added', 'success');

          // Add new category to select
          $('#category').append(
            $('<option>', {
              value: response.category.id,
              text: response.category.name
            })
          );

          // Select newly added category
          $('#category').val(response.category.id);

          // Reset form
          form[0].reset();

          // Close modal
          $('#categoryModal').modal('hide');
        }
      },
      error: function (xhr) {
        if (xhr.status === 422) {
          let errors = xhr.responseJSON.errors;
          if (errors.category_name) {
            $('#category_name_error').text(errors.category_name[0]);
          }
        } else {
          alert('Something went wrong!');
        }
      }
    });
  });
</script>

@endpush
