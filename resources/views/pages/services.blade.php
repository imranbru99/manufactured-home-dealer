@extends('layouts.app')
@section('title', 'Services')
@section('content')
<section class="section">
    <div class="wrap">
        <h2>Services</h2>
        <p class="lede">Prairieform sells and sets manufactured homes across Tulsa and Northeast Oklahoma — inventory tours, financing support, delivery, and setup.</p>
        <div class="grid-3">
            @foreach([
                ['lot-tour','Sales Lot Tour','https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1600&q=80'],
                ['home-sale','Manufactured Home Sale','https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?auto=format&fit=crop&w=1600&q=80'],
                ['finance-support','Finance & Paperwork Support','https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1600&q=80'],
                ['delivery-transport','Delivery & Transport','https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1600&q=80'],
                ['setup-blocking','Setup & Blocking','https://images.unsplash.com/photo-1600573472591-ee6981cf35b6?auto=format&fit=crop&w=1600&q=80'],
                ['warranty-handoff','Warranty Handoff','https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1600&q=80'],
            ] as [$slug,$title,$img])
                <a class="service-tile" href="{{ route('services.show', $slug) }}">
                    <img src="{{ $img }}" alt="{{ $title }}">
                    <div class="pad"><h3>{{ $title }}</h3></div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
