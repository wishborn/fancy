<?php

namespace App\Livewire;

use Livewire\Component;

/**
 * Timeline Demo
 *
 * Demonstrates the timeline component with TimelineJS3,
 * including standalone usage and embedding inside a carousel.
 * Copy this file to app/Livewire/TimelineDemo.php
 */
class TimelineExamples extends Component
{
    public array $techTimeline = [
        'title' => [
            'text' => [
                'headline' => 'The History of Computing',
                'text' => '<p>A journey through the key milestones that shaped the digital world.</p>',
            ],
        ],
        'events' => [
            [
                'start_date' => ['year' => 1936],
                'text' => [
                    'headline' => 'Turing Machine',
                    'text' => '<p>Alan Turing publishes his seminal paper introducing the concept of a universal machine, laying the theoretical foundation for modern computing.</p>',
                ],
                'group' => 'Theory',
                'unique_id' => 'turing',
            ],
            [
                'start_date' => ['year' => 1945],
                'text' => [
                    'headline' => 'ENIAC',
                    'text' => '<p>The Electronic Numerical Integrator and Computer, the first general-purpose electronic digital computer, is completed at the University of Pennsylvania.</p>',
                ],
                'media' => [
                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/4/4e/Eniac.jpg',
                    'caption' => 'ENIAC at the University of Pennsylvania',
                    'credit' => 'U.S. Army Photo',
                ],
                'group' => 'Hardware',
                'unique_id' => 'eniac',
            ],
            [
                'start_date' => ['year' => 1969, 'month' => 10, 'day' => 29],
                'text' => [
                    'headline' => 'ARPANET',
                    'text' => '<p>The first message is sent over ARPANET, the precursor to the modern Internet, between UCLA and Stanford Research Institute.</p>',
                ],
                'group' => 'Networking',
                'unique_id' => 'arpanet',
            ],
            [
                'start_date' => ['year' => 1971],
                'text' => [
                    'headline' => 'Microprocessor',
                    'text' => '<p>Intel releases the 4004, the first commercially available microprocessor, revolutionizing computing by putting an entire CPU on a single chip.</p>',
                ],
                'group' => 'Hardware',
                'unique_id' => 'microprocessor',
            ],
            [
                'start_date' => ['year' => 1976],
                'text' => [
                    'headline' => 'Apple I',
                    'text' => '<p>Steve Wozniak and Steve Jobs introduce the Apple I, one of the first personal computers, sparking the personal computing revolution.</p>',
                ],
                'group' => 'Hardware',
                'unique_id' => 'apple1',
            ],
            [
                'start_date' => ['year' => 1989],
                'text' => [
                    'headline' => 'World Wide Web',
                    'text' => '<p>Tim Berners-Lee proposes the World Wide Web at CERN, combining hypertext with the Internet to create a new way to share information globally.</p>',
                ],
                'group' => 'Networking',
                'unique_id' => 'www',
            ],
            [
                'start_date' => ['year' => 1991],
                'text' => [
                    'headline' => 'Linux Kernel',
                    'text' => '<p>Linus Torvalds releases the first version of the Linux kernel, launching the open-source operating system that would power servers, phones, and more.</p>',
                ],
                'group' => 'Software',
                'unique_id' => 'linux',
            ],
            [
                'start_date' => ['year' => 2007, 'month' => 1, 'day' => 9],
                'text' => [
                    'headline' => 'iPhone',
                    'text' => '<p>Apple introduces the iPhone, fundamentally changing how people interact with technology and ushering in the smartphone era.</p>',
                ],
                'group' => 'Hardware',
                'unique_id' => 'iphone',
            ],
            [
                'start_date' => ['year' => 2022, 'month' => 11, 'day' => 30],
                'text' => [
                    'headline' => 'ChatGPT',
                    'text' => '<p>OpenAI launches ChatGPT, bringing large language models to the mainstream and accelerating the AI revolution.</p>',
                ],
                'group' => 'AI',
                'unique_id' => 'chatgpt',
            ],
        ],
        'eras' => [
            [
                'start_date' => ['year' => 1930],
                'end_date' => ['year' => 1960],
                'text' => ['headline' => 'Early Computing'],
            ],
            [
                'start_date' => ['year' => 1960],
                'end_date' => ['year' => 1990],
                'text' => ['headline' => 'Personal Computing Era'],
            ],
            [
                'start_date' => ['year' => 1990],
                'end_date' => ['year' => 2020],
                'text' => ['headline' => 'Internet Age'],
            ],
            [
                'start_date' => ['year' => 2020],
                'end_date' => ['year' => 2026],
                'text' => ['headline' => 'AI Era'],
            ],
        ],
    ];

    public array $laravelTimeline = [
        'events' => [
            [
                'start_date' => ['year' => 2011, 'month' => 6],
                'text' => [
                    'headline' => 'Laravel 1.0',
                    'text' => '<p>Taylor Otwell releases the first version of Laravel, offering an elegant alternative to CodeIgniter with built-in authentication and routing.</p>',
                ],
                'unique_id' => 'laravel-1',
            ],
            [
                'start_date' => ['year' => 2013, 'month' => 5],
                'text' => [
                    'headline' => 'Laravel 4.0 - Illuminate',
                    'text' => '<p>A complete rewrite built on Composer packages. Introduced the IoC container, Eloquent ORM improvements, and database migrations.</p>',
                ],
                'unique_id' => 'laravel-4',
            ],
            [
                'start_date' => ['year' => 2015, 'month' => 2],
                'text' => [
                    'headline' => 'Laravel 5.0',
                    'text' => '<p>New directory structure, route caching, middleware, form requests, and the Artisan command generator. A major architectural leap.</p>',
                ],
                'unique_id' => 'laravel-5',
            ],
            [
                'start_date' => ['year' => 2020, 'month' => 9],
                'text' => [
                    'headline' => 'Laravel 8.0',
                    'text' => '<p>Jetstream, model factories with classes, migration squashing, job batching, and improved rate limiting.</p>',
                ],
                'unique_id' => 'laravel-8',
            ],
            [
                'start_date' => ['year' => 2023, 'month' => 2],
                'text' => [
                    'headline' => 'Laravel 10',
                    'text' => '<p>Native type declarations, Laravel Pennant for feature flags, and process interaction improvements.</p>',
                ],
                'unique_id' => 'laravel-10',
            ],
            [
                'start_date' => ['year' => 2024, 'month' => 3],
                'text' => [
                    'headline' => 'Laravel 11',
                    'text' => '<p>Streamlined application structure, per-second rate limiting, health routing, and the new Laravel Reverb real-time server.</p>',
                ],
                'unique_id' => 'laravel-11',
            ],
            [
                'start_date' => ['year' => 2025, 'month' => 2],
                'text' => [
                    'headline' => 'Laravel 12',
                    'text' => '<p>Continued refinement with starter kits, new defaults, and an ever-growing ecosystem.</p>',
                ],
                'unique_id' => 'laravel-12',
            ],
        ],
    ];

    public array $compactTimeline = [
        'events' => [
            [
                'start_date' => ['year' => 2024, 'month' => 1],
                'text' => [
                    'headline' => 'Q1 Planning',
                    'text' => '<p>Roadmap finalized and sprint goals set for the quarter.</p>',
                ],
                'unique_id' => 'q1',
            ],
            [
                'start_date' => ['year' => 2024, 'month' => 4],
                'text' => [
                    'headline' => 'Beta Launch',
                    'text' => '<p>First public beta released to early access users.</p>',
                ],
                'unique_id' => 'beta',
            ],
            [
                'start_date' => ['year' => 2024, 'month' => 7],
                'text' => [
                    'headline' => 'GA Release',
                    'text' => '<p>General availability with full feature set.</p>',
                ],
                'unique_id' => 'ga',
            ],
            [
                'start_date' => ['year' => 2024, 'month' => 10],
                'text' => [
                    'headline' => 'V2 Kickoff',
                    'text' => '<p>Version 2 development begins with new architecture.</p>',
                ],
                'unique_id' => 'v2',
            ],
        ],
    ];

    public function render()
    {
        return view('fancy-flux-demos::timeline-examples.timeline-examples');
    }
}
