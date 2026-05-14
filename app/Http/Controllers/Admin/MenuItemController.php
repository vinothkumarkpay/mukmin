<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MenuItemController extends Controller
{
    public function index(): View
    {
        $items = MenuItem::query()
            ->with('parent')
            ->orderByRaw('parent_id IS NULL DESC')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(30);

        return view('admin.menu-items.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.menu-items.create', [
            'parentOptions' => $this->parentOptions(null),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request, null);
        MenuItem::query()->create($data);

        return redirect()->route('admin.menu-items.index')->with('status', __('Menu item created.'));
    }

    public function edit(MenuItem $menu_item): View
    {
        return view('admin.menu-items.edit', [
            'item' => $menu_item,
            'parentOptions' => $this->parentOptions($menu_item->id),
        ]);
    }

    public function update(Request $request, MenuItem $menu_item): RedirectResponse
    {
        $menu_item->update($this->validated($request, $menu_item));

        return redirect()->route('admin.menu-items.index')->with('status', __('Menu item updated.'));
    }

    public function destroy(MenuItem $menu_item): RedirectResponse
    {
        if ($menu_item->children()->exists()) {
            return redirect()->route('admin.menu-items.index')->withErrors([
                'menu' => __('Delete or move submenu items before removing this parent link.'),
            ]);
        }
        $menu_item->delete();

        return redirect()->route('admin.menu-items.index')->with('status', __('Menu item removed.'));
    }

    /**
     * Top-level items only (submenus are one level deep).
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, MenuItem>
     */
    protected function parentOptions(?int $excludeId)
    {
        return MenuItem::query()
            ->whereNull('parent_id')
            ->when($excludeId, function ($q) use ($excludeId) {
                $q->where('id', '!=', $excludeId);
            })
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get(['id', 'label']);
    }

    protected function validated(Request $request, ?MenuItem $existing): array
    {
        $excludeId = $existing !== null ? $existing->id : null;
        $data = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999999'],
            'is_active' => ['sometimes', 'boolean'],
            'open_new_tab' => ['sometimes', 'boolean'],
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('menu_items', 'id')->where(function ($q) use ($excludeId) {
                    $q->whereNull('parent_id');
                    if ($excludeId) {
                        $q->where('id', '!=', $excludeId);
                    }
                }),
            ],
        ]) + [
            'is_active' => $request->boolean('is_active'),
            'open_new_tab' => $request->boolean('open_new_tab'),
            'sort_order' => (int) $request->input('sort_order', 0),
            'parent_id' => $request->filled('parent_id') ? (int) $request->input('parent_id') : null,
        ];

        if (! empty($data['parent_id']) && $existing && $existing->children()->exists()) {
            throw ValidationException::withMessages([
                'parent_id' => __('This item has submenu links. Remove them first before nesting this item under another top-level link.'),
            ]);
        }

        return $data;
    }
}
