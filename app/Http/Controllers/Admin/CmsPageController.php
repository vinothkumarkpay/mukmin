<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CmsPageController extends Controller
{
    public function index(): View
    {
        $pages = CmsPage::query()->orderByDesc('updated_at')->paginate(20);

        return view('admin.cms-pages.index', compact('pages'));
    }

    public function create(): View
    {
        return view('admin.cms-pages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request, null);
        $data['slug'] = Str::slug($data['slug']);
        CmsPage::query()->create($data);

        return redirect()->route('admin.cms-pages.index')->with('status', __('Page created.'));
    }

    public function edit(CmsPage $cms_page): View
    {
        return view('admin.cms-pages.edit', ['page' => $cms_page]);
    }

    public function update(Request $request, CmsPage $cms_page): RedirectResponse
    {
        $data = $this->validated($request, $cms_page->id);
        $data['slug'] = Str::slug($data['slug']);
        $cms_page->update($data);

        return redirect()->route('admin.cms-pages.index')->with('status', __('Page updated.'));
    }

    public function destroy(CmsPage $cms_page): RedirectResponse
    {
        $cms_page->delete();

        return redirect()->route('admin.cms-pages.index')->with('status', __('Page removed.'));
    }

    protected function validated(Request $request, ?int $ignoreId): array
    {
        $data = $request->validate([
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('cms_pages', 'slug')->ignore($ignoreId),
            ],
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'is_published' => ['sometimes', 'boolean'],
            'widgets_only' => ['sometimes', 'boolean'],
        ]) + [
            'is_published' => $request->boolean('is_published'),
            'widgets_only' => $request->boolean('widgets_only'),
        ];

        if ($data['is_published']) {
            $data['published_at'] = now();
        } else {
            $data['published_at'] = null;
        }

        return $data;
    }
}
