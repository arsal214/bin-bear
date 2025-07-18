# Jobber API Integration Documentation For BinBear

## Table of Contents
- [Authentication](#authentication)
- [Company-Level Integration (Recommended)](#company-level-integration-recommended)
- [User-Level Integration (Legacy)](#user-level-integration-legacy)
- [API Endpoints](#api-endpoints)
- [Base URL](#base-url)
- [Response Format](#response-format)
- [Quick Start Flow](#quick-start-flow)

---

## Authentication

All endpoints require authentication using a Bearer token. You must log in to obtain this token.

### Login
- **Endpoint:** `POST /api/login`
- **Purpose:** Authenticate a user and receive a Bearer token
- **Request Body:**
  ```json
  {
    "email": "user@example.com",
    "password": "your_password"
  }
  ```
- **Response:**
  ```json
  {
    "message": "Login successful",
    "user": {
      "id": 1,
      "first_name": "John",
      "last_name": "Doe",
      "email": "user@example.com"
    },
    "access_token": "<your_bearer_token>",
    "token_type": "Bearer"
  }
  ```
- **Usage:** Include this token in the `Authorization` header for all subsequent requests:
  ```
  Authorization: Bearer <your_bearer_token>
  ```

### 2. Refresh Token
- **Endpoint:** `POST /api/refresh-token`
- **Purpose:** Refresh an existing Bearer token (get a new token and invalidate the old one)
- **Headers:**
  - `Authorization: Bearer <your_current_token>`
- **Request Body:** None required
- **Response:**
  ```json
  {
    "message": "Token refreshed successfully",
    "user": {
      "id": 1,
      "first_name": "John",
      "last_name": "Doe",
      "email": "user@example.com"
    },
    "access_token": "<your_new_bearer_token>",
    "token_type": "Bearer"
  }
  ```
- **Notes:**
  - The old token will be invalidated and can no longer be used
  - Use the new token for all subsequent API calls
  - This endpoint requires a valid existing token

---

## Company-Level Integration (Recommended)

**This is the recommended approach for BinBear.** All clients are created in your main BinBear Jobber account (`contact@binbears.com`) regardless of which user makes the API call.

### Benefits:
- ✅ **Centralized client management** - All clients in one BinBear account
- ✅ **No user OAuth required** - Users don't need personal Jobber accounts
- ✅ **Simplified workflow** - One company token handles everything
- ✅ **Better data consistency** - All data in your main business account

### Setup Process:

#### 1. Company Authorization (One-time setup)
- **Endpoint:** `GET /api/company/jobber/auth`
- **Purpose:** Get authorization URL for BinBear main account
- **Headers:**
  - `Authorization: Bearer <your_api_token>`
- **Response:**
  ```json
  {
    "message": "Company authorization URL generated",
    "auth_url": "https://api.getjobber.com/api/oauth/authorize?...",
    "instructions": "Visit this URL with the BinBear main account (contact@binbears.com) to authorize company-wide access"
  }
  ```
- **Instructions:** 
  1. Visit the `auth_url` 
  2. **Log in with your main BinBear account** (`contact@binbears.com`)
  3. Complete the authorization
  4. This authorizes the entire BinBear system

#### 2. Check Company Authorization Status
- **Endpoint:** `GET /api/company/jobber/status`
- **Purpose:** Check if company Jobber account is connected
- **Headers:**
  - `Authorization: Bearer <your_api_token>`
- **Response:**
  ```json
  {
    "status": "ACTIVE",
    "company": {
      "name": "BinBear Junk Removal",
      "email": "contact@binbears.com"
    },
    "expires_at": "2025-08-18T15:30:00Z",
    "next_step": "Ready for company-wide Jobber operations"
  }
  ```

### Company API Endpoints:

#### Create Client (Company Account)
- **Endpoint:** `POST /api/company/create-client`
- **Purpose:** Create a new client in BinBear's main Jobber account
- **Headers:**
  - `Authorization: Bearer <your_api_token>`
- **Request Body:**
  ```json
  {
    "first_name": "John",
    "last_name": "Smith",
    "email": "john@example.com",
    "phone": "555-1234"
  }
  ```
- **Response:**
  ```json
  {
    "message": "Client created successfully in BinBear main Jobber account",
    "company_account": "contact@binbears.com",
    "jobber_client_id": "Z2lkOi8vSm9iYmVyL0NsaWVudC8xMDk3NDAwNjg=",
    "client_data": { ... }
  }
  ```
- **Notes:**
  - ✅ Client is created in **your main BinBear account**
  - ✅ Works for any authenticated user
  - ✅ No individual Jobber account needed

---

## User-Level Integration (Legacy)

**This is the original approach** where each user connects their own personal Jobber account. **Not recommended for BinBear** as it creates clients in individual user accounts rather than your main business account.

### User OAuth Flow

To use Jobber features, you must connect your Jobber account via OAuth. This is a one-time process per user.

### 3. Start Jobber OAuth
- **Endpoint:** `GET /api/jobber/auth`
- **Purpose:** Redirects you to Jobber to authorize the app
- **Headers:**
  - `Authorization: Bearer <your_bearer_token>`
- **Response:**
  ```json
  {
    "auth_url": "https://api.getjobber.com/api/oauth/authorize?...",
    "message": "Visit this URL to authorize the app"
  }
  ```
- **Instructions:** Open the `auth_url` in your browser and complete the authorization.

### 4. Jobber OAuth Callback
- **Endpoint:** `GET /api/jobber/callback`
- **Purpose:** Handles the redirect from Jobber after authorization
- **Headers:**
  - `Authorization: Bearer <your_bearer_token>`
- **What happens:**
  - The system exchanges the code for an access token and stores it for your user.
  - You are now ready to use Jobber-powered endpoints!

---

## API Endpoints

### Create Client
- **Endpoint:** `POST /api/create-client`
- **Purpose:** Create a new client in Jobber
- **Headers:**
  - `Authorization: Bearer <your_bearer_token>`
- **Request Body:**
  ```json
  {
    "first_name": "Test",
    "last_name": "Client",
    "email": "test.client@example.com",
    "phone": "123-456-7890"
  }
  ```
- **Response (Success):**
  ```json
  {
    "message": "Client created successfully in Jobber and saved to DB",
    "jobber_client_id": "...",
    "client_data": { ... }
  }
  ```
- **Response (Error):**
  ```json
  {
    "message": "Jobber not authorized for this user."
  }
  ```
- **Notes:**
  - You must have completed the Jobber OAuth flow.
  - The Bearer token must belong to a user who has authorized Jobber.

### Get Client
- **Endpoint:** `GET /api/clients/{jobberClientId}`
- **Purpose:** Retrieve client details from Jobber
- **Headers:**
  - `Authorization: Bearer <your_bearer_token>`
- **Response (Success):**
  ```json
  {
    "message": "Client fetched successfully",
    "client": { ... }
  }
  ```
- **Response (Error):**
  ```json
  {
    "message": "Jobber not authorized for this user."
  }
  ```
- **Notes:**
  - The client ID must be a valid Jobber client ID.
  - The Bearer token must belong to a user who has authorized Jobber.

### Get Available Times
- **Endpoint:** `GET /api/get-available-times`
- **Purpose:** Get available booking times from Jobber
- **Headers:**
  - `Authorization: Bearer <your_bearer_token>`
- **Response (Success):**
  ```json
  {
    "message": "Scheduled jobs fetched successfully",
    "scheduled_jobs": [ ... ],
    "available_slots": [ ... ]
  }
  ```
- **Notes:**
  - The Bearer token must belong to a user who has authorized Jobber.
  - The available slots are calculated based on scheduled jobs in Jobber.

### Create Job Draft
- **Endpoint:** `POST /api/jobber/create-job-draft`
- **Purpose:** Create a draft job in Jobber
- **Headers:**
  - `Authorization: Bearer <your_bearer_token>`
- **Request Body:**
  ```json
  {
    "jobber_client_id": "Z2lkOi8vSm9iYmVyL0NsaWVudC8xMDk3NDAwNjg=",
    "jobber_property_id": "Z2lkOi8vSm9iYmVyL1Byb3BlcnR5LzExNzcxNTQ3NQ==",
    "title": "Test Time Slot Draft Job",
    "description": "This is a test job",
    "start_at": "2025-08-20T09:00:00Z",
    "end_at": "2025-08-20T10:00:00Z",
    "price": 100.00
  }
  ```
- **Response (Success):**
  ```json
  {
    "message": "Job draft created successfully",
    "job": {
      "id": "Z2lkOi8vSm9iYmVyL0pvYi8xMTY4MjQzNzM=",
      "title": "Test Time Slot Draft Job",
      "jobNumber": 4,
      "instructions": "This is a test job",
      "startAt": "2025-08-20T09:00:00Z",
      "endAt": "2025-08-20T10:00:00Z",
      "jobStatus": "unscheduled"
    }
  }
  ```
- **Response (Error):**
  ```json
  {
    "message": "Jobber not authorized for this user."
  }
  ```
- **Notes:**
  - All fields except `description` and `price` are required
  - `start_at` and `end_at` must be valid ISO 8601 datetime strings
  - `end_at` must be after `start_at`
  - `price` must be a non-negative number if provided

### Get Client Properties
- **Endpoint:** `GET /api/jobber/client-properties/{jobberClientId}`
- **Purpose:** Get all properties associated with a client in Jobber
- **Headers:**
  - `Authorization: Bearer <your_bearer_token>`
- **Response (Success):**
  ```json
  {
    "message": "Client properties fetched successfully",
    "properties": [
      {
        "id": "Z2lkOi8vSm9iYmVyL1Byb3BlcnR5LzExNzcxNTQ3NQ==",
        "name": "Main Property",
        "address": {
          "street": "123 Main St",
          "city": "Toronto",
          "province": "ON",
          "postalCode": "M5V 2H1",
          "country": "CA"
        }
      }
    ]
  }
  ```
- **Response (Error):**
  ```json
  {
    "message": "Jobber not authorized for this user."
  }
  ```
- **Notes:**
  - The client ID must be a valid Jobber client ID
  - Returns an array of properties with their addresses

### Create Property
- **Endpoint:** `POST /api/jobber/create-property`
- **Purpose:** Create a new property for a client in Jobber
- **Headers:**
  - `Authorization: Bearer <your_bearer_token>`
- **Request Body:**
  ```json
  {
    "jobber_client_id": "Z2lkOi8vSm9iYmVyL0NsaWVudC8xMDk3NDAwNjg=",
    "name": "Main Property",
    "street": "123 Main St",
    "city": "Toronto",
    "province": "ON",
    "postal_code": "M5V 2H1",
    "country": "CA"
  }
  ```
- **Response (Success):**
  ```json
  {
    "message": "Property created successfully",
    "property": {
      "id": "Z2lkOi8vSm9iYmVyL1Byb3BlcnR5LzExNzcxNTQ3NQ==",
      "address": {
        "street": "123 Main St",
        "city": "Toronto",
        "province": "ON",
        "postalCode": "M5V 2H1",
        "country": "CA"
      }
    }
  }
  ```
- **Response (Error):**
  ```json
  {
    "message": "Jobber not authorized for this user."
  }
  ```
- **Notes:**
  - All fields except `name` are required
  - The client ID must be a valid Jobber client ID
  - Address fields must be valid according to Jobber's requirements

---

## Base URL
All endpoints are prefixed with `/api/`.

## Response Format
All responses are JSON. Always include your Bearer token in the `Authorization` header:
```
Authorization: Bearer <your_bearer_token>
```

---

## Quick Start Flow

### For BinBear (Recommended Company Integration):

1. **One-time Company Setup:**
   - Admin calls `GET /api/company/jobber/auth`
   - Admin visits authorization URL **with main BinBear account** (`contact@binbears.com`)
   - Completes OAuth authorization
   - Now the entire system is authorized!

2. **Daily Operations:**
   - Any user can call `POST /api/company/create-client` 
   - All clients are created in **BinBear main account**
   - No individual user authorization needed
   - Centralized client management

3. **Check Status Anytime:**
   - `GET /api/company/jobber/status` - Check if company account is connected
   - `POST /api/company/jobber/refresh` - Manually refresh company token if needed

### Legacy User-Level Flow:
1. **Login** to get your Bearer token.
2. **Authorize Jobber** via `/api/jobber/auth` and complete the OAuth flow.
3. Use your Bearer token to access Jobber-powered endpoints like creating clients, fetching clients, and getting available times.

### Migration from User-Level to Company-Level:

If you're currently using user-level integration:
1. Complete company authorization (steps above)
2. Switch to using `/api/company/create-client` instead of `/api/create-client`
3. All new clients will go to your main account
4. Existing clients remain in individual user accounts (can be migrated manually if needed) 