<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer; 
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
class CustomerController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Customer::query();

            if ($request->filled('search')) {
                $query->where(function($q) use ($request) {
                    $q->where('name', 'LIKE', "%{$request->search}%")
                      ->orWhere('email', 'LIKE', "%{$request->search}%")
                      ->orWhere('phone', 'LIKE', "%{$request->search}%")
                      ->orWhere('address', 'LIKE', "%{$request->search}%")
                      ->orWhere('opening_balance', 'LIKE', "%{$request->search}%");
                });
            }

            if ($request->has('all')) {
                return response()->json([
                    'success' => true,
                    'data' => $query->get()
                ]);
            }

            $data = $query->paginate($request->get('limit', 25));
            return response()->json([
                'success' => true,
                'data' => $data->items(),
                'total' => $data->total(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage()
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function store(CustomerRequest $request)
    {
        try {
            $data = $request->validated();
            $data['created_by'] = auth()->id();
            Customer::create($data);

            return $this->apiResponse(true, 'Customer created successfully.', null, 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create customer: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function update(CustomerRequest $request, Customer $customer)
    {
        try {
            $data = $request->validated();
            $data['updated_by'] = auth()->id();
            $customer->update($data);

            return $this->apiResponse(true, 'Customer updated successfully.', null, 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update customer: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function destroy(CustomerRequest $request)
    {
        try {
            DB::beginTransaction();
            $ids = $request->get('ids') ? explode(',', $request->get('ids')) : [];
            if (count($ids) > 0) {
                Customer::whereIn('id', $ids)->update([
                    'deleted_by' => auth()->id()
                ]);
                Customer::whereIn('id', $ids)->delete();
                DB::commit();
                return $this->apiResponse(true, 'Customers deleted successfully.');
            } else {
                return $this->apiResponse(false, 'No customer IDs provided for deletion.', null, 400);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete customers: ' . $e->getMessage(),
            ], 500);
        }
    }
    private function apiResponse($success = true, $message = '', $data = null, $status = 200)
{
    return response()->json([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ], $status);
}
}
