@extends('layouts.app')

@section('content')

<div class="container mt-5">

    <div class="text-center">

        <h1 class="display-1 text-danger">
            403
        </h1>

        <h3>
            Forbidden
        </h3>

        <p class="text-muted">

            Anda tidak memiliki hak akses ke halaman ini.

        </p>

        <a
            href="{{ route('dashboard') }}"
            class="btn btn-success">

            Kembali ke Dashboard

        </a>

    </div>

</div>

@endsection