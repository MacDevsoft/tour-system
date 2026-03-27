<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function index()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') abort(403);
        $accounts = BankAccount::all();
        return view('bank_accounts.index', compact('accounts'));
    }

    public function create()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') abort(403);
        return view('bank_accounts.create');
    }

    public function store(Request $request)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') abort(403);

        $request->validate([
            'bank_name'      => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:100',
            'account_type'   => 'required|string|max:50',
        ]);

        BankAccount::create([
            'bank_name'      => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder' => $request->account_holder,
            'account_type'   => $request->account_type,
            'is_active'      => false,
        ]);

        return redirect()->route('bank_accounts.index')->with('status', 'Se agrego cuenta bancaria con exito');
    }

    public function edit(BankAccount $bank_account)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') abort(403);
        return view('bank_accounts.edit', compact('bank_account'));
    }

    public function update(Request $request, BankAccount $bank_account)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') abort(403);

        $request->validate([
            'bank_name'      => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:100',
            'account_type'   => 'required|string|max:50',
        ]);

        $bank_account->update($request->only(['bank_name', 'account_number', 'account_holder', 'account_type']));

        return redirect()->route('bank_accounts.index')->with('status', 'Cuenta bancaria actualizada');
    }

    public function destroy(BankAccount $bank_account)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') abort(403);
        $bank_account->delete();
        return redirect()->route('bank_accounts.index')->with('status', 'Cuenta bancaria eliminada');
    }

    public function activate(BankAccount $bank_account)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') abort(403);

        // Desactiva todas primero
        BankAccount::query()->update(['is_active' => false]);
        // Activa la seleccionada
        $bank_account->update(['is_active' => true]);

        return redirect()->route('bank_accounts.index')->with('status', 'Cuenta activa actualizada');
    }

    public function deactivate(BankAccount $bank_account)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') abort(403);

        $bank_account->update(['is_active' => false]);

        return redirect()->route('bank_accounts.index')->with('status', 'Cuenta desactivada');
    }
}
