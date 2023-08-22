<?php

namespace App\Http\Livewire\Notice;

use App\Models\Auth\User;
use App\Models\Notice\NoticeBoard;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NoticeBoards extends Component
{
    public function render()
    {
        $Get_Notice = NoticeBoard::orderBy('id', 'DESC')->get();
        $Get_Users = User::orderBy('id', 'DESC')->get();
        return view('livewire.notice.notice-boards', compact('Get_Notice', 'Get_Users'))->layout('layouts.authorized');
    }

    public function postNotice(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $current_user = Auth::guard('Authorized')->user();
            $Notice = ($request->Notice_Titles === 'Other') ? $request->Notice_Title : $request->Notice_Titles;
            NoticeBoard::create([
                'Notice_Title' => $Notice,
                'Notice_Desc' => $request->Notice_Desc,
                'status' => $request->status,
                'user_id' => $request->User_ID,
                'created_by' => $current_user->id
            ]);
            DB::commit();
            return back()->with('Success!', 'Notice has been added successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('Error.Response', ['Message' => $e->getMessage()]);
        }
    }

    public function deleteNotice(Request $request): RedirectResponse
    {
        $Notice_ID = Crypt::decryptString($request->Notice_ID);
        $Notice = NoticeBoard::where('id', $Notice_ID)->delete();

        if ($Notice) {
            return back()->with('Success!', 'Notice Has been Deleted!');
        }
        return back()->with('Error!', 'Something Went Wrong!');
    }
}
