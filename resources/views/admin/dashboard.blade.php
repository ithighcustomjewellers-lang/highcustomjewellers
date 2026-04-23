@extends('admin.layouts.layout')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">

    <div class="row g-3">

        <div class="col-md-3">
            <div class="card-box bg-primary text-white p-3 rounded">
                <h5>Total Users</h5>
                <h3>120</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box bg-success text-white p-3 rounded">
                <h5>Total Orders</h5>
                <h3>80</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box bg-warning text-white p-3 rounded">
                <h5>Revenue</h5>
                <h3>$5000</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box bg-danger text-white p-3 rounded">
                <h5>Pending</h5>
                <h3>15</h3>
            </div>
        </div>

    </div>

</div>

@endsection
