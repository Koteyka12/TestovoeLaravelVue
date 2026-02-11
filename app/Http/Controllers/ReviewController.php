<?php

namespace App\Http\Controllers;

use App\Models\YandexOrganization;
use App\Services\YandexMapsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ReviewController extends Controller
{
    public function __construct(
        private YandexMapsService $yandexService
    ) {}

    /**
     * Страница отзывов
     */
    public function index(Request $request)
    {
        $organization = Auth::user()->yandexOrganizations()->first();

        if (!$organization) {
            return redirect()->route('yandex.settings')
                ->with('warning', 'Сначала подключите организацию в Яндекс Картах');
        }

        $reviews = $organization->reviews()
            ->orderByDesc('published_at')
            ->paginate(10);

        return Inertia::render('Yandex/Reviews', [
            'organization' => $organization,
            'reviews' => $reviews,
        ]);
    }

    /**
     * Синхронизировать отзывы
     */
    public function sync()
    {
        $organization = Auth::user()->yandexOrganizations()->first();

        if (!$organization) {
            return back()->with('error', 'Организация не найдена');
        }

        $result = $this->yandexService->syncReviews($organization);

        if ($result['success']) {
            return back()->with('success', 'Отзывы успешно синхронизированы');
        }

        return back()->with('error', $result['error'] ?? 'Ошибка синхронизации');
    }
}
