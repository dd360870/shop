
    <i class="material-icons" style="vertical-align:middle; color:green; cursor:pointer;" onclick="showForm(this);">add_box</i>
    <form method="POST" action="/admin/category/add" class="form-inline" style="display:none;">
        <input type="text" class="form-control form-control-sm" name="name">
        <button type="submit" class="btn btn-sm btn-success">Submit</button>
        @csrf
        <input type="text" value="{{ $slot }}" name="parent" style="display:none;">
        <input type="text" value="{{ $type }}" name="type" style="display:none;">
    </form>