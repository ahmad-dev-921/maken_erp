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
     public function index(CustomerRequest $request)
    {
        try {
            $ids = $request->get('ids') ? explode(',', $request->get('ids')) : [];
            if (request()->has('name_list') && request('name_list') == 'true') {
                return $this->apiResponse(true, 'Customers names retrieved successfully.', DB::table('customers')->select('id', 'name')->whereNull('deleted_at')->get());
            }
            $query = Customer::query();
            $query->when(count($ids) > 0, function ($q) use ($ids) {
                return $q->whereIn('id', $ids);
            });
            $query->when($request->filled('search'), function ($q) use ($request) {
                return $q->where('name', 'LIKE', "%{$request->search}%")
                    ->orWhere('email', 'LIKE', "%{$request->search}%")
                    ->orWhere('phone', 'LIKE', "%{$request->search}%")
                    ->orWhere('address', 'LIKE', "%{$request->search}%")
                    ->orWhere('opening_balance', 'LIKE', "%{$request->search}%");
            });
            $query->when(count($ids) > 0, fn($q) => $q->whereIn('id', $ids));

            $hasData = $query->exists();

           
 if (!$hasData) {
                return $this->apiResponse(false, 'No customers found.', null, 404);
            }
            $data = $query->paginate(request('limit', 25));
            return CustomerResource::collection($data)
                ->additional([
                    'success' => true,
                    'message' => 'Customers retrieved successfully.',
                ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve customers: ' . $e->getMessage(),
            ], 500);
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