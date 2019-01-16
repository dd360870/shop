@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <!-- Category sidebar -->
        <div class="col-lg-2">
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
                        <
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@endsection
