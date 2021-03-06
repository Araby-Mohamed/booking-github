@extends('layout.base')

@section('content')
@php use App\Http\Controllers\StatsController; @endphp
<h1>إحصائيات</h1>

<form method="GET">
  <div class="form-row">
    <div class="form-group col-md-3">
      <div class="form-group">
        <label for="statType">النوع</label>
          <select class="custom-select" name="type" id="statType">
            <option value="{{ StatsController::GUESTS_PER_COUNTRY }}"
                @isset($type) @if($type === StatsController::GUESTS_PER_COUNTRY) selected @endif @endisset>الضيوف حسب الدولة</option>
            <option value="{{ StatsController::NO_OF_NIGHTS }}"
                @isset($type) @if($type === StatsController::NO_OF_NIGHTS) selected @endif @endisset>عدد الإقامات الليلية</option>
          </select>
      </div>
    </div>
    <div class="form-group col-md-3">
      <div class="form-group">
        <label for="fromDateInput">من</label>
        <div class="input-group date">
          <input type="date" class="form-control actual_range" name="from" id="fromDateInput" autocomplete="off" required
            @if(isset($from_date)) value="{{ $from_date->format('Y-m-d') }}"@endif>
        </div>
      </div>
    </div>
    <div class="form-group col-md-3">
      <div class="form-group">
        <label for="toDateInput">الى</label>
        <div class="input-group date">
          <input type="date" class="form-control actual_range" name="to" id="toDateInput" autocomplete="off" required
            @if(isset($to_date)) value="{{ $to_date->format('Y-m-d') }}"@endif>
        </div>
      </div>
    </div>
    <div class="form-group col-md-3">
      <div class="form-group">
        <label>&nbsp;</label>
        <button type="submit" class="form-control btn btn-primary">انشاء</button>
      </div>
    </div>
  </div>
  <div class="form-row">
    <div class="form-group col-md-9">
      <div class="form-group">
        <p>الغرف</p>
        @foreach ($rooms as $room)
          <div class="custom-control custom-checkbox">
            <input class="custom-control-input" type="checkbox"
                name="rooms[]"
                id="room{{ $room->id }}"
                value="{{ $room->id }}"
                @if (count($selectedRooms) === 0 || in_array($room->id, $selectedRooms)) checked @endif>
            <label class="custom-control-label" for="room{{ $room->id }}">{{ $room->name }}</label>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</form>

@if ($type === StatsController::GUESTS_PER_COUNTRY)
    <table class="table table-hover">
        <thead>
        <tr>
            <th>الدولة</th>
            <th>الحجوزات</th>
            <th>ضيوف</th>
            <th>متوسط الضيوف لكل حجز</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($stats as $country)
            @if ($loop->last)
                @break
            @endif
            <tr>
                <td>{{ $country['country_name'] }}</td>
                <td>{{ $country['bookings'] }}</td>
                <td>{{ $country['guests'] }}</td>
                <td>{{ round($country['guests_per_booking'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>المجموع</th>
                <th>{{ $stats ? $stats['totals']['bookings'] : 0 }}</th>
                <th>{{ $stats ? $stats['totals']['guests'] : 0 }}</th>
                <th>{{ $stats ? round($stats['totals']['guests_per_booking'], 2) : 0 }}</th>
            </tr>
        </tfoot>
    </table>
@elseif ($type === StatsController::NO_OF_NIGHTS)
    <table class="table table-hover">
        <thead>
        <tr>
            <th>عدد الإقامات الليلية</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $stats['nights'] }} ليالي</td>
            </tr>
        </tbody>
    </table>
@endif
@endsection
