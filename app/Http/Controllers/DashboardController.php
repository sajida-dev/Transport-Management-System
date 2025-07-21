<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Exception;

use App\Services\BulkSmsService;
use App\Services\FirebaseService;
use Google\Cloud\Firestore\FirestoreClient;

class DashboardController extends Controller
{
    protected $smsService;
    protected $firebaseService;
    protected $firestore;

    public function __construct(BulkSmsService $smsService, FirebaseService $firebaseService)
    {
        $this->smsService = $smsService;
        $this->firebaseService = $firebaseService;
        
        // Initialize Firestore
        try {
            $this->firestore = new FirestoreClient([
                'projectId' => env('FIREBASE_PROJECT_ID', 'chishimba'),
            ]);
        } catch (\Exception $e) {
            report($e);
            $this->firestore = null;
        }
    }

    /**
     * Display the admin dashboard.
     * Route: admin.dashboard
     */
    public function home(): View
    {
        try {
            // Get truck bookings counts
            $truckBookingsRef = $this->firestore->collection('orders');
            $truckTotalBookings = count($truckBookingsRef->documents()->rows());
            $truckPendingRequests = count($truckBookingsRef->where('order_status', '==', 'pending')->documents()->rows());
            $truckCancelledOrders = count($truckBookingsRef->where('order_status', '==', 'cancelled')->documents()->rows());
            $truckApprovedOrders = count($truckBookingsRef->where('order_status', '==', 'approved')->documents()->rows());
            //Load approvals
            $loadRef = $this->firestore->collection('loads');
            $loadApprovals = count($loadRef->where('status', '==', 'pending')->documents()->rows());


            // Get load bookings counts
            $loadBookingsRef = $this->firestore->collection('load_orders');
            $loadTotalBookings = count($loadBookingsRef->documents()->rows());
            $loadPendingRequests = count($loadBookingsRef->where('order_status', '==', 'pending')->documents()->rows());
            $loadCancelledOrders = count($loadBookingsRef->where('order_status', '==', 'cancelled')->documents()->rows());
            $loadApprovedOrders = count($loadBookingsRef->where('order_status', '==', 'completed')->documents()->rows());

            // Get active trucks data
            $activeTrucks = 0;
            $activeTrucksData = [];
            $onlineUsers = $this->firestore->collection('users')
                ->where('isOnline', '==', true)
                ->documents()->rows();

            foreach ($onlineUsers as $user) {
                $userTrucks = $this->firestore->collection('trucks')
                    ->where('userId', '==', $user->id())
                    ->where('status', '==', 'approved')
                    ->documents()->rows();
                
                if (count($userTrucks) > 0) {
                    $activeTrucks++;
                    
                    // Add each truck to the active trucks data
                    foreach ($userTrucks as $truck) {
                        $truckData = $truck->data();
                        $truckData['id'] = $truck->id();
                        
                        // Get user details
                        $userData = $user->data();
                        $truckData['user'] = [
                            'name' => $userData['name'] ?? 'N/A',
                            'email' => $userData['email'] ?? 'N/A',
                            'phone' => $userData['phone'] ?? 'N/A'
                        ];
                        
                        $activeTrucksData[] = (object) $truckData;
                    }
                }
            }

            // Get user counts
            $loadOwners = count($this->firestore->collection('users')
                ->where('user_type', '==', 'Customer')
                ->documents()->rows());

            $transporters = count($this->firestore->collection('users')
                ->where('user_type', '==', 'Driver')
                ->documents()->rows());

            // Get KYC applications count
            $kycApplications = count($this->firestore->collection('trucks')
                ->where('status', '==', 'pending')
                ->documents()->rows());

            return view('admin.index', compact(
                'truckTotalBookings',
                'truckPendingRequests',
                'truckCancelledOrders',
                'truckApprovedOrders',
                'loadTotalBookings',
                'loadPendingRequests',
                'loadCancelledOrders',
                'loadApprovedOrders',
                'loadApprovals',
                'activeTrucks',
                'loadOwners',
                'transporters',
                'kycApplications',
                'activeTrucksData'
            ));
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Firestore Error: ' . $e->getMessage());
            
            // Return to the dashboard with error message
            return redirect()->route('admin.dashboard')->with('error', 'Error fetching data: ' . $e->getMessage());
        }
    }

    /**
     * Display truck bookings based on selection.
     */
    public function bookings(string $selection): View
    {
        try {
            $ordersRef = $this->firestore->collection('orders');
            $query = $ordersRef->orderBy('added_date', 'desc');

            // Apply status filter based on selection
            if ($selection !== 'all') {
                $status = match($selection) {
                    'pending' => 'pending',
                    'confirmed' => 'completed',
                    'in_transit' => 'in_transit',
                    'completed' => 'completed',
                    'cancelled' => 'cancelled',
                    default => null
                };
                
                if ($status) {
                    $query = $query->where('order_status', '==', $status);
                }
            }

            // Get the documents
            $documents = $query->documents();
            $bookings = [];

            // Debug information
            $debug = [
                'selection' => $selection,
                'status' => $status ?? 'all',
                'document_count' => $documents->size(),
                'collection' => 'orders'
            ];

            foreach ($documents as $doc) {
                $data = $doc->data();
                $data['id'] = $doc->id();
                
                // Get enhanced customer details using new helper method
                if (isset($data['cus_id'])) {
                    $customerDetails = $this->getCustomerDetails($data['cus_id']);
                    if ($customerDetails) {
                        $data['customer'] = $customerDetails;
                    } else {
                        $data['customer'] = [
                            'name' => 'N/A',
                            'email' => 'N/A',
                            'phone' => 'N/A'
                        ];
                    }
                }

                // Get enhanced transporter details if available using new helper method
                if (isset($data['truck_id']) && !empty($data['truck_id'])) {
                    $transporterDetails = $this->getTransporterDetails($data['truck_id']);
                    if ($transporterDetails) {
                        $data['transporter'] = $transporterDetails;
                        
                        // Add truck details for the view
                        $data['truck'] = (object) [
                            'id' => $data['truck_id'],
                            'plate_number' => $transporterDetails['truck']['plate_number'] ?? 'N/A',
                            'transporter_name' => $transporterDetails['truck']['transporter_name'] ?? $transporterDetails['name'],
                            'truck_type' => $transporterDetails['truck']['truck_type'] ?? 'N/A'
                        ];
                    } else {
                        // Fallback to basic truck details
                        try {
                            $truckDoc = $this->firestore->collection('trucks')->document($data['truck_id'])->snapshot();
                            if ($truckDoc->exists()) {
                                $truckData = $truckDoc->data();
                                $data['transporter'] = [
                                    'name' => $truckData['transporterName'] ?? 'N/A',
                                ];
                                $data['truck'] = (object) [
                                    'id' => $data['truck_id'],
                                    'plate_number' => $truckData['truck_plate_number'] ?? $truckData['licenseNumber'] ?? 'N/A',
                                    'transporter_name' => $truckData['transporterName'] ?? 'N/A',
                                    'truck_type' => $truckData['truck_type'] ?? 'N/A'
                                ];
                            }
                        } catch (\Exception $e) {
                            \Log::error('Error fetching truck details: ' . $e->getMessage());
                            $data['truck'] = null;
                            $data['transporter'] = null;
                        }
                    }
                } else {
                    $data['truck'] = null;
                    $data['transporter'] = null;
                }

                // Format dates for display
                if (isset($data['added_date'])) {
                    try {
                        $data['formatted_added_date'] = $data['added_date']->get()->format('M d, Y H:i');
                    } catch (\Exception $e) {
                        $data['formatted_added_date'] = 'N/A';
                    }
                }

                $bookings[] = (object) $data;
            }

            return view('admin.bookings.index', [
                'selection' => $selection,
                'bookings' => $bookings,
                'debug' => $debug
            ]);
        } catch (Exception $e) {
            report($e);
            return view('admin.bookings.index', [
                'selection' => $selection,
                'bookings' => [],
                'error' => 'Failed to fetch truck bookings: ' . $e->getMessage(),
                'debug' => [
                    'error' => $e->getMessage(),
                    'selection' => $selection,
                    'trace' => $e->getTraceAsString()
                ]
            ]);
        }
    }

    /**
     * Handle load booking submission (e.g., approve/cancel).
     */
    public function bookings_submit(Request $request, string $selection): RedirectResponse
    {
        try {
            if (!$this->firestore) {
                throw new \Exception('Firestore client is not initialized');
            }

            $action = $request->input('action');
            $booking_id = $request->input('booking_id');
            $truck_id = $request->input('truck_id');
            
            if (empty($booking_id)) {
                return redirect()->route('admin.bookings', ['selection' => $selection])
                    ->with('error', 'Booking ID is required.');
            }

            // Get the order document
            $ordersRef = $this->firestore->collection('orders');
            $orderDoc = $ordersRef->document($booking_id);
            $orderSnapshot = $orderDoc->snapshot();
            
            if (!$orderSnapshot->exists()) {
                return redirect()->route('admin.bookings', ['selection' => $selection])
                    ->with('error', 'Booking not found.');
            }
            
            $orderData = $orderSnapshot->data();
            
            // Get user contact info from users collection using cus_id as document ID
            $fcm_token = null;
            $phone_number = null;
            
            if (isset($orderData['cus_id'])) {
                try {
                    $userDoc = $this->firestore->collection('users')->document($orderData['cus_id'])->snapshot();
                    if ($userDoc->exists()) {
                        $userData = $userDoc->data();
                        $fcm_token = $userData['fcm_token'] ?? $userData['fcmToken'] ?? null;
                        $phone_number = $userData['phone_number'] ?? $userData['phone'] ?? null;
                        
                        \Log::info('Truck booking customer contact info retrieved', [
                            'cus_id' => $orderData['cus_id'],
                            'has_fcm_token' => !empty($fcm_token),
                            'has_phone' => !empty($phone_number)
                        ]);
                    }
                } catch (Exception $e) {
                    \Log::error('Error fetching customer data for truck booking notifications', [
                        'cus_id' => $orderData['cus_id'],
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            switch ($action) {
                case 'approve':
                    // Get truck details
                    if (!empty($truck_id)) {
                        $truckDoc = $this->firestore->collection('trucks')->document($truck_id);
                        $truckSnapshot = $truckDoc->snapshot();
                        
                        if ($truckSnapshot->exists()) {
                            $truckData = $truckSnapshot->data();
                            
                            // Update order status
                            $orderDoc->update([
                                ['path' => 'order_status', 'value' => 'in_transit'],
                                ['path' => 'truck_id', 'value' => $truck_id],
                                ['path' => 'driver_id', 'value' => $truckData['userId'] ?? null],
                                ['path' => 'approved_date', 'value' => new \Google\Cloud\Core\Timestamp(new \DateTime())]
                            ]);

                            // Set truck to offline
                            $truckDoc->update([
                                ['path' => 'isOnline', 'value' => false]
                            ]);

                            // Send notifications
                            $this->sendNotifications(
                                $fcm_token,
                                $phone_number,
                                "TRUCK BOOKING APPROVED",
                                "Your truck booking request has been approved.",
                                "Good news! Your truck booking request has been approved. Your driver will contact you soon."
                            );

                            return redirect()->route('admin.bookings', ['selection' => $selection])
                                ->with('success', 'Booking approved successfully!');
                        }
                    }
                    return redirect()->route('admin.bookings', ['selection' => $selection])
                        ->with('error', 'Truck not found.');

                case 'cancel':
                    $reason = $request->input('rejection_reason');
                    if (empty($reason)) {
                        return redirect()->route('admin.bookings', ['selection' => $selection])
                            ->with('error', 'Cancellation reason is required.');
                    }

                    $orderDoc->update([
                        ['path' => 'order_status', 'value' => 'cancelled'],
                        ['path' => 'reject_reason', 'value' => $reason],
                        ['path' => 'cancelled_date', 'value' => new \Google\Cloud\Core\Timestamp(new \DateTime())]
                    ]);

                    // Send notifications
                    $this->sendNotifications(
                        $fcm_token,
                        $phone_number,
                        "TRUCK BOOKING CANCELLED",
                        "Your truck booking request has been cancelled.",
                        "Sorry, your truck booking request has been cancelled. Reason: {$reason}"
                    );

                    return redirect()->route('admin.bookings', ['selection' => $selection])
                        ->with('success', 'Booking cancelled successfully.');

                case 'complete':
                    if ($orderData['order_status'] !== 'in_transit') {
                        return redirect()->route('admin.bookings', ['selection' => $selection])
                            ->with('error', 'Only in-transit bookings can be marked as completed.');
                    }

                    // Update order status to completed
                    $orderDoc->update([
                        ['path' => 'order_status', 'value' => 'completed'],
                        ['path' => 'completed_date', 'value' => new \Google\Cloud\Core\Timestamp(new \DateTime())]
                    ]);

                    // Set truck back to online if truck_id exists
                    if (isset($orderData['truck_id']) && !empty($orderData['truck_id'])) {
                        try {
                            $truckDoc = $this->firestore->collection('trucks')->document($orderData['truck_id']);
                            $truckDoc->update([
                                ['path' => 'isOnline', 'value' => true]
                            ]);
                        } catch (\Exception $e) {
                            \Log::error('Error updating truck status to online after completion', [
                                'truck_id' => $orderData['truck_id'],
                                'error' => $e->getMessage()
                            ]);
                        }
                    }

                    // Send notifications
                    $this->sendNotifications(
                        $fcm_token,
                        $phone_number,
                        "TRUCK BOOKING COMPLETED",
                        "Your truck booking has been completed successfully.",
                        "Congratulations! Your truck booking has been completed successfully. Thank you for using LoadMasta."
                    );

                    return redirect()->route('admin.bookings', ['selection' => $selection])
                        ->with('success', 'Truck booking has been marked as completed.');

                case 'cancel_in_transit':
                    if ($orderData['order_status'] !== 'in_transit') {
                        return redirect()->route('admin.bookings', ['selection' => $selection])
                            ->with('error', 'Only in-transit bookings can be cancelled.');
                    }

                    $reason = $request->input('cancellation_reason');
                    if (empty($reason)) {
                        return redirect()->route('admin.bookings', ['selection' => $selection])
                            ->with('error', 'Cancellation reason is required.');
                    }

                    $orderDoc->update([
                        ['path' => 'order_status', 'value' => 'cancelled'],
                        ['path' => 'cancellation_reason', 'value' => $reason],
                        ['path' => 'cancelled_date', 'value' => new \Google\Cloud\Core\Timestamp(new \DateTime())]
                    ]);

                    // Send notifications
                    $this->sendNotifications(
                        $fcm_token,
                        $phone_number,
                        "BOOKING CANCELLED",
                        "Your in-transit booking has been cancelled.",
                        "Your in-transit booking has been cancelled. Reason: {$reason}"
                    );

                    return redirect()->route('admin.bookings', ['selection' => $selection])
                        ->with('success', 'In-transit booking cancelled successfully.');

                default:
                    return redirect()->route('admin.bookings', ['selection' => $selection])
                        ->with('error', 'Invalid action specified.');
            }
        } catch (\Exception $e) {
            report($e);
            return redirect()->route('admin.bookings', ['selection' => $selection])
                ->with('error', 'Failed to process booking action: ' . $e->getMessage());
        }
    }

    /**
     * Display load approvals based on selection.
     */
    public function loadApprovals(string $selection): View
    {
        try {
            $loadsRef = $this->firestore->collection('loads');
            $query = $loadsRef->orderBy('added_date', 'desc');

            // Apply status filter based on selection
            if ($selection !== 'all') {
                $status = match($selection) {
                    'pending' => 'pending',
                    'approved' => 'approved',
                    'in_transit' => 'in_transit',
                    'completed' => 'completed',
                    'cancelled' => 'cancelled',
                    default => null
                };
                
                if ($status) {
                    $query = $query->where('status', '==', $status);
                }
            }

            // Get the documents
            $documents = $query->documents();
            $loads = [];

            // Debug information
            $debug = [
                'selection' => $selection,
                'status' => $status ?? 'all',
                'document_count' => $documents->size(),
                'collection' => 'loads'
            ];

            foreach ($documents as $doc) {
                $data = $doc->data();
                $data['id'] = $doc->id();
                
                // Get user details
                if (isset($data['user_id'])) {
                    $userDoc = $this->firestore->collection('users')->document($data['user_id'])->snapshot();
                    if ($userDoc->exists()) {
                        $userData = $userDoc->data();
                        $data['user'] = [
                            'name' => $userData['name'] ?? 'N/A',
                            'email' => $userData['email'] ?? 'N/A',
                            'phone' => $userData['phone'] ?? 'N/A'
                        ];
                    }
                }

                $loads[] = (object) $data;
            }

            return view('admin.loads.approvals', [
                'selection' => $selection,
                'loads' => $loads,
                'debug' => $debug
            ]);
        } catch (Exception $e) {
            report($e);
            return view('admin.loads.approvals', [
                'selection' => $selection,
                'loads' => [],
                'error' => 'Failed to fetch loads: ' . $e->getMessage(),
                'debug' => [
                    'error' => $e->getMessage(),
                    'selection' => $selection
                ]
            ]);
        }
    }

     /**
     * Handle load approval submission.
     */
    public function loadApprovalsSubmit(Request $request, string $selection, string $load_id)
    {
        try {
            $action = $request->input('action');
            
            $loadsRef = $this->firestore->collection('loads');
            $loadDoc = $loadsRef->document($load_id);
            $loadSnapshot = $loadDoc->snapshot();
            
            if (!$loadSnapshot->exists()) {
                return redirect()->route('admin.loadApprovals', ['selection' => $selection])
                    ->with('error', 'Load not found.');
            }
            
            $loadData = $loadSnapshot->data();
            
            // Get user contact info from users collection using user_id as document ID
            $fcm_token = null;
            $phone_number = null;
            
            if (isset($loadData['user_id'])) {
                try {
                    $userDoc = $this->firestore->collection('users')->document($loadData['user_id'])->snapshot();
                    if ($userDoc->exists()) {
                        $userData = $userDoc->data();
                        $fcm_token = $userData['fcm_token'] ?? $userData['fcmToken'] ?? null;
                        $phone_number = $userData['phone_number'] ?? $userData['phone'] ?? null;
                        
                        \Log::info('Load owner contact info retrieved', [
                            'user_id' => $loadData['user_id'],
                            'has_fcm_token' => !empty($fcm_token),
                            'has_phone' => !empty($phone_number)
                        ]);
                    }
                } catch (Exception $e) {
                    \Log::error('Error fetching load owner data for notifications', [
                        'user_id' => $loadData['user_id'],
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            if ($action === 'approve') {
                // Update load status to approved
                $loadDoc->update([
                    ['path' => 'status', 'value' => 'available'],
                    ['path' => 'approved_date', 'value' => new \Google\Cloud\Core\Timestamp(new \DateTime())]
                ]);

                // Send notifications
                $this->sendNotifications(
                    $fcm_token,
                    $phone_number,
                    "LOAD APPROVED",
                    "Your load has been approved and is now available for booking.",
                    "Great news! Your load has been approved and is now available for drivers to book.",
                    $loadData['user_id'] ?? null,
                    'load_approval',
                    [
                        'load_id' => $load_id,
                        'status' => 'approved',
                        'approved_date' => (new \DateTime())->format('Y-m-d H:i:s')
                    ]
                );
                
                return redirect()->route('admin.loadApprovals', ['selection' => $selection])
                    ->with('success', 'Load has been approved successfully.');
                    
            } elseif ($action === 'reject') {
                $reason = $request->input('rejection_reason');
                
                if (empty($reason)) {
                    return redirect()->route('admin.loadApprovals', ['selection' => $selection])
                        ->with('error', 'Rejection reason is required.');
                }
                
                // Update load status to rejected
                $loadDoc->update([
                    ['path' => 'status', 'value' => 'rejected'],
                    ['path' => 'rejection_reason', 'value' => $reason],
                    ['path' => 'rejected_date', 'value' => new \Google\Cloud\Core\Timestamp(new \DateTime())]
                ]);

                // Send notifications
                $this->sendNotifications(
                    $fcm_token,
                    $phone_number,
                    "LOAD REJECTED",
                    "Your load has been rejected.",
                    "Sorry, your load has been rejected. Reason: {$reason}. Please review and resubmit.",
                    $loadData['user_id'] ?? null,
                    'load_rejection',
                    [
                        'load_id' => $load_id,
                        'status' => 'rejected',
                        'rejection_reason' => $reason,
                        'rejected_date' => (new \DateTime())->format('Y-m-d H:i:s')
                    ]
                );
                
                return redirect()->route('admin.loadApprovals', ['selection' => $selection])
                    ->with('success', 'Load has been rejected successfully.');
            } else {
                return redirect()->route('admin.loadApprovals', ['selection' => $selection])
                    ->with('error', 'Invalid action specified.');
            }
        } catch (Exception $e) {
            report($e);
            return redirect()->route('admin.loadApprovals', ['selection' => $selection])
                ->with('error', 'Failed to process load action: ' . $e->getMessage());
        }
    }

    /**
     * Display load bookings based on selection.
     */
    public function load_bookings(string $selection): View
    {
        try {
            $loadOrdersRef = $this->firestore->collection('load_orders');
            $query = $loadOrdersRef->orderBy('added_date', 'desc');

            // Apply status filter based on selection
            if ($selection !== 'all') {
                $status = match($selection) {
                    'pending' => 'pending',
                    'confirmed' => 'approved',
                    'in_transit' => 'in_transit',
                    'completed' => 'completed',
                    'cancelled' => 'cancelled',
                    default => null
                };
                
                if ($status) {
                    $query = $query->where('order_status', '==', $status);
                }
            }

            // Get the documents
            $documents = $query->documents();
            $bookings = [];

            foreach ($documents as $doc) {
                $data = $doc->data();
                $data['id'] = $doc->id();
                
                // Get customer details
                if (isset($data['cus_id'])) {
                    $customerDoc = $this->firestore->collection('users')->document($data['cus_id'])->snapshot();
                    if ($customerDoc->exists()) {
                        $customerData = $customerDoc->data();
                        $data['customer'] = [
                            'name' => $customerData['name'] ?? 'N/A',
                            'email' => $customerData['email'] ?? 'N/A',
                            'phone' => $customerData['phone'] ?? 'N/A'
                        ];
                    }
                }

                // Get transporter details if available
                if (isset($data['truck_id'])) {
                    $truckDoc = $this->firestore->collection('trucks')->document($data['truck_id'])->snapshot();
                    if ($truckDoc->exists()) {
                        $truckData = $truckDoc->data();
                        $data['transporter'] = [
                            'name' => $truckData['transporterName'] ?? 'N/A',
                            'plate_number' => $truckData['truck_plate_number'] ?? 'N/A'
                        ];
                    }
                }

                $bookings[] = (object) $data;
            }

            return view('admin.load_bookings.index', [
                'selection' => $selection,
                'loadOrders' => $bookings
            ]);
        } catch (Exception $e) {
            report($e);
            return view('admin.load_bookings.index', [
                'selection' => $selection,
                'loadOrders' => [],
                'error' => 'Failed to fetch load orders: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Handle load booking submission.
     */
    public function loadApplicationsSubmit(Request $request, string $selection, string $booking_id)
    {
        try {
            $action = $request->input('action');
            $cancellation_reason = $request->input('cancellation_reason');
            
            // Get the load order document
            $loadOrdersRef = $this->firestore->collection('load_orders');
            $loadOrderDoc = $loadOrdersRef->document($booking_id);
            $loadOrderSnapshot = $loadOrderDoc->snapshot();
            
            if (!$loadOrderSnapshot->exists()) {
                return Redirect::route('admin.load_bookings', ['selection' => $selection])
                    ->with('error', 'Load booking not found.');
            }
            
            $loadOrderData = $loadOrderSnapshot->data();
            $load_id = $loadOrderData['load_id'] ?? null;
            
            // Get user contact info from users collection using cus_id as document ID
            $fcm_token = null;
            $phone_number = null;
            
            if (isset($loadOrderData['cus_id'])) {
                try {
                    $userDoc = $this->firestore->collection('users')->document($loadOrderData['cus_id'])->snapshot();
                    if ($userDoc->exists()) {
                        $userData = $userDoc->data();
                        $fcm_token = $userData['fcm_token'] ?? $userData['fcmToken'] ?? null;
                        $phone_number = $userData['phone_number'] ?? $userData['phone'] ?? null;
                        
                        \Log::info('Load order customer contact info retrieved', [
                            'cus_id' => $loadOrderData['cus_id'],
                            'has_fcm_token' => !empty($fcm_token),
                            'has_phone' => !empty($phone_number)
                        ]);
                    }
                } catch (Exception $e) {
                    \Log::error('Error fetching customer data for load order notifications', [
                        'cus_id' => $loadOrderData['cus_id'],
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            if ($action === 'confirm') {
                if (empty($load_id)) {
                    return Redirect::route('admin.load_bookings', ['selection' => $selection])
                        ->with('error', 'Load ID is missing from the booking.');
                }
                
                // Update load order status to in_transit
                $loadOrderDoc->update([
                    ['path' => 'order_status', 'value' => 'in_transit'],
                    ['path' => 'confirmed_date', 'value' => new \Google\Cloud\Core\Timestamp(new \DateTime())]
                ]);
                
                // Update the referenced load status to unavailable
                $loadsRef = $this->firestore->collection('loads');
                $loadDoc = $loadsRef->document($load_id);
                $loadSnapshot = $loadDoc->snapshot();
                
                if ($loadSnapshot->exists()) {
                    $loadDoc->update([
                        ['path' => 'status', 'value' => 'in_transit'],
                        ['path' => 'updated_date', 'value' => new \Google\Cloud\Core\Timestamp(new \DateTime())]
                    ]);
                }

                // Send notifications
                $this->sendNotifications(
                    $fcm_token,
                    $phone_number,
                    "LOAD BOOKING CONFIRMED",
                    "Your load booking has been confirmed and is now in transit.",
                    "Your load booking has been confirmed. The driver is now en route to pickup your load.",
                    $loadOrderData['cus_id'] ?? null,
                    'load_booking_confirmation',
                    [
                        'booking_id' => $booking_id,
                        'load_id' => $load_id,
                        'status' => 'in_transit',
                        'confirmed_date' => (new \DateTime())->format('Y-m-d H:i:s')
                    ]
                );
                
                return Redirect::route('admin.load_bookings', ['selection' => $selection])
                    ->with('success', 'Load booking has been confirmed and is now in transit.');
                    
            } elseif ($action === 'cancel') {
                // Validate cancellation reason more thoroughly
                if (empty($cancellation_reason) || trim($cancellation_reason) === '') {
                    return Redirect::route('admin.load_bookings', ['selection' => $selection])
                        ->with('error', 'Please provide a cancellation reason.');
                }
                
                // Update load order status to cancelled
                $loadOrderDoc->update([
                    ['path' => 'order_status', 'value' => 'cancelled'],
                    ['path' => 'cancellation_reason', 'value' => trim($cancellation_reason)],
                    ['path' => 'cancelled_date', 'value' => new \Google\Cloud\Core\Timestamp(new \DateTime())]
                ]);

                // Send notifications
                $this->sendNotifications(
                    $fcm_token,
                    $phone_number,
                    "LOAD BOOKING CANCELLED",
                    "Your load booking has been cancelled.",
                    "Your load booking has been cancelled. Reason: " . trim($cancellation_reason),
                    $loadOrderData['cus_id'] ?? null,
                    'load_booking_cancellation',
                    [
                        'booking_id' => $booking_id,
                        'status' => 'cancelled',
                        'cancellation_reason' => trim($cancellation_reason),
                        'cancelled_date' => (new \DateTime())->format('Y-m-d H:i:s')
                    ]
                );
                
                return Redirect::route('admin.load_bookings', ['selection' => $selection])
                    ->with('success', 'Load booking has been cancelled.');
                    
            } elseif ($action === 'complete') {
                if ($loadOrderData['order_status'] !== 'in_transit') {
                    return Redirect::route('admin.load_bookings', ['selection' => $selection])
                        ->with('error', 'Only in-transit loads can be marked as completed.');
                }
                
                // Update load order status to completed
                $loadOrderDoc->update([
                    ['path' => 'order_status', 'value' => 'completed'],
                    ['path' => 'completed_date', 'value' => new \Google\Cloud\Core\Timestamp(new \DateTime())]
                ]);
                
                // Update the referenced load status to completed
                if (!empty($load_id)) {
                    $loadsRef = $this->firestore->collection('loads');
                    $loadDoc = $loadsRef->document($load_id);
                    $loadSnapshot = $loadDoc->snapshot();
                    
                    if (!empty($load_id)) {
                        $loadsRef = $this->firestore->collection('loads');
                        $loadDoc = $loadsRef->document($load_id);
                        $loadSnapshot = $loadDoc->snapshot();
                        
                        if ($loadSnapshot->exists()) {
                            $loadDoc->update([
                                ['path' => 'status', 'value' => 'completed'],
                                ['path' => 'completed_date', 'value' => new \Google\Cloud\Core\Timestamp(new \DateTime())]
                            ]);
                        }
                    }
                    if ($loadSnapshot->exists()) {
                        $loadDoc->update([
                            ['path' => 'status', 'value' => 'completed'],
                            ['path' => 'completed_date', 'value' => new \Google\Cloud\Core\Timestamp(new \DateTime())]
                        ]);
                    }

                }

                // Send notifications
                $this->sendNotifications(
                    $fcm_token,
                    $phone_number,
                    "LOAD COMPLETED",
                    "Your load has been successfully delivered.",
                    "Congratulations! Your load has been successfully delivered. Thank you for using LoadMasta.",
                    $loadOrderData['cus_id'] ?? null,
                    'load_completion',
                    [
                        'booking_id' => $booking_id,
                        'status' => 'completed',
                        'completed_date' => (new \DateTime())->format('Y-m-d H:i:s')
                    ]
                );
                
                return Redirect::route('admin.load_bookings', ['selection' => $selection])
                    ->with('success', 'Load has been marked as completed.');
                    
            } elseif ($action === 'cancel_in_transit') {
                if ($loadOrderData['order_status'] !== 'in_transit') {
                    return Redirect::route('admin.load_bookings', ['selection' => $selection])
                        ->with('error', 'Only in-transit loads can be cancelled.');
                }
                
                // Validate cancellation reason more thoroughly
                if (empty($cancellation_reason) || trim($cancellation_reason) === '') {
                    return Redirect::route('admin.load_bookings', ['selection' => $selection])
                        ->with('error', 'Please provide a cancellation reason.');
                }
                
                // Update load order status to cancelled
                $loadOrderDoc->update([
                    ['path' => 'order_status', 'value' => 'cancelled'],
                    ['path' => 'cancellation_reason', 'value' => trim($cancellation_reason)],
                    ['path' => 'cancelled_date', 'value' => new \Google\Cloud\Core\Timestamp(new \DateTime())]
                ]);
                
                // Update the referenced load status to available again
                if (!empty($load_id)) {
                    $loadsRef = $this->firestore->collection('loads');
                    $loadDoc = $loadsRef->document($load_id);
                    $loadSnapshot = $loadDoc->snapshot();
                    
                    if ($loadSnapshot->exists()) {
                        $loadDoc->update([
                            ['path' => 'status', 'value' => 'available'],
                            ['path' => 'updated_date', 'value' => new \Google\Cloud\Core\Timestamp(new \DateTime())]
                        ]);
                    }
                }

                // Send notifications
                $this->sendNotifications(
                    $fcm_token,
                    $phone_number,
                    "LOAD CANCELLED",
                    "Your in-transit load has been cancelled.",
                    "Your in-transit load has been cancelled. Reason: " . trim($cancellation_reason) . ". Your load is now available for booking again.",
                    $loadOrderData['cus_id'] ?? null,
                    'load_in_transit_cancellation',
                    [
                        'booking_id' => $booking_id,
                        'status' => 'cancelled',
                        'cancellation_reason' => trim($cancellation_reason),
                        'cancelled_date' => (new \DateTime())->format('Y-m-d H:i:s')
                    ]
                );
                
                return Redirect::route('admin.load_bookings', ['selection' => $selection])
                    ->with('success', 'In-transit load has been cancelled.');
            } else {
                return Redirect::route('admin.load_bookings', ['selection' => $selection])
                    ->with('error', 'Invalid action specified.');
            }
        } catch (Exception $e) {
            report($e);
            return Redirect::route('admin.load_bookings', ['selection' => $selection])
                ->with('error', 'Failed to process load booking action: ' . $e->getMessage());
        }
    }

    /**
     * Display KYC applications.
     */
    public function kyc(): View
    {
        try {
            $trucksRef = $this->firestore->collection('trucks');
            $kycQuery = $trucksRef->where('status', '==', 'pending');
            $kycApplications = $kycQuery->documents();

            $documentCount = 0;
            $rawData = [];

            $kycData = [];
            foreach ($kycApplications as $doc) {
                $documentCount++;
                $data = $doc->data();
                $rawData[] = $data;
                
                $data['id'] = $doc->id();
                
                // Map the fields to match our view expectations using the actual Firestore field names
                $transporterNames = explode(' ', $data['transporterName'] ?? '');
                $data['driver_first_name'] = $transporterNames[0] ?? 'N/A';
                $data['driver_last_name'] = isset($transporterNames[1]) ? implode(' ', array_slice($transporterNames, 1)) : '';
                $data['driver_nrc_number'] = $data['idNumber'] ?? 'N/A';
                $data['license_number'] = $data['licenseNumber'] ?? 'N/A';
                $data['trailer_number'] = $data['trailerNumber'] ?? 'N/A';
                $data['tonage'] = $data['tonnage'] ?? 'N/A';
                $data['truck_model'] = $data['model'] ?? 'N/A';
                $data['truck_plate_number'] = $data['licenseNumber'] ?? 'N/A';
                $data['nrc_document'] = $data['drivingLicenseFront'] ?? '#';
                $data['added_date'] = $data['added_date'] ?? now()->toDateTimeString();
                
                $kycData[] = (object) $data;
            }
            
            // Create a custom paginator
            $perPage = 10;
            $currentPage = request()->get('page', 1);
            $pagedData = array_slice($kycData, ($currentPage - 1) * $perPage, $perPage);
            
            $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
                $pagedData,
                count($kycData),
                $perPage,
                $currentPage,
                ['path' => request()->url()]
            );

            return view('admin.kyc.index', [
                'kycApplications' => $paginator,
                'debug' => [
                    'documentCount' => $documentCount,
                    'rawData' => $rawData
                ]
            ]);
        } catch (Exception $e) {
            report($e);
            return view('admin.kyc.index', [
                'kycApplications' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10, 1, ['path' => request()->url()]),
                'error' => 'Failed to fetch KYC applications: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display detailed KYC application information
     */
    public function kycDetail(string $id): View
    {
        try {
            $trucksRef = $this->firestore->collection('trucks');
            $doc = $trucksRef->document($id)->snapshot();
            
            if (!$doc->exists()) {
                return view('admin.kyc.detail', [
                    'error' => 'KYC application not found'
                ]);
            }

            $data = $doc->data();
            $data['id'] = $doc->id();
            
            // Fetch user details if userId exists
            if (isset($data['userId'])) {
                try {
                    $userDoc = $this->firestore->collection('users')->document($data['userId'])->snapshot();
                    if ($userDoc->exists()) {
                        $userData = $userDoc->data();
                        $data['user'] = [
                            'first_name' => $userData['first_name'] ?? 'N/A',
                            'last_name' => $userData['last_name'] ?? 'N/A',
                            'profileImage' => $userData['profileImage'] ?? null,
                            'gender' => $userData['gender'] ?? 'N/A',
                            'country' => $userData['country'] ?? 'N/A',
                            'city_town' => $userData['city_town'] ?? 'N/A',
                            'phone_number' => $userData['phone_number'] ?? 'N/A',
                            'nrc' => $userData['nrc'] ?? 'N/A',
                            'province' => $userData['province'] ?? 'N/A',
                            'user_type' => $userData['user_type'] ?? 'N/A',
                            'driver_verified' => $userData['driver_verified'] ?? false,
                            'isOnline' => $userData['isOnline'] ?? false,
                            'added_date' => isset($userData['added_date']) ? $userData['added_date']->get()->format('M d, Y H:i') : 'N/A'
                        ];
                    }
                } catch (Exception $e) {
                    report($e);
                    $data['user'] = null;
                }
            }
            
            return view('admin.kyc.detail', [
                'application' => (object) $data
            ]);
        } catch (Exception $e) {
            report($e);
            return view('admin.kyc.detail', [
                'error' => 'Failed to fetch KYC application details: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Handle KYC application approval.
     */
    public function approveKyc(Request $request, string $id)
    {
        try {
            \Log::info('KYC Approval Started', ['kyc_id' => $id]);
            
            $trucksRef = $this->firestore->collection('trucks');
            $truck = $trucksRef->document($id);
            $truckSnapshot = $truck->snapshot();
            
            if (!$truckSnapshot->exists()) {
                \Log::error('KYC application not found', ['kyc_id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'KYC application not found.'
                ], 404);
            }
            
            $truckData = $truckSnapshot->data();
            \Log::info('Truck data retrieved', ['truck_data' => $truckData]);
            
            // Get user contact info from users collection using userId as document ID
            $fcm_token = null;
            $phone_number = null;
            
            if (isset($truckData['userId'])) {
                try {
                    $userRef = $this->firestore->collection('users')->document($truckData['userId']);
                    $userSnapshot = $userRef->snapshot();
                    
                    if ($userSnapshot->exists()) {
                        $userData = $userSnapshot->data();
                        $fcm_token = $userData['fcm_token'] ?? $userData['fcmToken'] ?? null;
                        $phone_number = $userData['phone_number'] ?? $userData['phone'] ?? null;
                        
                        \Log::info('User contact info retrieved for KYC approval', [
                            'user_id' => $truckData['userId'],
                            'has_fcm_token' => !empty($fcm_token),
                            'has_phone' => !empty($phone_number),
                            'fcm_token' => $fcm_token ? substr($fcm_token, 0, 20) . '...' : 'null',
                            'phone' => $phone_number
                        ]);
                        
                        // Update user's driver_verified status
                        $userRef->update([
                            ['path' => 'driver_verified', 'value' => true]
                        ]);
                    } else {
                        \Log::warning('User not found for truck', ['user_id' => $truckData['userId']]);
                    }
                } catch (Exception $e) {
                    \Log::error('Error fetching user data for KYC approval notifications', [
                        'user_id' => $truckData['userId'],
                        'error' => $e->getMessage()
                    ]);
                }
            } else {
                \Log::warning('No userId found in truck data', ['truck_id' => $id]);
            }
            
            // Update truck status
            $truck->update([
                ['path' => 'status', 'value' => 'approved'],
                ['path' => 'approved_date', 'value' => new \Google\Cloud\Core\Timestamp(new \DateTime())]
            ]);
            
            \Log::info('Truck status updated to approved', ['truck_id' => $id]);

            // Send notifications with detailed logging
            \Log::info('Attempting to send KYC approval notifications', [
                'fcm_token_available' => !empty($fcm_token),
                'phone_available' => !empty($phone_number)
            ]);
            
            $this->sendNotifications(
                $fcm_token,
                $phone_number,
                "KYC APPROVED",
                "Your truck KYC has been approved.",
                "Great news! Your truck KYC has been approved. You can now go online in your manage truck options to be available for bookings.",
                $truckData['userId'] ?? null,
                'kyc_approval',
                [
                    'truck_id' => $id,
                    'status' => 'approved',
                    'approved_date' => (new \DateTime())->format('Y-m-d H:i:s')
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'KYC application approved successfully.'
            ]);
        } catch (Exception $e) {
            \Log::error('Error in KYC approval', [
                'kyc_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve KYC application: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle KYC application rejection.
     */
    public function rejectKyc(Request $request, string $id)
    {
        try {
            \Log::info('KYC Rejection Started', ['kyc_id' => $id]);
            
            $reason = $request->input('reason');
            
            if (empty($reason)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rejection reason is required.'
                ], 400);
            }

            $trucksRef = $this->firestore->collection('trucks');
            $truck = $trucksRef->document($id);
            $truckSnapshot = $truck->snapshot();
            
            if (!$truckSnapshot->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'KYC application not found.'
                ], 404);
            }
            
            $truckData = $truckSnapshot->data();
            
            // Get user contact info from users collection using userId as document ID
            $fcm_token = null;
            $phone_number = null;
            
            if (isset($truckData['userId'])) {
                try {
                    $userRef = $this->firestore->collection('users')->document($truckData['userId']);
                    $userSnapshot = $userRef->snapshot();
                    
                    if ($userSnapshot->exists()) {
                        $userData = $userSnapshot->data();
                        $fcm_token = $userData['fcm_token'] ?? $userData['fcmToken'] ?? null;
                        $phone_number = $userData['phone_number'] ?? $userData['phone'] ?? null;
                        
                        \Log::info('User contact info retrieved for KYC rejection', [
                            'user_id' => $truckData['userId'],
                            'has_fcm_token' => !empty($fcm_token),
                            'has_phone' => !empty($phone_number)
                        ]);
                    }
                } catch (Exception $e) {
                    \Log::error('Error fetching user data for KYC rejection notifications', [
                        'user_id' => $truckData['userId'],
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            $truck->update([
                ['path' => 'status', 'value' => 'rejected'],
                ['path' => 'rejected_date', 'value' => new \Google\Cloud\Core\Timestamp(new \DateTime())],
                ['path' => 'rejection_reason', 'value' => $reason]
            ]);

            // Send notifications
            $this->sendNotifications(
                $fcm_token,
                $phone_number,
                "KYC REJECTED",
                "Your truck KYC has been rejected.",
                "Your truck KYC has been rejected. Reason: {$reason}. Please review your KYC documents and try again.",
                $truckData['userId'] ?? null,
                'kyc_rejection',
                [
                    'truck_id' => $id,
                    'status' => 'rejected',
                    'rejection_reason' => $reason,
                    'rejected_date' => (new \DateTime())->format('Y-m-d H:i:s')
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'KYC application rejected successfully.'
            ]);
        } catch (Exception $e) {
            \Log::error('Error in KYC rejection', [
                'kyc_id' => $id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject KYC application: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the user profile page.
     */
    public function profile_page(): View
    {
        // Placeholder: Fetch authenticated admin user data
        $adminUser = null; // Replace with Auth::user() or similar
        return view('admin.profile.index', compact('adminUser')); // Example view path
    }

    // --- New Methods Based on Routes --- 

    /**
     * Display a list of all users.
     */
    public function users(Request $request): View
    {
        try {
            $perPage = 15; // Number of items per page
            $currentPage = $request->query('page', 1);
            $startAt = ($currentPage - 1) * $perPage;

            $usersRef = $this->firestore->collection('users');
            $usersQuery = $usersRef->orderBy('added_date', 'desc');
            
            // Get total count for pagination
            $total = $usersQuery->documents()->size();
            
            // Get paginated results
            $usersQuery = $usersQuery->offset($startAt)->limit($perPage);
            $users = $usersQuery->documents();

            $usersData = [];
            foreach ($users as $doc) {
                $data = $doc->data();
                $data['uid'] = $doc->id();
                
                // Format dates for display
                if (isset($data['added_date'])) {
                    try {
                        $data['formatted_added_date'] = $data['added_date']->get()->format('M d, Y H:i');
                    } catch (Exception $e) {
                        $data['formatted_added_date'] = 'N/A';
                    }
                }
                
                $usersData[] = (object) $data;
            }

            // Create a custom paginator
            $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
                $usersData,
                $total,
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            // Get summary statistics
            $statistics = [
                'total_users' => $total,
                'load_owners' => count($this->firestore->collection('users')
                    ->where('user_type', '==', 'Customer')
                    ->documents()->rows()),
                'transporters' => count($this->firestore->collection('users')
                    ->where('user_type', '==', 'Driver')
                    ->documents()->rows()),
                'verified_drivers' => count($this->firestore->collection('users')
                    ->where('user_type', '==', 'Driver')
                    ->where('driver_verified', '==', true)
                    ->documents()->rows()),
                'online_users' => count($this->firestore->collection('users')
                    ->where('isOnline', '==', true)
                    ->documents()->rows())
            ];

            return view('admin.users.index', [
                'users' => $paginator,
                'statistics' => $statistics
            ]);
        } catch (Exception $e) {
            report($e);
            return view('admin.users.index', [
                'users' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15, 1),
                'statistics' => [
                    'total_users' => 0,
                    'load_owners' => 0,
                    'transporters' => 0,
                    'verified_drivers' => 0,
                    'online_users' => 0
                ],
                'error' => 'Failed to fetch users data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display a list of load owners.
     */
    public function load_owners(Request $request): View
    {
        try {
            $perPage = 10; // Number of items per page
            $currentPage = $request->query('page', 1);
            $startAt = ($currentPage - 1) * $perPage;

            $usersRef = $this->firestore->collection('users');
            $loadOwnersQuery = $usersRef->where('user_type', '==', 'Customer');
            
            // Get total count for pagination
            $total = $loadOwnersQuery->documents()->size();
            
            // Get paginated results
            $loadOwnersQuery = $loadOwnersQuery->offset($startAt)->limit($perPage);
            $loadOwners = $loadOwnersQuery->documents();

            $loadOwnersData = [];
            foreach ($loadOwners as $doc) {
                $data = $doc->data();
                $data['uid'] = $doc->id(); // Changed from 'id' to 'uid' to match your view
                $loadOwnersData[] = (object) $data;
            }

            // Create a custom paginator
            $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
                $loadOwnersData,
                $total,
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return view('admin.users.load_owners', [
                'loadOwners' => $paginator
            ]);
        } catch (Exception $e) {
            report($e);
            return view('admin.users.load_owners', [
                'loadOwners' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10, 1),
                'error' => 'Failed to fetch load owners data.'
            ]);
        }
    }

    /**
     * Display a list of transporters.
     */
    public function transporters(Request $request): View
    {
        try {
            $perPage = 10; // Number of items per page
            $currentPage = $request->query('page', 1);
            $startAt = ($currentPage - 1) * $perPage;

            $usersRef = $this->firestore->collection('users');
            $transportersQuery = $usersRef->where('user_type', '==', 'Driver');
            
            // Get total count for pagination
            $total = $transportersQuery->documents()->size();
            
            // Get paginated results
            $transportersQuery = $transportersQuery->offset($startAt)->limit($perPage);
            $transporters = $transportersQuery->documents();

            $transportersData = [];
            foreach ($transporters as $doc) {
                $data = $doc->data();
                $data['uid'] = $doc->id(); // Changed from 'id' to 'uid' to match your view
                $transportersData[] = (object) $data;
            }

            // Create a custom paginator
            $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
                $transportersData,
                $total,
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return view('admin.users.transporters', [
                'transporters' => $paginator
            ]);
        } catch (Exception $e) {
            report($e);
            return view('admin.users.transporters', [
                'transporters' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10, 1),
                'error' => 'Failed to fetch transporters data.'
            ]);
        }
    }

    /**
     * Display a list of loads based on selection.
     */
    public function loads(string $selection = 'all'): View
    {
        try {
            $loadsRef = $this->firestore->collection('loads');
            $query = $loadsRef->orderBy('added_date', 'desc');

            // Apply status filter based on selection
            if ($selection !== 'all') {
                $status = match($selection) {
                    'pending' => 'pending',
                    'approved' => 'approved',
                    'available' => 'available',
                    'unavailable' => 'unavailable',
                    'in_transit' => 'in_transit',
                    'completed' => 'completed',
                    'cancelled' => 'cancelled',
                    'rejected' => 'rejected',
                    default => null
                };
                
                if ($status) {
                    $query = $query->where('status', '==', $status);
                }
            }

            // Get the documents
            $documents = $query->documents();
            $loads = [];

            // Debug information
            $debug = [
                'selection' => $selection,
                'status' => $status ?? 'all',
                'document_count' => $documents->size(),
                'collection' => 'loads'
            ];

            foreach ($documents as $doc) {
                $data = $doc->data();
                $data['id'] = $doc->id();
                
                // Get user details if available
                if (isset($data['user_id'])) {
                    try {
                        $userDoc = $this->firestore->collection('users')->document($data['user_id'])->snapshot();
                        if ($userDoc->exists()) {
                            $userData = $userDoc->data();
                            $data['user'] = [
                                'name' => $userData['name'] ?? 'N/A',
                                'email' => $userData['email'] ?? 'N/A',
                                'phone' => $userData['phone'] ?? 'N/A',
                                'first_name' => $userData['first_name'] ?? 'N/A',
                                'last_name' => $userData['last_name'] ?? 'N/A'
                            ];
                        }
                    } catch (Exception $e) {
                        $data['user_error'] = $e->getMessage();
                        \Log::error('Error fetching user data for load: ' . $e->getMessage());
                    }
                }

                // Format dates for display
                if (isset($data['added_date'])) {
                    try {
                        $data['formatted_added_date'] = $data['added_date']->get()->format('M d, Y H:i');
                    } catch (Exception $e) {
                        $data['formatted_added_date'] = 'N/A';
                    }
                }
                
                if (isset($data['pickup_date'])) {
                    try {
                        $data['formatted_pickup_date'] = $data['pickup_date']->get()->format('M d, Y H:i');
                    } catch (Exception $e) {
                        $data['formatted_pickup_date'] = 'N/A';
                    }
                }

                if (isset($data['delivery_date'])) {
                    try {
                        $data['formatted_delivery_date'] = $data['delivery_date']->get()->format('M d, Y H:i');
                    } catch (Exception $e) {
                        $data['formatted_delivery_date'] = 'N/A';
                    }
                }

                $loads[] = (object) $data;
            }

            return view('admin.loads.index', [
                'selection' => $selection,
                'loads' => $loads,
                'debug' => $debug
            ]);
        } catch (Exception $e) {
            report($e);
            return view('admin.loads.index', [
                'selection' => $selection,
                'loads' => [],
                'error' => 'Failed to fetch loads: ' . $e->getMessage(),
                'debug' => [
                    'error' => $e->getMessage(),
                    'selection' => $selection,
                    'trace' => $e->getTraceAsString()
                ]
            ]);
        }
    }

    /**
     * Display load details.
     */
    public function loadDetail(string $selection, string $id): View
    {
        try {
            $loadsRef = $this->firestore->collection('loads');
            $loadDoc = $loadsRef->document($id)->snapshot();
            
            if (!$loadDoc->exists()) {
                return redirect()->route('admin.loadApprovals', ['selection' => $selection])
                    ->with('error', 'Load not found.');
            }

            $load = $loadDoc->data();
            $load['id'] = $loadDoc->id();
            
            // Get user details if available
            if (isset($load['user_id'])) {
                $userDoc = $this->firestore->collection('users')->document($load['user_id'])->snapshot();
                if ($userDoc->exists()) {
                    $userData = $userDoc->data();
                    $load['user'] = [
                        'first_name' => $userData['first_name'] ?? 'N/A',
                        'last_name' => $userData['last_name'] ?? 'N/A',
                        'name' => ($userData['first_name'] ?? '') . ' ' . ($userData['last_name'] ?? ''),
                        'profileImage' => $userData['profileImage'] ?? 'https://firebasestorage.googleapis.com/v0/b/chishimba.appspot.com/o/images%2Fprofile%2Fprofile.jpg?alt=media&token=046cbdb6-c66b-4d29-8940-d261850d03c5',
                        'email' => $userData['email'] ?? 'N/A',
                        'phone' => $userData['phone_number'] ?? 'N/A',
                        'phone_number' => $userData['phone_number'] ?? 'N/A',
                        'gender' => $userData['gender'] ?? 'N/A',
                        'country' => $userData['country'] ?? 'N/A',
                        'city_town' => $userData['city_town'] ?? 'N/A',
                        'province' => $userData['province'] ?? 'N/A',
                        'nrc' => $userData['nrc'] ?? 'N/A',
                        'is_staff' => $userData['is_staff'] ?? 0,
                        'uid' => $userData['uid'] ?? $load['user_id']
                    ];
                }
            }

            // Helper function for status badge styling
            $getStatusBadgeClass = function($status) {
                return match($status) {
                    'pending' => 'bg-yellow-100 text-yellow-800 ring-yellow-600/20',
                    'approved' => 'bg-green-100 text-green-700 ring-green-600/20',
                    'rejected' => 'bg-red-100 text-red-700 ring-red-600/10',
                    default => 'bg-gray-100 text-gray-600 ring-gray-500/10'
                };
            };

            return view('admin.loads.detail', [
                'selection' => $selection,
                'load' => (object) $load,
                'getStatusBadgeClass' => $getStatusBadgeClass
            ]);
        } catch (Exception $e) {
            report($e);
            return redirect()->route('admin.loadApprovals', ['selection' => $selection])
                ->with('error', 'Failed to fetch load details: ' . $e->getMessage());
        }
    }

    /**
     * Display load booking details.
     */
    public function loadBookingDetail(string $selection, string $id): View
    {
        try {
            $loadOrdersRef = $this->firestore->collection('load_orders');
            $loadOrderDoc = $loadOrdersRef->document($id)->snapshot();
            
            if (!$loadOrderDoc->exists()) {
                return redirect()->route('admin.load_bookings', ['selection' => $selection])
                    ->with('error', 'Load booking not found.');
            }

            $orderData = $loadOrderDoc->data();
            $orderData['id'] = $loadOrderDoc->id();
            
            // Get enhanced customer details using load_orders → loads → users chain
            if (isset($orderData['load_id']) && !empty($orderData['load_id'])) {
                try {
                    // First get the load document using load_id
                    $loadDoc = $this->firestore->collection('loads')->document($orderData['load_id'])->snapshot();
                    if ($loadDoc->exists()) {
                        $loadData = $loadDoc->data();
                        
                        // Store load details for later use
                        $orderData['load'] = [
                            'load_name' => $loadData['load_name'] ?? 'N/A',
                            'pickup_location' => $loadData['pickup_location'] ?? 'N/A',
                            'dropoff_location' => $loadData['dropoff_location'] ?? 'N/A',
                            'weight' => $loadData['weight'] ?? 'N/A',
                            'description' => $loadData['description'] ?? 'N/A',
                            'status' => $loadData['status'] ?? 'N/A'
                        ];
                        
                        // Now get customer details using user_id from the load
                        if (isset($loadData['user_id']) && !empty($loadData['user_id'])) {
                            $customerDetails = $this->getCustomerDetails($loadData['user_id']);
                            if ($customerDetails) {
                                $orderData['customer'] = $customerDetails;
                            } else {
                                $orderData['customer'] = [
                                    'name' => 'N/A',
                                    'email' => 'N/A',
                                    'phone' => 'N/A'
                                ];
                            }
                        } else {
                            $orderData['customer'] = [
                                'name' => 'N/A',
                                'email' => 'N/A',
                                'phone' => 'N/A'
                            ];
                        }
                    } else {
                        \Log::warning('Load not found for load_id: ' . $orderData['load_id']);
                        $orderData['load'] = null;
                        $orderData['customer'] = null;
                    }
                } catch (\Exception $e) {
                    \Log::error('Error fetching load and customer details for load booking: ' . $e->getMessage());
                    $orderData['load'] = null;
                    $orderData['customer'] = null;
                }
            } else {
                // Fallback to cus_id if load_id is not available
                \Log::warning('No load_id found in load order, falling back to cus_id');
                if (isset($orderData['cus_id']) && !empty($orderData['cus_id'])) {
                    try {
                        $customerDetails = $this->getCustomerDetails($orderData['cus_id']);
                        if ($customerDetails) {
                            $orderData['customer'] = $customerDetails;
                        } else {
                            $orderData['customer'] = [
                                'name' => 'N/A',
                                'email' => 'N/A',
                                'phone' => 'N/A'
                            ];
                        }
                    } catch (\Exception $e) {
                        \Log::error('Error fetching customer details via cus_id fallback: ' . $e->getMessage());
                        $orderData['customer'] = null;
                    }
                } else {
                    $orderData['customer'] = null;
                }
                $orderData['load'] = null;
            }

            // Get enhanced transporter/driver details using cus_id
            if (isset($orderData['cus_id']) && !empty($orderData['cus_id'])) {
                try {
                    $transporterDoc = $this->firestore->collection('users')->document($orderData['cus_id']);
                    $transporterSnapshot = $transporterDoc->snapshot();
                    
                    if ($transporterSnapshot->exists()) {
                        $transporterData = $transporterSnapshot->data();
                        $orderData['transporter'] = [
                            'name' => ($transporterData['first_name'] ?? '') . ' ' . ($transporterData['last_name'] ?? ''),
                            'first_name' => $transporterData['first_name'] ?? 'N/A',
                            'last_name' => $transporterData['last_name'] ?? 'N/A',
                            'email' => $transporterData['email'] ?? 'N/A',
                            'phone' => $transporterData['phone_number'] ?? 'N/A'
                        ];
                    }
                } catch (\Exception $e) {
                    report($e);
                    $orderData['driver'] = null;
                }
            }
            
            // Get truck details if truck_id exists
            if (isset($orderData['truck_id']) && !empty($orderData['truck_id'])) {
                try {
                    $truckRef = $this->firestore->collection('trucks')->document($orderData['truck_id']);
                    $truckSnapshot = $truckRef->snapshot();
                    
                    if ($truckSnapshot->exists()) {
                        $truckData = $truckSnapshot->data();
                        \Log::info('Truck data retrieved for order:', $truckData);
                        
                        // Map truck data according to the Firebase structure provided
                        $orderData['truck'] = [
                            'id' => $orderData['truck_id'],
                            'user_id' => $truckData['userId'] ?? 'N/A',
                            'is_online' => $truckData['isOnline'] ?? false,
                            'id_number' => $truckData['idNumber'] ?? 'N/A',
                            'address' => $truckData['address'] ?? 'N/A',
                            'city' => $truckData['city'] ?? 'N/A',
                            'province' => $truckData['province'] ?? 'N/A',
                            'license_number' => $truckData['licenseNumber'] ?? 'N/A',
                            'tonnage' => $truckData['tonnage'] ?? 'N/A',
                            'trailer_type' => $truckData['trailerType'] ?? 'N/A',
                            'trailer_type2' => $truckData['trailerType2'] ?? 'N/A',
                            'transporter_name' => $truckData['transporterName'] ?? 'N/A',
                            'transporter_phone' => $truckData['transporterPhone'] ?? 'N/A',
                            'model' => $truckData['model'] ?? 'N/A',
                            'trailer_number' => $truckData['trailerNumber'] ?? 'N/A',
                            'selfie_image_url' => $truckData['selfieImageUrl'] ?? null,
                            'id_front_url' => $truckData['idFrontUrl'] ?? null,
                            'id_back_url' => $truckData['idBackUrl'] ?? null,
                            'driving_license_front' => $truckData['drivingLicenseFront'] ?? null,
                            'driving_license_back' => $truckData['drivingLicenseBack'] ?? null,
                            'license_url' => $truckData['licenseUrl'] ?? null,
                            'side_view_url' => $truckData['sideViewUrl'] ?? null,
                            'trailer_url' => $truckData['trailerUrl'] ?? null,
                            'status' => $truckData['status'] ?? 'N/A',
                            'added_date' => isset($truckData['added_date']) ? $truckData['added_date']->get()->format('M d, Y H:i') : 'N/A',
                            'approved_date' => isset($truckData['approved_date']) ? $truckData['approved_date']->get()->format('M d, Y H:i') : 'N/A'
                        ];
                        
                        \Log::info('Processed truck data for view:', $orderData['truck']);
                    } else {
                        \Log::warning('Truck not found with ID: ' . $orderData['truck_id']);
                        $orderData['truck'] = null;
                    }
                } catch (\Exception $e) {
                    \Log::error('Error fetching truck details: ' . $e->getMessage());
                    report($e);
                    $orderData['truck'] = null;
                }
            }
            
            // Format dates for display
            if (isset($orderData['added_date'])) {
                try {
                    $orderData['formatted_added_date'] = $orderData['added_date']->get()->format('M d, Y H:i');
                } catch (\Exception $e) {
                    report($e);
                    $orderData['formatted_added_date'] = 'N/A';
                }
            }
            
            if (isset($orderData['confirmed_date'])) {
                try {
                    $orderData['formatted_confirmed_date'] = $orderData['confirmed_date']->get()->format('M d, Y H:i');
                } catch (\Exception $e) {
                    $orderData['formatted_confirmed_date'] = 'N/A';
                }
            }
            
            if (isset($orderData['completed_date'])) {
                try {
                    $orderData['formatted_completed_date'] = $orderData['completed_date']->get()->format('M d, Y H:i');
                } catch (\Exception $e) {
                    $orderData['formatted_completed_date'] = 'N/A';
                }
            }

            // Helper function for status badge styling
            $getStatusBadgeClass = function($status) {
                return match($status) {
                    'pending' => 'bg-yellow-100 text-yellow-800 ring-yellow-600/20',
                    'confirmed' => 'bg-blue-100 text-blue-700 ring-blue-700/10',
                    'approved' => 'bg-green-100 text-green-700 ring-green-600/20',
                    'in_transit' => 'bg-cyan-100 text-cyan-700 ring-cyan-700/10',
                    'completed' => 'bg-green-100 text-green-700 ring-green-600/20',
                    'cancelled' => 'bg-red-100 text-red-700 ring-red-600/10',
                    default => 'bg-gray-100 text-gray-600 ring-gray-500/10'
                };
            };

            return view('admin.load_bookings.detail', [
                'selection' => $selection,
                'order' => (object) $orderData,
                'getStatusBadgeClass' => $getStatusBadgeClass
            ]);
        } catch (Exception $e) {
            report($e);
            return redirect()->route('admin.load_bookings', ['selection' => $selection])
                ->with('error', 'Failed to fetch load booking details: ' . $e->getMessage());
        }
    }

    /**
     * Display booking details.
     */
    public function bookingDetail(string $selection, string $booking_id)
    {
        try {
            if (!$this->firestore) {
                throw new \Exception('Firestore client is not initialized');
            }

            // Use the Firestore client from the controller property
            $ordersRef = $this->firestore->collection('orders');
            
            // Get the order details
            $order = $ordersRef->document($booking_id)->snapshot();
            
            if (!$order->exists()) {
                return redirect()->route('admin.bookings', ['selection' => $selection])
                    ->with('error', 'Order not found.');
            }
            
            $orderData = $order->data();
            $orderData['id'] = $order->id();
            
            // Get customer details using cus_id from users collection
            if (isset($orderData['cus_id']) && !empty($orderData['cus_id'])) {
                try {
                    $customerRef = $this->firestore->collection('users')->document($orderData['cus_id']);
                    $customer = $customerRef->snapshot();
                    if ($customer->exists()) {
                        $customerData = $customer->data();
                        \Log::info('Raw customer data:', $customerData);
                        
                        $orderData['customer'] = [
                            'first_name' => $customerData['first_name'] ?? 'N/A',
                            'last_name' => $customerData['last_name'] ?? 'N/A',
                            'name' => ($customerData['first_name'] ?? '') . ' ' . ($customerData['last_name'] ?? ''),
                            'profileImage' => $customerData['profileImage'] ?? 'https://firebasestorage.googleapis.com/v0/b/chishimba.appspot.com/o/images%2Fprofile%2Fprofile.jpg?alt=media&token=046cbdb6-c66b-4d29-8940-d261850d03c5',
                            'profile_image' => $customerData['profileImage'] ?? 'https://firebasestorage.googleapis.com/v0/b/chishimba.appspot.com/o/images%2Fprofile%2Fprofile.jpg?alt=media&token=046cbdb6-c66b-4d29-8940-d261850d03c5',
                            'gender' => $customerData['gender'] ?? 'N/A',
                            'country' => $customerData['country'] ?? 'N/A',
                            'city_town' => $customerData['city_town'] ?? 'N/A',
                            'province' => $customerData['province'] ?? 'N/A',
                            'nrc' => $customerData['nrc'] ?? 'N/A',
                            'is_staff' => $customerData['is_staff'] ?? 0,
                            'user_type' => $customerData['user_type'] ?? 'Customer',
                            'driver_verified' => $customerData['driver_verified'] ?? false,
                            'isOnline' => $customerData['isOnline'] ?? false,
                            'isBanned' => $customerData['isBanned'] ?? false,
                            'banReason' => $customerData['banReason'] ?? '',
                            'added_date' => isset($customerData['added_date']) ? $customerData['added_date']->get()->format('M d, Y H:i') : 'N/A'
                        ];
                        
                        \Log::info('Processed customer data:', $orderData['customer']);
                    } else {
                        $orderData['customer'] = null;
                    }
                } catch (\Exception $e) {
                    report($e);
                    $orderData['customer'] = null;
                }
            }
            
            // Get driver details if driver_id exists
            if (isset($orderData['driver_id']) && !empty($orderData['driver_id'])) {
                try {
                    $driverDoc = $this->firestore->collection('users')->document($orderData['driver_id']);
                    $driverSnapshot = $driverDoc->snapshot();
                    
                    if ($driverSnapshot->exists()) {
                        $driverData = $driverSnapshot->data();
                        $orderData['driver'] = [
                            'name' => ($driverData['first_name'] ?? '') . ' ' . ($driverData['last_name'] ?? ''),
                            'first_name' => $driverData['first_name'] ?? 'N/A',
                            'last_name' => $driverData['last_name'] ?? 'N/A',
                            'email' => $driverData['email'] ?? 'N/A',
                            'phone' => $driverData['phone_number'] ?? 'N/A'
                        ];
                    }
                } catch (\Exception $e) {
                    report($e);
                    $orderData['driver'] = null;
                }
            }
            
            // Get truck details if truck_id exists
            if (isset($orderData['truck_id']) && !empty($orderData['truck_id'])) {
                try {
                    $truckRef = $this->firestore->collection('trucks')->document($orderData['truck_id']);
                    $truckSnapshot = $truckRef->snapshot();
                    
                    if ($truckSnapshot->exists()) {
                        $truckData = $truckSnapshot->data();
                        \Log::info('Truck data retrieved for order:', $truckData);
                        
                        // Map truck data according to the Firebase structure provided
                        $orderData['truck'] = [
                            'id' => $orderData['truck_id'],
                            'user_id' => $truckData['userId'] ?? 'N/A',
                            'is_online' => $truckData['isOnline'] ?? false,
                            'id_number' => $truckData['idNumber'] ?? 'N/A',
                            'address' => $truckData['address'] ?? 'N/A',
                            'city' => $truckData['city'] ?? 'N/A',
                            'province' => $truckData['province'] ?? 'N/A',
                            'license_number' => $truckData['licenseNumber'] ?? 'N/A',
                            'tonnage' => $truckData['tonnage'] ?? 'N/A',
                            'trailer_type' => $truckData['trailerType'] ?? 'N/A',
                            'trailer_type2' => $truckData['trailerType2'] ?? 'N/A',
                            'transporter_name' => $truckData['transporterName'] ?? 'N/A',
                            'transporter_phone' => $truckData['transporterPhone'] ?? 'N/A',
                            'model' => $truckData['model'] ?? 'N/A',
                            'trailer_number' => $truckData['trailerNumber'] ?? 'N/A',
                            'selfie_image_url' => $truckData['selfieImageUrl'] ?? null,
                            'id_front_url' => $truckData['idFrontUrl'] ?? null,
                            'id_back_url' => $truckData['idBackUrl'] ?? null,
                            'driving_license_front' => $truckData['drivingLicenseFront'] ?? null,
                            'driving_license_back' => $truckData['drivingLicenseBack'] ?? null,
                            'license_url' => $truckData['licenseUrl'] ?? null,
                            'side_view_url' => $truckData['sideViewUrl'] ?? null,
                            'trailer_url' => $truckData['trailerUrl'] ?? null,
                            'status' => $truckData['status'] ?? 'N/A',
                            'added_date' => isset($truckData['added_date']) ? $truckData['added_date']->get()->format('M d, Y H:i') : 'N/A',
                            'approved_date' => isset($truckData['approved_date']) ? $truckData['approved_date']->get()->format('M d, Y H:i') : 'N/A'
                        ];
                        
                        \Log::info('Processed truck data for view:', $orderData['truck']);
                    } else {
                        \Log::warning('Truck not found with ID: ' . $orderData['truck_id']);
                        $orderData['truck'] = null;
                    }
                } catch (\Exception $e) {
                    \Log::error('Error fetching truck details: ' . $e->getMessage());
                    report($e);
                    $orderData['truck'] = null;
                }
            }
            
            // Format dates for display
            if (isset($orderData['added_date'])) {
                try {
                    $orderData['formatted_added_date'] = $orderData['added_date']->get()->format('M d, Y H:i');
                } catch (\Exception $e) {
                    report($e);
                    $orderData['formatted_added_date'] = 'N/A';
                }
            }
            
            if (isset($orderData['confirmed_date'])) {
                try {
                    $orderData['formatted_confirmed_date'] = $orderData['confirmed_date']->get()->format('M d, Y H:i');
                } catch (\Exception $e) {
                    $orderData['formatted_confirmed_date'] = 'N/A';
                }
            }
            
            if (isset($orderData['completed_date'])) {
                try {
                    $orderData['formatted_completed_date'] = $orderData['completed_date']->get()->format('M d, Y H:i');
                } catch (\Exception $e) {
                    $orderData['formatted_completed_date'] = 'N/A';
                }
            }

            // Helper function for status badge styling
            $getStatusBadgeClass = function($status) {
                return match($status) {
                    'pending' => 'bg-yellow-100 text-yellow-800 ring-yellow-600/20',
                    'confirmed' => 'bg-blue-100 text-blue-700 ring-blue-700/10',
                    'approved' => 'bg-green-100 text-green-700 ring-green-600/20',
                    'in_transit' => 'bg-cyan-100 text-cyan-700 ring-cyan-700/10',
                    'completed' => 'bg-green-100 text-green-700 ring-green-600/20',
                    'cancelled' => 'bg-red-100 text-red-700 ring-red-600/10',
                    default => 'bg-gray-100 text-gray-600 ring-gray-500/10'
                };
            };

            return view('admin.bookings.detail', [
                'order' => (object)$orderData,
                'selection' => $selection,
                'getStatusBadgeClass' => $getStatusBadgeClass
            ]);
            
        } catch (\Exception $e) {
            report($e);
            return redirect()->route('admin.bookings', ['selection' => $selection])
                ->with('error', 'Failed to fetch order details: ' . $e->getMessage());
        }
    }

    /**
     * Display truck details.
     */
    public function truckDetail(string $id): View
    {
        try {
            // Get truck data
            $truckRef = $this->firestore->collection('trucks')->document($id);
            $truck = $truckRef->snapshot();
            
            if (!$truck->exists()) {
                \Log::error('Truck not found', ['truck_id' => $id]);
                return redirect()->route('admin.trucks', ['selection' => 'all'])
                    ->with('error', 'Truck not found.');
            }

            $truckData = $truck->data();
                       $truckData['id'] = $truck->id();
            \Log::info('Truck data retrieved', ['truck_id' => $id, 'data' => $truckData]);

            // Get user data if userId exists
            if (isset($truckData['userId'])) {
                try {
                    $userRef = $this->firestore->collection('users')->document($truckData['userId']);
                    $user = $userRef->snapshot();
                    
                    if ($user->exists()) {
                        $userData = $user->data();
                        $truckData['user'] = [
                            'name' => $userData['name'] ?? 'N/A',
                            'email' => $userData['email'] ?? 'N/A',
                            'phone' => $userData['phone'] ?? $userData['phone_number'] ?? 'N/A'
                        ];
                    } else {
                        $truckData['user'] = null;
                    }
                } catch (\Exception $e) {
                    \Log::error('Error fetching user data for truck', [
                        'user_id' => $truckData['userId'],
                        'error' => $e->getMessage()
                    ]);
                    $truckData['user'] = null;
                }
            } else {
                $truckData['user'] = null;
            }

            // Helper function for status badge styling
            $getStatusBadgeClass = function($status) {
                return match($status) {
                    'pending' => 'bg-yellow-100 text-yellow-800 ring-yellow-600/20',
                    'approved' => 'bg-green-100 text-green-700 ring-green-600/20',
                    'rejected' => 'bg-red-100 text-red-700 ring-red-600/10',
                    default => 'bg-gray-100 text-gray-600 ring-gray-500/10'
                };
            };

            return view('admin.trucks.detail', [
                'truck' => (object) $truckData,
                'getStatusBadgeClass' => $getStatusBadgeClass
            ]);
        } catch (\Exception $e) {
            report($e);
            return redirect()->route('admin.trucks', ['selection' => 'all'])
                ->with('error', 'Failed to fetch truck details: ' . $e->getMessage());
        }
    }

    /**
     * Display a list of trucks based on selection.
     */
    public function trucks(string $selection = 'all'): View

    {
        try {
            $trucksRef = $this->firestore->collection('trucks');
            $query = $trucksRef->orderBy('added_date', 'desc');
            $trucks = [];

            if ($selection === 'active') {
                // For active trucks, we need to:
                // 1. Get online users
                // 2. Get their approved trucks
                $onlineUsers = $this->firestore->collection('users')
                    ->where('isOnline', '==', true)
                    ->documents()->rows();

                foreach ($onlineUsers as $user) {
                    $userTrucks = $trucksRef
                        ->where('userId', '==', $user->id())
                        ->where('status', '==', 'approved')
                        ->documents()->rows();
                    
                    foreach ($userTrucks as $truck) {
                        $truckData = $truck->data();
                        $truckData['id'] = $truck->id();
                        
                        // Add user details to truck data
                        $userData = $user->data();
                        $truckData['user'] = [
                            'name' => $userData['name'] ?? 'N/A',
                            'email' => $userData['email'] ?? 'N/A',
                            'phone' => $userData['phone'] ?? 'N/A'
                        ];
                        
                        $trucks[] = (object) $truckData;
                    }
                }
            } else {
                // For other statuses (all, inactive, pending_approval, rejected)
                if ($selection !== 'all') {
                    $query = $query->where('status', '==', $selection);
                }

                $documents = $query->documents();
                
                foreach ($documents as $doc) {
                    $truckData = $doc->data();
                    $truckData['id'] = $doc->id();
                    
                    // Get user details if userId exists
                    if (isset($truckData['userId'])) {
                        try {
                            $userDoc = $this->firestore->collection('users')
                                ->document($truckData['userId'])
                                ->snapshot();
                            
                            if ($userDoc->exists()) {
                                $userData = $userDoc->data();
                                $truckData['user'] = [
                                    'name' => $userData['name'] ?? 'N/A',
                                    'email' => $userData['email'] ?? 'N/A',
                                    'phone' => $userData['phone'] ?? 'N/A'
                                ];
                            }
                        } catch (Exception $e) {
                            \Log::error('Error fetching user data for truck: ' . $e->getMessage());
                        }
                    }
                    
                    $trucks[] = (object) $truckData;
                }
            }

            return view('admin.trucks.index', [
                'selection' => $selection,
                'items' => $trucks
            ]);
            
        } catch (Exception $e) {
            report($e);
            return view('admin.trucks.index', [
                'selection' => $selection,
                'items' => [],
                'error' => 'Failed to fetch trucks: ' . $e->getMessage()
            ]);
        }
    }


    /**
     * Send notifications via FCM and SMS.
     */
    private function sendNotifications($fcmToken, $phoneNumber, $title, $shortMessage, $longMessage, $userId = null, $notificationType = 'general', $data = [])
    {
        try {
            // Send FCM notification if token is available
            if (!empty($fcmToken)) {
                $firebaseService = app(FirebaseService::class);
                $firebaseService->sendNotification($fcmToken, $title, $shortMessage, $data);
                \Log::info('FCM notification sent successfully');
            }

            // Send SMS if phone number is available
            if (!empty($phoneNumber)) {
                $this->smsService->sendSms($phoneNumber, $longMessage);
                \Log::info('SMS sent successfully');
            }

            // Save notification to Firestore if userId is provided
            if ($userId) {
                try {
                    $notificationData = [
                        'user_id' => $userId,
                        'title' => $title,
                        'message' => $longMessage,
                        'short_message' => $shortMessage,
                        'type' => $notificationType,
                        'data' => $data,
                        'read' => false,
                        'created_at' => new \Google\Cloud\Core\Timestamp(new \DateTime()),
                        'updated_at' => new \Google\Cloud\Core\Timestamp(new \DateTime())
                    ];

                    $notificationsRef = $this->firestore->collection('notifications');
                    $docRef = $notificationsRef->add($notificationData);
                    
                    \Log::info('Notification saved to Firestore', [
                        'notification_id' => $docRef->id(),
                        'user_id' => $userId,
                        'type' => $notificationType
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Failed to save notification to Firestore', [
                        'user_id' => $userId,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Log the notification details
            \Log::info('Notification sent', [
                'fcm_token' => $fcmToken ? substr($fcmToken, 0, 20) . '...' : null,
                'phone_number' => $phoneNumber,
                'title' => $title,
                'short_message' => $shortMessage,
                'long_message' => $longMessage,
                'user_id' => $userId,
                'notification_type' => $notificationType,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('Error sending notifications', [
                'error' => $e->getMessage(),
                'fcm_token' => $fcmToken ? substr($fcmToken, 0, 20) . '...' : null,
                'phone_number' => $phoneNumber
            ]);
        }
    }

    /**
     * Enhanced method to fetch complete customer information from users collection
     */
    private function getCustomerDetails($customerId)
    {
        try {
            $customerDoc = $this->firestore->collection('users')->document($customerId)->snapshot();
            if ($customerDoc->exists()) {
                $customerData = $customerDoc->data();
                return [
                    'id' => $customerId,
                    'uid' => $customerData['uid'] ?? $customerId,
                    'name' => ($customerData['first_name'] ?? '') . ' ' . ($customerData['last_name'] ?? ''),
                    'first_name' => $customerData['first_name'] ?? 'N/A',
                    'last_name' => $customerData['last_name'] ?? 'N/A',
                    'email' => $customerData['email'] ?? 'N/A',
                    'phone' => $customerData['phone_number'] ?? 'N/A',
                    'phone_number' => $customerData['phone_number'] ?? 'N/A',
                    'profileImage' => $customerData['profileImage'] ?? 'https://firebasestorage.googleapis.com/v0/b/chishimba.appspot.com/o/images%2Fprofile%2Fprofile.jpg?alt=media&token=046cbdb6-c66b-4d29-8940-d261850d03c5',
                    'gender' => $customerData['gender'] ?? 'N/A',
                    'country' => $customerData['country'] ?? 'N/A',
                    'city_town' => $customerData['city_town'] ?? 'N/A',
                    'province' => $customerData['province'] ?? 'N/A',
                    'nrc' => $customerData['nrc'] ?? 'N/A',
                    'is_staff' => $customerData['is_staff'] ?? 0,
                    'user_type' => $customerData['user_type'] ?? 'Customer',
                    'driver_verified' => $customerData['driver_verified'] ?? false,
                    'isOnline' => $customerData['isOnline'] ?? false,
                    'isBanned' => $customerData['isBanned'] ?? false,
                    'banReason' => $customerData['banReason'] ?? '',
                    'added_date' => isset($customerData['added_date']) ? $customerData['added_date']->get()->format('M d, Y H:i') : 'N/A'
                ];
            }
        } catch (Exception $e) {
            \Log::error('Error fetching customer details', [
                'customer_id' => $customerId,
                'error' => $e->getMessage()
            ]);
        }
        return null;
    }

    /**
     * Enhanced method to fetch complete transporter information from users collection via truck data
     */
    private function getTransporterDetails($truckId)
    {
        try {
            // First get truck data to find the userId
            $truckDoc = $this->firestore->collection('trucks')->document($truckId)->snapshot();
            if ($truckDoc->exists()) {
                $truckData = $truckDoc->data();
                
                // Get transporter details from users collection using userId
                if (isset($truckData['userId'])) {
                    $transporterDoc = $this->firestore->collection('users')->document($truckData['userId'])->snapshot();
                    if ($transporterDoc->exists()) {
                        $transporterData = $transporterDoc->data();
                        
                        return [
                            'id' => $truckData['userId'],
                            'uid' => $transporterData['uid'] ?? $truckData['userId'],
                            'name' => ($transporterData['first_name'] ?? '') . ' ' . ($transporterData['last_name'] ?? ''),
                            'first_name' => $transporterData['first_name'] ?? 'N/A',
                            'last_name' => $transporterData['last_name'] ?? 'N/A',
                            'email' => $transporterData['email'] ?? 'N/A',
                            'phone' => $transporterData['phone_number'] ?? 'N/A',
                            'phone_number' => $transporterData['phone_number'] ?? 'N/A',
                            'profileImage' => $transporterData['profileImage'] ?? 'https://firebasestorage.googleapis.com/v0/b/chishimba.appspot.com/o/images%2Fprofile%2Fprofile.jpg?alt=media&token=046cbdb6-c66b-4d29-8940-d261850d03c5',
                            'gender' => $transporterData['gender'] ?? 'N/A',
                            'country' => $transporterData['country'] ?? 'N/A',
                            'city_town' => $transporterData['city_town'] ?? 'N/A',
                            'province' => $transporterData['province'] ?? 'N/A',
                            'nrc' => $transporterData['nrc'] ?? 'N/A',
                            'is_staff' => $transporterData['is_staff'] ?? 0,
                            'user_type' => $transporterData['user_type'] ?? 'Driver',
                            'driver_verified' => $transporterData['driver_verified'] ?? false,
                            'isOnline' => $transporterData['isOnline'] ?? false,
                            'isBanned' => $transporterData['isBanned'] ?? false,
                            'banReason' => $transporterData['banReason'] ?? '',
                            'added_date' => isset($transporterData['added_date']) ? $transporterData['added_date']->get()->format('M d, Y H:i') : 'N/A',
                            
                            // Truck-specific information
                            'truck' => [
                                'id' => $truckId,
                                'plate_number' => $truckData['truck_plate_number'] ?? $truckData['licenseNumber'] ?? 'N/A',
                                'transporter_name' => $truckData['transporterName'] ?? 'N/A',
                                'truck_type' => $truckData['truck_type'] ?? 'N/A',
                                'model' => $truckData['model'] ?? 'N/A',
                                'tonnage' => $truckData['tonnage'] ?? 'N/A',
                                'trailer_type' => $truckData['trailerType'] ?? 'N/A',
                                'trailer_number' => $truckData['trailerNumber'] ?? 'N/A',
                                'capacity' => $truckData['capacity'] ?? $truckData['tonnage'] ?? 'N/A',
                                'status' => $truckData['status'] ?? 'N/A',
                                'isOnline' => $truckData['isOnline'] ?? false
                            ]
                        ];
                    }
                }
            }
        } catch (Exception $e) {
            \Log::error('Error fetching transporter details', [
                'truck_id' => $truckId,
                'error' => $e->getMessage()
            ]);
        }
        return null;
    }

    /**
     * Enhanced method to fetch transporter details directly by driver/user ID
     */
    private function getTransporterDetailsByUserId($userId)
    {
        try {
            $transporterDoc = $this->firestore->collection('users')->document($userId)->snapshot();
            if ($transporterDoc->exists()) {
                $transporterData = $transporterDoc->data();
                
                return [
                    'id' => $userId,
                    'uid' => $transporterData['uid'] ?? $userId,
                    'name' => ($transporterData['first_name'] ?? '') . ' ' . ($transporterData['last_name'] ?? ''),
                    'first_name' => $transporterData['first_name'] ?? 'N/A',
                    'last_name' => $transporterData['last_name'] ?? 'N/A',
                    'email' => $transporterData['email'] ?? 'N/A',
                    'phone' => $transporterData['phone_number'] ?? 'N/A',
                    'phone_number' => $transporterData['phone_number'] ?? 'N/A',
                    'profileImage' => $transporterData['profileImage'] ?? 'https://firebasestorage.googleapis.com/v0/b/chishimba.appspot.com/o/images%2Fprofile%2Fprofile.jpg?alt=media&token=046cbdb6-c66b-4d29-8940-d261850d03c5',
                    'gender' => $transporterData['gender'] ?? 'N/A',
                    'country' => $transporterData['country'] ?? 'N/A',
                    'city_town' => $transporterData['city_town'] ?? 'N/A',
                    'province' => $transporterData['province'] ?? 'N/A',
                    'nrc' => $transporterData['nrc'] ?? 'N/A',
                    'is_staff' => $transporterData['is_staff'] ?? 0,
                    'user_type' => $transporterData['user_type'] ?? 'Driver',
                    'driver_verified' => $transporterData['driver_verified'] ?? false,
                    'isOnline' => $transporterData['isOnline'] ?? false,
                    'isBanned' => $transporterData['isBanned'] ?? false,
                    'banReason' => $transporterData['banReason'] ?? '',
                    'added_date' => isset($transporterData['added_date']) ? $transporterData['added_date']->get()->format('M d, Y H:i') : 'N/A'
                ];
            }
        } catch (Exception $e) {
            \Log::error('Error fetching transporter details by user ID', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
        }
        return null;
    }
}