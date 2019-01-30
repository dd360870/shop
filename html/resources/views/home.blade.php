@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            @if (isset($alert) || $alert=Session::get('alert', false))
                @component('components.alert', ['type' => $alert['type']])
                    {{ $alert['message'] }}
                @endcomponent
            @endif
            <div class="border-bottom border-left border-right p-3">
                <h1 style="font-size:1em;">最新</h1>
                <div id="row_new">
                    <div class="card-group">
                        @foreach ($new_items as $item)
                            <div class="card" style="background-color: #EEE;">
                                <img src="{{ $item->photoUrl }}" class="card-img-top">
                                <div class="p-2" style="overflow:auto;">
                                    <h5 class=""><a href="{{ '/merchandise/'.$item->id }}">{{ $item->name }}</a></h5>
                                    <p class="text-muted m-0" style="float:right;">NT$ {{ $item->price }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <h1>熱銷</h1>
                <div id="row_hot">
                    <div class="card-group">
                        @foreach ($hot_items as $item)
                            <div class="card" style="background-color: #EEE;">
                                <img src="{{ $item->photoUrl }}" class="card-img-top">
                                <div class="p-2" style="overflow:auto;">
                                    <h5 class=""><a href="{{ '/merchandise/'.$item->id }}">{{ $item->name }}</a></h5>
                                    <p class="text-muted m-0" style="float:right;">NT$ {{ $item->price }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
    function adjust(container, width) {
        let elements = $(container).find('.card-group:last').children();
        let size = elements.length;

        for (let i = (size<width ? 0 : width); i < size; i += width) {
            console.log(i);

            let string = '<div class="card-group">';
            for (var j = 0; j < width && i + j < size; j++) {
                string += elements.get(i + j).outerHTML;
                elements.get(i + j).remove();
            }
            for (let k = 0; k < width - j; k++) {
                string += '<div class="card" style="visibility: hidden;"></div>';
            }
            string += '</div>';
            $(container).find('.card-group:last').after(string);
        }
    }
    $(document).ready(function () {
        adjust('#row_hot', 4);
        adjust('#row_new', 4);
    });

</script>
@endsection
