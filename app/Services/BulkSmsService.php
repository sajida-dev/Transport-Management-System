<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class BulkSmsService
{
    private string $baseUrl = 'https://bulksms.zamtel.co.zm';
    private string $apiKey = 'kqBehjqztUmYYC6snxFdYeCMESmvIMOg';
    private string $senderId = 'LoadMasta';

    public function sendSms(string $phoneNumber, string $message): array
    {
        // Clean and format phone number (remove spaces, dashes, etc.)
        $phoneNumber = preg_replace('/[^0-9+]/', '', $phoneNumber);
        
        // Ensure phone number starts with country code
        if (!str_starts_with($phoneNumber, '+260') && !str_starts_with($phoneNumber, '260')) {
            if (str_starts_with($phoneNumber, '0')) {
                $phoneNumber = '260' . substr($phoneNumber, 1);
            } else {
                $phoneNumber = '260' . $phoneNumber;
            }
        }
        
        $curl = curl_init();
        
        // Fixed URL construction - removed duplicate path
        $url = sprintf(
            "%s/api/v2.1/action/send/api_key/%s/contacts/%s/senderId/%s/message/%s",
            $this->baseUrl,
            $this->apiKey,
            $phoneNumber,
            $this->senderId,
            urlencode($message)
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30, // Increased timeout
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json'
            ],
        ));

        try {
            Log::info('Attempting to send SMS', [
                'phone' => $phoneNumber,
                'message_length' => strlen($message),
                'url' => $url
            ]);

            $response = curl_exec($curl);
            
            if (curl_errno($curl)) {
                throw new \Exception('CURL Error: ' . curl_error($curl));
            }

            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            
            Log::info('SMS API Response', [
                'status_code' => $statusCode,
                'response' => $response,
                'phone' => $phoneNumber
            ]);

            $success = $statusCode >= 200 && $statusCode < 300;
            
            if (!$success) {
                Log::error('SMS sending failed', [
                    'status_code' => $statusCode,
                    'response' => $response,
                    'phone' => $phoneNumber
                ]);
            }

            return [
                'success' => $success,
                'data' => $response,
                'status' => $statusCode,
                'phone' => $phoneNumber
            ];
        } catch (\Exception $e) {
            Log::error('SMS sending failed with exception', [
                'error' => $e->getMessage(),
                'phone' => $phoneNumber,
                'message' => $message
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'phone' => $phoneNumber
            ];
        } finally {
            curl_close($curl);
        }
    }
}