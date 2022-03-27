@extends('layout.base')

@section('title', $booking->customer->name)

@section('content')

<a class="btn btn-primary mt-2 mb-4" href="{{ route('planning', ['date' => $booking->arrival->toDateString() ]) }}">
    العودة إلى النظرة الأسبوعية</a>

<div class="row mt-2">
  <div class="col-sm">
    <h3>معلومات الحجز
      @can('edit.booking')
      <a href="{{ route('booking.delete', $booking) }}" class="btn btn-danger float-right js-delete"><i class="far fa-trash-alt"> </i></a>
      <a href="{{ route('booking.edit', $booking) }}" class="btn btn-primary float-right">تغيير الحجز</a>
      @endcan
    </h3>
    <table class="table table-hover mt-2">
      <tr>
        <th>وصول</th>
        <td>{{ $booking->arrival->formatLocalized('%a, %e %b %Y') }} &mdash; {{ $booking->arrival->formatLocalized('%H:%M') }}</td>
      </tr>

      <tr>
        <th>مقال</th>
        <td>{{ $booking->departure->formatLocalized('%a, %e %b %Y') }}</td>
      </tr>

      <tr>
        <th># ضيوف</th>
        <td>{{ $booking->guests }}</td>
      </tr>

      <tr>
        <th>مجمع</th>
        <td>{{ $booking->composition }}</td>
      </tr>

      <tr>
        <th>غرفة</th>
        <td>
          {{ $booking->rooms[0]->name }}
          @if ($booking->rooms[0]->properties->options['part'] != -1)
            &mdash; غرفة {{ $booking->rooms[0]->properties->options['part']+1 }}
          @endif
        </td>
      </tr>

      <tr>
        <th>السعر الأساسي</th>
        <td>&euro;&nbsp;{{ $booking->basePrice }}</td>
      </tr>

      <tr>
        <th>خصم</th>
        <td>{{ $booking->discount }}&nbsp;%</td>
      </tr>

      <tr>
        <th>الوديعة</th>
        <td>&euro;&nbsp;{{ $booking->deposit }}</td>
      </tr>

      <tr>
        <th>للدفع</th>
        <td>&euro;&nbsp;{{ $booking->remaining }}</td>
      </tr>

      <tr>
        <th>تعليقات</th>
        <td>{!! nl2br($booking->comments) !!}</td>
      </tr>
    </table>
  </div>
  {{-- booker info --}}
  <div class="col-sm">
    <h3>الحجز
      @can('edit.booking')
      <a href="{{ route('guest.edit', [$booking, $booking->customer]) }}" class="btn btn-primary float-right">تغيير الحجز</a>
      @endcan
    </h3>
    <table class="table table-hover mt-2">
      <tr>
        <th>الاسم</th>
        <td
          class="booked {{ $booking->color()['luma'] > 180.0 ? 'reversed' : '' }}"
          style="background-color: {{ $booking->color()['color'] }}">
          {{ $booking->customer->name }}
        </td>
      </tr>
      <tr>
        <th>البريد الإلكتروني</th>
        <td><a href="mailto:{{ $booking->customer->email }}">{{ $booking->customer->email }}</a></td>
      </tr>
      <tr>
        <th>رقم الهاتف</th>
        <td><a href="tel:{{ $booking->customer->phone }}">{{ $booking->customer->phone }}</a></td>
      </tr>
      <tr>
        <th>الدولة</th>
        <td>{{ $booking->customer->country_str }}</td>
      </tr>
    </table>
    @unless($booking->guests === 1)
    <h3>ضيوف إضافيون
      @if ($booking->extraGuests->count() < $booking->guests-1)
        <a href="" class="btn btn-primary float-right js-add-extra-guest">أضف ضيفًا إضافيًا</a>
      @endif
    </h3>
    @endunless
    @unless($booking->extraGuests->isEmpty())
    <table class="table table-hover mt-2" id="extraGuestTable">
    @foreach($booking->extraGuests as $guest)
      <tr>
        <td>{{ $guest->name }}</td>
        <td class="text-right">
          {{-- <a href="" class="btn btn-primary js-edit-extra-guest" data-guest-id="{{ $guest-> id }}"><i class="fas fa-pencil-alt"> </i></a> --}}
          <a href="{{ route('booking.extra.delete', [$booking, $guest]) }}" class="btn btn-danger"><i class="far fa-trash-alt"> </i></a>
        </td>
      </tr>
    @endforeach
    </table>
    @endunless
  </div>
</div>
<h3>إضافات
  <a href="" class="btn btn-primary float-right js-add-extra">أضف المزيد</a>
</h3>
@unless($booking->extras->isEmpty())
<table class="table table-hover mt-2" id="extrasTable">
  <thead class="thead-dark">
    <tr>
      <th></th>
      <th>رقم ال</th>
      <th>الاسم</th>
      <th>السعر</th>
      <th>لكل</th>
      <th>&nbsp;</th>
    </tr>
  </thead>
@foreach($booking->extras as $extra)
  <tr>
    <td>{!! $extra->icon !!}</td>
    <td>{{ $extra->pivot->amount }}</td>
    <td>{{ $extra->name }}</td>
    <td>&euro; {{ $extra->price }}</td>
    <td>{{ $extra->per }}</td>
    <td class="text-right">
      <a href="{{ route('booking.extras.delete', [$booking, $extra]) }}" class="btn btn-danger"><i class="far fa-trash-alt"> </i></a>
    </td>
  </tr>
@endforeach
</table>
@else
<p class="alert alert-light">لا إضافات لهذا الحجز.</p>
@endunless
@can('edit.booking')
<div class="modal" id="deleteBookingModal" tabindex="-1" role="dialog" aria-labelledby="deleteBookingLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">حذف الحجز</h5>
        <button class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4>هل أنت متأكد أنك تريد حذف هذا الحجز؟</h4>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal"></button>
        <button class="btn btn-primary" id="deleteBooking" data-id="{{ $booking->id }}">نعم</button>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="extraGuestModal" tabindex="-1" role="dialog" aria-labelledby="extraGuestModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">ضيف إضافي</h5>
        <button class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <select class="form-control custom-select mt-2 js-extra-guest-select" name="extra-guest" placeholder="Selecteer gast..." id="guestSelect">
          <option></option>
          <option value="new-guest">ضيف جديد...</option>
          @foreach($guests as $guest)
            <option
              @if(isset($booking) && $booking->customer_id == $guest->id) selected @endif value="{{ $guest->id }}">{{ $guest->name }}</option>
          @endforeach
        </select>
        @php unset($guest) @endphp
        <div class="new-extra-guest" style="display: none" data-booking-id="{{ $booking->id }}">
          @include('guests.create_form')
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
        <button class="btn btn-primary" id="addExtraGuest">حفظ</button>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="extrasModal" tabindex="-1" role="dialog" aria-labelledby="extrasModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="extrasModalLabel">أضف المزيد</h5>
        <button class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <select class="form-control custom-select my-2" name="extra" placeholder="Selecteer extra..." id="extraSelect">
          @foreach($extras as $extra)
            <option value="{{ $extra->id }}">{{ $extra->name }}</option>
          @endforeach
        </select>
        <input class="form-control" name="amount" placeholder="رقم ال" type=number min=1 />
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
        <button class="btn btn-primary" id="addExtra">اضافة</button>
      </div>
    </div>
  </div>
</div>
@endcan
@endsection
