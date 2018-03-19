@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <div class="col-12">
            <div class="card">
                <div class="card-header">Delivery</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-1 pt-1"><p>Distance :</p></div>
                        <div class="col-2 pt-1"><p>{{$delivereis->distance}}</p></div>
                        <div class="col-1 pt-1"><p>Duration :</p></div>
                        <div class="col-2 pt-1"><p>{{$delivereis->duration}}</p></div>
                        <div class="col-1 pt-1"><p>Price : </p></div>
                        <div class="col-1 pt-1"><p>{{$delivereis->price}} $</p></div>
                        <div class="col-2"><a class="waves-effect waves-light btn modal-trigger paid " href="#modal1">Paid</a></div>
                        <div class="col-2 "><a href="{{URL::asset('delete/'.$delivereis->id)}}" class="btn btn-danger float-right" >Cancel</a></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="modal1" class="modal" style="height: 180px">
            <div class="modal-content">
                <h4>Paid Delivery cost</h4>
                <p>Price : {{$delivereis->price}} $</p>

            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-action modal-close waves-effect btn waves-red btn-danger m-2 close-paid">Close</a>
                <a href="{{URL::asset('paid/'.$delivereis->id)}}" class="waves-effect waves-green btn btn-success">Paid</a>
            </div>
        </div>
    </div>
</div>
@endsection
