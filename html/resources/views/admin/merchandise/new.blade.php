@extends('admin.base')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <a href="/admin">Admin</a>
                    >
                    <a href="/admin/merchandise">Merchandise</a>
                    >
                    <a href="#">New</a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div style="width:100%;">
                    <img src="/default-merchandise.jpg" id="photoPreview" alt="..." class="img-thumbnail" style="width:300px; height:300px; object-fit:cover; margin:auto; display:block;">
                    </div>

                    <form id="newForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Photo</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input {{ $errors->has('photo') ? ' is-invalid' : '' }}" id="photoInput" name="photo" accept="image/*" value="{{ old('image') }}">
                                <label class="custom-file-label" for="photoInput">Choose a photo ...</label>
                                <div class="invalid-feedback"><strong>{{ $errors->first('photo') }}</strong></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nameInput">Name</label>
                        <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="nameInput" placeholder="Name" name="name" value="{{ old('name') }}">
                            <div class="invalid-feedback"><strong>{{ $errors->first('name') }}</strong></div>
                        </div>
                        <div class="form-group">
                            <label for="introInput">Introduction</label>
                            <textarea class="form-control {{ $errors->has('intro') ? ' is-invalid' : '' }}" rows="6" name="intro">{{ old('intro') }}</textarea>
                            <div class="invalid-feedback"><strong>{{ $errors->first('introduction') }}</strong></div>
                        </div>
                        <div class="form-group">
                            <label for="categoryInput">Category</label>
                            <select class="form-control {{ $errors->has('category') ? ' is-invalid' : '' }}" id="categoryInput" name="category_id">
                                @foreach ($categories as $c)
                                    @isset($c->lev3)
                                        <option value="{{ $c->lev3_id }}">{{ $c->lev1.' -> '.$c->lev2.' -> '.$c->lev3.' [ '.$c->lev3_id.' ] ' }}</option>
                                    @endisset
                                @endforeach
                            </select>
                            <div class="invalid-feedback"><strong>{{ $errors->first('category') }}</strong></div>
                        </div>
                        <div class="form-group">
                            <label for="priceInput">Price</label>
                            <input type="number" class="form-control {{ $errors->has('price') ? ' is-invalid' : '' }}" id="priceInput" name="price" value="{{ old('price') }}">
                            <div class="invalid-feedback"><strong>{{ $errors->first('price') }}</strong></div>
                        </div>
                        <div class="form-group">
                            <label for="amountInput">Amount</label>
                            <input type="number" class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}" id="amountInput" name="amount" value="{{ old('amount') }}">
                            <div class="invalid-feedback"><strong>{{ $errors->first('amount') }}</strong></div>
                        </div>
                        <div class="form-group">
                            <label for="statusInput">Status</label><br />
                            <div class="form-check form-check-inline">
                            <input class="form-check-input {{ $errors->has('status') ? ' is-invalid' : '' }}" type="radio" name="status" id="radio1" value="S"
                                @if (old('status') == 'S')
                                    checked
                                @endif>
                                <label class="form-check-label" for="radio1">On sheff</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input {{ $errors->has('status') ? ' is-invalid' : '' }}" type="radio" name="status" id="radio2" value="C"
                                @if (old('status') == 'C')
                                    checked
                                @endif>
                                <label class="form-check-label" for="radio2">Off sheff</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="barcode_ean">EAN barcode (NULL)</label>
                            <input type="text" class="form-control" id="barcode_ean" maxlength="13" name="barcode_ean" value="{{ old('barcode_ean') }}">
                            <div class="invalid-feedback"><strong>{{ $errors->first('barcode_ean') }}</strong></div>
                        </div>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js" integrity="sha256-bu/BP02YMudBc96kI7yklc639Mu4iKGUNNcam8D2nLc=" crossorigin="anonymous" ></script>
<script type="text/javascript">

    $(document).ready(function() {
        $('#photoInput').on('change', function() {
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
            readURL(this);
        })
        $('#newForm').validate({
            rules: {
                // no quoting necessary
                name: "required",
                intro: "required",
                category: "required",
                price: {
                    required: true,
                    number: true,
                    min: 0,
                },
                amount: {
                    required: true,
                    number: true,
                    min: 0,
                },
                status: "required",
            },
            messages: {
                
            },
            errorPlacement: function(error, element) {
                element.next('.invalid-feedback').html(error);
            },
            validClass: "is-valid",
            errorClass: "is-invalid",
        });
        function readURL(input) {
            console.log(input);
            if(input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    document.getElementById('photoPreview').src = e.target.result;
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    });

</script>
@endsection
