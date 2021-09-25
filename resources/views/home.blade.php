@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ __('Make a payment') }}
                </div>

                <div class="card-body">
                    <form action="{{route('pay-now')}}" method="POST" id="paymentForm">
                        @csrf
                        <div class="row">
                            <div class="col-auto">
                                <label for="">How much you want to pay?</label>
                                <input type="number" min="0.01" step="0.01" name="value" class="form-control" value="{{mt_rand(500 , 100000)/100}}">
                            </div>
                            <div class="col-auto">
                                <label for="">Currency</label>
                                <select class="custom-select form-control" name="currecncy" id="currecncy" required>
                                    @foreach ($currencies as $money)
                                        <option value="{{ $money->iso }}">{{ $money->iso }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row mt-3">
                                <div class="col">
                                    <label>Select the desire payment system</label>
                                    <div class="form-group" id="toggler">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            @foreach ($PaymentPlatForm as $item)
                                                <label  class="btn btn-outline-secondary rounded mt-3 p-1"  data-target="#{{$item->name}}Collapse" data-toggle="collapse">
                                                    <input class="d-none" type="radio" name="payment_platform" value="{{$item->id}}"  required>
                                                    <img style="height: 100px;width:200px" class="img-thumbnail" src="{{asset($item->image)}}" >
                                                </label>
                                            @endforeach
                                        </div>
                                        @foreach ($PaymentPlatForm as $item)
                                                <div  id="{{$item->name}}Collapse" class="collapse" data-parent="#toggler">
                                                    @includeIf('components.'.strtolower($item->name) .'-collapse')
                                                </div>
                                            @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2 text-center">
                            <button type="submit" id="paybutton"  class="btn btn-primary btn-sm">Pay Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
