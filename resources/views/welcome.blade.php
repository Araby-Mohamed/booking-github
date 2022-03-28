@extends('layout.base')
@section('content')
<h1>أهلا بك!</h1>
<h3>نظرة عامة على الأسبوعين المقبلين</h3>
<table class="table table-hover">
  <thead>
    <tr>
      <th>من</th>
      <th>الى</th>
      <th>الحجز</th>
      <th>دولة</th>
      <th># ضيوف</th>
      <th>غرفة</th>
  </thead>
  <tbody>
    @foreach($bookings as $booking)
    <tr @if ($booking->isNow())
      class="booking__now @if ($booking->color()['luma'] > 180.0) reversed @endif"
      style="background-color: {{$booking->color()['color'] }}" @endif>
      <td>{{ $booking->arrival->format('d/m/Y') }}</td>
      <td>{{ $booking->departure->format('d/m/Y') }}</td>
      <td>
        <a href="{{ route('planning', ['date' => $booking->arrival->toDateString()]) }}">
          {{ $booking->customer->name }}
        </a>
      </td>
      <td>{{ $booking->customer->country_str }}</td>
      <td>{{ $booking->guests }}</td>
      <td>
        {{ $booking->rooms[0]->name }}
        @if ($booking->rooms[0]->properties->options['part'] != -1)
          &mdash; غرفة {{ $booking->rooms[0]->properties->options['part']+1 }}
        @endif
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
<h3 class="mt-5">غادر اليوم</h3>
<table class="table table-hover">
  <thead>
    <tr>
      <th>من</th>
      <th>الى</th>
      <th>الحجز</th>
      <th># ضيوف</th>
      <th>غرفة</th>
      <th>السعر</th>
  </thead>
  <tbody>
    @foreach($leaving as $booking)
    <tr @if ($booking->isNow())
      class="booking__now @if ($booking->color()['luma'] > 180.0) reversed @endif"
      style="background-color: {{$booking->color()['color'] }}" @endif>
      <td>{{ $booking->arrival->format('d/m/Y') }}</td>
      <td>{{ $booking->departure->format('d/m/Y') }}</td>
      <td>
        <a href="{{ route('planning', ['date' => $booking->arrival->toDateString()]) }}">
          {{ $booking->customer->name }}
        </a>
      </td>
      <td>{{ $booking->guests }}</td>
      <td>{{ $booking->rooms[0]->name }}</td>
      <td>&euro;&nbsp;{{ $booking->basePrice * (1-$booking->discount) }}</td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection
