<?php

class GoogleAuth {
    private $client;

    public function __construct(Google_Client $client) {
        $this->client = $client;
    }

    // Method to redirect to Google's OAuth page
    public function redirectToGoogle() {
        $authUrl = $this->client->createAuthUrl();
        header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
        exit();
    }

    // Method to handle the Google callback and fetch user data
    public function handleGoogleCallback($pdo) {
        if (isset($_GET['code'])) {
            $this->client->authenticate($_GET['code']);
            $token = $this->client->getAccessToken();
            $this->client->setAccessToken($token);

            // Fetch user profile from Google
            $google_oauth = new Google_Service_Oauth2($this->client);
            $google_account_info = $google_oauth->userinfo->get();
            
            $userData = [
                'username' => $google_account_info->name,
                'email' => $google_account_info->email,
                'profile_picture' => $google_account_info->picture
            ];

            return $userData;
        }
        return null;
    }
}

?>
