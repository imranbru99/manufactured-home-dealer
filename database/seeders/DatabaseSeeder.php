<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\KnowledgeArticle;
use App\Models\Payment;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin', 'manager', 'staff'] as $role) {
            Role::findOrCreate($role);
        }

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '(918) 555-0183',
            ]
        );
        $admin->syncRoles(['admin']);

        User::query()->updateOrCreate(
            ['email' => 'manager@prairieform.example'],
            [
                'name' => 'Sofia Reyes',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'phone' => '(918) 555-0183',
            ]
        )->syncRoles(['manager']);

        User::query()->updateOrCreate(
            ['email' => 'lot@prairieform.example'],
            [
                'name' => 'Marcus Hale',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'phone' => '(918) 555-0183',
            ]
        )->syncRoles(['staff']);

        $settings = [
            'company_name' => 'Prairieform Homes',
            'company_phone' => '(918) 555-0183',
            'company_email' => 'hello@prairieform.example',
            'whatsapp' => '19185550183',
            'service_area' => 'Tulsa, Broken Arrow, Owasso & Northeast Oklahoma',
            'tax_rate' => '8.517',
        ];
        foreach ($settings as $key => $value) {
            Setting::setValue($key, $value);
        }

        $products = [
            ['name' => 'Single-Section Display Model A', 'sku' => 'MH-SS-A', 'category' => 'equipment', 'unit_cost' => 62000, 'unit_price' => 89400, 'stock_qty' => 2],
            ['name' => 'Multi-Section Display Model B', 'sku' => 'MH-MS-B', 'category' => 'equipment', 'unit_cost' => 98000, 'unit_price' => 142500, 'stock_qty' => 1],
            ['name' => 'Delivery Escort Allowance', 'sku' => 'DEL-ES-1', 'category' => 'service', 'unit_cost' => 400, 'unit_price' => 1200, 'stock_qty' => 999],
            ['name' => 'Setup & Blocking Package', 'sku' => 'SET-BK-1', 'category' => 'service', 'unit_cost' => 2800, 'unit_price' => 6500, 'stock_qty' => 999],
            ['name' => 'Option Upgrade — Kitchen Pack', 'sku' => 'OPT-KT-1', 'category' => 'supplies', 'unit_cost' => 1800, 'unit_price' => 4200, 'stock_qty' => 15],
            ['name' => 'Skirting Starter Kit', 'sku' => 'SK-ST-1', 'category' => 'supplies', 'unit_cost' => 650, 'unit_price' => 1450, 'stock_qty' => 20],
            ['name' => 'Finance Doc Pack', 'sku' => 'DOC-FN-1', 'category' => 'supplies', 'unit_cost' => 25, 'unit_price' => 0, 'stock_qty' => 200],
            ['name' => 'Warranty Orientation Visit', 'sku' => 'WAR-OR-1', 'category' => 'service', 'unit_cost' => 0, 'unit_price' => 150, 'stock_qty' => 999],
        ];
        foreach ($products as $product) {
            Product::query()->updateOrCreate(['sku' => $product['sku']], $product + ['unit' => 'each', 'is_active' => true]);
        }

        $customers = collect([
            ['name' => 'Ashley Moore', 'email' => 'ashley@example.com', 'phone' => '(918) 555-3301', 'company' => null, 'city' => 'Tulsa', 'state' => 'OK', 'zip' => '74145', 'address' => '4100 S Memorial'],
            ['name' => 'Brian Yates', 'email' => 'brian@arrowlandok.com', 'phone' => '(918) 555-3310', 'company' => 'Arrow Land Co', 'city' => 'Broken Arrow', 'state' => 'OK', 'zip' => '74012', 'address' => '2300 W New Orleans'],
            ['name' => 'Carmen Soto', 'email' => 'ops@owassopads.com', 'phone' => '(918) 555-3399', 'company' => 'Owasso Pads', 'city' => 'Owasso', 'state' => 'OK', 'zip' => '74055', 'address' => '9001 N 129th E Ave'],
            ['name' => 'Derek Lang', 'email' => 'derek.lang@example.com', 'phone' => '(918) 555-3188', 'company' => null, 'city' => 'Tulsa', 'state' => 'OK', 'zip' => '74133', 'address' => '8800 S Memorial'],
            ['name' => 'Elena Park', 'email' => 'elena@oakandpine.org', 'phone' => '(918) 555-3177', 'company' => 'Oak & Pine Realty', 'city' => 'Broken Arrow', 'state' => 'OK', 'zip' => '74011', 'address' => '1200 W Kenosha'],
        ])->map(fn ($c) => Customer::query()->updateOrCreate(['email' => $c['email']], $c));

        $projects = [
            ['customer' => 0, 'title' => 'Sale + setup — Moore single-section', 'service_type' => 'setup', 'status' => 'scheduled', 'sq_ft' => 1100, 'quoted_amount' => 92000],
            ['customer' => 1, 'title' => 'Multi-section delivery — Arrow Land', 'service_type' => 'delivery', 'status' => 'in_progress', 'sq_ft' => 1600, 'quoted_amount' => 148000],
            ['customer' => 2, 'title' => 'Lot tour follow-up — Owasso Pads', 'service_type' => 'tour', 'status' => 'quoted', 'sq_ft' => 0, 'quoted_amount' => 0],
            ['customer' => 3, 'title' => 'Finance packet — Lang purchase', 'service_type' => 'finance', 'status' => 'completed', 'sq_ft' => 1200, 'quoted_amount' => 105000, 'final_amount' => 104200],
            ['customer' => 4, 'title' => 'Warranty handoff — Kenosha placement', 'service_type' => 'warranty', 'status' => 'invoiced', 'sq_ft' => 1400, 'quoted_amount' => 132000, 'final_amount' => 131500],
        ];

        $projectModels = [];
        foreach ($projects as $i => $p) {
            $customer = $customers[$p['customer']];
            $projectModels[] = Project::query()->updateOrCreate(
                ['job_number' => 'JOB-26'.str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'customer_id' => $customer->id,
                    'title' => $p['title'],
                    'service_type' => $p['service_type'],
                    'status' => $p['status'],
                    'sq_ft' => $p['sq_ft'],
                    'quoted_amount' => $p['quoted_amount'],
                    'final_amount' => $p['final_amount'] ?? null,
                    'start_date' => now()->subDays(10 - $i)->toDateString(),
                    'site_address' => trim(($customer->address ?? '').', '.$customer->city.', '.$customer->state),
                    'notes' => 'Seeded demo job for Prairieform ERP.',
                ]
            );
        }

        foreach ([0, 1, 3, 4] as $idx) {
            $project = $projectModels[$idx];
            $invoice = Invoice::query()->updateOrCreate(
                ['invoice_number' => 'INV-26'.str_pad((string) ($idx + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'customer_id' => $project->customer_id,
                    'project_id' => $project->id,
                    'issue_date' => now()->subDays(8 - $idx)->toDateString(),
                    'due_date' => now()->addDays(14)->toDateString(),
                    'subtotal' => $project->final_amount ?? $project->quoted_amount,
                    'tax' => round(($project->final_amount ?? $project->quoted_amount) * (8.517/100), 2),
                    'total' => round(($project->final_amount ?? $project->quoted_amount) * (1+8.517/100), 2),
                    'amount_paid' => in_array($project->status, ['completed', 'invoiced'], true) ? round(($project->final_amount ?? $project->quoted_amount) * (1+8.517/100), 2) : 0,
                    'status' => in_array($project->status, ['completed', 'invoiced'], true) ? 'paid' : 'sent',
                ]
            );

            if ($invoice->status === 'paid') {
                Payment::query()->updateOrCreate(
                    ['reference' => 'PMT-'.$invoice->invoice_number],
                    [
                        'invoice_id' => $invoice->id,
                        'amount' => $invoice->total,
                        'paid_at' => now()->subDays(3)->toDateString(),
                        'method' => 'card',
                        'notes' => 'Demo payment',
                    ]
                );
            }
        }

        Appointment::query()->updateOrCreate(
            ['title' => 'Lot tour — Moore'],
            [
                'customer_id' => $customers[0]->id,
                'project_id' => $projectModels[0]->id,
                'type' => 'tour',
                'starts_at' => now()->addDays(1)->setTime(10, 0),
                'ends_at' => now()->addDays(1)->setTime(11, 0),
                'status' => 'scheduled',
                'location' => $customers[0]->address,
            ]
        );
        Appointment::query()->updateOrCreate(
            ['title' => 'Setup day — Arrow Land'],
            [
                'customer_id' => $customers[1]->id,
                'project_id' => $projectModels[1]->id,
                'type' => 'setup',
                'starts_at' => now()->addDays(2)->setTime(8, 0),
                'ends_at' => now()->addDays(2)->setTime(16, 0),
                'status' => 'confirmed',
                'location' => $customers[1]->address,
            ]
        );

        $posts = [
            [
                'title' => 'How to Compare Manufactured Home Specs on a Sales Lot',
                'excerpt' => 'Square footage is not the whole story — insulation and options matter.',
                'cover' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80',
                'body' => "Prairieform walks insulation packages, appliance lines, and transport constraints so buyers compare apples to apples.",
            ],
            [
                'title' => 'Delivery Day: What Landowners Should Prepare',
                'excerpt' => 'Access, setback stakes, and utility stubs save hours.',
                'cover' => 'https://images.unsplash.com/photo-1600573472591-ee6981cf35b6?auto=format&fit=crop&w=1200&q=80',
                'body' => "We send a site checklist before haul day so trucks and setup crews are not guessing at the gate.",
            ],
            [
                'title' => 'Finance Packets That Lenders Actually Open',
                'excerpt' => 'Serials, invoices, and setup scopes in one place.',
                'cover' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80',
                'body' => "Our ERP keeps deal documents together so land offices and lenders are not chasing emails.",
            ],
        ];
        foreach ($posts as $post) {
            Post::query()->updateOrCreate(
                ['slug' => Str::slug($post['title'])],
                [
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'],
                    'body' => $post['body'],
                    'cover_image' => $post['cover'],
                    'is_published' => true,
                    'published_at' => now()->subDays(random_int(2, 20)),
                ]
            );
        }

        $articles = [
            ['category' => 'issues', 'title' => 'Delivery delayed by site access', 'body' => "Widen gate openings, confirm turn radius, and mark soft ground. Prairieform can reschedule once access is verified."],
            ['category' => 'issues', 'title' => 'Lender asks for serial numbers', 'body' => "Export inventory serials and invoice from the deal record in the ERP."],
            ['category' => 'guides', 'title' => 'Buyer checklist before the lot tour', 'body' => "Bring land address. Note septic/water status. List must-have bedrooms. Share financing stage."],
            ['category' => 'guides', 'title' => 'From deposit to setup', 'body' => "Deposit locks inventory.
Options confirmed.
Delivery scheduled.
Setup and warranty handoff."],
            ['category' => 'admin', 'title' => 'Admin ERP quick start', 'body' => "Login at /admin with admin@example.com / password.
1. Add buyers.
2. Create a deal / setup job.
3. Schedule tours and deliveries.
4. Track home inventory.
5. Invoice and record payments.
6. Use AI Studio.
7. Update Settings."],
            ['category' => 'admin', 'title' => 'Roles: Admin, Manager, Staff', 'body' => "Admin: full ERP + settings + users.
Manager: deals, inventory, invoices, scheduling.
Staff: lot and setup records."],
            ['category' => 'troubleshoot', 'title' => 'Home inventory shows sold but still on lot', 'body' => "Update deal status and stock flags after physical lot check."],
            ['category' => 'troubleshoot', 'title' => 'Chatbot giving demo answers only', 'body' => "Add OPENAI_API_KEY or GEMINI_API_KEY to .env, then clear config cache."],
        ];
        foreach ($articles as $article) {
            KnowledgeArticle::query()->updateOrCreate(
                ['slug' => Str::slug($article['title'])],
                [
                    'title' => $article['title'],
                    'category' => $article['category'],
                    'body' => $article['body'],
                    'is_published' => true,
                ]
            );
        }
    }
}
