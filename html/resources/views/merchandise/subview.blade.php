@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        {{-- Category sidebar --}}
        <div class="col-lg-2">
            <h4>{{ $categories[0]->lev1 }}</h4>
            <ul>
            @for ($i = 0, $count = count($categories); $i < $count; )
                @if(isset($categories[$i]->lev3))
                    <li>{{ $categories[$i]->lev2 }}
                        <ul>
                        @for ($current_lev2=$categories[$i]->lev2_id; $i < $count && $categories[$i]->lev2_id==$current_lev2;$i++)
                            <li><a href="{{ '?category='.$categories[$i]->lev3_id }}" style="color:black;
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
        {{-- Main content --}}
        <div class="col-lg-10">
            @if (isset($alert) || $alert=Session::get('alert', false))
                @component('components.alert', ['type' => $alert['type']])
                    {{ $alert['message'] }}
                @endcomponent
            @endif
            <div class="border-bottom border-left border-right p-3">
                @if ($merchandises->isEmpty())
                    <h3>No merchandise in this category.</h3>
                @endif
                <div class="card-group" id="first-group">
                @foreach ($merchandises as $m)
                    <div class="card">
                        <img class="card-img-top" src="{{ $m->photo ? Storage::disk('s3')->url($m->photo) : 'default-merchandise.jpg' }}" alt="{{ $m->name }}">
                        <div class="card-body" style="background-color:#eee;">
                            <h5 class="card-title">
                                <a href="/merchandise/{{ $m->id }}">{{ $m->name }}</a>
                            </h5>
                            <p class="card-text">NT$ {{ $m->price }}
                                @if($m->amount < 1)
                                    <span style="color:#A33">無庫存</span>
                                @endif
                            </p>
                        </div>
                        <div class="card-footer" style="display:none;"></div>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        let width = 4;
        let elements = $('#first-group').children();
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
            $('.card-group:last').after(string);
        }
    });

</script>
@endsection
