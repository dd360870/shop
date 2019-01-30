@extends('admin.base')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            @if(isset($alert) || $alert = Session::get('alert', false))
                @component('components.alert', ['type' => $alert['type']])
                {{ $alert['message'] }}
                @endcomponent
            @endif
            <div class="card">
                <div class="card-header">
                    <a href="/admin">Admin</a>
                    >
                    <a href="/admin/merchandise">Merchandise</a>
                    >
                    <a href="#">{{ $Merchandise->name }}</a>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div style="width:100%;">
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="{{ $Merchandise->photoUrl }}"
                                    alt=""
                                    class="img-thumbnail"
                                    style="width:600px; height:width; display:block; margin:auto;">
                            </div>
                            <div class="col-md-6" style="min-height: 300px;">
                                <h2>{{ $Merchandise->name }}</h2>
                                <h5>
                                    {{ $Merchandise->intro }}
                                </h5>
                                <div>
                                    @foreach ($Merchandise->inventory()->select('color_id')->distinct()->get() as $inventory)
                                        <span class="px-3 py-2" style="
                                            color:#{{ (Config::get('constants.color')[$inventory->color_id]['font-color'] ?? 'fff') }};
                                            background-color:#{{ Config::get('constants.color')[$inventory->color_id]['hex'] }}">
                                        </span>
                                    @endforeach
                                </div>
                                <div style="">
                                    <p style="bottom:0; position:absolute; color:gray;">@lang('ID') : {{ sprintf('%07d', $Merchandise->id) }}</p>
                                    <h2 style="bottom:0; right:0; position:absolute; color:red; float:right;">
                                        NTD$ <span style="font-size:10vmin;">{{ $Merchandise->price }}</span>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        {{--
                        <div class="row">
                            <table class="table table-bordered" style="font-size:1em; text-align:center; padding:0; margin: 0;">
                                <tr>
                                    <th>Product ID</th>
                                    <th>Color</th>
                                    <th class="p-1">Color/Size</th>
                                    <th class="p-1">Amount</th>
                                </tr>
                            @foreach ($Merchandise->inventory as $key => $inventory)
                                <tr>
                                    <td>{{ $inventory->product_id }}</td>
                                    <td>{{ Config::get('constants.color')[$inventory->color_id]['name'] }}</td>
                                    <th class="px-3 py-2" style="
                                        color:#{{ (Config::get('constants.color')[$inventory->color_id]['font-color'] ?? 'fff') }};
                                        background-color:#{{ Config::get('constants.color')[$inventory->color_id]['hex'] }}">
                                        {{ Config::get('constants.size')[$inventory->size_id] }}
                                    </th>
                                    <td>{{ $inventory->amount }}</td>
                                </tr>
                            @endforeach
                            </table>
                        </div>
                        --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection