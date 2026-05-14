<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Support\SiteBranding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class SiteConfigurationController extends Controller
{
    public function edit(): View
    {
        $uploadedPath = SiteSetting::get('site_logo_path');

        return view('admin.site-configuration', [
            'siteName' => SiteSetting::get('site_name', config('app.name')),
            'siteLogoUrlField' => SiteSetting::get('site_logo_url', ''),
            'logoPreviewUrl' => SiteBranding::logoUrlForDisplay(),
            'hasUploadedLogo' => is_string($uploadedPath) && $uploadedPath !== '',
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'site_logo' => ['nullable', 'image', 'max:2048'],
            'site_logo_url' => ['nullable', 'string', 'max:2048'],
            'remove_uploaded_logo' => ['sometimes', 'boolean'],
        ]);

        $urlInput = trim((string) ($validated['site_logo_url'] ?? ''));
        if ($urlInput !== '') {
            Validator::make(
                ['site_logo_url' => $urlInput],
                ['site_logo_url' => ['required', 'url', 'max:2048']]
            )->validate();
        }

        SiteSetting::set('site_name', $validated['site_name']);

        if ($request->boolean('remove_uploaded_logo')) {
            $this->deleteStoredLogoIfAny();
            SiteSetting::set('site_logo_path', '');
        }

        if ($request->hasFile('site_logo')) {
            $this->deleteStoredLogoIfAny();
            $path = $request->file('site_logo')->store('branding', 'public');
            SiteSetting::set('site_logo_path', $path);
        }

        SiteSetting::set('site_logo_url', $urlInput);

        SiteSetting::forgetRuntimeCache();

        return redirect()
            ->route('admin.site-configuration.edit')
            ->with('status', __('Site configuration saved.'));
    }

    protected function deleteStoredLogoIfAny(): void
    {
        $oldPath = SiteSetting::get('site_logo_path');
        if (is_string($oldPath) && $oldPath !== '') {
            Storage::disk('public')->delete($oldPath);
        }
    }
}
