<?php

namespace App\Livewire;

use App\Services\GeminiConsultantService;
use Livewire\Component;

class AiConsultantModal extends Component
{
    public bool $isOpen = false;

    public string $petQuery = '';

    public ?array $result = null;

    public array $presetChips = [
        'Bulu gimbal & kusut parah',
        'Gatal-gatal, ada jamur & kutu',
        'Kucing persia perawatan rutin',
        'Anjing kotor & bau sehabis main',
        'Perawatan relaksasi & spa wangi',
    ];

    public function openModal(?string $preset = null): void
    {
        $this->isOpen = true;
        if ($preset) {
            $this->petQuery = $preset;
            $this->askAi(app(GeminiConsultantService::class));
        }
    }

    public function closeModal(): void
    {
        $this->isOpen = false;
        $this->resetErrorBag();
    }

    public function selectPreset(string $preset): void
    {
        $this->petQuery = $preset;
        $this->askAi(app(GeminiConsultantService::class));
    }

    public function askAi(GeminiConsultantService $consultant): void
    {
        $this->validate([
            'petQuery' => 'required|string|min:3|max:500',
        ], [
            'petQuery.required' => 'Silakan ceritakan kondisi atau keluhan anabul Anda.',
            'petQuery.min' => 'Ceritakan kondisi minimal 3 karakter.',
        ]);

        $this->result = $consultant->consult($this->petQuery);
    }

    public function resetConsultation(): void
    {
        $this->petQuery = '';
        $this->result = null;
        $this->resetErrorBag();
    }

    public function applyAndBook()
    {
        if (! empty($this->result['recommended_service_id'])) {
            $this->isOpen = false;

            return $this->redirect(route('booking', ['service' => $this->result['recommended_service_id']]), navigate: true);
        }

        return $this->redirect(route('booking'), navigate: true);
    }

    public function render()
    {
        return view('livewire.ai-consultant-modal');
    }
}
