@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <!-- Category sidebar -->
        <div class="col-lg-2 border-right">
            @for ($i = 0, $count=count($categories); $i < $count;)
                @if (isset($categories[$i]->lev3))
                    <h4>{{ $categories[$i]->lev1 }}</h4>
                    <ul>
                    @for ($current_lev1=$categories[$i]->lev1_id; $i < $count && $categories[$i]->lev1_id==$current_lev1; )
                        <li>{{ $categories[$i]->lev2 }}
                            <ul>
                            @for ($current_lev2=$categories[$i]->lev2_id; $i < $count && $categories[$i]->lev2_id==$current_lev2;$i++)
                                <li><a href="{{ '/men?category='.$categories[$i]->lev3_id }}" style="color:black;
                                    {{ $category==$categories[$i]->lev3_id ? 'font-weight:bold;' : NULL }}
                                ">{{ $categories[$i]->lev3 }}</a></li>
                            @endfor
                            </ul>
                        </li>
                    @endfor
                    </ul>
                @endif
            @endfor
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
                        <img style="height:100%; width:100%;" src="{{ $Merchandise->photo ? Storage::disk('s3')->url($Merchandise->photo) : '/default-merchandise.jpg' }}">
                    </div>
                    <div style="padding:60px;">
                        <h2>{{ $Merchandise->name }}</h2>
                        <p style="color:#555;">{{ $Merchandise->intro }}</p>
                        <form action="/shopping-cart" class="form-inline" width="100%" id="addCartForm">
                            @csrf
                            <input class="form-control" type="number" min="1" max="49" value="1" style="max-width:60px;" name="amount">
                            <input value="{{ $Merchandise->id }}" style="display:none;" name="Mid">
                            <input value="add" style="display:none;" name="method">
                            <input type="submit" class="btn btn-secondary" value="加入購物車"
                                @if ($Merchandise->amount == 0)
                                    disabled
                                @endif>
                        </form>
                        @if ($Merchandise->amount == 0)
                            <span style="font-weight:bold;">目前無庫存</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
$("#addCartForm").submit(function(e) {
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
