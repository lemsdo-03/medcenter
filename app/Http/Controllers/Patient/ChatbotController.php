<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    //the chatbot page. FR-52: greet the patient automatically when it opens.
    public function index()
    {
        $greeting = 'hello dear patient How can I help you today?';
        $menu = $this->mainMenu();

        return view('patient.chatbot.index', compact('greeting', 'menu'));
    }

    //handles every message: a button "topic" or free text the patient typed
    public function reply(Request $request)
    {
        $topic = $request->input('topic');
        $message = strtolower(trim((string) $request->input('message')));

        // if the patient typed text instead of using a button, map it to a topic (simple keyword match)
        if (!$topic && $message !== '') {
            $topic = $this->matchKeyword($message);
        }

        return response()->json($this->respond($topic));
    }

    //the main menu shown after the greeting and whenever the patient asks for it
    private function mainMenu(): array
    {
        return [
            ['label' => 'FAQs', 'topic' => 'faqs'],
            ['label' => 'Working Hours', 'topic' => 'hours'],
            ['label' => 'Location', 'topic' => 'location'],
            ['label' => 'Our Specialties', 'topic' => 'specialties'],
            ['label' => 'How to Book', 'topic' => 'booking'],
            ['label' => 'Downloads', 'topic' => 'downloads'],
        ];
    }

    //turn typed text into a topic
    private function matchKeyword(string $message): string
    {
        return match (true) {
            str_contains($message, 'hour') || str_contains($message, 'open') || str_contains($message, 'time') => 'hours',
            str_contains($message, 'where') || str_contains($message, 'location') || str_contains($message, 'address') || str_contains($message, 'map') => 'location',
            str_contains($message, 'book') || str_contains($message, 'appointment') => 'booking',
            str_contains($message, 'special') => 'specialties',
            str_contains($message, 'doctor') => 'specialties',
            str_contains($message, 'faq') || str_contains($message, 'question') => 'faqs',
            str_contains($message, 'download') || str_contains($message, 'form') || str_contains($message, 'file') => 'downloads',
            default => 'unknown',
        };
    }

    //build the bot answer for a given topic
    private function respond(?string $topic): array
    {
        // FR-58: a specialty was chosen -> list its doctors (topic looks like "doctors:Cardiology")
        if ($topic && str_starts_with($topic, 'doctors:')) {
            return $this->doctorsBySpecialty(substr($topic, strlen('doctors:')));
        }

        // FR-53: a specific FAQ was chosen (topic looks like "faq:0")
        if ($topic && str_starts_with($topic, 'faq:')) {
            return $this->faqAnswer((int) substr($topic, strlen('faq:')));
        }

        return match ($topic) {
            'menu' => $this->reply_('Here is what I can help with:', $this->mainMenu()),
            'faqs' => $this->faqList(),                 // FR-53
            'hours' => $this->workingHours(),           // FR-54
            'location' => $this->location(),            // FR-55
            'specialties' => $this->specialties(),      // FR-56
            'booking' => $this->booking(),              // FR-57
            'downloads' => $this->downloads(),          // FR-60
            default => $this->reply_(
                "Sorry, I didn't quite get that. You can pick one of the options below:",
                $this->mainMenu()
            ),
        };
    }

    // ---- individual answers ----

    private function faqList(): array
    {
        $options = [];
        foreach (config('chatbot.faqs') as $i => $faq) {
            $options[] = ['label' => $faq['q'], 'topic' => 'faq:' . $i];
        }
        $options[] = ['label' => '⬅ Main menu', 'topic' => 'menu'];

        return $this->reply_('Choose a question:', $options);
    }

    private function faqAnswer(int $index): array
    {
        $faqs = config('chatbot.faqs');
        if (!isset($faqs[$index])) {
            return $this->reply_('That question is no longer available.', [['label' => 'FAQs', 'topic' => 'faqs']]);
        }

        return $this->reply_($faqs[$index]['a'], [
            ['label' => 'More FAQs', 'topic' => 'faqs'],
            ['label' => '⬅ Main menu', 'topic' => 'menu'],
        ]);
    }

    private function workingHours(): array
    {
        return $this->reply_(config('chatbot.working_hours.text'), [['label' => '⬅ Main menu', 'topic' => 'menu']]);
    }

    private function location(): array
    {
        return $this->reply_(
            'You can find us at: ' . config('chatbot.location.address'),
            [['label' => '⬅ Main menu', 'topic' => 'menu']],
            [['label' => 'Open in Google Maps', 'url' => config('chatbot.location.map_link')]]
        );
    }

    private function specialties(): array
    {
        // FR-56: list the specialties that actually have doctors
        $specialties = User::where('role', 'doctor')
            ->whereNotNull('specialty')
            ->where('specialty', '!=', '')
            ->distinct()
            ->orderBy('specialty')
            ->pluck('specialty');

        if ($specialties->isEmpty()) {
            return $this->reply_('No specialties are registered yet.', [['label' => '⬅ Main menu', 'topic' => 'menu']]);
        }

        $options = [];
        foreach ($specialties as $specialty) {
            $options[] = ['label' => $specialty, 'topic' => 'doctors:' . $specialty];
        }
        $options[] = ['label' => '⬅ Main menu', 'topic' => 'menu'];

        return $this->reply_('Select a specialty to see its doctors:', $options);
    }

    private function doctorsBySpecialty(string $specialty): array
    {
        // FR-58: doctors for the chosen specialty
        $doctors = User::where('role', 'doctor')
            ->where('specialty', $specialty)
            ->orderBy('name')
            ->pluck('name');

        if ($doctors->isEmpty()) {
            return $this->reply_("No doctors are available for {$specialty} right now.", [['label' => 'Specialties', 'topic' => 'specialties']]);
        }

        // strip any existing "Dr." in the stored name so we don't end up with "Dr. Dr."
        $list = $doctors->map(fn ($name) => '• Dr. ' . preg_replace('/^dr\.?\s*/i', '', $name))->implode("\n");

        return $this->reply_("Doctors in {$specialty}:\n{$list}", [
            ['label' => 'Other specialties', 'topic' => 'specialties'],
            ['label' => '⬅ Main menu', 'topic' => 'menu'],
        ]);
    }

    private function booking(): array
    {
        $steps = '';
        foreach (config('chatbot.booking_steps') as $i => $step) {
            $steps .= ($i + 1) . '. ' . $step . "\n";
        }

        return $this->reply_(
            "Booking an appointment is easy:\n" . rtrim($steps),
            [['label' => '⬅ Main menu', 'topic' => 'menu']],
            [['label' => 'Go to Doctors', 'url' => route('patient.doctors.index')]]
        );
    }

    private function downloads(): array
    {
        $links = [];
        $text = "Here are the files you can download:\n";
        foreach (config('chatbot.downloads') as $file) {
            $text .= "• {$file['name']} — {$file['instructions']}\n";
            $links[] = ['label' => $file['name'], 'url' => $file['url']];
        }

        return $this->reply_(rtrim($text), [['label' => '⬅ Main menu', 'topic' => 'menu']], $links);
    }

    //small helper to keep the JSON response shape consistent
    private function reply_(string $text, array $options = [], array $links = []): array
    {
        return [
            'reply' => $text,
            'options' => $options,
            'links' => $links,
        ];
    }
}
