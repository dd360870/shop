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

                    {{--<div style="width:100%;">
                    <img src="/default-merchandise.jpg" id="photoPreview" alt="..." class="img-thumbnail" style="width:300px; height:300px; object-fit:cover; margin:auto; display:block;">
                    </div>--}}

                    <form id="newForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{--<div class="form-group">
                            <label>Photo</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input {{ $errors->has('photo') ? ' is-invalid' : '' }}" id="photoInput" name="photo" accept="image/*" value="{{ old('image') }}">
                                <label class="custom-file-label" for="photoInput">Choose a photo ...</label>
                                <div class="invalid-feedback"><strong>{{ $errors->first('photo') }}</strong></div>
                            </div>
                        </div>--}}
                        <div class="form-group">
                            <label for="nameInput">Name</label>
                        <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="nameInput" placeholder="Name" name="name" value="{{ old('name') }}">
                            <div class="invalid-feedback"><strong>{{ $errors->first('name') }}</strong></div>
                        </div>
                        <div class="form-group">
                            <label for="introInput">Introduction</label>
                            <textarea class="form-control {{ $errors->has('intro') ? ' is-invalid' : '' }}" rows="6" name="intro">{{ old('intro') }}</textarea>
                            <div class="invalid-feedback"><strong>{{ $errors->first('intro') }}</strong></div>
                        </div>
                        <div class="form-group">
                            <label for="categoryInput">Category</label>
                            <select class="form-control {{ $errors->has('category_id') ? ' is-invalid' : '' }}" id="categoryInput" name="category_id" required>
                                <option value=""></option>
                                @foreach ($categories as $c)
                                    @isset($c->lev3)
                                        <option value="{{ $c->lev3_id }}">{{ $c->lev1.' -> '.$c->lev2.' -> '.$c->lev3.' [ '.$c->lev3_id.' ] ' }}</option>
                                    @endisset
                                @endforeach
                            </select>
                            <div class="invalid-feedback"><strong>{{ $errors->first('category_id') }}</strong></div>
                        </div>
                        <div class="form-group">
                            <label for="priceInput">Price</label>
                            <input type="number" class="form-control {{ $errors->has('price') ? ' is-invalid' : '' }}" id="priceInput" name="price" value="{{ old('price') }}">
                            <div class="invalid-feedback"><strong>{{ $errors->first('price') }}</strong></div>
                        </div>
                        <div class="form-group">
                            <label for="statusInput">Selling</label><br />
                            <div class="form-check form-check-inline">
                            <input class="form-check-input {{ $errors->has('is_selling') ? ' is-invalid' : '' }}" type="radio" name="is_selling" id="radio1" value="1"
                                @if (old('is_selling') == 1)
                                    checked
                                @endif>
                                <label class="form-check-label" for="radio1">ON</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input {{ $errors->has('is_selling') ? ' is-invalid' : '' }}" type="radio" name="is_selling" id="radio2" value="0"
                                @if (old('is_selling') == 0)
                                    checked
                                @endif>
                                <label class="form-check-label" for="radio2">OFF</label>
                            </div>
                        </div>
                        <div class="form-group">
                        <label>Color</label><br>
                        @foreach (Config::get('constants.color') as $key => $color)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="color_id[]" value="{{ $key }}">
                                <label class="px-3 py-2 border form-check-label" style="
                                    font-size:1.5em;
                                    color:{{ isset($color['font-color']) ? '#'.$color['font-color'] : 'white' }};
                                    background-color:{{ '#'.$color['hex'] }};">
                                    {{ $color['name'] }}
                                </label>
                            </div>
                        @endforeach
                        </div>
                        <label>Size</label>
                        <div class="form-row">
                            <div class="form-group col-md-5" style="text-align:center;">
                                <label>MIN</label>
                                <select class="form-control {{ $errors->has('size_min') ? ' is-invalid' : '' }}" name="size_min">
                                    @foreach (Config::get('constants.size') as $id => $size)
                                        <option value="{{ $id }}">{{ $size }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"><strong>{{ $errors->first('size_min') }}</strong></div>
                            </div>
                            <div class="form-group col-md-2">

                            </div>
                            <div class="form-group col-md-5" style="text-align:center;">
                                <label>MAX</label>
                                <select class="form-control {{ $errors->has('size_max') ? ' is-invalid' : '' }}" name="size_max">
                                    @foreach (Config::get('constants.size') as $id => $size)
                                        <option value="{{ $id }}">{{ $size }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"><strong>{{ $errors->first('size_max') }}</strong></div>
                            </div>
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
        $.validator.addMethod(
            "lte", 
            function(value, element, params) {
                return value <= this.currentForm.size_max.value;
            },
            jQuery.validator.format("This field must less than {0}"),
        );
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
                is_selling: "required",
                size_min: {
                    required: true,
                    lte: 'size_max',
                },
                size_max: {
                    required: true,
                    number: true,
                },
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
