<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\BankDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankDetailsController extends Controller
{
    public function insert(Request $request)
    {


        $validated = Validator::make($request->all(), [
            'bankname' => 'required',
            'branch' => 'required',
            'accno' => 'required'
        ]);

        if ($validated->fails()) {

            return response()->json([
                'status' => 0,
                'error' => $validated->errors()->toArray(),
                'data' => $this->getjsondata()
            ]);
        } else {

            $BankDetails = new BankDetails();
            $BankDetails->bankname = $request->bankname;
            $BankDetails->branch = $request->branch;
            $BankDetails->accno = $request->accno;
            $BankDetails->save();

            return response()->json([
                'status' => 1,
                'msg' => 'New account has been successfully added.',
                'data' => $this->getjsondata()
            ]);
        }
    }


    public function getdata()
    {
        $data = BankDetails::all();
        return view("welcome", ['data' => $data]);
    }

    public function getjsondata()
    {
        return BankDetails::all();
    }

    public function delete(Request $request)
    {
        $result = DB::delete('delete from bank_details where id = ?', [(int)$request->id]);
        return response()->json([
            'status' => 1,
            'msg' => 'Account has been successfully deleted.',
            'data' => $this->getjsondata()
        ]);
    }



    public function edit(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'bankname' => 'required',
            'branch' => 'required',
            'accno' => 'required'
        ]);

        if ($validated->fails()) {

            return response()->json([
                'status' => 0,
                'error' => $validated->errors()->toArray(),
                'data' => $this->getjsondata()
            ]);
        } else {

            $BankDetails = BankDetails::find($request->id);
            $BankDetails->bankname = $request->bankname;
            $BankDetails->branch = $request->branch;
            $BankDetails->accno = $request->accno;
            $BankDetails->save();

            return response()->json([
                'status' => 1,
                'msg' => 'Account has been successfully updated.',
                'data' => $this->getjsondata()
            ]);
        }
    }
}
