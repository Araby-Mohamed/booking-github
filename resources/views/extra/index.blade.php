@extends('layout.base')

@section('content')
<h1>إضافات</h1>

<p><a href="{{ route('extra.create') }}" type="button" class="btn btn-primary">أضف المزيد</a></p>

<table class="table table-hover">
    <thead class="thead-dark">
    <tr>
        <th>الاسم</th>
        <th>السعر</th>
        <th>لكل</th>
        <th>أيقونة</th>
        <th width="5%"></th>
    </tr>
    </thead>
    <tbody>
    @foreach($extras as $extra)
        <tr>
            <td>{{ $extra->name }}</td>
            <td>&euro; {{ $extra->price }}</td>
            <td>{{ $extra->per }}</td>
            <td>{!! $extra->icon !!}</td>
            <td>
                <div class="btn-group" role="group">
                    <a href="{{ route('extra.edit', $extra->id) }}"
                        class="btn btn-success btn-sm">التعديل</a>
                    <a href="{{ route('extra.delete', $extra->id) }}"
                        class="btn btn-success btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
