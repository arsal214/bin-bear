# Jobber API Integration Documentation For BinBear

## Table of Contents
- [Login & Authentication](#login--authentication)
- [Jobber OAuth Flow](#jobber-oauth-flow)
- [API Endpoints](#api-endpoints)
  - [Create Client](#create-client)
  - [Get Client](#get-client)
  - [Get Available Times](#get-available-times)
  - [Create Job Draft](#create-job-draft)
  - [Get Client Properties](#get-client-properties)
  - [Create Property](#create-property)
- [Base URL](#base-url)
- [Response Format](#response-format)

---

## Login & Authentication

All endpoints require authentication using a Bearer token. You must log in to obtain this token.

### 1. Login
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
    "token": "<your_bearer_token>"
  }
  ```
- **Usage:** Include this token in the `Authorization` header for all subsequent requests:
  ```
  Authorization: Bearer <your_bearer_token>
  ```

---

## Jobber OAuth Flow

To use Jobber features, you must connect your Jobber account via OAuth. This is a one-time process per user.

### 2. Start Jobber OAuth
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

### 3. Jobber OAuth Callback
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
1. **Login** to get your Bearer token.
2. **Authorize Jobber** via `/api/jobber/auth` and complete the OAuth flow.
3. Use your Bearer token to access Jobber-powered endpoints like creating clients, fetching clients, and getting available times. 