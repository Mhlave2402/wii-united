<?php

namespace Modules\BusinessManagement\Http\Controllers\Web\Admin\PagesMedia;

use App\Http\Controllers\BaseController;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Modules\BusinessManagement\Http\Requests\BusinessPageStoreOrUpdateRequest;
use Modules\BusinessManagement\Http\Requests\SocialLinkStoreOrUpdateRequest;
use Modules\BusinessManagement\Service\Interface\BusinessSettingServiceInterface;
use Modules\BusinessManagement\Service\Interface\NotificationSettingServiceInterface;
use Modules\BusinessManagement\Service\Interface\SocialLinkServiceInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PagesMediaController extends BaseController
{
    protected $socialLinkService;
    protected $businessSettingService;
    protected $notificationSettingService;

    public function __construct(SocialLinkServiceInterface $socialLinkService, BusinessSettingServiceInterface $businessSettingService,
    NotificationSettingServiceInterface $notificationSettingService)
    {
        parent::__construct($socialLinkService);
        $this->socialLinkService = $socialLinkService;
        $this->businessSettingService = $businessSettingService;
        $this->notificationSettingService = $notificationSettingService;
    }

    public function index(?Request $request, string $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
        return parent::index($request, $type); // TODO: Change the autogenerated stub
    }

    public function socialMedia(Request $request): Renderable
    {
        $this->authorize('business_view');
        $links = $this->socialLinkService->index(criteria: $request->all(), limit: paginationLimit(), offset: 1,);
        return view('businessmanagement::admin.pages.social-media', compact('links'));
    }

    public function storeSocialLink(SocialLinkStoreOrUpdateRequest $request): Renderable|RedirectResponse
    {
        $this->authorize('business_edit');
        $this->socialLinkService->create($request->validated());
        Toastr::success(SOCIAL_MEDIA_LINK_STORE_200['message']);
        return back();
    }

    public function updateSocialLink($id, SocialLinkStoreOrUpdateRequest $request): Renderable|RedirectResponse
    {
        $this->authorize('business_edit');
        $this->socialLinkService->update(id: $id, data: $request->validated());
        Toastr::success(SOCIAL_MEDIA_LINK_STORE_200['message']);
        return back();
    }

    public function deleteSocialLink(Request $request)
    {
        $this->authorize('business_edit');
        $this->socialLinkService->delete(id: $request->id);
        Toastr::success(SOCIAL_MEDIA_LINK_DELETE_200['message']);
        return back();
    }

    public function updateSocialStatus(Request $request)
    {
        $this->authorize('business_edit');
        $model = $this->socialLinkService->statusChange(id: $request->id, data: $request->all());
        return response()->json($model);
    }

    public function businessPages(Request $request)
    {
        $this->authorize('business_view');
        $request->validate([
            'type' => 'in:about_us,privacy_policy,terms_and_conditions,legal,refund_policy',
        ]);
        $type = $request['type'] ?? 'about_us';
        $criteria = ['key_name' => $type, 'settings_type' => PAGES_SETTINGS];
        $data = $this->businessSettingService->findOneBy(criteria: $criteria);
        return view('businessmanagement::admin.pages.business-pages', compact('type', 'data'));
    }


    public function businessPagesUpdate(BusinessPageStoreOrUpdateRequest $request)
    {
        $this->authorize('business_edit');
        $this->businessSettingService->storeBusinessPage($request->validated());
        Toastr::success(BUSINESS_SETTING_UPDATE_200['message']);
        return back();
    }

    public function log(Request $request): View|Factory|Response|StreamedResponse|string|Application
    {
        $this->authorize('business_view');
        $request->merge([
            'logable_type' => 'Modules\BusinessManagement\Entities\SocialLink',
        ]);
        return log_viewer($request->all());
    }
}
