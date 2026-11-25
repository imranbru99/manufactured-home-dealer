<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\KnowledgeArticle;
use App\Models\Post;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        $posts = Post::query()->where('is_published', true)->latest('published_at')->take(3)->get();

        return view('pages.home', compact('posts'));
    }

    public function services(): View
    {
        return view('pages.services');
    }

    public function serviceShow(string $slug): View
    {
        $services = $this->serviceCatalog();
        abort_unless(isset($services[$slug]), 404);

        return view('services.show', [
            'slug' => $slug,
            'service' => $services[$slug],
            'services' => $services,
        ]);
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function contactStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['nullable', 'string', 'max:180'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::query()->create($data);

        return back()->with('success', 'Thanks! Prairieform will follow up within one business day.');
    }

    public function blog(): View
    {
        $posts = Post::query()->where('is_published', true)->latest('published_at')->paginate(9);

        return view('blog.index', compact('posts'));
    }

    public function blogShow(string $slug): View
    {
        $post = Post::query()->where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('blog.show', compact('post'));
    }

    public function knowledge(): View
    {
        $articles = KnowledgeArticle::query()->where('is_published', true)->orderBy('category')->orderBy('title')->get()->groupBy('category');

        return view('kb.index', compact('articles'));
    }

    public function knowledgeShow(string $slug): View
    {
        $article = KnowledgeArticle::query()->where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('kb.show', compact('article'));
    }

    public function chat(Request $request, AiService $ai)
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'session_id' => ['nullable', 'string', 'max:80'],
        ]);

        $result = $ai->chat($data['message'], $data['session_id'] ?? null);

        return response()->json($result);
    }

    /** @return array<string, array<string, string>> */
    protected function serviceCatalog(): array
    {
        return [
            'lot-tour' => [
                'title' => 'Sales Lot Tour',
                'summary' => 'Walk single- and multi-section models with a product specialist.',
                'body' => 'Prairieform tours highlight floor plans, insulation packages, and option upgrades so buyers compare real inventory — not just printouts.',
                'image' => 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1600&q=80',
            ],
            'home-sale' => [
                'title' => 'Manufactured Home Sale',
                'summary' => 'Contract-to-close support for new homes on our lot.',
                'body' => 'We manage deposits, option selections, and delivery windows with inventory locked in the ERP so the home you buy is the home that ships.',
                'image' => 'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?auto=format&fit=crop&w=1600&q=80',
            ],
            'finance-support' => [
                'title' => 'Finance & Paperwork Support',
                'summary' => 'Document packets lenders and land offices commonly request.',
                'body' => 'Prairieform helps assemble serials, invoices, and setup scopes so financing and land placement stay coordinated.',
                'image' => 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1600&q=80',
            ],
            'delivery-transport' => [
                'title' => 'Delivery & Transport',
                'summary' => 'Scheduled transport to your land or community.',
                'body' => 'We coordinate haul windows, escorts when required, and site access so delivery day is planned — not improvised.',
                'image' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1600&q=80',
            ],
            'setup-blocking' => [
                'title' => 'Setup & Blocking',
                'summary' => 'Placement, blocking, and utility-ready handoff.',
                'body' => 'Setup crews place the home, complete blocking, and document utility stubs so local trades can finish without guessing.',
                'image' => 'https://images.unsplash.com/photo-1600573472591-ee6981cf35b6?auto=format&fit=crop&w=1600&q=80',
            ],
            'warranty-handoff' => [
                'title' => 'Warranty Handoff',
                'summary' => 'Manufacturer warranty orientation for new owners.',
                'body' => 'We walk systems, document serials, and explain claim paths so the first year of ownership is not a scavenger hunt.',
                'image' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1600&q=80',
            ],
        ];
    }
}
