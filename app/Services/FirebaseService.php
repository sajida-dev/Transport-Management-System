<?php 

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseService
{
    protected $messaging;
    protected $firestore;

    public function __construct()
    {
        $firebase = (new Factory)
            ->withServiceAccount(base_path('google-services.json')); // Path to your JSON file
        $this->messaging = $firebase->createMessaging();
        $this->firestore = $firebase->createFirestore();
    }

    public function sendNotification(string $registrationToken, string $title, string $body, array $data = [])
    {
        try {
            $notification = Notification::create($title, $body);
            $messageBuilder = CloudMessage::withTarget('token', $registrationToken)
                ->withNotification($notification);

            // Add custom data if provided
            if (!empty($data)) {
                $messageBuilder = $messageBuilder->withData($data);
            }

            $message = $messageBuilder;
            $this->messaging->send($message);
            
            \Log::info('FCM notification sent successfully', [
                'token' => substr($registrationToken, 0, 20) . '...',
                'title' => $title,
                'body' => $body,
                'data' => $data
            ]);
            
            return ['success' => true, 'message' => 'Notification sent successfully'];
        } catch (\Exception $e) {
            \Log::error('FCM notification failed', [
                'token' => substr($registrationToken, 0, 20) . '...',
                'title' => $title,
                'error' => $e->getMessage()
            ]);
            
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Get truck data by truck_id from Firestore trucks collection
     */
    public function getTruckData(string $truckId)
    {
        try {
            $truckRef = $this->firestore->collection('trucks')->document($truckId);
            $truckSnapshot = $truckRef->snapshot();
            
            if (!$truckSnapshot->exists()) {
                \Log::warning('Truck not found', ['truck_id' => $truckId]);
                return null;
            }
            
            $truckData = $truckSnapshot->data();
            $truckData['id'] = $truckId;
            
            \Log::info('Truck data retrieved successfully', [
                'truck_id' => $truckId,
                'user_id' => $truckData['user_id'] ?? 'N/A',
                'plate_number' => $truckData['truck_plate_number'] ?? 'N/A'
            ]);
            
            return $truckData;
        } catch (\Exception $e) {
            \Log::error('Failed to retrieve truck data', [
                'truck_id' => $truckId,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    /**
     * Get user data by user_id from Firestore users collection
     */
    public function getUserData(string $userId)
    {
        try {
            $userRef = $this->firestore->collection('users')->document($userId);
            $userSnapshot = $userRef->snapshot();
            
            if (!$userSnapshot->exists()) {
                \Log::warning('User not found', ['user_id' => $userId]);
                return null;
            }
            
            $userData = $userSnapshot->data();
            $userData['uid'] = $userId;
            
            \Log::info('User data retrieved successfully', [
                'user_id' => $userId,
                'user_type' => $userData['user_type'] ?? 'N/A',
                'first_name' => $userData['first_name'] ?? 'N/A'
            ]);
            
            return $userData;
        } catch (\Exception $e) {
            \Log::error('Failed to retrieve user data', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    /**
     * Get enhanced truck data with user information
     */
    public function getEnhancedTruckData(string $truckId)
    {
        try {
            $truckData = $this->getTruckData($truckId);
            
            if (!$truckData) {
                return null;
            }
            
            // Get user data if user_id is available
            if (isset($truckData['user_id'])) {
                $userData = $this->getUserData($truckData['user_id']);
                if ($userData) {
                    $truckData['user'] = $userData;
                }
            }
            
            return $truckData;
        } catch (\Exception $e) {
            \Log::error('Failed to retrieve enhanced truck data', [
                'truck_id' => $truckId,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    /**
     * Get Firestore instance for direct access
     */
    public function getFirestore()
    {
        return $this->firestore;
    }

    /**
     * Get transporter details by cus_id from users collection
     * Based on the users structure provided
     */
    public function getTransporterByCusId(string $cusId)
    {
        try {
            $userRef = $this->firestore->collection('users')->document($cusId);
            $userSnapshot = $userRef->snapshot();
            
            if (!$userSnapshot->exists()) {
                \Log::warning('Transporter not found in users collection', ['cus_id' => $cusId]);
                return null;
            }
            
            $userData = $userSnapshot->data();
            
            // Structure the transporter data according to the users collection structure
            return [
                'uid' => $userData['uid'] ?? $cusId,
                'first_name' => $userData['first_name'] ?? 'N/A',
                'last_name' => $userData['last_name'] ?? 'N/A',
                'name' => ($userData['first_name'] ?? '') . ' ' . ($userData['last_name'] ?? ''),
                'profileImage' => $userData['profileImage'] ?? null,
                'gender' => $userData['gender'] ?? 'N/A',
                'country' => $userData['country'] ?? 'N/A',
                'city_town' => $userData['city_town'] ?? 'N/A',
                'phone_number' => $userData['phone_number'] ?? 'N/A',
                'is_staff' => $userData['is_staff'] ?? 0,
                'nrc' => $userData['nrc'] ?? 'N/A',
                'province' => $userData['province'] ?? 'N/A',
                'fcm_token' => $userData['fcm_token'] ?? null,
                'user_type' => $userData['user_type'] ?? 'N/A',
                'driver_verified' => $userData['driver_verified'] ?? false,
                'isOnline' => $userData['isOnline'] ?? false,
                'isBanned' => $userData['isBanned'] ?? false,
                'banReason' => $userData['banReason'] ?? '',
                'added_date' => isset($userData['added_date']) ? $userData['added_date']->get()->format('M d, Y H:i') : 'N/A'
            ];
        } catch (\Exception $e) {
            \Log::error('Failed to retrieve transporter by cus_id', [
                'cus_id' => $cusId,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    /**
     * Get truck information by truck_id from trucks collection
     * Based on the trucks structure provided
     */
    public function getTruckByTruckId(string $truckId)
    {
        try {
            $truckRef = $this->firestore->collection('trucks')->document($truckId);
            $truckSnapshot = $truckRef->snapshot();
            
            if (!$truckSnapshot->exists()) {
                \Log::warning('Truck not found in trucks collection', ['truck_id' => $truckId]);
                return null;
            }
            
            $truckData = $truckSnapshot->data();
            
            // Structure the truck data according to the trucks collection structure
            return [
                'id' => $truckId,
                'user_id' => $truckData['user_id'] ?? 'N/A',
                'status' => $truckData['status'] ?? 'N/A',
                'license_number' => $truckData['license_number'] ?? 'N/A',
                'trailer_number' => $truckData['trailer_number'] ?? 'N/A',
                'tonage' => $truckData['tonage'] ?? 'N/A',
                'tonnage' => $truckData['tonage'] ?? 'N/A', // Alternative field name
                'driver_first_name' => $truckData['driver_first_name'] ?? 'N/A',
                'driver_last_name' => $truckData['driver_last_name'] ?? 'N/A',
                'driver_name' => ($truckData['driver_first_name'] ?? '') . ' ' . ($truckData['driver_last_name'] ?? ''),
                'driver_country' => $truckData['driver_country'] ?? 'N/A',
                'nrc_document' => $truckData['nrc_document'] ?? null,
                'driver_nrc_number' => $truckData['driver_nrc_number'] ?? 'N/A',
                'truck_plate_number' => $truckData['truck_plate_number'] ?? 'N/A',
                'truck_model' => $truckData['truck_model'] ?? 'N/A',
                'added_date' => isset($truckData['added_date']) ? $truckData['added_date']->get()->format('M d, Y H:i') : 'N/A'
            ];
        } catch (\Exception $e) {
            \Log::error('Failed to retrieve truck by truck_id', [
                'truck_id' => $truckId,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    /**
     * Get complete transporter and truck information for load orders
     */
    public function getTransporterAndTruckDetails(string $cusId = null, string $truckId = null)
    {
        $result = [
            'transporter' => null,
            'truck' => null
        ];

        try {
            // Get transporter details using cus_id
            if ($cusId) {
                $result['transporter'] = $this->getTransporterByCusId($cusId);
                \Log::info('Transporter details fetched', [
                    'cus_id' => $cusId,
                    'found' => $result['transporter'] !== null
                ]);
            }

            // Get truck details using truck_id
            if ($truckId) {
                $result['truck'] = $this->getTruckByTruckId($truckId);
                \Log::info('Truck details fetched', [
                    'truck_id' => $truckId,
                    'found' => $result['truck'] !== null
                ]);
            }

            return $result;
        } catch (\Exception $e) {
            \Log::error('Failed to retrieve transporter and truck details', [
                'cus_id' => $cusId,
                'truck_id' => $truckId,
                'error' => $e->getMessage()
            ]);
            
            return $result;
        }
    }
}
