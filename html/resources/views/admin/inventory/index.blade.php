@extends('admin.base')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            @if(isset($alert) || $alert = Session::get('alert', false))
                @component('components.alert', ['type' => $alert['type']])
                    {{ $alert['message'] }}
                @endcomponent
            @endif
            <div>
                <div class="border-bottom border-left border-right p-3">
                    <form style="float:right;" method="GET">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <select class="input-group-text" name="method">
                                    <option value="name">Name</option>
                                    <option value="product_id">Product ID</option>
                                </select>
                            </div>
                            <input type="text" class="form-control" name="input">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="submit">Search</button>
                            </div>
                        </div>
                    </form>
                    <table class="table table-bordered" id="inventoriesTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Product_ID</th>
                                <th>庫存</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($inventories as $i)
                            <tr>
                                <td>{{ $i->merchandise->name.'/'.Config::get('constants.color')[$i->color_id]['name'].'/'.Config::get('constants.size')[$i->size_id] }}</td>
                                <td>{{ $i->product_id }}</td>
                                <td>{{ $i->amount }}</td>
                                <td>
                                    <form method="POST" action="/admin/inventory/{{ $i->product_id }}" class="inStockForm">
                                        @csrf
                                        @method('patch')
                                        <div class="form-row">
                                            <div class="col-sm-8">
                                                <input type="number" class="form-control" min=1 name="amount" placeholder="數量">
                                            </div>
                                            <div class="col-sm-4">
                                                <button type="submit" class="btn btn-success">進貨</button>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

<script type="text/javascript">
$('#inventoriesTable').on('submit','form.inStockForm',function(e) {
    submitForm(e, $(this));
});
function submitForm(e, form) {
    var url = form.attr('action');

    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(), // serializes the form's elements.
        success: function(data)
        {
            alert(data); // show response from the php script.
            location.reload();
        },
        error: function(jqXHR, textStatus, errorThrown ) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
    e.preventDefault(); // avoid to execute the actual submit of the form.
}
</script>

@endsection
