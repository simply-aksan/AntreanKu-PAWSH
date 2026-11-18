<?php

namespace App\Services;

use App\Models\Service;
use App\Models\StaffProfile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiConsultantService
{
    /**
     * Analyze pet condition and recommend suitable service and staff.
     *
     * @return array{
     *     diagnosis: string,
     *     recommended_service_id: int,
     *     recommended_service_name: string,
     *     recommended_service_price: int,
     *     recommended_staff_id: int,
     *     recommended_staff_name: string,
     *     reasoning: string,
     *     home_care_tip: string,
     *     source: string
     * }
     */
    public function consult(string $petQuery): array
    {
        $services = Service::where('is_active', true)->get();
        $staffList = StaffProfile::where('is_active', true)->get();

        if ($services->isEmpty()) {
            return $this->getEmptyFallback();
        }

        $apiKey = config('services.gemini.api_key') ?: env('GEMINI_API_KEY');

        if (! empty($apiKey)) {
            try {
                $aiResponse = $this->callGeminiApi($petQuery, $services, $staffList, $apiKey);
                if ($aiResponse !== null) {
                    return $aiResponse;
                }
            } catch (\Throwable $e) {
                Log::warning('Gemini API call failed, switching to heuristic AI engine: '.$e->getMessage());
            }
        }

        // Graceful smart heuristic fallback
        return $this->analyzeHeuristically($petQuery, $services, $staffList);
    }

    /**
     * Query Google Gemini API with structured instructions.
     *
     * @param  Collection<int, Service>  $services
     * @param  Collection<int, StaffProfile>  $staffList
     * @return array{
     *     diagnosis: string,
     *     recommended_service_id: int,
     *     recommended_service_name: string,
     *     recommended_service_price: int,
     *     recommended_staff_id: int,
     *     recommended_staff_name: string,
     *     reasoning: string,
     *     home_care_tip: string,
     *     source: string
     * }|null
     */
    protected function callGeminiApi(string $query, $services, $staffList, string $apiKey): ?array
    {
        $servicesContext = $services->map(fn ($s) => [
            'id' => $s->id,
            'name' => $s->name,
            'price' => $s->price,
            'duration' => $s->duration_minutes,
            'description' => $s->description,
        ])->toJson();

        $staffContext = $staffList->map(fn ($st) => [
            'id' => $st->id,
            'nickname' => $st->nickname,
            'title' => $st->title,
            'bio' => $st->bio,
        ])->toJson();

        $systemPrompt = "Anda adalah PAWSH! AI Grooming Consultant, asisten kecerdasan buatan klinis dan ahli perawatan anabul (anjing & kucing) profesional di PAWSH! Pet Studio.
Tugas Anda:
1. Menganalisis keluhan kondisi anabul dari pengguna.
2. Memilih TEPAT SATU layanan paling relevan dari daftar katalog resmi yang disediakan.
3. Memilih TEPAT SATU groomer spesialis paling cocok dari daftar staf kami.
4. Memberikan tips perawatan mandiri di rumah (home care tip) yang aman dan higienis.

PENTING: Anda WAJIB merespons HANYA dalam format JSON murni tanpa markdown triple-backtick, tanpa kata pembuka atau penutup.
Format JSON yang diharapkan:
{
  \"diagnosis\": \"Ringkasan analisa kondisi bulu / kulit anabul (maksimal 2 kalimat)\",
  \"recommended_service_id\": (angka ID layanan yang ada di katalog),
  \"recommended_staff_id\": (angka ID groomer yang ada di daftar staf),
  \"reasoning\": \"Alasan klinis mengapa paket dan groomer ini paling tepat untuk anabul tersebut (maksimal 2 kalimat)\",
  \"home_care_tip\": \"Tips praktis pencegahan di rumah (maksimal 2 kalimat)\"
}

Katalog Layanan Resmi:
{$servicesContext}

Daftar Groomer Bertugas:
{$staffContext}";

        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.8-flash:generateContent?key={$apiKey}";

        $response = Http::timeout(6)
            ->withoutVerifying()
            ->post($endpoint, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $systemPrompt."\n\nKeluhan Pemilik Anabul: \"".$query.'"'],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'temperature' => 0.2,
                    'maxOutputTokens' => 600,
                ],
            ]);

        if (! $response->successful()) {
            return null;
        }

        $rawText = $response->json('candidates.0.content.parts.0.text');
        if (empty($rawText)) {
            return null;
        }

        // Clean any code fences if present
        $cleanJson = trim(preg_replace('/^```(?:json)?\s*|\s*```$/m', '', $rawText));
        $data = json_decode($cleanJson, true);

        if (! is_array($data) || empty($data['recommended_service_id'])) {
            return null;
        }

        $selectedService = $services->firstWhere('id', (int) $data['recommended_service_id']) ?: $services->first();
        $selectedStaff = $staffList->firstWhere('id', (int) ($data['recommended_staff_id'] ?? 0)) ?: $staffList->first();

        return [
            'diagnosis' => $data['diagnosis'] ?? 'Analisis kondisi fisik dan kebersihan bulu anabul.',
            'recommended_service_id' => $selectedService->id,
            'recommended_service_name' => $selectedService->name,
            'recommended_service_price' => (int) $selectedService->price,
            'recommended_staff_id' => $selectedStaff?->id ?? 1,
            'recommended_staff_name' => $selectedStaff?->nickname ?? 'Groomer Berpengalaman',
            'reasoning' => $data['reasoning'] ?? 'Paket perawatan ini dirancang optimal untuk menangani kondisi anabul Anda.',
            'home_care_tip' => $data['home_care_tip'] ?? 'Pastikan anabul tetap dalam kondisi kering dan disisir secara teratur.',
            'source' => 'Gemini AI',
        ];
    }

    /**
     * Smart local heuristic rule-based AI engine fallback.
     * Guarantees 100% uptime and resilience during live demos.
     *
     * @param  Collection<int, Service>  $services
     * @param  Collection<int, StaffProfile>  $staffList
     * @return array{
     *     diagnosis: string,
     *     recommended_service_id: int,
     *     recommended_service_name: string,
     *     recommended_service_price: int,
     *     recommended_staff_id: int,
     *     recommended_staff_name: string,
     *     reasoning: string,
     *     home_care_tip: string,
     *     source: string
     * }
     */
    protected function analyzeHeuristically(string $query, $services, $staffList): array
    {
        $q = strtolower($query);

        // Pattern 1: Jamur, Kutu, Gatal, Kulit, Scabies
        if (str_contains($q, 'kutu') || str_contains($q, 'jamur') || str_contains($q, 'gatal') || str_contains($q, 'rontok parah') || str_contains($q, 'ketombe') || str_contains($q, 'scabies') || str_contains($q, 'merah')) {
            $matchedService = $services->first(fn ($s) => str_contains(strtolower($s->name), 'medicated') || str_contains(strtolower($s->name), 'bath') || str_contains(strtolower($s->name), 'flea')) ?: $services->first();
            $matchedStaff = $staffList->first(fn ($st) => str_contains(strtolower($st->title), 'flea') || str_contains(strtolower($st->title), 'medicated') || str_contains(strtolower($st->bio), 'jamur')) ?: $staffList->first();

            return [
                'diagnosis' => 'Terindikasi adanya gangguan ektoparasit (kutu) atau dermatitis jamur pada folikel bulu dan kulit anabul.',
                'recommended_service_id' => $matchedService->id,
                'recommended_service_name' => $matchedService->name,
                'recommended_service_price' => (int) $matchedService->price,
                'recommended_staff_id' => $matchedStaff->id,
                'recommended_staff_name' => $matchedStaff->nickname,
                'reasoning' => 'Paket terapi mandi obat khusus kami membasmi parasit hingga ke pangkal kulit serta mencegah iritasi lebih luas dengan pengawasan groomer spesialis.',
                'home_care_tip' => 'Cuci alas tidur anabul dengan air hangat dan hindari membiarkan anabul berada di tempat yang lembap.',
                'source' => 'PAWSH! AI Engine',
            ];
        }

        // Pattern 2: Bulu Gimbal, Kusut, Styling, Potong, Rapihin, Cukur
        if (str_contains($q, 'gimbal') || str_contains($q, 'kusut') || str_contains($q, 'potong') || str_contains($q, 'styling') || str_contains($q, 'gunting') || str_contains($q, 'cukur') || str_contains($q, 'panjang')) {
            $matchedService = $services->first(fn ($s) => str_contains(strtolower($s->name), 'full') || str_contains(strtolower($s->name), 'styling')) ?: $services->first();
            $matchedStaff = $staffList->first(fn ($st) => str_contains(strtolower($st->title), 'canine') || str_contains(strtolower($st->title), 'styling') || str_contains(strtolower($st->bio), 'gunting')) ?: $staffList->first();

            return [
                'diagnosis' => 'Terjadi matting (penggumpalan serat bulu) yang memerlukan penguraian bertahap dan pemotongan styling proporsional.',
                'recommended_service_id' => $matchedService->id,
                'recommended_service_name' => $matchedService->name,
                'recommended_service_price' => (int) $matchedService->price,
                'recommended_staff_id' => $matchedStaff->id,
                'recommended_staff_name' => $matchedStaff->nickname,
                'reasoning' => 'Layanan Full Grooming mencakup detangling, pembersihan telinga menyeluruh, serta penataan gaya bulu rapi tanpa menimbulkan stres pada anabul.',
                'home_care_tip' => 'Gunakan sisir slicker secara perlahan setiap dua hari sekali mulai dari bagian ujung bulu ke arah pangkal.',
                'source' => 'PAWSH! AI Engine',
            ];
        }

        // Pattern 3: Kucing Ras, Persiapan Kontes, Spa
        if (str_contains($q, 'kucing') || str_contains($q, 'persia') || str_contains($q, 'spa') || str_contains($q, 'relaksasi')) {
            $matchedService = $services->first(fn ($s) => str_contains(strtolower($s->name), 'full') || str_contains(strtolower($s->name), 'spa')) ?: $services->first();
            $matchedStaff = $staffList->first(fn ($st) => str_contains(strtolower($st->title), 'cat') || str_contains(strtolower($st->nickname), 'sari')) ?: $staffList->first();

            return [
                'diagnosis' => 'Kondisi bulu anabul membutuhkan nutrisi ekstra, perawatan kelembapan, dan penanganan ramah anti-stress.',
                'recommended_service_id' => $matchedService->id,
                'recommended_service_name' => $matchedService->name,
                'recommended_service_price' => (int) $matchedService->price,
                'recommended_staff_id' => $matchedStaff->id,
                'recommended_staff_name' => $matchedStaff->nickname,
                'reasoning' => 'Kombinasi shampoo organik hypoallergenic dan teknik pemijatan lembut oleh spesialis kucing akan mengembalikan kilau alami bulu.',
                'home_care_tip' => 'Berikan minyak ikan (omega-3) pada pakan untuk memperkuat akar bulu dan mengurangi kerontokan musiman.',
                'source' => 'PAWSH! AI Engine',
            ];
        }

        // Default: Mandi Segar & Pembersihan Rutin
        $defaultService = $services->first();
        $defaultStaff = $staffList->first();

        return [
            'diagnosis' => 'Kondisi anabul membutuhkan pembersihan higienis menyeluruh, pengeringan tuntas, dan perapian kuku rutin.',
            'recommended_service_id' => $defaultService->id,
            'recommended_service_name' => $defaultService->name,
            'recommended_service_price' => (int) $defaultService->price,
            'recommended_staff_id' => $defaultStaff->id,
            'recommended_staff_name' => $defaultStaff->nickname,
            'reasoning' => 'Paket Basic Fresh Bath sangat tepat untuk menjaga kesegaran tubuh, membasmi aroma tidak sedap, dan membersihkan area sensitif telinga & kuku.',
            'home_care_tip' => 'Pastikan telinga anabul selalu kering setelah beraktivitas dan jaga kebersihan kandang/tempat tidur.',
            'source' => 'PAWSH! AI Engine',
        ];
    }

    /**
     * @return array{
     *     diagnosis: string,
     *     recommended_service_id: int,
     *     recommended_service_name: string,
     *     recommended_service_price: int,
     *     recommended_staff_id: int,
     *     recommended_staff_name: string,
     *     reasoning: string,
     *     home_care_tip: string,
     *     source: string
     * }
     */
    protected function getEmptyFallback(): array
    {
        return [
            'diagnosis' => 'Belum ada katalog layanan aktif yang terdaftar.',
            'recommended_service_id' => 1,
            'recommended_service_name' => 'Layanan Standar',
            'recommended_service_price' => 50000,
            'recommended_staff_id' => 1,
            'recommended_staff_name' => 'Groomer',
            'reasoning' => 'Silakan hubungi staf untuk informasi ketersediaan layanan.',
            'home_care_tip' => 'Jaga kebersihan anabul secara berkala.',
            'source' => 'PAWSH! AI Engine',
        ];
    }
}
