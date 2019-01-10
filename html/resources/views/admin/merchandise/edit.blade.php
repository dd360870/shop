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
                    <a href="#">{{ $Merchandise->name }}[Edit]</a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div style="width:100%;">
                    <img src="{{ $Merchandise->photo ? secure_asset('storage/'.$Merchandise->photo) : secure_asset('default-merchandise.jpg') }}" id="photoPreview" alt="..." class="img-thumbnail" style="width:300px; height:300px; object-fit:cover; margin:auto; display:block;">
                    </div>

                    <form id="newForm" method="POST" enctype="multipart/form-data" action="/admin/merchandise/{{ $Merchandise->id }}">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label>Photo</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input {{ $errors->has('photo') ? ' is-invalid' : '' }}" id="photoInput" name="photo" accept="image/*">
                                <label class="custom-file-label" for="photoInput">{{ $Merchandise->photo ? $Merchandise->photo : 'Please choose a photo...' }}</label>
                                <div class="invalid-feedback"><strong>{{ $errors->first('photo') }}</strong></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nameInput">Name</label>
                        <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="nameInput" placeholder="Name" name="name" value="{{ $Merchandise->name }}">
                            <div class="invalid-feedback"><strong>{{ $errors->first('name') }}</strong></div>
                        </div>
                        <div class="form-group">
                            <label for="introInput">Introduction</label>
                            <textarea class="form-control {{ $errors->has('intro') ? ' is-invalid' : '' }}" rows="6" name="intro">{{ $Merchandise->intro }}</textarea>
                            <div class="invalid-feedback"><strong>{{ $errors->first('introduction') }}</strong></div>
                        </div>
                        <div class="form-group">
                            <label for="categoryInput">Category</label>
                            <select class="form-control {{ $errors->has('category') ? ' is-invalid' : '' }}" id="categoryInput" name="category">
                                <option value="0" @if($Merchandise->category == 0) selected @endif >上衣:短袖(00)</option>
                                <option value="1" @if($Merchandise->category == 1) selected @endif >上衣:長袖(01)</option>
                                <option value="2" @if($Merchandise->category == 2) selected @endif >褲子:短褲(02)</option>
                                <option value="3" @if($Merchandise->category == 3) selected @endif >褲子:長褲(03)</option>
                                <option value="4" @if($Merchandise->category == 4) selected @endif >內著(04)</option>
                                <option value="5" @if($Merchandise->category == 5) selected @endif >外套(05)</option>
                                <option value="6" @if($Merchandise->category == 6) selected @endif >襪子(06)</option>
                                <option value="7" @if($Merchandise->category == 7) selected @endif >配件(07)</option>
                            </select>
                            <div class="invalid-feedback"><strong>{{ $errors->first('category') }}</strong></div>
                        </div>
                        <div class="form-group">
                            <label for="priceInput">Price</label>
                            <input type="number" class="form-control {{ $errors->has('price') ? ' is-invalid' : '' }}" id="priceInput" name="price" value="{{ $Merchandise->price }}">
                            <div class="invalid-feedback"><strong>{{ $errors->first('price') }}</strong></div>
                        </div>
                        <div class="form-group">
                            <label for="amountInput">Amount</label>
                            <input type="number" class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}" id="amountInput" name="amount" value="{{ $Merchandise->amount }}">
                            <div class="invalid-feedback"><strong>{{ $errors->first('amount') }}</strong></div>
                        </div>
                        <div class="form-group">
                            <label for="statusInput">Status</label><br />
                            <div class="form-check form-check-inline">
                            <input class="form-check-input {{ $errors->has('status') ? ' is-invalid' : '' }}" type="radio" name="status" id="radio1" value="S"
                                @if ($Merchandise->status == 'S')
                                    checked
                                @endif>
                                <label class="form-check-label" for="radio1">On sheff</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input {{ $errors->has('status') ? ' is-invalid' : '' }}" type="radio" name="status" id="radio2" value="C"
                                @if ($Merchandise->status == 'C')
                                    checked
                                @endif>
                                <label class="form-check-label" for="radio2">Off sheff</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="barcode_ean">EAN barcode (NULL)</label>
                            <input type="text" class="form-control" id="barcode_ean" maxlength="13" name="barcode_ean" value="{{ $Merchandise->barcode_ean }}">
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
