@extends('layout.template')

@section('title', 'Homepage')

@section('content')

{{-- Memanggil partial komponen alert --}}
@include('partials.alert')

<h1>Popular Movie</h1>
<div class="row">
    @foreach ($movies as $movie)
        {{-- Memanggil partial komponen movie card untuk setiap film --}}
        @include('partials.movie-card', ['movie' => $movie])
    @endforeach
    
    <div class="d-flex justify-content-center mt-4">
        {{ $movies->links() }}
    </div>
</div>
@endsection