@extends('layouts.app')

@section('title', 'Prairieform Homes')
@section('meta_description', 'Single- and multi-section manufactured homes on our Tulsa lot — model tours, delivery, setup, and paperwork that lenders and land offices expect.')

@section('content')
<section class="hero">
    <div class="wrap hero-inner">
        <h1>Prairieform<br>Homes</h1>
        <p>Single- and multi-section manufactured homes on our Tulsa lot — model tours, delivery, setup, and paperwork that lenders and land offices expect.</p>
        <div class="hero-ctas">
            <a class="btn btn-primary" href="{{ route('contact') }}">Tour the sales lot</a>
            <a class="btn btn-ghost" href="{{ route('services') }}">Browse dealer services</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <h2>Services</h2>
        <p class="lede">Prairieform sells and sets manufactured homes across Tulsa and Northeast Oklahoma — inventory tours, financing support, delivery, and setup.</p>
        <div class="grid-3">
            @foreach([
                ['lot-tour','Sales Lot Tours','Walk real inventory with clear specs.','https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1600&q=80'],
                ['delivery-transport','Delivery','Scheduled transport to land or community.','https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?auto=format&fit=crop&w=1600&q=80'],
                ['setup-blocking','Setup & Blocking','Placement ready for utility trades.','https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1600&q=80'],
            ] as [$slug,$title,$blurb,$img])
                <a class="service-tile" href="{{ route('services.show', $slug) }}">
                    <img src="{{ $img }}" alt="{{ $title }}">
                    <div class="pad">
                        <h3>{{ $title }}</h3>
                        <p>{{ $blurb }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>Why Prairieform</h2>
        <p class="lede">Northeast Oklahoma dealers who treat delivery and setup as part of the sale — not a handoff you chase alone.</p>
        <div class="grid-3">
            <div class="panel">
                <h3>Models you can walk</h3>
                <p>Inventory on the lot with clear specs, not brochure-only surprises.</p>
            </div>
            <div class="panel">
                <h3>Delivery + setup</h3>
                <p>Transport, blocking, and utility-ready setup coordinated from one team.</p>
            </div>
            <div class="panel">
                <h3>ERP-run deals</h3>
                <p>Inventory, deposits, delivery dates, and invoices live together for buyers and lenders.</p>
            </div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>Ops dashboard preview</h2>
        <p class="lede">A glimpse of the Prairieform ERP used to dispatch crews, track inventory, and close invoices.</p>
        <div class="dash-preview">
            <div>
                <div class="grid-3" style="grid-template-columns:repeat(3,1fr)">
                    <div class="metric"><strong>12</strong> Homes in stock</div>
                    <div class="metric"><strong>$2.4M</strong> Pipeline</div>
                    <div class="metric"><strong>6</strong> Setups booked</div>
                </div>
                <div class="bars">
                    <span style="height:45%"></span><span style="height:70%"></span><span style="height:55%"></span>
                    <span style="height:85%"></span><span style="height:60%"></span><span style="height:92%"></span>
                    <span style="height:68%"></span>
                </div>
            </div>
            <div>
                <h3 style="font-family:var(--font-display);margin-top:0">Live modules</h3>
                <ul style="line-height:1.9;padding-left:1.1rem;color:rgba(255,255,255,.86)">
                    <li>Customers & jobs</li>
                    <li>Inventory / products</li>
                    <li>Invoices & payments</li>
                    <li>Appointments</li>
                    <li>AI Studio & chatbot logs</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>From the journal</h2>
        <p class="lede">Practical notes for customers and crews.</p>
        <div class="grid-3">
            @foreach($posts as $post)
                <a class="panel" href="{{ route('blog.show', $post->slug) }}">
                    <h3>{{ $post->title }}</h3>
                    <p>{{ $post->excerpt }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
