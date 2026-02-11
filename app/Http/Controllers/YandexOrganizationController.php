<?php

namespace App\Http\Controllers;

use App\Models\YandexOrganization;
use App\Services\YandexMapsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class YandexOrganizationController extends Controller
{
    public function __construct(
        private YandexMapsService $yandexService
    ) {}

    /**
     * Страница настроек - подключение Яндекс
     */
    public function settings()
    {
        $organization = Auth::user()->yandexOrganizations()->first();

        return Inertia::render('Yandex/Settings', [
            'organization' => $organization,
        ]);
    }

    /**
     * Сохранить ссылку на организацию
     */
    public function store(Request $request)
    {
        $request->validate([
            'yandex_url' => [
                'required',
                'url',
                'regex:/yandex\.(ru|com)\/maps.*\/org\//i',
            ],
        ], [
            'yandex_url.required' => 'Укажите ссылку на организацию в Яндекс Картах',
            'yandex_url.url' => 'Укажите корректную ссылку',
            'yandex_url.regex' => 'Ссылка должна вести на организацию в Яндекс Картах',
        ]);

        $organizationId = $this->yandexService->extractOrganizationId($request->yandex_url);

        $organization = Auth::user()->yandexOrganizations()->updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'yandex_url' => $request->yandex_url,
                'organization_id' => $organizationId,
            ]
        );

        // Синхронизируем данные
        $this->yandexService->syncReviews($organization);

        return redirect()->route('yandex.reviews')->with('success', 'Организация успешно подключена');
    }

    /**
     * Удалить организацию
     */
    public function destroy(YandexOrganization $organization)
    {
        if ($organization->user_id !== Auth::id()) {
            abort(403);
        }

        $organization->delete();

        return redirect()->route('yandex.settings')->with('success', 'Организация удалена');
    }
}
