@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <!-- Category sidebar -->
        <div class="col-lg-2 border-right">
            <h4>{{ $categories[0]->lev1 }}</h4>
            <ul>
            @for ($i = 0, $count = count($categories); $i < $count; )
                @if(isset($categories[$i]->lev3))
                    <li>{{ $categories[$i]->lev2 }}
                        <ul>
                        @for ($current_lev2=$categories[$i]->lev2_id; $i < $count && $categories[$i]->lev2_id==$current_lev2;$i++)
                            <li><a href="/{{ Config::get('constants.type')[$type].'?category='.$categories[$i]->lev3_id }}" style="color:black;
                                {{ app('request')->input('category')==$categories[$i]->lev3_id ? 'font-weight:bold;' : NULL }}
                            ">{{ $categories[$i]->lev3 }}</a></li>
                        @endfor
                        </ul>
                    </li>
                @else
                    @php $i++ @endphp
                @endif
            @endfor
            </ul>
        </div>
        <div class="col-lg-10">
            @if (isset($alert) || $alert=Session::get('alert', false))
                @component('components.alert', ['type' => $alert['type']])
                    {{ $alert['message'] }}
                @endcomponent
            @endif
            <div style="max-height:500px;">
                <div style="height:100%; width:100%; overflow: auto;">
                    <div style="height:100%; width:50%; padding:60px; float:left;">
                        <img id="main_img" class="rounded border" style="height:100%; width:100%;" src="{{ $Merchandise->photoUrl }}">
                    </div>
                    <div style="padding:60px;">
                        <h2>{{ $Merchandise->name }}</h2>
                        <p style="color:#555;">{{ $Merchandise->intro }}</p>
                        <form action="/shopping-cart" class="" width="100%" id="addCartForm">
                            @csrf
                            <input value="{{ $Merchandise->id }}" style="display:none;" name="Mid">
                            <input value="add" style="display:none;" name="method">
                            <div class="form-row p-2">
                                @foreach ($Merchandise->inventoryByColors as $inventory)
                                    <input class="color_picker" type="radio" name="color_id" value="{{ $inventory->color_id }}" required>
                                    <label class="py-3 px-3" style="background-color: #{{ Config::get('constants.color')[$inventory->color_id]['hex'] }}"></label>
                                @endforeach
                            </div>
                            <div class="form-row p-2">
                                @foreach ($Merchandise->inventory as $i)
                                    <div class="size_picker color_{{$i->color_id}}" style="display: none;">
                                        <input type="radio" name="size_id" onclick="document.getElementById('input_pid').value='{{ $i->product_id }}';"
                                            value="{{$i->size_id}}" @if($i->amount == 0) disabled @endif required>
                                        <label>{{ Config::get('constants.size')[$i->size_id] }}@if($i->amount == 0) [缺貨] @endif</label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-row p-2">
                                <input class="form-control mr-2" type="number" min="1" max="49" value="1" style="max-width:60px;" name="amount">
                                <input type="submit" class="btn btn-secondary" value="加入購物車">
                            </div>
                            <div class="form-row">
                                <label class="col-sm-4 col-form-label" >Product ID : </label>
                                <div class="col-sm-8">
                                    <input class="form-control-plaintext" id="input_pid" name="product_id">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
function imageUrl(mid, cid) {
    let str_mid = mid.toString().padStart(6, '0');
    let str_cid = cid.toString().padStart(2, '0');
    return '{{Storage::disk("s3")->url("/i/")}}'+str_mid+'/'+str_mid+str_cid+'.jpeg';
}
$(document).ready(function() {
    $('.color_picker').on('change', function() {
        document.getElementById('main_img').src = imageUrl({{$Merchandise->id}}, this.value);
        $('.size_picker').hide();
        $('.color_'+this.value).show();
    });
});
$("#addCartForm").submit(function(e) {
    if ($('input[name=size_id]:checked', '#addCartForm').val() == null) {
        alert('Size is required');
        e.preventDefault();
        return;
    }
    var form = $(this);
    var url = form.attr('action');

    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(), // serializes the form's elements.
        success: function(data)
        {
            refreshCart(true);
            alert(data); // show response from the php script.
        }
    });

    e.preventDefault(); // avoid to execute the actual submit of the form.
});
</script>
@endsection
