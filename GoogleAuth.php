<?php
class GoogleAuth {
    private $client;

    public function __construct($client) {
        $this->client = $client;
    }

    // Redirect to Google authentication
    public function redirectToGoogle() {
        $auth_url = $this->client->createAuthUrl();
        header("Location: " . filter_var($auth_url, FILTER_SANITIZE_URL));
        exit();
    }

    // Handle Google authentication
    public function handleGoogleCallback($pdo) {
        error_log("Handling Google callback...");

        if (isset($_GET['code'])) {
            error_log("Authorization code received: " . $_GET['code']);

            // Fetch the access token
            $token = $this->client->fetchAccessTokenWithAuthCode($_GET['code']);

            if (isset($token['error'])) {
                error_log("Error fetching access token: " . $token['error_description']);
                return null;
            }

            // Set the access token
            $this->client->setAccessToken($token['access_token']);
            error_log("Access token set.");

            // Get user profile information
            try {
                $google_oauth = new Google_Service_Oauth2($this->client);
                $user_info = $google_oauth->userinfo->get();
                error_log("User info retrieved: " . json_encode($user_info));

                return [
                    'username' => $user_info->name,
                    'email' => $user_info->email
                ];
            } catch (Exception $e) {
                error_log("Error fetching user info: " . $e->getMessage());
                return null;
            }
        }
        error_log("No authorization code provided.");
        return null;
    }
}
?>
