@extends('admin.base')

@php $inventories = $Merchandise->inventory()->select('color_id', 'merchandise_id')->distinct()->get(); @endphp

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
                        <img src="{{ $Merchandise->photoUrl }}" id="photoPreview" alt="..." class="img-thumbnail" style="width:300px; height:300px; object-fit:cover; margin:auto; display:block;">
                    </div>

                    <form id="newForm" method="POST" enctype="multipart/form-data" action="/admin/merchandise/{{ $Merchandise->id }}">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label>Photo</label>
                            <select class="form-control" id="main_photo" name="main_photo">
                                <option value=""></option>
                                @foreach(Storage::disk('s3')->files($Merchandise->photoDirectory) as $path)
                                    <option value="{{ $path }}" data="{{ Storage::disk('s3')->url($path) }}"
                                        @if ($path == $Merchandise->photoPath) selected @endif>
                                        {{ $path }}
                                    </option>
                                @endforeach
                            </select>
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
                            <select class="form-control {{ $errors->has('category') ? ' is-invalid' : '' }}" id="categoryInput" name="category_id">
                                @foreach ($categories as $c)
                                    @isset($c->lev3)
                                        <option value="{{ $c->lev3_id }}" {{ $Merchandise->category_id==$c->lev3_id ? 'selected':'' }}>{{ $c->lev1.' -> '.$c->lev2.' -> '.$c->lev3.' [ '.$c->lev3_id.' ] ' }}</option>
                                    @endisset
                                @endforeach
                            </select>
                            <div class="invalid-feedback"><strong>{{ $errors->first('category') }}</strong></div>
                        </div>
                        <div class="form-group">
                            <label for="priceInput">Price</label>
                            <input type="number" class="form-control {{ $errors->has('price') ? ' is-invalid' : '' }}" id="priceInput" name="price" value="{{ $Merchandise->price }}">
                            <div class="invalid-feedback"><strong>{{ $errors->first('price') }}</strong></div>
                        </div>
                        <div class="form-group">
                            <label for="statusInput">Selling</label><br />
                            <div class="form-check form-check-inline">
                            <input class="form-check-input {{ $errors->has('is_selling') ? ' is-invalid' : '' }}" type="radio" name="is_selling" id="radio1" value="1"
                                @if ($Merchandise->is_selling)
                                    checked
                                @endif>
                                <label class="form-check-label" for="radio1">ON</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input {{ $errors->has('is_selling') ? ' is-invalid' : '' }}" type="radio" name="is_selling" id="radio2" value="0"
                                @if (!$Merchandise->is_selling)
                                    checked
                                @endif>
                                <label class="form-check-label" for="radio2">OFF</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Color</label><br>
                            @foreach (Config::get('constants.color') as $key => $color)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="color_id[]" value="{{ $key }}" @if($inventories->contains('color_id', $key)) checked @endif>
                                    <label class="px-3 py-2 border form-check-label" style="
                                        font-size:1.5em;
                                        color:{{ isset($color['font-color']) ? '#'.$color['font-color'] : 'white' }};
                                        background-color:{{ '#'.$color['hex'] }};">
                                        {{ $color['name'] }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <table class="table table-bordered">
                            <tr>
                                <th>Photo</th>
                                <th>Color</th>
                                <th>Upload</th>
                            </tr>
                            @foreach ($Merchandise->inventoryByColors as $key => $inventory)
                                <tr>
                                    <td>
                                        {{ $inventory->photoPath }}
                                        <img src="{{ $inventory->photoUrl }}"
                                        id="photoPreview{{ $key }}" class="img-thumbnail" style="width:250px; height:250px; object-fit:cover; margin:auto; display:block;">
                                    </td>
                                    <td style="background-color:#{{ Config::get('constants.color')[$inventory->color_id]['hex']}};"></td>
                                    <td>
                                        <div class="form-group">
                                            <label>Photo</label>
                                            <div class="custom-file">
                                                <input type="file"
                                                    class="photoInput custom-file-input {{ $errors->has('photo') ? ' is-invalid' : '' }}" id="photoInput{{ $key }}"
                                                    name="photo_{{ $inventory->color_id }}" accept="image/*" value="{{ old('image') }}">
                                                <label class="custom-file-label" for="photoInput">Choose a photo ...</label>
                                                <div class="invalid-feedback"><strong>{{ $errors->first('photo') }}</strong></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
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
        $('#main_photo').on('change', function() {
            $('#photoPreview')[0].src = this.options[this.selectedIndex].getAttribute('data')
        });
        $('.photoInput').on('change', function() {
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
                    document.getElementById('photoPreview'+input.id[10]).src = e.target.result;
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    });

</script>
@endsection
