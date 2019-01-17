
    <i class="material-icons" style="vertical-align:middle; color:green; cursor:pointer;" onclick="showForm(this);">add_box</i>
    <form method="POST" action="/admin/category/add" class="form-inline" style="display:none;">
        <input type="text" class="form-control form-control-sm" name="name">
        <input type="submit" class="btn btn-sm btn-success">
        @csrf
        <input type="text" value="{{ $slot }}" name="parent">
        <input type="text" value="{{ $type }}" name="type">
    </form>