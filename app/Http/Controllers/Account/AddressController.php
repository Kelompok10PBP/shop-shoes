<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function index(): View
    {
        $addresses = Auth::user()->addresses()->latest()->get();
        return view('account.addresses.index', compact('addresses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:50',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'nullable|string|max:100',
            'is_default' => 'nullable|boolean',
        ]);

        $isDefault = $request->boolean('is_default');

        if ($isDefault) {
            Auth::user()->addresses()->update(['is_default' => false]);
        }

        Auth::user()->addresses()->create(array_merge($validated, [
            'country' => $validated['country'] ?? 'United States',
            'is_default' => $isDefault || Auth::user()->addresses()->count() === 0,
        ]));

        return back()->with('success', 'Address added successfully.');
    }

    public function update(Request $request, int $id)
    {
        $address = Auth::user()->addresses()->findOrFail($id);

        $validated = $request->validate([
            'label' => 'required|string|max:50',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'nullable|string|max:100',
            'is_default' => 'nullable|boolean',
        ]);

        if ($request->boolean('is_default')) {
            Auth::user()->addresses()->where('id', '!=', $id)->update(['is_default' => false]);
            $address->is_default = true;
        }

        $address->update($validated);

        return back()->with('success', 'Address updated successfully.');
    }

    public function destroy(int $id)
    {
        $address = Auth::user()->addresses()->findOrFail($id);
        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $next = Auth::user()->addresses()->first();
            if ($next) {
                $next->is_default = true;
                $next->save();
            }
        }

        return back()->with('success', 'Address deleted.');
    }

    public function setDefault(int $id)
    {
        Auth::user()->addresses()->update(['is_default' => false]);
        $address = Auth::user()->addresses()->findOrFail($id);
        $address->is_default = true;
        $address->save();

        return back()->with('success', 'Default address updated.');
    }
}
